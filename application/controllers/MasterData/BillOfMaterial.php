<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BillOfMaterial extends CI_Controller
{
	public $layout = 'layout';

	protected $Date;
	protected $DateTime;
	protected $Tmst_BOM = 'tmst_bill_of_material';
	protected $Tmst_Product = 'qmst_item';

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
		$data['page_title'] = "List of Bill Of Material (BOM)";
		$data['page_content'] = "Master/BillOfMaterial/index";
		$data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/BillOfMaterial/index.js?v=' . time() . '"></script>';

		$this->load->view($this->layout, $data);
	}

	public function add()
	{
		$data['page_title'] = "Add Item Bill Of Material (BOM)";
		$data['page_content'] = "Master/BillOfMaterial/add";
		$data['item_fg'] = $this->db->query("SELECT
								SysId, Item_Code, Item_Name
							FROM
								qmst_item
							WHERE
								Item_Category_Init = 'FG'	
								AND SysId NOT IN (SELECT id_item FROM tmst_bill_of_material WHERE id_parent = 0)");
		$data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/BillOfMaterial/add.js?v=' . time() . '"></script>';
		$this->load->view($this->layout, $data);
	}

	public function post()
    {
		$no_bom = $this->input->post('no_bom');
		$id_item = $this->input->post('id_item');
		$id_parent = $this->input->post('id_parent');
		$qty = $this->input->post('qty');

		if ($id_parent == 0) {
			$no_bom = $this->help->Generate_Identity_Number_BOM('BOM');
		}

		$this->db->trans_start();

		if ($id_parent == 0) {
			$this->db->insert($this->Tmst_BOM, [
				'no_bom' 		=> $no_bom,
				'id_item' 		=> $this->input->post('id_item'),
				'id_parent' 	=> 0,
				'qty' 			=> 1,
			]);
		} else {
			$this->db->insert($this->Tmst_BOM, [
				'no_bom' 		=> $no_bom,
				'id_item' 		=> $id_item,
				'id_parent' 	=> $id_parent,
				'qty' 			=> $qty,
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
        return $this->help->Fn_resulting_response($response);
    }

	public function edit($sysid)
    {
		$this->db->where('t1.SysId', $sysid);
		$this->db->select('t1.*, t2.Item_Code AS item_code, t2.Item_Name AS item_name');
		$this->db->from($this->Tmst_BOM . ' as t1');
		$this->db->join('tmst_item' . ' as t2', 't1.id_item = t2.SysId');
		$data['RowData'] = $this->db->get()->row();
		$items	= $this->get_items($data['RowData']->no_bom);
		$data['bom_tree']	= $this->GenerateTree($items);
		$data['item_non_fg'] = $this->db->get_where('qmst_item', array('Item_Category_Init !=' => 'FG'));
        $data['page_title'] = "Edit Data Bill Of Material : <b>" . $data['RowData']->item_code . " - " . $data['RowData']->item_name . "</b>";
        $data['page_content'] = "Master/BillOfMaterial/edit";
        $data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/BillOfMaterial/edit.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $data);
    }

	public function delete()
    {
        $this->db->trans_start();

		$sysid = $this->input->post('sysid');
		$id_parent = $this->input->post('id_parent');
		$no_bom = $this->input->post('no_bom');

		if ($id_parent == 0) { // menghapus semua
			$this->db->where('no_bom', $no_bom);        	
		} else { // menghapus current dan di bawahnya saja
			$this->db->where('(SysId = ' . $sysid . ' or id_parent = ' . $sysid . ')'); 
		}

		$this->db->delete($this->Tmst_BOM);

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
				"no_bom" => $no_bom,
                "msg" => "Data berhasil tersimpan!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

	function get_items($no = "")
	{
		$this->db->select('A.SysId, A.no_bom, B.Item_Code AS item_code, B.Item_Name AS item_name, A.id_parent');
		$this->db->from('tmst_bill_of_material A');
		$this->db->join('tmst_item B', 'A.id_item = B.SysId', 'left');
		$this->db->where(array('A.no_bom' => $no));
		$this->db->order_by('A.id_parent');
		$this->db->order_by('B.SysId');
		$query = $this->db->get();
		return $query->result_array();
	}

	// https://iamkate.com/code/tree-views/
	function GenerateTree($items = array(), $id_parent = 0)
	{
		$tree = '<ul class="tree">';
		for ($i = 0, $ni = count($items); $i < $ni; $i++) {
			if ($items[$i]['id_parent'] == $id_parent) {
				$tree .= '<li>';
					$tree .= '<details open>';
						$tree .= "<summary>" . $items[$i]['item_code'] . "</b>" . " - " . $items[$i]['item_name'] . "&nbsp;&nbsp;&nbsp;" . "<a href='#' class='btn btn-danger btn-xs btn-remove' sysid='" . $items[$i]['SysId'] . "' id_parent='" . $id_parent . "' no_bom='" . $items[$i]['no_bom'] . "' name='btn-remove'>Remove</a><a href='#' class='btn btn-primary btn-xs btn-add' sysid='" . $items[$i]['SysId'] . "' id_parent='" . $id_parent . "' no_bom='" . $items[$i]['no_bom'] . "' name='btn-add'>Add Child</a></summary>";
						$tree .= "<ul>";
							$tree .= "<li>";
								$tree .= "<details open>";
									$tree .= $this->GenerateTree($items, $items[$i]['SysId']);	
								$tree .= "</details>";	
							$tree .= "</li>";
						$tree .= "</ul>";		
					$tree .= '</details>';
				$tree .= '</li>';
			}
		}
		$tree .= '</ul>';
		return $tree;
	}

	public function DT_list_BOM()
	{
		$query = "SELECT
					A.SysId AS sysid,
					A.no_bom,
					B.Item_Code AS item_code,
					B.Item_Name AS item_name,
					A.id_parent,
					A.Is_Active
				FROM 
					tmst_bill_of_material A
					INNER JOIN tmst_item B ON A.id_item = B.SysId";
		$where  = array('A.id_parent' => 0);
		$search = array('A.no_bom', 'B.Item_Code', 'B.Item_Name');

		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}
}
