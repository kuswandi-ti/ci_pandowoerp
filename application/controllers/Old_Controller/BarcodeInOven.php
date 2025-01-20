<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BarcodeInOven extends CI_Controller
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
        $this->data['page_title'] = "Barcode Material Masuk Oven";
        $this->data['page_content'] = "Barcode/Index_InToOven";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/Barcode-script/InToOven.js"></script>';
        $this->data['ovens'] = $this->db->get_where('tmst_identity_oven', ['is_active' => '1'])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function Insert_into_oven()
    {
        $dateTime = date('Y-m-d H:i:s');
        $barcode = $this->input->get('barcode');
        $oven = $this->input->get('oven');
        $countRow = $this->db->get_where('ttrx_dtl_lpb_receive', ['no_lot' => $barcode])->num_rows();
        $countHst = $this->db->get_where('thst_in_to_oven', ['lot' => $barcode])->num_rows();

        if ($countRow == 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Kode barcode tidak terdaftar dalam system!'
            ]);
        }
        if ($countHst > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode telah dinyatakan masuk oven, pilih barcode lain!'
            ]);
        }

        $this->db->trans_start();
        $this->db->where('no_lot', $barcode);
        $this->db->update('ttrx_dtl_lpb_receive', [
            'into_oven' => 1,
            'placement' => 'OVEN'
        ]);
        $this->db->insert('thst_in_to_oven', [
            "lot" => $barcode,
            "oven" => $oven,
            "do_by" => $this->session->userdata('impsys_initial'),
            "do_time" => $dateTime,
            'remark_into_oven' => 'BARCODE',
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = ['code' => 505, 'msg' => 'lot gagal dinyatakan masuk oven!'];
        } else {
            $response = ['code' => 200];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function preview_detail_lot()
    {
        $barcode = $this->input->get('barcode');

        $response = $this->db->query("SELECT d.nama, a.lpb_hdr, a.no_lot, c.kode, a.qty, b.grader, b.tgl_kirim, b.tgl_finish_sortir, a.into_oven, e.status_kayu,
        a.qty * ((c.tebal * c.lebar * c.panjang) / 1000000) as kubikasi 
        from ttrx_dtl_lpb_receive a
        join ttrx_hdr_lpb_receive b on a.lpb_hdr = b.lpb
        join tmst_material_kayu c on a.sysid_material = c.sysid
        join tmst_supplier_material d on b.id_supplier = d.sysid
        join tmst_status_lot e on a.into_oven = e.kode
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
                "kubikasi" => floatval($response->kubikasi),
                "grader" => $response->grader,
                "tgl_kirim" => $response->tgl_kirim,
                "tgl_finish_sortir" => $response->tgl_finish_sortir,
                "status" => '<button class="btn btn-info btn-flat"><i class="fas fa-map-marker-alt blink_me"></i> ' . $response->status_kayu . '</button>'
            ]);
        }
    }
}
