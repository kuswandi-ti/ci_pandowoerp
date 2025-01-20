<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingAkun extends CI_Controller
{
	public $layout = 'layout';

	protected $Date;
	protected $DateTime;

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
		$data['page_title'] = "List of Setting Akun";
		$data['page_content'] = "FinanceAccounting/SettingAkun/index";
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/SettingAkun/list.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function add()
    {
        $data['page_title'] = "Add New Setting Akun";
		$data['akun'] = $this->db->get_where('tmst_chart_of_account', array('Is_Active' => 1));
        $data['page_content'] = "FinanceAccounting/SettingAkun/add";
        $data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/SettingAkun/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $data);
    }

	public function post()
    {
        $this->db->trans_start();
        $this->db->insert('tmst_akun_setting', [
            'trx_name' => $this->input->post('trx_name'),
            'id_akun_debit' => $this->input->post('id_akun_debit'),
			'id_akun_credit' => $this->input->post('id_akun_credit'),
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
        $data['RowData'] = $this->db->get_where('tmst_akun_setting', ['sysid' => $sysid])->row();
        $data['page_title'] = "Edit Data Setting Akun : " . $data['RowData']->trx_name;
		$data['akun'] = $this->db->get_where('tmst_chart_of_account', array('Is_Active' => 1));
        $data['page_content'] = "FinanceAccounting/SettingAkun/edit";
        $data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/SettingAkun/edit.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $data);
    }

	public function update()
    {
        $this->db->trans_start();
        $this->db->where('SysId', $this->input->post('sysid'))->update('tmst_akun_setting', [
            'trx_name' => $this->input->post('trx_name'),
            'id_akun_debit' => $this->input->post('id_akun_debit'),
			'id_akun_credit' => $this->input->post('id_akun_credit'),
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

	public function DT_list_data()
	{
		$query = "SELECT
					A.sysid,
					A.trx_name,
					A.id_akun_debit,
					CONCAT_WS(' - ', B.kode_akun, B.nama_akun) AS kode_akun_debit,
					A.id_akun_credit,
					CONCAT_WS(' - ', C.kode_akun, C.nama_akun) AS kode_akun_credit
				FROM
					tmst_akun_setting A
					INNER JOIN tmst_chart_of_account B ON A.id_akun_debit = B.SysId
					INNER JOIN tmst_chart_of_account C ON A.id_akun_credit = C.SysId";
		$where  = null;
		$search = array('A.trx_name', 'B.kode_akun', 'B.nama_akun', 'C.kode_akun', 'C.nama_akun');

		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}
}
