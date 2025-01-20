<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order</title>
    <style>
        body {
            padding: 0 30px 0 30px;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .svlk .text-italic {
            margin-bottom: 0;
        }

        .svlk span {
            font-size: 12px;
            color: #000;
        }

        .table-full {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-half {
            width: 50%;
            border-collapse: collapse;
        }

        tr>td {
            padding-left: .5rem !important;
            padding-right: .5rem !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
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

        .mt-0 {
            margin-top: 0;
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
        
        .cell-approve {
            vertical-align:bottom;
            text-align:center;
            height: 100px;
            width: 130px;
        }
    </style>
</head>
<body>
    <div id="header">
        <table class="table-full">
            <tr>
                <th style="width: 20%"><img src="<?= base_url('assets/public/image/logo-pandowo.jpg')?>" alt="Logo Pandowo" width="130"></th>
                <th style="width: 60%">

                    <h2 class="mb-0">PT Pandowo Makmur Sejahtera</h2>
                    <p style="font-size: 11px;">Kantor : Jl. Serma Marzuki RT/RW.05/02 88c, Kampung 200, Marga Jaya, Kec. Bekasi Selatan, Kota Madya Bekasi, Jawa Barat</p>
                    <p style="font-size: 11px; margin-left: 1rem; margin-right: 1rem;">Pabrik : Jalan Raya Kh Umar Rawa Ilat No. 17 Desa Mampir, Kec. Cileungsi, Kabupaten Bogor, Provinsi Jawa Barat</p>
                </th>
                <th style="width: 20%" class="text-grey svlk">
                    <img src="<?= base_url('assets/public/image/logo-svlk.jpg')?>" alt="Logo SVLK" width="130">
                    <br>
                        <span class="text-italic">Sustainable</span>
                    <br>
                        <span>VLHH-32-07-0224</span>
                </th>
            </tr>
        </table>
        <hr style="border-bottom: 4px solid #808080; padding: 0; margin: 0;">
    </div>
    <div id="container">
        <div class="text-center">
            <h3 class="header-container">PURCHASE ORDER</h3>
            <br>
            <span class="font-size-h3">NOMOR : 002/V/27052024</span>
        </div>
        <div class="font-size-content">
            <p>Kepada : Bapak Hendra</p>
            <p>Bersama Ini Kami PT Pandowo Makmur Sejahtera, Mengajukan Purchase Order (PO), Untuk Kayu Jabon Merah Sebagaimana Spesifikasi Dibawah Ini :</p>
        </div>
        <div class="table">
            <table border="1" class="table-full">
                <tr class="bg-lightgrey">
                    <th style="width: 20%;">Jenis Kayu</th>
                    <th style="width: 40%;">Ukuran</th>
                    <th style="width: 40%;">Spesifikasi</th>
                    <th style="width: 20%;">Ket</th>
                </tr>
                <tr>
                    <td class="text-center">Jabon Merah</td>
                    <td class="text-left">
                        <p>
                            Cutting 5,7 x 10 up x 150/200 <br>
                            Cutting 5,2 x 10 up x 150/200 <br>
                            Cutting 3,2 x 8,5 x 150/200 <br>
                            Cutting 3,2 x 10 x 150/200 <br>
                            Invoice 5,5 x 10 up x 150/200 <br>
                            Invoice 5 x 10 up x 150/200 <br>
                            Invoice 3 x 8,5 x 150/200 <br>
                            Invoice 3 x 10 x 150/200
                        </p>
                    </td>
                    <td class="text-justify">
                        Tidak pinhole, tidak blue stain, lepas hati, lepas kulit, mata mati dalam satu batang jangan lebih dari 2 (Harus berjarak 60 cm), diusahakan untuk ujung jangan pecah lebih dari 10 cm.
                    </td>
                    <td class="text-center"></td>
                </tr>
            </table>
        </div>
        <div class="font-size-content">
            <p>Demikian Purchase Order Ini Kami Sampaikan, Atas Perhatian Dan Kerjasamanya Kami Ucapkan Terima Kasih</p>
        </div>
    </div>
    <div id="footer">
        <table class="table-half" border="1" align="right">
            <tr>
                <th>Check</th>
                <th>Approve</th>
            </tr>
            <tr>
                <td class="cell-check">Widodo</td>
                <td class="cell-approve">Anton Seliyanto</td>
            </tr>
        </table>
    </div>
</body>
</html>
