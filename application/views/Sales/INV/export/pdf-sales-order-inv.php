<!DOCTYPE html>
<html>

<head>
    <title><?php echo $data_hdr->Invoice_Number; ?></title>
    <style>
        @page {
            margin: 20px 20px 20px 20px;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 0 5px;
            font-size: 10px;
        }

        .table-layout {
            width: 100%;
            border-collapse: collapse;
        }

        .table-layout td {
            vertical-align: top;
            padding: 5px;
        }

        .table-header {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 4px solid #808080;
        }

        .text-center {
            text-align: center;
        }

        .text-start {
            text-align: left;
        }

        .text-justify {
            text-align: justify;
        }

        .text-end {
            text-align: right;
        }

        .m-0 {
            margin: 0;
        }

        .mt-3 {
            margin-top: 0.5rem;
        }

        .mt-4 {
            margin-top: 0.5rem;
        }

        .my-2 {
            margin: 1rem 0;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        .heading-2 {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .table-bordered {
            width: 100%;
            border-collapse: collapse;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid black;
            padding: 5px;
            /* text-align: center; */
        }

        .text-center {
            text-align: center;
            vertical-align: middle;
        }

        /* .table-bordered th[colspan] {
            background-color: #f0f0f0;
        } */

        .mt-5 {
            margin-top: 1rem;
        }

        .signature {
            text-align: center;
            margin-top: 10px;
        }

        .signature-approve {
            display: inline-block;
            text-align: center;
        }

        .underline {
            display: inline-block;
            border-bottom: 1px solid black;
            padding-bottom: 2px;
            width: 200px;
            /* Sesuaikan dengan panjang teks terpanjang */
        }
    </style>
</head>

<body>
    <div id="header">
        <table class="table-full">
            <tr>
                <th style="width: 20%">
                    <img
                        src="<?= base_url('assets/public/image/logo-pandowo.jpg') ?>"
                        alt="Logo Pandowo"
                        width="130" />
                </th>
                <th style="width: 60%">
                    <h2 class="mb-0">PT Pandowo Makmur Sejahtera</h2>
                    <p style="font-size: 11px">
                        Kantor : Jl. Serma Marzuki RT/RW.05/02 88c, Kampung 200, Marga
                        Jaya, Kec. Bekasi Selatan, Kota Madya Bekasi, Jawa Barat
                    </p>
                    <p style="font-size: 11px; margin-left: 1rem; margin-right: 1rem">
                        Pabrik : Jalan Raya Kh Umar Rawa Ilat No. 17 Desa Mampir, Kec.
                        Cileungsi, Kabupaten Bogor, Provinsi Jawa Barat
                    </p>
                </th>
                <th style="width: 20%" class="text-grey svlk">
                    <img
                        src="<?= base_url('assets/public/image/logo-svlk.jpg') ?>"
                        alt="Logo SVLK"
                        width="130" />
                    <br />
                    <span class="text-italic">Sustainable</span>
                    <br />
                    <span>VLHH-32-07-0224</span>
                </th>
            </tr>
        </table>
        <hr style="border-bottom: 4px solid #808080; padding: 0; margin: 0" />
    </div>

    <!--  -->
    <table class="table-layout">
        <tr>
            <td style="width: 50%">
                <table style="width: 100%">
                    <tr>
                        <!-- $this->db->select('h.*, a.Account_Name, a.Account_Address, c.Telephone, c.Fax'); -->
                        <!-- Telephone -->
                        <td style="width: 30%" class="text-start">Nama Customer</td>
                        <td style="width: 10%" class="text-end">:</td>
                        <td><?php echo $data_hdr->Account_Name ?: '-'; ?></td>
                    </tr>
                    <tr>
                        <td class="text-start">Alamat</td>
                        <td class="text-end">:</td>
                        <td><?php echo $data_hdr->Account_Address ?: '-'; ?></td>
                    </tr>
                    <tr>
                        <td class="text-start">Telepon</td>
                        <td class="text-end">:</td>
                        <td><?php echo $data_hdr->Telephone ?: '-'; ?></td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; text-align: right">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 30%" class="text-start">Tanggal Invoice</td>
                        <td style="width: 10%" class="text-end">:</td>
                        <td style="width: 50%" class="text-start">
                            <?php echo date('d F Y', strtotime($data_hdr->Invoice_Date)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start">Nomer Invoice</td>
                        <td class="text-end">:</td>
                        <td class="text-start"><?php echo $data_hdr->Invoice_Number; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="mt-4">
        <table class="table-bordered">
            <thead class="table-light">
                <tr>
                    <td class="text-center" rowspan="2">No</td>
                    <td class="text-center" rowspan="2">Item Code</td>
                    <td class="text-center" rowspan="2">Nama Barang</td>
                    <td class="text-center" colspan="3">SIZE</td>
                    <td class="text-center" colspan="2">Qty</td>
                    <!-- <td class="text-center" rowspan="2">Satuan<br>Keterangan</td> -->
                    <td class="text-center" rowspan="2">Harga/PCS</td>
                    <td class="text-center" rowspan="2">Total</td>
                </tr>
                <tr>
                    <td class="text-center">mm</td><!-- // T -->
                    <td class="text-center">mm</td><!-- // L -->
                    <td class="text-center">mm</td><!-- // P -->
                    <td class="text-center">PCS</td><!-- // P -->
                    <td class="text-center">M3</td><!-- // P -->
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $totalSatuanPokok = 0;
                $totalSatuanSecondary = 0;
                $totalHarga = 0;
                $totalTaxAmount = 0;




                // Pastikan bahwa dimensi tidak kosong dan valid
                foreach ($data_dtl as $row) :
                    // $totalSatuanPokok += floatval($row->Qty);
                    // $totalSatuanSecondary += floatval($row->Volume_M3);
                    // $volume_m3 = str_replace(',', '.', $row->Volume_M3);  // Ganti koma dengan titik
                    // $totalSatuanSecondary += floatval($volume_m3);
                    $totalHarga += floatval($row->FinalPrice);
                    // 
                    $totalTaxAmount += floatval($row->Tax_Amount1); // Tambahkan nilai Tax_Amount1
                    $totalTaxAmount += floatval($row->Tax_Amount2); // Tambahkan nilai Tax_Amount2 (jika ada)
                ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-center"><?php echo $row->Item_Code; ?></td>
                        <td class="text-center"><?= $row->Item_Name; ?></td>
                        <td class="text-center"><?= floatval($this->m_wh->convertLength(floatval($row->Item_Height), $row->LWH_Unit, 'MM')) ?></td> <!-- Tinggi dalam MM -->
                        <td class="text-center"><?= floatval($this->m_wh->convertLength(floatval($row->Item_Width), $row->LWH_Unit, 'MM')) ?></td> <!-- Lebar dalam MM -->
                        <td class="text-center"><?= floatval($this->m_wh->convertLength(floatval($row->Item_Length), $row->LWH_Unit, 'MM')) ?></td> <!-- Panjang dalam MM -->
                        <?php if ($row->Uom == 'pcs') : ?>
                            <td class="text-center"><?php echo floatval($row->Qty); ?></td>
                            <td class="text-center"><?= $this->help->roundToFourDecimals((floatval($row->Item_Length) * floatval($row->Item_Width) * (floatval($row->Item_Height)) / 1000000) * floatval($row->Qty)) ?></td>

                            <?php $totalSatuanPokok += floatval($row->Qty); ?>
                            <?php $totalSatuanSecondary +=  number_format((floatval($row->Item_Height) * floatval($row->Item_Width) * (floatval($row->Item_Length)) / 1000000) * floatval($row->Qty), 4); ?>
                        <?php elseif ($row->Uom == 'm3'): ?>
                            <td class="text-center">
                                <?= (floatval($row->Secondary_Qty) == 0) ? '-' : floatval($row->Secondary_Qty) ?>
                            </td>
                            <td class="text-center"><?php echo floatval($row->Qty); ?></td>

                            <?php $totalSatuanPokok += floatval($row->Secondary_Qty); ?>
                            <?php $totalSatuanSecondary += floatval($row->Qty); ?>
                        <?php else : ?>
                            <td class="text-center"><?php echo floatval($row->Qty); ?></td>
                            <td class="text-center">
                                <?= (empty($row->Secondary_Qty)) ? '-' : floatval($row->Secondary_Qty) ?>
                            </td>

                            <?php $totalSatuanPokok += floatval($row->Qty); ?>
                            <?php $totalSatuanSecondary += floatval($row->Secondary_Qty); ?>
                        <?php endif; ?>
                        <td class="text-center"><?php echo number_format($row->UnitPrice, 2); ?></td>
                        <td class="text-center"><?php echo number_format($row->FinalPrice, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"></td>
                    <td colspan="3" class="text-center">Total</td>
                    <td class="text-center"><?= $totalSatuanPokok; ?></td>
                    <td class="text-center"><?= $totalSatuanSecondary; ?></td> <!-- Total Volume -->
                    <td class="text-center">TOTAL</td>
                    <td class="text-center"><?= number_format(round($totalHarga), 2) ?></td>
                </tr>
                <tr>
                    <td style="border: none;" colspan="8"></td>
                    <td class="text-center">Total Invoice Pembayaran</td>
                    <td class="text-center"><?= number_format(round($totalHarga), 2) ?></td>
                </tr>
                <tr>
                    <td class="text-center" style="border: none;" colspan="8"></td>
                    <td class="text-center"><?= $data_dtl[0]->Tax_Code1 ?>%</td>
                    <td class="text-center"><?= number_format(round($totalTaxAmount), 2) ?></td>
                </tr>
                <tr>
                    <td style="border: none;" colspan="8"></td>
                    <th class="text-center">Total Invoice Sesudah PPN</th>
                    <th class="text-center"><?= number_format(round($totalHarga + $totalTaxAmount), 2) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-4">
        <!-- Bagian Terbilang dan Informasi Pembayaran -->
        <table style="width: 100%;">
            <tr>
                <td style="background-color: #d3d3d3; padding: 10px;">
                    <strong>TERBILANG :</strong>
                    <br>
                    <span style="text-transform: capitalize;"> <?= penyebut(round($totalHarga + $totalTaxAmount)) . ' ' . $data_hdr->words_en; ?></span>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px;">
                    NB : Pembayaran dilakukan via transfer ke Rekening
                    <br>
                    a/n PT PANDOWO MAKMUR SEJAHTERA
                    <br>
                    Rek. No 173.00.98.170195
                    <br>
                    BANK MANDIRI
                </td>
            </tr>
        </table>
        <?php $UserApprove = $this->db->get_where('tmst_karyawan', ['nik' => $data_hdr->Approve_By])->row(); ?>
        <div class="signature">
            <table class="table-layout">
                <tr>
                    <td style="width: 50%;" class="text-center">
                        <!-- <p><strong>Dibuat:</strong></p>
                        <br /><br />
                        <p><?= $data_hdr->Created_By; ?></p> -->
                    </td>
                    <td style="width: 50%;" class="text-center">
                        <p>Hormat Kami, <br>
                            PT Pandowo Makmur Sejahtera</p>
                        <br /><br /><br /><br /><br /><br /><br /><br />
                        <div class="signature-approve">
                            <div class="underline"><?= $UserApprove->nama ?></div><br>
                            <?= $UserApprove->Jabatan_Aktual ?>
                            <div></div>
                        </div>
                    </td>

                </tr>
            </table>
        </div>
</body>

</html>

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