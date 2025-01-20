<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ChartOfAccount extends CI_Controller
{
	public $layout = 'layout';

	protected $Date;
	protected $DateTime;
	protected $Tmst_COA = 'tmst_chart_of_account';
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
		$data['page_title'] = "List of Chart Of Account";
		$data['page_content'] = "Master/ChartOfAccount/index";
		$data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/ChartOfAccount/chartofaccount.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function add()
    {
        $data['page_title'] = "Add New Chart Of Account";
		$data['akun_induk'] = $this->db->get('tmst_akun_induk');
        $data['page_content'] = "Master/ChartOfAccount/add";
        $data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/ChartOfAccount/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $data);
    }

	public function post()
    {
        $this->db->trans_start();
        $this->db->insert($this->Tmst_COA, [
            'id_akun_induk' => $this->input->post('id_akun_induk'),
            'kode_akun' => $this->input->post('kode_akun'),
			'nama_akun' => $this->input->post('nama_akun'),
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
        $data['RowData'] = $this->db->get_where($this->Tmst_COA, ['SysId' => $sysid])->row();
        $data['page_title'] = "Edit Data Chart Of Account : " . $data['RowData']->nama_akun;
		$data['akun_induk'] = $this->db->get('tmst_akun_induk');
        $data['page_content'] = "Master/ChartOfAccount/edit";
        $data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/ChartOfAccount/edit.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $data);
    }

	public function update()
    {
        $this->db->trans_start();
        $this->db->where('SysId', $this->input->post('sysid'))->update($this->Tmst_COA, [
            'id_akun_induk' => $this->input->post('id_akun_induk'),
            'kode_akun' => $this->input->post('kode_akun'),
			'nama_akun' => $this->input->post('nama_akun'),
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

	public function DT_list_chartofaccount()
	{
		$query = "SELECT tmst_chart_of_account.*, CONCAT_WS(' - ', tmst_akun_induk.kode_akun, tmst_akun_induk.nama_akun) AS akun_induk ".
                 "FROM tmst_chart_of_account ".
                 "INNER JOIN tmst_akun_induk ON tmst_chart_of_account.id_akun_induk = tmst_akun_induk.SysId";
		$where  = array('tmst_chart_of_account.nama_akun !' => NULL);
		$search = array('tmst_chart_of_account.SysId', 'tmst_chart_of_account.kode_akun', 'tmst_chart_of_account.nama_akun', 'tmst_akun_induk.kode_akun', 'tmst_akun_induk.nama_akun', 'tmst_chart_of_account.Is_Active');

		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}
}
