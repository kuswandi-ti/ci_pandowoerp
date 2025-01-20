<div class="modal fade" id="modal-manage-harga" data-backdrop="static" data-keyboard="false">
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
                                <th class="text-center text-white">Kode</th>
                                <th class="text-center text-white">Inisial</th>
                                <th class="text-center text-white">Deskripsi</th>
                                <th class="text-center text-white">T</th>
                                <th class="text-center text-white">L</th>
                                <th class="text-center text-white">P</th>
                                <th class="text-center text-white">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($materials->result() as $li) : ?>
                                <tr id="<?= $li->sysid ?>">
                                    <td><?= $li->kode ?></td>
                                    <td><?= $li->inisial_kode ?></td>
                                    <td><?= $li->deskripsi ?></td>
                                    <td><?= floatval($li->tebal) ?> Cm</td>
                                    <td><?= floatval($li->lebar) ?> Cm</td>
                                    <td><?= floatval($li->panjang) ?> Cm</td>
                                    <?php
                                    $price = $this->db->get_where('ttrx_harga_material_supplier', ['sysid_supplier' => $supplier->sysid, 'sysid_material' => $li->sysid])->row();
                                    ?>
                                    <?php if (!empty($price)) : ?>
                                        <td class="text-center"><a href="javascript:void(0)" data-pk="<?= $li->sysid ?>" class="editable_price"><?= number_format($price->harga_per_pcs, 2, '.', ',') ?></a></td>
                                    <?php else : ?>
                                        <td class="text-center"><a href="javascript:void(0)" data-pk="<?= $li->sysid ?>" class="editable_price"><?= 0 ?></a></td>
                                    <?php endif; ?>
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
<script src="<?= base_url() ?>assets/global-assets/x-editable/dist/bootstrap4-editable/js/bootstrap-editable.min.js"></script>
<script>
    $(document).ready(function() {
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editableform.buttons =
            '<button type="submit" class="btn btn-primary btn-xs editable-submit mt-1">' +
            '<i class="fa fa-fw fa-check"></i> | Save' +
            '</button>&nbsp;&nbsp;' +
            '<button type="button" class="btn btn-warning btn-xs editable-cancel mt-1">' +
            '<i class="fa fa-fw fa-times"></i> | Cancel' +
            '</button>';

        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            width: 300,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        var table = $("#tbl-list").DataTable({
            "responsive": true,
            "lengthChange": true,
            "pageLength": 100,
            // "select": true,
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

        $('.editable_price').editable({
            ajaxOptions: {
                dataType: 'json'
            },
            type: 'number',
            url: $('meta[name="base_url"]').attr('content') + "Master/store_editable_material_price/<?= $supplier->sysid ?>",
            title: 'Qty...',
            validate: function(value) {
                if ($.trim(value) == '') {
                    return Toast.fire({
                        icon: 'error',
                        title: 'Peringatan!',
                        text: 'Harga tidak boleh dikosongkan!'
                    });
                }
                if ($.trim(value) == undefined) {
                    return Toast.fire({
                        icon: 'error',
                        title: 'Peringatan!',
                        text: 'Harga tidak valid!'
                    });
                }
            },
            success: function(response, newValue) {
                if (response.code == 200) {
                    Toast.fire({
                        icon: 'success',
                        title: response.msg
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan teknis segera lapor pada admin!',
                        footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                    });
                }
            }
        });
    })
</script>