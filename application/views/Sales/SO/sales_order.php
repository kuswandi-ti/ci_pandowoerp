<style>
    #DataTable tbody tr {
        cursor: pointer;
    }

    .summary-table {
        width: 100%;
        /* border-collapse: collapse; */
    }

    .summary-table td {
        vertical-align: middle;
        padding-top: 10px;
        /* border: 1px solid #ccc; */
    }

    Q .table td,
    .table th {
        vertical-align: middle !important;
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

    .bg-item-close {
        background-color: #FFF59D;
    }

    .vertical-align-middle {
        vertical-align: middle !important;
    }

    .table-responsive {
        width: 100%;
    }

    .table-responsive:empty {
        width: 100%;
    }

    .table-responsive .dataTables_empty {
        width: 100%;
        text-align: center;
    }

    /* CSS untuk tooltip */
    .tooltip-hover {
        position: relative;
        display: inline-block;
        cursor: not-allowed;
        /* Kursor berubah menjadi tanda tidak bisa diklik */
    }

    .tooltip-hover .tooltiptext {
        visibility: hidden;
        width: 140px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 5px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 100%;
        /* Letakkan tooltip di atas elemen */
        left: 50%;
        margin-left: -70px;
        /* Geser untuk memposisikan di tengah */
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tooltip-hover:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }

    /* CSS untuk warna baris kuning */
    .bg-warning {
        background-color: #ffc107 !important;
    }

    /* Tooltip pada baris yang berwarna kuning */
    .bg-warning .tooltip-hover {
        cursor: not-allowed;
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

                                    <th class="text-center">Nomor SO</th>
                                    <th class="text-center">Tanggal SO</th>
                                    <th class="text-center">Nama Customer</th>
                                    <th class="text-center">Nomer PO</th>
                                    <th class="text-center">Amont</th>
                                    <th class="text-center">Currency</th>
                                    <th class="text-center">TGL Pengiriman</th>
                                    <th class="text-center">Approval</th>
                                    <th class="text-center">Status</th>
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
                    <input id="customer-id" name="customer_id" type="hidden">
                    <input id="customer-code" name="customer_code" type="hidden">
                    <input id="alamat-customer-id" name="alamat_customer_id" type="hidden">
                    <input id="account-npwp" name="account_npwp" type="hidden">
                    <input id="currency-name" name="currency_name" value="" type="hidden">
                    <input id="currency-symbol" name="currency_symbol" value="" type="hidden">
                    <input type="hidden" name="state" value="">
                    <!-- EDIT -->
                    <input id="so-sysId" name="so_sysId" value="" type="hidden">
                    <input id="so-rev" name="so_rev" value="" type="hidden">
                    <!-- END - Kumpulan inputan yang di hidden -->
                    <div class="card-header">
                        <h2 class="card-title mt-2"><span id="action-tittle"></span> <?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm" id="back" title="back"
                                data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="nomer-so" style="font-weight: 500;">Nomor SO:</label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-sm" id="nomer-so" name="nomer_so"
                                        type="text" readonly value="" placeholder="SO<?= date('Ymd'); ?>-xxxxx">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="tanggal-so" style="font-weight: 500;">Tanggal SO:</label>
                                <div class="input-group-prepend">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" id="tanggal-so"
                                            name="tanggal_so" type="text" placeholder="Tanggal sales order...">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex align-items-center">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="nama-customer" style="font-weight: 500;">Nama Customer</label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-sm" id="nama-customer" name="nama_customer"
                                        type="text" placeholder="Otomatis dari item" readonly value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="alamat-customer" style="font-weight: 500;">Alamat Customer</label>
                                <div class="input-group input-group-sm">
                                    <textarea class="form-control form-control-sm" id="alamat-customer"
                                        name="alamat_customer" placeholder="Alamat customer..." rows="3" readonly
                                        data-sysid=""></textarea>
                                    <div class="input-group-append">
                                        <button value="test" class="btn btn-success" id="btn-list-address"
                                            type="button">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-12 px-4">
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label for="nomer-po-customer" style="font-weight: 500;">Nomer PO Customer:</label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-sm" id="nomer-po-customer"
                                        name="nomer_po_customer" type="text" placeholder="Masukan Nomer PO customer"
                                        value="">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label for="tanggal-po-customer" style="font-weight: 500;">Tanggal PO Customer:</label>
                                <div class="input-group-prepend">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" id="tanggal-po-customer"
                                            name="tanggal_po_customer" type="text" placeholder="Masukan Tanggal PO...">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label for="tanggal-pengiriman" style="font-weight: 500;">Tanggal Pengiriman:</label>
                                <div class="input-group-prepend">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" id="tanggal-pengiriman"
                                            name="tanggal_pengiriman" type="text"
                                            placeholder="Masukan Tanggal Pengiriman...">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label for="term-of-payment" style="font-weight: 500;">Term Of Payment:</label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-sm" id="term-of-payment"
                                        name="term_of_payment" type="number"
                                        placeholder="Masukkan Term Of Payment...(TOP)" value="">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label for="unit-top" style="font-weight: 500;">Unit TOP:</label>
                                <select class="form-control form-control-sm" id="unit-top" name="unit_top">
                                    <option value="">---Pilih Unit TOP---</option>
                                    <option value="DAY">DAY</option>
                                    <option value="MONTH">MONTH</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label for="dokumen-top" style="font-weight: 500;">Dokumen TOP</label>
                                <select class="form-control form-control-sm" id="dokumen-top" name="dokumen_top">
                                    <option selected value="">---Pilih Document TOP---</option>
                                    <option value="AFTER INVOICE RECEIVED">AFTER INVOICE RECEIVED</option>
                                    <option value="AFTER PO CLOSE">AFTER PO CLOSE</option>
                                    <option value="AFTER GOODS RECEIVED NOTE">AFTER GOODS RECEIVED NOTE</option>
                                    <option value="AFTER PO RECEIVED">AFTER PO RECEIVED</option>
                                    <!-- <option value="AFTER INVOICE RECEIVED">Setelah Faktur</option>
                                    <option value="AFTER PO CLOSE">Setelah PO Selesai</option>
                                    <option value="AFTER GOODS RECEIVED NOTE">Setelah Terima Barang</option>
                                    <option value="AFTER PO RECEIVED">Setelah Terima PO</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-12 px-4">
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="currency" style="font-weight: 500;">Currency</label>
                                <select class="form-control form-control-sm select2-no-ajx" id="currency"
                                    name="currency">
                                    <?php foreach ($Currency->result() as $key) : ?>
                                        <option value="<?= $key->Currency_ID; ?>"
                                            data-currency-default="<?= ($key->Currency_ID == 'IDR') ? '1' : ''; ?>"
                                            data-currency-symbol="<?= $key->Currency_Symbol; ?>"
                                            data-currency-name="<?= $key->Currency_Description; ?>"
                                            <?= ($key->Currency_ID == 'IDR') ? 'selected' : ''; ?>>
                                            <?= $key->Currency_ID; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="rate-currency" style="font-weight: 500;">Rate Currency</label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-sm only-number" id="rate-currency"
                                        name="rate_currency" type="text" placeholder="Masukkan Kurs mata uang"
                                        value="1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 px-4">
                                <hr>
                            </div>
                        </div>
                        <div class="row mt-3 mb-1">
                            <div class="col-12 px-4 d-flex justify-content-between">
                                <div id="title-multipe-item" class="fw-semibold text-muted fh">Pilih Multiple Item</div>
                                <div id="btn-add-multiple-item" class="btn-group btn-group-toggle"
                                    data-toggle="buttons">
                                    <label class="btn btn-success btn-sm">
                                        <input type="radio" data-toggle="modal" data-target="#exampleModal"> <i
                                            class="fas fa-solid fa-plus"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 px-4">
                                <div class="table-responsive">
                                    <table id="table-detail-item"
                                        class="table table-sm table-bordered table-striped display nowrap"
                                        style="width: 100%; font-size: 0.7rem;">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;" class="text-center">Item Code</th>
                                                <th style="width: 13%;" class="text-center">Item Name</th>
                                                <th style="width: 10%;" class="text-center">Note</th>
                                                <th style="width: 5%;" class="text-center">Color</th>
                                                <th style="width: 5%;" class="text-center">Brand</th>
                                                <th style="width: 6%;" class="text-center">Qty</th>
                                                <th style="width: 3%;" class="text-center">UOM</th>
                                                <th style="width: 9%;" class="text-center">Unit Price</th>
                                                <th style="width: 3%;" class="text-center">Discount</th>
                                                <th style="width: 8%;" class="text-center">Disc Value</th>
                                                <th style="width: 10%;" class="text-center">Amount Item</th>
                                                <th style="width: 7%;" class="text-center">Tax 1</th>
                                                <th style="width: 7%;" class="text-center">Tax 2</th>
                                                <th style="width: 5%;" class="text-center">Action</th>
                                                <th style="width: 0%;" class="text-center d-none">HIDDEN-VALUE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <span class="badge badge-info" id="no_data_item">Tidak Ada Data</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-12 px-4">
                                <hr>
                            </div>
                        </div>
                        <div class="row d-flex align-items-center">
                            <div class="col-7 px-4 form-group">
                                <label for="keterangan" style="font-weight: 500;">Keterangan</label>
                                <div class="input-group input-group-sm">
                                    <textarea class="form-control form-control-sm" id="keterangan" name="keterangan"
                                        placeholder="Masukan Keterangan..." rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-5 px-4 form-group">
                                <div style="border-radius: 10px;" class="p-4 border border-1">
                                    <div class="fw-semibold text-muted fh my-2">Summary Detail</div>
                                    <table class="summary-table">
                                        <tr>
                                            <td style="width: 35%;">
                                                <label for="total-amount" style="font-weight: 500;"
                                                    class="p-0 m-0">Amount</label>
                                            </td>
                                            <td style="width: 5%;">
                                                <label for="total-amount" style="font-weight: 500;"
                                                    class="p-0 m-0 text-center">:</label>
                                            </td>
                                            <td style="width: 60%;">
                                                <input class="form-control form-control-sm w-100" id="total-amount"
                                                    name="total_amount" type="text" value="0.00" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 35%;">
                                                <label for="discount_percentage" style="font-weight: 500;"
                                                    class="p-0 m-0 ">Persentase Diskon</label>
                                            </td>
                                            <td style="width: 5%;">
                                                <label for="discount_percentage" style="font-weight: 500;"
                                                    class="p-0 m-0 text-center">:</label>
                                            </td>
                                            <td style="width: 60%;">
                                                <div class="input-group input-group-sm">
                                                    <input class="form-control form-control-sm only-number  input-decimal"
                                                        id="discount-percentage" name="discount_percentage" type="text"
                                                        placeholder="" value="0.00">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-percent"></i></span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 35%;">
                                                <label for="total_tax" style="font-weight: 500;"
                                                    class="p-0 m-0">Tax</label>
                                            </td>
                                            <td style="width: 5%;">
                                                <label for="total_tax" style="font-weight: 500;"
                                                    class="p-0 m-0 text-center">:</label>
                                            </td>
                                            <td style="width: 60%;">
                                                <input class="form-control form-control-sm w-100" id="total_tax"
                                                    name="total_tax" type="text" value="0.00" readonly>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="footer-main-form" class="card-footer text-muted py-3 text-center">
                        <div class="row d-flex justify-content-end">
                            <div class="col-12 px-3 d-flex justify-content-center">
                                <div class="hidden-element">
                                    <button type="button" href="javascript:void(0);"
                                        class="btn btn-primary px-5 btn-lg"><i class="fas fa-solid fa-calculator"></i> |
                                        Calculate</button>
                                    <button type="button" href="javascript:void(0);"
                                        class="btn btn-primary px-5 btn-lg"><i class="fas fa-save"></i> | Save &
                                        Submit</button>
                                </div>
                                <div>
                                    <button type="button" href="javascript:void(0);"
                                        class="btn btn-outline-primary px-5 btn-lg" id="btn-calculate"><i
                                            class="fas fa-solid fa-calculator"></i> | Calculate</button>
                                    <button type="submit" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i
                                            class="fas fa-save"></i> | Save & Submit</button>
                                </div>
                                <div class="hidden-element">
                                    <button type="button" href="javascript:void(0);"
                                        class="btn btn-primary px-5 btn-lg"><i class="fas fa-solid fa-calculator"></i> |
                                        Calculate</button>
                                    <button type="button" href="javascript:void(0);"
                                        class="btn btn-primary px-5 btn-lg"><i class="fas fa-save"></i> | Save &
                                        Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="container">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pilih Item</h5>
                    <button id="close-btn-modal-table-select-item" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="nomer-po-customer" style="font-weight: 500;">Nama Costumer:</label>
                            <div class="input-group input-group-sm">
                                <select class="form-control form-control-sm select2-no-ajx" id="select-customer"
                                    name="select-customer">
                                    <option value="">---Pilih Nama Costumer--</option>
                                    <?php foreach ($Account_CS->result() as $key) : ?>
                                        <option value="<?= $key->SysId; ?>" data-account-npwp=" <?= $key->TaxFileNumber; ?>"
                                            data-account-id="<?= $key->SysId; ?>"
                                            data-account-code="<?= $key->Account_Code; ?>"> <?= $key->Account_Name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class=" row">
                        <div class="col-12 form-group">
                            <label for="nomer-po-customer" style="font-weight: 500;">Item Category</label>
                            <div class="input-group input-group-sm">
                                <select class="form-control form-control-sm" id="select-category"
                                    name="select-category">
                                    <option value="">---Pilih Category--</option>
                                    <?php foreach ($List_Item_Category->result() as $key) : ?>
                                        <option value="<?= $key->SysId; ?>">
                                            <?= $key->Item_Category; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-primary btn-sm w-100" id="btn-search-item">Search...</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-select-item"
                                class="table table-sm table-bordered table-striped display nowrap"
                                style="width: 100%; font-size: 0.8rem;">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Item Code</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Color</th>
                                        <th class="text-center">Model</th>
                                        <th class="text-center">Brand</th>
                                        <th class="text-center">Dimensions</th>
                                        <th class="text-center">Weight</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button id="btn-close-modal" type="button" class="btn btn-outline-warning"" data-dismiss=" modal">Cancel</button> -->
                    <button id="btn-select-item" type="button" class="btn btn-primary">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="container">
                <div class="modal-header">
                    <h5 class="modal-title" id="addressModalLabel">Select Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="table-address"
                                class="table-responsive table table-sm table-bordered table-striped display nowrap"
                                style="width: 100%; font-size: 0.8rem;">
                                <thead>
                                    <tr>
                                        <th class="d-none">#</th>
                                        <th>#</th>
                                        <th>Address</th>
                                        <th>Area</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan dimasukkan di sini oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn-select-address" type="button" class="btn btn-primary">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Mengirim data pajak dari PHP ke JavaScript
    let taxOptions = <?= json_encode($List_Tax); ?>;
</script>