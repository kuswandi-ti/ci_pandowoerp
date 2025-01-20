<div class="modal fade" id="modal-edit-karyawan" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Karyawan : <?= $data->initial ?></h5>
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
                                    <span class="input-group-text" style="width: 130px;">Nama lengkap :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Nama..." required name="nama" id="nama" value="<?= $data->nama ?>">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Initial :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="initial..." required name="initial" id="initial" value="<?= $data->initial ?>">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Telpon/Wa :</span>
                                </div>
                                <input type="text" class="form-control" minlength="10" placeholder="Telpon..." required name="telp" id="telp" value="<?= $data->telp1 ?>">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Kontak Darurat :</span>
                                </div>
                                <input type="text" class="form-control" minlength="10" placeholder="Telpon..." name="telp2" id="telp2" value="<?= $data->telp2 ?>">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">No. KTP :</span>
                                </div>
                                <input type="ktp" class="form-control" required placeholder="ktp..." name="no_ktp" id="no_ktp" value="<?= $data->no_ktp ?>">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Tempat Lahir :</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Tempat lahir..." required name="tempat_lahir" id="tempat_lahir" value="<?= $data->tempat_lahir ?>">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Tgl Lahir :</span>
                                </div>
                                <input type="text" class="form-control datepicker" data-toggle="datetimepicker" data-target="#tanggal_lahir" placeholder="Tgl lahir..." required name="tanggal_lahir" id="tanggal_lahir" value="<?= $data->tanggal_lahir ?>">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Alamat Ktp :</span>
                                </div>
                                <textarea type="text" class="form-control" placeholder="Alamat..." required name="alamat_ktp" id="alamat_ktp"><?= $data->alamat_ktp ?></textarea>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Alamat Domisili :</span>
                                </div>
                                <textarea type="text" class="form-control" placeholder="Alamat..." required name="domisili" id="domisili"><?= $data->domisili ?></textarea>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Jenis Kelamin :</span>
                                </div>
                                <select type="text" class="form-control" required name="jen_kel" id="jen_kel">
                                    <option value="<?= $data->jenis_kelamin  ?>"><?= $data->jenis_kelamin ?></option>
                                    <option value="LAKI-LAKI">LAKI-LAKI</option>
                                    <option value="PEREMPUAN">PEREMPUAN</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Agama :</span>
                                </div>
                                <select type="text" class="form-control" required name="agama" id="agama">
                                    <option value="<?= $data->agama ?>"><?= $data->agama ?></option>
                                    <option value="ISLAM">ISLAM</option>
                                    <option value="KATOLIK">KATOLIK</option>
                                    <option value="PROTESTAN">PROTESTAN</option>
                                    <option value="KONGHUCU">KONGHUCU</option>
                                    <option value="BUDHA">BUDHA</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" required style="width: 130px;">Department :</span>
                                </div>
                                <select type="text" class="form-control" name="department" id="department">
                                    <option value="<?= $data->department ?>"><?= $data->department ?></option>
                                    <?php foreach ($departments as $li) : ?>
                                        <option value="<?= $li->name ?>"><?= $li->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Jabatan :</span>
                                </div>
                                <select type="text" class="form-control" required name="jabatan" id="jabatan">
                                    <option value="<?= $data->jabatan ?>"><?= $data->jabatan ?></option>
                                    <?php foreach ($jabatans as $li) : ?>
                                        <option value="<?= $li->name ?>"><?= $li->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 130px;">Type Salary :</span>
                                </div>
                                <select type="text" class="form-control" required name="type_pembayaran" id="type_pembayaran">
                                    <option value="<?= $data->type_pembayaran  ?>"><?= $data->type_pembayaran ?></option>
                                    <option value="HARI">HARI</option>
                                    <option value="BULAN">BULAN</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            viewMode: 'years'
        });
        $('#form-edit-modal').validate({
            rules: {
                nama: {
                    required: true,
                }
            },
            messages: {
                nama: {
                    required: "Nama Karyawan tidak boleh kosong!",
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
            if ($("#form-edit-modal").valid()) {
                Fn_Submit_Edit($('#form-edit-modal').serialize());
            } else {
                $('html, body').animate({
                    scrollTop: ($('.error:visible').offset().top - 200)
                }, 400);
            }
        });


        function Fn_Submit_Edit(DataForm) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: $('meta[name="base_url"]').attr('content') + "MasterData/Employee/store_edit_karyawan",
                data: DataForm,
                beforeSend: function() {
                    $("#submit-edit").prop("disabled", true);
                    $("#submit-edit").html(
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
                    )
                },
                success: function(response) {
                    if (response.code == 200) {
                        $('#modal-edit-karyawan').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: response.msg
                        });
                        $('#' + response.id).find('td:eq(1)').html($('#nama').val())
                        $('#' + response.id).find('td:eq(3)').html($('#type_pembayaran').val())
                        $('#' + response.id).find('td:eq(4)').html($('#telp').val())
                        $('#' + response.id).find('td:eq(5)').html($('#jabatan').val())
                        $('#' + response.id).find('td:eq(6)').html($('#department').val())
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan teknis segera lapor pada admin!',
                        footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                    });
                    $('#modal-edit-karyawan').modal('hide');
                }
            });
        }
    })
</script>