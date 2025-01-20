<style>
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
                                    <th class="text-center" style="width: 15%;">Nomor Shipping</th>
                                    <th class="text-center" style="width: 15%;">Tanggal Shipping</th>
                                    <th class="text-center" style="width: 30%;">Alamat Pengiriman</th>
                                    <th class="text-center" style="width: 15%;">Tanggal Pengiriman</th>
                                    <th class="text-center" style="width: 10%;">PEB & BC</th>
                                    <!-- <th class="text-center" style="width: 10%;">Pelabuhan Muat</th>
<th class="text-center" style="width: 10%;">Tempat Pengiriman</th>
<th class="text-center" style="width: 10%;">Pengangkut</th>
<th class="text-center" style="width: 10%;">Pelayaran</th> -->
                                    <th class="text-center" style="width: 7.5%;">Approval</th>
                                    <th class="text-center" style="width: 7.5%;">Status</th>

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
                    <input type="hidden" id="state" name="state" value="">
                    <input type="hidden" id="area" name="area" value="">
                    <!-- EDIT -->
                    <input id="so-sysId" name="si_sysId" value="" type="hidden">
                    <input id="si-dtl-sysId" name="si_dtl_sysId" value="" type="hidden">
                    <!-- <input id="so-rev" name="so_rev" value="" type="hidden"> -->
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
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="nomer-shipping" style="font-weight: 500;">Nomer Shipping:</label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-sm" id="nomer-shipping" name="nomer_shipping" type="text" readonly value="" placeholder="SI<?= date('Ymd'); ?>-xxxxx">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="tanggal-shipping" style="font-weight: 500;">Tanggal Shipping:</label>
                                <div class="input-group-prepend">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" id="tanggal-shipping" name="tanggal_shipping" type="text" placeholder="Tanggal sales order...">
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
                            <div class="col-12 px-4">
                                <hr>
                            </div>
                        </div>
                        <!--  -->
                        <!--  -->
                        <div class="row d-flex align-items-center">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="select-customer" style="font-weight: 500;">Nama Customer</label>
                                <div class="input-group input-group-sm">
                                    <select class="form-control form-control-sm select2-no-ajx" id="select-customer" name="select_customer">
                                        <option value="">---Pilih Nama Costumer--</option>
                                        <?php foreach ($Account_CS->result() as $key) : ?>
                                            <option value="<?= $key->SysId; ?>"> <?= $key->Account_Name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class=" col-lg-6 col-sm-12 px-4 form-group">
                                <label for="multiple-so-document" style="font-weight: 500;">Multiple SO Document</label>
                                <div id="info-multiple-so" class="text-center">

                                </div>
                                <div id="list-multiple-so" class="input-group input-group-sm input-group-flex">
                                    <span class="badge badge-info" id="no_data_item">Pilih Nama Cutomer</span>
                                </div>
                                <!-- data ajax -->
                                <!-- <div id="list-multiple-so" class="input-group input-group-sm input-group-flex">

                                </div> -->
                            </div>
                        </div>
                        <div>

                            <div id="list-multiple-so" class="input-group input-group-sm input-group-flex">
                                <!-- Badge akan ditambahkan di sini -->
                            </div>
                            <div class="row">
                                <div class="col-12 px-4">
                                    <div class="table-responsive">
                                        <table id="detail-table" class="table table-sm table-bordered table-striped nowrap" style="width: 100%; font-size: 0.7rem;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center vertical-align-middle">Nomer SO</th>
                                                    <th class="text-center vertical-align-middle">Item Code</th>
                                                    <th class="text-center vertical-align-middle">Item Name</th>
                                                    <th class="text-center vertical-align-middle">Note</th>
                                                    <th class="text-center vertical-align-middle">Color</th>
                                                    <th class="text-center vertical-align-middle">Brand</th>
                                                    <th class="text-center vertical-align-middle">Dimension</th>
                                                    <th class="text-center vertical-align-middle">Weight</th>
                                                    <th class="text-center vertical-align-middle">Qty Order</th>
                                                    <th class="text-center vertical-align-middle">Qty OST</th>
                                                    <th class="text-center vertical-align-middle" style="width: 5%;">Qty Shipped</th>
                                                    <th class="text-center vertical-align-middle">Uom</th>
                                                    <th class="text-center vertical-align-middle" style="width: 5%;">Qty Secondary</th>
                                                    <th class="text-center vertical-align-middle" style="width: 10%;">Uom Secondary</th>
                                                    <th colspan="2" class="text-center vertical-align-middle" style="width: 20%;">Warehouse</th>
                                                    <th class="text-center vertical-align-middle">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data akan ditambahkan di sini oleh JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 px-4">
                                    <hr>
                                </div>
                            </div>
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="alamat-customer" style="font-weight: 500;">Alamat Pengiriman</label>
                                    <div class="input-group input-group-sm">
                                        <textarea class="form-control form-control-sm" id="alamat-customer" name="alamat_customer" placeholder="Alamat customer..." rows="3" readonly data-sysid=""></textarea>
                                        <div class="input-group-append">
                                            <button class="btn btn-success" id="btn-list-address" type="button">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="tanggal-pengiriman" style="font-weight: 500;">Tanggal Pengiriman</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" id="tanggal-pengiriman" name="tanggal_pengiriman" type="text" placeholder="Tanggal pengiriman...">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 px-4">
                                    <hr>
                                </div>
                            </div>
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="port-of-loading" style="font-weight: 500;">Tempat Muat (Port of Loading)</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" id="port-of-loading" name="port_of_loading" type="text" placeholder="Masukan Tempat Muat" value="">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="place-of-delivery" style="font-weight: 500;">Tempat Pengiriman (Place of Delivery)</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" id="place-of-delivery" name="place_of_delivery" type="text" placeholder="Masukan Tempat Pengiriman" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="sailing" style="font-weight: 500;">Jenis Pengangkut</label>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control form-control-sm" id="sailing" name="sailing">
                                            <option value="mobil">Mobil</option>
                                            <option value="kapal laut">Kapal Laut</option>
                                            <option value="other">Lainnya</option>
                                        </select>
                                    </div>
                                    <!-- Inputan untuk "Other" yang akan tampil saat opsi "Other" dipilih -->
                                    <div class="input-group input-group-sm mt-2" id="otherInputDiv" style="display:none;">
                                        <input class="form-control form-control-sm" id="other_transport" name="other_transport" type="text" placeholder="Masukkan Jenis Pengangkut Lainnya">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label id="carrierLabel" for="carrier" style="font-weight: 500;">NO. Identifikasi Kendaraan</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" id="carrier" name="carrier" type="text" placeholder="Masukan NO Pol Kendaraan" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 px-4">
                                    <hr>
                                </div>
                            </div>
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="NotifeParty" style="font-weight: 500;">Pihak Penerima Pemberitahuan</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm export-field" id="NotifeParty" name="NotifeParty" type="text" placeholder="Masukan Pihak Penerima Pemberitahuan" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="NotifePartyAddress" style="font-weight: 500;">Alamat Pihak Penerima Pemberitahuan</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm export-field" id="NotifePartyAddress" name="NotifePartyAddress" type="text" placeholder="Masukan Alamat Pihak Penerima Pemberitahuan" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="ShippingMarks" style="font-weight: 500;">Tanda Pengiriman</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm export-field" id="ShippingMarks" name="ShippingMarks" type="text" placeholder="Masukan Tanda Pengiriman" value="">
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
                                        <input class="form-control form-control-sm export-field" id="LCNo" name="LCNo" type="text" placeholder="Masukan Nomor LC" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label for="LCDate" style="font-weight: 500;">Tanggal Letter of Credit (LC)</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr export-field" id="LCDate" name="LCDate" type="text" placeholder="Masukan Tanggal LC" value="">
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
                                        <input class="form-control form-control-sm export-field" id="LCBank" name="LCBank" type="text" placeholder="Masukan Bank LC" value="">
                                    </div>
                                </div>
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
</div>
<!--  -->
<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">
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
                            <table id="table-address" class="table-responsive table table-sm table-bordered table-striped display nowrap" style="width: 100%; font-size: 0.8rem;">
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
<!-- Modal -->
<div class="modal fade" id="list-stock-item-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Detail Barang</h5>
                <button id="close-btn-modal-list-gudang" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="left-container" class="col-12 p-3">
                        <div class="row w-100 h-100">
                            <div class="col-12 p-4 border border-1 bordered-container h-100" style="border-radius: 10px;">
                                <h5 class="fh text-muted">List Gudang</h5>
                                <div class="h-100 w-100 d-flex justify-content-center align-items-center">
                                    <div class="w-100">
                                        <table id="table-stock-item" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%; font-size: 0.8rem;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Kode Gudang</th>
                                                    <th class="text-center">Nama Gudang</th>
                                                    <th class="text-center">Stok Item</th>
                                                    <th class="text-center">Uom</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data akan dimasukkan di sini oleh JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="right-container-parent" style="display: none;" class="col-4">
                        <div class="row w-100">
                            <div id="right-container" class="col-12 p-3 d-flex flex-column justify-content-center">
                                <div class="row">
                                    <div class="col-12 p-4 border border-1 bordered-container" style="border-radius: 10px;">
                                        <h5 class="fh text-muted">Detail Item</h5>
                                        <!-- Tabel detail item akan ditempatkan di sini -->
                                        <table id="dtl-item-details-table">
                                            <tr>
                                                <td>Item Code</td>
                                                <td>:</td>
                                                <td id="dtl-item-code"></td>
                                            </tr>
                                            <tr>
                                                <td>Item Name</td>
                                                <td>:</td>
                                                <td id="dtl-item-name"></td>
                                            </tr>
                                            <tr>
                                                <td>Note</td>
                                                <td>:</td>
                                                <td id="dtl-note"></td>
                                            </tr>
                                            <tr>
                                                <td>Color</td>
                                                <td>:</td>
                                                <td id="dtl-color"></td>
                                            </tr>
                                            <tr>
                                                <td>Brand</td>
                                                <td>:</td>
                                                <td id="dtl-brand"></td>
                                            </tr>
                                            <tr>
                                                <td>Dimension</td>
                                                <td>:</td>
                                                <td id="dtl-dimension"></td>
                                            </tr>
                                            <tr>
                                                <td>Weight</td>
                                                <td>:</td>
                                                <td id="dtl-weight"></td>
                                            </tr>
                                            <tr>
                                                <td>QTY Order</td>
                                                <td>:</td>
                                                <td id="qty-order-validate"></td>
                                            </tr>
                                            <tr>
                                                <td>QTY OST</td>
                                                <td>:</td>
                                                <td id="qty-ost-validate"></td>
                                            </tr>
                                        </table>
                                        <input type="hidden" id="hdr-so-sysId">
                                        <input type="hidden" id="dtl-so-sysId">
                                    </div>
                                </div>
                                <h5 class="fh" style="visibility: hidden;">List Gudang</h5>
                                <div class="row mt-2">
                                    <div class="col-12 p-4 border border-1 bordered-container" style="border-radius: 10px;">
                                        <h5 class="fh text-muted">Gudang yang Dipilih</h5>
                                        <!-- Tabel gudang yang dipilih akan ditempatkan di sini -->
                                        <table id="selected-warehouses-table" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%; font-size: 0.8rem;">
                                            <thead>
                                                <tr>
                                                    <th style="width: 60%;" class="text-center">Gudang</th>
                                                    <th style="width: 40%;" class="text-center">QTY</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data akan dimasukkan di sini oleh JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="add-warehouse">Tambah</button>
            </div>
        </div>
    </div>
</div>
<!--  -->
<div class="modal fade" id="detail_PEB_BC" tabindex="-1" aria-labelledby="detailPEB_BCLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="detailPEB_BCLabel">Detail PEB & BC</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="peb_bc_form">
                    <input type="hidden" id="sysid-for-update-peb-bc" name="sysid_for_update_peb_bc" value="">
                    <div class="row">
                        <div class="col-12">
                            <div class="fh text-muted fw-bold">Pemberitahuan Ekspor Barang (PEB)</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <!-- Input fields go here -->
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 form-group">
                            <label class="text-muted" for="PEB_Number" style="font-weight: 500;">Nomor Pemberitahuan Ekspor Barang</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm" id="PEB-Number" name="PEB_Number" type="text" placeholder="Masukan Nomor PEB" value="">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 form-group">
                            <label class="text-muted" for="PEB_Date" style="font-weight: 500;">Tanggal Pemberitahuan Ekspor Barang</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm flatpickr" id="PEB-Date" name="PEB_Date" type="date" placeholder="Masukan Tanggal PEB" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 form-group">
                            <label class="text-muted" for="PEB_Receiver" style="font-weight: 500;">Penerima Pemberitahuan Ekspor Barang</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm" id="PEB-Receiver" name="PEB_Receiver" type="text" placeholder="Masukan Penerima PEB" value="">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 form-group">
                            <label class="text-muted" for="PEB_Country" style="font-weight: 500;">Negara Pemberitahuan Ekspor Barang</label>
                            <div class="input-group input-group-sm">
                                <select class="form-control form-control-sm select2-no-ajx" id="PEB-Country" name="PEB_Country">
                                    <option value="">---Pilih Nama Negara---</option>
                                    <?php foreach ($country->result() as $key) : ?>
                                        <option value="<?= $key->Country_Name; ?>"> <?= $key->Country_Name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 form-group">
                            <label class="text-muted" for="PEB_Amount" style="font-weight: 500;">Jumlah Pemberitahuan Ekspor Barang</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm only-number" id="PEB-Amount" name="PEB_Amount" type="number" placeholder="Masukan Jumlah PEB" value="">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 form-group">
                            <label class="text-muted" for="PEB_Volume" style="font-weight: 500;">Volume Pemberitahuan Ekspor Barang</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm only-number" id="PEB-Volume" name="PEB_Volume" type="number" placeholder="Masukan Volume PEB" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 form-group">
                            <label class="text-muted" for="PEB_Netto" style="font-weight: 500;">Netto Pemberitahuan Ekspor Barang</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm only-number" id="PEB-Netto" name="PEB_Netto" type="number" placeholder="Masukan Netto PEB" value="">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 form-group">
                            <label class="text-muted" for="PEB_Merk" style="font-weight: 500;">Merek Pemberitahuan Ekspor Barang</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm" id="PEB-Merk" name="PEB_Merk" type="text" placeholder="Masukan Merek PEB" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="text-muted" for="PEB_PackageNumber" style="font-weight: 500;">Nomor Paket Pemberitahuan Ekspor Barang</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm" id="PEB-PackageNumber" name="PEB_PackageNumber" type="number" placeholder="Masukan Nomor Paket PEB" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="fh fw-bold text-muted">Bea Cukai (BC)</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 form-group">
                            <label class="text-muted" for="BC_Type" style="font-weight: 500;">Jenis Bea Cukai</label>
                            <div class="input-group input-group-sm">
                                <select class="form-control form-control-sm select2-no-ajx" id="BC_Type" name="BC_Type">
                                    <option value="">---Pilih Jenis Bea Cukai---</option>
                                    <?php foreach ($bc_types->result() as $key) : ?>
                                        <option value="<?= $key->kode_cukai; ?>"> <?= $key->nama_cukai; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 form-group">
                            <label class="text-muted" for="BC_Number" style="font-weight: 500;">Nomor Bea Cukai</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm" id="BC-Number" name="BC_Number" type="number" placeholder="Masukan Nomor Bea Cukai" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="text-muted" for="BC_Date" style="font-weight: 500;">Tanggal Bea Cukai</label>
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-sm flatpickr" id="BC-Date" name="BC_Date" type="date" placeholder="Masukan Tanggal Bea Cukai" value="">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="save-peb-bc">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Mengirim data pajak dari PHP ke JavaScript
    let unitTypeOptions = <?= json_encode($unit_type->result()); ?>;
</script>