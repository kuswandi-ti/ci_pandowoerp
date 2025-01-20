<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class SalesReturn extends CI_Controller
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
    // 
    protected $ttrx_hdr_shipping_ins = 'ttrx_hdr_shipping_ins';
    protected $ttrx_dtl_shipping_ins = 'ttrx_dtl_shipping_ins';
    protected $ttrx_hdr_sales_return = 'ttrx_hdr_sales_return';
    //    
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
            $this->data['page_title'] = "Data Sales Return";
            $this->data['page_group'] = "SR";
            $this->data['page_content'] = "Sales/SR/sales_return";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/SR/sales_return.js?v=' . time() . '"></script>';

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

            // Mengambil data warehouse
            $warehouses = $this->db->query("SELECT * FROM qview_tmst_warehouse_active WHERE Item_Category_ID in (3,4)")->result_array();

            $warehouse_data = [];
            foreach ($warehouses as $row) {
                $warehouse_data[$row['Warehouse_ID']] = $row['Warehouse_Name'];
            }


            // Kirim data ke view
            $this->data['warehouses'] = $warehouse_data;

            $this->load->view($this->layout, $this->data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data."
            ]);
        }
    }

    public function DT_listdata()
    {

        try {
            $query  = "SELECT hdr_sr.SysId, hdr_sr.SR_Number, hdr_sr.SR_Date, tmt_a.Account_Name, hdr_sr.Approve, hdr_sr.Is_Cancel                      
                    FROM 
                        $this->ttrx_hdr_sales_return hdr_sr
                    JOIN 
                        $this->tmst_account tmt_a
                    ON 
                        hdr_sr.Account_ID = tmt_a.SysId";

            $search = array('SR_Number', 'SR_Date');
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

    // Fungsi DT_listdata_shipping: Mengambil data pengiriman untuk DataTables
    public function DT_listdata_shipping()
    {
        try {
            //         // Ambil selectedShipInstNumber dari request
            //         $selectedShipInstNumber = $this->input->post('selectedShipInstNumber');

            //         // Query untuk mengambil data dari ttrx_hdr_shipping_ins dan ttrx_dtl_shipping_ins
            //         $query = "
            //     SELECT   
            //         dtl.SysId AS ItemSysId,
            //         dtl.Item_Code,
            //         dtl.Item_Name,
            //         dtl.Dimension AS Dimension_Info,
            //         dtl.Qty AS Qty_Info,
            //         dtl.Warehouse_Qty,
            //         dtl.Uom,
            //         dtl.SO_Number,
            //         dtl.Notes,
            //         i.Item_Color AS Item_Color,
            //         i.Model AS Model,
            //         i.Brand AS Brand,
            //         ut.Uom AS Uom
            //     FROM ttrx_hdr_shipping_ins hdr
            //     JOIN ttrx_dtl_shipping_ins dtl ON hdr.SysId = dtl.SysId_Hdr
            //     LEFT JOIN tmst_item i ON dtl.Item_Code = i.Item_Code
            //     LEFT JOIN tmst_unit_type ut ON i.Uom_Id = ut.Unit_Type_ID
            // ";

            //         // Kolom pencarian yang dapat digunakan di tabel frontend
            //         $search = array('dtl.Item_Code', 'dtl.Item_Name', 'dtl.SO_Number', 'hdr.ShipInst_Number');

            //         // Kondisi WHERE berdasarkan nomor instruksi pengiriman yang diterima dari frontend
            //         $where = array('hdr.ShipInst_Number' => $selectedShipInstNumber);
            //         $iswhere = null;

            //         // Mengembalikan data dalam format JSON untuk DataTables di frontend
            //         header('Content-Type: application/json');
            //         echo $this->M_Datatables->get_tables_query($query, $search, $where, $iswhere);
            //     } catch (Exception $e) {
            //         log_message('error', $e->getMessage());
            //         return $this->help->Fn_resulting_response([
            //             "code" => 500,
            //             "msg" => "Terjadi kesalahan dalam mengambil daftar item."
            //         ]);
            //     }

            // Ambil Account_ID dari request
            $accountId = $this->input->post('Account_ID');
            $startDate = $this->input->post('From_Date');
            $endDate = $this->input->post('To_Date');
            // Query untuk mengambil data dari ttrx_hdr_shipping_ins dan tmst_account_address
            $query = "
                    SELECT  
                        hdr_shp.SysId, 
                        hdr_shp.ShipInst_Number,
                        hdr_shp.Invoice_Number,
                        adr.Account_Name
                    FROM $this->ttrx_hdr_shipping_ins hdr_shp
                    LEFT JOIN $this->tmst_account adr ON hdr_shp.Account_ID = adr.SysId
                    ";

            // Kolom pencarian yang dapat digunakan di tabel frontend
            $search = array('hdr_shp.ShipInst_Number');

            // Kondisi WHERE berdasarkan Account_ID
            // Membuat kondisi WHERE
            $where = [];
            $where["DATE_FORMAT(hdr_shp.ShipInst_Date, '%Y-%m-%d') >"] = $startDate;
            $where["DATE_FORMAT(hdr_shp.ShipInst_Date, '%Y-%m-%d') <"] = $endDate;
            $where['hdr_shp.Account_ID'] = $accountId; // Tambahkan kondisi untuk Account_ID
            $iswhere = null;

            // Mengembalikan data dalam format JSON untuk DataTables di frontend
            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_query($query, $search, $where, $iswhere);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil data pengiriman."
            ]);
        }
    }

    // Fungsi untuk menampilkan data dummy ke DataTables
    public function DT_modallistofitem()
    {
        try {
            // Ambil selectedShipInstNumber dari request
            // $selectedShipInstNumber = 'SI240829-00001';
            // $selectedShipInstNumber = ['SI240829-00001', 'SI240830-00001', 'SI240905-00002'];
            $selectedShipInstNumber = json_decode($this->input->post('selectedShipInstNumbers'), true);

            // Query untuk mengambil data dari ttrx_hdr_shipping_ins dan ttrx_dtl_shipping_ins
            // Query untuk mengambil data dari view `qview_si_so`
            $query =
                "
                    SELECT
                        ShipInst_Number,
                        sysId_dtl_shp AS ItemSysId,
                        Item_Code,
                        Item_Name,
                        Dimension_Info,
                        Qty_Shiped AS Qty_Info,
                        Warehouse_Qty,
                        Uom,
                        Currency_Symbol,
                        Item_Price,  
                        SO_Number,
                        Note AS Notes,
                        Item_Color,
                        Model,
                        Brand,
                        ShipInst_Date
                    FROM bicarase_pandowo_db.qview_si_so
                    ";

            // Kolom pencarian yang dapat digunakan di tabel frontend
            $search = array('ShipInst_Number', 'Item_Code', 'Item_Name', 'SO_Number', 'ShipInst_Number');

            // Kondisi WHERE berdasarkan nomor instruksi pengiriman yang diterima dari frontend
            $where = array('ShipInst_Number' => $selectedShipInstNumber);
            $iswhere = null;

            // Mengembalikan data dalam format JSON untuk DataTables di frontend
            header('Content-Type: application/json');
            echo $this->get_tables_where_in(
                $query,
                $search,
                $where,
                $iswhere
            );
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil daftar item."
            ]);
        }
    }

    function get_tables_where_in($query, $cari, $where, $iswhere)
    {
        // Ambil data yang di ketik user pada textbox pencarian
        $search = htmlspecialchars($_POST['search']['value']);
        // Ambil data limit per page
        $limit = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['length']}");
        // Ambil data start
        $start = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['start']}");

        if ($where != null) {
            $setWhere = array();
            foreach ($where as $key => $value) {
                // Jika $value adalah array, gunakan IN
                if (is_array($value)) {
                    $setWhere[] = $key . " IN ('" . implode("', '", array_map('addslashes', $value)) . "')";
                } else {
                    $setWhere[] = $key . "='" . addslashes($value) . "'";
                }
            }
            $fwhere = implode(' AND ', $setWhere);

            if (!empty($iswhere)) {
                $sql = $this->db->query($query . " WHERE $iswhere AND " . $fwhere);
            } else {
                $sql = $this->db->query($query . " WHERE " . $fwhere);
            }
            $sql_count = $sql->num_rows();

            $cari = implode(" LIKE '%" . $search . "%' OR ", $cari) . " LIKE '%" . $search . "%'";

            // Untuk mengambil nama field yg menjadi acuan untuk sorting
            $order_field = $_POST['order'][0]['column'];

            // Untuk menentukan order by "ASC" atau "DESC"
            $order_ascdesc = $_POST['order'][0]['dir'];

            // Tambahkan ORDER BY ShipInst_Date ASC
            $order = " ORDER BY ShipInst_Date ASC, " . $_POST['columns'][$order_field]['data'] . " " . $order_ascdesc;

            if (!empty($iswhere)) {
                $sql_data = $this->db->query($query . " WHERE $iswhere AND " . $fwhere . " AND (" . $cari . ")" . $order . " LIMIT " . $limit . " OFFSET " . $start);
            } else {
                $sql_data = $this->db->query($query . " WHERE " . $fwhere . " AND (" . $cari . ")" . $order . " LIMIT " . $limit . " OFFSET " . $start);
            }

            if (isset($search)) {
                if (!empty($iswhere)) {
                    $sql_cari =  $this->db->query($query . " WHERE $iswhere AND " . $fwhere . " AND (" . $cari . ")");
                } else {
                    $sql_cari =  $this->db->query($query . " WHERE " . $fwhere . " AND (" . $cari . ")");
                }
                $sql_filter_count = $sql_cari->num_rows();
            } else {
                if (!empty($iswhere)) {
                    $sql_filter = $this->db->query($query . " WHERE $iswhere");
                } else {
                    $sql_filter = $this->db->query($query . " WHERE " . $fwhere);
                }
                $sql_filter_count = $sql_filter->num_rows();
            }
            $data = $sql_data->result_array();
        } else {
            if (!empty($iswhere)) {
                $sql = $this->db->query($query . " WHERE  $iswhere ");
            } else {
                $sql = $this->db->query($query);
            }
            $sql_count = $sql->num_rows();

            $cari = implode(" LIKE '%" . $search . "%' OR ", $cari) . " LIKE '%" . $search . "%'";

            // Untuk mengambil nama field yg menjadi acuan untuk sorting
            $order_field = $_POST['order'][0]['column'];

            // Untuk menentukan order by "ASC" atau "DESC"
            $order_ascdesc = $_POST['order'][0]['dir'];

            // Tambahkan ORDER BY ShipInst_Date ASC
            $order = " ORDER BY ShipInst_Date ASC, " . $_POST['columns'][$order_field]['data'] . " " . $order_ascdesc;

            if (!empty($iswhere)) {
                $sql_data = $this->db->query($query . " WHERE $iswhere AND (" . $cari . ")" . $order . " LIMIT " . $limit . " OFFSET " . $start);
            } else {
                $sql_data = $this->db->query($query . " WHERE (" . $cari . ")" . $order . " LIMIT " . $limit . " OFFSET " . $start);
            }

            if (isset($search)) {
                if (!empty($iswhere)) {
                    $sql_cari =  $this->db->query($query . " WHERE $iswhere AND (" . $cari . ")");
                } else {
                    $sql_cari =  $this->db->query($query . " WHERE (" . $cari . ")");
                }
                $sql_filter_count = $sql_cari->num_rows();
            } else {
                if (!empty($iswhere)) {
                    $sql_filter = $this->db->query($query . " WHERE $iswhere");
                } else {
                    $sql_filter = $this->db->query($query);
                }
                $sql_filter_count = $sql_filter->num_rows();
            }
            $data = $sql_data->result_array();
        }

        $callback = array(
            'draw' => $_POST['draw'], // Ini dari datatablenya    
            'recordsTotal' => $sql_count,
            'recordsFiltered' => $sql_filter_count,
            'data' => $data
        );
        return json_encode($callback); // Convert array $callback ke json
    }

    public function getSalesReturnData()
    {
        // Ambil array nomor pengiriman dari request
        $selectedShipInstNumbers = json_decode($this->input->post('selectedShipInstNumbers'), true);

        // Cek apakah array nomor pengiriman tidak kosong
        if (!empty($selectedShipInstNumbers)) {
            // Query untuk mengambil data Sales Return dengan where_in
            $this->db->select('hdr.Approve, dtl.SysId, dtl.SO_Number, dtl.Item_Code, dtl.Qty');
            $this->db->from('ttrx_hdr_sales_return hdr');
            $this->db->join('ttrx_dtl_sales_return dtl', 'hdr.SysId = dtl.SysId_Hdr');
            $this->db->where('hdr.Is_Cancel', 0);
            $this->db->where('hdr.Approve !=', 2);
            $this->db->where_in('dtl.SI_Number', $selectedShipInstNumbers); // Menggunakan where_in untuk SI_Number

            // Eksekusi query dan ambil hasilnya
            $sales_return = $this->db->get()->result_array();

            // Mengirim data dalam format JSON
            echo json_encode(['sales_return' => $sales_return]);
        } else {
            // Jika tidak ada nomor pengiriman, kirimkan response kosong
            echo json_encode(['sales_return' => []]);
        }
    }


    // Fungsi approval: Menampilkan halaman Approval Sales Order
    public function approval()
    {

        try {
            $this->data['page_title']   = "Approval Sales Return";
            $this->data['page_group'] = "SR_Approval";
            $this->data['page_content'] = "Sales/SR/approval";
            $this->data['script_page'] =  '<script src="' . base_url() . 'assets/sales-script/SR/approval.js?v=' . time() . '"></script>';

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
            $query  = "SELECT hdr_sr.SysId, hdr_sr.SR_Number, hdr_sr.SR_Date, tmt_a.Account_Name, 
                              hdr_sr.Approve, hdr_sr.Is_Cancel                      
                   FROM $this->ttrx_hdr_sales_return hdr_sr
                   JOIN $this->tmst_account tmt_a ON hdr_sr.Account_ID = tmt_a.SysId";
            $search = array('hdr_sr.SR_Number');
            $where  = array('hdr_sr.Is_Cancel' => 0, 'hdr_sr.Approve' => 0);
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

            // Ambil baris yang sesuai dengan SysId dari tabel ttrx_hdr_sales_return
            $row = $this->db->get_where('ttrx_hdr_sales_return', ['SysId' => $sysId])->row();

            if ($row) {
                // Mulai transaksi database
                $this->db->trans_begin();

                // Update kolom Is_Cancel menjadi 1 dan simpan alasan pembatalan
                $this->db->where('SysId', $sysId);
                $this->db->update('ttrx_hdr_sales_return', [
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
                        "msg" => "Data telah dibatalkan!"
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

            // // Loop melalui setiap SysId dan lakukan update
            foreach ($sysIds as $sysId) {

                $sqlSelect = "
                SELECT sr.Qty, sr.warehouse_id, dtl_so.Item_Price , hdr_so.Currency_Rate 
                    FROM ttrx_hdr_sales_return hdr_sr
                    join ttrx_dtl_sales_return sr on hdr_sr.SysId = sr.SysId_Hdr 
                    join ttrxhdr_sls_salesorder hdr_so on sr.SO_Number = hdr_so.SO_Number
                    join ttrxdtl_sls_salesorder dtl_so on hdr_so.SysId = dtl_so.SysId_Hdr and sr.Item_Code = dtl_so.Item_Code 
                where hdr_sr.SysId =  $sysId
                group by sr.SysId, sr.Item_Code, hdr_so.SO_Number, dtl_so.SysId;
                ";

                // Jalankan query SELECT
                $queryResult = $this->db->query($sqlSelect)->result();

                $totalAmount = 0;
                $totalBaseAmount = 0;

                if ($isVerified != 2) {
                    // Loop melalui hasil query dan hitung amount dan base_amount
                    foreach ($queryResult as $row) {
                        $amount = $row->Qty * $row->Item_Price; // amount = Qty * Item_Price
                        $baseAmount = $amount * $row->Currency_Rate; // base_amount = amount * Currency_Rate
                        // Jumlahkan ke total jika diperlukan
                        $totalAmount += $amount;
                        $totalBaseAmount += $baseAmount;
                    }
                }

                // lalu update
                $this->db->where('SysId', $sysId);

                // Lakukan update pada tabel ttrx_hdr_sales_return
                $updateData = [
                    'Amount' => $totalAmount,
                    'Base_Amount' => $totalBaseAmount,
                    'Approve' => intval($isVerified),
                    'Approve_By' => $this->session->userdata('impsys_nik'), // Ambil NIK dari sesi pengguna
                    'Approve_At' => date('Y-m-d H:i:s') // Tanggal dan waktu saat ini
                ];

                // Cek apakah update berhasil, jika gagal, lempar exception
                if (!$this->db->update($this->ttrx_hdr_sales_return, $updateData)) {
                    throw new Exception($this->db->error()['message']);
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Transaksi gagal.');
            }
            $this->db->trans_commit();
            // Pesan sukses yang lebih relevan
            $response = [
                "code" => 200,
                "msg" => $isVerified == 2 ? "Data Sales Return telah di-reject!" : "Data Sales Return berhasil diverifikasi!"
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
            // Query untuk mengambil data header dari ttrx_hdr_sales_return
            $this->db->select('hdr_sr.*, ad.Account_Name');
            $this->db->from('ttrx_hdr_sales_return hdr_sr');
            $this->db->join('tmst_account ad', 'hdr_sr.Account_ID = ad.SysId', 'left');
            $this->db->where('hdr_sr.SysId', $sysid_hdr);
            $data_hdr = $this->db->get()->row();

            if (!$data_hdr) {
                throw new Exception("Data header tidak ditemukan");
            }

            // Ambil data detail dari ttrx_dtl_sales_return
            $this->db->select('dtl_sr.SO_Number, dtl_sr.SI_Number, dtl_sr.Item_Code, dtl_sr.Qty, dtl_sr.warehouse_id, warehouse.Warehouse_Name');
            $this->db->from('ttrx_dtl_sales_return dtl_sr');
            $this->db->join('tmst_warehouse warehouse', 'warehouse.Warehouse_ID = dtl_sr.warehouse_id', 'left');
            $this->db->where('dtl_sr.SysId_Hdr', $data_hdr->SysId);
            $data_dtl = $this->db->get()->result();

            // Array untuk menyimpan SI_Number tanpa duplikat
            $siNumbers = [];
            // Looping data detail untuk mengisi $siNumbers tanpa duplikat
            foreach ($data_dtl as $item) {
                // Hanya tambahkan SI_Number jika belum ada di array
                if (!in_array($item->SI_Number, $siNumbers)) {
                    $siNumbers[] = $item->SI_Number; // Masukkan SI_Number ke array
                }
            }

            // Jika SI_Number ada, lanjutkan query untuk mengambil daftar item pengiriman
            $data_item = [];

            if (!empty($siNumbers)) {
                // Menggunakan query builder dengan where_in
                $this->db->select('
                    sysId_dtl_shp AS ItemSysId,
                    Item_Code,
                    Item_Name,
                    Dimension_Info,
                    Qty_Shiped AS Qty_Info,
                    Warehouse_Qty,
                    Uom,
                    SO_Number,
                    Note AS Notes,
                    Item_Color,
                    Model,
                    Brand,
                    Currency_Symbol,
                    Item_Price
                ');
                $this->db->from('bicarase_pandowo_db.qview_si_so');
                $this->db->where_in('ShipInst_Number', $siNumbers); // Menggunakan where_in untuk array SI_Number

                // Eksekusi query dan ambil hasilnya
                $data_item = $this->db->get()->result();
            }

            // Gabungkan data_item ke dalam data_dtl berdasarkan SO_Number dan Item_Code
            foreach ($data_dtl as $detail) {
                foreach ($data_item as $item) {
                    if (
                        $detail->SO_Number === $item->SO_Number && $detail->Item_Code === $item->Item_Code
                    ) {
                        // Gabungkan informasi dari $data_item ke $data_dtl
                        $detail->ItemSysId = $item->ItemSysId;
                        $detail->Item_Name = $item->Item_Name;
                        $detail->Qty_Info = $item->Qty_Info;
                        $detail->Item_Price = $item->Item_Price;
                        $detail->Currency_Symbol = $item->Currency_Symbol;
                        $detail->Item_Color = $item->Item_Color;
                        $detail->Model = $item->Model;
                        $detail->Brand = $item->Brand;
                        $detail->Uom = $item->Uom;
                        break; // Setelah menemukan kecocokan, keluar dari loop data_item
                    }
                }
            }

            // Set page attributes
            $this->data['page_title'] = "Detail Sales Return"; // Ubah title sesuai kebutuhan
            $this->data['page_group'] = "Sales Return"; // Sesuaikan dengan grup halaman yang benar
            $this->data['page_content'] = "Sales/SR/sr_detail"; // Ubah path ke view yang sesuai untuk Sales Return
            $this->data['script_page'] = '<script src="' . base_url() . 'assets/sales-script/SR/detail.js?v=' . time() . '"></script>'; // Sesuaikan script path
            $this->data['data_hdr'] = $data_hdr;
            $this->data['si_numbers'] = $siNumbers;
            $this->data['data_dtl'] = $data_dtl;

            // echo "<pre>";
            // print_r($this->data);
            // echo "</pre>";
            // Load view dengan data yang sudah disiapkan
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
            // Query untuk mengambil data header dari ttrx_hdr_sales_return
            $this->db->select('hdr_sr.*, ad.Address');
            $this->db->from('ttrx_hdr_sales_return hdr_sr');
            $this->db->join('tmst_account_address ad', 'hdr_sr.Account_ID = ad.SysId', 'left');
            $this->db->where('hdr_sr.SysId', $sysid_hdr);
            $data_hdr = $this->db->get()->row();

            if (!$data_hdr) {
                throw new Exception("Data header tidak ditemukan");
            }

            // Ambil data detail dari ttrx_dtl_sales_return
            $this->db->select('dtl_sr.SysId, dtl_sr.SI_Number, dtl_sr.SO_Number, dtl_sr.Item_Code, dtl_sr.Qty, dtl_sr.warehouse_id');
            $this->db->from('ttrx_dtl_sales_return dtl_sr');
            $this->db->where('SysId_Hdr', $data_hdr->SysId);
            $data_dtl = $this->db->get()->result();

            // Array untuk menyimpan SI_Number tanpa duplikat
            $siNumbers = [];
            // Looping data detail untuk mengisi $siNumbers tanpa duplikat
            foreach ($data_dtl as $item) {
                // Hanya tambahkan SI_Number jika belum ada di array
                if (!in_array($item->SI_Number, $siNumbers)) {
                    $siNumbers[] = $item->SI_Number; // Masukkan SI_Number ke array
                }
            }
            // Jika SI_Number ada, lanjutkan query untuk mengambil daftar item pengiriman
            $data_item = [];
            if (!empty($siNumbers)) {
                // Menggunakan query builder dengan where_in
                $this->db->select('
                    sysId_dtl_shp AS ItemSysId,
                    Item_Code,
                    Item_Name,
                    Dimension_Info,
                    Qty_Shiped AS Qty_Info,
                    Warehouse_Qty,
                    Uom,
                    SO_Number,
                    Note AS Notes,
                    Item_Color,
                    Model,
                    Brand,
                    Currency_Symbol,
                    Item_Price
                ');
                $this->db->from('bicarase_pandowo_db.qview_si_so');
                $this->db->where_in('ShipInst_Number', $siNumbers); // Menggunakan where_in untuk array SI_Number

                // Eksekusi query dan ambil hasilnya
                $data_item = $this->db->get()->result();
            }
            // Nomor SO	Item Code	Item Name	Color	Brand	UOM	Qty Return	Warehouse
            // Gabungkan data_item ke dalam data_dtl berdasarkan SO_Number dan Item_Code
            foreach ($data_dtl as $detail) {
                foreach ($data_item as $item) {
                    if ($detail->SO_Number === $item->SO_Number && $detail->Item_Code === $item->Item_Code) {
                        // Gabungkan informasi dari $data_item ke $data_dtl
                        $detail->ItemSysId = $item->ItemSysId;
                        $detail->Item_Name = $item->Item_Name;
                        $detail->Qty_Info = $item->Qty_Info;
                        $detail->Item_Price = $item->Item_Price;
                        $detail->Currency_Symbol = $item->Currency_Symbol;
                        $detail->Item_Color = $item->Item_Color;
                        $detail->Model = $item->Model;
                        $detail->Brand = $item->Brand;
                        $detail->Uom = $item->Uom;
                        break; // Setelah menemukan kecocokan, keluar dari loop data_item
                    }
                }
            }

            // Membentuk respon
            $response = [
                "code" => 200,
                "msg" => "Berhasil Mendapatkan Data!",
                "si_numbers" => $siNumbers,
                "data_hdr" => $data_hdr,
                "data_dtl" => $data_dtl, // Data detail yang sudah digabung dengan item info
            ];
            // echo "<pre>";
            // print_r($data_dtl);
            echo json_encode($response);
            // echo "</pre>";
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat mengambil data."
            ]);
        }
    }

    // Fungsi DT_listdata: Mengambil data untuk DataTables
    public function store()
    {
        try {
            $state = $this->input->post('state');
            $SR_Number = ($state == 'ADD') ? $this->help->Gnrt_Identity_Monthly_Sales("SR", 5, '-') : "";

            $this->db->trans_start();

            $header_sysid = $this->input->post('sr_sysId');
            $data_header = array(
                'SR_Date' => date('Y-m-d', strtotime($this->input->post('SR_Date'))),
                // 'Account_ID' => $this->input->post('Account_ID'),
                // 'SI_Number' => $this->input->post('SI_Number'),
                'Amount' => 0, // Data dummy untuk Amount
                'Base_Amount' => 0, // Data dummy untuk Base_Amount
                'Notes' => $this->input->post('note'),
                'ip_create' => $this->input->ip_address(),
                'Approve' => 0,
                'Approve_By' => NULL,
                'Approve_At' => NULL,
                'Is_Cancel' => 0,
                'Cancel_Reason' => NULL,
                'Cancel_By' => NULL,
                'Cancel_At' => NULL,
            );

            if ($state == 'ADD') {
                $data_header['SR_Number'] = $SR_Number;
                $data_header['Account_ID'] = $this->input->post('Account_ID');
                $data_header['Created_By'] = $this->session->userdata('impsys_nik');
                $data_header['Created_At'] = date('Y-m-d');
                $data_header['Last_Updated_by'] = NULL;
                $data_header['Last_Updated_at'] = NULL;

                $this->db->insert('ttrx_hdr_sales_return', $data_header);
                $header_sysid = $this->db->insert_id();
                $msg = "Berhasil Menambah Sales Return!";
            } else {
                // Last_Updated_by dan Last_Updated_at diisi saat EDIT
                $data_header['Last_Updated_by'] = $this->session->userdata('impsys_nik');
                $data_header['Last_Updated_at'] = date('Y-m-d');

                $this->db->where('SysId', $header_sysid);
                $this->db->update('ttrx_hdr_sales_return', $data_header);

                // Hapus detail yang lama untuk diisi ulang
                $this->db->delete('ttrx_dtl_sales_return', ['SysId_Hdr' => $header_sysid]);

                $msg = "Berhasil Mengedit Sales Return!";
            }

            $detail_data = array(
                'SI_Number' => $this->input->post('siNumber'),
                'SO_Number' => $this->input->post('soNumber'),
                'Item_Code' => $this->input->post('itemCode'),
                'Item_Name' => $this->input->post('itemName'),
                'Qty' => $this->input->post('qty'),
                'warehouse_id' => $this->input->post('Warehouse_Selection')
            );

            // // Insert detail
            $this->store_DT_dtl($header_sysid, $detail_data);

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
                "msg" => "Terjadi kesalahan saat menyimpan data Sales Return."
            ]);
        }
    }

    // Fungsi store_DT_dtl: Menyimpan detail Sales Return
    private function store_DT_dtl($header_sysid, $detail_data)
    {
        try {
            $si_numbers = $detail_data['SI_Number'];
            $so_numbers = $detail_data['SO_Number'];
            $item_codes = $detail_data['Item_Code'];
            $item_names = $detail_data['Item_Name'];
            $qtys = $detail_data['Qty'];
            $wh_ids = $detail_data['warehouse_id'];

            foreach ($item_codes as $index => $item_code) {
                // Menyimpan detail ke tabel ttrx_dtl_sales_return
                $insert_detail = array(
                    'SysId' => NULL,
                    'SysId_Hdr' => $header_sysid, // Ambil SR_Number dari header
                    'SI_Number' => $si_numbers[$index],
                    'SO_Number' => $so_numbers[$index],
                    'Item_Code' => $item_code,
                    'Item_Name' => $item_names[$index],
                    'Qty' => $qtys[$index],
                    'warehouse_id' => $wh_ids[$index],
                );

                $this->db->insert('ttrx_dtl_sales_return', $insert_detail);
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan saat menyimpan detail Sales Return."
            ]);
        }
    }
}
