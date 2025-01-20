<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KeringStok extends CI_Controller
{
    public $layout = 'layout';
    protected $Tbl_hdr_lpb      = 'ttrx_hdr_lpb_receive';
    protected $Tbl_dtl_lpb      = 'ttrx_dtl_lpb_receive';
    protected $Hst_oven         = 'thst_in_to_oven';
    protected $Tmst_account     = 'tmst_account';
    protected $Tmst_item        = 'tmst_item';
    protected $Tmst_warehouse   = 'tmst_warehouse';
    protected $qview_dtl_size_item_lpb   = 'qview_dtl_size_item_lpb';
    protected $tmst_size_item_grid   = 'tmst_size_item_grid';
    protected $Date;
    protected $DateTime;

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');
        $this->load->model('m_helper', 'help');
        $this->load->model('m_lpb', 'lpb');
    }

    public function index()
    {
        $this->data['page_title'] = "Stok Material Kering";
        $this->data['page_content'] = "TrxWh/KeringStok/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/KeringStok/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function modal_list_lot_by_deskripsi_kering()
    {
        $sysid_material = $this->input->get('sysid_material');
        $Id_Size_Item = $this->input->get('Id_Size_Item');
        $status = $this->input->get('status');
        $row_material = $this->db->get_where($this->Tmst_item, ['SysId' => $sysid_material])->row();

        $this->data['title_modal'] = $this->input->get('title');
        $this->data['row_material'] = $row_material;
        $this->data['Size'] = $this->db->get_where($this->tmst_size_item_grid, ['SysId' => $Id_Size_Item])->row();

        $this->data['list_lot'] =  $this->db->query("SELECT 
                                a.no_lot, 
                                SUM(size.Qty_Usable) as qty, 
                                (SUM(size.Qty_Usable) * size.Cubication) as kubikasi,
                                c.grader, 
                                c.tgl_kirim, 
                                c.tgl_finish_sortir, 
                                c.lpb,
                                b.Item_Code, 
                                b.Item_Name, 
                                d.Account_Name AS nama,
                                DATE_FORMAT(e.do_time, '%Y-%m-%d %H:%i') AS time_in,
                                DATE_FORMAT(f.do_time, '%Y-%m-%d %H:%i') AS time_out,
                                g.Warehouse_Name AS nama_oven,
                                h.Warehouse_Name AS lokasi,
                                CONCAT(
                                    TRUNCATE(TIMESTAMPDIFF(hour, e.do_time, f.do_time) / 24, 0), ' Hari, ', 
                                    TIMESTAMPDIFF(hour, e.do_time, f.do_time) % 24, ' Jam'
                                ) AS timer
                                FROM ttrx_dtl_lpb_receive a
                                JOIN $this->Tmst_item b ON a.sysid_material = b.SysId
                                JOIN ttrx_hdr_lpb_receive c ON a.lpb_hdr = c.lpb
                                JOIN $this->Tmst_account d ON c.id_supplier = d.SysId 
                                LEFT JOIN thst_in_to_oven e ON a.no_lot = e.lot
                                LEFT JOIN thst_out_of_oven f ON a.no_lot = f.lot
                                LEFT JOIN $this->Tmst_warehouse g ON e.placement = g.Warehouse_ID
                                LEFT JOIN $this->Tmst_warehouse h ON f.placement = h.Warehouse_ID
                                JOIN  $this->qview_dtl_size_item_lpb AS size ON a.sysid = size.Id_Lot
                                    WHERE a.into_oven = $status
                                    AND c.status_lpb = 'SELESAI'
                                    AND a.sysid_material = $sysid_material
                                    AND size.Id_Size_Item = $Id_Size_Item
                                GROUP BY 
                                    a.no_lot, size.Id_Size_Item 
                                ORDER BY 
                                    a.no_lot, size.Item_Length");

        $this->load->view("general-modal/m_list_lot_by_deskripsi_kering", $this->data);
    }


    // -------------------------------------- Datatable Section

    public function DataTable_Stock_material_kering_by_lot()
    {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'a.no_lot',
            2 => 'b.Item_Name',
            3 => 'b.Item_Code',
            4 => 'd.Account_Name',
            5 => 'c.grader',
            6 => 'c.tgl_kirim',
            8 => 'SUM(size.Qty_Usable)',
            9 => 'SUM(size.Cubication * size.Qty_Usable)',
            10 => 'g.Warehouse_Name',
            11 => 'e.do_time',
            12 => "CONCAT(FLOOR(HOUR(TIMEDIFF(e.do_time, f.do_time)) / 24), ' hari,', MOD(HOUR(TIMEDIFF(e.do_time, f.do_time)), 24), ' jam')",
            13 => "h.Warehouse_Name",
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT a.sysid, a.no_lot, d.Account_Name as nama , b.Item_Name as deskripsi, b.Item_Code as kode, c.grader, e.do_time as time_in, f.do_time as time_out,
        c.tgl_kirim, c.tgl_finish_sortir, SUM(size.Qty_Usable) as qty, a.sysid_material, SUM(size.Cubication * size.Qty_Usable) as kubikasi,
        CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,e.do_time, f.do_time) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,e.do_time, f.do_time) % 24, ' Jam') as timer,
        g.Warehouse_Name as nama_oven, h.Warehouse_Name as lokasi
        FROM $this->Tbl_dtl_lpb a
        JOIN $this->Tmst_item b on a.sysid_material = b.SysId
        JOIN $this->Tbl_hdr_lpb c on a.lpb_hdr = c.lpb
        JOIN $this->Tmst_account d on c.id_supplier = d.SysId
        JOIN thst_in_to_oven e on a.no_lot = e.lot
        JOIN thst_out_of_oven f on a.no_lot = f.lot
        LEFT JOIN $this->Tmst_warehouse g on e.placement = g.Warehouse_ID
        LEFT JOIN $this->Tmst_warehouse h on f.placement = h.Warehouse_ID
        LEFT JOIN $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot
        WHERE a.into_oven = 2
        AND c.status_lpb  = 'SELESAI'";

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR h.Warehouse_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Account_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR h.Warehouse_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR e.do_time LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR f.do_time LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR g.Warehouse_Name LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $sql .= " GROUP by a.no_lot ";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        if ($requestData['length'] != -1) {
            $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        }
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid'] = $row["sysid"];
            $nestedData['sysid_material'] = $row["sysid_material"];
            $nestedData['no_lot'] = $row["no_lot"];
            $nestedData['deskripsi'] = $row["deskripsi"];
            $nestedData['kode'] = $row["kode"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['tgl_kirim'] = $row["tgl_kirim"];
            $nestedData['lokasi'] = $row["lokasi"];
            $nestedData['tgl_finish_sortir'] = $row["tgl_finish_sortir"];
            $nestedData['qty'] = $row["qty"];
            $nestedData['supplier'] = $row["nama"];
            $nestedData['kubikasi'] = floatval($row["kubikasi"]);
            $nestedData['time_in'] = $row["time_in"];
            $nestedData['time_out'] = $row["time_out"];
            $nestedData['timer'] = $row["timer"];
            $nestedData['nama_oven'] = $row["nama_oven"];

            $data[] = $nestedData;
        }
        //----------------------------------------------------------------------------------
        $json_data = array(
            "draw"            => intval($requestData['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        //----------------------------------------------------------------------------------
        echo json_encode($json_data);
    }


    public function DataTable_Stock_material_kering_by_deskripsi()
    {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'b.Item_Name',
            2 => 'b.Item_Name',
            3 => 'size.Item_Height',
            4 => 'size.Item_Width',
            5 => 'size.Item_Length',
            6 => 'size.Size_Code',
            7 => 'count(distinct a.no_lot)',
            8 => 'SUM(size.Qty_Usable)',
            9 => '(SUM(size.Qty_Usable) * size.Cubication)',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT b.Item_name as deskripsi, b.Item_Code as kode, size.Item_Height as tebal, size.Item_Width as lebar, size.Item_Length as panjang,
                a.sysid_material, size.Size_Code, size.Id_Size_Item,
                count(distinct a.no_lot) as row_lot,
                SUM(size.Qty_Usable) as t_qty,
                (SUM(size.Qty_Usable) * size.Cubication) as kubikasi
                FROM ttrx_dtl_lpb_receive a
                JOIN $this->Tmst_item b on a.sysid_material = b.SysId
                JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
                JOIN $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot
                WHERE a.into_oven = 2
                AND c.status_lpb  = 'SELESAI' ";

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR b.inisial_kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR size.Item_Height LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR size.Item_Width LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR size.Item_Length LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $sql .= " GROUP by b.Item_Code, size.Id_Size_Item  ";
        $totalData = $this->db->query($sql)->num_rows();
        if ($requestData['length'] != -1) {
            $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        }
        $totalFiltered = $this->db->query($sql)->num_rows();
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid_material'] = $row["sysid_material"];
            $nestedData['deskripsi'] = $row["deskripsi"];
            $nestedData['Id_Size_Item'] = $row["Id_Size_Item"];
            $nestedData['Size_Code'] = $row["Size_Code"];
            $nestedData['kode'] = $row["kode"];
            $nestedData['tebal'] = floatval($row["tebal"]);
            $nestedData['lebar'] = floatval($row["lebar"]);
            $nestedData['panjang'] = floatval($row["panjang"]);
            $nestedData['row_lot'] = $row["row_lot"];
            $nestedData['t_qty'] = $row["t_qty"];
            $nestedData['kubikasi'] = floatval($row["kubikasi"]);

            $data[] = $nestedData;
        }
        //----------------------------------------------------------------------------------
        $json_data = array(
            "draw"            => intval($requestData['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        //----------------------------------------------------------------------------------
        echo json_encode($json_data);
    }
}
