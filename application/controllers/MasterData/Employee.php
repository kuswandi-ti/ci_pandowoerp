<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employee extends CI_Controller
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
        $this->data['page_title'] = "Master Karyawan";
        $this->data['page_content'] = "Master/Employee/mst_karyawan";
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
        $this->data['page_content'] = "Master/Employee/form_add_karyawan";
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
}
