<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SubkonKiln extends CI_Controller
{
    public $layout = 'layout';
    public $qmst_item = 'qmst_item';
    public $tmst_warehouse = 'tmst_warehouse';
    public $ttrx_dtl_subkon_kiln = 'ttrx_dtl_subkon_kiln';
    public $ttrx_hdr_subkon_kiln = 'ttrx_hdr_subkon_kiln';
    public $qview_list_hdr_subkon_kiln = 'qview_list_hdr_subkon_kiln';
    protected $qview_dtl_size_item_lpb = 'qview_dtl_size_item_lpb';
    public $tmst_account = 'tmst_account';
    public $column_dtl = [];
    public $column_hdr = [];

    protected $Counter_Length = 4;
    protected $Pattern_DocNo = 'SKN';
    protected $Concate_DocNo = '-';

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
        $this->data['page_title'] = "List Data Alokasi Subkon Kiln";
        $this->data['page_content'] = "TrxWh/SubkonKiln/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/SubkonKiln/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function approval()
    {
        $this->data['page_title'] = "List Data Alokasi Subkon Kiln";
        $this->data['page_content'] = "TrxWh/SubkonKiln/approval";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/SubkonKiln/approval.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function add()
    {
        $this->data['page_title'] = "Add Data Alokasi Subkon Kiln";
        $this->data['page_content'] = "TrxWh/SubkonKiln/add";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/SubkonKiln/add.js?v=' . time() . '"></script>';

        $this->data['List_Vendor'] = $this->db
            ->where('Category_ID', 'VP')
            ->where('Is_Active', 1)
            ->get($this->tmst_account);

        $this->data['Transport_with'] = $this->db->get_where('tmst_transport_with', ['Is_Active' => 1])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function edit($SysId, $action)
    {
        $this->data['page_title'] = "Add Data Alokasi Subkon Kiln";
        $this->data['page_content'] = "TrxWh/SubkonKiln/edit";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/SubkonKiln/edit.js?v=' . time() . '"></script>';

        $this->data['List_Vendor'] = $this->db
            ->where('Category_ID', 'VP')
            ->where('Is_Active', 1)
            ->get($this->tmst_account);

        $this->data['Transport_with'] = $this->db->get_where('tmst_transport_with', ['Is_Active' => 1])->result();

        $this->data['Hdr'] = $this->db->get_where($this->qview_list_hdr_subkon_kiln, ['SysID' => $SysId])->row();
        $this->data['Dtls'] = $this->db->get_where('qview_list_dtl_subkon_kiln', ['SysId_Hdr' => $SysId])->result();
        $this->data['action'] = $action;

        $this->load->view($this->layout, $this->data);
    }

    public function store()
    {
        $Doc_Number = $this->help->Gnrt_Identity_Monthly($this->Pattern_DocNo, $this->Counter_Length, $this->Concate_DocNo);
        $Lots = $this->input->post('Lot');
        $Start_times = $this->input->post('Start_time');
        $End_times = $this->input->post('End_Time');

        $this->db->trans_start();
        $this->db->insert($this->ttrx_hdr_subkon_kiln, [
            'DocNo' => $Doc_Number,
            'DocDate' => $this->input->post('DocDate'),
            'Ref_Number' => $this->input->post('Ref_Number'),
            'Account_Code' => $this->input->post('Account_Code'),
            'Account_Addess_ID' => $this->input->post('Account_Address_ID'),
            'Waktu_Keberangkatan' => $this->input->post('Waktu_Keberangkatan'),
            'Waktu_Kepulangan' => $this->input->post('Waktu_Kepulangan'),
            'Estimasi_Mulai_Kiln' => $this->input->post('Estimasi_Mulai_Kiln'),
            'Estimasi_Selesai_Kln' => $this->input->post('Estimasi_Selesai_Kln'),
            'Jenis_Kendaraan' => $this->input->post('Jenis_Kendaraan'),
            'No_Polisi' => $this->input->post('No_Polisi'),
            'Note' => $this->input->post('Note'),
            // 'Is_Approve' => $this->input->post(''),
            // 'Aprove_By' => $this->input->post(''),
            // 'Approve_Time' => $this->input->post(''),
            'Created_By' => $this->session->userdata('impsys_initial'),
            'Created_Time' => $this->DateTime,
            'Created_IP' => $this->help->get_client_ip(),
            // 'Is_Cancel' => $this->input->post(''),
            // 'Cancel_Time' => $this->input->post(''),
            // 'Cancel_By' => $this->input->post(''),
            // 'Last_Updated_Time' => $this->input->post(''),
            'Last_Updated_By' => $this->session->userdata('impsys_initial'),
            // 'Last_Updated_Ip' => $this->input->post('')
        ]);

        $id = $this->db->insert_id();

        for ($i = 0; $i < count($Lots); $i++) {
            $this->db->insert($this->ttrx_dtl_subkon_kiln, [
                'SysId_Hdr' => $id,
                'lot' => $Lots[$i],
                'Start_time' => $Start_times[$i],
                'End_Time' => $End_times[$i],
            ]);
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
                "code" => 200,
                "msg" => "Berhasil Menyimpan Data Subkon Kiln dry !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }


    public function update()
    {
        $SysId = $this->input->post('SysId');
        $Lots = $this->input->post('Lot');
        $Start_times = $this->input->post('Start_time');
        $End_times = $this->input->post('End_Time');

        $this->db->trans_start();
        $this->db->delete($this->ttrx_dtl_subkon_kiln, ['SysId_Hdr' => $SysId]);
        $this->db->where('SysId', $SysId)->update($this->ttrx_hdr_subkon_kiln, [
            'DocDate' => $this->input->post('DocDate'),
            'Ref_Number' => $this->input->post('Ref_Number'),
            'Account_Code' => $this->input->post('Account_Code'),
            'Account_Addess_ID' => $this->input->post('Account_Address_ID'),
            'Waktu_Keberangkatan' => $this->input->post('Waktu_Keberangkatan'),
            'Waktu_Kepulangan' => $this->input->post('Waktu_Kepulangan'),
            'Estimasi_Mulai_Kiln' => $this->input->post('Estimasi_Mulai_Kiln'),
            'Estimasi_Selesai_Kln' => $this->input->post('Estimasi_Selesai_Kln'),
            'Jenis_Kendaraan' => $this->input->post('Jenis_Kendaraan'),
            'No_Polisi' => $this->input->post('No_Polisi'),
            'Note' => $this->input->post('Note'),
            // 'Is_Approve' => $this->input->post(''),
            // 'Aprove_By' => $this->input->post(''),
            // 'Approve_Time' => $this->input->post(''),
            // 'Created_By' => $this->session->userdata('impsys_initial'),
            // 'Created_Time' => $this->DateTime,
            // 'Created_IP' => $this->help->get_client_ip()
            // 'Is_Cancel' => $this->input->post(''),
            // 'Cancel_Time' => $this->input->post(''),
            // 'Cancel_By' => $this->input->post(''),
            'Last_Updated_Time' => $this->DateTime,
            'Last_Updated_By' => $this->session->userdata('impsys_initial'),
            'Last_Updated_Ip' => $this->help->get_client_ip()
        ]);

        for ($i = 0; $i < count($Lots); $i++) {
            $this->db->insert($this->ttrx_dtl_subkon_kiln, [
                'SysId_Hdr' => $SysId,
                'lot' => $Lots[$i],
                'Start_time' => $Start_times[$i],
                'End_Time' => $End_times[$i],
            ]);
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
                "code" => 200,
                "msg" => "Berhasil Menyimpan Update Data Subkon Kiln dry !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function verify()
    {
        $SysId = $this->input->post('SysId');
        $Param = intval($this->input->post('Param'));

        $Hdr = $this->db->get_where($this->qview_list_hdr_subkon_kiln, ['SysID' => $SysId])->row();
        $Dtls = $this->db->get_where('qview_list_dtl_subkon_kiln', ['SysId_Hdr' => $SysId])->result();
        $this->db->trans_start();

        $this->db->where('SysId', $SysId)->update($this->ttrx_hdr_subkon_kiln, [
            'Is_Approve' => $Param,
            'Approve_Time' => $this->DateTime,
            'Aprove_By' => $this->session->userdata('impsys_initial')
        ]);

        foreach ($Dtls as $li) {
            $this->db->where('sysid', $li->id_lot)->update('ttrx_dtl_lpb_receive', [
                'placement' => 4,
                'into_oven' => 2
            ]);

            $this->db->insert('thst_out_of_oven', [
                'lot' => $li->lot,
                'placement' => 4,
                'do_by' => $Hdr->Last_Updated_By,
                'do_time' => $li->End_Time,
                'remark_out_of_oven' => 'SUBKON/KK',
            ]);

            $this->db->insert('thst_in_to_oven', [
                'lot' => $li->lot,
                'placement' => 2,
                'do_by' => $Hdr->Last_Updated_By,
                'do_time' => $li->Start_time,
                'remark_into_oven' => 'SUBKON/KK',
            ]);
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
                "code" => 200,
                "msg" => "Status approval berhasil diubah !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function Cancel()
    {
        $SysId = $this->input->post('SysId');

        $Hdr = $this->db->get_where($this->ttrx_hdr_subkon_kiln, ['SysId' => $SysId])->row();
        $Dtls = $this->db->get_where('qview_list_dtl_subkon_kiln', ['SysId_Hdr' => $SysId])->result();
        if ($Hdr->Is_Cancel == 1) {
            return $this->help->Fn_resulting_response([
                'code' => 501,
                'msg' => 'Document sudah memiliki status cancel !'
            ]);
        }
        $this->db->trans_start();

        $this->db->where('SysId', $SysId)->update($this->ttrx_hdr_subkon_kiln, [
            'Is_Cancel' => 1,
            'Cancel_Time' => $this->DateTime,
            'Cancel_By' => $this->session->userdata('impsys_nik')
        ]);

        if ($Hdr->Is_Approve == 1) {
            foreach ($Dtls as $li) {
                $this->db->where('sysid', $li->id_lot)->update('ttrx_dtl_lpb_receive', [
                    'placement' => 1,
                    'into_oven' => 0
                ]);
                $this->db->delete('thst_out_of_oven', ['lot' => $li->lot]);
                $this->db->delete('thst_in_to_oven', ['lot' => $li->lot]);

                $Validate = $this->db->get_where('thst_material_to_prd', ['lot' => $li->lot])->num_rows();
                if ($Validate > 0) {
                    $this->db->trans_rollback();
                    return $this->help->Fn_resulting_response([
                        'code' => 501,
                        'msg' => 'Document tidak dapat di cancel karna LOT' . $li->lot . ' sudah dinyatakan teralokasi ke produksi !'
                    ]);
                }
            }
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
                "code" => 200,
                "msg" => "Nota Hasil Produksi berhasil dibatalkan !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    // ======================== DataTable Section

    public function DT_List_SubkonKiln()
    {
        $query = "SELECT * from $this->qview_list_hdr_subkon_kiln";
        $search = array('DocNo', 'DocDate', 'Account_Code', 'Account_Name', 'Account_Addess_ID', 'Address', 'Waktu_Keberangkatan', 'Waktu_Kepulangan', 'Jenis_Kendaraan', 'No_Polisi', 'Note');
        $where  = [];
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_List_SubkonKiln_toApprove()
    {
        $query = "SELECT * from $this->qview_list_hdr_subkon_kiln";
        $search = array('DocNo', 'DocDate', 'Account_Code', 'Account_Name', 'Account_Addess_ID', 'Address', 'Waktu_Keberangkatan', 'Waktu_Kepulangan', 'Jenis_Kendaraan', 'No_Polisi', 'Note');
        $where  = [];
        $isWhere = null;
        $where["Is_Approve"] = 0;
        $where["Is_Cancel"] = 0;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }

    public function DT_listofaccount_address()
    {
        try {
            $customer_code = $this->input->post('Account_Code');

            $this->db->select('SysId, Address, Area, Description');
            $this->db->from('tmst_account_address');
            $this->db->where('Account_Code', $customer_code);
            $query = $this->db->get();

            $result = $query->result();
            return $this->help->Fn_resulting_response([
                "code" => 200,
                "msg" => "Berhasil mengambil data alamat.",
                "data" => $result
            ]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $this->help->Fn_resulting_response([
                "code" => 500,
                "msg" => "Terjadi kesalahan dalam mengambil daftar alamat."
            ]);
        }
    }

    public function DT_list_lot()
    {
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $sysid = $this->input->get('sysid');

        // Escape the input to prevent SQL injection
        $startDate = $this->db->escape_str($startDate);
        $endDate = $this->db->escape_str($endDate);
        $sysidArray = explode(',', $this->db->escape_str($sysid));

        $query = "SELECT * FROM qview_detail_bundle";

        $search = array("lpb_hdr", "supplier", "grader", "kode", "deskripsi", "tgl_kirim", "status_kayu", "no_lot");

        $where  = [];
        if (!empty($sysidArray)) {
            $where['sysid NOT IN '] = $sysidArray;
        }
        $where["status_lpb"] = 'SELESAI';
        $where["into_oven"] = 0;
        $where["DATE_FORMAT(tgl_kirim, '%Y-%m-%d') >"] = $startDate;
        $where["DATE_FORMAT(tgl_kirim, '%Y-%m-%d') <"] = $endDate;

        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }
}
