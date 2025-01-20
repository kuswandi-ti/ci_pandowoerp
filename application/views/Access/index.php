<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-danger card-outline">
                <div class="card-body">
                    <form method="POST" id="form_add" action="#">
                        <div class="row">
                            <div class="col-sm-3 col-lg-3">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-danger" id="btn-choose-user" data-toggle="modal" data-target="#modal-list-karyawan">Choose <i class="fas fa-user"></i></button>
                                    </div>
                                    <input type="text" class="form-control readonly" name="nik" id="nik" placeholder="NIK...">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm readonly" name="nama" id="nama" placeholder="Nama ...">
                                </div>
                            </div>
                            <div class="col-sm-2 col-lg-2">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm readonly" placeholder="Inisial ..." name="init" id="init">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm readonly" placeholder="Department ..." name="dept" id="dept">
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="devider" />
                    <div class="table-responsive">
                        <table id="DataTable-Access" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">#</th>
                                    <th class="text-center text-white">GROUP MENU</th>
                                    <th class="text-center text-white" style="display: none;;">sysid_parent</th>
                                    <th class="text-center text-white">MAIN MENU</th>
                                    <th class="text-center text-white" style="display: none;;">sysid_child</th>
                                    <th class="text-center text-white">SUB MENU</th>
                                    <th class="text-center text-white" style="display: none;;">sysid_access</th>
                                    <th class="text-center text-white">ACCESS</th>

                                </tr>
                            </thead>
                            <tbody>
                                <!-- hi dude i dude some magic here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="location">
        <div class="modal fade" id="modal-list-karyawan" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">LIST KARYAWAN AKTIF</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl-modal-list-karyawan" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                                    <thead style="background-color: #3B6D8C;">
                                        <tr>
                                            <th class="text-center text-white">#</th>
                                            <th class="text-center text-white">NIK</th>
                                            <th class="text-center text-white">NAMA</th>
                                            <th class="text-center text-white">INIT</th>
                                            <th class="text-center text-white">DEPARTMENT</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-select-user"><i class="fas fa-user-plus"></i> Select</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
</div>