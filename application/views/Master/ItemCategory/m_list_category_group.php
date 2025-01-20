<div class="modal fade" id="ModalList" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="ModalListLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalListLabel">List Category Group : <?= $category->Item_Category ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="#" id="detail-form">
                    <div class="row">
                        <div class="col-lg-7 col-sm-12 px-4 form-group">
                            <label style="font-weight: bold;">Group Name :</label>
                            <input type="text" class="form-control form-control-sm" name="Group_Name" id="Group_Name" required placeholder="Category Name ....">
                        </div>
                        <div class="col-lg-4 col-sm-12 px-4 form-group">
                            <label style="font-weight: bold;">Group Code :</label>
                            <input type="text" maxlength="4" class="form-control form-control-sm" name="Grouping_Code" id="Grouping_Code" required placeholder="Category Name ....">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9 col-sm-12 px-4 form-group">
                            <label style="font-weight: bold;">Group Description :</label>
                            <input type="text" class="form-control form-control-sm" name="Category_Group_Description" id="Category_Group_Description" required placeholder="Category Name ....">
                        </div>
                        <input type="hidden" name="Category_Parent" id="Category_Parent" value="<?= $category->SysId ?>">
                        <div class="col-lg-3 col-sm-12 px-4 form-group">
                            <button type="button" class="btn btn-primary mt-4" id="submit-detail-form"><i class="fas fa-download"></i>&nbsp;&nbsp; Save</button>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table id="DataTable-List" class="table table-sm table-bordered table-striped display nowrap" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr class="text-center text-white">
                                <th>Group Name</th>
                                <th>Group Code</th>
                                <th>Description</th>
                                <th><i class="fas fa-cogs"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>&nbsp;&nbsp; Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var TableData = $("#DataTable-List").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            dom: 'f<"row"<"col-6"l><"col-6"B>>rtip',
            lengthMenu: [
                [10, 25, 50, 10000],
                [10, 25, 50, 'All']
            ],
            select: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "MasterData/ItemCategory/DT_item_category_group",
                dataType: "json",
                type: "POST",
                data: {
                    Category_Parent: $('#Category_Parent').val()
                }
            },
            columns: [{
                data: "Group_Name",
                name: "Group_Name",
            }, {
                data: "Grouping_Code",
                name: "Grouping_Code",
            }, {
                data: "Category_Group_Description",
                name: "Category_Group_Description",
            }, {
                data: "Is_Active",
                name: "Is_Active",
                render: function(data, type, row, meta) {
                    if (data == 1) {
                        return `<i class="fas fa-check text-success"></i>`
                    } else {
                        return `<i class="fas fa-times text-danger"></i>`
                    }
                }
            }],
            order: [
                [0, "asc"]
            ],
            columnDefs: [{
                    className: "text-center",
                    targets: [0, 1, 2, 3],
                },
                {
                    className: "text-left",
                    targets: []
                }
            ],
            autoWidth: false,
            // responsive: true,
            preDrawCallback: function() {
                $("#DataTable-List tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function() {
                $("#DataTable-List tbody td").addClass("blurry");
                setTimeout(function() {
                    $("#DataTable-List tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            },
            initComplete: function(settings, json) {
                // ---------------
            },
            "buttons": [{
                text: `<i class="fas fa-edit fs-3"></i>`,
                className: "btn disabled text-dark bg-white",
                action: function(e, dt, node, config) {
                    var RowData = dt.rows({
                        selected: true
                    }).data();
                    // if (RowData.length == 0) {
                    //     Swal.fire({
                    //         icon: 'warning',
                    //         title: 'Ooppss...',
                    //         text: 'Silahkan pilih data untuk melihat detail !',
                    //         footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                    //     });
                    // } else {
                    //     Init_Show_Detail(RowData[0].SysId)
                    // }
                }
            }, {
                text: `<i class="fas fa-toggle-on"></i> Active/In-Active`,
                className: "btn bg-dark",
                action: function(e, dt, node, config) {
                    var RowData = dt.rows({
                        selected: true
                    }).data();
                    if (RowData.length == 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Silahkan pilih data untuk merubah status !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        Fn_Toggle_Status(RowData[0].SysId)
                    }
                }
            }, {
                text: `Export to :`,
                className: "btn disabled text-dark bg-white",
            }, {
                text: `<i class="far fa-file-excel"></i>`,
                extend: 'excelHtml5',
                title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
                className: "btn btn-success",
            }, {
                text: `<i class="far fa-file-pdf"></i>`,
                extend: 'pdfHtml5',
                title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
                className: "btn btn-danger",
                orientation: "landscape"
            }],
        }).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');



        function Fn_Toggle_Status(SysId) {
            Swal.fire({
                title: 'System message!',
                text: `Apakah anda yakin untuk merubah status item ini ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ubah!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $('meta[name="base_url"]').attr('content') + "MasterData/HelperMaster/Toggle_Status",
                        type: "post",
                        dataType: "json",
                        data: {
                            sysid: SysId,
                            table: 'tmst_item_category_group'
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Loading....',
                                html: '<div class="spinner-border text-primary"></div>',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            })
                        },
                        success: function(response) {
                            if (response.code == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.msg,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Yes, Confirm!'
                                })
                                $("#DataTable-List").DataTable().ajax.reload(null, false);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.msg,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Yes, Confirm!',
                                    footer: '<a href="javascript:void(0)">Notification System</a>'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            var statusCode = xhr.status;
                            var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
                            Swal.fire({
                                icon: "error",
                                title: "Error!",
                                html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
                            });
                        }
                    });
                }
            })
        }

        // ------------------------------------ START FORM VALIDATION
        const DtlForm = $('#detail-form');
        const BtnDtlSubmit = $('#submit-detail-form');
        DtlForm.validate({
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
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

        $(BtnDtlSubmit).click(function(e) {
            e.preventDefault();
            if (DtlForm.valid()) {
                Swal.fire({
                    title: 'Loading....',
                    html: '<div class="spinner-border text-primary"></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
                Fn_Submit_Form(DtlForm)
            } else {
                $('html, body').animate({
                    scrollTop: ($('.error:visible').offset().top - 200)
                }, 400);
            }
        });

        function Fn_Submit_Form() {
            BtnDtlSubmit.prop("disabled", true);
            var formDataa = new FormData(DtlForm[0]);
            $.ajax({
                dataType: "json",
                type: "POST",
                url: $('meta[name="base_url"]').attr('content') + "MasterData/ItemCategory/post_category_group",
                data: formDataa,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.close()
                    if (response.code == 200) {
                        $(DtlForm)[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.msg,
                            showCancelButton: false,
                        }).then((result) => {
                            $("#DataTable-List").DataTable().ajax.reload(null, false);
                            $(DtlForm)[0].reset();
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.msg,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, Confirm!',
                            footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                        });
                    }
                    BtnDtlSubmit.prop("disabled", false);
                },
                error: function(xhr, status, error) {
                    var statusCode = xhr.status;
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
                    });
                }
            });
        }

    })
</script>