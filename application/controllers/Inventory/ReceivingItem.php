<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReceivingItem extends CI_Controller
{
    public $layout = 'layout';
    protected $ttrx_hdr_ri          = 'ttrx_hdr_pur_receive_item';
    protected $ttrx_dtl_ri          = 'ttrx_dtl_pur_receive_item';
    protected $ttrx_hdr_po          = 'ttrx_hdr_purchase_order';
    protected $ttrx_dtl_po          = 'ttrx_dtl_purchase_order';
    protected $tmst_account         = 'tmst_account';
    protected $tmst_account_address = 'tmst_account_address';
    protected $qmst_item            = 'qmst_item';
    protected $qview_price_approved = 'qview_price_approved';
    protected $tmst_transport_with  = 'tmst_transport_with';
    protected $ttrx_dtl_payment_purchase       = 'ttrx_dtl_payment_purchase';
    protected $ttrx_hdr_payment_purchase       = 'ttrx_hdr_payment_purchase';
    protected $tmst_warehouse       = 'tmst_warehouse';
    protected $tmst_beacukai        = 'tmst_beacukai';
    protected $qview_item_outstanding_po_vs_rr  = 'qview_item_outstanding_po_vs_rr';
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
        $this->data['page_title'] = "List Receiving Item";
        $this->data['page_content'] = "Inventory/ReceivingItem/list";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/inventory-assets/receiving-item/js/list.js?v=' . time() . '"></script>';

        $this->data['List_Vendor'] = $this->db->select('t1.SysId, t1.Account_Code, t1.Account_Name')
            ->from($this->tmst_account . ' t1')
            ->join($this->tmst_account_address . ' t2', 't1.Account_Code = t2.Account_Code')
            ->join($this->qview_price_approved . ' t3', 't1.Account_Code = t3.Account_Code')
            ->where('t1.Category_ID', 'VP')
            ->where('t1.Is_Active', 1)
            ->group_by('t1.SysId', 't1.Account_Code', 't1.Account_Name')
            ->get();

        $this->data['List_Transport_With'] = $this->db
            ->where('Is_Active', 1)
            ->get($this->tmst_transport_with);

        $this->data['bc_types'] = $this->db->get('tmst_beacukai');

        $this->load->view($this->layout, $this->data);
    }

    public function monitoring()
    {
        $this->data['page_title'] = "List Receiving Item";
        $this->data['page_content'] = "Inventory/ReceivingItem/monitoring";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/inventory-assets/receiving-item/js/monitoring.js?v=' . time() . '"></script>';

        $this->data['List_Vendor'] = $this->db->select('t1.SysId, t1.Account_Code, t1.Account_Name')
            ->from($this->tmst_account . ' t1')
            ->join($this->tmst_account_address . ' t2', 't1.Account_Code = t2.Account_Code')
            ->join($this->qview_price_approved . ' t3', 't1.Account_Code = t3.Account_Code')
            ->where('t1.Category_ID', 'VP')
            ->where('t1.Is_Active', 1)
            ->group_by('t1.SysId', 't1.Account_Code', 't1.Account_Name')
            ->get();

        $this->load->view($this->layout, $this->data);
    }

    public function store()
    {
        $state         = $this->input->post('state');
        $sysid         = $this->input->post('sysid');

        $rr_no          = $this->input->post('rr_no');
        $rr_date        = $this->input->post('rr_date');
        $vendor_sn      = $this->input->post('vendor_sn');
        $vendor_sn_date = $this->input->post('vendor_sn_date');
        $vendor_id      = $this->input->post('vendor_id');
        $transpot_with  = $this->input->post('transpot_with');
        $po_no          = $this->input->post('po_no');
        $po_date        = $this->input->post('po_date');
        $isAsset        = $this->input->post('isAsset');
        $base_amount    = $this->input->post('isAsset');
        $nopol          = $this->input->post('nopol');
        $notes          = $this->input->post('notes');

        $bc_number          = $this->input->post('bc_number');
        $bc_type_info       = $this->input->post('bc_type_info');
        $bc_number_info     = $this->input->post('bc_number_info');
        $bc_date_info       = $this->input->post('bc_date_info');
        $faktur_number      = $this->input->post('faktur_number');
        $faktur_number_info = $this->input->post('faktur_number_info');
        $faktur_date_info   = $this->input->post('faktur_date_info');

        $item_code          = $this->input->post('item_code');
        $item_name          = $this->input->post('item_name');
        $uom                = $this->input->post('uom');
        $outstanding        = $this->input->post('outstanding');
        $warehouse          = $this->input->post('warehouse');
        $po_qty             = $this->input->post('po_qty');
        $other_doc          = $this->input->post('other_doc');
        $received_now       = $this->input->post('received_now');

        if ($state == 'ADD') {
            $rr_no = $this->help->Gnrt_Identity_Number_RCV("RCV");
        }

        $this->db->trans_start();
        if ($state == 'ADD') {
            $this->db->insert($this->ttrx_hdr_ri, [
                'RR_Number'             => $rr_no,
                'RR_Date'               => date('Y-m-d', strtotime($rr_date)),
                'PO_Number'             => $po_no,
                // 'RR_Status'             => NULL,
                'DO_Numb_Suplier'       => $vendor_sn,
                'Account_ID'            => $vendor_id,
                'Receive_Status'        => 'FR',
                'isAsset'               => $isAsset,
                'isPartial'             => 0,
                // 'Invoice_Status'        => NULL,
                'Transport_With'        => $transpot_with,
                'No_Police_Vehicle'     => $nopol,
                'SupplierSNDate'        => date('Y-m-d', strtotime($vendor_sn_date)),
                // 'Ref_Purchase_Invoice'  => NULL,
                'isFreeItem'            => $base_amount > 0 ? 0 : 1,
                'Approval_Status'       => 0,
                'isCancel'              => 0,
                'RR_Notes'              => $notes,
                'ip_create'             => $this->help->get_client_ip(),
                'Created_By'            => $this->session->userdata('impsys_nik'),
                'Creation_DateTime'     => $this->DateTime,
            ]);

            // // Debugging ERROR
            // if (!$test) {
            //     // Menangkap error
            //     $error = $this->db->error();
            //     var_dump($error); // Tampilkan error detail
            //     die();
            // } else {
            //     var_dump($test); // Tampilkan hasil insert jika berhasil
            //     die();
            // }

            $total_outstanding = 0;
            for ($i = 0; $i < count($item_code); $i++) {
                $total_outstanding += $outstanding[$i];

                $this->db->insert($this->ttrx_dtl_ri, [
                    'RR_Number'             => $rr_no,
                    'Item_Code'             => $item_code[$i],
                    'Qty'                   => $received_now[$i],
                    'Uom'                   => $uom[$i],
                    'Warehouse_ID'          => $warehouse[$i],
                    // 'Secondary_Qty'         => NULL,
                    // 'Secondary_Uom'         => NULL,
                    // 'Secondary_Qty_Prod'    => NULL,
                    // 'Secondary_Uom_Prod'    => NULL,
                    'return_qty'            => 0,
                    // 'LabelOut'              => NULL,
                    // 'IsLabel'               => NULL,
                    // 'Item_CodeAlias'        => NULL,
                    // 'Item_NameAlias'        => NULL,
                ]);
            }

            if ($total_outstanding > 0) {
                $this->db->where('RR_Number', $rr_no);
                $this->db->update($this->ttrx_hdr_ri, [
                    'Receive_Status' => 'HR',
                    'isPartial'      => 1,
                ]);
            }
        } else {
            $this->db->where('SysId', $sysid);
            $this->db->update($this->ttrx_hdr_ri, [
                'RR_Date'               => date('Y-m-d', strtotime($rr_date)),
                'DO_Numb_Suplier'       => $vendor_sn,
                'Receive_Status'        => 'FR',
                'isPartial'             => 0,
                'Transport_With'        => $transpot_with,
                'No_Police_Vehicle'     => $nopol,
                'SupplierSNDate'        => date('Y-m-d', strtotime($vendor_sn_date)),
                'BC_Number'             => $bc_number,
                'BC_Type_Info'          => $bc_type_info,
                'BC_Number_Info'        => $bc_number_info,
                'BC_Date_Info'          => date('Y-m-d', strtotime($bc_date_info)),
                'Faktur_Number'         => $faktur_number,
                'Faktur_Date_Info'      => date('Y-m-d', strtotime($faktur_date_info)),
                'Faktur_Number_Info'    => $faktur_number_info,
                'Approval_Status'       => 0,
                'RR_Notes'              => $notes,
                'Update_By'             => $this->session->userdata('impsys_nik'),
                'Last_Update'           => $this->DateTime,
            ]);

            $this->db->delete($this->ttrx_dtl_ri, [
                'RR_Number' => $rr_no
            ]);

            // $total_outstanding = 0;
            for ($i = 0; $i < count($item_code); $i++) {
                // $total_outstanding += $outstanding[$i];

                $this->db->insert($this->ttrx_dtl_ri, [
                    'RR_Number'             => $rr_no,
                    'Item_Code'             => $item_code[$i],
                    'Qty'                   => $po_qty[$i],
                    'Uom'                   => $uom[$i],
                    'Warehouse_ID'          => $warehouse[$i],
                ]);
            }

            // if ($total_outstanding > 0) {
            //     $this->db->where('RR_Number', $rr_no);
            //     $this->db->update($this->ttrx_hdr_ri, [
            //         'Receive_Status' => 'HR',
            //         'isPartial'      => 1,
            //     ]);
            // }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // $error = $this->db->error();
            $this->db->trans_rollback();

            $response = [
                "code" => 505,
                "msg" => "Proses penyimpanan gagal !",
                // "error" => $error['message']
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Berhasil Menyimpan Receiving Item !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function store_faktur_bc()
    {
        $this->db->trans_start();

        $sysid              = $this->input->post('sysid_modal_faktur_bc');
        $bc_number          = $this->input->post('bc_number');
        $bc_type_info       = $this->input->post('bc_type_info');
        $bc_number_info     = $this->input->post('bc_number_info');
        $bc_date_info       = $this->input->post('bc_date_info');
        $faktur_number      = $this->input->post('faktur_number');
        $faktur_number_info = $this->input->post('faktur_number_info');
        $faktur_date_info   = $this->input->post('faktur_date_info');

        $this->db->where('SysId', $sysid);
        $this->db->update($this->ttrx_hdr_ri, [
            'BC_Number'             => $bc_number,
            'BC_Type_Info'          => $bc_type_info,
            'BC_Number_Info'        => $bc_number_info,
            'BC_Date_Info'          => $bc_date_info ? date('Y-m-d', strtotime($bc_date_info)) : NULL,
            'Faktur_Number'         => $faktur_number,
            'Faktur_Date_Info'      => $faktur_date_info ? date('Y-m-d', strtotime($faktur_date_info)) : NULL,
            'Faktur_Number_Info'    => $faktur_number_info,
            'Update_By'             => $this->session->userdata('impsys_nik'),
            'Last_Update'           => $this->DateTime,
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
                "msg" => "Berhasil Menyimpan Data Faktur & BC Receiving Item !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function GetDataDtlPO()
    {
        $sysid = $this->input->post('sysid');

        $this->db->where('SysId', $sysid);
        $this->db->from($this->qview_item_outstanding_po_vs_rr);

        $data_dtl = $this->db->get()->result();

        // echo "<pre>";
        // print_r($data_dtl);
        // echo "</pre>";
        // die();

        $response = [
            "code"      => 200,
            "msg"       => "Berhasil Mendapatkan Data !",
            "data_dtl"  => $data_dtl,
        ];

        return $this->help->Fn_resulting_response($response);
    }

    public function GetDataEditRevisi()
    {
        $sysid = $this->input->post('sysid');
        $state = $this->input->post('state');

        // GET DATA Receiving Item
        $this->db->where('SysId', $sysid);
        $this->db->from($this->ttrx_hdr_ri);

        $data_rr = $this->db->get()->row();
        // GET DATA Receiving Item - END

        if ($data_rr->Approval_Status == 1 && $state == 'EDIT') {
            $response = [
                "code" => 500,
                "msg"  => "Data Tidak Bisa Diubah Karena Sudah Approve",
            ];
        } else {
            $this->db->where('t1.SysId', $sysid);
            $this->db->select('t1.*, t2.Account_Name, t3.Doc_Date AS PO_Date, t4.Address AS Vendor_Address');
            $this->db->from($this->ttrx_hdr_ri . ' as t1');
            $this->db->join($this->tmst_account . ' as t2', 't1.Account_ID = t2.SysId', 'left');
            $this->db->join($this->ttrx_hdr_po . ' as t3', 't1.PO_Number = t3.Doc_No');
            $this->db->join($this->tmst_account_address . ' as t4', 't3.SysId_Address = t4.SysId');

            $data_hdr = $this->db->get()->row();

            $this->db->where('t1.RR_Number', $data_rr->RR_Number);
            $this->db->select('t1.*, t3.Item_Name, t3.Uom, t3.Unit_Price');
            $this->db->from($this->ttrx_dtl_ri . ' as t1');
            $this->db->join($this->ttrx_hdr_ri . ' as t2', 't1.RR_Number = t2.RR_Number');
            $this->db->join($this->ttrx_dtl_po . ' as t3', 't2.PO_Number = t3.Doc_No_Hdr AND t1.Item_Code = t3.Item_Code');

            $data_dtl = $this->db->get()->result();

            // die("<pre>" . print_r($data_hdr, true) . "</pre>");

            $response = [
                "code"      => 200,
                "msg"       => "Berhasil Mendapatkan Data !",
                "data_hdr"  => $data_hdr,
                "data_dtl"  => $data_dtl,
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function GetDataFakturBC()
    {
        $sysid = $this->input->post('sysid');

        $this->db->where('SysId', $sysid);
        $this->db->from($this->ttrx_hdr_ri);

        $data_hdr = $this->db->get()->row();

        $response = [
            "code"      => 200,
            "msg"       => "Berhasil Mendapatkan Data !",
            "data_hdr"  => $data_hdr,
        ];

        return $this->help->Fn_resulting_response($response);
    }

    public function Toggle_Status_Close()
    {
        $sysid  = $this->input->post('sysid');
        $reason = $this->input->post('reason');

        $row = $this->db->get_where($this->ttrx_hdr_ri, ['SysId' => $sysid])->row();

        if ($row->isCancel == 1) {
            $this->db->where('SysId', $sysid);
            $this->db->update($this->ttrx_hdr_ri, [
                'isCancel'      => 0,
                'Cancel_Reason' => NULL
            ]);

            $response = [
                "code" => 200,
                "msg" => "Status Receiving Item berhasil di ubah menjadi Open !"
            ];
        } else {
            // Cek Apakah RR udah ditarik ke table ttrx_dtl_payment_purchase apa belum

            // GET DATA Payment Purchase
            $this->db->where('t1.no_doc', $row->RR_Number);
            $this->db->where('t2.Is_Active', 1);
            $this->db->from($this->ttrx_dtl_payment_purchase . ' as t1');
            $this->db->join($this->ttrx_hdr_payment_purchase . ' as t2', 't1.id_hdr = t2.SysId');

            $data_pay = $this->db->get()->result();

            // print_r($data_pay);
            // die();

            // GET DATA Payment Purchase - END
            // if ($data_pay) {
            if (!empty($data_pay)) {
                $response = [
                    "code" => 500,
                    "msg" => "RR tidak bisa di close karena sudah terdapat payment !"
                ];
            } else {
                $this->db->where('SysId', $sysid);
                $this->db->update($this->ttrx_hdr_ri, [
                    'isCancel'      => 1,
                    'Cancel_Reason' => $reason
                ]);

                $response = [
                    "code" => 200,
                    "msg" => "Status Receiving Item berhasil di ubah menjadi Cancel !"
                ];
            }
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function approval()
    {
        $this->data['page_title']   = "Approval Receiving Item";
        $this->data['page_content'] = "Inventory/ReceivingItem/approval";
        $this->data['script_page']  =  '<script src="' . base_url() . 'assets/inventory-assets/receiving-item/js/approval.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function detail()
    {
        $sysid = $this->input->post('sysid');

        $this->db->where('t1.SysId', $sysid);
        $this->db->select('t1.*, t2.Account_Name, t3.Doc_Date AS PO_Date, t4.Address AS Vendor_Address, t5.Transport_Name');
        $this->db->from($this->ttrx_hdr_ri . ' as t1');
        $this->db->join($this->tmst_account . ' as t2', 't1.Account_ID = t2.SysId', 'left');
        $this->db->join($this->ttrx_hdr_po . ' as t3', 't1.PO_Number = t3.Doc_No');
        $this->db->join($this->tmst_account_address . ' as t4', 't3.SysId_Address = t4.SysId');
        $this->db->join($this->tmst_transport_with . ' as t5', 't1.Transport_With = t5.SysId');

        $data_hdr = $this->db->get()->row();

        $this->db->where('t1.RR_Number', $data_hdr->RR_Number);
        $this->db->select('t1.*, t3.Item_Name, t3.Uom, t3.Unit_Price, t4.Warehouse_Name');
        $this->db->from($this->ttrx_dtl_ri . ' as t1');
        $this->db->join($this->ttrx_hdr_ri . ' as t2', 't1.RR_Number = t2.RR_Number');
        $this->db->join($this->ttrx_dtl_po . ' as t3', 't2.PO_Number = t3.Doc_No_Hdr AND t1.Item_Code = t3.Item_Code');
        $this->db->join($this->tmst_warehouse . ' as t4', 't1.Warehouse_ID = t4.Warehouse_ID');

        $data_dtl = $this->db->get()->result();

        $response = [
            "code"      => 200,
            "msg"       => "Berhasil Mendapatkan Data !",
            "data_hdr"  => $data_hdr,
            "data_dtl"  => $data_dtl,
        ];

        return $this->help->Fn_resulting_response($response);
    }

    public function verify()
    {
        $sysid          = $this->input->post('sysid');
        $is_verified    = $this->input->post('is_verified');

        // var_dump($sysid);
        // die();
        $this->db->trans_start();
        if ($is_verified == 2) {
            $this->db->where('SysId', $sysid);
            $this->db->update($this->ttrx_hdr_ri, [
                'Approval_Status'  => 2,
                'Approve_Date'      => $this->DateTime
            ]);

            $response = [
                "code" => 200,
                "msg" => "Data telah di riject !"
            ];
        } else {
            // GET DATA Receiving Item
            $this->db->where('SysId', $sysid);
            $this->db->from($this->ttrx_hdr_ri);

            $data_rr = $this->db->get()->row();
            // GET DATA Receiving Item - END

            $this->db->where('t1.RR_Number', $data_rr->RR_Number);
            $this->db->select('t1.*, t2.isAsset, t3.SysId AS Item_Id, t3.Is_Expenses');
            $this->db->from($this->ttrx_dtl_ri . ' as t1');
            $this->db->join($this->ttrx_hdr_ri . ' as t2', 't1.RR_Number = t2.RR_Number');
            $this->db->join($this->qmst_item . ' as t3', 't1.Item_Code = t3.Item_Code');

            $data_dtl = $this->db->get()->result();

            foreach ($data_dtl as $key => $val) {
                // Ini Disini Ada Kondisi Ketika Is Asset Kalau IsAsset Maka dia akan buat nomor asset nanti dibuat bang kus kalau misal dia jasa maka gaa update kemana mana itu dilihat dari field isExpenses = 1 di qmst_item
                if ($val->isAsset == 1) {
                    $this->warehouse->generate_asset($val->Item_Id, $val->Qty, $this->DateTime, date('Y'));
                } else if ($val->Is_Expenses == 0) {
                    // $this->warehouse->update_stok($val->Item_Code, $val->Warehouse_ID, 'Receive', $val->Qty);
                }
            }

            $this->db->where('SysId', $sysid);
            $this->db->update($this->ttrx_hdr_ri, [
                'Approval_Status'   => 1,
                'Approve_Date'      => $this->DateTime
            ]);

            $response = [
                "code" => 200,
                "msg" => "Data berhasil di verifikasi !"
            ];
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }

        return $this->help->Fn_resulting_response($response);
    }
    // ------------------------ Data Table Section

    public function DT_listdata()
    {
        $query  = "SELECT t1.*, t2.Account_Name FROM $this->ttrx_hdr_ri AS t1 
                LEFT JOIN $this->tmst_account AS t2 ON t1.Account_ID = t2.SysId";

        $search = array('RR_Number', 'RR_Date', 'PO_Number', 'Account_Name', 'BC_Type_Info', 'BC_Number_Info', 'BC_Date_Info', 'Faktur_Number', 'Faktur_Date_Info');
        // $where  = array('nama_kategori' => 'Tutorial');
        $where  = null;

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_report_rr_detail()
    {
        $startDate = $this->input->post('from');
        $endDate = $this->input->post('to');

        $startDate = $this->db->escape_str($startDate);
        $endDate = $this->db->escape_str($endDate);

        $query = "SELECT * FROM qview_dtl_detail_rr_legitimate";

        $search = ['RR_Number', 'RR_Date', 'PO_Number', 'Account_Name', 'Invoice_Status', 'Item_Code', 'Item_Name', 'Warehouse_Name'];

        // Prepare the where clause
        $where = [];
        $where["DATE_FORMAT(RR_Date, '%Y-%m-%d') >"] = $startDate;
        $where["DATE_FORMAT(RR_Date, '%Y-%m-%d') <"] = $endDate;

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_listdata_approval()
    {
        $query  = "SELECT t1.*, t2.Account_Name FROM $this->ttrx_hdr_ri AS t1 
                LEFT JOIN $this->tmst_account AS t2 ON t1.Account_ID = t2.SysId";

        $search = array('RR_Number', 'RR_Date', 'PO_Number', 'Account_Name', 'BC_Type_Info', 'BC_Number_Info', 'BC_Date_Info', 'Faktur_Number', 'Faktur_Date_Info');
        $where  = array('isCancel' => 0, 'Approval_Status' => 0);

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_listdata_PO()
    {
        // $query  = "SELECT * FROM $this->ttrx_hdr_po";
        $query  = "SELECT DISTINCT t1.SysId, t1.SysId_Vendor, t1.Doc_No, t1.Doc_Date, t1.ETA, t1.ETD, t1.Amount, t1.Currency, t1.Note, t2.Account_Name, t3.Address, t1.IsAsset, t1.Base_Amount FROM $this->qview_item_outstanding_po_vs_rr AS t1 
                    LEFT JOIN $this->tmst_account AS t2 ON t1.SysId_Vendor = t2.SysId
                    LEFT JOIN $this->tmst_account_address AS t3 ON t1.SysId_Address = t3.SysId";

        $search = array('Doc_No', 'Doc_Date', 'ETA', 'ETD', 'Amount', 'Currency');
        $where  = null;

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function getSelect()
    {
        $this->db->where('Is_Entry_Wh', 1);

        $query_warehouse = $this->db->get($this->tmst_warehouse);

        $response = [
            "code"      => 200,
            "msg"       => "Berhasil Mendapatkan Data !",
            "warehouse" => $query_warehouse->result(),
        ];

        return $this->help->Fn_resulting_response($response);
    }

    public function export_pdf_rr($sysid)
    {
        // ----------------- START GET DATA -------------- //
        // $sysid = 5;

        $this->db->where('t1.SysId', $sysid);
        $this->db->select('t1.*, t2.Account_Name, t3.Currency, t3.Doc_Date AS PO_Date, t4.Address AS Vendor_Address, t5.Transport_Name');
        $this->db->from($this->ttrx_hdr_ri . ' as t1');
        $this->db->join($this->tmst_account . ' as t2', 't1.Account_ID = t2.SysId', 'left');
        $this->db->join($this->ttrx_hdr_po . ' as t3', 't1.PO_Number = t3.Doc_No');
        $this->db->join($this->tmst_account_address . ' as t4', 't3.SysId_Address = t4.SysId');
        $this->db->join($this->tmst_transport_with . ' as t5', 't1.Transport_With = t5.SysId');

        $data_hdr = $this->db->get()->row();

        $this->db->where('t1.RR_Number', $data_hdr->RR_Number);
        $this->db->select('t1.*, t2.PO_Number, t3.Item_Name, t3.Uom, t3.Unit_Price, t3.Discount, t3.value_tax_1, t3.value_tax_2, t4.Warehouse_Name');
        $this->db->from($this->ttrx_dtl_ri . ' as t1');
        $this->db->join($this->ttrx_hdr_ri . ' as t2', 't1.RR_Number = t2.RR_Number');
        $this->db->join($this->ttrx_dtl_po . ' as t3', 't2.PO_Number = t3.Doc_No_Hdr AND t1.Item_Code = t3.Item_Code');
        $this->db->join($this->tmst_warehouse . ' as t4', 't1.Warehouse_ID = t4.Warehouse_ID');

        $data_dtl = $this->db->get()->result();
        // ----------------- END GET DATA -------------- //

        $this->load->library('pdfgenerator');

        $data = [
            'data_hdr' => $data_hdr,
            'data_dtl' => $data_dtl
        ];

        $name_file = $data_hdr->RR_Number;
        $paper = 'A4';
        $orientation = "portrait";
        $html = $this->load->view('Inventory/ReceivingItem/export/pdf-receiving-item', $data, true);

        $this->pdfgenerator->generate($html, $name_file, $paper, $orientation);
    }
}
