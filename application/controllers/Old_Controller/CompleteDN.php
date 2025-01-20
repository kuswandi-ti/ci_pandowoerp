<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CompleteDN extends CI_Controller
{
    public $layout                  = 'layout';
    public $table_all_dn            = 'qview_sj_detail_all';
    public $tbl_dtl_dn              = 'ttrx_dtl_delivery_note';
    public $tbl_hdr_dn              = 'ttrx_hdr_delivery_note';
    public $qview_sj_detail_all     = 'qview_sj_detail_all';
    public $company_for_dn          = 'tmst_company_profile';
    public $qview_detail_loading    = 'qview_detail_hdr_loading';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "DataBase Delivery Note";
        $this->data['page_content'] = "DN/Complete_DN";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/DN/CompleteDN.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function DT_Complete_DN()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'SysId_Hdr',
            1 => 'SysId_Dtl',
            2 => 'DN_Number',
            3 => 'Customer_Code',
            4 => 'Customer_Name',
            5 => 'No_PO_Customer',
            6 => 'No_PO_Internal',
            7 => 'Product_Code',
            8 => 'Product_Name',
            9 => 'Qty',
            10 => 'Uom',
            11 => 'No_Loading',
            12 => 'Send_Date',
            13 => 'Complete_Address',
            14 => 'Att_To',
            15 => 'Vehicle_Police_Number',
            16 => 'Driver_Name',
            17 => 'Remark',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $customer = $this->input->get('customer');
        $sql_customer = "";
        if (!empty($customer)) {
            $sql_customer = " AND SysId_Customer = $customer ";
        }
        $from = $this->input->get('from');
        $to = $this->input->get('to');

        $sql = "SELECT * FROM $this->table_all_dn WHERE CHAR_LENGTH(No_Loading) > 1 
        $sql_customer 
        AND DATE_FORMAT(Send_Date, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(Send_Date, '%Y-%m-%d') <= '$to'
        ";

        $totalData = $this->db->query($sql)->num_rows();

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (DN_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR No_Po_Customer LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR No_PO_Internal LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Product_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Product_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Qty LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Send_Date LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Complete_Address LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Vehicle_Police_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Driver_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Remark LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Att_To LIKE '%" . $requestData['search']['value'] . "%') ";
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
            $nestedData['SysId_Hdr'] = $row["SysId_Hdr"];
            $nestedData['SysId_Dtl'] = $row["SysId_Dtl"];
            $nestedData['DN_Number'] = $row["DN_Number"];
            $nestedData['SysId_Customer'] = $row["SysId_Customer"];
            $nestedData['Customer_Code'] = $row["Customer_Code"];
            $nestedData['Customer_Name'] = $row["Customer_Name"];
            $nestedData['SysId_PO'] = $row["SysId_PO"];
            $nestedData['No_PO_Customer'] = $row["No_PO_Customer"];
            $nestedData['No_PO_Internal'] = $row["No_PO_Internal"];
            $nestedData['SysId_Product'] = $row["SysId_Product"];
            $nestedData['Product_Code'] = $row["Product_Code"];
            $nestedData['Product_Name'] = $row["Product_Name"];
            $nestedData['Qty'] = floatval($row["Qty"]);
            $nestedData['Uom'] = $row["Uom"];
            $nestedData['No_Loading'] = $row["No_Loading"];
            $nestedData['Send_Date'] = $row["Send_Date"];
            $nestedData['SysId_Address'] = $row["SysId_Address"];
            $nestedData['Complete_Address'] = $row["Complete_Address"];
            $nestedData['Att_To'] = $row["Att_To"];
            $nestedData['SysId_Vehicle'] = $row["SysId_Vehicle"];
            $nestedData['Vehicle_Police_Number'] = $row["Vehicle_Police_Number"];
            $nestedData['Init_Driver'] = $row["Init_Driver"];
            $nestedData['Driver_Name'] = $row["Driver_Name"];
            $nestedData['Remark'] = $row["Remark"];

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

    public function Print_DN($NumberDN)
    {
        $this->data['company'] = $this->db->get($this->company_for_dn)->row();
        $this->data['preview'] = $this->input->get('preview');
        $this->data['Hdr'] = $this->db->get_where($this->tbl_hdr_dn, ['DN_Number' => $NumberDN])->row();
        $this->data['Dtls'] = $this->db->order_by('SysId_Dtl', 'ASC')->get_where($this->qview_sj_detail_all, ['DN_Number' => $NumberDN]);


        if ($this->data['preview'] == 'false') {
            $this->db->where('DN_Number', $NumberDN);
            $this->db->update($this->tbl_hdr_dn, [
                'Count_Print_Dn' => floatval($this->data['Hdr']->Count_Print_Dn) + 1
            ]);
        }


        $this->load->view('DN/print_dn', $this->data);
    }

    public function M_DN_vs_Inv_Outstanding()
    {
        $this->data['customer_code'] = $this->input->get('customer_code');

        $this->load->view('DN/M_Dn_Outstanding_Inv', $this->data);
    }

    public function DT_List_Outstanding_Dn_vs_Invoice()
    {
        $requestData = $_REQUEST;
        $customer_code = $this->input->post('customer_code');
        $columns = array(
            0 => 'SysId_Hdr_DN',
            1 => 'SysId_Hdr_SO',
            2 => 'SysId_Hdr_Invoice',
            3 => 'DN_Number',
            4 => 'SO_Number',
            5 => 'No_PO_Customer',
            6 => 'Invoice_Number',
            7 => 'SysId_Customer',
            8 => 'Customer_Code',
            9 => 'Customer_Name',
            10 => 'Send_Date',
            11 => 'SysId_Address',
            12 => 'Complete_Address',
            13 => 'Att_To',
            14 => 'SysId_Vehicle',
            15 => 'Vehicle_Police_Number',
            16 => 'Init_Driver',
            17 => 'Driver_Name',
            18 => 'Remark'
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];
        $sql = "SELECT * FROM qview_invoice_vs_complete_dn_outstanding_invoice WHERE Customer_Code = '$customer_code' ";

        $totalData = $this->db->query($sql)->num_rows();

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (DN_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR SO_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR No_PO_Customer LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Send_Date LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Complete_Address LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Vehicle_Police_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Driver_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Remark LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Att_To LIKE '%" . $requestData['search']['value'] . "%') ";
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
            $nestedData['SysId_Hdr_DN']             = $row['SysId_Hdr_DN'];
            $nestedData['SysId_Hdr_SO']             = $row['SysId_Hdr_SO'];
            $nestedData['SysId_Hdr_Invoice']        = $row['SysId_Hdr_Invoice'];
            $nestedData['DN_Number']                = $row['DN_Number'];
            $nestedData['SO_Number']                = $row['SO_Number'];
            $nestedData['No_PO_Customer']           = $row['No_PO_Customer'];
            $nestedData['Invoice_Number']           = $row['Invoice_Number'];
            $nestedData['SysId_Customer']           = $row['SysId_Customer'];
            $nestedData['Customer_Code']            = $row['Customer_Code'];
            $nestedData['Customer_Name']            = $row['Customer_Name'];
            $nestedData['Send_Date']                = $row['Send_Date'];
            $nestedData['SysId_Address']            = $row['SysId_Address'];
            $nestedData['Complete_Address']         = $row['Complete_Address'];
            $nestedData['Att_To']                   = $row['Att_To'];
            $nestedData['SysId_Vehicle']            = $row['SysId_Vehicle'];
            $nestedData['Vehicle_Police_Number']    = $row['Vehicle_Police_Number'];
            $nestedData['Init_Driver']              = $row['Init_Driver'];
            $nestedData['Driver_Name']              = $row['Driver_Name'];
            $nestedData['Remark']                   = $row['Remark'];
            $nestedData['NPWP']                     = $row['NPWP'];

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
