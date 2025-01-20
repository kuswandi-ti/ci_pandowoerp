<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/imp-assets/apple-touch-icon.png" />
<style>
    @page {
        size: A4 landscape;
        margin: 20px 20px 20px 20px;
        font-size: 10pt !important;
        font-family: sans-serif;
    }

    @media print {
        @page {
            size: A4 landscape;
            margin: 20px 20px 20px 20px;
            font-size: 10pt !important;
            font-family: sans-serif;
        }
    }

    html,
    body {
        width: 280mm;
        height: 205mm;
        background: #FFF;
        overflow: visible;
    }

    .table-ttd {
        border-collapse: collapse;
        width: 100%;
        font-size: 10pt !important;
        font-family: sans-serif;
    }

    .table-ttd tr,
    .table-ttd tr td {
        border: 0.5px solid black;
        padding: 4px;
        padding: 4px;
        font-size: 10pt !important;
        font-family: sans-serif;
    }

    input,
    textarea,
    select {
        font-family: inherit;
    }

    .table-ttd {
        border-collapse: collapse;
        width: 100%;
        font-size: 10pt !important;
    }

    .table-ttd tr,
    .table-ttd tr td {
        border: 1px solid black;
        padding: 3px;
        padding: 3px;
        font-size: 10pt !important;
    }

    /* tr {
        page-break-before: always;
        page-break-inside: avoid;
        font-size: 10pt !important;
    } */

    .tablee td,
    .tablee th {
        padding: 5px;
        font-size: 10pt !important;

    }


    ul,
    li {
        list-style-type: none;
        font-size: 10pt !important;
    }

    .tablee tr:nth-child(even) {
        background-color: #f2f2f2;
        font-size: 10pt !important;
    }

    .table-ttd thead tr td,
    #tr-footer {
        font-weight: bold;
        font-family: sans-serif;
    }

    .tablee th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
        font-size: 10pt !important;
    }

    .text-center {
        text-align: center;
        vertical-align: middle;
    }

    .font-weight-bold {
        font-weight: bold;
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
    <title>Detail Temperature Oven <?= $Hdr->Doc_No ?></title>
</head>

<body>
    <table class="table-ttd" style="width: 367mm;">
        <tr>
            <td style="text-align: right; border-right:none;" rowspan="4">
                <img src="<?= base_url('assets/company_identity_image/Logo_Pandowo.png') ?>" style="width: 15vh; margin-right:50px;" alt="">
            </td>
            <td valign="top" style="border-left: none;" rowspan="4">
                <b style="font-size: 12pt;"><?= $this->config->item('company_name_full') ?></b>
                <br>
                <br>Workshop : <?= $this->config->item('workshop_address') ?>
                <br>
                <br>Office : <?= $this->config->item('office_address') ?>
                <br>
            </td>
            <td>No.Doc </td>
            <td><?= $Hdr->Doc_No ?></td>
        </tr>
        <tr>
            <td>Revisi</td>
            <td>0</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td><?= $Hdr->Doc_Date ?></td>
        </tr>
        <tr>
            <td>Paraf</td>
            <td>-</td>
        </tr>
    </table>

    <table class="table-ttd" style="width: 367mm; margin-top: 3mm;">
        <thead>
            <tr>
                <td class="text-center text-white" rowspan="2">TANGGAL</td>
                <td class="text-center text-white" rowspan="2">WAKTU</td>
                <td class="text-center text-white" colspan="3">KADAR AIR/MC(%)</td>
                <td class="text-center text-white" colspan="3">SUHU INTI KAYU</td>
                <td class="text-center text-white" colspan="2">SUHU BOILER</td>
                <td class="text-center text-white" colspan="2">T.DRY BULB</td>
                <td class="text-center text-white" colspan="2">T.WET BULD</td>
                <td class="text-center text-white" rowspan="2">Keterangan</td>
                <td class="text-center text-white" rowspan="2">Petugas</td>
            </tr>
            <tr>
                <td class="text-center text-white">MC1</td>
                <td class="text-center text-white">MC2</td>
                <td class="text-center text-white">MC3</td>
                <td class="text-center text-white">T1</td>
                <td class="text-center text-white">T2</td>
                <td class="text-center text-white">T3</td>
                <td class="text-center text-white">SET</td>
                <td class="text-center text-white">ACT</td>
                <td class="text-center text-white">SET</td>
                <td class="text-center text-white">ACT</td>
                <td class="text-center text-white">SET</td>
                <td class="text-center text-white">ACT</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($Dtls as $dtl) : ?>
                <tr>
                    <td class="text-center"><?= $dtl->Date ?></td>
                    <td class="text-center"><?= $dtl->Time ?></td>
                    <td class="text-center"><?= $dtl->KADAR_AIR_MC1 ?></td>
                    <td class="text-center"><?= $dtl->KADAR_AIR_MC2 ?></td>
                    <td class="text-center"><?= $dtl->KADAR_AIR_MC3 ?></td>
                    <td class="text-center"><?= $dtl->SIK_T1 ?></td>
                    <td class="text-center"><?= $dtl->SIK_T2 ?></td>
                    <td class="text-center"><?= $dtl->SIK_T3 ?></td>
                    <td class="text-center"><?= $dtl->BOILER_SET ?></td>
                    <td class="text-center"><?= $dtl->BOILER_ACT ?></td>
                    <td class="text-center"><?= $dtl->DRY_BULB_SET ?></td>
                    <td class="text-center"><?= $dtl->DRY_BULB_ACT ?></td>
                    <td class="text-center"><?= $dtl->WET_BULD_SET ?></td>
                    <td class="text-center"><?= $dtl->WET_BULD_ACT ?></td>
                    <td class="text-center"><?= $dtl->Keterangan ?></td>
                    <td class="text-center"><?php $User = $this->db->get_where('tmst_karyawan', ['initial' => $dtl->Created_By])->row(); ?> <?= !empty($User) ? $User->nama : '-'; ?></td>
                </tr>
            <?php endforeach; ?>
            <!-- , , , , , , , , , , , , , , Created_At, Last_Updated_By, Last_Updated_At -->
        </tbody>
    </table>
    <table style="width: 367mm; margin-top: 3mm;">
        <tbody>
            <tr>
                <td>Hari & Tanggal : <?= date("Y-m-d", strtotime($Hdr->Finish_At)); ?></td>
                <td>Di Ketahui Oleh :</td>
                <td>Di Laporkan Oleh :</td>
            </tr>
            <!-- $tanggal = date("Y-m-d", strtotime($datetime)); -->
            <!-- // Mengambil hanya jam:menit -->
            <!-- $jamMenit = date("H:i", strtotime($datetime)); -->
            <tr>
                <td>Jam Selesai : <?= date("H:i", strtotime($Hdr->Finish_At)); ?></td>
                <td>P. Jawab Heat Treatment</td>
                <td>P. Heat Treatment</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
<script>
    setTimeout(function() {
        window.print()
    }, 1000);
</script>