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
                $('.list-data').hide("slow");
                $('.add-data').show("slow");
                $('input[name="state"]').val('ADD');
                $('#title-add-hdr').html('Add');
                $('input[name="doc_no"]').val('Doc Number Akan Otomatis di isikan Oleh system.');
                $('#no_data_item').show('slow');
                $('#btn-submit').show();
                flatpickr();
                break;

            case 'EDIT':
                reset_input();
                $('.list-data').hide();
                $('.add-data').show();
                $('input[name="state"]').val('EDIT');
                $('#title-add-hdr').html('Edit');
                break;

            case 'DETAIL':
                reset_input();
                $('.list-data').hide();
                $('.add-data').show();
                $('#title-add-hdr').html('Detail');
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
        $('select[name="vendor"]').select2().val("").trigger("change");
        $('select[name="item_category"]').select2().val("").trigger("change");
        $('#table_item tbody').html('');
    }

    function reloadData() {
        $("#DataTable").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
            lengthMenu: [
                [10, 25, 50, 10000],
                [10, 25, 50, 'All']
            ],
            select: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Purchase/VendorPrice/DT_listdata",
                dataType: "json",
                type: "POST",
            },
            columns: [
                {
                    data: "VPR_NUMBER",
                },
                {
                    data: "VPR_DATE",
                    render: function (data, type, row, meta) {
                        return moment(data).format("DD MMMM YYYY");
                    }
                },
                {
                    data: "VPR_NOTES",
                    render: function (data, type, row, meta) {
                        return data ? data : '-';
                    }
                },
                {
                    data: "Account_Name",
                },
                {
                    data: "VPR_STATUS",
                    render: function (data, type, row, meta) {
                        if (data == 1) {
                            return `<i class="fas fa-check text-success"></i>`
                        } else {
                            return `<i class="fas fa-times text-danger"></i>`
                        }
                    }
                },
                {
                    data: "APPROVAL_STATUS",
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
                className: "text-center",
                targets: "_all",
            },
            {
                className: "text-left",
                targets: []
            }
            ],
            autoWidth: false,
            // responsive: true,
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
                text: `<i class="fas fa-plus fs-3"></i>&nbsp; Add VPR`,
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
                            text: 'Silahkan pilih data untuk melihat detail !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        Init_Edit_Detail(RowData[0].VPR_NUMBER, 'EDIT')
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
                        Init_Edit_Detail(RowData[0].VPR_NUMBER, 'DETAIL')
                    }
                }
            }, {
                // text: `<i class="fas fa-toggle-on"></i>`,
                text: `<i class="fa fa-times fs-3"></i>&nbsp; Cancel`,
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
                    } else if(RowData[0].VPR_STATUS == 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Data tidak bisa di ubah karena sudah tidak aktif !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        Fn_Toggle_Status(RowData[0].VPR_NUMBER)
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
    }

    function Init_Edit_Detail(VPR_NUMBER, State) {
        $.ajax({
            dataType: "json",
            type: "POST",
            url: $('meta[name="base_url"]').attr('content') + "Purchase/VendorPrice/edit",
            data: {
                vpr_number : VPR_NUMBER,
                state      : State 
            },
            success: function (response) {
                Swal.close()
                if (response.code == 200) {
                    form_state(State);

                    $('input[name="doc_no"]').val(response.data_hdr.VPR_NUMBER);
                    $('input[name="vpr_date"]').val(moment(response.data_hdr.VPR_DATE).format("DD MMMM YYYY"));
                    $('select[name="vendor"]').val(response.data_hdr.ACCOUNT_ID).trigger('change');
                    $('select[name="item_category"]').val(response.data_hdr.ITEM_CATEGORY_ID).trigger('change');
                    $('textarea[name="notes"]').val(response.data_hdr.VPR_NOTES);

                    // DETAIL //
                    var $tableItem = $('#table_item tbody');
                    $tableItem.empty();

                    getCurrency(function (currencyOptions) {
                        var no = 1;
                        $.each(response.data_dtl, function (index, rowData) {
                            var $newRow = $('<tr>');

                            // Buat kolom dengan input sesuai dengan data yang ada
                            $newRow.append('<td><input type="hidden" name="sysid_item[]" value="' + rowData.SysId_Item + '"><p class="mt-1">' + no + '</p></td>');
                            $newRow.append('<td><input class="form-control form-control-sm" name="item_code[]" type="text" value="' + rowData.ITEM_CODE + '" readonly></td>');
                            $newRow.append('<td><input class="form-control form-control-sm" name="item_name[]" type="text" value="' + rowData.Item_Name + '" readonly></td>');
                            $newRow.append('<td><input class="form-control form-control-sm" name="Item_Type[]" type="text" value="' + rowData.ITEM_TYPE + '" readonly></td>');
                            $newRow.append('<td><input class="form-control form-control-sm" type="text" value="' + rowData.Uom + '" readonly></td>');

                            $newRow.append(`
                            <td>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text txt-price`+ no + `">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control form-control-sm price only-number price`+ no + `" data-no="` + no + `" value="" name="price[]"> 
                                </div>
                            </td>`);

                            $newRow.append(`<td>
                                <select class="form-control form-control-sm select2 select-currency select2-currency`+ no + `" name="currency[]" data-no="` + no + `"></select>
                            </td>`);

                            $newRow.append(`
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm effective_date-`+ no + ` flatpickr" value="` + moment(rowData.EFFECTIVE_DATE).format("DD MMMM YYYY") + `" name="effective_date[]"> 
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </td>`);

                            $newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');

                            // Inisialisasi Select2 untuk elemen select dalam baris baru
                            var $select2Currency = $newRow.find('.select2-currency' + no);
                            $select2Currency.html(currencyOptions).select2();

                            // Masukkan baris baru ke dalam tabel tujuan
                            $tableItem.append($newRow);

                            // Set nilai default mata uang
                            $select2Currency.val(rowData.currency_id).trigger('change');
                            $('.price' + no).val(formatIdrAccounting(rowData.PRICE));

                            no++
                        });

                        $(".flatpickr").flatpickr({
                            dateFormat: "d F Y"
                        });

                        $('.flatpickr').removeAttr('readonly');
                    });

                    if (State == 'DETAIL') {
                        $('#btn-submit').hide();
                    } else {
                        $('#btn-submit').show();
                    }
                    
                    $('#no_data_item').hide('slow');
                    // DETAIL - END //
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

    function Fn_Toggle_Status(VPR_NUMBER) {
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
                    url: $('meta[name="base_url"]').attr('content') + "Purchase/VendorPrice/Toggle_Status",
                    type: "post",
                    dataType: "json",
                    data: {
                        vpr_number: VPR_NUMBER,
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
        form_state('BACK');
    });

    // ======================================================== //
    // --------------------- FUNGSI ADD ----------------- //

    $('select[name="vendor"]').change(function () {
        $('select[name="item_category"]').select2().val("").trigger("change");
    });

    $('select[name="item_category"]').change(function () {
        $('#no_data_item').show('slow');
        $('#table_item tbody').empty();
    });

    // Event delegation untuk menangkap perubahan pada elemen select-currency
    $(document).on('change', '.select-currency', function () {
        var no = $(this).data("no");

        const cur = $('option:selected', '.select2-currency' + no).text();
        const afterCutCur = cur.split('-')[1].trim();

        $('.txt-price' + no).html(afterCutCur);
        $('.price' + no).val(0);
    });

    var selectedRows = {};

    $(document).on('click', '.tambah_item_produk', function () {
        var vendor = $('select[name="vendor"]').val();
        var item_category = $('select[name="item_category"]').val();

        if (!vendor || !item_category) {
            Swal.fire({
                icon: 'warning',
                title: 'Ooppss...',
                text: 'Silahkan Pilih Vendor & Item Category Terlebih Dahulu!',
                footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
            });

            return true;
        }

        selectedRows = {};
        reloadDataItem();
    });

    function reloadDataItem() {
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
                data: "Uom",
            },
            {
                data: "Group_Name",
            },
            {
                data: "Default_Currency_Id",
            },
        ];

        var sysid_items = [];
        var sysid_items = $('input[name="sysid_item[]"]').map(function () {
            return $(this).val();
        }).get();

        $("#DataTable_Modal_ListItem").DataTable({
            destroy: true,
            processing: false,
            serverSide: true,
            lengthMenu: [
                [10, 25, 50, 10000],
                [10, 25, 50, 'All']
            ],
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Purchase/VendorPrice/DT_modallistofitem?sysid_items=" + sysid_items + "&item_category=" + $('select[name="item_category"]').val(),
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

        $('#modal_list_item').modal('show');

        $(document).off('click', '#select_item'); // Hapus event handler sebelumnya
        $(document).on('click', '#select_item', function () {
            var selectedRowsData = [];

            $.each(selectedRows, function (sysId, rowData) {
                if (rowData) {
                    selectedRowsData.push(rowData);
                }
            });

            // Menambahkan data ke tabel tujuan (#table-item)
            var $tableItem = $('#table_item tbody');

            // Kosongkan tabel tujuan sebelum memasukkan data baru
            var no = 1;
            if ($tableItem.children().length === 0) {
                $tableItem.empty();
            } else {
                var lastNumber = $('#table_item tbody tr:last td:first p').text().trim();
                no = parseInt(lastNumber) + 1;
            }

            // Iterasi melalui selectedRows dan buat baris baru di tabel tujuan
            getCurrency(function (currencyOptions) {
                $.each(selectedRowsData, function (index, rowData) {
                    var $newRow = $('<tr>');

                    // Buat kolom dengan input sesuai dengan data yang ada
                    $newRow.append('<td><input type="hidden" name="sysid_item[]" value="' + rowData.SysId + '"><p class="mt-1">' + no + '</p></td>');
                    $newRow.append('<td><input class="form-control form-control-sm" name="item_code[]" type="text" value="' + rowData.Item_Code + '" readonly></td>');
                    $newRow.append('<td><input class="form-control form-control-sm" name="item_name[]" type="text" value="' + rowData.Item_Name + '" readonly></td>');
                    $newRow.append('<td><input class="form-control form-control-sm" name="Item_Type[]" type="text" value="' + rowData.Group_Name + '" readonly></td>');
                    $newRow.append('<td><input class="form-control form-control-sm" type="text" value="' + rowData.Uom + '" readonly></td>');

                    $newRow.append(`
                    <td>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <div class="input-group-text txt-price`+ no + `">
                                </div>
                            </div>
                            <input type="text" class="form-control form-control-sm price only-number price`+ no + `" data-no="` + no + `" value="0" name="price[]"> 
                        </div>
                    </td>`);

                    $newRow.append(`<td>
                        <select class="form-control form-control-sm select2 select-currency select2-currency`+ no + `" name="currency[]" data-no="` + no + `"></select>
                    </td>`);

                    $newRow.append(`
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm effective_date-`+ no + ` flatpickr" value="`+ moment().format("DD MMMM YYYY") +`" name="effective_date[]"> 
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </td>`);
                    $newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');

                    // Inisialisasi Select2 untuk elemen select dalam baris baru
                    var $select2Currency = $newRow.find('.select2-currency' + no);
                    $select2Currency.html(currencyOptions).select2();

                    // Masukkan baris baru ke dalam tabel tujuan
                    $tableItem.append($newRow);

                    // Set nilai default mata uang
                    $select2Currency.val(rowData.Default_Currency_Id).trigger('change');

                    $(".flatpickr").flatpickr({
                        dateFormat: "d F Y"
                    });

                    $(".flatpickr").removeAttr('readonly');

                    $('.effective_date-' + no).val(moment().format("DD MMMM YYYY")).removeAttr('readonly');

                    no++;
                });
            });

            $('#no_data_item').hide('slow');
            $('#modal_list_item').modal('hide');
        });
    }

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

    // Ini Pake Document Karena kalau misal ada kondisi apend biar mengakamodir
    $(document).on('blur', '.price', function (event) {
        $(this).val(formatIdrAccounting($(this).val()));
    });
    // ------------ DEFINISI PRICE - END --------------- //

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
    $(document).on('click', '.remove_item_dtl', function () {
        var trIndex = $(this).closest("tr").index();

        $(this).closest("tr").remove();
        if (trIndex == 0) {
            $('#no_data_item').show('slow');
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
            url: $('meta[name="base_url"]').attr('content') + "Purchase/VendorPrice/store",
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
            }
        });
    }
    // ------- ADD SAVE - END --------- //
    // ------------------- FUNGSI ADD - END ----------------- //
    // ======================================================== //

    // ----------- Fungsi Get Master Currency --------- //
    function getCurrency(callback) {
        $.ajax({
            dataType: "json",
            type: "POST",
            url: $('meta[name="base_url"]').attr('content') + "Purchase/VendorPrice/getCurrency",
            success: function (response) {
                Swal.close();

                var option_currency = '';
                if (response.code == 200) {
                    $.each(response.currency, function (index, rowData) {
                        option_currency += '<option value="' + rowData.Currency_ID + '">' + rowData.Currency_ID + ' - ' + rowData.Currency_Symbol + '</option>';
                    });
                }

                callback(option_currency); // Pass the data to the callback
            },
            error: function (xhr, status, error) {
                // Handle error
            }
        });
    }
    // -------- Fungsi Get Master Currency - END --------- //
});
