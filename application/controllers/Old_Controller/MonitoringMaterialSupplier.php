<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MonitoringMaterialSupplier extends CI_Controller
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
        $this->data['page_title'] = "Monitoring Material Per Supplier";
        $this->data['page_content'] = "Monitoring/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/DataBaseLot-script/rekap_supplier.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function Rekap_Material_Supplier()
    {
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
        $supplier = $this->input->get('supplier');

        // var_dump($supplier);
        // die;

        $sql = "SELECT a.lpb_hdr, a.no_lot, d.kode, a.harga_per_pcs, a.qty, c.nama, b.grader, b.penilaian, b.tgl_finish_sortir, a.into_oven, a.qty * ((d.tebal * d.lebar * d.panjang) / 1000000) as kubikasi, e.status_kayu
        FROM ttrx_dtl_lpb_receive a
        join ttrx_hdr_lpb_receive b on a.lpb_hdr = b.lpb
        left join tmst_supplier_material c on b.id_supplier = c.sysid
        left join tmst_material_kayu d on a.sysid_material = d.sysid
        left join tmst_status_lot e on a.into_oven = e.kode
        where b.status_lpb = 'SELESAI'
        AND DATE_FORMAT(b.selesai_at, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(b.selesai_at, '%Y-%m-%d') <= '$to'
        AND b.id_supplier = '$supplier'";

        if (!empty($requestData['search']['value'])) {
            $sql .= " AND (a.lpb_hdr LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.nama LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.grader LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR d.kode LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.penilaian LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.tgl_finish_sortir LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR e.status_kayu LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR a.no_lot LIKE '%" . $requestData['search']['value'] . "%')";
        }
        $sql .= " GROUP BY a.sysid ,a.no_lot ";
        $totalData = $this->db->query($sql)->num_rows();
        //----------------------------------------------------------------------------------
        $sql .= " ORDER BY b.sysid asc, a.flag asc  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
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
            $nestedData['penilaian'] = $row["penilaian"];
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

    public function Summary_Material_supplier()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $supplier = $this->input->get('supplier');

        $sql = "SELECT SUM(a.qty * ((d.tebal * d.lebar * d.panjang) / 1000000)) as kubikasi, SUM(a.qty * a.harga_per_pcs) as amount
        FROM ttrx_dtl_lpb_receive a
        join ttrx_hdr_lpb_receive b on a.lpb_hdr = b.lpb
        left join tmst_material_kayu d on a.sysid_material = d.sysid
        where b.status_lpb = 'SELESAI'
        AND DATE_FORMAT(b.selesai_at, '%Y-%m-%d') >= '$from'
        AND DATE_FORMAT(b.selesai_at, '%Y-%m-%d') <= '$to'
        AND b.id_supplier = '$supplier' LIMIT 1";

        $result = $this->db->query($sql)->row();

        if (empty($result)) {
            return $this->help->Fn_resulting_response([
                'code' => 500,
                'msg' => "data tidak tersedia !"
            ]);
        } else {
            return $this->help->Fn_resulting_response([
                'code' => 200,
                'kubikasi' => floatval($result->kubikasi),
                'rupiah' => floatval($result->amount)
            ]);
        }
    }
}
