<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HistoryPrintBarcode extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_customer = 'tmst_customer';
    public $tbl_hdr_product = 'tmst_hdr_product';
    public $tbl_dtl_product = 'tmst_dtl_product';
    public $tbl_barcode = 'thst_print_barcode_product';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "History Print Barcode Product";
        $this->data['page_content'] = "TagProduct/History_Barcode_Product";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TagProduct/Hst_Print_Barcode.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function DataTable_hst_bcd_product()
    {
        $from = $this->input->post('from');
        $to = $this->input->post('to');

        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.SysId',
            1 => 'a.Barcode_Number',
            2 => 'a.Customer_Name',
            3 => 'a.Product_Code',
            4 => 'a.Date_Prd',
            5 => 'a.Checker_Rakit',
            6 => 'a.Leader_Rakit',
            7 => 'a.Created_at',
            8 => 'a.IS_WASTING',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT * FROM thst_print_barcode_product a
        WHERE a.Date_Prd >= '$from'
        AND a.Date_Prd <= '$to'";

        $totalData = $this->db->query($sql)->num_rows();

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.Barcode_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Customer_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Barcode_Value LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Customer_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Product_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Product_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Date_Prd LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Checker_Rakit LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Leader_Rakit LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Created_at LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.IS_WASTING LIKE '%" . $requestData['search']['value'] . "%')";
        }
        $totalFiltered = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId'] = $row["SysId"];
            $nestedData['Barcode_Number'] = $row["Barcode_Number"];
            $nestedData['Customer_Name'] = $row["Customer_Name"];
            $nestedData['Product_Code'] = $row["Product_Code"];
            $nestedData['Date_Prd'] = $row["Date_Prd"];
            $nestedData['Checker_Rakit'] = $row["Checker_Rakit"];
            $nestedData['Leader_Rakit'] = $row["Leader_Rakit"];
            $nestedData['Created_at'] = substr($row["Created_at"], 0, -3);;
            $nestedData['IS_WASTING'] = $row["IS_WASTING"];

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

    public function DataTable_hst_bcd_group()
    {
        $from = $this->input->post('from');
        $to = $this->input->post('to');

        $requestData = $_REQUEST;
        $columns = array(
            0 => 'MIN(a.Barcode_Number)',
            1 => 'MAX(a.Barcode_Number)',
            2 => 'COUNT(a.sysid)',
            3 => 'a.Customer_Name',
            4 => 'a.Product_Code',
            5 => 'a.Date_Prd',
            6 => 'a.Checker_Rakit',
            7 => 'a.Leader_Rakit',
            8 => 'a.FlagGrouping',
            9 => 'a.Created_at'
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT MIN(a.Barcode_Number) as min_barcode, MAX(a.Barcode_Number) as max_barcode, COUNT(a.sysid) as jumlah,
        a.Customer_Name, a.Product_Code, a.Date_Prd, a.Checker_Rakit, a.Leader_Rakit, a.FlagGrouping, a.Created_at
        FROM thst_print_barcode_product a
        WHERE a.Date_Prd >= '$from'
        AND a.Date_Prd <= '$to'";

        $totalData = $this->db->query($sql)->num_rows();

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.Product_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Product_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Date_Prd LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Checker_Rakit LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Leader_Rakit LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Created_at LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        $sql .= " GROUP BY a.FlagGrouping";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['min_barcode'] = $row["min_barcode"];
            $nestedData['max_barcode'] = $row["max_barcode"];
            $nestedData['jumlah'] = $row["jumlah"];
            $nestedData['Customer_Name'] = $row["Customer_Name"];
            $nestedData['Product_Code'] = $row["Product_Code"];
            $nestedData['Date_Prd'] = $row["Date_Prd"];
            $nestedData['Checker_Rakit'] = $row["Checker_Rakit"];
            $nestedData['Leader_Rakit'] = $row["Leader_Rakit"];
            $nestedData['Created_at'] = substr($row["Created_at"], 0, -9);
            $nestedData['FlagGrouping'] = $row["FlagGrouping"];

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
