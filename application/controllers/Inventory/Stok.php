<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok extends CI_Controller
{
    public $layout = 'layout';
    protected $qmst_item            = 'qmst_item';
    protected $tmst_warehouse       = 'tmst_warehouse';
    protected $qview_tmst_warehouse_active = 'qview_tmst_warehouse_active';
    protected $qstok_warehouse_item = 'qstok_warehouse_item';
    protected $Date;
    protected $DateTime;

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');
        $this->load->model('m_helper', 'help');
        $this->load->model('m_Warehouse', 'warehouse');
        $this->load->model('m_DataTable', 'M_Datatables');
    }

    public function index()
    {
        $this->data['page_title'] = "Stok Item " . $this->config->item('company_name');
        $this->data['page_content'] = "Inventory/Stok/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/inventory-assets/stok/index.js?v=' . time() . '"></script>';

        $this->data['warehouses'] = $this->db->get_where($this->qview_tmst_warehouse_active, [
            'Is_Kiln' => 0
        ])->result();

        $this->load->view($this->layout, $this->data);
    }

    // ================================== DataTable Section 

    public function DT_Stok()
    {
        $Warehouse = floatval($this->input->post('Warehouse'));

        $query = "SELECT Warehouse_ID, Warehouse_Name, Warehouse_Code, Item_Code, Item_Name, Item_Qty, Uom, Uom_Id
                  FROM $this->qstok_warehouse_item";

        $search = array(
            'Warehouse_Name',
            'Warehouse_Code',
            'Item_Code',
            'Item_Name',
            'Uom'
        );

        // Prepare the where clause
        $where = [];
        if (!empty($Warehouse)) {
            $where['Warehouse_ID '] = $Warehouse;
        }

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_Hst_Trx()
    {
        $Warehouse_ID = floatval($this->input->post('Warehouse_ID'));
        $Item_Code = $this->input->post('Item_Code');
        $startDate = $this->input->post('from');
        $endDate = $this->input->post('to');

        $query = "SELECT ID, DocNo, DocDate, Item_Code, Warehouse_ID, Begin_Balance, Qty_Adjust_Plus, Qty_Adjust_Min, End_Balance, Trans_Type, Created_Time
                  FROM thst_trx_stok_item ";

        $search = array(
            'DocNo',
            'DocDate'
        );

        // Prepare the where clause
        $where = [];
        $where['Warehouse_ID'] = $Warehouse_ID;
        $where['Item_Code'] = $Item_Code;
        $where["DATE_FORMAT(DocDate, '%Y-%m-%d') >"] = $startDate;
        $where["DATE_FORMAT(DocDate, '%Y-%m-%d') <"] = $endDate;

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }
}
