<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transport extends CI_Controller
{
    public $layout = 'layout';

    protected $Date;
    protected $DateTime;
    protected $tmst_transport_with = 'tmst_transport_with';

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
        $this->data['page_title'] = "Master Data Transport";
        $this->data['page_content'] = "Master/Transport/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Transport/index.js?v=' . time() . '"></script>';
        $this->load->view($this->layout, $this->data);
    }

    public function add()
    {
        $this->data['page_title'] = "Form Penambahan Data Transportasi";
        $this->data['page_content'] = "Master/Transport/add";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Transport/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function post()
    {
        $ValidateData = $this->db->get_where($this->tmst_transport_with, ['Transport_Name' => $this->input->post('transport_name')]);
        if ($ValidateData->num_rows() > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => "Nama Transportasi Sudah Terdaftar, Harap Masukkan Nama Transportasi Yang Lain !"
            ]);
        }

        $status = (int)$this->input->post('status');
        
        $this->db->trans_start();
        $this->db->insert($this->tmst_transport_with, [
            'Transport_Name' => $this->input->post('transport_name'),
            'Is_Active' => $status,
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
        $SysId = $this->input->post('SysId');
        $row = $this->db->get_where($this->tmst_transport_with, ['SysId' => $SysId])->row();

        if ($row->Is_Active == 1) {
            $this->db->where('SysId', $SysId);
            $this->db->update($this->tmst_transport_with, [
                'Is_Active' => 0
            ]);

            $response = [
                "code" => 200,
                "msg" => "Data telah di non-aktifkan !"
            ];
        } else {
            $this->db->where('SysId', $SysId);
            $this->db->update($this->tmst_transport_with, [
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

    public function DT_transport()
    {
        $tables = $this->tmst_transport_with;
        $search = array('Transport_Name');
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables($tables, $search, $isWhere);
    }
}
