<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TemperaturOven extends CI_Controller
{
    public $Date;
    public $DateTime;
    protected $layout = 'layout';
    protected $tmst_karyawan = 'tmst_karyawan';
    protected $tmst_oven = 'tmst_identity_oven';
    protected $thst_oven = 'thst_temperature_oven';
    protected $ttrx_hdr_temp_oven  = 'ttrx_hdr_temp_oven';
    protected $ttrx_dtl_temp_oven = 'ttrx_dtl_temp_oven';
    protected $tmst_oven_person_teknik  = 'tmst_oven_person_teknik';
    protected $tmst_oven_person_maintenance  = 'tmst_oven_person_maintenance';
    protected $tmst_oven_person_pj  = 'tmst_oven_person_pj';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');

        $this->load->model('m_helper', 'help');
    }

    public function index($id_oven = null)
    {
        $SqlValidate = $this->db->get_where($this->ttrx_hdr_temp_oven, ['SysId_Oven' => $id_oven, 'Doc_Status' => 'RUN']);
        if ($SqlValidate->num_rows() > 0) {
            return redirect("TempOven/TemperaturOven/form_detail_temperature/$id_oven");
        }

        $this->data['page_title'] = "Temperature Oven";
        $this->data['page_content'] = "TempOven/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TempOven/index.js?v=' . time() . '"></script>';

        $this->data['oven'] = $this->db->get_where($this->tmst_oven, ['sysid' => $id_oven])->row();
        $this->load->view($this->layout, $this->data);
    }

    public function form_detail_temperature($id_oven)
    {
        $this->data['page_title'] = "Temperature Oven";
        $this->data['page_content'] = "TempOven/form_detail_temp";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TempOven/form_detail_temp.js?v=' . time() . '"></script>';

        $this->data['running_oven'] = $this->db->get_where($this->ttrx_hdr_temp_oven, ['SysId_Oven' => $id_oven, 'Doc_Status' => 'RUN'])->row();
        $this->data['oven'] = $this->db->get_where($this->tmst_oven, ['sysid' => $id_oven])->row();
        $this->load->view($this->layout, $this->data);
    }

    public function Monitoring_history_temp_oven()
    {
        $this->data['page_title'] = "Monitoring Temperature Oven";
        $this->data['page_content'] = "TempOven/monitoring_temp_oven";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TempOven/monitoring_temp_oven.js?v=' . time() . '"></script>';

        $this->data['oven'] = $this->db->get_where($this->tmst_oven, ['is_active' => '1'])->row();
        $this->load->view($this->layout, $this->data);
    }

    public function store_hdr_temperature()
    {
        $DocNo = $this->help->Gnrt_Identity_Number_DocOven('KDP', $this->input->post('oven'));
        $dataArray = [
            "Doc_No" => $DocNo,
            "Doc_Status" => "RUN",
            "SysId_Oven" => $this->input->post('oven'),
            "Doc_Date" => $this->input->post('Doc_Date'),
            "R_Boiler_Pj_Oven" => $this->input->post('R_Boiler_Pj_Oven'),
            "R_Boiler_Mtc" => $this->input->post('R_Boiler_Mtc'),
            "R_Boiler_Teknik" => $this->input->post('R_Boiler_Teknik'),
            "Cerobong_Pj_Oven" => $this->input->post('Cerobong_Pj_Oven'),
            "Cerobong_Mtc" => $this->input->post('Cerobong_Mtc'),
            "Cerobong_Teknik" => $this->input->post('Cerobong_Teknik'),
            "Cyclon1_Pj_Oven" => $this->input->post('Cyclon1_Pj_Oven'),
            "Cyclon1_Mtc" => $this->input->post('Cyclon1_Mtc'),
            "Cyclon1_Teknik" => $this->input->post('Cyclon1_Teknik'),
            "Cyclon2_Pj_Oven" => $this->input->post('Cyclon2_Pj_Oven'),
            "Cyclon2_Mtc" => $this->input->post('Cyclon2_Mtc'),
            "Cyclon2_Teknik" => $this->input->post('Cyclon2_Teknik'),
            "R_Oven_Pj_Oven" => $this->input->post('R_Oven_Pj_Oven'),
            "R_Oven_Mtc" => $this->input->post('R_Oven_Mtc'),
            "R_Oven_Teknik" => $this->input->post('R_Oven_Teknik'),
            "Catatan_Kebersihan" => $this->input->post('Catatan_Kebersihan'),
            "Fas_Boiler_Pj_Oven" => $this->input->post('Fas_Boiler_Pj_Oven'),
            "Fas_Boiler_Mtc" => $this->input->post('Fas_Boiler_Mtc'),
            "Fas_Boiler_Teknik" => $this->input->post('Fas_Boiler_Teknik'),
            "Fas_PompaSirkulasi_Pj_Oven" => $this->input->post('Fas_PompaSirkulasi_Pj_Oven'),
            "Fas_PompaSirkulasi_Mtc" => $this->input->post('Fas_PompaSirkulasi_Mtc'),
            "Fas_PompaSirkulasi_Teknik" => $this->input->post('Fas_PompaSirkulasi_Teknik'),
            "Fas_Blowler_Pj_Oven" => $this->input->post('Fas_Blowler_Pj_Oven'),
            "Fas_Blowler_Mtc" => $this->input->post('Fas_Blowler_Mtc'),
            "Fas_Blowler_Teknik" => $this->input->post('Fas_Blowler_Teknik'),
            "Fas_Kipas_AtapGel_Pj_Oven" => $this->input->post('Fas_Kipas_AtapGel_Pj_Oven'),
            "Fas_Kipas_AtapGel_Mtc" => $this->input->post('Fas_Kipas_AtapGel_Mtc'),
            "Fas_Kipas_AtapGel_Teknik" => $this->input->post('Fas_Kipas_AtapGel_Teknik'),
            "Fas_Demper_Pj_Oven" => $this->input->post('Fas_Demper_Pj_Oven'),
            "Fas_Demper_Mtc" => $this->input->post('Fas_Demper_Mtc'),
            "Fas_Demper_Teknik" => $this->input->post('Fas_Demper_Teknik'),
            "Fas_AirToren_Atas_Pj_Oven" => $this->input->post('Fas_AirToren_Atas_Pj_Oven'),
            "Fas_AirToren_Atas_Mtc" => $this->input->post('Fas_AirToren_Atas_Mtc'),
            "Fas_AirToren_Atas_Teknik" => $this->input->post('Fas_AirToren_Atas_Teknik'),
            "Fas_AirToren_Bawah_Pj_Oven" => $this->input->post('Fas_AirToren_Bawah_Pj_Oven'),
            "Fas_AirToren_Bawah_Mtc" => $this->input->post('Fas_AirToren_Bawah_Mtc'),
            "Fas_AirToren_Bawah_Teknik" => $this->input->post('Fas_AirToren_Bawah_Teknik'),
            "Fas_Sensor_Inti_Suhu_Kayu_Pj_Oven" => $this->input->post('Fas_Sensor_Inti_Suhu_Kayu_Pj_Oven'),
            "Fas_Sensor_Inti_Suhu_Kayu_Mtc" => $this->input->post('Fas_Sensor_Inti_Suhu_Kayu_Mtc'),
            "Fas_Sensor_Inti_Suhu_Kayu_Teknik" => $this->input->post('Fas_Sensor_Inti_Suhu_Kayu_Teknik'),
            "Fas_Sensor_Mc_Pj_Oven" => $this->input->post('Fas_Sensor_Mc_Pj_Oven'),
            "Fas_Sensor_Mc_Mtc" => $this->input->post('Fas_Sensor_Mc_Mtc'),
            "Fas_Sensor_Mc_Teknik" => $this->input->post('Fas_Sensor_Mc_Teknik'),
            "Fas_Sensor_DB_WB_Pj_Oven" => $this->input->post('Fas_Sensor_DB_WB_Pj_Oven'),
            "Fas_Sensor_DB_WB_Mtc" => $this->input->post('Fas_Sensor_DB_WB_Mtc'),
            "Fas_Sensor_DB_WB_Teknik" => $this->input->post('Fas_Sensor_DB_WB_Teknik'),
            "Fas_Air_WB_Pj_Oven" => $this->input->post('Fas_Air_WB_Pj_Oven'),
            "Fas_Air_WB_Mtc" => $this->input->post('Fas_Air_WB_Mtc'),
            "Fas_Air_WB_Teknik" => $this->input->post('Fas_Air_WB_Teknik'),
            "Fas_KainKasa_Pj_Oven" => $this->input->post('Fas_KainKasa_Pj_Oven'),
            "Fas_KainKasa_Mtc" => $this->input->post('Fas_KainKasa_Mtc'),
            "Fas_KainKasa_Teknik" => $this->input->post('Fas_KainKasa_Teknik'),
            "Fas_PanelBox_Pj_Oven" => $this->input->post('Fas_PanelBox_Pj_Oven'),
            "Fas_PanelBox_Mtc" => $this->input->post('Fas_PanelBox_Mtc'),
            "Fas_PanelBox_Teknik" => $this->input->post('Fas_PanelBox_Teknik'),
            "Fas_Panel_DB_WB_Pj_Oven" => $this->input->post('Fas_Panel_DB_WB_Pj_Oven'),
            "Fas_Panel_DB_WB_Mtc" => $this->input->post('Fas_Panel_DB_WB_Mtc'),
            "Fas_Panel_DB_WB_Teknik" => $this->input->post('Fas_Panel_DB_WB_Teknik'),
            "Fas_Panel_Suhu_Inti_Kayu_Pj_Oven" => $this->input->post('Fas_Panel_Suhu_Inti_Kayu_Pj_Oven'),
            "Fas_Panel_Suhu_Inti_Kayu_Mtc" => $this->input->post('Fas_Panel_Suhu_Inti_Kayu_Mtc'),
            "Fas_Panel_Suhu_Inti_Kayu_Teknik" => $this->input->post('Fas_Panel_Suhu_Inti_Kayu_Teknik'),
            "Catatan_Fasilitas" => $this->input->post('Catatan_Fasilitas'),
            "Pj_Oven" => $this->input->post('Pj_Oven'),
            "Maintenance" => $this->input->post('Maintenance'),
            "M_Teknik" => $this->input->post('M_Teknik'),
            "Created_by" => $this->session->userdata('impsys_initial'),
            "Created_at" => $this->DateTime,
            "Last_Updated_by" => null,
            "Last_Updated_at" => $this->DateTime,
        ];

        $this->db->trans_start();

        $this->db->insert($this->ttrx_hdr_temp_oven, $dataArray);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code" => 505,
                "msg" => "gagal membuat formulir checklist oven !"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil menyimpan formulir checklist oven, anda dapat mengisi riwayat data temperatur sekarang !",
                "id_oven" => $this->input->post('oven')
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function store_temperature()
    {
        $dataArray = [
            "SysId_Hdr" => $this->input->post('sysid_hdr'),
            "Doc_No_Hdr" => $this->input->post('Doc_No'),
            "Date" => $this->input->post('Tgl'),
            "Time" => $this->input->post('JamMenit'),
            "KADAR_AIR_MC1" => str_replace(",", ".", $this->input->post('MC1')),
            "KADAR_AIR_MC2" => str_replace(",", ".", $this->input->post('MC2')),
            "KADAR_AIR_MC3" => str_replace(",", ".", $this->input->post('MC3')),
            "SIK_T1" => str_replace(",", ".", $this->input->post('T1')),
            "SIK_T2" => str_replace(",", ".", $this->input->post('T2')),
            "SIK_T3" => str_replace(",", ".", $this->input->post('T3')),
            "BOILER_SET" => str_replace(",", ".", $this->input->post('Boiler_Set')),
            "BOILER_ACT" => str_replace(",", ".", $this->input->post('Boiler_Act')),
            "DRY_BULB_SET" => str_replace(",", ".", $this->input->post('DryBulb_Set')),
            "DRY_BULB_ACT" => str_replace(",", ".", $this->input->post('DryBulb_Act')),
            "WET_BULD_SET" => str_replace(",", ".", $this->input->post('WetBuld_Set')),
            "WET_BULD_ACT" => str_replace(",", ".", $this->input->post('WetBuld_Act')),
            "Keterangan" => $this->input->post('Keterangan'),
            "Created_by" => $this->session->userdata('impsys_initial'),
            "Created_at" => $this->DateTime,
            "Last_Updated_by" => null,
            "Last_Updated_at" => $this->DateTime,
        ];

        $this->db->trans_start();

        $this->db->insert($this->ttrx_dtl_temp_oven, $dataArray);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $response = [
                "code" => 505,
                "msg" => "gagal menambahkan detail temperatur oven !"
            ];
        } else {
            $response = [
                "code" => 200,
                "msg" => "berhasil menyimpan detail temperatur oven !",
                "id_hdr" => $this->input->post('sysid_hdr')
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function delete_temperature()
    {
        $this->db->trans_start();

        $this->db->where('SysId', $this->input->post('SysId'));
        $this->db->delete($this->ttrx_dtl_temp_oven);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $response = ['code' => 505, 'msg' => 'data temperatur gagal dihapus!'];
        } else {
            $response = ['code' => 200, 'msg' => 'data temperatur berhasil dihapus!'];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function delete_doc_temperature()
    {
        $SysId = $this->input->post('SysId');

        // insert hdr ke thst
        $this->db->query(
            "insert into thst_hdr_temp_oven (SysId, Doc_No, SysId_Oven, R_Boiler_Pj_Oven, R_Boiler_Mtc, R_Boiler_Teknik, Cerobong_Pj_Oven, Cerobong_Mtc, Cerobong_Teknik, Cyclon1_Pj_Oven, Cyclon1_Mtc, Cyclon1_Teknik, Cyclon2_Pj_Oven, Cyclon2_Mtc, Cyclon2_Teknik, R_Oven_Pj_Oven, R_Oven_Mtc, R_Oven_Teknik, Fas_Boiler_Pj_Oven, Fas_Boiler_Mtc, Fas_Boiler_Teknik, Fas_PompaSirkulasi_Pj_Oven, Fas_PompaSirkulasi_Mtc, Fas_PompaSirkulasi_Teknik, Fas_Blowler_Pj_Oven, Fas_Blowler_Mtc, Fas_Blowler_Teknik, Fas_Kipas_AtapGel_Pj_Oven, Fas_Kipas_AtapGel_Mtc, Fas_Kipas_AtapGel_Teknik, Fas_Demper_Pj_Oven, Fas_Demper_Mtc, Fas_Demper_Teknik, Fas_AirToren_Atas_Pj_Oven, Fas_AirToren_Atas_Mtc, Fas_AirToren_Atas_Teknik, Fas_AirToren_Bawah_Pj_Oven, Fas_AirToren_Bawah_Mtc, Fas_AirToren_Bawah_Teknik, Fas_Sensor_Inti_Suhu_Kayu_Pj_Oven, Fas_Sensor_Inti_Suhu_Kayu_Mtc, Fas_Sensor_Inti_Suhu_Kayu_Teknik, Fas_Sensor_Mc_Pj_Oven, Fas_Sensor_Mc_Mtc, Fas_Sensor_Mc_Teknik, Fas_Sensor_DB_WB_Pj_Oven, Fas_Sensor_DB_WB_Mtc, Fas_Sensor_DB_WB_Teknik, Fas_Air_WB_Pj_Oven, Fas_Air_WB_Mtc, Fas_Air_WB_Teknik, Fas_KainKasa_Pj_Oven, Fas_KainKasa_Mtc, Fas_KainKasa_Teknik, Fas_PanelBox_Pj_Oven, Fas_PanelBox_Mtc, Fas_PanelBox_Teknik, Fas_Panel_DB_WB_Pj_Oven, Fas_Panel_DB_WB_Mtc, Fas_Panel_DB_WB_Teknik, Fas_Panel_Suhu_Inti_Kayu_Pj_Oven, Fas_Panel_Suhu_Inti_Kayu_Mtc, Fas_Panel_Suhu_Inti_Kayu_Teknik, Catatan_Kebersihan, Catatan_Fasilitas, Pj_Oven, Maintenance, M_Teknik, Doc_Status, Finish_At, Created_by, Created_at, Last_Updated_by, Last_Updated_at, Doc_Date)
            SELECT SysId, Doc_No, SysId_Oven, R_Boiler_Pj_Oven, R_Boiler_Mtc, R_Boiler_Teknik, Cerobong_Pj_Oven, Cerobong_Mtc, Cerobong_Teknik, Cyclon1_Pj_Oven, Cyclon1_Mtc, Cyclon1_Teknik, Cyclon2_Pj_Oven, Cyclon2_Mtc, Cyclon2_Teknik, R_Oven_Pj_Oven, R_Oven_Mtc, R_Oven_Teknik, Fas_Boiler_Pj_Oven, Fas_Boiler_Mtc, Fas_Boiler_Teknik, Fas_PompaSirkulasi_Pj_Oven, Fas_PompaSirkulasi_Mtc, Fas_PompaSirkulasi_Teknik, Fas_Blowler_Pj_Oven, Fas_Blowler_Mtc, Fas_Blowler_Teknik, Fas_Kipas_AtapGel_Pj_Oven, Fas_Kipas_AtapGel_Mtc, Fas_Kipas_AtapGel_Teknik, Fas_Demper_Pj_Oven, Fas_Demper_Mtc, Fas_Demper_Teknik, Fas_AirToren_Atas_Pj_Oven, Fas_AirToren_Atas_Mtc, Fas_AirToren_Atas_Teknik, Fas_AirToren_Bawah_Pj_Oven, Fas_AirToren_Bawah_Mtc, Fas_AirToren_Bawah_Teknik, Fas_Sensor_Inti_Suhu_Kayu_Pj_Oven, Fas_Sensor_Inti_Suhu_Kayu_Mtc, Fas_Sensor_Inti_Suhu_Kayu_Teknik, Fas_Sensor_Mc_Pj_Oven, Fas_Sensor_Mc_Mtc, Fas_Sensor_Mc_Teknik, Fas_Sensor_DB_WB_Pj_Oven, Fas_Sensor_DB_WB_Mtc, Fas_Sensor_DB_WB_Teknik, Fas_Air_WB_Pj_Oven, Fas_Air_WB_Mtc, Fas_Air_WB_Teknik, Fas_KainKasa_Pj_Oven, Fas_KainKasa_Mtc, Fas_KainKasa_Teknik, Fas_PanelBox_Pj_Oven, Fas_PanelBox_Mtc, Fas_PanelBox_Teknik, Fas_Panel_DB_WB_Pj_Oven, Fas_Panel_DB_WB_Mtc, Fas_Panel_DB_WB_Teknik, Fas_Panel_Suhu_Inti_Kayu_Pj_Oven, Fas_Panel_Suhu_Inti_Kayu_Mtc, Fas_Panel_Suhu_Inti_Kayu_Teknik, Catatan_Kebersihan, Catatan_Fasilitas, Pj_Oven, Maintenance, M_Teknik, Doc_Status, Finish_At, Created_by, Created_at, Last_Updated_by, Last_Updated_at, Doc_Date
            FROM ttrx_hdr_temp_oven where SysId = '$SysId'"
        );

        // insert dtl ke hst 
        $this->db->query(
            "insert into thst_dtl_temp_oven (SysId, SysId_Hdr, Doc_No_Hdr, `Date`, `Time`, KADAR_AIR_MC1, KADAR_AIR_MC2, KADAR_AIR_MC3, SIK_T1, SIK_T2, SIK_T3, BOILER_SET, BOILER_ACT, DRY_BULB_SET, DRY_BULB_ACT, WET_BULD_SET, WET_BULD_ACT, Keterangan, Created_By, Created_At, Last_Updated_By, Last_Updated_At)
            select SysId, SysId_Hdr, Doc_No_Hdr, `Date`, `Time`, KADAR_AIR_MC1, KADAR_AIR_MC2, KADAR_AIR_MC3, SIK_T1, SIK_T2, SIK_T3, BOILER_SET, BOILER_ACT, DRY_BULB_SET, DRY_BULB_ACT, WET_BULD_SET, WET_BULD_ACT, Keterangan, Created_By, Created_At, Last_Updated_By, Last_Updated_At from ttrx_dtl_temp_oven 
            where SysId_Hdr = '$SysId'"
        );

        $this->db->trans_start();

        $this->db->delete($this->ttrx_dtl_temp_oven, array('SysId_Hdr' => $SysId));
        $this->db->delete($this->ttrx_hdr_temp_oven, array('SysId' => $SysId));

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $response = ['code' => 505, 'msg' => 'data dokumen temperatur gagal dihapus!'];
        } else {
            $response = ['code' => 200, 'msg' => 'data dokumen temperatur berhasil dihapus!'];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function Set_Oven_To_Off()
    {
        $dataArray = [
            'Doc_Status' => 'FINISH',
            'Finish_At'  => $this->DateTime
        ];

        $this->db->trans_start();

        $this->db->where('SysId', $this->input->post('SysId'));
        $this->db->update($this->ttrx_hdr_temp_oven, $dataArray);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $response = ['code' => 505, 'msg' => 'Error, terjadi kesalahan saat merubah status menjadi finish !'];
        } else {
            $response = ['code' => 200, 'msg' => 'Status siklus oven berhasil diubah menjadi finish !'];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function re_run_temp_doc()
    {
        $SysId = $this->input->post('SysId');

        $RowDoc = $this->db->get_where($this->ttrx_hdr_temp_oven, ["SysId" => $SysId])->row();

        $SqlValidateRedundantRun = $this->db->get_where($this->ttrx_hdr_temp_oven, ['SysId_Oven' => $RowDoc->SysId_Oven, 'Doc_Status' => 'RUN']);
        if ($SqlValidateRedundantRun->num_rows() > 0) {
            $Redundant = $SqlValidateRedundantRun->row();
            return $this->help->Fn_resulting_response(['code' => 500, 'msg' => 'Dokumen ' . $Redundant->Doc_No . ' running, system hanya bisa running 1 dokumen ! harap ubah status dokumen untuk merunning dokumen ini']);
        }

        $Finish_At = strtotime($RowDoc->Finish_At);
        $NowTime = time();
        $Selisih = $NowTime - $Finish_At;
        $Selisih_jam = $Selisih / (60 * 60);
        if (floatval($Selisih_jam) > 25) {
            return $this->help->Fn_resulting_response(['code' => 500, 'msg' => 'Document ini telah finish lebih dari 24jam, anda hanya dapat menjalankan ulang document temperatur sebelum 24jam dari waktu finish !']);
        }

        $this->db->trans_start();

        $this->db->where('SysId', $SysId);
        $this->db->update($this->ttrx_hdr_temp_oven, [
            'Doc_Status' => 'RUN'
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $response = ['code' => 505, 'msg' => 'Error, terjadi kesalahan saat merubah status menjadi Running !'];
        } else {
            $response = ['code' => 200, 'msg' => 'Status siklus oven berhasil diubah menjadi Running !'];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function Print_FormChecklist_Oven($SysId)
    {
        $this->data['Hdr'] = $this->db->get_where($this->ttrx_hdr_temp_oven, ['SysId' => $SysId])->row();
        $this->data['Dtls'] = $this->db->get_where($this->ttrx_dtl_temp_oven, ['SysId_Hdr' => $SysId])->result();
        $this->data['Oven'] = $this->db->get_where($this->tmst_oven, ['sysid' => $this->data['Hdr']->SysId_Oven])->row();

        return $this->load->view('TempOven/Rpt_form_checklist', $this->data);
    }

    public function Print_List_Temperature($SysId)
    {
        $this->data['Hdr'] = $this->db->get_where($this->ttrx_hdr_temp_oven, ['SysId' => $SysId])->row();
        $this->data['Dtls'] = $this->db->get_where($this->ttrx_dtl_temp_oven, ['SysId_Hdr' => $SysId])->result();

        return $this->load->view('TempOven/Rpt_detail_temp', $this->data);
    }



    // ---------------------- SELECT 2

    public function get_master_pj_oven()
    {
        $query = $this->db->query("SELECT UserName , nama from $this->tmst_oven_person_pj join $this->tmst_karyawan on initial = UserName order by nama");

        if ($query->num_rows() > 0) {
            $list = array();
            $nestedData = array();
            $nestedData['id'] = '';
            $nestedData['text'] = "-- Pilih --";
            $list[] = $nestedData;
            foreach ($query->result_array() as $row) {
                $nestedData = array();
                $nestedData['id'] = $row['UserName'];
                $nestedData['text'] = $row['nama'];

                $list[] = $nestedData;
            }
            //----------------------------------------------------------------------------------
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function get_master_maintenance()
    {
        $query = $this->db->query("SELECT UserName , nama from $this->tmst_oven_person_maintenance join $this->tmst_karyawan on initial = UserName order by nama");

        if ($query->num_rows() > 0) {
            $list = array();
            $nestedData = array();
            $nestedData['id'] = '';
            $nestedData['text'] = "-- Pilih --";
            $list[] = $nestedData;
            foreach ($query->result_array() as $row) {
                $nestedData = array();
                $nestedData['id'] = $row['UserName'];
                $nestedData['text'] = $row['nama'];

                $list[] = $nestedData;
            }
            //----------------------------------------------------------------------------------
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }

    public function get_master_teknik()
    {
        $query = $this->db->query("SELECT UserName , nama from $this->tmst_oven_person_teknik join $this->tmst_karyawan on initial = UserName order by nama");

        if ($query->num_rows() > 0) {
            $list = array();
            $nestedData = array();
            $nestedData['id'] = '';
            $nestedData['text'] = "-- Pilih --";
            $list[] = $nestedData;
            foreach ($query->result_array() as $row) {
                $nestedData = array();
                $nestedData['id'] = $row['UserName'];
                $nestedData['text'] = $row['nama'];

                $list[] = $nestedData;
            }
            //----------------------------------------------------------------------------------
            echo json_encode($list);
        } else {
            echo "hasil kosong";
        }
    }


    // ---------------------- DATATABLE

    public function DataTable_Temperature_Oven()
    {
        $sysid_hdr = $this->input->get('sysid_hdr');
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'ttrx_dtl_temp_oven.SysId',
            1 => 'Date',
            2 => 'Time',
            3 => 'KADAR_AIR_MC1',
            4 => 'KADAR_AIR_MC2',
            5 => 'KADAR_AIR_MC3',
            6 => 'SIK_T1',
            7 => 'SIK_T2',
            8 => 'SIK_T3',
            9 => 'BOILER_SET',
            10 => 'BOILER_ACT',
            11 => 'DRY_BULB_SET',
            12 => 'DRY_BULB_ACT',
            13 => 'WET_BULD_SET',
            14 => 'WET_BULD_ACT',
            15 => 'Keterangan',
            16 => 'nama',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT ttrx_dtl_temp_oven.SysId, SysId_Hdr, Doc_No_Hdr, `Date`, `Time`, KADAR_AIR_MC1, KADAR_AIR_MC2, KADAR_AIR_MC3, SIK_T1, SIK_T2, SIK_T3, BOILER_SET, BOILER_ACT, DRY_BULB_SET, DRY_BULB_ACT, WET_BULD_SET, WET_BULD_ACT, Keterangan, nama
        FROM ttrx_dtl_temp_oven
        LEFT JOIN tmst_karyawan on ttrx_dtl_temp_oven.Created_By = tmst_karyawan.initial where SysId_Hdr = '$sysid_hdr'";

        $totalData = $this->db->query($sql)->num_rows();

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (Date LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Time LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR tmst_karyawan.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR Keterangan LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId'] = $row["SysId"];
            $nestedData['Date'] = $row['Date'];
            $nestedData['Time'] = $row['Time'];
            $nestedData['KADAR_AIR_MC1'] = floatval($row['KADAR_AIR_MC1']);
            $nestedData['KADAR_AIR_MC2'] = floatval($row['KADAR_AIR_MC2']);
            $nestedData['KADAR_AIR_MC3'] = floatval($row['KADAR_AIR_MC3']);
            $nestedData['SIK_T1'] = floatval($row['SIK_T1']);
            $nestedData['SIK_T2'] = floatval($row['SIK_T2']);
            $nestedData['SIK_T3'] = floatval($row['SIK_T3']);
            $nestedData['BOILER_SET'] = floatval($row['BOILER_SET']);
            $nestedData['BOILER_ACT'] = floatval($row['BOILER_ACT']);
            $nestedData['DRY_BULB_SET'] = floatval($row['DRY_BULB_SET']);
            $nestedData['DRY_BULB_ACT'] = floatval($row['DRY_BULB_ACT']);
            $nestedData['WET_BULD_SET'] = floatval($row['WET_BULD_SET']);
            $nestedData['WET_BULD_ACT'] = floatval($row['WET_BULD_ACT']);
            $nestedData['Keterangan'] = $row['Keterangan'];
            $nestedData['nama'] = $row['nama'];

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

    public function DataTable_Monitoring_Hdr_Temp()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'hdr.SysId',
            1 => 'hdr.Doc_No',
            2 => 'hdr.Doc_Date',
            3 => 'hdr.Doc_Status',
            4 => 'hdr.SysId_Oven',
            5 => 'tmst_identity_oven.nama',
            6 => 'tk_pj_oven.nama ',
            7 => 'tk_maintenance.nama ',
            8 => 'tk_m_teknik.nama ',
            9 => 'hdr.Created_by',
            10 => 'hdr.Created_at',
            11 => 'hdr.Last_Updated_by',
            12 => 'hdr.Last_Updated_at'

        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT hdr.SysId, hdr.Doc_No, 
        hdr.SysId_Oven, tmst_identity_oven.nama as nama_oven, 
        hdr.Pj_Oven, tk_pj_oven.nama as nama_pj_oven, 
        hdr.Maintenance, tk_maintenance.nama as nama_maintenance,
        hdr.M_Teknik, tk_m_teknik.nama as nama_m_teknik,
        hdr.Doc_Status, hdr.Created_by, hdr.Created_at, hdr.Last_Updated_by, hdr.Last_Updated_at, hdr.Doc_Date
        FROM ttrx_hdr_temp_oven hdr
        LEFT JOIN tmst_karyawan tk_pj_oven on hdr.Pj_Oven = tk_pj_oven.initial
        LEFT JOIN tmst_karyawan tk_maintenance on hdr.Maintenance = tk_maintenance.initial
        LEFT JOIN tmst_karyawan tk_m_teknik on hdr.M_Teknik = tk_m_teknik.initial
        LEFT JOIN tmst_identity_oven on hdr.SysId_Oven = tmst_identity_oven.sysid 
        WHERE hdr.Doc_Status <> 'RUN'";

        $totalData = $this->db->query($sql)->num_rows();

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (hdr.Doc_No LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR hdr.Doc_Date LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR tmst_identity_oven.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR tk_pj_oven.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR tk_maintenance.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR tk_m_teknik.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR hdr.Doc_Status LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";

        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['SysId'] = $row['SysId'];
            $nestedData['Doc_No'] = $row['Doc_No'];
            $nestedData['Doc_Date'] = $row['Doc_Date'];
            $nestedData['Doc_Status'] = $row['Doc_Status'];
            $nestedData['SysId_Oven'] = $row['SysId_Oven'];
            $nestedData['nama_oven'] = $row['nama_oven'];
            $nestedData['nama_pj_oven'] = $row['nama_pj_oven'];
            $nestedData['nama_maintenance'] = $row['nama_maintenance'];
            $nestedData['nama_m_teknik'] = $row['nama_m_teknik'];
            $nestedData['Created_by'] = $row['Created_by'];
            $nestedData['Created_at'] = $row['Created_at'];
            $nestedData['Last_Updated_by'] = $row['Last_Updated_by'];
            $nestedData['Last_Updated_at'] = $row['Last_Updated_at'];


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


// --------- Penambahan Table 
// ttrx_hdr_temp_oven
// tmst_oven_person_teknik
// tmst_oven_person_maintenance
// tmst_oven_person_pj
// ttrx_dtl_temp_oven


// query normalisasi suhu 
// "UPDATE ttrx_dtl_temp_oven
// SET 
//     KADAR_AIR_MC1 = CASE WHEN KADAR_AIR_MC1 > 100 THEN KADAR_AIR_MC1 / 10 ELSE KADAR_AIR_MC1 END,
//     KADAR_AIR_MC2 = CASE WHEN KADAR_AIR_MC2 > 100 THEN KADAR_AIR_MC2 / 10 ELSE KADAR_AIR_MC2 END,
//     KADAR_AIR_MC3 = CASE WHEN KADAR_AIR_MC3 > 100 THEN KADAR_AIR_MC3 / 10 ELSE KADAR_AIR_MC3 END,
//     SIK_T1 = CASE WHEN SIK_T1 > 100 THEN SIK_T1 / 10 ELSE SIK_T1 END,
//     SIK_T2 = CASE WHEN SIK_T2 > 100 THEN SIK_T2 / 10 ELSE SIK_T2 END,
//     SIK_T3 = CASE WHEN SIK_T3 > 100 THEN SIK_T3 / 10 ELSE SIK_T3 END,
//     BOILER_SET = CASE WHEN BOILER_SET > 100 THEN BOILER_SET / 10 ELSE BOILER_SET END,
//     BOILER_ACT = CASE WHEN BOILER_ACT > 100 THEN BOILER_ACT / 10 ELSE BOILER_ACT END,
//     DRY_BULB_SET = CASE WHEN DRY_BULB_SET > 100 THEN DRY_BULB_SET / 10 ELSE DRY_BULB_SET END,
//     DRY_BULB_ACT = CASE WHEN DRY_BULB_ACT > 100 THEN DRY_BULB_ACT / 10 ELSE DRY_BULB_ACT END,
//     WET_BULD_SET = CASE WHEN WET_BULD_SET > 100 THEN WET_BULD_SET / 10 ELSE WET_BULD_SET END,
//     WET_BULD_ACT = CASE WHEN WET_BULD_ACT > 100 THEN WET_BULD_ACT / 10 ELSE WET_BULD_ACT END";
