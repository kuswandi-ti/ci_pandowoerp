<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BukuBesar extends CI_Controller
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
		if(isset($_GET['coa']) && ! empty($_GET['coa']))
		{
			$id_coa = $_GET['coa'];
			$dari_tanggal = $_GET['dari_tanggal'];
			$sampai_tanggal = $_GET['sampai_tanggal'];
			$data['report'] = $this->generate_report($id_coa, $dari_tanggal, $sampai_tanggal);
		}
		else 
		{
			$data['report'] = $this->generate_report("", date('Y/m/d'), date('Y/m/d'));
		}
		$data['page_title'] = "List of Buku Besar";
		$data['page_content'] = "FinanceAccounting/BukuBesar/index";
		$data['coa'] = $this->db->get('tmst_chart_of_account');		
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/BukuBesar/index.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function generate_report($id_coa = "", $dari_tanggal = "", $sampai_tanggal = "")
	{
		$hasil = "";
		$saldo_awal = 0;
		$saldo_current = 0;
		$saldo_akhir = 0;

		// Saldo Awal
		$qry_saldo_awal = $this->db->query("SELECT debit - credit AS saldo_awal ".
						  "FROM tmst_chart_of_account ".
						  "WHERE SysId = '" . $id_coa . "'");
		if(($qry_saldo_awal->num_rows()) > 0)
		{
			foreach($qry_saldo_awal->result() as $row1)
			{
				$hasil .= "<tr style='border-bottom: 1px solid black;'>";
					$hasil .= "<td colspan='7'><b>Saldo Awal</b></td>";
					$hasil .= "<td align ='right'><b>" . format_currency($row1->saldo_awal) . "</b></td>";
				$hasil .= "</tr>";
				$saldo_awal = $row1->saldo_awal;
				$saldo_akhir = $saldo_awal;
			}			
		}
		else 
		{
			$hasil .= "";
		}

		// Jurnal
		$qry_jurnal = $this->db->query("SELECT B.no_jurnal, B.tgl_jurnal, D.nama_tipe_jurnal, B.reff_desc, B.keterangan, A.debit, A.credit ".
					  "FROM ttrx_dtl_jurnal A ".
					  "LEFT OUTER JOIN ttrx_hdr_jurnal B ON A.id_hdr = B.SysId ".
					  "LEFT OUTER JOIN tmst_chart_of_account C ON A.id_coa = C.SysId ".
					  "LEFT JOIN tmst_jurnal_tipe D ON B.id_jurnal_tipe = D.SysId ".
					  "WHERE C.SysId = '" . $id_coa . "' AND (B.tgl_jurnal BETWEEN '" . $dari_tanggal . "' AND '" . $sampai_tanggal . "')");
		if(($qry_jurnal->num_rows()) > 0)
		{
			foreach($qry_jurnal->result() as $row2)
			{		
				$saldo_current = $saldo_akhir + $row2->debit - $row2->credit;		
				$hasil .= "<tr style='border-bottom: 1px solid black;'>";
					$hasil .= "<td>" . $row2->no_jurnal . "</td>";
					$hasil .= "<td align ='center'>" . $row2->tgl_jurnal . "</td>";
					$hasil .= "<td>" . $row2->reff_desc . "</td>";
					$hasil .= "<td>" . $row2->keterangan . "</td>";
					$hasil .= "<td align ='center'>" . $row2->nama_tipe_jurnal . "</td>";
					$hasil .= "<td align ='right'>" . format_currency($row2->debit) . "</td>";
					$hasil .= "<td align ='right'>" . format_currency($row2->credit) . "</td>";
					$hasil .= "<td align ='right'>" . format_currency($saldo_current) . "</td>";
				$hasil .= "</tr>";	
				$saldo_akhir = $saldo_current;
			}			
		}
		else 
		{
			$hasil .= "";
		}

		// Saldo Akhir
		$hasil .= "<tr style='border-bottom: 1px solid black;'>";
			$hasil .= "<td colspan='7'><b>Saldo Akhir</b></td>";
			$hasil .= "<td align ='right'><b>" . format_currency($saldo_akhir) . "</b></td>";
		$hasil .= "</tr>";

		return $hasil;
	}

	public function DT_List_BukuBesar()
	{
		$query = "SELECT A.SysId AS id_dtl, B.SysId AS id_hdr, B.no_jurnal, B.tgl_jurnal, B.reff_desc, C.kode_akun, C.nama_akun, CONCAT_WS(' - ', C.kode_akun, C.nama_akun) AS akun, ".
		         	"COALESCE(A.debit, 0) AS debit, COALESCE(A.credit, 0) AS credit, B.keterangan ".
                 "FROM ttrx_dtl_jurnal A ".
                 "LEFT JOIN ttrx_hdr_jurnal B ON A.id_hdr = B.SysId ".
                 "LEFT JOIN tmst_chart_of_account C ON A.id_coa = C.SysId";
		$where  = null;
		$search = array('no_jurnal', 'reff_desc', 'kode_akun', 'nama_akun', 'keterangan');

		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}
}
