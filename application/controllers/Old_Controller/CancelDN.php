<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CancelDN extends CI_Controller
{
    public $layout = 'layout';
    public $table_all_dn = 'qview_sj_detail_all';
    public $tbl_hdr_dn = 'ttrx_hdr_delivery_note';
    public $tbl_dtl_dn = 'ttrx_dtl_delivery_note';
    public $dtl_loading = 'ttrx_dtl_loading';
    public $hdr_loading = 'ttrx_hdr_loading';
    public $tbl_barcode = 'thst_print_barcode_product';
    public $tbl_hdr_product = 'tmst_hdr_product';
    public $trx_finish_good = 'ttrx_finish_good';
    public $tmst_finish_good = 'tbl_finish_good';
    public $history_wasting_barcode = 'thst_wasting_barcode';
    public $qview_detail_loading = 'qview_detail_hdr_loading';
    public $data;

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Cancel/Swap Delivery Note";
        $this->data['page_content'] = "DN/cancel_dn";
        $this->data['script_page'] = '<script src="' . base_url() . 'assets/DN/Cancel_DN.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function Check_Valid_DN()
    {
        $dn_number = $this->input->post('dn_number');

        $Hdr = $this->db->get_where($this->tbl_hdr_dn, ['DN_Number' => $dn_number]);

        if ($Hdr->num_rows() == 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Delivery Note Number not found !',
            ]);
        }

        return $this->help->Fn_resulting_response([
            'code' => 200,
            'msg' => "Delivery Note number is valid !",
        ]);
    }

    public function select_product_dn()
    {
        $search = $this->input->get('search');
        $dn_number = $this->input->get('dn_number');
        $query = $this->db->query(
            "SELECT  * from $this->tbl_dtl_dn where DN_Number = '$dn_number' and (Product_Code like '%$search%' AND Product_Name like '%$search%')"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['SysId_Product'];
                $list[$key]['text'] = $row['Product_Name'] . ' (' . $row['Product_Code'] . ')';
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function Check_Loading_Number()
    {
        $dn_number = $this->input->post('dn_number');
        $product_id = $this->input->post('product');

        $ValidateDtlDn = $this->db->get_where($this->tbl_dtl_dn, [
            'DN_Number' => $dn_number,
            'SysId_Product' => $product_id,
        ])->row();

        if (empty($ValidateDtlDn->No_Loading)) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Item ini belum memiliki loading number, Harap periksa DN Outstanding !',
            ]);
        } else {
            return $this->help->Fn_resulting_response([
                'code' => 200,
                'msg' => 'Harap tunggu, system akan memunculkan list barcode delivery note !',
                'loading_number' => $ValidateDtlDn->No_Loading,
            ]);
        }
    }

    public function Store_CancelOrSwap_Item_Loading()
    {
        $barcode = $this->input->post('barcode');
        $subs_barcode = $this->input->post('subs');
        $dn_number = $this->input->post('no_dn');
        $product_id = $this->input->post('product_id');
        $no_loading = $this->input->post('no_loading');

        $this->db->trans_start();
        for ($i = 0; $i < count($barcode); $i++) {
            if ($this->input->post('action_' . $barcode[$i]) == 'RIJECT') {
                // ttrx_dtl_loading, remove barcode dari detail loading
                $this->db->delete($this->dtl_loading, ['Barcode_Value' => $barcode[$i]]);

                // ttrx_hdr_loading, update qty loading -1
                $this->db->query("UPDATE $this->hdr_loading SET Qty_Loading = Qty_Loading - 1 WHERE No_loading = '$no_loading'");

                // thst_print_barcode_product, set is wasting => 1
                $this->db->update($this->tbl_barcode, ['IS_WASTING' => 1], ['Barcode_Value' => $barcode[$i]]);

                // insert ke thst_wasting_barcode
                $this->db->insert($this->history_wasting_barcode, [
                    'barcode' => $barcode[$i],
                    'do_by' => $this->session->userdata('impsys_initial'),
                    'do_at' => date('Y-m-d H:i:s'),
                    'remark' => "ACTION RIJECT AT SWAP/CANCEL ITEM DN",
                    'info' => "REJECT DN ITEM TO TRASH"
                ]);

                // update qty detail dn
                $this->db->query("UPDATE $this->tbl_dtl_dn SET Qty = Qty - 1 WHERE DN_Number = '$dn_number' AND SysId_Product = $product_id");
            } else if ($this->input->post('action_' . $barcode[$i]) == 'SR') {
                // ---- langkah 1
                // check subs valid barcode
                $ValidateSubsBcd = $this->db->get_where($this->tbl_barcode, ['Barcode_Value' => $subs_barcode[$i]])->row();
                if (empty($ValidateSubsBcd)) {
                    return $this->help->Fn_resulting_response([
                        'code' => 505,
                        'msg' => "Transaksi gagal di lakukan, No. Barcode : $subs_barcode[$i], Tidak terdaftar dalam system ! pada baris " . $i + 1,
                    ]);
                }

                // check subs wasting atau tidak
                if ($ValidateSubsBcd->IS_WASTING == 1) {
                    return $this->help->Fn_resulting_response([
                        'code' => 505,
                        'msg' => "Transaksi gagal di lakukan, No. Barcode : $subs_barcode[$i], Telah dinyatakan Wasting ! pada baris " . $i + 1,
                    ]);
                }

                // check subs sesuai ukuran atau tidak
                $Row_Loading = $this->db->get_where($this->hdr_loading, ['No_loading' => $no_loading])->row();
                $RowProductInBarcode = $this->db->get_where($this->tbl_hdr_product, ['sysid' => $ValidateSubsBcd->Product_id])->row();
                $RowProductInLoading = $this->db->get_where($this->tbl_hdr_product, ['sysid' => $Row_Loading->Product_ID])->row();
                if ($RowProductInBarcode->Tebal != $RowProductInLoading->Tebal || $RowProductInBarcode->Lebar != $RowProductInLoading->Lebar || $RowProductInBarcode->Panjang != $RowProductInLoading->Panjang) {
                    return $this->help->Fn_resulting_response([
                        "code" => 500,
                        'msg'  => 'Ukuran Product tidak match antara ukuran Delivery Note dan ukuran item pengganti ! pada baris ' . $i + 1
                    ]);
                }

                // check subs sudah masuk ke detail loading lain
                $QryLoading = $this->db->get_where($this->dtl_loading, ['Barcode_Value' => $subs_barcode[$i]]);
                if ($QryLoading->num_rows() > 0) {
                    return $this->help->Fn_resulting_response([
                        "code" => 500,
                        'msg'  => 'Product Pengganti telah terdaftar pada Nomor Loading lain ! pada baris ' . $i + 1
                    ]);
                }

                // insert subs ke tbl-detail loading
                $this->db->insert($this->dtl_loading, [
                    'No_Loading_Hdr' => $no_loading,
                    'Barcode_Value' => $subs_barcode[$i],
                    'do_by' => $this->session->userdata('impsys_initial'),
                    'do_at' => date('Y-m-d H:i:s'),
                ]);

                // catat ke ttrx_finish_good (-)
                $fg = $this->db->get_where($this->tmst_finish_good, ['Product_Code' => $ValidateSubsBcd->Product_Code])->row();
                $this->db->insert($this->trx_finish_good, [
                    'ProductCode' => $ValidateSubsBcd->Product_Code,
                    'old_stok' => floatval($fg->Qty),
                    'qty_trans' => 1,
                    'aritmatics' => '-',
                    'new_stok' => floatval($fg->Qty) - 1,
                    'remark' => "SHIPPING SWAP STOK : SWAP/RIJECT(SR) TO TRASH",
                    'do_by' => $this->session->userdata('impsys_initial'),
                    'do_at' => date('Y-m-d H:i:s'),
                ]);

                // ---- langkah 2
                // ttrx_dtl_loading, remove barcode dari detail loading
                $this->db->delete($this->dtl_loading, ['Barcode_Value' => $barcode[$i]]);

                // thst_print_barcode_product, set is wasting => 1
                $this->db->update($this->tbl_barcode, ['IS_WASTING' => 1], ['Barcode_Value' => $barcode[$i]]);

                // insert ke thst_wasting_barcode
                $this->db->insert($this->history_wasting_barcode, [
                    'barcode' => $barcode[$i],
                    'do_by' => $this->session->userdata('impsys_initial'),
                    'do_at' => date('Y-m-d H:i:s'),
                    'remark' => "ACTION SWAP/RIJECT(SR) AT SWAP/CANCEL ITEM DN",
                    'info' => "SWAP/RIJECT(SR) DN ITEM TO TRASH"
                ]);
            } else if ($this->input->post('action_' . $barcode[$i]) == 'SS') {
                // ---- langkah 1
                // check subs valid barcode
                $ValidateSubsBcd = $this->db->get_where($this->tbl_barcode, ['Barcode_Value' => $subs_barcode[$i]])->row();
                if (empty($ValidateSubsBcd)) {
                    return $this->help->Fn_resulting_response([
                        'code' => 505,
                        'msg' => "Transaksi gagal di lakukan, No. Barcode : $subs_barcode[$i], Tidak terdaftar dalam system ! pada baris " . $i + 1,
                    ]);
                }

                // check subs wasting atau tidak
                if ($ValidateSubsBcd->IS_WASTING == 1) {
                    return $this->help->Fn_resulting_response([
                        'code' => 505,
                        'msg' => "Transaksi gagal di lakukan, No. Barcode : $subs_barcode[$i], Telah dinyatakan Wasting ! pada baris " . $i + 1,
                    ]);
                }

                // check subs sesuai ukuran atau tidak
                $Row_Loading = $this->db->get_where($this->hdr_loading, ['No_loading' => $no_loading])->row();
                $RowProductInBarcode = $this->db->get_where($this->tbl_hdr_product, ['sysid' => $ValidateSubsBcd->Product_id])->row();
                $RowProductInLoading = $this->db->get_where($this->tbl_hdr_product, ['sysid' => $Row_Loading->Product_ID])->row();
                if ($RowProductInBarcode->Tebal != $RowProductInLoading->Tebal || $RowProductInBarcode->Lebar != $RowProductInLoading->Lebar || $RowProductInBarcode->Panjang != $RowProductInLoading->Panjang) {
                    return $this->help->Fn_resulting_response([
                        "code" => 500,
                        'msg'  => 'Ukuran Product tidak match antara ukuran Delivery Note dan ukuran item pengganti ! pada baris ' . $i + 1
                    ]);
                }

                // check subs sudah masuk ke detail loading lain
                $QryLoading = $this->db->get_where($this->dtl_loading, ['Barcode_Value' => $subs_barcode[$i]]);
                if ($QryLoading->num_rows() > 0) {
                    return $this->help->Fn_resulting_response([
                        "code" => 500,
                        'msg'  => 'Product Pengganti telah terdaftar pada Nomor Loading lain ! pada baris ' . $i + 1
                    ]);
                }

                // insert subs ke tbl-detail loading
                $this->db->insert($this->dtl_loading, [
                    'No_Loading_Hdr' => $no_loading,
                    'Barcode_Value' => $subs_barcode[$i],
                    'do_by' => $this->session->userdata('impsys_initial'),
                    'do_at' => date('Y-m-d H:i:s'),
                ]);

                // catat ke ttrx_finish_good (-)
                $fg = $this->db->get_where($this->tmst_finish_good, ['Product_Code' => $ValidateSubsBcd->Product_Code])->row();
                $this->db->insert($this->trx_finish_good, [
                    'ProductCode' => $ValidateSubsBcd->Product_Code,
                    'old_stok' => floatval($fg->Qty),
                    'qty_trans' => 1,
                    'aritmatics' => '-',
                    'new_stok' => floatval($fg->Qty) - 1,
                    'remark' => "SHIPPING SWAP STOK : SWAP/RIJECT(SR) SWAP ITEM",
                    'do_by' => $this->session->userdata('impsys_initial'),
                    'do_at' => date('Y-m-d H:i:s'),
                ]);

                // --- langkah 2
                // ttrx_dtl_loading, remove barcode dari detail loading
                $this->db->delete($this->dtl_loading, ['Barcode_Value' => $barcode[$i]]);

                // tbl_finish_good + 1
                $ValidateExistingBcd = $this->db->get_where($this->tbl_barcode, ['Barcode_Value' => $barcode[$i]])->row();
                $fg_existing = $this->db->get_where($this->tmst_finish_good, ['Product_Code' => $ValidateExistingBcd->Product_Code])->row();
                $this->db->insert($this->trx_finish_good, [
                    'ProductCode' => $ValidateExistingBcd->Product_Code,
                    'old_stok' => floatval($fg_existing->Qty),
                    'qty_trans' => 1,
                    'aritmatics' => '+',
                    'new_stok' => floatval($fg_existing->Qty) + 1,
                    'remark' => "SHIPPING SWAP STOK : SWAP/RIJECT(SR) BACK TO STOK",
                    'do_by' => $this->session->userdata('impsys_initial'),
                    'do_at' => date('Y-m-d H:i:s'),
                ]);

                // catat lagi ke ttrx_finish_good
                // catat ke ttrx_finish_good (+)
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
                    'msg' => "Successfully update loading data, delivery note $dn_number !",
                ]);
            }
        }
    }
}
