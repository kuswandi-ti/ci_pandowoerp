<?php
defined('BASEPATH') or exit('No direct script access allowed');

class VendorPrice extends CI_Controller
{
    public    $layout               = 'layout';
    protected $ttrx_hdr_vpr         = 'ttrx_hdr_vendor_price';
    protected $ttrx_dtl_vpr         = 'ttrx_dtl_vendor_price';
    protected $ttrx_app_price       = 'ttrx_price_approved';
    protected $tmst_account         = 'tmst_account';
    protected $tmst_item_category   = 'tmst_item_category';
    protected $qmst_item            = 'qmst_item';
    protected $tmst_currency        = 'tmst_currency';
    protected $qview_price_approved = 'qview_price_approved';
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
        $this->data['page_title'] = "List Vendor Price";
        $this->data['page_content'] = "Purchase/VendorPrice/list";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/purchase-assets/vendor-price/js/list.js?v=' . time() . '"></script>';

        $this->data['List_Vendor'] = $this->db
            // ->where('Category_ID', 'VP') di disable karna kebutuhan sales return
            ->where('Is_Active', 1)
            ->get($this->tmst_account);

        $this->data['List_Item_Category'] = $this->db->get($this->tmst_item_category);

        $this->load->view($this->layout, $this->data);
    }

    public function store()
    {
        $state         = $this->input->post('state');
        $sysid         = $this->input->post('sysid');

        $doc_no         = $this->input->post('doc_no');
        $vpr_date       = $this->input->post('vpr_date');
        $vendor         = $this->input->post('vendor');
        $item_category  = $this->input->post('item_category');
        $notes          = $this->input->post('notes');

        $item_code      = $this->input->post('item_code');
        $item_type      = $this->input->post('Item_Type');
        $price          = $this->input->post('price');
        $currency       = $this->input->post('currency');
        $effective_date = $this->input->post('effective_date');

        if ($state == 'ADD') {
            $vpr_number = $this->help->Gnrt_Identity_Number('VPR');
        }

        $this->db->trans_start();
        if ($state == 'ADD') {
            $this->db->insert($this->ttrx_hdr_vpr, [
                'VPR_NUMBER'        => $vpr_number,
                'VPR_DATE'          => date('Y-m-d', strtotime($vpr_date)),
                'VPR_NOTES'         => $notes,
                'ACCOUNT_ID'        => $vendor,
                'ITEM_CATEGORY_ID'  => $item_category,
                'VPR_STATUS'        => 1,
                'APPROVAL_STATUS'   => 0,
                'ip_create'         => $this->help->get_client_ip(),
                'USER_ADD'          => $this->session->userdata('impsys_nik'),
                'DATE_ADD'          => $this->DateTime,
            ]);

            for ($i = 0; $i < count($item_code); $i++) {
                $this->db->insert($this->ttrx_dtl_vpr, [
                    'VPR_NUMBER'    => $vpr_number,
                    'ITEM_CODE'     => $item_code[$i],
                    'PRICE'         => $this->help->float_to_value($price[$i]),
                    'ITEM_TYPE'     => $item_type[$i],
                    'EFFECTIVE_DATE' => date('Y-m-d', strtotime($effective_date[$i])),
                    'CURRENCY_ID'   => $currency[$i],
                ]);
            }
        } else {
            $this->db->where('VPR_NUMBER', $doc_no);
            $this->db->update($this->ttrx_hdr_vpr, [
                'VPR_DATE'          => date('Y-m-d', strtotime($vpr_date)),
                'VPR_NOTES'         => $notes,
                'ACCOUNT_ID'        => $vendor,
                'ITEM_CATEGORY_ID'  => $item_category,
                'USER_UPDATE'       => $this->session->userdata('impsys_nik'),
                'DATE_UPDATE'       => $this->DateTime,
            ]);

            $this->db->delete($this->ttrx_dtl_vpr, [
                'VPR_NUMBER'   => $doc_no
            ]);

            for ($i = 0; $i < count($item_code); $i++) {
                $this->db->insert($this->ttrx_dtl_vpr, [
                    'VPR_NUMBER'    => $doc_no,
                    'ITEM_CODE'     => $item_code[$i],
                    'PRICE'         => $this->help->float_to_value($price[$i]),
                    'ITEM_TYPE'     => $item_type[$i],
                    'EFFECTIVE_DATE' => date('Y-m-d', strtotime($effective_date[$i])),
                    'CURRENCY_ID'   => $currency[$i],
                ]);
            }
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
                "msg" => "Berhasil Menyimpan VPR !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function edit()
    {
        $vpr_number = $this->input->post('vpr_number');
        $state      = $this->input->post('state');

        // GET DATA vpr
        $this->db->where('VPR_NUMBER', $vpr_number);
        $this->db->from($this->ttrx_hdr_vpr);

        $data_vpr = $this->db->get()->row();
        // GET DATA vpr - END

        if ($data_vpr->APPROVAL_STATUS != 0 && $state == 'EDIT') {
            $response = [
                "code" => 500,
                "msg"  => "Data Tidak Bisa Diubah Karena Sudah Approve/Reject",
            ];
        } else {
            $this->db->where('t1.VPR_NUMBER', $vpr_number);
            $this->db->select('t1.*, t2.Account_Name, t2.Account_Code, t3.Item_Category, t3.Item_Category_Init');
            $this->db->from($this->ttrx_hdr_vpr . ' as t1');
            $this->db->join($this->tmst_account . ' as t2', 't1.ACCOUNT_ID = t2.SysId', 'left');
            $this->db->join($this->tmst_item_category . ' as t3', 't1.ITEM_CATEGORY_ID = t3.SysId', 'left');

            $data_hdr = $this->db->get()->row();

            $this->db->where('t1.VPR_NUMBER', $vpr_number);
            $this->db->select('t1.*, t2.SysId AS SysId_Item, t2.Item_Name, t2.Item_Category, t2.Uom');
            $this->db->from($this->ttrx_dtl_vpr . ' as t1');
            $this->db->join($this->qmst_item . ' as t2', 't1.ITEM_CODE = t2.Item_Code', 'left');
            $this->db->order_by('EFFECTIVE_DATE', 'ASC');
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

    public function Toggle_Status()
    {
        $vpr_number = $this->input->post('vpr_number');

        $row = $this->db->get_where($this->ttrx_hdr_vpr, ['VPR_NUMBER' => $vpr_number])->row();

        if ($row->APPROVAL_STATUS == 1 && $row->VPR_STATUS == 1) {
            $response = [
                "code" => 500,
                "msg" => "Tidak bisa update status is active karena VPR ini sudah di approve !"
            ];
        } else {
            if ($row->VPR_STATUS == 1) {
                $this->db->where('VPR_NUMBER', $vpr_number);
                $this->db->update($this->ttrx_hdr_vpr, [
                    'VPR_STATUS' => 0
                ]);

                $response = [
                    "code" => 200,
                    "msg" => "Data telah di non-aktifkan !"
                ];
            } else {
                $this->db->where('VPR_NUMBER', $vpr_number);
                $this->db->update($this->ttrx_hdr_vpr, [
                    'VPR_STATUS' => 1
                ]);

                $response = [
                    "code" => 200,
                    "msg" => "Data berhasil di aktifkan !"
                ];
            }
        }

        return $this->help->Fn_resulting_response($response);
    }

    // --------------------- APPROVAL ------------ //
    public function approval()
    {
        $this->data['page_title']   = "Approval Vendor Price";
        $this->data['page_content'] = "Purchase/VendorPrice/approval";
        $this->data['script_page']  =  '<script src="' . base_url() . 'assets/purchase-assets/vendor-price/js/approval.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function detail()
    {
        $vpr_number     = $this->input->post('vpr_number');

        $this->db->where('t1.VPR_NUMBER', $vpr_number);
        $this->db->select('t1.*, t2.Account_Name, t2.Account_Code, t3.Item_Category, t3.Item_Category_Init');
        $this->db->from($this->ttrx_hdr_vpr . ' as t1');
        $this->db->join($this->tmst_account . ' as t2', 't1.ACCOUNT_ID = t2.SysId', 'left');
        $this->db->join($this->tmst_item_category . ' as t3', 't1.ITEM_CATEGORY_ID = t3.SysId', 'left');
        $data_hdr = $this->db->get()->row();

        $this->db->where('t1.VPR_NUMBER', $vpr_number);
        $this->db->select('t1.*, t2.Item_Name, t2.Item_Category, t2.Uom, t3.Currency_Symbol');
        $this->db->from($this->ttrx_dtl_vpr . ' as t1');
        $this->db->join($this->qmst_item . ' as t2', 't1.ITEM_CODE = t2.Item_Code', 'left');
        $this->db->join($this->tmst_currency . ' as t3', 't1.currency_id = t3.Currency_ID', 'left');
        $this->db->order_by('EFFECTIVE_DATE', 'ASC');
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
        return $this->help->Fn_resulting_response($response);
    }

    public function verify()
    {
        $vpr_number     = $this->input->post('vpr_number');
        $is_verified    = $this->input->post('is_verified');

        $this->db->trans_start();
        if ($is_verified == 2) {
            $this->db->where('VPR_NUMBER', $vpr_number);
            $this->db->update($this->ttrx_hdr_vpr, [
                'APPROVAL_STATUS'   => 2,
                'APPROVE_DATE'      => $this->DateTime
            ]);

            $response = [
                "code" => 200,
                "msg" => "Data telah di riject !"
            ];
        } else {
            $this->db->where('t2.VPR_NUMBER', $vpr_number);
            $this->db->select('t1.*, t2.ACCOUNT_ID, t2.ITEM_CATEGORY_ID');
            $this->db->from($this->ttrx_dtl_vpr . ' as t1');
            $this->db->join($this->ttrx_hdr_vpr . ' as t2', 't1.VPR_NUMBER = t2.VPR_NUMBER', 'left');
            $data_dtl = $this->db->get();

            if ($data_dtl->num_rows() > 0) {
                // echo "<pre>";
                // print_r($data_dtl->result());
                // echo "</pre>";
                // die();
                foreach ($data_dtl->result() as $val) {
                    $cek_app_price = $this->db->get_where($this->ttrx_app_price, array(
                        "Item_code"     => $val->ITEM_CODE,
                        "Account_ID"    => $val->ACCOUNT_ID,
                    ));

                    if ($cek_app_price->num_rows() > 0) {
                        $this->db->where('Item_code', $val->ITEM_CODE, 'Account_ID', $val->ACCOUNT_ID);
                        $this->db->update($this->ttrx_app_price, [
                            'Price'          => $val->PRICE,
                            'Effective_Date' => $val->EFFECTIVE_DATE,
                            'Is_Active'      => 1,
                            'Item_Category_Init' => $val->ITEM_CATEGORY_ID,
                            'currency_id'    => $val->currency_id,
                            'VPR_Number'     => $vpr_number,
                            // ? DIMENSION_ID DAPAT DARI MANA?
                        ]);
                    } else {
                        $this->db->insert($this->ttrx_app_price, [
                            'Item_code'      => $val->ITEM_CODE,
                            'Account_ID'     => $val->ACCOUNT_ID,
                            'Price'          => $val->PRICE,
                            'Effective_Date' => $val->EFFECTIVE_DATE,
                            'Is_Active'      => 1,
                            'Item_Category_Init' => $val->ITEM_CATEGORY_ID,
                            'currency_id'    => $val->currency_id,
                            'VPR_Number'     => $vpr_number,
                        ]);
                    }
                }

                $this->db->where('VPR_NUMBER', $vpr_number);
                $this->db->update($this->ttrx_hdr_vpr, [
                    'APPROVAL_STATUS'   => 1,
                    'APPROVE_DATE'      => $this->DateTime
                ]);

                $response = [
                    "code" => 200,
                    "msg" => "Data berhasil di verifikasi !"
                ];
            } else {

                $response = [
                    "code" => 200,
                    "msg" => "Data berhasil di verifikasi !"
                ];
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function DT_listdata_approval()
    {
        $query  = "SELECT t1.*, t2.Account_Name FROM $this->ttrx_hdr_vpr AS t1 LEFT JOIN $this->tmst_account AS t2 ON t1.ACCOUNT_ID = t2.SysId";

        $search = array('VPR_NUMBER', 'VPR_DATE', 'VPR_NOTES');
        $where  = array('VPR_STATUS' => 1, 'APPROVAL_STATUS' => 0);

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    // ---------------------- - END - ---------------------- //

    // ------------------- PRICE LIST ------------------ //
    public function price_list()
    {
        $this->data['page_title']   = "Price List";
        $this->data['page_content'] = "Purchase/VendorPrice/price_list";
        $this->data['script_page']  =  '<script src="' . base_url() . 'assets/purchase-assets/vendor-price/js/price_list.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function DT_listdata_price_list()
    {
        $query  = "SELECT * FROM $this->qview_price_approved";

        $search = array('Account_Name', 'Item_Code', 'Item_Name', 'VPR_Number', 'Price', 'Effective_Date');
        $where  = null;

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    // ---------------------- - END - ---------------------- //


    // ------------------------ Data Table Section

    public function DT_listdata()
    {
        $query  = "SELECT t1.*, t2.Account_Name FROM $this->ttrx_hdr_vpr AS t1 LEFT JOIN $this->tmst_account AS t2 ON t1.ACCOUNT_ID = t2.SysId";

        $search = array('VPR_NUMBER', 'VPR_DATE', 'VPR_NOTES', 'Account_Name');
        // $where  = array('nama_kategori' => 'Tutorial');
        $where  = null;

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_modallistofitem()
    {
        $query  = "select * from $this->qmst_item";

        $sysid_items    = $this->input->get('sysid_items');
        $item_category  = $this->input->get('item_category');

        $search = array('Item_Code', 'Item_Name', 'Uom', 'Group_Name', 'Default_Currency_Id');
        $where  = array('SysId NOT IN ' => explode(',', $sysid_items), 'Category_Parent' => $item_category);

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function getCurrency()
    {
        $currencies = $this->help->getCurrencyList();

        $response = [
            "code"      => 200,
            "msg"       => "Berhasil Mendapatkan Data !",
            "currency"  => $currencies,
        ];

        return $this->help->Fn_resulting_response($response);
    }
}
