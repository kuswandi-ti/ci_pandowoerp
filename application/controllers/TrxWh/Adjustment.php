<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Adjustment extends CI_Controller
{
    public $layout = 'layout';
    public $qmst_item = 'qmst_item';
    public $tmst_warehouse = 'tmst_warehouse';
    public $ttrx_hdr_adjustment_stok = 'ttrx_hdr_adjustment_stok';
    public $ttrx_dtl_adjustment_stok = 'ttrx_dtl_adjustment_stok';
    public $tmst_cost_center = 'tmst_cost_center';
    public $tabel_stok = 'qstok_warehouse_item';
    public $qview_detail_adjusment = 'qview_detail_adjusment';
    public $column_dtl = ['SysId', 'SysId_Hdr', 'Item_Code', 'Qty', 'Item_Price', 'Total_Price', 'Aritmatics', 'Warehouse_ID'];
    public $column_hdr = ['SysId', 'DocNo', 'DocDate', 'Currency', 'Rate', 'Amount', 'Base_Amount', 'Note', 'Is_Approve', 'Aprove_By', 'Approve_Time', 'Created_By', 'Created_Time', 'Created_IP', 'Is_Cancel', 'Cancel_Time', 'Cancel_By', 'Last_Updated_Time', 'Last_Updated_By', 'Last_Updated_Ip'];

    protected $Counter_Length = 4;
    protected $Pattern_DocNo = 'ADJ';
    protected $Concate_DocNo = '-';

    protected $Date;
    protected $DateTime;

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
        $this->load->model('m_lpb', 'lpb');
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');
        $this->load->model('m_DataTable', 'M_Datatables');
    }

    public function index()
    {
        $this->data['page_title'] = "List Document Penyesuaian Stok";
        $this->data['page_content'] = "TrxWh/Adjustment/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Adjustment/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function approval()
    {
        $this->data['page_title'] = "Approval Document Penyesuaian Stok";
        $this->data['page_content'] = "TrxWh/Adjustment/approval";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Adjustment/approval.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function report()
    {
        $this->data['page_title'] = "Report Document Penyesuaian Stok";
        $this->data['page_content'] = "TrxWh/Adjustment/report";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Adjustment/report.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function add()
    {
        $this->data['page_title'] = "Form Penyesuaian Stok";
        $this->data['page_content'] = "TrxWh/Adjustment/add";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Adjustment/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function edit($SysId, $action)
    {
        $this->data['page_title'] = "Edit Penyesuaian Stok";
        $this->data['page_content'] = "TrxWh/Adjustment/edit";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/Adjustment/edit.js?v=' . time() . '"></script>';
        $this->data['action'] =  $action;

        $this->data['Ccs'] = $this->db->get_where($this->tmst_cost_center, ['Is_Active' => 1])->result();
        $this->data['Warehouses'] = $this->db->query("SELECT * FROM qview_tmst_warehouse_active")->result();

        $this->data['Hdr'] = $this->db->get_where($this->ttrx_hdr_adjustment_stok, ['SysId' => $SysId])->row();
        $this->data['Dtls'] = $this->db->get_where($this->qview_detail_adjusment, ['SysId_Hdr' => $SysId])->result();


        $this->load->view($this->layout, $this->data);
    }

    public function store()
    {
        $Doc_Number = $this->help->Gnrt_Identity_Monthly($this->Pattern_DocNo, $this->Counter_Length, $this->Concate_DocNo);
        $DocDate = $this->input->post('DocDate');
        $Note = $this->input->post('Note');

        $SysId_Items = $this->input->post('SysId');
        $Item_Codes = $this->input->post('Item_Code');
        $Qtys = $this->input->post('Qty');
        $Qty_stoks = $this->input->post('Qty_stok');
        $Warehouses = $this->input->post('wh_id');
        $CostCenters = $this->input->post('ccs');
        $Prices = $this->input->post('Price');
        $Aritmatic = $this->input->post('aritmatic');

        $this->db->trans_start();

        $this->db->insert($this->ttrx_hdr_adjustment_stok, [
            'DocNo'     => $Doc_Number,
            'DocDate'   => $DocDate,
            'Note'      => $Note,
            'Is_Approve' => 0,
            'Currency'  => 'IDR',
            'Rate'      => 1,
            // 'Amount', // di input saat approve
            // 'Base_Amount' // di input saat approve
            // 'Approve_Date'=> 
            // 'Approve_by'=> 
            'Is_Cancel'  => 0,
            // 'Cancel_Date'=> Created_By, , 
            // 'Cancel_by'=> 
            'Created_IP' => $this->help->get_client_ip(),
            'Created_Time' => $this->DateTime,
            'Created_By' => $this->session->userdata('impsys_nik'),
            // 'Last_Update'=> 
            // 'Update_By'=> 
        ]);

        $id = $this->db->insert_id();

        for ($i = 0; $i < count($SysId_Items); $i++) {
            $Qty = str_replace(',', '', $Qtys[$i]);
            $Price = str_replace(',', '', $Prices[$i]);
            $Qty_stok =  str_replace(',', '', $Qty_stoks[$i]);
            if ($Aritmatic[$i] == '-' && floatval($Qty) > floatval($Qty_stok)) {
                return $this->help->Fn_resulting_response([
                    "code" => 505,
                    "msg" => 'Penyseuaian Mines pada ' . $Item_Codes[$i] . ' tidak bisa di lakukan karna melebihi stok (' . $Qty_stoks[$i] . ') yang tersedia pada gudang terpilih !'
                ]);
            }

            $this->db->insert($this->ttrx_dtl_adjustment_stok, [
                'SysId_Hdr'         => $id,
                'Item_Code'         => $Item_Codes[$i],
                'Qty'               => floatval($Qty),
                'Item_Price'        => floatval($Price),
                'Total_Price'       =>  floatval($Qty) * floatval($Price),
                'Aritmatics'        => $Aritmatic[$i],
                'Warehouse_ID'      => $Warehouses[$i],
                'Cost_Center_ID'    => $CostCenters[$i]
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
                "msg" => "Berhasil Menyimpan Pencatatan Penyesuaian !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function update()
    {
        $SysId_Hdr = $this->input->post('SysId_Hdr');
        $DocDate = $this->input->post('DocDate');
        $Note = $this->input->post('Note');

        $SysId_Items = $this->input->post('SysId');
        $Item_Codes = $this->input->post('Item_Code');
        $Qtys = $this->input->post('Qty');
        $Qty_stoks = $this->input->post('Qty_stok');
        $Warehouses = $this->input->post('wh_id');
        $CostCenters = $this->input->post('ccs');
        $Prices = $this->input->post('Price');
        $Aritmatic = $this->input->post('aritmatic');

        $this->db->trans_start();
        $this->db->delete($this->ttrx_dtl_adjustment_stok, ['SysId_Hdr' => $SysId_Hdr]);
        $this->db->where('SysId', $SysId_Hdr)->update($this->ttrx_hdr_adjustment_stok, [
            // 'DocNo'     => $Doc_Number,
            'DocDate'   => $DocDate,
            'Note'      => $Note,
            // 'Is_Approve' => 0,
            // 'Currency'  => 'IDR',
            // 'Rate'      => 1,
            // 'Amount', // di input saat approve
            // 'Base_Amount' // di input saat approve
            // 'Approve_Date'=> 
            // 'Approve_by'=> 
            // 'Is_Cancel'  => 0,
            // 'Cancel_Date'=> Created_By, , 
            // 'Cancel_by'=> 
            // 'Created_IP' => $this->help->get_client_ip(),
            // 'Created_Time' => $this->DateTime,
            // 'Created_By' => $this->session->userdata('impsys_nik'),
            'Last_Updated_Time' => $this->DateTime,
            'Last_Updated_By' => $this->session->userdata('impsys_nik'),
            'Last_Updated_Ip' => $this->help->get_client_ip()
        ]);

        for ($i = 0; $i < count($SysId_Items); $i++) {
            $Qty = str_replace(',', '', $Qtys[$i]);
            $Price = str_replace(',', '', $Prices[$i]);
            $Qty_stok =  str_replace(',', '', $Qty_stoks[$i]);
            if ($Aritmatic[$i] == '-' && floatval($Qty) > floatval($Qty_stok)) {
                return $this->help->Fn_resulting_response([
                    "code" => 505,
                    "msg" => 'Penyseuaian Mines pada ' . $Item_Codes[$i] . ' tidak bisa di lakukan karna melebihi stok (' . $Qty_stoks[$i] . ') yang tersedia pada gudang terpilih !'
                ]);
            }

            $this->db->insert($this->ttrx_dtl_adjustment_stok, [
                'SysId_Hdr'         => $SysId_Hdr,
                'Item_Code'         => $Item_Codes[$i],
                'Qty'               => floatval($Qty),
                'Item_Price'        => floatval($Price),
                'Total_Price'       =>  floatval($Qty) * floatval($Price),
                'Aritmatics'        => $Aritmatic[$i],
                'Warehouse_ID'      => $Warehouses[$i],
                'Cost_Center_ID'    => $CostCenters[$i]
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
                "msg" => "Berhasil Menyimpan perubahan Penyesuaian stok !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function Cancel()
    {
        $SysId = $this->input->post('SysId');

        $RowData = $this->db->get_where($this->ttrx_hdr_adjustment_stok, ['SysId' => $SysId])->row();
        if ($RowData->Is_Cancel == 1) {
            return $this->help->Fn_resulting_response([
                'code' => 501,
                'msg' => 'Document sudah memiliki status cancel !'
            ]);
        }

        $this->db->where('SysId', $SysId)->update($this->ttrx_hdr_adjustment_stok, [
            'Is_Cancel' => 1,
            'Cancel_Time' => $this->DateTime,
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
                "msg" => "Penyesuaian stok berhasil dibatalkan !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function verify()
    {
        $SysId = $this->input->post('SysId');
        $Param = $this->input->post('Param');

        $Hdr = $this->db->get_where($this->ttrx_hdr_adjustment_stok, ['SysId' => $SysId])->row();
        $Dtls = $this->db->get_where($this->ttrx_dtl_adjustment_stok, ['SysId_Hdr' => $SysId])->result();

        if ($Param == 1) {
            foreach ($Dtls as $li) {
                $stok = $this->db->get_where($this->tabel_stok, ['Item_Code' => $li->Item_Code, 'Warehouse_ID' => $li->Warehouse_ID])->row();

                if ($li->Aritmatics == '-' && floatval($li->Qty) > floatval($stok->Item_Qty)) {
                    return $this->help->Fn_resulting_response([
                        'code' => 501,
                        'msg' => "Penyesuaian Minus(-) pada item $li->Item_Code melebihi stok pada gudang $stok->Warehouse_Name, apabila dilanjutkan stok menjadi minus !"
                    ]);
                }
            }
        }

        $this->db->trans_start();

        $DataAmount = $this->db->query("SELECT COALESCE((sum(Total_Price) * Rate),0) as Base_Amount, COALESCE(SUM(Total_Price),0) as Amount
                                        from qview_detail_adjusment
                                        where SysId_Hdr = $SysId
                                        group by SysId_Hdr")->row();

        $this->db->where('SysId', $SysId)->update($this->ttrx_hdr_adjustment_stok, [
            'Is_Approve' => $Param,
            'Approve_Time' => $this->DateTime,
            'Aprove_By' => $this->session->userdata('impsys_nik'),
            'Amount' => $DataAmount->Amount,
            'Base_Amount' => $DataAmount->Base_Amount
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

    public function List_Cost_Center()
    {
        $Datas = $this->db->get_where($this->tmst_cost_center, [
            'Is_Active' => 1,
        ])->result_array();
        foreach ($Datas as $row) {
            $data[$row['SysId']] = $row['nama_cost_center'];
        }
        echo json_encode($data);
    }

    public function List_Warehouse_FG()
    {
        $Datas = $this->db->query("SELECT * FROM qview_tmst_warehouse_active")->result_array();
        foreach ($Datas as $row) {
            $data[$row['Warehouse_ID']] = $row['Warehouse_Name'];
        }
        echo json_encode($data);
    }

    // ========================================= Datatable Section ================================ //

    public function DT_List_Adjustment()
    {
        $query = "SELECT * from $this->ttrx_hdr_adjustment_stok";
        $search = array('DocNo', 'DocDate', 'Note');
        $where  = [];
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_List_Adjustment_Approval()
    {
        $query = "SELECT * from $this->ttrx_hdr_adjustment_stok";
        $search = array('DocNo', 'DocDate', 'Note');
        $where  = [];
        $where["Is_Approve"] = 0;
        $where["Is_Cancel"] = 0;
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_list_Item()
    {
        $sysid = $this->input->get('sysid');

        $sysidArray = explode(',', $this->db->escape_str($sysid));

        $query = "SELECT * from qview_stok_item_global_all";
        $search = array('Item_Code', 'Item_Name', 'Uom', 'Item_Description');

        $where = [];
        if (!empty($sysidArray)) {
            $where['SysId NOT IN '] = $sysidArray;
        }
        $where["Is_Expenses"] = 0;

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_report_adj_detail()
    {
        $startDate = $this->input->post('from');
        $endDate = $this->input->post('to');

        $startDate = $this->db->escape_str($startDate);
        $endDate = $this->db->escape_str($endDate);

        $query = "SELECT * FROM qview_detail_adjustment_legitimate";
        $search = ['DocNo', 'DocDate', 'Note', 'Item_Code', 'Item_Name', 'Warehouse_Name', 'nama_cost_center'];

        // Prepare the where clause
        $where = [];
        $where["DATE_FORMAT(DocDate, '%Y-%m-%d') >"] = $startDate;
        $where["DATE_FORMAT(DocDate, '%Y-%m-%d') <"] = $endDate;

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }
}
