<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BarcodeAlloc extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_lpb = 'ttrx_hdr_lpb_receive';
    public $tbl_dtl_lpb = 'ttrx_dtl_lpb_receive';
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Barcode Material Alokasi Produksi";
        $this->data['page_content'] = "Barcode/Index_AllocPrd";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/Barcode-script/AllocPrd.js"></script>';

        $this->data['products'] = $this->db->get_where($this->Tmst_CostCenter, ['Is_Active' => '1', 'cc_group' => 'PRD'])->result_array();

        $this->load->view($this->layout, $this->data);
    }

    public function update_alloc_prd()
    {
        $dateTime = date('Y-m-d H:i:s');
        $barcode = $this->input->post('barcode');
        $countRow = $this->db->get_where('ttrx_dtl_lpb_receive', ['no_lot' => $barcode])->num_rows();
        $countHstin = $this->db->get_where('thst_in_to_oven', ['lot' => $barcode])->num_rows();
        $countHstOut = $this->db->get_where('thst_out_of_oven', ['lot' => $barcode])->num_rows();
        $countAlloc = $this->db->get_where('thst_material_to_prd', ['lot' => $barcode])->num_rows();

        if ($countRow == 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Kode barcode tidak terdaftar dalam system!'
            ]);
        }
        if ($countHstin < 1) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode ' . $barcode . ' belum dinyatakan masuk oven, pilih barcode yang telah masuk oven !'
            ]);
        }
        if ($countHstOut < 1) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode ' . $barcode . ' belum dinyatakan keluar oven, pilih barcode yang telah masuk oven !'
            ]);
        }
        if ($countAlloc >= 1) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode ' . $barcode . ' sudah dinyatakan ter-alokasi ke produksi, pilih barcode yang telah keluar oven/gudang kering !'
            ]);
        }

        $this->db->trans_start();
        $this->db->where('no_lot', $barcode);
        $this->db->update('ttrx_dtl_lpb_receive', [
            'into_oven' => 3,
            'placement' => "PRODUKSI"
        ]);
        $this->db->insert('thst_material_to_prd', [
            "lot" => $barcode,
            "do_by" => $this->session->userdata('impsys_initial'),
            "do_time" => $dateTime,
            "remark_to_prd" => 'BARCODE',
            "sysid_product" => $this->input->post('product')
        ]);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = ['code' => 505, 'msg' => 'lot gagal di alokasikan ke produksi!'];
        } else {
            $this->db->trans_commit();
            $response = ['code' => 200];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function preview_detail_lot()
    {
        $barcode = $this->input->get('barcode');

        $response = $this->db->query("SELECT d.nama, a.lpb_hdr, a.no_lot, c.kode, a.qty, b.grader, a.into_oven, e.status_kayu, g.nama as nama_oven,
        a.qty * ((c.tebal * c.lebar * c.panjang) / 1000000) as kubikasi, 
        DATE_FORMAT(f.do_time, '%Y-%m-%d %H:%i') as time_in,
        DATE_FORMAT(h.do_time, '%Y-%m-%d %H:%i') as time_out,
        CONCAT(FLOOR(HOUR(TIMEDIFF(f.do_time, h.do_time)) / 24), ' hari,',MOD(HOUR(TIMEDIFF(f.do_time, h.do_time)), 24), ' jam') as timer
        from ttrx_dtl_lpb_receive a
        join ttrx_hdr_lpb_receive b on a.lpb_hdr = b.lpb
        join tmst_material_kayu c on a.sysid_material = c.sysid
        join tmst_supplier_material d on b.id_supplier = d.sysid
        join tmst_status_lot e on a.into_oven = e.kode
        left join thst_in_to_oven f on a.no_lot = f.lot
        left join tmst_identity_oven g on f.oven = g.sysid
        left join thst_out_of_oven h on a.no_lot = h.lot
        where a.no_lot = '$barcode'
        limit 1")->row();

        if (empty($response)) {
            return $this->help->Fn_resulting_response(['code' => 505]);
        } else {
            return $this->help->Fn_resulting_response([
                "code" => 200,
                "supplier" => $response->nama,
                "lpb" => $response->lpb_hdr,
                "lot" => $response->no_lot,
                "material" => $response->kode,
                "qty" => $response->qty,
                "grader" => $response->grader,
                "kubikasi" => floatval($response->kubikasi),
                "time_in" => $response->time_in,
                "time_out" => $response->time_out,
                "timer" => $response->timer,
                "oven" => $response->nama_oven,
                "status" => '<button class="btn btn-info btn-flat"><i class="fas fa-map-marker-alt blink_me"></i> ' . $response->status_kayu . '</button>',
            ]);
        }
    }
}
