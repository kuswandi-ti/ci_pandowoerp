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

    /* #section-after-chose-si {
        display: none;
        position: relative;
    } */

    /*  */

    #section-after-chose-si {
        /* display: none; */
        /* Awalnya sembunyikan elemen */
        position: relative;
        /* Posisi relatif untuk memastikan urutannya */
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
                                    <th class="text-center" style="width: 15%;">Nomor SR</th>
                                    <th class="text-center" style="width: 15%;">Tanggal SR</th>
                                    <th class="text-center" style="width: 30%;">Nama Customer</th>
                                    <!-- <th class="text-center" style="width: 15%;">Nomor SJ</th> -->
                                    <!-- <th class="text-center" style="width: 10%;">Nomor SO</th> -->
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
                    <input type="hidden" id="state" name="state" value="">
                    <input type="hidden" id="sr-sysId" name="sr_sysId" value="">
                    <!-- EDIT -->
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
                                <label for="nomer-shipping" style="font-weight: 500;">Nomor Sales Return:</label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-sm" id="nomer-sr" name="nomor_sr"
                                        type="text" readonly value="" placeholder="SI<?= date('Ymd'); ?>-xxxxx">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="tanggal-shipping" style="font-weight: 500;">Tanggal Sales Return:</label>
                                <div class="input-group-prepend">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" id="SR-Date"
                                            name="SR_Date" type="text" placeholder="Tanggal sales order...">
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
                        <div id="section-before-chose-si">
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="select-customer" style="font-weight: 500;">Nama Customer</label>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control form-control-sm select2-no-ajx" id="select-customer"
                                            name="Account_ID">
                                            <option value="">---Pilih Nama Costumer--</option>
                                            <?php foreach ($Account_CS->result() as $key) : ?>
                                                <option value="<?= $key->SysId; ?>"> <?= $key->Account_Name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Dev Here -->
                                <div class=" col-lg-6 col-sm-12 px-4 h-100">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="text-muted fw-bold m-0 fh" for="" style="font-weight: 500;">Multiple
                                            Document
                                            SJ</label>
                                        <div id="btn-add-multiple-sj-doc" class="btn-group btn-group-toggle"
                                            data-toggle="buttons">
                                            <label class="btn btn-success btn-sm">
                                                <input type="radio" data-toggle="modal" id="show-list-sj-doc"> <i
                                                    class="fas fa-solid fa-plus"></i>
                                            </label>
                                        </div>
                                    </div>
                                    <table id="shipping-table" class="table table-bordered table-sm mt-3" style="display: none; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;" class="text-center border-bottom-0 vertical-align-middle">No</th>
                                                <th class="text-center border-bottom-0 vertical-align-middle">Nomor Shipping</th>
                                                <th class="text-center border-bottom-0 vertical-align-middle">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- AJAX -->
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
                        <div id="section-after-chose-si">
                            <div class="row mt-3 mb-1">
                                <div class="col-12 px-4 d-flex justify-content-between">
                                    <div id="title-multipe-item" class="fw-semibold text-muted fh">Pilih Multiple Item
                                    </div>
                                    <div id="btn-add-multiple-item" class="btn-group btn-group-toggle"
                                        data-toggle="buttons">
                                        <label class="btn btn-success btn-sm">
                                            <input type="radio" data-toggle="modal" id="show-list-item"> <i
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
                                                    <th class="text-center">Nomor SI</th>
                                                    <th class="text-center">Nomor SO</th>
                                                    <th class="text-center">Item Code</th>
                                                    <th class="text-center">Item Name</th>
                                                    <th class="text-center">Color</th>
                                                    <th class="text-center">Brand</th>
                                                    <th class="text-center">UOM</th>
                                                    <th class="text-center">Item Price</th>
                                                    <th class="text-center" style="width: 10%;">Qty Shipped</th>
                                                    <th class="text-center" style="width: 15%;">Qty Return</th>
                                                    <th class="text-center" style="width: 20%;">Warehouse</th>
                                                    <th class="text-center">Action</th>
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
                            <div class="row d-flex align-items-center">
                                <div class="col-12 px-4 form-group">
                                    <label for="keterangan" style="font-weight: 500;">Keterangan</label>
                                    <div class="input-group input-group-sm">
                                        <textarea class="form-control form-control-sm" id="note" name="note"
                                            placeholder="Masukan Keterangan..." rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="footer-main-form" class="card-footer text-muted py-3 text-center">
                        <div class="row d-flex justify-content-end">
                            <div class="col-12 px-3 d-flex justify-content-center">
                                <div>
                                    <button type="submit" class="btn btn-primary px-5 btn-lg" id="btn-submit"><i
                                            class="fas fa-save"></i> | Save & Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-list-sj-doc" tabindex=" -1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="container">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Pilih Document SJ</h5>
                    <button id="close-btn-modal-table-list-sj-doc" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label for="from" style="font-weight: 500;">Tanggal Produksi:</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="from" id="from" data-toggle="datetimepicker" data-target="#from" required class="form-control text-center datepicker readonly" value="<?= date('Y-m-01') ?>">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-info btn-sm"><i class="fas fa-calendar"></i> S/D</button>
                                    </div>
                                    <input type="text" name="to" id="to" data-toggle="datetimepicker" data-target="#to" required class="form-control text-center datepicker readonly" value="<?= date('Y-m-t') ?>">
                                    <div class="input-group-append">
                                        <button type="button" id="do--filter" class="btn btn-info btn-sm"><i class="fas fa-search"></i> Tampilkan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label style="font-weight: 500;" for="from">Tanggal Produksi:</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="from" id="from" data-toggle="datetimepicker" data-target="#from" required class="form-control text-center datepicker" value="<?= date('Y-m-01') ?>">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-info btn-sm"><i class="fas fa-calendar"></i> S/D</button>
                                    </div>
                                    <input type="text" name="to" id="to" data-toggle="datetimepicker" data-target="#to" required class="form-control text-center datepicker" value="<?= date('Y-m-t') ?>">
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <button type="button" id="do--filter" class="btn btn-sm btn-info btn-block"><i class="fas fa-search"></i> Tampilkan</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-list-sj-doc"
                                class="table table-sm table-bordered table-striped display nowrap"
                                style="width: 100%; font-size: 0.8rem;">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;" class="text-center">#</th>
                                        <th class="text-center">Nomor SJ</th>
                                        <!-- <th class="text-center">Nama Customer</th> -->
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
                    <button id="btn-select-sj-doc" type="button" class="btn btn-primary">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-list-item" tabindex=" -1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="container">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pilih Item</h5>
                    <button id="close-btn-modal-table-select-item" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="table-select-item"
                                class="table table-sm table-bordered table-striped display nowrap"
                                style="width: 100%; font-size: 0.8rem;">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">SO Number</th>
                                        <th class="text-center">Item Code</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Color</th>
                                        <th class="text-center">Model</th>
                                        <th class="text-center">Brand</th>
                                        <th class="text-center">Dimensions</th>
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


<!--  -->
<script type="text/javascript">
    // Mengirim data warehouse dari PHP ke JavaScript
    let warehouseOptions = <?= json_encode($warehouses); ?>;


    // Mengirim data sales_return dari PHP ke JavaScript
</script>