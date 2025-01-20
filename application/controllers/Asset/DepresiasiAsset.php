<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DepresiasiAsset extends CI_Controller
{
    public $layout = 'layout';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
        $this->load->model('m_DataTable', 'M_Datatables');
    }

    public function index()
    {
        $this->data['page_title'] = "List Depresiasi Aset";
        $this->data['page_content'] = "Asset/DepresiasiAsset/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/asset-script/DepresiasiAsset/index.js?v=' . time() . '"></script>';
        $this->load->view($this->layout, $this->data);
    }
	
	public function DT_List_Depresiasi_Asset()
	{
		$requestData = $_REQUEST;

		$tgl_finish = $this->input->post('tgl_finish');

		if (isset($tgl_finish)) {
			$tgl_finish = $tgl_finish;
		} else {
			$tgl_finish = date("Y-m-d");;
		}

		$query  = "SELECT
					A.SysId AS sysid,
					A.no_asset,
					A.item_id,
					B.Item_Code AS item_code,
					B.Item_Name AS item_name,
					A.tgl_perolehan,
					A.tahun_perolehan,
					A.harga_perolehan,
					A.masa_tahun_pakai,
					A.nilai_penyusutan,
					A.Is_Active AS is_active,
					'$tgl_finish' AS tgl_akhir_perolehan,
					FLOOR(TIMESTAMPDIFF(MONTH, A.tgl_perolehan, '$tgl_finish') / 12) AS lama_asset_terpakai_tahun,
					FLOOR(TIMESTAMPDIFF(MONTH, A.tgl_perolehan, '$tgl_finish') / 12) * A.nilai_penyusutan AS total_nilai_penyusutan_persen,
					(FLOOR(TIMESTAMPDIFF(MONTH, A.tgl_perolehan, '$tgl_finish') / 12) * A.nilai_penyusutan) / 100 * A.harga_perolehan AS nilai_asset_berkurang,
					A.harga_perolehan - ((FLOOR(TIMESTAMPDIFF(MONTH, A.tgl_perolehan, '$tgl_finish') / 12) * A.nilai_penyusutan) / 100 * A.harga_perolehan) AS nilai_asset_sisa
				FROM
					tmst_item_asset A
					LEFT OUTER JOIN tmst_item B ON A.item_id = B.SysId";
		$search = array('A.no_asset', 'B.Item_Code', 'B.Item_Name');
		$where  = null;
		$isWhere = null;

		header('Content-Type: application/json');
		echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
	}
}
