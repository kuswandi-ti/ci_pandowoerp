<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SizeGrid extends CI_Controller
{
    public $layout = 'layout';
    protected $qmst_item = 'qmst_item';
    protected $qmst_item_grid = 'qmst_item_grid';
    protected $tmst_item = 'tmst_item';
    protected $tmst_uom = 'tmst_unit_type';
    protected $tmst_size_item_grid = 'tmst_size_item_grid';
    protected $Date;
    protected $DateTime;

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');
        $this->load->model('m_helper', 'help');
        $this->load->model('m_Warehouse', 'm_wh');
        $this->load->model('m_DataTable', 'M_Datatables');
    }

    public function index()
    {
        $this->data['page_title'] = "List Master Data Ukuran Item Grid";
        $this->data['page_content'] = "Master/SizeGrid/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/SizeGrid/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function add()
    {
        $this->data['page_title'] = "List Master Data Ukuran Item Grid";
        $this->data['page_content'] = "Master/SizeGrid/add";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/SizeGrid/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function post()
    {
        $tebal = floatval($this->input->post('Item_Height'));
        $lebar = floatval($this->input->post('Item_Width'));
        $panjang = floatval($this->input->post('Item_Length'));
        $Cubication = ($tebal * $lebar * $panjang) / 1000000;

        // $KodeGeometri = $this->m_wh->get_code_geometri($this->input->post('Size_Category'));
        // $Grid_Pattern_Code = $this->db->get_where($this->qmst_item_grid, ['SysId' => $this->input->post('Item_ID')])->row()->Grid_Pattern_Code;

        $Size_Code = 'T' . $tebal . '-L' . $lebar . '-P' . $panjang;
        $Initial_Size = $tebal . '-' . $lebar . '-' . $panjang;

        $this->db->trans_start();

        $this->db->insert($this->tmst_size_item_grid, [
            // 'Item_ID' => $this->input->post('Item_ID'),
            'Size_Code' => $Size_Code,
            // 'Size_Category' => $this->input->post('Size_Category'),
            'Initial_Size' => $Initial_Size,
            'Item_Height' => $tebal,
            'Item_Width' => $lebar,
            'Item_Length' => $panjang,
            'Cubication' => $Cubication,
            'Created_at' => $this->DateTime,
            'Created_by' => $this->session->userdata('impsys_nik'),
            'Created_IP' => $this->help->get_client_ip()
        ]);

        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => $error_msg
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Ukuran Berhasil disimpan & dapat digunakan di proses grade !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }


    // ======================= Datatable Section 

    public function DT_size_grid()
    {
        $query  = "SELECT *, 'CM' as UOM from $this->tmst_size_item_grid";

        $search = array(
            'Size_Code',
            'Item_Height',
            'Item_Width',
            'Item_Length',
            'Cubication'
        );
        // $where  = array('nama_kategori' => 'Tutorial');
        $where  = ['SysId !' => NULL];

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }
}
