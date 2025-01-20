<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PurchaseOrder extends CI_Controller
{
    public $layout = 'layout';
    protected $ttrx_hdr_po          = 'ttrx_hdr_purchase_order';
    protected $ttrx_dtl_po          = 'ttrx_dtl_purchase_order';
    protected $ttrx_app_price       = 'ttrx_price_approved';
    protected $tmst_account         = 'tmst_account';
    protected $tmst_cost_center     = 'tmst_cost_center';
    protected $tmst_account_address = 'tmst_account_address';
    protected $tmst_account_contact = 'tmst_account_contact';
    protected $tmst_item_category   = 'tmst_item_category';
    protected $tmst_tax             = 'tmst_tax';
    protected $qmst_item            = 'qmst_item';
    protected $qview_price_approved = 'qview_price_approved';
    protected $tmst_currency        = 'tmst_currency';
    protected $tmst_unit            = 'Unit_Type_ID'; // UOM
    protected $qview_item_os_po_vs_rr_no_check = 'qview_item_os_po_vs_rr_no_check'; // UOM

    protected $qview_item_outstanding_po    = 'qview_item_outstanding_po_vs_rr';
    protected $ttrx_hdr_pur_receive_item    = 'ttrx_hdr_pur_receive_item';
    protected $ttrx_dtl_pur_receive_item    = 'ttrx_dtl_pur_receive_item';

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
        $this->data['page_title'] = "List Purchase Order";
        $this->data['page_content'] = "Purchase/PurchaseOrder/list";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/purchase-assets/purchase-order/js/list.js?v=' . time() . '"></script>';

        $this->data['List_Vendor'] = $this->db->select('t1.SysId, t1.Account_Code, t1.Account_Name')
            ->from($this->tmst_account . ' t1')
            ->join($this->tmst_account_address . ' t2', 't1.Account_Code = t2.Account_Code')
            ->join($this->qview_price_approved . ' t3', 't1.Account_Code = t3.Account_Code')
            ->where('t1.Category_ID', 'VP')
            ->where('t1.Is_Active', 1)
            ->group_by('t1.SysId', 't1.Account_Code', 't1.Account_Name')
            ->get();

        $this->data['List_Currency'] = $this->db
            ->where('Status', 1)
            ->order_by('Is_Default', 'DESC')
            ->get($this->tmst_currency);

        $this->load->view($this->layout, $this->data);
    }

    public function store()
    {
        $state         = $this->input->post('state');
        $sysid         = $this->input->post('sysid');

        // $doc_no         = $this->input->post('doc_no');
        $doc_date       = $this->input->post('doc_date');
        $person         = $this->input->post('person_id');
        $currency       = $this->input->post('currency');
        $rate           = $this->input->post('rate');
        $isImport       = $this->input->post('isImport');
        $isAsset        = $this->input->post('isAsset');
        $vendor_id      = $this->input->post('vendor_id');
        $address_id     = $this->input->post('vendor_address_id');
        $eta            = $this->input->post('eta');
        $etd            = $this->input->post('etd');
        $notes          = $this->input->post('notes');
        $custom_field_1 = $this->input->post('custom_field_1');
        $custom_field_2 = $this->input->post('custom_field_2');
        $custom_field_3 = $this->input->post('custom_field_3');

        $sysid_dtl          = $this->input->post('sysid_dtl');
        $sysid_item         = $this->input->post('sysid_item');
        $item_code          = $this->input->post('item_code');
        $item_name          = $this->input->post('item_name');
        $uom                = $this->input->post('uom');
        $costcenter         = $this->input->post('costcenter');
        $qty                = $this->input->post('qty');
        $discount_percent   = $this->input->post('discount_percent');
        $value_discount     = $this->input->post('value_discount');
        $type_tax1          = $this->input->post('type_tax1');
        $value_tax1         = $this->input->post('value_tax1');
        $type_tax2          = $this->input->post('type_tax2');
        $value_tax2         = $this->input->post('value_tax2');
        $unit_price         = $this->input->post('unit_price');
        $base_unit_price    = $this->input->post('base_unit_price');
        $total_price        = $this->input->post('total_price');
        $total_base_price   = $this->input->post('total_base_price');
        $remarks            = $this->input->post('remarks');

        $percent_discount_all   = $this->input->post('percent_discount_all');
        $total_tax_1            = $this->input->post('total_tax_1');
        $total_tax_2            = $this->input->post('total_tax_2');
        $grand_total            = $this->input->post('grand_total');

        $base_amount = $this->help->float_to_value($grand_total) * $this->help->float_to_value($rate);
        if ($state == 'ADD') {
            $po_number = $this->help->Gnrt_Identity_Number_PO("PO");
        }

        $this->db->trans_start();
        if ($state == 'ADD') {

            $this->db->insert($this->ttrx_hdr_po, [
                'SysId_Vendor'      => $vendor_id,
                'SysId_Address'     => $address_id,
                'SysId_Person'      => $person,
                'Doc_No'            => $po_number,
                'Doc_Rev'           => 0,
                'Doc_Date'          => date('Y-m-d', strtotime($doc_date)),
                'ETA'               => date('Y-m-d', strtotime($eta)),
                'ETD'               => date('Y-m-d', strtotime($etd)),
                'Discount'          => $this->help->float_to_value($percent_discount_all),
                'Currency'          => $currency,
                'Rate'              => $this->help->float_to_value($rate),
                'Value_Tax_1'       => $this->help->float_to_value($total_tax_1),
                'Value_Tax_2'       => $this->help->float_to_value($total_tax_2),
                'Amount'            => $this->help->float_to_value($grand_total),
                'Base_Amount'       => $base_amount,
                'Approve'           => 0,
                'IsImport'          => $isImport,
                'IsAsset'           => $isAsset,
                'IsClose'           => 0,
                'Note'              => $notes,
                'Custom_Field_1'    => $custom_field_1,
                'Custom_Field_2'    => $custom_field_2,
                'Custom_Field_3'    => $custom_field_3,
                'ip_create'         => $this->help->get_client_ip(),
                'Rec_UserId'        => $this->session->userdata('impsys_nik'),
                'Rec_LastDateTime'  => $this->DateTime,
            ]);

            // Dapatkan SysId yang telah insert header
            $header_sysid = $this->db->insert_id();

            for ($i = 0; $i < count($item_code); $i++) {
                $this->db->insert($this->ttrx_dtl_po, [
                    'SysId_Hdr'         => $header_sysid,
                    'Doc_No_Hdr'        => $po_number,
                    'SysId_Item'        => $sysid_item[$i],
                    'Item_Code'         => $item_code[$i],
                    'Item_Name'         => $item_name[$i],
                    'Uom'               => $uom[$i],
                    'Qty'               => $qty[$i],
                    'Discount'          => $this->help->float_to_value($discount_percent[$i]),
                    'type_tax_1'        => $type_tax1[$i] ? $type_tax1[$i] : NULL,
                    'type_tax_2'        => $type_tax2[$i] ? $type_tax2[$i] : NULL,
                    'value_tax_1'       => $type_tax1[$i] ? $this->help->float_to_value($value_tax1[$i]) : 0,
                    'value_tax_2'       => $type_tax2[$i] ? $this->help->float_to_value($value_tax2[$i]) : 0,
                    'CostCenter_ID'     => $costcenter[$i],
                    'Remark'            => $remarks[$i],
                    'Unit_Price'        => $this->help->float_to_value($unit_price[$i]),
                    'Base_UnitPrice'    => $this->help->float_to_value($base_unit_price[$i]),
                    'Total_Price'       => $this->help->float_to_value($total_price[$i]),
                    'Base_TotalPrice'   => $this->help->float_to_value($total_base_price[$i]),
                    'Rec_UserId'        => $this->session->userdata('impsys_nik'),
                    'Rec_LastDateTime'  => $this->DateTime,
                ]);
            }
        } else if ($state == 'EDIT') {
            $this->db->where('sysid', $sysid);
            $this->db->update($this->ttrx_hdr_po, [
                'SysId_Person'          => $person,
                'SysId_Address'         => $address_id,
                'Doc_Date'              => date('Y-m-d', strtotime($doc_date)),
                'ETA'                   => date('Y-m-d', strtotime($eta)),
                'ETD'                   => date('Y-m-d', strtotime($etd)),
                'Discount'              => $this->help->float_to_value($percent_discount_all),
                'Currency'              => $currency,
                'Rate'                  => $this->help->float_to_value($rate),
                'Value_Tax_1'           => $this->help->float_to_value($total_tax_1),
                'Value_Tax_2'           => $this->help->float_to_value($total_tax_2),
                'Amount'                => $this->help->float_to_value($grand_total),
                'Base_Amount'           => $base_amount,
                'IsImport'              => $isImport,
                'IsAsset'               => $isAsset,
                'Note'                  => $notes,
                'Custom_Field_1'        => $custom_field_1,
                'Custom_Field_2'        => $custom_field_2,
                'Custom_Field_3'        => $custom_field_3,
                'Rec_Update_UserId'     => $this->session->userdata('impsys_nik'),
                'Rec_Update_DateTime'   => $this->DateTime,
            ]);
            
            $this->db->delete($this->ttrx_dtl_po, [
                'SysId_Hdr' => $sysid
            ]);

            // GET DATA PO
            $this->db->where('sysid', $sysid);
            $this->db->from($this->ttrx_hdr_po);

            $data_po = $this->db->get()->row();
            // GET DATA PO - END

            for ($i = 0; $i < count($item_code); $i++) {
                $this->db->insert($this->ttrx_dtl_po, [
                    'SysId_Hdr'         => $sysid,
                    'Doc_No_Hdr'        => $data_po->Doc_No,
                    'SysId_Item'        => $sysid_item[$i],
                    'Item_Code'         => $item_code[$i],
                    'Item_Name'         => $item_name[$i],
                    'Uom'               => $uom[$i],
                    'Qty'               => $qty[$i],
                    'Discount'          => $this->help->float_to_value($discount_percent[$i]),
                    'type_tax_1'        => $type_tax1[$i] ? $type_tax1[$i] : NULL,
                    'type_tax_2'        => $type_tax2[$i] ? $type_tax2[$i] : NULL,
                    'value_tax_1'       => $type_tax1[$i] ? $this->help->float_to_value($value_tax1[$i]) : 0,
                    'value_tax_2'       => $type_tax2[$i] ? $this->help->float_to_value($value_tax2[$i]) : 0,
                    'CostCenter_ID'     => $costcenter[$i],
                    'Remark'            => $remarks[$i],
                    'Unit_Price'        => $this->help->float_to_value($unit_price[$i]),
                    'Base_UnitPrice'    => $this->help->float_to_value($base_unit_price[$i]),
                    'Total_Price'       => $this->help->float_to_value($total_price[$i]),
                    'Base_TotalPrice'   => $this->help->float_to_value($total_base_price[$i]),
                    'Rec_UserId'        => $this->session->userdata('impsys_nik'),
                    'Rec_LastDateTime'  => $this->DateTime,
                ]);
            }
        } else {
            // GET DATA PO
            $this->db->where('sysid', $sysid);
            $this->db->from($this->ttrx_hdr_po);

            $data_po = $this->db->get()->row();
            // GET DATA PO - END

            $doc_rev = $data_po->Doc_Rev;
            if ($state == 'REVISI') {
                $doc_rev = $data_po->Doc_Rev + 1;
            }

            $this->db->where('sysid', $sysid);
            $this->db->update($this->ttrx_hdr_po, [
                'Doc_Rev'               => $doc_rev,
                'Value_Tax_1'           => $this->help->float_to_value($total_tax_1),
                'Value_Tax_2'           => $this->help->float_to_value($total_tax_2),
                'Amount'                => $this->help->float_to_value($grand_total),
                'Base_Amount'           => $base_amount,
                
                'ETA'                   => date('Y-m-d', strtotime($eta)),
                'ETD'                   => date('Y-m-d', strtotime($etd)),
                'Note'                  => $notes,
                'Custom_Field_1'        => $custom_field_1,
                'Custom_Field_2'        => $custom_field_2,
                'Custom_Field_3'        => $custom_field_3,

                'Rec_Update_UserId'     => $this->session->userdata('impsys_nik'),
                'Rec_Update_DateTime'   => $this->DateTime,
            ]);
            
            $this->db->delete($this->ttrx_dtl_po, [
                'SysId_Hdr' => $sysid
            ]);

            for ($i = 0; $i < count($item_code); $i++) {
                $this->db->insert($this->ttrx_dtl_po, [
                    'SysId_Hdr'         => $sysid,
                    'Doc_No_Hdr'        => $data_po->Doc_No,
                    'SysId_Item'        => $sysid_item[$i],
                    'Item_Code'         => $item_code[$i],
                    'Item_Name'         => $item_name[$i],
                    'Uom'               => $uom[$i],
                    'Qty'               => $qty[$i],
                    'Discount'          => $this->help->float_to_value($discount_percent[$i]),
                    'type_tax_1'        => $type_tax1[$i] ? $type_tax1[$i] : NULL,
                    'type_tax_2'        => $type_tax2[$i] ? $type_tax2[$i] : NULL,
                    'value_tax_1'       => $type_tax1[$i] ? $this->help->float_to_value($value_tax1[$i]) : 0,
                    'value_tax_2'       => $type_tax2[$i] ? $this->help->float_to_value($value_tax2[$i]) : 0,
                    'CostCenter_ID'     => $costcenter[$i],
                    'Remark'            => $remarks[$i],
                    'Unit_Price'        => $this->help->float_to_value($unit_price[$i]),
                    'Base_UnitPrice'    => $this->help->float_to_value($base_unit_price[$i]),
                    'Total_Price'       => $this->help->float_to_value($total_price[$i]),
                    'Base_TotalPrice'   => $this->help->float_to_value($total_base_price[$i]),
                    'Rec_UserId'        => $this->session->userdata('impsys_nik'),
                    'Rec_LastDateTime'  => $this->DateTime,
                ]);
            }

            // for ($i = 0; $i < count($sysid_dtl); $i++) {
            //     $this->db->where('SysId', $sysid_dtl[$i]);
            //     $this->db->update($this->ttrx_dtl_po, [
            //         'Discount'          => $this->help->float_to_value($discount_percent[$i]),
            //         'type_tax_1'        => $type_tax1[$i] ? $type_tax1[$i] : NULL,
            //         'type_tax_2'        => $type_tax2[$i] ? $type_tax2[$i] : NULL,
            //         'value_tax_1'       => $type_tax1[$i] ? $this->help->float_to_value($value_tax1[$i]) : 0,
            //         'value_tax_2'       => $type_tax2[$i] ? $this->help->float_to_value($value_tax2[$i]) : 0,
            //         'Unit_Price'        => $this->help->float_to_value($unit_price[$i]),
            //         'Base_UnitPrice'    => $this->help->float_to_value($base_unit_price[$i]),
            //         'Total_Price'       => $this->help->float_to_value($total_price[$i]),
            //         'Base_TotalPrice'   => $this->help->float_to_value($total_base_price[$i]),
            //         'Rec_UserUpdateId'        => $this->session->userdata('impsys_nik'),
            //         'Rec_LastDateTimeUpdate'  => $this->DateTime,
            //     ]);
            // }
        }

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
                "msg" => "Berhasil Menyimpan PO !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function GetDataEditRevisi()
    {
        $sysid = $this->input->post('sysid');
        $state = $this->input->post('state');

        // GET DATA PO
        $this->db->where('SysId', $sysid);
        $this->db->from($this->ttrx_hdr_po);

        $data_po = $this->db->get()->row();
        // GET DATA PO - END

        if ($data_po->Approve == 1 && $state == 'EDIT') {
            $response = [
                "code" => 500,
                "msg"  => "Data Tidak Bisa Diubah Karena Sudah Approve",
            ];
        } else if ($data_po->Approve != 1 && $state == 'REVISI') {
            $response = [
                "code" => 500,
                "msg"  => "Status Data PO harus sudah Approve",
            ];
        } else {
            $this->db->where('t1.SysId', $sysid);
            $this->db->select('t1.*, t2.Account_Name, t3.Address, t4.Contact_Name');
            $this->db->from($this->ttrx_hdr_po . ' as t1');
            $this->db->join($this->tmst_account . ' as t2', 't1.SysId_Vendor = t2.SysId', 'left');
            $this->db->join($this->tmst_account_address . ' as t3', 't1.SysId_Address = t3.SysId', 'left');
            $this->db->join($this->tmst_account_contact . ' as t4', 't1.SysId_Person = t4.Sysid', 'left');

            $data_hdr = $this->db->get()->row();

            if ($state == 'EDIT') {
                $this->db->where('t1.SysId_Hdr', $sysid);
                $this->db->select('t1.*, t2.SysId AS SysId_Cost_Center, t2.nama_cost_center as Nama_Cost_Center');
                $this->db->from($this->ttrx_dtl_po . ' as t1');
                $this->db->join($this->tmst_cost_center . ' as t2', 't1.CostCenter_ID = t2.SysId', 'left');
            } else {
                $this->db->where('t1.SysId_Hdr', $sysid);
                $this->db->select('t1.*, t2.SysId AS SysId_Cost_Center, t2.nama_cost_center as Nama_Cost_Center, t3.Total_Qty_RR');
                $this->db->from($this->ttrx_dtl_po . ' as t1');
                $this->db->join($this->tmst_cost_center . ' as t2', 't1.CostCenter_ID = t2.SysId', 'left');
                $this->db->join($this->qview_item_os_po_vs_rr_no_check . ' as t3', 't1.Doc_No_Hdr = t3.Doc_No AND t1.Item_Code = t3.Item_Code');
            }

            $data_dtl = $this->db->get()->result();

            // echo "<pre>";
            // print_r($data_dtl);
            // echo "</pre>";
            // die();

            $response = [
                "code"      => 200,
                "msg"       => "Berhasil Mendapatkan Data !",
                "data_hdr"  => $data_hdr,
                "data_dtl"  => $data_dtl,
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function Toggle_Status_Close()
    {
        $sysid = $this->input->post('sysid');

        $row = $this->db->get_where($this->ttrx_hdr_po, ['SysId' => $sysid])->row();

        if ($row->IsClose == 1) {
            $this->db->where('SysId', $sysid);
            $this->db->update($this->ttrx_hdr_po, [
                'IsClose' => 0
            ]);

            $response = [
                "code" => 200,
                "msg" => "Status PO berhasil di ubah menjadi Open !"
            ];
        } else {
            $this->db->where('SysId', $sysid);
            $this->db->update($this->ttrx_hdr_po, [
                'IsClose' => 1
            ]);

            $response = [
                "code" => 200,
                "msg" => "Status PO berhasil di ubah menjadi Close !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function approval()
    {
        $this->data['page_title']   = "Approval Purchase Order";
        $this->data['page_content'] = "Purchase/PurchaseOrder/approval";
        $this->data['script_page']  =  '<script src="' . base_url() . 'assets/purchase-assets/purchase-order/js/approval.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function detail()
    {
        $sysid = $this->input->post('sysid');

        $this->db->where('t1.SysId', $sysid);
        $this->db->select('t1.*, t2.Account_Name, t3.Address, t4.Contact_Name');
        $this->db->from($this->ttrx_hdr_po . ' as t1');
        $this->db->join($this->tmst_account . ' as t2', 't1.SysId_Vendor = t2.SysId', 'left');
        $this->db->join($this->tmst_account_address . ' as t3', 't1.SysId_Address = t3.SysId', 'left');
        $this->db->join($this->tmst_account_contact . ' as t4', 't1.SysId_Person = t4.Sysid', 'left');

        $data_hdr = $this->db->get()->row();

        $this->db->where('t1.SysId_Hdr', $sysid);
        $this->db->select('t1.*, t2.SysId AS SysId_Cost_Center, t2.nama_cost_center as Nama_Cost_Center, t3.Tax_Code AS Tax_Code1, t3.Tax_Name AS Tax_Name1, t4.Tax_Code AS Tax_Code2, t4.Tax_Name AS Tax_Name2');
        $this->db->from($this->ttrx_dtl_po . ' as t1');
        $this->db->join($this->tmst_cost_center . ' as t2', 't1.CostCenter_ID = t2.SysId', 'left');
        $this->db->join($this->tmst_tax . ' as t3', 't1.type_tax_1 = t3.Tax_Id', 'left');
        $this->db->join($this->tmst_tax . ' as t4', 't1.type_tax_2 = t4.Tax_Id', 'left');

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
            $this->db->update($this->ttrx_hdr_po, [
                'Approve'       => 2,
                'Approve_Date'  => $this->DateTime
            ]);

            $response = [
                "code" => 200,
                "msg" => "Data telah di riject !"
            ];
        } else {
            $this->db->where('SysId', $sysid);
            $this->db->update($this->ttrx_hdr_po, [
                'Approve'   => 1,
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

    public function DT_listdata_VendorAddress()
    {
        $vendor_id = $this->input->post('vendor_id');

        // GET DATA VENDOR
        $this->db->where('SysId', $vendor_id);
        $this->db->from($this->tmst_account);

        $data_vendor = $this->db->get()->row();
        // GET DATA VENDOR - END

        $query  = "SELECT * FROM $this->tmst_account_address";

        $search = array('Sysid', 'Address', 'Area', 'Description');
        $where  = array('Account_Code' => $data_vendor->Account_Code);

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_listdata_Person()
    {
        $vendor_id = $this->input->post('vendor_id');

        // GET DATA VENDOR
        $this->db->where('SysId', $vendor_id);
        $this->db->from($this->tmst_account);

        $data_vendor = $this->db->get()->row();
        // GET DATA VENDOR - END

        $query  = "SELECT * FROM $this->tmst_account_contact";

        $search = array('SysId', 'Contact_Name', 'Contact_Initial_Name', 'Job_title');
        $where  = array('Account_Code' => $data_vendor->Account_Code);

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }
    // ------------------------ Data Table Section

    public function DT_listdata()
    {
        $query  = "SELECT t1.*, t2.Account_Name, t3.Address FROM $this->ttrx_hdr_po AS t1 
                    LEFT JOIN $this->tmst_account AS t2 ON t1.SysId_Vendor = t2.SysId
                    LEFT JOIN $this->tmst_account_address AS t3 ON t1.SysId_Address = t3.SysId";

        $search = array('Doc_No', 'Doc_Date', 'ETA');
        // $where  = array('nama_kategori' => 'Tutorial');
        $where  = null;

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_modallistpriceitem()
    {
        $sysid_items    = $this->input->get('sysid_items');
        $vendor_id      = $this->input->get('vendor_id');
        $isasset        = $this->input->get('isasset');

        $query  = "select * from $this->qview_price_approved";
        $search = array('Item_Code', 'Item_Name');
        $where  = array('SysId NOT IN ' => explode(',', $sysid_items), 'Account_ID' => $vendor_id);

        if ($isasset == 1) {
            $where['Item_Category'] = 'Assets';
        } else {
            $where['Item_Category NOT IN'] = array('Assets');
        }

        // print_r($where);
        // die();
        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_listdata_approval()
    {
        $query  = "SELECT t1.*, t2.Account_Name, t3.Address FROM $this->ttrx_hdr_po AS t1 
                    LEFT JOIN $this->tmst_account AS t2 ON t1.SysId_Vendor = t2.SysId
                    LEFT JOIN $this->tmst_account_address AS t3 ON t1.SysId_Address = t3.SysId";

        $search = array('Doc_No', 'Doc_Date', 'ETA');
        $where  = array('IsClose' => 0, 'Approve' => 0);

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function getSelect()
    {
        $this->db->where('Is_Active', 1);
        $this->db->order_by('kode_cost_center', 'ASC');
        $query_cost_center = $this->db->get($this->tmst_cost_center);

        $this->db->where('Is_Active', 1);
        $this->db->where('ForPurchase', 1);
        $this->db->order_by('Tax_Id', 'ASC');
        $query_tax = $this->db->get($this->tmst_tax);

        $response = [
            "code"        => 200,
            "msg"         => "Berhasil Mendapatkan Data !",
            "tax"         => $query_tax->result(),
            "cost_center" => $query_cost_center->result(),
        ];

        return $this->help->Fn_resulting_response($response);
    }

    // --------------------------- OUTSTANDING ------------------------ //
    public function outstanding()
    {
        $this->data['page_title']   = "Outstanding Purchase Order";
        $this->data['page_content'] = "Purchase/PurchaseOrder/outstanding";
        $this->data['script_page']  =  '<script src="' . base_url() . 'assets/purchase-assets/purchase-order/js/outstanding.js?v=' . time() . '"></script>';
        // $this->data['script_page']  =  '';

        $this->load->view($this->layout, $this->data);
    }

    public function DT_listdata_outstanding()
    {
        $query  = "SELECT t1.*, t2.Account_Name FROM $this->qview_item_outstanding_po AS t1 
                    LEFT JOIN $this->tmst_account AS t2 ON t1.SysId_Vendor = t2.SysId";

        $search = array('Doc_No', 'Item_Code', 'Item_name');
        $where  = [];
        $iswhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $iswhere);
    }

    public function DT_listdata_detail_os()
    {
        $po_number = $this->input->post('po_no');
        $item_code = $this->input->post('item_code');

        $query  = "SELECT t1.*, t2.PO_Number, t2.RR_Date, t3.Unit_Price, t2.Approval_Status FROM $this->ttrx_dtl_pur_receive_item AS t1 
                    JOIN $this->ttrx_hdr_pur_receive_item AS t2 ON t1.RR_Number = t2.RR_Number
                    JOIN $this->qview_item_outstanding_po AS t3 ON t2.PO_Number = t3.Doc_No AND t1.Item_Code = t3.Item_Code";

        $search = array('t1.RR_Number', 't2.RR_Date', 't1.Qty');
        $where  = array('t2.PO_Number' => $po_number, 't1.Item_Code' => $item_code, 't2.Approval_Status' => '< 2');
        // $where = null;
        $iswhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $iswhere);
    }

    public function detailOutstanding()
    {
        $sysid = $this->input->post('sysid');

        $this->db->where('t1.SysId', $sysid);
        $this->db->select('t1.*, t3.RR_Number, t3.RR_Date');
        $this->db->from($this->qview_item_outstanding_po . ' as t1');
        $this->db->join($this->ttrx_dtl_pur_receive_item . ' as t2', 't1.Item_Code = t2.Item_Code');
        $this->db->join($this->ttrx_hdr_pur_receive_item . ' as t3', 't2.RR_Number = t2.RR_Number');

        $data = $this->db->get()->row();

        $response = [
            "code"      => 200,
            "msg"       => "Berhasil Mendapatkan Data !",
            "data"  => $data,
        ];

        return $this->help->Fn_resulting_response($response);
    }
    // ----------------------- OUTSTANDING - END ------------------------ //

    // ----------------------- GENERATE DOMPDF - START ------------------------ //
    function export_pdf_po($sysid)
    {
        // ----------------- GET DATA -------------- //
        // $sysid = $this->input->post('sysid');
        // $sysid = 9;

        $this->db->where('t1.SysId', $sysid);
        $this->db->select('t1.*, t2.Account_Name, t3.Address, t4.Contact_Name');
        $this->db->from($this->ttrx_hdr_po . ' as t1');
        $this->db->join($this->tmst_account . ' as t2', 't1.SysId_Vendor = t2.SysId', 'left');
        $this->db->join($this->tmst_account_address . ' as t3', 't1.SysId_Address = t3.SysId', 'left');
        $this->db->join($this->tmst_account_contact . ' as t4', 't1.SysId_Person = t4.Sysid', 'left');

        $data_hdr = $this->db->get()->row();

        $this->db->where('t1.SysId_Hdr', $sysid);
        $this->db->select('t1.*, t2.SysId AS SysId_Cost_Center, t2.nama_cost_center as Nama_Cost_Center, t3.Tax_Code AS Tax_Code1, t3.Tax_Name AS Tax_Name1, t4.Tax_Code AS Tax_Code2, t4.Tax_Name AS Tax_Name2');
        $this->db->from($this->ttrx_dtl_po . ' as t1');
        $this->db->join($this->tmst_cost_center . ' as t2', 't1.CostCenter_ID = t2.SysId', 'left');
        $this->db->join($this->tmst_tax . ' as t3', 't1.type_tax_1 = t3.Tax_Id', 'left');
        $this->db->join($this->tmst_tax . ' as t4', 't1.type_tax_2 = t4.Tax_Id', 'left');

        $data_dtl = $this->db->get()->result();
        // ----------------- GET DATA - END -------------- //

        $this->load->library('pdfgenerator');

        // $data['title'] = "Data Random";
        $data = [
            'data_hdr' => $data_hdr,
            'data_dtl' => $data_dtl
        ];
        $name_file = $data_hdr->Doc_No;
        $paper = 'A4';
        $orientation = "portrait";
        $html = $this->load->view('Purchase/PurchaseOrder/export/pdf-purchase-order', $data, true);

        $this->pdfgenerator->generate($html, $name_file, $paper, $orientation);
    }

    function generatePDF()
    {
        $this->load->library('pdfgenerator');
        $data['title'] = "Data Random";
        $file_pdf = $data['title'];
        $paper = 'A4';
        $orientation = "portrait";
        $html = $this->load->view('Purchase/PurchaseOrder/template-pdf', $data, true);
        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }
    // ----------------------- GENERATE DOMPDF - END ------------------------ //
}
