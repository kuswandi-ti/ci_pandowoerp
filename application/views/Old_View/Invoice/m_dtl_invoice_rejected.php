<div class="modal fade" id="modal-detail-invoice" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 85%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Preview Detail Invoice : <b><?= $Hdr->Invoice_Number ?></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <div class="card card-danger card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><b>Main Data Invoice</b></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="form_hdr_invoice" method="post" action="#">
                                    <input type="hidden" name="Invoice_Number" id="Invoice_Number" readonly value="<?= !empty($Hdr) ? $Hdr->Invoice_Number : 'NEW' ?>">
                                    <div class="form-group">
                                        <label>Invoice Date :</label>
                                        <div class="input-group input-group-sm shadow">
                                            <input type="text" class="form-control form-control-sm datepicker" readonly name="invoice_date" id="invoice_date" data-toggle="datetimepicker" data-target="#invoice_date" placeholder="Invoice Date..." required value="<?= !empty($Hdr) ? $Hdr->Invoice_Date : date('Y-m-d') ?>">
                                            <div class="input-group-append">
                                                <span id="btn--date" class="input-group-text">&nbsp;<i class="fas fa-calendar"></i>&nbsp;</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Customer :</label>
                                        <input type="hidden" name="id_customer" id="id_customer" value="<?= !empty($Hdr) ? $Hdr->Customer_ID : NULL ?>">
                                        <input type="hidden" name="customer_code" id="customer_code" value="<?= !empty($Hdr) ? $Hdr->Customer_Code : NULL ?>">
                                        <div class="input-group input-group-sm shadow">
                                            <input type="text" class="form-control form-control-sm" name="customer_name" id="customer_name" placeholder="Customer..." required readonly value="<?= !empty($Hdr) ? $Hdr->Customer_Name : NULL ?>">
                                            <!-- <div class="input-group-append">
                                                <button type="button" id="btn--customer" class="btn bg-gradient-info">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Delivery Note :</label>
                                        <input type="hidden" name="dn_id" id="dn_id" value="<?= !empty($Hdr) ? $Hdr->DN_ID : NULL ?>">
                                        <div class="input-group input-group-sm shadow">
                                            <input type="text" class="form-control form-control-sm" name="DN" id="DN" placeholder="Delivery Note..." required readonly value="<?= !empty($Hdr) ? $Hdr->DN_Number : NULL ?>">
                                            <!-- <div class="input-group-append">
                                                <button type="button" id="btn--dn" class="btn bg-gradient-info">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Purchase Order Number :</label>
                                        <input type="hidden" name="id_po" id="id_po" value="<?= !empty($Hdr) ? $Hdr->SO_ID : NULL ?>">
                                        <div class="input-group input-group-sm shadow">
                                            <input type="text" class="form-control form-control-sm" name="no_po_customer" id="no_po_customer" placeholder="PO Number..." required readonly value="<?= !empty($Hdr) ? $Hdr->No_PO_Customer : NULL ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>SO. Number :</label>
                                        <div class="input-group input-group-sm shadow">
                                            <input type="text" class="form-control form-control-sm" name="no_po_internal" id="no_po_internal" placeholder="SO Number..." required readonly value="<?= !empty($Hdr) ? $Hdr->SO_Number : NULL ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>NPWP :</label>
                                        <div class="input-group input-group-sm shadow">
                                            <input type="text" class="form-control form-control-sm" name="NPWP" id="NPWP" placeholder="NPWP..." required readonly value="<?= !empty($Hdr) ? $Hdr->NPWP : NULL ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Customer Address :</label>
                                        <input type="hidden" id="id_address" name="id_address" value="<?= !empty($Hdr) ? $Hdr->Address_ID : NULL ?>">
                                        <div class="input-group input-group-sm shadow">
                                            <textarea class="form-control form-control-sm" readonly name="customer_address" id="customer_address" placeholder="Customer Address..." required rows="3"><?= !empty($Hdr) ? $Hdr->Customer_Address : NULL ?></textarea>
                                            <!-- <div class="input-group-append">
                                                <button type="button" id="btn--list--address" class="btn bg-gradient-info">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-search"></i>&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Due Date :</label>
                                        <div class="input-group input-group-sm shadow">
                                            <input type="number" class="form-control form-control-sm" name="due_date" id="due_date" placeholder="Due Date..." required value="<?= !empty($Hdr) ? $Hdr->Due_Date : NULL ?>" readonly>
                                            <div class="input-group-append">
                                                <span id="btn--date" class="input-group-text">&nbsp;DAY&nbsp;</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>User Create :</label>
                                        <div class="input-group input-group-sm shadow">
                                            <input type="text" class="form-control form-control-sm" name="created_by" id="created_by" placeholder="Created by" required value="<?= $Hdr->created_by ?>" readonly>
                                            <div class="input-group-append">
                                                <span id="btn--date" class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>DateTime Create :</label>
                                        <div class="input-group input-group-sm shadow">
                                            <input type="text" class="form-control form-control-sm" name="created_at" id="created_at" placeholder="Created at" required value="<?= $Hdr->created_at ?>" readonly>
                                            <div class="input-group-append">
                                                <span id="btn--date" class="input-group-text"><i class="fas fa-clock"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6" id="location-detail-form" <?= !empty($Hdr) ? NULL : 'style="display: none;"' ?>>
                        <div class="card card-danger card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><b>Detail Item Invoice</b></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="form_item_invoice" method="post" action="#">
                                    <div class="row">
                                        <div class="col-sm-5 col-lg-5">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="background-color: #3B6D8C; color: white;"><i class="fas fa-tag"></i>&nbsp;No. Inv</span>
                                                    </div>
                                                    <input type="text" class="form-control" required name="Invoice_Number_Show" id="Invoice_Number_Show" placeholder="Invoice Number..." readonly value="<?= !empty($Hdr) ? $Hdr->Invoice_Number : NULL ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-7 col-lg-7">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="background-color: #3B6D8C; color: white;"><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;Item Amount</span>
                                                    </div>
                                                    <input type="text" class="form-control" required name="Item_Amount" id="Item_Amount" placeholder="Invoice Number..." readonly value="<?= !empty($Hdr) ? number_format($Hdr->Item_Amount, 2) : NULL ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 col-lg-5">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="background-color: #3B6D8C; color: white;"><i class="fas fa-percent"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PPn&nbsp;&nbsp;</span>
                                                    </div>
                                                    <input type="text" class="form-control" required name="PPN" id="PPN" placeholder="PPn..." readonly value="<?= !empty($Hdr) ? $Hdr->PPN : NULL ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-7 col-lg-7">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="background-color: #3B6D8C; color: white;"><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;&nbsp;PPn Amount</span>
                                                    </div>
                                                    <input type="text" class="form-control" required name="PPN_Amount" id="PPN_Amount" placeholder="PPn Amount..." readonly value="<?= !empty($Hdr) ?  number_format($Hdr->PPN_Amount, 2) : NULL ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 col-lg-5"></div>
                                        <div class="col-sm-7 col-lg-7">
                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="background-color: #3B6D8C; color: white;"><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;&nbsp;&nbsp;Inv Amount</span>
                                                    </div>
                                                    <input type="text" class="form-control" required name="Invoice_Amount" id="Invoice_Amount" placeholder="Invoice Amount..." readonly value="<?= !empty($Hdr) ? number_format($Hdr->Invoice_Amount, 2)  : NULL ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 mt-1">
                                            <table class="table-xs table-striped table-bordered table-hover" style="width: 100%;" id="tbl-item-inv">
                                                <thead style="background-color: #3B6D8C;">
                                                    <tr class="text-white">
                                                        <th class="text-center">#</th>
                                                        <th class="text-center">PRODUCT CODE</th>
                                                        <th class="text-center">PRODUCT DESCRIPTION</th>
                                                        <th class="text-center">UOM</th>
                                                        <th class="text-center">PRICE</th>
                                                        <th class="text-center">QTY</th>
                                                        <th class="text-center">SUB-TOTAL</th>
                                                        <th class="text-center"><i class="fas fa-cogs"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- HI DUDE I DO SOME MAGIC HERE -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        Swal.close()

        var TableDtl = $("#tbl-item-inv").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            paging: false,
            searching: false,
            "responsive": false,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "RejectedInvoice/DT_preview_detail_item_invoice",
                dataType: "json",
                type: "get",
                data: {
                    Invoice_Number: $('#Invoice_Number').val()
                }
            },
            columns: [{
                    data: "SysId",
                    name: "SysId",
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    data: "Product_Code",
                    name: "Product_Code",
                    orderable: false,
                },
                {
                    data: "Product_Name",
                    name: "Product_Name",
                    orderable: false,
                },
                {
                    data: "Uom",
                    name: "Uom",
                    orderable: false,
                },
                {
                    data: "Product_Price",
                    name: "Product_Price",
                    orderable: false,
                },
                {
                    data: "Qty",
                    name: "Qty",
                    orderable: false,
                },
                {
                    data: "Amount_Item",
                    name: "Amount_Item",
                    orderable: false,
                },
                {
                    data: null,
                    name: "handle",
                    orderable: false,
                    visible: false,
                    render: function(data, type, row, meta) {
                        return `<div class="btn btn-group">
						<button type="button" data-toggle="tooltip" title="Delete Item" class="btn bg-gradient-danger btn-xs btn-delete-item" data-pk="${row.SysId}"><i class="fas fa-trash"></i></button>
						</div>`
                    }
                },
            ],
            "order": [
                [0, "asc"]
            ],
            columnDefs: [{
                className: "text-center",
                targets: [0, 1, 2, 3, 4, 5, 6],
            }, ],
            autoWidth: false,
            preDrawCallback: function() {
                $("#tbl-item-inv tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function() {
                $("#tbl-item-inv tbody td").addClass("blurry");
                setTimeout(function() {
                    $("#tbl-item-inv tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            }
        })
    })
</script>