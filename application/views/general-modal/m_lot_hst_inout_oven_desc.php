<div class="modal fade" id="modal_list_lot_by_deskripsi" data-backdrop="static" data-keyboard="false">
    <style>
        #table_lot_by_deskripsi_filter {
            float: left;
        }

        #table_lot_by_deskripsi_filter label input {
            width: 50vh;
        }
    </style>
    <div class="modal-dialog" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title"><?= $title_modal ?>, ITEM CODE : <?= $row_material->Item_Code ?> (<?= 'T' . floatval($Size->Item_Height) . '-L' . floatval($Size->Item_Width) . '-P' . floatval($Size->Item_Length) ?>) | <i class="fas fa-calendar"></i> <?= date('Y-m-d') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="table_lot_by_deskripsi" class="table table-sm table-striped table-bordered display compact nowrap" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">LPB</th>
                                <th class="text-center text-white">BUNDLE</th>
                                <th class="text-center text-white">QTY</th>
                                <th class="text-center text-white">KUBIKASI</th>
                                <th class="text-center text-white">SUPPLIER</th>
                                <th class="text-center text-white">GRADER</th>
                                <th class="text-center text-white"><i class="fas fa-calendar"></i> KIRIM</th>
                                <!-- <th class="text-center text-white"><i class="fas fa-calendar"></i> FINISH GRID</th> -->
                                <th class="text-center text-white"><i class="fas fa-map-marker-alt"></i> OVEN</th>
                                <th class="text-center text-white"><i class="fas fa-calendar"></i> MASUK</th>
                                <th class="text-center text-white"><i class="far fa-clock"></i> TIMER</th>
                                <th class="text-center text-white"><i class="far fa-calendar"></i> KELUAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($list_lot->result() as $li) : ?>
                                <tr>
                                    <td class="text-center align-middle"><?= $i; ?></td>
                                    <td class="text-center align-middle"><?= $li->lpb ?></td>
                                    <td class="text-center align-middle"><?= $li->no_lot ?></td>
                                    <td class="text-center align-middle"><?= $li->qty ?></td>
                                    <td class="text-center align-middle"><?= $this->help->roundToFourDecimals($li->kubikasi) ?></td>
                                    <td class="text-center align-middle"><?= $li->nama ?></td>
                                    <td class="text-center align-middle"><?= $li->grader ?></td>
                                    <td class="text-center align-middle"><?= $li->tgl_kirim ?></td>
                                    <!-- <td class="text-center align-middle"><= $li->tgl_finish_sortir ?></td> -->
                                    <td class="text-center align-middle"><span class="badge badge-default"><?= ucwords($li->nama_oven) ?></span></td>
                                    <td class="text-center align-middle"><small><b><?= $li->do_time_in ?></b></small></td>
                                    <td class="text-center align-middle">
                                        <?php if (intval(substr($li->timer, 0, 2)) >= 6) : ?>
                                            <span class="badge badge-danger blink_me"><i><?= $li->timer ?></i></span>
                                        <?php else : ?>
                                            <span class="badge badge-xs badge-success"><?= $li->timer ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><small><b><?= $li->do_time_out ?></b></small></td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer float-right">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(function() {
        $('#table_lot_by_deskripsi').DataTable({
            dom: 'lBfrtip',
            "oLanguage": {
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
            },
            "buttons": ["copy",
                {
                    extend: 'csvHtml5',
                    title: $('#modal-title').text(),
                    className: "btn btn-info",
                }, {
                    extend: 'excelHtml5',
                    title: $('#modal-title').text(),
                    className: "btn btn-success",
                }, {
                    extend: 'pdfHtml5',
                    title: $('#modal-title').text(),
                    className: "btn btn-danger",
                    orientation: 'landscape',
                }, "print"
            ],
        }).buttons().container().appendTo('#table_lot_by_deskripsi .col-md-6:eq(0)');
    })
</script>