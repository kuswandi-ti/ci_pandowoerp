<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SaldoAwal extends CI_Controller
{
	public $layout = 'layout';

	protected $Date;
	protected $DateTime;
	protected $Tmst_COA = 'tmst_chart_of_account';

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
		$data['page_title'] = "Saldo Awal";
		$data['page_content'] = "FinanceAccounting/SaldoAwal/index";
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/SaldoAwal/saldoawal.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function add()
    {
        $data['page_title'] = "Add New Saldo Awal";
		$data['coa'] = $this->db->get('tmst_chart_of_account');
        $data['page_content'] = "FinanceAccounting/SaldoAwal/add";
        $data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/SaldoAwal/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $data);
    }

	public function post()
    {
        $this->db->trans_start();
        $this->db->where('SysId', $this->input->post('id_coa'))->update($this->Tmst_COA, [
            'debit' => $this->input->post('debit'),
            'credit' => $this->input->post('credit'),
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
        $data['page_title'] = "Edit Data Saldo Awal : " . $data['RowData']->nama_akun;
		$data['coa'] = $this->db->get('tmst_chart_of_account');
        $data['page_content'] = "FinanceAccounting/SaldoAwal/edit";
        $data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/SaldoAwal/edit.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $data);
    }

	public function update()
    {
        $this->db->trans_start();
        $this->db->where('SysId', $this->input->post('sysid'))->update($this->Tmst_COA, [
			'debit' => $this->input->post('debit'),
            'credit' => $this->input->post('credit'),
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

	public function DT_list_saldoawal()
	{
		$query = "SELECT * FROM tmst_chart_of_account";
		$where  = array('Is_Active ' => 1);
		$search = array('SysId', 'id_akun_induk', 'kode_akun', 'nama_akun', 'debit', 'credit', 'saldo', 'Is_Active');

		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}
}
