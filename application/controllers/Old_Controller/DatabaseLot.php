<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DatabaseLot extends CI_Controller
{
    public $layout = 'layout';
    public $tbl_hdr_lpb = 'ttrx_hdr_lpb_receive';
    public $tbl_dtl_lpb = 'ttrx_dtl_lpb_receive';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "DataBase LOT";
        $this->data['page_content'] = "DatabaseLot/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/DataBaseLot-script/DataBaseLot.js"></script>';

        $this->data['materials'] = $this->db->get_where('tmst_material_kayu', ['is_active' => 1])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function modal_list_lot_by_deskripsi()
    {
        $sysid_material = $this->input->get('sysid_material');
        $status = $this->input->get('status');
        $row_material = $this->db->get_where('tmst_material_kayu', ['sysid' => $sysid_material])->row();

        $this->data['title_modal'] = $this->input->get('title');
        $this->data['row_material'] = $row_material;
        $this->data['list_lot'] =  $this->db->query("SELECT a.no_lot, c.grader, a.qty, c.grader, c.tgl_kirim, c.tgl_finish_sortir, a.qty * ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi, d.nama, c.lpb, a.placement
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_material_kayu b on a.sysid_material = b.sysid
        JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
        join tmst_supplier_material d on c.id_supplier = d.sysid 
        WHERE a.into_oven = $status
        AND c.status_lpb  = 'SELESAI'
        AND b.tebal = " . floatval($row_material->tebal) . "
        AND b.lebar = " . floatval($row_material->lebar) . "
        AND b.panjang = " . floatval($row_material->panjang) . "
        group BY a.no_lot
        order BY a.no_lot");
        $this->load->view("general-modal/m_list_lot_by_deskripsi", $this->data);
    }

    public function modal_list_lot_by_deskripsi_inOven()
    {
        $sysid_material = $this->input->get('sysid_material');
        $status = $this->input->get('status');
        $row_material = $this->db->get_where('tmst_material_kayu', ['sysid' => $sysid_material])->row();

        $this->data['title_modal'] = $this->input->get('title');
        $this->data['row_material'] = $row_material;
        $this->data['list_lot'] =  $this->db->query("SELECT a.no_lot, c.grader, a.qty, c.grader, c.tgl_kirim, c.tgl_finish_sortir, 
        a.qty * ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi, d.nama, c.lpb,
        DATE_FORMAT(e.do_time, '%Y-%m-%d %H:%i') as do_time,
        CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,e.do_time, NOW()) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,e.do_time, NOW()) % 24, ' Jam') as timer,
        f.nama as nama_oven
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_material_kayu b on a.sysid_material = b.sysid
        JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
        join tmst_supplier_material d on c.id_supplier = d.sysid 
        left join thst_in_to_oven e on a.no_lot = e.lot
        left join tmst_identity_oven f on e.oven = f.sysid
        WHERE a.into_oven = $status
        AND c.status_lpb  = 'SELESAI'
        AND b.tebal = " . floatval($row_material->tebal) . "
        AND b.lebar = " . floatval($row_material->lebar) . "
        AND b.panjang = " . floatval($row_material->panjang) . "
        group BY a.no_lot
        order BY a.no_lot");
        $this->load->view("general-modal/m_list_lot_by_deskripsi_inOven", $this->data);
    }

    public function modal_list_lot_by_deskripsi_kering()
    {
        $sysid_material = $this->input->get('sysid_material');
        $status = $this->input->get('status');
        $row_material = $this->db->get_where('tmst_material_kayu', ['sysid' => $sysid_material])->row();

        $this->data['title_modal'] = $this->input->get('title');
        $this->data['row_material'] = $row_material;
        $this->data['list_lot'] =  $this->db->query("SELECT a.no_lot, c.grader, a.qty, c.grader, c.tgl_kirim, c.tgl_finish_sortir, a.qty * ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi, d.nama, c.lpb, g.nama as nama_oven,
        DATE_FORMAT(e.do_time, '%Y-%m-%d %H:%i') as time_in, DATE_FORMAT(f.do_time, '%Y-%m-%d %H:%i') as time_out, h.lokasi,
        CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,e.do_time, f.do_time) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,e.do_time, f.do_time) % 24, ' Jam') as timer
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_material_kayu b on a.sysid_material = b.sysid
        JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
        join tmst_supplier_material d on c.id_supplier = d.sysid 
        left join thst_in_to_oven e on a.no_lot = e.lot
        left join thst_out_of_oven f on a.no_lot = f.lot
        left join tmst_identity_oven g on e.oven = g.sysid
        left join tmst_placement_material h on f.placement = h.sysid
        WHERE a.into_oven = $status
        AND c.status_lpb  = 'SELESAI'
        AND b.tebal = " . floatval($row_material->tebal) . "
        AND b.lebar = " . floatval($row_material->lebar) . "
        AND b.panjang = " . floatval($row_material->panjang) . "
        group BY a.no_lot
        order BY a.no_lot");
        $this->load->view("general-modal/m_list_lot_by_deskripsi_kering", $this->data);
    }
    public function modal_list_lot_by_deskripsi_alloc_prd()
    {
        $sysid_material = $this->input->get('sysid_material');
        $status = $this->input->get('status');
        $row_material = $this->db->get_where('tmst_material_kayu', ['sysid' => $sysid_material])->row();

        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $product = $this->input->get('product');

        if (!empty($product)) {
            $filter_product = " AND g.sysid_product = $product ";
        } else {
            $filter_product = "";
        }

        $this->data['title_modal'] = $this->input->get('title');
        $this->data['row_material'] = $row_material;
        $this->data['time_range'] = "$from s/d $to";
        $this->data['list_lot'] =  $this->db->query("SELECT a.no_lot, c.grader, a.qty, c.grader, c.tgl_kirim, c.tgl_finish_sortir, a.qty * ((b.tebal * b.lebar * b.panjang) / 1000000) as kubikasi, d.nama, c.lpb, DATE_FORMAT(e.do_time, '%Y-%m-%d %H:%i') as time_in, DATE_FORMAT(f.do_time, '%Y-%m-%d %H:%i') as time_out, DATE_FORMAT(g.do_time, '%Y-%m-%d %H:%i') as time_alloc,
         CONCAT(TRUNCATE(TIMESTAMPDIFF(hour ,e.do_time, f.do_time) / 24,0), ' Hari, ', TIMESTAMPDIFF(hour ,e.do_time, f.do_time) % 24, ' Jam') as timer,
         h.nama as nama_oven, i.Kode AS kode_product, i.Nama AS nama_product
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_material_kayu b on a.sysid_material = b.sysid
        JOIN ttrx_hdr_lpb_receive c on a.lpb_hdr = c.lpb
        join tmst_supplier_material d on c.id_supplier = d.sysid 
        join thst_in_to_oven e on a.no_lot = e.lot
        join thst_out_of_oven f on a.no_lot = f.lot
        join thst_material_to_prd g on a.no_lot = g.lot
        join tmst_identity_oven h on e.oven = h.sysid
        JOIN tmst_hdr_product i ON g.sysid_product = i.sysid
        WHERE a.into_oven = $status
        AND c.status_lpb  = 'SELESAI'
        $filter_product
        AND DATE_FORMAT(g.do_time, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(g.do_time, '%Y-%m-%d') <= '$to' 
        AND b.tebal = " . floatval($row_material->tebal) . "
        AND b.lebar = " . floatval($row_material->lebar) . "
        AND b.panjang = " . floatval($row_material->panjang) . "
        group BY a.no_lot
        order BY a.no_lot");
        $this->load->view("general-modal/m_list_lot_by_deskripsi_alloc_prd", $this->data);
    }
    public function modal_list_lot_by_deskripsi_alloc_prd_today()
    {
        $sysid_material = $this->input->get('sysid_material');
        $status = $this->input->get('status');
        $today = date('Y-m-d');
        $row_material = $this->db->get_where('tmst_material_kayu', ['sysid' => $sysid_material])->row();

        $this->data['title_modal'] = $this->input->get('title');
        $this->data['row_material'] = $row_material;
        $this->data['time_range'] = $today;
        $this->data['list_lot'] =  $this->db->query("SELECT a.no_lot, c.grader, a.qty, c.grader, c.tgl_kirim, c.tgl_finish_sortir, a.qty * ((b.tebal * b.lebar * b.panjang) / 1000000) AS kubikasi, d.nama, c.lpb, DATE_FORMAT(e.do_time, '%Y-%m-%d %H:%i') AS time_in, DATE_FORMAT(f.do_time, '%Y-%m-%d %H:%i') AS time_out, DATE_FORMAT(g.do_time, '%Y-%m-%d %H:%i') AS time_alloc, CONCAT(FLOOR(HOUR(TIMEDIFF(e.do_time, f.do_time)) / 24), ' hari,', MOD(HOUR(TIMEDIFF(e.do_time, f.do_time)), 24), ' jam') AS timer,
         h.nama AS nama_oven, i.Kode AS kode_product, i.Nama AS nama_product
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_material_kayu b ON a.sysid_material = b.sysid
        JOIN ttrx_hdr_lpb_receive c ON a.lpb_hdr = c.lpb
        JOIN tmst_supplier_material d ON c.id_supplier = d.sysid 
        JOIN thst_in_to_oven e ON a.no_lot = e.lot
        JOIN thst_out_of_oven f ON a.no_lot = f.lot
        JOIN thst_material_to_prd g ON a.no_lot = g.lot
        JOIN tmst_identity_oven h ON e.oven = h.sysid
        JOIN tmst_hdr_product i ON g.sysid_product = i.sysid
        WHERE a.into_oven = $status
        AND c.status_lpb  = 'SELESAI'
        AND b.tebal = " . floatval($row_material->tebal) . "
        AND b.lebar = " . floatval($row_material->lebar) . "
        AND b.panjang = " . floatval($row_material->panjang) . "
        AND DATE_FORMAT(g.do_time, '%Y-%m-%d') = '$today'
        GROUP BY a.no_lot
        ORDER BY a.no_lot");
        $this->load->view("general-modal/m_list_lot_by_deskripsi_alloc_prd", $this->data);
    }

    public function dtl_HstDataLot()
    {
        $no_lot = $this->input->get('lot');
        $queryRaw = "SELECT 
        a.no_lot, a.qty, i.nama as oven,
        b.lpb, b.grader, b.legalitas, b.penilaian,
        c.nama,
        d.tebal, d.lebar, d.panjang, d.kode, d.deskripsi,
        f.do_time as masuk_oven_pada, f.do_by as masuk_oven_oleh, f.remark_into_oven,
        g.do_time as keluar_oven_pada, g.do_by as keluar_oven_oleh, g.remark_out_of_oven,
        h.do_time as alokasi_pada, h.do_by as alokasi_oleh, h.remark_to_prd,
        j.Nama as nama_product
        from ttrx_dtl_lpb_receive a
        join ttrx_hdr_lpb_receive b on a.lpb_hdr = b.lpb
        join tmst_supplier_material c on b.id_supplier = c.sysid
        join tmst_material_kayu d on a.sysid_material = d.sysid
        left join thst_in_to_oven f on a.no_lot = f.lot
        left join thst_out_of_oven g on a.no_lot = g.lot
        left join thst_material_to_prd h on a.no_lot = h.lot
        left join tmst_identity_oven i on f.oven = i.sysid
        left join tmst_hdr_product j on h.sysid_product = j.sysid
        where a.no_lot = '$no_lot' LIMIT 1";
        $this->data['dtl'] = $this->db->query($queryRaw)->row();
        // $this->data['RowLot'] = $this->db->get_where($this->tbl_dtl_lpb, ['no_lot' => $no_lot])->row();
        // $this->data['RowLpb'] = $this->db->get_where($this->tbl_hdr_lpb, ['lpb' => $this->data['RowLot']->lpb_hdr])->row();
        // $this->data['supplier'] = $this->db->get_where('tmst_supplier_material', ['sysid' => $this->data['RowLpb']->id_supplier])->row();
        // $this->data['material'] = $this->db->get_where('tmst_material_kayu', ['sysid' => $this->data['RowLot']->sysid_material])->row();
        // $this->data['RowInToOven'] = $this->db->get_where('thst_in_to_oven', ['lot' => $no_lot])->row();
        // $this->data['oven'] = $this->db->get_where('tmst_identity_oven', ['sysid' => $this->data['RowInToOven']->oven])->row();
        // $this->data['RowOutOven'] = $this->db->get_where('thst_out_of_oven', ['lot' => $no_lot])->row();
        // $this->data['RowAllocPrd'] = $this->db->get_where('thst_material_to_prd', ['lot' => $no_lot])->row();

        $this->load->view("general-modal/m_hst_lot_allocprd", $this->data);
    }

    public function DataTable_DataBase_Lot()
    {
        $material = $this->input->get('material');
        $sql_material = "";
        if (!empty($material)) {
            $sql_material = " AND a.sysid_material = $material ";
        }

        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.lpb_hdr',
            1 => 'c.nama',
            2 => 'b.grader',
            3 => 'd.kode',
            4 => 'a.no_lot',
            5 => 'a.harga_per_pcs'
        );

        $from = $this->input->get('from');
        $to = $this->input->get('to');

        $sql = "SELECT a.lpb_hdr, a.no_lot, d.kode, a.harga_per_pcs, a.qty, c.nama, b.grader, b.tgl_kirim, b.selesai_at, b.tgl_finish_sortir, a.into_oven, a.qty * ((d.tebal * d.lebar * d.panjang) / 1000000) as kubikasi, e.status_kayu
        FROM ttrx_dtl_lpb_receive a
        left join ttrx_hdr_lpb_receive b on a.lpb_hdr = b.lpb
        left join tmst_supplier_material c on b.id_supplier = c.sysid
        left join tmst_material_kayu d on a.sysid_material = d.sysid
        left join tmst_status_lot e on a.into_oven = e.kode
        where b.status_lpb = 'SELESAI'
        $sql_material
        AND DATE_FORMAT(b.selesai_at, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(b.selesai_at, '%Y-%m-%d') <= '$to'";

        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.lpb_hdr LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.selesai_at LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR e.status_kayu LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%')";
        }
        $sql .= " GROUP BY a.sysid ,a.no_lot ";
        $totalData = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY b.sysid desc, a.flag asc  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        $totalFiltered = $this->db->query($sql)->num_rows();
        $query = $this->db->query($sql);
        $data = array();
        $no = 1;
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['lpb_hdr'] = $row["lpb_hdr"];
            $nestedData['no_lot'] = $row["no_lot"];
            $nestedData['kode'] = $row["kode"];
            $nestedData['harga_per_pcs'] = 'Rp' . number_format($row["harga_per_pcs"], 0, ',', '.');
            $nestedData['qty'] = $row["qty"];
            $nestedData['kubikasi'] = floatval($row["kubikasi"]);
            $nestedData['subtotal'] = 'Rp. ' . number_format(floatval($row["qty"]) * floatval($row["harga_per_pcs"]), 0, ',', '.');
            $nestedData['supplier'] = $row["nama"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['tgl_kirim'] = $row["tgl_kirim"];
            $nestedData['selesai_at'] = $row["selesai_at"];
            $nestedData['tgl_finish_sortir'] = $row["tgl_finish_sortir"];
            $nestedData['into_oven'] = $row["into_oven"];
            $nestedData['status_kayu'] = $row["status_kayu"];


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
