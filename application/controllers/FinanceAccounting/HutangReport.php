<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HutangReport extends CI_Controller
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
		$data['page_title'] = "List of Report Hutang";
		$data['page_content'] = "FinanceAccounting/HutangReport/index";
		$data['supplier'] = $this->db->query('SELECT SysId, Account_Code, Account_Name
											FROM tmst_account
											WHERE Category_ID = "VP"');
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/HutangReport/index.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function DT_List_Hutang()
	{
		$requestData = $_REQUEST;

		$columns = array(
			0 => 'id_supplier',
			1 => 'supplier',
			2 => 'amount_receive',
			3 => 'amount_payment',
			4 => 'amount_os',
		);

		$id_supplier = $this->input->get('id_supplier');
		if ($id_supplier == "ALL") {
			$where_term = "WHERE id_supplier <> 0 AND amount_os > 0";
		} else {
			$where_term = "WHERE id_supplier = '$id_supplier' AND amount_os > 0";
		}

		$sql = "SELECT * 
				FROM qview_tagihan_purchase_payment_hutang_report " . $where_term;

		// JIKA ADA PARAM DARI SEARCH
		if (!empty($requestData['search']['value'])) {
			$sql .= " AND (code_supplier LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR name_supplier LIKE '%" . $requestData['search']['value'] . "%' ";
		}
		$sql .= " GROUP BY id_supplier, code_supplier, name_supplier";
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
			$nestedData['amount_receive'] = $row["amount_receive"];
			$nestedData['amount_payment'] = $row["amount_payment"];
			$nestedData['amount_os'] = $row["amount_os"];

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
