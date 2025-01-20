<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JurnalPenyesuaian extends CI_Controller
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
		$this->data['page_title'] = "List Jurnal Penyesuaian (Adjustment)";
		$this->data['page_content'] = "FinanceAccounting/JurnalPenyesuaian/index";
		$this->data['akun_induk'] = $this->db->query('SELECT
													*
												FROM
													tmst_akun_induk');
		$this->data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/JurnalPenyesuaian/index.js?v=' . time() . '"></script>';
		$this->load->view($this->layout, $this->data);
	}

	public function store()
	{
		$state         	= $this->input->post('state');
		$sysid_hdr      = $this->input->post('sysid_hdr');

		$no_jurnal      = $this->input->post('no_jurnal');
		$tgl_jurnal     = $this->input->post('tgl_jurnal');
		$reff_desc    	= $this->input->post('reff_desc');
		$keterangan  	= $this->input->post('keterangan');
		$total_debit  	= $this->help->float_to_value($this->input->post('total_debit'));
		$total_credit	= $this->help->float_to_value($this->input->post('total_kredit'));

		$kode_akun      = $this->input->post('kode_akun');
		$id_coa      	= $this->input->post('id_coa');
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
				'reff_desc'         => $reff_desc,
				'id_jurnal_tipe' 	=> 2,
				'keterangan'       	=> $keterangan,
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
				'reff_desc'         => $reff_desc,
				'id_jurnal_tipe' 	=> 2,
				'keterangan'       	=> $keterangan,
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
		$this->db->select('t1.*');
		$this->db->from($this->ttrx_hdr . ' as t1');
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
		$query  = "SELECT A.SysId, A.no_jurnal, A.tgl_jurnal, A.reff_id, A.reff_desc, A.keterangan, A.debit, A.credit, A.isCancel, B.no_jurnal_cancel 
				FROM $this->ttrx_hdr A 
				LEFT OUTER JOIN ttrx_pivot_jurnal B ON A.no_jurnal = B.no_jurnal_source";

		$search = array('no_jurnal', 'reff_desc', 'keterangan');
		$where  = array('id_jurnal_tipe ' => '2');
		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}

	public function DT_modallistofitem()
	{
		$id_coa = $this->input->get('id_coa');
		$id_akun_induk = $this->input->get('id_akun_induk');

		$query  = "SELECT * FROM tmst_chart_of_account";

		$search = array('kode_akun', 'nama_akun');
		$where  = array('SysId NOT IN ' => explode(',', $id_coa), 'Is_Active ' => 1, 'id_akun_induk ' => $id_akun_induk);
		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}

	public function print($sysid)
	{
		$this->db->where('t1.SysId', $sysid);
		$this->db->select('t1.*');
		$this->db->from('ttrx_hdr_jurnal' . ' as t1');
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
		$html = $this->load->view('Print/report_jurnal_penyesuaian', $data, true);

		$this->pdfgenerator->generate($html, $name_file, $paper, $orientation);
	}
}
