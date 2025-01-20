<style>
    .vertical-align-middle {
        vertical-align: middle !important;
    }

    /*  */

    .custom-btn-link {
        background: none;
        border: none;
        color: #007bff;
        text-decoration: none;
        cursor: pointer;
        padding: 0;
    }

    .custom-btn-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }


    .fh {
        font-size: 1rem !important;
    }

    .fw-bold {
        font-weight: 700 !important;
    }

    .fw-semibold {
        font-weight: 600 !important;
    }

    #DataTable_wrapper .dataTables_filter {
        float: left;
        /* Pastikan tombol pencarian berada di kanan */
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card card-primary card-outline list-data">
                <div class="card-header">
                    <h3 class="card-title"><?= $page_title ?></h3>
                    <div class="card-tools">
                        <but type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </but>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="DataTable" class="table table-sm table-bordered" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr class="text-white">
                                    <th class="text-center vertical-align-middle">Nomor SO</th>
                                    <th class="text-center vertical-align-middle">Nomor PO</th>
                                    <th class="text-center vertical-align-middle">Nama Customer</th>
                                    <th class="text-center vertical-align-middle">Kode Item</th>
                                    <th class="text-center vertical-align-middle">Nama Item</th>
                                    <th class="text-center vertical-align-middle">Unit Price</th>
                                    <th class="text-center vertical-align-middle">Currency</th>
                                    <th class="text-center vertical-align-middle">QTY SO</th>
                                    <th class="text-center vertical-align-middle">Value SO</th>
                                    <th class="text-center vertical-align-middle">QTY Shipped</th>
                                    <th class="text-center vertical-align-middle">Value shipped</th>
                                    <th class="text-center vertical-align-middle">OST SO</th>
                                    <th class="text-center vertical-align-middle">Value OST SO</th>
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
</div>

<!-- Modal -->
<!-- <div class="modal fade" id="show_shipping" tabindex="-1" role="dialog" aria-labelledby="show_shippingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="show_shippingLabel">Detail Shipping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <td class="fh fw-bold">No Shipping</td>
                        <td class="fh fw-bold">:</td>
                        <td class="fh fw-bold" id="no_shipping">2020202020</td>
                    </tr>
                    <tr>
                        <td class="fh fw-bold">TGL Shipping</td>
                        <td class="fh fw-bold">:</td>
                        <td class="fh fw-bold" id="tgl_shipping">2020202020</td>
                    </tr>
                    <tr>
                        <td class="fh fw-bold">Item Code</td>
                        <td class="fh fw-bold">:</td>
                        <td class="fh fw-bold" id="item_code">2020202020</td>
                    </tr>
                    <tr>
                        <td class="fh fw-bold">Item Name</td>
                        <td class="fh fw-bold">:</td>
                        <td class="fh fw-bold" id="item_name">2020202020</td>
                    </tr>
                    <tr>
                        <td class="fh fw-bold">QTY SHP</td>
                        <td class="fh fw-bold">:</td>
                        <td class="fh fw-bold" id="qty_shp">2020202020</td>
                    </tr>
                    <tr>
                        <td class="fh fw-bold">Alamat</td>
                        <td class="fh fw-bold">:</td>
                        <td class="fh fw-bold" id="alamat">2020202020</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->

<div id="location">

</div>