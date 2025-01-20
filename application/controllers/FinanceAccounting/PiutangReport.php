<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PiutangReport extends CI_Controller
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
		$data['page_title'] = "List of Report Piutang";
		$data['page_content'] = "FinanceAccounting/PiutangReport/index";
		$data['customer'] = $this->db->query('SELECT SysId, Account_Code, Account_Name
											  FROM tmst_account
											  WHERE Category_ID = "CS"');
		$data['script_page'] =  '<script src="' . base_url() . 'assets/financeaccounting-script/PiutangReport/index.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function DT_List_Piutang()
	{
		$requestData = $_REQUEST;

		$columns = array(
			0 => 'id_customer',
			1 => 'customer',
			2 => 'amount_invoice',
			3 => 'amount_receive',
			4 => 'os_receive',
		);

		$id_customer = $this->input->get('id_customer');
		if ($id_customer == "ALL") {
			$where_term = "WHERE id_customer <> 0";
		} else {
			$where_term = "WHERE id_customer = '$id_customer'";
		}

		$sql = "SELECT *
				FROM qview_penerimaan_invoice_receive_piutang_report " . $where_term;

		// JIKA ADA PARAM DARI SEARCH
		if (!empty($requestData['search']['value'])) {
			$sql .= " AND (code_customer LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR name_customer LIKE '%" . $requestData['search']['value'] . "%' ";
		}
		$sql .= " GROUP BY id_customer, code_customer, name_customer";
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
			$nestedData['amount_invoice'] = $row["amount_invoice"];
			$nestedData['amount_receive'] = $row["amount_receive"];
			$nestedData['os_receive'] = $row["os_receive"];

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
