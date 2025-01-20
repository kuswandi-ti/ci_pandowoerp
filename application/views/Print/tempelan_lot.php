<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAG OK GRADING</title>
    <style>
        @page {
            margin-left: 1mm;
            margin-top: 4mm;
            font-size: 10pt !important;
            font-family: sans-serif;
            background: white;
        }

        @media print {
            @page {
                margin-left: 1mm;
                margin-top: 4mm;
                font-size: 10pt !important;
                font-family: sans-serif;
                background: white;
            }
        }

        html,
        body {
            width: 80mm;
            margin: 0;
            padding: 0;
            background: #FFF;
            overflow: visible;
        }

        .table-ttd {
            border-collapse: collapse;
            width: 75mm;
            font-size: 10pt !important;
            font-family: sans-serif;
            margin-left: 1mm;
        }

        .table-ttd tr,
        .table-ttd td {
            border: 1px solid black;
            padding: 3px;
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

        .barcode-container {
            width: 80mm;
            height: auto;
            overflow: hidden;
        }

        .barcode {
            width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <?php $i = 1; ?>
    <?php $generator = new Picqer\Barcode\BarcodeGeneratorSVG(); ?>
    <?php foreach ($lpb_dtls as $lpb_dtl) : ?>
        <table class="table-ttd" style="margin-top: 4mm">
            <tbody>
                <tr>
                    <td colspan=" 5" class="text-center">
                        <span><u><?= $this->config->item('company_name_full') ?></u></span>
                        <br>
                        <span class="font-weight-bold" style="margin-top: -9px;">TAG OK GRADE</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">LPB : <?= $lpb_hdr->lpb ?></td>
                </tr>
                <tr>
                    <td colspan="5">ITEM : <?= $lpb_dtl->Item_Name ?></td>
                </tr>
                <tr>
                    <td colspan="5">KUBIKASI : <?= $this->help->roundToFourDecimals($lpb_dtl->kubikasi) ?> (m3)</td>
                </tr>
                <tr>
                    <td colspan="5">SUPPLIER : <?= $lpb_hdr->Account_Name ?></td>
                </tr>
                <tr>
                    <td colspan="5">DAERAH : <?= $lpb_hdr->asal_kiriman ?></td>
                </tr>
                <tr>
                    <td colspan="5">TGL KIRIM : <?= ($lpb_hdr->tgl_finish_sortir == '0000-00-00') ? date('Y-m-d') : $lpb_hdr->tgl_finish_sortir ?></td>
                </tr>
                <tr>
                    <td colspan="5">TGL SORTIR : <?= ($lpb_hdr->tgl_kirim == '0000-00-00') ? date('Y-m-d') : $lpb_hdr->tgl_kirim ?></td>
                </tr>
                <tr>
                    <td colspan="5">GRADER : <?= empty($lpb_hdr->grader) ? $this->session->userdata('impsys_initial') : $lpb_hdr->grader ?></td>
                </tr>
                <tr style="border-top:none;">
                    <td colspan="5" class="text-center" style="border-top:none;">
                        <br />
                        <div class="">
                            <span style="transform: scale(0.97); display: inline-block;"><?= $generator->getBarcode($lpb_dtl->no_lot, $generator::TYPE_CODE_128); ?></span>
                        </div>
                        <br />
                    </td>
                </tr>
                <tr style="border-top:none;">
                    <td colspan="5" class="text-center" style="border-top:none;">
                        <span style="font-size: 11pt !important;">NO. BUNDLE : <b><?= $lpb_dtl->no_lot ?></b></span>
                    </td>
                </tr>

                <?php
                $childs = $this->db->get_where('ttrx_child_dtl_size_item_lpb', ['Id_Lot' => $lpb_dtl->sysid])->result();
                ?>

                <tr>
                    <td colspan="5">
                        <hr style="border: solid black 2px;">
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold text-center">T</td>
                    <td class="font-weight-bold text-center">L</td>
                    <td class="font-weight-bold text-center">P</td>
                    <td class="font-weight-bold text-center">Qty</td>
                    <td class="font-weight-bold text-center">Kubikasi</td>
                </tr>
                <?php $tot_kubikasi = 0; ?>
                <?php $tot_qty = 0; ?>
                <?php foreach ($childs as $ch) : ?>
                    <tr>
                        <td class="text-right"><?= floatval($ch->Item_Height) ?></td>
                        <td class="text-right"><?= floatval($ch->Item_Width) ?></td>
                        <td class="text-right"><?= floatval($ch->Item_Length) ?></td>
                        <td class="text-right"><?= floatval($ch->Qty) ?></td>
                        <td class="text-right"><?= $this->help->roundToFourDecimals($ch->Cubication * $ch->Qty) ?></td>
                    </tr>
                    <?php $tot_kubikasi += $ch->Cubication * $ch->Qty; ?>
                    <?php $tot_qty += $ch->Qty ?>
                <?php endforeach; ?>
                <tr>
                    <td class="text-center" style="border-top:none;">
                        TOTAL
                    <td class="text-center" style="border-top:none;" colspan="3">
                        <?= 'QTY : ' . floatval($tot_qty) ?>
                    </td>
                    <td class="text-center" style="border-top:none;" colspan="1">
                        <?= 'M3 : ' . $this->help->roundToFourDecimals($tot_kubikasi) ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php $i++; ?>
    <?php endforeach; ?>
</body>

</html>
<script>
    setTimeout(function() {
        window.print();
    }, 1000);
</script>