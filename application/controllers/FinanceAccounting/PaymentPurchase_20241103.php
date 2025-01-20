<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PaymentPurchase extends CI_Controller
{
	public $layout = 'layout';
	protected $ttrx_hdr	= 'ttrx_hdr_payment_purchase';
	protected $ttrx_dtl	= 'ttrx_dtl_payment_purchase';
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
		$data['page_title'] = "List Payment Purchase";
		$data['page_content'] = "FinanceAccounting/PaymentPurchase/index";
		$data['supplier'] = $this->db->query('SELECT id_supplier, code_supplier, name_supplier
											FROM qview_tagihan_purchase_payment_all
											GROUP BY id_supplier, code_supplier, name_supplier');
		$data['doc'] = $this->db->query('SELECT sysid, no_doc, amount_os FROM qview_tagihan_purchase_payment_os WHERE amount_os > 0');
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/PaymentPurchase/index.js?v=' . time() . '"></script>';
		$this->load->view($this->layout, $data);
	}

	public function store()
	{
		$state         	= $this->input->post('state');
		$sysid_hdr      = $this->input->post('sysid_hdr');

		$doc_number   	= $this->input->post('doc_number');
		$doc_date     	= $this->input->post('doc_date');
		$id_supplier	= $this->input->post('id_supplier');
		$keterangan  	= $this->input->post('keterangan');
		$total  		= $this->help->float_to_value($this->input->post('total'));
		$is_lunas  		= $this->input->post('is_lunas');
		$note_lunas  	= $this->input->post('note_lunas');

		$id_doc  		= $this->input->post('id_doc');
		$no_doc  		= $this->input->post('no_doc');
		$type_doc  		= $this->input->post('type_doc');
		// $total_qty  	= $this->help->float_to_value($this->input->post('total_qty'));
		// $base_amount  	= $this->help->float_to_value($this->input->post('base_amount'));
		// $discount  		= $this->help->float_to_value($this->input->post('discount'));
		// $subtotal  		= $this->help->float_to_value($this->input->post('subtotal'));
		// $ppn1_persen  	= $this->help->float_to_value($this->input->post('ppn1_persen'));
		// $ppn1_amount  	= $this->help->float_to_value($this->input->post('ppn1_amount'));
		// $ppn2_persen  	= $this->help->float_to_value($this->input->post('ppn2_persen'));
		// $ppn2_amount  	= $this->help->float_to_value($this->input->post('ppn2_amount'));
		$amount_payment	= $this->help->float_to_value($this->input->post('amount_payment'));

		if ($state == 'ADD') {
			$doc_no = $this->help->Generate_Identity_Number_FA('PP');
		}

		$this->db->trans_start();
		
		if ($state == 'ADD') {
			$this->db->insert($this->ttrx_hdr, [
				'doc_number'      	=> $doc_no,
				'doc_date'      	=> date('Y-m-d', strtotime($doc_date)),
				'id_supplier'     	=> $id_supplier,
				'keterangan'       	=> $keterangan,
				'total'       		=> $total,
				'Is_Lunas'			=> $is_lunas,
				'note_lunas'		=> $note_lunas,
			]);
			$id_hdr = $this->db->insert_id();

			for ($i = 0; $i < count($id_doc); $i++) {
				$this->db->insert($this->ttrx_dtl, [
					'id_hdr'    		=> $id_hdr,
					'id_doc'  			=> $id_doc[$i],
					'no_doc'  			=> $no_doc[$i],
					'type_doc'  		=> $type_doc[$i],
					// 'total_qty'  		=> $total_qty[$i],
					// 'base_amount'  		=> $base_amount[$i],
					// 'discount'  		=> $discount[$i],
					// 'subtotal'  		=> $subtotal[$i],
					// 'ppn1_persen'  		=> $ppn1_persen[$i],
					// 'ppn1_amount'  		=> $ppn1_amount[$i],
					// 'ppn2_persen'  		=> $ppn2_persen[$i],
					// 'ppn2_amount'  		=> $ppn2_amount[$i],
					'amount_payment'	=> $amount_payment[$i],
				]);

				// Update Invoice_Status di tabel LPB, atau
				// Update Invoice_Status di tabel RR
				if ($type_doc[$i] == 'RR') {
					// Hitung total os payment purchase berdasarkan dokumen RR
					$amount_os = $this->db->query("SELECT amount_os FROM qview_tagihan_purchase_payment_os WHERE no_doc = '" . $no_doc[$i] . "' AND type_doc = 'RR'")->row()->amount_os;
					if ($amount_os <= 0) {
						$invoice_status = 'FP';
					} else if ($amount_os > 0) {
						$invoice_status = 'HP';
					}
					$this->db->where('SysId', $id_doc[$i]);
					$this->db->update('ttrx_hdr_pur_receive_item', [
						'Invoice_Status'	=> $invoice_status,
					]);
				} elseif ($type_doc[$i] == 'LPB') {
					// Hitung total os payment purchase berdasarkan dokumen LPB
					$amount_os = $this->db->query("SELECT amount_os FROM qview_tagihan_purchase_payment_os WHERE no_doc = '" . $no_doc[$i] . "' AND type_doc = 'LPB'")->row()->amount_os;
					if ($amount_os <= 0) {
						$invoice_status = 'FP';
					} else if ($amount_os > 0) {
						$invoice_status = 'HP';
					}
					$this->db->where('sysid', $id_doc[$i]);
					$this->db->update('ttrx_hdr_lpb_receive', [
						'Invoice_Status'	=> $invoice_status,
					]);
				}
			}
		} else {
			$this->db->where('SysId', $sysid_hdr);
			$this->db->update($this->ttrx_hdr, [
				'doc_date'      	=> date('Y-m-d', strtotime($doc_date)),
				'id_supplier'     	=> $id_supplier,
				'keterangan'       	=> $keterangan,
				'total'       		=> $total,
			]);

			$this->db->delete($this->ttrx_dtl, [
				'id_hdr' => $sysid_hdr
			]);

			if (!empty($id_doc)) {
				for ($i = 0; $i < count($id_doc); $i++) {
					$this->db->insert($this->ttrx_dtl, [
						'id_hdr'    		=> $sysid_hdr,
						'id_doc'  			=> $id_doc[$i],
						'no_doc'  			=> $no_doc[$i],
						'type_doc'  		=> $type_doc[$i],
						// 'total_qty'  		=> $total_qty[$i],
						// 'base_amount'  		=> $base_amount[$i],
						// 'discount'  		=> $discount[$i],
						// 'subtotal'  		=> $subtotal[$i],
						// 'ppn1_persen'  		=> $ppn1_persen[$i],
						// 'ppn1_amount'  		=> $ppn1_amount[$i],
						// 'ppn2_persen'  		=> $ppn2_persen[$i],
						// 'ppn2_amount'  		=> $ppn2_amount[$i],
						'amount_payment'	=> $amount_payment[$i],
					]);

					// Update Invoice_Status di tabel LPB, atau
					// Update Invoice_Status di tabel RR
					if ($type_doc[$i] == 'RR') {
						// Hitung total os payment purchase berdasarkan dokumen RR
						$amount_os = $this->db->query("SELECT amount_os FROM qview_tagihan_purchase_payment_os WHERE no_doc = '" . $no_doc[$i] . "' AND type_doc = 'RR'")->row()->amount_os;
						if ($amount_os <= 0) {
							$invoice_status = 'FP';
						} else if ($amount_os > 0) {
							$invoice_status = 'HP';
						}
						$this->db->where('SysId', $id_doc[$i]);
						$this->db->update('ttrx_hdr_pur_receive_item', [
							'Invoice_Status'	=> $invoice_status,
						]);
					} elseif ($type_doc[$i] == 'LPB') {
						// Hitung total os payment purchase berdasarkan dokumen LPB
						$amount_os = $this->db->query("SELECT amount_os FROM qview_tagihan_purchase_payment_os WHERE no_doc = '" . $no_doc[$i] . "' AND type_doc = 'LPB'")->row()->amount_os;
						if ($amount_os <= 0) {
							$invoice_status = 'FP';
						} else if ($amount_os > 0) {
							$invoice_status = 'HP';
						}
						$this->db->where('sysid', $id_doc[$i]);
						$this->db->update('ttrx_hdr_lpb_receive', [
							'Invoice_Status'	=> $invoice_status,
						]);
					}
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
		$this->db->join('tmst_account' . ' as t2', 't1.id_supplier = t2.SysId', 'left');
		$data_hdr = $this->db->get()->row();

		$this->db->where('id_hdr', $sysid_hdr);
		$this->db->select('*');
		$this->db->from($this->ttrx_dtl);
		$data_dtl = $this->db->get()->result();

		$response = [
			"code"      => 200,
			"msg"       => "Berhasil Mendapatkan Data !",
			"data_hdr"  => $data_hdr,
			"data_dtl"  => $data_dtl,
		];
		return $this->help->Fn_resulting_response($response);
	}

	public function print($sysid)
	{
		$data['hdr'] = $this->db->get_where('ttrx_hdr_payment_purchase', ['SysId' => $sysid])->row();
		$data['dtl'] = $this->db->get_where('ttrx_dtl_payment_purchase', ['id_hdr' => $sysid])->result();
		$data['nama_supplier'] = $this->db->query("SELECT
													B.Account_Name AS account_name
												FROM
													ttrx_hdr_payment_purchase A
													LEFT OUTER JOIN tmst_account B ON A.id_supplier = B.SysId
												WHERE
													A.sysid = '" . $sysid . "'")->row()->account_name;

		$qry_dtl = $this->db->query("SELECT 
											*	
									FROM  
										ttrx_dtl_payment_purchase
									WHERE
										id_hdr = '" . $sysid . "'");
		$no_doc = '';
		if (($qry_dtl->num_rows()) > 0) {
			foreach ($qry_dtl->result() as $row) {
				if ($no_doc == '') {
					$no_doc = $row->no_doc . ' <br>';
				} else {
					$no_doc = $no_doc . ' ' . $row->no_doc;
				}
			}
		}
		$data['no_doc']	= $no_doc;

		$data['print_out']	= $this->PrintOut($sysid);
		$data['page_title'] = "Print Payment Purchase - " . $data['hdr']->doc_number;

		$this->load->view('Print/report_payment_purchase', $data);
	}

	function PrintOut($sysid)
	{
		$result = '';
		$type_document = '';
		$qry_print_1 = $this->db->query("SELECT 
											no_doc, type_doc	
										FROM  
											qview_print_payment_purchase
										WHERE
											sysid = '" . $sysid . "'
										GROUP BY no_doc, type_doc 
										ORDER BY no_doc, type_doc");
		if (($qry_print_1->num_rows()) > 0) {
			foreach ($qry_print_1->result() as $row1) {
				$result .= '<table class="table-ttd" style="margin-top: 3mm;">';
				$result .= '<thead>';
				$result .= '<td class="text-center font-weight-bold">No.</td>';
				$result .= '<td class="text-center font-weight-bold">Deskripsi</td>';
				$result .= '<td class="text-center font-weight-bold">Unit</td>';
				$result .= '<td class="text-center font-weight-bold">Qty</td>';
				$result .= '<td class="text-center font-weight-bold">Harga</td>';
				$result .= '<td class="text-center font-weight-bold">Total</td>';
				$result .= '</thead>';
				$result .= '<tbody>';
				$result .= '<tr>';
				$result .= '<td valign="top" colspan="6" class="font-weight-bold">' . $row1->no_doc . '</td>';
				$result .= '</tr>';
				$i = 0;
				$grand_total = 0;
				$type_document = $row1->type_doc;
				if ($type_document == 'LPB') {
					$qry_print_2 = $this->db->query("SELECT 
														a.*,
														b.Item_Code as kode,
														CONCAT(size.Item_Length, ' x ', size.Item_Width, ' x ', size.Item_Height, 'CM') as inisial_kode,
														b.Item_Name as deskripsi,
														size.Item_Height as tebal,
														size.Item_Width as lebar,
														size.Item_Length as panjang,
														size.Qty,
														unit.Uom,
														cur.Currency_Symbol,
														CASE
															WHEN unit.Uom = 'm3' THEN ((size.Qty * size.Cubication) * a.harga_per_pcs)
															WHEN unit.Uom = 'pcs' THEN (size.Qty * a.harga_per_pcs)
															ELSE 0
														END AS sub_amount,
														b.created_by, b.created_at,
														size.Cubication as kubikasi,
														size.Qty * size.Cubication as sub_tot_kubikasi
													FROM 
														ttrx_dtl_lpb_receive a
														join tmst_item b on a.sysid_material = b.SysId
														JOIN tmst_unit_type unit ON b.Uom_Id = unit.Unit_Type_ID 
														join qview_dtl_size_item_lpb size on a.sysid = size.Id_Lot
														join ttrx_hdr_lpb_receive hdr on a.lpb_hdr = hdr.lpb
														JOIN tmst_currency cur on hdr.Currency = cur.Currency_ID
													WHERE 
														a.lpb_hdr = '$row1->no_doc'
													order BY 
														size.Item_Height,
														size.Item_Width,
														size.Item_Length");
				} else {
					$qry_print_2 = $this->db->query("SELECT 
														sysid,
														id_doc,
														no_doc,
														type_doc,
														item_code,
														item_name AS deskripsi,
														uom AS Uom,
														qty AS Qty,
														price AS harga_per_pcs,
														total AS sub_amount
													FROM 
														qview_print_payment_purchase
													WHERE 
														sysid = '$sysid'");
				}
				
				if (($qry_print_2->num_rows()) > 0) {
					foreach ($qry_print_2->result() as $row2) {
						$i++;
						$result .= '<tr>';
						$result .= '<td valign="top" class="text-center" width="7%">' . $i . '</td>';
						$result .= '<td valign="top" width="33%">' . $row2->deskripsi . '</td>';
						$result .= '<td valign="top" class="text-center" width="10%">' . $row2->Uom . '</td>';
						$result .= '<td valign="top" class="text-right" width="12%">' . $this->help->FormatIdr($row2->Qty) . '</td>';
						$result .= '<td valign="top" class="text-right" width="19%">' . $this->help->FormatIdr($row2->harga_per_pcs) . '</td>';
						$result .= '<td valign="top" class="text-right" width="19%">' . $this->help->FormatIdr($row2->sub_amount) . '</td>';
						$grand_total = $grand_total + $row2->sub_amount;
						$result .= '</tr>';
					}
					$result .= '<tr>';
					$result .= '<td valign="top" colspan="5" class="font-weight-bold text-right">Grand Total</td>';
					$result .= '<td valign="top" class="font-weight-bold text-right">' . $this->help->FormatIdr($grand_total) . '</td>';
					$result .= '</tr>';
				}
				$result .= '</tbody>';
				$result .= '</table>';
			}
		}

		return $result;
	}

	public function DT_listdata()
	{
		$query  = "SELECT A.*, B.Account_Name AS supplier " .
			"FROM ttrx_hdr_payment_purchase A " .
			"INNER JOIN tmst_account B ON A.id_supplier = B.SysId";
		$search = array('A.doc_number', 'A.keterangan', 'B.Account_Name');
		$where  = null;
		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}

	public function DT_modallistofitem()
	{
		$id_supplier = $this->input->get('id_supplier');
		$no_doc = $this->input->get('no_doc');

		$query  = "SELECT * FROM qview_tagihan_purchase_payment_os";

		$search = array('no_doc');
		$where  = array(
			'no_doc NOT IN ' => explode(',', $no_doc),
			'id_supplier ' => $id_supplier,
			'amount_os >' => 1
		);
		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}

	public function Toggle_Status()
	{
		$sysid = $this->input->post('sysid');
		$table = $this->input->post('table');

		$row = $this->db->get_where($table, ['SysId' => $sysid])->row();

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
											id_doc, no_doc, type_doc	
									FROM  
										ttrx_dtl_payment_purchase
									WHERE
										id_hdr = '" . $sysid . "'");
			if (($qry->num_rows()) > 0) {
				foreach ($qry->result() as $row) {
					$id_doc = $row->id_doc;
					$no_doc = $row->no_doc;
					$type_doc = $row->type_doc;
					if ($type_doc == 'RR') {
						$this->db->where('SysId', $id_doc);
						$this->db->update('ttrx_hdr_pur_receive_item', [
							'Invoice_Status' => 'NP',
						]);
					} else if ($type_doc == 'LPB') {
						$this->db->where('sysid', $id_doc);
						$this->db->update('ttrx_hdr_lpb_receive', [
							'Invoice_Status' => 'NP',
						]);
					}
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

		return $this->help->Fn_resulting_response($response);
	}
}
