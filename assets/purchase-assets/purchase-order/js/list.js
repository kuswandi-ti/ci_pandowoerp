$(document).ready(function () {
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
    })

    form_state('LOAD');

    function setFormState(isDisabled, isReadonly) {
        $('#btn-list-person').prop("disabled", isDisabled);
        $('#btn-list-address').prop("disabled", isDisabled);
        $('input[name="doc_date"]').prop('disabled', isDisabled);
        $('input[name="rate"]').prop('readonly', isReadonly);
        // $('input[name="eta"]').prop('disabled', isDisabled);
        // $('input[name="etd"]').prop('disabled', isDisabled);
        // $('textarea[name="notes"]').prop('readonly', isReadonly);
        // $('textarea[name="custom_field_1"]').prop('readonly', isReadonly);
        // $('textarea[name="custom_field_2"]').prop('readonly', isReadonly);
        // $('textarea[name="custom_field_3"]').prop('readonly', isReadonly);
        $('input[name="isImport"]').prop('disabled', isDisabled);
        $('input[name="isAsset"]').prop('disabled', isDisabled);
        $('select[name="currency"]').prop('disabled', isDisabled).trigger('change.select2');

        $('input[name="percent_discount_all"]').prop('disabled', isDisabled);
    }

    function form_state(state) {
        switch (state) {
            case 'LOAD':
                $('.list-data').show("slow");
                $('.add-data').hide("slow");
                $('input[name="state"]').val('');
                reloadData();
                break;

            case 'ADD':
                reset_input();
                $(MainForm)[0].reset();
                $('.list-data').hide("slow");
                $('.add-data').show("slow");
                $('input[name="state"]').val('ADD');
                $('#title-add-hdr').html('Add');
                $('input[name="rate"]').val(1);
                $('input[name="isImport"][value="0"]').prop('checked', true);
                $('input[name="isAsset"][value="0"]').prop('checked', true);
                $('input[name="doc_no"]').val('Doc Number Akan Otomatis di isikan Oleh system.');
                setFormState(false, false);
                $('#no_data_item').show('slow');
                $('.footer-table').hide('slow');
                $('#btn-submit').show();
                flatpickr();
                break;

            case 'EDIT':
                reset_input();
                $('.list-data').hide();
                $('.add-data').show();
                $('input[name="state"]').val('EDIT');
                $('#title-add-hdr').html('Edit');
                setFormState(false, false);
                $('#no_data_item').hide('slow');
                $('.footer-table').show('slow');
                flatpickr();
                break;

            case 'DETAIL':
                reset_input();
                $('.list-data').hide();
                $('.add-data').show();
                $('input[name="state"]').val('DETAIL');
                $('#title-add-hdr').html('Detail');
                setFormState(true, true);
                $('#no_data_item').hide('slow');
                $('.footer-table').show('slow');
                flatpickr();
                break;

            case 'REVISI':
                reset_input();
                $('.list-data').hide();
                $('.add-data').show();
                $('input[name="state"]').val('REVISI');
                $('#title-add-hdr').html('Revisi');
                setFormState(true, true);
                $('#no_data_item').hide('slow');
                $('.footer-table').show('slow');
                break;

            case 'BACK':
                $('.list-data').show("slow");
                $('.add-data').hide("slow");
                break;
        }
    }

    function reset_input() {
        $("input:text").val('');
        $('input[type="hidden"]').val('');
        $('#table_item tbody').html('');
        $('.select2').val('').trigger('change');
        $('select[name="currency"]').val('IDR').trigger('change');
    }

    function reloadData() {
        $("#DataTable").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            dom: 'l<"row"<"col-4"f><"col-8"B>>rtip',
            lengthMenu: [
                [10, 25, 50, 10000],
                [10, 25, 50, 'All']
            ],
            select: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/DT_listdata",
                dataType: "json",
                type: "POST",
            },
            columns: [
                {
                    data: 'SysId', // gunakan 'null' karena kita akan menggunakan render function
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // meta.row adalah indeks baris (mulai dari 0)
                    },
                },
                {
                    data: "Doc_No",
                },
                {
                    data: "Doc_Rev",
                    render: function (data, type, row, meta) {
                        var txt_rev = '01.0';

                        return data ? txt_rev + data : txt_rev;
                    }
                },
                {
                    data: "Doc_Date",
                    render: function (data, type, row, meta) {
                        return moment(data).format("DD MMMM YYYY");
                    }
                },
                {
                    data: "Account_Name", // Name Vendor
                },
                {
                    data: "Address",
                },
                {
                    data: "ETA",
                    render: function (data, type, row, meta) {
                        return moment(data).format("DD MMMM YYYY");
                    }
                },
                {
                    data: "ETD",
                    render: function (data, type, row, meta) {
                        return moment(data).format("DD MMMM YYYY");
                    }
                },
                {
                    data: "Currency",
                },
                {
                    data: "Amount",
                    render: function (data, type, row, meta) {
                        return formatIdrAccounting(data);
                    }
                },
                {
                    data: "Note",
                    render: function (data, type, row, meta) {
                        return data ? data : '-';
                    }
                },
                {
                    data: "IsClose",
                    render: function (data, type, row, meta) {
                        if (data == 1) {
                            return `<div class='d-flex justify-content-center'><span class="badge bg-danger">Closed</span></div>`;
                        } else {
                            return `<div class='d-flex justify-content-center'><span class="badge bg-warning">Open</span></div>`;
                        }
                    }
                },
                {
                    data: "Approve",
                    render: function (data, type, row, meta) {
                        if (data == 0) {
                            return `<i class="fas fa-question text-dark"></i>`
                        } else if (data == 1) {
                            return `<i class="fas fa-check text-success"></i>`
                        } else {
                            return `<i class="fas fa-times text-danger"></i>`
                        }
                    }
                },
            ],
            order: [
                [0, "desc"]
            ],
            columnDefs: [{
                className: "text-center align-middle",
                targets: "_all",
            },
            {
                className: "text-left",
                targets: []
            }
            ],
            autoWidth: false,
            // responsive: true,
            rowCallback: function (row, data, index) {
                // Gantilah 'yourColumnName' dengan nama kolom Anda
                if (data.IsClose == 1) {
                    $(row).css('background-color', '#F8D7DA');
                }
            },
            preDrawCallback: function () {
                $("#DataTable tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function () {
                $("#DataTable tbody td").addClass("blurry");
                setTimeout(function () {
                    $("#DataTable tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            },
            initComplete: function (settings, json) {
                // ---------------
            },
            "buttons": [{
                text: `<i class="fas fa-plus fs-3"></i>&nbsp; Add PO`,
                className: "bg-primary",
                action: function (e, dt, node, config) {
                    form_state('ADD');
                }
            }, {
                text: `<i class="fas fa-edit fs-3"></i>&nbsp; Edit`,
                className: "btn btn-warning",
                action: function (e, dt, node, config) {
                    var RowData = dt.rows({
                        selected: true
                    }).data();
                    if (RowData.length == 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Silahkan pilih data untuk edit data !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        Init_Edit_Detail_Revisi(RowData[0].SysId, 'EDIT')
                    }
                }
            }, {
                text: `<i class="fas fa-random fs-3"></i>&nbsp; Revisi`,
                className: "btn btn-success",
                action: function (e, dt, node, config) {
                    var RowData = dt.rows({
                        selected: true
                    }).data();
                    if (RowData.length == 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Silahkan pilih data untuk revisi data !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        Init_Edit_Detail_Revisi(RowData[0].SysId, 'REVISI')
                    }
                }
            }, {
                text: `<i class="fas fa-search fs-3"></i>&nbsp; View Detail`,
                className: "btn btn-info",
                action: function (e, dt, node, config) {
                    var RowData = dt.rows({
                        selected: true
                    }).data();
                    if (RowData.length == 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Silahkan pilih data untuk melihat detail data !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        Init_Edit_Detail_Revisi(RowData[0].SysId, 'DETAIL')
                    }
                }
            }, {
                text: `<i class="fas fa-print fs-3"></i>&nbsp; Print`,
                className: "btn bg-gradient-success",
                action: function (e, dt, node, config) {
                    var RowData = dt.rows({
                        selected: true
                    }).data();
                    if (RowData.length == 0) {
                        return Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Silahkan pilih data terlebih dahulu !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else if (RowData[0].IsClose == 1 || RowData[0].Approve == 2 || RowData[0].Approve == 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Report cetak hanya bisa di tampilkan pada data yang telah legitimate (tidak close dan sudah approve)!',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        window.open($('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/export_pdf_po/" + RowData[0].SysId, "_blank");
                    }
                }
            }, {
                text: `<i class="fa fa-times fs-3"></i>&nbsp; Close`,
                className: "btn btn-dark",
                action: function (e, dt, node, config) {
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
                    } else if (RowData[0].IsClose == 1) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Data tidak bisa di ubah karena sudah Close !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        Fn_Toggle_Status_Close(RowData[0].SysId)
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
        }).buttons().container().appendTo('#TableData_wrapper .col-md-8:eq(0)');
    }

    function Init_Edit_Detail_Revisi(SysId, State) {
        $.ajax({
            dataType: "json",
            type: "POST",
            url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/GetDataEditRevisi",
            data: {
                sysid: SysId,
                state: State
            },
            success: function (response) {
                Swal.close()
                if (response.code == 200) {
                    form_state(State);

                    $('input[name="sysid"]').val(SysId);
                    $('input[name="doc_no"]').val(response.data_hdr.Doc_No);
                    $('.doc_rev').text('01.0' + response.data_hdr.Doc_Rev)
                    $('input[name="vendor_id"]').val(response.data_hdr.SysId_Vendor);
                    $('input[name="vendor"]').val(response.data_hdr.Account_Name);
                    $('input[name="vendor_address_id"]').val(response.data_hdr.SysId_Address);
                    $('input[name="vendor_address"]').val(response.data_hdr.Address);
                    $('input[name="eta"]').val(moment(response.data_hdr.ETA).format("DD MMMM YYYY"));
                    $('input[name="etd"]').val(moment(response.data_hdr.ETD).format("DD MMMM YYYY"));
                    $('textarea[name="notes"]').val(response.data_hdr.Note);

                    $('input[name="doc_date"]').val(moment(response.data_hdr.Doc_Date).format("DD MMMM YYYY"));
                    $('input[name="person_id"]').val(response.data_hdr.SysId_Person);
                    $('input[name="person"]').val(response.data_hdr.Contact_Name);
                    $('select[name="currency"]').val(response.data_hdr.Currency).trigger('change');
                    $('input[name="rate"]').val(response.data_hdr.Rate);
                    $('input[name="isImport"][value="' + response.data_hdr.IsImport + '"]').prop('checked', true);
                    $('input[name="isAsset"][value="' + response.data_hdr.IsAsset + '"]').prop('checked', true);
                    $('textarea[name="custom_field_1"]').val(response.data_hdr.Custom_Field_1);
                    $('textarea[name="custom_field_2"]').val(response.data_hdr.Custom_Field_2);
                    $('textarea[name="custom_field_3"]').val(response.data_hdr.Custom_Field_3);

                    $('input[name="percent_discount_all"]').val(response.data_hdr.Discount);

                    // DETAIL //
                    var $tableItem = $('#table_item tbody');
                    $tableItem.empty();

                    var no = 1;

                    getSelect(function (costCenterOptions, base_tax, taxOptions) {
                        $.each(response.data_dtl, function (index, rowData) {
                            var unit_price = rowData.Unit_Price;

                            qty_rr = '';
                            if (State == 'REVISI') {
                                qty_rr = '<input type="hidden" class="qty_rr qty_rr' + no + '" name="qty_rr[]" value="' + rowData.Total_Qty_RR + '">'
                            }

                            var rate = $('input[name="rate"]').val();
                            rate = rate ? rate : 0;
                            var qty = rowData.Qty;

                            var base_unit_price = unit_price * rate;
                            var total_price = unit_price * qty;
                            var total_base_price = base_unit_price * qty;
                            var discount = rowData.Discount;
                            var discount_value = (total_price * discount) / 100;
                            var priceAfterDiscount = total_price - discount_value;

                            var $newRow = $('<tr>');

                            // Buat kolom dengan input sesuai dengan data yang ada
                            $newRow.append(`<td>
                                <input type="hidden" name="sysid_dtl[]" value="` + rowData.SysId + `">
                                <input type="hidden" name="sysid_item[]" value="` + rowData.SysId_Item + `">
                                <p class="mt-1">`+ no + `</p>
                            </td>`);
                            $newRow.append('<td><input class="form-control form-control-sm" name="item_code[]" type="text" value="' + rowData.Item_Code + '" readonly></td>');
                            $newRow.append('<td><input class="form-control form-control-sm" name="item_name[]" type="text" value="' + rowData.Item_Name + '" readonly></td>');

                            $newRow.append(`<td>
                                <input class="form-control form-control-sm" name="uom[]" type="text" value="`+ rowData.Uom + `" readonly>
                            </td>`);

                            $newRow.append(`<td>
                                <select class="form-control form-control-sm select2 select-costcenter select2-costcenter`+ no + `" name="costcenter[]" data-no="` + no + `"></select>
                            </td>`);

                            $newRow.append(`<td>
                                `+ qty_rr +`
                                <input class="form-control form-control-sm only-number qty qty` + no + `" type="text" name="qty[]" value="` + qty + `" data-no="` + no + `" data-qty="` + qty + `">
                            </td>`);

                            $newRow.append(`
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm only-number discount_percent discount_percent`+ no + `" value="` + discount + `" name="discount_percent[]" data-no="` + no + `"> 
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            %
                                        </div>
                                    </div>
                                </div>
                            </td>`);

                            $newRow.append('<td><input class="form-control form-control-sm value_discount' + no + '" name="value_discount[]" type="text" value="' + formatIdrAccounting(discount_value) + '" data-no="' + no + '" readonly></td>');

                            $newRow.append(`<td>
                                `+ base_tax + `
                                <select class="form-control form-control-sm select2 select-tax1 select2-tax1`+ no + `" name="type_tax1[]" data-no="` + no + `"></select>
        
                                <input class="value_tax1`+ no + `" name="value_tax1[]" type="hidden" value="` + rowData.value_tax_1 + `">
                            </td>`);

                            $newRow.append(`<td>
                                <select class="form-control form-control-sm select2 select-tax2 select2-tax2`+ no + `" name="type_tax2[]" data-no="` + no + `"></select>
                                
                                <input class="value_tax2`+ no + `" name="value_tax2[]" type="hidden" value="` + rowData.value_tax_2 + `">
                            </td>`);

                            $newRow.append('<td><input class="form-control form-control-sm unit_price unit_price' + no + '" type="text" name="unit_price[]" value="' + formatIdrAccounting(unit_price) + '" data-no="' + no + '" readonly></td>');

                            $newRow.append('<td><input class="form-control form-control-sm base_unit_price' + no + '" type="text" name="base_unit_price[]" value="' + formatIdrAccounting(base_unit_price) + '" readonly></td>');

                            $newRow.append('<td><input class="form-control form-control-sm total_price total_price' + no + '" type="text" name="total_price[]" value="' + formatIdrAccounting(priceAfterDiscount) + '" data-no="' + no + '" readonly></td>');

                            $newRow.append('<td><input class="form-control form-control-sm total_base_price' + no + '" type="text" name="total_base_price[]" value="' + formatIdrAccounting(total_base_price) + '" readonly></td>');

                            $newRow.append('<td><textarea rows="2" class="form-control remarks form-control-sm" name="remarks[]" placeholder="Tulis Notes ...">' + rowData.Remark + '</textarea></td>');

                            $newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl remove_item_dtl' + no + '"><span class="fa fa-times"></span></a></td>');

                            // Inisialisasi Select2 untuk elemen select dalam baris baru
                            var $select2CostCenter = $newRow.find('.select2-costcenter' + no);
                            $select2CostCenter.html(costCenterOptions).select2();

                            var $select2Tax1 = $newRow.find('.select2-tax1' + no);
                            $select2Tax1.html(taxOptions).select2();

                            var $select2Tax2 = $newRow.find('.select2-tax2' + no);
                            $select2Tax2.html(taxOptions).select2();

                            // Masukkan baris baru ke dalam tabel tujuan
                            $tableItem.append($newRow);

                            // Set nilai default mata uang
                            $select2CostCenter.val(rowData.CostCenter_ID).trigger('change');
                            $select2Tax1.val(rowData.type_tax_1 ? rowData.type_tax_1 : '').trigger('change');
                            $select2Tax2.val(rowData.type_tax_2 ? rowData.type_tax_2 : '').trigger('change');

                            no++;
                        });

                        calculate_all();
                    });

                    $(".flatpickr").flatpickr({
                        dateFormat: "d F Y"
                    });

                    $('.flatpickr').removeAttr('readonly');
                    // DETAIL - END //

                    if (State == 'DETAIL') {
                        $('#btn-submit').hide();
                    } else {
                        $('#btn-submit').show();
                    }
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
            },
            error: function (xhr, status, error) {
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

    function Fn_Toggle_Status_Close(SysId) {
        Swal.fire({
            title: 'System message!',
            text: `Apakah anda yakin untuk merubah status close po ini ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, ubah!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/Toggle_Status_Close",
                    type: "post",
                    dataType: "json",
                    data: {
                        sysid: SysId,
                    },
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Loading....',
                            html: '<div class="spinner-border text-primary"></div>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        })
                    },
                    success: function (response) {
                        if (response.code == 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.msg,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Yes, Confirm!'
                            })
                            $("#DataTable").DataTable().ajax.reload(null, false);
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
                    error: function (xhr, status, error) {
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

    $(document).on('click', '#back', function () {
        console.log("test");
        form_state('BACK');
    });

    // ======================================================== //
    // --------------------- FUNGSI ADD ----------------- //
    $('input[name="isAsset"]').on('change', function () {
        $('#table_item tbody').empty();
        $('#no_data_item').show('slow');
        $('.footer-table').hide('slow');
    });

    $(document).on('click', '.tambah_item', function () {
        var vendor_id = $('input[name="vendor_id"]').val();
        var select_vendor = '';

        if ($('input[name="state"]').val() == 'ADD') {
            $('.vendor_input_modal_item').show();
        } else {
            $('.vendor_input_modal_item').hide();
        }

        $('#modal_tambah_item .table-responsive').hide();
        if (vendor_id) {
            select_vendor = vendor_id;
            reloadDataItem(vendor_id);
        }

        $('select[name="vendor_modal"]').val(select_vendor).trigger('change');

        if (select_vendor == '') {
            $('#select_item').hide();
        }

        $('#modal_tambah_item').modal('show');
    });

    var selectedRows = {};
    // Bersihkan selectedRows saat modal ditutup
    $('#modal_tambah_item').on('hidden.bs.modal', function () {
        selectedRows = {}; // Reset the selectedRows object
        $('#table_item tbody tr').removeClass('selected'); // Remove 'selected' class from all rows
        $('#table_item tbody input[type="checkbox"]').prop('checked', false); // Uncheck all checkboxes
    });

    $('select[name="vendor_modal"]').on('change', function () {
        var val = $(this).val();

        if (val != null) {
            reloadDataItem(val);
        }
    });

    function reloadDataItem(vendor_id) {
        $('#modal_tambah_item .table-responsive').show();
        $('#select_item').show();

        selectedRows = {};

        var columnDefs = [
            {
                data: 'SysId',
                render: function (data, type, row) {
                    var isChecked = selectedRows[data] ? 'checked' : '';
                    var btnCheck = '<input type="checkbox" id="basic_checkbox_' + data + '" class="filled-in checkbox_checked chk_select" value="' + data + '" ' + isChecked + '/> <label for="basic_checkbox_' + data + '">&nbsp;</label>';
                    return btnCheck;
                },
            },
            {
                data: "Item_Code",
            },
            {
                data: "Item_Name",
            },
            {
                data: "Item_Category",
            },
            {
                data: "Uom",
            },
            {
                data: "Effective_Date",
                render: function (data, type, row, meta) {
                    return moment(data).format("DD MMMM YYYY");
                }
            },
            {
                data: "currency_id",
            },
            {
                data: "Price",
                render: function (data, type, row, meta) {
                    return formatIdrAccounting(data);
                }
            },
        ];

        var sysid_items = [];
        var sysid_items = $('input[name="sysid_item[]"]').map(function () {
            return $(this).val();
        }).get();

        if (vendor_id != $('input[name="vendor_id"]').val()) {
            sysid_items = [];
        }

        $("#DataTable_Modal_ListItem").DataTable({
            destroy: true,
            processing: false,
            serverSide: true,
            lengthMenu: [
                [10, 25, 50, 10000],
                [10, 25, 50, 'All']
            ],
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/DT_modallistpriceitem?sysid_items=" + sysid_items + "&vendor_id=" + vendor_id + "&isasset=" + $('input[name="isAsset"]:checked').val(),
                dataType: "json",
                type: "POST",
            },
            columns: columnDefs,
            order: [
                [0, "desc"]
            ],
            columnDefs: [{
                className: "text-center",
                targets: "_all",
            },
            {
                className: "text-left",
                targets: []
            }],
            autoWidth: false,
            preDrawCallback: function () {
                $("#DataTable_Modal_ListItem tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function () {
                $("#DataTable_Modal_ListItem tbody td").addClass("blurry");
                setTimeout(function () {
                    $("#DataTable_Modal_ListItem tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();

                // Menandai checkbox yang sudah dipilih
                for (var key in selectedRows) {
                    if (selectedRows.hasOwnProperty(key)) {
                        $('#basic_checkbox_' + key).prop('checked', true);
                        $('#basic_checkbox_' + key).closest('tr').addClass('selected');
                    }
                }
            },
            initComplete: function (settings, json) {
                // ---------------
            },
        });

        $('#modal_tambah_item').modal('show');

        $(document).off('click', '#select_item'); // Hapus event handler sebelumnya
        $(document).on('click', '#select_item', function () {
            // Menambahkan data ke tabel tujuan (#table-item)
            var $tableItem = $('#table_item tbody');
            let length_list_dtl = Object.keys(selectedRows).length;

            if ($('input[name="vendor_modal"]').val() == '' || length_list_dtl == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ooppss...',
                    text: 'Harap isi vendor dan pilih item!',
                    footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                });
                return true;
            }
            var txt_vendor = $('select[name="vendor_modal"] option:selected').text();

            if (vendor_id != $('input[name="vendor_id"]').val()) {
                $('input[name="vendor_address_id"]').val('');
                $('input[name="vendor_address"]').val('');
                $('input[name="person_id"]').val('');
                $('input[name="person"]').val('');

                $tableItem.empty();
            }

            $('input[name="vendor_id"]').val(vendor_id);
            $('input[name="vendor"]').val(txt_vendor);

            var selectedRowsData = [];

            $.each(selectedRows, function (sysId, rowData) {
                if (rowData) {
                    selectedRowsData.push(rowData);
                }
            });

            // Kosongkan tabel tujuan sebelum memasukkan data baru
            var no = 1;
            if ($tableItem.children().length === 0) {
                $tableItem.empty();
            } else {
                var lastNumber = $('#table_item tbody tr:last td:first p').text().trim();
                no = parseInt(lastNumber) + 1;
            }

            getSelect(function (costCenterOptions, base_tax, taxOptions) {
                $.each(selectedRowsData, function (index, rowData) {
                    var $newRow = $('<tr>');

                    var unit_price = rowData.Price;

                    var rate = $('input[name="rate"]').val();
                    rate = rate ? rate : 0;
                    var qty = 1;

                    // unit_price.replace(".", "");
                    var base_unit_price = unit_price * rate;
                    var total_price = unit_price * qty;
                    var total_base_price = base_unit_price * qty;

                    // Buat kolom dengan input sesuai dengan data yang ada
                    $newRow.append('<td><input type="hidden" name="sysid_item[]" value="' + rowData.SysId + '"><p class="mt-1">' + no + '</p></td>');
                    $newRow.append('<td><input class="form-control form-control-sm" name="item_code[]" type="text" value="' + rowData.Item_Code + '" readonly></td>');
                    $newRow.append('<td><input class="form-control form-control-sm" name="item_name[]" type="text" value="' + rowData.Item_Name + '" readonly></td>');

                    // <input type="hidden" name="uom_id[]" value="`+ rowData.Uom_Id +`">
                    $newRow.append(`<td>
                        <input class="form-control form-control-sm" name="uom[]" type="text" value="`+ rowData.Uom + `" readonly>
                    </td>`);

                    $newRow.append(`<td>
                        <select class="form-control form-control-sm select2 select-costcenter select2-costcenter`+ no + `" name="costcenter[]" data-no="` + no + `"></select>
                    </td>`);

                    $newRow.append('<td><input class="form-control form-control-sm only-number qty qty' + no + '" type="text" name="qty[]" value="1" data-no="' + no + '"></td>');

                    $newRow.append(`
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm only-number discount_percent discount_percent`+ no + `" value="0" name="discount_percent[]" data-no="` + no + `"> 
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                        </div>
                    </td>`);

                    $newRow.append('<td><input class="form-control form-control-sm value_discount' + no + '" name="value_discount[]" type="text" value="0.00" data-no="' + no + '" readonly></td>');

                    var base_data_tax = '';
                    if ($('.base_tax').length === 0) {
                        base_data_tax = base_tax;
                    }
                    $newRow.append(`<td>
                        `+ base_data_tax + `
                        <select class="form-control form-control-sm select2 select-tax1 select2-tax1`+ no + `" name="type_tax1[]" data-no="` + no + `"></select>

                        <input class="value_tax1`+ no + `" name="value_tax1[]" type="hidden" value="">
                    </td>`);

                    $newRow.append(`<td>
                        <select class="form-control form-control-sm select2 select-tax2 select2-tax2`+ no + `" name="type_tax2[]" data-no="` + no + `"></select>
                        
                        <input class="value_tax2`+ no + `" name="value_tax2[]" type="hidden" value="">
                    </td>`);

                    $newRow.append('<td><input class="form-control form-control-sm unit_price unit_price' + no + '" type="text" name="unit_price[]" value="' + formatIdrAccounting(unit_price) + '" data-no="' + no + '" readonly></td>');

                    $newRow.append('<td><input class="form-control form-control-sm base_unit_price' + no + '" type="text" name="base_unit_price[]" value="' + formatIdrAccounting(base_unit_price) + '" readonly></td>');

                    $newRow.append('<td><input class="form-control form-control-sm total_price total_price' + no + '" type="text" name="total_price[]" value="' + formatIdrAccounting(total_price) + '" data-no="' + no + '" readonly></td>');

                    $newRow.append('<td><input class="form-control form-control-sm total_base_price' + no + '" type="text" name="total_base_price[]" value="' + formatIdrAccounting(total_base_price) + '" readonly></td>');

                    $newRow.append('<td><textarea rows="2" class="form-control remarks form-control-sm" name="remarks[]" placeholder="Tulis Notes ..."></textarea></td>');

                    $newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');

                    // Inisialisasi Select2 untuk elemen select dalam baris baru
                    var $select2CostCenter = $newRow.find('.select2-costcenter' + no);
                    $select2CostCenter.html(costCenterOptions).select2();

                    var $select2Tax1 = $newRow.find('.select2-tax1' + no);
                    $select2Tax1.html(taxOptions).select2();

                    var $select2Tax2 = $newRow.find('.select2-tax2' + no);
                    $select2Tax2.html(taxOptions).select2();

                    // Masukkan baris baru ke dalam tabel tujuan
                    $tableItem.append($newRow);

                    // Set nilai default mata uang
                    // $('.unit_price' + no).val(price);
                    no++;
                });

                $(".flatpickr").flatpickr({
                    dateFormat: "d F Y"
                });

                $(".flatpickr").removeAttr('readonly');
            });

            calculate_base_price($('input[name="rate"]').val());
            $('#no_data_item').hide('slow');
            $('.footer-table').show('slow');
            $('#modal_tambah_item').modal('hide');
        });
    }

    function calculateDiscountPrice(no) {
        var state = $('input[name="state"]').val();

        // var qty = formatAritmatika($(".qty" + no).val());
        var qtyInput = $(".qty" + no);
        var qty = formatAritmatika(qtyInput.val());
        var previousQty = qtyInput.data('qty'); // Mengambil nilai sebelumnya
        
        if (state == 'REVISI') {
            var qty_rr = $(".qty_rr" + no).val();
            
            if (parseFloat(qty) < parseFloat(qty_rr)) {
                toastr.error('Quantity yang diubah tidak boleh kurang dari Quantity RR! (Qty RR: ' + parseFloat(qty_rr) + ')', 'Information', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: '3500',
                    extendedTimeOut: '1000',
                    showDuration: '300',
                    hideDuration: '1000',
                    hideEasing: 'linear',
                    hideMethod: 'fadeOut'
                });
                
                qtyInput.val(previousQty);
                return;
            }
        }

        var discount_percent = formatAritmatika($(".discount_percent" + no).val());
        var unit_price  = formatAritmatika($(".unit_price" + no).val());
        var total_price = unit_price * qty;

        // Hitung jumlah diskon
        var discountAmount = (total_price * discount_percent) / 100;

        // Hitung harga setelah diskon
        var priceAfterDiscount = total_price - discountAmount;

        var select_tax_1 = $('.select2-tax1' + no).val();
        let taxAmount1 = 0;
        if (select_tax_1) {
            let base_tax = formatAritmatika($('.base_tax' + select_tax_1).val());

            taxAmount1 = (priceAfterDiscount * base_tax) / 100;
        }

        var select_tax_2 = $('.select2-tax2' + no).val();
        let taxAmount2 = 0;
        if (select_tax_2) {
            let base_tax = formatAritmatika($('.base_tax' + select_tax_2).val());

            taxAmount2 = (priceAfterDiscount * base_tax) / 100;
        }

        priceAfterDiscount = priceAfterDiscount + taxAmount1 + taxAmount2;

        $(".value_discount" + no).val(formatIdrAccounting(discountAmount));
        $(".total_price" + no).val(formatIdrAccounting(priceAfterDiscount));
        $('.value_tax1' + no).val(taxAmount1);
        $('.value_tax2' + no).val(taxAmount2);
    }

    // Waktu delay dalam milidetik sebelum melakukan pengecekan
    var typingTimer;
    var doneTypingInterval = 2500; // 2.5 detik

    // Event listener untuk memulai timer saat mengetik
    $("#table_item").on('keyup', '.qty, .discount_percent', function () {
        var no = $(this).data('no');

        clearTimeout(typingTimer);
        typingTimer = setTimeout(function() {
            calculateDiscountPrice(no);
        }, doneTypingInterval);
    });

    // Event listener untuk langsung melakukan pengecekan saat input kehilangan fokus
    $("#table_item").on('blur', '.qty, .discount_percent', function () {
        var no = $(this).data('no');

        clearTimeout(typingTimer);
        calculateDiscountPrice(no);
    });
    // $("#table_item").on('keyup blur', '.qty, .discount_percent', function () {
    //     var no = $(this).data('no');

    //     calculateDiscountPrice(no);
    // });

    $("#table_item").on('change', '.select-tax1, .select-tax2', function () {
        var no = $(this).data('no');

        calculateDiscountPrice(no);
    });

    $('input[name="rate"]').on('keyup blur', function () {
        if ($('#table_item tbody').children().length != 0) {
            var rate = $(this).val();

            calculate_base_price(rate);
        }
    });

    function calculate_base_price(rate) {
        $(".qty").each(function () {
            // Ambil nilai dari atribut data-no
            var no = $(this).data('no');
            var qty = $(this).val();
            var unit_price = formatAritmatika($('.unit_price' + no).val());
            var base_unit_price = unit_price * rate;

            $('.base_unit_price' + no).val(formatIdrAccounting(base_unit_price));
            $('.total_base_price' + no).val(formatIdrAccounting(base_unit_price * qty));
        });
    }

    $(document).on('click', '#calculate', function () {
        calculate_all();
    });

    function calculate_all() {
        let sum_total_price = 0;
        let sum_value_tax_1 = 0;
        let sum_value_tax_2 = 0;
        $(".total_price").each(function () {
            var no = $(this).data('no');

            var value_tax_1 = $('.value_tax1' + no).val() ? $('.value_tax1' + no).val() : '0.00';
            var value_tax_2 = $('.value_tax2' + no).val() ? $('.value_tax2' + no).val() : '0.00';

            sum_total_price += +formatAritmatika($(this).val());
            sum_value_tax_1 += +formatAritmatika(value_tax_1);
            sum_value_tax_2 += +formatAritmatika(value_tax_2);
        });

        // console.log(sum_total_price);

        let total_amount = sum_total_price;

        // Hitung jumlah diskon
        var percent_discount_all = $('input[name="percent_discount_all"]').val();
        var discount = 0;
        if (percent_discount_all) {
            var discount = (total_amount * $('input[name="percent_discount_all"]').val()) / 100;
            discount = discount;
        }

        let grand_total = total_amount - discount;

        $('input[name="total_amount"]').val(formatIdrAccounting(total_amount));
        $('input[name="total_discount"]').val(formatIdrAccounting(discount));
        $('input[name="total_tax_1"]').val(formatIdrAccounting(sum_value_tax_1));
        $('input[name="total_tax_2"]').val(formatIdrAccounting(sum_value_tax_2));
        $('input[name="grand_total"]').val(formatIdrAccounting(grand_total));
    }

    $("#DataTable_Modal_ListItem tbody").on("click", ".chk_select", function () {
        var sysId = $(this).val();
        var row = $(this).closest('tr');
        var rowData = $('#DataTable_Modal_ListItem').DataTable().row(row).data();

        if ($(this).is(':checked')) {
            selectedRows[sysId] = rowData;
        } else {
            delete selectedRows[sysId];
        }

        if ($(this).parents("tr").hasClass("selected")) {
            $(this).parents("tr").removeClass("selected");
        } else {
            $(this).parents("tr").addClass("selected");
        }
    });

    $('#no_data_item').show('slow');
    $('.footer-table').hide('slow');

    $(document).on('click', '.remove_item_dtl', function () {
        var trIndex = $(this).closest("tr").index();
        var state = $('input[name="state"]').val();

        if (state == 'ADD' || state == 'EDIT') {
            $(this).closest("tr").remove();
            if (trIndex == 0) {
                $('#no_data_item').show('slow');
                $('.footer-table').hide('slow');
            }
        } else if (state == 'REVISI') {
            var qty_rr = parseFloat($(this).closest("tr").find('.qty_rr').val());

            if (qty_rr > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Item tidak dapat dihapus karena sudah terproses dalam RR!',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Confirm!',
                    footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                });
            } else {
                $(this).closest("tr").remove();
                if (trIndex == 0) {
                    $('#no_data_item').show('slow');
                    $('.footer-table').hide('slow');
                }
            }
        }
    });

    $('#search-list-item').keyup(function () {
        var found = false;

        var searchText = $(this).val().toLowerCase();
        $('#table_item tbody tr').each(function () {
            var Item_Code = $(this).find('td:eq(1) input').val().toLowerCase(); // Ambil nilai dari input di kolom Item_Code
            var Item_Name = $(this).find('td:eq(2) input').val().toLowerCase();

            if (Item_Code.includes(searchText) || Item_Name.includes(searchText)) {
                $(this).show();
                found = true;
            } else {
                $(this).hide();
            }
        });

        if (!found) {
            $('#no_data_item').show();
        } else {
            $('#no_data_item').hide();
        }
    });
    // --------- ADD SAVE --------- //

    // ------------------------------------ START FORM VALIDATION
    const MainForm = $('#main-form');
    const BtnSubmit = $('#btn-submit');
    MainForm.validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
    $.validator.setDefaults({
        debug: true,
        success: 'valid'
    });

    $(BtnSubmit).click(function (e) {
        e.preventDefault();
        if (MainForm.valid()) {
            Swal.fire({
                title: 'Loading....',
                html: '<div class="spinner-border text-primary"></div>',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            Fn_Submit_Form(MainForm)
        } else {
            $('html, body').animate({
                scrollTop: ($('.error:visible').offset().top - 200)
            }, 400);
        }
    });
    // ------------------------------------ END FORM VALIDATION

    function Fn_Submit_Form() {
        calculate_all();

        if ($('#table_item tbody').children().length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Ooppss...',
                text: 'Detail Item Tidak Boleh Kosong!',
                footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
            });

            return true;
        }

        BtnSubmit.prop("disabled", true);
        var formDataa = new FormData(MainForm[0]);

        $.ajax({
            dataType: "json",
            type: "POST",
            url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/store",
            data: formDataa,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                Swal.close()
                if (response.code == 200) {
                    $(MainForm)[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.msg,
                        showCancelButton: false,
                    }).then((result) => {
                        form_state('LOAD');
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
                BtnSubmit.prop("disabled", false);
            },
            error: function (xhr, status, error) {
                var statusCode = xhr.status;
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
                });

                BtnSubmit.prop("disabled", false);
            }
        });
    }
    // ------- ADD SAVE - END --------- //
    // ------------------- FUNGSI ADD - END ----------------- //
    // ======================================================== //

    // ----------------------- BROWSE DATA ------------------ //
    // -- Reload Data Address
    function reloadDataAddress(VendorId) {
        $("#table-address").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            lengthMenu: [
                [10, 25, 50, 10000],
                [10, 25, 50, 'All']
            ],
            select: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/DT_listdata_VendorAddress",
                dataType: "json",
                type: "POST",
                data: {
                    vendor_id: VendorId
                },
            },
            columns: [
                {
                    data: 'SysId', // gunakan 'null' karena kita akan menggunakan render function
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // meta.row adalah indeks baris (mulai dari 0)
                    },
                },
                {
                    data: "Address",
                },
                {
                    data: "Area",
                },
                {
                    data: "Description",
                },
            ],
            order: [
                [0, "desc"]
            ],
            columnDefs: [{
                className: "text-center",
                targets: [0, 2],
            },
            {
                className: "text-left",
                targets: []
            }
            ],
            autoWidth: false,
            // responsive: true,
            preDrawCallback: function () {
                $("#table-address tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function () {
                $("#table-address tbody td").addClass("blurry");
                setTimeout(function () {
                    $("#table-address tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    }

    $("#btn-select-address").click(function (e) {
        table = $("#table-address").DataTable();
        data = table.rows('.selected').data()[0];

        $('input[name="vendor_address_id"]').val(data.SysId);
        $('input[name="vendor_address"]').val(data.Address);

        $("#addressModal").modal("hide");
    });

    $("#btn-list-address").click(function (event) {
        var VendorId = $('input[name="vendor_id"]').val();

        if ($('input[name="vendor"]').val() == '') {
            Swal.fire({
                icon: "warning",
                title: "Ooppss...",
                text: "Silahkan masukkan item detail terlebih dahulu!",
                footer:
                    '<a href="javascript:void(0)" class="text-info">Informasi System</a>',
            });
            return true;
        }

        $('#addressModal').modal('show');
        reloadDataAddress(VendorId);
    });
    // -- Reload Data Address - END

    // -- Reload Data Person
    function reloadDataPerson(VendorId) {
        $("#table-person").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            lengthMenu: [
                [10, 25, 50, 10000],
                [10, 25, 50, 'All']
            ],
            select: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/DT_listdata_Person",
                dataType: "json",
                type: "POST",
                data: {
                    vendor_id: VendorId
                },
            },
            columns: [
                {
                    data: 'Sysid', // gunakan 'null' karena kita akan menggunakan render function
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // meta.row adalah indeks baris (mulai dari 0)
                    },
                },
                {
                    data: "Contact_Name",
                },
                {
                    data: "Contact_Initial_Name",
                },
                {
                    data: "Job_title",
                },
            ],
            order: [
                [0, "desc"]
            ],
            columnDefs: [{
                className: "text-center",
                targets: [0, 2],
            },
            {
                className: "text-left",
                targets: []
            }
            ],
            autoWidth: false,
            // responsive: true,
            preDrawCallback: function () {
                $("#table-person tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function () {
                $("#table-person tbody td").addClass("blurry");
                setTimeout(function () {
                    $("#table-person tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    }

    $("#btn-select-person").click(function (e) {
        table = $("#table-person").DataTable();
        data = table.rows('.selected').data()[0];

        $('input[name="person_id"]').val(data.Sysid);
        $('input[name="person"]').val(data.Contact_Name);

        $("#personModal").modal("hide");
    });

    $("#btn-list-person").click(function (event) {
        var VendorId = $('input[name="vendor_id"]').val();

        if ($('input[name="vendor"]').val() == '') {
            Swal.fire({
                icon: "warning",
                title: "Ooppss...",
                text: "Silahkan masukkan item detail terlebih dahulu!",
                footer:
                    '<a href="javascript:void(0)" class="text-info">Informasi System</a>',
            });
            return true;
        }

        $('#personModal').modal('show');
        reloadDataPerson(VendorId);
    });
    // -- Reload Data Person - END
    // ------------------- BROWSE DATA - END ------------------ //

    // ----------- Fungsi Get Master Currency --------- //
    function getSelect(callback) {
        $.ajax({
            dataType: "json",
            type: "POST",
            url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/getSelect",
            success: function (response) {
                if (response.code == 200) {
                    var option_cost_center = '';
                    $.each(response.cost_center, function (index, rowData) {
                        option_cost_center += '<option value="' + rowData.SysId + '">' + rowData.nama_cost_center + '</option>';
                    });

                    var base_tax = '<input type="hidden" class="base_tax base_tax0" value="0">';

                    var option_tax = '<option value="" selected>None</option>';
                    var i = 1;
                    $.each(response.tax, function (index, rowData) {
                        var Tax_Rate = formatAritmatika(rowData.Tax_Rate);
                        base_tax += '<input type="hidden" class="base_tax' + rowData.Tax_Id + '" value="' + Tax_Rate + '">';

                        option_tax += '<option value="' + rowData.Tax_Id + '">' + rowData.Tax_Name + '</option>';
                        i++;
                    });

                    callback(option_cost_center, base_tax, option_tax); // Pass the data to the callback
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Gagal Mendapatkan Select Detail",
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, Confirm!',
                        footer: '<a href="javascript:void(0)">Notification System</a>'
                    });
                }
            },
            error: function (xhr, status, error) {
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
    // -------- Fungsi Get Master Currency - END --------- //

    // ----------------- DEFINISI PRICE -------------------- //
    $(document).on('keypress keyup blur', '.only-number', function (event) {
        var inputVal = $(this).val();
        // Mengizinkan hanya digit, titik (.) dan koma (,)
        $(this).val(inputVal.replace(/[^\d.,]/g, ""));

        // Pengecekan untuk mencegah lebih dari satu titik atau koma
        if (
            (event.which !== 44 || inputVal.indexOf(",") !== -1) &&
            (event.which !== 46 || inputVal.indexOf(".") !== -1) &&
            (event.which < 48 || event.which > 57)
        ) {
            event.preventDefault();
        }
    });
    // ------------ DEFINISI PRICE - END --------------- //

    function formatAritmatika(str) {
        return str ? str.replace(/,/g, '') : '0';
    }
});