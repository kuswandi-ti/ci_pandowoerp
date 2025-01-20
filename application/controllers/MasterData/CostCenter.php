<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CostCenter extends CI_Controller
{
	public $layout = 'layout';

	protected $Date;
	protected $DateTime;
	protected $Tmst_CostCenter = 'tmst_cost_center';
	protected $LengthCounterAccount = 5;

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
		$data['page_title'] = "List of Cost Center";
		$data['page_content'] = "Master/CostCenter/index";
		$data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/CostCenter/costcenter.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function add()
    {
        $this->data['page_title'] = "Add New Cost Center";
        $this->data['page_content'] = "Master/CostCenter/add";
		$this->data['cc_group'] = $this->db->get('tmst_cost_center_group')->result();
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/CostCenter/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

	public function post()
    {
        $this->db->trans_start();
        $this->db->insert($this->Tmst_CostCenter, [
			'cc_group_id' 		=> $this->input->post('cc_group_id'),
            'kode_cost_center' 	=> $this->input->post('kode_cost_center'),
            'nama_cost_center' 	=> $this->input->post('nama_cost_center'),
        ]);
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
        return $this->help->Fn_resulting_response($response);
    }

	public function edit($sysid)
    {
        $this->data['RowData'] = $this->db->get_where($this->Tmst_CostCenter, ['SysId' => $sysid])->row();
        $this->data['page_title'] = "Edit Data Cost Center : " . $this->data['RowData']->nama_cost_center;
        $this->data['page_content'] = "Master/CostCenter/edit";
		$this->data['cc_group'] = $this->db->get('tmst_cost_center_group')->result();
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/CostCenter/edit.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

	public function update()
    {
        $this->db->trans_start();
        $this->db->where('SysId', $this->input->post('sysid'))->update($this->Tmst_CostCenter, [
			'cc_group_id' 		=> $this->input->post('cc_group_id'),
            'kode_cost_center' 	=> $this->input->post('kode_cost_center'),
            'nama_cost_center' 	=> $this->input->post('nama_cost_center'),
        ]);

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
        return $this->help->Fn_resulting_response($response);
    }

	public function DT_list_costcenter()
	{
		$query = "SELECT
					A.SysId,
					B.cc_group_name,
					A.kode_cost_center,
					A.nama_cost_center,
					A.Is_Active
				FROM
					tmst_cost_center A
					LEFT OUTER JOIN tmst_cost_center_group B ON A.cc_group_id = B.sysid";
		$where  = array('nama_cost_center !' => NULL);
		$search = array('A.SysId', 'cc_group_name', 'kode_cost_center', 'nama_cost_center', 'Is_Active');

		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}
}
