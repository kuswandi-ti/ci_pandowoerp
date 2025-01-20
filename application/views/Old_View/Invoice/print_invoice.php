<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/imp-assets/apple-touch-icon.png" />
<style>
    @page {
        size: A4;
        margin: 3px 10px 10px 15px;
        font-size: 11pt !important;
        font-family: Arial, Helvetica, sans-serif;
    }

    <?php if ($preview != 'true') : ?>@media print {
        @page {
            size: A4;
            margin: 3px 10px 10px 15px;
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
        font-size: 10pt !important;
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
</style>

<head>
    <title>INVOICE <?= $Hdr->Invoice_Number ?></title>
</head>

<body>
    <table class="table-ttd" style="width: 100%;">
        <tr style="border: none;">
            <td style="border: none; width: 30%;" rowspan="3" class="text-center"><img src="<?= base_url() ?>assets/imp-assets/logo-pt.jpeg" style="width: 70%;" alt=""></td>
            <td class="font-weight-bold" style="border: none;"><?= strtoupper($company->Name) ?></td>
            <td style="border: none; vertical-align: top;" rowspan="3"><b style="border: solid black 1px;">INVOICE</b></td>
        </tr>
        <tr style="border: none;">
            <td class="font-weight-bold" style="border: none;">Office : <?= str_replace('Blok ', '', $company->Address_Office) ?></td>
        </tr>
        <tr style="border: none;">
            <td class="font-weight-bold" style="border: none;">Factory : <?= $company->Address_Factory ?></td>
        </tr>
    </table>
    <table class="table-ttd" style="width: 100%;">
        <tr style="border-bottom: none;">
            <td style="border: none;" class="font-weight-bold text-center">FAKTUR PENJUALAN</td>
        </tr>
    </table>
    <table class="table-ttd" style="width: 100%; border:solid black 1px;">
        <tr style="border: none;">
            <td class="font-weight-bold" style="border:none;padding-top: 3px; width: 10%;">NAMA</td>
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">:</td>
            <td class="font-weight-bold only-border-right" style="padding-top: 3px;"><?= $Hdr->Customer_Name ?></td>
            <td class="font-weight-bold" style="border:none;padding-top: 3px; width: 10%;">TANGGAL</td>
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">:</td>
            <td class="font-weight-bold only-border-right" style="padding-top: 3px;"><?= date("d-m-Y", strtotime($Hdr->Invoice_Date)) ?></td>
        </tr>
        <tr style="border: none;">
            <td class="font-weight-bold" rowspan="2" style="border:none;padding-top: 3px; width: 10%; vertical-align: top;">ALAMAT</td>
            <td class="font-weight-bold" rowspan="2" style="border:none;padding-top: 3px; vertical-align: top;">:</td>
            <td class="font-weight-bold only-border-right" rowspan="2" style="padding-top: 3px; vertical-align: top;"><?= $Hdr->Customer_Address ?></td>
            <td class="font-weight-bold" style="border:none;padding-top: 3px; width: 10%;">NOMOR</td>
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">:</td>
            <td class="font-weight-bold only-border-right" style="padding-top: 3px;"><?= $Hdr->Invoice_Number ?></td>
        </tr>
        <tr style="border: none;">
            <td class="font-weight-bold" style="border:none;padding-top: 3px; width: 10%;">PO No.</td>
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">:</td>
            <td class="font-weight-bold only-border-right" style="padding-top: 3px; width: 30%;"><?= $Hdr->No_PO_Customer ?></td>
        </tr>
        <tr style="border: none;">
            <td class="font-weight-bold" style="border:none;padding-top: 3px; width: 10%;">NPWP</td>
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">:</td>
            <td class="font-weight-bold only-border-right" style="padding-top: 3px;"><?= $Hdr->NPWP ?></td>
            <td class="font-weight-bold" style="border:none;padding-top: 3px; width: 10%;">Due Date</td>
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">:</td>
            <td class="font-weight-bold only-border-right" style="padding-top: 3px;"><?= $Hdr->Due_Date ?> Days</td>
        </tr>
    </table>

    <table class="table-ttd" style="width: 100%; border:solid black 1px;">
        <tr style="border: none;">
            <td class="font-weight-bold text-center" style="padding-top: 3px;">NO</td>
            <td class="font-weight-bold" style="padding-top: 3px;">NAMA BARANG</td>
            <td class="font-weight-bold" style="padding-top: 3px;">BANYAKNYA</td>
            <td class="font-weight-bold" style="padding-top: 3px;">HARGA</td>
            <td class="font-weight-bold" style="padding-top: 3px;">TOTAL</td>
        </tr>
        <?php $i = 1; ?>
        <?php foreach ($Dtls->result() as $dtl) : ?>
            <tr style="border: none; height: <?php echo 110 / $Dtls->num_rows() ?>px;">
                <td style="font-size: 10.5pt !important;" class="font-weight-bold border-left-right text-center"><?= $i; ?></td>
                <td style="font-size: 10.5pt !important;" class="font-weight-bold border-left-right"><?= $dtl->Product_Name ?></td>
                <td style="font-size: 10.5pt !important;" class="font-weight-bold border-left-right text-center"><?= $dtl->Qty ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $dtl->Uom ?></td>
                <td style="font-size: 10.5pt !important;" class="font-weight-bold border-left-right text-center">Rp. <?= number_format($dtl->Product_Price, 2) ?></td>
                <td style="font-size: 10.5pt !important;" class="font-weight-bold border-left-right text-center">Rp. <?= number_format($dtl->Amount_Item, 2) ?></td>
            </tr>
            <?php $i++; ?>
        <?php endforeach; ?>
        <tr style="border-bottom: none;">
            <td style="font-size: 10.5pt !important;" class="font-weight-bold border-left-right" colspan="2">Pembayaran mohon di transfer ke :</td>
            <td class="font-weight-bold border-left-right" colspan="2" style="padding-left: 65px; font-size: 10.5pt !important; padding-bottom: 4px; padding-top: 4px;">HARGA TOTAL</tdclass=>
            <td style="font-size: 10.5pt !important;" class="font-weight-bold border-left-right">Rp. <?= number_format($Hdr->Item_Amount, 2) ?></td>
        </tr>
        <tr style="border: none;">
            <td style="font-size: 10.5pt !important;" class="font-weight-bold border-left-right" colspan="2">Bank &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : BCA KCU Cibubur, No Ac : <?= $company->No_Rekening ?></td>
            <td class="font-weight-bold border-left-right" colspan="2" style="padding-left: 65px; font-size: 10.5pt !important; padding-bottom: 4px; padding-top: 4px;">PPN <?= $Hdr->PPN ?>%</td>
            <td style="font-size: 10.5pt !important;" class="font-weight-bold border-left-right">Rp. <?= number_format($Hdr->PPN_Amount, 2) ?></td>
        </tr>
        <tr style="border: none;">
            <td style="font-size: 10.5pt !important;" class="font-weight-bold border-left-right" colspan="2">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?= $company->Name ?></td>
            <td class="font-weight-bold border-left-right" colspan="2" style="padding-left: 65px; font-size: 10.5pt !important; padding-bottom: 4px; padding-top: 4px;">GRAND TOTAL</td>
            <td style="font-size: 10.5pt !important;" class="font-weight-bold border-left-right">Rp. <?= number_format($Hdr->Invoice_Amount, 2) ?></td>
        </tr>
    </table>
    <p></p>
    <table class="table-ttd" style="width: 100%; border:none;">
        <tr style="border: none;">
            <td style="border: none; width: 25%;"></td>
            <td style="border: none; width: 35%;"></td>
            <td style="border: none; font-weight: bolder;">Hormat Kami</td>
        </tr>
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