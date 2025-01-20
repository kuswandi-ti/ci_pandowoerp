<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NeracaSaldo extends CI_Controller
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
		$data['page_title'] = "List of Neraca Saldo";
		$data['page_content'] = "FinanceAccounting/NeracaSaldo/index";
		$data['report'] = $this->generate_report();
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/NeracaSaldo/index.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function generate_report()
	{
		$hasil = "";

		/*$qry_tipe_akun = $this->db->query("SELECT A.SysId, CONCAT_WS(' - ', A.kode_tipe_akun, A.nama_tipe_akun) AS akun, ".
						    "SUM(COALESCE(C.debit, 0)) - SUM(COALESCE(C.credit, 0)) AS saldo_awal, ".
							"SUM(COALESCE(D.debit, 0)) AS debit, SUM(COALESCE(D.credit, 0)) AS credit, ".
							"(SUM(COALESCE(C.debit, 0)) - SUM(COALESCE(C.credit, 0))) + SUM(COALESCE(D.debit, 0)) - SUM(COALESCE(D.credit, 0)) AS saldo_akhir ".
						  "FROM tmst_akun_tipe A ".
						  "LEFT OUTER JOIN tmst_akun_induk B ON A.SysId = B.id_akun_tipe ".
						  "LEFT OUTER JOIN tmst_chart_of_account C ON B.SysId = C.id_akun_induk ".
						  "LEFT OUTER JOIN ttrx_dtl_jurnal D ON C.SysId = D.id_coa ".
						  "WHERE A.kode_tipe_akun IN (1, 3, 4, 5) AND (D.debit > 0 OR D.credit > 0) ".
						  "GROUP BY A.SysId, A.kode_tipe_akun, A.nama_tipe_akun");*/
		$qry_tipe_akun = $this->db->query("SELECT A.SysId, CONCAT_WS(' - ', A.kode_tipe_akun, A.nama_tipe_akun) AS akun, " .
			"SUM(COALESCE(C.credit, 0)) - SUM(COALESCE(C.debit, 0)) AS saldo_awal, " .
			"SUM(COALESCE(D.debit, 0)) AS debit, SUM(COALESCE(D.credit, 0)) AS credit, " .
			"(SUM(COALESCE(C.credit, 0)) - SUM(COALESCE(C.debit, 0))) + SUM(COALESCE(D.credit, 0)) - SUM(COALESCE(D.debit, 0)) AS saldo_akhir " .
			"FROM tmst_akun_tipe A " .
			"LEFT OUTER JOIN tmst_akun_induk B ON A.SysId = B.id_akun_tipe " .
			"LEFT OUTER JOIN tmst_chart_of_account C ON B.SysId = C.id_akun_induk " .
			"LEFT OUTER JOIN ttrx_dtl_jurnal D ON C.SysId = D.id_coa " .
			"WHERE A.kode_tipe_akun IN (1, 3, 4, 5) AND (D.debit > 0 OR D.credit > 0) " .
			"GROUP BY A.SysId, A.kode_tipe_akun, A.nama_tipe_akun");
		if (($qry_tipe_akun->num_rows()) > 0) {
			$total_debit = 0;
			$total_credit = 0;
			foreach ($qry_tipe_akun->result() as $row1) {
				$total_debit = $total_debit + $row1->debit;
				$total_credit = $total_credit + $row1->credit;

				$hasil .= "<tr style='border-bottom: 1px solid black;'>";
				$hasil .= "<td colspan='3'><b>" . $row1->akun . "</b></td>";
				//$hasil .= "<td align ='right'><b>" . format_currency($row1->saldo_awal) . "</b></td>";
				$hasil .= "<td align ='right'><b>" . format_currency($row1->debit) . "</b></td>";
				$hasil .= "<td align ='right'><b>" . format_currency($row1->credit) . "</b></td>";
				//$hasil .= "<td align ='right'><b>" . format_currency($row1->saldo_akhir) . "</b></td>";
				$hasil .= "</tr>";

				/*$qry_induk_akun = $this->db->query("SELECT A.SysId, CONCAT_WS(' - ', A.kode_akun, A.nama_akun) AS akun, " .
					"SUM(COALESCE(B.debit, 0)) - SUM(COALESCE(B.credit, 0)) AS saldo_awal, " .
					"SUM(COALESCE(C.debit, 0)) AS debit, SUM(COALESCE(C.credit, 0)) AS credit, " .
					"(SUM(COALESCE(B.debit, 0)) - SUM(COALESCE(B.credit, 0))) + SUM(COALESCE(C.debit, 0)) - SUM(COALESCE(C.credit, 0)) AS saldo_akhir " .
					"FROM tmst_akun_induk A " .
					"LEFT OUTER JOIN tmst_chart_of_account B ON A.SysId = B.id_akun_induk " .
					"LEFT OUTER JOIN ttrx_dtl_jurnal C ON B.SysId = C.id_coa " .
					"WHERE A.id_akun_tipe = '" . $row1->SysId . "' AND (C.debit > 0 OR C.credit > 0) " .
					"GROUP BY A.SysId, A.kode_akun, A.nama_akun");*/
				$qry_induk_akun = $this->db->query("SELECT A.SysId, CONCAT_WS(' - ', A.kode_akun, A.nama_akun) AS akun, " .
					"SUM(COALESCE(B.credit, 0)) - SUM(COALESCE(B.debit, 0)) AS saldo_awal, " .
					"SUM(COALESCE(C.debit, 0)) AS debit, SUM(COALESCE(C.credit, 0)) AS credit, " .
					"(SUM(COALESCE(B.credit, 0)) - SUM(COALESCE(B.debit, 0))) + SUM(COALESCE(C.credit, 0)) - SUM(COALESCE(C.debit, 0)) AS saldo_akhir " .
					"FROM tmst_akun_induk A " .
					"LEFT OUTER JOIN tmst_chart_of_account B ON A.SysId = B.id_akun_induk " .
					"LEFT OUTER JOIN ttrx_dtl_jurnal C ON B.SysId = C.id_coa " .
					"WHERE A.id_akun_tipe = '" . $row1->SysId . "' AND (C.debit > 0 OR C.credit > 0) " .
					"GROUP BY A.SysId, A.kode_akun, A.nama_akun");
				if (($qry_induk_akun->num_rows()) > 0) {
					foreach ($qry_induk_akun->result() as $row2) {
						$hasil .= "<tr style='border-bottom: 1px solid black;'>";
						$hasil .= "<td width='2%'>&nbsp;</td>";
						$hasil .= "<td colspan='2'><b>" . $row2->akun . "</b></td>";
						//$hasil .= "<td align ='right'><b>" . format_currency($row2->saldo_awal) . "</b></td>";
						$hasil .= "<td align ='right'><b>" . format_currency($row2->debit) . "</b></td>";
						$hasil .= "<td align ='right'><b>" . format_currency($row2->credit) . "</b></td>";
						//$hasil .= "<td align ='right'><b>" . format_currency($row2->saldo_akhir) . "</b></td>";
						$hasil .= "</tr>";

						/*$qry_coa = $this->db->query("SELECT CONCAT_WS(' - ', A.kode_akun, A.nama_akun) AS akun, SUM(COALESCE(A.debit, 0)) - SUM(COALESCE(A.credit, 0)) AS saldo_awal, " .
							"SUM(COALESCE(B.debit, 0)) AS debit, SUM(COALESCE(B.credit, 0)) AS credit, " .
							"(SUM(COALESCE(A.debit, 0)) - SUM(COALESCE(A.credit, 0))) + SUM(COALESCE(B.debit, 0)) - SUM(COALESCE(B.credit, 0)) AS saldo_akhir " .
							"FROM tmst_chart_of_account A " .
							"LEFT OUTER JOIN ttrx_dtl_jurnal B ON A.SysId = B.id_coa " .
							"WHERE A.id_akun_induk = '" . $row2->SysId . "' AND (B.debit > 0 OR B.credit > 0) " .
							"GROUP BY A.kode_akun, A.nama_akun");*/
						$qry_coa = $this->db->query("SELECT CONCAT_WS(' - ', A.kode_akun, A.nama_akun) AS akun, SUM(COALESCE(A.credit, 0)) - SUM(COALESCE(A.debit, 0)) AS saldo_awal, " .
							"SUM(COALESCE(B.debit, 0)) AS debit, SUM(COALESCE(B.credit, 0)) AS credit, " .
							"(SUM(COALESCE(A.credit, 0)) - SUM(COALESCE(A.debit, 0))) + SUM(COALESCE(B.credit, 0)) - SUM(COALESCE(B.debit, 0)) AS saldo_akhir " .
							"FROM tmst_chart_of_account A " .
							"LEFT OUTER JOIN ttrx_dtl_jurnal B ON A.SysId = B.id_coa " .
							"WHERE A.id_akun_induk = '" . $row2->SysId . "' AND (B.debit > 0 OR B.credit > 0) " .
							"GROUP BY A.kode_akun, A.nama_akun");
						if (($qry_coa->num_rows()) > 0) {
							foreach ($qry_coa->result() as $row3) {
								$hasil .= "<tr style='border-bottom: 1px solid black;'>";
								$hasil .= "<td width='2%'>&nbsp;</td>";
								$hasil .= "<td width='2%'>&nbsp;</td>";
								$hasil .= "<td>" . $row3->akun . "</td>";
								//$hasil .= "<td align ='right'>" . format_currency($row3->saldo_awal) . "</td>";
								$hasil .= "<td align ='right'>" . format_currency($row3->debit) . "</td>";
								$hasil .= "<td align ='right'>" . format_currency($row3->credit) . "</td>";
								//$hasil .= "<td align ='right'>" . format_currency($row3->saldo_akhir) . "</td>";
								$hasil .= "</tr>";
							}
						}
					}
				}
			}
			/*$hasil .= "<tr>";
			$hasil .= "<td colspan='3'><b>TOTAL</b></td>";
			$hasil .= "<td  align ='right'><b>" . format_currency($total_debit) . "</b></td>";
			$hasil .= "<td  align ='right'><b>" . format_currency($total_credit) . "</b></td>";
			$hasil .= "</tr>";*/
		} else {
			$hasil .= "";
		}

		return $hasil;
	}

	public function DT_List_NeracaSaldo()
	{
		$query = "SELECT A.id_coa, CONCAT_WS(' - ', C.kode_akun, C.nama_akun) AS akun, C.saldo AS saldo_awal, SUM(A.debit) AS debit, SUM(A.credit) AS credit, C.saldo + SUM(A.debit) - SUM(A.credit) AS saldo_akhir " .
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
