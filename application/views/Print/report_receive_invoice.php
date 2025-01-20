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
    <title><?= $page_title ?></title>
</head>

<body>	
    <table class="table-ttd">
		<tr>
			<td class="text-right font-weight-bold" colspan="6">
                No : <?= $hdr->doc_number ?><br/>
				<?= $this->config->item('company_name_full') ?>
            </td>
        </tr>

		<tr>
            <td class="text-center font-weight-bold" colspan="6">
                <p>RECEIVE INVOICE (RI)</p>
            </td>
        </tr>

		<tr>
            <td>Received From</td>
			<td>:</td>
			<td><?= $nama_customer; ?></td>

			<!-- <td valign="top" rowspan="3">Customer Invoice Number</td>
			<td valign="top" rowspan="3">:</td>
			<td valign="top" rowspan="3">
				<?php echo $no_doc; ?>
			</td> -->
        </tr>

		<tr>
            <td valign="top">Amount</td>
			<td valign="top">:</td>
			<td class="font-weight-bold">
				<?= $this->help->FormatIdr($hdr->total); ?>
				<!-- <hr>
				<?= penyebut($hdr->total); ?>
				<hr> -->
			</td>
        </tr>

		<!-- <tr>
            <td valign="top">Descriptions</td>
			<td valign="top">:</td>
			<td>
				PT Pandowo Makmur Sejahtera
			</td>
        </tr> -->
	</table>

	<?php echo $print_out; ?>

	<table class="table-ttd" style="margin-top: 3mm;">
        <tbody>	
			<tr>	
				<td class="text-center font-weight-bold">Checked By:</td>
				<td class="text-center font-weight-bold">Verified By:</td>
				<td class="text-center font-weight-bold">Approved By:</td>	
			</tr>
			<tr>
				<td valign="top" class="text-center">
					<br><br><br><br>
					<hr>
					Accounting Dept
				</td>
				<td valign="top" class="text-center">
					<br><br><br><br>
					<hr>
					&nbsp;
				</td>
				<td valign="top" class="text-center">
					<br><br><br><br>
					<hr>
					&nbsp;
				</td>
			</tr>
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
