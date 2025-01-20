<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PrintBarcodeProduct extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_customer = 'tmst_customer';
    public $tbl_hdr_product = 'tmst_hdr_product';
    public $tbl_dtl_product = 'tmst_dtl_product';
    public $tbl_barcode = 'thst_print_barcode_product';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Form Print Barcode Product";
        $this->data['page_content'] = "TagProduct/Form_Print_Barcode_Product";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TagProduct/Form_barcode_product.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function Store_Barcode_Product()
    {
        $qty = $this->input->post('qty');
        $id_customer = $this->input->post('customer');
        $id_product = $this->input->post('product');

        $row_customer = $this->db->get_where($this->tbl_customer, ['SysId' => $id_customer])->row();
        $row_product = $this->db->get_where($this->tbl_hdr_product, ['SysId' => $id_product])->row();

        $FlagGrouping = $this->session->userdata('impsys_initial') . '|' . $qty . '|' . time();

        $this->db->trans_start();
        for ($i = 1; $i <= $qty; $i++) {
            $Barcode_Number = $this->help->Counter_Product_Number($row_customer->Customer_Code);
            $Barcode_Val = 'IMP-' . $row_customer->Customer_Code . '-' . date('ym') . '-' . $Barcode_Number;
            $this->db->insert($this->tbl_barcode, [
                'FlagGrouping' => $FlagGrouping,
                'Counter_Print' => $i,
                'Product_id' => $row_product->sysid,
                'Product_Code' => $row_product->Kode,
                'Product_Name' => $row_product->Nama,
                'Customer_id' => $row_customer->SysId,
                'Customer_Code' => $row_customer->Customer_Code,
                'Customer_Name' => $row_customer->Customer_Name,
                'Checker_Rakit' => $this->input->post('checker_rakit'),
                'Leader_Rakit' => $this->input->post('leader_rakit'),
                'Date_Prd' => $this->input->post('tgl_prd'),
                'Barcode_Number' =>  $row_customer->Customer_Code . '-' . date('ym') . '-' . $Barcode_Number,
                'Barcode_Value' => $Barcode_Val,
                'IS_WASTING' => 0,
                'Created_at' => date('Y-m-d H:i:s'),
                'Created_by' => $this->session->userdata('impsys_initial'),
            ]);
        }

        $fg = $this->db->get_where('tbl_finish_good', ['Product_Code' => $row_product->Kode]);

        if ($fg->num_rows() == 0) {
            $old_qty = 0;
        } else {
            $row_fg = $fg->row();
            $old_qty = floatval($row_fg->Qty);
        }
        $this->db->insert('ttrx_finish_good', [
            'ProductCode'   => $row_product->Kode,
            'old_stok'      => $old_qty,
            'qty_trans'     => $qty,
            'aritmatics'    => '+',
            'new_stok'      => $old_qty + floatval($qty),
            'remark'        => 'GENERATE BARCODE',
            'do_by'         => $this->session->userdata('impsys_initial')
        ]);


        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Transaksi generate barcode gagal !',
            ]);
        } else {
            $this->db->trans_commit();
            return $this->help->Fn_resulting_response([
                'code' => 200,
                'msg' => 'Transaksi generate barcode berhasil !',
                'flag' => $FlagGrouping,
            ]);
        }
    }

    public function print($flag)
    {
        $flag_group = str_replace('%7C', '|', $flag);
        $this->data['Barcodes'] = $this->db->get_where($this->tbl_barcode, ['FlagGrouping' => $flag_group, 'IS_WASTING' => 0])->result();
        return $this->load->view('Print/tempelan_product', $this->data);
    }

    public function Print_Ulang($sysid)
    {
        $this->data['Barcodes'] = $this->db->get_where($this->tbl_barcode, ['SysId' => $sysid, 'IS_WASTING' => 0])->result();
        return $this->load->view('Print/tempelan_product', $this->data);
    }





    // --------------------------- utility form 

    public function select_customer()
    {
        $search = $this->input->get('search');
        $query = $this->db->query(
            "SELECT * from tmst_customer where is_active = '1' and Customer_Name like '%$search%'"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['SysId'];
                $list[$key]['text'] = $row['Customer_Name'] . ' (' . $row['Customer_Code'] . ')';
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function select_product($id_customer)
    {
        $search = $this->input->get('search');
        $query = $this->db->query(
            "SELECT sysid, Customer_id, Nama, Kode from tmst_hdr_product where Customer_id = $id_customer and is_active = 1 and Nama like '%$search%'"
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

    public function select_leader_rakit()
    {
        $search = $this->input->get('search');
        $query = $this->db->query(
            "SELECT a.initial, b.nama 
            from tmst_leader_rakit a
            JOIN tmst_karyawan b on a.nik = b.nik
            WHERE a.active = 1 
            and b.is_active = 1 
            and b.nama like '%$search%' order by b.nama"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['initial'];
                $list[$key]['text'] = $row['nama'];
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function select_checker_rakit()
    {
        $search = $this->input->get('search');
        $query = $this->db->query(
            "SELECT a.initial, b.nama 
            from tmst_checker_rakit a
            JOIN tmst_karyawan b on a.nik = b.nik
            WHERE a.active = 1 
            and b.is_active = 1 
            and b.nama like '%$search%' order by b.nama"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['initial'];
                $list[$key]['text'] = $row['nama'];
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }
}
