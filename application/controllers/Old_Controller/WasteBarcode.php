<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WasteBarcode extends CI_Controller
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
        $this->data['page_title'] = "Waste Barcode Product";
        $this->data['page_content'] = "TagProduct/Waste_Barcode_Product";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TagProduct/WasteBarcode.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function preview_detail_data_barcode()
    {
        $barcode = $this->input->get('barcode');

        $response = $this->db->get_where($this->tbl_barcode, ['Barcode_Value' => $barcode])->row();

        if (empty($response)) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'barcode tidak terdaftar dalam system !'
            ]);
        }

        $is_wasting = '<button class="btn btn-sm btn-success">STOK</button>';
        if ($response->IS_WASTING == 1) {
            $is_wasting = '<button class="btn btn-sm btn-danger">WASTING</button>';
        }


        return $this->help->Fn_resulting_response([
            "code" => 200,
            "Customer_Name"     => $response->Customer_Name,
            "Customer_Code"     => $response->Customer_Code,
            "Barcode_Number"    => $response->Barcode_Number,
            "Barcode_Value"     => $response->Barcode_Value,
            "Date_Prd"          => $response->Date_Prd,
            "Product_Code"      => $response->Product_Code,
            "Product_Name"      => $response->Product_Name,
            "Leader_Rakit"      => $response->Leader_Rakit,
            "Checker_Rakit"     => $response->Checker_Rakit,
            "Created_by"        => $response->Created_by,
            "IS_WASTING"        => $is_wasting
        ]);
    }

    public function update_as_wasting()
    {
        $barcode = $this->input->post('barcode');
        $info = $this->input->post('info');
        $userinit = $this->session->userdata('impsys_initial');

        $data = $this->db->get_where($this->tbl_barcode, ['Barcode_Value' => $barcode])->row();

        if (empty($data)) {
            $response = ['code' => 505, 'msg' => 'barcode tidak terdaftar dalam system !'];
            return $this->help->Fn_resulting_response($response);
        }

        if ($data->IS_WASTING == '1') {
            $response = ['code' => 505, 'msg' => 'barcode sudah dinyatakan kadaluarsa/wasting !'];
            return $this->help->Fn_resulting_response($response);
        }

        $fg = $this->db->get_where('tbl_finish_good', ['Product_Code' => $data->Product_Code])->row();

        $this->db->trans_start();
        $this->db->where('Barcode_Value', $barcode);
        $this->db->update($this->tbl_barcode, [
            'IS_WASTING' => 1
        ]);
        $this->db->insert('thst_wasting_barcode', [
            'barcode' => $barcode,
            'do_by' => $userinit,
            'remark' => 'BARCODE',
            'info' => strtoupper($info),
        ]);

        $this->db->insert('ttrx_finish_good', [
            'ProductCode'   => $data->Product_Code,
            'old_stok'      => floatval($fg->Qty),
            'qty_trans'     => 1,
            'aritmatics'    => '-',
            'new_stok'      => floatval($fg->Qty) - 1,
            'remark'        => 'WASTING',
            'do_by'         => $this->session->userdata('impsys_initial')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = ['code' => 505, 'msg' => 'barcode gagal dinyatakan wasting!'];
        } else {
            $response = ['code' => 200];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function History_wasting_barcode()
    {
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
            9 => 'b.do_at',
            10 => 'b.do_by',
        );

        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT a.*,  b.do_by, b.do_at FROM thst_print_barcode_product a
        join thst_wasting_barcode b on a.Barcode_Value = b.barcode
        WHERE a.IS_WASTING = 1 ";

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
            $sql .= " OR b.do_at LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.do_by LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData['Created_at'] = substr($row["Created_at"], 0, -3);
            $nestedData['IS_WASTING'] = $row["IS_WASTING"];
            $nestedData['do_at'] = substr($row["do_at"], 0, -3);;
            $nestedData['do_by'] = $row["do_by"];

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
