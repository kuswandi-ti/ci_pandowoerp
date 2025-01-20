<div class="modal fade" id="m_list_loading_product" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 80%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">List Loading Outstanding Product : <?= $Dtl_Dn->Product_Code . "($Dtl_Dn->Product_Name)" ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="SysId_Dtl" name="SysId_Dtl" value="<?= $SysId_Dtl ?>">
                <div class="table-responsive">
                    <table id="TableDataLoading" class="tbl-xs table-bordered table-hover display compact table-valign-middle" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">No Loading</th>
                                <th class="text-center text-white">Customer Code</th>
                                <th class="text-center text-white">Status</th>
                                <th class="text-center text-white">Product Code</th>
                                <th class="text-center text-white">Product Name</th>
                                <th class="text-center text-white">Qty Loading</th>
                                <th class="text-center text-white">STATUS</th>
                                <th class="text-center text-white">Silang Product</th>
                                <th class="text-center text-white"><i class="far fa-clock"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submit--loading"><i class="fas fa-check"></i> Choose Loading Number</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        Swal.close()

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
        });

        var TableDataLoading = $("#TableDataLoading").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            select: true,
            paging: true,
            orderCellsTop: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "DnOutstanding/DT_Loading_product_vs_DN",
                dataType: "json",
                type: "POST",
                data: {
                    SysId_Dtl: $('#SysId_Dtl').val()
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
                    data: "No_loading",
                    name: "No_loading"
                },
                {
                    data: "Customer_Code",
                    name: "Customer_Code"
                },
                {
                    data: "STATUS",
                    name: "STATUS"
                },
                {
                    data: "Product_Code",
                    name: "Product_Code"
                },
                {
                    data: "Product_Name",
                    name: "Product_Name"
                },
                {
                    data: "Qty_Loading",
                    name: "Qty_Loading",
                },
                {
                    data: "STATUS",
                    name: "STATUS",
                    render: function(data, type, row, meta) {
                        if (data == 'SELESAI') {
                            return `<a href="javascript:void(0)" class="btn btn-success">SELESAI</a>`;
                        } else {
                            return `<a href="javascript:void(0)" class="btn btn-danger">Proses Loading</a>`;
                        }
                    }
                },
                {
                    data: "Silang_Product",
                    name: "Silang_Product",
                    render: function(data, type, row, meta) {
                        if (data == 'TRUE') {
                            return `<a href="javascript:void(0)" class="btn btn-success"><i class="fas fa-check"></i></a>`;
                        } else {
                            return `<a href="javascript:void(0)" class="btn btn-danger"><i class="fas fa-times"></i></a>`;
                        }
                    }
                },
                {
                    data: "Selesai_at",
                    name: "Selesai_at",
                    render: function(data, type, row, meta) {
                        return `<pre>${data.substring(0, 16)}</pre>`;

                    }
                }
            ],
            "order": [
                [9, "ASC"]
            ],
            columnDefs: [{
                    className: "text-center",
                    targets: [0, 1, 2, 3, 4, , 5, 7, 8, 9],
                },
                {
                    className: "text-left",
                    targets: []
                }
            ],
            // autoWidth: false,
            responsive: true,
            preDrawCallback: function() {
                $("#TableDataLoading tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function() {
                $("#TableDataLoading tbody td").addClass("blurry");
                setTimeout(function() {
                    $("#TableDataLoading tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            }
        })

        $(document).on('click', '#submit--loading', function() {
            var rowData = TableDataLoading.rows({
                selected: true
            }).data()[0];


            if (!rowData) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'You should choose Loading Data !',
                    footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                });
            }

            Swal.fire({
                title: 'System Message!',
                text: 'Are You sure to combine this Loading Number to DN Number ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: $('meta[name="base_url"]').attr('content') + "DnOutstanding/Combine_Loading_vs_DN",
                        data: {
                            SysId_Dtl: $('#SysId_Dtl').val(),
                            No_Loading: rowData.No_loading,
                        },
                        beforeSend: function() {
                            $('#submit--loading').prop("disabled", true);
                            Swal.fire({
                                title: 'Loading....',
                                html: '<div class="spinner-border text-primary"></div>',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            })
                        },
                        success: function(response) {
                            Swal.close()
                            if (response.code == 200) {
                                $("#TableData").DataTable().ajax.reload(null, false);
                                Toast.fire({
                                    icon: 'success',
                                    title: response.msg,
                                });
                                $('#m_list_loading_product').modal('hide');
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Oops...',
                                    text: response.msg,
                                    footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                                });
                                $('#submit--loading').prop("disabled", false);
                            }
                        },
                        error: function() {
                            Swal.close()
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan teknis segera lapor pada admin!',
                                footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                            });
                        }
                    });
                }
            })
        })


    })
</script>

<!-- // $('#id_po').val(rowData.SO_SysId_Hdr);
// $('#no_po_customer').val(rowData.No_Po_Customer);
// $('#no_po_internal').val(rowData.SO_Number);
// $('#id_address').val(rowData.ID_Address);
// $('#customer_address').val(rowData.Customer_Address);
// $('#modal-list-po').modal('hide'); -->