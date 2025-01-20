<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PohonIndustri extends CI_Controller
{
    public $layout = 'layout';
    public $tmst_pohon_kayu_industri = 'tmst_pohon_kayu_industri';

    protected $Date;
    protected $DateTime;
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');
        $this->load->model('m_helper', 'help');
        $this->load->model('m_DataTable', 'M_Datatables');
    }

    // SELECT SysId, Nama_Pohon_Kayu, Grouping_Code, Is_Active, Created_at, Created_by
    // FROM tmst_pohon_kayu_industri;
    public function index()
    {
        $this->data['page_title'] = "List Jenis Pohon Kayu Industri";
        $this->data['page_content'] = "Master/PohonIndustri/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/PohonIndustri/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function add()
    {
        $this->data['page_title'] = "Add New Cost Center";
        $this->data['page_content'] = "Master/PohonIndustri/add";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/PohonIndustri/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function post()
    {
        $this->db->trans_start();
        $this->db->insert($this->tmst_pohon_kayu_industri, [
            'Nama_Pohon_Kayu' => $this->help->toTitleCase($this->input->post('Nama_Pohon_Kayu')),
            'Grouping_Code' => $this->help->toTitleCase($this->input->post('Grouping_Code')),
            'Created_by' => $this->session->userdata('impsys_nik')
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Gagal Menyimpan data !"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Data berhasil tersimpan!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }


    public function DT_list_pohon_industri()
    {
        $query = "SELECT * FROM $this->tmst_pohon_kayu_industri ";
        $where  = array('SysId !' => NULL);
        $search = array('Nama_Pohon_Kayu', 'Grouping_Code');

        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }
}
