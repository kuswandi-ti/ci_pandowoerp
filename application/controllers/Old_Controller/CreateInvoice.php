<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CreateInvoice extends CI_Controller
{
    public $layout = 'layout';
    public $tmp_hdr_invoice = 'ttmp_hdr_invoice';
    public $tmp_dtl_invoice = 'ttmp_dtl_invoice';
    public $hdr_invoice     = 'ttrx_hdr_invoice';
    public $dtl_invoice     = 'ttrx_dtl_invoice';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Create Invoice";
        $this->data['page_content'] = "Invoice/create_invoice";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/Invoice/form_invoice.js"></script>';

        $this->data['tmp_hdr'] = $this->db->get_where($this->tmp_hdr_invoice, ['Status' => 'CREATE', 'created_by' => $this->session->userdata('impsys_initial')])->row();

        $this->load->view($this->layout, $this->data);
    }

    public function Store_Hdr_Tmp_Invoice()
    {
        $No_Invoice_Show = $this->input->post('Invoice_Number');

        $DN_Number = $this->input->post('DN');
        $SO_Number = $this->input->post('no_po_internal');

        $ValidateLoadingItem = $this->db->get_where('qview_sj_detail_all', ['DN_Number' => $DN_Number, 'No_Loading' => NULL]);
        if ($ValidateLoadingItem->num_rows() > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => "Some item in Delivery Note $DN_Number, doesnt have loading number, check DN Outstanding !",
            ]);
        }

        $RowAmountDN = $this->db->get_where('qview_so_vs_sj_amount_dn', ['DN_Number' => $DN_Number, 'SO_Number' => $SO_Number])->row();

        $PPN            = $RowAmountDN->PPn;
        $Item_Amount    = $RowAmountDN->Amount_Product;
        $PPN_Amount     = $RowAmountDN->Amount_PPn;
        $Invoice_Amount = $RowAmountDN->Amount_Invoice;

        $ListItems = $this->db->get_where('qview_so_vs_sj_price_product_dn', ['DN_Number' => $DN_Number])->result();
        $Invoice_Number = $this->help->Gnrt_Identity_Number_Dn("INV-IMP");

        if ($No_Invoice_Show == 'NEW') {
            $this->db->trans_start();
            $this->db->insert('ttmp_hdr_invoice', [
                'Invoice_Number' => $Invoice_Number,
                'Invoice_Rev' => 0,
                'Count_Print' => 0,
                'Status' => 'CREATE',
                'Invoice_Date' => $this->input->post('invoice_date'),
                'Due_Date' => $this->input->post('due_date'),
                'SO_ID' => $this->input->post('id_po'),
                'SO_Number' => $this->input->post('no_po_internal'),
                'No_PO_Customer' => $this->input->post('no_po_customer'),
                'DN_Number' => $this->input->post('DN'),
                'DN_ID' => $this->input->post('dn_id'),
                'Customer_ID' => $this->input->post('id_customer'),
                'Customer_Code' => $this->input->post('customer_code'),
                'Customer_Name' => $this->input->post('customer_name'),
                'Address_ID' => $this->input->post('id_address'),
                'Customer_Address' => $this->input->post('customer_address'),
                'NPWP' => $this->input->post('NPWP'),
                'Item_Amount' => $Item_Amount,
                'PPN' => $PPN,
                'PPN_Amount' => $PPN_Amount,
                'Invoice_Amount' => $Invoice_Amount,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('impsys_initial'),
                'last_updated_at' => NULL,
                'last_updated_by' => NULL
            ]);

            foreach ($ListItems as $item) {
                $this->db->insert('ttmp_dtl_invoice', [
                    'Invoice_Number' => $Invoice_Number,
                    'Product_ID' => $item->SysId_Product,
                    'Product_Code' => $item->Product_Code,
                    'Product_Name' => $item->Product_Name,
                    'Qty' => $item->Qty,
                    'Uom' => $item->Uom,
                    'Product_Price' => $item->Product_Price,
                    'Amount_Item' => $item->Product_Amount,
                    'Created_at' => date('Y-m-d H:i:s'),
                    'Created_by' => $this->session->userdata('impsys_initial'),
                ]);
            }
            $error_msg = $this->db->error()["message"];
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return $this->help->Fn_resulting_response([
                    'code' => 505,
                    'msg'  => $error_msg,
                ]);
            } else {
                $this->db->trans_commit();
                return $this->help->Fn_resulting_response([
                    'code' => 200,
                    'msg' => 'Invoice Number successfully created !',
                    'Invoice_Number' => $Invoice_Number,
                    'Item_Amount' => number_format(floatval($Item_Amount), 2),
                    'PPN' => floatval($PPN),
                    'PPN_Amount' => number_format(floatval($PPN_Amount), 2),
                    'Invoice_Amount' => number_format(floatval($Invoice_Amount), 2),
                ]);
            }
        } else {
            $Invoice_Number = $this->input->post('Invoice_Number');
        }
    }

    public function DT_Dtl_Item_Invoice()
    {
        $requestData = $_REQUEST;
        $created_by = $this->session->userdata('impsys_initial');
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
        $sql = "SELECT * FROM ttmp_dtl_invoice WHERE Created_by = '$created_by' ";

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

    public function delete_tmp_invoice()
    {
        $Invoice_Number = $this->input->post('Invoice_Number');

        $this->db->trans_start();
        // -------------------------------

        $this->db->where('Invoice_Number', $Invoice_Number);
        $this->db->delete($this->tmp_dtl_invoice);
        $this->db->where('Invoice_Number', $Invoice_Number);
        $this->db->delete($this->tmp_hdr_invoice);

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
                "msg" => 'Pembatalan form invoice berhasil !'
            ]);
        }
    }

    public function Store_Invoice()
    {
        $Invoice_Number = $this->input->post('Invoice_Number');
        $Hdr = $this->db->get_where($this->tmp_hdr_invoice, ['Invoice_Number' => $Invoice_Number])->row();
        $Dtls = $this->db->get_where($this->tmp_dtl_invoice, ['Invoice_Number' => $Invoice_Number])->result();
        $this->db->trans_start();
        // -------------------------------
        $this->db->insert($this->hdr_invoice, [
            'Invoice_Number' => $Hdr->Invoice_Number,
            'Invoice_Rev' => $Hdr->Invoice_Rev,
            'Count_Print' => $Hdr->Count_Print,
            'Approve' => 'PREPARATION',
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
            'Item_Amount' => floatval($Hdr->Item_Amount),
            'PPN' => floatval($Hdr->PPN),
            'PPN_Amount' => floatval($Hdr->PPN_Amount),
            'Invoice_Amount' => floatval($Hdr->Invoice_Amount),
            'created_at' => $Hdr->created_at,
            'created_by' => $Hdr->created_by,
            'last_updated_at' => $Hdr->last_updated_at,
            'last_updated_by' => $Hdr->last_updated_by,
        ]);

        foreach ($Dtls as $dtl) {
            $this->db->insert($this->dtl_invoice, [
                'Invoice_Number' => $dtl->Invoice_Number,
                'Product_ID' => $dtl->Product_ID,
                'Product_Code' => $dtl->Product_Code,
                'Product_Name' => $dtl->Product_Name,
                'Qty' => floatval($dtl->Qty),
                'Uom' => $dtl->Uom,
                'Product_Price' => floatval($dtl->Product_Price),
                'Amount_Item' => floatval($dtl->Amount_Item),
                'Created_at' => $dtl->Created_at,
                'Created_by' => $dtl->Created_by,
                'Last_Updated_at' => $dtl->Last_Updated_at,
                'Last_Updated_by' => $dtl->Last_Updated_by,
            ]);
        }

        // -------------------------------
        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => $error_msg
            ]);
        } else {

            $this->db->where('Invoice_Number', $Invoice_Number);
            $this->db->delete($this->tmp_hdr_invoice);
            $this->db->where('Invoice_Number', $Invoice_Number);
            $this->db->delete($this->tmp_dtl_invoice);

            return $this->help->Fn_resulting_response([
                "code" => 200,
                "msg" => "Invoice Number : $Invoice_Number, Successfully saved !"
            ]);
        }
    }
}
