<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NeracaKeuangan extends CI_Controller
{
	public $layout = 'layout';

	protected $Date;
	protected $DateTime;
	protected $ttrx_hdr = 'ttrx_hdr_jurnal';
	protected $ttrx_dtl = 'ttrx_jurnal_jurnal';
	protected $tmst_coa = 'tmst_chart_of_account';

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
		if ((isset($_GET['dari_tanggal']) && ! empty($_GET['dari_tanggal'])) || (isset($_GET['sampai_tanggal']) && ! empty($_GET['sampai_tanggal']))) {
			$dari_tanggal = $_GET['dari_tanggal'];
			$sampai_tanggal = $_GET['sampai_tanggal'];
			//$data['report'] = $this->generate_report($section, $dari_tanggal, $sampai_tanggal);			
		} else {
			$dari_tanggal = date('Y/m/d');
			$sampai_tanggal = date('Y/m/d');
			//$data['report'] = $this->generate_report("", $dari_tanggal, $sampai_tanggal);
		}

		$data['page_title'] = "List of Neraca Keuangan";
		$data['page_content'] = "FinanceAccounting/NeracaKeuangan/index";
		$data['report_left'] = $this->generate_report("left", $dari_tanggal, $sampai_tanggal);
		$data['report_right'] = $this->generate_report("right", $dari_tanggal, $sampai_tanggal);
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/NeracaKeuangan/index.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function generate_report($section = "", $dari_tanggal = "", $sampai_tanggal = "")
	{
		$hasil = "";
		$leftrightsection = ($section == "left") ? "1" : "3, 5";

		$total_all = 0;

		$qry_tipe_akun = $this->db->query("SELECT A.SysId, CONCAT_WS(' - ', A.kode_tipe_akun, A.nama_tipe_akun) AS akun, " .
			"SUM(COALESCE(C.debit, 0)) - SUM(COALESCE(C.credit, 0)) AS saldo_awal, " .
			"SUM(COALESCE(D.debit, 0)) AS debit, SUM(COALESCE(D.credit, 0)) AS credit, " .
			"(SUM(COALESCE(C.debit, 0)) - SUM(COALESCE(C.credit, 0))) + SUM(COALESCE(D.debit, 0)) - SUM(COALESCE(D.credit, 0)) AS saldo_akhir " .
			"FROM tmst_akun_tipe A " .
			"LEFT OUTER JOIN tmst_akun_induk B ON A.SysId = B.id_akun_tipe " .
			"LEFT OUTER JOIN tmst_chart_of_account C ON B.SysId = C.id_akun_induk " .
			"LEFT OUTER JOIN ttrx_dtl_jurnal D ON C.SysId = D.id_coa " .
			"LEFT OUTER JOIN ttrx_hdr_jurnal E ON D.id_hdr = E.SysId " .
			"WHERE A.kode_tipe_akun IN (" . $leftrightsection . ") AND (D.debit > 0 OR D.credit > 0) AND E.isCancel = 0 AND (E.tgl_jurnal BETWEEN '" . $dari_tanggal . "' AND '" . $sampai_tanggal . "') " .
			"GROUP BY A.SysId, A.kode_tipe_akun, A.nama_tipe_akun");
		if (($qry_tipe_akun->num_rows()) > 0) {
			foreach ($qry_tipe_akun->result() as $row1) {
				$hasil .= "<tr style='border-bottom: 1px solid black;'>";
				$hasil .= "<td colspan='3'><b>" . $row1->akun . "</b></td>";
				$hasil .= "<td align ='right' class='text-primary'><b>" . format_currency($row1->saldo_akhir) . "</b></td>";
				$hasil .= "</tr>";

				$qry_induk_akun = $this->db->query("SELECT A.SysId, CONCAT_WS(' - ', A.kode_akun, A.nama_akun) AS akun, " .
					"SUM(COALESCE(B.debit, 0)) - SUM(COALESCE(B.credit, 0)) AS saldo_awal, " .
					"SUM(COALESCE(C.debit, 0)) AS debit, SUM(COALESCE(C.credit, 0)) AS credit, " .
					"(SUM(COALESCE(B.debit, 0)) - SUM(COALESCE(B.credit, 0))) + SUM(COALESCE(C.debit, 0)) - SUM(COALESCE(C.credit, 0)) AS saldo_akhir " .
					"FROM tmst_akun_induk A " .
					"LEFT OUTER JOIN tmst_chart_of_account B ON A.SysId = B.id_akun_induk " .
					"LEFT OUTER JOIN ttrx_dtl_jurnal C ON B.SysId = C.id_coa " .
					"LEFT OUTER JOIN ttrx_hdr_jurnal D ON C.id_hdr = D.SysId " .
					"WHERE A.id_akun_tipe = '" . $row1->SysId . "' AND (C.debit > 0 OR C.credit > 0) AND D.isCancel = 0 AND (D.tgl_jurnal BETWEEN '" . $dari_tanggal . "' AND '" . $sampai_tanggal . "') " .
					"GROUP BY A.SysId, A.kode_akun, A.nama_akun");
				if (($qry_induk_akun->num_rows()) > 0) {
					foreach ($qry_induk_akun->result() as $row2) {
						$hasil .= "<tr style='border-bottom: 1px solid black;'>";
						$hasil .= "<td width='2%'>&nbsp;</td>";
						$hasil .= "<td colspan='2'><b>" . $row2->akun . "</b></td>";
						$hasil .= "<td align ='right'><b>" . format_currency($row2->saldo_akhir) . "</b></td>";
						$hasil .= "</tr>";

						$qry_coa = $this->db->query("SELECT CONCAT_WS(' - ', A.kode_akun, A.nama_akun) AS akun, SUM(COALESCE(A.debit, 0)) - SUM(COALESCE(A.credit, 0)) AS saldo_awal, " .
							"SUM(COALESCE(B.debit, 0)) AS debit, SUM(COALESCE(B.credit, 0)) AS credit, " .
							"(SUM(COALESCE(A.debit, 0)) - SUM(COALESCE(A.credit, 0))) + SUM(COALESCE(B.debit, 0)) - SUM(COALESCE(B.credit, 0)) AS saldo_akhir " .
							"FROM tmst_chart_of_account A " .
							"LEFT OUTER JOIN ttrx_dtl_jurnal B ON A.SysId = B.id_coa " .
							"LEFT OUTER JOIN ttrx_hdr_jurnal C ON B.id_hdr = C.SysId " .
							"WHERE A.id_akun_induk = '" . $row2->SysId . "' AND (B.debit > 0 OR B.credit > 0) AND C.isCancel = 0 AND (C.tgl_jurnal BETWEEN '" . $dari_tanggal . "' AND '" . $sampai_tanggal . "') " .
							"GROUP BY A.kode_akun, A.nama_akun");
						if (($qry_coa->num_rows()) > 0) {
							foreach ($qry_coa->result() as $row3) {
								$total_all = $total_all + $row3->saldo_akhir;
								$hasil .= "<tr style='border-bottom: 1px solid black;'>";
								$hasil .= "<td width='2%'>&nbsp;</td>";
								$hasil .= "<td width='2%'>&nbsp;</td>";
								$hasil .= "<td>" . $row3->akun . "</td>";
								$hasil .= "<td align ='right'>" . format_currency($row3->saldo_akhir) . "</td>";
								$hasil .= "</tr>";
							}
						}
					}
				}
			}

			$hasil .= "<tr>";
			$hasil .= "<td colspan='3' class='text-danger'><b>TOTAL</b></td>";
			$hasil .= "<td  align ='right' class='text-danger'><b>" . format_currency($total_all) . "</b></td>";
			$hasil .= "</tr>";
		} else {
			$hasil .= "";
		}

		return $hasil;
	}

	public function DT_List_NeracaKeuangan()
	{
		$query = "SELECT A.id_coa, CONCAT_WS(' - ', C.kode_akun, C.nama_akun) AS akun, C.saldo + SUM(A.debit) - SUM(A.credit) AS saldo_akhir " .
			"FROM ttrx_dtl_jurnal A " .
			"INNER JOIN ttrx_hdr_jurnal B ON A.id_hdr = B.SysId " .
			"INNER JOIN tmst_chart_of_account C ON A.id_coa = C.SysId";
		$where  = null;
		$search = array('kode_akun', 'nama_akun');

		$isWhere = null;

		$groupby = "GROUP BY A.id_coa, C.kode_akun, C.nama_akun";

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query_group_by($query, $search, $where, $isWhere, $groupby);
	}
}
