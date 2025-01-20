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
    <?php
    $emptyMsg = "Khusus untuk transaksi ekspor"; // Variabel pesan jika nilai kosong
    ?>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card bd-callout shadow add-data">
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
                            <h2 class="card-title mt-2">Add <?= $page_title ?></h2>
                            <div class="card-tools">
                                <a href="javascript:void(0);" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!--  -->
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Nomor Invoice:</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" type="text" value="<?= $data_hdr->Invoice_Number; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Nama Customer:</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" type="text" value="<?= $data_hdr->Account_Name; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Alamat Customer:</label>
                                    <div class="input-group input-group-sm">
                                        <textarea class="form-control form-control-sm" rows="3" readonly><?= $data_shp[0]->ShipToAddress; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Document Shipping:</label>
                                    <table class="table table-bordered table-sm" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;" class="text-center border-bottom-0 vertical-align-middle">#</th>
                                                <th class="text-center border-bottom-0 vertical-align-middle">No Shipping</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $shp_numb = ''; ?>
                                            <?php foreach ($data_shp as $index => $item): ?>
                                                <?php if ($item->ShipInst_Number !== $data_hdr->SI_Number) continue; ?>
                                                <tr class="bg-primary">
                                                    <td class="text-center">
                                                        <input type="radio" name="select-item" class="select-item vertical-align-middle m-0" value="<?= $item->ShipInst_Number ?>" checked>
                                                    </td>
                                                    <td class="text-center vertical-align-middle">
                                                        <?= $item->ShipInst_Number ?>
                                                    </td>
                                                </tr>
                                                <?php $shp_numb = $item->ShipInst_Number; ?>
                                            <?php endforeach; ?>
                                        </tbody>

                                    </table>
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
                                    <label style="font-weight: 500;">Pihak Penerima Pemberitahuan</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" type="text" value="<?= $data_hdr->NotifeParty ?: $emptyMsg; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Alamat Pihak Penerima Pemberitahuan</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" type="text" value="<?= $data_hdr->NotifePartyAddress ?: $emptyMsg; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Tempat Muat (Port of Loading)</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm text-capitalize" type="text" value="<?= $data_hdr->PortOfLoading ?: $emptyMsg; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Tempat Pengiriman (Place of Delivery)</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm text-capitalize" type="text" value="<?= $data_hdr->PlaceOfDelivery ?: $emptyMsg; ?>" readonly>
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
                                    <label style="font-weight: 500;">Tanggal Invoice</label>
                                    <div class="input-group input-group-sm">

                                        <input class="form-control form-control-sm" disabled type="text" value="<?= date('d F Y', strtotime($data_hdr->Invoice_Date)) ?>" readonly>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Tanggal Jatuh Tempo</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" disabled type="text" value="<?= date('d F Y', strtotime($data_hdr->Due_Date)) ?>" readonly>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Tanggal Pajak</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm flatpickr" disabled type="text" value="<?= date('d F Y', strtotime($data_hdr->Tax_Date)) ?>" readonly>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
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
                                    <label style="font-weight: 500;">Nomor Dokumen Pajak PPh</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" type="text" value="<?= $data_hdr->TaxDocNumPPh ?: "-"; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Catatan</label>
                                    <div class="input-group input-group-sm">
                                        <textarea readonly class="form-control form-control-sm"><?= $data_hdr->Invoice_Notes ?: "-"; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Tanggal Cetak Invoice</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" disabled type="text" value="<?= date('d F Y', strtotime($data_hdr->InvoicePrintDate)) ?: "-" ?>" readonly>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Tipe Harga</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" type="text" value="<?= $data_hdr->PriceType ?>" readonly>
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
                                    <label style="font-weight: 500;">Nomor Letter of Credit (LC)</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" type="text" value="<?= $data_hdr->LC_Number ?: $emptyMsg; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Tanggal Letter of Credit (LC)</label>
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm" type="text" value="<?= $data_hdr->LC_Date ? date('d F Y', strtotime($data_hdr->LC_Date)) : $emptyMsg ?>">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Bank Letter of Credit (LC)</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" type="text" value="<?= $data_hdr->LC_Bank ?: $emptyMsg; ?>" readonly>
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
                                    <?php
                                    // Cek apakah nilai Sailing adalah "mobil", "kapal laut", atau lainnya
                                    if ($data_hdr->Sailing === "mobil") {
                                        $labelSailing = "Jenis Pengangkut:";
                                        $labelCarrier = "Nomor Polisi Kendaraan:";
                                        $placeholderCarrier = "Masukan NO Pol Kendaraan";
                                    } elseif ($data_hdr->Sailing === "kapal laut") {
                                        $labelSailing = "Jenis Pengangkut:";
                                        $labelCarrier = "Nomor Identifikasi Kapal:";
                                        $placeholderCarrier = "Masukan Nomor Identifikasi Kapal";
                                    } else {
                                        $labelSailing = "Jenis Pengangkut Lainnya:";
                                        $labelCarrier = "Nomor Identifikasi Pengangkut:";
                                        $placeholderCarrier = "Masukan Nomor Identifikasi Pengangkut";
                                    }

                                    // Nilai sailing
                                    $sailingValue = !empty($data_hdr->Sailing) ? $data_hdr->Sailing : $emptyMsg;
                                    ?>
                                    <label style="font-weight: 500;"><?= $labelSailing ?></label>
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm text-capitalize" type="text" value="<?= $sailingValue ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label id="carrierLabel" style="font-weight: 500;"><?= $labelCarrier ?></label>
                                    <div class="input-group input-group-sm">
                                        <?php
                                        // Ambil nilai NO Pol Kendaraan atau gunakan $emptyMsg jika kosong
                                        $carrierValue = !empty($data_hdr->Carrier) ? $data_hdr->Carrier : $emptyMsg;
                                        ?>
                                        <input readonly class="form-control form-control-sm text-uppercase" id="carrier" type="text" value="<?= $carrierValue ?>" placeholder="<?= $placeholderCarrier ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Tanda Pengiriman</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm" type="text" value="<?= $data_hdr->ShippingMarks ?: $emptyMsg; ?>" readonly>
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
                                    <label style="font-weight: 500;">Currency</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm only-number" type="text" value="<?= $data_hdr->Currency_ID; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 px-4 form-group">
                                    <label style="font-weight: 500;">Rate Currency</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm only-number" type="text" value="1" readonly>
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
                            <!-- Bagian detail invoice -->
                            <div class="row my-3">
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
                                                <?php foreach ($data_dtl as $index => $item): ?>
                                                    <tr>
                                                        <td class="text-center"><?= $index + 1 ?></td>
                                                        <td class="text-center"><?= $shp_numb ?></td>
                                                        <td class="text-center"><?= $item->SO_Number ?></td>
                                                        <td class="text-center"><?= $item->Item_Code ?></td>
                                                        <td class="text-center"><?= $item->Item_Description ?></td>
                                                        <td class="text-center"><?= $item->Item_Color ?></td>
                                                        <td class="text-center"><?= $item->Brand  ?></td>
                                                        <td class="text-center"><?= number_format($item->Qty, 2) ?></td>
                                                        <td class="text-center text-uppercase"><?= $item->Uom ?></td>
                                                        <td class="text-center"><?= number_format($item->UnitPrice, 2) ?></td>
                                                        <td class="text-center"><?= number_format($item->Disc_percentage, 2) ?>%</td>
                                                        <td class="text-center"><?= number_format($item->Disc_Value, 2) ?></td>
                                                        <td class="text-center"><?= number_format($item->TotalPrice, 2) ?></td>
                                                        <td class="text-center text-uppercase"><?= $item->Tax_Code1 ?></td>
                                                        <td class="text-center text-uppercase"><?= $item->Tax_Code2 ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row" style="visibility: hidden;">
                                <div class="col-12 px-4">
                                    <hr>
                                </div>
                            </div>
                            <!--  -->
                            <!-- Bagian ringkasan detail -->
                            <div class="row d-flex align-items-center my-3">
                                <div class="col-4"></div>
                                <div class="col-4 px-4 form-group">
                                    <div style="border-radius: 10px;" class="p-4 border border-1">
                                        <div class="fw-semibold text-muted fh my-2">Summary Detail</div>
                                        <table class="summary-table">
                                            <tr>
                                                <td style="width: 35%;">
                                                    <label style="font-weight: 500;" class="p-0 m-0">Amount</label>
                                                </td>
                                                <td style="width: 5%;">
                                                    <label style="font-weight: 500;" class="p-0 m-0 text-center">:</label>
                                                </td>
                                                <td style="width: 60%;">
                                                    <input class="form-control form-control-sm w-100" type="text" value="<?= number_format($data_hdr->Invoice_Amount, 2); ?>" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 35%;">
                                                    <label style="font-weight: 500;" class="p-0 m-0 ">Persentase Diskon</label>
                                                </td>
                                                <td style="width: 5%;">
                                                    <label style="font-weight: 500;" class="p-0 m-0 text-center">:</label>
                                                </td>
                                                <td style="width: 60%;">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control form-control-sm only-number" type="text" value="<?= number_format($data_hdr->TransactionDiscountPresentase, 2) ?>" readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 35%;">
                                                    <label style="font-weight: 500;" class="p-0 m-0">Tax</label>
                                                </td>
                                                <td style="width: 5%;">
                                                    <label style="font-weight: 500;" class="p-0 m-0 text-center">:</label>
                                                </td>
                                                <td style="width: 60%;">
                                                    <input class="form-control form-control-sm w-100" type="text" value="<?= number_format($data_hdr->Tax_Amount, 2); ?>" readonly>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-4"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>