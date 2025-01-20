<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bank extends CI_Controller
{
	public $layout = 'layout';

	protected $Date;
	protected $DateTime;
	protected $Tmst_Bank = 'tmst_bank';
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
		$data['page_title'] = "List of Bank";
		$data['page_content'] = "Master/Bank/index";
		$data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Bank/bank.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function add()
    {
        $data['page_title'] = "Add New Bank";
		$data['coa'] = $this->db->get('tmst_chart_of_account');
		$data['bank'] = $this->db->get('tsys_bank');
		$data['currency'] = $this->db->get('tmst_currency');
        $data['page_content'] = "Master/Bank/add";
        $data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Bank/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $data);
    }

	public function post()
    {
        $this->db->trans_start();
        $this->db->insert($this->Tmst_Bank, [
            'id_coa' => $this->input->post('id_coa'),
            'id_bank' => $this->input->post('id_bank'),
			'kode_bank' => $this->input->post('kode_bank'),
			'nomor_rekening_bank' => $this->input->post('nomor_rekening_bank'),
			'nama_rekening_bank' => $this->input->post('nama_rekening_bank'),
			'cabang_bank' => $this->input->post('cabang_bank'),
			'currency_bank' => $this->input->post('currency_bank'),
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
        $data['RowData'] = $this->db->get_where($this->Tmst_Bank, ['SysId' => $sysid])->row();
        $data['page_title'] = "Edit Data Bank : " . $data['RowData']->kode_bank;
		$data['coa'] = $this->db->get('tmst_chart_of_account');
		$data['bank'] = $this->db->get('tsys_bank');
		$data['currency'] = $this->db->get('tmst_currency');
        $data['page_content'] = "Master/Bank/edit";
        $data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Bank/edit.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $data);
    }

	public function update()
    {
        $this->db->trans_start();
        $this->db->where('SysId', $this->input->post('sysid'))->update($this->Tmst_Bank, [
            'id_coa' => $this->input->post('id_coa'),
            'id_bank' => $this->input->post('id_bank'),
			'nomor_rekening_bank' => $this->input->post('nomor_rekening_bank'),
			'nama_rekening_bank' => $this->input->post('nama_rekening_bank'),
			'cabang_bank' => $this->input->post('cabang_bank'),
			'currency_bank' => $this->input->post('currency_bank'),
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

	public function DT_list_bank()
	{
		$query = "SELECT tmst_bank.*, CONCAT_WS(' - ', tmst_chart_of_account.kode_akun, tmst_chart_of_account.nama_akun) AS coa, ".
		         "CONCAT_WS(' - ', tsys_bank.kode_bank, tsys_bank.nama_bank) AS bank, ".
		         "CONCAT_WS(' - ', tmst_currency.Currency_ID, tmst_currency.Currency_Description) AS currency ".
                 "FROM tmst_bank ".
                 "INNER JOIN tmst_chart_of_account ON tmst_bank.id_coa = tmst_chart_of_account.Sysid ".
				 "INNER JOIN tsys_bank ON tmst_bank.id_bank = tsys_bank.Sysid ".
                 "INNER JOIN tmst_currency ON tmst_bank.currency_bank = tmst_currency.Currency_ID";
		$where  = array('tmst_bank.kode_bank !' => NULL);
		$search = array('tmst_bank.SysId', 'tmst_bank.id_coa', 'tmst_bank.kode_bank', 'tmst_bank.nomor_rekening_bank', 'tmst_bank.nama_rekening_bank', 'tmst_bank.cabang_bank', 'tmst_bank.currency_bank', 'tmst_bank.catatan', 'tmst_bank.Is_Active', 'tmst_chart_of_account.kode_akun', 'tmst_chart_of_account.nama_akun', 'tsys_bank.kode_bank', 'tsys_bank.nama_bank');

		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}
}
