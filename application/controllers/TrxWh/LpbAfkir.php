<?php

defined('BASEPATH') or exit('No direct script access allowed');

class LpbAfkir extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_lpb = 'ttrx_hdr_lpb_receive';
    public $tbl_dtl_lpb = 'ttrx_dtl_lpb_receive';
    public $ttrx_child_dtl_size_item_lpb = 'ttrx_child_dtl_size_item_lpb';
    public $qview_detail_lpb_lot_child_size = 'qview_detail_lpb_lot_child_size';
    public $ttrx_hdr_afkir_lpb = 'ttrx_hdr_afkir_lpb';
    public $qview_list_hdr_afkir = 'qview_list_hdr_afkir';
    public $ttrx_dtl_afkir_lpb = 'ttrx_dtl_afkir_lpb';
    public $qview_ttrx_detail_lpb_afkir = 'qview_ttrx_detail_lpb_afkir';
    public $qview_dtl_size_item_lpb = 'qview_dtl_size_item_lpb';
    public $ttrx_price_approved = 'ttrx_price_approved';
    public $qmst_item = 'qmst_item';
    public $thst_pre_oven = 'thst_pre_oven';
    protected $tmst_currency = 'tmst_currency';
    protected $tmst_size_item_grid = 'tmst_size_item_grid';
    protected $qmst_operator_grader = 'qmst_operator_grader';

    protected $Counter_Length = 3;
    protected $Pattern_DocNo = 'AFR-';
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
        $this->data['page_title'] = "List Afkir Hasil Grade";
        $this->data['page_content'] = "TrxWh/LpbAfkir/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/LpbAfkir/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function add()
    {
        $this->data['page_title'] = "Form Afkir Bundle";
        $this->data['page_content'] = "TrxWh/LpbAfkir/add";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/LpbAfkir/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function edit($sysid, $action)
    {
        $this->data['page_title'] = "Form Afkir Bundle";
        $this->data['page_content'] = "TrxWh/LpbAfkir/edit";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/LpbAfkir/edit.js?v=' . time() . '"></script>';

        $this->data['action'] = $action;
        $this->data['Hdr'] = $this->db->get_where($this->ttrx_hdr_afkir_lpb, ['SysId' => $sysid])->row();
        $this->data['Dtls'] = $this->db->get_where($this->qview_ttrx_detail_lpb_afkir, ['SysId_Hdr' => $sysid])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function approval()
    {
        $this->data['page_title'] = "Approval Afkir Bundle";
        $this->data['page_content'] = "TrxWh/LpbAfkir/approval";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/LpbAfkir/approval.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function Cancel()
    {
        $SysId = $this->input->post('SysId');

        $RowData = $this->db->get_where($this->ttrx_hdr_afkir_lpb, ['SysId' => $SysId])->row();
        if ($RowData->Is_Cancel == 1) {
            return $this->help->Fn_resulting_response([
                'code' => 501,
                'msg' => 'Document sudah memiliki status cancel !'
            ]);
        }

        $Dtls = $this->db->get_where($this->ttrx_dtl_afkir_lpb, ['SysId_Hdr' => $SysId])->result();

        $this->db->trans_start();
        foreach ($Dtls as $li) {
            $this->db->query(
                "UPDATE ttrx_child_dtl_size_item_lpb set Qty_Afkir = Qty_Afkir - $li->Qty where SysId = $li->SysId_Child_Size"
            );
        }

        $this->db->where('SysId', $SysId)->update($this->ttrx_hdr_afkir_lpb, [
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
                "msg" => "Afkir berhasil dibatalkan !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store()
    {
        $Doc_Number = $this->help->Gnrt_Identity_Monthly($this->Pattern_DocNo, $this->Counter_Length, $this->Concate_DocNo);
        $Date_Afkir = $this->input->post('Date_Afkir');
        $Note = $this->input->post('Note');
        $IDs = $this->input->post('ID');
        $Qty_Availables = $this->input->post('Qty_Available');
        $Qty_Afkirs = $this->input->post('Qty_Afkir');
        $Remarks = $this->input->post('remark');

        $this->db->trans_start();

        $this->db->insert($this->ttrx_hdr_afkir_lpb, [
            'Doc_Afkir' => $Doc_Number,
            'Date_Afkir' => $Date_Afkir,
            'Note' => $Note,
            'Created_By' => $this->session->userdata('impsys_nik'),
            'Created_Time' => $this->DateTime,
            'Created_IP' => $this->help->get_client_ip()
        ]);

        $id = $this->db->insert_id();

        for ($i = 0; $i < count($IDs); $i++) {
            $rowData = $this->db->get_where($this->qview_detail_lpb_lot_child_size, ['SysId' => $IDs[$i]])->row();
            $this->db->insert($this->ttrx_dtl_afkir_lpb, [
                'SysId_Hdr' => $id,
                'SysId_Child_Size' => $IDs[$i],
                'Size_ID' => $rowData->Id_Size_Item,
                'SysId_Lot' =>  $rowData->Id_Lot,
                'Item_Code' => $rowData->Item_Code,
                'Item_Price' => floatval($rowData->original_price),
                'Item_Base_Price' => floatval($rowData->harga_per_pcs),
                'Uom_Purchase' => $rowData->Uom,
                'Item_Height' => $rowData->Item_Height,
                'Item_Width' => $rowData->Item_Width,
                'Item_Length' => $rowData->Item_Length,
                'Cubication' => $rowData->Cubication,
                'Qty' => floatval($Qty_Afkirs[$i]),
                'Remark' => $Remarks[$i],
                'Last_Updated_Time' => $this->DateTime,
                'Last_Updated_By' => $this->session->userdata('impsys_nik'),
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
                "msg" => "Berhasil Menyimpan Pencatatan Afkir LPB !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function update()
    {
        $SysId = $this->input->post('SysId');
        $Date_Afkir = $this->input->post('Date_Afkir');
        $Note = $this->input->post('Note');
        $IDs = $this->input->post('ID');
        $Qty_Availables = $this->input->post('Qty_Available');
        $Qty_Afkirs = $this->input->post('Qty_Afkir');
        $Remarks = $this->input->post('remark');

        $this->db->trans_start();
        $this->db->delete($this->ttrx_dtl_afkir_lpb, ['SysId_Hdr' => $SysId]);
        $this->db->where('SysId', $SysId)->update($this->ttrx_hdr_afkir_lpb, [
            'Date_Afkir' => $Date_Afkir,
            'Note' => $Note,
            'Last_Updated_By' => $this->session->userdata('impsys_nik'),
            'Last_Updated_Time' => $this->DateTime,
            'Last_Updated_Ip' => $this->help->get_client_ip()
        ]);

        for ($i = 0; $i < count($IDs); $i++) {
            $rowData = $this->db->get_where($this->qview_detail_lpb_lot_child_size, ['SysId' => $IDs[$i]])->row();
            $this->db->insert($this->ttrx_dtl_afkir_lpb, [
                'SysId_Hdr' => $SysId,
                'SysId_Child_Size' => $IDs[$i],
                'Size_ID' => $rowData->Id_Size_Item,
                'SysId_Lot' =>  $rowData->Id_Lot,
                'Item_Code' => $rowData->Item_Code,
                'Item_Price' => floatval($rowData->original_price),
                'Item_Base_Price' => floatval($rowData->harga_per_pcs),
                'Uom_Purchase' => $rowData->Uom,
                'Item_Height' => $rowData->Item_Height,
                'Item_Width' => $rowData->Item_Width,
                'Item_Length' => $rowData->Item_Length,
                'Cubication' => $rowData->Cubication,
                'Qty' => floatval($Qty_Afkirs[$i]),
                'Remark' => $Remarks[$i],
                'Last_Updated_Time' => $this->DateTime,
                'Last_Updated_By' => $this->session->userdata('impsys_nik'),
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
                "msg" => "Berhasil Menyimpan Pencatatan Afkir LPB !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function verify()
    {
        $SysId = $this->input->post('SysId');
        $Param = intval($this->input->post('Param'));
        $is_valid = true;

        $SqlSum = $this->db->query("SELECT SUM((Cubication * Qty) * Item_Price) as Amount,
                                        SUM((Cubication * Qty) * Item_Base_Price) as Base_Amount
                                        from ttrx_dtl_afkir_lpb
                                        where SysId_Hdr = $SysId
                                        group by SysId_Hdr");
        if ($Param == 1) {
            if ($SqlSum->num_rows() == 0) {
                $is_valid = false;
            }
            if (!$is_valid) {
                $response = [
                    "code" => 505,
                    "msg" => "Amount Afkir tidak ditemukan !."
                ];
                return $this->help->Fn_resulting_response($response);
            }
        }
        $Dtls = $this->db->get_where($this->ttrx_dtl_afkir_lpb, ['SysId_Hdr' => $SysId])->result();
        $DataAmount = $SqlSum->row();

        $this->db->trans_start();

        if ($Param == 1) {
            foreach ($Dtls as $li) {
                $this->db->where('SysId', $li->SysId_Child_Size)->update('ttrx_child_dtl_size_item_lpb', [
                    'Qty_Afkir' => $li->Qty
                ]);
            }
        }

        $this->db->where('SysId', $SysId)->update($this->ttrx_hdr_afkir_lpb, [
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

    public function print_tempelan($sysid)
    {
        $this->data['Hdr'] = $this->db->get_where($this->qview_list_hdr_afkir, ['SysId' => $sysid])->row();
        $this->data['Dtls'] = $this->db->get_where($this->qview_ttrx_detail_lpb_afkir, ['SysId_Hdr' => $sysid])->result();
        $this->data['page_title'] = "TAG AFKIR BAHAN BAKU";

        $this->load->view('Print/tempelan_afkir', $this->data);
    }

    // =================================================== Datatable section

    public function DT_List_Lpb_Afkir()
    {
        $query = "SELECT * from $this->qview_list_hdr_afkir";
        $search = array('Doc_Afkir', 'Date_Afkir', 'Note');
        $where  = [];
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_List_Lpb_Afkir_Approval()
    {
        $query = "SELECT * from $this->qview_list_hdr_afkir";
        $search = array('Doc_Afkir', 'Date_Afkir', 'Note');
        $where  = ['Is_Approve' => 0];
        $where  = ['Is_Cancel' => 0];
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_list_lot()
    {
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $sysid = $this->input->get('sysid');

        // Escape the input to prevent SQL injection
        $startDate = $this->db->escape_str($startDate);
        $endDate = $this->db->escape_str($endDate);
        $sysidArray = explode(',', $this->db->escape_str($sysid));

        $query = "SELECT SysId, Id_Lot, no_lot, lpb, tgl_kirim, sysid_material, Item_Code, Item_Name, Uom, Currency, original_price,
                  harga_per_pcs, Id_Size_Item, Size_Code, Initial_Size, Item_Height, Item_Width, Item_Length, Cubication, status_kayu,into_oven,
                  Qty, Qty_Afkir, Qty_Available, flag
                  FROM $this->qview_detail_lpb_lot_child_size";

        $search = array(
            'no_lot',
            'lpb',
            'Item_Code',
            'Item_Name',
            'Uom',
            'Currency',
            'original_price',
            'harga_per_pcs',
            'Size_Code',
            'Initial_Size',
            'Item_Height',
            'Item_Width',
            'Item_Length',
            'status_kayu'
        );

        // Prepare the where clause
        $where = [];
        if (!empty($sysidArray)) {
            $where['SysId NOT IN '] = $sysidArray;
        }
        $where["Qty_Available >"] = 0;
        $where["DATE_FORMAT(tgl_kirim, '%Y-%m-%d') >"] = $startDate;
        $where["DATE_FORMAT(tgl_kirim, '%Y-%m-%d') <"] = $endDate;
        $where["into_oven !"] = 3;

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }
}
