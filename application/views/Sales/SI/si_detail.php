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
            <div class="card bd-callout shadow add-data">
                <form method="post" enctype="multipart/form-data" action="#">
                    <!-- Kumpulan inputan yang di hidden -->
                    <input value="<?= $data_hdr->SysId ?>" type="hidden">
                    <div class="card-header">
                        <h2 class="card-title mt-2"><?= $page_title ?></h2>
                        <div class="card-tools">
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm" id="back" title="back" data-toggle="tooltip">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $emptyMsg = "Khusus untuk transaksi ekspor"; // Variabel pesan jika nilai kosong
                        ?>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">NO. Shipping:</label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-sm" readonly value="<?= $data_hdr->ShipInst_Number ?: $emptyMsg ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Shipping:</label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-sm" readonly value="<?= date('d F Y', strtotime($data_hdr->ShipInst_Date)) ?: $emptyMsg ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nama Customer:</label>
                                <div class="input-group input-group-sm">
                                    <input class="form-control form-control-sm" readonly value="<?= $data_hdr->Account_Name ?: $emptyMsg ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Multiple SO Document:</label>
                                <div class="input-group input-group-sm input-group-flex">
                                    <?php
                                    $soNumbers = [];
                                    foreach ($data_dtl as $index => $detail) :
                                        if (!in_array($detail->SO_Number, $soNumbers)) :
                                            $soNumbers[] = $detail->SO_Number;
                                    ?>
                                            <span class="badge badge-success">
                                                <?= htmlspecialchars($detail->SO_Number ?: $emptyMsg) ?>
                                                <div class="badge-number"><?= $index + 1 ?></div>
                                            </span>
                                    <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data_dtl as $row) : ?>
                                                <tr>
                                                    <td class="text-center vertical-align-middle"><?= $row->SO_Number ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle"><?= $row->Item_Code ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle"><?= $row->Item_Name ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle"><?= $row->Note ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle"><?= $row->Item_Color ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle"><?= $row->Brand ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle"><?= $row->Dimension_Info ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle"><?= $row->Weight_Info ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle"><?= $row->Qty_order ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle"><?= $row->Qty_ost_so ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle"><?= floatval($row->Qty_Shiped) ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle"><?= strtoupper($row->Uom ?: "-") ?></td>
                                                    <td class="text-center vertical-align-middle"><?= floatval($row->Secondary_Qty) ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle"><?= $row->Secondary_Uom ?: "-" ?></td>
                                                    <td class="text-center vertical-align-middle">
                                                        <?php foreach ($row->Warehouse_Qty as $warehouse) : ?>
                                                            <div class="input-group input-group-sm mb-1">
                                                                <input class="form-control form-control-sm text-center" readonly value="<?= $warehouse['warehouse_code'] ?: $emptyMsg ?>">
                                                                <input class="ml-2 form-control form-control-sm text-center" readonly value="<?= $warehouse['qty'] ?: $emptyMsg ?>">
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex align-items-center">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Alamat Pengiriman:</label>
                                <div class="input-group input-group-sm">
                                    <textarea required class="form-control form-control-sm" placeholder="Alamat customer..." rows="3" readonly data-sysid=""><?= $data_hdr->Address ?: $emptyMsg ?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Pengiriman:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly disabled required class="form-control form-control-sm" type="text" value="<?= date('d F Y', strtotime($data_hdr->ExpectedDeliveryDate)) ?: $emptyMsg ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tempat Muat:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm text-capitalize" type="text" value="<?= $data_hdr->PortOfLoading ?: $emptyMsg ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tempat Pengiriman:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm text-capitalize" type="text" value="<?= $data_hdr->PlaceOfDelivery ?: $emptyMsg ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
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
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label id="carrierLabel" style="font-weight: 500;"><?= $labelCarrier ?></label>
                                <div class="input-group input-group-sm">
                                    <?php
                                    // Ambil nilai NO Pol Kendaraan atau gunakan $emptyMsg jika kosong
                                    $carrierValue = !empty($data_hdr->Carrier) ? $data_hdr->Carrier : $emptyMsg;
                                    ?>
                                    <input readonly class="form-control form-control-sm text-uppercase" id="carrier" type="text" value="<?= $carrierValue ?>" placeholder="<?= $placeholderCarrier ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row d-flex align-items-center">
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Pihak Penerima Pemberitahuan:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" value="<?= $data_hdr->NotifeParty ?: $emptyMsg ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Alamat Pihak Penerima Pemberitahuan:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" value="<?= $data_hdr->NotifePartyAddress ?: $emptyMsg ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanda Pengiriman:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" value="<?= $data_hdr->ShippingMarks ?: $emptyMsg ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex align-items-center">
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nomor Letter of Credit:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" value="<?= $data_hdr->LCNo ?: $emptyMsg ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal Letter of Credit:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" value="<?= $data_hdr->LCDate ? date('d F Y', strtotime($data_hdr->LCDate)) : $emptyMsg ?>">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Bank Letter of Credit:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" value="<?= $data_hdr->LCBank ?: $emptyMsg ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nomor PEB:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" value="<?= $data_hdr->PEB_Number ?: $emptyMsg ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal PEB:</label>
                                <div class="input-group input-group-sm">
                                    <input disabled class="form-control form-control-sm" type="text" value="<?= isset($data_hdr->PEB_Date) ? $data_hdr->PEB_Date : $emptyMsg ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Penerima PEB:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" value="<?= $data_hdr->PEB_Receiver ?: $emptyMsg ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Negara PEB:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" value="<?= $data_hdr->PEB_Country ?: $emptyMsg ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tipe BC:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" value="<?= $data_hdr->BC_Type ?: $emptyMsg ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Nomer BC:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" value="<?= $data_hdr->BC_Number ?: $emptyMsg ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 px-4 form-group">
                                <label style="font-weight: 500;">Tanggal BC:</label>
                                <div class="input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" value="<?= isset($data_hdr->BC_Date) ? date('d F Y', strtotime($data_hdr->BC_Date)) : $emptyMsg ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>