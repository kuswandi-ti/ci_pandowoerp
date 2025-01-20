<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SalesOrder extends CI_Controller
{
    public $layout = 'layout';

    // Nama tabel sebagai variabel global
    protected $tmst_currency = 'tmst_currency';
    protected $tmst_account = 'tmst_account';
    protected $tmst_item_category = 'tmst_item_category';
    protected $tmst_item_category_group = 'tmst_item_category_group';
    protected $tmst_source_item = 'tmst_source_item';
    protected $tmst_item = 'tmst_item';
    protected $tmst_unit_type = 'tmst_unit_type';
    protected $tmst_account_address = 'tmst_account_address';
    protected $ttrxhdr_sls_salesorder = 'ttrxhdr_sls_salesorder';
    protected $ttrxdtl_sls_salesorder = 'ttrxdtl_sls_salesorder';
    protected $t_stok_wh_item = 't_stok_wh_item';
    protected $qview_outstanding_so_vs_si = 'qview_outstanding_so_vs_si';
    protected $tmst_tax = 'tmst_tax';
    protected $ttrx_dtl_shipping_ins = 'ttrx_dtl_shipping_ins';
    protected $ttrx_hdr_shipping_ins = 'ttrx_hdr_shipping_ins';

    protected $Date;
    protected $DateTime;

    // Konstruktor: Menginisialisasi data awal saat controller dipanggil
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');
        $this->load->model('m_helper', 'help');
        $this->load->model('m_Tax', 'tax');
        $this->load->model('m_DataTable', 'M_Datatables');
    }

    // Fungsi index: Menampilkan halaman utama Sales Order
    public function index()
    {
        try {
            $this->data['page_title'] = "Data Sales Order";
            $this->data['page_group'] = "SO";
            $this->data['page_content'] = "Sales/SO/sales_order";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/SO/sales_order.js?v=' . time() . '"></script>';

            // Mengambil data currency
            $this->data['Currency'] = $this->db
                ->order_by('Currency_ID', 'ASC')
                ->get($this->tmst_currency);

            // Mengambil data account dengan kategori CS
            $this->data['Account_CS'] = $this->db
                ->where('Category_ID', 'CS')
                ->where('is_active', '1')
                ->get($this->tmst_account);

            // Mengambil daftar kategori item
            $this->data['List_Item_Category'] = $this->db
                ->get($this->tmst_item_category);

            // 
            $this->data['List_Tax'] = $this->db
                ->where('Is_Active', 1) // Menambahkan kondisi untuk hanya mengambil pajak yang aktif
                ->get($this->tmst_tax)
                ->result();

            // Memuat view dengan layout yang telah ditentukan
            $this->load->view($this->layout, $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data."
            ]);
        }
    }

    // Fungsi DT_listdata: Mengambil data Sales Order untuk DataTables
    public function DT_listdata()
    {
        try {
            $query  = "SELECT SysId, SO_Number, SO_Date, Customer_Name, PO_Number, FORMAT(Amount, 0) AS Amount, Currency_Symbol, Approve, SO_DeliveryDate, Is_Close FROM $this->ttrxhdr_sls_salesorder";
            $search = array('SO_Number', 'SO_Date', 'PO_Number', 'Customer_Name', 'SO_DeliveryDate');
            $where  = null;
            $isWhere = null;

            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data Sales Order."
            ]);
        }
    }

    // Fungsi store: Menyimpan data Sales Order baru atau memperbarui data yang ada
    public function store()
    {
        try {
            $state = $this->input->post('state');
            $SO_Number = ($state == 'ADD') ? $this->help->Gnrt_Identity_Monthly_Sales("SO", 5, '-') : "";

            $this->db->trans_start();

            $details = $this->input->post('details');

            // Mengambil data dari input POST
            $SO_Rev = 1;
            $SO_Date = date('Y-m-d', strtotime($this->input->post('tanggal_so')));
            $timestamp_of_SO_Date = strtotime($SO_Date);
            $Period_Month = date('m', $timestamp_of_SO_Date);
            $Period_Year = date('Y', $timestamp_of_SO_Date);
            $Customer_Id = $this->input->post('customer_id');
            $Customer_Code = $this->input->post('customer_code');
            $Customer_Name = $this->input->post('nama_customer');
            $CustomerAddress_Id = $this->input->post('alamat_customer_id');
            $Customer_Address = $this->input->post('alamat_customer');
            $Term_Of_Payment = $this->input->post('term_of_payment');
            $TOP_unit = $this->input->post('unit_top');
            $TOP_Doc = $this->input->post('dokumen_top');
            $PO_Date = date('Y-m-d', strtotime($this->input->post('tanggal_po_customer')));
            $NPWP = $this->input->post('account_npwp');
            $PO_Number = $this->input->post('nomer_po_customer');
            $CurrencyType_Id = $this->input->post('currency');
            $CurrencyType_Name = $this->input->post('currency_name');
            $Currency_Symbol = $this->input->post('currency_symbol');
            $Remarks = $this->input->post('keterangan');
            $SO_DeliveryDate = date('Y-m-d', strtotime($this->input->post('tanggal_pengiriman')));

            // Perhitungan aritmatika untuk jumlah dasar dan diskon
            $Amount = $this->help->float_to_value($this->input->post('total_amount'));
            $Discount_Persen = $this->help->float_to_value($this->input->post('discount_percentage'));
            $Currency_Rate = $this->help->float_to_value($this->input->post('rate_currency'));
            $Base_amount = $Amount * $Currency_Rate;
            $Discount_Amount = $Amount * ($Discount_Persen / 100);
            $Discount_Base_Amount = $Base_amount * ($Discount_Persen / 100);
            $Netto_Amount = $Amount - $Discount_Amount;
            $Netto_Base_Amount = $Base_amount - $Discount_Base_Amount;


            $insert_header = [
                'SO_Rev' => $SO_Rev,
                'Period_Month' => $Period_Month,
                'Period_Year' => $Period_Year,
                'Term_Of_Payment' => $Term_Of_Payment,
                'TOP_unit' => $TOP_unit,
                'TOP_Doc' => $TOP_Doc,
                'PO_Date' => $PO_Date,
                'NPWP' => $NPWP,
                'PO_Number' => $PO_Number,
                'Amount' => $Amount,
                'Base_amount' => $Base_amount,
                'Discount_Persen' => $Discount_Persen,
                'Discount_Amount' => $Discount_Amount,
                'Discount_Base_Amount' => $Discount_Base_Amount,
                'Netto_Amount' => $Netto_Amount,
                'Netto_Base_Amount' => $Netto_Base_Amount,
                'CurrencyType_Id' => $CurrencyType_Id,
                'CurrencyType_Name' => $CurrencyType_Name,
                'Currency_Symbol' => $Currency_Symbol,
                'Currency_Rate' => $Currency_Rate,
                'SO_DeliveryDate' => $SO_DeliveryDate,
                'Remarks' => $Remarks,
            ];

            if ($state == 'ADD') {
                // Menambahkan SO_Number, SO_Date, dan data Customer hanya jika state adalah ADD
                $insert_header['SO_Number'] = $SO_Number;
                $insert_header['SO_Date'] = $SO_Date;
                $insert_header['Customer_Id'] = $Customer_Id;
                $insert_header['Customer_Code'] = $Customer_Code;
                $insert_header['Customer_Name'] = $Customer_Name;
                $insert_header['CustomerAddress_Id'] = $CustomerAddress_Id;
                $insert_header['Customer_Address'] = $Customer_Address;
                // Field-field ini hanya berlaku di state ADD
                $insert_header['Approve'] = 0;
                $insert_header['Approve_By'] = NULL;
                $insert_header['Approve_At'] = NULL;
                $insert_header['Is_Close'] = '';
                $insert_header['Close_By'] = NULL;
                $insert_header['Close_At'] = NULL;
                $insert_header['Created_By'] = $this->session->userdata('impsys_nik');
                $insert_header['Created_At'] = date('Y-m-d');
                $insert_header['Last_Updated_by'] = NULL;
                $insert_header['Last_Updated_at'] = NULL;
                // Menyimpan header Sales Order

                $this->db->insert($this->ttrxhdr_sls_salesorder, $insert_header);

                // Dapatkan SysId yang telah diinsert header
                $header_sysid = $this->db->insert_id();

                if (!$header_sysid) {
                    log_message('error', 'Error inserting header: ' . $this->db->last_query());
                    $this->db->trans_rollback();
                    return $this->help->Fn_resulting_response([
                        "code" => 505,
                        "msg" => "Terjadi kesalahan saat menyimpan header."
                    ]);
                }

                // Simpan detail Sales Order
                $this->store_DT_dtl($details, $header_sysid, $Currency_Rate, $SO_DeliveryDate);
                $this->db->trans_complete();
                $msg = "Berhasil Menyimpan Data Sales Order!";
            } else {
                // Update Sales Order yang sudah ada
                $header_sysid = $this->input->post('so_sysId');
                $SO_Number = $this->input->post('nomer_so');
                $SO_Rev = intval($this->input->post('so_rev')) + 1;

                if ($state === 'REVISI') {
                    $actionMsg = "Merevisi";
                } else {
                    $actionMsg = "Mengedit";

                    $insert_header['SO_Date'] = $SO_Date;
                    $insert_header['Customer_Id'] = $Customer_Id;
                    $insert_header['Customer_Code'] = $Customer_Code;
                    $insert_header['Customer_Name'] = $Customer_Name;
                    $insert_header['CustomerAddress_Id'] = $CustomerAddress_Id;
                    $insert_header['Customer_Address'] = $Customer_Address;
                    // Last_Updated_by dan Last_Updated_at diisi dengan nilai yang sesuai saat EDIT
                    $insert_header['Last_Updated_by'] = $this->session->userdata('impsys_nik');
                    $insert_header['Last_Updated_at'] = date('Y-m-d');
                }


                $this->db->where('SysId', $header_sysid);

                $this->db->update($this->ttrxhdr_sls_salesorder, $insert_header);

                // Hapus detail lama dan simpan yang baru
                $this->db->delete($this->ttrxdtl_sls_salesorder, ['SysId_Hdr' => $header_sysid]);
                $this->store_DT_dtl($details, $header_sysid, $Currency_Rate, $SO_DeliveryDate);
                $this->db->trans_complete();
                $msg = "Berhasil $actionMsg Sales Order!";
            }

            // Berikan respons sesuai hasil transaksi
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return $this->help->Fn_resulting_response([
                    "code" => 505,
                    "msg" => "Proses penyimpanan gagal!"
                ]);
            } else {
                $this->db->trans_commit();
                return $this->help->Fn_resulting_response([
                    "code" => 200,
                    "msg" => $msg
                ]);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat menyimpan data Sales Order."
            ]);
        }
    }

    // Fungsi store_DT_dtl: Menyimpan detail Sales Order
    public function store_DT_dtl($details, $header_sysid, $Currency_Rate, $SO_DeliveryDate)
    {
        try {
            foreach ($details as $key => $detail) {
                // Perhitungan aritmatika detail
                $DTL_qty = $this->help->float_to_value($detail['detail_qty']);
                $DTL_unit_price = $this->help->float_to_value($detail['detail_unit_price']);
                $DTL_discount = $this->help->float_to_value($detail['detail_persentase_discount']);

                $Amount_Detail = $DTL_qty * $DTL_unit_price;
                $Base_Amount_Detail = $Amount_Detail * $Currency_Rate;
                $Discount_Amount = $Amount_Detail * ($DTL_discount / 100);
                $Discount_Base_Amount = $Base_Amount_Detail * ($DTL_discount / 100);

                // Perhitungan pajak
                $Type_Tax_1 = $detail['tax1'];
                $Type_Tax_2 = $detail['tax2'];
                // PAJAK Yang dihitung amount setelah discount or no
                $amountDiscount = $Amount_Detail - $Discount_Amount;
                $Value_Tax_1 = $this->tax->calculate_tax($amountDiscount, $Type_Tax_1);
                $Value_Tax_2 = $this->tax->calculate_tax($amountDiscount, $Type_Tax_2);

                // Menyimpan data detail
                $insert_detail = [
                    'SysId' => 0,
                    'SysId_Hdr' => $header_sysid,
                    // 'SO_Number' => $so_number,
                    'Sort' => $key + 1,
                    'SysId_Item' => $detail['SysId_Item'],
                    'Item_Code' => $detail['Item_Code'],
                    'Item_Name' => $detail['Item_Name'],
                    'Unit_Id' => $detail['Unit_Id'],
                    'Cs_SysId_Item' => $detail['Cs_SysId_Item'],
                    'Cs_Item_Code' => $detail['Cs_Item_Code'],
                    'Cs_Item_Name' => $detail['Cs_Item_Name'],
                    'Qty' => $DTL_qty,
                    'Item_Price' => $DTL_unit_price,
                    'Amount_Detail' => $Amount_Detail,
                    'Base_Amount_Detail' => $Base_Amount_Detail,
                    'Discount' => $DTL_discount,
                    'Discount_Amount' => $Discount_Amount,
                    'Discount_Base_Amount' => $Discount_Base_Amount,
                    'Type_Tax_1' => $Type_Tax_1,
                    'Value_Tax_1' => $Value_Tax_1,
                    'Type_Tax_2' => $Type_Tax_2,
                    'Value_Tax_2' => $Value_Tax_2,
                    'ETA' => date('Y-m-d', strtotime($SO_DeliveryDate)),
                    'Note' => $detail['note'],
                    'Created_By' => $this->session->userdata('impsys_nik'),
                    'Created_At' => date('Y-m-d'),
                    'Last_Updated_by' => $this->session->userdata('impsys_nik'),
                    'Last_Updated_at' => date('Y-m-d')
                ];

                $this->db->insert($this->ttrxdtl_sls_salesorder, $insert_detail);

                if (!$this->db->affected_rows()) {
                    log_message('error', 'Error inserting detail: ' . $this->db->last_query());
                    $this->db->trans_rollback();
                    return $this->help->Fn_resulting_response([
                        "code" => 505,
                        "msg" => "Terjadi kesalahan saat menyimpan detail."
                    ]);
                }
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat menyimpan detail SO."
            ]);
        }
    }

    // Fungsi edit: Mengambil data Sales Order untuk ditampilkan di form edit
    public function edit()
    {
        try {
            $sysid_hdr = $this->input->post('sys_id');
            $state = $this->input->post('state');

            // Ambil data header berdasarkan SysId
            $this->db->where('SysId', $sysid_hdr);
            $this->db->select('h.*');
            $this->db->from($this->ttrxhdr_sls_salesorder . ' as h');
            $data_hdr = $this->db->get()->row();

            // Pastikan data header ditemukan
            if (!$data_hdr) {
                throw new Exception('Data header tidak ditemukan.');
            }

            $SO_Number = $data_hdr->SO_Number;
            $qty_array = array();

            // Hanya jalankan query ini jika state adalah "revisi"
            if ($state === 'REVISI') {
                // Query untuk mendapatkan QTY_SHP berdasarkan SO_Number
                $this->db->select('dtl_shp.Item_Code, SUM(chl_dtl_shp.Qty) AS QTY_SHP');
                $this->db->from('ttrx_hdr_shipping_ins hdr_shp');
                $this->db->join('ttrx_dtl_shipping_ins dtl_shp', 'hdr_shp.SysId = dtl_shp.SysId_Hdr', 'inner');
                $this->db->join('ttrx_child_dtl_shipping_ins chl_dtl_shp', 'dtl_shp.SysId = chl_dtl_shp.Detail_ID', 'inner');
                $this->db->where('hdr_shp.Is_Cancel', 0);
                $this->db->where('hdr_shp.Approve !=', 2);
                $this->db->where('dtl_shp.SO_Number', $SO_Number);
                $this->db->group_by('dtl_shp.Item_Code');
                $qty_so_in_shp = $this->db->get()->result();

                // Buat array asosiatif untuk QTY_SHP berdasarkan Item_Code
                foreach ($qty_so_in_shp as $item) {
                    $qty_array[$item->Item_Code] = $item->QTY_SHP;
                }
            }

            // Ambil data detail berdasarkan SysId_Hdr
            $this->db->select('d.*, i.Item_Color, i.Brand, ut.Uom');
            $this->db->from($this->ttrxdtl_sls_salesorder . ' as d');
            $this->db->join($this->tmst_item . ' as i', 'd.SysId_Item = i.SysId', 'left');
            $this->db->join($this->tmst_unit_type . ' as ut', 'i.Uom_Id = ut.Unit_Type_ID', 'left');
            $this->db->where('d.SysId_Hdr', $sysid_hdr);
            $data_dtl = $this->db->get()->result();

            $totalTax = 0;
            $totalAmountOverall = 0;

            // Loop untuk menghitung totalAmount, totalTax
            foreach ($data_dtl as $item) {
                $totalAmount = $this->help->float_to_value($item->Amount_Detail) - $this->help->float_to_value($item->Discount_Amount);

                $tax1Amount = $this->tax->calculate_tax($totalAmount, $item->Type_Tax_1);
                $item->Value_Tax_1 = $tax1Amount;

                $tax2Amount = $this->tax->calculate_tax($totalAmount, $item->Type_Tax_2);
                $item->Value_Tax_2 = $tax2Amount;

                $totalTax += $tax1Amount + $tax2Amount;
                $totalAmountOverall += $totalAmount;
            }

            $response = [
                "code" => 200,
                "msg" => "Berhasil Mendapatkan Data!",
                "data_hdr" => $data_hdr,
                "data_dtl" => $data_dtl,
                "total_tax" => $totalTax,
                "total_amount" => $totalAmountOverall,
                "qty_shp" => $qty_array // Menambahkan qty_array ke response
            ];
            return $this->help->Fn_resulting_response($response);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat mengambil data SO."
            ]);
        }
    }

    // Fungsi detail: Menampilkan detail Sales Order
    public function detail($sysid_hdr)
    {
        try {
            // Ambil data header
            $this->db->where('SysId', $sysid_hdr);
            $this->db->select('h.*');
            $this->db->from($this->ttrxhdr_sls_salesorder . ' as h');
            $data_hdr = $this->db->get()->row();

            // Ambil data detail
            $this->db->where('d.SysId_Hdr', $sysid_hdr);
            $this->db->select('d.*, i.Item_Color, i.Brand, ut.Uom, t1.Tax_Id as Tax1_Id, t1.Tax_Code as Tax1_Code, t2.Tax_Id as Tax2_Id, t2.Tax_Code as Tax2_Code');
            $this->db->from($this->ttrxdtl_sls_salesorder . ' as d');
            $this->db->join($this->tmst_item . ' as i', 'd.SysId_Item = i.SysId', 'left');
            $this->db->join($this->tmst_unit_type . ' as ut', 'i.Uom_Id = ut.Unit_Type_ID', 'left');
            $this->db->join($this->tmst_tax . ' as t1', 'd.Type_Tax_1 = t1.Tax_Id', 'left');
            $this->db->join($this->tmst_tax . ' as t2', 'd.Type_Tax_2 = t2.Tax_Id', 'left');
            $data_dtl = $this->db->get()->result();



            $totalTax = 0;
            $totalAmountOverall = 0;

            foreach ($data_dtl as $item) {
                $totalAmount = $this->help->float_to_value($item->Amount_Detail) - $this->help->float_to_value($item->Discount_Amount);

                $tax1Amount = $this->tax->calculate_tax($totalAmount, $item->Type_Tax_1);
                $item->Value_Tax_1 = $tax1Amount;

                $tax2Amount = $this->tax->calculate_tax($totalAmount, $item->Type_Tax_2);
                $item->Value_Tax_2 = $tax2Amount;

                $totalTax += $tax1Amount + $tax2Amount;

                $totalAmountOverall += $totalAmount;
            }

            // Assign data ke variabel yang akan dipassing ke view
            $this->data['page_title'] = "Detail Sales Order";
            $this->data['page_group'] = "SO";
            $this->data['page_content'] = "Sales/SO/so_detail";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/SO/detail.js?v=' . time() . '"></script>';
            $this->data['data_hdr'] = $data_hdr;
            $this->data['data_dtl'] = $data_dtl;
            $this->data['total_tax'] = $totalTax;

            // Render view
            $this->load->view($this->layout, $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam menampilkan detail Sales Order."
            ]);
        }
    }


    // Fungsi approval: Menampilkan halaman approval Sales Order
    public function approval()
    {
        try {
            $this->data['page_title'] = "Approval Sales Order";
            $this->data['page_group'] = "SO_Approval";
            $this->data['page_content'] = "Sales/SO/approval";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/SO/approval.js?v=' . time() . '"></script>';

            $this->load->view($this->layout, $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam menampilkan halaman approval."
            ]);
        }
    }

    // Fungsi verify: Memverifikasi atau menolak Sales Order
    public function verify()
    {

        try {
            $sys_ids = $this->input->post('sys_ids');
            $is_verified = $this->input->post('is_verified');

            if (empty($sys_ids) || !is_array($sys_ids)) {
                throw new Exception('Nomor Sales Order tidak valid.');
            }

            if (!in_array($is_verified, [1, 2])) {
                throw new Exception('Status verifikasi tidak valid.');
            }

            $this->db->trans_start();

            foreach ($sys_ids as $sys_id) {
                $update_data = [
                    'Approve' => $is_verified,
                    'Approve_By' => $this->session->userdata('impsys_nik'),
                    'Approve_At' => date('Y-m-d')
                ];

                $this->db->where('SysId', $sys_id);
                $this->db->update($this->ttrxhdr_sls_salesorder, $update_data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Gagal menyimpan perubahan ke database.');
            }

            $response = [
                "code" => 200,
                "msg" => $is_verified == 2 ? "Data telah di-reject!" : "Data berhasil diverifikasi!"
            ];
            return $this->help->Fn_resulting_response($response);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan: " . $e->getMessage()
            ]);
        }
    }

    // Fungsi DT_listdata_approval: Mengambil data Sales Order yang perlu di-approve
    public function DT_listdata_approval()
    {
        try {
            $query = "SELECT SysId, SO_Number, SO_Date, Customer_Name, PO_Number, FORMAT(Amount, 0) AS Amount, CurrencyType_Id, SO_DeliveryDate FROM $this->ttrxhdr_sls_salesorder";
            $search = array('Customer_Name', 'PO_Number', 'SO_DeliveryDate');
            $where = array('Is_Close' => 0, 'Approve' => 0);
            $isWhere = null;

            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data approval SO."
            ]);
        }
    }

    // Fungsi close_status: Menutup status Sales Order
    public function close_status()
    {
        try {
            $sys_id = $this->input->post('sys_id');

            // Ambil baris yang sesuai dengan SysId
            $row = $this->db->get_where($this->ttrxhdr_sls_salesorder, ['SysId' => $sys_id])->row();

            if ($row) {
                // Update kolom Is_Close menjadi 1 berdasarkan SysId
                $this->db->where('SysId', $sys_id);
                $this->db->update($this->ttrxhdr_sls_salesorder, [
                    'Is_Close' => 1,
                    'Close_By' => $this->session->userdata('impsys_nik'),
                    'Close_At' => date('Y-m-d')
                ]);

                return $this->help->Fn_resulting_response([
                    "code" => 200,
                    "msg" => "Data telah dinonaktifkan!"
                ]);
            } else {
                return $this->help->Fn_resulting_response([
                    "code" => 404,
                    "msg" => "Data tidak ditemukan!"
                ]);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat menutup status SO."
            ]);
        }
    }

    // Fungsi DT_modallistofitem: Mengambil daftar item untuk modal pilihan
    public function DT_modallistofitem()
    {
        try {
            $sysid_item_category = $this->input->post('sysid_item_category');

            $query = "
                SELECT 
                    i.*,
                    CONCAT(
                        FORMAT(i.Item_Length, 0), ' x ',
                        FORMAT(i.Item_Width, 0), ' x ',
                        FORMAT(i.Item_Height, 0), ' ',
                        i.LWH_Unit
                    ) AS Dimension_Info,
                    CONCAT(
                        FORMAT(i.Item_Weight, 0), ' ',
                        i.Weight_Unit
                    ) AS Weight_Info,
                    ut.Unit_Type_ID, ut.Uom, 
                    tsi.SysId AS sourceSysId, tsi.Code_Source, tsi.Source_Name
                FROM $this->tmst_item_category ic
                JOIN $this->tmst_item_category_group icg ON ic.SysId = icg.Category_Parent
                JOIN $this->tmst_item i ON icg.SysId = i.Item_Category_Group
                LEFT JOIN $this->tmst_unit_type ut ON i.Uom_Id = ut.Unit_Type_ID
                LEFT JOIN $this->tmst_source_item tsi ON i.Source = tsi.SysId
            ";
            $search = array('i.Item_Code', 'i.Item_Name', 'Item_Color', 'Model');
            $where = array('ic.SysId' => $sysid_item_category);
            $iswhere = null;

            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_query($query, $search, $where, $iswhere);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil daftar item."
            ]);
        }
    }

    // Fungsi DT_listoftax: Mengambil daftar pajak untuk DataTables
    public function DT_listoftax()
    {
        try {
            $this->db->select('Tax_Id, Tax_Code, Tax_Name, Tax_Rate, ForSales, ForPurchase, isInclude, isKreditable, isPPNBM, Is_Active, Created_at, Created_by');
            $this->db->from($this->tmst_tax);
            $query = $this->db->get();
            $data_tax = $query->result();

            return $this->help->Fn_resulting_response([
                "code" => 200,
                "msg" => "Berhasil mengambil data pajak.",
                "data" => $data_tax
            ]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil daftar pajak."
            ]);
        }
    }

    // Fungsi DT_listofaccount_address: Mengambil daftar alamat akun untuk DataTables
    public function DT_listofaccount_address()
    {
        try {
            $customer_code = $this->input->post('customer_code');

            $this->db->select('SysId, Address, Area, Description');
            $this->db->from($this->tmst_account_address);
            $this->db->where('Account_Code', $customer_code);
            $query = $this->db->get();

            $result = $query->result();
            return $this->help->Fn_resulting_response([
                "code" => 200,
                "msg" => "Berhasil mengambil data alamat.",
                "data" => $result
            ]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil daftar alamat."
            ]);
        }
    }

    // Fungsi outstanding: Menampilkan halaman Sales Order Outstanding
    public function outstanding()
    {
        try {
            $this->data['page_title'] = "Sales Order Outstanding";
            $this->data['page_group'] = "SO";
            $this->data['page_content'] = "Sales/SO/outstanding";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/SO/outstanding.js?v=' . time() . '"></script>';

            $this->load->view($this->layout, $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam menampilkan halaman SO Outstanding."
            ]);
        }
    }

    // Fungsi DT_listdata_outstanding: Mengambil data Sales Order Outstanding untuk DataTables
    public function DT_listdata_outstanding()
    {
        try {
            $query = "SELECT * FROM $this->qview_outstanding_so_vs_si";
            $search = array('SO_Number', 'PO_Number', 'Item_Code', 'Item_Name');
            $where = null;
            $isWhere = null;

            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data SO Outstanding."
            ]);
        }
    }

    // Fungsi DT_detail_shipping: Mengambil detail shipping berdasarkan nomor SO
    public function DT_detail_shipping()
    {
        try {
            $so_number = $this->input->post('so_number');
            $this->db->select('*');
            $this->db->from($this->ttrx_dtl_shipping_ins);
            $this->db->where('SO_Number', $so_number);

            $query = $this->db->get();
            $result = $query->result();

            if (!empty($result)) {
                return $this->help->Fn_resulting_response([
                    "code" => 200,
                    "msg" => "Berhasil mengambil detail shipping.",
                    "data" => $result
                ]);
            } else {
                return $this->help->Fn_resulting_response([
                    "code" => 404,
                    "msg" => "Data tidak ditemukan!"
                ]);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil detail shipping."
            ]);
        }
    }

    // Fungsi modal_detail_shipping: Menampilkan modal detail shipping berdasarkan nomor SO
    public function modal_detail_shipping()
    {
        try {
            $so_number = $this->input->get('so_number');
            $item_code = $this->input->get('item_code'); // Ambil item_code dari parameter GET

            $this->data['so_number'] = $so_number;
            $this->data['item_code'] = $item_code;

            $this->data['datas'] = $this->db->query("
                SELECT hdr.ShipToAddress_ID, taa.Address, hdr.ShipInst_Number, 
                    DATE_FORMAT(hdr.ShipInst_Date, '%Y-%m-%d') AS ShipInst_Date,
                    dtl.SysId, dtl.SysId_Hdr, dtl.Item_Code, dtl.Item_Name, 
                    dtl.Dimension, dtl.Qty, dtl.Warehouse_Qty, dtl.Uom, 
                    dtl.isFreeItem, dtl.SO_Number, dtl.Notes
                FROM $this->ttrx_dtl_shipping_ins as dtl
                JOIN $this->ttrx_hdr_shipping_ins as hdr 
                    ON dtl.SysId_Hdr = hdr.SysId
                JOIN $this->tmst_account_address taa 
                    ON hdr.ShipToAddress_ID = taa.SysId
                JOIN $this->ttrxhdr_sls_salesorder tss 
                    ON dtl.SO_Number = tss.SO_Number
                WHERE tss.SO_Number = '$so_number'
                AND dtl.Item_Code = '$item_code'
                AND hdr.Is_Cancel = 0 -- Memastikan pengiriman tidak dibatalkan
                AND hdr.Approve = 1 -- Memastikan pengiriman sudah di-approve
            ")->result();


            $this->load->view("Sales/SO/m_list_shipping", $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam menampilkan modal detail shipping."
            ]);
        }
    }

    // Fungsi calculate_tax: Menghitung pajak berdasarkan jumlah total dan tarif pajak
    public function calculate_tax()
    {
        try {
            // Ambil array dari post untuk tax1, tax2, dan amount
            $tax1Rates = $this->input->post('tax1Rate');
            $tax2Rates = $this->input->post('tax2Rate');
            $amounts = $this->input->post('amount'); // Menambahkan pengambilan amount dari setiap item

            $result = 0;

            // Lakukan iterasi untuk menghitung pajak pada setiap item berdasarkan amount-nya
            for ($i = 0; $i < count($tax1Rates); $i++) {
                $amount = $amounts[$i]; // Ambil nilai amount dari masing-masing item

                // Menghitung pajak berdasarkan amount dan rate pajak
                $resultTax1Rate = $this->tax->calculate_tax($amount, $tax1Rates[$i]);
                $resultTax2Rate = $this->tax->calculate_tax($amount, $tax2Rates[$i]);

                // Menambahkan hasil pajak dari tax1 dan tax2 untuk item ini
                $result += $resultTax1Rate + $resultTax2Rate;
            }

            // Kembalikan response sukses dengan hasil total pajak
            return $this->help->Fn_resulting_response([
                "code" => 200,
                "msg" => "Pajak berhasil dihitung.",
                "result" => $result
            ]);
        } catch (Exception $e) {
            // Log error dan kembalikan response error
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat menghitung pajak."
            ]);
        }
    }

    // 
    function export_pdf_so($sysid)
    {
        // 
        try {
            $this->db->where('h.SysId', $sysid);
            $this->db->select(
                'h.*, 
            a.Account_Name, a.Account_Address, a.Account_Phone1, a.Account_EmailAddress, 
            c.Contact_Name, c.Job_title, c.Email_Address as Contact_Email, c.Mobile_Phone as Contact_Mobile,
            k1.nama AS Created_By,
            k1.nama AS Approve_By'
            );
            $this->db->from($this->ttrxhdr_sls_salesorder . ' as h');
            $this->db->join('tmst_account as a', 'h.Customer_Id = a.SysId', 'left');
            $this->db->join('tmst_account_contact as c', 'a.Account_Code = c.Account_Code', 'left');
            $this->db->join('tmst_karyawan as k1', 'k1.nik = h.Created_By', 'left');
            $this->db->join('tmst_karyawan as k2', 'k2.nik = h.Approve_By', 'left');
            $data_hdr = $this->db->get()->row();

            $this->db->where('d.SysId_Hdr', $sysid);
            $this->db->select('d.*, i.Item_Color, i.Brand, ut.Uom');
            $this->db->from($this->ttrxdtl_sls_salesorder . ' as d');
            $this->db->join($this->tmst_item . ' as i', 'd.SysId_Item = i.SysId', 'left');
            $this->db->join($this->tmst_unit_type . ' as ut', 'i.Uom_Id = ut.Unit_Type_ID', 'left');
            $data_dtl = $this->db->get()->result();

            $response = [
                "data_hdr" => $data_hdr,
                "data_dtl" => $data_dtl,
            ];

            // ----------------- GET DATA -------------- //
            $paper = 'A4';
            $orientation = "portrait";
            $html = $this->load->view('Sales/SO/export/pdf-sales-order', $response, true);

            $poDate = date('d F Y', strtotime($data_hdr->PO_Date)); // Tanggal format Indonesia
            $customerName = $data_hdr->Account_Name ?: ''; // Jika tidak ada nama, gunakan default
            $name_file = "Sales Order $customerName $poDate";
            // 
            $this->load->library('pdfgenerator');
            // 

            $this->pdfgenerator->generate($html, $name_file, $paper, $orientation);
            // 
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat mengambil data SO."
            ]);
        }
    }
}
