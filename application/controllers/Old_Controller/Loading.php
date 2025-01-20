<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loading extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_loading = 'ttrx_hdr_loading';
    public $tbl_dtl_loading = 'ttrx_dtl_loading';
    public $tmp_dtl_loading = 'ttmp_dtl_loading';
    public $tbl_hdr_product = 'tmst_hdr_product';
    public $tbl_barcode = 'thst_print_barcode_product';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "List Pekerjaan Loading";
        $this->data['page_content'] = "Loading/list_loading";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/Loading/list_loading.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function loading_product($no_loading)
    {
        $this->data['page_title'] = "Loading Shipping";
        $this->data['page_content'] = "Loading/loading";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/Loading/loading.js"></script>';

        $this->data['loading'] = $this->db->query("SELECT a.SysId ,b.Customer_Name, b.Customer_Code, c.Nama, c.Kode, a.No_loading, a.Qty_Loading, a.STATUS, a.Created_at, a.Created_by, a.Silang_Product
        FROM ttrx_hdr_loading a
        join tmst_customer b on a.Customer_ID = b.SysId
        join tmst_hdr_product c on a.Product_ID = c.sysid
        WHERE a.No_loading = '$no_loading' AND a.STATUS = 'LOADING'
        LIMIT 1
        ")->row();


        $this->load->view($this->layout, $this->data);
    }

    public function preview_detail_data_barcode()
    {
        $barcode = $this->input->post('barcode');

        $this->data['barcode'] = $this->db->get_where($this->tbl_barcode, ['Barcode_Value' => $barcode])->row();

        $this->load->view('general-modal/m_detail_barcode_prd', $this->data);
    }

    public function Store_Barcode_Loading()
    {
        $barcode = $this->input->post('barcode');
        $no_loading = $this->input->post('no_loading');
        $silang_product = $this->input->post('silang_product');

        $QryBcd = $this->db->get_where($this->tbl_barcode, ['Barcode_Value' => $barcode]);
        $QryLDG = $this->db->get_where($this->tbl_hdr_loading, ['No_loading' => $no_loading]);

        if ($QryBcd->num_rows() == 0) {
            return $this->help->Fn_resulting_response([
                "code" => 500,
                'msg' => 'Barcode tidak terdaftar dalam system !'
            ]);
        }
        $Row_Loading = $QryLDG->row();
        $Row_Barcode = $QryBcd->row();

        if ($silang_product == 'TRUE') {
            $RowProductInBarcode = $this->db->get_where($this->tbl_hdr_product, ['sysid' => $Row_Barcode->Product_id])->row();
            $RowProductInLoading = $this->db->get_where($this->tbl_hdr_product, ['sysid' => $Row_Loading->Product_ID])->row();

            if ($RowProductInBarcode->Tebal != $RowProductInLoading->Tebal || $RowProductInBarcode->Lebar != $RowProductInLoading->Lebar || $RowProductInBarcode->Panjang != $RowProductInLoading->Panjang) {
                return $this->help->Fn_resulting_response([
                    "code" => 500,
                    'msg'  => 'Loading gagal, Silang Product Beda ukuran tidak diperbolehkan !'
                ]);
            }
        } else {
            if ($Row_Barcode->Product_id != $Row_Loading->Product_ID) {
                return $this->help->Fn_resulting_response([
                    "code" => 500,
                    'msg'  => 'Loading gagal, Jenis product tidak sesuai permintaan !'
                ]);
            }
        }

        $QryTmp = $this->db->get_where($this->tmp_dtl_loading, ['No_Loading_Hdr' => $no_loading]);
        if ($QryTmp->num_rows() >= $Row_Loading->Qty_Loading) {
            return $this->help->Fn_resulting_response([
                "code" => 500,
                'msg'  => 'Kebutuhan Loading ' . $Row_Loading->Qty_Loading . ' pcs, tidak dapat melebihi Quantity permintaan !'
            ]);
        }

        $CheckWasScanned = $this->db->get_where($this->tmp_dtl_loading, ['Barcode_Value' => $barcode])->num_rows();
        if ($CheckWasScanned > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 500,
                'msg'  => 'Barcode telah di scan !'
            ]);
        }
        $CheckTrans = $this->db->get_where($this->tbl_dtl_loading, ['Barcode_Value' => $barcode])->num_rows();
        if ($CheckTrans > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 500,
                'msg'  => 'Barcode telah di scan oleh no. loading lain !'
            ]);
        }

        $this->db->trans_start();

        $this->db->insert($this->tmp_dtl_loading, [
            'No_Loading_Hdr'    => $no_loading,
            'Barcode_Value'     => $barcode,
            'do_by'             => $this->session->userdata('impsys_initial')
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Scan Loading gagal, terjadi kesalahan system !',
            ]);
        } else {
            $this->db->trans_commit();
            return $this->help->Fn_resulting_response([
                'code' => 200,
                'msg' => 'Scan loading berhasil !',
            ]);
        }
    }

    public function Selesai_Loading()
    {
        $no_loading = $this->input->post('no_loading');
        $silang_product = $this->input->post('silang_product');
        $Row_Loading = $this->db->get_where($this->tbl_hdr_loading, ['No_loading' => $no_loading])->row();

        $QryTmp = $this->db->get_where($this->tmp_dtl_loading, ['No_Loading_Hdr' => $no_loading]);
        if ($QryTmp->num_rows() != intval($Row_Loading->Qty_Loading)) {
            return $this->help->Fn_resulting_response([
                "code" => 500,
                'msg'  => 'Kebutuhan Loading ' . $Row_Loading->Qty_Loading . ' pcs, Harap sesuaikan !'
            ]);
        }

        $Hdr_Product = $this->db->get_where($this->tbl_hdr_product, ['sysid' => $Row_Loading->Product_ID])->row();
        $fg = $this->db->get_where('tbl_finish_good', ['Product_Code' => $Hdr_Product->Kode])->row();

        $this->db->trans_start();

        foreach ($QryTmp->result() as $li) {
            $this->db->insert($this->tbl_dtl_loading, [
                'No_Loading_Hdr'    => $li->No_Loading_Hdr,
                'Barcode_Value'     => $li->Barcode_Value,
                'do_at'             => $li->do_at,
                'do_by'             => $li->do_by
            ]);
        }

        $this->db->where('No_loading', $no_loading);
        $this->db->update($this->tbl_hdr_loading, [
            'STATUS' => 'SELESAI',
            'Selesai_at' => date('Y-m-d H:i:s')
        ]);

        if ($silang_product == 'TRUE') {
            $Rekap_Loading = $this->db->query("SELECT count(a.SysId) as qty_per_product,c.Qty as qty_fg, a.Product_id , a.Product_Code , a.Product_Name 
            FROM impsys.thst_print_barcode_product a
            join ttmp_dtl_loading b on a.Barcode_Value = b.Barcode_Value
            join tbl_finish_good c on a.Product_Code = c.Product_Code
            where b.No_Loading_Hdr = '$no_loading'
            group by a.Product_id")->result();

            foreach ($Rekap_Loading as $li) {
                $this->db->insert('ttrx_finish_good', [
                    'ProductCode'   => $li->Product_Code,
                    'old_stok'      => floatval($li->qty_fg),
                    'qty_trans'     => floatval($li->qty_per_product),
                    'aritmatics'    => '-',
                    'new_stok'      => floatval($li->qty_fg) - floatval($li->qty_per_product),
                    'remark'        => 'SHIPPING',
                    'do_by'         => $this->session->userdata('impsys_initial')
                ]);
            }

            $List_Tmp_Loading = $this->db->query("SELECT a.SysId, d.Product_ID, f.Nama, f.Kode, d.Customer_ID, e.Customer_Code, e.Customer_Name, b.No_Loading_Hdr, a.Product_id, a.Product_Code, a.Product_Name, a.Barcode_Value
            FROM impsys.thst_print_barcode_product a
            join ttmp_dtl_loading b on a.Barcode_Value = b.Barcode_Value
            join tbl_finish_good c on a.Product_Code = c.Product_Code
            join ttrx_hdr_loading d on b.No_Loading_Hdr = d.No_loading 
            join tmst_customer e on d.Customer_ID = e.SysId 
            join tmst_hdr_product f on d.Product_ID = f.sysid  
            where b.No_Loading_Hdr = '$no_loading'
            and a.Product_id <> $Row_Loading->Product_ID")->result();

            foreach ($List_Tmp_Loading as $ul) {
                $this->db->where('Barcode_Value', $ul->Barcode_Value);
                $this->db->update($this->tbl_barcode, [
                    'Product_id' => $ul->Product_ID,
                    'Product_Code' => $ul->Kode,
                    'Product_Name' => $ul->Nama,
                    'Customer_id' => $ul->Customer_ID,
                    'Customer_Code' => $ul->Customer_Code,
                    'Customer_Name' => $ul->Customer_Name,
                    'Last_updated_at' => date('Y-m-d H:i:s'),
                    'Last_updated_by' => $this->session->userdata('impsys_initial')
                ]);
            }
        } else {
            $this->db->insert('ttrx_finish_good', [
                'ProductCode'   => $fg->Product_Code,
                'old_stok'      => floatval($fg->Qty),
                'qty_trans'     => floatval($Row_Loading->Qty_Loading),
                'aritmatics'    => '-',
                'new_stok'      => floatval($fg->Qty) - floatval($Row_Loading->Qty_Loading),
                'remark'        => 'SHIPPING',
                'do_by'         => $this->session->userdata('impsys_initial')
            ]);
        }

        $this->db->where('No_Loading_Hdr', $no_loading);
        $this->db->delete($this->tmp_dtl_loading);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Loading gagal dinyatakan selesai !',
            ]);
        } else {
            $this->db->trans_commit();
            return $this->help->Fn_resulting_response([
                'code' => 200,
                'msg' => 'Loading berhasil dinyatakan selesai !',
            ]);
        }
    }

    public function DT_ttmp_loading()
    {
        $no_loading = $this->input->post('no_loading');

        $requestData = $_REQUEST;
        $columns = array(
            0 => 'SysId',
            1 => 'Barcode_Value',
            2 => 'do_at',
            3 => 'do_by',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT * FROM ttmp_dtl_loading where No_Loading_Hdr = '$no_loading' ";

        $totalData = $this->db->query($sql)->num_rows();
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (Barcode_Value LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR do_by LIKE '%" . $requestData['search']['value'] . "%'";
            $sql .= " OR do_at LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        $totalFiltered = $this->db->query($sql)->num_rows();
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId'] = $row["SysId"];
            $nestedData['Barcode_Value'] = $row["Barcode_Value"];
            $nestedData['do_at'] = $row["do_at"];
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

    public function DT_On_Going_Loading()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.SysId',
            1 => 'a.No_loading',
            2 => 'b.Customer_Name',
            3 => 'c.Nama',
            4 => 'a.Qty_Loading',
            5 => 'a.Created_by',
            6 => 'a.STATUS',
            7 => 'a.STATUS',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT a.SysId, a.No_loading, a.Qty_Loading, a.STATUS, a.Created_by, b.Customer_Name, c.Nama
        FROM ttrx_hdr_loading a
        JOIN tmst_customer b on a.Customer_ID = b.SysId
        JOIN tmst_hdr_product c on a.Product_ID = c.sysid
        WHERE a.STATUS = 'LOADING' ";

        $totalData = $this->db->query($sql)->num_rows();
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.No_loading LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Qty_Loading LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.Nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR  b.Customer_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.Created_by LIKE '%" . $requestData['search']['value'] . "%') ";
        }
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        $totalFiltered = $this->db->query($sql)->num_rows();
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId'] = $row["SysId"];
            $nestedData['No_loading'] = $row["No_loading"];
            $nestedData['Customer_Name'] = $row["Customer_Name"];
            $nestedData['Nama'] = $row["Nama"];
            $nestedData['Qty_Loading'] = $row["Qty_Loading"];
            $nestedData['Created_by'] = $row["Created_by"];
            $nestedData['STATUS'] = $row["STATUS"];

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

    public function Delete_TTmp_Barcode()
    {
        $SysId = $this->input->post('SysId');

        $this->db->trans_start();

        // -------------------------------
        $this->db->where('SysId', $SysId);
        $this->db->delete($this->tmp_dtl_loading);
        // -------------------------------
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code" => 505,
                "msg" => "gagal delete data Barcode!"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil delete data Barcode!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function delete_loading()
    {
        $sysid = $this->input->post('sysid');

        $Hdr_Loading = $this->db->get_where($this->tbl_hdr_loading, ['SysId' => $sysid, 'STATUS' => 'LOADING']);
        $Row_Hdr_Loading = $Hdr_Loading->row();

        // var_dump($Row_Hdr_Loading);
        // die;
        // $Tmp_Loading = $this->db->get_where($this->tmp_dtl_loading, ['No_Loading_Hdr' => $Row_Hdr_Loading->No_Loading]);


        $this->db->trans_start();
        // -------------------------------

        $this->db->where('No_Loading_Hdr', $Row_Hdr_Loading->No_loading);
        $this->db->delete($this->tmp_dtl_loading);

        $this->db->where('SysId', $sysid);
        $this->db->delete($this->tbl_hdr_loading);

        // -------------------------------
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code" => 505,
                "msg" => "gagal delete data LPB!"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil delete data LPB!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function edit_qty_loading()
    {
        $sysid = $this->input->post('pk');
        $value = $this->input->post('value');

        $this->db->trans_start();
        $data = ['Qty_Loading' => $value];
        $this->db->where('SysId', $sysid);
        $this->db->update('ttrx_hdr_loading', $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal update qty loading !"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil update qty loading"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }
}
