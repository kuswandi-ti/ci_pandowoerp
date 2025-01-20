<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProcessGrid extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_lpb = 'ttrx_hdr_lpb_receive';
    public $tbl_dtl_lpb = 'ttrx_dtl_lpb_receive';
    public $ttrx_child_dtl_size_item_lpb = 'ttrx_child_dtl_size_item_lpb';
    public $qview_dtl_size_item_lpb = 'qview_dtl_size_item_lpb';
    public $ttrx_price_approved = 'ttrx_price_approved';
    public $qmst_item = 'qmst_item';
    protected $tmst_currency = 'tmst_currency';
    protected $tmst_size_item_grid = 'tmst_size_item_grid';
    protected $qmst_operator_grader = 'qmst_operator_grader';

    public $thst_pre_oven = 'thst_pre_oven';
    public $thst_in_to_oven = 'thst_in_to_oven';
    public $thst_out_of_oven = 'thst_out_of_oven';
    public $thst_bundle_trading = 'thst_bundle_trading';

    protected $Date;
    protected $DateTime;

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
        $this->load->model('m_lpb', 'lpb');
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');
        $this->load->model('m_DataTable', 'M_Datatables');
    }

    public function index()
    {
        $this->data['page_title'] = "Prosess Grade";
        $this->data['page_content'] = "TrxWh/ProcessGrid/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/ProcessGrid/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function approval()
    {
        $this->data['page_title'] = "Approval LPB";
        $this->data['page_content'] = "TrxWh/ProcessGrid/approval";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/ProcessGrid/approval.js?v=' . time() . '"></script>';
        $this->data['legalitas'] =  $this->db->get_where('tmst_legalitas_supplier', ['is_active' => 1])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function check_detail_lpb($lpb, $action)
    {
        $this->data['page_title'] = "Detail Lembar Penerimaan Bahan Baku";
        if ($action == 'edit') {
            $this->data['page_title'] = "Form Process Grid";
        }
        $this->data['page_content'] = "TrxWh/ProcessGrid/form_grid";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/ProcessGrid/form_grid.js?v=' . time() . '"></script>';

        $this->data['legalitas'] =  $this->db->get_where('tmst_legalitas_supplier', ['is_active' => 1])->result();
        $this->data['List_Currency'] = $this->db->where('Status', 1)->order_by('Is_Default', 'DESC')->get($this->tmst_currency);
        $this->data['lpb_hdr'] =  $this->db->query(
            "SELECT a.*, b.Account_Code, b.AccountTitle_Code, b.Account_Name
            FROM ttrx_hdr_lpb_receive a 
            join tmst_account b on a.id_supplier = b.SysId
            WHERE a.lpb = '$lpb'
            "
        )->row();

        $this->data['lpb_dtls'] =  $this->db->query(
            "SELECT a.sysid, a.lpb_hdr, a.flag, a.no_lot, a.sysid_material, a.qty, b.SysId as sysid_material, b.Item_Code as kode, b.Item_Name as deskripsi, a.lot_printed, a.placement, c.Warehouse_Name, COALESCE(SUM(size.Cubication * size.Qty),0) as kubikasi
            FROM ttrx_dtl_lpb_receive a
            left join tmst_item b on a.sysid_material = b.SysId
            left join tmst_warehouse c on a.placement = c.Warehouse_Id
            left join ttrx_child_dtl_size_item_lpb size on a.sysid = size.Id_Lot
            where a.lpb_hdr = '$lpb'
            group by a.no_lot
            order by a.flag"
        )->result();

        $this->data['graders'] = $this->db->get_where($this->qmst_operator_grader, ['active' => 1])->result();

        $this->data['action'] = $action;

        $this->load->view($this->layout, $this->data);
    }

    public function printAllLot()
    {
        $lpb = $this->input->post('lpb');

        $validate_item = $this->db->get_where('ttrx_dtl_lpb_receive', ['lpb_hdr' => $lpb, 'sysid_material' => null])->num_rows();
        $validate_placement = $this->db->get_where('ttrx_dtl_lpb_receive', ['lpb_hdr' => $lpb, 'placement' => null])->num_rows();
        $validate_qty = $this->db->get_where($this->qview_dtl_size_item_lpb, ['lpb_hdr' => $lpb, 'Qty' => 0])->num_rows();
        $validate_size = $this->db->get_where($this->qview_dtl_size_item_lpb, ['lpb_hdr' => $lpb, 'Id_Size_Item' => NULL])->num_rows();

        if ($validate_item > 0) {
            return $this->help->Fn_resulting_response([
                'msg' => 'beberapa lot masih dalam proses!, belum memiliki item !',
                'code' => 505,
            ]);
        }
        if ($validate_placement > 0) {
            return $this->help->Fn_resulting_response([
                'msg' => 'beberapa lot masih dalam proses!, belum memiliki penempatan !',
                'code' => 505,
            ]);
        }
        if ($validate_qty > 0) {
            return $this->help->Fn_resulting_response([
                'msg' => 'beberapa lot masih dalam proses!, belum memiliki detail quantity !',
                'code' => 505,
            ]);
        }
        if ($validate_size > 0) {
            return $this->help->Fn_resulting_response([
                'msg' => 'beberapa lot masih dalam proses!, belum memiliki detail ukuran !',
                'code' => 505,
            ]);
        }
        $this->db->trans_start();

        $this->db->where(['lpb_hdr' => $lpb, 'lot_printed' => 1])->update('ttrx_dtl_lpb_receive', [
            'lot_printed' => 1,
            'last_printed_by' => $this->session->userdata('impsys_initial'),
            'last_printed_at' => $this->DateTime
        ]);
        $this->db->where(['lpb_hdr' => $lpb, 'lot_printed' => 0])->update('ttrx_dtl_lpb_receive', [
            'lot_printed' => 1,
            'first_printed_by' => $this->session->userdata('impsys_initial'),
            'first_printed_at' => $this->DateTime
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


        return $this->help->Fn_resulting_response($response);
    }

    public function verify()
    {
        $sysid = $this->input->post('sysid');
        $lpb_hdr = $this->input->post('lpb');
        $Param = $this->input->post('Param');

        if ($Param == 'SELESAI') {
            $lpb = $this->db->get_where($this->tbl_hdr_lpb, ['sysid' => $sysid])->row();
            $lots = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb_hdr])->result();
            $supplier = $this->db->get_where('tmst_account', ['SysId' => $lpb->id_supplier])->row();

            $ItemEmptyPrice = '';
            $item_Before = '';
            foreach ($lots as $lot) {
                $harga = $this->db->query("SELECT vpr.VPR_Number, vpr.Price_ID, vpr.Item_code, item.Item_Name, vpr.Account_ID, vpr.Price, vpr.Effective_Date 
                        FROM $this->ttrx_price_approved vpr
                        join $this->qmst_item item on item.Item_Code = vpr.Item_code
                        where Account_ID = '$lpb->id_supplier' and item.SysId = $lot->sysid_material")->row();

                if (empty($harga) or empty($harga->Price) or $harga->Price == 0 or $harga->Price == NULL) {
                    $RowItem = $this->db->get_where($this->qmst_item, ['SysId' => $lot->sysid_material])->row();

                    if ($item_Before != $RowItem->Item_Code) {
                        $item_Before = $RowItem->Item_Code;
                        $ItemEmptyPrice .= $RowItem->Item_Code . '<br />';
                    }
                }
            }

            if ($ItemEmptyPrice != '') {
                return $this->help->Fn_resulting_response([
                    "code" => 505,
                    "msg" => "Supplier $supplier->AccountTitle_Code $supplier->Account_Name,
                    belum memiliki harga pada material : <br /> $ItemEmptyPrice
                    ,hubungi administrator untuk melengkapi harga pada menu Vendor Pricing !"
                ]);
            }

            $this->db->trans_start();

            $this->db->where('sysid', $sysid)->update($this->tbl_hdr_lpb, [
                'status_lpb' => 'SELESAI',
                'selesai_at' => date('Y-m-d H:i:s'),
                'selesai_by' => $this->session->userdata('impsys_initial'),
            ]);

            foreach ($lots as $lot) {
                $RowItem = $this->db->get_where($this->qmst_item, ['SysId' => $lot->sysid_material])->row();
                $harga = $this->db->get_where($this->ttrx_price_approved, [
                    'Item_code' => $RowItem->Item_Code,
                    'Account_ID' => $lpb->id_supplier
                ])->row();

                $this->db->where('sysid', $lot->sysid);
                $this->db->update($this->tbl_dtl_lpb, [
                    'original_price' => $harga->Price,
                    'harga_per_pcs' => $harga->Price * $lpb->RateToIDR
                ]);
            }

            $this->db->query("UPDATE ttrx_dtl_lpb_receive x
                            SET x.into_oven = 0
                            WHERE x.lpb_hdr = '$lpb_hdr'
                            AND EXISTS (
                                SELECT 1
                                FROM thst_pre_oven a
                                WHERE a.lot = x.no_lot
                            )");

            $this->db->query("UPDATE ttrx_dtl_lpb_receive z
                            SET z.into_oven = 4
                            WHERE z.lpb_hdr = '$lpb_hdr'
                            AND EXISTS (
                                SELECT 1
                                FROM thst_bundle_trading b
                                WHERE b.lot = z.no_lot
                            )");

            $this->db->query("UPDATE ttrx_dtl_lpb_receive z
                            SET z.into_oven = 2
                            WHERE z.lpb_hdr = '$lpb_hdr'
                            AND EXISTS (
                                SELECT 1
                                FROM thst_out_of_oven b
                                WHERE b.lot = z.no_lot
                            )");

            $this->db->insert('thst_activity_lpb_finish', [
                "lpb" => $lpb_hdr,
                "action" => "LPB Approve",
                'do_at' => date('Y-m-d H:i:s'),
                'do_by' => $this->session->userdata('impsys_initial'),
            ]);
            $msg = "Approval berhasil !";
        } else {
            $this->db->trans_start();
            $this->db->where('sysid', $sysid)->update($this->tbl_hdr_lpb, [
                'status_lpb' => 'REVISI',
                'send_to_approval' => 0
                // 'selesai_at' => date('Y-m-d H:i:s'),
                // 'selesai_by' => $this->session->userdata('impsys_initial'),
            ]);

            $this->db->insert('thst_activity_lpb_finish', [
                "lpb" => $lpb_hdr,
                "action" => "REVISI LPB",
                'do_at' => date('Y-m-d H:i:s'),
                'do_by' => $this->session->userdata('impsys_initial'),
            ]);
            $msg = "Approval berhasil di kembalikan ke proses grid untuk di revisi !";
        }
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
                "msg" => $msg
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

        $LPB_Type = null;
        if ($this->input->post('legalitas') == 'SALES RETURN') {
            $LPB_Type = 'SR';
        }

        $this->db->trans_start();
        // -------------------------------
        $this->db->where('sysid', $sysid);
        $this->db->update($this->tbl_hdr_lpb, [
            'id_supplier' => $this->input->post('supplier'),
            'grader' => $this->input->post('grader'),
            'no_legalitas' => $this->input->post('no_legalitas'),
            'LPB_Type' => $LPB_Type,
            'SR_Numb' => $this->input->post('SR_Numb'),
            'tgl_kirim' => $kirim,
            'tgl_finish_sortir' => $finish,
            'legalitas' => $this->input->post('legalitas'),
            'penilaian' => $this->input->post('penilaian'),
            'jumlah_kiriman' => $this->input->post('jumlah_kiriman'),
            'jumlah_pcs_kiriman' => $this->input->post('jumlah_pcs_kiriman'),
            'tanggungan_uang_bongkar' => $this->input->post('tanggungan_uang_bongkar'),
            'asal_kiriman' => $this->input->post('daerah'),
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

    public function add_row_lpb_dtl()
    {
        $this->db->trans_start();
        $this->db->insert($this->tbl_dtl_lpb, [
            'lpb_hdr' => $this->input->post('lpb_hdr'),
            'flag' => $this->input->post('flag'),
            'no_lot' => $this->input->post('no_lot'),
            'last_updated_by' => $this->session->userdata('impsys_initial'),
        ]);

        $id_lot = $this->db->insert_id();

        $this->db->insert($this->ttrx_child_dtl_size_item_lpb, [
            'Id_Lot' => $id_lot,
            'flag' => 1,
            'Last_updated_at' => date('Y-m-d H:i:s'),
            'Last_updated_by' => $this->session->userdata('impsys_initial'),
            'Last_update_ip' => $this->help->get_client_ip()
        ]);

        $id_size = $this->db->insert_id();

        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => $error_msg
            ];
        } else {
            $response = [
                "code" => 200,
                "id_lot" => $id_lot,
                "qty" => 0,
                "id_size" => $id_size,
                "msg" => "berhasil menambahkan detail LPB!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function add_row_size_lot()
    {
        $sysid_lot = $this->input->post('sysid_lot');
        $CountSizes = $this->db->get_where($this->qview_dtl_size_item_lpb, ['Id_Lot' => $sysid_lot])->num_rows();

        $this->db->trans_start();

        $this->db->insert($this->ttrx_child_dtl_size_item_lpb, [
            'Id_Lot' => $sysid_lot,
            'flag' => $CountSizes + 1,
            'Last_updated_at' => $this->DateTime,
            'Last_updated_by' => $this->session->userdata('impsys_initial'),
            'Last_update_ip' => $this->help->get_client_ip(),
        ]);

        $newid = $this->db->insert_id();

        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => $error_msg
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "flag" => $CountSizes + 1,
                "sysid_size" => $newid,
                "sysid_lot" => $sysid_lot,
                "msg" => "berhasil menambahkan detail ukuran !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function remove_row_size_lot()
    {
        $SysId = $this->input->post('SysId');
        $this->db->trans_start();

        $this->db->delete($this->ttrx_child_dtl_size_item_lpb, ['SysId' => $SysId]);

        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => $error_msg
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "berhasil menghapus detail ukuran !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function delete_row_lpb_dtl()
    {
        $RowData = $this->db->get_where('ttrx_dtl_lpb_receive', ['sysid' => $this->input->post('sysid')])->row();

        $this->db->trans_start();

        $this->db->where('lot', $RowData->no_lot)->delete($this->thst_pre_oven);
        $this->db->where('lot', $RowData->no_lot)->delete($this->thst_bundle_trading);
        $this->db->where('Id_Lot', $this->input->post('sysid'))->delete($this->ttrx_child_dtl_size_item_lpb);
        $this->db->where('sysid', $this->input->post('sysid'))->delete('ttrx_dtl_lpb_receive');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
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

    // public function send_to_approval() // racuk
    // {
    //     $lpb = $this->input->post('lpb');

    //     $tot_lot = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb])->num_rows();
    //     $lot_printed = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb, 'lot_printed' => 1])->num_rows();
    //     $placement = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb, 'placement' => NULL])->num_rows();
    //     $null_material = $this->db->query("SELECT * FROM ttrx_dtl_lpb_receive WHERE lpb_hdr = '$lpb' AND sysid_material IS NULL")->num_rows();
    //     $qty_0 = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb, 'qty' => 0])->num_rows();
    //     if ($null_material == 0 && $qty_0 == 0 && $tot_lot == $lot_printed && $placement == 0) {
    //         $this->db->trans_start();

    //         $this->db->where('lpb', $lpb)->update($this->tbl_hdr_lpb, [
    //             'send_to_approval' => 1,
    //             'send_to_approval_time' => $this->DateTime,
    //             'send_to_approval_by' => $this->session->userdata('impsys_initial')
    //         ]);

    //         $this->db->trans_complete();
    //         if ($this->db->trans_status() === FALSE) {
    //             $this->db->trans_rollback();
    //             $response = [
    //                 "code" => 505,
    //                 "msg" => "LPB gagal di ajukan ke proses approval"
    //             ];
    //         } else {
    //             $response = [
    //                 "code" => 200,
    //                 "msg" => "LPB berhasil di ajukan ke proses approval!"
    //             ];
    //         }
    //     } else {
    //         $response = [
    //             "code" => 505,
    //             "msg" => "Quantity/Ukuran kayu/Placement dalam tabel LPB belum lengkap ! atau ada lot yang belum tercetak !"
    //         ];
    //     }
    //     return $this->help->Fn_resulting_response($response);
    // }

    public function send_to_approval()
    {
        $lpb = $this->input->post('lpb');

        $Sql_Lot = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb]);
        $tot_lot = $Sql_Lot->num_rows();
        $DataLot = $Sql_Lot->result();

        $lot_printed = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb, 'lot_printed' => 1])->num_rows();
        $lot_doesnt_material = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb, 'sysid_material' => NULL])->num_rows();
        $lot_doesnt_plcement = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb, 'placement' => NULL])->num_rows();

        $null_material = $this->db->get_where($this->qview_dtl_size_item_lpb, ['lpb_hdr' => $lpb, 'Id_Size_Item' => NULL])->num_rows();
        $qty_0 = $this->db->get_where($this->qview_dtl_size_item_lpb, ['lpb_hdr' => $lpb, 'Qty' => 0])->num_rows();

        // if ($null_material == 0 && $qty_0 == 0 && $tot_lot == $lot_printed && $placement == 0) {
        if ($tot_lot != $lot_printed) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Untuk mengajukan approval, semua bundle harus sudah di print !"
            ]);
        }
        if ($null_material > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Ada detail data bundle yangg belum memiliki ukuran !"
            ]);
        }
        if ($qty_0 > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Ada detail data bundle yang belum mengisi Qty !"
            ]);
        }
        if ($lot_doesnt_plcement > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Beberapa bundle belum memiliki penempatan !"
            ]);
        }
        if ($lot_doesnt_material > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Beberapa bundle belum memiliki item !"
            ]);
        }

        $Hdr = $this->db->get_where($this->tbl_hdr_lpb, ['lpb' => $lpb])->row();

        if (empty($Hdr->grader) || empty($Hdr->tgl_finish_sortir) || empty($Hdr->penilaian) || empty($Hdr->tanggungan_uang_bongkar) || empty($Hdr->jumlah_kiriman)) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Harap lengkapi data Header : Grader, Tgl Finish sortir, Penilaian, Uang Bongkar & Jumlah Kiriman !"
            ]);
        }

        $this->db->trans_start();
        $this->db->where('lpb', $lpb)->update($this->tbl_hdr_lpb, [
            'send_to_approval' => 1,
            'send_to_approval_time' => $this->DateTime,
            'send_to_approval_by' => $this->session->userdata('impsys_initial')
        ]);
        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => $error_msg
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "LPB berhasil di ajukan ke proses approval!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function save_and_send_to_approval()
    {
        $lpb = $this->input->post('lpb');
        $sysid = $this->input->post('sysid');

        $Sql_Lot = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb]);
        $tot_lot = $Sql_Lot->num_rows();
        $DataLot = $Sql_Lot->result();

        $lot_printed = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb, 'lot_printed' => 1])->num_rows();
        $lot_doesnt_material = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb, 'sysid_material' => NULL])->num_rows();
        $lot_doesnt_plcement = $this->db->get_where($this->tbl_dtl_lpb, ['lpb_hdr' => $lpb, 'placement' => NULL])->num_rows();

        $null_material = $this->db->get_where($this->qview_dtl_size_item_lpb, ['lpb_hdr' => $lpb, 'Id_Size_Item' => NULL])->num_rows();
        $qty_0 = $this->db->get_where($this->qview_dtl_size_item_lpb, ['lpb_hdr' => $lpb, 'Qty' => 0])->num_rows();

        // if ($null_material == 0 && $qty_0 == 0 && $tot_lot == $lot_printed && $placement == 0) {
        if ($tot_lot != $lot_printed) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Untuk mengajukan approval, semua bundle harus sudah di print !"
            ]);
        }
        if ($null_material > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Ada detail data bundle yangg belum memiliki ukuran !"
            ]);
        }
        if ($qty_0 > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Ada detail data bundle yang belum mengisi Qty !"
            ]);
        }
        if ($lot_doesnt_plcement > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Beberapa bundle belum memiliki penempatan !"
            ]);
        }
        if ($lot_doesnt_material > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Beberapa bundle belum memiliki item !"
            ]);
        }

        $kirim = $this->input->post('tgl_kirim');
        $finish = $this->input->post('tgl_finish_sortir');

        $LPB_Type = null;
        if ($this->input->post('legalitas') == 'SALES RETURN') {
            $LPB_Type = 'SR';
        }

        $this->db->trans_start();

        $this->db->where('sysid', $sysid)->update($this->tbl_hdr_lpb, [
            'id_supplier' => $this->input->post('supplier'),
            'grader' => $this->input->post('grader'),
            'no_legalitas' => $this->input->post('no_legalitas'),
            'tgl_kirim' => $kirim,
            'tgl_finish_sortir' => $finish,
            'send_to_approval' => 1,
            'send_to_approval_time' => $this->DateTime,
            'send_to_approval_by' => $this->session->userdata('impsys_initial'),
            'legalitas' => $this->input->post('legalitas'),
            'LPB_Type' => $LPB_Type,
            'SR_Numb' => $this->input->post('SR_Numb'),
            'penilaian' => $this->input->post('penilaian'),
            'jumlah_kiriman' => $this->input->post('jumlah_kiriman'),
            'jumlah_pcs_kiriman' => $this->input->post('jumlah_pcs_kiriman'),
            'tanggungan_uang_bongkar' => $this->input->post('tanggungan_uang_bongkar'),
            'asal_kiriman' => $this->input->post('daerah'),
            'keterangan' => $this->input->post('keterangan'),
            'last_updated_by' => $this->session->userdata('impsys_initial'),
        ]);
        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => $error_msg
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "LPB berhasil di ajukan ke proses approval!"
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
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal update quantity bundle"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil update quantity bundle"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function select_item_grid()
    {
        $search = $this->input->get('search');

        $query = $this->db->query(
            "SELECT SysId,Item_Code,Item_Name
            from tmst_item 
            where Is_Active = 1
            and Is_Grid_Item = 1
            and (Item_Code like '%$search%' or Item_Name like '%$search%')"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['SysId'];
                $list[$key]['text'] = $row['Item_Name'] . ' (' . $row['Item_Code'] . ')';
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function select_placement_grid()
    {
        $search = $this->input->get('search');
        $query = $this->db->query(
            "SELECT * from tmst_warehouse where Is_Receive_Grid = 1 and Is_Active = 1
            and (Warehouse_Name like '%$search%' or Warehouse_Code like '%$search%')"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['Warehouse_ID'];
                $list[$key]['text'] = $row['Warehouse_Name'];
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

    public function select_size_item()
    {
        $search = $this->input->get('search');
        $child = $this->db->get_where('qview_dtl_size_item_lpb', ['SysId' => $this->input->get('sysid')])->row();
        // Id_Parent_Item
        $query = $this->db->query(
            "SELECT * from  tmst_size_item_grid
            where Is_Active = 1 
            and (Initial_Size like '$search%') 
            order by Item_Height, Item_Width, Item_Length"
        );

        if ($query->num_rows() > 0) {
            $list = array();
            $key = 1;
            foreach ($query->result_array() as $row) {
                $list[$key]['id'] = $row['SysId'];
                $list[$key]['text'] = $row['Size_Code'];
                $key++;
            }
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function store_editable_placement()
    {
        $sysid = $this->input->post('sysid');

        $row_lot = $this->db->get_where($this->tbl_dtl_lpb, ['sysid' => $sysid])->row();
        $warehouse = $this->db->get_where('tmst_warehouse', ['Warehouse_ID' => $this->input->post('placement')])->row();
        // $pre_oven = $this->db->get_where($this->thst_pre_oven, ['lot' => $row_lot->no_lot]);
        // $bundle_trade = $this->db->get_where($this->thst_bundle_trading, ['lot' => $row_lot->no_lot]);

        $this->db->trans_start();

        $this->db->delete($this->thst_pre_oven, ['lot' => $row_lot->no_lot]);
        $this->db->delete($this->thst_bundle_trading, ['lot' => $row_lot->no_lot]);
        $this->db->delete($this->thst_in_to_oven, ['lot' => $row_lot->no_lot]);
        $this->db->delete($this->thst_out_of_oven, ['lot' => $row_lot->no_lot]);

        $this->db->where('sysid', $sysid);
        $this->db->update('ttrx_dtl_lpb_receive', [
            'placement' => $this->input->post('placement'),
        ]);

        if ($warehouse->Is_Trading_Wh == 1 && $warehouse->Is_Wh_After_Kiln == 0) {
            $table_trans = $this->thst_bundle_trading;
            $remark = 'FORM LPB - TRADING';

            $this->db->insert($table_trans, [
                'lot' => $row_lot->no_lot,
                'placement' => $this->input->post('placement'),
                'do_by' => $this->session->userdata('impsys_initial'),
                'do_time' => $this->DateTime,
                'remark_placement' => $remark
            ]);
        } else if ($warehouse->Is_Trading_Wh == 0 && $warehouse->Is_Wh_After_Kiln == 1) {
            $table_trans = $this->thst_out_of_oven;
            $remark = 'FORM LPB - GRID KAYU KERING';


            $this->db->insert($this->thst_pre_oven, [
                'lot' => $row_lot->no_lot,
                'placement' => 1,
                'do_by' => $this->session->userdata('impsys_initial'),
                'do_time' => $this->DateTime,
                'remark_placement' => $remark
            ]);

            $this->db->insert($this->thst_in_to_oven, [
                'lot' => $row_lot->no_lot,
                'placement' => 23,
                'do_by' => $this->session->userdata('impsys_initial'),
                'do_time' => $this->DateTime,
                'remark_into_oven' => $remark
            ]);

            $this->db->insert($table_trans, [
                'lot' => $row_lot->no_lot,
                'placement' => $this->input->post('placement'),
                'do_by' => $this->session->userdata('impsys_initial'),
                'do_time' => $this->DateTime,
                'remark_out_of_oven' => $remark
            ]);
        } else if ($warehouse->Is_Trading_Wh == 0 && $warehouse->Is_Wh_After_Kiln == 0) {
            $table_trans = $this->thst_pre_oven;
            $remark = 'FORM LPB - GRID KAYU BASAH';

            $this->db->insert($table_trans, [
                'lot' => $row_lot->no_lot,
                'placement' => $this->input->post('placement'),
                'do_by' => $this->session->userdata('impsys_initial'),
                'do_time' => $this->DateTime,
                'remark_placement' => $remark
            ]);
        }

        // public $thst_pre_oven = 'thst_pre_oven';
        // public $thst_in_to_oven = 'thst_in_to_oven';
        // public $thst_out_of_oven = 'thst_out_of_oven';
        // public $thst_bundle_trading = 'thst_bundle_trading';

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal update lokasi penyimpanan bundle !"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "berhasil update lokasi penyimpanan bundle !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }

    public function store_material_dtl_lpb()
    {
        $row_material = $this->db->get_where('tmst_item', ['SysId' => $this->input->post('sysid_material')])->row();
        $rowLot = $this->db->get_where('ttrx_dtl_lpb_receive', ['sysid' =>  $this->input->post('sysid')])->row();

        $this->db->trans_start();
        if (empty($rowLot->sysid_material)) {
            $this->db->where('sysid', $this->input->post('sysid'))->update('ttrx_dtl_lpb_receive', [
                'sysid_material' => $this->input->post('sysid_material'),
                'harga_per_pcs' => 0,
                'lot_printed' => 0,
                'qty' => $row_material->StdQty_Bundle,
                'last_updated_by' => $this->session->userdata('impsys_initial'),
            ]);
            $code = 200;
        } else {
            $this->db->where(['Id_Lot' => $rowLot->sysid, 'flag >' => 1])->delete($this->ttrx_child_dtl_size_item_lpb);
            $this->db->where(['Id_Lot' => $rowLot->sysid, 'flag' => 1])->update(
                $this->ttrx_child_dtl_size_item_lpb,
                [
                    'Id_Size_Item' => NULL,
                    'Qty'    => 0,
                    'Item_Height' => NULL,
                    'Item_Width' => NULL,
                    'Item_Length' => NULL,
                    'Cubication' => 0
                ]
            );
            $this->db->where('sysid', $this->input->post('sysid'))->update('ttrx_dtl_lpb_receive', [
                'sysid_material' => $this->input->post('sysid_material'),
                'harga_per_pcs' => 0,
                // 'qty' => $row_material->StdQty_Bundle,
                'last_updated_by' => $this->session->userdata('impsys_initial'),
            ]);
            $code = 201;
        }

        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => $error_msg
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => $code,
                "std_qty" => $rowLot->qty,
                "msg" => "berhasil update detail material"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store_size_lot()
    {
        $sysid = $this->input->post('sysid');
        $size_id = $this->input->post('size_id');

        $RowSize = $this->db->get_where($this->tmst_size_item_grid, ['SysId' => $size_id])->row();

        $this->db->trans_start();

        $this->db->where('SysId', $sysid);
        $this->db->update($this->ttrx_child_dtl_size_item_lpb, [
            'Id_Size_Item' => $RowSize->SysId,
            'Item_Height' => $RowSize->Item_Height,
            'Item_Width' => $RowSize->Item_Width,
            'Item_Length'    => $RowSize->Item_Length,
            'Cubication'    => $RowSize->Cubication,
            'Last_updated_at'    => $this->DateTime,
            'Last_updated_by'    => $this->session->userdata('impsys_initial'),
            'Last_update_ip' => $this->help->get_client_ip()
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal update size bundle"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil update size bundle"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store_editable_child_qty()
    {
        $this->db->trans_start();

        $this->db->where('SysId', $this->input->post('pk'));
        $this->db->update('ttrx_child_dtl_size_item_lpb', [
            'Qty' => $this->input->post('value'),
            'Last_updated_by' => $this->session->userdata('impsys_initial'),
            'Last_updated_at' => $this->DateTime,
            'Last_update_ip' => $this->help->get_client_ip()

        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "gagal update quantity bundle"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil update quantity bundle"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function delete_lpb()
    {
        $lpb = $this->input->post('lpb');
        $Bundles = $this->db->get_where('ttrx_dtl_lpb_receive', ['lpb_hdr' => $lpb])->result();

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

        foreach ($Bundles as $li) {
            $this->db->where('lot', $li->no_lot)->delete($this->thst_pre_oven);
            $this->db->where('lot', $li->no_lot)->delete($this->thst_bundle_trading);
            $this->db->where('lot', $li->no_lot)->delete($this->thst_in_to_oven);
            $this->db->where('lot', $li->no_lot)->delete($this->thst_out_of_oven);
            $this->db->where('Id_Lot', $li->sysid)->delete($this->ttrx_child_dtl_size_item_lpb);
        }

        $this->db->where('lpb', $lpb)->delete('ttrx_hdr_lpb_receive');
        $this->db->where('lpb_hdr', $lpb)->delete('ttrx_dtl_lpb_receive');
        // -------------------------------
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
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

        $null_material = $this->db->get_where($this->qview_dtl_size_item_lpb, ['Id_Lot' => $sysid, 'Id_Size_Item' => NULL])->num_rows();
        $qty_0 = $this->db->get_where($this->qview_dtl_size_item_lpb, ['Id_Lot' => $sysid, 'Qty' => 0])->num_rows();
        // if ($null_material == 0 && $qty_0 == 0 && $tot_lot == $lot_printed && $placement == 0) {

        if (empty($row->sysid_material)) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Anda belum memilih item pada bundle : $row->no_lot"
            ]);
        }
        if (empty($row->placement)) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Bundle : $row->no_lot, Tidak memiliki penempatan !"
            ]);
        }
        if ($null_material > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Ada detail data bundle yangg belum memiliki ukuran !"
            ]);
        }
        if ($qty_0 > 0) {
            return $this->help->Fn_resulting_response([
                "code" => 505,
                "msg" => "Ada detail data bundle yang belum mengisi Qty !"
            ]);
        }

        if ($row->sysid_material == '' or $row->sysid_material == null) {
            $response = [
                "code" => 505,
                "msg" => "Harap pastikan anda telah memilih ukuran kayu & quantity nya!"
            ];
        } else {
            if ($row->lot_printed == 0) {
                $this->db->where('sysid', $sysid)->update($this->tbl_dtl_lpb, [
                    'lot_printed' => 1,
                    'first_printed_by' => $this->session->userdata('impsys_initial'),
                    'first_printed_at' => date('Y-m-d H:i:s')
                ]);

                $response = [
                    "code" => 200,
                    "msg" => "Berhasil mengubah status lot menjadi telar di print!"
                ];
            } else {

                $this->db->where('sysid', $sysid)->update($this->tbl_dtl_lpb, [
                    'last_printed_by' => $this->session->userdata('impsys_initial'),
                    'last_printed_at' => date('Y-m-d H:i:s')
                ]);

                $response = [
                    "code" => 201,
                ];
            }
        }
        return $this->help->Fn_resulting_response($response);
    }

    // public function Recalculate_cubication_lpb() // racuk
    // {
    //     $Lpb = $this->input->post('Lpb');

    //     $Sql = $this->db->query("SELECT IFNULL(SUM(((item.Item_Length * item.Item_Width * item.Item_Height) / 1000000) * lot.qty), 0) AS kubikasi
    //                             FROM ttrx_dtl_lpb_receive lot
    //                             JOIN tmst_item item ON lot.sysid_material = item.SysId
    //                             WHERE lot.lpb_hdr = '$Lpb'
    //                             GROUP BY lot.lpb_hdr;
    //                             ")->row();

    //     $response = [
    //         "code" => 200,
    //         'kubikasi' => (empty($Sql->kubikasi) ? 0 : round(floatval($Sql->kubikasi), 2))
    //     ];
    //     return $this->help->Fn_resulting_response($response);
    // }

    public function Recalculate_cubication_lpb()
    {
        $Lpb = $this->input->post('Lpb');

        $Sql = $this->db->query("
        SELECT SUM(Cubication * Qty) as kubikasi
        FROM qview_dtl_size_item_lpb
        where lpb_hdr = '$Lpb' and Cubication > 0
        group by lpb_hdr
        ")->row();

        $response = [
            "code" => 200,
            'kubikasi' => (empty($Sql->kubikasi) ? 0 : $Sql->kubikasi)
        ];
        return $this->help->Fn_resulting_response($response);
    }

    // ======================================= UTILITY DataTable

    public function DataTable_monitoring_grading()
    {
        $query = "SELECT a.sysid, a.status_lpb, a.lpb, b.Account_Code, b.AccountTitle_Code, b.Account_Name as supplier, a.tgl_kirim, a.tgl_finish_sortir,
                    a.grader, COUNT(DISTINCT c.no_lot) as lot, a.legalitas, 
                    COALESCE(SUM(size.Cubication * size.Qty),0) as kubikasi,
                    a.send_to_approval, COUNT(DISTINCT CASE WHEN c.lot_printed = 1 THEN c.sysid ELSE NULL END) as lot_printed
                    from ttrx_hdr_lpb_receive a
                    JOIN tmst_account b on a.id_supplier = b.SysId
                    join ttrx_dtl_lpb_receive c on a.lpb = c.lpb_hdr
                    left join ttrx_child_dtl_size_item_lpb size on c.sysid = size.Id_Lot";

        $where  = array('a.status_lpb !' => 'SELESAI');
        $search = array('a.lpb', 'b.Account_Name', 'a.tgl_kirim', 'a.tgl_finish_sortir', 'a.legalitas', 'a.grader');

        $isWhere = "a.lpb is not null";
        $groupby = 'group by a.lpb';

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query_group_by($query, $search, $where, $isWhere, $groupby);
    }

    public function DT_Lpb_ToApprove()
    {
        $query = "SELECT a.sysid, a.status_lpb, a.lpb, b.Account_Code, b.AccountTitle_Code, b.Account_Name as supplier, a.tgl_kirim, a.tgl_finish_sortir ,
                    a.grader,
                    COUNT(DISTINCT c.no_lot) as lot,
                    a.legalitas, 
                    COALESCE(SUM(size.Cubication * size.Qty),0) as kubikasi,
                    a.send_to_approval,
                    COUNT(DISTINCT CASE WHEN c.lot_printed = 1 THEN c.sysid ELSE NULL END) as lot_printed
                    from ttrx_hdr_lpb_receive a
                    JOIN tmst_account b on a.id_supplier = b.SysId
                    join ttrx_dtl_lpb_receive c on a.lpb = c.lpb_hdr
                    left join ttrx_child_dtl_size_item_lpb size on c.sysid = size.Id_Lot";

        $where  = array('a.status_lpb !' => 'SELESAI', 'a.send_to_approval' => 1);
        $search = array('a.lpb', 'b.Account_Name', 'a.tgl_kirim', 'a.tgl_finish_sortir', 'a.legalitas', 'a.grader');

        $isWhere = "a.lpb is not null";
        $groupby = 'group by a.lpb';

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query_group_by($query, $search, $where, $isWhere, $groupby);
    }
}
