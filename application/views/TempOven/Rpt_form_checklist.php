<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/imp-assets/apple-touch-icon.png" />
<style>
    @page {
        size: A4;
        margin: 20px 20px 20px 20px;
        font-size: 9pt !important;
        font-family: sans-serif;
    }

    @media print {
        @page {
            size: A4;
            margin: 20px 20px 20px 20px;
            font-size: 9pt !important;
            font-family: sans-serif;
        }
    }

    html,
    body {
        /* 21 x 29,7 Cm */
        width: 205mm;
        height: 280mm;
        background: #FFF;
        overflow: visible;
    }

    .table-ttd {
        border-collapse: collapse;
        width: 100%;
        font-size: 9pt !important;
        font-family: sans-serif;
    }

    .table-ttd tr,
    .table-ttd tr td {
        border: 0.5px solid black;
        padding: 4px;
        padding: 4px;
        font-size: 9pt !important;
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
        font-size: 9pt !important;
    }

    .table-ttd tr,
    .table-ttd tr td {
        border: 1px solid black;
        padding: 3px;
        padding: 3px;
        font-size: 9pt !important;
    }

    /* tr {
        page-break-before: always;
        page-break-inside: avoid;
        font-size: 9pt !important;
    } */

    .tablee td,
    .tablee th {
        padding: 5px;
        font-size: 9pt !important;

    }


    ul,
    li {
        list-style-type: none;
        font-size: 9pt !important;
    }

    .tablee tr:nth-child(even) {
        background-color: #f2f2f2;
        font-size: 9pt !important;
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
        font-size: 9pt !important;
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
    <title>Temperature Oven <?= $Hdr->Doc_No ?></title>
</head>

<body>
    <table class="table-ttd" style="width: 100%;">
        <tr>
            <td style="text-align: right; border-right:none;" rowspan="4">
                <img src="<?= base_url() ?>assets/imp-assets/logo-pt.jpeg" style="width: 25vh;" alt="">
            </td>
            <td valign="top" style="border-left: none;" rowspan="4">
                <b style="font-size: 11pt;">PT. PANDOWO MAKMUR SEJAHTERA</b>
                <br>
                <br>Fact.1 : Kp. Nyangegeng RT09/04, Cileungsi
                <br>Fact.2 : Kepatihan Industry 99B, Gresik
                <br>
                <br>www.pandowomakmursejahtera.co.id | www.pms.com
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

    <table class="table-ttd" style="width: 100%; margin-top: 3mm; border-bottom: none;">
        <tr>
            <td style="font-weight: bold;" class="text-center">FORMULIR CEK LIST OVEN</td>
        </tr>
        <tr style="border-bottom: none;">
            <td>
                <ul>
                    <li>KD : <?= $Oven->nama ?></li>
                    <li>TGL PENGECEKAN : <?= $Hdr->Doc_Date ?></li>
                    <li>&nbsp;</li>
                    <li style="font-weight: bold;">1. CHECKLIST KEBERSIHAN :</li>
                </ul>
            </td>
        </tr>
    </table>
    <table class="table-ttd" style="width: 100%; border-top: none;">
        <!-- --------------------------- HEADER ------------------------------- -->
        <tr style="border-top: none;">
            <td rowspan="2"> </td>
            <td rowspan="2">PENGECEKAN</td>
            <td colspan="3" style="text-align: center;">SUDAH DI LAKUKAN DAN DI PERIKSA OLEH :</td>
        </tr>
        <tr>
            <td style="text-align: center;">PJ. OVEN</td>
            <td style="text-align: center;">MAINTENANCE</td>
            <td style="text-align: center;">M. TEKNIK</td>
        </tr>
        <!-- --------------------------- HEADER ------------------------------- -->
        <tr>
            <td>A.</td>
            <td>RUANG BOILER</td>
            <td><?= $this->help->Get_Full_Name($Hdr->R_Boiler_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->R_Boiler_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->R_Boiler_Teknik) ?></td>
        </tr>
        <tr>
            <td>B.</td>
            <td>CEROBONG</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Cerobong_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Cerobong_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Cerobong_Teknik) ?></td>
        </tr>
        <tr>
            <td>C.</td>
            <td>CYCLON 1</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Cyclon1_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Cyclon1_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Cyclon1_Teknik) ?></td>
        </tr>
        <tr>
            <td>D.</td>
            <td>CYCLON 2</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Cyclon2_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Cyclon2_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Cyclon2_Teknik) ?></td>
        </tr>
        <tr>
            <td>E.</td>
            <td>RUANG OVEN</td>
            <td><?= $this->help->Get_Full_Name($Hdr->R_Oven_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->R_Oven_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->R_Oven_Teknik) ?></td>
        </tr>
        <tr>
            <td colspan="5" style="height: 8vh;">CATATAN : <?= $Hdr->Catatan_Kebersihan ?></td>
        </tr>
        <tr>
            <td colspan="5" style="font-weight: bold;">
                <ul>
                    <li>2. CHECKLIST FASILITAS :</li>
                </ul>
            </td>
        </tr>
        <!-- --------------------------- HEADER ------------------------------- -->
        <tr style="border-top: none;">
            <td rowspan="2"> </td>
            <td rowspan="2">PENGECEKAN</td>
            <td colspan="3" style="text-align: center;">SUDAH DI LAKUKAN DAN DI PERIKSA OLEH :</td>
        </tr>
        <tr>
            <td style="text-align: center;">PJ. OVEN</td>
            <td style="text-align: center;">MAINTENANCE</td>
            <td style="text-align: center;">M. TEKNIK</td>
        </tr>
        <!-- --------------------------- HEADER ------------------------------- -->
        <tr>
            <td>A.</td>
            <td>BOILER</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Boiler_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Boiler_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Boiler_Teknik) ?></td>
        </tr>
        <tr>
            <td>B.</td>
            <td>POMPA SIRKULASI</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_PompaSirkulasi_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_PompaSirkulasi_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_PompaSirkulasi_Teknik) ?></td>
        </tr>
        <tr>
            <td>C.</td>
            <td>BLOWER</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Blowler_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Blowler_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Blowler_Teknik) ?></td>
        </tr>
        <tr>
            <td>D.</td>
            <td>KIPAS DAN ATAP GEL</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Kipas_AtapGel_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Kipas_AtapGel_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Kipas_AtapGel_Teknik) ?></td>
        </tr>
        <tr>
            <td>E.</td>
            <td>CHECKING DEMPER</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Demper_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Demper_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Demper_Teknik) ?></td>
        </tr>
        <tr>
            <td>F.</td>
            <td>AIR TOREN ATAS</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_AirToren_Atas_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_AirToren_Atas_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_AirToren_Atas_Teknik) ?></td>
        </tr>
        <tr>
            <td>G.</td>
            <td>AIR TOREN BAWAH</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_AirToren_Bawah_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_AirToren_Bawah_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_AirToren_Bawah_Teknik) ?></td>
        </tr>
        <tr>
            <td>H.</td>
            <td>SENSOR INTI SUHU KAYU</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Sensor_Inti_Suhu_Kayu_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Sensor_Inti_Suhu_Kayu_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Sensor_Inti_Suhu_Kayu_Teknik) ?></td>
        </tr>
        <tr>
            <td>I.</td>
            <td>SENSOR MC</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Sensor_Mc_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Sensor_Mc_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Sensor_Mc_Teknik) ?></td>
        </tr>
        <tr>
            <td>J.</td>
            <td>SENSOR DB/WB</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Sensor_DB_WB_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Sensor_DB_WB_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Sensor_DB_WB_Teknik) ?></td>
        </tr>
        <tr>
            <td>K.</td>
            <td>AIR WB</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Air_WB_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Air_WB_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Air_WB_Teknik) ?></td>
        </tr>
        <tr>
            <td>L.</td>
            <td>KAIN KASA</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_KainKasa_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_KainKasa_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_KainKasa_Teknik) ?></td>
        </tr>
        <tr>
            <td>M.</td>
            <td>PANEL BOX</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_PanelBox_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_PanelBox_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_PanelBox_Teknik) ?></td>
        </tr>
        <tr>
            <td>N.</td>
            <td>PANEL DB/WB</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Panel_DB_WB_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Panel_DB_WB_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Panel_DB_WB_Teknik) ?></td>
        </tr>
        <tr>
            <td>O.</td>
            <td>PANEL DB/WB</td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Panel_Suhu_Inti_Kayu_Pj_Oven) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Panel_Suhu_Inti_Kayu_Mtc) ?></td>
            <td><?= $this->help->Get_Full_Name($Hdr->Fas_Panel_Suhu_Inti_Kayu_Teknik) ?></td>
        </tr>
        <tr>
            <td colspan="5" style="height: 8vh;">CATATAN : <?= $Hdr->Catatan_Fasilitas ?></td>
        </tr>
    </table>
    <table style="width: 100%; border-top: none;">
        <tr>
            <td style="width: 20%;">&nbsp;&nbsp;</td>
            <td style="text-align: center;">PJ. OVEN :</td>
            <td style="text-align: center;">MAINTENANCE :</td>
            <td style="text-align: center;">M. TEKNIK :</td>
        </tr>
        <tr>
            <td style="width: 20%;">&nbsp;&nbsp;</td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
        </tr>
        <tr>
            <td style="width: 20%;">&nbsp;&nbsp;</td>
            <td style="text-align: center;"><?= $this->help->Get_Full_Name($Hdr->Pj_Oven) ?></td>
            <td style="text-align: center;"><?= $this->help->Get_Full_Name($Hdr->Maintenance) ?></td>
            <td style="text-align: center;"><?= $this->help->Get_Full_Name($Hdr->M_Teknik) ?></td>
        </tr>
    </table>
</body>
<!-- SELECT SysId, Doc_No, Doc_Status, Finish_At, Doc_Date, SysId_Oven

, , , , , , , , , , , , , , , , , , , , , , , , , , , , , Created_by, Created_at, Last_Updated_by, Last_Updated_at
FROM impsys.ttrx_hdr_temp_oven; -->

</html>
<!-- <script>
    setTimeout(function() {
        window.print()
    }, 1000);
</script> -->