<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StokBasah extends CI_Controller
{
    public $layout = 'layout';
    protected $tbl_hdr_lpb = 'ttrx_hdr_lpb_receive';
    protected $tbl_dtl_lpb = 'ttrx_dtl_lpb_receive';
    protected $Hst_entry_oven   = 'thst_in_to_oven';
    protected $tdtl_child_size_lpb = 'qview_dtl_size_item_lpb';
    protected $tmst_size_item_grid = 'tmst_size_item_grid';
    protected $Tmst_account     = 'tmst_account';
    protected $Tmst_item        = 'tmst_item';
    protected $Tmst_warehouse   = 'tmst_warehouse';
    protected $thst_pre_oven   = 'thst_pre_oven';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
        $this->load->model('m_lpb', 'lpb');
    }

    public function index()
    {
        $this->data['page_title'] = "Stok Kayu Basah";
        $this->data['page_content'] = "TrxWh/StokBasah/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/StokBasah/index.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function modal_list_lot_by_deskripsi()
    {
        $sysid_item = $this->input->get('sysid_material');
        $sysid_size = $this->input->get('Id_Size_Item');
        $status = $this->input->get('status');
        $row_material = $this->db->get_where($this->Tmst_item, ['SysId' => $sysid_item])->row();
        $this->data['Size'] = $this->db->get_where($this->tmst_size_item_grid, ['SysId' => $sysid_size])->row();

        $this->data['title_modal'] = $this->input->get('title');
        $this->data['row_material'] = $row_material;
        $this->data['list_lot'] =  $this->db->query("SELECT 
                                                    a.no_lot, 
                                                    c.grader, 
                                                    SUM(f.Qty_Usable) as qty, 
                                                    c.grader, 
                                                    c.tgl_kirim, 
                                                    c.tgl_finish_sortir, 
                                                    (SUM(f.Qty_usable) * f.Cubication) as kubikasi, 
                                                    d.Account_Name as nama, 
                                                    c.lpb, 
                                                    e.Warehouse_Name as placement
                                                FROM ttrx_dtl_lpb_receive a
                                                JOIN tmst_item b on a.sysid_material = b.SysId
                                                JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
                                                JOIN tmst_account d on c.id_supplier = d.SysId 
                                                LEFT JOIN tmst_warehouse e on a.placement = e.Warehouse_ID
                                                JOIN $this->tdtl_child_size_lpb f on  a.sysid = f.Id_Lot
                                                WHERE a.into_oven = $status
                                                AND c.status_lpb = 'SELESAI'
                                                AND a.sysid_material = $sysid_item
                                                AND f.Id_Size_Item = $sysid_size
                                                GROUP BY a.no_lot, f.Id_Size_Item
                                                ORDER BY a.no_lot, f.flag");
        $this->load->view("general-modal/m_list_lot_by_deskripsi", $this->data);
    }

    public function modal_list_size_lot()
    {
        $sysid = $this->input->get('sysid');
        // $this->data['Sizes'] = $this->db->get_where($this->tdtl_child_size_lpb, ['Id_Lot' => $sysid])->result();
        $this->data['lpb_dtl'] =  $this->db->query(
            "SELECT a.sysid, a.lpb_hdr, a.flag, a.no_lot, a.sysid_material, size.Qty_Usable, b.SysId as sysid_material, b.Item_Code as kode, b.Item_Name as deskripsi,
             a.lot_printed, a.placement, c.Warehouse_Name, COALESCE(SUM(size.Cubication * size.Qty_Usable),0) as kubikasi
            FROM ttrx_dtl_lpb_receive a
            left join tmst_item b on a.sysid_material = b.SysId
            left join tmst_warehouse c on a.placement = c.Warehouse_Id
            left join $this->tdtl_child_size_lpb as size on a.sysid = size.Id_Lot
            where a.sysid = '$sysid'
            group by a.no_lot
            order by a.flag"
        )->row();

        $queryRaw = "SELECT 
        a.no_lot,
        b.asal_kiriman,
        b.no_legalitas,
        b.jumlah_kiriman,
        b.jumlah_pcs_kiriman,
        b.tanggungan_uang_bongkar,
        i.Warehouse_Name as oven,
        b.lpb, b.grader, b.legalitas, b.penilaian,
        c.Account_Name as nama,
        d.Item_Code as kode, d.Item_Name as deskripsi,
        f.do_time as masuk_oven_pada, f.do_by as masuk_oven_oleh, f.remark_into_oven,
        g.do_time as keluar_oven_pada, g.do_by as keluar_oven_oleh, g.remark_out_of_oven,
        h.do_time as alokasi_pada, h.do_by as alokasi_oleh, h.remark_to_prd,
        j.nama_cost_center
        from ttrx_dtl_lpb_receive a
        join ttrx_hdr_lpb_receive b on a.lpb_hdr = b.lpb
        join tmst_account c on b.id_supplier = c.SysId
        join tmst_item d on a.sysid_material = d.SysId
        left join thst_in_to_oven f on a.no_lot = f.lot
        left join thst_out_of_oven g on a.no_lot = g.lot
        left join thst_material_to_prd h on a.no_lot = h.lot
        left join tmst_warehouse i on f.placement = i.Warehouse_Id
        LEFT JOIN tmst_cost_center j ON h.cost_center_id = j.SysId
        where a.sysid = '$sysid' LIMIT 1";
        $this->data['dtl'] = $this->db->query($queryRaw)->row();

        $this->data['title_modal'] = "Detail Bundle : " . $this->data['lpb_dtl']->no_lot;

        $this->load->view("general-modal/m_detail_size_lot", $this->data);
    }

    // ============================================ DATATABLE ============================================//

    public function DataTable_Stock_Kayu_Basah_by_deskripsi()
    {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'b.Item_Name',
            2 => 'b.Item_Code',
            3 => 'd.Item_Height',
            4 => 'd.Item_Width',
            5 => 'd.Item_Length',
            6 => 'd.Size_Code',
            7 => 'count(distinct a.no_lot)',
            8 => 'sum(a.qty)',
            9 => '(SUM(d.Qty_Usable) * d.Cubication)',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT b.Item_Name as deskripsi, 
                b.Item_Code as kode, 
                d.Item_Height as tebal, 
                d.Item_Width as lebar, 
                d.Item_Length as panjang,
                d.Size_Code,
                d.Id_Size_Item,
                count(distinct a.no_lot) as row_lot, 
                sum(d.Qty_Usable) as t_qty, 
                a.sysid_material,
                (SUM(d.Qty_Usable) * d.Cubication) as kubikasi
                FROM $this->tbl_dtl_lpb a
                JOIN $this->Tmst_item b on a.sysid_material = b.SysId
                JOIN $this->tbl_hdr_lpb c on a.lpb_hdr = c.lpb
                JOIN $this->tdtl_child_size_lpb d on a.sysid = d.Id_Lot
                WHERE a.into_oven = 0
                AND c.status_lpb  = 'SELESAI' ";

        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Item_Length LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Item_Width LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Size_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Item_Height LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $sql .= " GROUP by b.Item_Code, d.Id_Size_Item";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        if ($requestData['length'] != -1) {
            $sql .= " ORDER BY $order $dir LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        }
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid_material'] = $row["sysid_material"];
            $nestedData['deskripsi'] = $row["deskripsi"];
            $nestedData['kode'] = $row["kode"];
            $nestedData['tebal'] = floatval($row["tebal"]);
            $nestedData['lebar'] = floatval($row["lebar"]);
            $nestedData['panjang'] = floatval($row["panjang"]);
            $nestedData['Size_Code'] = $row["Size_Code"];
            $nestedData['Id_Size_Item'] = $row["Id_Size_Item"];
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

    public function DataTable_Stock_Kayu_Basah_by_lot()
    {
        $requestData = $_REQUEST;
        $columns = array(
            1 => 'a.no_lot',
            2 => 'b.Item_Name',
            3 => 'b.Item_Code',
            4 => 'd.Account_Name',
            5 => 'c.grader',
            6 => 'c.tgl_kirim',
            7 => 'c.tgl_finish_sortir',
            8 => 'a.qty',
            9 => '(f.Cubication * f.Qty_Usable)',
            10 => 'a.Warehouse_Name',
            11 => "CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,pre.do_time, NOW()) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,pre.do_time, NOW()) % 24, ' Jam')"
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT a.sysid, a.no_lot, d.Account_Name as nama , b.Item_Name as deskripsi, b.Item_Code as kode, c.grader, c.tgl_kirim, c.tgl_finish_sortir, SUM(f.Qty_Usable) as qty,
        CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,pre.do_time, NOW()) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,pre.do_time, NOW()) % 24, ' Jam') as timer_simpan,
        a.sysid_material, e.Warehouse_Name as placement, SUM(f.Cubication * f.Qty_Usable) as kubikasi
        FROM $this->tbl_dtl_lpb a
        JOIN $this->Tmst_item b on a.sysid_material = b.SysId
        JOIN $this->tbl_hdr_lpb c on a.lpb_hdr = c.lpb
        JOIN $this->Tmst_account d on c.id_supplier = d.SysId
        LEFT JOIN $this->Tmst_warehouse e on a.placement = e.Warehouse_ID
        LEFT JOIN $this->tdtl_child_size_lpb f on a.sysid = f.Id_Lot
        JOIN $this->thst_pre_oven pre on a.no_lot = pre.lot
        WHERE a.into_oven = 0
        AND c.status_lpb  = 'SELESAI'";

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Account_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR e.Warehouse_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.qty LIKE '%" . $requestData['search']['value'] . "%')";
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
            $nestedData['tgl_finish_sortir'] = $row["tgl_finish_sortir"];
            $nestedData['qty'] = $row["qty"];
            $nestedData['supplier'] = $row["nama"];
            $nestedData['kubikasi'] = floatval($row["kubikasi"]);
            $nestedData['timer_simpan'] = $row["timer_simpan"];
            $nestedData['placement'] = $row["placement"];

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
