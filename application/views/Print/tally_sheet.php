<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
        }

        .container {
            width: 210mm;
            margin: 0 auto;
            padding: 10mm;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 150px;
        }

        .header .title {
            text-align: center;
        }

        .header .title h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
        }

        .header .title p {
            margin: 5px 0;
            font-size: 10pt;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px;
        }

        .info-table td.title {
            font-weight: bold;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        .footer {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="<?= base_url('assets/company_identity_image/Logo_Pandowo.png') ?>" alt="Pandowo Logo">
            <div class="title">
                <h1><?= $this->config->item('company_name_full') ?></h1>
                <br>
                <p>Kantor: <?= $this->config->item('office_address') ?></p>
                <p style="margin-top: 15px;">Pabrik: <?= $this->config->item('workshop_address') ?></p>
            </div>
            <img src="<?= base_url('assets/company_identity_image/svlk_black-removebg.png') ?>" alt="Svlk Logo" style="width: 200px;">
        </div>
        <hr style="border: solid black 3px; margin-top: -20px;">
        <hr style="border: solid black 1px; margin-top: -5px;">
        <table class="info-table">
            <tr>
                <td class="title">Tally Sheet</td>
                <td>: <?= $lpb_hdr->lpb ?></td>
                <td class="title" rowspan="2" style="border: solid black 1px;"><?= $lpb_hdr->supplier ?></td>
                <td style="border: solid black 1px;">Pcs</td>
                <td style="border: solid black 1px;"><?= floatval($lpb_hdr->jumlah_pcs_kiriman) ?></td>
            </tr>
            <tr>
                <td class="title">Tanggal Pengiriman</td>
                <td>: <?= DateTime::createFromFormat('Y-m-d', $lpb_hdr->tgl_kirim)->format('d/m/Y'); ?></td>
                <td style="border: solid black 1px;">Volume</td>
                <td style="border: solid black 1px;"><?= floatval($lpb_hdr->jumlah_kiriman) ?></td>
            </tr>
            <tr>
                <td class="title">Tanggal Telly</td>
                <td>: <?= DateTime::createFromFormat('Y-m-d', $lpb_hdr->tgl_finish_sortir)->format('d/m/Y');  ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="title" rowspan="2" style="border: solid black 1px;"><?= $this->config->item('company_name_init') ?></td>
                <td style="border: solid black 1px;">Pcs</td>
                <td style="border: solid black 1px;" id="pcs_top"></td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 16pt; text-align: center;"><strong><?= ($lpb_hdr->status_lpb == 'SELESAI') ? '' : '[DRAFT]'; ?></strong></td>
                <td style="border: solid black 1px;">Volume</td>
                <td style="border: solid black 1px;" id="vol_top"></td>
            </tr>
        </table>
        <table class="info-table">
            <tr>
                <td class="title" style="text-align: center; border: solid black 2.5px;">
                    <?= $lpb_hdr->AccountTitle_Code ?>. <?= $lpb_hdr->supplier ?>
                </td>
            </tr>
        </table>
        <table class="data-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>ITEM</th>
                    <th>T</th>
                    <th>L</th>
                    <th>P</th>
                    <th>PCS</th>
                    <th>VOLUME</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $pcs = 0;
                $kubikasi = 0;
                ?>

                <?php foreach ($lpb_dtls as $li) : ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td><?= $li->Item_Name  ?> (<?= $li->Item_Code ?>)<?= ($li->into_oven == 4) ? ' <b>*</b>': ''; ?></td>
                        <td><?= floatval($li->tebal) ?></td>
                        <td><?= floatval($li->lebar) ?></td>
                        <td><?= floatval($li->panjang) ?></td>
                        <td><?= floatval($li->Qty) ?></td>
                        <td><?= $this->help->roundToFourDecimals(floatval($li->sub_tot_kubikasi)) ?></td>
                        <?php
                        $pcs += floatval($li->Qty);
                        $kubikasi += floatval($li->sub_tot_kubikasi);
                        ?>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>
                <tr style="background-color: #B8BECC;">
                    <td colspan="5" class="title" style="text-align: right; font-weight: bold;">TOTAL</td>
                    <td id="pcs_bot"><?= $pcs ?></td>
                    <td id="vol_bot"><?= $this->help->roundToFourDecimals(floatval($kubikasi)) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

<script>
    document.getElementById('pcs_top').innerHTML = document.getElementById('pcs_bot').innerHTML;
    document.getElementById('vol_top').innerHTML = document.getElementById('vol_bot').innerHTML;

    setTimeout(function() {
        window.print()
    }, 1500);
</script>