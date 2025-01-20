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

    .table td,
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
</style>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card bd-callout shadow add-data">
                <form method="post" enctype="multipart/form-data" action="#" id="main-form">
                    <div class="card-header">
                        <h2 class="card-title mt-2"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="nomer-so" style="font-weight: 500;">Nomer SO:</label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-sm" type="text" disabled value="<?= $data_hdr->SO_Number ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="tanggal-so" style="font-weight: 500;">Tanggal SO:</label>
                                <div class="input-group-prepend">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" disabled value="<?= $data_hdr->SO_Date ?>">
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
                                    <input class="form-control form-control-sm" disabled value="<?= $data_hdr->Customer_Name ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="alamat-customer" style="font-weight: 500;">Alamat Customer</label>
                                <div class="input-group input-group-sm">
                                    <textarea class="form-control form-control-sm" disabled><?= $data_hdr->Customer_Address ?></textarea>
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
                                    <input class="form-control form-control-sm" disabled value="<?= $data_hdr->PO_Number ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label for="tanggal-po-customer" style="font-weight: 500;">Tanggal PO Customer:</label>
                                <div class="input-group-prepend">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" disabled value="<?= $data_hdr->PO_Date ?>">
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
                                        <input class="form-control form-control-sm flatpickr" disabled value="<?= $data_hdr->SO_DeliveryDate ?>">
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
                                    <input class="form-control form-control-sm" disabled value="<?= $data_hdr->Term_Of_Payment ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label for="unit-top" style="font-weight: 500;">Unit TOP:</label>
                                <input class="form-control form-control-sm" disabled value="<?= $data_hdr->TOP_unit ?>">
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label for="dokumen-top" style="font-weight: 500;">Dokumen TOP</label>
                                <input class="form-control form-control-sm" disabled value="<?= $data_hdr->TOP_Doc ?>">
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
                                <input class="form-control form-control-sm" disabled value="<?= $data_hdr->CurrencyType_Id ?>">
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label for="rate-currency" style="font-weight: 500;">Rate Currency</label>
                                <input class="form-control form-control-sm" disabled value="<?= floatval($data_hdr->Currency_Rate) ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 px-4">
                                <hr>
                            </div>
                        </div>
                        <div class="row mt-3 mb-1">
                            <div class="col-12 px-4 d-flex justify-content-between">
                                <div id="title-multipe-item" class="fw-semibold text-muted fh">Detail Item List</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 px-4">

                                <div class="table-responsive">
                                    <table id="table-detail-item" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%; font-size: 0.7rem;">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;" class="text-center">Item Code</th>
                                                <th style="width: 13%;" class="text-center">Item Name</th>
                                                <th style="width: 15%;" class="text-center">Note</th>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data_dtl as $item) : ?>
                                                <tr>
                                                    <td class="text-center"><?= $item->Item_Code; ?></td>
                                                    <td class="text-center"><?= $item->Item_Name; ?></td>
                                                    <td class="text-center"><?= $item->Note; ?></td>
                                                    <td class="text-center"><?= $item->Item_Color; ?></td>
                                                    <td class="text-center"><?= $item->Brand; ?></td>
                                                    <td class="text-center"><?= number_format($item->Qty, 2); ?></td>
                                                    <td class="text-center text-uppercase"><?= $item->Uom; ?></td>
                                                    <td class="text-center"><?= number_format($item->Item_Price, 2); ?></td>
                                                    <td class="text-center"><?= number_format($item->Discount, 2); ?></td>
                                                    <td class="text-center"><?= number_format($item->Discount_Amount, 2); ?></td>
                                                    <td class="text-center"><?= number_format($item->Amount_Detail - $item->Discount_Amount, 2); ?></td>
                                                    <td class="text-center"><?= isset($item->Tax1_Code) && $item->Tax1_Code ? $item->Tax1_Code : 'NULL'; ?></td>
                                                    <td class="text-center"><?= isset($item->Tax2_Code) && $item->Tax2_Code ? $item->Tax2_Code : 'NULL'; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tbody>
                                        </tbody>
                                    </table>
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
                                    <textarea class="form-control form-control-sm" disabled><?= $data_hdr->Remarks ?></textarea>
                                </div>
                            </div>
                            <div class="col-5 px-4 form-group">
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
                                                <input class="form-control form-control-sm w-100" disabled value="<?= isset($data_hdr->Amount) ? number_format($data_hdr->Amount, 2) : 'NULL' ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 35%;">
                                                <label for="discount_percentage" style="font-weight: 500;" class="p-0 m-0">Persentase Diskon</label>
                                            </td>
                                            <td style="width: 5%;">
                                                <label for="discount_percentage" style="font-weight: 500;" class="p-0 m-0 text-center">:</label>
                                            </td>
                                            <td style="width: 60%;">
                                                <div class="input-group input-group-sm">
                                                    <input class="form-control form-control-sm" disabled value="<?= isset($data_hdr->Discount_Persen) ? number_format($data_hdr->Discount_Persen, 2) : 'NULL' ?>">
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
                                                <input class="form-control form-control-sm w-100" disabled value="<?= isset($total_tax) ? number_format($total_tax, 2) : 'NULL' ?>">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>