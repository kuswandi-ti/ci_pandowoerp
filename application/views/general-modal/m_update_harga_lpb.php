<div class="modal fade" id="modal-update-harga-lpb" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Manage Harga LPB Perukuran Material : <?= $Hdr->lpb ?> (<?= $supplier->Account_Name ?>)</h5>
            </div>
            <div class="modal-body">
                <style>
                    #tbl-list_filter {
                        float: left;
                        width: 100%;
                    }

                    #tbl-list_filter label {
                        width: 100%;
                    }
                </style>
                <div class="table-responsive">
                    <input type="hidden" id="lpb" name="lpb" value="<?= $Hdr->lpb ?>">
                    <table id="tbl-list" class="table table-sm table-bordered table-striped " style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">KODE</th>
                                <th class="text-center text-white">MATERIAL</th>
                                <th class="text-center text-white">TEBAL</th>
                                <th class="text-center text-white">LEBAR</th>
                                <th class="text-center text-white">PANJANG</th>
                                <th class="text-center text-white">HARGA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($rowDatas as $li) : ?>
                                <tr id="<?= $li->sysid_material ?>">
                                    <td class="text-center"><?= $i ?></td>
                                    <td><?= $li->kode ?></td>
                                    <td><?= $li->deskripsi ?></td>
                                    <td class="text-center"><?= floatval($li->tebal) ?> <i>cm</i></td>
                                    <td class="text-center"><?= floatval($li->lebar) ?> <i>cm</i></td>
                                    <td class="text-center"><?= floatval($li->panjang) ?> <i>cm</i></td>
                                    <td>
                                        <a class="editable-price" data-pk="<?= $li->sysid_material ?>" href="javascript:void(0)">
                                            <?= number_format($li->harga_per_pcs, 2, '.', ',') ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <a href="" type="button" class="btn btn-danger">Close</a>
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
            '<button type="submit" class="btn btn-primary btn-sm editable-submit mt-1">' +
            '<i class="fa fa-fw fa-check"></i>' +
            '</button>&nbsp;&nbsp;' +
            '<button type="button" class="btn btn-warning btn-sm editable-cancel mt-1">' +
            '<i class="fa fa-fw fa-times"></i>' +
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
            dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
            "autoWidth": true,
            orderCellsTop: true,
            fixedHeader: {
                header: true,
                headerOffset: 48
            },
            "buttons": [{
                extend: 'excelHtml5',
                title: $('#modal-title').text(),
                className: "btn btn-success",
            }, {
                extend: 'pdfHtml5',
                title: $('#modal-title').text(),
                className: "btn btn-danger",
            }],
        }).buttons().container().appendTo('#tbl-list.col-md-6:eq(0)');

        $('.editable-price').editable({
            ajaxOptions: {
                dataType: 'json'
            },
            type: 'number',
            url: $('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/update_harga_lpb/" + $('#lpb').val(),
            title: 'Qty...',
            validate: function(value) {
                if ($.trim(value) == '') {
                    return Toast.fire({
                        icon: 'error',
                        title: 'Peringatan!',
                        text: 'harga tidak boleh dikosongkan!'
                    });
                }
                if ($.trim(value) == undefined) {
                    return Toast.fire({
                        icon: 'error',
                        title: 'Peringatan!',
                        text: 'harga tidak valid!'
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