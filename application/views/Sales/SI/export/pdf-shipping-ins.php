<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Surat Jalan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        /* Container */
        .container-fluid {
            width: 100%;
        }

        /* Border and padding */
        .border-bottom {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        /* Custom dashed border */
        .dashed {
            border-bottom: 1px dotted #2f437f;
            margin-bottom: 30px;
            padding-bottom: 2px;
            /* Menambahkan jarak antara border dan teks */
            color: #263238;
        }

        /* Text */
        .text-primary {
            color: #2f437f;
        }

        .h4 {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .fs-4 {
            font-size: 1.5rem;
        }

        .nowrap {
            white-space: nowrap;
        }

        /* Image */
        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        /* Table styles without border */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
            /* No border for the table */
        }

        .table td {
            padding: 0.2rem;
            /* border: none; */
            /* No border for table cells */
        }

        /* Alignment */
        .align-middle {
            vertical-align: middle;
        }

        .bordered-text::before {
            content: "(";
            margin-right: 5px;
        }

        .bordered-text::after {
            content: ")";
            margin-left: 5px;
        }

        .bordered-div {
            border-bottom: 1px dotted #2f437f;
            width: 80%;
            margin: 0 auto;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div
            class="container-fluid"
            style="padding-bottom: 1rem; margin-bottom: 1.5rem">
            <table class="table" style="border-spacing: 0">
                <tr class="align-middle">
                    <td width="100%">
                        <table style="border-spacing: 0">
                            <tr>
                                <td style="width: 20%">
                                    <img
                                        src="<?= base_url('assets/public/image/logo-pandowo.jpg') ?>"
                                        alt="Company Logo"
                                        style="max-width: 100%; height: auto" />
                                </td>
                                <td style="width: 40%">
                                    <p
                                        class="text-primary nowrap"
                                        style="
                        font-size: 1.2rem;
                        margin: 0;
                        font-weight: bold;
                        font-family: 'Times New Roman', Times, serif;
                      ">
                                        Pt. Pandowo Makmur Sejahtera
                                    </p>
                                    <table style="width: 100%; border-spacing: 0">
                                        <tr>
                                            <td style="width: 90%">
                                                <p
                                                    class="text-primary"
                                                    style="font-size: 0.8rem; margin: 0; margin-top: 3%">
                                                    Jalan Raya KH. Umar Rawa Ilat No. 17, <br />
                                                    Desa Mampir, Kec. Cileungsi, <br />
                                                    Kabupaten Bogor 16820 <br />
                                                    Telp. 021 8047 6155
                                                </p>
                                            </td>
                                            <td style="width: 10%; padding: 0">
                                                <p
                                                    class="text-primary"
                                                    style="font-size: 0.8rem; margin-top: 0; padding: 0">
                                                    Tuan
                                                    <span
                                                        style="
                                display: block;
                                border-bottom: 1px solid #2f437f;
                                margin: 2px 0;
                              "></span>
                                                    Toko
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width: 40%">
                                    <div
                                        class="dashed"
                                        style="
                        font-size: 0.8rem;
                        margin-bottom: 10px;
                        margin-top: 30px;
                      ">
                                        <?= date('d F Y', strtotime($data_hdr->ShipInst_Date)); ?>
                                    </div>
                                    <div
                                        class="dashed"
                                        style="font-size: 1rem; margin-bottom: 10px">
                                        <?= $data_hdr->Account_Name; ?>
                                    </div>
                                    <div
                                        class="dashed"
                                        style="
                        font-size: 0.8rem;
                        margin-bottom: 10px;
                        text-align: justify;
                      ">
                                        <?= $data_hdr->Address; ?>
                                        <!-- Jl. Dusun Bayuyon, Desa Dawuan Barat, -->
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="align-middle">
                    <td>
                        <div class="text-primary" style="font-size: 1.3rem">
                            <span> SURAT JALAN</span>

                            <span style="font-size: 1rem">No</span>
                            <span class="dashed"><?= $data_hdr->ShipInst_Number; ?></span>
                        </div>
                    </td>
                </tr>
                <tr class="align-middle">
                    <td>
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 60%">
                                    <div class="text-primary" style="font-size: 0.8rem">
                                        Kami kirimkan barang-barang tersebut dibawah ini dengan
                                        kendaraan
                                    </div>
                                </td>
                                <td style="width: 20%; text-align: center">
                                    <div style="font-size: 0.8rem">
                                        <span class="dashed" style="text-transform: uppercase;"><?= $data_hdr->Sailing; ?></span>
                                    </div>
                                </td>
                                <td style="width: 20%; text-align: center">
                                    <div class="text-primary" style="font-size: 0.8rem">
                                        <span style="margin-top: 100px">No.</span>
                                        <span class="dashed" style="text-transform: uppercase;"><?= $data_hdr->Carrier; ?></span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!-- Existing layout code -->
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #2f437f;">
                <thead class="text-primary">
                    <tr style="border: 1px solid #2f437f">
                        <td style="border: 1px solid #2f437f; padding: 5px; text-align: center;">
                            SATUAN POKOK
                        </td>
                        <td style="border: 1px solid #2f437f; padding: 5px; text-align: center;">
                            SATUAN KETERANGAN
                        </td>
                        <td style="border: 1px solid #2f437f; padding: 5px; text-align: center;">
                            NAMA BARANG
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalSatuanPokok = 0;
                    $totalSatuanSecondary = 0;
                    $totalQty = 0;  // Variable to store the total quantity
                    $rowCount = 0;

                    // Loop untuk menampilkan data yang tersedia
                    foreach ($data_dtl as $item) : ?>
                        <tr style="border: 1px solid #2f437f">
                            <?php if ($item->Uom == 'pcs') : ?>
                                <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem;"><?php echo floatval($item->Qty_Shiped); ?> <?= ucwords($item->Uom) ?></td>
                                <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem;"><?= $this->help->roundToFourDecimals((floatval($item->Item_Length) * floatval($item->Item_Width) * (floatval($item->Item_Height)) / 1000000) * floatval($item->Qty_Shiped)) ?> m3</td>
                                <?php $totalSatuanPokok += floatval($item->Qty_Shiped); ?>
                                <?php $totalSatuanSecondary +=  number_format((floatval($item->Item_Height) * floatval($item->Item_Width) * (floatval($item->Item_Length)) / 1000000) * floatval($item->Qty_Shiped), 4); ?>
                            <?php elseif ($item->Uom == 'm3'): ?>

                                <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem;" class="text-center">
                                    <?= (floatval($item->Secondary_Qty) == 0) ? '-' : floatval($item->Secondary_Qty) . ' ' . ucwords($item->Secondary_Uom)  ?>
                                </td>
                                <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem;"><?php echo floatval($item->Qty_Shiped); ?> <?= strtolower($item->Uom) ?></td>
                                <?php $totalSatuanPokok += floatval($item->Secondary_Qty); ?>
                                <?php $totalSatuanSecondary += floatval($item->Qty_Shiped); ?>
                            <?php else : ?>
                                <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem;"><?php echo floatval($item->Qty_Shiped); ?> <?= ucwords($item->Uom) ?></td>
                                <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem;">
                                    <?= (empty($item->Secondary_Qty)) ? '-' : floatval($item->Secondary_Qty) . ' ' . ucwords($item->Secondary_Uom)  ?>
                                </td>
                                <?php $totalSatuanPokok += floatval($item->Qty_Shiped); ?>
                                <?php $totalSatuanSecondary += floatval($item->Secondary_Qty); ?>
                            <?php endif; ?>
                            <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem;">
                                <?= $item->Item_Name_Customer ?>
                            </td>
                        </tr>
                    <?php
                        // $totalQty += $item->Qty_Shiped;  // Add each quantity to total
                        $rowCount++;
                    endforeach;

                    // Jika data kurang dari 10, tambahkan baris kosong
                    for ($i = $rowCount; $i < 10; $i++) : ?>
                        <tr style="border: 1px solid #2f437f">
                            <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem; text-align: center;">
                                &nbsp;
                            </td>
                            <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem; text-align: center;">
                                &nbsp;
                            </td>
                            <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem;">
                                &nbsp;
                            </td>
                        </tr>
                    <?php endfor; ?>
                    <!-- Baris untuk menampilkan total jumlah di kolom "BANYAKNYA" -->
                    <tr style="border: 1px solid #2f437f; font-weight: bold;">
                        <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem; text-align: center;">
                            Total: <?= $totalSatuanPokok ?>
                        </td>
                        <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem; text-align: center;">
                            Total: <?= $totalSatuanSecondary ?>
                        </td>
                        <td style="border: 1px solid #2f437f; padding: 5px; font-size: 0.8rem;">
                            &nbsp;
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- footer -->
            <table class="table" style="border-spacing: 0; width: 100%;">
                <tr>
                    <td style="width: 10%;"></td>
                    <td style="width: 26.67%; text-align: center; font-size: 0.8rem;" class="text-primary">
                        Tanda Terima
                        <br /><br />
                        <br /><br />
                        <span class="bordered-text">
                            <div class="bordered-div">&nbsp;</div>
                        </span>
                        <!-- Tempat untuk tanda tangan -->
                    </td>
                    <td style="width: 26.67%; text-align: center;">
                        <img src="<?= base_url('assets/public/image/logo-svlk.jpg') ?>" alt="Logo SVLK" width="130" />
                        <span style="font-size: 0.8rem; font-weight: bold; font-style: italic;">Sustainable</span>
                        <br />
                        <span style="font-size: 0.8rem; font-family: 'Times New Roman', Times, serif;;">VLHH-32-07-0224</span>
                    </td>
                    <td style="width: 26.67%; text-align: center; font-size: 0.8rem;" class="text-primary">
                        Hormat Kami
                        <br /><br />
                        <br /><br />
                        <span class="bordered-text">
                            <div class="bordered-div">&nbsp;</div>
                        </span>
                        <!-- Tempat untuk tanda tangan -->
                    </td>
                    <td style="width: 10%;"></td>
                </tr>
            </table>
        </div>
        <!--  -->
    </div>
</body>

</html>