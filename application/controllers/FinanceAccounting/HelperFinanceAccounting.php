<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HelperFinanceAccounting extends CI_Controller
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

	public function CancelJurnal()
	{
		$sysid = $this->input->post('sysid');
		$table = $this->input->post('table');

		$row = $this->db->get_where($table, ['SysId' => $sysid])->row();

		if ($row->isCancel == 1) {
			// $this->db->where('SysId', $sysid);
			// $this->db->update($table, [
			//     'isCancel' => 0
			// ]);

			// // Hapus di tabel pivot
			// // Cari no jurnal di tabel ttrx_hdr_jurnal berdasarkan sysid
			// $no_jurnal_source = $this->db->get_where($table, ['Sysid' => $sysid])->row()->no_jurnal;
			// // Cari no jurnal cancel di tabel ttrx_pivot_jurnal berdasarkan no_jurnal
			// $no_jurnal_cancel = $this->db->get_where('ttrx_pivot_jurnal', ['no_jurnal_source' => $no_jurnal_source])->row()->no_jurnal_cancel;
			// // Delete datanya
			// $this->db->where(array('no_jurnal_source' => $no_jurnal_source, 'no_jurnal_cancel' => $no_jurnal_cancel));
			// $this->db->delete('ttrx_pivot_jurnal');

			$response = [
				"code" => 500,
				"msg" => "Data yang telah di non-aktifkan tidak bisa menjadi aktif kembali !"
			];
		} else {
			$this->db->where('SysId', $sysid);
			$this->db->update($table, [
				'isCancel' => 1
			]);

			// Insert ke tabel pivot
			$no_jurnal_cancel = $this->help->Generate_Identity_Number_FA('VJUR');
			$no_jurnal_source = $this->db->get_where($table, ['Sysid' => $sysid])->row()->no_jurnal;

			$this->db->insert('ttrx_pivot_jurnal', [
				'no_jurnal_cancel'	=> $no_jurnal_cancel,
				'no_jurnal_source'	=> $no_jurnal_source,
			]);

			// Insert ke tabel transaksi jurnal
			$row_hdr = $this->db->get_where($table, ['SysId' => $sysid])->row();
			$row_dtl = $this->db->get_where('ttrx_dtl_jurnal', ['id_hdr' => $sysid])->row();

			$this->db->insert($table, [
				'no_jurnal'        	=> $no_jurnal_cancel,
				'tgl_jurnal'      	=> $row_hdr->tgl_jurnal,
				'reff_id'         	=> $row_hdr->reff_id,
				'reff_desc'         => $row_hdr->reff_desc,
				'id_jurnal_tipe' 	=> $row_hdr->id_jurnal_tipe,
				'debit' 			=> $row_hdr->debit * -1,
				'credit' 			=> $row_hdr->credit * -1,
				'keterangan'       	=> $row_hdr->keterangan,
				'isCancel' 			=> 1,
			]);
			$id_hdr = $this->db->insert_id();

			$query = "INSERT INTO ttrx_dtl_jurnal (id_hdr, id_coa, debit, credit)
					SELECT $id_hdr, id_coa, debit * -1, credit * -1
					FROM ttrx_dtl_jurnal WHERE id_hdr = $sysid";
			$this->db->query($query);

			$response = [
				"code" => 200,
				"msg" => "Data berhasil di non-aktifkan !"
			];
		}

		return $this->help->Fn_resulting_response($response);
	}
}
