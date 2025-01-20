<div class="modal fade" id="modal-history-harga" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Manage Harga Material : <?= $supplier->nama ?> (<?= $supplier->nama_kontak ?>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 70vh;">
                <div class="table-responsive">
                    <table id="tbl-list" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <!-- b.nama, c.kode , c.deskripsi ,a.action_is, a.do_at, a.do_by -->
                                <th class="text-center text-white">INISIAL</th>
                                <th class="text-center text-white">KODE</th>
                                <th class="text-center text-white">AKSI</th>
                                <th class="text-center text-white">PELAKU</th>
                                <th class="text-center text-white">WAKTU</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($rowDatas as $li) : ?>
                                <tr>
                                    <td><?= $li->kode ?></td>
                                    <td><?= $li->deskripsi ?></td>
                                    <td class="text-center"><?= $li->action_is ?></td>
                                    <td class="text-center"><?= $li->do_by ?></td>
                                    <td class="text-center"><?= $li->do_at ?></td>
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
            "select": true,
            "autoWidth": true,
            "pageLength": 50,
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