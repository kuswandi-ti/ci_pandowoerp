$(document).ready(function () {
	$(document).on('focus', 'input[name="Qty[]"]', function () {
		if ($(this).val() == '0') {
			$(this).val('');
		}
	})
	var selectedRows = {};
	$(document).on('click', '.search-item', function () {
		selectedRows = {};
		Swal.fire({
			title: 'Loading....',
			html: '<div class="spinner-border text-primary"></div>',
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false
		})
		reloadDataItem();
		$('#modal_list_item').modal('show');
	});

	let inputOptionsPromiseCC = new Promise(function (resolve) {
		$.getJSON($('meta[name="base_url"]').attr('content') + "TrxWh/NotaHasilProduksi/List_Cost_Center", function (data) {

			resolve(data)
		});
	})

	let inputOptionsPromiseWH = new Promise(function (resolve) {
		$.getJSON($('meta[name="base_url"]').attr('content') + "TrxWh/NotaHasilProduksi/List_Warehouse_FG", function (data) {
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

	function reloadDataItem() {
		var SysId = [];
		var SysId = $('input[name="SysId[]"]').map(function () {
			return $(this).val();
		}).get();

		$("#DataTable_Modal_ListItem").DataTable({
			destroy: true,
			processing: false,
			serverSide: true,
			lengthMenu: [
				[10, 25, 50, 1000000000],
				[10, 25, 50, 'All']
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/NotaHasilProduksi/DT_list_Item_FG?sysid=" + SysId,
				dataType: "json",
				type: "POST"
			},
			columns: [{
				data: 'SysId',
				render: function (data, type, row) {
					var isChecked = selectedRows[data] ? 'checked' : '';
					var btnCheck = '<input type="checkbox" id="basic_checkbox_' + data + '" class="filled-in checkbox_checked chk_select" value="' + data + '" ' + isChecked + '/> <label for="basic_checkbox_' + data + '">&nbsp;</label>';
					return btnCheck;
				},
			}, {
				data: "Item_Code",
			}, {
				data: "Item_Name",
			}, {
				data: "Group_Name",
			}, {
				data: "Item_Color",
			}, {
				data: "Brand",
			}, {
				data: "Model",
			}, {
				data: "Item_Dimensions",
			}, {
				data: "Uom",
			}],
			"order": [
				[2, "ASC"]
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
				for (var key in selectedRows) {
					if (selectedRows.hasOwnProperty(key)) {
						$('#basic_checkbox_' + key).prop('checked', true);
						$('#basic_checkbox_' + key).closest('tr').addClass('selected');
					}
				}

				// Add event listener for checkboxes
				$('.chk_select').on('change', function () {
					var id = $(this).val();
					var rowData = $("#DataTable_Modal_ListItem").DataTable().row($(this).closest('tr')).data();
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
			var $tableItem = $('#table_item_selected tbody');
			var no = 1;
			if ($tableItem.children().length === 0) {
				$tableItem.empty();
			} else {
				var lastNumber = $('#table_item_selected tbody tr:last td:first p').text().trim();
				no = parseInt(lastNumber) + 1;
			}
			$.each(selectedRowsData, function (index, rowData) {
				var $newRow = $('<tr>');
				$newRow.append(`<td class="text-center align-middle"><input type="hidden" required name="SysId[]" value="${rowData.SysId}"><p class="mt-1">${no}</p></td>`);
				$newRow.append('<td class="text-center align-middle"><input type="hidden" name="item_codes[]" id="item_code_' + index + '" value="' + rowData.Item_Code + '">' + rowData.Item_Code + '</td>');
				$newRow.append('<td class="text-center align-middle">' + rowData.Item_Name + '</td>');
				$newRow.append('<td class="text-center align-middle">' + rowData.Item_Dimensions + '</td>');
				$newRow.append('<td class="text-center align-middle">' + rowData.Uom + '</td>');
				$newRow.append('<td class="align-middle" style="width:120px;"><div class="input-group input-group-xs"><input name="Qty[]" type="number" class="form-control only-number text-center" value="0" id="qty' + index + '" placeholder="Quantitas..."></td>');
				$newRow.append(`
                    <td class="align-middle">
                        <div class="input-group input-group-xs">
                                <select class="form-control select2 whs" name="wh_id[]" id="wh_id_${index}">
                                <option selected disabled value="">-Pilih-</option>
                                ${WH_OptionsHtml}
                                </select>
                        </div>
                    </td>`);
				$newRow.append(`
                        <td class="align-middle">
                            <div class="input-group input-group-xs">
                                <select class="form-control select2 ccs" name="ccs[]" id="ccs_${index}">
                                <option selected disabled value="">-Pilih-</option>
                                ${CC_OptionsHtml}
                                </select>
                            </div>
                        </td>`);
				$newRow.append('<td style="width:150px;"><div class="input-group input-group-xs"><textarea class="form-control" placeholder="remark...." name="remark[]" id="remark_' + index + '"></textarea></td>');
				$newRow.append(`<td class="text-center align-middle"><button type="button" class="remove-row btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></td></tr>`);
				$tableItem.append($newRow);
				no++;
			});

			$('#no_data_item').hide('slow');
			// $('.select2').select2()
			$('#modal_list_item').modal('hide');
		});

	}

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
			if (inputValue === "" || inputValue === null || inputValue == 0) {
				hasErrors = true;
				$(this).addClass('is-invalid')
				inputGroup.append('<span class="error invalid-feedback">Qty harus di isi</span>');
			}

		});
		$('select[name="wh_id[]"]').each(function () {
			let inputValue = $(this).val();
			let inputGroup = $(this).closest('.input-group');

			inputGroup.find('.error.invalid-feedback').remove();
			$(this).removeClass('is-invalid')
			if (inputValue === "" || inputValue === null || inputValue == 0) {
				hasErrors = true;
				$(this).addClass('is-invalid')
				inputGroup.append('<span class="error invalid-feedback">Pilih warehouse !</span>');
			}

		});
		$('select[name="ccs[]"]').each(function () {
			let inputValue = $(this).val();
			let inputGroup = $(this).closest('.input-group');

			inputGroup.find('.error.invalid-feedback').remove();
			$(this).removeClass('is-invalid')
			if (inputValue === "" || inputValue === null || inputValue == 0) {
				hasErrors = true;
				$(this).addClass('is-invalid')
				inputGroup.append('<span class="error invalid-feedback">Pilih Cost center</span>');
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
		if ($('#table_item_selected tbody').children().length === 0) {
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
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/NotaHasilProduksi/store",
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
						return window.location.href = $('meta[name="base_url"]').attr('content') + "TrxWh/NotaHasilProduksi/index";
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
