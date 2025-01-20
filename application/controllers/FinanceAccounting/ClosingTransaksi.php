<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ClosingTransaksi extends CI_Controller
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
		$data['page_title'] = "Closing Transaksi";
		$data['page_content'] = "FinanceAccounting/ClosingTransaksi/index";
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/ClosingTransaksi/index.js?v=' . time() . '"></script>';
		$this->load->view($this->layout, $data);
	}

	public function store()
	{
		$this->db->trans_start();

		$dari_tanggal	= $this->input->post('dari_tanggal');
		$sampai_tanggal	= $this->input->post('sampai_tanggal');

		$qry_tipe_akun = $this->db->query("SELECT 
											A.SysId AS id_coa, 
											CONCAT_WS(' - ', A.kode_akun, A.nama_akun) AS akun,
											COALESCE(A.debit, 0) AS saldo_awal_debit,
											COALESCE(A.credit, 0) AS saldo_awal_credit,
											SUM(COALESCE(B.debit, 0)) AS jurnal_debit, 
											SUM(COALESCE(B.credit, 0)) AS jurnal_credit,
											COALESCE(A.debit, 0) + SUM(COALESCE(B.debit, 0)) AS saldo_akhir_debit,
											COALESCE(A.credit, 0) + SUM(COALESCE(B.credit, 0)) AS saldo_akhir_credit	
										FROM  
											tmst_chart_of_account A 	
											LEFT OUTER JOIN ttrx_dtl_jurnal B ON A.SysId = B.id_coa
											LEFT OUTER JOIN ttrx_hdr_jurnal C ON B.id_hdr = C.SysId
										WHERE
											C.tgl_jurnal BETWEEN '" . $dari_tanggal . "' AND '" . $sampai_tanggal . "'
											AND C.isCancel = 0
										GROUP 
											BY A.SysId, A.kode_akun, A.nama_akun");
		if(($qry_tipe_akun->num_rows()) > 0) {
			foreach($qry_tipe_akun->result() as $row) {
				$this->db->where('SysId', $row->id_coa);
				$this->db->update('tmst_chart_of_account', [
					'debit' => $row->saldo_akhir_debit,
					'credit' => $row->saldo_akhir_credit,
				]);
			}
		}

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
				"msg" => "Berhasil Memproses Data !"
			];
		}
		return $this->help->Fn_resulting_response($response);
	}
}
