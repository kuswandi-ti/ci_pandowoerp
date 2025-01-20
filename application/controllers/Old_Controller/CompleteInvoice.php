<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CompleteInvoice extends CI_Controller
{
    public $layout = 'layout';
    public $hdr_invoice     = 'ttrx_hdr_invoice';
    public $dtl_invoice     = 'ttrx_dtl_invoice';
    public $hst_approve_invoice = 'thst_approve_invoice';
    public $hst_reject_hdr_invoice = 'thst_rejected_hdr_invoice';
    public $hst_reject_dtl_invoice = 'thst_rejected_dtl_invoice';

    public $company_for_dn          = 'tmst_company_profile';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Complete Invoice";
        $this->data['page_content'] = "Invoice/complete_invoice";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/Invoice/complete_invoice.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function DT_Database_Invoice()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'SysId',
            1 => 'Approve',
            2 => 'Invoice_Number',
            3 => 'Customer_Code',
            4 => 'Customer_Name',
            5 => 'DN_Number',
            6 => 'No_PO_Customer',
            7 => 'SO_Number',
            8 => 'Invoice_Date',
            9 => 'Due_Date',
            10 => 'Item_Amount',
            11 => 'PPN',
            12 => 'PPN_Amount',
            13 => 'Invoice_Amount',
            14 => 'NPWP',
            15 => 'Customer_Address',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $customer = $this->input->get('customer');
        $sql_customer = "";
        if (!empty($customer)) {
            $sql_customer = " AND Customer_ID = $customer ";
        }
        $from = $this->input->get('from');
        $to = $this->input->get('to');

        $sql = "SELECT * FROM $this->hdr_invoice WHERE Approve = 'APPROVE'
                $sql_customer 
                AND DATE_FORMAT(Invoice_Date, '%Y-%m-%d') >= '$from'
                AND DATE_FORMAT(Invoice_Date, '%Y-%m-%d') <= '$to'";

        $totalData = $this->db->query($sql)->num_rows();

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (Invoice_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR DN_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR No_PO_Customer LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR SO_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Invoice_Date LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Due_Date LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Item_Amount LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR PPN_Amount LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Invoice_Amount LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR NPWP LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Address LIKE '%" . $requestData['search']['value'] . "%') ";
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
            $nestedData['SysId'] = $row["SysId"];
            $nestedData['Approve'] = $row["Approve"];
            $nestedData['Invoice_Number'] = $row["Invoice_Number"];
            $nestedData['Customer_Code'] = $row["Customer_Code"];
            $nestedData['Customer_Name'] = $row["Customer_Name"];
            $nestedData['DN_Number'] = $row["DN_Number"];
            $nestedData['No_PO_Customer'] = $row["No_PO_Customer"];
            $nestedData['SO_Number'] = $row["SO_Number"];
            $nestedData['Invoice_Date'] = $row["Invoice_Date"];
            $nestedData['Due_Date'] = $row["Due_Date"];
            $nestedData['Item_Amount'] = $row["Item_Amount"];
            $nestedData['PPN'] = $row["PPN"];
            $nestedData['PPN_Amount'] = $row["PPN_Amount"];
            $nestedData['Invoice_Amount'] = $row["Invoice_Amount"];
            $nestedData['NPWP'] = $row["NPWP"];
            $nestedData['Customer_Address'] = $row["Customer_Address"];

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

    public function Print_Invoice($Invoice_Number)
    {
        $this->data['company'] = $this->db->get($this->company_for_dn)->row();
        $this->data['preview'] = $this->input->get('preview');

        $this->data['Hdr'] = $this->db->get_where($this->hdr_invoice, ['Invoice_Number' => $Invoice_Number])->row();
        $this->data['Dtls'] = $this->db->order_by('SysId', 'ASC')->get_where($this->dtl_invoice, ['Invoice_Number' => $Invoice_Number]);


        if ($this->data['preview'] == 'false') {
            $this->db->where('Invoice_Number', $Invoice_Number);
            $this->db->update($this->hdr_invoice, [
                'Count_Print' => floatval($this->data['Hdr']->Count_Print) + 1
            ]);
        }

        $this->load->view('Invoice/print_invoice', $this->data);
    }
}
