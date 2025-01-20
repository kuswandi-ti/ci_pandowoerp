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

    .text-right {
        text-align: right;
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
    <title>COMMERCIAL-REPORT-LPB-<?= $lpb_hdr->lpb ?></title>
</head>

<body>
    <table class="table-ttd" style="width: 367mm;">
        <tr>
            <td colspan="2" class="text-center" style="width:99mm;"><b style="font-size: 14pt;"><?= $this->config->item('company_name_full') ?></b><br /><i>________________________________</i></td>
            <td class="text-center font-weight-bold" rowspan="6">
                <p style="font-size: 20pt;">COMMERCIAL & REPORT L.P.B</p>
                <p style="margin-top: -20px;"><?= $lpb_hdr->legalitas ?> : <?= ($lpb_hdr->legalitas == 'SALES RETURN') ? $lpb_hdr->SR_Numb : $lpb_hdr->no_legalitas; ?></p>
            </td>
            <td class="text-center font-weight-bold" style="width: 33mm;">REPORT MAKER</td>
            <td class="text-center font-weight-bold" style="width: 33mm;">CHECKER</td>
            <td class="text-center font-weight-bold" style="width: 33mm;">GRADER</td>
        </tr>
        <tr>
            <td style="border-right: none;"> &nbsp;&nbsp;&nbsp; LPB KAYU NO </td>
            <td style="border-left: none; width: 65mm;">:&nbsp;&nbsp;&nbsp; <?= $lpb_hdr->lpb ?></td>
            <td class="text-center" rowspan="4"><b style="font-size: 14pt;"><?= $this->session->userdata('impsys_initial')  ?></b></td>
            <td class="text-center" rowspan="4"><b style="font-size: 14pt;"><?= $lpb_hdr->selesai_by ?></b></td>
            <td class="text-center" rowspan="4"><b style="font-size: 14pt;"><?= $lpb_hdr->grader ?></b></td>
        </tr>
        <tr>
            <td style="border-right: none;"> &nbsp;&nbsp;&nbsp; SUPPLIER </td>
            <td style="border-left: none; width: 65mm;">:&nbsp;&nbsp;&nbsp; <?= $lpb_hdr->supplier ?></td>
        </tr>
        <tr>
            <td style="border-right: none;"> &nbsp;&nbsp;&nbsp; TANGGAL KIRIM </td>
            <td style="border-left: none; width: 65mm;">:&nbsp;&nbsp;&nbsp;
                <?php
                $date = date_create_from_format('Y-m-d', $lpb_hdr->tgl_kirim);
                echo date_format($date, 'd F Y');
                ?>
        </tr>
        <tr>
            <td style="border-right: none;"> &nbsp;&nbsp;&nbsp; TANGGAL L.P.B </td>
            <td style="border-left: none; width: 65mm;">:&nbsp;&nbsp;&nbsp;
                <?php
                $date = date_create_from_format('Y-m-d', $lpb_hdr->tgl_finish_sortir);
                echo date_format($date, 'd F Y');
                ?></td>
        </tr>
        <tr>
            <td style="border-right: none;" colspan="2"> &nbsp;&nbsp;&nbsp;
                <?php if ($lpb_hdr->legalitas == 'SALES RETURN'): ?>
                    <?= $lpb_hdr->legalitas ?> : <?= $lpb_hdr->no_legalitas ?>
                <?php else: ?>
                    <?= $lpb_hdr->penilaian ?> :<?= $lpb_hdr->keterangan ?>
                <?php endif; ?>
            </td>
            <td class="text-center">
                <?= date('d F Y') ?></td>
            <td class="text-center"><?php
                                    $date = date_create_from_format('Y-m-d', substr($lpb_hdr->selesai_at, 0, 10));
                                    echo date_format($date, 'd F Y');
                                    ?></td>
            <td class="text-center"><?php
                                    $date = date_create_from_format('Y-m-d', substr($lpb_hdr->created_at, 0, 10));
                                    echo date_format($date, 'd F Y');
                                    ?></td>
        </tr>
    </table>

    <table class="table-ttd" style="width: 367mm; margin-top: 3mm;">
        <thead>
            <td class="text-center font-weight-bold">NO.LOT</td>
            <td class="text-center font-weight-bold">DESKRIPSI</td>
            <td class="text-center font-weight-bold">TEBAL (CM)</td>
            <td class="text-center font-weight-bold">LEBAR (CM)</td>
            <td class="text-center font-weight-bold">PANJANG (CM)</td>
            <td class="text-center font-weight-bold">HARGA</td>
            <td class="text-center font-weight-bold">UOM PEMBELIAN</td>
            <td class="text-center font-weight-bold">KUBIKASI</td>
            <td class="text-center font-weight-bold">QTY</td>
            <td class="text-center font-weight-bold">SUB-TOTAL (M3)</td>
            <td class="text-center font-weight-bold">SUBTOTAL</td>
        </thead>
        <tbody><?php $total_harga = 0; ?>
        <tbody><?php $kubikasi = 0; ?>
        <tbody><?php $qty = 0; ?>
            <?php foreach ($lpb_dtls as $row) : ?>
                <tr>
                    <td class="text-center"><?= $row->no_lot ?></td>
                    <td class=""><?= $row->deskripsi ?></td>
                    <td class="text-center"><?= floatval($row->tebal) ?> cm</td>
                    <td class="text-center"><?= floatval($row->lebar) ?> cm</td>
                    <td class="text-center"><?= floatval($row->panjang) ?> cm</td>
                    <td class="text-center"><?= number_format($row->harga_per_pcs, 2, ',', '.'); ?></td>
                    <td class="text-center"><?= $row->Uom ?></td>
                    <td class="text-center"><?= floatval($row->kubikasi) ?></td>
                    <td class="text-center"><?= $row->Qty ?></td>
                    <td class="text-center"><?= $this->help->roundToFourDecimals($row->sub_tot_kubikasi) ?></td>
                    <td class="text-center"><?= $this->help->FormatIdr($row->sub_amount, 2, ',', '.'); ?></td>

                    <?php $total_harga += floatval($row->sub_amount) ?>
                    <?php $kubikasi += $this->help->roundToFourDecimals($row->sub_tot_kubikasi) ?>
                    <?php $qty += intval($row->Qty) ?>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td class="text-right font-weight-bold" style="height: 8mm;" colspan="8">TOTAL DITERIMA : </td>
                <!--                 <td class="text-center font-weight-bold" style="height: 8mm;" colspan="2">Potongan Uang Bongkar : . - <= number_format(floatval($lpb_hdr->tanggungan_uang_bongkar) * floatval($kubikasi), 0, ',', '.') ?></td> -->
                <!-- <td class="text-center font-weight-bold" style="height: 8mm;">&nbsp;</td> -->
                <!-- <td class="text-center font-weight-bold" style="height: 8mm;">&nbsp;</td> -->
                <td class="text-center font-weight-bold" style="height: 8mm;"><?= $qty ?> Pcs</td>
                <td class="text-center font-weight-bold" style="height: 8mm;"><?= $this->help->roundToFourDecimals($kubikasi) ?> M³</td>
                <td class="text-center font-weight-bold" style="height: 8mm;" colspan="2"><?= $row->Currency_Symbol . ". " . number_format(floatval($total_harga), 0, ',', '.'); ?></td>
            </tr>
            <?php
            $persent = (floatval($kubikasi) / max($lpb_hdr->jumlah_kiriman, 1)) * 100;
            $persentase = round($persent, 2);
            if ($persentase < 80) {
                $text_kalkulasi = 'JELEK';
            } else if ($persentase < 90) {
                $text_kalkulasi = 'CUKUP';
            } else {
                $text_kalkulasi = 'BAGUS';
            }
            ?>
            <tr>
                <td class="text-right font-weight-bold" colspan="6">JUMLAH KIRIMAN, PENERIMAAN & UANG BONGKAR</td>
                <td class="text-center font-weight-bold" colspan="2">Persentase Penerimaan Grid : <br><?= $persentase ?>% (<?= $text_kalkulasi ?>)</td>
                <td class="text-center font-weight-bold">Total Kiriman : <br> <?= floatval($lpb_hdr->jumlah_kiriman) ?>(m3)</td>
                <td class="text-center font-weight-bold">Uang Bongkar :<br><?= $row->Currency_Symbol . ". " . $this->help->FormatIdr($lpb_hdr->tanggungan_uang_bongkar) ?></td>
                <td class="text-center font-weight-bold" colspan="2"></td>
            </tr>
            <?php
            $pembayaran = floatval($total_harga);
            ?>
            <tr>
                <td style="height: 8mm;" class="text-right font-weight-bold" colspan="6">TOTAL PEMBAYARAN :</td>
                <td style="height: 8mm;" class="font-weight-bold" colspan="6"><?= $row->Currency_Symbol . ". " . number_format($pembayaran) ?> (<?= penyebut($pembayaran) ?>)</td>
        </tbody>
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
<script>
    setTimeout(function() {
        window.print()
    }, 1000);
</script>