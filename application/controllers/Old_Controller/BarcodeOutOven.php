<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BarcodeOutOven extends CI_Controller
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
        $this->data['page_title'] = "Barcode Material Keluar Oven";
        $this->data['page_content'] = "Barcode/Index_OutOven";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/Barcode-script/OutOven.js"></script>';

        $this->data['placements'] = $this->db->get_where(
            'tmst_placement_material',
            [
                'kategori' => 'KERING',
                'is_active' => '1'
            ]
        )->result();

        var_dump($this->data['placements']);

        $this->load->view($this->layout, $this->data);
    }

    public function update_out_oven()
    {
        $dateTime = date('Y-m-d H:i:s');
        $barcode = $this->input->get('barcode');
        $placement = $this->input->get('placement');
        $countRow = $this->db->get_where('ttrx_dtl_lpb_receive', ['no_lot' => $barcode])->num_rows();
        $countHstin = $this->db->get_where('thst_in_to_oven', ['lot' => $barcode])->num_rows();
        $countHstOut = $this->db->get_where('thst_out_of_oven', ['lot' => $barcode])->num_rows();

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
        if ($countHstOut > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode ' . $barcode . ' sudah dinyatakan keluar oven, pilih barcode lain!'
            ]);
        }

        $this->db->trans_start();
        $this->db->where('no_lot', $barcode);
        $this->db->update('ttrx_dtl_lpb_receive', [
            'into_oven' => 2,
            'placement' => 'KERING'
        ]);
        $this->db->insert('thst_out_of_oven', [
            "lot" => $barcode,
            "do_by" => $this->session->userdata('impsys_initial'),
            "do_time" => $dateTime,
            "remark_out_of_oven" => 'BARCODE',
            "placement" => $placement
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = ['code' => 505, 'msg' => 'lot gagal dinyatakan keluar oven!'];
        } else {
            $response = ['code' => 200];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function preview_detail_lot()
    {
        $barcode = $this->input->get('barcode');

        $response = $this->db->query("SELECT d.nama, a.lpb_hdr, a.no_lot, c.kode, a.qty, b.grader, b.tgl_kirim, b.tgl_finish_sortir, a.into_oven, e.status_kayu, g.nama as nama_oven,
        DATE_FORMAT(f.do_time, '%Y-%m-%d %H:%i') as time_in, a.qty * ((c.tebal * c.lebar * c.panjang) / 1000000) as kubikasi,
        CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,f.do_time, NOW()) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,f.do_time, NOW()) % 24, ' Jam') as timer
        from ttrx_dtl_lpb_receive a
        join ttrx_hdr_lpb_receive b on a.lpb_hdr = b.lpb
        join tmst_material_kayu c on a.sysid_material = c.sysid
        join tmst_supplier_material d on b.id_supplier = d.sysid
        join tmst_status_lot e on a.into_oven = e.kode
        left join thst_in_to_oven f on a.no_lot = f.lot
        left join tmst_identity_oven g on f.oven = g.sysid
        where a.no_lot = '$barcode'
        limit 1")->row();

        if (floatval(substr($response->timer, 0, 2)) >= 6) {
            $timer = '<span class="badge badge-danger"><i class="blink_me">' . $response->timer . '</i></span>';
        } else {
            $timer = '<span class="badge badge-xs badge-info"><i>' . $response->timer . '</i></span>';
        }

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
                "time_in" => $response->time_in,
                "kubikasi" => floatval($response->kubikasi),
                "oven" => $response->nama_oven,
                "timer" => $timer,
                "status" => '<button class="btn btn-info btn-flat"><i class="fas fa-map-marker-alt blink_me"></i> ' . $response->status_kayu . '</button>',
            ]);
        }
    }
}
