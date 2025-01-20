<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_helper', 'help');
	}

	public function index()
	{
		if ($this->session->userdata("impsys_nik")) {
			return redirect("Dashboard");
		}
		$this->data['page_title'] = "Authorization";

		$this->load->view('Auth/index.php', $this->data);
	}


	public function post_login()
	{
		if (!$this->input->is_ajax_request()) {
			$response = [
				"code" => 404,
				"msg" => "Terjadi kesalahan teknis, Request denied!"
			];
			return $this->help->Fn_resulting_response($response);
		}
		$login = $this->input->post(null, true);
		$login = (object) $login;
		$login->username = $login->u;
		$login->password = md5($login->p);

		if (empty($login->username) && empty($login->password)) {
			$response = [
				"code" => 404,
				"msg" => "Username & Password tidak boleh kosong!"
			];
			return $this->help->Fn_resulting_response($response);
		}

		$users = $this->db->get_where('tmst_karyawan', [
			'initial' => $login->username,
			'password' => $login->password
		]);

		if ($users->num_rows() > 0) {
			$user = $users->row_array();
			$session_data = array(
				'impsys_nama'	 			=> $user['nama'],
				'impsys_nik'	 			=> $user['nik'],
				'impsys_initial'	 		=> $user['initial'],
				'impsys_jabatan'	 		=> $user['jabatan'],
				'impsys_telp'	 			=> $user['telp1'],
				'impsys_type_pembayaran'	=> $user['type_pembayaran']
			);
			$this->session->set_userdata($session_data);
			$response = [
				"code" => 200,
				"msg" => "Anda Berhasil login!"
			];
			return $this->help->Fn_resulting_response($response);
		} else {
			$users = $this->db->get_where('tmst_karyawan', [
				'initial' => $login->username,
			]);
			if ($users->num_rows() > 0) {
				$response = [
					"code" => 505,
					"msg" => "Password tidak sesuai!"
				];
				return $this->help->Fn_resulting_response($response);
			} else {
				$response = [
					"code" => 505,
					"msg" => "Username & Password tidak terdaftar!"
				];
				return $this->help->Fn_resulting_response($response);
			}
		}
	}

	public function logout()
	{
		$this->output->delete_cache();
		$array_items = array('impsys_name', 'impsys_nik', 'impsys_initial', 'impsys_jabatan', 'impsys_telp', 'impsys_type_pembayaran');
		$this->session->unset_userdata($array_items);
		session_destroy();
		$this->session->set_flashdata('success', "Silahkan login kembali untuk mengakses" . $this->config->item('app_name'));
		return redirect('Auth');
	}
}
