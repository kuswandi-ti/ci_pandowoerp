<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExitKd extends CI_Controller
{
    public $layout = 'layout';
    protected $Tbl_hdr_lpb      = 'ttrx_hdr_lpb_receive';
    protected $Tbl_dtl_lpb      = 'ttrx_dtl_lpb_receive';
    protected $Hst_entry_oven   = 'thst_in_to_oven';
    protected $Hst_exit_oven    = 'thst_out_of_oven';
    protected $Tmst_account     = 'tmst_account';
    protected $Tmst_item        = 'tmst_item';
    protected $Tmst_warehouse   = 'tmst_warehouse';
    protected $qview_dtl_size_item_lpb   = 'qview_dtl_size_item_lpb';
    protected $tmst_size_item_grid   = 'tmst_size_item_grid';
    protected $ParamKD          = ['Is_Kiln' => 1, 'Is_Active' => 1];
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
        $this->data['page_title'] = "Scan Barcode : Exit KD";
        $this->data['page_content'] = "TrxWh/ExitKd/exit_scan";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/ExitKd/exit_scan.js?v=' . time() . '"></script>';

        $this->data['placements'] = $this->db->get_where('tmst_warehouse', ['Is_Wh_After_Kiln' => 1, 'Is_Active' => 1])->result();

        $this->load->view($this->layout, $this->data);
    }

    public function history()
    {
        $this->data['page_title'] = "History Bahan Baku Exit Kiln";
        $this->data['page_content'] = "TrxWh/ExitKd/history";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/ExitKd/history.js?v=' . time() . '"></script>';

        $this->data['items'] = $this->db->get_where('qmst_item', ['Is_Active' => 1, 'Is_Grid_Item' => 1])->result();
        $this->data['kilns'] = $this->db->get_where($this->Tmst_warehouse, $this->ParamKD)->result();

        $this->load->view($this->layout, $this->data);
    }

    public function update_out_oven()
    {
        $barcode = $this->input->post('barcode');
        $placement = $this->input->post('placement');
        $remark = $this->input->post('remark');

        $countHstOut    = $this->db->get_where($this->Hst_exit_oven, ['lot' => $barcode])->num_rows();
        $countRow       = $this->db->get_where($this->Tbl_dtl_lpb, ['no_lot' => $barcode])->num_rows();
        $countHstin     = $this->db->get_where($this->Hst_entry_oven, ['lot' => $barcode])->num_rows();

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
        if ($countHstOut > 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode ' . $barcode . ' sudah dinyatakan keluar kd, pilih barcode lain!'
            ]);
        }

        $this->db->trans_start();
        $this->db->where('no_lot', $barcode);
        $this->db->update($this->Tbl_dtl_lpb, [
            'into_oven' => 2,
            'placement' => $placement
        ]);
        $this->db->insert($this->Hst_exit_oven, [
            "lot" => $barcode,
            "do_by" => $this->session->userdata('impsys_initial'),
            "do_time" => $this->DateTime,
            "remark_out_of_oven" => $remark,
            "placement" => $placement
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = ['code' => 505, 'msg' => 'lot gagal dinyatakan keluar kd!'];
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
                    d.Account_Name AS nama, 
                    a.lpb_hdr, 
                    a.sysid, 
                    a.no_lot,
                    a.into_oven, 
                    c.Item_Code AS kode, 
                    c.Item_Name, 
                    COALESCE(SUM(size.Cubication * size.Qty),0) as kubikasi,
                    SUM(size.Qty_Usable) as qty,
                    b.grader, 
                    b.tgl_kirim, 
                    b.tgl_finish_sortir, 
                    e.status_kayu, 
                    g.Warehouse_Name AS nama_oven,
                    DATE_FORMAT(f.do_time, '%Y-%m-%d %H:%i') AS time_in, 
                    CASE 
                        WHEN h.do_time IS NULL THEN 
                            CONCAT(
                                TRUNCATE(TIMESTAMPDIFF(hour, f.do_time, NOW()) / 24, 0), ' Hari, ', 
                                TIMESTAMPDIFF(hour, f.do_time, NOW()) % 24, ' Jam'
                            )
                        ELSE 
                            CONCAT(
                                TRUNCATE(TIMESTAMPDIFF(hour, f.do_time, h.do_time) / 24, 0), ' Hari, ', 
                                TIMESTAMPDIFF(hour, f.do_time, h.do_time) % 24, ' Jam'
                            )
                    END AS timer
                FROM $this->Tbl_dtl_lpb a
                JOIN $this->Tbl_hdr_lpb b ON a.lpb_hdr = b.lpb
                JOIN $this->Tmst_item c ON a.sysid_material = c.SysId
                JOIN $this->Tmst_account d ON b.id_supplier = d.SysId
                JOIN tmst_status_lot e ON a.into_oven = e.kode
                LEFT JOIN $this->Hst_entry_oven f ON a.no_lot = f.lot
                LEFT JOIN $this->Tmst_warehouse g ON f.placement = g.Warehouse_ID
                LEFT JOIN $this->Hst_exit_oven h ON a.no_lot = h.lot
                join $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot 
                WHERE a.no_lot = '$barcode'

                LIMIT 1
        ")->row();

        if (floatval(substr($response->timer, 0, 2)) >= 6) {
            $timer = '<span class="badge badge-danger"><i class="blink_me">' . $response->timer . '</i></span>';
        } else {
            $timer = '<span class="badge badge-xs badge-info"><i>' . $response->timer . '</i></span>';
        }

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
                "time_in" => $response->time_in,
                "kubikasi" => floatval($response->kubikasi),
                "oven" => $response->nama_oven,
                "timer" => $timer,
                "status" => '<button class="btn btn-info btn-flat"><i class="fas fa-map-marker-alt blink_me"></i> ' . $response->status_kayu . '</button>',
            ]);
        }
    }

    public function DataTable_HstOven_by_Lot()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $oven = $this->input->get('oven');
        $material = $this->input->get('material');
        $sql_oven = "";
        $sql_material = "";
        if (!empty($oven)) {
            $sql_oven = " AND e.placement = $oven ";
        }
        if (!empty($material)) {
            $sql_material = " AND a.sysid_material = $material ";
        }


        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.sysid',
            1 => 'a.no_lot',
            2 => 'b.Item_Name',
            3 => 'b.Item_Code',
            4 => 'd.Acount_Name',
            5 => 'c.grader',
            6 => 'c.tgl_kirim',
            7 => 'SUM(size.Qty_Usable)',
            8 => 'SUM(size.Cubication * size.Qty_Usable)',
            9 => "CONCAT(FLOOR(HOUR(TIMEDIFF(e.do_time, h.do_time)) / 24), ' hari,', MOD(HOUR(TIMEDIFF(e.do_time, h.do_time)), 24), ' jam')",
            10 => 'f.Warehouse_Name',
            11 => 'e.do_time',
            12 => 'h.do_time',
            13 => 'g.status_kayu',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT a.sysid, 
                a.no_lot, 
                SUM(size.Qty_Usable) as qty, 
                a.sysid_material, 
                a.into_oven,
                SUM(size.Cubication * size.Qty_Usable) AS kubikasi,
                c.grader, 
                c.tgl_finish_sortir, 
                c.tgl_kirim,
                b.Item_Name AS deskripsi, 
                b.Item_Code AS kode,
                d.Account_Name AS nama,
                e.do_time AS time_in,
                h.do_time AS time_out,
                f.Warehouse_Name AS nama_oven,
                g.status_kayu,
                CONCAT(
                    TRUNCATE(TIMESTAMPDIFF(hour, e.do_time, h.do_time) / 24, 0), ' Hari, ', 
                    TIMESTAMPDIFF(hour, e.do_time, h.do_time) % 24, ' Jam'
                ) AS timer
                FROM ttrx_dtl_lpb_receive a
                JOIN tmst_item b ON a.sysid_material = b.SysId
                JOIN ttrx_hdr_lpb_receive c ON a.lpb_hdr = c.lpb
                JOIN tmst_account d ON c.id_supplier = d.SysId
                JOIN thst_in_to_oven e ON a.no_lot = e.lot
                LEFT JOIN tmst_warehouse f ON e.placement = f.Warehouse_ID
                JOIN tmst_status_lot g ON a.into_oven = g.kode
                JOIN thst_out_of_oven h ON a.no_lot = h.lot
                JOIN $this->qview_dtl_size_item_lpb size on a.sysid = size.Id_Lot
                WHERE c.status_lpb = 'SELESAI'
                $sql_oven
                $sql_material
                AND DATE_FORMAT(h.do_time, '%Y-%m-%d') >= '$from'
                AND DATE_FORMAT(h.do_time, '%Y-%m-%d') <= '$to'";


        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.deskripsi LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.inisial_kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR f.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR  LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR g.status_kayu LIKE '%" . $requestData['search']['value'] . "%')";
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
            $nestedData['no_lot'] = $row["no_lot"];
            $nestedData['deskripsi'] = $row["deskripsi"];
            $nestedData['kode'] = $row["kode"];
            $nestedData['supplier'] = $row["nama"];
            $nestedData['grader'] = $row["grader"];
            $nestedData['tgl_kirim'] = $row["tgl_kirim"];
            $nestedData['tgl_kirim'] = $row["tgl_kirim"];
            $nestedData['qty'] = $row["qty"];
            $nestedData['kubikasi'] = floatval($row["kubikasi"]);
            $nestedData['timer'] = $row["timer"];
            $nestedData['nama_oven'] = $row["nama_oven"];
            $nestedData['time_in'] = $row["time_in"];
            $nestedData['time_out'] = $row["time_out"];
            $nestedData['status_kayu'] = $row["status_kayu"];
            $nestedData['into_oven'] = floatval($row["into_oven"]);

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

    public function DataTable_HstOven_by_Desc()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $oven = $this->input->get('oven');
        $material = $this->input->get('material');
        $sql_oven = "";
        $sql_material = "";
        if (!empty($oven)) {
            $sql_oven = " AND d.placement = $oven ";
        }
        if (!empty($material)) {
            $sql_material = " AND a.sysid_material = $material ";
        }

        $requestData = $_REQUEST;
        $columns = array(
            1 => 'b.Item_Name',
            2 => 'b.Item_Code',
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

        $sql = "SELECT 
        b.Item_Name as deskripsi,
        b.Item_Code as kode,
        size.Item_Height as tebal,
        size.Item_Width as lebar,
        size.Item_Length as panjang,
        size.Id_Size_Item,
        size.Size_Code,
        a.sysid_material,
        count(a.no_lot) as row_lot,
        SUM(size.Qty_Usable) as t_qty, 
        (SUM(size.Qty_Usable) * size.Cubication) as kubikasi
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_item b ON a.sysid_material = b.SysId
        JOIN ttrx_hdr_lpb_receive c ON a.lpb_hdr = c.lpb
        JOIN thst_in_to_oven d ON a.no_lot = d.lot
        JOIN thst_out_of_oven e ON a.no_lot = e.lot
        JOIN $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot
        WHERE c.status_lpb  = 'SELESAI' 
        $sql_oven
        $sql_material
        AND DATE_FORMAT(e.do_time, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(e.do_time, '%Y-%m-%d') <= '$to'";

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

    public function M_Hst_Lot_By_Material()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $oven = $this->input->get('oven');
        $material = $this->input->get('material'); // from filter 
        $sql_oven = "";
        $sql_material = "";
        if (!empty($oven)) {
            $sql_oven = " AND e.oven = $oven ";
        }
        if (!empty($material)) {
            $sql_material = " AND a.sysid_material = $material ";
        }
        $sysid_material = $this->input->get('sysid_material'); // from datatable
        $Id_Size_Item = $this->input->get('Id_Size_Item'); // from datatable
        $status = $this->input->get('status');
        $row_material = $this->db->get_where('tmst_item', ['SysId' => $sysid_material])->row();

        $this->data['title_modal'] = $this->input->get('title');
        $this->data['Size'] = $this->db->get_where($this->tmst_size_item_grid, ['SysId' => $Id_Size_Item])->row();
        $this->data['row_material'] = $row_material;
        $this->data['from'] = $from;
        $this->data['to'] = $to;

        $this->data['list_lot'] =  $this->db->query("SELECT 
                                    a.no_lot, 
                                    SUM(size.Qty_Usable) as qty, 
                                    (SUM(size.Qty_Usable) * size.Cubication) as kubikasi,
                                    size.Item_Height, 
                                    size.Item_Width, 
                                    size.Item_Length,
                                    c.grader, 
                                    c.tgl_kirim, 
                                    c.tgl_finish_sortir, 
                                    c.lpb,
                                    d.Account_Name AS nama,
                                    DATE_FORMAT(e.do_time, '%Y-%m-%d %H:%i') AS do_time_in,
                                    DATE_FORMAT(g.do_time, '%Y-%m-%d %H:%i') AS do_time_out,
                                    CONCAT(
                                        TRUNCATE(TIMESTAMPDIFF(hour, e.do_time, g.do_time) / 24, 0), ' Hari, ', 
                                        TIMESTAMPDIFF(hour, e.do_time, g.do_time) % 24, ' Jam'
                                    ) AS timer,
                                    f.Warehouse_Name AS nama_oven
                                FROM ttrx_dtl_lpb_receive a
                                JOIN tmst_item b ON a.sysid_material = b.SysId
                                JOIN ttrx_hdr_lpb_receive c ON a.lpb_hdr = c.lpb
                                JOIN tmst_account d ON c.id_supplier = d.SysId 
                                JOIN thst_in_to_oven e ON a.no_lot = e.lot
                                JOIN tmst_warehouse f ON e.placement = f.Warehouse_ID
                                JOIN thst_out_of_oven g ON a.no_lot = g.lot
                                JOIN  $this->qview_dtl_size_item_lpb AS size ON a.sysid = size.Id_Lot
                                WHERE c.status_lpb = 'SELESAI'
                                    AND a.sysid_material = $sysid_material
                                    AND size.Id_Size_Item = $Id_Size_Item
                                    $sql_oven
                                    $sql_material
                                    AND DATE_FORMAT(g.do_time, '%Y-%m-%d') >= '$from'
                                    AND DATE_FORMAT(g.do_time, '%Y-%m-%d') <= '$to'
                                GROUP BY 
                                    a.no_lot, size.Id_Size_Item 
                                ORDER BY 
                                    a.no_lot, size.Item_Length");
        $this->load->view("general-modal/m_lot_hst_inout_oven_desc", $this->data);
    }
}
