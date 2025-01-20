<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grader extends CI_Controller
{
    public $layout = 'layout';

    protected $Date;
    protected $DateTime;
    protected $tmst_operator_grading = 'tmst_operator_grading';

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
        $this->data['page_title'] = "Master Data Grader";
        $this->data['page_content'] = "Master/Grader/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Grader/index.js?v=' . time() . '"></script>';

        $this->data['checker'] = $this->db->query("
        SELECT b.*, a. active
        FROM tmst_operator_grading a
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
        $this->db->delete($this->tmst_operator_grading);
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

        if ($this->db->get_where($this->tmst_operator_grading, ['nik' => $nik])->num_rows() > 0) {
            $response = [
                "code" => 505,
                "msg" => "Karyawan tersebut sudah terdaftar sebagai checker!"
            ];
            return $this->help->Fn_resulting_response($response);
        }

        $this->db->trans_start();
        $this->db->insert($this->tmst_operator_grading, [
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
}
