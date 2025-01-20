<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Add new karyawan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form class="form-horizontal" id="form_add">
                            <div class="card-body">
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Nama :</labe>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-sm form-control-border" required name="nama" id="nama" placeholder="Nama..." onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Inisial :</labe>
                                    <div class="col-sm-5">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm form-control-border" required name="initial" id="initial" placeholder="Initial..." onkeyup="this.value = this.value.toUpperCase();">
                                            <span class="input-group-append">
                                                <button type="button" id="ConfirmInit" class="btn btn-danger btn-flat"><i class="fas fa-eye"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Tanggal Join :</labe>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-sm form-control-border readonly datepicker" required name="since" id="since" placeholder="Tanggal Lahir..." data-toggle="datetimepicker" data-target="#since">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">No. Ktp :</labe>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control form-control-sm form-control-border onlyfloat" required name="no_ktp" id="no_ktp" placeholder="No.Ktp ...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Tempat Lahir :</labe>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-sm form-control-border" required name="tempat_lahir" id="tempat_lahir" placeholder="Tempat Lahir..." onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Tanggal Lahir :</labe>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-sm form-control-border readonly datepicker" required name="tanggal_lahir" id="tanggal_lahir" placeholder="Tanggal Lahir..." data-toggle="datetimepicker" data-target="#tanggal_lahir">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Alamat (KTP) :</labe>
                                    <div class="col-sm-10">
                                        <textarea class="form-control form-control-sm form-control-border" required name="alamat_ktp" id="alamat_ktp" placeholder="Alamat (KTP) ..."></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Domisili :</labe>
                                    <div class="col-sm-10">
                                        <textarea class="form-control form-control-sm form-control-border" required name="domisili" id="domisili" placeholder="Domisili (tempat tinggal) ..."></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Jenis Kelamin :</labe>
                                    <div class="col-sm-10">
                                        <select class="form-control form-control-sm form-control-border" required name="jenis_kelamin" id="jenis_kelamin">
                                            <option selected disabled>-PILIH-</option>
                                            <option value="LAKI-LAKI">LAKI-LAKI</option>
                                            <option value="PEREMPUAN">PEREMPUAN</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Agama :</labe>
                                    <div class="col-sm-10">
                                        <select class="form-control form-control-sm form-control-border" required name="agama" id="agama">
                                            <option selected disabled>-PILIH-</option>
                                            <option value="ISLAM">ISLAM</option>
                                            <option value="KATOLIK">KATOLIK</option>
                                            <option value="PROTESTAN">PROTESTAN</option>
                                            <option value="KONGHUCU">KONGHUCU</option>
                                            <option value="BUDHA">BUDHA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Telpon/WA :</labe>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control form-control-sm form-control-border onlyfloat" required name="telp1" id="telp1" placeholder="Telpon atau Watsapp...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Telpon/WA 2 :</labe>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control form-control-sm form-control-border onlyfloat" name="telp2" id="telp2" placeholder="Telpon atau Watsapp...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Department :</labe>
                                    <div class="col-sm-10">
                                        <select class="form-control form-control-sm form-control-border" required name="department" id="department">
                                            <option selected disabled>-PILIH-</option>
                                            <?php foreach ($departments as $li) : ?>
                                                <option value="<?= $li->name ?>"><?= $li->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Jabatan :</labe>
                                    <div class="col-sm-10">
                                        <select class="form-control form-control-sm form-control-border" required name="jabatan" id="jabatan">
                                            <option selected disabled>-PILIH-</option>
                                            <?php foreach ($jabatans as $li) : ?>
                                                <option value="<?= $li->name ?>"><?= $li->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <labe class="col-sm-2 col-form-label">Type Salary :</labe>
                                    <div class="col-sm-10">
                                        <select class="form-control form-control-sm form-control-border" required name="type_pembayaran" id="type_pembayaran">
                                            <option selected disabled>-PILIH-</option>
                                            <option value="HARI">HARI</option>
                                            <option value="BULAN">BULAN</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <a href="<?= base_url('Master/index_master_karyawan') ?>" class="btn btn-danger float-right"><i class="fas fa-backward"></i> | Back</a>
                                <button type="submit" disabled class="btn btn-info" id="submit-form"><i class="fab fa-wpforms"></i> | SUBMIT</button>
                            </div>
                            <!-- /.card-footer -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>