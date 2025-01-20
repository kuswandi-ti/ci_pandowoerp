<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse extends CI_Controller
{
    public $layout = 'layout';
    protected $Tmst_warehouse = 'tmst_warehouse';
    protected $Tmst_item_category = 'tmst_item_category';
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

    public function index()
    {
        $this->data['page_title']   = "Master Data Warehouse";
        $this->data['page_content'] = "Master/Warehouse/index";
        $this->data['script_page']  = '<script src="' . base_url() . 'assets/master-script/Warehouse/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function add()
    {
        $this->data['page_title']   = "Add Warehouse";
        $this->data['page_content'] = "Master/Warehouse/add";
        $this->data['script_page']  = '<script src="' . base_url() . 'assets/master-script/Warehouse/add.js?v=' . time() . '"></script>';
        $this->data['Categories'] = $this->db->get($this->Tmst_item_category)->result();

        $this->load->view($this->layout, $this->data);
    }

    public function post()
    {
        $RowWh = $this->db->get_where($this->Tmst_warehouse, ['Warehouse_Code' => $this->input->post('Warehouse_Code')]);
        if ($RowWh->num_rows() > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Kode Gudang/Warehouse telah di gunakan oleh gudang lain !'
            ]);
        }

        $this->db->trans_start();
        $this->db->insert($this->Tmst_warehouse, [
            'Item_Category_ID' => $this->input->post('Item_Category_ID'),
            'Warehouse_Code' => strtoupper($this->input->post('Warehouse_Code')),
            'Warehouse_Name' => $this->input->post('Warehouse_Name'),
            'Description' => $this->input->post('Description'),
            'Is_Receive_Grid' => $this->input->post('Is_Receive_Grid'),
            'Is_Kiln' => $this->input->post('Is_Kiln'),
            'Is_Wh_After_Kiln' => $this->input->post('Is_Wh_After_Kiln'),
            'Is_Entry_Wh' => $this->input->post('Is_Entry_Wh'),
            'Is_Afkir' => $this->input->post('Is_Afkir'),
            'Is_Source_Allocation' => $this->input->post('Is_Source_Allocation'),
            'Is_Source_Shp' => $this->input->post('Is_Source_Shp'),
            'Created_at' => $this->DateTime,
            'Created_by' => $this->session->userdata('impsys_nik'),
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
        $Warehouse_ID = $this->input->post('Warehouse_ID');
        $table = $this->Tmst_warehouse;

        $StokInWh = $this->db->query("Select SUM(Item_Qty) as Sum_Item_Qty from t_stok_wh_item where Warehouse_ID = $Warehouse_ID")->row();
        if (!empty($StokInWh->Sum_Item_Qty)) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Gagal Menonaktifkan, Beberapa item masih memiliki quantity !"
            ]);
        }


        $row = $this->db->get_where($table, ['Warehouse_ID' => $Warehouse_ID])->row();

        if ($row->Is_Active == 1) {
            $this->db->where('Warehouse_ID', $Warehouse_ID);
            $this->db->update($table, [
                'Is_Active' => 0
            ]);

            $response = [
                "code" => 200,
                "msg" => "Data telah di non-aktifkan !"
            ];
        } else {
            $this->db->where('Warehouse_ID', $Warehouse_ID);
            $this->db->update($table, [
                'Is_Active' => 1
            ]);

            $response = [
                "code" => 200,
                "msg" => "Data berhasil di aktifkan !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }


    // -------------------------- Datatable Section 

    public function DT_list_warehouse()
    {
        $query  = "SELECT *
                   FROM tmst_warehouse
                   JOIN tmst_item_category on Item_Category_ID = SysId";
        $search = array('Item_Category', 'Warehouse_Code', 'Warehouse_Name', 'Description');
        $where  = null;
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }
}
