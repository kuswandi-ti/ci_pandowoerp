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
            <div class="card bd-callout shadow add-data">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
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
                                <label for="nomer-sr" style="font-weight: 500;">Nomor Sales Return:</label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-sm" id="nomer-sr" type="text" readonly value="<?= $data_hdr->SR_Number ?? '' ?>" placeholder="Nomor Sales Return">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="SR-Date" style="font-weight: 500;">Tanggal Sales Return:</label>
                                <div class="input-group-prepend">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" id="SR-Date" type="text" readonly value="<?= $data_hdr->SR_Date ?? '' ?>" placeholder="Tanggal Sales Return">
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

                        <div id="section-before-chose-si">
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label for="select-customer" style="font-weight: 500;">Nama Customer</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" type="text" readonly value="<?= $data_hdr->Account_Name ?? '' ?>" placeholder="Nama Customer">
                                    </div>
                                </div>
                                <div class=" col-lg-6 col-sm-12 px-4 h-100">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="text-muted fw-bold m-0 fh" for="" style="font-weight: 500;">Multiple
                                            Document
                                            SJ</label>
                                        <!-- <div id="btn-add-multiple-sj-doc" class="btn-group btn-group-toggle"
                                            data-toggle="buttons">
                                            <label class="btn btn-success btn-sm">
                                                <input type="radio" data-toggle="modal" id="show-list-sj-doc"> <i
                                                    class="fas fa-solid fa-plus"></i>
                                            </label>
                                        </div> -->
                                    </div>
                                    <table id="shipping-table" class="table table-bordered table-sm mt-3" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;" class="text-center border-bottom-0 vertical-align-middle">No</th>
                                                <th class="text-center border-bottom-0 vertical-align-middle">Nomor Shipping</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($si_numbers as $shippingNumber): ?>
                                                <tr>
                                                    <td class="text-center vertical-align-middle"><?= $no++; ?></td>
                                                    <td class="text-center vertical-align-middle"><?= htmlspecialchars($shippingNumber); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
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
                                    <div id="title-multipe-item" class="fw-semibold text-muted fh">Multiple Item</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 px-4">
                                    <div class="table-responsive">
                                        <table id="table-detail-item" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%; font-size: 0.7rem;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Nomor SO</th>
                                                    <th class="text-center">Item Code</th>
                                                    <th class="text-center">Item Name</th>
                                                    <th class="text-center">Color</th>
                                                    <th class="text-center">Brand</th>
                                                    <th class="text-center">UOM</th>
                                                    <th class="text-center">Item Price</th>
                                                    <th class="text-center" style="width: 10%;">Qty Shipped</th>
                                                    <th class="text-center" style="width: 15%;">Qty Return</th>
                                                    <th class="text-center" style="width: 25%;">Warehouse</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data_dtl as $detail) : ?>
                                                    <tr>
                                                        <td class="text-center"><?= $detail->SO_Number ?></td>
                                                        <td class="text-center"><?= $detail->Item_Code ?></td>
                                                        <td class="text-center"><?= $detail->Item_Name ?></td>
                                                        <td class="text-center"><?= $detail->Item_Color ?></td>
                                                        <td class="text-center"><?= $detail->Brand ?></td>
                                                        <td class="text-center text-uppercase"><?= $detail->Uom ?></td>
                                                        <td class="text-center"><?= $detail->Currency_Symbol . '. ' . number_format($detail->Item_Price, 2) ?></td>
                                                        <td class="text-center"><?= $detail->Qty_Info ?></td>
                                                        <td class="text-center"><?= $detail->Qty ?></td>
                                                        <td class="text-center"><?= isset($detail->Warehouse_Name) ? $detail->Warehouse_Name : '' ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-flex align-items-center">
                                <div class="col-12 px-4 form-group">
                                    <label for="keterangan" style="font-weight: 500;">Keterangan</label>
                                    <div class="input-group input-group-sm">
                                        <textarea class="form-control form-control-sm" id="keterangan" readonly placeholder="Masukan Keterangan..." rows="3"><?= $data_hdr->Notes ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
                    <button id="close-btn-modal-table-select-item" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

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

<div class="modal fade" id="modal-list-warehouse" tabindex=" -1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="container">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pilih Warehouse</h5>
                    <button id="close-btn-modal-table-select-item" type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                        <th class="text-center">Nama Gudang</th>
                                        <th class="text-center">Kode Gudang</th>
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