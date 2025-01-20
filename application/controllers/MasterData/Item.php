<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Item extends CI_Controller
{
    public $layout = 'layout';
    protected $qmst_item = 'qmst_item';
    protected $tmst_item_aliases = 'tmst_item_aliases';
    protected $Tmst_CostCenter = 'tmst_cost_center';
    protected $Tmst_currency = 'tmst_currency';
    protected $tmst_item_category = 'tmst_item_category';
    protected $qview_item_category = 'qview_item_category_active';
    protected $tmst_warehouse_active = 'qview_tmst_warehouse_active';
    protected $tmst_pohon_kayu_industri = 'tmst_pohon_kayu_industri';
    protected $tmst_item = 'tmst_item';
    protected $tmst_uom = 'tmst_unit_type';
    protected $tmst_account = 'tmst_account';
    protected $tmst_barcode_pattern = 'tmst_barcode_pattern_item';
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
        $this->data['page_title'] = "List of Item";
        $this->data['page_content'] = "Master/Item/item";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Item/item.js?v=' . time() . '"></script>';

        $this->data['List_Cust'] = $this->db->select('t1.SysId, t1.Account_Code, t1.Account_Name, t1.AccountTitle_Code')
            ->from($this->tmst_account . ' t1')
            ->where('t1.Category_ID', 'CS')
            ->where('t1.Is_Active', 1)
            ->get()->result();

        $this->load->view($this->layout, $this->data);
    }

    public function add()
    {
        $this->data['page_title'] = "Form Add New Item";
        $this->data['page_content'] = "Master/Item/add";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Item/add.js?v=' . time() . '"></script>';
        $this->data['Currencys'] = $this->db->get($this->Tmst_currency)->result();

        $this->data['Categories'] = $this->db->get($this->tmst_item_category);
        $this->data['Uoms'] = $this->db->get($this->tmst_uom);
        $this->data['Warehouses'] = $this->db->get($this->tmst_warehouse_active);
        $this->data['Woods'] = $this->db->get_where($this->tmst_pohon_kayu_industri, ['Is_Active' => 1]);

        $this->load->view($this->layout, $this->data);
    }

    public function post()
    {
        $Barcode_Pattern_Item = $this->db->get_where($this->qview_item_category, ['SysId' => $this->input->post('item_category_group')])->row();
        // if ($this->input->post('Is_Grid_Item') == '1') {
        //     $panjang = $this->input->post('item_length');
        //     $lebar = $this->input->post('item_width');
        //     $tinggi = $this->input->post('item_height');
        //     $Pattern_Pohon = $this->db->get_where($this->tmst_pohon_kayu_industri, ['SysId' => $this->input->post('Id_Pki')])->row()->Grouping_Code;
        //     $Item_Code = $this->help->Gnrt_kode_item_grid($Pattern_Pohon, $Barcode_Pattern_Item->Grouping_Code, $tinggi, $lebar, $panjang);
        // } else {
        if ($this->input->post('patern_item_code') == 'otomatis_ic') {
            $Item_Code = $this->help->Gnrt_Identity_Number_Continious_Monthly($Barcode_Pattern_Item->Grouping_Code, $this->param_identity_number_db);
        } else {
            $Item_Code = strtoupper($this->input->post('item_code'));
        }
        // }

        $RowItem = $this->db->get_where($this->qmst_item, ['Item_Code' => $Item_Code]);
        if ($RowItem->num_rows() > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => "Item code $Item_Code tersebut sudah dimiliki oleh item lain !"
            ]);
        }

        $CostingMethod = 'AVG';
        $Grid_Pattern_Code =  NULL;
        if (intval($this->input->post('Is_Grid_Item')) == 1) {
            $CostingMethod = 'SPESIFIC';
            $Grid_Pattern_Code =  strtoupper($this->input->post('Grid_Pattern_Code'));
        }

        $this->db->trans_start();
        $this->db->insert($this->tmst_item, [
            'Item_Code' => $Item_Code,
            'Item_Name' => $this->input->post('item_name'),
            'Is_Expenses' => intval($this->input->post('is_expenses')),
            'Is_Grid_Item' => intval($this->input->post('Is_Grid_Item')),
            'Source' => $this->input->post('source'),
            'CostingMethod' => $CostingMethod,
            // 'Is_Active' => ,
            'Uom_Id' => $this->input->post('uom_id'),
            'Total_Stock' => 0,
            'Default_Currency_Id' => $this->input->post('Currency'),
            'Item_Category_Group' => $this->input->post('item_category_group'),
            'Default_Warehouse_Id' => $this->input->post('Default_Warehouse_Id'),
            'Selling_Currency_ID' => $this->input->post('Currency'),
            'Item_Length' => $this->input->post('item_length'),
            'Item_Width' => $this->input->post('item_width'),
            'Item_Height' => $this->input->post('item_height'),
            'LWH_Unit' => 'CM',
            'Item_Weight' => $this->input->post('item_weight'),
            'Weight_Unit' => 'KG',
            // 'Id_Pki' => $Id_Pki,
            'Grid_Pattern_Code' => $Grid_Pattern_Code,
            'Volume_M3' => $this->input->post('Volume_M3'),
            'MeterSquare_M2' => $this->input->post('MeterSquare_M2'),
            'Brand' => $this->input->post('brand'),
            'Model' => $this->input->post('model'),
            'Item_Color' => $this->input->post('item_color'),
            'Item_Description' => $this->input->post('item_description'),
            'Barcode_Pattern' => NULL,
            'PackingList_Type' => $this->input->post('PackingList_Type'),
            'Custom_Field_1' => $this->input->post('Custom_Field_1'),
            'Custom_Field_2' => $this->input->post('Custom_Field_2'),
            'Custom_Field_3' => $this->input->post('Custom_Field_3'),
            'Created_Ip' => $this->help->get_client_ip(),
            'Created_at' => $this->DateTime,
            'Created_by' => $this->session->userdata('impsys_nik'),
            'Last_Updated_at' => NULL,
            'Last_Updated_by' => NULL,
            'last_zero_stock_date' => $this->Date
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Proses penyimpanan gagal !"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Berhasil Menyimpan item code baru $Item_Code !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function edit($sysid)
    {
        $this->data['page_title'] = "View/Edit Data Item";
        $this->data['page_content'] = "Master/Item/edit";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Item/edit.js?v=' . time() . '"></script>';
        $this->data['Currencys'] = $this->db->get($this->Tmst_currency)->result();

        $this->data['item'] = $this->db->get_where($this->qmst_item, ['SysId' => $sysid])->row();


        $this->data['Woods'] = $this->db->get($this->tmst_pohon_kayu_industri);
        $this->data['Categories'] = $this->db->get($this->tmst_item_category);
        $this->data['Categorie_Groups'] = $this->db->get($this->qview_item_category);
        $this->data['Uoms'] = $this->db->get($this->tmst_uom);
        $this->data['Warehouses'] = $this->db->get($this->tmst_warehouse_active);

        $RowPattern = $this->db->get_where($this->tmst_barcode_pattern, ['Item_Code' => $this->data['item']->Item_Code]);
        $this->data['RowPattern'] = $RowPattern->row_array();
        if ($RowPattern->num_rows() == 0) {
            $this->data['RowPattern'] = [
                'Qty_Packing' => 1,
                'Company_Identity' => NULL,
                'Pattern_Char' => NULL,
                'First_Concate' => NULL,
                'Reset_Period' => NULL,
                'Second_Concate' => NULL,
                'Counter_Length' => NULL,
            ];
        }

        $this->load->view($this->layout, $this->data);
    }

    public function update()
    {
        $Item_Code = $this->input->post('item_code');
        $this->db->trans_start();
        $this->db->where('SysId', $this->input->post('sysid'))->update($this->tmst_item, [
            'Item_Name' => $this->input->post('item_name'),
            // 'Is_Grid_Item' => intval($this->input->post('Is_Grid_Item')),
            'Source' => $this->input->post('source'),
            'Default_Warehouse_Id' => $this->input->post('Default_Warehouse_Id'),
            'Item_Length' => $this->input->post('item_length'),
            'Item_Width' => $this->input->post('item_width'),
            'Item_Height' => $this->input->post('item_height'),
            'Item_Weight' => $this->input->post('item_weight'),
            'Default_Currency_Id' => $this->input->post('Currency'),
            'Selling_Currency_ID' => $this->input->post('Currency'),
            'Brand' => $this->input->post('brand'),
            'Volume_M3' => $this->input->post('Volume_M3'),
            'MeterSquare_M2' => $this->input->post('MeterSquare_M2'),
            'Model' => $this->input->post('model'),
            'Item_Color' => $this->input->post('item_color'),
            'Item_Description' => $this->input->post('item_description'),
            'PackingList_Type' => $this->input->post('PackingList_Type'),
            'Custom_Field_1' => $this->input->post('Custom_Field_1'),
            'Custom_Field_2' => $this->input->post('Custom_Field_2'),
            'Custom_Field_3' => $this->input->post('Custom_Field_3'),
            'Last_Updated_at' => $this->DateTime,
            'Last_Updated_by' => $this->session->userdata('impsys_nik'),
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Proses update gagal !"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Data $Item_Code berhasil terupdate!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function post_barcode_setting()
    {
        $Item_Code = $this->input->post('item_code_param');
        $RowPattern = $this->db->get_where($this->tmst_barcode_pattern, ['Item_Code' => $Item_Code]);
        $this->db->trans_start();
        if ($RowPattern->num_rows() == 0) {
            $this->db->insert($this->tmst_barcode_pattern, [
                'Item_Code' => $Item_Code,
                'Qty_Packing' => $this->input->post('qty_item_perpack'),
                'Company_Identity' => $this->input->post('CompanyIdentity'),
                'Pattern_Char' => $this->input->post('header'),
                'First_Concate' => $this->input->post('FirstConcate'),
                'Reset_Period' => $this->input->post('PeriodeReset'),
                'Second_Concate' => $this->input->post('SecondConcate'),
                'Counter_Length' => $this->input->post('LengthCounter'),
                'Created_At' => $this->DateTime,
                'Created_By' => $this->session->userdata('impsys_nik'),
                // 'Last_Updated_At' => $this->DateTime,
                // 'Last_Updated_by' => $this->session->userdata('impsys_nik'),
            ]);
        } else {
            $this->db->where('Item_Code', $Item_Code)->update($this->tmst_barcode_pattern, [
                // 'Item_Code' => $Item_Code,
                'Qty_Packing' => $this->input->post('qty_item_perpack'),
                'Company_Identity' => $this->input->post('CompanyIdentity'),
                'Pattern_Char' => $this->input->post('header'),
                'First_Concate' => $this->input->post('FirstConcate'),
                'Reset_Period' => $this->input->post('PeriodeReset'),
                'Second_Concate' => $this->input->post('SecondConcate'),
                'Counter_Length' => $this->input->post('LengthCounter'),
                // 'Created_At' => $this->DateTime,
                // 'Created_By' => $this->session->userdata('impsys_nik'),
                'Last_Updated_At' => $this->DateTime,
                'Last_Updated_by' => $this->session->userdata('impsys_nik'),
            ]);
        }


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
                "msg" => "Barcode Setting $Item_Code berhasil tersimpan!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function select_item_group()
    {
        $search = $this->input->get('search');
        $id_category = $this->input->get('category');
        $query = $this->db->query(
            "SELECT * from qview_item_category_active where `Group_Name` like '%$search%' and Category_Parent = $id_category"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['SysId'];
                $list[$key]['text'] = $row['Group_Name'] . '(' . $row['Grouping_Code'] . ')';
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function select_item_group_report()
    {
        $search = $this->input->get('search');
        $id_category = $this->input->get('category');
        $query = $this->db->query(
            "SELECT * from qview_item_category_active where `Group_Name` like '%$search%' and Category_Parent = $id_category"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $list[1]['id'] = 'ALL';
            $list[1]['text'] = 'ALL';
            $key = 2;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['SysId'];
                $list[$key]['text'] = $row['Group_Name'] . '(' . $row['Grouping_Code'] . ')';
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function select_warehouse_report()
    {
        $search = $this->input->get('search');
        $id_category = $this->input->get('category');
        $query = $this->db->query(
            "SELECT * from qview_tmst_warehouse_all where `Warehouse_Name` like '%$search%' and Item_Category_ID = $id_category"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $list[1]['id'] = 'ALL';
            $list[1]['text'] = 'ALL';
            $key = 2;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['Warehouse_ID'];
                $list[$key]['text'] = $row['Warehouse_Name'] . '(' . $row['Warehouse_Code'] . ')';
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function store_item_alias()
    {
        $state = $this->input->post('state');
        $Item_Code_Internal = $this->input->post('Item_Code_Internal');
        $SysId = $this->input->post('SysId');
        $Account_ID = $this->input->post('Account_ID');
        $Item_CodeAlias = strtoupper($this->input->post('Item_CodeAlias'));
        $Item_NameAlias = strtoupper($this->input->post('Item_NameAlias'));

        $this->db->trans_start();

        if ($state == 'ADD') {
            $this->db->insert($this->tmst_item_aliases, [
                'Item_Code' => $Item_Code_Internal,
                'Account_ID' => $Account_ID,
                'Item_CodeAlias' => $Item_CodeAlias,
                'Item_NameAlias' => $Item_NameAlias,
                'Last_Updated_at' => $this->DateTime,
                'Last_Updated_by' => $this->session->userdata('impsys_nik'),
            ]);
        } else {
            $this->db->where('ID', $SysId)->update($this->tmst_item_aliases, [
                'Item_Code' => $Item_Code_Internal,
                'Account_ID' => $Account_ID,
                'Item_CodeAlias' => $Item_CodeAlias,
                'Item_NameAlias' => $Item_NameAlias,
                'Last_Updated_at' => $this->DateTime,
                'Last_Updated_by' => $this->session->userdata('impsys_nik'),
            ]);
        }

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
                "msg" => "Berhasil Menyimpan Identitas Item Customer : $Item_CodeAlias !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }


    // ------------------------ Data Table Section

    public function DT_listofitem()
    {
        $query  = "select * from qmst_item_all";

        $search = array('Item_Code', 'Item_Name', 'Group_Name', 'Item_Category');
        // $where  = array('nama_kategori' => 'Tutorial');
        $where  = null;

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_listofitem_fg()
    {
        $query  = "select * from qmst_item_finish_good_trading";

        $search = array('Item_Code', 'Item_Name', 'Group_Name', 'Item_Category');
        // $where  = array('nama_kategori' => 'Tutorial');
        $where  = null;

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_list_item_alias()
    {
        $query  = "SELECT ti.*, ta.Account_Name from tmst_item_aliases ti
        join tmst_account ta on ti.Account_ID = ta.SysId";

        $search = array('ti.Item_CodeAlias', 'ti.Item_NameAlias', 'ta.Account_Name');
        // $where  = array('nama_kategori' => 'Tutorial');
        $where  = ['Item_Code' => $this->input->post('Item_Code')];

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }
}
