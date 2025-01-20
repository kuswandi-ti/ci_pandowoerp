<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class m_Tax extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Fungsi untuk mendapatkan tax_rate berdasarkan tax_id
    public function get_tax_rate($tax_id)
    {
        $this->db->select('Tax_Rate');
        $this->db->from('tmst_tax');
        $this->db->where('Tax_Id', $tax_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return floatval($row->Tax_Rate);
        } else {
            return null; // Atau bisa mengembalikan 0 atau pesan error
        }
    }

    // Fungsi untuk menghitung pajak berdasarkan nominal dan tax_id
    public function calculate_tax($nominal, $tax_id)
    {
        // Ambil tax_rate berdasarkan tax_id
        $tax_rate = $this->get_tax_rate($tax_id);

        // Hitung pajak
        if ($tax_rate !== null) {
            $tax_amount = (floatval($nominal) * $tax_rate) / 100;
            return $tax_amount;
        } else {
            return false; // Atau bisa mengembalikan 0 atau pesan error
        }
    }
}
