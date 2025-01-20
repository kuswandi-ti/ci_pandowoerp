<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApprovalInvoice extends CI_Controller
{
    public $layout = 'layout';
    public $hdr_invoice     = 'ttrx_hdr_invoice';
    public $dtl_invoice     = 'ttrx_dtl_invoice';
    public $hst_approve_invoice = 'thst_approve_invoice';
    public $hst_reject_hdr_invoice = 'thst_rejected_hdr_invoice';
    public $hst_reject_dtl_invoice = 'thst_rejected_dtl_invoice';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Invoice Approval";
        $this->data['page_content'] = "Invoice/Approval_Invoice";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/Invoice/approval_invoice.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function DT_Approval_Invoice()
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

        $sql = "SELECT * FROM $this->hdr_invoice WHERE Approve = 'PREPARATION' ";

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

    public function M_Dtl_Invoice()
    {
        $this->data['Hdr'] = $this->db->get_where($this->hdr_invoice, ['SysId' => $this->input->get('SysId_Invoice')])->row();

        return $this->load->view('Invoice/m_dtl_invoice', $this->data);
    }

    public function DT_preview_detail_item_invoice()
    {
        $requestData = $_REQUEST;
        $Invoice_Number = $this->input->get('Invoice_Number');
        $columns = array(
            0 => 'SysId',
            1 => 'Invoice_Number',
            2 => 'Product_ID',
            3 => 'Product_Code',
            4 => 'Product_Name',
            5 => 'Qty',
            6 => 'Uom',
            7 => 'Product_Price',
            8 => 'Amount_Item',
            9 => 'Created_at',
            10 => 'Created_by'
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];
        $sql = "SELECT * FROM $this->dtl_invoice WHERE Invoice_Number = '$Invoice_Number' ";

        $totalData = $this->db->query($sql)->num_rows();

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            // $sql .= " AND (DN_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR SO_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR No_PO_Customer LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR Customer_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR Customer_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR Send_Date LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR Complete_Address LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR Vehicle_Police_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR Driver_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR Remark LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR Att_To LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        // $sql .= " GROUP BY ";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY $order $dir ";

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId'] = $row['SysId'];
            $nestedData['Invoice_Number'] = $row['Invoice_Number'];
            $nestedData['Product_ID'] = $row['Product_ID'];
            $nestedData['Product_Code'] = $row['Product_Code'];
            $nestedData['Product_Name'] = $row['Product_Name'];
            $nestedData['Qty'] = number_format(floatval($row['Qty']), 2);
            $nestedData['Uom'] = $row['Uom'];
            $nestedData['Product_Price'] = number_format(floatval($row['Product_Price']), 2);
            $nestedData['Amount_Item'] = number_format(floatval($row['Amount_Item']), 2);
            $nestedData['Created_at'] = $row['Created_at'];
            $nestedData['Created_by'] = $row['Created_by'];
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

    public function Approve_Invoice()
    {
        $Invoice_Number = $this->input->post('Invoice_Number');

        $this->db->trans_start();
        // -------------------------------

        $this->db->where('Invoice_Number', $Invoice_Number);
        $this->db->update($this->hdr_invoice, [
            'Approve' => 'APPROVE'
        ]);
        $this->db->insert($this->hst_approve_invoice, [
            'Invoice_Number' => $Invoice_Number,
            'Status' => "APPROVE",
            'created_by' => $this->session->userdata('impsys_initial'),
        ]);

        // -------------------------------
        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => $error_msg
            ]);
        } else {
            return $this->help->Fn_resulting_response([
                "code" => 200,
                "msg" => 'Successfully approve Invoice !'
            ]);
        }
    }
    public function Reject_Invoice()
    {
        $Invoice_Number = $this->input->post('Invoice_Number');

        $Hdr = $this->db->get_where($this->hdr_invoice, ['Invoice_Number' => $Invoice_Number])->row();
        $dtls = $this->db->get_where($this->dtl_invoice, ['Invoice_Number' => $Invoice_Number])->result();

        $this->db->trans_start();
        // -------------------------------

        $this->db->insert($this->hst_reject_hdr_invoice, [
            'SysId' => $Hdr->SysId,
            'Invoice_Number' => $Hdr->Invoice_Number,
            'Invoice_Rev' => $Hdr->Invoice_Rev,
            'Count_Print' => $Hdr->Count_Print,
            'Approve' => 'REJECT',
            'Invoice_Date' => $Hdr->Invoice_Date,
            'Due_Date' => $Hdr->Due_Date,
            'SO_ID' => $Hdr->SO_ID,
            'SO_Number' => $Hdr->SO_Number,
            'No_PO_Customer' => $Hdr->No_PO_Customer,
            'DN_Number' => $Hdr->DN_Number,
            'DN_ID' => $Hdr->DN_ID,
            'Customer_ID' => $Hdr->Customer_ID,
            'Customer_Code' => $Hdr->Customer_Code,
            'Customer_Name' => $Hdr->Customer_Name,
            'Address_ID' => $Hdr->Address_ID,
            'Customer_Address' => $Hdr->Customer_Address,
            'NPWP' => $Hdr->NPWP,
            'Item_Amount' => $Hdr->Item_Amount,
            'PPN' => $Hdr->PPN,
            'PPN_Amount' => $Hdr->PPN_Amount,
            'Invoice_Amount' => $Hdr->Invoice_Amount,
            'created_at' => $Hdr->created_at,
            'created_by' => $Hdr->created_by,
            'last_updated_at' => date('Y-m-d H:i:s'),
            'last_updated_by' => $this->session->userdata('impsys_initial'),
        ]);

        foreach ($dtls as $dtl) {
            $this->db->insert($this->hst_reject_dtl_invoice, [
                'SysId' => $dtl->SysId,
                'Invoice_Number' => $dtl->Invoice_Number,
                'Product_ID' => $dtl->Product_ID,
                'Product_Code' => $dtl->Product_Code,
                'Product_Name' => $dtl->Product_Name,
                'Qty' => $dtl->Qty,
                'Uom' => $dtl->Uom,
                'Product_Price' => $dtl->Product_Price,
                'Amount_Item' => $dtl->Amount_Item,
                'Created_at' => $dtl->Created_at,
                'Created_by' => $dtl->Created_by,
                'Last_Updated_at' => date('Y-m-d H:i:s'),
                'Last_Updated_by' => $this->session->userdata('impsys_initial'),
            ]);
        }

        $this->db->insert(
            $this->hst_approve_invoice,
            [
                'Invoice_Number' => $Invoice_Number,
                'Status' => "REJECT",
                'created_by' => $this->session->userdata('impsys_initial'),
            ]
        );

        $this->db->delete($this->hdr_invoice, ['Invoice_Number' => $Invoice_Number]);
        $this->db->delete($this->dtl_invoice, ['Invoice_Number' => $Invoice_Number]);

        // -------------------------------
        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => $error_msg
            ]);
        } else {
            return $this->help->Fn_resulting_response([
                "code" => 200,
                "msg" => 'Successfully reject Invoice !'
            ]);
        }
    }
}
