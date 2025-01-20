
<?php
class m_lpb extends CI_Model
{
    public function TableLpb_ToArray($ukuran, $qty)
    {
        for ($i = 0; $i < count($qty); $i++) {
            $nestedData = array();

            $nestedData['flag'] = $i + 1;
            // $nestedData['lot'] = $lot[$i];
            $nestedData['ukuran'] = $ukuran[$i];
            $nestedData['qty'] = $qty[$i];

            $data[] = $nestedData;
        }

        return $data;
    }

    public function prediksi_no_lpb($param)
    {
        $rows = $this->db->get_where('tsys_identity_number', array(
            "parameter" => $param,
            "year" => date('Y'),
            "month" => date('m')
        ));

        $length = 3;
        if ($rows->num_rows() > 0) {
            $row = $rows->row();
            $newCount = intval($row->count) + 1;

            $string = substr(str_repeat(0, $length) . $newCount, -$length);
            $identity_number = date("y") . date("m") . $string;
        } else {
            $newCount = 1;
            $string = substr(str_repeat(0, $length) . $newCount, -$length);
            $identity_number = date("y") . date("m") . $string;
        }

        return $identity_number;
    }
}
