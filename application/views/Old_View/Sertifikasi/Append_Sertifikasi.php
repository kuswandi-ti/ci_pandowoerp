<style>
    th,
    td {
        white-space: nowrap;
    }

    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>
<div class="row">
    <div class="col-lg-4 col-sm-12">
        <div class="card">
            <div class="card-header text-right">
                <a href="<?= base_url('SertifikasiProduct/Print_Sertifikasi/' . $barcode->Barcode_Value) ?>?preview=false" onclick="window.open('<?= base_url('SertifikasiProduct/Print_Sertifikasi/' . $barcode->Barcode_Value) ?>?preview=false','popup-<?= $barcode->Barcode_Value ?>','width=800,height=800'); return false;" target="popup" data-toggle="tooltip" title="Print Sertifikasi" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> Export Pdf</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-sm-12">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <h6 class="text-center"><b>Detail Product : <?= $barcode->Barcode_Value ?></b></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table_detail_barcode" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white" style="width: 35%;">LABEL</th>
                                <th class="text-white">DATA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">KODE PRODUCT :</td>
                                <td style="vertical-align: middle;"><?= $barcode->Product_Code ?></td>
                            </tr>

                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">NAMA PRODUCT :</td>
                                <td style="vertical-align: middle;"><?= $barcode->Product_Name ?></td>
                            </tr>

                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">KODE CUSTOMER :</td>
                                <td style="vertical-align: middle;"><?= $barcode->Customer_Code ?></td>
                            </tr>

                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">NAMA CUSTOMER :</td>
                                <td style="vertical-align: middle;"><?= $barcode->Customer_Name ?></td>
                            </tr>

                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">CHECKER :</td>
                                <td style="vertical-align: middle;"><?= $barcode->Checker_Rakit ?></td>
                            </tr>

                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">LEADER RAKIT :</td>
                                <td style="vertical-align: middle;"><?= $barcode->Leader_Rakit ?></td>
                            </tr>

                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">TANGGAL PRODUKSI :</td>
                                <td style="vertical-align: middle;"><?= $barcode->Date_Prd ?></td>
                            </tr>

                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">BARCODE VALUE :</td>
                                <td style="vertical-align: middle;"><?= $barcode->Barcode_Value ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">QC CHECK</td>
                                <td>
                                    <?php if ($barcode->IS_WASTING == '0') : ?>
                                        <button class="btn btn-sm bg-gradient-success">OK</button>
                                    <?php else : ?>
                                        <button class="btn btn-sm bg-gradient-danger">NG</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-sm-12">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <h6 class="text-center"><b>Detail Pengiriman Product : <?= $barcode->Barcode_Value ?></b></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table_detail_loading" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white" style="width: 35%;">LABEL</th>
                                <th class="text-white">DATA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">NO. PO CUSTOMER :</td>
                                <td style="vertical-align: middle;"><?= $barcode->No_PO_Customer ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">NO. SO :</td>
                                <td style="vertical-align: middle;"><?= $barcode->SO_Number ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">NO. SURAT JALAN :</td>
                                <td style="vertical-align: middle;"><?= $barcode->DN_Number ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">TANGGAL KIRIM :</td>
                                <td style="vertical-align: middle;"><?= $barcode->Send_Date ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">ALAMAT CUST :</td>
                                <td style="vertical-align: middle;"><?= $barcode->DN_Address ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">NO. KENDARAAN :</td>
                                <td style="vertical-align: middle;"><?= $barcode->Vehicle_Police_Number ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">NO. LOADING :</td>
                                <td style="vertical-align: middle;"><?= $barcode->No_loading ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">QTY LOADING :</td>
                                <td style="vertical-align: middle;"><?= $barcode->Qty_Loading ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; vertical-align: middle;">WAKTU LOADING :</td>
                                <td style="vertical-align: middle;"><?= $barcode->waktu_loading ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <h6 class="text-center" id="data-material"><b>Penggunaaan Material : <?= $barcode->Barcode_Value ?></b></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="Detail-Data-Material" class="table-sm table-bordered table-striped display compact nowrap" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="font-weight-bold text-white text-center">#</th>
                                <th class="font-weight-bold text-white text-center">NO.LOT</th>
                                <th class="font-weight-bold text-white text-center">KODE</th>
                                <th class="font-weight-bold text-white text-center">UKURAN</th>
                                <th class="font-weight-bold text-white text-center">QTY</th>
                                <th class="font-weight-bold text-white text-center">KUBIKASI</th>
                                <th class="font-weight-bold text-white text-center">SUPPLIER</th>
                                <th class="font-weight-bold text-white text-center">LEGALITAS</th>
                                <th class="font-weight-bold text-white text-center">NO. LEGALITAS</th>
                                <th class="font-weight-bold text-white text-center">TGL. KIRIM</th>
                                <th class="font-weight-bold text-white text-center">TGL. GRID</th>
                                <th class="bg-dark">#</th>
                                <th class="font-weight-bold text-white text-center">WAKTU MASUK OVEN</th>
                                <th class="font-weight-bold text-white text-center">TIMER OVEN</th>
                                <th class="font-weight-bold text-white text-center">WAKTU KELUAR OVEN</th>
                                <th class="font-weight-bold text-white text-center">OVEN</th>
                                <th class="bg-dark">#</th>
                                <th class="font-weight-bold text-white text-center">ALLOKASI PRODUKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($Materials as $li) : ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $li->no_lot ?></td>
                                    <td><?= $li->kode ?></td>
                                    <td><?= $li->tebal ?>CM X <?= $li->lebar ?>CM X <?= $li->panjang ?>CM</td>
                                    <td><?= $li->qty ?></td>
                                    <td><?= $li->kubikasi ?></td>
                                    <td><?= $li->nama_supplier ?></td>
                                    <td><?= $li->legalitas ?></td>
                                    <td><?= $li->no_legalitas ?></td>
                                    <td><?= $li->tgl_kirim ?></td>
                                    <td><?= $li->waktu_selesai_grid ?></td>
                                    <td class="bg-dark">&nbsp;</td>
                                    <td><?= $li->waktu_masuk_oven ?></td>
                                    <td><?= $li->timer_oven ?></td>
                                    <td><?= $li->waktu_keluar_oven ?></td>
                                    <td><?= $li->nama_oven ?></td>
                                    <td class="bg-dark">&nbsp;</td>
                                    <td><?= $li->waktu_alloc ?></td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        var table = $('#Detail-Data-Material').DataTable({
            paging: false,
            select: true,
            "ordering": false,
            dom: 'lBfrtip',
            "oLanguage": {
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
            },
            "buttons": ["copy",
                {
                    extend: 'csvHtml5',
                    title: $('#data-material').text(),
                    className: "btn btn-info",
                }, {
                    extend: 'excelHtml5',
                    title: $('#data-material').text(),
                    className: "btn btn-success",
                }
            ]
        });

    })
</script>