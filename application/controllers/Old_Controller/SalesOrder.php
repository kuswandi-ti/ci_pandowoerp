<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SalesOrder extends CI_Controller
{
    public $layout = 'layout';

    public $tbl_master_customer = 'tmst_customer';
    public $tbl_master_hdr_po   = 'ttrx_hdr_po_receive';
    public $tbl_master_dtl_po   = 'ttrx_dtl_po_receive';
    public $hdr_dn              = 'ttrx_hdr_delivery_note';
    public $table_all_dn        = 'qview_sj_detail_all';
    public $tbl_pajak           = 'tmst_persentase_pajak';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Data Sales Order";
        $this->data['page_content'] = "SO/sales_order";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/SO/sales_order.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function DT_Database_PO()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'SysId',
            1 => 'Doc_No_Internal',
            2 => 'No_Po_Customer',
            3 => 'Status_PO',
            4 => 'Handle',
            5 => 'Customer_Code',
            6 => 'Customer_Name',
            7 => 'Tgl_Terbit',
            8 => 'Term_Of_Payment',
            9 => 'Remark_TOP',
            10 => 'Term_Of_Delivery',
            11 => 'Customer_Address',
            12 => 'Koresponden',
            13 => 'Note',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $customer = $this->input->get('customer');
        $sql_customer = "";
        if (!empty($customer)) {
            $sql_customer = " AND a.ID_Customer = $customer ";
        }
        $from = $this->input->get('from');
        $to = $this->input->get('to');

        $sql = "SELECT a.SysId, a.Doc_No_Internal, a.No_Po_Customer, sum(c.Qty_Order) as tot_qty_Order, Fn_Sum_Qty_DN_SO(a.Doc_No_Internal) as tot_qty_dn, a.ID_Customer, a.Customer_Code, b.Customer_Name, a.Tgl_Terbit, a.Term_Of_Payment, a.Remark_TOP,
        a.Term_Of_Delivery, a.ID_Address, a.Customer_Address, a.Koresponden, a.Note, a.Status_PO, a.created_at, a.created_by, a.last_updated_at, a.last_updated_by
        FROM ttrx_hdr_po_receive a 
        join tmst_customer b on a.Customer_Code = b.Customer_Code
        join ttrx_dtl_po_receive c on a.Doc_No_Internal = c.Doc_No_Hdr
        WHERE a.Doc_No_Internal is not null
        $sql_customer 
        AND DATE_FORMAT(a.Tgl_Terbit, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(a.Tgl_Terbit, '%Y-%m-%d') <= '$to'
        ";

        $totalData = $this->db->query($sql)->num_rows();

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.Doc_No_Internal LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.No_Po_Customer LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Customer_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Customer_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Tgl_Terbit LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Term_Of_Payment LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Remark_TOP LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Term_Of_Delivery LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Customer_Address LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Koresponden LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Note LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        $sql .= " GROUP BY a.Doc_No_Internal , c.Doc_No_Hdr";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId'] = $row["SysId"];
            $nestedData['Doc_No_Internal'] = $row["Doc_No_Internal"];
            $nestedData['No_Po_Customer'] = $row["No_Po_Customer"];
            $nestedData['Status_PO'] = $row["Status_PO"];
            $nestedData['tot_qty_dn'] = floatval($row["tot_qty_dn"]);
            $nestedData['tot_qty_Order'] = floatval($row["tot_qty_Order"]);
            // $nestedData['Handle'] = null;
            $nestedData['Customer_Code'] = $row["Customer_Code"];
            $nestedData['Customer_Name'] = $row["Customer_Name"];
            $nestedData['Tgl_Terbit'] = $row["Tgl_Terbit"];
            $nestedData['Term_Of_Payment'] = $row["Term_Of_Payment"] . " Day";
            $nestedData['Remark_TOP'] = $row["Remark_TOP"];
            $nestedData['Term_Of_Delivery'] = $row["Term_Of_Delivery"];
            $nestedData['ID_Address'] = $row["ID_Address"];
            $nestedData['Customer_Address'] = $row["Customer_Address"];
            $nestedData['Koresponden'] = $row["Koresponden"];
            $nestedData['Note'] = $row["Note"];

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

    public function preview_sales_order()
    {
        $SysId = $this->input->get('SysId');

        $this->data['Hdr'] = $this->db->get_where($this->tbl_master_hdr_po, ['SysId' => $SysId])->row();
        $this->data['Dtl'] = $this->db->get_where($this->tbl_master_dtl_po, ['Doc_No_Hdr' => $this->data['Hdr']->Doc_No_Internal])->result();
        $this->data['PPn'] = $this->db->get_where($this->tbl_pajak, ['Name' => 'PPN'])->row();
        $this->data['Cust'] = $this->db->get_where($this->tbl_master_customer, ['SysId' => $this->data['Hdr']->ID_Customer])->row();

        return $this->load->view("SO/m_pvw_so", $this->data);
    }

    public function M_List_Dn_So()
    {
        $SysId = $this->input->get('SysId');

        $this->data['Hdr'] = $this->db->get_where($this->tbl_master_hdr_po, ['SysId' => $SysId])->row();
        $this->data['Cust'] = $this->db->get_where($this->tbl_master_customer, ['SysId' => $this->data['Hdr']->ID_Customer])->row();

        return $this->load->view("SO/m_pvw_dn_so", $this->data);
    }

    public function M_Edit_Hdr_So()
    {
        $SysId = $this->input->get('SysId');

        $this->data['Hdr'] = $this->db->get_where($this->tbl_master_hdr_po, ['SysId' => $SysId])->row();
        $this->data['Dtl'] = $this->db->get_where($this->tbl_master_dtl_po, ['Doc_No_Hdr' => $this->data['Hdr']->Doc_No_Internal])->result();
        $this->data['PPn'] = $this->db->get_where($this->tbl_pajak, ['Name' => 'PPN'])->row();
        $this->data['Cust'] = $this->db->get_where($this->tbl_master_customer, ['SysId' => $this->data['Hdr']->ID_Customer])->row();

        return $this->load->view("SO/m_edit_hdr_so", $this->data);
    }

    public function M_Edit_Dtl_So()
    {
        $SysId = $this->input->get('SysId');

        $this->data['Hdr'] = $this->db->get_where($this->tbl_master_hdr_po, ['SysId' => $SysId])->row();
        $this->data['Dtls'] = $this->db->get_where($this->tbl_master_dtl_po, ['Doc_No_Hdr' => $this->data['Hdr']->Doc_No_Internal])->result();
        $this->data['Cust'] = $this->db->get_where($this->tbl_master_customer, ['SysId' => $this->data['Hdr']->ID_Customer])->row();

        return $this->load->view("SO/m_edit_dtl_so", $this->data);
    }

    public function M_List_SO_Outstanding_Customer_Pick()
    {
        $this->data['Customer_Code'] = $this->input->get('customer_code');
        return $this->load->view("SO/M_List_SO_Outstanding_Customer_Pick", $this->data);
    }

    public function DT_SO_Outstanding_Customer_ID()
    {
        $id_customer = $this->input->post('id_customer');

        $requestData = $_REQUEST;
        $columns = array(
            0 => 'SO_SysId_Hdr',
            1 => 'SO_Number',
            2 => 'No_Po_Customer',
            3 => 'Status_SO',
            4 => 'Customer_Code',
            5 => 'Customer_Name',
            6 => 'Tgl_Terbit',
            7 => 'Term_Of_Payment',
            8 => 'Remark_TOP',
            9 => 'Term_Of_Delivery',
            10 => 'Customer_Address',
            11 => 'Koresponden',
            12 => 'Note',
            13 => 'ID_Address',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT SO_SysId_Hdr, SO_Number, No_Po_Customer, Status_SO, Customer_Code, Customer_Name, Tgl_Terbit, Term_Of_Payment, Remark_TOP, Term_Of_Delivery, ID_Address, Customer_Address, Koresponden, Note , SUM(Qty_SO_OutStanding) as Sum_Outstanding from qview_so_vs_sj_outstanding_so where SysId_Customer = $id_customer ";

        // SELECT SO_SysId_Hdr, SO_SysId_Dtl, SO_Number, No_Po_Customer, SysId_Customer, Customer_Code, Customer_Name, Flag, SysId_Product, Product_Code, Product_Name, Qty_SO, Qty_SJ, Uom, Product_Price, Amount_SO_PerItem, Amount_SJ, Qty_SO_OutStanding, Outstanding_Amount_SO, Tgl_Terbit, Term_Of_Payment, Remark_TOP, Term_Of_Delivery, ID_Address, Customer_Address, Koresponden, Note, Status_SO
        // FROM impsys.qview_so_vs_sj_outstanding_so;


        $totalData = $this->db->query($sql)->num_rows();

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (SO_Number LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR No_Po_Customer LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Tgl_Terbit LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Term_Of_Payment LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Remark_TOP LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Term_Of_Delivery LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Address LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Koresponden LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Note LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        $sql .= " GROUP BY SO_Number having Sum_Outstanding > 0";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SO_SysId_Hdr']     = $row["SO_SysId_Hdr"];
            $nestedData['SO_Number']        = $row["SO_Number"];
            $nestedData['No_Po_Customer']   = $row["No_Po_Customer"];
            $nestedData['Status_SO']        = $row["Status_SO"];
            $nestedData['Customer_Code']    = $row["Customer_Code"];
            $nestedData['Customer_Name']    = $row["Customer_Name"];
            $nestedData['Tgl_Terbit']       = $row["Tgl_Terbit"];
            $nestedData['Term_Of_Payment']  = $row["Term_Of_Payment"] . " Day";
            $nestedData['Remark_TOP']       = $row["Remark_TOP"];
            $nestedData['Term_Of_Delivery'] = $row["Term_Of_Delivery"];
            $nestedData['ID_Address']       = $row["ID_Address"];
            $nestedData['Customer_Address'] = $row["Customer_Address"];
            $nestedData['Koresponden']      = $row["Koresponden"];
            $nestedData['Note']             = $row["Note"];

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

    public function Delete_SO()
    {
        $SysId = $this->input->post('SysId');

        $Hdr = $this->db->get_where($this->tbl_master_hdr_po, ['SysId' => $SysId])->row();
        // $Dtl = $this->db->get_where($this->tbl_master_dtl_po, ['Doc_No_Hdr' => $Hdr->Doc_No_Internal]);
        $HdrDN = $this->db->get_where($this->hdr_dn, ['No_PO_Internal' => $Hdr->Doc_No_Internal]);

        if ($HdrDN->num_rows() > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => "This SO has been have Delivery Note !"
            ]);
        }


        $Hdr = $this->db->get_where($this->tbl_master_hdr_po, ['SysId' => $SysId])->row();
        $DataBefore = [
            'Action'            => 'DELETE',
            'SysId'             => $Hdr->SysId,
            'Doc_No_Internal'   => $Hdr->Doc_No_Internal,
            'No_Po_Customer'    => $Hdr->No_Po_Customer,
            'ID_Customer'       => $Hdr->ID_Customer,
            'Customer_Code'     => $Hdr->Customer_Code,
            'Tgl_Terbit'        => $Hdr->Tgl_Terbit,
            'Term_Of_Payment'   => $Hdr->Term_Of_Payment,
            'Remark_TOP'        => $Hdr->Remark_TOP,
            'Term_Of_Delivery'  => $Hdr->Term_Of_Delivery,
            'PPn'               => $Hdr->PPn,
            'ID_Address'        => $Hdr->ID_Address,
            'Customer_Address'  => $Hdr->Customer_Address,
            'Koresponden'       => $Hdr->Koresponden,
            'Note'              => $Hdr->Note,
            'Status_PO'         => $Hdr->Status_PO,
            'created_at'        => $Hdr->created_at,
            'created_by'        => $Hdr->created_by,
            'last_updated_at'   => $Hdr->last_updated_at,
            'last_updated_by'   => $Hdr->last_updated_by,
            'do_at' => date('Y-m-d H:i:s'),
            'do_by' => $this->session->userdata('impsys_initial'),
        ];

        $RowPos = $this->db->get_where($this->tbl_master_dtl_po, ['Doc_No_Hdr' => $Hdr->Doc_No_Internal])->result();

        $this->db->trans_start();
        // -------------------------------
        foreach ($RowPos as $RowPo) {
            $this->db->insert('thst_dtl_po_receive', [
                'Action'        => "DELETE ROW BY DELETE HEADER",
                'SysId'         => $RowPo->SysId,
                'Flag'          => $RowPo->Flag,
                'Doc_No_Hdr'    => $RowPo->Doc_No_Hdr,
                'Product_ID'    => $RowPo->Product_ID,
                'Product_Code'  => $RowPo->Product_Code,
                'Product_Name'  => $RowPo->Product_Name,
                'Product_Price' => $RowPo->Product_Price,
                'Qty_Order'     => $RowPo->Qty_Order,
                'Uom'           => $RowPo->Uom,
                'created_at'    => $RowPo->created_at,
                'created_by'    => $RowPo->created_by,
                'do_at'         => $this->session->userdata('impsys_initial'),
                'do_by'         => date('Y-m-d H:i:s'),
            ]);
        }

        $this->db->insert('thst_hdr_po_receive', $DataBefore);

        $this->db->where('Doc_No_Hdr', $Hdr->Doc_No_Internal);
        $this->db->delete($this->tbl_master_dtl_po);

        $this->db->where('SysId', $SysId);
        $this->db->delete($this->tbl_master_hdr_po);

        // -------------------------------
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code" => 505,
                "msg" => "Failed delete SO !"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "Successfully Delete SO !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function DT_Dn_So()
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
        $SO = $this->input->post('SO');

        $sql = "SELECT * FROM $this->table_all_dn WHERE  No_PO_Internal = '$SO'";

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

    public function Store_Update_So()
    {
        $RowCust = $this->db->get_where($this->tbl_master_customer, ['SysId' => $this->input->post('customer')])->row();
        $PPn = ($this->input->post('PPn') == NULL) ? 0 : floatval($this->input->post('PPn'));
        $RowBefore = $this->db->get_where($this->tbl_master_hdr_po, ['SysId' => $this->input->post('SysId')])->row();
        $DataBefore = [
            'Action'            => 'UPDATE HEADER',
            'SysId'             => $RowBefore->SysId,
            'Doc_No_Internal'   => $RowBefore->Doc_No_Internal,
            'No_Po_Customer'    => $RowBefore->No_Po_Customer,
            'ID_Customer'       => $RowBefore->ID_Customer,
            'Customer_Code'     => $RowBefore->Customer_Code,
            'Tgl_Terbit'        => $RowBefore->Tgl_Terbit,
            'Term_Of_Payment'   => $RowBefore->Term_Of_Payment,
            'Remark_TOP'        => $RowBefore->Remark_TOP,
            'Term_Of_Delivery'  => $RowBefore->Term_Of_Delivery,
            'PPn'               => $RowBefore->PPn,
            'ID_Address'        => $RowBefore->ID_Address,
            'Customer_Address'  => $RowBefore->Customer_Address,
            'Koresponden'       => $RowBefore->Koresponden,
            'Note'              => $RowBefore->Note,
            'Status_PO'         => $RowBefore->Status_PO,
            'created_at'        => $RowBefore->created_at,
            'created_by'        => $RowBefore->created_by,
            'last_updated_at'   => $RowBefore->last_updated_at,
            'last_updated_by'   => $RowBefore->last_updated_by,
            'do_at' => date('Y-m-d H:i:s'),
            'do_by' => $this->session->userdata('impsys_initial'),
        ];

        $this->db->trans_start();

        $this->db->insert('thst_hdr_po_receive', $DataBefore);

        $this->db->where('SysId', $this->input->post('SysId'));
        $this->db->update($this->tbl_master_hdr_po, [
            'ID_Customer' => $this->input->post('customer'),
            'Customer_Code' => $RowCust->Customer_Code,
            'Tgl_Terbit' => $this->input->post('tgl_terbit'),
            'Term_Of_Payment' => floatval($this->input->post('term_of_payment')),
            'Remark_TOP' => $this->input->post('condition_top'),
            'Term_Of_Delivery' => $this->input->post('term_of_delivery'),
            'PPn' => $PPn,
            'ID_Address' => $this->input->post('id_address'),
            'Customer_Address' => $this->input->post('customer_address'),
            'Koresponden' => $this->input->post('koresponden'),
            'Note' => $this->input->post('note'),
            'last_updated_at' => date('Y-m-d H:i:s'),
            'last_updated_by' => $this->session->userdata('impsys_initial')
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'Update Header PO Customer gagal !',
            ]);
        } else {
            $this->db->trans_commit();
            return $this->help->Fn_resulting_response([
                'code'      => 200,
                'msg'       => 'Update Header PO Customer berhasil !',
            ]);
        }
    }
}
