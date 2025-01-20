<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UsageNote extends CI_Controller
{
    public $layout = 'layout';
    protected $Ttrx_dtl_usage_note = 'ttrx_dtl_usage_note';
    protected $Ttrx_hdr_usage_note = 'ttrx_hdr_usage_note';
    protected $tmst_item_category = 'tmst_item_category';
    protected $tmst_cost_center = 'tmst_cost_center';
    protected $Tmst_account = 'tmst_account';
    protected $Tmst_item = 'qmst_item';
    protected $Tbl_stok = 'qstok_warehouse_item';
    protected $Qview_dtl_usage_note = 'qview_dtl_usage_note';
    protected $Date;
    protected $DateTime;
    protected $Counter_Length = 4;
    protected $Pattern_DocNo = 'SUN';
    protected $Concate_DocNo = '-';
    protected $Type_Trans = 'Used';
    protected $Type_Trans_Cancel = 'Revert Used';

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
        $this->data['page_title'] = "Alokasi Bahan Baku";
        $this->data['page_content'] = "TrxWh/UsageNote/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/UsageNote/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function approval()
    {
        $this->data['page_title'] = "Approval Alokasi Bahan Baku";
        $this->data['page_content'] = "TrxWh/UsageNote/approval";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/UsageNote/approval.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function report()
    {
        $this->data['page_title'] = "Report History Alokasi Bahan Baku";
        $this->data['page_content'] = "TrxWh/UsageNote/report";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/UsageNote/report.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function edit($SysId, $Action)
    {
        $this->data['page_title'] = 'Detail Data Nota Alokasi Bahan Baku';
        if ($Action == 'form') {
            $this->data['page_title'] = "Edit Data Alokasi Bahan Baku";
        }
        $this->data['page_content'] = "TrxWh/UsageNote/edit";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/UsageNote/edit.js?v=' . time() . '"></script>';
        $this->data['Hdr'] = $this->db->get_where($this->Ttrx_hdr_usage_note, ['SysId' => $SysId])->row();
        $this->data['dtls'] = $this->db->get_where($this->Qview_dtl_usage_note, ['UN_NUMBER' => $this->data['Hdr']->UN_NUMBER])->result();
        $this->data['Item_Categories'] = $this->db->where('Is_Allocation', 1)->get($this->tmst_item_category);
        $this->data['Cost_Centers'] = $this->db->where('Is_Active', 1)->get($this->tmst_cost_center);
        $this->data['Action'] = $Action;

        $this->load->view($this->layout, $this->data);
    }

    public function add()
    {
        $this->data['page_title'] = "Form Alokasi Bahan Baku";
        $this->data['page_content'] = "TrxWh/UsageNote/add";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/UsageNote/add.js?v=' . time() . '"></script>';
        $this->data['Item_Categories'] = $this->db->where('Is_Allocation', 1)->get($this->tmst_item_category);
        $this->data['Cost_Centers'] = $this->db->where('Is_Active', 1)->get($this->tmst_cost_center);

        $this->load->view($this->layout, $this->data);
    }

    public function verify()
    {
        $SysId = $this->input->post('SysId');
        $Param = intval($this->input->post('Param'));
        $BaseAmount = 0;

        $this->db->trans_start();

        $RowHdr = $this->db->get_where($this->Ttrx_hdr_usage_note, ['SysId' => $SysId])->row();
        $Dtls = $this->db->get_where($this->Ttrx_dtl_usage_note, ['UN_NUMBER' => $RowHdr->UN_NUMBER])->result();

        // validasi jika ada qty dtls yang melebihi qty stok maka rollback semua transaksi
        $is_valid = true;
        foreach ($Dtls as $dtl) {
            $current_stok = $this->m_wh->get_stok($dtl->Item_Code, $dtl->Warehouse_ID);
            if (floatval($dtl->Qty) > floatval($current_stok->Item_Qty)) {
                $is_valid = false;
                break;
            }
            // $this->m_wh->update_stok($dtl->Item_Code, $dtl->Warehouse_ID, $this->Type_Trans, $dtl->Qty);
            $this->m_wh->reset_avg_date($dtl->Item_Code, $this->Type_Trans);
            $AvgObject = $this->db->get_where('tmst_item', ['Item_Code' => $dtl->Item_Code])->row();

            $this->db->where('SysId', $dtl->SysId)->update($this->Ttrx_dtl_usage_note, [
                'UnitPrice' => floatval($AvgObject->Avg_Price),
                'Base_Price' => floatval($AvgObject->Avg_Price),
                'Base_TotalPrice' => floatval($dtl->Qty) * floatval($AvgObject->Avg_Price)
            ]);

            $BaseAmount += floatval($dtl->Qty) * floatval($AvgObject->Avg_Price);
        }
        if (!$is_valid) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Quantity melebihi stok yang tersedia. Transaksi tidak dapat disetujui !."
            ];
            return $this->help->Fn_resulting_response($response);
        }
        $this->db->where('SysId', $SysId)->update($this->Ttrx_hdr_usage_note, [
            'Approval_Status' => $Param,
            'Approve_Date' => $this->DateTime,
            'Base_Amount' => $BaseAmount,
            'Approve_by' => $this->session->userdata('impsys_nik')
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
                "msg" => "Status approval berhasil diubah !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function Cancel()
    {
        $SysId = $this->input->post('SysId');

        $this->db->trans_start();

        $this->db->where('SysId', $SysId)->update($this->Ttrx_hdr_usage_note, [
            'isCancel' => 1,
            'Cancel_Date' => $this->DateTime,
            'Cancel_by' => $this->session->userdata('impsys_nik')
        ]);

        $RowHdr = $this->db->get_where($this->Ttrx_hdr_usage_note, ['SysId' => $SysId])->row();
        $Dtls = $this->db->get_where($this->Ttrx_dtl_usage_note, ['UN_NUMBER' => $RowHdr->UN_NUMBER])->result();
        // $this->m_wh->record_cancel_usage_note($RowHdr->UN_NUMBER);

        if ($RowHdr->Approval_Status == 1) {
            foreach ($Dtls as $dtl) {
                $this->m_wh->RollBack_last_zero_stock_date($dtl->Item_Code, $this->Type_Trans_Cancel);
                // $this->m_wh->revert_stok($dtl->Item_Code, $dtl->Warehouse_ID, $this->Type_Trans, $dtl->Qty);
            }
        }
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
                "msg" => "Alokasi berhasil dibatalkan !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store()
    {
        $Doc_Number = $this->help->Gnrt_Identity_Monthly($this->Pattern_DocNo, $this->Counter_Length, $this->Concate_DocNo);

        $sysid_items = $this->input->post('sysid_item');
        $item_codes = $this->input->post('item_code');
        $item_names = $this->input->post('item_name');
        $currencys = $this->input->post('currency');
        $qtys = $this->input->post('qty');
        $wh_ids = $this->input->post('wh_id');
        $costing_methods = $this->input->post('costingmethod');

        $this->db->trans_start();

        $this->db->insert($this->Ttrx_hdr_usage_note, [
            'UN_NUMBER' => $Doc_Number,
            // 'Ref_Number' => $this->input->post(''),
            'UN_DATE' => date('Y-m-d', strtotime($this->input->post('UN_DATE'))),
            'UN_Notes' => $this->input->post('notes'),
            // 'UN_Status' => $this->input->post(''),
            'ReceivedDate' => date('Y-m-d', strtotime($this->input->post('ReceivedDate'))),
            // 'Approval_Status' => $this->input->post(''),
            // 'Warehouse_ID' => $this->input->post(''),
            'ItemCategoryType' => $this->input->post('ItemCategoryType'),
            'Cost_Center' => $this->input->post('cost_center'),
            // 'Approve_Date' => $this->input->post(''),
            // 'Currency' => $this->input->post(''),
            // 'Amount' => $this->input->post(''),
            // 'Rate' => $this->input->post(''),
            // 'Base_Amount' => $this->input->post(''),
            // 'isCancel' => $this->input->post(''),
            // 'Cancel_Reason' => $this->input->post(''),
            'Creation_DateTime' => $this->DateTime,
            'Created_IP' => $this->help->get_client_ip(),
            'Created_By' => $this->session->userdata('impsys_nik'),
            // 'Last_Update' => $this->input->post(''),
            // 'Update_By' => $this->input->post('')
        ]);

        for ($i = 0; $i < count($item_codes); $i++) {
            $this->db->insert($this->Ttrx_dtl_usage_note, [
                'UN_NUMBER' => $Doc_Number,
                'Item_Code' => $item_codes[$i],
                'Item_Name' => $item_names[$i],
                'Qty' => $qtys[$i],
                'Currency' => $currencys[$i],
                'Warehouse_ID' => $wh_ids[$i],
                // 'UnitPrice' => ,
                // 'TotalPrice' => ,
                // 'Base_TotalPrice' => ,
                'CostingMethod' => $costing_methods[$i]
            ]);
            // $this->m_wh->update_stok($item_codes[$i], $wh_ids[$i], $this->Type_Trans, $qtys[$i]);
        }

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
                "msg" => "Berhasil Menyimpan Pencatatan Alokasi Bahan Baku !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function update()
    {
        $SysId = $this->input->post('SysId');
        $UN_NUMBER = $this->input->post('UN_Number');

        $item_codes = $this->input->post('item_code');
        $item_names = $this->input->post('item_name');
        $currencys = $this->input->post('currency');
        $qtys = $this->input->post('qty');
        $wh_ids = $this->input->post('wh_id');
        $costing_methods = $this->input->post('costingmethod');

        $Dtls = $this->db->get_where($this->Ttrx_dtl_usage_note, ['UN_NUMBER' => $UN_NUMBER])->result();
        // foreach ($Dtls as $dtl) {
        //     $this->m_wh->revert_stok($dtl->Item_Code, $dtl->Warehouse_ID, $this->Type_Trans, $dtl->Qty);
        // }
        $this->db->delete($this->Ttrx_dtl_usage_note, ['UN_NUMBER' => $UN_NUMBER]);
        $this->db->trans_start();
        $this->db->where('SysId', $SysId)->update($this->Ttrx_hdr_usage_note, [
            'UN_NUMBER' => $UN_NUMBER,
            'UN_DATE' => $this->input->post('UN_DATE'),
            'UN_Notes' => $this->input->post('notes'),
            'ReceivedDate' => $this->input->post('ReceivedDate'),
            'ItemCategoryType' => $this->input->post('ItemCategoryType'),
            'Cost_Center' => $this->input->post('cost_center'),
            // 'Approve_Date' => $this->input->post(''),
            // 'Currency' => $this->input->post(''),
            // 'Amount' => $this->input->post(''),
            // 'Rate' => $this->input->post(''),
            // 'Base_Amount' => $this->input->post(''),
            'Last_Update' => $this->DateTime,
            'Update_By' => $this->session->userdata('impsys_nik')
        ]);

        for ($i = 0; $i < count($item_codes); $i++) {
            $this->db->insert($this->Ttrx_dtl_usage_note, [
                'UN_NUMBER' => $UN_NUMBER,
                'Item_Code' => $item_codes[$i],
                'Item_Name' => $item_names[$i],
                'Qty' => $qtys[$i],
                'Currency' => $currencys[$i],
                'Warehouse_ID' => $wh_ids[$i],
                // 'UnitPrice' => ,
                // 'TotalPrice' => ,
                // 'Base_TotalPrice' => ,
                'CostingMethod' => $costing_methods[$i]
            ]);

            // $this->m_wh->update_stok($item_codes[$i], $wh_ids[$i], $this->Type_Trans, $qtys[$i]);
        }

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
                "msg" => "Pencatatan Alokasi Bahan Baku, Berhasil di perbaharui !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    // ------------------- DATA TABLE SECTION 

    public function DT_List_Sun()
    {
        $query = "SELECT sun.*, tic.Item_Category 
        FROM ttrx_hdr_usage_note as sun
        join tmst_item_category tic on sun.ItemCategoryType = tic.SysId ";
        $search = array('UN_Number', 'UN_Notes');
        $where  = [];
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_Sun_ToApprove()
    {
        $query = "SELECT sun.*, tic.Item_Category 
        FROM ttrx_hdr_usage_note as sun
        join tmst_item_category tic on sun.ItemCategoryType = tic.SysId ";
        $search = array('UN_Number', 'UN_Notes');
        $where  = ['Approval_Status' => 0, 'isCancel' => 0];
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_list_item()
    {
        $item_category = $this->input->get('item_category');
        $sysid_items    = $this->input->get('sysid_items');

        $query = "SELECT * FROM qview_stok_item_global_all";
        $search = ['Item_Name', 'Item_Code'];
        $where  = array('SysId NOT IN ' => explode(',', $sysid_items), 'Category_Parent' => $item_category, 'Is_Grid_Item' => 0);

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_list_stok()
    {
        $Item_Code = $this->input->post('Item_Code');

        $tables = $this->Tbl_stok;
        $search = ['Warehouse_Name', 'Warehouse_Code'];
        $where  = array('Item_Code' => $Item_Code);
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables, $search, $where, $isWhere);
    }

    public function DT_report_un_detail()
    {
        $startDate = $this->input->post('from');
        $endDate = $this->input->post('to');

        $startDate = $this->db->escape_str($startDate);
        $endDate = $this->db->escape_str($endDate);

        $query = "SELECT * FROM qview_detail_usage_note";

        $search = ['UN_NUMBER', 'UN_DATE', 'nama_cost_center', 'Item_Code', 'Item_Name', 'Warehouse_Name', 'Item_Category'];

        // Prepare the where clause
        $where = [];
        $where["DATE_FORMAT(UN_DATE, '%Y-%m-%d') >"] = $startDate;
        $where["DATE_FORMAT(UN_DATE, '%Y-%m-%d') <"] = $endDate;

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_report_un_sum()
    {
        $startDate = $this->input->post('from');
        $endDate = $this->input->post('to');

        $startDate = $this->db->escape_str($startDate);
        $endDate = $this->db->escape_str($endDate);

        $query = "SELECT Cost_Center, SysId_CC, nama_cost_center, Item_Code, Item_Name, Uom, SUM(Qty) as Qty, Warehouse_ID, Warehouse_Name, Currency, UnitPrice, Base_Price, SUM(Base_TotalPrice) as Base_TotalPrice, CostingMethod, Item_Category, Group_Name
        FROM qview_detail_usage_note ";

        $search = ['nama_cost_center', 'Item_Code', 'Item_Name', 'Warehouse_Name', 'Item_Category'];

        // Prepare the where clause
        $where = [];
        $where["DATE_FORMAT(UN_DATE, '%Y-%m-%d') >"] = $startDate;
        $where["DATE_FORMAT(UN_DATE, '%Y-%m-%d') <"] = $endDate;

        $groupby = " GROUP BY Item_Code, Uom, Warehouse_ID, Group_Name, UnitPrice, SysId_CC ";

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query_group_by($query, $search, $where, $isWhere, $groupby);
    }
}
