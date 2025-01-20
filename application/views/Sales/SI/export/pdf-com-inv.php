<!DOCTYPE html>
<html>

<head>
    <title><?= $data_hdr->ShipInst_Number; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0 10px;
            font-size: 10px;
        }

        .table-layout {
            width: 100%;
            border-collapse: collapse;
        }

        .table-layout td {
            vertical-align: top;
            padding: 5px;
        }

        .table-header {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 4px solid #808080;
        }

        .text-center {
            text-align: center;
        }

        .text-start {
            text-align: left;
        }

        .text-justify {
            text-align: justify;
        }

        .text-end {
            text-align: right;
        }

        .m-0 {
            margin: 0;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .my-2 {
            margin: 1rem 0;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        .heading-2 {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .table-bordered {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table-bordered th,
        .table-bordered td {
            border: 0.8px solid #000000;
            padding: 8px;
            text-align: center;
        }

        .table-light th,
        .table-light td {
            background-color: #f8f9fa;
        }

        .mt-5 {
            margin-top: 3rem;
        }

        .signature {
            text-align: center;
            margin-top: 18px;
        }

        .signature-approve {
            display: inline-block;
            text-align: center;
        }

        .underline {
            display: inline-block;
            border-bottom: 1px solid black;
            padding-bottom: 2px;
            width: 200px;
            /* Sesuaikan dengan panjang teks terpanjang */
        }
    </style>
</head>

<body>
    <div id="header">
        <table class="table-full">
            <tr>
                <th style="width: 20%">
                    <img src="<?= base_url('assets/public/image/logo-pandowo.jpg') ?>" alt="Logo Pandowo" width="130" />
                </th>
                <th style="width: 60%">
                    <h2 class="mb-0">PT Pandowo Makmur Sejahtera</h2>
                    <p style="font-size: 11px">
                        Kantor: Jl. Serma Marzuki RT/RW.05/02 88c, Kampung 200, Marga
                        Jaya, Kec. Bekasi Selatan, Kota Madya Bekasi, Jawa Barat
                    </p>
                    <p style="font-size: 11px; margin-left: 1rem; margin-right: 1rem">
                        Pabrik: Jalan Raya Kh Umar Rawa Ilat No. 17 Desa Mampir, Kec.
                        Cileungsi, Kabupaten Bogor, Provinsi Jawa Barat
                    </p>
                </th>
                <th style="width: 20%" class="text-grey svlk">
                    <img src="<?= base_url('assets/public/image/logo-svlk.jpg') ?>" alt="Logo SVLK" width="130" />
                    <br />
                    <span style="font-style: italic;">Sustainable</span>
                    <br />
                    <span>VLHH-32-07-0224</span>
                </th>
            </tr>
        </table>
        <hr style="border-bottom: 4px solid #808080; padding: 0; margin: 0" />
    </div>

    <table class="table-layout">
        <tr>
            <td style="width: 50%">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 30%" class="text-start">Comercial Invoice</td>
                        <td style="width: 10%" class="text-end">:</td>
                        <td style="width: 50%"><?= $data_hdr->ShipInst_Number; ?></td>
                    </tr>
                    <tr>
                        <td class="text-start">Nama Customer</td>
                        <td class="text-end">:</td>
                        <td> <?= $data_hdr->Account_Name; ?></td>
                    </tr>
                    <tr>
                        <td class="text-start">NO. Purchase Order</td>
                        <td class="text-end">:</td>
                        <td>
                            <?php
                            if (!empty($po_numbers)) {
                                // Gabungkan nomor PO dengan pemisah koma
                                echo implode(', ', $po_numbers);
                            } else {
                                echo 'Tidak ada nomor PO';
                            }
                            ?>
                        </td>
                    </tr>

                </table>
            </td>
            <td style="width: 50%; text-align: right">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 30%" class="text-start">Alamat</td>
                        <td style="width: 10%" class="text-end">:</td>
                        <td style="width: 50%" class="text-start">
                            <?= $data_hdr->Address; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start">NO. Sales Order</td>
                        <td class="text-end">:</td>
                        <td class="text-start">
                            <?php
                            $so_numbers = [];
                            foreach ($data_dtl as $row) {
                                $so_numbers[] = $row->SO_Number;
                            }
                            echo implode(', ', array_unique($so_numbers));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start">Pengangkut</td>
                        <td class="text-end">:</td>
                        <td class="text-start text-capitalize" style="text-transform: uppercase;"><?= $data_hdr->Sailing ?: '-'; ?></td>
                    </tr>
                    <tr>
                        <td class="text-start">NO. Pol Kendaraan</td>
                        <td class="text-end">:</td>
                        <td class="text-start" style="text-transform: uppercase;"><?= $data_hdr->Carrier; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="mt-2">
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Description</th>
                    <th>Color</th>
                    <th>Brand</th>
                    <th>Dimension</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($data_dtl as $row) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td style="text-align: left;"><?= $row->Item_Name_Customer; ?></td>
                        <td><?= $row->Item_Color ?: '-'; ?></td>
                        <td><?= $row->Brand ?: '-'; ?></td>
                        <td><?php
                            $legth = 0;
                            $width = 0;
                            $height = 0;
                            if (!empty($row->Item_Length)) {
                                $legth = floatval($this->m_wh->convertLength(floatval($row->Item_Length), $row->LWH_Unit, 'MM'));
                            }
                            if (!empty($row->Item_Width)) {
                                $width = floatval($this->m_wh->convertLength(floatval($row->Item_Width), $row->LWH_Unit, 'MM'));
                            }
                            if (!empty($row->Item_Height)) {
                                $height = floatval($this->m_wh->convertLength(floatval($row->Item_Height), $row->LWH_Unit, 'MM'));
                            }
                            if ($legth + $width + $height == 0) {
                                echo '-';
                            } else {
                                echo  $height . ' x ' . $width . ' x ' . $legth . ' MM';
                            }
                            ?></td>
                        <td><?= floatval($row->Qty_Shiped) ?></td>
                        <td><?= number_format($row->Item_Price, 2); ?></td>
                        <td><?= number_format($row->Amount_si, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Total Quantity:</th>
                    <th>
                        <?php
                        $total_qty = 0;
                        foreach ($data_dtl as $row) {
                            $total_qty += floatval($row->Qty_Shiped);
                        }
                        echo $total_qty;
                        ?>
                    </th>
                    <th colspan="1" class="text-end">Total Harga:</th>
                    <th>
                        <?php
                        $total_amount = 0;
                        foreach ($data_dtl as $row) {
                            $total_amount += $row->Amount_si;
                        }
                        echo number_format(round($total_amount), 2);
                        ?>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="signature">
        <table class="table-layout">
            <tr>
                <td style="width: 50%;" class="text-center">
                    <p><strong>Dibuat:</strong></p>
                    <br /><br />
                    <p><?= $data_hdr->Created_By; ?></p>
                </td>
                <td style="width: 50%;" class="text-center">
                    <p><strong>Disetujui:</strong></p>
                    <br /><br />
                    <div class="signature-approve">
                        <div class="underline">Widodo</div><br>
                        <div>Manager Operasional</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>