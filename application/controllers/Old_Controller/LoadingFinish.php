<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LoadingFinish extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_loading = 'ttrx_hdr_loading';
    public $tbl_dtl_loading = 'ttrx_dtl_loading';
    public $tmp_dtl_loading = 'ttmp_dtl_loading';
    public $tbl_barcode = 'thst_print_barcode_product';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Pekerjaan Loading Selesai";
        $this->data['page_content'] = "Loading/list_loading_finish";
        $this->data['script_page'] = '<script src="' . base_url() . 'assets/Loading/list_loading_finish.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function DT_Loading()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.SysId',
            1 => 'a.No_loading',
            2 => 'b.Customer_Name',
            3 => 'c.Nama',
            4 => 'a.Qty_Loading',
            5 => 'a.Created_by',
            6 => 'a.STATUS',
            7 => 'a.STATUS',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT a.SysId, a.No_loading, a.Qty_Loading, a.STATUS, a.Created_by, b.Customer_Name, c.Nama
        FROM ttrx_hdr_loading a
        JOIN tmst_customer b on a.Customer_ID = b.SysId
        JOIN tmst_hdr_product c on a.Product_ID = c.sysid
        WHERE a.STATUS = 'SELESAI' ";

        $totalData = $this->db->query($sql)->num_rows();
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.No_loading LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Qty_Loading LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.Nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  b.Customer_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Created_by LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        //----------------------------------------------------------------------------------
        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId'] = $row["SysId"];
            $nestedData['No_loading'] = $row["No_loading"];
            $nestedData['Customer_Name'] = $row["Customer_Name"];
            $nestedData['Nama'] = $row["Nama"];
            $nestedData['Qty_Loading'] = $row["Qty_Loading"];
            $nestedData['Created_by'] = $row["Created_by"];
            $nestedData['STATUS'] = $row["STATUS"];

            $data[] = $nestedData;
        }
        //----------------------------------------------------------------------------------
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        //----------------------------------------------------------------------------------
        echo json_encode($json_data);
    }

    public function PreviewLoading($no_loading)
    {
        $this->data['page_title'] = "Loading Shipping";
        $this->data['page_content'] = "Loading/preview_loading";
        $this->data['script_page'] = '<script src="' . base_url() . 'assets/Loading/loading_finish.js"></script>';

        $this->data['loading'] = $this->db->query("SELECT b.Customer_Name, b.Customer_Code, c.Nama, c.Deskripsi, c.Kode, a.No_loading, a.Qty_Loading, a.STATUS, a.Created_at, a.Created_by
        FROM ttrx_hdr_loading a
        join tmst_customer b on a.Customer_ID = b.SysId
        join tmst_hdr_product c on a.Product_ID = c.sysid
        WHERE a.No_loading = '$no_loading' AND a.STATUS = 'SELESAI'
        LIMIT 1
        ")->row();

        $this->load->view($this->layout, $this->data);
    }

    public function DT_Barcode_loading()
    {
        $no_loading = $this->input->post('no_loading');

        $requestData = $_REQUEST;
        $columns = array(
            0 => 'SysId',
            1 => 'Barcode_Value',
            2 => 'do_at',
            3 => 'do_by',
        );
        // $order = $columns[$requestData['order']['0']['column']];
        // $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT * FROM $this->tbl_dtl_loading where No_Loading_Hdr = '$no_loading' ";
        $totalData = $this->db->query($sql)->num_rows();

        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (Barcode_Value LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR do_by LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR do_at LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        //----------------------------------------------------------------------------------

        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY Barcode_Value ASC";

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId'] = $row["SysId"];
            $nestedData['Barcode_Value'] = $row["Barcode_Value"];
            $nestedData['do_at'] = $row["do_at"];
            $nestedData['do_by'] = $row["do_by"];

            $data[] = $nestedData;
        }
        //----------------------------------------------------------------------------------
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        //----------------------------------------------------------------------------------
        echo json_encode($json_data);
    }

    public function M_PreviewLoading()
    {
        $no_loading = $this->input->get('No_Loading');

        $this->data['loading'] = $this->db->query("SELECT b.Customer_Name, b.Customer_Code, c.Nama, c.Deskripsi, c.Kode, a.No_loading, a.Qty_Loading, a.STATUS, a.Created_at, a.Created_by
        FROM ttrx_hdr_loading a
        join tmst_customer b on a.Customer_ID = b.SysId
        join tmst_hdr_product c on a.Product_ID = c.sysid
        WHERE a.No_loading = '$no_loading' AND a.STATUS = 'SELESAI'
        LIMIT 1
        ")->row();

        return $this->load->view('Loading/m_preview_loading', $this->data);
    }
}
