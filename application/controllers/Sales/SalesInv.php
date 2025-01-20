<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SalesInv extends CI_Controller
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
    protected $ttrx_hdr_sls_invoice = 'ttrx_hdr_sls_invoice';
    protected $ttrx_dtl_sls_invoice = 'ttrx_dtl_sls_invoice';
    protected $ttrx_dtl_amount_sls_inv_so = 'ttrx_dtl_amount_sls_inv_so';
    protected $ttrx_hdr_shipping_ins = 'ttrx_hdr_shipping_ins';
    protected $ttrx_dtl_shipping_ins = 'ttrx_dtl_shipping_ins';
    protected $tmst_tax = 'tmst_tax';
    protected $tmst_beacukai = 'tmst_beacukai';

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
        $this->load->model('m_Warehouse', 'm_wh');
        $this->load->model('m_Tax', 'tax');
        $this->load->model('m_DataTable', 'M_Datatables');
    }

    // Fungsi index: Menampilkan halaman utama Sales Invoice
    public function index()
    {
        try {
            $this->data['page_title'] = "Data Sales Inv";
            $this->data['page_group'] = "INV";
            $this->data['page_content'] = "Sales/INV/sales_inv";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/INV/sales_inv.js?v=' . time() . '"></script>';

            // Mengambil data currency
            $this->data['Currency'] = $this->db
                ->order_by('Currency_ID', 'ASC')
                ->get($this->tmst_currency);

            // Mengambil data account dengan kategori CS
            $this->data['Account_CS'] = $this->db
                ->where('Category_ID', 'CS')
                ->where('is_active', '1')
                ->get($this->tmst_account);

            // Mengambil data negara
            $this->data['country'] = $this->db->get('tmst_country');

            // Mengambil data jenis bea cukai
            $this->data['bc_types'] = $this->db->get($this->tmst_beacukai);

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

    // Fungsi DT_listdata: Mengambil data Sales Invoice untuk DataTables
    public function DT_listdata()
    {
        try {
            $query  = "
                        SELECT 
                            ttrx_hdr_sls_invoice.Invoice_ID, 
                            ttrx_hdr_sls_invoice.Invoice_Number, 
                            ttrx_hdr_sls_invoice.SI_Number,
                            ttrx_hdr_sls_invoice.Invoice_Date, 
                            ttrx_hdr_sls_invoice.Due_Date, 
                            ttrx_hdr_sls_invoice.Invoice_Status, 
                            ttrx_hdr_sls_invoice.Approve, 
                            ttrx_hdr_sls_invoice.Is_Cancel, 
                            tmst_account.Account_Name
                        FROM 
                            $this->ttrx_hdr_sls_invoice
                        LEFT JOIN 
                            $this->tmst_account 
                        ON 
                            $this->ttrx_hdr_sls_invoice.Account_ID = $this->tmst_account.SysId
                    ";

            $search = array('Invoice_Number', 'SI_Number', 'Invoice_Date', 'Due_Date', 'Invoice_Status', 'tmst_account.Account_Name');
            $where  = null;
            $isWhere = null;

            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data Sales Invoice."
            ]);
        }
    }

    // Fungsi DT_listdata_shipping: Mengambil data pengiriman untuk DataTables
    public function DT_listdata_shipping()
    {
        // KEEP
        try {

            $accountId = $this->input->post('Account_ID');
            // Membuat subquery untuk memeriksa apakah ShipInst_Number ada di SI_Number dari invoice
            $subquery = $this->db->select('SI_Number')
                ->from($this->ttrx_hdr_sls_invoice . ' inv')
                ->where('inv.Is_Cancel', 0)
                ->where('Approve !=', 2)
                ->where('inv.SI_Number = hdr_shp.ShipInst_Number')
                ->get_compiled_select();

            // Query utama dengan left join dan subquery
            $this->db->select('
                hdr_shp.ShipInst_Number,
                hdr_shp.Invoice_Number,
                adr.Address as ShipToAddress,
                CASE 
                    WHEN EXISTS (' . $subquery . ') THEN (' . $subquery . ')
                    ELSE NULL 
                END as SI_Number
            ');
            $this->db->from($this->ttrx_hdr_shipping_ins . ' hdr_shp');
            $this->db->join($this->tmst_account_address . ' adr', 'hdr_shp.ShipToAddress_ID = adr.SysId', 'left');
            $this->db->where('hdr_shp.Account_ID', $accountId);
            $this->db->where('hdr_shp.Approve', 1);
            $this->db->where('hdr_shp.Is_Cancel', 0);

            // Eksekusi query
            $result = $this->db->get()->result();
            $result = ['shipping' => $result];
            // Mengecek apakah hasil query kosong
            // if (empty($result)) {
            //     $result = ['shipping' => []]; // Mengirim data kosong jika SI_Number sudah ada atau tidak ada hasil
            // } else {
            //     $result = ['shipping' => $result];
            // }

            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data pengiriman."
            ]);
        }
    }


    // Fungsi DT_sales_shipping: Mengambil detail pengiriman berdasarkan nomor SI
    // Here
    public function DT_sales_shipping()
    {
        try {
            $ShipInst_Number = $this->input->post('SINumber');

            $this->db->select('`hdr_shp`.*, `dtl_shp`.`Qty`, dtl_shp.Qty_Return, (`dtl_shp`.`Qty` - `dtl_shp`.`Qty_Return`) as Qty_Invoiced , `dtl_shp`.`isFreeItem`, `dtl_shp`.`SO_Number`,
            `dtl_shp`.`Dimension`, `dtl_shp`.`Notes`,
            `dtl_shp`.`Item_Code`, `dtl_shp`.`Item_Name`, `dtl_sls`.`Item_Price`, `dtl_sls`.`SysId_Item`, `dtl_sls`.`SysId_Item`, `dtl_sls`.`Type_Tax_1`,
            `dtl_sls`.`Value_Tax_1`, `dtl_sls`.`Type_Tax_2`, `dtl_sls`.`Value_Tax_2`, `dtl_sls`.`Discount`, `hdr_sls`.`SO_Number`, `hdr_sls`.`CurrencyType_Id`,
            `hdr_sls`.`Currency_Rate`, `hdr_sls`.`Discount_Persen`, `i`.`Item_Color`, `i`.`Brand`, `ut`.`Uom`,
            (dtl_sls.Item_Price * dtl_shp.Qty * (1 - (dtl_sls.Discount / 100))) as Final_Amount,
            (dtl_sls.Item_Price * dtl_shp.Qty * (dtl_sls.Discount / 100)) as Discount_Value,
            IFNULL(t1.Tax_Id, "0") as Tax1_Id, IFNULL(t1.Tax_Code, "none") as Tax1_Code,
            IFNULL(t2.Tax_Id, "0") as Tax2_Id, IFNULL(t2.Tax_Code, "none") as Tax2_Code
            ');

            $this->db->from('ttrx_hdr_shipping_ins hdr_shp');
            $this->db->join('ttrx_dtl_shipping_ins dtl_shp', 'hdr_shp.SysId = dtl_shp.SysId_Hdr');
            $this->db->join('ttrxdtl_sls_salesorder dtl_sls', 'dtl_shp.Item_Code = dtl_sls.Item_Code');
            $this->db->join('ttrxhdr_sls_salesorder hdr_sls', 'dtl_sls.SysId_Hdr = hdr_sls.SysId');
            $this->db->join('tmst_item as i', 'dtl_sls.Item_Code = i.Item_Code', 'left');
            $this->db->join('tmst_unit_type as ut', 'i.Uom_Id = ut.Unit_Type_ID', 'left');
            $this->db->join('tmst_tax as t1', 'dtl_sls.Type_Tax_1 = t1.Tax_Id', 'left'); // Join untuk Type_Tax_1
            $this->db->join('tmst_tax as t2', 'dtl_sls.Type_Tax_2 = t2.Tax_Id', 'left'); // Join untuk Type_Tax_2
            $this->db->where('dtl_shp.SO_Number = hdr_sls.SO_Number');
            $this->db->where('hdr_shp.ShipInst_Number', $ShipInst_Number);

            $query = $this->db->get();
            $result = $query->result_array();
            // echo '<pre>';
            // print_r($result);
            // echo '</pre>';
            // die;
            // /
            $grouped_result = [];
            $total_tax = 0;
            // 
            foreach ($result as $row) {
                $header_id = $row['ShipInst_Number'];

                if (!isset($grouped_result[$header_id])) {
                    $grouped_result[$header_id] = [
                        'header' => [
                            'SysId' => $row['SysId'],
                            'ShipInst_Number' => $row['ShipInst_Number'],
                            'ShipInst_Date' => $row['ShipInst_Date'],
                            'Account_ID' => $row['Account_ID'],
                            'ShipToAddress_ID' => $row['ShipToAddress_ID'],
                            'ExpectedDeliveryDate' => $row['ExpectedDeliveryDate'],
                            'NotifeParty' => $row['NotifeParty'],
                            'NotifePartyAddress' => $row['NotifePartyAddress'],
                            'PortOfLoading' => $row['PortOfLoading'],
                            'PlaceOfDelivery' => $row['PlaceOfDelivery'],
                            'Carrier' => $row['Carrier'],
                            'Sailing' => $row['Sailing'],
                            'ShippingMarks' => $row['ShippingMarks'],
                            'LCNo' => $row['LCNo'],
                            'LCDate' => $row['LCDate'],
                            'LCBank' => $row['LCBank'],
                            'Approve' => $row['Approve'],
                            'Approve_By' => $row['Approve_By'],
                            'Approve_At' => $row['Approve_At'],
                            'Created_By' => $row['Created_By'],
                            'Created_At' => $row['Created_At'],
                            'isExport' => $row['isExport'],
                            'Is_Cancel' => $row['Is_Cancel'],
                            'Discount_Persen' => $row['Discount_Persen'],
                            'CurrencyType_Id' => $row['CurrencyType_Id'],
                            'Currency_Rate' => $row['Currency_Rate'],
                        ],
                        'details' => []
                    ];
                }

                // Menghitung total tax untuk item saat ini
                $item_tax_1 = $this->tax->calculate_tax($row['Final_Amount'], $row['Type_Tax_1']);
                $item_tax_2 = $this->tax->calculate_tax($row['Final_Amount'], $row['Type_Tax_2']);
                $item_total_tax = $this->tax->calculate_tax($row['Final_Amount'], $row['Type_Tax_1']) + $this->tax->calculate_tax($row['Final_Amount'], $row['Type_Tax_2']);
                // Perhitungan pajak

                // Menambahkan total tax dari item saat ini ke total keseluruhan
                $total_tax += $item_total_tax;

                $grouped_result[$header_id]['details'][] = [
                    'ShipInst_Number' => $row['ShipInst_Number'],
                    'SysId_Item' => $row['SysId_Item'],
                    'Item_Code' => $row['Item_Code'],
                    'Item_Name' => $row['Item_Name'],
                    'Dimension' => $row['Dimension'],
                    'Qty' => $row['Qty'],
                    'Qty_Invoiced' => $row['Qty_Invoiced'],
                    'Uom' => $row['Uom'],
                    'isFreeItem' => $row['isFreeItem'],
                    'SO_Number' => $row['SO_Number'],
                    'Item_Price' => $row['Item_Price'],
                    'Type_Tax_1' => $row['Type_Tax_1'],
                    'Value_Tax_1' => $item_tax_1,
                    'Type_Tax_2' => $row['Type_Tax_2'],
                    'Value_Tax_2' => $item_tax_2,
                    'Note' => $row['Notes'],
                    'Item_Color' => $row['Item_Color'],
                    'Brand' => $row['Brand'],
                    'Tax1_Id' => $row['Tax1_Id'],
                    'Tax1_Code' => $row['Tax1_Code'],
                    'Tax2_Id' => $row['Tax2_Id'],
                    'Tax2_Code' => $row['Tax2_Code'],
                    'Discount' => $row['Discount'],
                    'Final_Discount' => $row['Discount_Value'],
                    'Final_Amount' => $row['Final_Amount'],
                ];
            }
            // print_r($grouped_result);
            // die;
            return $this->help->Fn_resulting_response([
                "code" => 200,
                "msg" => "Data pengiriman berhasil diambil.",
                "data" => array_values($grouped_result),
                "total_tax" => $total_tax // Total keseluruhan tax
            ]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data detail pengiriman."
            ]);
        }
    }

    // Fungsi store: Menyimpan data Sales Invoice baru atau memperbarui data yang ada
    public function store()
    {
        try {

            $state = $this->input->post('state');
            $invoice_number = ($state == 'ADD') ? $this->help->Gnrt_Identity_Monthly_Sales("INV", 7, '-') : "";
            $this->db->trans_start();

            // Rate Curr
            $rateCurrency = $this->help->float_to_value($this->input->post('rate_currency'));
            // Total Amount
            $invoiceAmount = $this->help->float_to_value($this->input->post('total_amount'));
            $baseInvoiceAmount = $invoiceAmount * $rateCurrency;
            // Persentase Diskon
            $discount_percentage = $this->help->float_to_value($this->input->post('discount_percentage'));
            // 
            $taxAmount = $this->help->float_to_value($this->input->post('total_tax'));
            $baseTaxAmount = $taxAmount * $rateCurrency;
            $paidTaxAmount = $taxAmount - ($taxAmount * ($discount_percentage / 100));
            // Menghitung TransactionDiscountAmount (Jumlah diskon berdasarkan persentase diskon)
            $transaction_discount_amount = $invoiceAmount * ($discount_percentage / 100);
            // Menghitung TransactionDiscountBaseAmount (Diskon dalam mata uang dasar)
            $transaction_discount_base_amount = $transaction_discount_amount * $rateCurrency;

            // tagihan
            $paid_invoice_amount = ($invoiceAmount - $transaction_discount_amount) + $taxAmount;
            // 

            $hdr_so_num = $this->input->post('HDR_SO_Number');

            $si_number = $this->input->post('SI_Number') ?: NULL;
            $data_header = [
                'SI_Number' => $si_number,
                'SO_Number' => $hdr_so_num,
                'Invoice_Status' => $this->input->post('invoice_status') ?: 'NP',
                'Invoice_Date' => $this->input->post('invoice_date') ? date('Y-m-d', strtotime($this->input->post('invoice_date'))) : NULL,
                'Due_Date' => $this->input->post('due_date') ? date('Y-m-d', strtotime($this->input->post('due_date'))) : NULL,
                'Account_ID' => $this->input->post('customer_id') ?: NULL,
                'Invoice_Notes' => $this->input->post('notes') ?: NULL,
                'TermNumber' => $this->input->post('term_number') ?: NULL,
                'ISJob' => $this->input->post('is_job') ?: 0,
                'Project_ID' => $this->input->post('project_id') ?: NULL,
                'Parent_path' => $this->input->post('parent_path') ?: NULL,
                'TaxDocNumPPN' => $this->input->post('tax_doc_num_ppn') ?: NULL,
                'TaxDocNumPPh' => $this->input->post('tax_docnum_pph') ?: NULL,
                'disc_date' => $this->input->post('disc_date') ? date('Y-m-d', strtotime($this->input->post('disc_date'))) : NULL,
                'Early_Amount' => $this->help->float_to_value($this->input->post('early_amount')),
                'Late_Amount' => $this->help->float_to_value($this->input->post('late_amount')),
                'Currency_ID' => $this->input->post('currency') ?: NULL,
                'Invoice_Amount' => $invoiceAmount,
                'Base_Invoice_Amount' => $baseInvoiceAmount,
                'Paid_InvoiceAmount' => $paid_invoice_amount,
                'Tax_Currency_ID' => $this->input->post('tax_currency_id') ?: NULL,
                'Tax_Amount' => $taxAmount,
                'Base_TaxAmount' => $baseTaxAmount,
                'Paid_TaxAmount' => $paidTaxAmount,
                'Freight_Currency_ID' => $this->input->post('freight_currency_id') ?: NULL,
                'Freight_Amount' => $this->help->float_to_value($this->input->post('freight_amount')),
                'Base_Freight_Amount' => $this->help->float_to_value($this->input->post('base_freight_amount')),
                'Paid_FreightAmount' => $this->help->float_to_value($this->input->post('paid_freight_amount')),
                'isTaxAble' => $this->input->post('is_tax_able') ?: 0,
                'isAsset' => $this->input->post('is_asset') ?: 0,
                'LstCBDoc' => $this->input->post('lst_cb_doc') ?: NULL,
                'Tax_Date' => $this->input->post('tax_date') ? date('Y-m-d', strtotime($this->input->post('tax_date'))) : NULL,
                'DP_Amount' => $this->help->float_to_value($this->input->post('dp_amount')),
                'RemKursTax_tobase' => $this->help->float_to_value($this->input->post('remkurs_tax_tobase')),
                'RemKursFreight_tobase' => $this->help->float_to_value($this->input->post('remkurs_freight_tobase')),
                'RemKursAmount_tobase' => $this->help->float_to_value($this->input->post('remkurs_amount_tobase')),
                'LstLinkAccount' => $this->input->post('lst_link_account') ?: NULL,
                'StatusPPh23' => $this->input->post('status_pph23') ?: 0,
                'VatType' => $this->input->post('vat_type') ?: NULL,
                'CustInvoice_Number' => $this->input->post('cust_invoice_number') ?: NULL,
                'PPH23ReceiptDoc' => $this->input->post('pph23_receipt_doc') ?: NULL,
                'isWithHold' => $this->input->post('is_with_hold') ?: NULL,
                'TERMOFPAYMENT' => $this->input->post('term_of_payment') ?: NULL,
                'PrintCounter' => (int)$this->input->post('print_counter') ?: NULL,
                'INVOICETYPE' => $this->input->post('invoice_type') ?: NULL,
                'StatusRevalue' => $this->input->post('status_revalue') ?: NULL,
                'Revalue_Id' => (int)$this->input->post('revalue_id') ?: NULL,
                'PPNNumberGenerated' => $this->input->post('ppn_number_generated') ?: NULL,
                'DirectType' => (int)$this->input->post('direct_type') ?: NULL,
                'PPH23ReceiptDate' => $this->input->post('pph23_receipt_date') ? date('Y-m-d', strtotime($this->input->post('pph23_receipt_date'))) : NULL,
                'TransactionDiscountPresentase' => $discount_percentage ?: NULL,
                'TransactionDiscountAmount' => $transaction_discount_amount ?: NULL,
                'TransactionDiscountBaseAmount' => $transaction_discount_base_amount ?: NULL,
                'Doc_Return' => $this->input->post('doc_return') ?: NULL,
                'PriceType' => $this->input->post('price_type') ?: NULL,
                'LC_Number' => $this->input->post('LC_No') ?: NULL,
                'LC_Date' => $this->input->post('LC_Date') ? date('Y-m-d', strtotime($this->input->post('LC_Date'))) : NULL,
                'LC_Bank' => $this->input->post('LC_Bank') ?: NULL,
                'Carrier' => $this->input->post('carrier') ?: NULL,
                'Sailing' => $this->input->post('sailing') ?: NULL,
                'claimdeduction' => NULL,
                'InvoicePrintDate' => $this->input->post('invoice_print_date') ? date('Y-m-d', strtotime($this->input->post('invoice_print_date'))) : NULL,
                'NotifeParty' => $this->input->post('NotifeParty') ?: NULL,
                'NotifePartyAddress' => $this->input->post('NotifePartyAddress') ?: NULL,
                'PortOfLoading' => $this->input->post('port_of_loading') ?: NULL,
                'PlaceOfDelivery' => $this->input->post('place_of_delivery') ?: NULL,
                'ShippingMarks' => $this->input->post('shipping_marks') ?: NULL,
                'Notes' => $this->input->post('notes') ?: NULL,
                'isExport' => NULL,
                'Approve' => 0,
                'Approve_By' => NULL,
                'Approve_At' => NULL,
                'Is_Cancel' => 0,
                'Cancel_Reason' => NULL,
                'Cancel_By' => NULL,
                'Cancel_At' => NULL,
            ];

            if ($state == 'ADD') {
                $data_header['Invoice_Number'] = $invoice_number;
                $data_header['Created_By'] = $this->session->userdata('impsys_nik');
                $data_header['Created_At'] = date('Y-m-d');
                $data_header['Last_Updated_by'] = NULL;
                $data_header['Last_Updated_at'] = NULL;

                $this->db->insert($this->ttrx_hdr_sls_invoice, $data_header);
                $msg = "Berhasil Menambah Sales INV!";
            } else {
                $invoice_number = $this->input->post('invoice_number_edit');
                $invoice_id = $this->input->post('invoice_id_edit');
                // 
                // Tambahkan Last_Updated_by dan Last_Updated_at ke $data_header
                $data_header['Last_Updated_by'] = $this->session->userdata('impsys_nik');
                $data_header['Last_Updated_at'] = date('Y-m-d');
                // 
                $this->db->where('Invoice_ID', $invoice_id);
                // update header
                $this->db->update($this->ttrx_hdr_sls_invoice, $data_header);
                // delete detail
                $this->db->delete($this->ttrx_dtl_sls_invoice, ['Invoice_Number' => $invoice_number]);
                // delete child
                $this->db->delete($this->ttrx_dtl_amount_sls_inv_so, ['sales_invoice_number' => $invoice_number]);

                $msg = "Berhasil Mengedit Sales INV!";
            }

            $detail_data = [
                'Invoice_Number' => $invoice_number,
                'Item_Code' => $this->input->post('item_code[]') ?: null,
                'Item_Description' => $this->input->post('item_name[]') ?: null,
                'Qty' => $this->help->float_to_value($this->input->post('qty[]') ?: null),
                'UnitPrice' => $this->help->float_to_value($this->input->post('item_price[]') ?: null),
                'Base_UnitPrice' => $this->help->float_to_value("") ?: null, // Sesuaikan jika perlu
                'Disc_percentage' => $this->help->float_to_value($this->input->post('discount[]') ?: null),
                'Disc_Value' => $this->help->float_to_value($this->input->post('disc_value[]') ?: null),
                'Tax_Code1' => $this->input->post('tax1_code[]') ?: null,
                'Tax_percentage1' => $this->help->float_to_value("") ?: null, // Sesuaikan jika perlu
                'Tax_operator1' => $this->help->float_to_value("") ?: null, // Sesuaikan jika perlu
                'Tax_Amount1' => $this->help->float_to_value($this->input->post('Tax_Amount1[]') ?: null),
                'Tax_Code2' => $this->input->post('tax2_code[]') ?: null,
                'Tax_Percentage2' => $this->help->float_to_value("") ?: null, // Sesuaikan jika perlu
                'Tax_Operator2' => $this->help->float_to_value("") ?: null, // Sesuaikan jika perlu
                'Tax_Amount2' => $this->help->float_to_value($this->input->post('Tax_Amount2[]') ?: null),
                'TotalPrice' => $this->help->float_to_value($this->input->post('amount_detail[]') ?: null),
                'Base_TotalPrice' => $this->help->float_to_value("") ?: null, // Sesuaikan jika perlu
                'Product_Size' => $this->input->post('product_size[]') ?: null,
                'LstItemAccount' => "", // Sesuaikan jika ada
                'DO_Number' => "", // Sesuaikan jika ada
                'Ref_Number' => "", // Sesuaikan jika ada
                'isFreeItem' => $this->help->float_to_value($this->input->post('is_free[]') ?: null), // Jika ini angka
                'SO_Number' => $this->input->post('dtl_so_number[]') ?: null
            ];


            $this->store_DT_dtl($invoice_number, $detail_data, $rateCurrency, $taxAmount);
            $this->db->trans_commit();
            $response = ["code" => 200, "msg" => $msg];
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            $response = ["code" => 505, "msg" => "Proses penyimpanan gagal! Error: " . $th->getMessage()];
        }

        return $this->help->Fn_resulting_response($response);
    }

    // Fungsi store_DT_dtl: Menyimpan detail Sales Invoice
    private function store_DT_dtl($invoice_number, $detail_data, $rateCurrency, $taxAmount)
    {
        try {
            $aggregatedAmounts = [];

            foreach ($detail_data['Item_Code'] as $index => $item_code) {
                $dtlQty = $detail_data['Qty'][$index];
                $unitPrice = $detail_data['UnitPrice'][$index];
                $baseUnitPrice = $unitPrice * $rateCurrency;
                $persentaseDisc = $detail_data['Disc_percentage'][$index];
                $discValue = $detail_data['Disc_Value'][$index];
                $totalPrice = $detail_data['UnitPrice'][$index] * $dtlQty;
                $baseTotalPrice = $totalPrice * $rateCurrency;

                $insert_detail = [
                    'Invoice_Number' => $invoice_number,
                    'Item_Code' => $item_code,
                    'Item_Description' => $detail_data['Item_Description'][$index],
                    'Qty' => $dtlQty,
                    'UnitPrice' => $unitPrice,
                    'Base_UnitPrice' => $baseUnitPrice,
                    'Disc_percentage' => $persentaseDisc,
                    'Disc_Value' => $discValue,
                    'Tax_Code1' => $detail_data['Tax_Code1'][$index],
                    'tax_percentage1' => "",
                    'Tax_operator1' => "",
                    'Tax_Amount1' => $detail_data['Tax_Amount1'][$index],
                    'Tax_Code2' => $detail_data['Tax_Code2'][$index],
                    'Tax_Percentage2' => "",
                    'Tax_Operator2' => "",
                    'Tax_Amount2' => $detail_data['Tax_Amount2'][$index],
                    'TotalPrice' => $totalPrice,
                    'Base_TotalPrice' => $baseTotalPrice,
                    'Product_Size' => $detail_data['Product_Size'][$index],
                    'LstItemAccount' => "",
                    'DO_Number' => "",
                    'Ref_Number' => "",
                    'isFreeItem' => $detail_data['isFreeItem'][$index],
                    'SO_Number' => $detail_data['SO_Number'][$index]
                ];

                $so_number = $detail_data['SO_Number'][$index];
                if (!isset($aggregatedAmounts[$so_number])) {
                    $aggregatedAmounts[$so_number] = [
                        'invoice_amount' => 0,
                        'tax_amount' => 0
                    ];
                }
                $aggregatedAmounts[$so_number]['invoice_amount'] += $totalPrice;
                $totalTax = floatval($detail_data['Tax_Amount1'][$index]) + floatval($detail_data['Tax_Amount2'][$index]);
                $aggregatedAmounts[$so_number]['tax_amount'] += $totalTax;

                $this->db->insert($this->ttrx_dtl_sls_invoice, $insert_detail);
            }

            foreach ($aggregatedAmounts as $so_number => $amounts) {
                $insert_amount = [
                    'sales_invoice_number' => $invoice_number,
                    'so_number' => $so_number,
                    'invoice_amount' => $amounts['invoice_amount'],
                    'tax_amount' => $amounts['tax_amount'],
                    'Is_Cancel' => ''
                ];

                $this->db->insert($this->ttrx_dtl_amount_sls_inv_so, $insert_amount);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat menyimpan detail invoice."
            ]);
        }
    }

    // Fungsi detail: Menampilkan detail Sales Invoice
    public function detail($invoice_id)
    {
        try {
            $this->db->select('hdr_sls_inv.*, ac.Account_Name');
            $this->db->from($this->ttrx_hdr_sls_invoice . ' hdr_sls_inv');
            $this->db->join($this->tmst_account . ' ac', 'hdr_sls_inv.Account_ID = ac.SysId');
            $this->db->where('Invoice_ID', $invoice_id);
            $data_hdr = $this->db->get()->row();

            // $this->db->select('*');
            // $this->db->from($this->ttrx_dtl_sls_invoice);
            // $this->db->where('Invoice_Number', $data_hdr->Invoice_Number);
            // $data_dtl = $this->db->get()->result();

            $this->db->select('ttrx_dtl_sls_invoice.*, tmst_item.Brand, tmst_item.Item_Color, tmst_unit_type.Uom');
            $this->db->from($this->ttrx_dtl_sls_invoice);
            $this->db->join('tmst_item', 'tmst_item.Item_Code = ttrx_dtl_sls_invoice.Item_Code', 'left');
            $this->db->join('tmst_unit_type', 'tmst_unit_type.Unit_Type_ID = tmst_item.Uom_Id', 'left');
            $this->db->where('ttrx_dtl_sls_invoice.Invoice_Number', $data_hdr->Invoice_Number);
            $data_dtl = $this->db->get()->result();

            $this->db->select('hdr_shp.ShipInst_Number, adr.Address as ShipToAddress');
            $this->db->from($this->ttrx_hdr_shipping_ins . ' hdr_shp');
            $this->db->join($this->tmst_account_address . ' adr', 'hdr_shp.ShipToAddress_ID = adr.SysId', 'left');
            $this->db->where('hdr_shp.Account_ID', $data_hdr->Account_ID);
            $this->db->where('hdr_shp.Approve', 1);
            $data_shp = $this->db->get()->result();

            $this->data['page_title'] = "Detail Shipping Instruction";
            $this->data['page_group'] = "SO";
            $this->data['page_content'] = "Sales/INV/s_inv_detail";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/INV/detail.js?v=' . time() . '"></script>';
            $this->data['data_hdr'] = $data_hdr;
            $this->data['data_dtl'] = $data_dtl;
            $this->data['data_shp'] = $data_shp;

            $this->load->view($this->layout, $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam menampilkan detail Sales Invoice."
            ]);
        }
    }

    // Fungsi approval: Menampilkan halaman approval Sales Invoice
    public function approval()
    {
        try {
            $this->data['page_title'] = "Approval Sales Invoice";
            $this->data['page_group'] = "SO_Approval";
            $this->data['page_content'] = "Sales/INV/approval";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/INV/approval.js?v=' . time() . '"></script>';

            $this->load->view($this->layout, $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam menampilkan halaman approval."
            ]);
        }
    }

    // Fungsi DT_listdata_approval: Mengambil data Sales Invoice yang perlu di-approve
    public function DT_listdata_approval()
    {
        try {
            $query = "
                SELECT 
                    inv.Invoice_ID, 
                    inv.Invoice_Number, 
                    inv.Invoice_Date, 
                    inv.Due_Date, 
                    inv.Account_ID, 
                    inv.Invoice_Status, 
                    acc.Account_Name, 
                    acc.Account_Address 
                FROM 
                    $this->ttrx_hdr_sls_invoice inv
                JOIN 
                    tmst_account acc 
                ON 
                    inv.Account_ID = acc.SysId
            ";

            $search = array('inv.Invoice_Number', 'acc.Account_Name');
            $where = array('inv.Is_Cancel' => 0, 'inv.Approve' => 0);
            $isWhere = null;

            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data approval SI."
            ]);
        }
    }

    // Fungsi verify: Memverifikasi atau menolak Sales Invoice
    public function verify()
    {
        try {
            $account_ids = $this->input->post('invoice_Ids');
            $is_verified = $this->input->post('is_verified');

            $this->db->trans_start();

            foreach ($account_ids as $account_id) {
                $this->db->where('Invoice_ID', $account_id);
                $this->db->update($this->ttrx_hdr_sls_invoice, [
                    'Approve' => $is_verified,
                    'Approve_By' => $this->session->userdata('impsys_nik'),
                    'Approve_At' => date('Y-m-d')
                ]);
                // 
                $this->db->select('SI_Number, Invoice_Number');
                $this->db->from($this->ttrx_hdr_sls_invoice);
                $this->db->where('Invoice_ID', $account_id);
                $query = $this->db->get();

                if ($query->num_rows() > 0 && $is_verified == 1) {
                    $row = $query->row();
                    $si_number = $row->SI_Number;
                    $invoice_number = $row->Invoice_Number;

                    $shipping_update_data = [
                        'Invoice_Number' => $invoice_number,
                        'Last_Updated_by' => $this->session->userdata('impsys_nik'),
                        'Last_Updated_at' => date('Y-m-d H:i:s')
                    ];
                    $this->db->where('ShipInst_Number', $si_number);
                    $this->db->update('ttrx_hdr_shipping_ins', $shipping_update_data);
                }
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
            $this->db->trans_rollback();
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat memverifikasi data."
            ]);
        }
    }

    // Fungsi edit: Mengambil data Sales Invoice untuk ditampilkan di form edit
    public function edit()
    {
        try {
            $invoice_id = $this->input->post('invoice_ID');

            $this->db->select('*');
            $this->db->from($this->ttrx_hdr_sls_invoice);
            $this->db->where('Invoice_ID', $invoice_id);
            $data_hdr = $this->db->get()->row();

            // $this->db->select('*');
            // $this->db->from($this->ttrx_dtl_sls_invoice);
            // $this->db->where('Invoice_Number', $data_hdr->Invoice_Number);
            // $data_dtl = $this->db->get()->result();

            $this->db->select('ttrx_dtl_sls_invoice.*, tmst_item.Brand, tmst_item.Item_Color, tmst_unit_type.Uom');
            $this->db->from($this->ttrx_dtl_sls_invoice);
            $this->db->join('tmst_item', 'tmst_item.Item_Code = ttrx_dtl_sls_invoice.Item_Code', 'left');
            $this->db->join('tmst_unit_type', 'tmst_unit_type.Unit_Type_ID = tmst_item.Uom_Id', 'left');
            $this->db->where('ttrx_dtl_sls_invoice.Invoice_Number', $data_hdr->Invoice_Number);
            $data_dtl = $this->db->get()->result();

            $response = [
                "code"      => 200,
                "msg"       => "Berhasil Mendapatkan Data!",
                "data_hdr"  => $data_hdr,
                "data_dtl"  => $data_dtl,
            ];

            echo json_encode($response);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data Sales Invoice."
            ]);
        }
    }

    // Fungsi cancel_status: Menutup status Sales Invoice
    public function cancel_status()
    {
        try {
            $invoice_id = $this->input->post('invoice_Id');

            // Ambil baris yang sesuai dengan Invoice_ID dari tabel ttrx_hdr_sls_invoice
            $row = $this->db->get_where($this->ttrx_hdr_sls_invoice, ['Invoice_ID' => $invoice_id])->row();

            if ($row) {
                // Cek apakah sudah ada data di tabel ttrx_dtl_receive_invoice terkait dengan invoice
                $this->db->where('id_invoice', $invoice_id);
                $receiveCheck = $this->db->get('ttrx_dtl_receive_invoice')->row();

                if ($receiveCheck) {
                    // Jika sudah ada data di tabel ttrx_dtl_receive_invoice, kirimkan respons error
                    return $this->help->Fn_resulting_response([
                        "code" => 400,
                        "msg" => "Invoice ini sudah terkait dengan data penerimaan dan tidak dapat dibatalkan!"
                    ]);
                }

                // Update kolom Is_Cancel menjadi 1 dan simpan alasan pembatalan
                $this->db->where('Invoice_ID', $invoice_id);
                $this->db->update($this->ttrx_hdr_sls_invoice, [
                    'Is_Cancel' => 1,
                    'Cancel_Reason' => $this->input->post('reason'),
                    'Cancel_By'   => $this->session->userdata('impsys_nik'),
                    'Cancel_At'   => date('Y-m-d')
                ]);

                // Cek apakah status Approve adalah 1 sebelum mengosongkan Invoice_Number di ttrx_hdr_shipping_ins
                if ($row->Approve == 1) {
                    $this->db->where('Invoice_Number', $row->Invoice_Number);
                    $this->db->update('ttrx_hdr_shipping_ins', [
                        'Invoice_Number' => NULL,
                        'Last_Updated_by' => $this->session->userdata('impsys_nik'),
                        'Last_Updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

                return $this->help->Fn_resulting_response([
                    "code" => 200,
                    "msg" => "Data telah dicancel!"
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
                "msg" => "Terjadi kesalahan saat menonaktifkan status Invoice."
            ]);
        }
    }


    // Fungsi calculate_tax: Menghitung pajak berdasarkan jumlah total dan tarif pajak
    public function calculate_tax()
    {
        try {
            $totalAmount = $this->input->post('totalAmount');
            $tax1Rates = $this->input->post('tax1Rate');
            $tax2Rates = $this->input->post('tax2Rate');

            $result = 0;
            for ($i = 0; $i < count($tax1Rates); $i++) {
                $resultTax1Rate = $this->tax->calculate_tax($totalAmount, $tax1Rates[$i]);
                $resultTax2Rate = $this->tax->calculate_tax($totalAmount, $tax2Rates[$i]);
                $result += $resultTax1Rate + $resultTax2Rate;
            }

            return $this->help->Fn_resulting_response([
                "code" => 200,
                "msg" => "Pajak berhasil dihitung.",
                "result" => $result
            ]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat menghitung pajak."
            ]);
        }
    }

    // 
    function export_pdf_so_inv($invoice_id)
    {

        try {
            $this->db->select('h.*, a.Account_Name, a.Account_Address, c.Telephone, c.Fax, curr.words_en');
            $this->db->from($this->ttrx_hdr_sls_invoice . ' as h');
            $this->db->where('Invoice_ID', $invoice_id);
            $this->db->join('tmst_account as a', 'h.Account_ID = a.SysId', 'left');
            $this->db->join('tmst_account_contact as c', 'a.Account_Code = c.Account_Code', 'left');

            $this->db->join('tmst_currency as curr', 'h.Currency_ID = curr.Currency_ID', 'left');
            $data_hdr = $this->db->get()->row();

            // $this->db->select('dtl_sls_inv.*, (dtl_sls_inv.TotalPrice - dtl_sls_inv.Disc_Value) as FinalPrice');
            // $this->db->from($this->ttrx_dtl_sls_invoice . ' dtl_sls_inv'); // Tambahkan spasi antara nama tabel dan alias
            // $this->db->where('dtl_sls_inv.Invoice_Number', $data_hdr->Invoice_Number); // Gunakan alias di where
            // $data_dtl = $this->db->query("SELECT
            // dtl_sls_inv.Detail_ID, dtl_sls_inv.Invoice_Number, dtl_sls_inv.Item_Description, dtl_sls_inv.Qty, dtl_sls_inv.UnitPrice, dtl_sls_inv.Base_UnitPrice, dtl_sls_inv.Disc_percentage, dtl_sls_inv.Disc_Value, dtl_sls_inv.Tax_Code1, dtl_sls_inv.tax_percentage1, dtl_sls_inv.Tax_operator1, dtl_sls_inv.Tax_Amount1, dtl_sls_inv.Tax_Code2, dtl_sls_inv.Tax_Percentage2, dtl_sls_inv.Tax_Operator2, dtl_sls_inv.Tax_Amount2, dtl_sls_inv.TotalPrice, dtl_sls_inv.Base_TotalPrice, dtl_sls_inv.Product_Size, dtl_sls_inv.LstItemAccount, dtl_sls_inv.DO_Number, dtl_sls_inv.Ref_Number, dtl_sls_inv.isFreeItem, dtl_sls_inv.SO_Number,
            // (dtl_sls_inv.TotalPrice - dtl_sls_inv.Disc_Value) as FinalPrice,
            // item.Item_Length, item.Item_Width, item.Item_Height, item.LWH_Unit,
            // COALESCE(Item_CodeAlias, CONCAT('-',dtl_sls_inv.Item_Code)) as Item_Code, COALESCE(Item_NameAlias, CONCAT('-',item.Item_Name)) as Item_Name
            // FROM $this->ttrx_dtl_sls_invoice as dtl_sls_inv
            // JOIN tmst_item item on dtl_sls_inv.Item_Code = item.Item_Code
            // JOIN ttrx_hdr_sls_invoice as hdr_sls_inv on dtl_sls_inv.Invoice_Number = hdr_sls_inv.Invoice_Number
            // LEFT JOIN tmst_item_aliases item_cs on dtl_sls_inv.Item_Code = item_cs.Item_Code and hdr_sls_inv.Account_ID = item_cs.Account_ID
            // WHERE dtl_sls_inv.Invoice_Number = '$data_hdr->Invoice_Number'
            // ")->result();
            $data_dtl = $this->db->query("SELECT
            dtl_sls_inv.Detail_ID, dtl_sls_inv.Invoice_Number, dtl_sls_inv.Tax_Code1, dtl_sls_inv.Item_Description, dtl_sls_inv.Qty, unit.Uom, dtl_sls_inv.UnitPrice, dtl_sls_inv.Base_UnitPrice, dtl_sls_inv.Disc_percentage, dtl_sls_inv.Disc_Value, dtl_sls_inv.Tax_Code1, dtl_sls_inv.tax_percentage1, dtl_sls_inv.Tax_operator1, dtl_sls_inv.Tax_Amount1, dtl_sls_inv.Tax_Code2, dtl_sls_inv.Tax_Percentage2, dtl_sls_inv.Tax_Operator2, dtl_sls_inv.Tax_Amount2, dtl_sls_inv.TotalPrice, dtl_sls_inv.Base_TotalPrice, dtl_sls_inv.Product_Size, dtl_sls_inv.LstItemAccount, dtl_sls_inv.DO_Number, dtl_sls_inv.Ref_Number, dtl_sls_inv.isFreeItem, dtl_sls_inv.SO_Number,
            (dtl_sls_inv.TotalPrice - dtl_sls_inv.Disc_Value) as FinalPrice,
            item.Item_Length, item.Item_Width, item.Item_Height, item.LWH_Unit,
            COALESCE(Item_CodeAlias, CONCAT('-',dtl_sls_inv.Item_Code)) as Item_Code, COALESCE(Item_NameAlias, CONCAT('-',item.Item_Name)) as Item_Name,
            dtl_shp.Secondary_Qty, dtl_shp.Secondary_Uom
            FROM ttrx_dtl_sls_invoice as dtl_sls_inv
            JOIN tmst_item item on dtl_sls_inv.Item_Code = item.Item_Code
            join tmst_unit_type unit on item.Uom_Id = unit.Unit_Type_ID 
            JOIN ttrx_hdr_sls_invoice as hdr_sls_inv on dtl_sls_inv.Invoice_Number = hdr_sls_inv.Invoice_Number
            LEFT JOIN tmst_item_aliases item_cs on dtl_sls_inv.Item_Code = item_cs.Item_Code and hdr_sls_inv.Account_ID = item_cs.Account_ID
            left join ttrx_hdr_shipping_ins hdr_shp on hdr_sls_inv.SI_Number = hdr_shp.ShipInst_Number 
            left join ttrx_dtl_shipping_ins dtl_shp on hdr_shp.SysId = dtl_shp.SysId_Hdr and  dtl_sls_inv.Item_Code = dtl_shp.Item_Code
            WHERE dtl_sls_inv.Invoice_Number = '$data_hdr->Invoice_Number'
            order by LTRIM(COALESCE(Item_NameAlias, CONCAT('-',item.Item_Name))) ASC
            ")->result();


            // =================== HITUNGAN EINSTAIN ===================
            // Proses konversi Product_Size secara dinamis
            // foreach ($data_dtl as &$row) {
            //     // Misalnya, Product_Size dalam format '60 x 7 x 4 CM'
            //     $size = $row->Product_Size;

            //     // Gunakan regular expression untuk mengambil satuan (mis. 'CM') dan dimensi
            //     preg_match('/([0-9 x]+)([A-Z]+)/i', $size, $matches);

            //     // $matches[1] akan berisi '60 x 7 x 4'
            //     // $matches[2] akan berisi 'CM' (satuan)
            //     $dimensions = explode('x', trim($matches[1])); // Pisahkan dimensi
            //     $unit = trim($matches[2]); // Ambil satuan (mis. CM)

            //     // Variabel untuk menyimpan dimensi dalam cm sebelum konversi
            //     $length_cm = 0;
            //     $width_cm = 0;
            //     $height_cm = 0;

            //     // Iterasi setiap dimensi dan konversi nilainya
            //     foreach ($dimensions as $index => $dim) {
            //         // Hilangkan spasi dan pastikan $dim adalah angka yang valid
            //         $dim = trim($dim); // Trim untuk menghapus spasi di sekitar

            //         // Cek apakah $dim adalah nilai numerik
            //         if (is_numeric($dim)) {
            //             // Simpan nilai cm sebelum konversi
            //             if ($index == 0) {
            //                 $length_cm = $dim;
            //             } elseif ($index == 1) {
            //                 $width_cm = $dim;
            //             } elseif ($index == 2) {
            //                 $height_cm = $dim;
            //             }

            //             // Konversi setiap dimensi menggunakan satuan yang diambil (CM dalam hal ini)
            //             $convertedValue = $this->m_wh->convertLength($dim, $unit, 'MM');

            //             // Dinamis menambahkan nilai hasil konversi ke properti baru di array
            //             $row->{'Dimension_' . ($index + 1) . '_MM'} = $convertedValue;
            //         } else {
            //             // Jika $dim bukan angka, tampilkan error atau set nilai default
            //             $row->{'Dimension_' . ($index + 1) . '_MM'} = 0; // Nilai default jika tidak valid
            //         }
            //     }

            //     // Setelah semua dimensi (Panjang, Lebar, Tinggi) didapatkan, hitung volume dalam m³ berdasarkan rumus
            //     if ($length_cm > 0 && $width_cm > 0 && $height_cm > 0 && is_numeric($row->Qty)) {
            //         // Hitung volume dalam meter kubik (m³) berdasarkan rumus ((P x L x T)/1,000,000) * Qty
            //         $volume_m3 = (($length_cm * $width_cm * $height_cm) / 1000000) * $row->Qty;

            //         // Gunakan floatval untuk memastikan hasil float, lalu str_replace untuk mengganti titik dengan koma
            //         $volume_m3 = str_replace('.', ',', floatval($volume_m3));

            //         // Simpan hasil volume ke properti baru dalam m³
            //         $row->Volume_M3 = $volume_m3;
            //     } else {
            //         // Jika dimensi tidak valid, set volume menjadi 0
            //         $row->Volume_M3 = str_replace('.', ',', 0);
            //     }
            // }


            $response = [
                "code"      => 200,
                "msg"       => "Berhasil Mendapatkan Data!",
                "data_hdr"  => $data_hdr,
                "data_dtl"  => $data_dtl,
            ];


            $paper = 'A4';
            $orientation = "portrait";
            $html = $this->load->view('Sales/INV/export/pdf-sales-order-inv', $response, true);
            // 
            $invoiceDate = date('d F Y', strtotime($data_hdr->Invoice_Date));  // Tanggal format Indonesia
            $customerName = $data_hdr->Account_Name ?: ''; // Jika tidak ada nama, gunakan default
            $name_file = "Invoice $customerName $invoiceDate";  // Gabungkan menjadi nama file
            // 
            $this->load->library('pdfgenerator');
            // 

            $this->pdfgenerator->generate($html, $name_file, $paper, $orientation);
            // 
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data Sales Invoice."
            ]);
        }
        // ----------------- GET DATA -------------- //

    }
}
