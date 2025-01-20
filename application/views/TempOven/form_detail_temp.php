<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-danger card-outline">
                <div class="card-body">
                    <form method="POST" id="form_add" action="#">
                        <input type="hidden" name="sysid_hdr" id="sysid_hdr" value="<?= $running_oven->SysId ?>">
                        <input type="hidden" name="id_oven" id="id_oven" value="<?= $oven->sysid ?>">
                        <div class="row">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="width: 12vh;">Oven</td>
                                        <td>:&nbsp;&nbsp;&nbsp;</td>
                                        <td><?= $oven->nama ?></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 12vh;">No. Doc</td>
                                        <td>:&nbsp;&nbsp;&nbsp;</td>
                                        <td><?= $running_oven->Doc_No ?></td>
                                        <input type="hidden" name="Doc_No" id="Doc_no" value="<?= $running_oven->Doc_No ?>">
                                    </tr>
                                    <tr>
                                        <td style="width: 12vh;">Revisi</td>
                                        <td>:&nbsp;&nbsp;&nbsp;</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 12vh;">Tanggal Doc</td>
                                        <td>:&nbsp;&nbsp;&nbsp;</td>
                                        <td><?= $running_oven->Doc_Date ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr class="devider" />
                        <div class="row">
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>Tanggal <i class="far fa-calendar-alt"></i></label>
                                    <input type="text" class="form-control form-control-sm" data-target="#Tgl" data-toggle="datetimepicker" placeholder="Waktu temperatur ..." required name="Tgl" id="Tgl" value="<?= date('Y-m-d') ?>" minlength="10" maxlength="10">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>Jam & Menit <i class="fas fa-clock"></i></label>
                                    <input type="text" class="form-control form-control-sm" data-target="#JamMenit" data-toggle="datetimepicker" placeholder="Waktu temperatur ..." required name="JamMenit" id="JamMenit" value="<?= date('H:i') ?>" minlength="5" maxlength="5">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <div class="form-group">
                                        <br />
                                        <label class="">KADAR AIR/MC %</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>MC 1</label>
                                    <input type="number" minlength="1" maxlength="6" class="onlyfloat form-control form-control-sm" name="MC1" id="MC1" placeholder="MC 1 ...">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>MC 2</label>
                                    <input type="number" minlength="1" maxlength="6" class="onlyfloat form-control form-control-sm" name="MC2" id="MC2" placeholder="MC 2 ...">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>MC 3</label>
                                    <input type="number" minlength="1" maxlength="6" class="onlyfloat form-control form-control-sm" name="MC3" id="MC3" placeholder="MC 3 ...">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <div class="form-group">
                                        <br />
                                        <label class="">SUHU INTI KAYU</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>T1</label>
                                    <input type="number" minlength="1" maxlength="6" class="onlyfloat form-control form-control-sm" required name="T1" id="T1" placeholder="T1 ...">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>T2</label>
                                    <input type="number" minlength="1" maxlength="6" class="onlyfloat form-control form-control-sm" required name="T2" id="T2" placeholder="T2 ...">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>T3</label>
                                    <input type="number" minlength="1" maxlength="6" class="onlyfloat form-control form-control-sm" required name="T3" id="T3" placeholder="T3 ...">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <div class="form-group">
                                        <br />
                                        <label class="">SUHU BOILER</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>SETTING</label>
                                    <input type="number" minlength="1" maxlength="6" class="onlyfloat form-control form-control-sm" required name="Boiler_Set" id="Boiler_Set" placeholder="Set ...">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>AKTUAL</label>
                                    <input type="number" minlength="1" maxlength="6" class="onlyfloat form-control form-control-sm" required name="Boiler_Act" id="Boiler_Act" placeholder="Act ...">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <div class="form-group">
                                        <br />
                                        <label class="">T. DRY BULB</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>SETTING</label>
                                    <input type="number" minlength="1" maxlength="6" class="onlyfloat form-control form-control-sm" required name="DryBulb_Set" id="DryBulb_Set" placeholder="Set ...">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>AKTUAL</label>
                                    <input type="number" minlength="1" maxlength="6" class="onlyfloat form-control form-control-sm" required name="DryBulb_Act" id="DryBulb_Act" placeholder="Act ...">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <div class="form-group">
                                        <br />
                                        <label class="">T. WET BULD</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>SETTING</label>
                                    <input type="number" minlength="1" maxlength="6" class="onlyfloat form-control form-control-sm" required name="WetBuld_Set" id="WetBuld_Set" placeholder="Set ...">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>AKTUAL</label>
                                    <input type="number" minlength="1" maxlength="6" class="onlyfloat form-control form-control-sm" required name="WetBuld_Act" id="WetBuld_Act" placeholder="Act ...">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <div class="form-group">
                                    <label>KETERANGAN</label>
                                    <input type="text" class="form-control form-control-sm" name="Keterangan" id="Keterangan" placeholder="Keterangan ...">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label>&nbsp;</label> <br>
                                    <button type="button" class="btn btn-sm btn-primary btn-block" id="submit-form">
                                        <i class="fas fa-download"></i> Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="devider" />
                    <div class="table-responsive">
                        <table id="DataTable-Temperature" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white" rowspan="2"><i class="fas fa-trash"></i></th>
                                    <th class="text-center text-white" rowspan="2">TANGGAL</th>
                                    <th class="text-center text-white" rowspan="2">WAKTU</th>
                                    <th class="text-center text-white" colspan="3">KADAR AIR/MC(%)</th>
                                    <th class="text-center text-white" colspan="3">SUHU INTI KAYU</th>
                                    <th class="text-center text-white" colspan="2">SUHU BOILER</th>
                                    <th class="text-center text-white" colspan="2">T.DRY BULB</th>
                                    <th class="text-center text-white" colspan="2">T.WET BULD</th>
                                    <th class="text-center text-white" rowspan="2">Keterangan</th>
                                    <th class="text-center text-white" rowspan="2">Petugas</th>
                                </tr>
                                <tr>
                                    <th class="text-center text-white">MC1</th>
                                    <th class="text-center text-white">MC2</th>
                                    <th class="text-center text-white">MC3</th>
                                    <th class="text-center text-white">T1</th>
                                    <th class="text-center text-white">T2</th>
                                    <th class="text-center text-white">T3</th>
                                    <th class="text-center text-white">SET</th>
                                    <th class="text-center text-white">ACT</th>
                                    <th class="text-center text-white">SET</th>
                                    <th class="text-center text-white">ACT</th>
                                    <th class="text-center text-white">SET</th>
                                    <th class="text-center text-white">ACT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- hi dude i dude some magic here -->
                            </tbody>
                        </table>
                    </div>
                    <hr class="devider" />
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <button class="btn bg-gradient-danger btn-lg font-weight-bold" id="btn-off"><i class="fas fa-power-off"></i>SELESAI & BERHENTIKAN PENCATATAN TEMPERATUR OVEN</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="location">

    </div>
</div>