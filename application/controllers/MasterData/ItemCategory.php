<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ItemCategory extends CI_Controller
{
    public $layout = 'layout';
    protected $qmst_item = 'qmst_item';
    protected $tmst_item = 'tmst_item';
    protected $tmst_item_category = 'tmst_item_category';
    protected $tmst_item_category_group = 'tmst_item_category_group';
    protected $param_identity_number_db = 'Item_Code';
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
        $this->data['page_title'] = "Item Category & Category Group";
        $this->data['page_content'] = "Master/ItemCategory/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/ItemCategory/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function post()
    {
        $this->db->trans_start();
        $this->db->insert($this->tmst_item_category, [
            'Item_Category_Init' => $this->input->post('Item_Category_Init'),
            'Item_Category' => $this->input->post('Item_Category'),
            'Is_Prod' => intval($this->input->post('Is_Prod')),
            'Is_Allocation' => intval($this->input->post('Is_Allocation')),
            'Is_Asset' => intval($this->input->post('Is_Asset')),
            'Is_So_Item' => intval($this->input->post('Is_So_Item')),
            'Is_Po_Item' => intval($this->input->post('Is_Po_Item')),
        ]);

        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Error : $error_msg"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Data berhasil disimpan !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function append_modal_category_group()
    {
        $SysId = $this->input->get('SysId');
        $this->data['category'] = $this->db->get_where($this->tmst_item_category, ['SysId' => $SysId])->row();
        $this->data['groups'] = $this->db->get_where($this->tmst_item_category_group, ['Category_Parent' => $SysId])->result();


        $this->load->view("Master/ItemCategory/m_list_category_group", $this->data);
    }

    public function post_category_group()
    {
        $this->db->trans_start();
        $this->db->insert($this->tmst_item_category_group, [
            'Category_Parent' => $this->input->post('Category_Parent'),
            'Group_Name' => $this->input->post('Group_Name'),
            'Grouping_Code' => $this->input->post('Grouping_Code'),
            'Category_Group_Description' => $this->input->post('Category_Group_Description'),
            'Created_Ip' => $this->help->get_client_ip(),
            'Created_by' => $this->session->userdata('impsys_nik'),
            'Created_at' => $this->DateTime
        ]);

        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Error : $error_msg"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Data berhasil disimpan !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    //  ---------------------------------------- Datatable Section

    public function DT_item_category()
    {
        $tables = $this->tmst_item_category;
        $search = array('Item_Category_Init', 'Item_Category');
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables($tables, $search, $isWhere);
    }

    public function DT_item_category_group()
    {
        $tables = $this->tmst_item_category_group;
        $search = array('Group_Name', 'Grouping_Code', 'Category_Group_Description');
        $where  = array('Category_Parent' => $this->input->post('Category_Parent'));
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables, $search, $where, $isWhere);
    }
}
