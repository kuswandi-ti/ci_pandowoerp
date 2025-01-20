<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DnOutstanding extends CI_Controller
{
    public $layout       = 'layout';
    public $table_all_dn = 'qview_sj_detail_all';
    public $tbl_hdr_dn = 'ttrx_hdr_delivery_note';
    public $tbl_dtl_dn = 'ttrx_dtl_delivery_note';
    public $qview_detail_loading = 'qview_detail_hdr_loading';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Delivery Note OutStanding";
        $this->data['page_content'] = "DN/Dn_Outstanding";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/DN/Dn_Outstanding.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function DT_OutStanding_DN()
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
            11 => 'Send_Date',
            12 => 'Complete_Address',
            13 => 'Att_To',
            14 => 'Vehicle_Police_Number',
            15 => 'Driver_Name',
            16 => 'Remark',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT * FROM $this->table_all_dn WHERE isnull(No_Loading)";

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

    public function Dn_Vs_Loading()
    {
        $this->data['SysId_Dtl'] = $this->input->get('SysId_Dtl');
        $this->data['Dtl_Dn'] = $this->db->get_where($this->tbl_dtl_dn, ['SysId' => $this->input->get('SysId_Dtl')])->row();
        // $this->data['Loadings'] = $this->db->get_where($this->qview_detail_loading, [
        //     '' => $this->data['Dtl_Dn']->Product_Code,
        //     'No_Loading_DN' => NULL
        // ])->eresult();
        return $this->load->view("DN/m_list_dn_loading", $this->data);
    }

    public function DT_Loading_product_vs_DN()
    {
        $SysId_Dtl = $this->input->post('SysId_Dtl');
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'SysId',
            2 => 'No_loading',
            3 => 'Customer_Code',
            4 => 'STATUS',
            5 => 'Product_Code',
            6 => 'Product_Name',
            7 => 'Qty_Loading',
            8 => 'Silang_Product',
            9 => 'Selesai_at',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $DtlDN =  $this->db->get_where($this->tbl_dtl_dn, ['SysId' => $SysId_Dtl])->row();

        $sql = "SELECT * FROM $this->qview_detail_loading WHERE Product_Code = '$DtlDN->Product_Code' and isnull(No_Loading_DN)";

        $totalData = $this->db->query($sql)->num_rows();

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (No_loading LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Customer_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR STATUS LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Product_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Product_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Qty_Loading LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Silang_Product LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Selesai_at LIKE '%" . $requestData['search']['value'] . "%') ";
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
            $nestedData['SysId'] = $row['SysId'];
            $nestedData['No_loading'] = $row['No_loading'];
            $nestedData['No_Loading_DN'] = $row['No_Loading_DN'];
            $nestedData['Customer_ID'] = $row['Customer_ID'];
            $nestedData['Customer_Code'] = $row['Customer_Code'];
            $nestedData['Customer_Name'] = $row['Customer_Name'];
            $nestedData['Product_ID'] = $row['Product_ID'];
            $nestedData['Product_Code'] = $row['Product_Code'];
            $nestedData['Product_Name'] = $row['Product_Name'];
            $nestedData['Qty_Loading'] = $row['Qty_Loading'];
            $nestedData['STATUS'] = $row['STATUS'];
            $nestedData['Silang_Product'] = $row['Silang_Product'];
            $nestedData['Selesai_at'] = $row['Selesai_at'];
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

    public function Combine_Loading_vs_DN()
    {
        $SysId_Dtl = $this->input->post('SysId_Dtl');
        $No_Loading = $this->input->post('No_Loading');

        $DtlDn = $this->db->get_where($this->tbl_dtl_dn, ['SysId' => $SysId_Dtl])->row();
        $Loading = $this->db->get_where($this->qview_detail_loading, ['No_loading' => $No_Loading])->row();

        if (floatval($DtlDn->Qty) != floatval($Loading->Qty_Loading)) {
            return $this->help->Fn_resulting_response([
                'code' => 500,
                'msg' => 'Qty Not Match!'
            ]);
        }

        $this->db->trans_start();
        // -------------------------------

        $this->db->where('SysId', $SysId_Dtl);
        $this->db->update($this->tbl_dtl_dn, [
            'No_Loading' => $No_Loading
        ]);

        // -------------------------------
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code" => 505,
                "msg" => "Combine No. Loading with Dn Number Failed !"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "Combine No. Loading with Dn Number Successfully !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function Delete_DN()
    {
        $SysId = $this->input->post('SysId');

        $Hdr = $this->db->get_where($this->tbl_hdr_dn, ['SysId' => $SysId])->row();
        $Dtls = $this->db->get_where($this->tbl_dtl_dn, ['DN_Number' => $Hdr->DN_Number])->result();

        $this->db->trans_start();
        // -------------------------------

        $this->db->insert('thst_ttrx_hdr_delivery_note', [
            'DN_Number' => $Hdr->DN_Number,
            'SysId_Customer' => $Hdr->SysId_Customer,
            'Customer_Code' => $Hdr->Customer_Code,
            'Customer_Name' => $Hdr->Customer_Name,
            'SysId_PO' => $Hdr->SysId_PO,
            'No_PO_Customer' => $Hdr->No_PO_Customer,
            'No_PO_Internal' => $Hdr->No_PO_Internal,
            'Send_Date' => $Hdr->Send_Date,
            'SysId_Address' => $Hdr->SysId_Address,
            'Complete_Address' => $Hdr->Complete_Address,
            'Att_To' => $Hdr->Att_To,
            'SysId_Vehicle' => $Hdr->SysId_Vehicle,
            'Vehicle_Police_Number' => $Hdr->Vehicle_Police_Number,
            'Init_Driver' => $Hdr->Init_Driver,
            'Driver_Name' => $Hdr->Driver_Name,
            'Remark' => $Hdr->Remark,
            'Count_Print_Dn' => $Hdr->Count_Print_Dn,
            'Count_Print_Invoice' => $Hdr->Count_Print_Invoice,
            'Created_at' => $Hdr->Created_at,
            'Created_by' => $Hdr->Created_by,
            'Last_updated_at' => $Hdr->Last_updated_at,
            'Last_updated_by' => $Hdr->Last_updated_by,
            'do_at' => date('Y-m-d H:i:s'),
            'do_by' => $this->session->userdata('impsys_initial'),
            'action' => 'DELETE',
        ]);

        foreach ($Dtls as $dtl) {
            $this->db->insert('thst_ttrx_dtl_delivery_note', [
                'Flag' => $dtl->Flag,
                'DN_Number' => $dtl->DN_Number,
                'SysId_Product' => $dtl->SysId_Product,
                'Product_Code' => $dtl->Product_Code,
                'Product_Name' => $dtl->Product_Name,
                'Qty' => $dtl->Qty,
                'Uom' => $dtl->Uom,
                'No_Loading' => $dtl->No_Loading,
                'Created_at' => $dtl->Created_at,
                'Created_by' => $dtl->Created_by,
                'Last_updated_at' => $dtl->Last_updated_at,
                'Last_updated_by' => $dtl->Last_updated_by,
                'do_at' => date('Y-m-d H:i:s'),
                'do_by' => $this->session->userdata('impsys_initial'),
                'action' => 'DELETE',

            ]);
        }

        $this->db->delete($this->tbl_dtl_dn, ['DN_Number' => $Hdr->DN_Number]);
        $this->db->delete($this->tbl_hdr_dn, ['SysId' => $SysId]);

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
                "msg" => 'Successfully delete delivery note !'
            ]);
        }
    }
}
