<!DOCTYPE html>
<html>
<head>
    <title><?php echo $data_hdr->RR_Number ?></title>
    <style>
        body {
            padding: 0 10px 0 10px;
            font-size: 11.5px !important;
        }

        .svlk .text-italic {
            margin-bottom: 0;
        }

        .svlk span {
            font-size: 12px;
            color: #000;
        }

        td {
            vertical-align: top;
        }

        .tbl-header,
        .table-full {
            margin-top: 1rem;
            font-size: 11.5px;
        }

        .table-full {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-half {
            width: 30%;
            border-collapse: collapse;
        }

        .table-full tr>td {
            padding-left: .3rem !important;
            padding-right: .3rem !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .table-total {
            font-weight: 700;
            font-size: 11px;
            margin-top: 1rem;
        }

        .text-grey {
            color: #808080;
        }

        .text-italic {
            font-style: italic;
        }

        #container {
            font-family: Tahoma, "Trebuchet MS", sans-serif;
        }
        
        #footer {
            font-family: Tahoma, "Trebuchet MS", sans-serif;
            margin-top: 2rem;
        }

        #footer table {
            border-color: #000;
        }

        #footer table tr>th,
        #footer table tr>td {
            border-color: #000;
        }

        .header-container {
            display: inline-block;
            width: auto;
            text-align: center;
            margin-bottom: 0;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .mt-0 {
            margin-top: 0;
        }

        .pt-1 {
            padding-top: 1rem !important;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .font-size-h2 {
            font-size: 24px;
        }

        .font-size-content {
            font-size: 17px;
        }

        .bg-lightgrey {
            background-color: #F2F2F2;
        }

        .text-justify {
            text-align: justify;
        }

        .text-left {
            text-align: left;
        }

        .cell-check {
            vertical-align:bottom;
            text-align:center;
            height: 100px;
            width: 100px;
        }

        .font-size-h5 {
            font-size: 12px;
            margin-top: .2rem !important;
        }

        .border-none {
            border: none;
        }
        
        .cell-approve {
            vertical-align:bottom;
            text-align:center;
            width: 130px;
            border: none;
        }

        .mb-5 {
            margin-bottom: 1rem;
        }

        .fw-bold{
            font-weight: bold;
        }

        .table-bordered{
            border: 1px solid #000;
        }
        
        table.table-bordered td,
        table.table-bordered th
        {
            border: 1px solid #000;
        }

        table.table-bordered tr.no-border-bottom td {
            border: none;
        }

        .tbl-dtl tr>td {
            padding-top: 3px !important;
        }
    </style>
</head>
<body>
    <div id="header">
        <table class="table-full">
            <tr>
                <th style="width: 20%"><img src="<?= base_url('assets/public/image/logo-pandowo.jpg')?>" alt="Logo Pandowo" width="130"></th>
                <th style="width: 60%">
                    <h2 class="mb-0">PT Pandowo Makmur Sejahtera</h2>
                    <p style="font-size: 11px;">Kantor : Jl. Serma Marzuki RT/RW.05/02 88c, Kampung 200, Marga Jaya, Kec. Bekasi Selatan, Kota Madya Bekasi, Jawa Barat</p>
                    <p style="font-size: 11px; margin-left: 1rem; margin-right: 1rem;">Pabrik : Jalan Raya Kh Umar Rawa Ilat No. 17 Desa Mampir, Kec. Cileungsi, Kabupaten Bogor, Provinsi Jawa Barat</p>
                </th>
                <th style="width: 20%" class="text-grey svlk">
                    <img src="<?= base_url('assets/public/image/logo-svlk.jpg')?>" alt="Logo SVLK" width="130">
                    <br>
                        <span class="text-italic">Sustainable</span>
                    <br>
                        <span>VLHH-32-07-0224</span>
                </th>
            </tr>
        </table>
        <hr style="border-bottom: 4px solid #808080; padding: 0; margin: 0;">
    </div>
    <div id="container">
        <div class="text-center">
            <h3 class="header-container">Lembar Penerimaan</h3>
        </div>
        <table width="100%" class="tbl-header">
            <tr>
                <td width="15.5%">Nomor RR</td>
                <td width="3%">:</td>
                <td width="30%"><?= $data_hdr->RR_Number ?></td>
                <td width="15.5%">Tanggal RR</td>
                <td width="3%">:</td>
                <td width="20%"><?= date('d F Y', strtotime($data_hdr->RR_Date)) ?></td>
            </tr>
            <tr>
                <td>Nomor PO</td>
                <td>:</td>
                <td><?= $data_hdr->PO_Number ?></td>
                <td>Tanggal PO</td>
                <td>:</td>
                <td><?= date('d F Y', strtotime($data_hdr->PO_Date)) ?></td>
            </tr>
            <tr>
                <td>Kendaraan</td>
                <td>:</td>
                <td><?= $data_hdr->Transport_Name ?></td>
                <td>Nopol Kendaraan</td>
                <td>:</td>
                <td><?= $data_hdr->No_Police_Vehicle ?></td>
            </tr>
            <tr>
                <td>Terima Dari</td>
                <td>:</td>
                <td><?= $data_hdr->Account_Name ?></td>
                <td>Catatan</td>
                <td>:</td>
                <td><?= $data_hdr->RR_Notes ? $data_hdr->RR_Notes : '-' ?></td>
            </tr>
        </table>
        
        <table class="table-full mb-5 table-bordered tbl-dtl">
            <thead>
                <tr class="bg-lightgrey">
                    <th>No</th>
                    <th>Item Description</th>
                    <th>Unit</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <?php
                    $total_amount = 0;
                    $count = count($data_dtl);
                    $no = 1;
                    foreach ($data_dtl as $val) {
                        $iteration = $no++;
                ?>
                <tr>
                    <td style="border-top: none; border-bottom: none; border-right: 1px solid black; border-left: 1px solid black;" class="text-center"><?= $iteration; ?>.</td>
                    <td style="border-top: none; border-bottom: none; border-right: 1px solid black; border-left: 1px solid black;" class="text-left"><?= $val->Item_Name . ' [' . $val->Item_Code . ']' ?></td>
                    <td style="border-top: none; border-bottom: none; border-right: 1px solid black; border-left: 1px solid black;" class="text-left"><?= $val->Uom ?></td>
                    <td style="border-top: none; border-bottom: none; border-right: 1px solid black; border-left: 1px solid black;" class="text-right"><?= number_format($val->Qty, 2) ?></td>
                    <td style="border-top: none; border-bottom: none; border-right: 1px solid black; border-left: 1px solid black;" class="text-right"><?= number_format($val->Unit_Price, 4) ?></td>
                    <td style="border-top: none; border-bottom: none; border-right: 1px solid black; border-left: 1px solid black;" class="text-right"><?= number_format($val->Unit_Price * $val->Qty, 4) ?></td>
                </tr>
                <?php if ($count == $iteration): ?>
                    <tr>
                        <td style="border-top: none; border-bottom: none; border-right: 1px solid black; border-left: 1px solid black; height: 100px;"></td>
                        <td style="border-top: none; border-bottom: none; border-right: 1px solid black; border-left: 1px solid black; height: 100px;"></td>
                        <td style="border-top: none; border-bottom: none; border-right: 1px solid black; border-left: 1px solid black; height: 100px;"></td>
                        <td style="border-top: none; border-bottom: none; border-right: 1px solid black; border-left: 1px solid black; height: 100px;"></td>
                        <td style="border-top: none; border-bottom: none; border-right: 1px solid black; border-left: 1px solid black; height: 100px;"></td>
                        <td style="border-top: none; border-bottom: none; border-right: 1px solid black; border-left: 1px solid black; height: 100px;"></td>
                    </tr>
                <?php endif; ?>
                <?php
                        $total_amount += $val->Unit_Price * $val->Qty;
                    }
                        $discont    = 0;
                        $tax        = 0;
                        $grandtotal = $total_amount + $tax + $discont;
                ?>
                <!-- <tr>
                    <td class="fw-bold" colspan="5">Total</td>
                    <td class="text-right">IDR <?= number_format($total_amount, 4) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="fw-bold" colspan="5">Discount</td>
                    <td class="text-right">IDR <?= number_format($discont, 4) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="fw-bold" colspan="5">Total Tax</td>
                    <td class="text-right">IDR <?= number_format($tax, 4) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="fw-bold" colspan="5">Grand Total</td>
                    <td class="text-right">IDR <?= number_format($grandtotal, 4) ?></td>
                    <td></td>
                </tr> -->
            </tbody>
        </table>
        <table class="table-total" width="100%">
            <tr>
                <td class="" width="70%"></td>
                <td class="" width="15%">Grand Total</td>
                <td class="text-right " width="20%"><?php echo $data_hdr->Currency . ' ' . number_format($grandtotal,4,'.',',') ?></td>
            </tr>
        </table>
        <!-- <span style="padding-top: 100px;">Note : This sheet as an evidence, must be submitted when billing</span> -->
    </div>
    
    <div id="footer">
        <table class="table-half" border="1" align="right">
            <tr>
                <th>Approve</th>
            </tr>
            <tr class="border-none">
                <td class="text-center border-none">
                    <?php
                        $height = 120;
                        if ($data_hdr->Approval_Status == 1) {
                            // $height = 0;
                    ?>
                        <!-- <img src="<?= base_url('assets/public/image/check-mark.png')?>" alt="Check Approve" width="130"> -->
                    <?php
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="cell-approve" style="height: <?php echo $height ?>">Anton Seliyanto</td>
            </tr>
        </table>
    </div>
    <!-- <div id="footer">
        <table class="table-full mb-5">
            <tr>
                <th>Acknowledge By</th>
                <th>Checked By</th>
                <th>Delivered By</th>
                <th>Received By</th>
            </tr>
            <tr class="border-none">
                <td class="text-center border-none">
                    <?php
                        $height1 = 70;
                        if (0 == 1) {
                            $height1 = 0;
                    ?>
                        <img src="<?= base_url('assets/public/image/check-mark.png')?>" alt="Check Approve" width="130">
                    <?php
                        }
                    ?>
                </td>
                <td class="text-center border-none">
                    <?php
                        $height2 = 70;
                        if (0 == 1) {
                            $height2 = 0;
                    ?>
                        <img src="<?= base_url('assets/public/image/check-mark.png')?>" alt="Check Approve" width="130">
                    <?php
                        }
                    ?>
                </td>
                <td class="text-center border-none">
                    <?php
                        $height3 = 70;
                        if (0 == 1) {
                            $height3 = 0;
                    ?>
                        <img src="<?= base_url('assets/public/image/check-mark.png')?>" alt="Check Approve" width="130">
                    <?php
                        }
                    ?>
                </td>
                <td class="text-center border-none">
                    <?php
                        $height4 = 70;
                        if (0 == 1) {
                            $height4 = 0;
                    ?>
                        <img src="<?= base_url('assets/public/image/check-mark.png')?>" alt="Check Approve" width="130">
                    <?php
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="cell-approve" style="height: <?php echo $height1 ?>">(_______________)</td>
                <td class="cell-approve" style="height: <?php echo $height2 ?>">(_______________)</td>
                <td class="cell-approve" style="height: <?php echo $height3 ?>">(_______________)</td>
                <td class="cell-approve" style="height: <?php echo $height4 ?>">(_______________)</td>
            </tr>
        </table>
    </div> -->
</body>
</html>
