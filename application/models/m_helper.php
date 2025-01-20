<?php
class m_helper extends CI_Model
{

	public function Fn_resulting_response($responses)
	{
		$response = json_encode($responses);
		echo $response;
	}

	public function Gnrt_kode_matkyu($t, $l, $p)
	{
		$inisial_kode = $t . '-' . $l . '-' . $p;
		$kode = 'T' . $t . '-L' . $l . '-P' . $p;

		$data = [
			"inisial_kode" => $inisial_kode,
			"kode" => $kode,
		];

		return $data;
	}

	function Gnrt_Identity_Counter_Only($Param, $lengthCounter)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $Param,
		));

		$length = $lengthCounter;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $Param);
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $Param,
				"year" => date('Y'),
				"month" => 0,
				"date" => 0,
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = $string;
		}

		return $Param . $identity_number;
	}

	public function Gnrt_Identity_Daily($Param, $lengthCounter, $ConcateChar)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $Param,
			"year" => date('Y'),
			"month" => date('m'),
			"date" => date('d'),
		));

		$length = $lengthCounter;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $Param);
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $Param,
				"year" => date('Y'),
				"month" => date('m'),
				"date" => date('d'),
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = $string;
		}

		return $Param . date('y') . date('m') . date('d') . $ConcateChar . $identity_number;
	}

	public function Gnrt_Identity_Monthly($Param, $lengthCounter, $ConcateChar)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $Param,
			"year" => date('Y'),
			"month" => date('m'),
		));

		$length = $lengthCounter;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $Param);
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $Param,
				"year" => date('Y'),
				"month" => date('m'),
				"date" => 0,
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = $string;
		}

		return $Param . date('y') . date('m') . $ConcateChar . $identity_number;
	}

	public function Gnrt_Identity_Monthly_Sales($Param, $lengthCounter, $ConcateChar)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $Param,
			"year" => date('Y'),
			"month" => date('m'),
		));

		$length = $lengthCounter;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $Param);
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $Param,
				"year" => date('Y'),
				"month" => date('m'),
				"date" => 0,
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = $string;
		}

		return $Param . date('ymd') . $ConcateChar . $identity_number;
	}

	public function Gnrt_Identity_Yearly($Param, $lengthCounter, $ConcateChar)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $Param,
			"year" => date('Y')
		));

		$length = $lengthCounter;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $Param);
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $Param,
				"year" => date('Y'),
				"month" => 0,
				"date" => 0,
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = $string;
		}

		return $Param . date('y') . $ConcateChar . $identity_number;
	}

	public function Gnrt_kode_item_grid($kode_jeni_kayu, $bentuk_kayu, $t, $l, $p)
	{
		$kode = $kode_jeni_kayu . '-' . $bentuk_kayu . '-' . 'T' . $t . '-L' . $l . '-P' . $p;
		return $kode;
	}

	public function Gnrt_Identity_WithoutDateParam($param)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $param,
			"year" => date('Y'),
			"month" => date('m'),
		));

		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $param);
			$this->db->where('year', date('Y'));
			$this->db->where('month', date('m'));
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$length = 3;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $param,
				"year" => date('Y'),
				"month" => date('m'),
				"count" => 1,
			]);
			$newCount = 1;
			$length = 3;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . $string;
		}

		return $identity_number;
	}

	public function Gnrt_Identity_Number($param)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $param,
			"year" => date('Y'),
			"month" => date('m'),
		));

		$length = 3;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $param);
			$this->db->where('year', date('Y'));
			$this->db->where('month', date('m'));
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $param,
				"year" => date('Y'),
				"month" => date('m'),
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . $string;
		}

		return $param . $identity_number;
	}

	public function Gnrt_Identity_Number_Continious_Monthly($param, $param_db)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $param_db,
			"year" => date('Y'),
			"month" => date('m'),
		));

		$length = 3;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $param_db);
			$this->db->where('year', date('Y'));
			$this->db->where('month', date('m'));
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $param_db,
				"year" => date('Y'),
				"month" => date('m'),
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . $string;
		}

		return $param . $identity_number;
	}

	public function Gnrt_Identity_Number_PO($param)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $param,
			"year" => date('Y'),
			"month" => date('m'),
		));

		$length = 4;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $param);
			$this->db->where('year', date('Y'));
			$this->db->where('month', date('m'));
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("ymd") . '-' . $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $param,
				"year" => date('Y'),
				"month" => date('m'),
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("ymd") . '-' . $string;
		}

		return $param . $identity_number;
	}

	public function Gnrt_Identity_Number_RCV($param)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $param,
			"year" => date('Y'),
			"month" => date('m'),
		));

		$length = 4;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $param);
			$this->db->where('year', date('Y'));
			$this->db->where('month', date('m'));
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("ymd") . '-' . $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $param,
				"year" => date('Y'),
				"month" => date('m'),
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("ymd") . '-' . $string;
		}

		return $param . $identity_number;
	}

	public function Gnrt_Identity_Number_SO($param)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $param,
			"year" => date('Y'),
			"month" => date('m'),
		));

		$length = 4;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $param);
			$this->db->where('year', date('Y'));
			$this->db->where('month', date('m'));
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number =
				date("ymd") . $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $param,
				"year" => date('Y'),
				"month" => date('m'),
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("ymd") . $string;
		}

		return $param . '-' . $identity_number;
	}

	public function Gnrt_Identity_Number_Dn($param)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $param,
			"year" => date('Y'),
			"month" => date('m'),
		));

		$length = 3;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $param);
			$this->db->where('year', date('Y'));
			$this->db->where('month', date('m'));
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $param,
				"year" => date('Y'),
				"month" => date('m'),
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . $string;
		}

		return $param . '-' . $identity_number;
	}

	public function Gnrt_Identity_Number_DocOven($param, $id_oven)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $param,
			"year" => date('Y'),
			"month" => date('m'),
		));

		$length = 3;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $param);
			$this->db->where('year', date('Y'));
			$this->db->where('month', date('m'));
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $param,
				"year" => date('Y'),
				"month" => date('m'),
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . $string;
		}

		return $param . '-' . $id_oven . '/' . $identity_number;
	}

	public function Generate_Identity_Number_FA($param)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $param,
			"year" => date('Y'),
			"month" => date('m'),
		));

		$length = 4;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $param);
			$this->db->where('year', date('Y'));
			$this->db->where('month', date('m'));
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . "-" . $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $param,
				"year" => date('Y'),
				"month" => date('m'),
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . "-" . $string;
		}

		return $param . "-" . $identity_number;
	}

	public function Generate_Identity_Number_BOM($param)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $param,
			"year" => date('Y'),
			"month" => date('m'),
		));

		$length = 4;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $param);
			$this->db->where('year', date('Y'));
			$this->db->where('month', date('m'));
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . "" . $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $param,
				"year" => date('Y'),
				"month" => date('m'),
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . "" . $string;
		}

		return $param . "" . $identity_number;
	}

	public function Counter_Product_Number($customer_code)
	{
		$trx_name = 'BARCODE_PRD_' . $customer_code;
		$trx_year = date('Y');
		$trx_month = intval(date('m'));
		$tsys = $this->db->get_where('tsys_identity_number', [
			'parameter' => $trx_name,
			'year' => $trx_year,
			'month' => intval($trx_month),
		]);
		if ($tsys->num_rows() == 0) {
			$current_no = 1;
			$this->db->insert('tsys_identity_number', [
				'parameter' => $trx_name,
				'year' => $trx_year,
				'month' => intval($trx_month),
				'date' => 0,
				'count' => $current_no,
			]);
		} else {
			$row_tsys = $tsys->row();
			$current_no = intval($row_tsys->count) + 1;

			$this->db->set('count', $current_no);
			$this->db->where('sysid', $row_tsys->sysid);
			$this->db->update('tsys_identity_number');
		}

		return substr('0000' . $current_no, -4);
	}

	public function Get_Full_Name($Initial)
	{
		$Employee = $this->db->get_where('tmst_karyawan', ['initial' => $Initial]);
		if ($Employee->num_rows() > 0) {
			return ucwords(strtolower($Employee->row()->nama));
		} else {
			return '-';
		}
	}

	public function getCurrencyList()
	{
		$this->db->where('Status', 1);
		$this->db->order_by('Currency_ID', 'ASC');
		$query = $this->db->get('tmst_currency');

		return $query->result();
	}

	public function get_client_ip()
	{
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if (getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if (getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if (getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if (getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if (getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	public function konversi_angka_ke_nama_bulan($angka)
	{
		switch ($angka) {
			case 1:
				echo "Januari";
				break;
			case 2:
				echo "Februari";
				break;
			case 3:
				echo "Maret";
				break;
			case 4:
				echo "April";
				break;
			case 5:
				echo "Mei";
				break;
			case 6:
				echo "Juni";
				break;
			case 7:
				echo "Juli";
				break;
			case 8:
				echo "Agustus";
				break;
			case 9:
				echo "September";
				break;
			case 10:
				echo "Oktober";
				break;
			case 11:
				echo "November";
				break;
			case 12:
				echo "Desember";
				break;
			default:
				echo "-";
		}
	}

	public function float_to_value($val)
	{
		$hasil = $val == null ? 0 : str_replace(',', '', $val);

		return $hasil;
	}

	public function toTitleCase($string)
	{
		// Ubah string menjadi array kata
		$words = explode(' ', $string);

		// Ubah huruf pertama dari setiap kata menjadi huruf besar
		$titleCasedWords = array_map(function ($word) {
			return ucfirst(strtolower($word));
		}, $words);

		// Gabungkan kembali kata-kata menjadi string
		$titleCasedString = implode(' ', $titleCasedWords);

		return $titleCasedString;
	}

	public function validation_isEditable($DocNo)
	{
		$OutstandingPayment = $this->db->get_where('qview_tagihan_purchase_payment_os', ['no_doc' => $DocNo])->num_rows();
		if ($OutstandingPayment == 0) {
			// is not editable
			return false;
		} else {
			// is editable
			return true;
		}
	}

	public function Generate_Identity_Number_Asset($param)
	{
		$rows = $this->db->get_where('tsys_identity_number', array(
			"parameter" => $param,
			"year" => date('Y'),
			"month" => date('m'),
		));

		$length = 4;
		if ($rows->num_rows() > 0) {
			$row = $rows->row();
			$newCount = intval($row->count) + 1;

			$this->db->where('parameter', $param);
			$this->db->where('year', date('Y'));
			$this->db->where('month', date('m'));
			$this->db->update('tsys_identity_number', [
				'count' => $newCount,
			]);

			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . "-" . $string;
		} else {
			$this->db->insert('tsys_identity_number', [
				"parameter" => $param,
				"year" => date('Y'),
				"month" => date('m'),
				"count" => 1,
			]);
			$newCount = 1;
			$string = substr(str_repeat(0, $length) . $newCount, -$length);
			$identity_number = date("y") . date("m") . "-" . $string;
		}

		return $param . "-" . $identity_number;
	}

	function roundToTwoDecimals($num)
	{
		return number_format(round($num * 100) / 100, 2, '.', ',');
	}

	function roundToFourDecimals($num)
	{
		return number_format(round($num, 4), 4, '.', ',');
	}

	function FormatIdr($num)
	{
		return number_format(round($num * 100) / 100, 2, '.', ',');
	}

	function FormatIdrAccounting($num)
	{
		return number_format(round($num * 100) / 100, 4, '.', ',');
	}
}
