<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class m_Warehouse extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_stok($item_code, $warehouse_id)
	{
		$this->db->select('Item_Code, Item_Qty, Item_Qty_Receive, Item_Qty_Used, Item_Qty_Prod, Item_Qty_Afkir, Warehouse_ID');
		$this->db->from('t_stok_wh_item');
		$this->db->where('Item_Code', $item_code);
		$this->db->where('Warehouse_ID', $warehouse_id);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_average_price_and_avg_start_date($item_code)
	{
		return $this->db->select('Avg_Price', 'last_zero_stock_date')
			->get_where(
				'tmst_item',
				['Item_Code' => $item_code]
			)->row();
	}

	public function reset_avg_date($item_code, $type)
	{
		$NowDate = date('Y-m-d');
		$RowItem = $this->db->get_where('tmst_item', [
			'Item_Code' => $item_code
		])->row();

		$RowAvailable = $this->db->select('Qty_Avaliable')->get_where('qview_stok_item_global_all', ['Item_Code' => $item_code])->row();

		if (empty($RowAvailable->Qty_Avaliable) || floatval($RowAvailable->Qty_Avaliable) == 0) {
			$this->db->insert('thst_zero_stok_date', [
				'Item_Code' => $item_code,
				'Avg_Price' => $RowItem->Avg_Price,
				'Last_Zero_Stock_Date' => $RowItem->last_zero_stock_date,
				'Change_Date' => $NowDate,
				'Change_Type' => $type,
				'Created_by' => $this->session->userdata('impsys_nik')
			]);

			return $this->db->where('Item_Code', $item_code)->update('tmst_item', [
				'last_zero_stock_date' => $NowDate
			]);
		}

		return true;
	}

	public function updateAveragePrice($item_code)
	{
		$this->db->select('last_zero_stock_date');
		$this->db->from('tmst_item');
		$this->db->where('Item_Code', $item_code);
		$last_zero_stock_date = $this->db->get()->row()->last_zero_stock_date;

		$this->db->select('SUM(qty) as total_qty, SUM(qty * purchase_price) as total_value');
		$this->db->from('qview_rr_legitimate');
		$this->db->where('Item_Code', $item_code);
		$this->db->where('RR_Date >=', $last_zero_stock_date);
		$this->db->where('RR_Date <=', date('Y-m-d'));
		$result = $this->db->get()->row();

		if ($result->total_qty > 0) {
			$average_price = $result->total_value / $result->total_qty;
		} else {
			$average_price = 0;
		}

		return $this->db->where('Item_Code', $item_code)->update('tmst_item', ['Avg_Price' => $average_price]);
	}

	// DI PANGGIL APABILA USAGE NOTE YANG DICANCEL MENGHASILKAN CURR QTY 0, dimaksudkan untuk mengembalikan last zero qty
	public function RollBack_last_zero_stock_date($item_code, $type)
	{
		$NowDate = date('Y-m-d');

		$RowAvailable = $this->db->select('Qty_Avaliable')->get_where('qview_stok_item_global_all', ['Item_Code' => $item_code])->row();

		if (empty($RowAvailable->Qty_Avaliable) || floatval($RowAvailable->Qty_Avaliable) == 0) {
			$RowItem = $this->db->get_where('tmst_item', [
				'Item_Code' => $item_code
			])->row();
			$this->db->insert('thst_zero_stok_date', [
				'Item_Code' => $item_code,
				'Avg_Price' => $RowItem->Avg_Price,
				'Last_Zero_Stock_Date' => $RowItem->last_zero_stock_date,
				'Change_Date' => $NowDate,
				'Change_Type' => 'Revert Used',
				'Created_by' => $this->session->userdata('impsys_nik')
			]);
			$last_log = $this->db->order_by('log_id', 'DESC')
				->get_where('thst_zero_stok_date', ['Item_Code' => $item_code, 'Change_Type' => $type])
				->row();
			if ($last_log) {
				return $this->db->where('Item_Code', $item_code)->update('tmst_item', ['last_zero_stock_date' => $last_log->Change_Date]);
			}
		}
		return true;
	}

	// Mengupdate stok berdasarkan parameter yang diberikan
	public function update_stok($item_code, $warehouse_id, $type, $quantity)
	{
		// Mendapatkan stok saat ini
		$stok = $this->get_stok($item_code, $warehouse_id);

		if ($stok) {
			// Menentukan field yang akan diperbarui dan operatornya
			$data = array();
			switch ($type) {
				case 'Receive':
					$data['Item_Qty_Receive'] = floatval($stok->Item_Qty_Receive) + floatval($quantity);
					// $data['Item_Qty'] = floatval($stok->Item_Qty) + floatval($quantity);
					break;
				case 'Used':
					$data['Item_Qty_Used'] = floatval($stok->Item_Qty_Used) + floatval($quantity);
					// $data['Item_Qty'] = floatval($stok->Item_Qty) - floatval($quantity);
					break;
				case 'Prod':
					$data['Item_Qty_Prod'] = floatval($stok->Item_Qty_Prod) + floatval($quantity);
					// $data['Item_Qty'] = floatval($stok->Item_Qty) + floatval($quantity);
					break;
				case 'Afkir':
					$data['Item_Qty_Afkir'] = floatval($stok->Item_Qty_Afkir) + floatval($quantity);
					// $data['Item_Qty'] = floatval($stok->Item_Qty) - floatval($quantity);
					break;
				default:
					return false; // Jenis operasi tidak valid
			}

			// Mengupdate stok di database
			$this->db->where('Item_Code', $item_code);
			$this->db->where('Warehouse_ID', $warehouse_id);
			return $this->db->update('t_stok_wh_item', $data);
		} else {
			$data = array(
				'Item_Code' => $item_code,
				'Warehouse_ID' => $warehouse_id
			);
			switch ($type) {
				case 'Receive':
					$data['Item_Qty_Receive'] = floatval($quantity);
					// $data['Item_Qty'] = floatval($quantity);
					break;
				case 'Prod':
					$data['Item_Qty_Prod'] = floatval($quantity);
					// $data['Item_Qty'] = floatval($quantity);
					break;
				default:
					return false;
			}
			return $this->db->insert('t_stok_wh_item', $data);
		}

		return false;
	}

	public function revert_stok($item_code, $warehouse_id, $type, $quantity)
	{
		// Mendapatkan stok saat ini
		$stok = $this->get_stok($item_code, $warehouse_id);

		if ($stok) {
			// Menentukan field yang akan diperbarui dan operatornya
			$data = array();
			switch ($type) {
				case 'Receive':
					$data['Item_Qty_Receive'] = floatval($stok->Item_Qty_Receive) - floatval($quantity);
					$data['Item_Qty'] = floatval($stok->Item_Qty) - floatval($quantity);
					break;
				case 'Used':
					$data['Item_Qty_Used'] = floatval($stok->Item_Qty_Used) - floatval($quantity);
					$data['Item_Qty'] = floatval($stok->Item_Qty) + floatval($quantity);
					break;
				case 'Prod':
					$data['Item_Qty_Prod'] = floatval($stok->Item_Qty_Prod) - floatval($quantity);
					$data['Item_Qty'] = floatval($stok->Item_Qty) - floatval($quantity);
					break;
				case 'Afkir':
					$data['Item_Qty_Afkir'] = floatval($stok->Item_Qty_Afkir) - floatval($quantity);
					$data['Item_Qty'] = floatval($stok->Item_Qty) + floatval($quantity);
					break;
				default:
					return false; // Jenis operasi tidak valid
			}

			// Mengupdate stok di database
			$this->db->where('Item_Code', $item_code);
			$this->db->where('Warehouse_ID', $warehouse_id);
			return $this->db->update('t_stok_wh_item', $data);
		}

		return false;
	}

	// ========================================== History Section

	public function record_cancel_usage_note($Un_Number)
	{
		$this->db->query("INSERT INTO `ttrx_hdr_void_usage_note` (
                        `CUN_Number`,
                        `Ref_Number`,
                        `CUN_DATE`,
                        `CUN_Notes`,
                        `ReceivedDate`,
                        `Approval_Status`,
                        `Approve_Date`,
                        `Approve_by`,
                        `ItemCategoryType`,
                        `Cost_Center`,
                        `Rate`,
                        `Base_Amount`,
                        `isCancel`,
                        `Cancel_Date`,
                        `Cancel_by`,
                        `Created_IP`,
                        `Cancel_Reason`,
                        `Creation_DateTime`,
                        `Created_By`,
                        `Last_Update`,
                        `Update_By`
                    )
                    SELECT 
                        CONCAT('V', `UN_Number`) AS `CUN_Number`,
                        `UN_Number` AS `Ref_Number`,
                        `UN_DATE` AS `CUN_DATE`,
                        `UN_Notes` AS `CUN_Notes`,
                        `ReceivedDate`,
                        `Approval_Status`,
                        `Approve_Date`,
                        `Approve_by`,
                        `ItemCategoryType`,
                        `Cost_Center`,
                        `Rate`,
                        `Base_Amount`,
                        `isCancel`,
                        `Cancel_Date`,
                        `Cancel_by`,
                        `Created_IP`,
                        `Cancel_Reason`,
                        `Creation_DateTime`,
                        `Created_By`,
                        `Last_Update`,
                        `Update_By`
                    FROM `ttrx_hdr_usage_note` where ttrx_hdr_usage_note.UN_Number = '$Un_Number'");


		$this->db->query("INSERT INTO `ttrx_dtl_void_usage_note` (
                        `CUN_NUMBER`,
						`UN_Number`,
                        `Item_Code`,
                        `Item_Name`,
                        `Qty`,
                        `Warehouse_ID`,
                        `Base_Price`,
                        `UnitPrice`,
                        `Base_TotalPrice`,
                        `CostingMethod`,
                        `Currency`
                    )
                    SELECT 
                    CONCAT('V', `UN_Number`) AS `CUN_NUMBER`,
						`UN_Number`,
                        `Item_Code`,
                        `Item_Name`,
                        `Qty`,
                        `Warehouse_ID`,
                        `Base_Price`,
                        `UnitPrice`,
                        `Base_TotalPrice`,
                        `CostingMethod`,
                        `Currency`
                    FROM `ttrx_dtl_usage_note` where ttrx_dtl_usage_note.UN_Number = '$Un_Number'");
	}

	public function generate_asset($item_id, $qty, $tgl_perolehan, $tahun_perolehan, $harga_perolehan = 0, $masa_tahun_pakai = 0, $nilai_penyusutan = 0)
	{
		for ($i = 0; $i < $qty; $i++) {
			// Insert ke tabel asset
			$no_asset = $this->help->Generate_Identity_Number_Asset('ASSET');
			$this->db->insert('tmst_item_asset', [
				'no_asset'     			=> $no_asset,
				'item_id'				=> $item_id,
				'tgl_perolehan'			=> $tgl_perolehan,
				'tahun_perolehan'		=> $tahun_perolehan,
				'harga_perolehan'		=> $harga_perolehan,
				'masa_tahun_pakai'		=> $masa_tahun_pakai,
				'nilai_penyusutan'		=> $nilai_penyusutan,
			]);
		}
	}


	public function get_code_geometri($type)
	{
		switch ($type) {
			case 'BALOK':
				return 'B';
			case 'PAPAN':
				return 'P';
			case 'KUBUS':
				return 'K';
			case 'STIK':
				return 'S';
			case 'SEGITIGA':
				return '3';
			case 'LETTER-L':
				return 'L';
			case 'LEMBARAN':
				return 'LM';
			default:
				return null; // atau nilai default yang lain
		}
	}

	function convertLength($value, $fromUnit, $toUnit)
	{
		$units = [
			'MM' => 1,         // Millimeter
			'CM' => 10,        // Centimeter
			'M'  => 1000,      // Meter
			'KM' => 1000000,   // Kilometer
			'IN' => 25.4,      // Inch (2.54 cm)
			'FT' => 304.8,     // Foot (12 inches)
			'YD' => 914.4,     // Yard (3 feet)
			'MI' => 1609344    // Mile (1760 yards)
		];

		// Convert the value to millimeters first
		$valueInMM = $value * $units[strtoupper($fromUnit)];

		// Convert millimeters to the target unit
		$result = $valueInMM / $units[strtoupper($toUnit)];

		return $result;
	}
}
