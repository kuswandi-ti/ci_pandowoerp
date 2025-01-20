<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FinishGood extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_product = 'tmst_hdr_product';
    public $tbl_fg = 'tbl_finish_good';
    public $ttrx_fg = 'ttrx_finish_good';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Finish Good " . $this->config->item('company_initial');
        $this->data['page_content'] = "FinishGood/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/finish_good/index.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function DataTable_FG()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.SysId',
            1 => 'c.Customer_Name',
            2 => 'a.Product_Code',
            3 => 'b.Nama',
            4 => 'a.Qty',
            5 => 'b.Uom',
            6 => 'a.Created_at',
            7 => 'a.Created_by',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT a.SysId, c.Customer_Name, a.Product_Code, b.Nama, a.Qty, b.Uom, a.Created_at, a.Created_by
        FROM impsys.tbl_finish_good a
        join tmst_hdr_product b on a.Product_Code = b.Kode
        join tmst_customer c on b.Customer_id = c.SysId";

        $totalData = $this->db->query($sql)->num_rows();
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (c.Customer_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Product_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Qty LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Uom LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        //----------------------------------------------------------------------------------
        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId'] = $row["SysId"];
            $nestedData['Customer_Name'] = $row["Customer_Name"];
            $nestedData['Product_Code'] = $row["Product_Code"];
            $nestedData['Nama'] = $row["Nama"];
            $nestedData['Qty'] = floatval($row["Qty"]);
            $nestedData['Uom'] = $row["Uom"];

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

    public function  popup_detail_ttrx()
    {
        $product_code = $this->input->get('product_code');

        $this->data['row_finish_good'] = $this->db->get_where($this->tbl_fg, ['Product_Code' => $product_code])->row();
        $this->data['row_product'] = $this->db->get_where($this->tbl_hdr_product, ['Kode' => $product_code])->row();

        $this->load->view('FinishGood/m_detail_ttrx', $this->data);
    }

    public function  popup_detail_stok()
    {
        $product_code = $this->input->get('product_code');

        $this->data['row_finish_good'] = $this->db->get_where($this->tbl_fg, ['Product_Code' => $product_code])->row();
        $this->data['row_product'] = $this->db->get_where($this->tbl_hdr_product, ['Kode' => $product_code])->row();

        $this->load->view('FinishGood/m_detail_stok', $this->data);
    }

    public function DataTable_Ttrx_FG()
    {
        $product_code = $this->input->post('product_code');

        $requestData = $_REQUEST;
        $columns = array(
            0 => 'sysid',
            1 => 'ProductCode',
            2 => 'old_stok',
            3 => 'aritmatics',
            4 => 'qty_trans',
            5 => 'new_stok',
            6 => 'remark',
            7 => 'do_at',
            8 => 'do_by',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT * from $this->ttrx_fg WHERE ProductCode = '$product_code' ";

        $totalData = $this->db->query($sql)->num_rows();
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (ProductCode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR old_stok LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR aritmatics LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR qty_trans LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR remark LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR do_at LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR do_by LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR new_stok LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        //----------------------------------------------------------------------------------
        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid']        = $row["sysid"];
            $nestedData['ProductCode']  = $row["ProductCode"];
            $nestedData['old_stok']     = floatval($row["old_stok"]);
            $nestedData['aritmatics']   = $row["aritmatics"];
            $nestedData['qty_trans']    = floatval($row["qty_trans"]);
            $nestedData['new_stok']     = floatval($row["new_stok"]);
            $nestedData['remark']       = $row["remark"];
            $nestedData['do_at']        = substr($row["do_at"], 0, -3);
            $nestedData['do_by']        = $row["do_by"];

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

    public function DataTable_Stok_Product()
    {
        $product_code = $this->input->post('product_code');

        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.SysId',
            1 => 'a.Customer_Name',
            2 => 'a.Product_Code',
            3 => 'a.Product_Name',
            4 => 'a.Checker_Rakit',
            5 => 'a.Leader_Rakit',
            6 => 'a.Date_Prd',
            7 => 'a.Barcode_Value',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT  a.SysId, a.Customer_Name, a.Product_Code, a.Product_Name, a.Checker_Rakit, a.Leader_Rakit, a.Date_Prd, a.Barcode_Value
        FROM impsys.thst_print_barcode_product a
        left join ttrx_dtl_loading b on a.Barcode_Value = b.Barcode_Value
        where a.IS_WASTING = 0
        and a.Product_Code = '$product_code'
        and isnull(b.No_Loading_Hdr)";

        $totalData = $this->db->query($sql)->num_rows();
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.Customer_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Product_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Product_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Checker_Rakit LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Leader_Rakit LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Date_Prd LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Barcode_Value LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        //----------------------------------------------------------------------------------
        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId']         = $row["SysId"];
            $nestedData['Customer_Name'] = $row["Customer_Name"];
            $nestedData['Product_Code']  = $row["Product_Code"];
            $nestedData['Product_Name']  = $row["Product_Name"];
            $nestedData['Checker_Rakit'] = $row["Checker_Rakit"];
            $nestedData['Leader_Rakit']  = $row["Leader_Rakit"];
            $nestedData['Date_Prd']      = $row["Date_Prd"];
            $nestedData['Barcode_Value'] = $row["Barcode_Value"];

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
