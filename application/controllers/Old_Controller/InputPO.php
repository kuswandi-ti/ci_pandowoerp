<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InputPO extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_master_hdr_po   = 'ttrx_hdr_po_receive';
    public $tbl_master_dtl_po   = 'ttrx_dtl_po_receive';
    public $tmp_master_hdr_po   = 'ttmp_hdr_po_receive';
    public $tmp_dtl_po          = 'ttmp_dtl_po_receive';
    public $tbl_master_customer = 'tmst_customer';
    public $tbl_pajak           = 'tmst_persentase_pajak';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Form Receive PO";
        $this->data['page_content'] = "PO/form_po_receive";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/PO/form_po_receive.js"></script>';
        $this->data['PPn'] = $this->db->get_where($this->tbl_pajak, ['Name' => 'PPN'])->row();

        $this->load->view($this->layout, $this->data);
    }

    public function Store_Hdr_PO()
    {
        $RowCust = $this->db->get_where($this->tbl_master_customer, ['SysId' => $this->input->post('customer')])->row();
        $Doc_Number = $this->help->Gnrt_Identity_Number_PO("SO-" . $RowCust->Customer_Code . '-');
        $PPn = ($this->input->post('PPn') == NULL) ? 0 : floatval($this->input->post('PPn'));

        $this->db->trans_start();

        $this->db->insert($this->tmp_master_hdr_po, [
            'Doc_No_Internal' => $Doc_Number,
            'No_Po_Customer' => $this->input->post('po_number'),
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
            'created_by' => $this->session->userdata('impsys_initial')
        ]);

        $this->db->insert($this->tmp_dtl_po, [
            'Doc_No_Hdr'  => $Doc_Number,
            'Flag'       => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('impsys_initial')
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'Pembuatan Header PO gagal !',
            ]);
        } else {
            $this->db->trans_commit();
            return $this->help->Fn_resulting_response([
                'code'      => 200,
                'msg'       => 'Pembuatan Header PO berhasil !',
                'No_Doc'    => $Doc_Number,
            ]);
        }
    }

    public function Call_Form_Detail_Item_PO()
    {
        $Po_Internal = $this->input->get('No_Doc');

        $this->data['No_Doc'] =  $Po_Internal;
        $this->data['items'] = $this->db->get_where($this->tmp_dtl_po, ['Doc_No_Hdr' => $Po_Internal])->result();
        $this->load->view('PO/form_detail_item_po', $this->data);
    }

    public function add_row_tmp_detail_po()
    {
        $ValidateEmptyProduct = $this->db->get_where($this->tmp_dtl_po, [
            'Doc_No_Hdr' => $this->input->post('no_po_internal'),
            'Product_ID' => NULL
        ])->num_rows();
        if ($ValidateEmptyProduct > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => "Your last row, doesnt have product!"
            ]);
        }

        $this->db->trans_start();
        $this->db->insert($this->tmp_dtl_po, [
            'Flag' => $this->input->post('flag'),
            'Doc_No_Hdr' => $this->input->post('no_po_internal'),
            'created_by' => $this->session->userdata('impsys_initial'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal menambahkan detail order!"
            ];
        } else {
            $row = $this->db->get_where($this->tmp_dtl_po, [
                'Flag' => $this->input->post('flag'),
                'Doc_No_Hdr' => $this->input->post('no_po_internal'),
            ])->row();
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "SysId" => $row->SysId,
                "Qty_Order" => $row->Qty_Order,
                "msg" => "berhasil menambahkan detail order!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function add_row_tmp_detail_po_edit()
    {
        $ValidateEmptyProduct = $this->db->get_where($this->tbl_master_dtl_po, [
            'Doc_No_Hdr' => $this->input->post('no_po_internal'),
            'Product_ID' => NULL
        ])->num_rows();
        if ($ValidateEmptyProduct > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => "Your last row, doesnt have product!"
            ]);
        }

        $this->db->trans_start();

        $this->db->insert($this->tbl_master_dtl_po, [
            'Flag' => $this->input->post('flag'),
            'Doc_No_Hdr' => $this->input->post('no_po_internal'),
            'created_by' => $this->session->userdata('impsys_initial'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal menambahkan detail order!"
            ];
        } else {
            $row = $this->db->get_where($this->tbl_master_dtl_po, [
                'Flag' => $this->input->post('flag'),
                'Doc_No_Hdr' => $this->input->post('no_po_internal'),
            ])->row();
            $this->db->insert('thst_dtl_po_receive', [
                'Action'        => "ADD ROW",
                'SysId'         => $row->SysId,
                'Flag'          => $this->input->post('flag'),
                'Doc_No_Hdr'    => $this->input->post('no_po_internal'),
                'Product_ID'    => null,
                'Product_Code'  => null,
                'Product_Name'  => null,
                'Product_Price' => null,
                'Qty_Order'     => 0,
                'Uom'           => null,
                'created_at'    => $this->session->userdata('impsys_initial'),
                'created_by'    => date('Y-m-d H:i:s'),
                'do_at'         => $this->session->userdata('impsys_initial'),
                'do_by'         => date('Y-m-d H:i:s'),
            ]);
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "SysId" => $row->SysId,
                "Qty_Order" => $row->Qty_Order,
                "msg" => "berhasil menambahkan detail order!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function delete_row_detail_po()
    {
        $this->db->trans_start();

        $this->db->where('SysId', $this->input->post('sysid'));
        $this->db->delete($this->tmp_dtl_po);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "delete detail po failed!"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "success delete detail po!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function delete_row_detail_po_edit()
    {
        $RowPo = $this->db->get_where($this->tbl_master_dtl_po, ['SysId' => $this->input->post('sysid')])->row();
        $ValidateHavingDn = $this->db->get_where('qview_sj_detail_all', ['No_PO_Internal' => $RowPo->Doc_No_Hdr, 'Product_Code' => $RowPo->Product_Code]);
        if ($ValidateHavingDn->num_rows() > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'This item was having Delivery Note !'
            ]);
        }

        $this->db->trans_start();
        $this->db->insert('thst_dtl_po_receive', [
            'Action'        => "DELETE ROW",
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

        $this->db->where('SysId', $this->input->post('sysid'));
        $this->db->delete($this->tbl_master_dtl_po);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "delete detail po failed!"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "success delete detail po!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store_editable_detail_qty_order()
    {
        $value = floatval($this->input->post('value'));
        $sysid = $this->input->post('pk');

        $this->db->trans_start();

        $this->db->where('SysId', $sysid);
        $this->db->update($this->tmp_dtl_po, ['Qty_Order' => $value]);

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

    public function store_editable_detail_qty_order_edit()
    {
        $value = floatval($this->input->post('value'));
        $sysid = $this->input->post('pk');
        $RowPo = $this->db->get_where($this->tbl_master_dtl_po, ['SysId' => $sysid])->row();

        $ValidateQtyDn = $this->db->query("SELECT sum(qty) as total_qty_dn
        from qview_sj_detail_all
        where No_PO_Internal = '$RowPo->Doc_No_Hdr'
        and Product_Code = '$RowPo->Product_Code'")->row();

        if ($value < floatval($ValidateQtyDn->total_qty_dn)) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'Qty SO Must greather or equal qty DN !'
            ]);
        }

        $this->db->trans_start();
        $this->db->insert('thst_dtl_po_receive', [
            'Action'        => "UPDATE QTY",
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
            'do_at'    => $this->session->userdata('impsys_initial'),
            'do_by'    => date('Y-m-d H:i:s'),
        ]);

        $this->db->where('SysId', $sysid);
        $this->db->update($this->tbl_master_dtl_po, [
            'Qty_Order' => $value,
            'last_updated_at' => date('Y-m-d H:i:s'),
            'last_updated_by' => $this->session->userdata('impsys_initial')
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 500,
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
        $customer_id = $this->input->get('customer_id');
        $query = $this->db->query(
            "SELECT * from tmst_hdr_product where is_active = '1' and Customer_id = '$customer_id' and (Nama like '%$search%' or Kode like '%$search%' or Deskripsi like '%$search%')"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['sysid'];
                $list[$key]['text'] = $row['Nama'] . ' (' . $row['Kode'] . ')';
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

        $RowTmpPo = $this->db->get_where($this->tmp_dtl_po, ['SysId' => $SysId])->row();

        $ValidateUniqueProduct = $this->db->get_where($this->tmp_dtl_po, ['Doc_No_Hdr' => $RowTmpPo->Doc_No_Hdr, 'Product_ID' => $id_product, 'Flag !=' => $flag])->num_rows();
        if ($ValidateUniqueProduct > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'Product has been choosen on other row in this transaction !'
            ]);
        }

        $RowProduct = $this->db->get_where('tmst_hdr_product', ['sysid' => $id_product])->row();
        $this->db->trans_start();

        $this->db->where('SysId', $SysId);
        $this->db->update($this->tmp_dtl_po, [
            'Product_ID' => $id_product,
            'Product_Code' => $RowProduct->Kode,
            'Product_Name' => $RowProduct->Nama,
            'Product_Price' => $RowProduct->Price,
            'Uom' => $RowProduct->uom
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
                "msg" => "Success select product !",
                "uom" => $RowProduct->uom,
                "price" => $RowProduct->Price
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function Get_utility_detail_product_edit()
    {
        $id_product = $this->input->post('id_product');
        $SysId = $this->input->post('sysid');
        $flag = $this->input->post('flag');

        $RowPo = $this->db->get_where($this->tbl_master_dtl_po, ['SysId' => $SysId])->row();

        $ValidateUniqueProduct = $this->db->get_where($this->tbl_master_dtl_po, ['Doc_No_Hdr' => $RowPo->Doc_No_Hdr, 'Product_ID' => $id_product, 'Flag !=' => $flag])->num_rows();
        if ($ValidateUniqueProduct > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'Product has been choosen on other row in this transaction !'
            ]);
        }

        $ValidateHavingDn = $this->db->get_where('qview_sj_detail_all', ['No_PO_Internal' => $RowPo->Doc_No_Hdr, 'Product_Code' => $RowPo->Product_Code]);
        if ($ValidateHavingDn->num_rows() > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'This item was having Delivery Note !'
            ]);
        }

        $RowProduct = $this->db->get_where('tmst_hdr_product', ['sysid' => $id_product])->row();
        $this->db->trans_start();
        $this->db->insert('thst_dtl_po_receive', [
            'Action'        => "UPDATE PRODUCT",
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
            'do_at'    => $this->session->userdata('impsys_initial'),
            'do_by'    => date('Y-m-d H:i:s'),
        ]);

        $this->db->where('SysId', $SysId);
        $this->db->update($this->tbl_master_dtl_po, [
            'Product_ID' => $id_product,
            'Product_Code' => $RowProduct->Kode,
            'Product_Name' => $RowProduct->Nama,
            'Product_Price' => $RowProduct->Price,
            'Uom' => $RowProduct->uom,
            'last_updated_at' => date('Y-m-d H:i:s'),
            'last_updated_by' => $this->session->userdata('impsys_initial')
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 500,
                "msg" => "Select product detail po failed!"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Success select product !",
                "uom" => $RowProduct->uom,
                "price" => $RowProduct->Price
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function Store_Complete_Po()
    {
        $Po_Internal = $this->input->post('No_Po_Internal');

        $ZeroQty =  $this->db->get_where($this->tmp_dtl_po, ['Doc_No_Hdr' => $Po_Internal, 'Qty_Order' => 0])->num_rows();
        if ($ZeroQty > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'Quantity purchase order must greather than zero !'
            ]);
        }

        $DataHdr = $this->db->get_where($this->tmp_master_hdr_po, ['Doc_No_Internal' => $Po_Internal])->row();
        $DataDtls = $this->db->get_where($this->tmp_dtl_po, ['Doc_No_Hdr' => $Po_Internal])->result();

        $this->db->trans_start();

        $this->db->insert($this->tbl_master_hdr_po, [
            'Doc_No_Internal' => $DataHdr->Doc_No_Internal,
            'No_Po_Customer' => $DataHdr->No_Po_Customer,
            'ID_Customer' => $DataHdr->ID_Customer,
            'Customer_Code' => $DataHdr->Customer_Code,
            'Tgl_Terbit' => $DataHdr->Tgl_Terbit,
            'Term_Of_Payment' => $DataHdr->Term_Of_Payment,
            'Remark_TOP' => $DataHdr->Remark_TOP,
            'Term_Of_Delivery' => $DataHdr->Term_Of_Delivery,
            'ID_Address' => $DataHdr->ID_Address,
            'Customer_Address' =>  $DataHdr->Customer_Address,
            'Koresponden' =>  $DataHdr->Koresponden,
            'Note' =>  $DataHdr->Note,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('impsys_initial'),
        ]);

        foreach ($DataDtls as $DataDtl) {
            $this->db->insert($this->tbl_master_dtl_po, [
                'Flag' => $DataDtl->Flag,
                'Doc_No_Hdr' => $DataDtl->Doc_No_Hdr,
                'Product_ID' => $DataDtl->Product_ID,
                'Product_Code' => $DataDtl->Product_Code,
                'Product_Name' => $DataDtl->Product_Name,
                'Product_Price' => $DataDtl->Product_Price,
                'Qty_Order' => $DataDtl->Qty_Order,
                'Uom' => $DataDtl->Uom,
                'created_at' => $DataDtl->created_at,
                'created_by' => $DataDtl->created_by,
            ]);
        }

        $this->db->where('Doc_No_Internal', $Po_Internal);
        $this->db->delete($this->tmp_master_hdr_po);

        $this->db->where('Doc_No_Hdr', $Po_Internal);
        $this->db->delete($this->tmp_dtl_po);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Failed to register Purchase Order from customer!"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Purchase Order from customer successfully registered !",
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function Store_Complete_Po_Edit()
    {
        $Po_Internal = $this->input->post('No_Po_Internal');

        $ZeroQty =  $this->db->get_where($this->tbl_master_dtl_po, ['Doc_No_Hdr' => $Po_Internal, 'Qty_Order' => 0])->num_rows();
        if ($ZeroQty > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'Quantity purchase order must greather than zero !'
            ]);
        }

        $ProductNull =  $this->db->get_where($this->tbl_master_dtl_po, ['Doc_No_Hdr' => $Po_Internal, 'Product_Code' => NULL])->num_rows();
        if ($ProductNull > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg'  => 'You must select product in every row detail SO !'
            ]);
        }

        $RowBefore = $this->db->get_where($this->tbl_master_hdr_po, ['Doc_No_Internal' => $Po_Internal])->row();
        $DataBefore = [
            'Action'            => 'UPDATE DETAIL ITEM',
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

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Failed to register Purchase Order from customer!"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Purchase Order from customer successfully registered !",
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }
}
