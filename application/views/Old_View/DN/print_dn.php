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
        font-weight: bold;
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
    <title>Surat Jalan <?= $Hdr->DN_Number ?></title>
</head>

<body>
    <table class="table-ttd" style="width: 100%;">
        <tr style="border-bottom: none;">
            <td style="border: none;" class="font-weight-bold"><?= $company->Name ?></td>
            <td style="border: none; width: 35%;" class="text-center" rowspan="2"><b>SURAT JALAN</b></td>
        </tr>
        <tr style="border-top: none;">
            <td style="border: none;" class="font-weight-bold"><?= $company->Address_Office ?></td>
        </tr>
    </table>
    <table class="table-ttd" style="width: 100%; border:solid black 1px;">
        <tr style="border: none;">
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">Tanggal</td>
            <td class="font-weight-bold only-border-right" style="padding-top: 3px;">: <?= date("d-m-Y", strtotime($Hdr->Send_Date))  ?></td>
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">Kepada Yth.</td>
        </tr>
        <tr style="border: none;">
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">Nomor</td>
            <td class="font-weight-bold only-border-right" style="padding-top: 3px; width: 30%;">: <?= $Hdr->DN_Number ?></td>
            <td class="font-weight-bold" style="border:none;padding-top: 3px;"><?= $Hdr->Customer_Name ?></td>
        </tr>
        <tr style="border: none;">
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">NO.PO</td>
            <td class="font-weight-bold only-border-right" style="padding-top: 3px;">: <?= $Hdr->No_PO_Customer ?></td>
            <td class="font-weight-bold" rowspan="2" style="border:none;padding-top: 3px;"><?= $Hdr->Complete_Address ?></td>
        </tr>
        <tr style="border: none;">
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">NO.Kend.</td>
            <td class="font-weight-bold only-border-right" style="padding-top: 3px;">: <?= $Hdr->Vehicle_Police_Number ?></td>
        </tr>
        <tr style="border: none;">
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">&nbsp;</td>
            <td class="font-weight-bold" style="border:none;padding-top: 3px;">&nbsp;</td>
            <td class="font-weight-bold only-border-left" style="padding-top: 3px;">ATT TO : <?= $Hdr->Att_To ?></td>
        </tr>
        <tr style="border: none;">
            <td class="font-weight-bold text-center" style="padding-top: 3px;">NO</td>
            <td class="font-weight-bold" style="padding-top: 3px;">QUANTITY</td>
            <td class="font-weight-bold" style="padding-top: 3px;">DESKRIPSI</td>
        </tr>
        <?php $i = 1; ?>
        <?php foreach ($Dtls->result() as $dtl) : ?>
            <tr style="border: none; height: <?php echo 110 / $Dtls->num_rows() ?>px;">
                <td class="font-weight-bold border-left-right text-center"><?= $i; ?></td>
                <td class="font-weight-bold border-left-right text-center"><?= floatval($dtl->Qty) ?>&nbsp;&nbsp;&nbsp;&nbsp;<?= $dtl->Uom ?></td>
                <td class="font-weight-bold border-left-right"><?= $dtl->Deskripsi_Product ?>
                    <?php if ($Hdr->Customer_Code) : ?>
                        <br>
                        <input style="border: none; font-weight: bold; width: 80%;">
                    <?php endif; ?>
                </td>
            </tr>
            <?php $i++; ?>
        <?php endforeach; ?>
    </table>
    <table class="table-footer" style="width: 100%;">
        <tr>
            <td colspan="4" class="font-weight-bold" style="font-size: 12pt !important;">CHECK LIST KEBERSIHAN DAN SAFETY</td>
        </tr>
        <tr>
            <td class="font-weight-bold font11" style="width: 13%;">&nbsp;</td>
            <td class="font-weight-bold font11">1. TRUK SUDAH DI BERSIHKAN</td>
            <td class="font-weight-bold font11" style="width: 13%;">&nbsp;</td>
            <td class="font-weight-bold font11">7. LAMPU TRUK MENYALA</td>
        </tr>
        <tr>
            <td class="font-weight-bold font11" style="width: 13%;">&nbsp;</td>
            <td class="font-weight-bold font11">2. DALAM TRUK BERSIH DARI KOTORAN</td>
            <td class="font-weight-bold font11" style="width: 13%;">&nbsp;</td>
            <td class="font-weight-bold font11">8. BAN TIDAK GUNDUL</td>
        </tr>
        <tr>
            <td class="font-weight-bold font11" style="width: 13%;">&nbsp;</td>
            <td class="font-weight-bold font11">3. TRUK TIDAK BOCOR</td>
            <td class="font-weight-bold font11" style="width: 13%;">&nbsp;</td>
            <td class="font-weight-bold font11">9. KIR MASIH HIDUP</td>
        </tr>
        <tr>
            <td class="font-weight-bold font11" style="width: 13%;">&nbsp;</td>
            <td class="font-weight-bold font11">4. MEMBAWA APAR</td>
            <td class="font-weight-bold font11" style="width: 13%;">&nbsp;</td>
            <td class="font-weight-bold font11">10. PAJAK MASIH HIDUP</td>
        </tr>
        <tr>
            <td class="font-weight-bold" style="width: 13%;">&nbsp;</td>
            <td class="font-weight-bold">5. MEMBAWA P3K</td>
            <td class="font-weight-bold" style="width: 13%;">&nbsp;</td>
            <td class="font-weight-bold">11. SIM B1 MASIH BERLAKU</td>
        </tr>
        <tr>
            <td class="font-weight-bold" style="width: 13%;">&nbsp;</td>
            <td class="font-weight-bold">6. MEMBAWA APD</td>
            <td class="font-weight-bold" style="width: 13%;">&nbsp;</td>
            <td class="font-weight-bold">12. .................................</td>
        </tr>
        <tr style="border: none;">
            <td style="border: none;" colspan="2" class="font-weight-bold">Pengirim :</td>
            <td style="border: none;" colspan="2" class="font-weight-bold">Penerima :</td>
        </tr>
        <tr style="border: none;">
            <td style="border: none;" colspan="2" class="font-weight-bold">
                <img src="<?= base_url() ?>assets/imp-assets/logo_dn.jpeg" alt="">
            </td>
            <td style="border: none;" colspan="2" class="font-weight-bold">&nbsp;</td>
        </tr>
        <tr style="border: none;">
            <td style="border: none;" colspan="2" class="font-weight-bold">PT. Pandowo Makmur Sejahtera</td>
            <td style="border: none;" colspan="2" class="font-weight-bold">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</td>
        </tr>

    </table>
</body>

<?php
function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}
?>

</html>
<?php if ($preview != 'true') : ?>
    <script>
        setTimeout(function() {
            window.print()
        }, 1000);
    </script>
<?php endif; ?>