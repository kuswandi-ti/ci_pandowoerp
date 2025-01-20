<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/imp-assets/apple-touch-icon.png" />
<style>
    @page {
        size: A4;
        margin: 20px 20px 20px 20px;
        font-size: 11pt !important;
        font-family: Arial, Helvetica, sans-serif;
    }

    <?php if ($preview != 'true') : ?>@media print {
        @page {
            size: A4;
            margin: 20px 20px 20px 20px;
            font-size: 11pt !important;
            font-family: Arial, Helvetica, sans-serif;
        }
    }

    <?php else : ?>@media print {
        body {
            display: none
        }
    }

    <?php endif; ?>html,
    body {
        width: 220mm;
        height: 280mm;
        background: #FFF;
        overflow: visible;
    }

    .table-ttd {
        border-collapse: collapse;
        width: 100%;
        font-size: 11pt !important;
        font-family: Arial, Helvetica, sans-serif;
    }

    .table-ttd tr,
    .table-ttd tr td {
        border: 1px solid black;
        padding: 2px;
        font-size: 11pt !important;
    }


    .table-footer {
        border-collapse: collapse;
    }

    .table-footer tr,
    .table-footer tr td {
        border: 1px solid black;
        padding: 3px;
        font-size: 11pt !important;
        font-family: Arial, Helvetica, sans-serif;
    }

    input,
    textarea,
    select {
        font-family: inherit;
    }

    .table-ttd {
        border-collapse: collapse;
        width: 100%;
        font-size: 11pt !important;
    }

    /* tr {
        page-break-before: always;
        page-break-inside: avoid;
        font-size: 12pt !important;
    } */

    .tablee td,
    .tablee th {
        padding: 5px;
        font-size: 11pt !important;

    }


    ul,
    li {
        list-style-type: none;
        font-size: 11pt !important;
    }

    .tablee tr:nth-child(even) {
        background-color: #f2f2f2;
        font-size: 11pt !important;
    }

    .table-ttd thead tr td,
    #tr-footer {
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
    }

    .tablee th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
        font-size: 12pt !important;
    }

    .text-center {
        text-align: center;
        vertical-align: middle;
    }

    .font-weight-bold {
        font-weight: bolder;
    }

    .only-border-right {
        border-top: none !important;
        border-left: none !important;
        border-bottom: none !important;
        border-right: solid black 1px !important;
    }

    .only-border-left {
        border-top: none !important;
        border-right: none !important;
        border-bottom: none !important;
        border-left: solid black 1px !important;
    }

    .border-left-right {
        border-top: none !important;
        border-right: solid black 1px !important;
        border-bottom: none !important;
        border-left: solid black 1px !important;
    }

    .border-left-right {
        border-top: none !important;
        border-right: solid black 1px !important;
        border-bottom: none !important;
        border-left: solid black 1px !important;
    }

    .font11 {
        font-size: 8pt !important;
    }

    .rotate {
        /* FF3.5+ */
        -moz-transform: rotate(-90.0deg);
        /* Opera 10.5 */
        -o-transform: rotate(-90.0deg);
        /* Saf3.1+, Chrome */
        -webkit-transform: rotate(-90.0deg);
        /* IE6,IE7 */
        filter: progid: DXImageTransform.Microsoft.BasicImage(rotation=0.083);
        /* IE8 */
        -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
        /* Standard */
        transform: rotate(-90.0deg);
    }

    * {
        box-sizing: border-box;
    }

    .row {
        margin-left: -5px;
        margin-right: -5px;
    }

    .column {
        float: left;
        width: 50%;
        padding: 5px;
    }

    /* Clearfix (clear floats) */
    .row::after {
        content: "";
        clear: both;
        display: table;
    }
</style>

<head>
    <title>SERTIFIKASI <?= $barcode->Barcode_Value ?></title>
</head>

<body>
    <table class="table-footer" style="width: 100%;">
        <tr style="border: none;">
            <td style="border: none; width: 30%;" rowspan="3" class="text-center"><img src="<?= base_url() ?>assets/imp-assets/logo-pt.jpeg" style="width: 70%;" alt=""></td>
            <td class="font-weight-bold" style="border: none;"><?= strtoupper($company->Name) ?></td>
            <td style="border: none; vertical-align: top;" rowspan="3"><b style="border: solid black 1px;">SERTIFIKASI <br /> </b></td>
        </tr>
        <tr style="border: none;">
            <td class="font-weight-bold" style="border: none;">Office : <?= str_replace('Blok ', '', $company->Address_Office) ?></td>
        </tr>
        <tr style="border: none;">
            <td class="font-weight-bold" style="border: none;">Factory : <?= $company->Address_Factory ?></td>
        </tr>
    </table>
    <!-- <div class="row">
        <div class="column"> -->
    <table class="table-ttd">
        <thead>
            <tr>
                <th class="text-white" colspan="2">DETAIL PRODUCT : <?= $barcode->Barcode_Value ?></th>
            </tr>
            <tr>
                <th class="text-center text-white" style="width: 35%;">LABEL</th>
                <th class="text-white">DATA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: bold; vertical-align: middle;">KODE PRODUCT :</td>
                <td style="vertical-align: middle;"><?= $barcode->Product_Code ?></td>
            </tr>

            <tr>
                <td style="font-weight: bold; vertical-align: middle;">NAMA PRODUCT :</td>
                <td style="vertical-align: middle;"><?= $barcode->Product_Name ?></td>
            </tr>

            <tr>
                <td style="font-weight: bold; vertical-align: middle;">KODE CUSTOMER :</td>
                <td style="vertical-align: middle;"><?= $barcode->Customer_Code ?></td>
            </tr>

            <tr>
                <td style="font-weight: bold; vertical-align: middle;">NAMA CUSTOMER :</td>
                <td style="vertical-align: middle;"><?= $barcode->Customer_Name ?></td>
            </tr>

            <tr>
                <td style="font-weight: bold; vertical-align: middle;">CHECKER :</td>
                <td style="vertical-align: middle;"><?= $barcode->Checker_Rakit ?></td>
            </tr>

            <tr>
                <td style="font-weight: bold; vertical-align: middle;">LEADER RAKIT :</td>
                <td style="vertical-align: middle;"><?= $barcode->Leader_Rakit ?></td>
            </tr>

            <tr>
                <td style="font-weight: bold; vertical-align: middle;">TANGGAL PRODUKSI :</td>
                <td style="vertical-align: middle;"><?= $barcode->Date_Prd ?></td>
            </tr>

            <tr>
                <td style="font-weight: bold; vertical-align: middle;">BARCODE VALUE :</td>
                <td style="vertical-align: middle;"><?= $barcode->Barcode_Value ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; vertical-align: middle;">QC CHECK</td>
                <td>
                    <?php if ($barcode->IS_WASTING == '0') : ?>
                        <button class="btn btn-sm bg-gradient-success">OK</button>
                    <?php else : ?>
                        <button class="btn btn-sm bg-gradient-danger">NG</button>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    <!-- </div>
        <div class="column"> -->
    <table class="table-ttd">
        <thead>
            <tr>
                <th class="text-white" colspan="2">DATA DELIVERY NOTE</th>
            </tr>
            <tr>
                <th class="text-center text-white" style="width: 35%;">LABEL</th>
                <th class="text-white">DATA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: bold; vertical-align: middle;">NO. PO CUSTOMER :</td>
                <td style="vertical-align: middle;"><?= $barcode->No_PO_Customer ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; vertical-align: middle;">NO. SO :</td>
                <td style="vertical-align: middle;"><?= $barcode->SO_Number ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; vertical-align: middle;">NO. SURAT JALAN :</td>
                <td style="vertical-align: middle;"><?= $barcode->DN_Number ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; vertical-align: middle;">TANGGAL KIRIM :</td>
                <td style="vertical-align: middle;"><?= $barcode->Send_Date ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; vertical-align: middle;">ALAMAT CUST :</td>
                <td style="vertical-align: middle;"><?= $barcode->DN_Address ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; vertical-align: middle;">NO. KENDARAAN :</td>
                <td style="vertical-align: middle;"><?= $barcode->Vehicle_Police_Number ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; vertical-align: middle;">NO. LOADING :</td>
                <td style="vertical-align: middle;"><?= $barcode->No_loading ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; vertical-align: middle;">QTY LOADING :</td>
                <td style="vertical-align: middle;"><?= $barcode->Qty_Loading ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; vertical-align: middle;">WAKTU LOADING :</td>
                <td style="vertical-align: middle;"><?= $barcode->waktu_loading ?></td>
            </tr>
        </tbody>
    </table>
    <!-- </div>
    </div> -->
    <br />
    <table class="table-ttd" style="width: 100%;">
        <thead>
            <tr>
                <th class="font-weight-bold text-white text-center" colspan="18">DETAIL PEMAKAIAN BAHAN BAKU <?= $barcode->Date_Prd ?></th>
            </tr>
            <tr>
                <td class="font-weight-bold text-white text-center">#</td>
                <td class="font-weight-bold text-white text-center">NO.LOT</td>
                <td class="font-weight-bold text-white text-center">KODE</td>
                <!-- <td class="font-weight-bold text-white text-center">UKURAN</td> -->
                <td class="font-weight-bold text-white text-center">QTY</td>
                <td class="font-weight-bold text-white text-center">KUBIKASI</td>
                <td class="font-weight-bold text-white text-center">SUPPLIER</td>
                <td class="font-weight-bold text-white text-center">LEGALITAS</td>
                <td class="font-weight-bold text-white text-center">NO. LEGALITAS</td>
                <!-- <td class="font-weight-bold text-white text-center">TGL. KIRIM</td> -->
                <!-- <td class="font-weight-bold text-white text-center">TGL. GRID</td> -->
                <!-- <td class="bg-dark">#</td> -->
                <td class="font-weight-bold text-white text-center">MASUK OVEN</td>
                <!-- <td class="font-weight-bold text-white text-center">TIMER OVEN</td> -->
                <!-- <td class="font-weight-bold text-white text-center">WAKTU KELUAR OVEN</td> -->
                <td class="font-weight-bold text-white text-center">OVEN</td>
                <!-- <td class="bg-dark">#</td> -->
                <!-- <td class="font-weight-bold text-white text-center">ALLOKASI PRODUKSI</td> -->
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($Materials as $li) : ?>
                <tr>
                    <td style="font-size: 10pt !important;"><?= $i ?></td>
                    <td style="font-size: 10pt !important;"><?= $li->no_lot ?></td>
                    <td style="font-size: 10pt !important;"><?= $li->kode ?></td>
                    <!-- <td style="font-size: 10pt !important;"><?= $li->tebal ?>CM X <?= $li->lebar ?>CM X <?= $li->panjang ?>CM</td> -->
                    <td style="font-size: 10pt !important;"><?= $li->qty ?></td>
                    <td style="font-size: 10pt !important;"><?= floatval($li->kubikasi) ?></td>
                    <td style="font-size: 10pt !important;"><?= $li->nama_supplier ?></td>
                    <td style="font-size: 10pt !important;"><?= $li->legalitas ?></td>
                    <td style="font-size: 10pt !important;"><?= $li->no_legalitas ?></td>
                    <!-- <td style="font-size: 10pt !important;"><?= $li->tgl_kirim ?></td>
                    <td style="font-size: 10pt !important;"><?= $li->waktu_selesai_grid ?></td> -->
                    <!-- <td class="bg-dark">&nbsp;</td> -->
                    <td style="font-size: 10pt !important;"><?= $li->waktu_masuk_oven ?></td>
                    <!-- <td style="font-size: 10pt !important;"><?= $li->timer_oven ?></td> -->
                    <!-- <td style="font-size: 10pt !important;"><?= $li->waktu_keluar_oven ?></td> -->
                    <td style="font-size: 10pt !important;"><?= $li->nama_oven ?></td>
                    <!-- <td class="bg-dark">&nbsp;</td> -->
                    <!-- <td style="font-size: 10pt !important;"><?= $li->waktu_alloc ?></td> -->
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>


</html>
<?php if ($preview != 'true') : ?>
    <script>
        setTimeout(function() {
            window.print()
        }, 1000);
    </script>
<?php endif; ?>