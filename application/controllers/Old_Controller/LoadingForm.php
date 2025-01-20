<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LoadingForm extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_loading = 'ttrx_hdr_loading';
    public $tbl_dtl_loading = 'ttrx_dtl_loading';
    public $tmp_dtl_loading = 'ttmp_dtl_loading';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Form Loading Shipping";
        $this->data['page_content'] = "Loading/form_loading";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/Loading/form_loading.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function store_form_loading()
    {
        if (empty($this->input->post('silang_product'))) {
            $silang_product = 'FALSE';
        } else {
            $silang_product = $this->input->post('silang_product');
        }

        $no_loading = $this->help->Gnrt_Identity_Number('LDG');
        $this->db->trans_start();
        $this->db->insert($this->tbl_hdr_loading, [
            'No_loading' => $no_loading,
            'Customer_ID' =>  $this->input->post('customer'),
            'Product_ID' => $this->input->post('product'),
            'Qty_Loading' => $this->input->post('qty'),
            'STATUS' => 'LOADING',
            'Silang_Product' => $silang_product,
            'Selesai_at' => null,
            'Created_by' => $this->session->userdata('impsys_initial')
        ]);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', "Data loading gagal disimpan !");
            return redirect('LoadingForm/index');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', "Data loading berhasil di simpan, harap lanjutkan scan product untuk melengkapi data loading!");
            return redirect('Loading/loading_product/' . $no_loading);
        }
    }
}
