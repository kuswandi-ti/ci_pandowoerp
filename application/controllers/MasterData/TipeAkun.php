<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TipeAkun extends CI_Controller
{
	public $layout = 'layout';

	protected $Date;
	protected $DateTime;
	protected $Tmst_TipeAkun = 'tmst_akun_tipe';
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
		$data['page_title'] = "List of Tipe Akun";
		$data['page_content'] = "Master/TipeAkun/index";
		$data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/TipeAkun/tipeakun.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function add()
    {
        $this->data['page_title'] = "Add New Tipe Akun";
        $this->data['page_content'] = "Master/TipeAkun/add";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/TipeAkun/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

	public function post()
    {
        $this->db->trans_start();
        $this->db->insert($this->Tmst_TipeAkun, [
            'nama_tipe_akun' => $this->input->post('nama_tipe_akun'),
            'kode_tipe_akun' => $this->input->post('kode_tipe_akun'),
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
        $this->data['RowAccount'] = $this->db->get_where($this->Tmst_TipeAkun, ['sysid' => $sysid])->row();
        $this->data['page_title'] = "Edit Data Tipe Akun : " . $this->data['RowAccount']->nama_tipe_akun;
        $this->data['page_content'] = "Master/TipeAkun/edit";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/TipeAkun/edit.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

	public function update()
    {
        $this->db->trans_start();
        $this->db->where('sysid', $this->input->post('sysid'))->update($this->Tmst_TipeAkun, [
            'nama_tipe_akun' => $this->input->post('nama_tipe_akun'),
            'kode_tipe_akun' => $this->input->post('kode_tipe_akun'),
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

	public function DT_list_tipeakun()
	{
		$query = "SELECT * FROM $this->Tmst_TipeAkun";
		$where  = array('kode_tipe_akun !' => NULL);
		$search = array('SysId', 'nama_tipe_akun', 'kode_tipe_akun', 'Is_Active');

		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}
}
