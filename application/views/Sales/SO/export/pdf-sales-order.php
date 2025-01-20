<!DOCTYPE html>
<html>

<head>
    <title><?= $data_hdr->SO_Number; ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0 10px;
            font-size: 11.5px;
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
            padding: 0.5rem;
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
            margin-top: 50px;
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
                    <img
                        src="<?= base_url('assets/public/image/logo-pandowo.jpg') ?>"
                        alt="Logo Pandowo"
                        width="130" />
                </th>
                <th style="width: 60%">
                    <h2 class="mb-0">PT Pandowo Makmur Sejahtera</h2>
                    <p style="font-size: 11px">
                        Kantor : Jl. Serma Marzuki RT/RW.05/02 88c, Kampung 200, Marga
                        Jaya, Kec. Bekasi Selatan, Kota Madya Bekasi, Jawa Barat
                    </p>
                    <p style="font-size: 11px; margin-left: 1rem; margin-right: 1rem">
                        Pabrik : Jalan Raya Kh Umar Rawa Ilat No. 17 Desa Mampir, Kec.
                        Cileungsi, Kabupaten Bogor, Provinsi Jawa Barat
                    </p>
                </th>
                <th style="width: 20%" class="text-grey svlk">
                    <img
                        src="<?= base_url('assets/public/image/logo-svlk.jpg') ?>"
                        alt="Logo SVLK"
                        width="130" />
                    <br />
                    <span style="font-style: italic;">Sustainable</span>
                    <br />
                    <span>VLHH-32-07-0224</span>
                </th>
            </tr>
        </table>
        <hr style="border-bottom: 4px solid #808080; padding: 0; margin: 0" />
    </div>

    <div>
        <table class="table-layout">
            <tr>
                <td style="width: 70%">
                    <table style="width: 100%;">
                        <tr>
                            <!-- Contact_Name, c.Job_title -->
                            <td style="width: 30%;" class="text-start">Kepada Yth</td>
                            <td style="width: 10%;" class="text-end">:</td>
                            <td style="width: 60%;"><?= $data_hdr->Job_title . " " . $data_hdr->Contact_Name . " " . $data_hdr->Customer_Name; ?></td>
                        </tr>
                        <tr>
                            <td class="text-start">Alamat</td>
                            <td class="text-end">:</td>
                            <td><?= $data_hdr->Customer_Address; ?></td>
                        </tr>
                        <tr>
                            <td class="text-start">NO. Sales Order</td>
                            <td class="text-end">:</td>
                            <td><?= $data_hdr->SO_Number; ?></td>
                        </tr>
                        <tr>
                            <td class="text-start">NO. Purchase Order</td>
                            <td class="text-end">:</td>
                            <td><?= $data_hdr->PO_Number; ?></td>
                        </tr>
                        <tr>
                            <td class="text-start">TGL. Purchase Order</td>
                            <td class="text-end">:</td>
                            <td><?= date('d F Y', strtotime($data_hdr->PO_Date)); ?></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 30%" class="text-end">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 30%;" class="text-start">Tanggal</td>
                            <td style="width: 10%;" class="text-end">:</td>
                            <td style="width: 60%;" class=""><?= date('d F Y', strtotime($data_hdr->SO_Date)); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="mt-4">
        <table class="table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No.</th>
                    <th>Description</th>
                    <th>Color</th>
                    <th>Brand</th>
                    <th>Quantity</th>
                    <th>UOM</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Disc Value</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($data_dtl as $row) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row->Item_Name; ?></td>
                        <td><?= !empty($row->Item_Color) ? $row->Item_Color : ''; ?></td>
                        <td><?= !empty($row->Brand) ? $row->Brand : ''; ?></td>
                        <td><?= number_format($row->Qty); ?></td>
                        <td style="text-transform: uppercase;"><?= !empty($row->Uom) ? $row->Uom : ''; ?></td>
                        <td><?= number_format($row->Item_Price, 2); ?></td>
                        <td><?= $row->Discount != 0 ? number_format($row->Discount) . '%' : '-'; ?></td>
                        <td><?= $row->Discount != 0 ? number_format($row->Discount_Amount, 2) : '-';; ?></td>
                        <td><?= number_format($row->Amount_Detail, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="4" class="text-end">Total Quantity:</th>
                    <th>
                        <?php
                        $total_qty = 0;
                        foreach ($data_dtl as $row) {
                            $total_qty += $row->Qty;
                        }
                        echo number_format($total_qty);
                        ?>
                    </th>
                    <th></th>
                    <th colspan="3" class="text-end">Total Harga:</th>
                    <th><?= number_format($data_hdr->Netto_Amount, 2); ?></th>
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