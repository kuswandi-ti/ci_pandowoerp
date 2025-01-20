<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ListAsset extends CI_Controller
{
	public $layout = 'layout';

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
		$data['page_title'] = "List Asset";
		$data['page_content'] = "Asset/ListAsset/index";
		$data['script_page'] =  '<script src="' . base_url() . 'assets/asset-script/ListAsset/index.js?v=' . time() . '"></script>';
		$this->load->view($this->layout, $data);
	}

	public function store()
	{
		$this->db->trans_start();

		$state         		= $this->input->post('state');
		$sysid      		= $this->input->post('sysid');

		$masa_tahun_pakai	= $this->input->post('masa_tahun_pakai');
		$nilai_penyusutan 	= $this->input->post('nilai_penyusutan');
		
		$this->db->where('SysId', $sysid);
		$this->db->update('tmst_item_asset', [
			'masa_tahun_pakai'	=> $masa_tahun_pakai,
			'nilai_penyusutan'	=> $nilai_penyusutan,
		]);

		$error_msg = $this->db->error()["message"];
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				"code" => 505,
				"msg" => $error_msg
			];
		} else {
			$this->db->trans_commit();
			$response = [
				"code" => 200,
				"msg" => "Berhasil Menyimpan Data !"
			];
		}
		return $this->help->Fn_resulting_response($response);
	}

	public function edit()
	{
		$sysid = $this->input->post('sysid');

		$this->db->where('t1.SysId', $sysid);
		$this->db->select('t1.*, t2.Item_Code AS item_code, t2.Item_Name AS item_name, t2.uom AS uom');
		$this->db->from('tmst_item_asset' . ' as t1');
		$this->db->join('qmst_item' . ' as t2', 't1.item_id = t2.SysId', 'left');
		$data = $this->db->get()->row();

		$response = [
			"code"      => 200,
			"msg"       => "Berhasil Mendapatkan Data !",
			"data"  	=> $data,
		];
		return $this->help->Fn_resulting_response($response);
	}

	public function DT_listdata()
	{
		$query  = "SELECT
					A.SysId AS sysid,
					A.no_asset,
					A.item_id,
					B.Item_Code AS item_code,
					B.Item_Name AS item_name,
					A.tgl_perolehan,
					A.tahun_perolehan,
					A.harga_perolehan,
					A.masa_tahun_pakai,
					A.nilai_penyusutan,
					A.Is_Active
				FROM
					tmst_item_asset A
					LEFT OUTER JOIN tmst_item B ON A.item_id = B.SysId";
		$search = array('A.no_asset', 'B.Item_Code', 'B.Item_Name', 'A.tahun_perolehan');
		$where  = null;
		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}

	// public function DT_modallistofitem()
	// {
	// 	$id_customer = $this->input->get('id_customer');
	// 	$id_invoice = $this->input->get('id_invoice');

	// 	$query  = "SELECT * FROM qview_penerimaan_invoice_receive_os";

	// 	$search = array('no_invoice');
	// 	$where  = array('id_invoice NOT IN ' => explode(',', $id_invoice), 'id_customer ' => $id_customer);
	// 	$isWhere = null;

	// 	header('Content-Type: application/json');
	// 	echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	// }
}
