<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SoOutstanding extends CI_Controller
{
    public $layout = 'layout';

    public $tbl_master_customer = 'tmst_customer';
    public $tbl_hdr_po   = 'ttrx_hdr_po_receive';
    public $tbl_dtl_po   = 'ttrx_dtl_po_receive';
    public $tbl_hdr_dn   = 'ttrx_hdr_delivery_note';
    public $tbl_dtl_dn   = 'ttrx_dtl_delivery_note';
    public $tbl_outstanding_so = 'qview_so_vs_sj_outstanding_so';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "OutStanding Sales Order";
        $this->data['page_content'] = "SO/OutstandingSO";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/SO/OutStandingSO.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function DT_SO_OutStanding()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0  => 'SO_SysId_Hdr',
            1  => 'SO_Number',
            2  => 'No_Po_Customer',
            3  => 'Status_SO',
            4  => 'Customer_Code',
            5  => 'Customer_Name',
            6  => 'Flag',
            7  => 'Product_Code',
            8  => 'Product_Name',
            9  => 'Product_Price',
            10 => 'Qty_SO',
            11 => 'Amount_SO_PerItem',
            12 => 'Qty_SJ',
            13 => 'Amount_SJ',
            14 => 'Uom',
            15 => 'Qty_SO_OutStanding',
            16 => 'Outstanding_Amount_SO',
            17 => 'Tgl_Terbit',
            18 => 'Term_Of_Payment',
            19 => 'Remark_TOP',
            20 => 'Term_Of_Delivery',
            21 => 'Customer_Address',
            22 => 'Koresponden',
            23 => 'Note',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT * FROM  $this->tbl_outstanding_so WHERE Qty_SO_OutStanding > 0";

        $totalData = $this->db->query($sql)->num_rows();

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (SO_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR No_Po_Customer LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Product_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Product_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Product_Price LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Qty_SO LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Qty_SJ LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Uom LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Tgl_Terbit LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Term_Of_Payment LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Remark_TOP LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Term_Of_Delivery LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Address LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Koresponden LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Note LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        // $sql .= " GROUP BY ";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData["SO_SysId_Hdr"] = $row['SO_SysId_Hdr'];
            $nestedData["SO_Number"] = $row['SO_Number'];
            $nestedData["No_Po_Customer"] = $row['No_Po_Customer'];
            $nestedData["Status_SO"] = $row['Status_SO'];
            $nestedData["Customer_Code"] = $row['Customer_Code'];
            $nestedData["Customer_Name"] = $row['Customer_Name'];
            $nestedData["Flag"] = $row['Flag'];
            $nestedData["Product_Code"] = $row['Product_Code'];
            $nestedData["Product_Name"] = $row['Product_Name'];
            $nestedData["Product_Price"] = $row['Product_Price'];
            $nestedData["Qty_SO"] = floatval($row['Qty_SO']);
            $nestedData["Amount_SO_PerItem"] = floatval($row['Amount_SO_PerItem']);
            $nestedData["Qty_SJ"] = floatval($row['Qty_SJ']);
            $nestedData["Amount_SJ"] = floatval($row['Amount_SJ']);
            $nestedData["Uom"] = $row['Uom'];
            $nestedData["Qty_SO_OutStanding"] = floatval($row['Qty_SO_OutStanding']);
            $nestedData["Outstanding_Amount_SO"] = floatval($row['Outstanding_Amount_SO']);
            $nestedData["Tgl_Terbit"] = $row['Tgl_Terbit'];
            $nestedData["Term_Of_Payment"] = $row['Term_Of_Payment'];
            $nestedData["Remark_TOP"] = $row['Remark_TOP'];
            $nestedData["Term_Of_Delivery"] = $row['Term_Of_Delivery'];
            $nestedData["Customer_Address"] = $row['Customer_Address'];
            $nestedData["Koresponden"] = $row['Koresponden'];
            $nestedData["Note"] = $row['Note'];


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
