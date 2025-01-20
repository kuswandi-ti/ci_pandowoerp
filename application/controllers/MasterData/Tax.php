<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tax extends CI_Controller
{
    public $layout = 'layout';

    protected $Date;
    protected $DateTime;
    protected $Tmst_tax = 'tmst_tax';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');
        $this->load->model('m_helper', 'help');
        $this->load->model('m_DataTable', 'M_Datatables');
    }

    public function index()
    {
        $this->data['page_title'] = "Master Data Type Pajak";
        $this->data['page_content'] = "Master/Tax/tax";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Tax/tax.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function add()
    {
        $this->data['page_title'] = "Form Penambahan Type Pajak";
        $this->data['page_content'] = "Master/Tax/add";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Tax/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function post()
    {

        $ValidateTax = $this->db->get_where($this->Tmst_tax, ['Tax_Code' => $this->input->post('Tax_Code')]);
        if ($ValidateTax->num_rows() > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => "Kode pajak sudah digunakan oleh type pajak lain !"
            ]);
        }

        $this->db->trans_start();
        $this->db->insert($this->Tmst_tax, [
            'Tax_Code' => $this->input->post('Tax_Code'),
            'Tax_Name' => $this->input->post('Tax_Name'),
            'Tax_Rate' => $this->input->post('Tax_Rate'),
            'ForSales' => $this->input->post('ForSales'),
            'ForPurchase' => $this->input->post('ForPurchase'),
            'isInclude' => $this->input->post('isInclude'),
            'isKreditable' => $this->input->post('isKreditable'),
            'isPPNBM' => $this->input->post('isPPNBM'),
            'Created_at' => $this->DateTime,
            'Created_by' => $this->session->userdata('impsys_nik')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Proses penyimpanan data gagal !"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Data berhasil di simpan !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function Toggle_Status()
    {
        $Tax_Id = $this->input->post('Tax_Id');
        $row = $this->db->get_where($this->Tmst_tax, ['Tax_Id' => $Tax_Id])->row();

        if ($row->Is_Active == 1) {
            $this->db->where('Tax_Id', $Tax_Id);
            $this->db->update($this->Tmst_tax, [
                'Is_Active' => 0
            ]);

            $response = [
                "code" => 200,
                "msg" => "Data telah di non-aktifkan !"
            ];
        } else {
            $this->db->where('Tax_Id', $Tax_Id);
            $this->db->update($this->Tmst_tax, [
                'Is_Active' => 1
            ]);

            $response = [
                "code" => 200,
                "msg" => "Data berhasil di aktifkan !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    // -------------------------------- Datatable section

    public function DT_tax()
    {
        $tables = $this->Tmst_tax;
        $search = array('Tax_Code', 'Tax_Name');
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables($tables, $search, $isWhere);
    }
}
