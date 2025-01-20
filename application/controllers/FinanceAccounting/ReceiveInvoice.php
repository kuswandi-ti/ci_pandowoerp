<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReceiveInvoice extends CI_Controller
{
	public $layout = 'layout';
	protected $ttrx_hdr	= 'ttrx_hdr_receive_invoice';
	protected $ttrx_dtl	= 'ttrx_dtl_receive_invoice';
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
		$data['page_title'] = "List Receive Invoice";
		$data['page_content'] = "FinanceAccounting/ReceiveInvoice/index";
		$data['customer'] = $this->db->query('SELECT id_customer, code_customer, name_customer FROM qview_penerimaan_invoice_receive GROUP BY id_customer, code_customer, name_customer');
		$data['invoice'] = $this->db->query('SELECT * FROM qview_penerimaan_invoice_receive_os');
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/ReceiveInvoice/index.js?v=' . time() . '"></script>';
		$this->load->view($this->layout, $data);
	}

	public function store()
	{
		$state         	= $this->input->post('state');
		$sysid_hdr      = $this->input->post('sysid_hdr');

		$doc_number   	= $this->input->post('doc_number');
		$doc_date     	= $this->input->post('doc_date');
		$id_customer	= $this->input->post('id_customer');
		$keterangan  	= $this->input->post('keterangan');
		$total  		= $this->help->float_to_value($this->input->post('total'));
		$is_lunas  		= $this->input->post('is_lunas');
		$note_lunas  	= $this->input->post('note_lunas');

		$id_invoice  	= $this->input->post('id_invoice');
		$item_amount  	= $this->help->float_to_value($this->input->post('item_amount'));
		$ppn_persen  	= $this->help->float_to_value($this->input->post('ppn_persen'));
		$ppn_amount  	= $this->help->float_to_value($this->input->post('ppn_amount'));
		$amount_receive	= $this->help->float_to_value($this->input->post('amount_receive'));
		$type_doc  		= $this->input->post('type_doc');

		if ($state == 'ADD') {
			$doc_no = $this->help->Generate_Identity_Number_FA('RI');
		}

		$this->db->trans_start();

		if ($state == 'ADD') {
			$this->db->insert($this->ttrx_hdr, [
				'doc_number'      	=> $doc_no,
				'doc_date'      	=> date('Y-m-d', strtotime($doc_date)),
				'id_customer'     	=> $id_customer,
				'keterangan'       	=> $keterangan,
				'total'       		=> $total,
				'Is_Lunas'			=> $is_lunas,
				'note_lunas'		=> $note_lunas,
			]);
			$id_hdr = $this->db->insert_id();

			for ($i = 0; $i < count($id_invoice); $i++) {
				if ($is_lunas == true) {
					$sisa_lunas	= $item_amount[$i] - $amount_receive[$i];
				} else {
					$sisa_lunas	= 0;
				}
				$this->db->insert($this->ttrx_dtl, [
					'id_hdr'    		=> $id_hdr,
					'id_invoice'  		=> $id_invoice[$i],
					'item_amount'  		=> $item_amount[$i],
					'amount_receive'	=> $amount_receive[$i],
					'sisa_lunas'		=> $sisa_lunas,
					'type_doc'			=> $type_doc[$i],
				]);

				$query = $this->db->query("SELECT no_invoice, os_receive FROM qview_penerimaan_invoice_receive_os WHERE id_invoice = '" . $id_invoice[$i] . "'");
				if ($query->num_rows() > 0) {
					foreach ($query->result() as $row) {
						$amount_os = $row->os_receive;
					}
				} else {
					$amount_os = 0;
				}

				if ($amount_os <= 0) {
					$invoice_status = 'FP';
				} else if ($amount_os > 0) {
					$invoice_status = 'HP';
				}
				$this->db->where('Invoice_ID', $id_invoice[$i]);
				$this->db->update('ttrx_hdr_sls_invoice', [
					'Invoice_Status' => $invoice_status,
				]);
			}
		} else {
			$this->db->where('SysId', $sysid_hdr);
			$this->db->update($this->ttrx_hdr, [
				'doc_date'      	=> date('Y-m-d', strtotime($doc_date)),
				'id_customer'     	=> $id_customer,
				'keterangan'       	=> $keterangan,
				'total'       		=> $total,
			]);

			$this->db->delete($this->ttrx_dtl, [
				'id_hdr' => $sysid_hdr
			]);

			if (!empty($id_invoice)) {
				for ($i = 0; $i < count($id_invoice); $i++) {
					if ($is_lunas == true) {
						$sisa_lunas	= $item_amount[$i] - $amount_receive[$i];
					} else {
						$sisa_lunas	= 0;
					}
					$this->db->insert($this->ttrx_dtl, [
						'id_hdr'    		=> $sysid_hdr,
						'id_invoice'  		=> $id_invoice[$i],
						'item_amount'  		=> $item_amount[$i],
						'amount_receive'	=> $amount_receive[$i],
						'sisa_lunas'		=> $sisa_lunas,
						'type_doc'			=> $type_doc[$i],
					]);

					$query = $this->db->query("SELECT invoice_amount, os_receive FROM qview_penerimaan_invoice_receive_os WHERE id_invoice = '" . $id_invoice[$i] . "'");
					if ($query->num_rows() > 0) {
						foreach ($query->result() as $row) {
							$amount_invoice = $row->invoice_amount;
							$amount_os = $row->os_receive;
						}
					} else {
						$amount_invoice = 0;
						$amount_os = 0;
					}

					if ($amount_os <= 0) {
						$invoice_status = 'FP';
					} else if ($amount_os > 0 && $amount_os < $amount_invoice) {
						$invoice_status = 'HP';
					} else if ($amount_os >= $amount_invoice) {
						$invoice_status = 'NP';
					}
					$this->db->where('Invoice_ID', $id_invoice[$i]);
					$this->db->update('ttrx_hdr_sls_invoice', [
						'Invoice_Status' => $invoice_status,
					]);
				}
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
		$this->db->join('tmst_account' . ' as t2', 't1.id_customer = t2.SysId', 'left');
		$data_hdr = $this->db->get()->row();

		$this->db->where('t1.id_hdr', $sysid_hdr);
		$this->db->select('t1.*, t2.Invoice_Number AS no_invoice');
		$this->db->from($this->ttrx_dtl . ' as t1');
		$this->db->join('ttrx_hdr_sls_invoice' . ' as t2', 't1.id_invoice = t2.Invoice_ID', 'left');
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
		$query  = "SELECT A.*, B.Account_Name AS customer " .
			"FROM ttrx_hdr_receive_invoice A " .
			"INNER JOIN tmst_account B ON A.id_customer = B.SysId";
		$search = array('A.doc_number', 'A.keterangan', 'B.Account_Name');
		$where  = null;
		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}

	public function DT_modallistofitem()
	{
		$id_customer = $this->input->get('id_customer');
		$id_invoice = $this->input->get('id_invoice');
		$flag_lunas = $this->input->get('flag_lunas');

		$query  = "SELECT * FROM qview_penerimaan_invoice_receive_os";

		$search = array('no_invoice');
		$where  = array('id_invoice NOT IN ' => explode(',', $id_invoice), 'id_customer ' => $id_customer);
		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}

	public function Toggle_Status()
	{
		$sysid = $this->input->post('sysid');
		$table = $this->input->post('table');

		$row = $this->db->get_where($table, ['SysId' => $sysid])->row();

		// Cek ke jurnal, apakah sudah dilakukan jurnal untuk dokumen ini
		$qry = $this->db->select('reff_id')->from('ttrx_hdr_jurnal')
			->where('reff_id', $sysid)
			->where('id_jurnal_tipe', 3)
			->get();
		if ($qry->num_rows() > 0) {
			$response = [
				"code" => 505,
				"msg" => "Dokumen RI tidak bisa Cancel, karena sudah dilakukan jurnal"
			];
		} else {
			$this->db->trans_start();

			if ($row->Is_Active == 1) {
				$this->db->where('SysId', $sysid);
				$this->db->update($table, [
					'Is_Active' => 0
				]);

				// $response = [
				//     "code" => 200,
				//     "msg" => "Data telah di non-aktifkan !"
				// ];

				// Update Status Invoice
				$qry = $this->db->query("SELECT 
											id_invoice	
									FROM  
										ttrx_dtl_receive_invoice
									WHERE
										id_hdr = '" . $sysid . "'");
				if (($qry->num_rows()) > 0) {
					foreach ($qry->result() as $row) {
						$id_invoice = $row->id_invoice;
						// Lihat dulu os amount
						$query = $this->db->query("SELECT invoice_amount, os_receive FROM qview_penerimaan_invoice_receive WHERE id_invoice = '" . $id_invoice . "'");
						if ($query->num_rows() > 0) {
							foreach ($query->result() as $row) {
								$amount_invoice = $row->invoice_amount;
								$amount_os = $row->os_receive;
							}
						} else {
							$amount_invoice = 0;
							$amount_os = 0;
						}

						if ($amount_os <= 0) {
							$invoice_status = 'FP';
						} else if ($amount_os > 0 && $amount_os < $amount_invoice) {
							$invoice_status = 'HP';
						} else if ($amount_os >= $amount_invoice) {
							$invoice_status = 'NP';
						}
						$this->db->where('Invoice_ID', $id_invoice);
						$this->db->update('ttrx_hdr_sls_invoice', [
							'Invoice_Status' => $invoice_status,
						]);
					}
				}

				$message = "Data telah di non-aktifkan !";
			} else {
				$this->db->where('SysId', $sysid);
				$this->db->update($table, [
					'Is_Active' => 1
				]);

				// $response = [
				//     "code" => 200,
				//     "msg" => "Data berhasil di aktifkan !"
				// ];

				$message = "Data berhasil di aktifkan !";
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
					"msg" => $message
				];
			}
		}

		return $this->help->Fn_resulting_response($response);
	}

	public function print($sysid)
	{
		$data['hdr'] = $this->db->get_where('ttrx_hdr_receive_invoice', ['SysId' => $sysid])->row();
		$data['dtl'] = $this->db->get_where('ttrx_dtl_receive_invoice', ['id_hdr' => $sysid])->result();
		$data['nama_customer'] = $this->db->query("SELECT
													B.Account_Name AS account_name
												FROM
													ttrx_hdr_receive_invoice A
													LEFT OUTER JOIN tmst_account B ON A.id_customer = B.SysId
												WHERE
													A.sysid = '" . $sysid . "'")->row()->account_name;

		$data['print_out']	= $this->PrintOut($sysid);
		$data['page_title'] = "Print Receive Invoice - " . $data['hdr']->doc_number;

		$this->load->view('Print/report_receive_invoice', $data);
	}

	function PrintOut($sysid)
	{
		$result = '';

		$result .= '<table class="table-ttd" style="margin-top: 3mm;">';
		$result .= '<thead>';
		$result .= '<td class="text-center font-weight-bold">No.</td>';
		$result .= '<td class="text-center font-weight-bold">No. Customer Invoice</td>';
		$result .= '<td class="text-center font-weight-bold">Tgl Customer Invoice</td>';
		$result .= '<td class="text-center font-weight-bold">No. SO</td>';
		$result .= '<td class="text-center font-weight-bold">Amount Customer Invoice</td>';
		$result .= '<td class="text-center font-weight-bold">Amount Receive</td>';
		$result .= '</thead>';
		$result .= '<tbody>';

		$i = 0;
		$grand_total = 0;
		$qry_print_2 = $this->db->query("SELECT
															A.*,
															B.*,
															C.*
														FROM
															ttrx_hdr_receive_invoice A
															LEFT OUTER JOIN ttrx_dtl_receive_invoice B ON A.SysId = B.id_hdr
															LEFT OUTER JOIN ttrx_hdr_sls_invoice C ON B.id_invoice = C.Invoice_ID
														WHERE
															A.SysId = '" . $sysid . "'");
		if (($qry_print_2->num_rows()) > 0) {
			foreach ($qry_print_2->result() as $row2) {
				$i++;
				$result .= '<tr>';
				$result .= '<td valign="top" class="text-center" width="7%">' . $i . '</td>';
				$result .= '<td valign="top" width="28%">' . $row2->Invoice_Number . '</td>';
				$result .= '<td valign="top" class="text-center" width="15%">' . date('d F Y', strtotime($row2->Invoice_Date)) . '</td>';
				$result .= '<td valign="top" class="text-right" width="10%">' . $row2->SO_Number . '</td>';
				$result .= '<td valign="top" class="text-right" width="25%">' . $this->help->FormatIdr($row2->Invoice_Amount) . '</td>';
				$result .= '<td valign="top" class="text-right" width="25%">' . $this->help->FormatIdr($row2->amount_receive) . '</td>';
				$grand_total = $grand_total + $row2->amount_receive;
				$result .= '</tr>';
			}
			$result .= '<tr>';
			$result .= '<td valign="top" colspan="5" class="font-weight-bold text-right">Grand Total</td>';
			$result .= '<td valign="top" class="font-weight-bold text-right">' . $this->help->FormatIdr($grand_total) . '</td>';
			$result .= '</tr>';
		}

		$result .= '</tbody>';
		$result .= '</table>';

		return $result;
	}
}
