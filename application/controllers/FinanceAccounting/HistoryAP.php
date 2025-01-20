<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HistoryAP extends CI_Controller
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
		$data['page_title'] = "List of History Transaksi AP";
		$data['page_content'] = "FinanceAccounting/HistoryAP/index";
		$data['supplier'] = $this->db->query('SELECT SysId, Account_Code, Account_Name
											FROM tmst_account
											WHERE Category_ID = "VP"');
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/HistoryAP/index.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function DT_List_History_AP()
	{
		$requestData = $_REQUEST;

		$columns = array(
			0 => 'id_supplier',
			1 => 'supplier',
			2 => 'no_doc_supplier',
			3 => 'type_doc',
			4 => 'amount_receive',
			5 => 'no_doc_pp',
			6 => 'amount_payment',
		);

		$id_supplier = $this->input->get('id_supplier');
		if ($id_supplier == "ALL") {
			$where_term = "WHERE A.id_supplier <> 0";
		} else {
			$where_term = "WHERE A.id_supplier = '$id_supplier'";
		}

		$sql = "SELECT
					A.id_supplier,
					A.code_supplier,
					A.name_supplier,
					CONCAT_WS(' - ', A.code_supplier, A.name_supplier) AS supplier,
					A.no_doc AS no_doc_supplier,	
					A.type_doc,
					A.amount_receive,
					C.doc_number AS no_doc_pp,
					SUM(COALESCE(B.amount_payment, 0)) AS amount_payment
				FROM
					qview_tagihan_purchase_payment_all A
					LEFT OUTER JOIN ttrx_dtl_payment_purchase B ON A.no_doc = B.no_doc
					LEFT OUTER JOIN ttrx_hdr_payment_purchase C ON B.id_hdr = C.SysId " . $where_term;

		// JIKA ADA PARAM DARI SEARCH
		if (!empty($requestData['search']['value'])) {
			$sql .= " AND (A.code_supplier LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR A.name_supplier LIKE '%" . $requestData['search']['value'] . "%' ";
		}
		$sql .= " GROUP BY A.no_doc,
					A.id_supplier,
					A.code_supplier,
					A.name_supplier,
					A.type_doc,
					A.amount_receive,
					C.doc_number";
		$totalData = $this->db->query($sql)->num_rows();
		$totalFiltered = $this->db->query($sql)->num_rows();
		//----------------------------------------------------------------------------------
		$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'];
		$query = $this->db->query($sql);
		$data = array();
		$no = 1;
		foreach ($query->result_array() as $row) {
			$nestedData = array();
			$nestedData['id_supplier'] = $row["id_supplier"];
			$nestedData['supplier'] = $row["supplier"];
			$nestedData['no_doc_supplier'] = $row["no_doc_supplier"];
			$nestedData['type_doc'] = $row["type_doc"];
			$nestedData['amount_receive'] = $row["amount_receive"];
			$nestedData['no_doc_pp'] = $row["no_doc_pp"];
			$nestedData['amount_payment'] = $row["amount_payment"];

			$data[] = $nestedData;
		}
		//----------------------------------------------------------------------------------
		$json_data = array(
			"draw"            => intval($requestData['draw']),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
		);
		//----------------------------------------------------------------------------------
		echo json_encode($json_data);
	}
}
