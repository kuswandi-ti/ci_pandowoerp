<div class="modal fade" id="modal-list" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">List Address : <?= $Hdr->Customer_Name ?> (<?= $Hdr->Customer_Code ?>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- , , NPWP, Koresponden, is_active, Created_by, Created_at, Last_updated_by, Last_updated_at -->

            <!-- , , , , , ,  -->
            <div class="modal-body" style="height: 70vh;">
                <div class="table-responsive">
                    <table id="tbl-list" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <!-- Bentuk, Tebal, Lebar, Panjang, Pcs, Remark -->
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">Detail Address</th>
                                <th class="text-center text-white">City</th>
                                <th class="text-center text-white">Postal_Code</th>
                                <th class="text-center text-white">Phone</th>
                                <th class="text-center text-white">Email</th>
                                <th class="text-center text-white">Fax</th>
                                <th class="text-center text-white">Koresponden</th>
                                <th class="text-center text-white">Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($Dtls as $li) : ?>
                                <tr id="<?= $li->SysId ?>">
                                    <td class="text-center"><?= $i; ?></td>
                                    <td class="text-center"><?= $li->Address ?></td>
                                    <td class="text-center"><?= $li->City ?></td>
                                    <td class="text-center"><?= $li->Postal_Code ?></td>
                                    <td class="text-center"><?= $li->Phone ?></td>
                                    <td class="text-center"><?= $li->Email ?></td>
                                    <td class="text-center"><?= $li->Fax ?></td>
                                    <td class="text-center"><?= $li->Koresponden ?></td>
                                    <td class="text-center">
                                        <?php if ($li->is_active == 1) : ?>
                                            <button data-toggle="tooltip" data-pk="<?= $li->SysId ?>" data-original-title="Non-aktifkan" class="btn btn-sm bg-gradient-success is-active-address blink_me"><i class="fas fa-check"></i></button>
                                        <?php else : ?>
                                            <button data-toggle="tooltip" data-pk="<?= $li->SysId ?>" data-original-title="Aktifkan" class="btn btn-sm bg-gradient-secondary is-active-address"><i class="fas fa-times"></i></button>
                                    </td>
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

        $(document).on('click', '.is-active-address', function() {
            var this_is = $(this);
            Swal.fire({
                title: 'System Message!',
                text: `Apakah anda yakin untuk merubah status customer ini ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: $('meta[name="base_url"]').attr('content') + "Master/toggle_status_address",
                        data: {
                            sysid: $(this).attr('data-pk')
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Loading....',
                                html: '<div class="spinner-border text-primary"></div>',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            })
                        },
                        success: function(response) {
                            Swal.close()
                            if (response.code == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success...',
                                    text: response.msg,
                                    footer: '<a href="javascript:void(0)" class="text-info">Notifikasi System</a>'
                                });

                                if (response.is_active == 1) {
                                    this_is.removeClass('bg-gradient-secondary');
                                    this_is.addClass('bg-gradient-success');
                                    this_is.html(`<i class="fas fa-check-circle"></i>`);
                                } else {
                                    this_is.removeClass('bg-gradient-success');
                                    this_is.addClass('bg-gradient-secondary');
                                    this_is.html(`<i class="fas fa-times-circle"></i>`);
                                }

                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Warning!',
                                    text: response.msg,
                                    footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                                });
                            }
                        },
                        error: function() {
                            Swal.close()
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan teknis segera lapor pada admin!',
                                footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                            });
                        }
                    });
                }
            })
        })
    })
</script>