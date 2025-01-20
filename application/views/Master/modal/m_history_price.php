<div class="modal fade" id="modal-price-history" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">History Perubahan Harga <?= $material->deskripsi ?> (<?= floatval($material->tebal) ?>-<?= floatval($material->lebar) ?>-<?= floatval($material->panjang) ?>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- The time line -->
                        <div class="timeline">

                            <?php foreach ($historis as $li) : ?>
                                <div class="time-label">
                                    <span class="bg-red"><?php $date = date_create($li->do_at);
                                                            echo date_format($date, 'd F Y') ?></span>
                                </div>
                                <div>
                                    <i class="far fa-calendar bg-warning"></i>
                                    <div class="timeline-item">
                                        <span class="time text-black text-dark"><i class="fas fa-clock"></i> <?= date_format($date, 'H:i') ?></span>
                                        <h3 class="timeline-header"><a href="#">Changed by : </a> <?= $li->do_by ?></h3>
                                        <div class="timeline-body">
                                            <?= $li->action_is ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                    <!-- /.col -->
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
<!-- <script>
    $(function() {
        $('#table_lot_by_deskripsi').DataTable();
    })
</script> -->