<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AkunInduk extends CI_Controller
{
	public $layout = 'layout';

	protected $Date;
	protected $DateTime;
	protected $Tmst_AkunInduk = 'tmst_akun_induk';
	protected $LengthCounterAccount = 5;

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
		$data['page_title'] = "List of Akun Induk";
		$data['page_content'] = "Master/AkunInduk/index";
		$data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/AkunInduk/akuninduk.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function add()
    {
        $data['page_title'] = "Add New Akun Induk";
		$data['akun_tipe'] = $this->db->get('tmst_akun_tipe');
        $data['page_content'] = "Master/AkunInduk/add";
        $data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/AkunInduk/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $data);
    }

	public function post()
    {
        $this->db->trans_start();
        $this->db->insert($this->Tmst_AkunInduk, [
            'id_akun_tipe' => $this->input->post('id_akun_tipe'),
            'kode_akun' => $this->input->post('kode_akun'),
			'nama_akun' => $this->input->post('nama_akun'),
			'catatan' => $this->input->post('catatan'),
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Gagal Menyimpan data !"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Data berhasil tersimpan!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

	public function edit($sysid)
    {
        $data['RowData'] = $this->db->get_where($this->Tmst_AkunInduk, ['SysId' => $sysid])->row();
        $data['page_title'] = "Edit Data Akun Induk : " . $data['RowData']->nama_akun;
		$data['akun_tipe'] = $this->db->get('tmst_akun_tipe');
        $data['page_content'] = "Master/AkunInduk/edit";
        $data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/AkunInduk/edit.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $data);
    }

	public function update()
    {
        $this->db->trans_start();
        $this->db->where('SysId', $this->input->post('sysid'))->update($this->Tmst_AkunInduk, [
            'id_akun_tipe' => $this->input->post('id_akun_tipe'),
            'kode_akun' => $this->input->post('kode_akun'),
			'nama_akun' => $this->input->post('nama_akun'),
			'catatan' => $this->input->post('catatan'),
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Gagal Menyimpan data !"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Data berhasil tersimpan!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

	public function DT_list_akuninduk()
	{
		$query = "SELECT tmst_akun_induk.*, tmst_akun_tipe.nama_tipe_akun FROM tmst_akun_induk INNER JOIN tmst_akun_tipe ON tmst_akun_induk.id_akun_tipe= tmst_akun_tipe.SysId";
		$where  = array('tmst_akun_tipe.nama_tipe_akun !' => NULL);
		$search = array('tmst_akun_induk.SysId', 'id_akun_tipe', 'kode_akun', 'nama_akun', 'catatan', 'nama_tipe_akun', 'tmst_akun_induk.Is_Active');

		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}
}
