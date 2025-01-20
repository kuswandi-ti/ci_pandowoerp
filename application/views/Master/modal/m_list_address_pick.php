<div class="modal fade" id="modal-list-address" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 80%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">List Address : <?= $Hdr->Customer_Name ?> (<?= $Hdr->Customer_Code ?>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tbl-list-address" class="table table-sm table-bordered table-striped" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white" style="width: 35%;">Detail Address</th>
                                <th class="text-center text-white">City</th>
                                <th class="text-center text-white">Postal_Code</th>
                                <th class="text-center text-white">Phone</th>
                                <th class="text-center text-white">Email</th>
                                <th class="text-center text-white">Fax</th>
                                <th class="text-center text-white">Koresponden</th>
                                <th class="text-center text-white">SysId</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($Dtls as $li) : ?>
                                <tr id="<?= $li->SysId ?>">
                                    <td class="text-center"><?= $i; ?></td>
                                    <td class="text-center" style="width: 35%;"><?= $li->Address ?></td>
                                    <td class="text-center"><?= $li->City ?></td>
                                    <td class="text-center"><?= $li->Postal_Code ?></td>
                                    <td class="text-center"><?= $li->Phone ?></td>
                                    <td class="text-center"><?= $li->Email ?></td>
                                    <td class="text-center"><?= $li->Fax ?></td>
                                    <td class="text-center"><?= $li->Koresponden ?></td>
                                    <td class="text-center"><?= $li->SysId ?></td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit--add"><i class="fas fa-check"></i> Choose Address</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        var TableData = $("#tbl-list-address").DataTable({
            "responsive": true,
            "lengthChange": true,
            "select": true,
            "autoWidth": true,
            "oLanguage": {
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
            },
            columnDefs: [{
                targets: 8,
                visible: false,
            }],
            orderCellsTop: true,
            fixedHeader: {
                header: true,
                headerOffset: 48
            }
        })

        $(document).on('click', '#submit--add', function() {
            var rowData = TableData.rows({
                selected: true
            }).data()[0];

            if (rowData == undefined || rowData.length == 0) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'You need select customer address !',
                    footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                });
            }

            $('#id_address').val(rowData[8]);
            $('#customer_address').val(rowData[1]);
            $('#modal-list-address').modal('hide');
        })
    })
</script>