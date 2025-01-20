<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DatabaseLpb extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_lpb = 'ttrx_hdr_lpb_receive';
    public $tbl_dtl_lpb = 'ttrx_dtl_lpb_receive';
    public $thst_lpb = 'thst_activity_lpb_finish';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "DataBase LPB";
        $this->data['page_content'] = "DatabaseLpb/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/DataBaseLpb-script/DataBaseLpb.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function preview_detail_lpb($lpb)
    {
        $this->data['page_title'] = "Detail LPB $lpb";
        $this->data['page_content'] = "DatabaseLpb/detail_lpb";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/DataBaseLpb-script/detail_lpb.js"></script>';

        $this->data['lpb_hdr'] =  $this->db->query(
            "SELECT a.*, b.nama
                FROM ttrx_hdr_lpb_receive a 
                join tmst_supplier_material b on a.id_supplier = b.sysid
                WHERE a.lpb = '$lpb'
                "
        )->row();
        $this->data['lpb_dtls'] =  $this->db->query(
            "SELECT a.sysid, a.lot_printed, a.lpb_hdr, a.flag, a.no_lot, a.sysid_material, a.qty, b.sysid as sysid_material, a.harga_per_pcs, b.kode, b.inisial_kode, b.deskripsi, a.into_oven, c.status_kayu, ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi 
                FROM ttrx_dtl_lpb_receive a
                left join tmst_material_kayu b on a.sysid_material = b.sysid
                join tmst_status_lot c on a.into_oven = c.kode 
                where a.lpb_hdr = '$lpb'
                order by a.flag"
        )->result();

        $this->load->view($this->layout, $this->data);
    }

    public function popup_detail_lpb()
    {
        $lpb = $this->input->get('lpb');

        $this->data['lpb_hdr'] =  $this->db->query(
            "SELECT a.*, b.nama
                FROM ttrx_hdr_lpb_receive a 
                join tmst_supplier_material b on a.id_supplier = b.sysid
                WHERE a.lpb = '$lpb'
                "
        )->row();
        $this->data['lpb_dtls'] =  $this->db->query(
            "SELECT a.sysid, a.lot_printed, a.lpb_hdr, a.flag, a.no_lot, a.sysid_material, a.qty, b.sysid as sysid_material, a.harga_per_pcs, b.kode, b.inisial_kode, b.deskripsi, a.into_oven, c.status_kayu, ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi, a.placement
                FROM ttrx_dtl_lpb_receive a
                left join tmst_material_kayu b on a.sysid_material = b.sysid
                join tmst_status_lot c on a.into_oven = c.kode 
                where a.lpb_hdr = '$lpb'
                order by a.flag"
        )->result();

        $this->load->view('general-modal/m_detail_lpb', $this->data);
    }

    public function modal_history()
    {
        $lpb = $this->input->get('lpb');

        $this->data['lpb'] =  $lpb;
        $this->data['historys'] =  $this->db->get_where('thst_activity_lpb_finish', ['lpb' => $lpb])->result();

        $this->load->view('general-modal/m_history_lpb', $this->data);
    }

    public function report_commercial_lpb($lpb)
    {
        $this->data['lpb_hdr'] =  $this->db->query(
            "SELECT a.*, b.nama as supplier
                FROM ttrx_hdr_lpb_receive a 
                join tmst_supplier_material b on a.id_supplier = b.sysid
                WHERE a.lpb = '$lpb'
                "
        )->row();
        $this->data['lpb_dtls'] =  $this->db->query(
            "SELECT a.*,
                    b.kode, b.inisial_kode, b.deskripsi, b.tebal, b.lebar, b.panjang, b.created_by, b.created_at,
                    ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi 
                FROM ttrx_dtl_lpb_receive a
                left join tmst_material_kayu b on a.sysid_material = b.sysid
                where a.lpb_hdr = '$lpb'
                order by a.flag"
        )->result();

        $this->load->view('Print/report_commercial_lpb', $this->data);
    }

    public function tempelan_lot_material($lpb)
    {
        $this->db->where('lpb', $lpb);
        $this->db->update('ttrx_hdr_lpb_receive', [
            "lot_last_printed_all_by" => $this->session->userdata('impsys_initial'),
            "lot_last_printed_all_at" => date('Y-m-d H:i:s'),
        ]);

        $this->data['title'] = 'Lembar Tempelan No.lot';
        $this->data['lpb'] = $lpb;
        $this->data['lpb_hdr'] = $this->db->query(
            "select * 
            from ttrx_hdr_lpb_receive a
            join tmst_supplier_material b on a.id_supplier = b.sysid
            where lpb = '$lpb'"
        )->row();

        $this->data['lpb_dtls'] =  $this->db->query(
            "SELECT a.*, b.*, ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi 
                FROM ttrx_dtl_lpb_receive a
                left join tmst_material_kayu b on a.sysid_material = b.sysid
                where a.lpb_hdr = '$lpb'
                order by a.flag"
        )->result();

        $this->load->view('Print/tempelan_lot', $this->data);
    }

    public function tempelan_single_lot($sysid)
    {
        $row = $this->db->get_where($this->tbl_dtl_lpb, ['sysid' => $sysid])->row();

        $this->data['title'] = 'Lembar Tempelan No.lot';
        $this->data['lpb_hdr'] = $this->db->query(
            "select * 
            from ttrx_hdr_lpb_receive a
            join tmst_supplier_material b on a.id_supplier = b.sysid
            where lpb = '$row->lpb_hdr'"
        )->row();
        $this->data['lpb_dtls'] =  $this->db->query(
            "SELECT a.*, b.*, ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi 
                FROM ttrx_dtl_lpb_receive a
                left join tmst_material_kayu b on a.sysid_material = b.sysid
                where a.sysid = '$sysid'
                order by a.flag"
        )->result();
        $this->load->view('Print/tempelan_lot', $this->data);
    }

    // =========================== DataTable =====================================//

    public function DataTable_DataBase_Lpb()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.sysid',
            1 => 'a.lpb',
            2 => 'b.nama',
            3 => 'a.tgl_finish_sortir',
            4 => 'a.grader',
        );

        $from = $this->input->get('from');
        $to = $this->input->get('to');

        // a.qty * ((d.tebal * d.lebar * d.panjang) / 1000000) as kubikasi
        $sql = "SELECT a.sysid, a.lpb, b.nama, a.tgl_finish_sortir , a.grader, COUNT(c.lpb_hdr) as lot, SUM(c.qty) as pcs,
        SUM(c.qty * ((d.tebal * d.lebar * d.panjang) / 1000000)) as kubikasi, SUM(c.qty * c.harga_per_pcs) as amount
        from ttrx_hdr_lpb_receive a
        JOIN tmst_supplier_material b on a.id_supplier = b.sysid 
        join ttrx_dtl_lpb_receive c on a.lpb = c.lpb_hdr 
        join tmst_material_kayu d on c.sysid_material = d.sysid
        where a.status_lpb = 'SELESAI'
        AND DATE_FORMAT(a.selesai_at, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(a.selesai_at, '%Y-%m-%d') <= '$to'";



        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.lpb LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.selesai_at LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.grader LIKE '%" . $requestData['search']['value'] . "%')";
        }
        $sql .= " GROUP BY a.lpb";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'] . "  LIMIT "
            . $requestData['start'] . " ," . $requestData['length'] . " ";
        $query = $this->db->query($sql);
        $data = array();
        $no = 1;
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid'] = $row["sysid"];
            $nestedData['lpb'] = $row["lpb"];
            $nestedData['supplier'] = $row["nama"];
            $nestedData['tgl_finish_sortir'] = $row["tgl_finish_sortir"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['lot'] = $row["lot"];
            $nestedData['kubikasi'] = floatval($row["kubikasi"]);
            $nestedData['amount'] = 'Rp. ' . number_format(floatval($row["amount"]), 0, ',', '.');
            $nestedData['pcs'] = $row["pcs"];

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

    public function modal_form_edit_harga_lpb()
    {
        $lpb = $this->input->get('lpb');

        $this->data['Hdr'] = $this->db->get_where('ttrx_hdr_lpb_receive', ['lpb' => $lpb])->row();
        $this->data['supplier'] = $this->db->get_where('tmst_supplier_material', ['sysid' => $this->data['Hdr']->id_supplier])->row();
        $this->data['rowDatas'] = $this->db->query("SELECT a.sysid_material, a.lpb_hdr, a.harga_per_pcs, b.kode, b.deskripsi , b.tebal , b.lebar , b.panjang 
        FROM impsys.ttrx_dtl_lpb_receive a
        join tmst_material_kayu b on a.sysid_material = b.sysid 
        where lpb_hdr = '$lpb'
        group by a.sysid_material, a.lpb_hdr, b.kode, b.deskripsi, a.harga_per_pcs
        order by b.kode
        ")->result();

        $this->load->view('general-modal/m_update_harga_lpb', $this->data);
    }

    public function update_harga_lpb($lpb)
    {
        $sysid_material = $this->input->post('pk');
        $value = $this->input->post('value');

        $material = $this->db->get_where('tmst_material_kayu', ['sysid' => $sysid_material])->row();
        $hdr = $this->db->get_where('ttrx_hdr_lpb_receive', ['lpb' => $lpb])->row();
        $harga = $this->db->get_where('ttrx_harga_material_supplier', ['sysid_material' => $sysid_material, 'sysid_supplier' => $hdr->id_supplier])->row();

        $this->db->trans_start();
        $data = [
            'harga_per_pcs' => $value
        ];

        $this->db->where('lpb_hdr', $lpb);
        $this->db->where('sysid_material', $sysid_material);
        $this->db->update('ttrx_dtl_lpb_receive', $data);

        $this->db->insert('thst_activity_lpb_finish', [
            'lpb' => $lpb,
            'material' => $material->kode,
            'kode' => $material->deskripsi,
            'sysid_material' => $sysid_material,
            'action' => 'UPDATE HARGA PERUKURAN',
            'price_before' => floatval(number_format($harga->harga_per_pcs, 2)),
            'price_after' => floatval(number_format($value, 2)),
            'do_at' => date('Y-m-d H:i:s'),
            'do_by' => $this->session->userdata('impsys_initial'),
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal update harga material lpb : $lpb"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "berhasil update harga material lpb : $lpb"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function delete_lot()
    {
        $sysid = $this->input->post('sysid');
        $old_entity = "";
        $new_entity = "";

        $old = $this->db->get_where($this->tbl_dtl_lpb, ['sysid' => $sysid])->row_array();
        $material = $this->db->get_where('tmst_material_kayu', ['sysid' => $old['sysid_material']])->row();
        $old_entity .= json_encode($old);

        $this->db->trans_start();
        $this->db->insert($this->thst_lpb, [
            "lpb" => $old['lpb_hdr'],
            "no_lot" => $old['no_lot'],
            'material' => $material->kode,
            'kode' => $material->deskripsi,
            'sysid_material' => $old['sysid_material'],
            "action" => "DELETE DATA",
            "price_before" => floatval($old['harga_per_pcs']),
            "price_after" => 0,
            "entity_before" => $old_entity,
            "entity_after" => null,
            'do_at' => date('Y-m-d H:i:s'),
            'do_by' => $this->session->userdata('impsys_initial'),
        ]);

        $this->db->where('sysid', $sysid);
        $this->db->delete($this->tbl_dtl_lpb);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal delete data LOT!"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil delete data LOT!",
                "id" => "row--" . $sysid
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store_editable_lot_price()
    {
        $sysid = $this->input->post('pk');
        $old = $this->db->get_where($this->tbl_dtl_lpb, ['sysid' => $sysid])->row_array();
        $material = $this->db->get_where('tmst_material_kayu', ['sysid' => $old['sysid_material']])->row();


        $this->db->trans_start();
        $this->db->insert($this->thst_lpb, [
            "lpb" => $old['lpb_hdr'],
            "no_lot" => $old['no_lot'],
            'material' => $material->kode,
            'kode' => $material->deskripsi,
            'sysid_material' => $old['sysid_material'],
            "action" => "UPDATE HARGA LOT",
            "price_before" => floatval($old['harga_per_pcs']),
            "price_after" => floatval($this->input->post('value')),
            "entity_before" => null,
            "entity_after" => null,
            'do_at' => date('Y-m-d H:i:s'),
            'do_by' => $this->session->userdata('impsys_initial'),
        ]);

        $this->db->where('sysid', $sysid);
        $this->db->update($this->tbl_dtl_lpb, [
            'harga_per_pcs' => floatval($this->input->post('value'))
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal update harga material"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil update harga material"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store_editable_uang_bongkar()
    {
        $sysid = $this->input->post('pk');
        $old = $this->db->get_where($this->tbl_hdr_lpb, ['sysid' => $sysid])->row_array();


        $this->db->trans_start();
        $this->db->insert($this->thst_lpb, [
            "lpb" => $old['lpb'],
            "no_lot" => null,
            'material' => null,
            'kode' => null,
            'sysid_material' => null,
            "action" => "UPDATE UANG BONGKAR PERKUBIK",
            "price_before" => floatval($old['tanggungan_uang_bongkar']),
            "price_after" => floatval($this->input->post('value')),
            "entity_before" => null,
            "entity_after" => null,
            'do_at' => date('Y-m-d H:i:s'),
            'do_by' => $this->session->userdata('impsys_initial'),
        ]);

        $this->db->where('sysid', $sysid);
        $this->db->update($this->tbl_hdr_lpb, [
            'tanggungan_uang_bongkar' => floatval($this->input->post('value'))
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal update uang bongkar/kubik !"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil update uang bongkar/kubik !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store_editable_kubikasi_pengiriman()
    {
        $sysid = $this->input->post('pk');
        $old = $this->db->get_where($this->tbl_hdr_lpb, ['sysid' => $sysid])->row_array();


        $this->db->trans_start();
        $this->db->insert($this->thst_lpb, [
            "lpb" => $old['lpb'],
            "no_lot" => null,
            'material' => null,
            'kode' => null,
            'sysid_material' => null,
            "action" => "UPDATE TOTAL JUMLAH KIRIMAN FROM " . floatval($old['jumlah_kiriman']) . " (m3), TO " . floatval($this->input->post('value')) . " (m3)",
            "price_before" => null,
            "price_after" => null,
            "entity_before" => null,
            "entity_after" => null,
            'do_at' => date('Y-m-d H:i:s'),
            'do_by' => $this->session->userdata('impsys_initial'),
        ]);

        $this->db->where('sysid', $sysid);
        $this->db->update($this->tbl_hdr_lpb, [
            'jumlah_kiriman' => floatval($this->input->post('value'))
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal update total kubikasi pengiriman !"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil update total kubikasi pengiriman !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store_add_lot_susulan()
    {
        $sysid_hdr = $this->input->post('sysid_hdr');
        $lpb = $this->input->post('lpb');
        $Hdr = $this->db->get_where($this->tbl_hdr_lpb, ['sysid' => $sysid_hdr])->row();

        $harga = $this->db->get_where('ttrx_harga_material_supplier', [
            'sysid_supplier' => $Hdr->id_supplier,
            'sysid_material' => $this->input->post('ukuran')
        ])->row();



        $clausa = $this->db->query("SELECT * FROM ttrx_dtl_lpb_receive WHERE lpb_hdr = $lpb ORDER BY flag desc")->row();
        $new_flag = floatval($clausa->flag) + 1;

        if (empty($harga->harga_per_pcs) or $harga->harga_per_pcs == 0) {
            $this->session->set_flashdata('danger', "Supplier ini belum memiliki harga untuk material yang anda pilih !, hubungi administrator untuk melengkapi harga!");
            return redirect('DatabaseLpb/preview_detail_lpb/' . $this->input->post('lpb'));
        }

        $data = [
            'lpb_hdr' => $this->input->post('lpb'),
            'flag' => $new_flag,
            'no_lot' =>  $lpb . '-' . $new_flag,
            'sysid_material' => $this->input->post('ukuran'),
            'qty' => $this->input->post('Qty'),
            "harga_per_pcs" => $harga->harga_per_pcs,
            'last_updated_by' => $this->session->userdata('impsys_initial'),
            'last_updated_at' => date('Y-m-d H:i:s'),
            'placement' => 'GUDANG KAYU BASAH',
            'into_oven' => 0
        ];

        $insert = $this->db->insert('ttrx_dtl_lpb_receive', $data);
        $material = $this->db->get_where('tmst_material_kayu', ['sysid' => $this->input->post('ukuran')])->row();
        $this->db->insert($this->thst_lpb, [
            "lpb" => $lpb,
            "no_lot" =>  $lpb . '-' . $new_flag,
            'material' => $material->kode,
            'kode' => $material->deskripsi,
            'sysid_material' => $this->input->post('ukuran'),
            "action" => "ADD NEW LOT",
            "price_before" => 0,
            "price_after" => floatval($harga->harga_per_pcs),
            "entity_before" => null,
            "entity_after" => json_encode($data),
            'do_at' => date('Y-m-d H:i:s'),
            'do_by' => $this->session->userdata('impsys_initial'),
        ]);

        if ($insert) {
            $this->session->set_flashdata('success', 'Berhasil menambahkan LOT : ' . $lpb . '-' . $new_flag . ' !');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan LOT !');
        }

        return redirect('DatabaseLpb/preview_detail_lpb/' . $this->input->post('lpb'));
    }
}
