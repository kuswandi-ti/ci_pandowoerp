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
                        <div class="col-sm-4 col-lg-4"></div>
                        <div class="col-sm-4 col-lg-4">
                            <div class="input-group input-group-sm">
                                <select type="text" class="form-control" id="oven" name="oven">
                                    <option value="" selected>-PILIH OVEN-</option>
                                    <?php foreach ($ovens as $li) : ?>
                                        <option value="<?= $li->sysid ?>"><?= $li->nama ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-lg-4"></div>
                        <div class="col-sm-4 col-lg-4">
                            <div class="input-group input-group-sm mb-3">
                                <input type="text" class="form-control" autofocus="autofocus" id="no_barcode" name="no_barcode">
                                <div class="input-group-prepend">
                                    <button type="button" data-toggle="tooltip" title="Melihat Detail Lot" id="preview--lot" class="btn bg-gradient-danger"> &nbsp;&nbsp;&nbsp;<b><i class="fas fa-barcode"></i></b>&nbsp;&nbsp;&nbsp; </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-lg-4"></div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="button" data-toggle="tooltip" title="Menyatakan lot masuk oven" class="btn btn-primary btn-lg float-center" id="submit--lotNo"><i class="fas fa-download"></i> | NYATAKAN SEBAGAI MASUK OVEN</button>
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
                                    <th class="text-center text-white">SUPPLIER</th>
                                    <th class="text-center text-white">LPB</th>
                                    <th class="text-center text-white">NO.LOT</th>
                                    <th class="text-center text-white">MATERIAL</th>
                                    <th class="text-center text-white">QTY</th>
                                    <th class="text-center text-white">KUBIKASI</th>
                                    <th class="text-center text-white">GRADER</th>
                                    <th class="text-center text-white">KIRIM</th>
                                    <th class="text-center text-white">FINISH</th>
                                    <th class="text-center text-white">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center" id="SUPPLIER"></td>
                                    <td class="text-center" id="LPB"></td>
                                    <td class="text-center" id="LOT"></td>
                                    <td class="text-center" id="MATERIAL"></td>
                                    <td class="text-center" id="QTY"></td>
                                    <td class="text-center" id="KUBIKASI"></td>
                                    <td class="text-center" id="GRADER"></td>
                                    <td class="text-center" id="KIRIM"></td>
                                    <td class="text-center" id="FINISH"></td>
                                    <td class="text-center" id="STATUS"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>