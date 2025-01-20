<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{
    public $layout = 'layout';
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Master Data";
        $this->data['page_content'] = "Master/index";
        $this->data['script_page'] =  null;

        $this->load->view($this->layout, $this->data);
    }

    // ================================ MATERIAL KAYU ================================= //

    public function index_material_kayu()
    {
        $this->data['page_title'] = "Master Material Kayu";
        $this->data['page_content'] = "Master/mst_material_kayu";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/master-kayu.js"></script>';

        $this->data['materials'] = $this->db->get('tmst_material_kayu');

        $this->load->view($this->layout, $this->data);
    }

    public function toggle_status_material()
    {
        $sysid = $this->input->post('sysid');

        $row = $this->db->get_where('tmst_material_kayu', ['sysid' => $sysid])->row();

        if ($row->is_active == 1) {
            $this->db->where('sysId', $sysid);
            $this->db->update('tmst_material_kayu', [
                'is_active' => 0
            ]);

            $response = [
                "code" => 200,
                "is_active" => 0,
                "msg" => "Material kayu " . $row->deskripsi . " di non-aktifkan !"
            ];
            $this->db->insert('thst_update_material', [
                'sysid_material' => $sysid,
                'action_is' => 'Merubah status menjadi non-aktif.',
                'do_by' => $this->session->userdata('impsys_initial')
            ]);
        } else {
            $this->db->where('sysId', $sysid);
            $this->db->update('tmst_material_kayu', [
                'is_active' => 1
            ]);

            $response = [
                "code" => 200,
                "is_active" => 1,
                "msg" => "Material kayu " . $row->deskripsi . " di aktifkan !"
            ];

            $this->db->insert('thst_update_material', [
                'sysid_material' => $sysid,
                'action_is' => 'Merubah status kembali aktif.',
                'do_by' => $this->session->userdata('impsys_initial')
            ]);
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function modal_edit_material_kayu()
    {
        $sysid = $this->input->get('sysid');

        $this->data['data'] =  $this->db->get_where('tmst_material_kayu', ['sysid' => $sysid])->row();
        $this->load->view("Master/modal/m_mat_kayu", $this->data);
    }

    public function store_edit_mat_kayu()
    {
        $sysid = $this->input->post('sysid');

        $data = [
            "std_qty_lot" => floatval($this->input->post('std_qty_lot')),
            "last_updated_by" => $this->session->userdata('impsys_initial'),

        ];
        $this->db->where('sysid', $sysid);
        $this->db->update('tmst_material_kayu', $data);

        $response = [
            "code" => 200,
            "id" => $sysid,
            "qty" => floatval($this->input->post('std_qty_lot')),
            "msg" => "Data Material " . $this->input->post('inisial') . " berhasil di update !"
        ];

        return $this->help->Fn_resulting_response($response);
    }

    public function form_add_material_kayu()
    {
        $this->data['page_title'] = "Form New Material Kayu";
        $this->data['page_content'] = "Master/form_add_mat_kayu";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/form-add-matkayu.js"></script>';;

        $this->load->view($this->layout, $this->data);
    }

    public function store_add_matkayu()
    {
        $rowData = $this->db->get_where('tmst_material_kayu', [
            "tebal" => floatval($this->input->post('tebal')),
            "lebar" => floatval($this->input->post('lebar')),
            "panjang" => floatval($this->input->post('panjang')),
        ]);

        if ($rowData->num_rows() > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Material dengan Tebal, Lebar & Panjang tersebut sudah tersedia !"
            ]);
        }

        $code = $this->help->Gnrt_kode_matkyu(floatval($this->input->post('tebal')), floatval($this->input->post('lebar')), floatval($this->input->post('panjang')));
        $data = [
            "kode" => $code['kode'],
            "inisial_kode" => $code['inisial_kode'],
            "deskripsi" => strtoupper($this->input->post('deskripsi')),
            "tebal" => floatval($this->input->post('tebal')),
            "lebar" => floatval($this->input->post('lebar')),
            "panjang" => floatval($this->input->post('panjang')),
            "std_qty_lot" => floatval($this->input->post('std_qty_lot')),
            "created_at" => date('Y-m-d H:i:s'),
            "created_by" => $this->session->userdata('impsys_initial'),
        ];

        $this->db->insert('tmst_material_kayu', $data);
        $response = [
            "code" => 200,
            "msg" => "Data material " . $this->input->post('deskripsi') . " berhasil di tambahkan !"
        ];

        return $this->help->Fn_resulting_response($response);
    }

    public function modal_price_history()
    {
        $sysid = $this->input->get('sysid');

        $this->data['historis'] =  $this->db->get_where('thst_update_material', ['sysid_material' => $sysid])->result();
        $this->data['material'] =  $this->db->get_where('tmst_material_kayu', ['sysid' => $sysid])->row();
        $this->load->view("Master/modal/m_history_price", $this->data);
    }

    // ================================ KARYAWAN ================================= //
    public function index_master_karyawan()
    {
        $this->data['page_title'] = "Master Karyawan";
        $this->data['page_content'] = "Master/mst_karyawan";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/master-karyawan.js"></script>';

        $this->data['karyawans'] = $this->db->get('tmst_karyawan');

        $this->load->view($this->layout, $this->data);
    }

    public function toggle_status_karyawan()
    {
        $sysid = $this->input->post('sysid');

        $row = $this->db->get_where('tmst_karyawan', ['sysid' => $sysid])->row();

        if ($row->is_active == 1) {
            $this->db->where('sysId', $sysid);
            $this->db->update('tmst_karyawan', [
                'is_active' => 0
            ]);

            $response = [
                "code" => 200,
                "is_active" => 0,
                "msg" => "Karyawan " . $row->initial . " di non-aktifkan !"
            ];
        } else {
            $this->db->where('sysId', $sysid);
            $this->db->update('tmst_karyawan', [
                'is_active' => 1
            ]);

            $response = [
                "code" => 200,
                "is_active" => 1,
                "msg" => "Karyawan " . $row->initial . " di aktifkan !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function modal_edit_karyawan()
    {
        $sysid = $this->input->get('sysid');

        $this->data['data'] =  $this->db->get_where('tmst_karyawan', ['sysid' => $sysid])->row();
        $this->data['jabatans'] =  $this->db->get('tmst_jabatan')->result();
        $this->data['departments'] =  $this->db->get('tmst_department')->result();
        $this->load->view("Master/modal/m_karyawan", $this->data);
    }

    public function store_edit_karyawan()
    {
        $sysid = $this->input->post('sysid');

        $data = [
            "nama" => $this->input->post('nama'),
            "telp1" => $this->input->post('telp'),
            "telp2" => $this->input->post('telp2'),
            "no_ktp" => $this->input->post('no_ktp'),
            "tempat_lahir" => $this->input->post('tempat_lahir'),
            "tanggal_lahir" => $this->input->post('tanggal_lahir'),
            "alamat_ktp" => $this->input->post('alamat_ktp'),
            "domisili" => $this->input->post('domisili'),
            "jenis_kelamin" => $this->input->post('jen_kel'),
            "agama" => $this->input->post('agama'),
            "jabatan" => $this->input->post('jabatan'),
            "department" => $this->input->post('department'),
            "type_pembayaran" => $this->input->post('type_pembayaran'),
            "last_updated_by " => $this->session->userdata('impsys_initial'),

        ];
        $this->db->where('sysId', $sysid);
        $this->db->update('tmst_karyawan', $data);

        $response = [
            "code" => 200,
            "id" => $sysid,
            "msg" => "Data Karyawan " . $this->input->post('initial') . " berhasil di update !"
        ];

        return $this->help->Fn_resulting_response($response);
    }

    public function form_add_karyawan()
    {
        $this->data['page_title'] = "Form New Karyawan";
        $this->data['page_content'] = "Master/form_add_karyawan";
        $this->data['jabatans'] =  $this->db->get('tmst_jabatan')->result();
        $this->data['departments'] =  $this->db->get('tmst_department')->result();
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/form-add-karyawan.js"></script>';;

        $this->load->view($this->layout, $this->data);
    }

    public function store_new_karyawan()
    {
        $nik = $this->help->Gnrt_Identity_WithoutDateParam('NIK');

        $data = [
            "nik" => $nik,
            "nama" => $this->input->post('nama'),
            "initial" => $this->input->post('initial'),
            "no_ktp" => $this->input->post('no_ktp'),
            "tempat_lahir" => $this->input->post('tempat_lahir'),
            "tanggal_lahir" => $this->input->post('tanggal_lahir'),
            "domisili" => $this->input->post('domisili'),
            "alamat_ktp" => $this->input->post('alamat_ktp'),
            "jenis_kelamin" => $this->input->post('jenis_kelamin'),
            "agama" => $this->input->post('agama'),
            "telp1" => $this->input->post('telp1'),
            "telp2" => $this->input->post('telp2'),
            "jabatan" => $this->input->post('jabatan'),
            "department" => $this->input->post('department'),
            "type_pembayaran" => $this->input->post('type_pembayaran'),
            "since" => $this->input->post('since'),
            "is_active" => 1,
            "rec_createdtime" => date('Y-m-d H:i:s'),
            "rec_createdby" => $this->session->userdata('impsys_initial'),
            "last_updated_by" => $this->session->userdata('impsys_initial'),
        ];

        $this->db->insert('tmst_karyawan', $data);

        $response = [
            "code" => 200,
            "msg" => "Data Karyawan " . $this->input->post('initial') . " berhasil di tambahkan !"
        ];

        return $this->help->Fn_resulting_response($response);
    }

    public function Confirm_InitKaryawan()
    {
        $initial = $this->input->post('initial');

        $counts = $this->db->get_where('tmst_karyawan', ['initial' => $initial])->num_rows();
        if ($counts > 0) {
            $response = [
                "code" => 505,
                "msg" => "Inisial " . $initial . " tidak tersedia!"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "Inisial " . $initial . " tersedia !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function M_List_Supir()
    {
        return $this->load->view('Master/modal/m_list_supir');
    }

    public function DT_List_Driver()
    {
        $requestData = $_REQUEST;
        // sysid, nik, nama, initial, jabatan
        $columns = array(
            0 => 'sysid',
            1 => 'nik',
            2 => 'nama',
            3 => 'initial',
            4 => 'jabatan',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT * FROM tmst_karyawan where is_active = 1 and jabatan = 'SUPIR '";

        $totalData = $this->db->query($sql)->num_rows();
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (nik LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR initial LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        //----------------------------------------------------------------------------------
        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid'] = $row["sysid"];
            $nestedData['nik'] = $row["nik"];
            $nestedData['nama'] = $row["nama"];
            $nestedData['initial'] = $row["initial"];
            $nestedData['jabatan'] = $row["jabatan"];

            $data[] = $nestedData;
        }
        //----------------------------------------------------------------------------------
        $json_data = array(
            "draw"            => intval($requestData['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        //----------------------------------------------------------------------------------
        echo json_encode($json_data);
    }

    // ================================ SUPPLIER ================================= //
    public function index_master_supplier_material()
    {
        $this->data['page_title'] = "Master Supplier Material";
        $this->data['page_content'] = "Master/mst_supplier_mtrl";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/master-supplier-mtrl.js"></script>';

        $this->data['suppliers'] = $this->db->get('tmst_supplier_material');

        $this->load->view($this->layout, $this->data);
    }

    public function toggle_status_supplier()
    {
        $sysid = $this->input->post('sysid');

        $row = $this->db->get_where('tmst_supplier_material', ['sysid' => $sysid])->row();

        if ($row->is_active == 1) {
            $this->db->where('sysId', $sysid);
            $this->db->update('tmst_supplier_material', [
                'is_active' => 0
            ]);

            $response = [
                "code" => 200,
                "is_active" => 0,
                "msg" => "Supplier " . $row->nama . " di non-aktifkan !"
            ];
        } else {
            $this->db->where('sysId', $sysid);
            $this->db->update('tmst_supplier_material', [
                'is_active' => 1
            ]);

            $response = [
                "code" => 200,
                "is_active" => 1,
                "msg" => "Supplier " . $row->nama . " di aktifkan !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function modal_edit_suppplier()
    {
        $sysid = $this->input->get('sysid');

        $this->data['data'] =  $this->db->get_where('tmst_supplier_material', ['sysid' => $sysid])->row();
        $this->load->view("Master/modal/m_supplier", $this->data);
    }

    public function store_edit_supplier()
    {
        $sysid = $this->input->post('sysid');

        $data = [
            "nama" => $this->input->post('nama'),
            "alamat" => $this->input->post('alamat'),
            "telp" => $this->input->post('telp'),
            "email" => $this->input->post('email'),
            "nama_kontak" => $this->input->post('nama_kontak'),
            'uang_bongkar' => floatval($this->input->post('uang_bongkar')),
            "last_updated_by" => $this->session->userdata('impsys_initial'),
        ];
        $this->db->where('sysId', $sysid);
        $this->db->update('tmst_supplier_material', $data);

        $response = [
            "code" => 200,
            "id" => $sysid,
            "msg" => "Data supplier " . $this->input->post('nama') . " berhasil di update !"
        ];

        return $this->help->Fn_resulting_response($response);
    }

    public function form_add_supplier()
    {
        $this->data['page_title'] = "Form New Supplier";
        $this->data['page_content'] = "Master/form_add_supplier";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/form-add-supplier.js"></script>';;

        $this->load->view($this->layout, $this->data);
    }

    public function store_add_supplier()
    {
        $CountData = $this->db->get_where('tmst_supplier_material', ['nama' => $this->input->post('nama')])->num_rows();
        if ($CountData > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Data supplier " . $this->input->post('nama') . " gagal di tambahkan, terjadi redudansi data !"
            ]);
        }

        $data = [
            'nama' => $this->input->post('nama'),
            'alamat' => $this->input->post('alamat'),
            'telp' => $this->input->post('telp'),
            'email' => $this->input->post('email'),
            'nama_kontak' => $this->input->post('nama_kontak'),
            'uang_bongkar' => floatval($this->input->post('uang_bongkar')),
            'created_at' => date('Y-m-d'),
            'created_by' => $this->session->userdata('impsys_initial'),
            'last_updated_by' => $this->session->userdata('impsys_initial'),

        ];

        $this->db->insert('tmst_supplier_material', $data);

        $response = [
            "code" => 200,
            "msg" => "Data supplier " . $this->input->post('nama') . " berhasil di tambahkan !"
        ];

        return $this->help->Fn_resulting_response($response);
    }

    public function modal_manage_harga()
    {
        $sysid = $this->input->get('sysid');
        $this->data['supplier'] = $this->db->get_where('tmst_supplier_material', ['sysid' => $sysid])->row();
        $this->data['materials'] =  $this->db->get_where('tmst_material_kayu', ['is_active' => 1]);
        $this->load->view("Master/modal/m_manage_harga", $this->data);
    }

    public function store_editable_material_price($sysid_supplier)
    {
        $sysid_material = $this->input->post('pk');

        $RowData = $this->db->get_where('ttrx_harga_material_supplier', ['sysid_material' => $sysid_material, 'sysid_supplier' => $sysid_supplier]);

        $this->db->trans_start();

        if ($RowData->num_rows() > 0) {
            $data = [
                'harga_per_pcs' => $this->input->post('value'),
                'last_updated_at' => date('Y-m-d H:i:s'),
                'last_updated_by' => $this->session->userdata('impsys_initial')
            ];
            $this->db->where('sysid_supplier', $sysid_supplier);
            $this->db->where('sysid_material', $sysid_material);
            $this->db->update('ttrx_harga_material_supplier', $data);

            $history = [
                'sysid_supplier' => $sysid_supplier,
                'sysid_material' => $sysid_material,
                'action_is' => "UPDATE PRICE TO Rp. " . number_format($this->input->post('value'), 0, ',', '.'),
                'do_at' =>  date('Y-m-d H:i:s'),
                'do_by' => $this->session->userdata('impsys_initial')
            ];
            $this->db->insert('thst_update_material', $history);
        } else {
            $data = [
                'sysid_supplier' => $sysid_supplier,
                'sysid_material' => $sysid_material,
                'harga_per_pcs' => $this->input->post('value'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('impsys_initial')
            ];
            $this->db->insert('ttrx_harga_material_supplier', $data);

            $history = [
                'sysid_material' => $sysid_material,
                'sysid_supplier' => $sysid_supplier,
                'action_is' => "INSERT PRICE Rp. " . number_format($this->input->post('value'), 0, ',', '.'),
                'do_at' =>  date('Y-m-d H:i:s'),
                'do_by' => $this->session->userdata('impsys_initial')
            ];
            $this->db->insert('thst_update_material', $history);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code" => 505,
                "msg" => "gagal update harga material"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil update harga material"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function modal_history_harga()
    {
        $sysid_supplier = $this->input->get('sysid');

        $this->data['supplier'] = $this->db->get_where('tmst_supplier_material', ['sysid' => $sysid_supplier])->row();
        $this->data['rowDatas'] = $this->db->query("SELECT b.nama, c.kode , c.deskripsi ,a.action_is, a.do_at, a.do_by
        from thst_update_material a
        join tmst_supplier_material b on a.sysid_supplier = b.sysid 
        join tmst_material_kayu c on a.sysid_material = c.sysid
        where sysid_supplier = '$sysid_supplier'
        order by c.kode")->result();

        $this->load->view("Master/modal/m_history_harga", $this->data);
    }

    // ============================ MASTER CHECKER =============================== //

    public function index_checker_grading()
    {
        $this->data['page_title'] = "Master Checker Hasil Grading";
        $this->data['page_content'] = "Master/mst_checker_grading";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/master-checker-grading.js"></script>';

        $this->data['checker'] = $this->db->query("
        SELECT b.*, a. active
        FROM tmst_checker_grading a
        join tmst_karyawan b on a.NIK = b.nik
        order by nama
        ");
        $this->load->view($this->layout, $this->data);
    }

    public function delete_authority_checker()
    {
        $nik = $this->input->post('nik');

        $this->db->trans_start();
        $this->db->where('nik', $nik);
        $this->db->delete('tmst_checker_grading');
        $this->db->trans_complete();

        if ($this->db->trans_status() == FALSE) {
            $response = [
                "code" => 505,
                "msg" => "Terjadi kesalahan teknik hubungi admin!"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "authority checker " . $nik . " dicabut !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }
    public function add_authority_checker()
    {
        $nik = $this->input->post('nik');
        $initial = $this->input->post('initial');

        if ($this->db->get_where('tmst_checker_grading', ['nik' => $nik])->num_rows() > 0) {
            $response = [
                "code" => 505,
                "msg" => "Karyawan tersebut sudah terdaftar sebagai checker!"
            ];
            return $this->help->Fn_resulting_response($response);
        }

        $this->db->trans_start();
        $this->db->insert('tmst_checker_grading', [
            'nik' => $nik,
            'initial' => $initial,
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status() == FALSE) {
            $response = [
                "code" => 505,
                "msg" => "Terjadi kesalahan teknik hubungi admin!"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "authority checker " . $nik . " berhasil ditambahkan !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    // ===================================== Legalistas Supplier =======================//

    public function index_legalitas_supplier()
    {
        $this->data['page_title'] = "Master Legalitas Supplier";
        $this->data['page_content'] = "Master/mst_legalitas_supplier";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/master-legalitas.js"></script>';

        $this->data['legalitas'] = $this->db->get('tmst_legalitas_supplier');

        $this->load->view($this->layout, $this->data);
    }

    public function store_legalitas_supplier()
    {
        $insert = $this->db->insert('tmst_legalitas_supplier', [
            'kode_legalitas' => $this->input->post('legalitas'),
            'deskripsi' => $this->input->post('deskripsi')
        ]);

        if ($insert) {
            $this->session->set_flashdata('success', 'Berhasil menambahkan legalitas !');
        } else {
            $this->session->set_flashdata('danger', 'Gagal menambahkan legalitas !');
        }

        return redirect('Master/index_legalitas_supplier');
    }

    public function toggle_status_legalitas()
    {
        $sysid = $this->input->post('sysid');

        $row = $this->db->get_where('tmst_legalitas_supplier', ['sysid' => $sysid])->row();

        if ($row->is_active == 1) {
            $this->db->where('sysid', $sysid);
            $this->db->update('tmst_legalitas_supplier', [
                'is_active' => 0
            ]);

            $response = [
                "code" => 200,
                "is_active" => 0,
                "msg" => "Legalitas " . $row->kode_legalitas . " di non-aktifkan !"
            ];
        } else {
            $this->db->where('sysid', $sysid);
            $this->db->update('tmst_legalitas_supplier', [
                'is_active' => 1
            ]);

            $response = [
                "code" => 200,
                "is_active" => 1,
                "msg" => "Legalitas " . $row->kode_legalitas . " di aktifkan !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    // ===================================== MASTER OVEN =======================//

    public function index_identity_oven()
    {
        $this->data['page_title'] = "Master Data Oven";
        $this->data['page_content'] = "Master/mst_oven";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/master-oven.js"></script>';

        $this->data['ovens'] = $this->db->get('tmst_identity_oven');

        $this->load->view($this->layout, $this->data);
    }

    public function toggle_status_oven()
    {
        $sysid = $this->input->post('sysid');

        $row = $this->db->get_where('tmst_identity_oven', ['sysid' => $sysid])->row();

        if ($row->is_active == 1) {
            $this->db->where('sysid', $sysid);
            $this->db->update('tmst_identity_oven', [
                'is_active' => 0
            ]);

            $response = [
                "code" => 200,
                "is_active" => 0,
                "msg" => $row->nama . " di non-aktifkan !"
            ];
        } else {
            $this->db->where('sysid', $sysid);
            $this->db->update('tmst_identity_oven', [
                'is_active' => 1
            ]);

            $response = [
                "code" => 200,
                "is_active" => 1,
                "msg" => $row->nama . " di aktifkan !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function store_oven()
    {
        $insert = $this->db->insert('tmst_identity_oven', [
            'nama' => strtoupper($this->input->post('nama')),
            'description' => $this->input->post('deskripsi')
        ]);

        if ($insert) {
            $this->session->set_flashdata('success', 'Berhasil menambahkan oven !');
        } else {
            $this->session->set_flashdata('danger', 'Gagal menambahkan oven !');
        }

        return redirect('Master/index_identity_oven');
    }

    public function List_oven_active()
    {
        $ovens = $this->db->get_where('tmst_identity_oven', ['is_active' => '1'])->result_array();
        foreach ($ovens as $row) {
            $data[$row['sysid']] = $row['nama'];
        }
        echo json_encode($data);
    }

    public function List_placement_kayu_kering()
    {
        $placements = $this->db->get_where('tmst_placement_material', ['kategori' => 'KERING', 'is_active' => '1'])->result_array();
        foreach ($placements as $row) {
            $data[$row['sysid']] = $row['lokasi'];
        }
        echo json_encode($data);
    }

    // ====================================== MASTER PLACEMENT ======================//
    public function index_placement()
    {
        $this->data['page_title'] = "Master Placement Stok Material";
        $this->data['page_content'] = "Master/mst_placement";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/master-placement.js"></script>';

        $this->data['placements'] = $this->db->get('tmst_placement_material');

        $this->load->view($this->layout, $this->data);
    }

    public function store_placement()
    {
        $insert = $this->db->insert('tmst_placement_material', [
            'kategori' => $this->input->post('kategori'),
            'lokasi' => $this->input->post('nama'),
            'keterangan' => $this->input->post('deskripsi'),
            'created_by' => $this->session->userdata('impsys_initial'),
        ]);

        if ($insert) {
            $this->session->set_flashdata('success', 'Berhasil menambahkan lokasi baru !');
        } else {
            $this->session->set_flashdata('danger', 'Gagal menambahkan lokasi baru !');
        }

        return redirect('Master/index_placement');
    }

    public function toggle_status_placement()
    {
        $sysid = $this->input->post('sysid');

        $row = $this->db->get_where('tmst_placement_material', ['sysid' => $sysid])->row();

        if ($row->is_active == 1) {
            $this->db->where('sysid', $sysid);
            $this->db->update('tmst_placement_material', [
                'is_active' => 0
            ]);

            $response = [
                "code" => 200,
                "is_active" => 0,
                "msg" => $row->lokasi . " di non-aktifkan !"
            ];
        } else {
            $this->db->where('sysid', $sysid);
            $this->db->update('tmst_placement_material', [
                'is_active' => 1
            ]);

            $response = [
                "code" => 200,
                "is_active" => 1,
                "msg" => $row->lokasi . " di aktifkan !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    // ====================================== DataTable =============================//

    public function modal_list_karyawan()
    {
        $this->data['title'] = 'LIST DATA KARYAWAN';

        $this->load->view("Master/modal/m_list_karyawan", $this->data);
    }

    public function DataTable_modal_list_karyawan()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'sysid',
            1 => 'nik',
            2 => 'nama',
            3 => 'initial',
            4 => 'department'
        );
        $sql = "SELECT * from tmst_karyawan WHERE is_active = '1' ";

        // JIKA ADA PARAM DARI SEARCH
        $totalData = $this->db->query($sql)->num_rows();
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (nik LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR initial LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR department LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $totalFiltered = $this->db->query($sql)->num_rows();

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'] . "  LIMIT "
            . $requestData['start'] . " ," . $requestData['length'] . " ";

        $query = $this->db->query($sql);
        $data = array();
        $no = 1;
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid'] = $row["sysid"];
            $nestedData['nik'] = $row["nik"];
            $nestedData['nama'] = $row["nama"];
            $nestedData['initial'] = $row["initial"];
            $nestedData['department'] = $row["department"];

            $data[] = $nestedData;
        }
        //----------------------------------------------------------------------------------
        $json_data = array(
            "draw"            => intval($requestData['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        //----------------------------------------------------------------------------------
        echo json_encode($json_data);
    }

    // ========================== MASTER PRODUCT =========================== //

    public function index_product()
    {
        $this->data['page_title'] = "Master Product";
        $this->data['page_content'] = "Master/mst_product";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/master-product.js"></script>';

        $this->data['Products'] = $this->db->query("
        SELECT a.*, b.Customer_Name
        from tmst_hdr_product a
        join tmst_customer b on a.Customer_id = b.sysid;
        ");
        $this->data['customers'] = $this->db->get_where('tmst_customer', ['is_active' => '1'])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function store_product()
    {
        $this->db->trans_start();
        $upload_image = $_FILES['Photo']['name'];
        if ($upload_image) {
            $config['allowed_types'] = 'pdf|doc|ppt|docx|pptx|xls|xlsx|png|jpg|jpeg';
            $config['max_size']      = '25062';
            $config['upload_path'] = './assets/Master/';
            $config['file_name'] = 'Image-' . str_replace(" ", "-", $this->input->post('Kode'));

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('Photo')) {
                $image_name = 'Image-' . str_replace(" ", "-", $this->input->post('Kode')) . '.' . pathinfo($upload_image, PATHINFO_EXTENSION);
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                return redirect('Master/index_product');
            }
        }

        $upload_attachment = $_FILES['Attachment']['name'];
        if ($upload_attachment) {
            $config['allowed_types'] = 'pdf|doc|ppt|docx|pptx|xls|xlsx|png|jpg|jpeg';
            $config['max_size']      = '25062';
            $config['upload_path'] = './assets/Master/';
            $config['file_name'] = 'Attachment-' . str_replace(" ", "-", $this->input->post('Kode'));

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('Attachment')) {
                $attachment_name = 'Attachment-' . str_replace(" ", "-", $this->input->post('Kode')) . '.' . pathinfo($upload_attachment, PATHINFO_EXTENSION);
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                return redirect('Master/index_product');
            }
        }

        $data = [
            'Customer_id' => $this->input->post('customer'),
            'Nama' => $this->input->post('Nama'),
            'Kode' => $this->input->post('Kode'),
            'Deskripsi' => $this->input->post('Deskripsi'),
            'uom' => strtoupper($this->input->post('uom')),
            'Tebal' => $this->input->post('Tebal'),
            'Lebar' => $this->input->post('Lebar'),
            'Panjang' => $this->input->post('Panjang'),
            'Price' => floatval($this->input->post('Price')),
            'Image' => $image_name,
            'Attachment' => $attachment_name,
            'Created_at' => date('Y-m-d H:i:s'),
            'Created_by' =>  $this->session->userdata('impsys_initial'),
        ];

        $this->db->insert('tmst_hdr_product', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal menambahkan item ke master product !');
            return redirect('Master/index_product');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Berhail menambahkan item ke master product !');
            return redirect('Master/index_product');
        }
    }

    public function toggle_status_product()
    {
        $sysid = $this->input->post('sysid');

        $row = $this->db->get_where('tmst_hdr_product', ['sysid' => $sysid])->row();

        if ($row->is_active == 1) {
            $this->db->where('sysid', $sysid);
            $this->db->update('tmst_hdr_product', [
                'is_active' => 0
            ]);

            $response = [
                "code" => 200,
                "is_active" => 0,
                "msg" => "Product " . $row->Kode . " di non-aktifkan !"
            ];
            $this->db->insert('thst_update_material', [
                'sysid_material' => $sysid,
                'action_is' => 'Merubah status menjadi non-aktif.',
                'do_by' => $this->session->userdata('impsys_initial')
            ]);
        } else {
            $this->db->where('sysid', $sysid);
            $this->db->update('tmst_hdr_product', [
                'is_active' => 1
            ]);

            $response = [
                "code" => 200,
                "is_active" => 1,
                "msg" => "Product " . $row->Kode . " di aktifkan !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function popup_form_edit_hdr_product()
    {
        $this->data['Hdr'] = $this->db->get_where('tmst_hdr_product', ['sysid' => $this->input->get('sysid')])->row();

        return $this->load->view('Master/modal/m_edit_product_hdr', $this->data);
    }

    public function edit_product()
    {
        $this->db->trans_start();
        $upload_image = $_FILES['Photo']['name'];
        if (!empty($upload_image)) {
            $config['allowed_types'] = 'pdf|doc|ppt|docx|pptx|xls|xlsx|png|jpg|jpeg';
            $config['max_size']      = '25062';
            $config['upload_path'] = './assets/Master/';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('Photo')) {
                $image_name = $_FILES['Photo']['name'];
                unlink(FCPATH . 'assets/Master/' . $this->input->post('old_Photo'));
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                return redirect('Master/index_product');
            }
        } else {
            $image_name = $this->input->post('old_Photo');
        }

        $upload_attachment = $_FILES['Attachment']['name'];
        if (!empty($upload_attachment)) {
            $config['allowed_types'] = 'pdf|doc|ppt|docx|pptx|xls|xlsx|png|jpg|jpeg';
            $config['max_size']      = '25062';
            $config['upload_path'] = './assets/Master/';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('Attachment')) {
                $attachment_name = $_FILES['Attachment']['name'];
                unlink(FCPATH . 'assets/Master/' . $this->input->post('old_Attachment'));
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                return redirect('Master/index_product');
            }
        } else {
            $attachment_name = $this->input->post('old_Attachment');
        }

        $rowDataBefore = $this->db->get_where('tmst_hdr_product', ['sysid' => $this->input->post('sysid')])->row_array();
        $this->db->insert('thst_mst_product', [
            'sysid_product' => $this->input->post('sysid'),
            'array_update_data' => json_encode($rowDataBefore),
            'do_by' =>  $this->session->userdata('impsys_initial'),
            'action' => 'update'
        ]);

        $data = [
            'Nama' => $this->input->post('Nama'),
            'Kode' => $this->input->post('Kode'),
            'Deskripsi' => $this->input->post('Deskripsi'),
            'uom' => strtoupper($this->input->post('uom')),
            'Tebal' => $this->input->post('Tebal'),
            'Lebar' => $this->input->post('Lebar'),
            'Panjang' => $this->input->post('Panjang'),
            'Price' => floatval($this->input->post('Price')),
            'Image' => $image_name,
            'Attachment' => $attachment_name,
            'Last_Updated_at' => date('Y-m-d H:i:s'),
            'Last_Updated_by' =>  $this->session->userdata('impsys_initial'),
        ];
        $this->db->where('sysid', $this->input->post('sysid'));
        $this->db->update('tmst_hdr_product', $data);


        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal Update item master product !');
            return redirect('Master/index_product');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Berhail Update item master product !');
            return redirect('Master/index_product');
        }
    }

    public function popup_form_structure_hdr_product()
    {
        $this->data['Hdr'] = $this->db->get_where('tmst_hdr_product', ['sysid' => $this->input->get('sysid')])->row();
        $this->data['Dtls'] = $this->db->get_where('tmst_dtl_product', ['sysid_hdr' => $this->input->get('sysid')])->result();
        $this->data['materials'] = $this->db->get('tmst_bentuk_material')->result();

        return $this->load->view('Master/modal/m_detail_product', $this->data);
    }

    public function list_struktur_product()
    {
        $this->data['Hdr'] = $this->db->get_where('tmst_hdr_product', ['sysid' => $this->input->get('sysid')])->row();
        $this->data['Dtls'] = $this->db->get_where('tmst_dtl_product', ['sysid_hdr' => $this->input->get('sysid')])->result();

        return $this->load->view('Master/modal/m_list_struktur_product', $this->data);
    }

    public function store_structure_product()
    {
        $sysid_hdr = $this->input->post('sysid_hdr');
        $Bentuk = $this->input->post('Bentuk');
        $Tebal = $this->input->post('Tebal');
        $Lebar = $this->input->post('Lebar');
        $Panjang = $this->input->post('Panjang');
        $Pcs = $this->input->post('Pcs');
        $Remark = $this->input->post('Remark');

        $this->db->where('sysid_hdr', $sysid_hdr);
        $this->db->delete('tmst_dtl_product');

        $this->db->trans_start();
        if (!empty($Bentuk)) {
            for ($i = 0; $i < count($Bentuk); $i++) {
                $this->db->insert("tmst_dtl_product", [
                    'sysid_hdr'     => $sysid_hdr,
                    'Bentuk'        => $Bentuk[$i],
                    'Tebal'         => $Tebal[$i],
                    'Lebar'         => $Lebar[$i],
                    'Panjang'       => $Panjang[$i],
                    'Pcs'           => $Pcs[$i],
                    'Remark'        => $Remark[$i],
                    'created_at'    => date('Y-m-d H:i:s'),
                    'created_by'    => $this->session->userdata('impsys_initial')
                ]);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal membuat struktur Product !');
            return redirect('Master/index_product');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Berhail membuat struktur Product !');
            return redirect('Master/index_product');
        }
    }

    public function delete_mst_product()
    {
        $sysid = $this->input->post('sysid');

        $rowDataBefore = $this->db->get_where('tmst_hdr_product', ['sysid' => $this->input->post('sysid')])->row_array();
        $this->db->insert('thst_mst_product', [
            'sysid_product' => $this->input->post('sysid'),
            'array_update_data' => json_encode($rowDataBefore),
            'do_by' =>  $this->session->userdata('impsys_initial'),
            'action' => 'delete'
        ]);

        $this->db->trans_start();
        // -------------------------------
        $this->db->where('sysid_hdr', $sysid);
        $this->db->delete('tmst_dtl_product');
        $this->db->where('sysid', $sysid);
        $this->db->delete('tmst_hdr_product');
        // -------------------------------
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code"  => 505,
                "msg"   => "gagal delete data product !"
            ];
        } else {
            $response = [
                "code"  => 200,
                "msg"   => "berhasil delete data product!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function List_Product_Active()
    {
        $products = $this->db->get_where('tmst_hdr_product', ['is_active' => '1'])->result_array();
        foreach ($products as $row) {
            $data[$row['sysid']] = $row['Nama'] . ' (' . $row['Kode'] . ')';
        }
        echo json_encode($data);
    }
    // =========================================== MASTER CUSTOMER

    public function index_customer()
    {
        $this->data['page_title'] = "Master Customer";
        $this->data['page_content'] = "Master/mst_customer";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/master-customer.js"></script>';

        $this->data['Customers'] = $this->db->get('tmst_customer')->result();

        $this->load->view($this->layout, $this->data);
    }

    public function store_customer()
    {
        $count_code = $this->db->get_where('tmst_customer', ['Customer_Code' => $this->input->post('customer_code')])->num_rows();
        if ($count_code > 0) {
            $this->session->set_flashdata('error', 'Gagal Menambahkan master customer, kode customer sudah digunakan oleh customer lain!');
            return redirect('Master/index_customer');
        }

        $this->db->trans_start();

        $this->db->insert(
            'tmst_customer',
            [
                'Customer_Code' => strtoupper($this->input->post('customer_code')),
                'Customer_Name' => $this->input->post('customer_name'),
                'NPWP' => $this->input->post('npwp'),
                'Koresponden' => $this->input->post('koresponden'),
                'Created_by' => $this->session->userdata('impsys_initial'),
                'Created_at' => date('Y-m-d H:i:s')
            ]
        );


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal menambahkan new customer !');
            return redirect('Master/index_customer');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Berhail menambahkan customer !');
            return redirect('Master/index_customer');
        }
    }

    public function toggle_status_customer()
    {
        $sysid = $this->input->post('sysid');

        $row = $this->db->get_where('tmst_customer', ['SysId' => $sysid])->row();

        if ($row->is_active == '1') {
            $this->db->where('SysId', $sysid);
            $this->db->update('tmst_customer', [
                'is_active' => '0'
            ]);

            $response = [
                "code" => 200,
                "is_active" => 0,
                "msg" => "Customer " . $row->Customer_Name . " di non-aktifkan !"
            ];
        } else {
            $this->db->where('SysId', $sysid);
            $this->db->update('tmst_customer', [
                'is_active' => '1'
            ]);

            $response = [
                "code" => 200,
                "is_active" => 1,
                "msg" => "Customer " . $row->Customer_Name . " di aktifkan !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function popup_form_edit_customer()
    {
        $this->data['Customer'] = $this->db->get_where('tmst_customer', ['SysId' => $this->input->get('sysid')])->row();

        return $this->load->view('Master/modal/m_edit_customer', $this->data);
    }

    public function edit_customer()
    {
        $this->db->trans_start();
        $data = [
            'NPWP' => $this->input->post('npwp'),
            'Koresponden' => $this->input->post('koresponden'),
            'Last_updated_by' => $this->session->userdata('impsys_initial'),
            'Last_updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('SysId', $this->input->post('sysid'));
        $this->db->update('tmst_customer', $data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal Update item master customer !');
            return redirect('Master/index_customer');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Berhail Update item master customer !');
            return redirect('Master/index_customer');
        }
    }

    public function customer_add_address()
    {
        $row = $this->db->get_where('tmst_customer', ['SysId' => $this->input->post('sysid_customer')])->row();

        $this->db->trans_start();
        $this->db->insert(
            'tmst_customer_address',
            [
                'Customer_id' => $this->input->post('sysid_customer'),
                'Address' => $this->input->post('Address'),
                'City' => $this->input->post('City'),
                'Postal_Code' => $this->input->post('Postal_Code'),
                'Phone' => $this->input->post('Phone'),
                'Fax' => $this->input->post('Fax'),
                'Email' => $this->input->post('Email'),
                'Koresponden' => $this->input->post('Koresponden'),
                'Created_by' => $this->session->userdata('impsys_initial'),
                'Created_at' => date('Y-m-d H:i:s')
            ]
        );

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', "Gagal menambahkan Address $row->Customer_Name !");
            return redirect('Master/index_customer');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', "Berhail menambahkan Address $row->Customer_Name !");
            return redirect('Master/index_customer');
        }
    }

    public function list_address_customer()
    {
        $this->data['Hdr'] = $this->db->get_where('tmst_customer', ['SysId' => $this->input->get('sysid')])->row();
        $this->data['Dtls'] = $this->db->get_where('tmst_customer_address', ['Customer_id' => $this->input->get('sysid')])->result();

        return $this->load->view('Master/modal/m_list_address', $this->data);
    }

    public function List_Address_Customer_Pick()
    {
        $this->data['Hdr'] = $this->db->get_where('tmst_customer', ['SysId' => $this->input->get('sysid')])->row();
        $this->data['Dtls'] = $this->db->get_where('tmst_customer_address', ['Customer_id' => $this->input->get('sysid')])->result();

        return $this->load->view('Master/modal/m_list_address_pick', $this->data);
    }

    public function toggle_status_address()
    {
        $sysid = $this->input->post('sysid');

        $row = $this->db->get_where('tmst_customer_address', ['SysId' => $sysid])->row();

        if ($row->is_active == '1') {
            $this->db->where('SysId', $sysid);
            $this->db->update('tmst_customer_address', [
                'is_active' => '0'
            ]);

            $response = [
                "code" => 200,
                "is_active" => 0,
                "msg" => "Address di non-aktifkan !"
            ];
        } else {
            $this->db->where('SysId', $sysid);
            $this->db->update('tmst_customer_address', [
                'is_active' => '1'
            ]);

            $response = [
                "code" => 200,
                "is_active" => 1,
                "msg" => "Address di aktifkan !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function M_List_Customer()
    {
        return $this->load->view('Master/modal/m_List_Customer');
    }

    public function DT_List_Customer()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'SysId',
            1 => 'Customer_Code',
            2 => 'Customer_Name',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT * FROM tmst_customer where is_active = '1' ";

        $totalData = $this->db->query($sql)->num_rows();
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (Customer_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Name LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        //----------------------------------------------------------------------------------
        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId'] = $row["SysId"];
            $nestedData['Customer_Code'] = $row["Customer_Code"];
            $nestedData['Customer_Name'] = $row["Customer_Name"];

            $data[] = $nestedData;
        }
        //----------------------------------------------------------------------------------
        $json_data = array(
            "draw"            => intval($requestData['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        //----------------------------------------------------------------------------------
        echo json_encode($json_data);
    }



    // ============================ MASTER KENDARAAN ============================//

    public function index_kendaraan()
    {
        $this->data['page_title'] = "Master Kendaraan";
        $this->data['page_content'] = "Master/mst_kendaraan";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/master-kendaraan.js"></script>';

        $this->data['Kendaraans'] = $this->db->get('tmst_kendaraan_shipping');

        $this->load->view($this->layout, $this->data);
    }

    public function store_kendaraan()
    {
        $this->db->trans_start();
        $this->db->insert('tmst_kendaraan_shipping', [
            'No_Polisi' => strtoupper($this->input->post('no_polisi')),
            'Status_Kepemilikan' => $this->input->post('kepemilikan'),
            'Jenis' => $this->input->post('jenis'),
            'Remark' => $this->input->post('catatan'),
            'created_by' => $this->session->userdata('impsys_initial')
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', "Gagal menambahkan Kendaraan !");
            return redirect('Master/index_kendaraan');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', "Berhail menambahkan Kendaraan !");
            return redirect('Master/index_kendaraan');
        }
    }

    public function toggle_status_kendaraan()
    {
        $sysid = $this->input->post('sysid');

        $row = $this->db->get_where('tmst_kendaraan_shipping', ['SysId' => $sysid])->row();
        if ($row->is_active == '1') {
            $this->db->where('SysId', $sysid);
            $this->db->update('tmst_kendaraan_shipping', [
                'is_active' => 0
            ]);

            $response = [
                "code" => 200,
                "is_active" => 0,
                "msg" => "Kendaraan di non-aktifkan !"
            ];
        } else {
            $this->db->where('SysId', $sysid);
            $this->db->update('tmst_kendaraan_shipping', [
                'is_active' => 1
            ]);

            $response = [
                "code" => 200,
                "is_active" => 1,
                "msg" => "Kendaraan di aktifkan !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function M_List_kendaraan()
    {
        return $this->load->view('Master/modal/m_list_kendaraan');
    }

    public function DT_List_Vehicle()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'SysId',
            1 => 'No_Polisi',
            2 => 'Status_Kepemilikan',
            3 => 'Jenis',
            4 => 'Remark',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT * FROM tmst_kendaraan_shipping where is_active = 1 ";

        $totalData = $this->db->query($sql)->num_rows();
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (No_Polisi LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Status_Kepemilikan LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Remark LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Jenis LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        //----------------------------------------------------------------------------------
        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId'] = $row["SysId"];
            $nestedData['No_Polisi'] = $row["No_Polisi"];
            $nestedData['Status_Kepemilikan'] = $row["Status_Kepemilikan"];
            $nestedData['Jenis'] = $row["Jenis"];
            $nestedData['Remark'] = $row["Remark"];

            $data[] = $nestedData;
        }
        //----------------------------------------------------------------------------------
        $json_data = array(
            "draw"            => intval($requestData['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        //----------------------------------------------------------------------------------
        echo json_encode($json_data);
    }
}
