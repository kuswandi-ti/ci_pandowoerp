<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AllocationBundle extends CI_Controller
{
    public $layout = 'layout';
    protected $Tbl_hdr_lpb      = 'ttrx_hdr_lpb_receive';
    protected $Tbl_dtl_lpb      = 'ttrx_dtl_lpb_receive';
    protected $Hst_oven         = 'thst_in_to_oven';
    protected $Tmst_account     = 'tmst_account';
    protected $Tmst_item        = 'tmst_item';
    protected $Tmst_warehouse   = 'tmst_warehouse';
    protected $Tmst_CostCenter   = 'tmst_cost_center';
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
        $this->data['page_title'] = "Scan Alokasi Bundle ke Produksi";
        $this->data['page_content'] = "TrxWh/AllocationBundle/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/AllocationBundle/index.js?v=' . time() . '"></script>';

        $this->data['cost_centers'] = $this->db->get_where($this->Tmst_CostCenter, ['Is_Active' => '1', 'cc_group_id' => 1])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function history()
    {
        $this->data['page_title'] = "History Bundle Alokasi ke Produksi";
        $this->data['page_content'] = "TrxWh/AllocationBundle/history";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/AllocationBundle/history.js?v=' . time() . '"></script>';

        $this->data['cost_centers'] = $this->db->get_where($this->Tmst_CostCenter, ['Is_Active' => '1', 'cc_group_id' => 1])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function monitoring()
    {
        $this->data['page_title'] = "Monitoring Bundle Alokasi ke Produksi";
        $this->data['page_content'] = "TrxWh/AllocationBundle/monitoring";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/AllocationBundle/monitoring.js?v=' . time() . '"></script>';

        $this->data['cost_centers'] = $this->db->get_where($this->Tmst_CostCenter, ['Is_Active' => '1', 'cc_group_id' => 1])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function List_Cost_Center()
    {
        $products = $this->db->get_where($this->Tmst_CostCenter, ['Is_Active' => '1', 'cc_group_id' => 1])->result_array();
        foreach ($products as $row) {
            $data[$row['SysId']] = $row['nama_cost_center'] . ' (' . $row['kode_cost_center'] . ')';
        }
        echo json_encode($data);
    }

    public function post_alloc_prd()
    {
        $dateTime = date('Y-m-d H:i:s');
        $barcode = $this->input->post('barcode');
        $remark = $this->input->post('remark');

        $countRow = $this->db->get_where('ttrx_dtl_lpb_receive', ['no_lot' => $barcode])->num_rows();
        $countHstin = $this->db->get_where('thst_in_to_oven', ['lot' => $barcode])->num_rows();
        $countHstOut = $this->db->get_where('thst_out_of_oven', ['lot' => $barcode])->num_rows();
        $countHstOut = $this->db->get_where('thst_out_of_oven', ['lot' => $barcode]);
        $DataHstOut = $countHstOut->row();
        $countAlloc = $this->db->get_where('thst_material_to_prd', ['lot' => $barcode])->num_rows();

        if ($countRow == 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Kode barcode tidak terdaftar dalam system!'
            ]);
        }
        if ($countHstin < 1) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode ' . $barcode . ' belum dinyatakan masuk kd, pilih barcode yang telah masuk kd !'
            ]);
        }
        if ($countHstOut->num_rows() < 1) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode ' . $barcode . ' belum dinyatakan keluar kd, pilih barcode yang telah keluar kd !'
            ]);
        }
        if ($countAlloc >= 1) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode ' . $barcode . ' sudah dinyatakan ter-alokasi ke produksi, pilih barcode yang telah keluar kd atau ada di gudang kering !'
            ]);
        }

        if (strtotime($DataHstOut->do_time > time())) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode ' . $barcode . ' masih dalam proses kiln oven oleh subkontraktor !'
            ]);
        }

        $this->db->trans_start();
        $this->db->where('no_lot', $barcode);
        $this->db->update('ttrx_dtl_lpb_receive', [
            'into_oven' => 3,
            'placement' => 0
        ]);
        $this->db->insert('thst_material_to_prd', [
            "lot" => $barcode,
            "do_by" => $this->session->userdata('impsys_initial'),
            "do_time" => $dateTime,
            "remark_to_prd" => $remark,
            "cost_center_id" => $this->input->post('cc')
        ]);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = ['code' => 505, 'msg' => 'bundle gagal di alokasikan ke produksi!'];
        } else {
            $this->db->trans_commit();
            $response = ['code' => 200];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function preview_detail_lot()
    {
        $barcode = $this->input->get('barcode');

        $response = $this->db->query("SELECT 
                                    a.sysid,
                                    d.Account_Name AS nama, 
                                    a.lpb_hdr, 
                                    a.no_lot, 
                                    a.into_oven,
                                    c.Item_Code AS kode, 
                                    c.Item_Name, 
                                    COALESCE(SUM(size.Cubication * size.Qty),0) as kubikasi,
                                    SUM(size.Qty_Usable) as qty,
                                    b.grader,
                                    e.status_kayu,
                                    DATE_FORMAT(f.do_time, '%Y-%m-%d %H:%i') AS time_in,
                                    DATE_FORMAT(h.do_time, '%Y-%m-%d %H:%i') AS time_out,
                                    CONCAT(
                                        FLOOR(HOUR(TIMEDIFF(f.do_time, h.do_time)) / 24), ' hari,',
                                        MOD(HOUR(TIMEDIFF(f.do_time, h.do_time)), 24), ' jam'
                                    ) AS timer,
                                    g.Warehouse_Name AS nama_oven
                                    FROM ttrx_dtl_lpb_receive a
                                    JOIN ttrx_hdr_lpb_receive b ON a.lpb_hdr = b.lpb
                                    JOIN tmst_item c ON a.sysid_material = c.SysId
                                    JOIN tmst_account d ON b.id_supplier = d.SysId
                                    JOIN tmst_status_lot e ON a.into_oven = e.kode
                                    LEFT JOIN thst_in_to_oven f ON a.no_lot = f.lot
                                    LEFT JOIN tmst_warehouse g ON f.placement = g.Warehouse_ID
                                    LEFT JOIN thst_out_of_oven h ON a.no_lot = h.lot
                                    join $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot 
                                    WHERE a.no_lot = '$barcode'
                                    LIMIT 1")->row();

        if (empty($response)) {
            return $this->help->Fn_resulting_response(['code' => 505]);
        } else {
            return $this->help->Fn_resulting_response([
                "code" => 200,
                "supplier" => $response->nama,
                "lpb" => $response->lpb_hdr,
                "lot" => '<a href="javascript:void(0)" class="detail--size" data-pk="' . $response->sysid . '"><u>' . $response->no_lot . '</u></a>',
                "material" => $response->Item_Name,
                "qty" => $response->qty,
                "grader" => $response->grader,
                "kubikasi" => $this->help->roundToTwoDecimals($response->kubikasi),
                "time_in" => $response->time_in,
                "time_out" => $response->time_out,
                "timer" => $response->timer,
                "oven" => $response->nama_oven,
                "status" => '<button class="btn btn-info btn-flat"><i class="fas fa-map-marker-alt blink_me"></i> ' . $response->status_kayu . '</button>',
            ]);
        }
    }

    public function modal_list_lot_by_deskripsi_alloc_prd()
    {
        $sysid_material = $this->input->get('sysid_material');
        $Id_Size_Item = $this->input->get('Id_Size_Item');
        $status = $this->input->get('status');
        $row_material = $this->db->get_where('tmst_item', ['SysId' => $sysid_material])->row();
        $this->data['Size'] = $this->db->get_where($this->tmst_size_item_grid, ['SysId' => $Id_Size_Item])->row();

        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $cc = $this->input->get('cc');

        $filter_cc = "";
        if (!empty($product)) {
            $filter_cc = " AND g.cost_center_id = $product ";
        }

        $this->data['title_modal'] = $this->input->get('title');
        $this->data['row_material'] = $row_material;
        $this->data['time_range'] = "$from s/d $to";
        $this->data['list_lot'] =  $this->db->query("SELECT 
                                    a.no_lot, 
                                    c.grader,  
                                    c.tgl_kirim, 
                                    c.tgl_finish_sortir,
                                    SUM(size.Qty_Usable) as qty, 
                                    (SUM(size.Qty_Usable) * size.Cubication) as kubikasi,
                                    d.Account_Name AS nama, 
                                    c.lpb,
                                    DATE_FORMAT(e.do_time, '%Y-%m-%d %H:%i') AS time_in,
                                    DATE_FORMAT(f.do_time, '%Y-%m-%d %H:%i') AS time_out,
                                    DATE_FORMAT(g.do_time, '%Y-%m-%d %H:%i') AS time_alloc,
                                    CONCAT(
                                        TRUNCATE(TIMESTAMPDIFF(hour, e.do_time, f.do_time) / 24, 0), ' Hari, ', 
                                        TIMESTAMPDIFF(hour, e.do_time, f.do_time) % 24, ' Jam'
                                    ) AS timer,
                                    h.Warehouse_Name AS nama_oven, 
                                    i.kode_cost_center AS cc_code, 
                                    i.nama_cost_center AS cc_name
                                    FROM ttrx_dtl_lpb_receive a
                                    JOIN tmst_item b ON a.sysid_material = b.SysId
                                    JOIN ttrx_hdr_lpb_receive c ON a.lpb_hdr = c.lpb
                                    JOIN tmst_account d ON c.id_supplier = d.SysId 
                                    JOIN thst_in_to_oven e ON a.no_lot = e.lot
                                    JOIN thst_out_of_oven f ON a.no_lot = f.lot
                                    JOIN thst_material_to_prd g ON a.no_lot = g.lot
                                    LEFT JOIN tmst_warehouse h ON e.placement = h.Warehouse_ID
                                    JOIN tmst_cost_center i ON g.cost_center_id = i.SysId
                                    JOIN  $this->qview_dtl_size_item_lpb AS size ON a.sysid = size.Id_Lot
                                    WHERE a.into_oven = $status
                                        AND c.status_lpb = 'SELESAI'
                                        $filter_cc
                                        AND DATE_FORMAT(g.do_time, '%Y-%m-%d') >= '$from'
                                        AND DATE_FORMAT(g.do_time, '%Y-%m-%d') <= '$to'
                                        AND b.SysId = $sysid_material
                                        AND size.Id_Size_Item = $Id_Size_Item
                                    GROUP BY a.no_lot, size.Id_Size_Item 
                                    ORDER BY a.no_lot, size.Item_Length");
        $this->load->view("general-modal/m_list_lot_by_deskripsi_alloc_prd", $this->data);
    }

    // ================================================== DataTable Section

    public function DataTable_alloc_prd_by_deskripsi()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $cc = $this->input->get('cc');

        $filter_cc = "";
        if (!empty($cc)) {
            $filter_cc = " AND d.cost_center_id = $cc ";
        }

        $requestData = $_REQUEST;
        $columns = array(
            1 => 'b.Item_Name',
            2 => 'b.Item_Code',
            3 => 'size.Item_Height',
            4 => 'size.Item_Width',
            5 => 'size.Item_Length',
            6 => 'size.Size_Code',
            7 => 'COUNT(distinct a.no_lot)',
            8 => 'SUM(size.Qty_Usable)',
            9 => '(SUM(size.Qty_Usable) * size.Cubication)',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT 
                b.Item_Name AS deskripsi, 
                b.Item_Code AS kode, 
                size.Item_Height AS tebal, 
                size.Item_Width AS lebar, 
                size.Item_Length AS panjang,
                size.Size_Code,
                size.Id_Size_Item,
                COUNT(distinct a.no_lot) AS row_lot, 
                SUM(size.Qty_Usable) AS t_qty, 
                a.sysid_material, 
                (SUM(size.Qty_Usable) * size.Cubication) AS kubikasi
                FROM ttrx_dtl_lpb_receive a
                JOIN tmst_item b ON a.sysid_material = b.SysId
                JOIN ttrx_hdr_lpb_receive c ON a.lpb_hdr = c.lpb
                JOIN thst_material_to_prd d ON a.no_lot = d.lot
                JOIN $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot
                WHERE a.into_oven = 3
                AND c.status_lpb = 'SELESAI'
                $filter_cc
                AND DATE_FORMAT(d.do_time, '%Y-%m-%d') >= '$from'
                AND DATE_FORMAT(d.do_time, '%Y-%m-%d') <= '$to'";

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR size.Size_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR size.Item_Height LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR size.Item_Width LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR size.Item_Length LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $sql .= " GROUP BY a.sysid_material, size.Id_Size_Item ";
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
            $nestedData['Size_Code'] = $row["Size_Code"];
            $nestedData['Id_Size_Item'] = $row["Id_Size_Item"];
            $nestedData['deskripsi'] = $row["deskripsi"];
            $nestedData['kode'] = $row["kode"];
            $nestedData['tebal'] = floatval($row["tebal"]);
            $nestedData['lebar'] = floatval($row["lebar"]);
            $nestedData['panjang'] = floatval($row["panjang"]);
            $nestedData['row_lot'] = $row["row_lot"];
            $nestedData['t_qty'] = $this->help->FormatIdr($row["t_qty"]);
            $nestedData['kubikasi'] = $this->help->roundToTwoDecimals($row["kubikasi"]);

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

    public function DataTable_alloc_prd_by_lot()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $cc = $this->input->get('cc');

        $filter_cc = "";
        if (!empty($cc)) {
            $filter_cc = " AND g.cost_center_id = $cc ";
        } else {
        }

        $requestData = $_REQUEST;
        $columns = array(
            1 => 'a.no_lot',
            2 => 'b.Item_Name',
            3 => 'b.Item_Code',
            4 => 'd.Account_name',
            5 => 'c.grader',
            6 => 'c.tgl_kirim',
            7 => 'c.tgl_finish_sortir',
            8 => 'SUM(size.Qty_Usable)',
            9 => 'SUM(size.Cubication * size.Qty_Usable)',
            10 => 'h.Warehouse_name',
            11 => 'e.do_time',
            12 => "CONCAT(FLOOR(HOUR(TIMEDIFF(e.do_time, f.do_time)) / 24), ' hari,', MOD(HOUR(TIMEDIFF(e.do_time, f.do_time)), 24), ' jam')",
            13 => 'g.do_time',
            14 => 'i.nama_cost_center'
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT 
                a.sysid, 
                a.no_lot, 
                d.Account_Name AS nama, 
                b.Item_Name AS deskripsi, 
                b.Item_Code AS kode, 
                c.grader, 
                e.do_time AS time_in, 
                f.do_time AS time_out, 
                g.do_time AS time_alloc,
                c.tgl_kirim, 
                c.tgl_finish_sortir, 
                SUM(size.Qty_Usable) as qty, 
                SUM(size.Cubication * size.Qty_Usable) AS kubikasi,
                SUM(size.Cubication * size.Qty_Usable) * harga_per_pcs AS Harga_Lot,
                a.sysid_material, 
                h.Warehouse_Name AS nama_oven,
                i.nama_cost_center AS cc_name,
                CONCAT(
                    TRUNCATE(TIMESTAMPDIFF(hour, e.do_time, f.do_time) / 24, 0), ' Hari, ', 
                    TIMESTAMPDIFF(hour, e.do_time, f.do_time) % 24, ' Jam'
                ) AS timer
                FROM ttrx_dtl_lpb_receive a
                JOIN tmst_item b ON a.sysid_material = b.SysId
                JOIN ttrx_hdr_lpb_receive c ON a.lpb_hdr = c.lpb
                JOIN tmst_account d ON c.id_supplier = d.SysId
                JOIN thst_in_to_oven e ON a.no_lot = e.lot
                JOIN thst_out_of_oven f ON a.no_lot = f.lot
                JOIN thst_material_to_prd g ON a.no_lot = g.lot
                LEFT JOIN tmst_warehouse h ON e.placement = h.Warehouse_ID
                JOIN tmst_cost_center i ON g.cost_center_id = i.SysId
                LEFT JOIN $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot
                WHERE a.into_oven = 3
                    AND c.status_lpb = 'SELESAI'
                    $filter_cc
                    AND DATE_FORMAT(g.do_time, '%Y-%m-%d') >= '$from'
                    AND DATE_FORMAT(g.do_time, '%Y-%m-%d') <= '$to'";

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR b.inisial_kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Account_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR e.do_time LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR f.do_time LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR g.do_time LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR i.nama_cost_center LIKE '%" . $requestData['search']['value'] . "%' ";
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
            $nestedData['qty'] = $this->help->roundToTwoDecimals($row["qty"]);
            $nestedData['supplier'] = $row["nama"];
            $nestedData['kubikasi'] = $this->help->roundToFourDecimals($row["kubikasi"]);
            $nestedData['time_in'] = $row["time_in"];
            $nestedData['time_out'] = $row["time_out"];
            $nestedData['timer'] = $row["timer"];
            $nestedData['time_alloc'] = $row["time_alloc"];
            $nestedData['nama_oven'] = $row["nama_oven"];
            $nestedData['cc_name'] = $row["cc_name"];
            $nestedData['Harga_Lot'] = $row["Harga_Lot"];

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
