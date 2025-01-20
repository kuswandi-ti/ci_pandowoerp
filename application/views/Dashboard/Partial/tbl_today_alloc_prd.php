<div class="card card-success card-outline">
    <div class="card-header">
        <h3 class="card-title">Rincian kayu Teralokasi ke Produksi hari ini</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="DataTable" class="table-bordered table-striped display nowrap" style="width: 100%;">
                <thead style="background-color: #28A745;">
                    <tr>
                        <th class="text-center text-white">#</th>
                        <th class="text-center text-white">NO. LOT</th>
                        <th class="text-center text-white">ITEM</th>
                        <th class="text-center text-white">KODE UKURAN</th>
                        <th class="text-center text-white">KUBIKASI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($data_today_alloc_prd as $li) : ?>
                        <tr>
                            <td class="text-center"><?= $i; ?></td>
                            <td class="text-center"><?= $li->no_lot ?></td>
                            <td class="text-center"><?= $li->Item_Name ?></td>
                            <td class="text-center"><?= $li->Size_Code ?></td>
                            <td class="text-center"><?= floatval($li->kubikasi) ?></td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>