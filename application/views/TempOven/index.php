<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h5 class="text-center font-weight-bold">FORM CHECKLIST : <?= $oven->nama ?></h5>
                </div>
                <div class="card-body">
                    <form id="form_hdr" method="post" action="javascript:void(0)">
                        <input type="hidden" name="oven" id="oven" value="<?= $oven->sysid ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">Tanggal Pengecekan :</label>
                                    <input type="text" class="form-control" id="Doc_Date" name="Doc_Date" data-target="#Doc_Date" data-toggle="datetimepicker" value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <h5 class="text-center font-weight-bold">Kebersihan</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">A. PJ. Oven Ruang Boiler :</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="R_Boiler_Pj_Oven" name="R_Boiler_Pj_Oven" data-placeholder="Ruang Boiler PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">A. Maintenance Ruang Boiler :</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="R_Boiler_Mtc" name="R_Boiler_Mtc" data-placeholder="Ruang Boiler Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">A. M. Teknik Ruang Boiler :</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="R_Boiler_Teknik" name="R_Boiler_Teknik" data-placeholder="Ruang Boiler M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">B. PJ. Oven Cerobong :</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Cerobong_Pj_Oven" name="Cerobong_Pj_Oven" data-placeholder="Cerobong PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">B. Maintenance Cerobong :</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Cerobong_Mtc" name="Cerobong_Mtc" data-placeholder="Cerobong Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">B. M. Teknik Cerobong :</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Cerobong_Teknik" name="Cerobong_Teknik" data-placeholder="Cerobong M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">C. PJ. Oven Cyclon 1 :</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Cyclon1_Pj_Oven" name="Cyclon1_Pj_Oven" data-placeholder="Cyclon 1 PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">C. Maintenance Cyclon 1 :</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Cyclon1_Mtc" name="Cyclon1_Mtc" data-placeholder="Cyclon 1 Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">C. M. Teknik Cyclon 1 :</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Cyclon1_Teknik" name="Cyclon1_Teknik" data-placeholder="Cyclon 1 M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">D. PJ. Oven Cyclon 2 :</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Cyclon2_Pj_Oven" name="Cyclon2_Pj_Oven" data-placeholder="Cyclon 2 PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">D. Maintenance Cyclon 2 :</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Cyclon2_Mtc" name="Cyclon2_Mtc" data-placeholder="Cyclon 2 Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">D. M. Teknik Cyclon 2 :</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Cyclon2_Teknik" name="Cyclon2_Teknik" data-placeholder="Cyclon 2 M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">E. PJ. Oven Ruang Oven :</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="R_Oven_Pj_Oven" name="R_Oven_Pj_Oven" data-placeholder="Ruang Oven PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">E. Maintenance Ruang Oven :</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="R_Oven_Mtc" name="R_Oven_Mtc" data-placeholder="Ruang Oven Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">E. M. Teknik Ruang Oven :</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="R_Oven_Teknik" name="R_Oven_Teknik" data-placeholder="Ruang Oven M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-group-sm">
                                    <label for="">Catatan Kebersihan :</label>
                                    <textarea class="form-control" id="Catatan_Kebersihan" name="Catatan_Kebersihan" placeholder="Catatan Kebersihan..." rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <h5 class="text-center font-weight-bold">Pengecekan Fasilitas</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">A. PJ. Oven Boiler :</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_Boiler_Pj_Oven" name="Fas_Boiler_Pj_Oven" data-placeholder="Boiler PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">A. Maintenance Boiler :</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_Boiler_Mtc" name="Fas_Boiler_Mtc" data-placeholder="Boiler Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">A. M. Teknik Boiler :</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_Boiler_Teknik" name="Fas_Boiler_Teknik" data-placeholder="Boiler M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">B. PJ. Oven Pompa Sirkulasi :</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_PompaSirkulasi_Pj_Oven" name="Fas_PompaSirkulasi_Pj_Oven" data-placeholder="Pompa Sirkulasi PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">B. Maintenance Pompa Sirkulasi :</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_PompaSirkulasi_Mtc" name="Fas_PompaSirkulasi_Mtc" data-placeholder="Pompa Sirkulasi Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">B. M. Teknik Pompa Sirkulasi :</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_PompaSirkulasi_Teknik" name="Fas_PompaSirkulasi_Teknik" data-placeholder="Pompa Sirkulasi M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">C. PJ. Oven Blowler :</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_Blowler_Pj_Oven" name="Fas_Blowler_Pj_Oven" data-placeholder="Blowler PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">C. Maintenance Blowler :</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_Blowler_Mtc" name="Fas_Blowler_Mtc" data-placeholder="Blowler Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">C. M. Teknik Blowler :</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_Blowler_Teknik" name="Fas_Blowler_Teknik" data-placeholder="Blowler M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">D. PJ. Oven Kipas & Atap Gel :</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_Kipas_AtapGel_Pj_Oven" name="Fas_Kipas_AtapGel_Pj_Oven" data-placeholder="Kipas & Atap Gel PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">D. Maintenance Kipas & Atap Gel :</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_Kipas_AtapGel_Mtc" name="Fas_Kipas_AtapGel_Mtc" data-placeholder="Kipas & Atap Gel Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">D. M. Teknik Kipas & Atap Gel :</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_Kipas_AtapGel_Teknik" name="Fas_Kipas_AtapGel_Teknik" data-placeholder="Kipas & Atap Gel M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">E. PJ. Oven Demper :</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_Demper_Pj_Oven" name="Fas_Demper_Pj_Oven" data-placeholder="Demper PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">E. Maintenance Demper :</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_Demper_Mtc" name="Fas_Demper_Mtc" data-placeholder="Demper Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">E. M. Teknik Demper :</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_Demper_Teknik" name="Fas_Demper_Teknik" data-placeholder="Demper M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">F. PJ. Oven Air Toren Atas :</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_AirToren_Atas_Pj_Oven" name="Fas_AirToren_Atas_Pj_Oven" data-placeholder="Air Toren Atas PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">F. Maintenance Air Toren Atas :</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_AirToren_Atas_Mtc" name="Fas_AirToren_Atas_Mtc" data-placeholder="Air Toren Atas MaintenancF..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">F. M. Teknik Air Toren Atas :</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_AirToren_Atas_Teknik" name="Fas_AirToren_Atas_Teknik" data-placeholder="Air Toren Atas M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">G. PJ. Oven Air Toren Bawah :</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_AirToren_Bawah_Pj_Oven" name="Fas_AirToren_Bawah_Pj_Oven" data-placeholder="Air Toren Bawah PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">G. Maintenance Air Toren Bawah :</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_AirToren_Bawah_Mtc" name="Fas_AirToren_Bawah_Mtc" data-placeholder="Air Toren Bawah Maintenanc..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">G. M. Teknik Air Toren Bawah :</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_AirToren_Bawah_Teknik" name="Fas_AirToren_Bawah_Teknik" data-placeholder="Air Toren Bawah M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">H. PJ. Oven Sensor Inti Suhu Kayu:</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_Sensor_Inti_Suhu_Kayu_Pj_Oven" name="Fas_Sensor_Inti_Suhu_Kayu_Pj_Oven" data-placeholder="Sensor Inti Suhu Kayu PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">H. Maintenance Sensor Inti Suhu Kayu:</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_Sensor_Inti_Suhu_Kayu_Mtc" name="Fas_Sensor_Inti_Suhu_Kayu_Mtc" data-placeholder="Sensor Inti Suhu Kayu Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">H. M. Teknik Sensor Inti Suhu Kayu:</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_Sensor_Inti_Suhu_Kayu_Teknik" name="Fas_Sensor_Inti_Suhu_Kayu_Teknik" data-placeholder="Sensor Inti Suhu Kayu M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">I. PJ. Oven Sensor Mc</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_Sensor_Mc_Pj_Oven" name="Fas_Sensor_Mc_Pj_Oven" data-placeholder="Sensor Mc PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">I. Maintenance Sensor Mc</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_Sensor_Mc_Mtc" name="Fas_Sensor_Mc_Mtc" data-placeholder="Sensor Mc Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">I. M. Teknik Sensor Mc</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_Sensor_Mc_Teknik" name="Fas_Sensor_Mc_Teknik" data-placeholder="Sensor Mc M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">J. PJ. Oven Sensor DB/WB</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_Sensor_DB_WB_Pj_Oven" name="Fas_Sensor_DB_WB_Pj_Oven" data-placeholder="Sensor DB/WB PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">J. Maintenance Sensor DB/WB</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_Sensor_DB_WB_Mtc" name="Fas_Sensor_DB_WB_Mtc" data-placeholder="Sensor DB/WB Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">J. M. Teknik Sensor DB/WB</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_Sensor_DB_WB_Teknik" name="Fas_Sensor_DB_WB_Teknik" data-placeholder="Sensor DB/WB M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">K. PJ. Oven Air WB</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_Air_WB_Pj_Oven" name="Fas_Air_WB_Pj_Oven" data-placeholder="Air WB PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">K. Maintenance Air WB</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_Air_WB_Mtc" name="Fas_Air_WB_Mtc" data-placeholder="Air WB Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">K. M. Teknik Air WB</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_Air_WB_Teknik" name="Fas_Air_WB_Teknik" data-placeholder="Air WB M. Teknik...."></select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">L. PJ. Oven Kain Kasa</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_KainKasa_Pj_Oven" name="Fas_KainKasa_Pj_Oven" data-placeholder="Kain Kasa PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">L. Maintenance Kain Kasa</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_KainKasa_Mtc" name="Fas_KainKasa_Mtc" data-placeholder="Kain Kasa Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">L. M. Teknik Kain Kasa</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_KainKasa_Teknik" name="Fas_KainKasa_Teknik" data-placeholder="Kain Kasa M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">M. PJ. Oven Panel Box</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_PanelBox_Pj_Oven" name="Fas_PanelBox_Pj_Oven" data-placeholder="Panel Box PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">M. Maintenance Panel Box</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_PanelBox_Mtc" name="Fas_PanelBox_Mtc" data-placeholder="Panel Box Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">M. M. Teknik Panel Box</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_PanelBox_Teknik" name="Fas_PanelBox_Teknik" data-placeholder="Panel Box M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">N. PJ. Oven Panel DB/WB</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_Panel_DB_WB_Pj_Oven" name="Fas_Panel_DB_WB_Pj_Oven" data-placeholder="Panel DB/WB PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">N. Maintenance Panel DB/WB</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_Panel_DB_WB_Mtc" name="Fas_Panel_DB_WB_Mtc" data-placeholder="Panel DB/WB Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">N. M. Teknik Panel DB/WB</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_Panel_DB_WB_Teknik" name="Fas_Panel_DB_WB_Teknik" data-placeholder="Panel DB/WB M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">O. PJ. Oven Panel Suhu Inti Kayu</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Fas_Panel_Suhu_Inti_Kayu_Pj_Oven" name="Fas_Panel_Suhu_Inti_Kayu_Pj_Oven" data-placeholder="Panel Suhu Inti Kayu PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">O. Maintenance Panel Suhu Inti Kayu</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Fas_Panel_Suhu_Inti_Kayu_Mtc" name="Fas_Panel_Suhu_Inti_Kayu_Mtc" data-placeholder="Panel Suhu Inti Kayu Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">O. M. Teknik Panel Suhu Inti Kayu</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="Fas_Panel_Suhu_Inti_Kayu_Teknik" name="Fas_Panel_Suhu_Inti_Kayu_Teknik" data-placeholder="Panel Suhu Inti Kayu M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-group-sm">
                                    <label for="">Catatan Fasilitas :</label>
                                    <textarea class="form-control" id="Catatan_Fasilitas" name="Catatan_Fasilitas" placeholder="Catatan Fasilitas..." rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">Penjaga Oven</label>
                                    <select required="true" class="form-select form-control pjoven" data-control="select2" id="Pj_Oven" name="Pj_Oven" data-placeholder="PJ. Oven..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">Maintenance</label>
                                    <select required="true" class="form-select form-control maintenance" data-control="select2" id="Maintenance" name="Maintenance" data-placeholder="Maintenance..."></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <label for="">M. Teknik</label>
                                    <select required="true" class="form-select form-control teknik" data-control="select2" id="M_Teknik" name="M_Teknik" data-placeholder="M. Teknik...."></select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class=" card-footer">
                    <button type="button" value="RUN" id="run_save" class="btn btn-lg btn-primary"><b>Simpan & Jalankan Oven</b> &nbsp;&nbsp;<i class="fas fa-save"></i></button>
                    <!-- <button type="button" value="WAIT" id="wait_save" class="btn btn-lg btn-warning"><b>Simpan & Tunggu</b> &nbsp;&nbsp;<i class="fas fa-save"></i></button> -->
                    <button type="button" id="back" class="btn btn-lg btn-danger float-right">Batal &nbsp;&nbsp;<i class="fas fa-arrow-left"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div id="location">

    </div>
</div>