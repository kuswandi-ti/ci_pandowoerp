<div class="modal fade" id="modal-list" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Structure Product : <?= $Hdr->Nama ?> (<?= $Hdr->Kode ?>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 70vh;">
                <div class="table-responsive">
                    <table id="tbl-list" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <!-- Bentuk, Tebal, Lebar, Panjang, Pcs, Remark -->
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">Bentuk Material</th>
                                <th class="text-center text-white">Tebal</th>
                                <th class="text-center text-white">Lebar</th>
                                <th class="text-center text-white">Panjang</th>
                                <th class="text-center text-white">Qty</th>
                                <th class="text-center text-white">Remark</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($Dtls as $li) : ?>
                                <tr id="<?= $li->sysid ?>">
                                    <td class="text-center"><?= $i; ?></td>
                                    <td class="text-center"><?= $li->Bentuk ?></td>
                                    <td class="text-center"><?= floatval($li->Tebal) ?></td>
                                    <td class="text-center"><?= floatval($li->Lebar) ?></td>
                                    <td class="text-center"><?= floatval($li->Panjang) ?></td>
                                    <td class="text-center"><?= floatval($li->Pcs) ?></td>
                                    <td class="text-center"><?= $li->Remark ?></td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
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
        var table = $("#tbl-list").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "oLanguage": {
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
            },
            orderCellsTop: true,
            fixedHeader: {
                header: true,
                headerOffset: 48
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
                }, "print", "colvis"
            ],
        }).buttons().container().appendTo('#tbl-list_wrapper .col-md-6:eq(0)');
    })
</script>