$(document).ready(function () {
	let tableAddress = $("#table-address").DataTable({
		destroy: true,
		columns: [{
				data: "SysId",
				visible: false,
			},
			{
				data: null,
				orderable: false,
				searchable: false,
				defaultContent: "<input type='radio' name='selectAddress'>",
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
		responsive: true,
		autoWidth: false,
	});

	$("#btn-select-address").click(function (e) {
		let selectedRow = $('input[name="selectAddress"]:checked').closest("tr");
		let rowData = tableAddress.row(selectedRow).data();
		let selectedAddress = rowData.Address;
		let selectedSysId = rowData.SysId;

		$("#Account_Address").val(selectedAddress).data("sysid", selectedSysId);
		$("#Account_Address_ID").val(selectedSysId);
		// Tutup modal
		$("#addressModal").modal("hide");
	});

	$("#btn-list-address").click(function () {
		let Account_Code = $("#Account_Code").val();
		let currentSysId = $("#Account_Address_ID").val();

		if (Account_Code == "") {
			Swal.fire({
				icon: "warning",
				title: "Ooppss...",
				text: "Silahkan pilih vendor terlebih dahulu!",
				footer: '<a href="javascript:void(0)" class="text-info">Informasi System</a>',
			});
			return;
		}

		$.ajax({
			url: $('meta[name="base_url"]').attr("content") + "TrxWh/SubkonKiln/DT_listofaccount_address",
			type: "POST",
			data: {
				Account_Code: Account_Code,
			},
			dataType: "json",
			success: function (response) {
				if (response.code === 200) {
					let data = response.data;
					let noAlamat = false;
					tableAddress.clear().rows.add(data).draw();

					$("#table-address tbody tr").each(function () {
						let rowData = tableAddress.row(this).data();
						if (typeof rowData == "undefined") {
							Swal.fire({
								icon: "info",
								title: "Informasi",
								text: "Customer belum memiliki alamat",
								footer: '<a href="javascript:void(0)" class="text-info">Informasi System</a>',
							});
							noAlamat = true;
							return;
						}
						let sysid = rowData.SysId;
						if (sysid === currentSysId) {
							$(this).find('input[type="radio"]').prop("checked", true);
							$(this).addClass("table-primary");
						}
					});

					if (!noAlamat) {
						$("#addressModal").modal("show");
					}
				} else {
					Swal.fire({
						icon: "error",
						title: "Gagal",
						text: response.msg,
						footer: '<a href="javascript:void(0)" class="text-info">Informasi System</a>',
					});
				}
			},
			error: function (xhr, status, error) {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Terjadi kesalahan dalam mengambil data. Silakan coba lagi.",
					footer: '<a href="javascript:void(0)" class="text-info">Informasi System</a>',
				});
			},
		});
	});

	var selectedRows = {};
	$(document).on('click', '.search-data', function () {
		let Start_time = $('#Estimasi_Mulai_Kiln').val();
		let End_time = $('#Estimasi_Selesai_Kln').val();

		if (Start_time == '' || End_time == '') {
			return Swal.fire({
				icon: "warning",
				title: "Perhatian!",
				text: "Harap pilih waktu estimasi mulai dan selesai kiln !",
				footer: '<a href="javascript:void(0)" class="text-info">Informasi System</a>',
			});
		}

		Swal.fire({
			title: 'Loading....',
			html: '<div class="spinner-border text-primary"></div>',
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false
		});
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
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/SubkonKiln/DT_list_lot?sysid=" + SysId_child_size,
				dataType: "json",
				type: "POST",
				data: {
					startDate: $('#from').val(),
					endDate: $('#to').val()
				}
			},
			columns: [{
					data: 'sysid',
					render: function (data, type, row) {
						var isChecked = selectedRows[data] ? 'checked' : '';
						var btnCheck = '<input type="checkbox" id="basic_checkbox_' + data + '" class="filled-in checkbox_checked chk_select" value="' + data + '" ' + isChecked + '/> <label for="basic_checkbox_' + data + '">&nbsp;</label>';
						return btnCheck;
					},
				}, {
					data: "no_lot",
					name: "no_lot",
				},
				{
					data: "supplier",
					name: "supplier",
				},
				{
					data: "kode",
					name: "kode",
					render: function (data, type, row, meta) {
						return row.kode + ' (' + row.deskripsi + ')';
					}
				},
				{
					data: "qty",
					name: "qty",
				},
				{
					data: "kubikasi",
					name: "kubikasi",
					orderable: false,
					render: function (data) {
						return roundToFourDecimals(data);
					}
				},
				{
					data: "grader",
					name: "grader",
				},
				{
					data: "tgl_kirim",
					name: "tgl_kirim",
				},
				{
					data: "Warehouse_Name",
					name: "Warehouse_Name",
				},
				{
					data: "into_oven",
					name: "into_oven",
					render: function (data, type, row, meta) {
						if (row.into_oven == '0') {
							status_lot = `<span class="badge badge-warning">${row.status_kayu}</span>`
						} else if (row.into_oven == '1') {
							status_lot = `<span class="badge badge-danger blink_me">${row.status_kayu}</span>`
						} else if (row.into_oven == '2') {
							status_lot = `<span class="badge badge-success">${row.status_kayu}</span>`
						} else if (row.into_oven == '3') {
							status_lot = `<span class="badge badge-primary">${row.status_kayu}</span>`
						} else {
							status_lot = `<span class="badge badge-info">${row.status_kayu}</span>`
						}
						return status_lot;
					}
				},
			],
			order: [
				[7, "desc"]
			],
			columnDefs: [{
				className: "align-middle text-center",
				targets: [0, 1, 2, 5, 6, 8, 9],
			}],
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

		let Start_time = $('#Estimasi_Mulai_Kiln').val();
		let End_time = $('#Estimasi_Selesai_Kln').val();

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
			// SELECT lpb_hdr, sysid, no_lot, status_lpb, Warehouse_Name, kode, deskripsi, harga_per_pcs, supplier, grader, tgl_kirim, selesai_at, tgl_finish_sortir, into_oven, qty, kubikasi, status_kayu
			$.each(selectedRowsData, function (index, rowData) {
				var $newRow = $('<tr>');
				$newRow.append(`<td class="text-center align-middle"><input type="hidden" required name="ID[]" value="${rowData.sysid}"><p class="mt-1">${no}</p></td>`);
				$newRow.append('<td class="text-center align-middle"><input type="hidden" required name="Lot[]" value="' + rowData.no_lot + '">' + rowData.no_lot + '</td>');
				$newRow.append('<td class="text-center align-middle">' + rowData.kode + '</td>');
				$newRow.append('<td class="text-center align-middle">' + rowData.deskripsi + '</td>');
				$newRow.append('<td class="text-center align-middle">' + parseFloat(rowData.qty) + '</td>');
				$newRow.append('<td class="text-center align-middle">' + parseFloat(rowData.kubikasi) + '</td>');
				$newRow.append('<td class="text-center align-middle"><input name="Start_time[]" value="' + Start_time + '" type="text" placeholder="waktu...." style="height:40px;" class="form-control form-control-sm flatpickr-time text-center" id="Start_time_' + no + '"></td>');
				$newRow.append('<td class="text-center align-middle"><input name="End_Time[]" value="' + End_time + '" type="text" placeholder="waktu...." style="height:40px;" class="form-control form-control-sm flatpickr-time text-center" id="End_Time_' + no + '"></td>');
				$newRow.append(`<td class="text-center align-middle"><button type="button" class="remove-row btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></td></tr>`);
				$tableItem.append($newRow);
				no++;
			});
			$(".flatpickr-time").flatpickr({
				dateFormat: "Y-m-d H:i",
				enableTime: true,
				time_24hr: true,
				allowInput: true,
			});
			$('#no_data_selected').hide();
			$('#no_data_item').hide('slow');
			$('#modal_list_lot').modal('hide');
		});
	}

	$(document).on('click', '.remove-row', function () {
		$(this).closest('tr').remove();
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

		$('input[name="Start_time[]"]').each(function () {
			let inputValue = $(this).val();
			let inputGroup = $(this).closest('.input-group');

			inputGroup.find('.error.invalid-feedback').remove();
			$(this).removeClass('is-invalid')
			if (inputValue === "" || inputValue === null || inputValue == 0) {
				hasErrors = true;
				$(this).addClass('is-invalid')
				inputGroup.append('<span class="error invalid-feedback">Waktu mulai kiln wajib di isi</span>');
			}
		});
		$('input[name="End_time[]"]').each(function () {
			let inputValue = $(this).val();
			let inputGroup = $(this).closest('.input-group');

			inputGroup.find('.error.invalid-feedback').remove();
			$(this).removeClass('is-invalid')
			if (inputValue === "" || inputValue === null || inputValue == 0) {
				hasErrors = true;
				$(this).addClass('is-invalid')
				inputGroup.append('<span class="error invalid-feedback">Waktu selesai kiln wajib di isi</span>');
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
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/SubkonKiln/update",
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
						return window.location.href = $('meta[name="base_url"]').attr('content') + "TrxWh/SubkonKiln/index";
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
		var rowCount = $("#table_data_selected tbody tr").length;

		if (rowCount > 0) {
			$('#no_data_selected').hide('slow');
		} else {
			$('#no_data_selected').show('slow');
		}
	}

	checkRowCount()
})
