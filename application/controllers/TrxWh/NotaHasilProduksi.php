<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class NotaHasilProduksi extends CI_Controller
{
    public $layout = 'layout';
    protected $tmst_cost_center = 'tmst_cost_center';
    protected $ttrx_hdr_nota_hasil_produksi = 'ttrx_hdr_nota_hasil_produksi';
    protected $ttrx_dtl_nota_hasil_produksi = 'ttrx_dtl_nota_hasil_produksi';
    protected $qview_detail_nota_hasil_produksi = 'qview_detail_nota_hasil_produksi';
    protected $Counter_Length = 4;
    protected $Pattern_DocNo = 'PRD-';
    protected $Concate_DocNo = '-';
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
        $this->data['page_title'] = "List Nota Hasil Produksi";
        $this->data['page_content'] = "TrxWh/Nhp/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Nhp/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function edit($SysId, $Action)
    {
        $this->data['page_title'] = "Edit Nota Hasil Produksi";
        $this->data['page_content'] = "TrxWh/Nhp/edit";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Nhp/edit.js?v=' . time() . '"></script>';

        $this->data['Action'] = $Action;

        $this->data['Ccs'] = $this->db->get_where($this->tmst_cost_center, ['Is_Active' => 1, 'cc_group_id' =>  1])->result();
        $this->data['Warehouses'] = $this->db->query("SELECT * FROM qview_tmst_warehouse_active WHERE Item_Category_ID = 3")->result();

        $this->data['Hdr'] = $this->db->get_where($this->ttrx_hdr_nota_hasil_produksi, ['SysId' => $SysId])->row();
        $this->data['Dtls'] = $this->db->get_where($this->qview_detail_nota_hasil_produksi, ['SysId_Hdr' => $SysId])->result();


        $this->load->view($this->layout, $this->data);
    }

    public function approval()
    {
        $this->data['page_title'] = "Approval Nota Hasil Produksi";
        $this->data['page_content'] = "TrxWh/Nhp/approval";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Nhp/approval.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function add()
    {
        $this->data['page_title'] = "List Nota Hasil Produksi";
        $this->data['page_content'] = "TrxWh/Nhp/add";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Nhp/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function report()
    {
        $this->data['page_title'] = "Report Nota Hasil Produksi";
        $this->data['page_content'] = "TrxWh/Nhp/report";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Nhp/report.js?v=' . time() . '"></script>';
        $this->data['Warehouses'] = $this->db->query("SELECT * FROM qview_tmst_warehouse_active WHERE Item_Category_ID = 3")->result();

        $this->load->view($this->layout, $this->data);
    }

    public function daily_report()
    {
        $this->data['page_title'] = "Rekap Hasil Produksi Harian";
        $this->data['page_content'] = "TrxWh/Nhp/daily_report";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Nhp/daily_report.js?v=' . time() . '"></script>';
        $this->data['Warehouses'] = $this->db->query("SELECT * FROM qview_tmst_warehouse_active WHERE Item_Category_ID = 3")->result();

        $this->load->view($this->layout, $this->data);
    }

    public function store()
    {
        $Doc_Number = $this->help->Gnrt_Identity_Monthly($this->Pattern_DocNo, $this->Counter_Length, $this->Concate_DocNo);

        $SysId_Items = $this->input->post('SysId');
        $Items_Codes = $this->input->post('item_codes');
        $Qtys = $this->input->post('Qty');
        $Wh_ids = $this->input->post('wh_id');
        $Ccs = $this->input->post('ccs');
        $Remarks = $this->input->post('remark');

        $this->db->trans_start();

        $this->db->insert($this->ttrx_hdr_nota_hasil_produksi, [
            'DocNo' => $Doc_Number,
            'DocDate' => $this->input->post('DocDate'),
            'Note' => $this->input->post('Note'),
            'Approval_Status' => 0,
            // 'Approve_Date'=> 
            // 'Approve_by'=> 
            'isCancel' => 0,
            // 'Cancel_Date'=> 
            // 'Cancel_by'=> 
            'Created_IP' => $this->help->get_client_ip(),
            'Creation_DateTime' => $this->DateTime,
            'Created_By' => $this->session->userdata('impsys_nik'),
            // 'Last_Update'=> 
            // 'Update_By'=> 
        ]);

        $id = $this->db->insert_id();

        for ($i = 0; $i < count($Items_Codes); $i++) {
            $this->db->insert($this->ttrx_dtl_nota_hasil_produksi, [
                'SysId_Hdr' => $id,
                'SysId_Item' => $SysId_Items[$i],
                'Item_Code' => $Items_Codes[$i],
                'Warehouse_ID' => $Wh_ids[$i],
                'CostCenter_ID' => $Ccs[$i],
                'Qty' => $Qtys[$i],
                'Remark' => $Remarks[$i]
            ]);
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
                "msg" => "Berhasil Menyimpan Pencatatan hasil produksi !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function update()
    {
        $SysId_Hdr = $this->input->post('sysid');
        $SysId_Items = $this->input->post('SysId');
        $Items_Codes = $this->input->post('item_codes');
        $Qtys = $this->input->post('Qty');
        $Wh_ids = $this->input->post('wh_id');
        $Ccs = $this->input->post('ccs');
        $Remarks = $this->input->post('remark');

        $this->db->trans_start();
        $this->db->delete($this->ttrx_dtl_nota_hasil_produksi, ['SysId_Hdr' => $SysId_Hdr]);
        $this->db->where('SysId', $SysId_Hdr)->update($this->ttrx_hdr_nota_hasil_produksi, [
            'DocDate' => $this->input->post('DocDate'),
            'Note' => $this->input->post('Note'),
            'Last_Update' => $this->DateTime,
            'Update_By' => $this->session->userdata('impsys_nik')
        ]);

        for ($i = 0; $i < count($Items_Codes); $i++) {
            $this->db->insert($this->ttrx_dtl_nota_hasil_produksi, [
                'SysId_Hdr' =>  $SysId_Hdr,
                'SysId_Item' => $SysId_Items[$i],
                'Item_Code' => $Items_Codes[$i],
                'Warehouse_ID' => $Wh_ids[$i],
                'CostCenter_ID' => $Ccs[$i],
                'Qty' => $Qtys[$i],
                'Remark' => $Remarks[$i]
            ]);
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
                "msg" => "Berhasil Menyimpan Pencatatan hasil produksi !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function verify()
    {
        $SysId = $this->input->post('SysId');
        $Param = intval($this->input->post('Param'));

        $this->db->trans_start();

        $this->db->where('SysId', $SysId)->update($this->ttrx_hdr_nota_hasil_produksi, [
            'Approval_Status' => $Param,
            'Approve_Date' => $this->DateTime,
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

        $RowData = $this->db->get_where($this->ttrx_hdr_nota_hasil_produksi, ['SysId' => $SysId])->row();
        if ($RowData->isCancel == 1) {
            return $this->help->Fn_resulting_response([
                'code' => 501,
                'msg' => 'Document sudah memiliki status cancel !'
            ]);
        }

        $this->db->trans_start();

        $this->db->where('SysId', $SysId)->update($this->ttrx_hdr_nota_hasil_produksi, [
            'isCancel' => 1,
            'Cancel_Date' => $this->DateTime,
            'Cancel_By' => $this->session->userdata('impsys_nik')
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
                "msg" => "Nota Hasil Produksi berhasil dibatalkan !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function print_daily_report()
    {
        $StartDate = $this->input->get('StartDate');
        $EndDate = $this->input->get('EndDate');
        $approved_by = $this->input->get('approved_by');
        $created_by = $this->input->get('created_by');

        $production_data = $this->get_production_data($StartDate, $EndDate);
        $date_range = $this->create_date_range($StartDate, $EndDate);

        // Membuat array untuk menampung unique item codes
        $unique_item_codes = [];

        // Mengisi date range dengan data produksi yang sesuai atau array kosong
        foreach ($date_range as $date => $items) {
            foreach ($production_data as $prod_date => $prod_items) {
                if ($date == $prod_date) {
                    $date_range[$date] = $prod_items;
                }
            }
        }

        // Loop through production data untuk mengumpulkan unique item codes
        foreach ($production_data as $items) {
            foreach ($items as $code => $item_details) {
                if (!in_array($code, $unique_item_codes)) {
                    $unique_item_codes[] = $code; // Menambahkan kode yang unik
                }
            }
        }
        sort($unique_item_codes); // Mengurutkan item codes jika diperlukan

        $data = [
            'production_data' => $date_range,
            'unique_item_codes' => $unique_item_codes,
            'StartDate' => $StartDate,
            'EndDate' => $EndDate,
            'created_by' => $created_by,
            'approved_by' => $approved_by,
        ];

        $this->load->view('TrxWh/Nhp/print_daily_report', $data);
    }

    private function create_date_range($start, $end)
    {
        $range = [];
        $start = new DateTime($start);
        $end = new DateTime($end);
        $end = $end->modify('+1 day'); // Include end date

        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($start, $interval, $end);

        foreach ($dateRange as $date) {
            $range[$date->format("Y-m-d")] = [];
        }

        return $range;
    }

    private function get_production_data($StartDate, $EndDate)
    {
        $this->db->select("DocDate, CONCAT(Item_Code, ' (', Item_Name, ')') as Item_Code, SUM(Qty) as TotalQty");
        $this->db->from('qview_detail_nota_hasil_produksi');
        $this->db->where('DocDate >=', $StartDate);
        $this->db->where('DocDate <=', $EndDate);
        $this->db->where('Approval_Status', 1);
        $this->db->where('isCancel', 0);
        $this->db->group_by(['DocDate', 'Item_Code']);
        $this->db->order_by('DocDate', 'ASC');
        $this->db->order_by('Item_Code', 'ASC');
        $query = $this->db->get();

        $results = [];
        foreach ($query->result_array() as $row) {
            $results[$row['DocDate']][$row['Item_Code']] = $row;
        }
        return $results;
    }

    // =================================

    public function List_Cost_Center()
    {
        $Datas = $this->db->get_where($this->tmst_cost_center, [
            'Is_Active' => 1,
            'cc_group_id' =>  1,
        ])->result_array();
        foreach ($Datas as $row) {
            $data[$row['SysId']] = $row['nama_cost_center'];
        }
        echo json_encode($data);
    }

    public function List_Warehouse_FG()
    {
        $Datas = $this->db->query("SELECT * FROM qview_tmst_warehouse_active WHERE Item_Category_ID in (3,4)")->result_array();
        foreach ($Datas as $row) {
            $data[$row['Warehouse_ID']] = $row['Warehouse_Name'];
        }
        echo json_encode($data);
    }

    public function DT_List_Nap()
    {
        $query = "SELECT SysId, DocNo, DocDate, Note, Approval_Status, Approve_Date, Approve_by, isCancel,
                         Cancel_Date, Cancel_by, Created_IP, Creation_DateTime, Created_By, Last_Update, Update_By
                  FROM ttrx_hdr_nota_hasil_produksi";

        $search = array(
            'DocNo',
            'DocDate',
            'Note'
        );

        $where = [];

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_List_Nap_ToApprove()
    {
        $query = "SELECT SysId, DocNo, DocDate, Note, Approval_Status, Approve_Date, Approve_by, isCancel,
                         Cancel_Date, Cancel_by, Created_IP, Creation_DateTime, Created_By, Last_Update, Update_By
                  FROM ttrx_hdr_nota_hasil_produksi";

        $search = array('DocNo', 'DocDate', 'Note');

        $where = ['Approval_Status' => 0, 'isCancel' => 0];

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_list_Item_FG()
    {
        $sysid = $this->input->get('sysid');
        $sysidArray = explode(',', $this->db->escape_str($sysid));

        $query = "SELECT * FROM qmst_item";

        $search = array(
            'Item_Code',
            'Item_Name',
            'Group_Name',
            'Item_Color',
            'Brand',
            'Model',
            'Item_Dimensions',
            'Uom'
        );

        $where = [];
        if (!empty($sysidArray)) {
            $where['SysId NOT IN '] = $sysidArray;
        }
        $where['Item_Category_Init NOT IN '] =  [
            'SP',
            'RM',
            'AST'
        ];
        $where["Is_Expenses"] = 0;
        $where["Is_Prod"] = 1;

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_report_nhp_detail()
    {
        $startDate = $this->input->post('from');
        $endDate = $this->input->post('to');
        $warehouse = $this->input->post('warehouse');

        $startDate = $this->db->escape_str($startDate);
        $endDate = $this->db->escape_str($endDate);

        $query = "SELECT * FROM qview_detail_nhp_legitimate";
        $search = ['DocNo', 'DocDate', 'Item_Code', 'Item_Name', 'Warehouse_Name', 'nama_cost_center', 'Remark'];

        // Prepare the where clause
        $where = [];
        $where["DATE_FORMAT(DocDate, '%Y-%m-%d') >"] = $startDate;
        $where["DATE_FORMAT(DocDate, '%Y-%m-%d') <"] = $endDate;
        if (!empty($warehouse)) {
            $where["Warehouse_ID"] = $warehouse;
        }

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }
}
