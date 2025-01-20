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
	});

	var selectedRows = {};
	$(document).on('click', '.tambah_item_produk', function () {
		var item_category = $('select[name="ItemCategoryType"]').val();

		if (!item_category) {
			Swal.fire({
				icon: 'warning',
				title: 'Ooppss...',
				text: 'Silahkan Pilih Item Category Terlebih Dahulu!',
				footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
			});

			return true;
		}

		selectedRows = {};
		reloadDataItem();
	});

	function reloadDataItem() {
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
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/UsageNote/DT_list_item?sysid_items=" + sysid_items + "&item_category=" + $('#ItemCategoryType').val(),
				dataType: "json",
				type: "POST",
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
				data: "Group_Name",
			},
			{
				data: "Item_Color",
			},
			{
				data: "Brand",
			},
			{
				data: "Model",
			},
			{
				data: "Item_Length",
				render: function (data, type, row) {
					return parseFloat(row.Item_Length) + ' x ' + parseFloat(row.Item_Width) + ' x ' + parseFloat(row.Item_Height) + ' ' + row.LWH_Unit
				}
			},
			{
				data: "Qty_Avaliable",
				render: function (data) {
					return parseFloat(data)
				}
			},
			{
				data: "Uom",
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
			},
			initComplete: function (settings, json) { },
		});

		$('#modal_list_item').modal('show');

		$(document).off('click', '#select_item');
		$(document).on('click', '#select_item', function () {
			var selectedRowsData = [];

			$.each(selectedRows, function (sysId, rowData) {
				if (rowData) {
					selectedRowsData.push(rowData);
				}
			});
			var $tableItem = $('#table_item tbody');
			var no = 1;
			if ($tableItem.children().length === 0) {
				$tableItem.empty();
			} else {
				var lastNumber = $('#table_item tbody tr:last td:first p').text().trim();
				no = parseInt(lastNumber) + 1;
			}
			$.each(selectedRowsData, function (index, rowData) {
				var $newRow = $('<tr>');
				$newRow.append(`<td>
									<input type="hidden" required name="sysid_item[]" value="${rowData.SysId}">
									<input type="hidden" required name="currency[]" value="${rowData.Default_Currency_Id}">
									<input type="hidden" required name="costingmethod[]" value="${rowData.CostingMethod}">
									<p class="mt-1">${no}</p>
								</td>`);
				$newRow.append('<td><input name="item_code[]" class="input-xs form-control" required type="text" value="' + rowData.Item_Code + '" readonly></td>');
				$newRow.append('<td><input name="item_name[]" class="input-xs form-control" required type="text" value="' + rowData.Item_Name + '" readonly></td>');
				$newRow.append('<td>' + rowData.Group_Name + '</td>');
				$newRow.append('<td>' + rowData.Item_Color + '</td>');
				$newRow.append('<td>' + rowData.Brand + '</td>');
				$newRow.append('<td>' + rowData.Model + '</td>');
				$newRow.append('<td>' + parseFloat(rowData.Item_Length) + ' x ' + parseFloat(rowData.Item_Width) + ' x ' + parseFloat(rowData.Item_Height) + ' ' + rowData.LWH_Unit + '</td>');
				$newRow.append('<td>' + rowData.Uom + '</td>');
				$newRow.append('<td><div class="input-group"><input name="qty[]" type="text" class="input-xs form-control only-number" value="0" id="qty_' + index + '"></td></div>');
				$newRow.append(`
                    <td>
                        <div class="input-group input-group-xs">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-xs bg-primary input-group-text append_modal_wh" data-no="${no}" data-item="${rowData.Item_Code}"><i class="fas fa-search"></i></button>
                            </div>
                            <input type="hidden" id="wh_id_${no}" name="wh_id[]"> 
                            <input type="text" class="form-control" id="wh_name_${no}" name="wh_name[]" id="wh_${index}'" readonly> 
                            <input type="hidden" id="qty_stok_${no}" name="qty_stok[]" class="qty_stok_item">
                        </div>
                    </td>`);
				$newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');
				$tableItem.append($newRow);
				no++;
			});

			$('#no_data_item').hide('slow');
			$('#modal_list_item').modal('hide');
		});
	}

	$(document).on('change', '#ItemCategoryType', function () {
		const tableItem = $('#table_item tbody');
		let rowCount = $('#table_item tbody tr').length;
		if (rowCount > 0) {
			tableItem.empty();
		}
	})

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
		$(this).closest("tr").remove();
		var rowCount = $('#table_item tbody tr').length;
		if (rowCount == 0) {
			$('#no_data_item').show('slow');
		}
	});

	$(document).on('keypress keyup blur', '.only-number', function (event) {
		var inputVal = $(this).val();
		$(this).val(inputVal.replace(/[^\d.,]/g, ""));
		if (
			(event.which !== 44 || inputVal.indexOf(",") !== -1) &&
			(event.which !== 46 || inputVal.indexOf(".") !== -1) &&
			(event.which < 48 || event.which > 57)
		) {
			event.preventDefault();
		}
	});

	$(document).on('input', '.price', function () {
		var value = $(this).val();
		var no = $(this).data("no");
		var value = value.replace(/[^\d]/g, '');

		if (value === '') {
			value = '0';
		}

		var def_cur = $('.txt-price' + no).text() == 'Rp' ? 'id-ID' : 'en-US';
		var formattedValue = parseInt(value, 10).toLocaleString(def_cur);

		$(this).val(formattedValue);
	});

	var WhselectedRows = {};
	$(document).on('click', '.append_modal_wh', function () {
		let no = $(this).attr('data-no');
		let item_code = $(this).attr('data-item');
		let id = $('#wh_id_' + no);
		let name = $('#wh_name_' + no);
		let stok = $('#qty_stok_' + no);

		$('#modal-title-stok').html(`List Persediaan Item : ${item_code}`)
		$('#modal_list_stok').modal('show');
		WhselectedRows = {};
		Initialize_DataTable_Stok(no, item_code, id, name, stok);
	})

	$(document).on('focus', 'input[name="qty[]"]', function () {
		if ($(this).val() == '0') {
			$(this).val('');
		}
	})

	function Initialize_DataTable_Stok(no, item_code, id, name, stok) {
		$("#DataTable_Modal_Stok").DataTable({
			destroy: true,
			processing: false,
			serverSide: true,
			lengthMenu: [
				[10, 25, 50, 10000],
				[10, 25, 50, 'All']
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/UsageNote/DT_list_stok",
				dataType: "json",
				type: "POST",
				data: {
					Item_Code: item_code
				}
			},
			columns: [{
				data: 'Warehouse_ID',
				render: function (data, type, row) {
					var isChecked = WhselectedRows[data] ? 'checked' : '';
					var btnCheck = '<input type="checkbox" id="wh_checkbox_' + data + '" class="filled-in stok_select" value="' + data + '" ' + isChecked + '/> <label for="wh_checkbox_' + data + '">&nbsp;</label>';
					return btnCheck;
				},
			},
			{
				data: "Warehouse_Name",
			},
			{
				data: "Warehouse_Code",
			},
			{
				data: "Item_Qty",
				render: function (data, type, row) {
					return parseFloat(data)
				}
			},
			{
				data: "Uom",
			}
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
			preDrawCallback: function () {
				$("#DataTable_Modal_Stok tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#DataTable_Modal_Stok tbody td").addClass("blurry");
				setTimeout(function () {
					$("#DataTable_Modal_Stok tbody td").removeClass("blurry");
				});
				$('[data-toggle="tooltip"]').tooltip();

				for (var key in WhselectedRows) {
					if (WhselectedRows.hasOwnProperty(key)) {
						$('#wh_checkbox_' + key).prop('checked', true);
						$('#wh_checkbox_' + key).closest('tr').addClass('selected');
					}
				}
			},
			initComplete: function (settings, json) {
				// ---------------
			},
		});

		$(document).off('click', '#select_wh');
		$(document).on('click', '#select_wh', function () {
			var selectedWhRows = [];

			$.each(WhselectedRows, function (sysId, rowData) {
				if (rowData) {
					selectedWhRows.push(rowData);
				}
			});
			$.each(selectedWhRows, function (index, rowData) {
				id.val(rowData.Warehouse_ID)
				name.val(rowData.Warehouse_Name)
				stok.val(rowData.Item_Qty)
			})

			$('#modal_list_stok').modal('hide');
		})
	}

	$("#DataTable_Modal_Stok tbody").on("click", ".stok_select", function () {
		var Warehouse_ID = $(this).val();
		var row = $(this).closest('tr');
		var rowData = $('#DataTable_Modal_Stok').DataTable().row(row).data();

		if ($(this).is(':checked')) {
			WhselectedRows[Warehouse_ID] = rowData;
		} else {
			delete WhselectedRows[Warehouse_ID];
		}

		if ($(this).parents("tr").hasClass("selected")) {
			$(this).parents("tr").removeClass("selected");
		} else {
			$(this).parents("tr").addClass("selected");
		}
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
		$('input[name="qty[]"]').each(function () {
			let inputValue = $(this).val();
			let inputGroup = $(this).closest('.input-group');
			let currentRow = $(this).closest('tr');
			let stokItem = currentRow.find('input.qty_stok_item');

			inputGroup.find('.error.invalid-feedback').remove();
			$(this).removeClass('is-invalid')
			if (inputValue === "" || inputValue === null || inputValue == 0) {
				hasErrors = true;
				inputGroup.append('<span class="error invalid-feedback">This field is required.</span>');
			}
			if (parseFloat(inputValue) > parseFloat(stokItem.val())) {
				hasErrors = true;
				$(this).addClass('is-invalid')
				inputGroup.append('<span class="error invalid-feedback">Alokasi melebihi qty stok.</span>');
			}
		});
		$('input[name="wh_name[]"]').each(function () {
			let inputValue = $(this).val();
			let inputGroup = $(this).closest('.input-group');

			// Remove existing error messages
			inputGroup.find('.error.invalid-feedback').remove();
			$(this).removeClass('is-invalid')
			if (inputValue === "" || inputValue === null || inputValue == NaN) {
				hasErrors = true;
				inputGroup.append('<span class="error invalid-feedback">This field is required.</span>');
				$(this).addClass('is-invalid')
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
	// ------------------------------------ END FORM VALIDATION

	function Initialize_Submit_Form() {
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
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/UsageNote/store",
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
						return window.location.href = $('meta[name="base_url"]').attr('content') + "TrxWh/UsageNote/index";
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
});
