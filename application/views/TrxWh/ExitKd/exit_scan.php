<style>
    div.dt-buttons {
        clear: both;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h6 class="text-center" style="font-weight: bold;"><?= $page_title ?></h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <select type="text" class="form-control" id="placement" name="placement">
                                    <option value="" selected>-PENEMPATAN-</option>
                                    <?php foreach ($placements as $li) : ?>
                                        <option value="<?= $li->Warehouse_ID ?>"><?= $li->Warehouse_Name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" autofocus="autofocus" id="no_barcode" name="no_barcode">
                                <div class="input-group-prepend">
                                    <button type="button" data-toggle="tooltip" title="Melihat Detail Lot" id="preview--lot" class="btn bg-gradient-danger"> &nbsp;&nbsp;&nbsp;<b><i class="fas fa-barcode"></i></b>&nbsp;&nbsp;&nbsp; </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="button" data-toggle="tooltip" title="Menyatakan lot keluar oven" class="btn btn-success btn-lg float-center" id="submit--lotNo"><i class="fas fa-external-link-alt"></i> &nbsp; NYATAKAN SEBAGAI KELUAR KD</button>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12" id="container-detail-barcode">
            <div class="card card-danger card-outline">
                <div class="card-header text-center">
                    <h6 class="text-center"><i>DETAIL DATA BARCODE</i></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="Detail-Data-Lot" class="table-sm table-striped table-bordered" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="text-center text-white">BUNDLE</th>
                                    <th class="text-center text-white">SUPPLIER</th>
                                    <th class="text-center text-white">LPB</th>
                                    <th class="text-center text-white">MATERIAL</th>
                                    <th class="text-center text-white">QTY</th>
                                    <th class="text-center text-white">GRADER</th>
                                    <th class="text-center text-white">MASUK OVEN</th>
                                    <th class="text-center text-white">KUBIKASI</th>
                                    <th class="text-center text-white">OVEN</th>
                                    <th class="text-center text-white">TIMER</th>
                                    <th class="text-center text-white">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center" id="LOT"></td>
                                    <td class="text-center" id="SUPPLIER"></td>
                                    <td class="text-center" id="LPB"></td>
                                    <td class="text-center" id="MATERIAL"></td>
                                    <td class="text-center" id="QTY"></td>
                                    <td class="text-center" id="GRADER"></td>
                                    <td class="text-center" id="TIME-IN"></td>
                                    <td class="text-center" id="KUBIKASI"></td>
                                    <td class="text-center" id="OVEN"></td>
                                    <td class="text-center" id="TIMER"></td>
                                    <td class="text-center" id="STATUS"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="location">

    </div>
</div>