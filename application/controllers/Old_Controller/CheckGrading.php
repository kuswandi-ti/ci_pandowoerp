<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CheckGrading extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_lpb = 'ttrx_hdr_lpb_receive';
    public $tbl_dtl_lpb = 'ttrx_dtl_lpb_receive';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
        $this->load->model('m_lpb', 'lpb');
    }

    public function index()
    {
        $this->data['page_title'] = "Check Grading";
        $this->data['page_content'] = "CheckGrading/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/checkgrading-script/grading.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function check_detail_lpb($lpb)
    {
        $this->data['page_title'] = "Form Process Grid";
        $this->data['page_content'] = "TrxWh/ProcessGrid/form_grid";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/ProcessGrid/form_grid.js"></script>';

        $this->data['legalitas'] =  $this->db->get_where('tmst_legalitas_supplier', ['is_active' => 1])->result();
        $this->data['checker'] = $this->db->get_where('tmst_checker_grading', ['nik' => $this->session->userdata('impsys_nik')])->num_rows();

        $this->data['lpb_hdr'] =  $this->db->query(
            "SELECT a.*, b.nama
            FROM ttrx_hdr_lpb_receive a 
            join tmst_supplier_material b on a.id_supplier = b.sysid
            WHERE a.lpb = '$lpb'
            "
        )->row();
        $this->data['lpb_dtls'] =  $this->db->query(
            "SELECT a.sysid, a.lpb_hdr, a.flag, a.no_lot, a.sysid_material, a.qty, b.sysid as sysid_material, b.kode, b.inisial_kode, b.deskripsi, a.lot_printed, a.placement
            FROM impsys.ttrx_dtl_lpb_receive a
            left join impsys.tmst_material_kayu b on a.sysid_material = b.sysid
            where a.lpb_hdr = '$lpb'
            order by a.flag"
        )->result();

        $this->load->view($this->layout, $this->data);
    }

    public function printAllLot()
    {
        $lpb = $this->input->post('lpb');

        $zero_val = $this->db->get_where('ttrx_dtl_lpb_receive', ['lpb_hdr' => $lpb, 'qty' => 0])->num_rows();
        $un_size = $this->db->get_where('ttrx_dtl_lpb_receive', ['lpb_hdr' => $lpb, 'sysid_material' => null])->num_rows();

        if ($zero_val > 0) {
            $response = [
                'msg' => 'beberapa lot masih dalam proses!, belum memiliki qty dan ukuran.',
                'code' => 505,
            ];
        }
        if ($un_size > 0) {
            $response = [
                'msg' => 'beberapa lot masih dalam proses!, belum memiliki qty dan ukuran.',
                'code' => 505,
            ];
        } else {
            $this->db->trans_start();

            $this->db->where('lpb_hdr', $lpb);
            $this->db->update('ttrx_dtl_lpb_receive', [
                'lot_printed' => 1
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $response = [
                    "code" => 505,
                    "msg" => "Terjadi kesalahan segera kontak administrator!"
                ];
            } else {
                $response = [
                    'lpb' => $lpb,
                    'code' => 200,
                ];
            }
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function update_lpb_as_selesai()
    {
        $sysid = $this->input->post('sysid');
        $lpb = $this->input->post('lpb');
        $kirim = $this->input->post('tgl_kirim');
        $finish = $this->input->post('tgl_finish_sortir');
        $tot_lot = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb])->num_rows();
        $lot_printed = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb, 'lot_printed' => 1])->num_rows();
        $placement = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb, 'placement' => NULL])->num_rows();

        $null_material = $this->db->query("SELECT * FROM ttrx_dtl_lpb_receive WHERE lpb_hdr = '$lpb' AND sysid_material IS NULL")->num_rows();
        $qty_0 = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb, 'qty' => 0])->num_rows();

        $row_hdr = $this->db->get_where($this->tbl_hdr_lpb, ['sysid' =>  $sysid])->row();
        $supplier = $this->db->get_where('tmst_supplier_material', ['sysid' => $row_hdr->id_supplier])->row();

        if ($null_material == 0 && $qty_0 == 0 && $tot_lot == $lot_printed &&  $placement == 0) {

            $lots = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb])->result();
            foreach ($lots as $lot) {
                $harga = $this->db->get_where('ttrx_harga_material_supplier', [
                    'sysid_supplier' => $this->input->post('supplier'),
                    'sysid_material' => $lot->sysid_material
                ])->row();

                if (empty($harga->harga_per_pcs) or $harga->harga_per_pcs == 0) {
                    return $this->help->Fn_resulting_response([
                        "code" => 505,
                        "msg" => "Supplier ini belum memiliki harga untuk material pada lot $lot->no_lot !, hubungi administrator untuk melengkapi harga!"
                    ]);
                }
            }
            $this->db->trans_start();

            $this->db->where('sysid', $sysid);
            $this->db->update($this->tbl_hdr_lpb, [
                'id_supplier' => $this->input->post('supplier'),
                'grader' => $this->input->post('grader'),
                'status_lpb' => 'SELESAI',
                'tgl_kirim' => $kirim,
                'tgl_finish_sortir' => $finish,
                'legalitas' => $this->input->post('legalitas'),
                'penilaian' => $this->input->post('penilaian'),
                'keterangan' => $this->input->post('keterangan'),
                'jumlah_kiriman' => $this->input->post('jumlah_kiriman'),
                'selesai_at' => date('Y-m-d H:i:s'),
                'selesai_by' => $this->session->userdata('impsys_initial'),
                "tanggungan_uang_bongkar" => floatval($supplier->uang_bongkar)
            ]);

            foreach ($lots as $lot) {
                $harga = $this->db->get_where('ttrx_harga_material_supplier', [
                    'sysid_supplier' => $this->input->post('supplier'),
                    'sysid_material' => $lot->sysid_material
                ])->row();
                $this->db->where('sysid', $lot->sysid);
                $this->db->update($this->tbl_dtl_lpb, [
                    'harga_per_pcs' => $harga->harga_per_pcs
                ]);
            }

            $this->db->insert('thst_activity_lpb_finish', [
                "lpb" => $lpb,
                "action" => "SELESAI GRID",
                'do_at' => date('Y-m-d H:i:s'),
                'do_by' => $this->session->userdata('impsys_initial'),
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $response = [
                    "code" => 505,
                    "msg" => "Terjadi kesalahan segera kontak administrator!"
                ];
            } else {
                $response = [
                    "code" => 200,
                    "msg" => "LPB dinyatakan selesai !"
                ];
            }
        } else {
            $response = [
                "code" => 505,
                "msg" => "Quantity/Ukuran kayu/Placement dalam tabel LPB belum lengkap ! atau ada lot yang belum tercetak !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function update_lpb_still_buka()
    {
        $sysid = $this->input->post('sysid');
        $lpb = $this->input->post('lpb');

        $kirim = $this->input->post('tgl_kirim');
        $finish = $this->input->post('tgl_finish_sortir');

        $this->db->trans_start();
        // -------------------------------
        $this->db->where('sysid', $sysid);
        $this->db->update($this->tbl_hdr_lpb, [
            'id_supplier' => $this->input->post('supplier'),
            'grader' => $this->input->post('grader'),
            'no_legalitas' => $this->input->post('no_legalitas'),
            'tgl_kirim' => $kirim,
            'tgl_finish_sortir' => $finish,
            'legalitas' => $this->input->post('legalitas'),
            'penilaian' => $this->input->post('penilaian'),
            'jumlah_kiriman' => $this->input->post('jumlah_kiriman'),
            'keterangan' => $this->input->post('keterangan'),
            'last_updated_by' => $this->session->userdata('impsys_initial'),
        ]);
        // -------------------------------
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 500,
                "msg" => "LPB gagal di perbaharui !"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "LPB berhasil di perbaharui !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    // ======================================= UTILITY DataTable

    public function DataTable_monitoring_grading()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.sysid',
            1 => 'a.lpb',
            2 => 'b.nama',
            3 => 'a.tgl_kirim',
            4 => 'a.tgl_finish_sortir',
            5 => 'a.grader',
            6 => 'a.status_lpb',
        );
        $sql = "SELECT a.sysid, a.status_lpb, a.lpb, b.nama, a.tgl_kirim, a.tgl_finish_sortir , a.grader, COUNT(c.lpb_hdr) as lot, a.legalitas,
        COUNT(CASE WHEN c.lot_printed = 1 then 1 ELSE NULL END) as lot_printed
        from ttrx_hdr_lpb_receive a
        JOIN tmst_supplier_material b on a.id_supplier = b.sysid 
        join ttrx_dtl_lpb_receive c on a.lpb = c.lpb_hdr where a.status_lpb = 'BUKA'";

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.lpb LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.legalitas LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData['tgl_kirim'] = $row["tgl_kirim"];
            $nestedData['tgl_finish_sortir'] = $row["tgl_finish_sortir"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['lot'] = $row["lot"];
            $nestedData['legalitas'] = $row["legalitas"];
            $nestedData['status_lpb'] = $row["status_lpb"];
            $nestedData['lot_printed'] = $row["lot_printed"];

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

    public function delete_lpb()
    {
        $lpb = $this->input->post('lpb');

        $this->db->trans_start();
        // -------------------------------
        $this->db->insert('thst_activity_lpb_finish', [
            'lpb' => $lpb,
            'action' => 'DELETE LPB',
            'entity_before' => "NOT FINISH YET",
            'entity_after' => 'DELETED',
            'do_at' => date('Y-m-d H:i:s'),
            'do_by' => $this->session->userdata('impsys_initial'),
        ]);

        $this->db->where('lpb', $lpb);
        $this->db->delete('ttrx_hdr_lpb_receive');
        $this->db->where('lpb_hdr', $lpb);
        $this->db->delete('ttrx_dtl_lpb_receive');
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

    public function update_Asprinted_single_lot()
    {
        $sysid = $this->input->post('sysid');
        $row = $this->db->get_where('ttrx_dtl_lpb_receive', ['sysid' => $sysid])->row();

        if ($row->sysid_material == '' or $row->sysid_material == null or $row->qty == 0) {
            $response = [
                "code" => 505,
                "msg" => "Harap pastikan anda telah memilih ukuran kayu & quantity nya!"
            ];
        } else {
            if ($row->lot_printed == 0) {
                $this->db->where('sysid', $sysid);
                $this->db->update($this->tbl_dtl_lpb, [
                    'lot_printed' => 1,
                    'first_printed_by' => $this->session->userdata('impsys_initial'),
                    'first_printed_at' => date('Y-m-d H:i:s')
                ]);
                $response = [
                    "code" => 200,
                    "msg" => "Berhasil mengubah status lot menjadi telar di print!"
                ];
            } else {
                $response = [
                    "code" => 201,
                ];
            }
        }
        return $this->help->Fn_resulting_response($response);
    }
}
