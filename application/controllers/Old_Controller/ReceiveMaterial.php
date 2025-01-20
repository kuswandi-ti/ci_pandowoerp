<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReceiveMaterial extends CI_Controller
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
        $this->data['page_title'] = "Generate L.P.B";
        $this->data['page_content'] = "ReceiveMaterial/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/receive-material/index.js"></script>';
        $this->data['lpb'] =  $this->lpb->prediksi_no_lpb('LPB');
        $this->data['legalitas'] =  $this->db->get_where('tmst_legalitas_supplier', ['is_active' => 1])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function store_form_lpb()
    {
        $NoLpb = $this->help->Gnrt_Identity_Number_LPB('LPB');
        $arr_lpb = $this->lpb->TableLpb_ToArray($this->input->post('ukuran[]'), $this->input->post('qty[]'));

        $this->db->trans_start();

        $this->db->insert($this->tbl_hdr_lpb, [
            'lpb' => $NoLpb,
            'id_supplier' => $this->input->post('supplier'),
            'grader' => null,
            'tgl_kirim' => $this->input->post('tgl_kirim'),
            'tgl_finish_sortir' => $this->input->post('tgl_finish_sortir'),
            'legalitas' => $this->input->post('legalitas'),
            'no_legalitas' => strtoupper($this->input->post('no_legalitas')),
            'asal_kiriman' => $this->input->post('daerah'),
            'penilaian' => $this->input->post('penilaian'),
            'keterangan' => $this->input->post('keterangan'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('impsys_initial'),
        ]);

        foreach ($arr_lpb as $li) {
            $this->db->insert($this->tbl_dtl_lpb, [
                'lpb_hdr' => $NoLpb,
                'flag' => $li['flag'],
                'no_lot' => $NoLpb . '-' . $li['flag'],
                'sysid_material' => $li['ukuran'],
                'qty' => $li['qty'],
                'last_updated_by' => $this->session->userdata('impsys_initial'),
            ]);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', "Data penerimaan material gagal disimpan!");
            return redirect('ReceiveMaterial/index');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', "Data penerimaan material telah disimpan!");
            return redirect('CheckGrading');
        }
    }

    public function store_editable_supplier()
    {
        $row_material = $this->db->get_where('tmst_material_kayu', [
            'sysid' => $this->input->post('sysid_material')
        ])->row();

        $this->db->trans_start();
        $this->db->where('sysid', $this->input->post('sysid'));
        $this->db->update('ttrx_dtl_lpb_receive', [
            'sysid_material' => $this->input->post('sysid_material'),
            'harga_per_pcs' => 0,
            'qty' => $row_material->std_qty_lot,
            'last_updated_by' => $this->session->userdata('impsys_initial'),
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code" => 505,
                "msg" => "gagal update detail material"
            ];
        } else {
            $response = [
                "code" => 200,
                "std_qty" => $row_material->std_qty_lot,
                "msg" => "berhasil update detail material"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store_editable_placement()
    {
        $this->db->trans_start();
        $this->db->where('sysid', $this->input->post('sysid'));
        $this->db->update('ttrx_dtl_lpb_receive', [
            'placement' => $this->input->post('placement'),
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code" => 505,
                "msg" => "gagal update lokasi LOT !"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil update lokasi LOT !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store_editable_qty()
    {
        $this->db->trans_start();

        $this->db->where('sysid', $this->input->post('pk'));
        $this->db->update('ttrx_dtl_lpb_receive', [
            'qty' => $this->input->post('value'),
            'last_updated_by' => $this->session->userdata('impsys_initial'),
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code" => 505,
                "msg" => "gagal update quantity material"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil update quantity material"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function add_row_lpb_dtl()
    {
        $this->db->trans_start();

        $this->db->insert('ttrx_dtl_lpb_receive', [
            'lpb_hdr' => $this->input->post('lpb_hdr'),
            'flag' => $this->input->post('flag'),
            'no_lot' => $this->input->post('no_lot'),
            'last_updated_by' => $this->session->userdata('impsys_initial'),
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code" => 505,
                "msg" => "gagal menambahkan detail LPB!"
            ];
        } else {
            $row = $this->db->get_where('ttrx_dtl_lpb_receive', [
                'lpb_hdr' => $this->input->post('lpb_hdr'),
                'flag' => $this->input->post('flag'),
                'no_lot' => $this->input->post('no_lot')
            ])->row();
            $response = [
                "code" => 200,
                "sysid" => $row->sysid,
                "qty" => $row->qty,
                "msg" => "berhasil menambahkan detail LPB!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function delete_row_lpb_dtl()
    {
        $this->db->trans_start();

        $this->db->where('sysid', $this->input->post('sysid'));
        $this->db->delete('ttrx_dtl_lpb_receive');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code" => 505,
                "msg" => "gagal delete detail LPB!"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil delete detail LPB!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }
    // ============================ UTILITY

    public function select_supplier()
    {
        $search = $this->input->get('search');
        $query = $this->db->query(
            "SELECT sysid, nama from tmst_supplier_material where is_active = 1 and nama like '%$search%'"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['sysid'];
                $list[$key]['text'] = $row['nama'];
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function select_material_kayu()
    {
        $search = $this->input->get('search');
        $query = $this->db->query(
            "SELECT sysid ,inisial_kode, deskripsi 
            from tmst_material_kayu 
            where is_active = 1 
            and (inisial_kode like '%$search%' or deskripsi like '%$search%')"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['sysid'];
                $list[$key]['text'] = $row['deskripsi'] . ' (' . $row['inisial_kode'] . ')';
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function select_placement_basah()
    {
        $search = $this->input->get('search');
        $query = $this->db->query(
            "SELECT lokasi
            from tmst_placement_material 
            where is_active = 1 and kategori = 'BASAH'
            and (lokasi like '%$search%')"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['lokasi'];
                $list[$key]['text'] = $row['lokasi'];
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function select_placement_kering()
    {
        $search = $this->input->get('search');
        $query = $this->db->query(
            "SELECT lokasi
            from tmst_placement_material 
            where is_active = 1 and kategori = 'KERING'
            and (lokasi like '%$search%')"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['lokasi'];
                $list[$key]['text'] = $row['lokasi'];
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function select_daerah()
    {
        $search = $this->input->get('search');
        $query = $this->db->query(
            "SELECT * from regencies where `name` like '%$search%'"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['name'];
                $list[$key]['text'] = $row['name'];
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }
}
