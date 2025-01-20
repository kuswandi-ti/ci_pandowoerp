<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PurchaseReturn extends CI_Controller
{
    public $layout = 'layout';
    protected $ttrx_hdr_po                  = 'ttrx_hdr_purchase_order';
    protected $tmst_account                 = 'tmst_account';
    protected $tmst_account_address         = 'tmst_account_address';
    protected $qview_item_outstanding_rr    = 'qview_item_outstanding_rr_vs_pr';
    protected $ttrx_hdr_pur_receive_item    = 'ttrx_hdr_pur_receive_item';
    protected $ttrx_dtl_pur_receive_item    = 'ttrx_dtl_pur_receive_item';
    protected $ttrx_hdr_purchase_return     = 'ttrx_hdr_purchase_return';
    protected $ttrx_dtl_purchase_return     = 'ttrx_dtl_purchase_return';
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

    public function return()
    {
        $this->data['page_title']   = "List Purchase Return ";
        $this->data['page_content'] = "Purchase/PurchaseReturn/list";
        $this->data['script_page']  =  '<script src="' . base_url() . 'assets/purchase-assets/purchase-return/js/list.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function DT_listdata_return()
    {
        $query  = "select t1.*, t2.Account_Name from $this->ttrx_hdr_purchase_return as t1
                    LEFT JOIN $this->tmst_account AS t2 ON t1.Account_ID = t2.SysId";
        $search = array('PR_Number', 'RR_Number', 'PO_Number');
        $where  = [];
        $iswhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $iswhere);
    }

    public function DT_listdata_browse_rr()
    {
        // $query  = "SELECT t1.*, t2.Account_Name, t4.Address FROM $this->ttrx_hdr_pur_receive_item as t1
        //             LEFT JOIN $this->tmst_account AS t2 ON t1.Account_ID = t2.SysId
        //             LEFT JOIN $this->ttrx_hdr_po AS t3 ON t1.PO_Number = t3.Doc_No
        //             LEFT JOIN $this->tmst_account_address AS t4 ON t3.SysId_Address = t4.SysId";

        $query = "SELECT * FROM $this->qview_item_outstanding_rr";

        $search = array('RR_Number', 'RR_Date', 'PO_Number', 'Name_Vendor', 'Address_Vendor', 'RR_Notes');

        $where  = array(
            'Approval_Status' => 1,
            'isCancel' => 0
        );

        $iswhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query_group_by($query, $search, $where, $iswhere, ' GROUP BY RR_Number');
    }

    public function GetDataDtlRR()
    {
        $RRNumber = $this->input->post('sysid');
        // RCV240717-005

        $this->db->where('RR_Number', $RRNumber);
        $this->db->from($this->qview_item_outstanding_rr);

        $data_dtl = $this->db->get()->result();

        $response = [
            "code"      => 200,
            "msg"       => "Berhasil Mendapatkan Data !",
            "data_dtl"  => $data_dtl,
        ];

        return $this->help->Fn_resulting_response($response);
    }

    public function store()
    {
        // Kebutuhan Update Saja
        $state         = $this->input->post('state');
        $sysid         = $this->input->post('sysid');
        $pr_no         = $this->input->post('pr_no');

        // Kebutuhan Create Data & Update
        $rr_no          = $this->input->post('rr_number');
        $pr_date        = $this->input->post('pr_date');
        $vendor_id      = $this->input->post('vendor_id');
        $po_no          = $this->input->post('po_no');
        $notes          = $this->input->post('notes');

        // Kebutuhan Create Data & Update Detail
        $sysid_dtl      = $this->input->post('sysid_dtl');
        $item_code      = $this->input->post('item_code');
        $item_name      = $this->input->post('item_name');
        $return_qty     = $this->input->post('return_qty');
        $warehouse_id   = $this->input->post('warehouse_id');

        $ip = $this->help->get_client_ip();

        if ($state == 'ADD') {
            $pr_no = $this->help->Gnrt_Identity_Number_RCV("PR");
        }

        $this->db->trans_start();
        if ($state == 'ADD') {
            $this->db->insert($this->ttrx_hdr_purchase_return, [
                'PR_Number'             => $pr_no,
                'PR_Date'               => date('Y-m-d', strtotime($pr_date)),
                'Approval_Status'       => 0,
                'Account_ID'            => $vendor_id,
                'RR_Number'             => $rr_no,
                'PO_Number'             => $po_no,
                'Notes'                 => $notes,
                'ip_create'             => $ip,
                'created_by'            => $this->session->userdata('impsys_nik'),
                'created_at'            => $this->DateTime,
            ]);

            for ($i = 0; $i < count($item_code); $i++) {
                $this->db->insert($this->ttrx_dtl_purchase_return, [
                    'PR_Number'     => $pr_no,
                    'Item_Code'     => $item_code[$i],
                    'Item_Name'     => $item_name[$i],
                    'Qty'           => $return_qty[$i],
                    'warehouse_id'  => $warehouse_id[$i],
                ]);
            }
        } else {
            $this->db->where('SysId', $sysid);

            $this->db->update($this->ttrx_hdr_purchase_return, [
                'PR_Date'    => date('Y-m-d', strtotime($pr_date)),
                'Notes'      => $notes,
                'ip_create'  => $ip,
                'updated_by' => $this->session->userdata('impsys_nik'),
                'updated_at' => $this->DateTime,
            ]);

            for ($i = 0; $i < count($sysid_dtl); $i++) {
                $this->db->where('SysId', $sysid_dtl[$i]);
                $this->db->update($this->ttrx_dtl_purchase_return, [
                    'Qty' => $return_qty[$i],
                ]);
            }
        }

        $msg_simpan = $state == 'ADD' ? 'Menyimpan' : 'Mengubah';

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
                "msg" => "Berhasil " . $msg_simpan . " Purchase Return !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function Toggle_Status_Cancel()
    {
        $pr_number = $this->input->post('pr_number');

        $row = $this->db->get_where($this->ttrx_hdr_purchase_return, ['PR_Number' => $pr_number])->row();

        if ($row->IsCancel == 1) {
            $this->db->where('PR_Number', $pr_number);
            $this->db->update($this->ttrx_hdr_purchase_return, [
                'IsCancel' => 0
            ]);

            $response = [
                "code" => 200,
                "msg" => "Status PR berhasil di ubah menjadi Open !"
            ];
        } else {
            $this->db->where('t2.PR_Number', $pr_number);
            $this->db->select('t1.*, t2.RR_Number');
            $this->db->join($this->ttrx_hdr_purchase_return . ' as t2', 't1.PR_Number = t2.PR_Number');
            $this->db->from($this->ttrx_dtl_purchase_return . ' as t1');

            $data_dtl = $this->db->get()->result();

            foreach ($data_dtl as $key => $val) {
                $this->db->where('RR_Number', $val->RR_Number);
                $this->db->where('Item_Code', $val->Item_Code);
                $this->db->update($this->ttrx_dtl_pur_receive_item, [
                    'PR_Number'     => NULL,
                    'return_qty'    => 0,
                ]);
            }

            $this->db->where('PR_Number', $pr_number);
            $this->db->update($this->ttrx_hdr_purchase_return, [
                'IsCancel' => 1
            ]);

            $response = [
                "code" => 200,
                "msg" => "Status PR berhasil di ubah menjadi Cancel !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function GetDataDetailReturn()
    {
        $sysid = $this->input->post('sysid');
        $state = $this->input->post('state');

        // GET DATA Purchase Return
        $this->db->where('SysId', $sysid);
        $this->db->from($this->ttrx_hdr_purchase_return);

        $data_pr = $this->db->get()->row();
        // GET DATA Purchase Return - END

        if ($data_pr->Approval_Status == 1 && $state == 'EDIT') {
            $response = [
                "code" => 500,
                "msg"  => "Data Tidak Bisa Diubah Karena Sudah Approve",
            ];
        } else {
            $this->db->where('t1.SysId', $sysid);
            $this->db->select('t1.*, t2.Account_Name, t4.Address AS Vendor_Address');
            $this->db->from($this->ttrx_hdr_purchase_return . ' as t1');
            $this->db->join($this->tmst_account . ' as t2', 't1.Account_ID = t2.SysId', 'left');
            $this->db->join($this->ttrx_hdr_po . ' as t3', 't1.PO_Number = t3.Doc_No');
            $this->db->join($this->tmst_account_address . ' as t4', 't3.SysId_Address = t4.SysId');

            $data_hdr = $this->db->get()->row();

            $this->db->where('t1.PR_Number', $data_pr->PR_Number);
            $this->db->select('t1.*, t3.Qty AS Qty_RR, t3.Uom');
            $this->db->from($this->ttrx_dtl_purchase_return . ' as t1');
            $this->db->join($this->ttrx_hdr_purchase_return . ' as t2', 't1.PR_Number = t2.PR_Number');
            $this->db->join($this->ttrx_dtl_pur_receive_item . ' as t3', 't2.RR_Number = t3.RR_Number AND t1.Item_Code = t3.Item_Code');

            $data_dtl = $this->db->get()->result();

            // die("<pre>" . print_r($data_dtl, true) . "</pre>");

            $response = [
                "code"      => 200,
                "msg"       => "Berhasil Mendapatkan Data !",
                "data_hdr"  => $data_hdr,
                "data_dtl"  => $data_dtl,
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    // ----------------------- APPROVAL --------------------- //
    public function approval()
    {
        $this->data['page_title']   = "Approval Purchase Return";
        $this->data['page_content'] = "Purchase/PurchaseReturn/approval";
        $this->data['script_page']  =  '<script src="' . base_url() . 'assets/purchase-assets/purchase-return/js/approval.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function DT_listdata_approval()
    {
        $query  = "SELECT t1.*, t2.Account_Name FROM $this->ttrx_hdr_purchase_return AS t1 
                LEFT JOIN $this->tmst_account AS t2 ON t1.Account_ID = t2.SysId";

        $search = array('PR_Number', 'PR_Date', 'RR_Number', 'Account_Name');
        $where  = array('Approval_Status' => 0);

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function verify()
    {
        $pr_number   = $this->input->post('pr_number');
        $rr_number   = $this->input->post('rr_number');
        $is_verified = $this->input->post('is_verified');


        $this->db->trans_start();
        if ($is_verified == 2) {
            $this->db->where('PR_Number', $pr_number);
            $this->db->update($this->ttrx_hdr_purchase_return, [
                'Approval_Status'  => 2,
                'Approve_Date'      => $this->DateTime
            ]);

            $response = [
                "code" => 200,
                "msg" => "Data telah di riject !"
            ];
        } else {
            $this->db->where('PR_Number', $pr_number);
            $this->db->from($this->ttrx_dtl_purchase_return);

            $data_dtl = $this->db->get()->result();

            foreach ($data_dtl as $key => $val) {
                $this->db->where('RR_Number', $rr_number);
                $this->db->where('Item_Code', $val->Item_Code);
                $this->db->update($this->ttrx_dtl_pur_receive_item, [
                    'PR_Number' => $pr_number,
                    'return_qty'    => $val->Qty,
                ]);
            }

            $this->db->where('PR_Number', $pr_number);
            $this->db->update($this->ttrx_hdr_purchase_return, [
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
    // ----------------------- APPROVAL - END --------------------- //

}
