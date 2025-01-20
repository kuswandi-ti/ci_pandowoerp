<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EntryKd extends CI_Controller
{
    public $layout = 'layout';
    protected $Tbl_hdr_lpb      = 'ttrx_hdr_lpb_receive';
    protected $Tbl_dtl_lpb      = 'ttrx_dtl_lpb_receive';
    protected $Hst_oven         = 'thst_in_to_oven';
    protected $Tmst_account     = 'tmst_account';
    protected $Tmst_item        = 'tmst_item';
    protected $Tmst_warehouse   = 'tmst_warehouse';
    protected $ParamKD          = ['Is_Kiln' => 1, 'Is_Active' => 1, 'Warehouse_ID <>' => 23];
    protected $qview_dtl_size_item_lpb = 'qview_dtl_size_item_lpb';
    protected $tmst_size_item_grid = 'tmst_size_item_grid';
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
        $this->data['page_title'] = "Scan Barcode : Entry KD";
        $this->data['page_content'] = "TrxWh/EntryKd/entry_scan";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/EntryKd/entry_scan.js?v=' . time() . '"></script>';
        $this->data['ovens'] = $this->db->get_where($this->Tmst_warehouse, $this->ParamKD)->result();

        $this->load->view($this->layout, $this->data);
    }

    public function history()
    {
        $this->data['page_title'] = "History Entry Kiln";
        $this->data['page_content'] = "TrxWh/EntryKd/history";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/TrxWh/EntryKd/history.js?v=' . time() . '"></script>';

        $this->data['items'] = $this->db->get_where('qmst_item', ['Is_Active' => 1, 'Is_Grid_Item' => 1])->result();
        $this->data['kilns'] = $this->db->get_where($this->Tmst_warehouse, $this->ParamKD)->result();

        $this->load->view($this->layout, $this->data);
    }

    public function insert_into_oven()
    {
        $barcode = $this->input->post('barcode');
        $oven = $this->input->post('oven');
        $remark = $this->input->post('remark');
        $RowLot = $this->db->get_where('ttrx_dtl_lpb_receive', ['no_lot' => $barcode]);
        $RowHst = $this->db->get_where('thst_in_to_oven', ['lot' => $barcode]);

        $DataDtl = $RowLot->row();
        $RowHdr = $this->db->get_where('ttrx_hdr_lpb_receive', ['lpb' => $DataDtl->lpb_hdr])->row();

        if ($RowHdr->status_lpb != 'SELESAI') {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Proses Grid belum selesai, kayu masih dalam proses grid !'
            ]);
        }

        if ($RowLot->num_rows() == 0) {
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => 'Barcode tidak terdaftar dalam system!'
            ]);
        }
        if ($RowHst->num_rows() > 0) {
            $DataHst = $RowHst->row();
            $ActionTime = date('d F Y H:i', strtotime($DataHst->do_time));
            return $this->help->Fn_resulting_response([
                'code' => 505,
                'msg' => "Barcode scan telah dinyatakan masuk kd pada $ActionTime !"
            ]);
        }

        $this->db->trans_start();
        $this->db->where('no_lot', $barcode)->update($this->Tbl_dtl_lpb, [
            'into_oven' => 1,
            'placement' => $oven
        ]);
        $this->db->insert('thst_in_to_oven', [
            "lot" => $barcode,
            "placement" => $oven,
            "do_by" => $this->session->userdata('impsys_initial'),
            "do_time" => $this->DateTime,
            'remark_into_oven' => $remark,
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = ['code' => 505, 'msg' => 'lot gagal dinyatakan masuk kd!'];
        } else {
            $this->db->trans_commit();
            $response = ['code' => 200];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function preview_detail_lot()
    {
        $barcode = $this->input->get('barcode');
        $response = $this->db->query("SELECT a.sysid, wh.Warehouse_Name, d.Account_Name as nama, a.lpb_hdr, a.no_lot, c.Item_Code as kode,c.Item_Name, SUM(size.Qty_Usable) as qty, b.grader, b.tgl_kirim,
        b.tgl_finish_sortir, a.into_oven, e.status_kayu, COALESCE(SUM(size.Cubication * size.Qty),0) as kubikasi
        from $this->Tbl_dtl_lpb a
        join $this->Tbl_hdr_lpb b on a.lpb_hdr = b.lpb
        join $this->Tmst_item c on a.sysid_material = c.SysId
        join $this->Tmst_account d on b.id_supplier = d.SysId
        left join $this->Tmst_warehouse wh on a.placement = wh.Warehouse_ID
        join tmst_status_lot e on a.into_oven = e.kode
        join $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot 
        where a.no_lot = '$barcode'
        limit 1")->row();

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
                "kubikasi" => floatval($response->kubikasi),
                "grader" => $response->grader,
                "tgl_kirim" => $response->tgl_kirim,
                "Warehouse_Name" => $response->Warehouse_Name,
                "status" => '<button class="btn btn-info btn-flat"><i class="fas fa-map-marker-alt blink_me"></i> ' . $response->status_kayu . '</button>'
            ]);
        }
    }

    public function M_Hst_Lot_By_Material()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $oven = $this->input->get('oven');

        $sql_oven = "";
        if (!empty($oven)) {
            $sql_oven = " AND e.placement = $oven ";
        }

        $sysid_material = $this->input->get('sysid_material'); // from datatable
        $Id_Size_Item = $this->input->get('sysid_size'); // from datatable
        $status = $this->input->get('status');
        $row_material = $this->db->get_where('tmst_item', ['SysId' => $sysid_material])->row();

        $this->data['title_modal'] = $this->input->get('title');
        $this->data['Size'] = $this->db->get_where($this->tmst_size_item_grid, ['SysId' => $Id_Size_Item])->row();
        $this->data['row_material'] = $row_material;

        $this->data['from'] = $from;
        $this->data['to'] = $to;

        $this->data['list_lot'] =  $this->db->query("SELECT 
                                    a.no_lot, 
                                    c.grader, 
                                    SUM(size.Qty_Usable) as qty, 
                                    c.grader, 
                                    c.tgl_kirim, 
                                    c.tgl_finish_sortir, 
                                    (SUM(size.Qty_Usable) * size.Cubication) as kubikasi,
                                    d.Account_Name AS nama, 
                                    c.lpb,
                                    DATE_FORMAT(e.do_time, '%Y-%m-%d %H:%i') AS do_time_in,
                                    DATE_FORMAT(g.do_time, '%Y-%m-%d %H:%i') AS do_time_out,
                                    CASE 
                                        WHEN g.do_time IS NULL THEN 
                                            CONCAT(
                                                TRUNCATE(TIMESTAMPDIFF(hour, e.do_time, NOW()) / 24, 0), ' Hari, ', 
                                                TIMESTAMPDIFF(hour, e.do_time, NOW()) % 24, ' Jam'
                                            )
                                        ELSE 
                                            CONCAT(
                                                TRUNCATE(TIMESTAMPDIFF(hour, e.do_time, g.do_time) / 24, 0), ' Hari, ', 
                                                TIMESTAMPDIFF(hour, e.do_time, g.do_time) % 24, ' Jam'
                                            )
                                    END AS timer,
                                    f.Warehouse_Name AS nama_oven
                                FROM ttrx_dtl_lpb_receive a
                                JOIN tmst_item b ON a.sysid_material = b.SysId
                                JOIN ttrx_hdr_lpb_receive c ON a.lpb_hdr = c.lpb
                                JOIN tmst_account d ON c.id_supplier = d.SysId 
                                JOIN thst_in_to_oven e ON a.no_lot = e.lot
                                LEFT JOIN tmst_warehouse f ON e.placement = f.Warehouse_ID
                                LEFT JOIN thst_out_of_oven g ON a.no_lot = g.lot
                                JOIN  $this->qview_dtl_size_item_lpb AS size ON a.sysid = size.Id_Lot
                                WHERE 
                                    c.status_lpb = 'SELESAI'
                                    AND a.sysid_material = $sysid_material
                                    AND size.Id_Size_Item = $Id_Size_Item
                                    $sql_oven
                                    AND DATE_FORMAT(e.do_time, '%Y-%m-%d') >= '$from'
                                    AND DATE_FORMAT(e.do_time, '%Y-%m-%d') <= '$to'
                                GROUP BY 
                                    a.no_lot, size.Id_Size_Item 
                                ORDER BY 
                                    a.no_lot, size.Item_Length");
        $this->load->view("general-modal/m_lot_hst_inout_oven_desc", $this->data);
    }
    // ======================================= Datatable Section
    public function DataTable_HstOven_by_Lot()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $oven = $this->input->get('oven');
        $material = $this->input->get('material');

        $sql_oven = "";
        if (!empty($oven)) {
            $sql_oven = " AND e.placement = $oven ";
        }
        $sql_material = "";
        if (!empty($material)) {
            $sql_material = " AND a.sysid_material = $material ";
        }


        $requestData = $_REQUEST;
        $columns = array(
            0 => 'a.sysid',
            1 => 'a.no_lot',
            2 => 'b.Item_Code',
            3 => 'b.Item_Name',
            4 => 'd.Account_Name',
            5 => 'c.grader',
            6 => 'c.tgl_kirim',
            7 => 'SUM(size.Qty_Usable)',
            8 => 'SUM(size.Cubication * size.Qty_Usable)',
            9 => "CONCAT(FLOOR(HOUR(TIMEDIFF(e.do_time, NOW())) / 24), ' hari,', MOD(HOUR(TIMEDIFF(e.do_time, NOW())), 24), ' jam')",
            10 => 'f.Warehouse_Name',
            11 => 'e.do_time',
            12 => 'h.do_time',
            13 => 'g.status_kayu',
        );
        $order = $columns[$requestData['order']['0']['column']];
        $dir = $requestData['order']['0']['dir'];

        $sql = "SELECT a.sysid, 
        a.no_lot, 
        d.Account_Name AS nama, 
        b.Item_Name AS deskripsi, 
        b.Item_Code AS kode, 
        c.grader, 
        f.Warehouse_Name AS nama_oven,
        c.tgl_finish_sortir, 
        c.tgl_kirim, 
        SUM(size.Qty_Usable) as qty, 
        a.sysid_material, 
        g.status_kayu, 
        e.do_time AS time_in, 
        h.do_time AS time_out, 
        a.into_oven,
        CASE 
            WHEN h.do_time IS NULL THEN 
                CONCAT(
                    TRUNCATE(TIMESTAMPDIFF(hour, e.do_time, NOW()) / 24, 0), ' Hari, ', 
                    TIMESTAMPDIFF(hour, e.do_time, NOW()) % 24, ' Jam'
                )
            ELSE 
                CONCAT(
                    TRUNCATE(TIMESTAMPDIFF(hour, e.do_time, h.do_time) / 24, 0), ' Hari, ', 
                    TIMESTAMPDIFF(hour, e.do_time, h.do_time) % 24, ' Jam'
                )
        END AS timer,
        SUM(size.Cubication * size.Qty_Usable) AS kubikasi
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_item b ON a.sysid_material = b.SysId
        JOIN ttrx_hdr_lpb_receive c ON a.lpb_hdr = c.lpb
        JOIN tmst_account d ON c.id_supplier = d.SysId
        JOIN thst_in_to_oven e ON a.no_lot = e.lot
        left JOIN tmst_warehouse f ON e.placement = f.Warehouse_ID
        JOIN tmst_status_lot g ON a.into_oven = g.kode
        LEFT JOIN thst_out_of_oven h ON a.no_lot = h.lot
        JOIN $this->qview_dtl_size_item_lpb size on a.sysid = size.Id_Lot
        WHERE c.status_lpb = 'SELESAI'
        $sql_oven
        $sql_material
        AND DATE_FORMAT(e.do_time, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(e.do_time, '%Y-%m-%d') <= '$to'";


        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            // $sql .= " OR b.inisial_kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.Account_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.tgl_kirim LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR f.Warehouse_Name LIKE '%" . $requestData['search']['value'] . "%' ";
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
        if (!empty($oven)) {
            $sql_oven = " AND d.placement = $oven ";
        }
        $sql_material = "";
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

        $sql = "SELECT b.Item_Name as deskripsi,
        b.Item_Code as kode,
        size.Item_Height as tebal,
        size.Item_Width as lebar,
        size.Item_Length as panjang,
        size.Id_Size_Item,
        size.Size_Code,
        count(distinct a.no_lot) as row_lot,
        SUM(size.Qty_Usable) as t_qty,
        a.sysid_material, 
        (SUM(size.Qty_Usable) * size.Cubication) as kubikasi
        FROM ttrx_dtl_lpb_receive a
        JOIN tmst_item b ON a.sysid_material = b.SysId
        JOIN ttrx_hdr_lpb_receive c ON a.lpb_hdr = c.lpb
        JOIN thst_in_to_oven d ON a.no_lot = d.lot
        JOIN $this->qview_dtl_size_item_lpb as size on a.sysid = size.Id_Lot
        WHERE c.status_lpb  = 'SELESAI' 
        $sql_oven
        $sql_material
        AND DATE_FORMAT(d.do_time, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(d.do_time, '%Y-%m-%d') <= '$to'";

        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (b.Item_Name LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.Item_Code LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR size.Size_Code LIKE '%" . $requestData['search']['value'] . "%' ";
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
}
