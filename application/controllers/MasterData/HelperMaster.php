<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HelperMaster extends CI_Controller
{
    public $layout = 'layout';
    protected $Date;
    protected $DateTime;

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');
        $this->load->model('m_helper', 'help');
        $this->load->model('m_DataTable', 'M_Datatables');
    }

    public function Toggle_Status()
    {
        $sysid = $this->input->post('sysid');
        $table = $this->input->post('table');

        $row = $this->db->get_where($table, ['SysId' => $sysid])->row();

		$this->db->trans_start();

        if ($row->Is_Active == 1) {
            $this->db->where('SysId', $sysid);
            $this->db->update($table, [
                'Is_Active' => 0
            ]);

            // $response = [
            //     "code" => 200,
            //     "msg" => "Data telah di non-aktifkan !"
            // ];

			$message = "Data telah di non-aktifkan !";
        } else {
            $this->db->where('SysId', $sysid);
            $this->db->update($table, [
                'Is_Active' => 1
            ]);

            // $response = [
            //     "code" => 200,
            //     "msg" => "Data berhasil di aktifkan !"
            // ];

			$message = "Data berhasil di aktifkan !";
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
                "msg" => $message
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }
}
