<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Access extends CI_Controller
{
    public $layout = 'layout';
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('m_helper', 'help');
    }

    public function index()
    {
        $this->data['page_title'] = "Control Access";
        $this->data['page_content'] = "Access/index";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/Access/index.js"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function toggle_access()
    {
        $nik = $this->input->post("nik_input");
        $row_nik = $this->input->post("row_nik");
        $row_sysid_group = $this->input->post("row_sysid_group");
        $row_sysid_parent = $this->input->post("row_sysid_parent");
        $row_sysid_child = $this->input->post("row_sysid_child") ? $this->input->post("row_sysid_child") : null;

        if ($row_nik == '') {
            $this->db->insert('ttrx_authority_access_menu', [
                'sysid_group'   => $row_sysid_group,
                'sysid_parent'  => $row_sysid_parent,
                'sysid_child'   => $row_sysid_child,
                'nik'           => $nik
            ]);
            $response = [
                "code" => 200,
                "msg" => "Akses telah diberikan !"
            ];
        } else {
            $this->db->delete('ttrx_authority_access_menu', [
                'sysid_group'   => $row_sysid_group,
                'sysid_parent'  => $row_sysid_parent,
                'sysid_child'   => $row_sysid_child,
                'nik'           => $nik
            ]);
            $response = [
                "code" => 200,
                "msg" => "Akses telah dihapus !"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function Datatable_Access_User()
    {
        $requestData = $_REQUEST;
        $nik = $this->input->post('nik');
        $columns = array(
            0 => 'a.sysid_group',
            1 => 'a.label_group',
            2 => 'b.sysid_parent',
            3 => 'b.label_parent',
            4 => 'c.sysid_child',
            5 => 'c.label_child',
            6 => 'x.sysid',
            7 => 'x.nik',
        );
        $sql = "SELECT DISTINCT a.sysid_group ,a.label_group ,b.sysid_parent ,b.label_parent , c.sysid_child ,c.label_child, x.sysid ,x.nik
        from tmst_grop_menu a
        left join tmst_parent_menu b on a.sysid_group = b.pk_group 
        left join tmst_child_menu c on b.sysid_parent = c.pk_parent
        left join ttrx_authority_access_menu x on x.sysid_group = a.sysid_group 
                                            and b.sysid_parent = x.sysid_parent 
                                            and (c.sysid_child = x.sysid_child or c.sysid_child is null) 
                                            and x.nik = '$nik'";
        // JIKA ADA PARAM DARI SEARCH
        if (!empty($requestData['search']['value'])) {
            $sql .= " WHERE (a.label_group LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR b.label_parent LIKE '%" . $requestData['search']['value'] . "%' ";
            $sql .= " OR c.label_child LIKE '%" . $requestData['search']['value'] . "%')";
        }
        //----------------------------------------------------------------------------------
        $sql .= " GROUP BY a.sysid_group , b.sysid_parent ,c.sysid_child ,x.sysid ,x.nik";
        $totalData = $this->db->query($sql)->num_rows();
        $totalFiltered = $this->db->query($sql)->num_rows();
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
        $query = $this->db->query($sql);
        $data = array();
        foreach ($query->result_array() as $row) {
            $nestedData = array();
            $nestedData['sysid_group'] = $row["sysid_group"];
            $nestedData['label_group'] = $row["label_group"];
            $nestedData['sysid_parent'] = $row["sysid_parent"];
            $nestedData['label_parent'] = $row["label_parent"];
            $nestedData['sysid_child'] = $row["sysid_child"];
            $nestedData['label_child'] = $row["label_child"];
            $nestedData['sysid'] = $row["sysid"];
            $nestedData['nik'] = $row["nik"];
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
