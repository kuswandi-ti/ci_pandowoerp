<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JurnalPenerimaan extends CI_Controller
{
	public $layout = 'layout';
	protected $ttrx_hdr	= 'ttrx_hdr_jurnal';
	protected $ttrx_dtl	= 'ttrx_dtl_jurnal';
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
		$data['page_title'] = "List Jurnal Penerimaan (Customer Invoice)";
		$data['page_content'] = "FinanceAccounting/JurnalPenerimaan/index";
		$data['receive_invoice'] = $this->db->query('SELECT
														A.SysId,
														A.doc_number,
														B.item_amount,
														B.amount_receive,
														COALESCE(C.debit, 0) AS debit,
														COALESCE(C.credit, 0) AS credit,
														A.Is_Lunas
													FROM
														ttrx_hdr_receive_invoice A
														LEFT OUTER JOIN ttrx_dtl_receive_invoice B ON A.SysId = B.id_hdr
														LEFT OUTER JOIN ttrx_hdr_jurnal C ON A.SysId = C.reff_id
													WHERE
														C.no_jurnal IS NULL');
		$data['invoice'] = $this->db->query('SELECT
														A.SysId,
														A.doc_number,
														B.item_amount,
														B.amount_receive,
														COALESCE(C.debit, 0) AS debit,
														COALESCE(C.credit, 0) AS credit,
														A.Is_Lunas
													FROM
														ttrx_hdr_receive_invoice A
														LEFT OUTER JOIN ttrx_dtl_receive_invoice B ON A.SysId = B.id_hdr
														LEFT OUTER JOIN ttrx_hdr_jurnal C ON A.SysId = C.reff_id
													WHERE
														C.no_jurnal IS NOT NULL');
		$data['akun_induk'] = $this->db->query('SELECT
													*
												FROM
													tmst_akun_induk');
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/JurnalPenerimaan/index.js?v=' . time() . '"></script>';
		$this->load->view($this->layout, $data);
	}

	public function store()
	{
		$state         	= $this->input->post('state');
		$sysid_hdr      = $this->input->post('sysid_hdr');

		$no_jurnal      = $this->input->post('no_jurnal');
		$tgl_jurnal     = $this->input->post('tgl_jurnal');
		$reff_id    	= $this->input->post('reff_id');
		$reff_desc    	= $this->input->post('reff_desc');
		$jurnal_tipe   	= '3';
		$keterangan  	= $this->input->post('keterangan');
		$type_doc   	= $this->input->post('type_doc');
		$total_debit 	= $this->help->float_to_value($this->input->post('total_debit'));
		$total_credit 	= $this->help->float_to_value($this->input->post('total_kredit'));

		$kode_akun      = $this->input->post('kode_akun');
		$id_coa      	= $this->input->post('coa_id');
		$debit      	= $this->help->float_to_value($this->input->post('debit'));
		$credit      	= $this->help->float_to_value($this->input->post('credit'));
		$note      		= $this->input->post('note');

		if ($state == 'ADD') {
			$doc_no = $this->help->Generate_Identity_Number_FA('JUR');
		}

		$this->db->trans_start();

		if ($state == 'ADD') {
			$this->db->insert($this->ttrx_hdr, [
				'no_jurnal'        	=> $doc_no,
				'tgl_jurnal'      	=> date('Y-m-d', strtotime($tgl_jurnal)),
				'reff_id'         	=> $reff_id,
				'reff_desc'         => $reff_desc,
				'id_jurnal_tipe' 	=> 3,
				'keterangan'       	=> $keterangan,
				'type_doc'       	=> $type_doc,
				'debit'    			=> $total_debit,
				'credit'    		=> $total_credit,
				'Created_at' 		=> $this->DateTime,
				'Created_by' 		=> $this->session->userdata('impsys_nik'),
			]);
			$id_hdr = $this->db->insert_id();

			for ($i = 0; $i < count($kode_akun); $i++) {
				$this->db->insert($this->ttrx_dtl, [
					'id_hdr'    	=> $id_hdr,
					'id_coa'     	=> $id_coa[$i],
					'debit'         => $debit[$i],
					'credit'     	=> $credit[$i],
					'note'     		=> $note[$i],
					'Created_at' 	=> $this->DateTime,
					'Created_by' 	=> $this->session->userdata('impsys_nik'),
				]);
			}
		} else {
			$this->db->where('SysId', $sysid_hdr);
			$this->db->update($this->ttrx_hdr, [
				'tgl_jurnal'      	=> date('Y-m-d', strtotime($tgl_jurnal)),
				'reff_id'         	=> $reff_id,
				'reff_desc'         => $reff_desc,
				'id_jurnal_tipe' 	=> 3,
				'keterangan'       	=> $keterangan,
				'type_doc'       	=> $type_doc,
				'debit'    			=> $total_debit,
				'credit'    		=> $total_credit,
				'Last_updated_at'   => $this->DateTime,
				'Last_updated_by'   => $this->session->userdata('impsys_nik'),
			]);

			$this->db->delete($this->ttrx_dtl, [
				'id_hdr' => $sysid_hdr
			]);

			if (!empty($kode_akun)) {
				for ($i = 0; $i < count($kode_akun); $i++) {
					$this->db->insert($this->ttrx_dtl, [
						'id_hdr'    		=> $sysid_hdr,
						'id_coa'     		=> $id_coa[$i],
						'debit'         	=> $debit[$i],
						'credit'     		=> $credit[$i],
						'note'     			=> $note[$i],
						'Created_at' 		=> $this->DateTime,
						'Created_by' 		=> $this->session->userdata('impsys_nik'),
						'Last_updated_at'   => $this->DateTime,
						'Last_updated_by'   => $this->session->userdata('impsys_nik'),
					]);
				}
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				"code" => 505,
				"msg" => "Proses penyimpanan gagal !"
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
		$sysid_hdr = $this->input->post('sysid');

		$this->db->where('t1.SysId', $sysid_hdr);
		$this->db->select('t1.*, t2.doc_number, t2.Is_Lunas');
		$this->db->from($this->ttrx_hdr . ' as t1');
		$this->db->join('ttrx_hdr_receive_invoice' . ' as t2', 't1.reff_id = t2.SysId', 'left');
		$data_hdr = $this->db->get()->row();

		$this->db->where('t1.id_hdr', $sysid_hdr);
		$this->db->select('t1.*, t2.SysId AS id_coa, t2.kode_akun, t2.nama_akun');
		$this->db->from($this->ttrx_dtl . ' as t1');
		$this->db->join('tmst_chart_of_account' . ' as t2', 't1.id_coa = t2.SysId', 'left');
		$data_dtl = $this->db->get()->result();

		$response = [
			"code"      => 200,
			"msg"       => "Berhasil Mendapatkan Data !",
			"data_hdr"  => $data_hdr,
			"data_dtl"  => $data_dtl,
		];
		return $this->help->Fn_resulting_response($response);
	}

	public function DT_listdata()
	{
		$query  = "SELECT A.*, B.doc_number, C.no_jurnal_cancel  " .
			"FROM ttrx_hdr_jurnal A " .
			"INNER JOIN ttrx_hdr_receive_invoice B ON A.reff_id = B.SysId " .
			"LEFT OUTER JOIN ttrx_pivot_jurnal C ON A.no_jurnal = C.no_jurnal_source";

		$search = array('no_jurnal', 'reff_desc', 'A.keterangan', 'doc_number');
		$where  = array('id_jurnal_tipe ' => '3');
		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}

	public function DT_modallistofitem()
	{
		$id_coa = $this->input->get('id_coa');
		$id_ri = $this->input->get('id_ri');
		$flag_lunas = $this->input->get('flag_lunas');
		$id_akun_induk = $this->input->get('id_akun_induk');

		// $query  = "SELECT * FROM tmst_chart_of_account";
		$query = "SELECT
					A.*,
					B.nama_akun AS nama_akun_induk
				FROM
					tmst_chart_of_account A
					LEFT OUTER JOIN tmst_akun_induk B ON A.id_akun_induk = B.SysId";

		$search = array('A.kode_akun', 'A.nama_akun', 'B.nama_akun');
		// $where  = array('SysId NOT IN ' => explode(',', $id_coa), 'Is_Active ' => 1, 'id_akun_induk ' => $id_akun_induk);
		$where  = array('A.SysId NOT IN ' => explode(',', $id_coa), 'A.Is_Active ' => 1);
		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}

	public function get_ri()
	{
		$ri_id = $this->input->post('ri_id');

		$this->db->where('id_hdr', $ri_id);
		$this->db->select('*');
		$this->db->from('ttrx_dtl_receive_invoice');
		$data_ri = $this->db->get()->row();

		$response = [
			"code"      => 200,
			"msg"       => "Berhasil Mendapatkan Data !",
			"data_ri"  	=> $data_ri,
		];
		return $this->help->Fn_resulting_response($response);
	}

	public function init_ri()
	{
		$state = $this->input->post('state');
		$sysid = $this->input->post('sysid');

		if ($state == 'ADD') {
			$this->db->where('C.no_jurnal IS NULL');
			$output = '<option value="">Pilih Nomor Dokumen</option>';
		} else {
			$this->db->where('C.SysId', $sysid);
			$output = '';
		}

		$this->db->select('A.SysId, A.doc_number, B.item_amount, B.amount_receive, COALESCE(C.debit, 0) AS debit, COALESCE(C.credit, 0) AS credit, A.Is_Lunas, B.type_doc AS type_doc, C.SysId AS jurnal_id');
		$this->db->from('ttrx_hdr_receive_invoice' . ' as A');
		$this->db->join('ttrx_dtl_receive_invoice' . ' as B', 'A.SysId = B.id_hdr', 'left');
		$this->db->join('ttrx_hdr_jurnal' . ' as C', 'A.SysId = C.reff_id', 'left');
		$this->db->where('A.Is_Active', 1);
		$query = $this->db->get();

		foreach ($query->result() as $row) {
			$output .= "<option value=" . $row->SysId . " 
							data-doc_number=" . $row->doc_number . " 
							data-type-doc='" . $row->type_doc . "' 
							data-flag-lunas=" . $row->Is_Lunas . ">" . $row->doc_number .
				"</option>";
		}

		echo $output;
	}

	public function print($sysid)
	{
		$this->db->where('t1.SysId', $sysid);
		$this->db->select('t1.*, t3.Account_Name');
		$this->db->from('ttrx_hdr_jurnal' . ' as t1');
		$this->db->join('ttrx_hdr_receive_invoice as t2', 't1.reff_id = t2.SysId', 'left');
		$this->db->join('tmst_account as t3', 't2.id_customer = t3.SysId', 'left');
		$data_hdr = $this->db->get()->row();

		$this->db->where('t1.id_hdr', $sysid);
		$this->db->select('t1.*, t2.kode_akun, t2.nama_akun');
		$this->db->from('ttrx_dtl_jurnal' . ' as t1');
		$this->db->join('tmst_chart_of_account as t2', 't1.id_coa = t2.SysId', 'left');
		$data_dtl = $this->db->get()->result();

		$this->load->library('pdfgenerator');

		$data = [
			'data_hdr' => $data_hdr,
			'data_dtl' => $data_dtl
		];

		$name_file = $data_hdr->no_jurnal;
		$paper = 'A4';
		$orientation = "portrait";
		$html = $this->load->view('Print/report_jurnal_penerimaan', $data, true);

		$this->pdfgenerator->generate($html, $name_file, $paper, $orientation);
	}

	public function DT_list_coa()
	{
		$id_coa = $this->input->get('id_coa');
		$id_akun_induk = $this->input->get('id_akun_induk');

		// $query  = "SELECT * FROM tmst_chart_of_account";
		$query = "SELECT
					A.*,
					B.nama_akun AS nama_akun_induk
				FROM
					tmst_chart_of_account A
					LEFT OUTER JOIN tmst_akun_induk B ON A.id_akun_induk = B.SysId";

		$search = array('A.kode_akun', 'A.nama_akun', 'B.nama_akun');
		// $where  = array('SysId NOT IN ' => explode(',', $id_coa), 'Is_Active ' => 1, 'id_akun_induk ' => $id_akun_induk);
		$where  = array('A.SysId NOT IN ' => explode(',', $id_coa), 'A.Is_Active ' => 1);
		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}
}
