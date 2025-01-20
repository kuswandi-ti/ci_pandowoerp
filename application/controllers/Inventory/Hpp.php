<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hpp extends CI_Controller
{
    public $layout = 'layout';
    protected $tmst_item                                = 'tmst_item';
    protected $thst_hpp                                 = 'thst_hpp';
    protected $qmst_item                                = 'qmst_item';
    protected $qmst_item_finish_good_trading            = 'qmst_item_finish_good_trading';
    protected $tmst_warehouse                           = 'tmst_warehouse';
    protected $qview_tmst_warehouse_active              = 'qview_tmst_warehouse_active';
    protected $qstok_warehouse_item                     = 'qstok_warehouse_item';
    protected $Date;
    protected $DateTime;

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');
        $this->load->model('m_helper', 'help');
        $this->load->model('m_Warehouse', 'warehouse');
        $this->load->model('m_DataTable', 'M_Datatables');
    }

    public function index()
    {
        $this->data['page_title'] = "Harga Pokok Produksi " . $this->config->item('company_name');
        $this->data['page_content'] = "Inventory/Hpp/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/inventory-assets/hpp/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function GetDataLastPriceHpp()
    {
        $Item_Code = $this->input->post('Item_Code');

        $HppData = $this->db->query(
            "SELECT item.Item_Code, item.Item_Name,
            hpp.SysId, hpp.Hpp_Date, hpp.Hpp, hpp.Note, hpp.Created_at, hpp.Created_by, hpp.Last_Updated_at, hpp.Last_Updated_by
            FROM tmst_item item
            left join thst_hpp hpp on item.Item_Code = hpp.Item_Code
            where item.Item_Code = '$Item_Code'
            order by hpp.Hpp_Date, hpp.Created_at desc
            limit 1"
        )->row_array();

        return $this->help->Fn_resulting_response([
            'data' => $HppData
        ]);
    }

    public function store()
    {

        $state = $this->input->post('state');
        $SysId = $this->input->post('SysId_Item');
        $Item_Code = $this->input->post('Item_Code');
        $Hpp_Date = $this->input->post('Hpp_Date');
        $Hpp = floatval($this->input->post('Hpp'));
        $Note = $this->input->post('Note');

        $this->db->trans_start();

        $this->db->where('SysId', $SysId)->update($this->tmst_item, [
            'Spesific_Price_Fg' => $Hpp,
        ]);

        $this->db->insert($this->thst_hpp, [
            'Hpp_Date' => $Hpp_Date,
            'Item_Code' => $Item_Code,
            'Hpp' => $Hpp,
            'Note' => $Note,
            'Created_by' => $this->session->userdata('impsys_nik'),
            'Last_Updated_at' => $this->DateTime,
            'Last_Updated_by' => $this->session->userdata('impsys_nik'),
        ]);


        $error_msg = $this->db->error()["message"];
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => $error_msg
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Hpp Berhasil di perbarui !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    // ------------------------ Datatable Section

    public function DT_Hst_Hpp()
    {
        $Item_Code = $this->input->post('Item_Code');

        $query  = "SELECT hpp.SysId, hpp.Hpp_Date, hpp.Item_Code, hpp.Hpp, Note, hpp.Created_at, hpp.Created_by, hpp.Last_Updated_at, hpp.Last_Updated_by,
                    item.Item_Name, item.Default_Currency_Id, emp.nama 
                    FROM bicarase_pandowo_db.thst_hpp hpp
                    join tmst_item item on hpp.Item_Code = item.Item_Code 
                    join tmst_karyawan emp on hpp.Created_by = emp.nik ";

        $search = array('hpp.Item_Code', 'item.Item_Name', 'hpp.Hpp_Date', 'emp.nama');

        $where  = array('hpp.Item_Code' => $Item_Code);

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';
        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_query($query, $search, $where, $isWhere);
    }
}
