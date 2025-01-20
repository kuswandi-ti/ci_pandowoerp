<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
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
    <table class="table-ttd" style="margin-top: 4mm">
        <tbody>
            <tr>
                <td colspan="6" class="text-center">
                    <span><u><?= $this->config->item('company_name_full') ?></u></span>
                    <br>
                    <span class="font-weight-bold" style="margin-top: -9px;"><?= $page_title ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="6">NO. DOCUMENT : <?= $Hdr->Doc_Afkir ?></td>
            </tr>
            <tr>
                <td colspan="6">TANGGAL. Afkir : <?= ($Hdr->Date_Afkir == '0000-00-00') ? date('Y-m-d') : $Hdr->Date_Afkir ?></td>
            </tr>
            <tr>
                <td colspan="6">TOTAL QTY : <?= floatval($Hdr->tot_Pcs) ?> Pcs</td>
            </tr>
            <tr>
                <td colspan="6">KUBIKASI : <?= $this->help->roundToFourDecimals($Hdr->tot_Cubication) ?> (m3)</td>
            </tr>
            <tr>
                <td colspan="6">
                    <hr style="border: solid black 2px;">
                </td>
            </tr>
            <tr>
                <td class="font-weight-bold text-center">ITEM</td>
                <td class="font-weight-bold text-center">T</td>
                <td class="font-weight-bold text-center">L</td>
                <td class="font-weight-bold text-center">P</td>
                <td class="font-weight-bold text-center">Qty</td>
                <td class="font-weight-bold text-center">Kubikasi</td>
            </tr>
            <?php foreach ($Dtls as $li) : ?>
                <tr>
                    <td class="text-right"><?= $li->Item_Name ?></td>
                    <td class="text-right"><?= floatval($li->Item_Height) ?></td>
                    <td class="text-right"><?= floatval($li->Item_Width) ?></td>
                    <td class="text-right"><?= floatval($li->Item_Length) ?></td>
                    <td class="text-right"><?= floatval($li->Qty) ?></td>
                    <td class="text-right"><?= $this->help->roundToFourDecimals($li->Cubication * $li->Qty) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
<script>
    setTimeout(function() {
        window.print();
    }, 1000);
</script>