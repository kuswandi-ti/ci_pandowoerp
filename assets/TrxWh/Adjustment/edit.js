$(document).ready(function () {
    $(document).on('focus', 'input[name="Qty[]"]', function () {
        if ($(this).val() == '0') {
            $(this).val('');
        }
    })
    $(document).on('focus', 'input[name="Price[]"]', function () {
        if ($(this).val() == '0.00') {
            $(this).val('');
        }
    })

    let inputOptionsPromiseCC = new Promise(function (resolve) {
        $.getJSON($('meta[name="base_url"]').attr('content') + "TrxWh/Adjustment/List_Cost_Center", function (data) {

            resolve(data)
        });
    })

    let inputOptionsPromiseWH = new Promise(function (resolve) {
        $.getJSON($('meta[name="base_url"]').attr('content') + "TrxWh/Adjustment/List_Warehouse_FG", function (data) {
            resolve(data)
        });
    })

    let CC_OptionsHtml = '';
    inputOptionsPromiseCC.then(function (optionsData) {
        Object.entries(optionsData).forEach(([key, value]) => {
            CC_OptionsHtml += `<option value="${key}">${value}</option>`;
        });
    }).catch(error => {
        console.error('Error:', error);
    });

    let WH_OptionsHtml = '';
    inputOptionsPromiseWH.then(function (optionsData) {
        Object.entries(optionsData).forEach(([key, value]) => {
            WH_OptionsHtml += `<option value="${key}">${value}</option>`;
        });
    }).catch(error => {
        console.error('Error:', error);
    });

    var selectedRows = {};
    $(document).on('click', '.search-data', function () {
        $('#modal_list_browse').modal('show');
        selectedRows = {};
        Swal.fire({
            title: 'Loading....',
            html: '<div class="spinner-border text-primary"></div>',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        })
        reloadData();
    });

    function reloadData() {
        var SysId = [];
        var SysId = $('input[name="SysId[]"]').map(function () {
            return $(this).val();
        }).get();

        $("#Tbl_Browse_Data").DataTable({
            destroy: true,
            processing: false,
            serverSide: true,
            lengthMenu: [
                [10, 25, 50, 1000000000],
                [10, 25, 50, 'All']
            ],
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "TrxWh/Adjustment/DT_list_Item?sysid=" + SysId,
                dataType: "json",
                type: "POST",
                data: {
                    startDate: $('#from').val(),
                    endDate: $('#to').val()
                }
            },
            columns: [{
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
                data: "Brand",
            },
            {
                data: "Model",
            },
            {
                data: "Item_Color",
            },
            {
                data: "Item_Dimensions",
            },
            {
                data: "Qty_Avaliable",
                render: function (data) {
                    return parseFloat(data)
                }
            }
            ],
            "order": [
                [2, "asc"]
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
            autoWidth: true,
            responsive: true,
            preDrawCallback: function () {
                $("#Tbl_Browse_Data tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function () {
                $("#Tbl_Browse_Data tbody td").addClass("blurry");
                setTimeout(function () {
                    $("#Tbl_Browse_Data tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
                for (var key in selectedRows) {
                    if (selectedRows.hasOwnProperty(key)) {
                        $('#basic_checkbox_' + key).prop('checked', true);
                        $('#basic_checkbox_' + key).closest('tr').addClass('selected');
                    }
                }

                // Add event listener for checkboxes
                $('.chk_select').on('change', function () {
                    var id = $(this).val();
                    var rowData = $("#Tbl_Browse_Data").DataTable().row($(this).closest('tr')).data();
                    if ($(this).is(':checked')) {
                        selectedRows[id] = rowData;
                    } else {
                        delete selectedRows[id];
                    }
                });
            },
            initComplete: function (settings, json) {
                Swal.close()
            },
        });

        $(document).off('click', '#select_data');
        $(document).on('click', '#select_data', function () {
            var selectedRowsData = [];

            $.each(selectedRows, function (sysId, rowData) {
                if (rowData) {
                    selectedRowsData.push(rowData);
                }
            });
            var $tableItem = $('#table_data_selected tbody');
            var no = 1;
            if ($tableItem.children().length === 0) {
                $tableItem.empty();
            } else {
                var lastNumber = $('#table_data_selected tbody tr:last td:first p').text().trim();
                no = parseInt(lastNumber) + 1;
            }
            $.each(selectedRowsData, function (index, rowData) {
                var $newRow = $('<tr>');
                $newRow.append(`<td class="text-center align-middle"><input type="hidden" required name="SysId[]" value="${rowData.SysId}"><p class="mt-1">${no}</p></td>`);
                $newRow.append('<td class="text-center align-middle"><input class="text-center" type="hidden" readonly name="Item_Code[]" id="Item_Code_' + rowData.SysId + '" value="' + rowData.Item_Code + '">' + rowData.Item_Code + '</td>');
                $newRow.append('<td class="text-center align-middle">' + rowData.Item_Name + '</td>');
                $newRow.append('<td class="text-center align-middle" style="width: 150px;"><div class="input-group input-group-xs"><input type="text" class="form-control" name="Qty[]" id="Qty_' + rowData.SysId + '" value="0" placeholder="kuantitas..."></div><input type="hidden" class="Qty_stok" name="Qty_stok[]" id="Qty_stok_' + rowData.SysId + '" value="' + formatIdr(rowData.Qty_Avaliable) + '"></td>');
                $newRow.append('<td class="text-center align-middle">' + rowData.Uom + '</td>');
                $newRow.append('<td class="text-center align-middle">' + 'IDR' + '</td>');
                $newRow.append('<td class="text-center align-middle" style="width: 175px;"><div class="input-group input-group-xs"><input type="text" class="form-control price" name="Price[]" id="Price_' + rowData.SysId + '" placeholder="harga/nilai item..." value="0.00"></div></td>');
                $newRow.append('<td class="text-center align-middle amount" id="amount_' + rowData.SysId + '">0.00</td>');
                $newRow.append(`<td class="text-center align-middle">
									<div class="input-group input-group-xs">
										<select class="form-control aritmatics" name="aritmatic[]" id="aritmatic_${rowData.SysId}">
										<option value="">-Pilih-</option>
										<option value="+">Penyesuaian Plus (+)</option>
										<option value="-">Penyesuaian Minus (-)</option>
										</select>
									</div> 
								</td>`);
                $newRow.append(`
					<td class="align-middle text-center">
						<div class="input-group input-group-xs">
								<select class="form-control select2 whs" name="wh_id[]" id="wh_id_${rowData.SysId}">
									<option selected disabled value="">-Pilih-</option>
									${WH_OptionsHtml}
								</select>
						</div>
					</td>`);
                $newRow.append(`
						<td class="align-middle text-center">
							<div class="input-group input-group-xs">
								<select class="form-control select2 ccs" name="ccs[]" id="ccs_${rowData.SysId}">
									<option selected disabled value="">-Pilih-</option>
									${CC_OptionsHtml}
								</select>
							</div>
						</td>`);
                $newRow.append(`<td class="text-center align-middle"><button type="button" class="remove-row btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></td></tr>`);
                $tableItem.append($newRow);
                no++;
            });

            $('#no_data_selected').hide('slow');
            $('#modal_list_browse').modal('hide');
            $('.select2').select2()
        });

    }

    // Fungsi untuk menghitung dan mengupdate nilai pada td.amount
    function updateAmount(row) {
        // Ambil nilai dari input Qty dan Price
        var qty = parseFloat(row.find('input[name="Qty[]"]').val().replace(/,/g, '')) || 0;
        var price = parseFloat(row.find('input[name="Price[]"]').val().replace(/,/g, '')) || 0;

        // Hitung total amount
        var amount = qty * price;

        // Update nilai pada td.amount
        row.find('td.amount').html(formatIdr(amount));
        row.find('input[name="Qty[]"]').val(formatIdr(qty))
        row.find('input[name="Price[]"]').val(formatIdr(price))
    }

    // Event listener untuk Qty
    $(document).on('blur', 'input[name="Qty[]"]', function () {
        var row = $(this).closest('tr');
        updateAmount(row);
    });

    // Event listener untuk Price
    $(document).on('blur', 'input[name="Price[]"]', function () {
        var row = $(this).closest('tr');
        updateAmount(row);
    });

    // ------------------------------------ START FORM VALIDATION
    const MainForm = $('#main-form');
    const BtnSubmit = $('#btn-submit');
    MainForm.validate({
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.input-group').append(error);
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
        // -------------------- start validasi manual
        let hasErrors = false;

        $('input[name="Qty[]"]').each(function () {
            let inputValue = $(this).val();
            let inputGroup = $(this).closest('.input-group');

            inputGroup.find('.error.invalid-feedback').remove();
            $(this).removeClass('is-invalid')
            if (inputValue == "" || inputValue == null || inputValue == 0) {
                hasErrors = true;
                $(this).addClass('is-invalid')
                inputGroup.append('<span class="error invalid-feedback">This field is required.</span>');
            }
        });

        $('input[name="Price[]"]').each(function () {
            let PriceValue = $(this).val();
            let inputGroup = $(this).closest('.input-group');

            inputGroup.find('.error.invalid-feedback').remove();
            $(this).removeClass('is-invalid')
            if (PriceValue == "" || PriceValue == null || PriceValue == 0) {
                hasErrors = true;
                $(this).addClass('is-invalid')
                inputGroup.append('<span class="error invalid-feedback">This field is required.</span>');
            }
        });

        $('select[name="aritmatic[]"]').each(function () {
            let AritmaticValue = $(this).val();
            let inputGroup = $(this).closest('.input-group');

            inputGroup.find('.error.invalid-feedback').remove();
            $(this).removeClass('is-invalid')
            if (AritmaticValue == "" || AritmaticValue == null || AritmaticValue == 0) {
                hasErrors = true;
                $(this).addClass('is-invalid')
                inputGroup.append('<span class="error invalid-feedback">This field is required.</span>');
            }
        });

        $('select[name="wh_id[]"]').each(function () {
            let WarehouseValue = $(this).val();
            let inputGroup = $(this).closest('.input-group');

            inputGroup.find('.error.invalid-feedback').remove();
            $(this).removeClass('is-invalid')
            if (WarehouseValue == "" || WarehouseValue == null || WarehouseValue == 0) {
                hasErrors = true;
                $(this).addClass('is-invalid')
                inputGroup.append('<span class="error invalid-feedback">This field is required.</span>');
            }
        });

        $('select[name="ccs[]"]').each(function () {
            let CcValue = $(this).val();
            let inputGroup = $(this).closest('.input-group');

            inputGroup.find('.error.invalid-feedback').remove();
            $(this).removeClass('is-invalid')
            if (CcValue == "" || CcValue == null || CcValue == 0) {
                hasErrors = true;
                $(this).addClass('is-invalid')
                inputGroup.append('<span class="error invalid-feedback">This field is required.</span>');
            }
        });

        // -------------------- end validasi manual
        if (!hasErrors) {
            if (MainForm.valid()) {
                Swal.fire({
                    title: 'Loading....',
                    html: '<div class="spinner-border text-primary"></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
                Initialize_Submit_Form(MainForm)
            } else {
                $('html, body').animate({
                    scrollTop: ($('.error:visible').offset().top - 200)
                }, 400);
            }
        }
    });

    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });

    // ------------------------------------ END FORM VALIDATION

    function Initialize_Submit_Form() {
        if ($('#table_data_selected tbody').children().length === 0) {
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
            url: $('meta[name="base_url"]').attr('content') + "TrxWh/Adjustment/update",
            data: formDataa,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                Swal.close()
                if (response.code == 200) {
                    // $(MainForm)[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.msg,
                        showCancelButton: false,
                    }).then((result) => {
                        return window.location.href = $('meta[name="base_url"]').attr('content') + "TrxWh/Adjustment/index";
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
})
