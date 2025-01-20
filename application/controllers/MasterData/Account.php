<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends CI_Controller
{
    public $layout = 'layout';

    protected $Date;
    protected $DateTime;
    protected $Tmst_account = 'tmst_account';
    protected $Tmst_account_address = 'tmst_account_address';
    protected $Tmst_account_contact = 'tmst_account_contact';
    protected $Tmst_currency = 'tmst_currency';
    protected $LengthCounterAccount = 5;
    protected $tmst_country = 'tmst_country';
    protected $tmst_account_title = 'tmst_account_title';

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->Date = date('Y-m-d');
        $this->DateTime = date('Y-m-d H:i:s');
        $this->load->model('m_helper', 'help');
        $this->load->model('m_DataTable', 'M_Datatables');
    }

    public function customer()
    {
        $this->data['IdentityPattern'] = "CS";
        $this->data['page_title'] = "List of Customer";
        $this->data['page_content'] = "Master/Account/account";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Account/account.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function vendor()
    {
        $this->data['IdentityPattern'] = "VP";
        $this->data['page_title'] = "List of Vendor";
        $this->data['page_content'] = "Master/Account/account";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Account/account.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function approval($IdentityPattern)
    {
        $this->data['IdentityPattern'] = $IdentityPattern;
        if ($IdentityPattern == 'CS') {
            $this->data['page_title'] = "Approval New Customer";
            $this->data['account'] = "Customer";
        } else {
            $this->data['page_title'] = "Approval New Vendor";
            $this->data['account'] = "Vendor";
        }
        $this->data['page_content'] = "Master/Account/approval";
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Account/approval.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }



    public function add($IdentityPattern)
    {
        if ($IdentityPattern == 'CS') {
            $this->data['page_title'] = "Add New Customer";
            $this->data['account'] = "Customer";
        } else {
            $this->data['page_title'] = "Add New Vendor";
            $this->data['account'] = "Vendor";
        }
        $this->data['Currencys'] = $this->db->get($this->Tmst_currency);
        $this->data['IdentityPattern'] = $IdentityPattern;
        $this->data['page_content'] = "Master/Account/add";
        $this->data['Countries'] = $this->db->get($this->tmst_country)->result();
        $this->data['Titles'] = $this->db->get($this->tmst_account_title)->result();
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Account/add.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function edit($SysId)
    {
        $this->data['RowAccount'] = $this->db->get_where($this->Tmst_account, ['SysId' => $SysId])->row();
        $IdentityPattern = $this->data['RowAccount']->Category_ID;
        if ($IdentityPattern == 'CS') {
            $this->data['page_title'] = "Edit Data Customer : " . $this->data['RowAccount']->Account_Name;
            $this->data['account'] = "Customer";
        } else {
            $this->data['page_title'] = "Edit Data Vendor : " . $this->data['RowAccount']->Account_Name;
            $this->data['account'] = "Vendor";
        }
        $this->data['Currencys'] = $this->db->get($this->Tmst_currency);
        $this->data['IdentityPattern'] = $IdentityPattern;
        $this->data['page_content'] = "Master/Account/edit";
        $this->data['Countries'] = $this->db->get($this->tmst_country)->result();
        $this->data['Titles'] = $this->db->get($this->tmst_account_title)->result();
        $this->data['script_page'] =  '<script src="' . base_url() . 'assets/master-script/Account/edit.js?v=' . time() . '"></script>';

        $this->load->view($this->layout, $this->data);
    }

    public function post()
    {
        $IdentityPattern = $this->input->post('IdentityPattern');
        $AccountCode = $this->help->Gnrt_Identity_Counter_Only($IdentityPattern, $this->LengthCounterAccount);
        $Area = ($this->input->post('islocal') == '1' ? 'Domestic' : 'OverSeas');

        $this->db->trans_start();
        $this->db->insert($this->Tmst_account, [
            'Account_Code' => $AccountCode,
            'AccountTitle_Code' => $this->input->post('AccountTitle_Code'),
            'Account_Name' => $this->input->post('Account_Name'),
            'Account_Address' => $this->input->post('Account_Address'),
            'Account_City_Id' => $this->input->post('Account_City_Id'),
            'Account_State_Id' => $this->input->post('Account_State_Id'),
            'Account_Country_Id' => $this->input->post('Account_Country_Id'),
            'Account_ZipCode' => $this->input->post('Account_ZipCode'),
            'Account_Phone1' => $this->input->post('Account_Phone1'),
            'Account_Phone2' => $this->input->post('Account_Phone2'),
            'Account_Fax' => $this->input->post('Account_Fax'),
            'Account_EmailAddress' => $this->input->post('Account_EmailAddress'),
            'Account_Description' => $this->input->post('Account_Description'),
            'Account_IndustryId' => $this->input->post('Account_IndustryId'),
            'TaxFileNumber' => $this->input->post('TaxFileNumber'),
            'Category_ID' => $this->input->post('IdentityPattern'),
            'Account_CurrencyId' => $this->input->post('Account_CurrencyId'),
            'Account_PaymentId' => $this->input->post('Account_PaymentId'),
            'shipment_address' => $this->input->post('shipment_address'),
            'PaymentTerms' => $this->input->post('PaymentTerms'),
            'Website' => $this->input->post('Website'),
            'nppkp' => $this->input->post('nppkp'),
            'PaymentTerm' => $this->input->post('PaymentTerm'),
            'PaymentTermTypeDoc' => $this->input->post('PaymentTermTypeDoc'),
            'PaymentCondition' => $this->input->post('PaymentCondition'),
            'BankCode' => $this->input->post('BankCode'),
            'BankName' => $this->input->post('BankName'),
            'BankBranch' => $this->input->post('BankBranch'),
            'BankAccount' => $this->input->post('BankAccount'),
            'BankAccName' => $this->input->post('BankAccName'),
            'BankCurrencyID' => $this->input->post('BankCurrencyID'),
            'islocal' => $this->input->post('islocal'),
            'CREATEDBY' => $this->session->userdata('impsys_nik'),
            'CREATEDDATE' => $this->DateTime,
            'LASTUPDATEBY' => null,
            'LASTUPDATE' => null,
        ]);

        $this->db->insert($this->Tmst_account_address, [
            'Account_Code' => $AccountCode,
            'Address' => $this->input->post('Account_Address'),
            'Area' => $Area,
            'Description' => '',
            'CREATEDBY' => $this->session->userdata('impsys_nik'),
            'CREATEDAT' => $this->DateTime,
        ]);

        $this->db->insert($this->Tmst_account_contact, [
            'Account_Code' => $AccountCode,
            'Contact_Name' => $this->input->post('Account_Name'),
            'Telephone' => $this->input->post('Account_Phone1'),
            'Country' => $this->input->post('Account_Country_Id'),
            'Created_at' => $this->session->userdata('impsys_nik'),
            'Created_by' => $this->DateTime,
        ]);

        $this->db->trans_complete();

        if ($this->input->post('IdentityPattern') == 'CS') {
            $page = 'customer';
        } else {
            $page = 'vendor';
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Gagal Menyimpan data !"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "page" => $page,
                "msg" => "Data berhasil tersimpan!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }
    public function update()
    {

        $this->db->trans_start();
        $this->db->where('SysId', $this->input->post('SysId'))->update($this->Tmst_account, [
            'Account_Address' => $this->input->post('Account_Address'),
            'Account_City_Id' => $this->input->post('Account_City_Id'),
            'Account_State_Id' => $this->input->post('Account_State_Id'),
            'Account_Country_Id' => $this->input->post('Account_Country_Id'),
            'Account_ZipCode' => $this->input->post('Account_ZipCode'),
            'Account_Phone1' => $this->input->post('Account_Phone1'),
            'Account_Phone2' => $this->input->post('Account_Phone2'),
            'Account_Fax' => $this->input->post('Account_Fax'),
            'Account_EmailAddress' => $this->input->post('Account_EmailAddress'),
            'Account_Description' => $this->input->post('Account_Description'),
            'Account_IndustryId' => $this->input->post('Account_IndustryId'),
            'TaxFileNumber' => $this->input->post('TaxFileNumber'),
            'Category_ID' => $this->input->post('IdentityPattern'),
            'Account_CurrencyId' => $this->input->post('Account_CurrencyId'),
            'Account_PaymentId' => $this->input->post('Account_PaymentId'),
            'shipment_address' => $this->input->post('shipment_address'),
            'PaymentTerms' => $this->input->post('PaymentTerms'),
            'Website' => $this->input->post('Website'),
            'nppkp' => $this->input->post('nppkp'),
            'PaymentTerm' => $this->input->post('PaymentTerm'),
            'PaymentTermTypeDoc' => $this->input->post('PaymentTermTypeDoc'),
            'PaymentCondition' => $this->input->post('PaymentCondition'),
            'BankCode' => $this->input->post('BankCode'),
            'BankName' => $this->input->post('BankName'),
            'BankBranch' => $this->input->post('BankBranch'),
            'BankAccount' => $this->input->post('BankAccount'),
            'BankAccName' => $this->input->post('BankAccName'),
            'BankCurrencyID' => $this->input->post('BankCurrencyID'),
            'islocal' => $this->input->post('islocal'),
            'LASTUPDATEBY' => $this->session->userdata('impsys_nik'),
            'LASTUPDATE' => $this->DateTime,
        ]);

        if ($this->input->post('IdentityPattern') == 'CS') {
            $page = 'customer';
        } else {
            $page = 'vendor';
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Gagal Menyimpan data !"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "page" => $page,
                "msg" => "Data berhasil tersimpan!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function post_address()
    {
        $this->db->trans_start();
        $this->db->insert($this->Tmst_account_address, [
            'Account_Code' => $this->input->post('Account_Code_Addr'),
            'Address' => $this->input->post('Address'),
            'Area' => $this->input->post('Area'),
            'Description' => $this->input->post('Address'),
            'Is_Active' => 1,
            'CREATEDBY' => $this->session->userdata('impsys_nik'),
            'CREATEDAT' => $this->DateTime

        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Gagal Menyimpan data !"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Data berhasil tersimpan!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function post_contact()
    {
        $this->db->trans_start();
        $this->db->insert($this->Tmst_account_contact, [
            'Account_Code' => $this->input->post('Account_Code_Contact'),
            'Contact_Name' => $this->input->post('Contact_Name'),
            'Contact_Initial_Name' => $this->input->post('Contact_Initial_Name'),
            'Gender' => $this->input->post('Gender'),
            'Job_title' => $this->input->post('Job_title'),
            'Email_Address' => $this->input->post('Email_Address'),
            'Telephone' => $this->input->post('Telephone'),
            'Country' => $this->input->post('Country_contact'),
            'State' => $this->input->post('State'),
            'City' => $this->input->post('City'),
            'Home_Address' => $this->input->post(''),
            'Mobile_Phone' => $this->input->post('Mobile_Phone'),
            'Fax' => $this->input->post('Fax'),
            'Note' => $this->input->post('Note'),
            'Created_at' => $this->session->userdata('impsys_nik'),
            'Created_by' => $this->DateTime
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response = [
                "code" => 505,
                "msg" => "Gagal Menyimpan data !"
            ];
        } else {
            $this->db->trans_commit();
            $response = [
                "code" => 200,
                "msg" => "Data berhasil tersimpan!"
            ];
        }
        return $this->help->Fn_resulting_response($response);
    }

    public function verify()
    {
        $sysid = $this->input->post('sysid');
        $is_verified = $this->input->post('is_verified');


        if ($is_verified == 2) {
            $this->db->where('SysId', $sysid);
            $this->db->update($this->Tmst_account, [
                'Is_Active' => 0,
                'Is_Verified' => 2
            ]);

            $response = [
                "code" => 200,
                "msg" => "Data telah di riject !"
            ];
        } else {
            $this->db->where('SysId', $sysid);
            $this->db->update($this->Tmst_account, [
                'Is_Active' => 1,
                'Is_Verified' => 1
            ]);

            $response = [
                "code" => 200,
                "msg" => "Data berhasil di verifikasi !"
            ];
        }

        return $this->help->Fn_resulting_response($response);
    }
    // --- DATATABLE SECTION

    public function DT_list_account()
    {
        $IdentityPattern = $this->input->post('IdentityPattern');
        $tables = $this->Tmst_account;
        $where  = array('Category_ID' => $IdentityPattern);
        $search = array('Account_Code', 'Account_Name', 'Account_Address', 'Account_Phone1', 'Account_EmailAddress', 'TaxFileNumber');

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';

        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables, $search, $where, $isWhere);
    }

    public function DT_list_account_need_verification()
    {
        $IdentityPattern = $this->input->post('IdentityPattern');
        $tables = $this->Tmst_account;
        $where  = array('Category_ID' => $IdentityPattern, 'Is_Verified' => 0);
        $search = array('Account_Code', 'Account_Name', 'Account_Address', 'Account_Phone1', 'Account_EmailAddress', 'TaxFileNumber');

        // jika memakai IS NULL pada where sql
        // $isWhere = 'artikel.deleted_at IS NULL';

        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables, $search, $where, $isWhere);
    }

    public function DT_shipment_address()
    {
        $Account_Code = $this->input->post('Account_Code');
        $tables = $this->Tmst_account_address;
        $where  = array('Account_Code' => $Account_Code);
        $search = array(
            'Address',
            'Area',
            'Description'
        );

        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables, $search, $where, $isWhere);
    }

    public function DT_account_contact()
    {
        $SysId = $this->input->post('SysId');
        $tables = $this->Tmst_account_contact;
        $where  = array('Account_Code' => $SysId);
        $search = array(
            'Contact_Name',
            'Contact_Initial_Name',
            'Gender',
            'Job_title',
            'Email_Address',
            'Telephone',
            'Country',
            'State',
            'City',
            'Home_Address',
            'Mobile_Phone',
            'Fax',
            'Note'
        );

        $isWhere = null;

        header('Content-Type: application/json');
        echo $this->M_Datatables->get_tables_where($tables, $search, $where, $isWhere);
    }
}
