<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public $layout = 'layout';
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        // BELUM ADA MENU/PAGE TERDEFINISI.
    }

    public function form_change_password()
    {
        $this->data['page_title'] = "Change Password";
        $this->data['page_content'] = "User/change-password";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/user-script/change-password.js"></script>';;;

        $this->load->view($this->layout, $this->data);
    }

    public function store_change_password()
    {
        $password = $this->input->post('password');
        $new_password = $this->input->post('password1');
        $repeat_password = $this->input->post('password2');

        $user = $this->db->get_where('tmst_karyawan', ['nik' => $this->session->userdata('impsys_nik')])->row();

        if (md5($password) == $user->password) {
            $this->db->where('nik', $this->session->userdata('impsys_nik'));
            $this->db->update('tmst_karyawan', [
                'password' => md5($repeat_password)
            ]);
            $response = [
                "code" => 200,
                "msg" => "Password anda telah berubah, harap login kembali !"
            ];
        } else {
            $response = [
                "code" => 505,
                "msg" => "Password salah, harap perhatikan password anda !"
            ];
        }


        return $this->help->Fn_resulting_response($response);
    }
}
