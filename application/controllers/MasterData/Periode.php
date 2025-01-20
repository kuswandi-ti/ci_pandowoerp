<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Periode extends CI_Controller
{
	public $layout = 'layout';

	protected $Date;
	protected $DateTime;
	protected $Tmst_Periode = 'tmst_periode';

	public function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->Date = date('Y-m-d');
		$this->DateTime = date('Y-m-d H:i:s');
		$this->load->model('m_helper', 'help');
		$this->load->model('m_DataTable', 'M_Datatables');
	}

	public function index()
	{
		$data['page_title'] = "List of Periode";
		$data['page_content'] = "Master/Periode/index";
		$data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Periode/index.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function add()
    {
        $data['page_title'] = "Add New Periode";
        $data['page_content'] = "Master/Periode/add";
        $data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Periode/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $data);
    }

	public function post()
    {
		$tahun = $this->input->post('tahun');

		// Sebelumnya lihat dulu, apakah periode sudah ada?
		$this->db->where('tahun', $tahun);
		$q = $this->db->get($this->Tmst_Periode);
		$this->db->reset_query();
		if ( $q->num_rows() > 0 ) 
		{
			$response = [
                "code" => 505,
                "msg" => "Gagal Menyimpan data ! Data periode sudah ada"
            ];
		} else {
			$this->db->trans_start();
			for ($x = 0; $x <= 11; $x++) {
				$this->db->insert($this->Tmst_Periode, [
					'tahun' => $tahun,
					'bulan' => $x + 1,
				]);
			}
			
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$response = [
					"code" => 505,
					"msg" => "Gagal Menyimpan data !"
				];
			} else {
				$this->db->trans_commit();
				$response = [
					"code" => 200,
					"msg" => "Data berhasil tersimpan!"
				];
			}
		}
        
        return $this->help->Fn_resulting_response($response);
    }
	
	public function DT_list_periode()
	{
		$query = "SELECT * FROM " . $this->Tmst_Periode;
		$where  = null;
		$search = array('tahun', 'bulan');

		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}
}
