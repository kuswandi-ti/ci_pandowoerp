$(document).ready(function () {
	$(document).on('focus', 'input[name="Qty_Afkir[]"]', function () {
		if ($(this).val() == '0') {
			$(this).val('');
		}
	})
	var selectedRows = {};
	$(document).on('click', '.search-bundle', function () {
		$('#modal_list_lot').modal('show');
		selectedRows = {};
		reloadDataItem();
	});

	$(document).on('click', '#do--filter', function () {
		selectedRows = {};
		reloadDataItem();
		Swal.fire({
			title: 'Loading....',
			html: '<div class="spinner-border text-primary"></div>',
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false
		});
	})

	function reloadDataItem() {
		var SysId_child_size = [];
		var SysId_child_size = $('input[name="ID[]"]').map(function () {
			return $(this).val();
		}).get();

		$("#Tbl_List_Lot").DataTable({
			destroy: true,
			processing: false,
			serverSide: true,
			lengthMenu: [
				[10, 25, 50, 1000000000],
				[10, 25, 50, 'All']
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/LpbAfkir/DT_list_lot?sysid=" + SysId_child_size,
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
					data: "no_lot",
				},
				{
					data: "tgl_kirim",
				},
				{
					data: "Item_Code",
				},
				{
					data: "Item_Name",
				},
				{
					data: "Size_Code",
				},
				{
					data: "Item_Height",
					render: function (data) {
						return parseFloat(data)
					}
				},
				{
					data: "Item_Width",
					render: function (data) {
						return parseFloat(data)
					}
				},
				{
					data: "Item_Length",
					render: function (data) {
						return parseFloat(data)
					}
				},
				{
					data: "Qty_Available",
					render: function (data) {
						return parseFloat(data)
					}
				},
				{
					data: "flag",
					visible: false
				}
			],
			"order": [
				[1, "desc"],
				[10, "desc"]
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
				$("#Tbl_List_Lot tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#Tbl_List_Lot tbody td").addClass("blurry");
				setTimeout(function () {
					$("#Tbl_List_Lot tbody td").removeClass("blurry");
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
					var rowData = $("#Tbl_List_Lot").DataTable().row($(this).closest('tr')).data();
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
			var $tableItem = $('#table_bundle_selected tbody');
			var no = 1;
			if ($tableItem.children().length === 0) {
				$tableItem.empty();
			} else {
				var lastNumber = $('#table_bundle_selected tbody tr:last td:first p').text().trim();
				no = parseInt(lastNumber) + 1;
			}
			$.each(selectedRowsData, function (index, rowData) {
				var $newRow = $('<tr>');
				$newRow.append(`<td class="text-center align-middle"><input type="hidden" required name="ID[]" value="${rowData.SysId}"><p class="mt-1">${no}</p></td>`);
				$newRow.append('<td class="text-center align-middle">' + rowData.no_lot + '</td>');
				$newRow.append('<td class="text-center align-middle">' + rowData.Item_Code + '</td>');
				$newRow.append('<td class="text-center align-middle">' + rowData.Item_Name + '</td>');
				$newRow.append('<td class="text-center align-middle">' + rowData.Size_Code + '</td>');
				$newRow.append('<td class="text-center align-middle">' + parseFloat(rowData.Item_Height) + '</td>');
				$newRow.append('<td class="text-center align-middle">' + parseFloat(rowData.Item_Width) + '</td>');
				$newRow.append('<td class="text-center align-middle">' + parseFloat(rowData.Item_Length) + '</td>');

				$newRow.append('<td class="text-center align-middle"><input name="Qty_Available[]" type="number" readonly value="' + parseFloat(rowData.Qty_Available) + '" class="form-control form-control-sm only-number text-center qty_stok_item" id="Qty_Available_' + index + '"></td>');

				$newRow.append('<td class="align-middle"><div class="input-group"><input name="Qty_Afkir[]" type="number" class="form-control form-control-sm only-number text-center" value="0" id="qty_afkir_' + index + '"></td>');

				$newRow.append('<td><div class="input-group"><textarea class="form-control form-control-sm" placeholder="remark...." name="remark[]" id="remark_' + index + '"></textarea></td>');

				$newRow.append(`<td class="text-center align-middle"><button type="button" class="remove-row btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></td></tr>`);
				$tableItem.append($newRow);
				no++;
			});

			$('#no_data_item').hide('slow');
			$('#modal_list_lot').modal('hide');
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

		$('input[name="Qty_Afkir[]"]').each(function () {
			let inputValue = $(this).val();
			let inputGroup = $(this).closest('.input-group');
			let currentRow = $(this).closest('tr');
			let stokItem = currentRow.find('input.qty_stok_item');

			inputGroup.find('.error.invalid-feedback').remove();
			$(this).removeClass('is-invalid')
			if (inputValue === "" || inputValue === null || inputValue == 0) {
				hasErrors = true;
				$(this).addClass('is-invalid')
				inputGroup.append('<span class="error invalid-feedback">This field is required.</span>');
			}
			if (parseFloat(inputValue) > parseFloat(stokItem.val())) {
				hasErrors = true;
				$(this).addClass('is-invalid')
				inputGroup.append('<span class="error invalid-feedback">Alokasi melebihi qty stok.</span>');
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
		if ($('#table_bundle_selected tbody').children().length === 0) {
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
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/LpbAfkir/update",
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
						return window.location.href = $('meta[name="base_url"]').attr('content') + "TrxWh/LpbAfkir/index";
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


	function checkRowCount() {
		var rowCount = $("#table_bundle_selected tbody tr").length;

		if (rowCount > 0) {
			$('#no_data_item').hide('slow');
		} else {
			$('#no_data_item').show('slow');
		}
	}
	checkRowCount()
})
