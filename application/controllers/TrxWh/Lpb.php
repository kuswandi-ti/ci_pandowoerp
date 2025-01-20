<?php

use FontLib\Table\Type\post;

defined('BASEPATH') or exit('No direct script access allowed');

class Lpb extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_lpb = 'ttrx_hdr_lpb_receive';
    public $tbl_dtl_lpb = 'ttrx_dtl_lpb_receive';
    public $thst_lpb = 'thst_activity_lpb_finish';
    public $qview_dtl_size_item_lpb = 'qview_dtl_size_item_lpb';
    protected $Tmst_account = 'tmst_account';
    protected $tmst_currency        = 'tmst_currency';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
        $this->load->model('m_lpb', 'lpb');
    }

    public function index()
    {
        $this->data['page_title'] = "Form Pembukaan Lembar Penerimaan Bahan Baku";
        $this->data['page_content'] = "TrxWh/LPB/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/LPB/index.js?v=' . time() . '"></script>';
        $this->data['legalitas'] =  $this->db->get_where('tmst_legalitas_supplier', ['is_active' => 1])->result();

        $this->data['List_Currency'] = $this->db->where('Status', 1)->order_by('Is_Default', 'DESC')->get($this->tmst_currency);

        $this->load->view($this->layout, $this->data);
    }

    public function list()
    {
        $this->data['page_title'] = "List Lembar Penerimaan Bahan Baku";
        $this->data['page_content'] = "TrxWh/LPB/list";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/LPB/list.js?v=' . time() . '"></script>';
        $this->data['legalitas'] =  $this->db->get_where('tmst_legalitas_supplier', ['is_active' => 1])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function store_lpb()
    {
        $NoLpb = $this->help->Gnrt_Identity_Monthly('LPB', 3, '');
        $arr_lpb = $this->lpb->TableLpb_ToArray($this->input->post('ukuran'), $this->input->post('qty'));

        $LPB_Type = null;
        $SR_Numb = null;
        if ($this->input->post('legalitas') == 'SALES RETURN') {
            $LPB_Type = 'SR';
            $SR_Numb = $this->input->post('SR_Numb');
        }

        $this->db->trans_start();

        $this->db->insert($this->tbl_hdr_lpb, [
            'lpb' => $NoLpb,
            'id_supplier' => $this->input->post('supplier'),
            'grader' => null,
            'LPB_Type' => $LPB_Type,
            'SR_Numb' => $SR_Numb,
            'tgl_kirim' => $this->input->post('tgl_kirim'),
            'tgl_finish_sortir' => NULL,
            'RateToIDR' => $this->input->post('Rate'),
            'Currency' => $this->input->post('Currency'),
            'legalitas' => $this->input->post('legalitas'),
            'no_legalitas' => strtoupper($this->input->post('no_legalitas')),
            'keterangan' => $this->input->post('Note'),
            'asal_kiriman' => $this->input->post('daerah'),
            'penilaian' => $this->input->post('penilaian'),
            // 'keterangan' => $this->input->post('keterangan'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('impsys_initial'),
        ]);

        foreach ($arr_lpb as $li) {
            $this->db->insert($this->tbl_dtl_lpb, [
                'lpb_hdr'           => $NoLpb,
                'flag'              => $li['flag'],
                'no_lot'            => $NoLpb . '-' . $li['flag'],
                'sysid_material'    => $li['ukuran'],
                'qty'               => $li['qty'],
                'last_updated_by'   => $this->session->userdata('impsys_initial'),
            ]);

            $inserted_id = $this->db->insert_id();

            $this->db->insert('ttrx_child_dtl_size_item_lpb', [
                'Id_Lot' => $inserted_id,
                'flag' => 1,
                'Last_updated_at' => date('Y-m-d H:i:s'),
                'Last_updated_by' => $this->session->userdata('impsys_initial'),
                'Last_update_ip' => $this->help->get_client_ip()
            ]);
        }



        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', "Data penerimaan material gagal disimpan!");
            return redirect('TrxWh/ProcessGrid/index');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', "Data penerimaan material telah disimpan!");
            return redirect('TrxWh/ProcessGrid/index');
        }
    }


    // ---------------------- PENUNJANG FORM
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

    public function select_supplier()
    {
        $search = $this->input->get('search');
        $query = $this->db->query(
            "SELECT SysId, Account_Code, AccountTitle_Code, Account_Name from $this->Tmst_account 
            where Category_ID = 'VP' 
            and Is_Verified = 1
            and Is_Active = 1
            and Account_Name like '%$search%'"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['SysId'];
                $list[$key]['text'] = $row['AccountTitle_Code'] . '. ' . $row['Account_Name'] . '(' . $row['Account_Code'] . ')';
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function select_customer()
    {
        $search = $this->input->get('search');
        $query = $this->db->query(
            "SELECT SysId, Account_Code, AccountTitle_Code, Account_Name from $this->Tmst_account 
            where Category_ID = 'CS' 
            and Is_Verified = 1
            and Is_Active = 1
            and Account_Name like '%$search%'"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['SysId'];
                $list[$key]['text'] = $row['AccountTitle_Code'] . '. ' . $row['Account_Name'] . '(' . $row['Account_Code'] . ')';
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
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
            "select a.*, b.Account_Name
            from ttrx_hdr_lpb_receive a
            join tmst_account b on a.id_supplier = b.SysId
            where lpb = '$lpb'"
        )->row();

        $this->data['lpb_dtls'] =  $this->db->query(
            "SELECT a.*, b.Item_Name, sum(c.Qty * c.Cubication) as kubikasi
                FROM ttrx_dtl_lpb_receive a
                left join tmst_item b on a.sysid_material = b.SysId
                left join ttrx_child_dtl_size_item_lpb c on a.sysid = c.Id_Lot
                where a.lpb_hdr = '$lpb'
                group by a.no_lot 
                order by a.flag"
        )->result();

        $this->load->view('Print/tempelan_lot', $this->data);
    }

    public function tempelan_single_lot($sysid)
    {
        $row = $this->db->get_where($this->tbl_dtl_lpb, ['sysid' => $sysid])->row();

        $this->data['title'] = 'Lembar Tempelan No.lot';
        $this->data['lpb_hdr'] = $this->db->query(
            "select a.*, b.Account_Name
            from ttrx_hdr_lpb_receive a
            join tmst_account b on a.id_supplier = b.SysId
            where lpb = '$row->lpb_hdr'"
        )->row();
        $this->data['lpb_dtls'] =  $this->db->query(
            "SELECT a.*, b.Item_Name, sum(c.Qty * c.Cubication) as kubikasi
                FROM ttrx_dtl_lpb_receive a
                left join tmst_item b on a.sysid_material = b.SysId
                left join ttrx_child_dtl_size_item_lpb c on a.sysid = c.Id_Lot
                where a.sysid = '$sysid'
                group by a.no_lot 
                order by a.flag"
        )->result();

        $this->load->view('Print/tempelan_lot', $this->data);
    }

    public function report_tally_sheet($lpb)
    {
        $this->data['title'] = 'Tally Sheet : ' . $lpb;

        $this->data['lpb_hdr'] =  $this->db->query(
            "SELECT a.*,
             b.Account_Name as supplier ,
             b.Account_Code,
             b.AccountTitle_Code	
            FROM ttrx_hdr_lpb_receive a 
            join tmst_account b on a.id_supplier = b.SysId
            WHERE a.lpb = '$lpb'"
        )->row();

        $this->data['lpb_dtls'] =  $this->db->query(
            "SELECT a.*,
                    b.Item_Code,
                    CONCAT(size.Item_Length, ' x ', size.Item_Width, ' x ', size.Item_Height, 'CM') as inisial_kode,
                    b.Item_Name,
                    size.Item_Height as tebal,
                    size.Item_Width as lebar,
                    size.Item_Length as panjang,
                    size.Qty,
                    unit.Uom,
                    cur.Currency_Symbol,
                    CASE
                        WHEN unit.Uom = 'm3' THEN ((size.Qty * size.Cubication) * a.harga_per_pcs)
                        WHEN unit.Uom = 'pcs' THEN (size.Qty * a.harga_per_pcs)
                        ELSE 0
                    END AS sub_amount,
                    b.created_by, b.created_at,
                    size.Cubication as kubikasi,
                    size.Qty * size.Cubication as sub_tot_kubikasi
                FROM ttrx_dtl_lpb_receive a
                join tmst_item b on a.sysid_material = b.SysId
                JOIN tmst_unit_type unit ON b.Uom_Id = unit.Unit_Type_ID 
                join $this->qview_dtl_size_item_lpb size on a.sysid = size.Id_Lot
                join ttrx_hdr_lpb_receive hdr on a.lpb_hdr = hdr.lpb
                JOIN tmst_currency cur on hdr.Currency = cur.Currency_ID
                where a.lpb_hdr = '$lpb'
                order by size.Item_Height,
                    size.Item_Width,
                    size.Item_Length"
        )->result();


        $this->load->view('Print/tally_sheet', $this->data);
    }

    public function report_commercial_lpb($lpb)
    {
        $this->data['lpb_hdr'] =  $this->db->query(
            "SELECT a.*,
             b.Account_Name as supplier ,
             b.Account_Code,
             b.AccountTitle_Code	
            FROM ttrx_hdr_lpb_receive a 
            join tmst_account b on a.id_supplier = b.SysId
            WHERE a.lpb = '$lpb'"
        )->row();


        $this->data['lpb_dtls'] =  $this->db->query(
            "SELECT a.*,
                    b.Item_Code as kode,
                    CONCAT(size.Item_Length, ' x ', size.Item_Width, ' x ', size.Item_Height, 'CM') as inisial_kode,
                    b.Item_Name as deskripsi,
                    size.Item_Height as tebal,
                    size.Item_Width as lebar,
                    size.Item_Length as panjang,
                    size.Qty,
                    unit.Uom,
                    cur.Currency_Symbol,
                    CASE
                        WHEN unit.Uom = 'm3' THEN ((size.Qty * size.Cubication) * a.harga_per_pcs)
                        WHEN unit.Uom = 'pcs' THEN (size.Qty * a.harga_per_pcs)
                        ELSE 0
                    END AS sub_amount,
                    b.created_by, b.created_at,
                    size.Cubication as kubikasi,
                    size.Qty * size.Cubication as sub_tot_kubikasi
                FROM ttrx_dtl_lpb_receive a
                join tmst_item b on a.sysid_material = b.SysId
                JOIN tmst_unit_type unit ON b.Uom_Id = unit.Unit_Type_ID 
                join $this->qview_dtl_size_item_lpb size on a.sysid = size.Id_Lot
                join ttrx_hdr_lpb_receive hdr on a.lpb_hdr = hdr.lpb
                JOIN tmst_currency cur on hdr.Currency = cur.Currency_ID
                where a.lpb_hdr = '$lpb'
                order by size.Item_Height,
                    size.Item_Width,
                    size.Item_Length"
        )->result();

        $this->load->view('Print/report_commercial_lpb', $this->data);
    }

    public function modal_history()
    {
        $lpb = $this->input->get('lpb');

        $this->data['lpb'] =  $lpb;
        $this->data['historys'] =  $this->db->get_where('thst_activity_lpb_finish', ['lpb' => $lpb])->result();

        $this->load->view('general-modal/m_history_lpb', $this->data);
    }

    public function preview_detail_lpb($lpb)
    {
        $this->data['page_title'] = "Detail LPB $lpb";
        $this->data['page_content'] = "TrxWh/LPB/detail_lpb";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/LPB/detail_lpb.js?v=' . time() . '"></script>';
        $this->data['List_Currency'] = $this->db->where('Status', 1)->order_by('Is_Default', 'DESC')->get($this->tmst_currency);

        $this->data['lpb_hdr'] =  $this->db->query(
            "SELECT a.*,
            b.Account_Name as nama,
            curr.Currency_Symbol
            FROM ttrx_hdr_lpb_receive a 
            left join tmst_account b on a.id_supplier = b.SysId
            left join tmst_currency curr on a.Currency = curr.Currency_ID
            WHERE a.lpb = '$lpb'"
        )->row();

        $this->data['lpb_dtls'] =  $this->db->query(
            "SELECT a.sysid,
            a.lot_printed,
            a.lpb_hdr,
            a.flag,
            a.no_lot,
            a.sysid_material,
            b.SysId as sysid_material,
            unit.Uom,
            a.harga_per_pcs,
            a.original_price,
            b.Item_Code as kode,
            b.Item_Name as deskripsi,
            a.into_oven,
            c.status_kayu,
            a.placement,
            d.Warehouse_Name,
            SUM(size.Qty_Usable) as qty, 
            SUM(size.Cubication * size.Qty_Usable) AS kubikasi
                FROM ttrx_dtl_lpb_receive a
                left join tmst_item b on a.sysid_material = b.SysId
                left join tmst_status_lot c on a.into_oven = c.kode 
                left join tmst_warehouse d on a.placement = d.Warehouse_ID
                LEFT JOIN $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot
                JOIN tmst_unit_type unit on b.Uom_Id = unit.Unit_Type_ID
                where a.lpb_hdr = '$lpb'
                group by a.no_lot
                order by a.flag"
        )->result();

        $this->load->view($this->layout, $this->data);
    }

    public function modal_form_edit_harga_lpb()
    {
        $lpb = $this->input->get('lpb');
        if ($this->help->validation_isEditable($lpb) == false) {
            return $this->help->Fn_resulting_response([
                'code' => '505',
                'msg' => "$lpb tidak dapat di ubah karna sudah memiliki data payment !"
            ]);
        };

        $this->data['Hdr'] = $this->db->get_where('ttrx_hdr_lpb_receive', ['lpb' => $lpb])->row();
        $this->data['supplier'] = $this->db->get_where('tmst_account', ['SysId' => $this->data['Hdr']->id_supplier])->row();

        $this->data['rowDatas'] = $this->db->query(
            "SELECT a.sysid_material, a.lpb_hdr, a.harga_per_pcs, 
            b.Item_Code as kode, b.Item_Name as deskripsi , b.Item_Height as tebal , b.Item_Width as lebar , b.Item_Length as panjang , b.LWH_Unit
            FROM ttrx_dtl_lpb_receive a
            join tmst_item b on a.sysid_material = b.SysId 
            where lpb_hdr = '$lpb'
            group by a.sysid_material, a.lpb_hdr, b.Item_Code, b.Item_Name, a.harga_per_pcs
            order by b.Item_Code
        "
        )->result();

        $this->load->view('general-modal/m_update_harga_lpb', $this->data);
    }

    public function update_harga_lpb($lpb)
    {
        $sysid_material = $this->input->post('pk');
        $value = $this->input->post('value');

        $material = $this->db->get_where('tmst_item', ['sysid' => $sysid_material])->row();
        $rowLot = $this->db->get_where('ttrx_dtl_lpb_receive', [
            'lpb_hdr' => $lpb,
            'sysid_material' => $sysid_material
        ])->row();
        $Old_Harga = $rowLot->harga_per_pcs;

        $this->db->trans_start();
        $data = [
            'harga_per_pcs' => $value
        ];

        $this->db->where('lpb_hdr', $lpb);
        $this->db->where('sysid_material', $sysid_material);
        $this->db->update('ttrx_dtl_lpb_receive', $data);

        $this->db->insert('thst_activity_lpb_finish', [
            'lpb' => $lpb,
            'material' => $material->Item_Code,
            'kode' => $material->Item_Name,
            'sysid_material' => $sysid_material,
            'action' => 'UPDATE HARGA PERUKURAN',
            'price_before' => floatval($Old_Harga),
            'price_after' => floatval($value),
            'do_at' => date('Y-m-d H:i:s'),
            'do_by' => $this->session->userdata('impsys_initial'),
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal update harga material $lpb"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "berhasil update harga material $lpb"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store_add_lot_susulan()
    {
        $sysid_hdr = $this->input->post('sysid_hdr');
        $lpb = $this->input->post('lpb');
        $Hdr = $this->db->get_where($this->tbl_hdr_lpb, ['sysid' => $sysid_hdr])->row();
        $id_wh = $this->input->post('warehouse');
        $id_item = $this->input->post('ukuran');
        $qty = $this->input->post('Qty');

        if ($this->help->validation_isEditable($lpb) == false) {
            $this->session->set_flashdata('error', "$lpb tidak dapat di ubah karna sudah memiliki data payment !");
            return redirect('TrxWh/Lpb/preview_detail_lpb/' . $lpb);
        };

        $Item = $this->db->get_where('tmst_item', ['SysId' => $id_item])->row();

        $harga = $this->db->get_where('ttrx_price_approved', [
            'Account_ID' => $Hdr->id_supplier,
            'Item_code' => $Item->Item_Code
        ])->row();

        if (empty($harga->Price) or $harga->Price == 0) {
            $this->session->set_flashdata('error', "Supplier ini belum memiliki harga untuk material yang anda pilih !,
            silahkan lengkapi harga pada menu vendor price, dan kembali kesini !");
            return redirect('TrxWh/Lpb/preview_detail_lpb/' . $lpb);
        }

        $clausa = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb])->num_rows();
        $new_flag = floatval($clausa) + 1;


        $data = [
            'lpb_hdr' => $this->input->post('lpb'),
            'flag' => $new_flag,
            'no_lot' =>  $lpb . '-' . $new_flag,
            'sysid_material' => $id_item,
            'qty' => $qty,
            "harga_per_pcs" => $harga->Price,
            'last_updated_by' => $this->session->userdata('impsys_initial'),
            'last_updated_at' => date('Y-m-d H:i:s'),
            'placement' => $id_wh,
            'into_oven' => 0
        ];

        $insert = $this->db->insert($this->tbl_dtl_lpb, $data);
        $material = $this->db->get_where('tmst_material_kayu', ['sysid' => $this->input->post('ukuran')])->row();
        $this->db->insert($this->thst_lpb, [
            "lpb" => $lpb,
            "no_lot" =>  $lpb . '-' . $new_flag,
            'material' => $material->kode,
            'kode' => $material->deskripsi,
            'sysid_material' => $this->input->post('ukuran'),
            "action" => "ADD NEW LOT",
            "price_before" => 0,
            "price_after" => floatval($harga->Price),
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

        return redirect('TrxWh/Lpb/preview_detail_lpb/' . $this->input->post('lpb'));
    }

    public function delete_lot()
    {
        $sysid = $this->input->post('sysid');
        $old_entity = "";
        $new_entity = "";

        $old = $this->db->get_where($this->tbl_dtl_lpb, ['sysid' => $sysid])->row_array();
        $lpb = $old['lpb_hdr'];
        if ($this->help->validation_isEditable($lpb) == false) {
            $this->session->set_flashdata('error', "$lpb tidak dapat di ubah karna sudah memiliki data payment !");
            return redirect('TrxWh/Lpb/preview_detail_lpb/' . $lpb);
        };

        $material = $this->db->get_where('tmst_material', ['SysId' => $old['sysid_material']])->row();
        $old_entity .= json_encode($old);

        $this->db->trans_start();
        $this->db->insert($this->thst_lpb, [
            "lpb" => $old['lpb_hdr'],
            "no_lot" => $old['no_lot'],
            'material' => $material->Item_Code,
            'kode' => $material->Item_Name,
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
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "berhasil delete data LOT!",
                "id" => "row--" . $sysid
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
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "berhasil update total kubikasi pengiriman !"
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

    // -------------------------------------------------- Datatable Section
    public function DT_List_Lpb()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'sysid',
            1 => 'lpb',
            2 => 'Invoice_Status',
            3 => 'Account_Name',
            4 => 'tgl_kirim',
            5 => 'grader',
        );

        $from = $this->input->get('from');
        $to = $this->input->get('to');

        // a.qty * ((d.tebal * d.lebar * d.panjang) / 1000000) as kubikasi
        $sql = "WITH CalculatedAmounts AS (
        SELECT 
            a.sysid, 
            a.lpb, 
            a.Invoice_Status,
            vp.AccountTitle_Code, 
            vp.Account_Name, 
            a.tgl_kirim, 
            a.grader,
            a.tanggungan_uang_bongkar, -- Pindahkan kolom ini ke CTE
            c.no_lot,
            size.Qty,
            size.Cubication,
            unit.Uom,
            c.original_price,
            CASE
                WHEN unit.Uom = 'm3' THEN COALESCE(size.Cubication * size.Qty, 0) * c.original_price
                WHEN unit.Uom = 'pcs' THEN size.Qty * c.original_price
                ELSE 0
            END AS item_amount
        FROM 
            ttrx_hdr_lpb_receive a
        JOIN 
            tmst_account vp ON a.id_supplier = vp.SysId 
        JOIN 
            ttrx_dtl_lpb_receive c ON a.lpb = c.lpb_hdr 
        JOIN 
            tmst_item item ON c.sysid_material = item.SysId
        JOIN 
            tmst_unit_type unit ON item.Uom_Id = unit.Unit_Type_ID
        JOIN
            qview_dtl_size_item_lpb size  on c.sysid = size.Id_Lot
        WHERE 
            a.status_lpb = 'SELESAI'
            AND DATE_FORMAT(a.tgl_kirim, '%Y-%m-%d') >= '$from'
            AND DATE_FORMAT(a.tgl_kirim, '%Y-%m-%d') <= '$to'
    )
            SELECT 
                sysid, 
                lpb, 
                Invoice_Status,
                AccountTitle_Code, 
                Account_Name, 
                tgl_kirim, 
                grader,
                COUNT(DISTINCT no_lot) as lot,
                SUM(Qty) AS pcs,
                SUM(CASE WHEN Uom = 'm3' THEN Cubication * Qty ELSE 0 END) as kubikasi,
                SUM(item_amount) AS amount,
                tanggungan_uang_bongkar as uang_bongkar -- Sekarang kolom ini tersedia
            FROM CalculatedAmounts where 1=1 ";
        // $sql = "SELECT 
        //         a.sysid, 
        //         a.lpb, 
        //         a.Invoice_Status,
        //         vp.AccountTitle_Code, 
        //         vp.Account_Name, 
        //         a.tgl_kirim, 
        //         a.grader, 
        //         COUNT(DISTINCT c.no_lot) as lot, 
        //         SUM(size.Qty) AS pcs,
        //         COALESCE(SUM(size.Cubication * size.Qty),0) as kubikasi,
        //         CASE
        //             WHEN unit.Uom = 'm3' THEN COALESCE(SUM(size.Cubication * size.Qty),0) * c.harga_per_pcs
        //             WHEN unit.Uom = 'pcs' THEN SUM(size.Qty) * c.harga_per_pcs 
        //             ELSE 0
        //         END AS amount,
        //         tanggungan_uang_bongkar AS uang_bongkar
        //         FROM 
        //             ttrx_hdr_lpb_receive a
        //         JOIN 
        //             tmst_account vp ON a.id_supplier = vp.SysId 
        //         JOIN 
        //             ttrx_dtl_lpb_receive c ON a.lpb = c.lpb_hdr 
        //         JOIN 
        //             tmst_item item ON c.sysid_material = item.SysId
        //         JOIN 
        //             tmst_unit_type unit ON item.Uom_Id = unit.Unit_Type_ID
        //         JOIN
        //             $this->qview_dtl_size_item_lpb size  on c.sysid = size.Id_Lot
        //         WHERE 
        //             a.status_lpb = 'SELESAI'
        //             AND DATE_FORMAT(a.tgl_kirim, '%Y-%m-%d') >= '$from'
        //             AND DATE_FORMAT(a.tgl_kirim, '%Y-%m-%d') <= '$to' ";

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (lpb LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Account_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Invoice_Status LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR grader LIKE '%" . $requestData['search']['value'] . "%')";
        }
        $sql .= " GROUP BY sysid, 
                lpb, 
                Invoice_Status,
                AccountTitle_Code, 
                Account_Name, 
                tgl_kirim, 
                grader, tanggungan_uang_bongkar ";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'];
        $query = $this->db->query($sql);
        $data = array();
        $no = 1;
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid'] = $row["sysid"];
            $nestedData['lpb'] = $row["lpb"];
            $nestedData['Invoice_Status'] = $row["Invoice_Status"];
            $nestedData['supplier'] = $row["Account_Name"];
            $nestedData['tgl_kirim'] = $row["tgl_kirim"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['lot'] = $row["lot"];
            $nestedData['kubikasi'] = $this->help->roundToFourDecimals($row["kubikasi"]);
            $nestedData['amount'] = floatval($row["amount"]);
            $nestedData['uang_bongkar'] = $this->help->FormatIdr($row["uang_bongkar"]);
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
}
