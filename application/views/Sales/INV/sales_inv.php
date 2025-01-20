<style>
    .summary-table {
        width: 100%;
        /* border-collapse: collapse; */
    }

    .summary-table td {
        vertical-align: middle;
        padding-top: 10px;
        /* border: 1px solid #ccc; */
    }

    .fh {
        font-size: 1rem !important;
    }

    .fw-bold {
        font-weight: 700 !important;
    }

    .fw-semibold {
        font-weight: 600 !important;
    }

    .hidden-element {
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.5s, visibility 0s 0.5s;
    }

    /*  */
    .badge-large {
        font-size: 0.8rem;
        /* Ubah ukuran font sesuai keinginan Anda */
        padding: 0.5em 1em;
        /* Ubah padding sesuai keinginan Anda */
    }

    .input-group-flex {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .input-group-flex .badge {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: calc(25% - 1rem);
        /* 3 kolom pada ukuran layar normal */
        padding: 0.5rem 1rem;
        /* font-size: 0.875rem; */
        cursor: pointer;
    }


    .badge .badge-number {
        position: absolute;
        top: -0.5rem;
        right: -0.5rem;
        background-color: #fff;
        /* Background color of the icon */
        color: #495057;
        /* Text color of the icon */
        border-radius: 50%;
        width: 1.3rem;
        height: 1.3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        border: 2px solid #ced4da;
        /* Red border color */
    }

    .bg-item-close {
        background-color: #FFF59D;
    }

    .vertical-align-middle {
        vertical-align: middle !important;
    }

    .fh {
        font-size: 1rem !important;
    }

    .bordered-container {
        position: relative;
    }

    .bordered-container h5 {
        position: absolute;
        top: -15px;
        left: 15px;
        background: white;
        padding: 0 5px;
        z-index: 1;
        /* Ensure text is above the border */
    }

    .blur-effect {
        filter: blur(5px);
        pointer-events: none;
    }

    @keyframes slideInDown {
        0% {
            opacity: 0;
            transform: translateY(-100%);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideOutUp {
        0% {
            opacity: 1;
            transform: translateY(0);
        }

        100% {
            opacity: 0;
            transform: translateY(-100%);
        }
    }

    .animate__slideInDown {
        animation: slideInDown 0.7s ease forwards;
    }

    .animate__slideOutUp {
        animation: slideOutUp 0.7s ease forwards;
    }

    #section-before-chose-customer {
        display: block;
        position: relative;
    }

    #section-before-chose-si {
        display: block;
        position: relative;
    }

    #section-after-chose-si {
        display: none;
        position: relative;
    }
</style>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline list-data">
                <div class="card-header">
                    <h3 class="card-title"><?= $page_title ?></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="DataTable" class="table table-sm table-bordered" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr class="text-white">
                                    <th class="text-center vertical-align-middle" style="width: 20%;">Nomor Invoice</th>
                                    <th class="text-center vertical-align-middle" style="width: 20%;">Tanggal Invoice</th>
                                    <th class="text-center vertical-align-middle" style="width: 20%;">Nomor SJ</th>
                                    <th class="text-center vertical-align-middle" style="width: 20%;">Tanggal Jatuh
                                        Tempo</th>
                                    <th class="text-center vertical-align-middle" style="width: 15%;">Nama Customer</th>
                                    <th class="text-center vertical-align-middle" style="width: 10%;">Status Pembayaran
                                    </th>
                                    <th class="text-center vertical-align-middle" style="width: 7.5%;">Approval</th>
                                    <th class="text-center vertical-align-middle" style="width: 7.5%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- hi dude i dude some magic here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card bd-callout shadow add-data">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <!-- HERE -->
                    <!-- Kumpulan inputan yang di hidden -->
                    <input id="SI-Number" name="SI_Number" type="hidden">
                    <input id="HDR-SO-Number" name="HDR_SO_Number" type="hidden">
                    <!--  -->
                    <input id="customer-id" name="customer_id" type="hidden">
                    <!-- <input id="customer-code" name="customer_code" type="hidden">
                    <input id="alamat-customer-id" name="alamat_customer_id" type="hidden">
                    <input id="account-npwp" name="account_npwp" type="hidden"> -->
                    <input type="hidden" id="state" name="state" value="">
                    <!-- <input type="hidden" id="area" name="area" value=""> -->
                    <!-- EDIT -->
                    <input id="invoice-id-edit" name="invoice_id_edit" value="" type="hidden">
                    <input id="invoice-number-edit" name="invoice_number_edit" value="" type="hidden">
                    <!-- <input id="so-rev" name="so_rev" value="" type="hidden"> -->
                    <!-- <input id="tax-amount1" name="tax_amount_1" value="" type="text">
                    <input id="tax-amount2" name="tax_amount_2" value="" type="text"> -->
                    <!-- END - Kumpulan inputan yang di hidden -->
                    <div class="card-header">
                        <h2 class="card-title mt-2"><span id="action-tittle"></span> <?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--  -->
                        <div id="section-before-chose-customer">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="nomer-shipping" style="font-weight: 500;">Nomor Invoice:</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" id="nomer-shipping" name="nomer_shipping" type="text" readonly value="" placeholder="INV<?= date('Ymd'); ?>-xxxxxxx">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="select-customer" style="font-weight: 500;">Nama Customer:</label>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control form-control-sm select2-no-ajx" id="select-customer" name="select_customer">
                                            <option value="">---Pilih Nama Costumer--</option>
                                            <?php foreach ($Account_CS->result() as $key) : ?>
                                                <option value="<?= $key->SysId; ?>"> <?= $key->Account_Name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                        </div>
                        <!--  -->
                        <div id="section-before-chose-si">
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="alamat-customer" style="font-weight: 500;">Alamat Customer:</label>
                                    <div class="input-group input-group-sm">
                                        <textarea class="form-control form-control-sm" id="alamat-customer" name="alamat_customer" placeholder="Alamat customer..." rows="3" readonly data-sysid=""></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="select-customer" style="font-weight: 500;">Document Shipping:</label>
                                    <!--  -->
                                    <!--  -->
                                    <table id="shipping-table" class="table table-bordered table-sm" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;" class="text-center border-bottom-0 vertical-align-middle">#</th>
                                                <th class="text-center border-bottom-0 vertical-align-middle">Nomor Shipping
                                                </th>
                                                <th class="text-center border-bottom-0 vertical-align-middle">Keterangan
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Hide dari sini -->
                        <div id="section-after-chose-si">
                            <!--  -->
                            <div class="row">
                                <div class="col-12 px-4">
                                    <hr>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="NotifeParty" style="font-weight: 500;">Pihak Penerima Pemberitahuan</label>
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm" id="NotifeParty" name="NotifeParty" type="text" placeholder="Masukan Pihak Penerima Pemberitahuan" value="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="NotifePartyAddress" style="font-weight: 500;">Alamat Pihak Penerima
                                        Pemberitahuan</label>
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm" id="NotifePartyAddress" name="NotifePartyAddress" type="text" placeholder="Masukan Alamat Pihak Penerima Pemberitahuan" value="">
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="port-of-loading" style="font-weight: 500;">Tempat Muat (Port of
                                        Loading)</label>
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm text-capitalize" id="port-of-loading" name="port_of_loading" type="text" placeholder="Masukan Port of Loading" value="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="place-of-delivery" style="font-weight: 500;">Tempat Pengiriman (Place of
                                        Delivery)</label>
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm text-capitalize" id="place-of-delivery" name="place_of_delivery" type="text" placeholder="Masukan Place of Delivery" value="">
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-12 px-4">
                                    <hr>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="invoice-date" style="font-weight: 500;">Tanggal Invoice</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" id="invoice-date" name="invoice_date" type="date" placeholder="Masukan Tanggal Faktur" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="due-date" style="font-weight: 500;">Tanggal Jatuh Tempo</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" id="due-date" name="due_date" type="date" placeholder="Masukan Tanggal Jatuh Tempo" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="tax-date" style="font-weight: 500;">Tanggal Pajak</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" id="tax-date" name="tax_date" type="date" placeholder="Masukan Tanggal Pajak" value="">
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-12 px-4">
                                    <hr>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="taxdocnumpph" style="font-weight: 500;">Nomor Dokumen Pajak PPh</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" id="taxdocnumpph" name="tax_docnum_pph" type="text" placeholder="Masukan Nomor Dokumen Pajak PPh" value="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="notes" style="font-weight: 500;">Catatan</label>
                                    <div class="input-group input-group-sm">
                                        <textarea class="form-control form-control-sm" id="notes" name="notes" placeholder="Masukan Catatan"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="invoice-print-date" style="font-weight: 500;">Tanggal Cetak Invoice</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" id="invoice-print-date" name="invoice_print_date" type="date" placeholder="Masukan Tanggal Cetak Faktur" value="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="price-type" style="font-weight: 500;">Tipe Harga</label>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control form-control-sm" id="price-type" name="price_type">
                                            <option value="" selected>Pilih Tipe Harga</option>
                                            <option value="CIF">Biaya, Asuransi, dan Pengiriman (CIF)</option>
                                            <option value="FOB">Bebas (FOB)</option>
                                            <option value="CFR">Biaya dan Pengiriman (CFR)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-12 px-4">
                                    <hr>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="LCNo" style="font-weight: 500;">Nomor Letter of Credit (LC)</label>
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm" id="LCNo" name="LC_No" type="text" placeholder="Masukan Nomor LC" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="LCDate" style="font-weight: 500;">Tanggal Letter of Credit (LC)</label>
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm" id="LCDate" name="LC_Date" type="text" placeholder="Masukan Tanggal LC" value="">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="LCBank" style="font-weight: 500;">Bank Letter of Credit (LC)</label>
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm" id="LCBank" name="LC_Bank" type="text" placeholder="Masukan Bank LC" value="">
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-12 px-4">
                                    <hr>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="sailing" style="font-weight: 500;">Jenis Pengangkut</label>
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm text-capitalize" id="sailing" name="sailing" type="text" placeholder="Masukan Sailing" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="carrier" style="font-weight: 500;">NO. Identifikasi Kendaraan</label>
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm text-uppercase" id="carrier" name="carrier" type="text" placeholder="Masukan Carrier" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="ShippingMarks" style="font-weight: 500;">Tanda Pengiriman</label>
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm" id="ShippingMarks" name="shipping_marks" type="text" placeholder="Masukan Tanda Pengiriman" value="">
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-12 px-4">
                                    <hr>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="currency" style="font-weight: 500;">Currency</label>
                                    <select class="form-control form-control-sm select2-no-ajx" id="currency" name="currency">
                                        <?php foreach ($Currency->result() as $key) : ?>
                                            <option value="<?= $key->Currency_ID; ?>" data-currency-default="<?= ($key->Currency_ID == 'IDR') ? '1' : ''; ?>" data-currency-symbol="<?= $key->Currency_Symbol; ?>" data-currency-name="<?= $key->Currency_Description; ?>" <?= ($key->Currency_ID == 'IDR') ? 'selected' : ''; ?>>
                                                <?= $key->Currency_ID; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="rate-currency" style="font-weight: 500;">Rate Currency</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm only-number" id="rate-currency" name="rate_currency" type="text" placeholder="Masukkan Kurs mata uang" value="1">
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-12 px-4">
                                    <hr>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-12 px-4">
                                    <div class="table-responsive">
                                        <table id="table-detail-item" class="table-mini dt-nowrap" style="width: 100%; font-size: 0.7rem;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Nomer SI</th>
                                                    <th class="text-center">Nomer SO</th>
                                                    <th class="text-center">Item Code</th>
                                                    <th class="text-center">Item Name</th>
                                                    <th class="text-center">Color</th>
                                                    <th class="text-center">Brand</th>
                                                    <th class="text-center">QTY</th>
                                                    <th class="text-center">UOM</th>
                                                    <th class="text-center">Unit Price</th>
                                                    <th class="text-center">Discount</th>
                                                    <th class="text-center">Disc Value</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Tax 1</th>
                                                    <th class="text-center">Tax 2</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <!-- <div class="text-center">
                                        <span class="badge badge-info" id="no_data_item">Tidak Ada Data</span>
                                    </div> -->
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <!--  -->
                            <div class="row">
                                <div class="col-12 px-4">
                                    <hr>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row d-flex align-items-center my-3">
                                <div class="col-4"></div>
                                <div class="col-4 px-4 form-group">
                                    <div style="border-radius: 10px;" class="p-4 border border-1">
                                        <div class="fw-semibold text-muted fh my-2">Summary Detail</div>
                                        <table class="summary-table">
                                            <tr>
                                                <td style="width: 35%;">
                                                    <label for="total-amount" style="font-weight: 500;" class="p-0 m-0">Amount</label>
                                                </td>
                                                <td style="width: 5%;">
                                                    <label for="total-amount" style="font-weight: 500;" class="p-0 m-0 text-center">:</label>
                                                </td>
                                                <td style="width: 60%;">
                                                    <input class="form-control form-control-sm w-100" id="total-amount" name="total_amount" type="text" value="0" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 35%;">
                                                    <label for="discount_percentage" style="font-weight: 500;" class="p-0 m-0 ">Persentase Diskon</label>
                                                </td>
                                                <td style="width: 5%;">
                                                    <label for="discount_percentage" style="font-weight: 500;" class="p-0 m-0 text-center">:</label>
                                                </td>
                                                <td style="width: 60%;">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control form-control-sm only-number" id="discount-percentage" name="discount_percentage" type="text" placeholder="" value="0" readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 35%;">
                                                    <label for="total_tax" style="font-weight: 500;" class="p-0 m-0">Tax</label>
                                                </td>
                                                <td style="width: 5%;">
                                                    <label for="total_tax" style="font-weight: 500;" class="p-0 m-0 text-center">:</label>
                                                </td>
                                                <td style="width: 60%;">
                                                    <input class="form-control form-control-sm w-100" id="total_tax" name="total_tax" type="text" value="0" readonly>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-4"></div>
                            </div>
                            <!--  -->
                        </div>
                    </div>
                    <div id="footer-main-form" class="card-footer text-muted py-3 text-center">
                        <div class="row d-flex justify-content-end">
                            <div class="col-12 px-3 d-flex justify-content-center">
                                <div>
                                    <button type="submit" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i class="fas fa-save"></i> | Save & Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--  -->