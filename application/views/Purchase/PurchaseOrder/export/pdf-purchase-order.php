<!DOCTYPE html>
<html>

<head>
    <title><?php echo $data_hdr->Doc_No; ?></title>
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
            border-bottom: 2px solid #000;
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
            vertical-align: bottom;
            text-align: center;
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
            vertical-align: bottom;
            text-align: center;
            width: 130px;
            border: none;
        }
    </style>
</head>

<body>
    <div id="header">
        <table class="table-full">
            <tr>
                <th style="width: 20%"><img src="<?= base_url('assets/public/image/logo-pandowo.jpg') ?>" alt="Logo Pandowo" width="130"></th>
                <th style="width: 60%">
                    <h2 class="mb-0">PT Pandowo Makmur Sejahtera</h2>
                    <p style="font-size: 11px;">Kantor : Jl. Serma Marzuki RT/RW.05/02 88c, Kampung 200, Marga Jaya, Kec. Bekasi Selatan, Kota Madya Bekasi, Jawa Barat</p>
                    <p style="font-size: 11px; margin-left: 1rem; margin-right: 1rem;">Pabrik : Jalan Raya Kh Umar Rawa Ilat No. 17 Desa Mampir, Kec. Cileungsi, Kabupaten Bogor, Provinsi Jawa Barat</p>
                </th>
                <th style="width: 20%" class="text-grey svlk">
                    <img src="<?= base_url('assets/public/image/logo-svlk.jpg') ?>" alt="Logo SVLK" width="130">
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
            <h4 class="header-container">PURCHASE ORDER</h4>
            <br>
            <p class="font-size-h5">No : <?php echo $data_hdr->Doc_No; ?></p>
        </div>
        <table width="100%" class="tbl-header">
            <tr>
                <td width="13.5%">No. Dokumen</td>
                <td width="3%">:</td>
                <td width="35%"><?php echo $data_hdr->Doc_No; ?></td>
                <td width="15.5%">Tanggal</td>
                <td width="3%">:</td>
                <td width="23%"><?php echo date('d F Y', strtotime($data_hdr->Doc_Date)); ?></td>
            </tr>
            <tr>
                <td>Vendor</td>
                <td>:</td>
                <td><b><?php echo $data_hdr->Account_Name; ?></b></td>
                <td>CP</td>
                <td>:</td>
                <td><?php echo $data_hdr->Contact_Name; ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?php echo $data_hdr->Address; ?></td>
                <td>Mata Uang</td>
                <td>:</td>
                <td><?php echo $data_hdr->Currency; ?></td>
            </tr>
            <tr>
                <td>ETA</td>
                <td>:</td>
                <td><?php echo date('d F Y', strtotime($data_hdr->ETA)); ?></td>
                <td>Rate Currency</td>
                <td>:</td>
                <td><?php echo $data_hdr->Rate . ' (' . $data_hdr->Currency . ')'; ?></td>
            </tr>
            <tr>
                <td>ETD</td>
                <td>:</td>
                <td><?php echo date('d F Y', strtotime($data_hdr->ETD)); ?></td>
                <td>Import</td>
                <td>:</td>
                <td><?php echo $data_hdr->IsImport == 0 ? 'Tidak' : "Ya"; ?></td>
            </tr>
            <tr>
                <td>Catatan</td>
                <td>:</td>
                <td><?php echo $data_hdr->Note ? $data_hdr->Note : '-'; ?></td>
            </tr>
        </table>

        <table border="1" class="table-full">
            <thead>
                <tr class="bg-lightgrey">
                    <th>No</th>
                    <th>Kode Item</th>
                    <th>Deskripsi</th>
                    <th>Unit</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga</th>
                    <th>Note</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <?php
                $total_amount = 0;
                $no = 1;
                foreach ($data_dtl as $val) {
                ?>
                    <tr>
                        <td class="text-right"><?php echo $no ?>.</td>
                        <td class="text-left"><?php echo $val->Item_Code ?></td>
                        <td class="text-left"><?php echo $val->Item_Name ?></td>
                        <td class="text-left"><?php echo $val->Uom ?></td>
                        <td class="text-right"><?php echo number_format($val->Qty, 2, '.', ',') ?></td>
                        <td class="text-right"><?php echo number_format($val->Unit_Price, 2, '.', ',') ?></td>
                        <td class="text-right"><?php echo number_format($val->Total_Price, 2, '.', ',') ?></td>
                        <td><?php echo $val->Remark ? $val->Remark : '-' ?></td>
                    </tr>
                <?php
                    $no++;
                    $total_amount += $val->Total_Price;
                }
                ?>
            </tbody>
        </table>

        <table class="table-total" width="100%">
            <tr>
                <td width="65%"></td>
                <td width="15%">Total Harga</td>
                <td width="20%" class="text-right"><?php echo $data_hdr->Currency . ' ' . number_format($total_amount, 2, '.', ',') ?></td>
            </tr>
            <tr>
                <?php
                $discount_percent_all = $data_hdr->Discount;
                $discount_value_all   = $total_amount * $discount_percent_all / 100;
                ?>
                <td></td>
                <td>Discount (<?php echo $discount_percent_all ?>%)</td>
                <td class="text-right"><?php echo $data_hdr->Currency . ' ' . number_format($discount_value_all, 2, '.', ',') ?></td>
            </tr>
            <tr>
                <td></td>
                <td>Total Tax 1</td>
                <td class="text-right"><?php echo $data_hdr->Currency . ' ' . number_format($data_hdr->Value_Tax_1, 2, '.', ',') ?></td>
            </tr>
            <tr>
                <td></td>
                <td>Total Tax 2</td>
                <td class="text-right"><?php echo $data_hdr->Currency . ' ' . number_format($data_hdr->Value_Tax_2, 2, '.', ',') ?></td>
            </tr>
            <tr>
                <td class="pt-1"></td>
                <td class="pt-1">Grand Total</td>
                <td class="text-right pt-1"><?php echo $data_hdr->Currency . ' ' . number_format($data_hdr->Amount, 2, '.', ',') ?></td>
            </tr>
        </table>
    </div>
    <div id="footer">
        <table class="table-half" border="1" align="right">
            <tr>
                <th>Approve</th>
            </tr>
            <tr class="border-none">
                <td class="text-center border-none">
                    <?php
                    $height = 80;
                    if ($data_hdr->Approve == 1) {
                        // $height = 0;
                    ?>
                        <!-- <img src="<?= base_url('assets/public/image/check-mark.png') ?>" alt="Check Approve" width="130"> -->
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
</body>

</html>