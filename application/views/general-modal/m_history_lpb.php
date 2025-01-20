<div class="modal fade" id="modal-history-lpb" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">History Perubahan Data LPB : <?= $lpb ?></h5>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tbl-list" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <!-- no_lot, lpb, material, kode, sysid_material, `action`, price_before, price_after -->
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">LPB</th>
                                <th class="text-center text-white">NO. LOT</th>
                                <th class="text-center text-white">MATERIAL</th>
                                <th class="text-center text-white">KODE</th>
                                <th class="text-center text-white">ACTION</th>
                                <th class="text-center text-white">Price Before</th>
                                <th class="text-center text-white">Price After</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($historys as $li) : ?>
                                <tr id="<?= $li->sysid_material ?>">
                                    <td class="text-center"><?= $i ?></td>
                                    <td align="center"><?= $li->lpb ?></td>
                                    <td align="center"><?= $li->no_lot ?></td>
                                    <td><?= $li->material ?></td>
                                    <td><?= $li->kode ?></td>
                                    <td><?= $li->action ?></td>
                                    <td align="right">Rp. &nbsp;&nbsp;&nbsp;<?= number_format($li->price_before) ?></td>
                                    <td align="right">Rp. &nbsp;&nbsp;&nbsp;<?= number_format($li->price_after) ?></td>
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
    $(function() {
        $('#tbl-list').DataTable({
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
        }).buttons().container().appendTo('#tbl-list .col-md-6:eq(0)');
    })
</script>