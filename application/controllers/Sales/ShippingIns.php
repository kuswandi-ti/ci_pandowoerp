<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ShippingIns extends CI_Controller
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
    protected $tmst_country = 'tmst_country';
    protected $tmst_beacukai = 'tmst_beacukai';
    protected $qstok_warehouse_item = 'qstok_warehouse_item';
    protected $ttrx_hdr_shipping_ins = 'ttrx_hdr_shipping_ins';
    protected $ttrx_dtl_shipping_ins = 'ttrx_dtl_shipping_ins';
    protected $ttrx_hdr_sls_invoice = 'ttrx_hdr_sls_invoice';
    protected $qview_si_so = 'qview_si_so';
    protected $tmst_warehouse = 'tmst_warehouse';
    protected $ttrxhdr_sls_salesorder = 'ttrxhdr_sls_salesorder';
    protected $qview_stok_item_for_shipping = 'qview_stok_item_for_shipping';
    protected $qview_dtl_so_qty_ost = 'qview_dtl_so_qty_ost';

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

    // Fungsi index: Menampilkan halaman utama Shipping Instruction
    public function index()
    {
        try {
            $this->data['page_title'] = "Data Surat Jalan";
            $this->data['page_group'] = "SI";
            $this->data['page_content'] = "Sales/SI/shipping_ins";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/SI/shipping_ins.js?v=' . time() . '"></script>';

            // Mengambil data Account CS
            $this->data['Account_CS'] = $this->db
                ->where('Category_ID', 'CS')
                ->where('is_active', '1')
                ->get($this->tmst_account);

            // Mengambil data negara
            $this->data['country'] = $this->db->get($this->tmst_country);

            // Mengambil jenis Bea Cukai
            $this->data['bc_types'] = $this->db->get($this->tmst_beacukai);


            // Mengambil jenis Bea Cukai
            $this->data['unit_type'] = $this->db->get($this->tmst_unit_type);

            $this->load->view($this->layout, $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data."
            ]);
        }
    }

    // Fungsi approval: Menampilkan halaman Approval Sales Order
    public function approval()
    {
        try {
            $this->data['page_title']   = "Approval Sales Order";
            $this->data['page_group'] = "SO_Approval";
            $this->data['page_content'] = "Sales/SI/approval";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/SI/approval.js?v=' . time() . '"></script>';

            $this->load->view($this->layout, $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam menampilkan halaman approval."
            ]);
        }
    }

    // Fungsi DT_listdata_approval: Mengambil data untuk approval Shipping Instruction
    public function DT_listdata_approval()
    {
        try {
            $query  = "SELECT t1.SysId, t1.ShipInst_Number, t1.ShipInst_Date, t1.ShipToAddress_ID, t1.ExpectedDeliveryDate, 
                  t1.PortOfLoading, t1.PlaceOfDelivery, t1.Carrier, t1.Sailing, t2.Account_Name 
           FROM $this->ttrx_hdr_shipping_ins t1
           JOIN tmst_account t2 ON t1.Account_ID = t2.SysId";
            $search = array('t1.ShipInst_Number');
            $where  = array('t1.Is_Cancel' => 0, 't1.Approve' => 0);
            $isWhere = null;


            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data approval."
            ]);
        }
    }

    // Fungsi cancel_status: Membatalkan status Shipping Instruction
    public function cancel_status()
    {
        try {
            $sysId = $this->input->post('sys_id');

            // Ambil baris yang sesuai dengan SysId dari tabel ttrx_hdr_shipping_ins
            $row = $this->db->get_where($this->ttrx_hdr_shipping_ins, ['SysId' => $sysId])->row();

            if ($row) {
                // Cek apakah ada pengiriman yang sudah terkait dengan invoice yang belum dibatalkan
                $this->db->where('SI_Number', $row->ShipInst_Number);
                $this->db->where('Is_Cancel', 0);
                $invoiceCheck = $this->db->get($this->ttrx_hdr_sls_invoice)->row();

                if ($invoiceCheck) {
                    // Jika pengiriman terkait dengan invoice, kirimkan respons error
                    return $this->help->Fn_resulting_response([
                        "code" => 400,
                        "msg" => "Pengiriman sudah terkait dengan invoice dan tidak dapat dibatalkan!"
                    ]);
                }

                // Mulai transaksi database
                $this->db->trans_begin();

                // Update kolom Is_Cancel menjadi 1 dan simpan alasan pembatalan
                $this->db->where('SysId', $sysId);
                $this->db->update($this->ttrx_hdr_shipping_ins, [
                    'Is_Cancel' => 1,
                    'Cancel_Reason' => $this->input->post('reason'),
                    'Cancel_By' => $this->session->userdata('impsys_nik'), // Ambil NIK pengguna dari sesi
                    'Cancel_At' => date('Y-m-d') // Simpan tanggal pembatalan
                ]);

                // Cek status transaksi
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Gagal memperbarui status pembatalan.');
                } else {
                    $this->db->trans_commit();
                    return $this->help->Fn_resulting_response([
                        "code" => 200,
                        "msg" => "Data telah dinonaktifkan!"
                    ]);
                }
            } else {
                return $this->help->Fn_resulting_response([
                    "code" => 404,
                    "msg" => "Data tidak ditemukan!"
                ]);
            }
        } catch (Exception $e) {
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat memperbarui data: " . $e->getMessage()
            ]);
        }
    }

    // Fungsi verify: Memverifikasi atau menolak Shipping Instruction
    public function verify()
    {
        try {
            $sysIds = $this->input->post('sys_ids'); // Array of SysId
            $isVerified = $this->input->post('is_verified'); // Status verifikasi

            $this->db->trans_start();

            // Loop melalui setiap SysId dan lakukan update
            foreach ($sysIds as $sysId) {
                $this->db->where('SysId', $sysId);

                // Lakukan update pada tabel ttrx_hdr_shipping_ins
                $updateData = [
                    'Approve' => $isVerified,
                    'Approve_By' => $this->session->userdata('impsys_nik'), // Ambil NIK dari sesi pengguna
                    'Approve_At' => date('Y-m-d') // Tanggal hari ini
                ];

                // Cek apakah update berhasil, jika gagal, lempar exception
                if (!$this->db->update($this->ttrx_hdr_shipping_ins, $updateData)) {
                    throw new Exception($this->db->error()['message']);
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Transaksi gagal.');
            }

            $response = [
                "code" => 200,
                "msg" => $isVerified == 2 ? "Data telah di-reject!" : "Data berhasil diverifikasi!"
            ];
            return $this->help->Fn_resulting_response($response);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan: " . $e->getMessage()
            ]);
        }
    }

    // Fungsi detail: Menampilkan detail Shipping Instruction
    public function detail($sysid_hdr)
    {
        try {
            // Query untuk mengambil data header
            $this->db->select('hdr_shp.*, ac.Account_Name, ad.Address');
            $this->db->from($this->ttrx_hdr_shipping_ins . ' hdr_shp');
            $this->db->join($this->tmst_account . ' ac', 'hdr_shp.Account_ID = ac.SysId');
            $this->db->join($this->tmst_account_address . ' ad', 'hdr_shp.ShipToAddress_ID = ad.SysId');
            $this->db->where('hdr_shp.SysId', $sysid_hdr);
            $data_hdr = $this->db->get()->row();

            // Ambil data detail
            $this->db->select('*');
            $this->db->from($this->qview_si_so);
            $this->db->where('sysId_hdr_si', $sysid_hdr);
            $data_dtl = $this->db->get()->result();

            // Ambil semua Warehouse_ID yang ada di data_dtl
            $warehouseIds = [];
            foreach ($data_dtl as $item) {
                if (isset($item->Warehouse_Qty)) {
                    $parsedQty = $this->parseWarehouseQty($item->Warehouse_Qty);
                    $warehouseIds = array_merge($warehouseIds, array_keys($parsedQty));
                    $item->Warehouse_Qty = $parsedQty;
                }
            }

            // Hapus duplikat Warehouse_ID
            $warehouseIds = array_unique($warehouseIds);

            // Query untuk mengambil informasi warehouse
            $this->db->select('*');
            $this->db->from($this->tmst_warehouse);
            $this->db->where_in('Warehouse_ID', $warehouseIds);
            $warehouses = $this->db->get()->result();

            // Buat array warehouse dengan Warehouse_ID sebagai kunci
            $warehousesMap = [];
            foreach ($warehouses as $warehouse) {
                $warehousesMap[$warehouse->Warehouse_ID] = $warehouse;
            }

            // Gabungkan data warehouse dengan data_dtl
            foreach ($data_dtl as $item) {
                if (isset($item->Warehouse_Qty) && is_array($item->Warehouse_Qty)) {
                    foreach ($item->Warehouse_Qty as $warehouseId => $qty) {
                        if (isset($warehousesMap[$warehouseId])) {
                            $item->Warehouse_Qty[$warehouseId] = [
                                'qty' => $qty,
                                'warehouse_code' => $warehousesMap[$warehouseId]->Warehouse_Code,
                                'warehouse_name' => $warehousesMap[$warehouseId]->Warehouse_Name
                            ];
                        }
                    }
                }
            }

            $this->data['page_title'] = "Detail Surat Jalan";
            $this->data['page_group'] = "SO";
            $this->data['page_content'] = "Sales/SI/si_detail";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/SI/detail.js?v=' . time() . '"></script>';
            $this->data['data_hdr'] = $data_hdr;
            $this->data['data_dtl'] = $data_dtl;

            $this->load->view($this->layout, $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam menampilkan detail."
            ]);
        }
    }

    // Fungsi edit: Mengambil data Shipping Instruction untuk ditampilkan di form edit
    public function edit()
    {
        try {
            $sysid_hdr = $this->input->post('sys_id');

            // Query untuk mengambil data header
            $this->db->select('hdr_shp.*, ad.Address');
            $this->db->from($this->ttrx_hdr_shipping_ins . ' hdr_shp');
            $this->db->join($this->tmst_account_address . ' ad', 'hdr_shp.ShipToAddress_ID = ad.SysId');
            $this->db->where('hdr_shp.SysId', $sysid_hdr);
            $data_hdr = $this->db->get()->row();

            // Ambil data detail
            $this->db->select('*');
            $this->db->from($this->qview_si_so);
            $this->db->where('sysId_hdr_si', $sysid_hdr);
            $data_dtl = $this->db->get()->result();

            // Ambil semua Warehouse_ID yang ada di data_dtl
            $warehouseIds = [];
            foreach ($data_dtl as $item) {
                if (isset($item->Warehouse_Qty)) {
                    $parsedQty = $this->parseWarehouseQty($item->Warehouse_Qty);
                    $warehouseIds = array_merge($warehouseIds, array_keys($parsedQty));
                    $item->Warehouse_Qty = $parsedQty;
                }
            }

            // Hapus duplikat Warehouse_ID
            $warehouseIds = array_unique($warehouseIds);

            // Query untuk mengambil informasi warehouse
            $this->db->select('*');
            $this->db->from($this->tmst_warehouse);
            $this->db->where_in('Warehouse_ID', $warehouseIds);
            $warehouses = $this->db->get()->result();

            // Buat array warehouse dengan Warehouse_ID sebagai kunci
            $warehousesMap = [];
            foreach ($warehouses as $warehouse) {
                $warehousesMap[$warehouse->Warehouse_ID] = $warehouse;
            }

            // Gabungkan data warehouse dengan data_dtl
            foreach ($data_dtl as $item) {
                if (isset($item->Warehouse_Qty) && is_array($item->Warehouse_Qty)) {
                    foreach ($item->Warehouse_Qty as $warehouseId => $qty) {
                        if (isset($warehousesMap[$warehouseId])) {
                            $item->Warehouse_Qty[$warehouseId] = [
                                'qty' => $qty,
                                'warehouse_code' => $warehousesMap[$warehouseId]->Warehouse_Code,
                                'warehouse_name' => $warehousesMap[$warehouseId]->Warehouse_Name
                            ];
                        }
                    }
                }
            }

            // Membentuk respon
            $response = [
                "code" => 200,
                "msg" => "Berhasil Mendapatkan Data!",
                "data_hdr" => $data_hdr,
                "data_dtl" => $data_dtl,
            ];

            echo json_encode($response);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat mengambil data."
            ]);
        }
    }

    // Fungsi parseWarehouseQty: Parsing data Warehouse Qty dari string ke array
    private function parseWarehouseQty($warehouseQty)
    {
        $result = [];
        $pairs = explode('|', $warehouseQty);
        foreach ($pairs as $pair) {
            list($warehouseId, $qty) = explode('=', $pair);
            $result[(int)$warehouseId] = floatval($qty);
        }
        return $result;
    }

    // Fungsi DT_listdata: Mengambil data untuk DataTables
    public function DT_listdata()
    {
        try {
            $query  = "SELECT 
                        t1.SysId, 
                        t1.ShipInst_Number, 
                        t1.ShipInst_Date, 
                        t1.ShipToAddress_ID, 
                        t1.ExpectedDeliveryDate, 
                        t1.PortOfLoading, 
                        t1.PlaceOfDelivery, 
                        t1.Carrier, 
                        t1.Sailing, 
                        t1.Approve, 
                        t1.Is_Cancel,                
                        t2.Address              
                    FROM 
                        $this->ttrx_hdr_shipping_ins t1
                    JOIN 
                        $this->tmst_account_address t2 
                    ON 
                        t1.ShipToAddress_ID = t2.SysId";

            $search = array('ShipInst_Number', 'ShipInst_Date', 'Address', 'ExpectedDeliveryDate');
            $where  = null;
            $isWhere = null;

            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data."
            ]);
        }
    }

    // Fungsi DT_listdata_SO: Mengambil data akun dan header Sales Order
    public function DT_listdata_SO()
    {
        try {
            $account_id = $this->input->post('account_id');

            // Ambil data dari tabel tmst_account
            $this->db->select('a.*');
            $this->db->from($this->tmst_account . ' a');
            $this->db->where('a.SysId', $account_id);
            $data_account = $this->db->get()->row_array();

            // Ambil data dari tabel ttrxhdr_sls_salesorder
            $this->db->select('s.*, d.Item_Code, v.Qty_ost_so');
            $this->db->from($this->ttrxhdr_sls_salesorder . ' s');

            // Join ke tabel ttrxdtl_sls_salesorder untuk mendapatkan Item_Code
            $this->db->join('ttrxdtl_sls_salesorder d', 's.SysId = d.SysId_Hdr', 'left');

            // Join ke view qview_outstanding_so_vs_si menggunakan SO_Number dan Item_Code
            $this->db->join('qview_outstanding_so_vs_si v', 's.SO_Number = v.SO_Number AND v.Item_Code = d.Item_Code', 'left');

            // Filter berdasarkan Customer_Id dan kondisi lainnya
            $this->db->where('s.Customer_Id', $account_id);
            $this->db->where('s.Approve', 1);
            $this->db->where('s.Is_Close', 0);

            // Dapatkan hasil query
            $data_so = $this->db->get()->result_array();

            $result = [
                'account' => $data_account,
                'sales_orders' => $data_so
            ];

            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data Surat Jalan."
            ]);
        }
    }

    // Fungsi DT_listdata_SO_details: Mengambil data detail SO berdasarkan header SO
    public function DT_listdata_SO_details()
    {
        try {
            $sysid_hdr = $this->input->post('header_ids');

            $this->db->select('*');
            $this->db->from($this->qview_dtl_so_qty_ost);
            $this->db->where_in('sysId_hdr_so', $sysid_hdr);
            $data_detail = $this->db->get()->result_array();

            header('Content-Type: application/json');
            echo json_encode($data_detail);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data detail Surat Jalan."
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
            $result = $this->db->get()->result();

            echo json_encode($result);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil daftar alamat."
            ]);
        }
    }

    // Fungsi DT_listofstock_item: Mengambil data stok item untuk DataTables
    public function DT_listofstock_item()
    {
        try {
            $Item_Code = $this->input->post('Item_Code');

            $tables = $this->qview_stok_item_for_shipping;
            $search = ['Warehouse_Name', 'Warehouse_Code'];
            $where  = array('Item_Code' => $Item_Code);
            $isWhere = 'Item_Qty > 0';

            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_where($tables, $search, $where, $isWhere);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data stok item."
            ]);
        }
    }

    // Fungsi DT_PEB_BC: Mengambil data PEB dan Bea Cukai
    public function DT_PEB_BC()
    {
        try {
            $sys_id = $this->input->post('sys_id');

            // Select only the columns needed
            $this->db->select('isExport, Is_Cancel, SysId, PEB_Number, PEB_Date, PEB_Receiver, PEB_Country, PEB_Amount, PEB_Volume, PEB_Netto, PEB_Merk, PEB_PackageNumber, BC_Type, BC_Number, BC_Date');
            $this->db->from($this->ttrx_hdr_shipping_ins);
            $this->db->where('SysId', $sys_id);

            $result = $this->db->get()->result();

            if (!empty($result)) {
                echo json_encode(['status' => 'success', 'data' => $result]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Data not found!']);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data PEB dan Bea Cukai."
            ]);
        }
    }

    // Fungsi Update_PEB_BC: Mengupdate data PEB dan Bea Cukai
    public function Update_PEB_BC()
    {
        try {
            $this->db->trans_start();

            // Get POST data from AJAX request
            $sysid = $this->input->post('sysid_for_update_peb_bc');

            $data_peb_bc = array(
                'PEB_Number' => $this->input->post('PEB_Number'),
                'PEB_Date' => date('Y-m-d', strtotime($this->input->post('PEB_Date'))),
                'PEB_Receiver' => $this->input->post('PEB_Receiver'),
                'PEB_Country' => $this->input->post('PEB_Country'),
                'PEB_Amount' => $this->input->post('PEB_Amount'),
                'PEB_Volume' => $this->input->post('PEB_Volume'),
                'PEB_Netto' => $this->input->post('PEB_Netto'),
                'PEB_Merk' => $this->input->post('PEB_Merk'),
                'PEB_PackageNumber' => $this->input->post('PEB_PackageNumber'),
                'BC_Type' => $this->input->post('BC_Type'),
                'BC_Number' => $this->input->post('BC_Number'),
                'BC_Date' => date('Y-m-d', strtotime($this->input->post('BC_Date')))
            );

            $this->db->where('SysId', $sysid);
            $this->db->update($this->ttrx_hdr_shipping_ins, $data_peb_bc);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return $this->help->Fn_resulting_response([
                    "code" => 505,
                    "msg" => "Proses penyimpanan gagal! Error: " . $this->db->error()['message']
                ]);
            } else {
                $this->db->trans_commit();
                return $this->help->Fn_resulting_response([
                    "code" => 200,
                    "msg" => "Berhasil Mengedit PEB & BC!"
                ]);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat memperbarui data PEB & BC."
            ]);
        }
    }

    // Fungsi store: Menyimpan data Shipping Instruction
    public function store()
    {

        try {
            $state = $this->input->post('state');
            $ShipInst_Number = ($state == 'ADD') ?  $this->help->Gnrt_Identity_Monthly_Sales("SI", 5, '-') : "";

            $this->db->trans_start();


            $header_sysid = $this->input->post('si_sysId');
            $data_header = array(
                'ShipInst_Date' => date('Y-m-d', strtotime($this->input->post('tanggal_shipping'))),
                'Account_ID' => $this->input->post('customer_id'),
                'ShipToAddress_ID' => $this->input->post('alamat_customer_id'),
                'ExpectedDeliveryDate' => date('Y-m-d', strtotime($this->input->post('tanggal_pengiriman'))),
                'Creation_DateTime' => NULL,
                'Invoice_Number' => NULL,
                'NotifeParty' => $this->input->post('NotifeParty'),
                'NotifePartyAddress' => $this->input->post('NotifePartyAddress'),
                'PortOfLoading' => $this->input->post('port_of_loading'),
                'PlaceOfDelivery' => $this->input->post('place_of_delivery'),
                'Carrier' => $this->input->post('carrier'),
                'Sailing' => $this->input->post('other_transport') ? $this->input->post('other_transport') : $this->input->post('sailing'),
                'ShippingMarks' => $this->input->post('ShippingMarks'),
                'InvoicePrintDate' => NULL,
                'LCNo' => $this->input->post('LCNo'),
                'LCDate' => $this->input->post('area') === 'Domestic' ? NULL :  date('Y-m-d', strtotime($this->input->post('LCDate'))),
                'LCBank' => $this->input->post('LCBank'),
                'PEB_Number' => NULL,
                'PEB_Date' => NULL,
                'PEB_Receiver' => NULL,
                'PEB_Country' => NULL,
                'PEB_Amount' => NULL,
                'PEB_Volume' => NULL,
                'PEB_Netto' => NULL,
                'PEB_Merk' => NULL,
                'PEB_PackageNumber' => NULL,
                'BC_Type' => NULL,
                'BC_Number' => NULL,
                'BC_Date' => NULL,
                'isExport' => $this->input->post('area') === 'Domestic' ? 0 : 1,
                'Approve' => 0,
                'Approve_By' => NULL,
                'Approve_At' => NULL,
                'Is_Cancel' => "",
                'Cancel_Reason' => NULL,
                'Cancel_By' => NULL,
                'Cancel_At' => NULL,
            );

            if ($state == 'ADD') {
                $data_header['ShipInst_Number'] = $ShipInst_Number;
                $data_header['Created_By'] = $this->session->userdata('impsys_nik');
                $data_header['Created_At'] = date('Y-m-d');
                $data_header['Last_Updated_by'] = NULL;
                $data_header['Last_Updated_at'] = NULL;

                $this->db->insert($this->ttrx_hdr_shipping_ins, $data_header);
                $header_sysid = $this->db->insert_id();
                // 
                $msg = "Berhasil Menambah Surat Jalan!";
            } else {
                // Last_Updated_by dan Last_Updated_at diisi dengan nilai yang sesuai saat EDIT
                $insert_header['Last_Updated_by'] = $this->session->userdata('impsys_nik');
                $insert_header['Last_Updated_at'] = date('Y-m-d');

                $this->db->where('SysId', $header_sysid);
                $this->db->update($this->ttrx_hdr_shipping_ins, $data_header);
                $this->db->delete($this->ttrx_dtl_shipping_ins, ['SysId_Hdr' => $header_sysid]);

                $sysIdDtlSoValues = $this->input->post('si_dtl_sysId');
                $sysIdDtlSoArray = explode(',', $sysIdDtlSoValues);

                foreach ($sysIdDtlSoArray as $detailId) {
                    $this->db->delete('ttrx_child_dtl_shipping_ins', ['Detail_ID' => $detailId]);
                }

                $msg = "Berhasil Mengedit Surat Jalan!";
            }

            $detail_data = array(
                'sysid_dtl_so' => $this->input->post('sysId_dtl_so'),
                'so_numbers' => $this->input->post('so_number'),
                'item_codes' => $this->input->post('item_code'),
                'item_names' => $this->input->post('item_name'),
                'dimensions' => $this->input->post('dimension'),
                'qty_shipped' => $this->input->post('qty_shipped'),
                'wh_id' => $this->input->post('wh_id'),
                'stock_item' => $this->input->post('stock_item'),
                'uoms' => $this->input->post('uom'),
                'amount' => $this->input->post('amount'),
                'secondary_qty' => $this->input->post('Secondary_Qty'),
                'secondary_uom' => $this->input->post('Secondary_Uom'),
            );


            $this->store_DT_dtl($header_sysid, $detail_data);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return $this->help->Fn_resulting_response([
                    "code" => 505,
                    "msg" => "Proses penyimpanan gagal! Error: " . $error['message']
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
                "msg" => "Terjadi kesalahan saat menyimpan data SI."
            ]);
        }
    }

    // Fungsi store_DT_dtl: Menyimpan detail Shipping Instruction
    private function store_DT_dtl($header_id, $detail_data)
    {
        try {
            $amounts = $detail_data['amount'];
            $wh_ids = $detail_data['wh_id'];
            $dmyDtl = 0;

            foreach ($detail_data['item_codes'] as $index => $item_code) {
                $isFreeItem = (floatval($amounts[$index]) == 0) ? 1 : "";

                // Pastikan nilai Secondary_Qty dan Secondary_Uom tidak kosong
                $secondary_qty = isset($detail_data['secondary_qty'][$index]) && $detail_data['secondary_qty'][$index] !== ''
                    ? $detail_data['secondary_qty'][$index]
                    : 0; // Nilai default 0 jika kosong
                $secondary_uom = isset($detail_data['secondary_uom'][$index]) && $detail_data['secondary_uom'][$index] !== ''
                    ? $detail_data['secondary_uom'][$index]
                    : null; // Nilai default null jika kosong

                $combined_wh_stock = [];
                for ($i = 0; $i < count($wh_ids); $i++) {
                    list($sysId, $pgr_itmCode) = explode('-', $wh_ids[$i]);
                    list($form_item_code, $warehouse_id_item_qty) = explode('#', $pgr_itmCode);
                    list($warehouse_id, $item_qty) = explode('=', $warehouse_id_item_qty);

                    if ($form_item_code == $item_code and $sysId == $detail_data['sysid_dtl_so'][$index]) {
                        $combined_wh_stock[] = $warehouse_id . '=' . $item_qty;

                        $insert_child_dtl = array(
                            'SysId' => NULL,
                            'Detail_ID' => $dmyDtl,
                            'Warehouse_ID' => $warehouse_id,
                            'Qty' => $item_qty,
                        );

                        $this->db->insert('ttrx_child_dtl_shipping_ins', $insert_child_dtl);
                    }
                }

                $combined_wh_stock_str = implode('|', $combined_wh_stock);

                $insert_detail = array(
                    'SysId' => NULL,
                    'SysId_Hdr' => $header_id,
                    'Item_Code' => $item_code,
                    'Item_Name' => $detail_data['item_names'][$index],
                    'Dimension' => $detail_data['dimensions'][$index],
                    'Qty' => $detail_data['qty_shipped'][$index],
                    'Warehouse_Qty' => $combined_wh_stock_str,
                    'Secondary_Qty' => $secondary_qty,
                    'Uom' => $detail_data['uoms'][$index],
                    'Secondary_Uom' => $secondary_uom,
                    'isFreeItem' => $isFreeItem,
                    'SO_Number' => $detail_data['so_numbers'][$index],
                    'Notes' => NULL
                );

                $this->db->insert($this->ttrx_dtl_shipping_ins, $insert_detail);
                $dtl_id = $this->db->insert_id();
                $data = array(
                    'Detail_ID' =>  $dtl_id
                );

                $this->db->where('Detail_ID', $dmyDtl);
                $this->db->update('ttrx_child_dtl_shipping_ins', $data);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat menyimpan detail SI."
            ]);
        }
    }

    public function monitoring()
    {
        try {
            $this->data['page_title'] = "Monitoring Item Surat Jalan";
            $this->data['page_group'] = "SJ";
            $this->data['page_content'] = "Sales/SI/monitoring";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/SI/monitoring.js?v=' . time() . '"></script>';

            $this->load->view($this->layout, $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam menampilkan halaman SO Outstanding."
            ]);
        }
    }

    // Surat jalan OST
    public function outstanding()
    {

        try {
            $this->data['page_title'] = "Outstanding Surat Jalan";
            $this->data['page_group'] = "SI";
            $this->data['page_content'] = "Sales/SI/outstanding";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/SI/outstanding.js?v=' . time() . '"></script>';

            // Mengambil data Account

            $this->load->view($this->layout, $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data."
            ]);
        }
    }

    // public function DT_listdata()
    // {
    //     try {
    //         $query  = "SELECT 
    //                     t1.SysId, 
    //                     t1.ShipInst_Number, 
    //                     t1.ShipInst_Date, 
    //                     t1.ShipToAddress_ID, 
    //                     t1.ExpectedDeliveryDate, 
    //                     t1.PortOfLoading, 
    //                     t1.PlaceOfDelivery, 
    //                     t1.Carrier, 
    //                     t1.Sailing, 
    //                     t1.Approve, 
    //                     t1.Is_Cancel,                
    //                     t2.Address              
    //                 FROM 
    //                     $this->ttrx_hdr_shipping_ins t1
    //                 JOIN 
    //                     $this->tmst_account_address t2 
    //                 ON 
    //                     t1.ShipToAddress_ID = t2.SysId";

    //         $search = array('ShipInst_Number', 'ShipInst_Date', 'Address', 'ExpectedDeliveryDate');
    //         $where  = null;
    //         $isWhere = null;

    //         header('Content-Type: application/json');
    //         echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    //     } catch (Exception $e) {
    //         log_message('error', $e->getMessage());
    //         return $this->help->Fn_resulting_response([
    //             "code" => 500,
    //             "msg" => "Terjadi kesalahan dalam mengambil data."
    //         ]);
    //     }
    // }
    public function DT_listdata_monitoring_item()
    {

        try {

            // $query  = "SELECT 
            //             t1.SysId, 
            //             t1.ShipInst_Number, 
            //             t1.ShipInst_Date, 
            //             t1.ShipToAddress_ID, 
            //             t1.ExpectedDeliveryDate, 
            //             t1.PortOfLoading, 
            //             t1.PlaceOfDelivery, 
            //             t1.Carrier, 
            //             t1.Sailing, 
            //             t1.Approve, 
            //             t1.Is_Cancel,                
            //             t2.Address              
            //         FROM 
            //             $this->ttrx_hdr_shipping_ins t1
            //         JOIN 
            //             $this->tmst_account_address t2 
            //         ON 
            //             t1.ShipToAddress_ID = t2.SysId";

            // $search = array('ShipInst_Number', 'ShipInst_Date', 'Address', 'ExpectedDeliveryDate');
            // Query untuk mengambil data dari view qview_detail_shipping
            $query = "SELECT * FROM qview_detail_shipping";

            // // Kolom yang akan dicari
            $search = array('Nomor_SO', 'Item_Code', 'Item_Name');
            $where  = null; // Bisa disesuaikan jika ada syarat tambahan
            $where  = array('Is_Cancel' => 0, 'Approve' => 1);
            $isWhere = null; // Bisa disesuaikan jika ada syarat tambahan

            // Mengambil data dari database dan mengembalikan hasil sebagai JSON
            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
        } catch (Exception $e) {
            // Menangani kesalahan
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data."
            ]);
        }
    }

    // Fungsi DT_listdata: Mengambil data untuk DataTables
    public function DT_listdata_ost()
    {
        try {
            $query  = "SELECT 
                        t1.SysId, 
                        t1.ShipInst_Number, 
                        t1.ShipInst_Date, 
                        t1.ShipToAddress_ID, 
                        t1.ExpectedDeliveryDate, 
                        t1.PortOfLoading, 
                        t1.PlaceOfDelivery, 
                        t1.Carrier, 
                        t1.Sailing, 
                        t1.Approve, 
                        t1.Is_Cancel,                
                        t2.Address              
                    FROM 
                        $this->ttrx_hdr_shipping_ins t1
                    JOIN 
                        $this->tmst_account_address t2 
                    ON 
                        t1.ShipToAddress_ID = t2.SysId";

            $search = array('ShipInst_Number', 'ShipInst_Date', 'Address', 'ExpectedDeliveryDate');
            $where  = array('Is_Cancel' => 0, 'Approve' => 1);
            $isWhere = 't1.Invoice_Number IS NULL';

            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data."
            ]);
        }
    }

    // 
    function export_pdf_si($sysid_hdr)
    {
        try {
            $sysid_hdr = explode('.', $sysid_hdr);
            // Query untuk mengambil data header

            $this->db->select('hdr_shp.*, ac.Account_Name, ad.Address, k1.nama AS Created_By');
            $this->db->from($this->ttrx_hdr_shipping_ins . ' hdr_shp');
            $this->db->join($this->tmst_account . ' ac', 'hdr_shp.Account_ID = ac.SysId');
            $this->db->join($this->tmst_account_address . ' ad', 'hdr_shp.ShipToAddress_ID = ad.SysId');
            $this->db->join('tmst_karyawan as k1', 'k1.nik = hdr_shp.Created_By', 'left');
            // $this->db->join('tmst_karyawan as k2', 'k2.nik = hdr_shp.Approve_By', 'left');
            $this->db->where('hdr_shp.SysId', $sysid_hdr[0]);
            $this->db->or_where('hdr_shp.Invoice_Number', $sysid_hdr[0]);
            $data_hdr = $this->db->get()->row();

            // Ambil data detail
            $sysid_hdr_si = $data_hdr->SysId;
            $this->db->select('*');
            $this->db->from($this->qview_si_so);
            $this->db->where('sysId_hdr_si', $sysid_hdr_si);
            $this->db->order_by('LTRIM(Item_Name)', 'ASC');
            $data_dtl = $this->db->get()->result();

            // 
            $po_numbers = [];
            foreach ($data_dtl as $row) {
                $this->db->select('PO_Number');
                $this->db->from('ttrxhdr_sls_salesorder');
                $this->db->where('SO_Number', $row->SO_Number);
                $po_result = $this->db->get()->row();

                if ($po_result) {
                    $po_numbers[$row->SO_Number] = $po_result->PO_Number;
                } else {
                    $po_numbers[$row->SO_Number] = 'N/A'; // Jika tidak ditemukan
                }
            }
            // Membentuk respon
            $response = [
                "code" => 200,
                "msg" => "Berhasil Mendapatkan Data!",
                "data_hdr" => $data_hdr,
                "data_dtl" => $data_dtl,
                "po_numbers" => $po_numbers
            ];
            // echo "<pre>";
            // print_r($response);
            // echo "</pre>";
            // die;
            // ----------------- GET DATA -------------- //
            $paper = 'A4';
            $orientation = "portrait";
            if ($sysid_hdr[1] == 0) {
                $html = $this->load->view('Sales/SI/export/pdf-shipping-ins', $response, true);
                // Buat nama file berdasarkan Account_Name dan ShipInst_Number
                $name_file = "Surat-Jalan_" . $data_hdr->Account_Name . "_" . $data_hdr->ShipInst_Number;
                // Pastikan nama file bersih dari karakter yang tidak diizinkan dalam nama file
                $name_file = preg_replace('/[^A-Za-z0-9_\-]/', '_', $name_file);
            } else {
                $html = $this->load->view('Sales/SI/export/pdf-com-inv', $response, true);
                // Buat nama file berdasarkan Account_Name dan ShipInst_Number
                $name_file = "Comm-Invoice_" . $data_hdr->Account_Name . "_" . $data_hdr->ShipInst_Number;
                // Pastikan nama file bersih dari karakter yang tidak diizinkan dalam nama file
                $name_file = preg_replace('/[^A-Za-z0-9_\-]/', '_', $name_file);
            }

            // 
            $this->load->library('pdfgenerator');
            // 

            $this->pdfgenerator->generate($html, $name_file, $paper, $orientation);
            // 
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat mengambil data."
            ]);
        }
    }
}
