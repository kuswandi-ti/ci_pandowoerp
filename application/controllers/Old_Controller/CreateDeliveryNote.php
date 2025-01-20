<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CreateDeliveryNote extends CI_Controller
{
    public $layout          = 'layout';
    public $tbl_hdr_po      = 'ttrx_hdr_po_receive';
    public $tbl_dtl_po      = 'ttrx_dtl_po_receive';
    public $tmp_hdr_dn      = 'ttmp_hdr_delivery_note';
    public $tmp_dtl_dn      = 'ttmp_dtl_delivery_note';
    public $Tbl_Hdr_DN      = 'ttrx_hdr_delivery_note';
    public $Tbl_Item_DN     = 'ttrx_dtl_delivery_note';
    public $tbl_master_customer = 'tmst_customer';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Create Delivery Note";
        $this->data['page_content'] = "DN/form_dn";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/DN/form_dn.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function Store_Hdr_Tmp_DN()
    {
        $DN_Number = $this->help->Gnrt_Identity_Number_Dn("DN-IMP");

        $this->db->trans_start();
        $this->db->insert(
            $this->tmp_hdr_dn,
            [
                "DN_Number" => $DN_Number,
                "SysId_Customer" => $this->input->post('id_customer'),
                "Customer_Code" => $this->input->post('customer_code'),
                "Customer_Name" => $this->input->post('customer_name'),
                "SysId_PO" => $this->input->post('id_po'),
                "No_PO_Customer" => $this->input->post('no_po_customer'),
                "No_PO_Internal" => $this->input->post('no_po_internal'),
                "Send_Date" => $this->input->post('tgl_kirim'),
                "SysId_Address" => $this->input->post('id_address'),
                "Complete_Address" => $this->input->post('customer_address'),
                "Att_To" => strtoupper($this->input->post('att_to')),
                "SysId_Vehicle" => $this->input->post('id_kendaraan'),
                "Vehicle_Police_Number" => $this->input->post('no_kendaraan'),
                "Init_Driver" => $this->input->post('init_driver'),
                "Driver_Name" => $this->input->post('nama_driver'),
                "Remark" => "CREATE DN",
                "Created_at" => date('Y-m-d H:i:s'),
                "Created_by" => $this->session->userdata('impsys_initial')
            ]
        );

        $this->db->insert($this->tmp_dtl_dn, [
            'Flag' => 1,
            'DN_Number' => $DN_Number,
            'SysId_Product' => NULL,
            'Product_Code' => NULL,
            'Product_Name' => NULL,
            'Qty' => 0,
            'Uom' => NULL,
            'Created_at' => date('Y-m-d H:i:s'),
            'Created_by' => $this->session->userdata('impsys_initial')
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'Create Dn Number failed !',
            ]);
        } else {
            $this->db->trans_commit();
            $FirstRowDtl = $this->db->get_where($this->tmp_dtl_dn, ['DN_Number' => $DN_Number])->row();

            return $this->help->Fn_resulting_response([
                'code'  => 200,
                'msg'   => 'Dn Number successfully created !',
                'No_DN' => $DN_Number,
                'Flag' => 1,
                'SysId_Dtl' => $FirstRowDtl->SysId
            ]);
        }
    }

    public function add_row_tmp_detail_dn()
    {
        $DN_Number = $this->input->post('DN_Number');
        $Flag = $this->input->post('Flag');

        $this->db->trans_start();
        $this->db->insert($this->tmp_dtl_dn, [
            'Flag' => $Flag,
            'DN_Number' => $DN_Number,
            'SysId_Product' => NULL,
            'Product_Code' => NULL,
            'Product_Name' => NULL,
            'Qty' => 0,
            'Uom' => NULL,
            'Created_at' => date('Y-m-d H:i:s'),
            'Created_by' => $this->session->userdata('impsys_initial')
        ]);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'Add item DN failed !',
            ]);
        } else {
            $this->db->trans_commit();
            $FirstRowDtl = $this->db->get_where($this->tmp_dtl_dn, ['DN_Number' => $DN_Number, 'Flag' => $Flag])->row();

            return $this->help->Fn_resulting_response([
                'code'      => 200,
                'msg'       => 'Successfully Add item DN !',
                'Flag'      =>  $Flag,
                'SysId' => $FirstRowDtl->SysId
            ]);
        }
    }

    public function delete_row_detail_dn()
    {
        $this->db->trans_start();

        $this->db->where('SysId', $this->input->post('sysid'));
        $this->db->delete($this->tmp_dtl_dn);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "delete detail DN failed!"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "success delete detail DN!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store_editable_detail_qty_dn()
    {
        $value = floatval($this->input->post('value'));
        $sysid = $this->input->post('pk');

        $tmp_dtl = $this->db->get_where($this->tmp_dtl_dn, ['SysId' => $sysid])->row();

        if (empty($tmp_dtl->Product_Code)) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "You must select item first !"
            ]);
        }

        $tmp_hdr = $this->db->get_where($this->tmp_hdr_dn, ['DN_Number' => $tmp_dtl->DN_Number])->row();


        $SO_Outstanding = $this->db->get_where('qview_so_vs_sj_outstanding_so', [
            'SO_Number' => $tmp_hdr->No_PO_Internal,
            'Product_Code' => $tmp_dtl->Product_Code
        ])->row();

        if (floatval($value) > floatval($SO_Outstanding->Qty_SO_OutStanding)) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Qty Delivery note cannot greather than qty SO Outstanding !"
            ]);
        }

        $this->db->trans_start();

        $this->db->where('SysId', $sysid);
        $this->db->update($this->tmp_dtl_dn, ['Qty' => $value]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "failed update Qty item!"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Successfully update Qty item !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function select_product_customer()
    {
        $search = $this->input->get('search');
        $no_po_internal = $this->input->get('no_po_internal');
        $query = $this->db->query(
            "SELECT a.Product_ID , a.Product_Code , a.Product_Name , a.Uom , a.Qty_Order , b.Qty_SO_OutStanding
            FROM ttrx_dtl_po_receive a
            join qview_so_vs_sj_outstanding_so b on a.Doc_No_Hdr = b.SO_Number and a.Product_Code = b.Product_Code 
            WHERE a.Doc_No_Hdr = '$no_po_internal' and b.Qty_SO_OutStanding > 0
            AND (a.Product_Code like '%$search%' and a.Product_Name like '%$search%')"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['Product_ID'];
                $list[$key]['text'] = $row['Product_Name'] . ' (' . $row['Product_Code'] . ')';
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function Get_utility_detail_product()
    {
        $id_product = $this->input->post('id_product');
        $SysId = $this->input->post('sysid');
        $flag = $this->input->post('flag');
        $no_po_internal = $this->input->post('no_po_internal');

        $RowTmpDn   = $this->db->get_where($this->tmp_dtl_dn, ['SysId' => $SysId])->row();

        $ValidateUniqueProduct = $this->db->get_where($this->tmp_dtl_dn, [
            'DN_Number'  => $RowTmpDn->DN_Number,
            'SysId_Product' => $id_product,
            'Flag !='    => $flag
        ])->num_rows();

        if ($ValidateUniqueProduct > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'Product has been choosen on other row in this transaction !'
            ]);
        }

        $RowProductPO = $this->db->get_where($this->tbl_dtl_po, ['Doc_No_Hdr' => $no_po_internal, 'Product_ID' => $id_product])->row();
        $this->db->trans_start();

        $this->db->where('SysId', $SysId);
        $this->db->update($this->tmp_dtl_dn, [
            'SysId_Product' => $RowProductPO->Product_ID,
            'Product_Code' => $RowProductPO->Product_Code,
            'Product_Name' => $RowProductPO->Product_Name,
            'Uom' => $RowProductPO->Uom,
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Select product detail po failed!"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Product has been selected !",
                "uom" => $RowProductPO->Uom,
                "price" => $RowProductPO->Product_Price
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function Store_Complete_Dn()
    {
        $DN_Number = $this->input->post('DN_Number');

        $ZeroQty =  $this->db->get_where($this->tmp_dtl_dn, ['DN_Number' => $DN_Number, 'Qty' => 0])->num_rows();
        if ($ZeroQty > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'Quantity DN must greather than zero !'
            ]);
        }

        $DataHdr = $this->db->get_where($this->tmp_hdr_dn, ['DN_Number' => $DN_Number])->row();
        $DataDtls = $this->db->get_where($this->tmp_dtl_dn, ['DN_Number' => $DN_Number])->result();

        $this->db->trans_start();
        $this->db->insert($this->Tbl_Hdr_DN, [
            'DN_Number'         => $DataHdr->DN_Number,
            'SysId_Customer'    => $DataHdr->SysId_Customer,
            'Customer_Code'     => $DataHdr->Customer_Code,
            'Customer_Name'     => $DataHdr->Customer_Name,
            'SysId_PO'          => $DataHdr->SysId_PO,
            'No_PO_Customer'    => $DataHdr->No_PO_Customer,
            'No_PO_Internal'    => $DataHdr->No_PO_Internal,
            'Send_Date'         => $DataHdr->Send_Date,
            'SysId_Address'     => $DataHdr->SysId_Address,
            'Complete_Address'  => $DataHdr->Complete_Address,
            'Att_To'            => strtoupper($DataHdr->Att_To),
            'SysId_Vehicle'     => $DataHdr->SysId_Vehicle,
            'Vehicle_Police_Number' => $DataHdr->Vehicle_Police_Number,
            'Init_Driver'       => $DataHdr->Init_Driver,
            'Driver_Name'       => $DataHdr->Driver_Name,
            'Remark'            => "CREATE FROM IMPSYS",
            'Created_at'        => date('Y-m-d H:i:s'),
            'Created_by'        => $this->session->userdata('impsys_initial'),
        ]);
        foreach ($DataDtls as $DataDtl) {
            $this->db->insert($this->Tbl_Item_DN, [
                "Flag" => $DataDtl->Flag,
                "DN_Number" => $DataDtl->DN_Number,
                "SysId_Product" => $DataDtl->SysId_Product,
                "Product_Code" => $DataDtl->Product_Code,
                "Product_Name" => $DataDtl->Product_Name,
                "Qty" => $DataDtl->Qty,
                "Uom" => $DataDtl->Uom,
                "Created_at" => $DataDtl->Created_at,
                "Created_by" => $DataDtl->Created_by,
            ]);
        }

        $this->db->where('DN_Number', $DN_Number);
        $this->db->delete($this->tmp_hdr_dn);

        $this->db->where('DN_Number', $DN_Number);
        $this->db->delete($this->tmp_dtl_dn);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Failed to register Delivery Note !"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Delivery Note successfully registered !",
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }
}
