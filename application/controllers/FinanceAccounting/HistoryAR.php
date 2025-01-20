<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HistoryAR extends CI_Controller
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
		$data['page_title'] = "List of History Transaksi AR";
		$data['page_content'] = "FinanceAccounting/HistoryAR/index";
		$data['customer'] = $this->db->query('SELECT SysId, Account_Code, Account_Name
											  FROM tmst_account
											  WHERE Category_ID = "CS"');
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/HistoryAR/index.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function DT_List_History_AR()
	{
		$requestData = $_REQUEST;

		$columns = array(
			0 => 'id_customer',
			1 => 'customer',
			2 => 'no_doc_customer',
			4 => 'invoice_amount',
			5 => 'doc_no_ri',
			6 => 'amount_receive',
		);

		$id_customer = $this->input->get('id_customer');
		if ($id_customer == "ALL") {
			$where_term = "WHERE A.Account_ID <> 0";
		} else {
			$where_term = "WHERE A.Account_ID = '$id_customer'";
		}

		$sql = "SELECT 
					A.Account_ID AS id_customer,
					D.Account_Code AS code_customer,
					D.Account_Name AS name_customer,
					CONCAT_WS(' - ', D.Account_Code, D.Account_Name) AS customer,
					A.Invoice_Number AS no_doc_customer,	
					COALESCE(A.Invoice_Amount,0) AS invoice_amount,
					c.doc_number AS doc_no_ri,
					sum(coalesce(B.amount_receive,0)) AS amount_receive
				FROM 
					ttrx_hdr_sls_invoice A 
					left join ttrx_dtl_receive_invoice B on A.Invoice_ID = B.id_invoice
					left join ttrx_hdr_receive_invoice C on B.id_hdr = C.SysId
					left join tmst_account D on A.Account_ID = D.SysId " . $where_term;

		// JIKA ADA PARAM DARI SEARCH
		if (!empty($requestData['search']['value'])) {
			$sql .= " AND (D.Account_Code LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR D.Account_Name LIKE '%" . $requestData['search']['value'] . "%' ";
		}
		$sql .= " GROUP BY A.Account_ID,
				D.Account_Code,
				D.Account_Name,
				A.Invoice_Number,
				A.Invoice_Amount,
				c.doc_number";
		$totalData = $this->db->query($sql)->num_rows();
		$totalFiltered = $this->db->query($sql)->num_rows();
		//----------------------------------------------------------------------------------
		$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'];
		$query = $this->db->query($sql);
		$data = array();
		$no = 1;
		foreach ($query->result_array() as $row) {
			$nestedData = array();
			$nestedData['id_customer'] = $row["id_customer"];
			$nestedData['customer'] = $row["customer"];
			$nestedData['no_doc_customer'] = $row["no_doc_customer"];
			$nestedData['invoice_amount'] = $row["invoice_amount"];
			$nestedData['doc_no_ri'] = $row["doc_no_ri"];
			$nestedData['amount_receive'] = $row["amount_receive"];

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
