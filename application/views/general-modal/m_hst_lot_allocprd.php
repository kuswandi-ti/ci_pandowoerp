<div class="modal fade" id="modal-detail-lpb">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail History <?= $dtl->no_lot ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <p class="text-center font-weight-bold bg-warning" style="margin-bottom: -5px;">Identitas Lot</p>
                    <table class="table-sm table-striped table-bordered display compact nowrap" id="tbl_identity_lot" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="col text-center text-white">NO.LPB</th>
                                <th class="col text-center text-white">SUPPLIER</th>
                                <th class="col text-center text-white">MATERIAL</th>
                                <th class="col text-center text-white">TEBAL</th>
                                <th class="col text-center text-white">LEBAR</th>
                                <th class="col text-center text-white">PANJANG</th>
                                <th class="col text-center text-white">GRADER</th>
                                <th class="col text-center text-white">LEGALITAS</th>
                                <th class="col text-center text-white">PENILAIAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td class="text-center"><?= $dtl->lpb ?></td>
                            <td class="text-center"><?= $dtl->nama ?></td>
                            <td class="text-center"><?= $dtl->deskripsi ?></td>
                            <td class="text-center"><?= floatval($dtl->tebal) . 'CM' ?></td>
                            <td class="text-center"><?= floatval($dtl->lebar) . 'CM' ?></td>
                            <td class="text-center"><?= floatval($dtl->panjang) . 'CM' ?></td>
                            <td class="text-center"><?= $dtl->grader ?></td>
                            <td class="text-center"><?= $dtl->legalitas ?></td>
                            <td class="text-center"><?= $dtl->penilaian ?></td>
                        </tbody>
                    </table>
                </div>
                <hr class="devider" style="border: solid black 1px;">
                <div class="container-fluid">
                    <p class="text-center font-weight-bold bg-warning" style="margin-bottom: -5px;">Detail Proses Oven</p>
                    <table class="table-sm table-striped table-bordered display compact nowrap" id="tbl_hst_oven" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="col text-center text-white"><i class="fas fa-map-marker-alt"></i> OVEN</th>
                                <th class="col text-center text-white"><i class="fas fa-calendar"></i> WAKTU MASUK</th>
                                <th class="col text-center text-white"><i class="fas fa-clipboard-list"></i> REMARK MASUK</th>
                                <th class="col text-center text-white"><i class="fas fa-user"></i> MASUK</th>
                                <th class="col text-center text-white"><i class="fas fa-clock"></i> TIMER</th>
                                <th class="col text-center text-white"><i class="fas fa-calendar"></i> WAKTU KELUAR</th>
                                <th class="col text-center text-white"><i class="fas fa-user"></i> KELUAR</th>
                                <th class="col text-center text-white"><i class="fas fa-clipboard-list"></i> REMARK KELUAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td class="text-center"><?= $dtl->oven ?></td>
                            <td class="text-center"><?= $dtl->masuk_oven_pada ?></td>
                            <td class="text-center"><?= $dtl->remark_into_oven ?>&nbsp;</td>
                            <td class="text-center"><?= $dtl->masuk_oven_oleh ?></td>
                            <td class="text-center">
                                <?php
                                if ($dtl->masuk_oven_pada == '') {
                                    echo null;
                                } else {
                                    $datetime1 = new DateTime($dtl->masuk_oven_pada);
                                    $datetime2 = new DateTime($dtl->keluar_oven_pada);
                                    $interval = $datetime2->diff($datetime1);
                                    echo $interval->format('%d') . " Hari, " . $interval->format('%h') . " Jam";
                                }
                                ?>
                            </td>
                            <td class="text-center"><?= $dtl->keluar_oven_pada ?></td>
                            <td class="text-center"><?= $dtl->keluar_oven_oleh ?></td>
                            <td class="text-center"><?= $dtl->remark_out_of_oven ?></td>

                        </tbody>
                    </table>
                </div>
                <hr class="devider" style="border: solid black 1px;">
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-center font-weight-bold bg-warning" style="margin-bottom: -5px;">Detail Alokasi Produksi</p>
                        <table class="table-sm table-striped table-bordered display compact nowrap" id="tbl_hst_alloc" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="col text-center text-white"><i class="fas fa-user"></i> ALOKASI</th>
                                    <th class="col text-center text-white"><i class="fas fa-calendar"></i> WAKTU ALOKASI</th>
                                    <th class="col text-center text-white"><i class="fas fa-clipboard-list"></i> REMARK ALOKASI</th>
                                    <th class="col text-center text-white">ALLOCATED TO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td class="text-center"><?= $dtl->alokasi_oleh ?>&nbsp;</td>
                                <td class="text-center"><?= $dtl->alokasi_pada ?></td>
                                <td class="text-center"><?= $dtl->remark_to_prd ?></td>
                                <td class="text-center"><?= $dtl->nama_product ?></td>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <p class="text-center font-weight-bold bg-warning" style="margin-bottom: -5px;">Detail Quantity</p>
                        <table class="table-sm table-striped table-bordered display compact nowrap" id="tbl_qty" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="col text-center text-white">MATERIAL</th>
                                    <th class="col text-center text-white">QUANTITY (PCS)</th>
                                    <th class="col text-center text-white">KUBIKASI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td class="text-center"><?= $dtl->kode ?></td>
                                <td class="text-center"><?= floatval($dtl->qty) ?></td>
                                <td class="text-center"><?= floatval($dtl->qty * (($dtl->tebal *  $dtl->lebar *  $dtl->panjang) / 1000000)) ?></td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(document).ready(function() {
        $('#tbl_identity_lot').DataTable({
            "ordering": false,
            "searching": false,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "info": false,
        });
        $('#tbl_hst_oven').DataTable({
            "ordering": false,
            "searching": false,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "info": false,
        });
        $('#tbl_hst_alloc').DataTable({
            "ordering": false,
            "searching": false,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "info": false,
        });
        $('#tbl_qty').DataTable({
            "ordering": false,
            "searching": false,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "info": false,
        });
    })
</script>