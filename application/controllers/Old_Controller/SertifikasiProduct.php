<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SertifikasiProduct extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_customer    = 'tmst_customer';
    public $tbl_hdr_product = 'tmst_hdr_product';
    public $tbl_dtl_product = 'tmst_dtl_product';
    public $tbl_barcode     = 'thst_print_barcode_product';
    public $company_for_dn  = 'tmst_company_profile';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Sertifikasi Product";
        $this->data['page_content'] = "Sertifikasi/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/Sertifikasi/index.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function Preview_Data_Sertifikasi()
    {
        $barcode = $this->input->post('barcode');
        $Row_Barcode = $this->db->get_where('qview_detail_loading_per_product', ['Barcode_Value' => $barcode])->row();

        $Sql_Alloc = "SELECT * from qview_sertifikasi_material_product WHERE DATE_FORMAT(waktu_alloc,'%Y-%m-%d') = '$Row_Barcode->Date_Prd'";
        $this->data['Materials'] = $this->db->query($Sql_Alloc)->result();
        $this->data['barcode'] = $Row_Barcode;
        $this->load->view('Sertifikasi/Append_Sertifikasi', $this->data);
    }

    public function Print_Sertifikasi($barcode)
    {
        $this->data['company'] = $this->db->get($this->company_for_dn)->row();
        $this->data['preview'] = $this->input->get('preview');
        $Row_Barcode = $this->db->get_where('qview_detail_loading_per_product', ['Barcode_Value' => $barcode])->row();

        $Sql_Alloc = "SELECT * from qview_sertifikasi_material_product WHERE DATE_FORMAT(waktu_alloc,'%Y-%m-%d') = '$Row_Barcode->Date_Prd'";
        $this->data['Materials'] = $this->db->query($Sql_Alloc)->result();
        $this->data['barcode'] = $Row_Barcode;
        $this->load->view('Sertifikasi/print_sertifikasi', $this->data);
    }
}
