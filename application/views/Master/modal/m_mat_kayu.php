<div class="modal fade" id="modal-edit-material-kayu" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Material : <?= $data->inisial_kode ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-edit" method="POST" action="#">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" name="sysid" id="sysid" value="<?= $data->sysid ?>">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Kode :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Kode..." readonly name="kode" id="kode" value="<?= $data->kode ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Inisial :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Inisial..." readonly name="inisial" id="inisial" value="<?= $data->inisial_kode ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Deskripsi :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Deskripsi..." required name="deskripsi" id="deskripsi" readonly value="<?= $data->deskripsi ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Tebal (CM) :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Tebal..." readonly name="tebal" id="tebal" value="<?= floatval($data->tebal) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Lebar (CM) :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Lebar..." readonly name="lebar" id="lebar" value="<?= floatval($data->lebar) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Panjang (CM) :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Panjang..." readonly name="panjang" id="panjang" value="<?= floatval($data->panjang) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Standar Tumpukan:</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Standar tumpukan per LOT..." required name="std_qty_lot" id="std_qty_lot" value="<?= floatval($data->std_qty_lot) ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <div class="form-group">
                    <div class="input-group mb-3">
                        <label for="form-control-label form-control text-center">Ukuran dalam satuan CM & untuk decimal harap menggunakan (.) titik</label>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" id="submit-edit">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
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
        })
        $('#form-edit').validate({
            rules: {
                deskripsi: {
                    required: true,
                }
            },
            messages: {
                deskripsi: {
                    required: "Deskripsi tidak boleh kosong!",
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.input-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
        $.validator.setDefaults({
            debug: true,
            success: 'valid'
        });

        $('#submit-edit').click(function(e) {
            e.preventDefault();
            if ($("#form-edit").valid()) {
                Fn_Submit_Edit_($('#form-edit').serialize());
            } else {
                $('html, body').animate({
                    scrollTop: ($('.error:visible').offset().top - 200)
                }, 400);
            }
        });


        function Fn_Submit_Edit_(DataForm) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: $('meta[name="base_url"]').attr('content') + "Master/store_edit_mat_kayu",
                data: DataForm,
                beforeSend: function() {
                    $("#submit-edit").prop("disabled", true);
                    $("#submit-edit").html(
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
                    )
                },
                success: function(response) {
                    if (response.code == 200) {
                        $('#modal-edit-material-kayu').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: response.msg
                        });

                        // $('#' + response.id).find('td:eq(2)').html($('#deskripsi').val())
                        $('#' + response.id).find('td:eq(6)').html(response.qty)
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan teknis segera lapor pada admin!',
                        footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                    });
                    $('#modal-edit-material-kayu').modal('hide');
                }
            });
        }
    })
</script>