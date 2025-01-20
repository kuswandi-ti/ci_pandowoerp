<div class="modal fade" id="modal-edit-supplier" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supplier : <?= $data->nama ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-edit-modal" method="POST" action="#">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" name="sysid" id="sysid" value="<?= $data->sysid ?>">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Nama Supplier :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Nama..." required name="nama" id="nama" value="<?= $data->nama ?>">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Alamat :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Alamat..." name="alamat" id="alamat" value="<?= $data->alamat ?>">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Telpon/Wa :</span>
                                </div>
                                <input type="text" class="form-control" minlength="10" placeholder="Telpon..." name="telp" id="telp" value="<?= $data->telp ?>">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Email :</span>
                                </div>
                                <input type="email" class="form-control" placeholder="Email..." name="email" id="email" value="<?= $data->email ?>">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Koresponden :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="koresponden..." name="nama_kontak" id="nama_kontak" value="<?= $data->nama_kontak ?>">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">$ Bongkar/Kubik :</span>
                                </div>
                                <input type="number" class="form-control" placeholder="Uang Bongkar/kubik..." name="uang_bongkar" id="uang_bongkar" value="<?= floatval($data->uang_bongkar) ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submit-edit-supplier">Save changes</button>
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
        $('#form-edit-modal').validate({
            rules: {
                nama: {
                    required: true,
                }
            },
            messages: {
                nama: {
                    required: "Nama supplier tidak boleh kosong!",
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

        $('#submit-edit-supplier').click(function(e) {
            e.preventDefault();
            if ($("#form-edit-modal").valid()) {
                Fn_Submit_Edit_supplier($('#form-edit-modal').serialize());
            } else {
                $('html, body').animate({
                    scrollTop: ($('.error:visible').offset().top - 200)
                }, 400);
            }
        });


        function Fn_Submit_Edit_supplier(formSupplier) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: $('meta[name="base_url"]').attr('content') + "Master/store_edit_supplier",
                data: formSupplier,
                beforeSend: function() {
                    $("#submit-edit-supplier").prop("disabled", true);
                    $("#submit-edit-supplier").html(
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
                    )
                },
                success: function(response) {
                    if (response.code == 200) {
                        $('#modal-edit-supplier').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: response.msg
                        });

                        $('#' + response.id).find('td:eq(0)').html($('#nama').val())
                        $('#' + response.id).find('td:eq(1)').html($('#alamat').val())
                        $('#' + response.id).find('td:eq(2)').html($('#telp').val())
                        $('#' + response.id).find('td:eq(3)').html($('#email').val())
                        $('#' + response.id).find('td:eq(4)').html($('#nama_kontak').val())
                        $('#' + response.id).find('td:eq(5)').html(`Rp. &nbsp;&nbsp;&nbsp;` + $('#uang_bongkar').val())
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan teknis segera lapor pada admin!',
                        footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                    });
                    $('#modal-edit-supplier').modal('hide');
                }
            });
        }
    })
</script>