<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{
    public $layout = 'layout';
    protected $qmst_item            = 'qmst_item';
    protected $tmst_warehouse       = 'tmst_warehouse';
    protected $qview_tmst_warehouse_active = 'qview_tmst_warehouse_active';
    protected $qview_item_category_active = 'qview_item_category_active';
    protected $qstok_warehouse_item = 'qstok_warehouse_item';
    protected $tmst_item_category = 'tmst_item_category';
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

    public function index_list_of_items_per_warehouse()
    {
        $this->data['page_title'] = "List Of Items Per Warehouse";
        $this->data['page_content'] = "Inventory/Report/index_list_of_items_per_wh";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/inventory-assets/report/index_list_of_items_per_wh.js?v=' . time() . '"></script>';

        $this->data['Warehouses'] = $this->db->get_where($this->qview_tmst_warehouse_active, [
            'Is_Kiln' => 0
        ])->result();
        $this->data['Categories'] = $this->db->get($this->tmst_item_category);
        $this->data['Groups'] = $this->db->get($this->qview_item_category_active);

        $this->load->view($this->layout, $this->data);
    }

    public function print_loipw()
    {
        $StartDate = $this->input->get('StartDate');
        $EndDate = $this->input->get('EndDate');
        $item_category = $this->input->get('item_category');
        $item_category_group = $this->input->get('item_category_group');
        $Warehouse = $this->input->get('Warehouse');
        $source_value = $this->input->get('source_value');
        $sql_group = '';
        if (!empty($item_category_group)) {
            if ($item_category_group != 'ALL') {
                $sql_group = " AND ti.Item_Category_Group = '$item_category_group' ";
            }
        }
        $sql_wh = '';
        if (!empty($Warehouse)) {
            if ($Warehouse != 'ALL') {
                $sql_wh = " AND ihst.Warehouse_ID = '$Warehouse' ";
            }
        }

        $Sql = "SELECT DISTINCT 
                ihst.Item_Code, ti.Item_Name , ti.Item_Category, ti.Group_Name, ti.Uom, 
                ti.Item_Length,ti.Item_Width,ti.Item_Height,ti.LWH_Unit,ti.Item_Dimensions,
                ti.Item_Weight,ti.Weight_Unit,ti.Volume_M3,ti.MeterSquare_M2,
                ti.Brand,ti.Model,ti.Item_Color,ti.Item_Description,
                ihst.Warehouse_ID, tw.Warehouse_Name, tw.Warehouse_Code 
                FROM thst_trx_stok_item ihst
                join qmst_item_all ti on ihst.Item_Code = ti.Item_Code 
                join tmst_warehouse tw on ihst.Warehouse_ID = tw.Warehouse_ID 
                WHERE ihst.DocDate <= '$EndDate'
                AND ihst.DocDate >= '$StartDate'
                AND ti.Id_item_category = $item_category 
                $sql_group 
                $sql_wh
                order by ti.Item_Name, tw.Warehouse_Name
                ";

        $this->data['SDatas'] = $this->db->query($Sql)->result();
        $this->data['source_value'] = $source_value;
        $this->data['StartDate'] = $StartDate;
        $this->data['EndDate'] = $EndDate;

        $this->load->view('Inventory/Report/rpt_loipw', $this->data);
    }
}
