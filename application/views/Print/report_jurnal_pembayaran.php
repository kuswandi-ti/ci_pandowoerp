<!DOCTYPE html>
<html>
<head>
    <title><?php echo $data_hdr->no_jurnal; ?></title>
    <style>
        body {
            padding: 0 10px 0 10px;
            font-size: 11.5px !important;
        }

        .svlk .text-italic {
            margin-bottom: 0;
        }

        .svlk span {
            font-size: 12px;
            color: #000;
        }

        td {
            vertical-align: top;
        }

        .tbl-header,
        .table-full {
            margin-top: 1rem;
            font-size: 11.5px;
        }

        .table-full {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-half {
            width: 30%;
            border-collapse: collapse;
        }

        .table-full tr>td {
            padding-left: .3rem !important;
            padding-right: .3rem !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .table-total {
            font-weight: 700;
            font-size: 11px;
            margin-top: 1rem;
        }

        .text-grey {
            color: #808080;
        }

        .text-italic {
            font-style: italic;
        }

        #container {
            font-family: Tahoma, "Trebuchet MS", sans-serif;
        }
        
        #footer {
            font-family: Tahoma, "Trebuchet MS", sans-serif;
            margin-top: 2rem;
        }

        #footer table {
            border-color: #000;
        }

        #footer table tr>th,
        #footer table tr>td {
            border-color: #000;
        }

        .header-container {
            border-bottom: 2px solid #000;
            display: inline-block;
            width: auto;
            text-align: center;
            margin-bottom: 0;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .mt-0 {
            margin-top: 0;
        }

        .pt-1 {
            padding-top: 1rem !important;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .font-size-h2 {
            font-size: 24px;
        }

        .font-size-content {
            font-size: 17px;
        }

        .bg-lightgrey {
            background-color: #F2F2F2;
        }

        .text-justify {
            text-align: justify;
        }

        .text-left {
            text-align: left;
        }

        .cell-check {
            vertical-align:bottom;
            text-align:center;
            height: 100px;
            width: 100px;
        }

        .font-size-h5 {
            font-size: 12px;
            margin-top: .2rem !important;
        }

        .border-none {
            border: none;
        }
        
        .cell-approve {
            vertical-align:bottom;
            text-align:center;
            width: 130px;
            border: none;
        }
    </style>
</head>
<body>
    <div id="container">
		<table width="100%" class="tbl-header">
            <tr>
                <td width="13.5%"></td>
                <td width="3%"></td>
                <td width="35%"></td>
                <td width="15.5%" align="right">No.</td>
                <td width="3%" align="center">:</td>
                <td width="23%"><b><?php echo $data_hdr->no_jurnal; ?></b></td>
            </tr>
			<tr>
                <td width="13.5%"></td>
                <td width="3%"></td>
                <td width="35%"></td>
                <td width="15.5%" align="right">Tgl</td>
                <td width="3%" align="center">:</td>
                <td width="23%"><?php echo date('d F Y', strtotime($data_hdr->tgl_jurnal)); ?></td>
            </tr>
		</table>

		<div class="text-center">
            <h4 class="header-container">JURNAL PEMBAYARAN</h4>
            <br>
        </div>

		<table width="100%" class="tbl-header">
			<tr>
                <td width="5%">Paid To</td>
                <td width="1%">:</td>
                <td width="94%"><?php echo $data_hdr->Account_Name; ?></td>
            </tr>
			<tr>
                <td width="5%">Keterangan</td>
                <td width="1%">:</td>
                <td width="94%"><?php echo $data_hdr->keterangan; ?></td>
            </tr>
			<tr>
                <td width="5%">Referensi</td>
                <td width="1%">:</td>
                <td width="94%"><?php echo $data_hdr->reff_desc; ?></td>
            </tr>
		</table>

		<table border="1" class="table-full">
            <thead>
                <tr class="bg-lightgrey">
                    <th>No</th>
                    <th>Akun</th>
                    <th>Debit</th>
                    <th>Credit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $total_debit = 0;
					$total_credit = 0;
                    $no = 1;
                    foreach ($data_dtl as $val) {
                ?>
                    <tr>
                        <td class="text-center" width="7%"><?php echo $no ?>.</td>
                        <td class="text-left" width="63%"><?php echo $val->kode_akun ?> - <?php echo $val->nama_akun ?></td>
                        <td class="text-right" width="15%"><?php echo number_format($val->debit,2,'.',',') ?></td>
                        <td class="text-right" width="15%"><?php echo number_format($val->credit,2,'.',',') ?></td>
                    </tr>
                <?php
                        $no++;
                        $total_debit += $val->debit;
						$total_credit += $val->credit;
                    }
                ?>

				<tr>
					<td colspan="2" class="text-right"><b>Total</b></td>
					<td class="text-right"><b><?php echo number_format($total_debit,2,'.',',') ?></b></td>
					<td class="text-right"><b><?php echo number_format($total_credit,2,'.',',') ?></b></td>
				</tr>
            </tbody>
        </table>

		<br><br>

		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td width="20%" class="text-center">Prepared By</td>
				<td width="20%" class="text-center">Approved By</td>
				<td width="20%" class="text-center">Cashier</td>
				<td width="20%" class="text-center">Checked By</td>
				<td width="20%" class="text-center">Received By</td>
			</tr>

			<br><br><br><br><br>

			<tr>
				<td width="20%" class="text-center">(_________________)</td>
				<td width="20%" class="text-center">(_________________)</td>
				<td width="20%" class="text-center">(_________________)</td>
				<td width="20%" class="text-center">(_________________)</td>
				<td width="20%" class="text-center">(_________________)</td>
			</tr>
		</table>
	</div>
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
