<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    @page {
        margin: 0px 0px 0px 0px;
        font-size: 10pt !important;
        font-family: sans-serif;
        background: white;
    }

    @media print {
        @page {
            margin: 2px 0px 0px 2px;
            font-size: 10pt !important;
            font-family: sans-serif;
            width: 80mm;
            background: white;
        }

        table tr td span {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    }

    html,
    body {
        width: 80mm;
        /* height: 30mm; */
        background: #FFF;
        overflow: visible;
    }

    .table-ttd {
        /* border-collapse: collapse; */
        width: 100%;
        /* font-size: 10pt !important; */
        font-family: sans-serif;
        border-spacing: 0px;
    }

    .table-ttd tr,
    .table-ttd tr td {
        /* border: 0.5px solid black;
        padding: 1px; */
        /* font-size: 10pt !important; */
        font-family: sans-serif;
    }

    input,
    textarea,
    select {
        font-family: inherit;
    }

    ul,
    li {
        list-style-type: none;
        font-size: 10pt !important;
    }

    .table-ttd thead tr td,
    #tr-footer {
        font-weight: bold;
        font-family: sans-serif;
    }

    .text-center {
        text-align: center;
        vertical-align: middle;
    }

    .font-weight-bold {
        font-weight: bold;
    }

    table {
        page-break-inside: avoid;
    }
</style>

<head>
    <title>BARCODE PRODUCT</title>
</head>
<?php $i = 1; ?>
<?php $generator = new Picqer\Barcode\BarcodeGeneratorSVG(); ?>

<body>
    <?php foreach ($Barcodes as $li) : ?>
        <table class="table-ttd" <?php if ($i > 1) echo 'style="padding-top: 10px;"'
                                    ?>>
            <tbody style="border :none;">
                <tr style="border :none; padding: 0px;">
                    <td class="text-center" style="border :none;">
                        <span style="width: 100%; text-align: center;"><?= $generator->getBarcode($li->Barcode_Value, $generator::TYPE_CODE_128, 1, 25, 'black'); ?></span>
                    </td>
                </tr>
                <tr style="border :none; padding: 0px;">
                    <td class="text-center" style="border :none; padding: 0px;">
                        <span style="font-size: 15pt !important; margin-top: -4px;"><?= $li->Date_Prd ?></span><br>
                        <span style="border :none; padding: 0px; font-size:  27pt !important; margin-top: -25px; magin-left:-10px; padding-left: -10px;"><b><?= $li->Barcode_Number ?></b></span>
                    </td>
                </tr>
                <!-- <tr style="border :none; padding: 0px;">
                    <td class="text-center" >
                        
                    </td>
                </tr> -->
            </tbody>
        </table>
        <?php $i++; ?>
    <?php endforeach; ?>
</body>

</html>
<script>
    window.print()
    // setTimeout(function() {
    //     window.close();
    // }, 1000);
</script>