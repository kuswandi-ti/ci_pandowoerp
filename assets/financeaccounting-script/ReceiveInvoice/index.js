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
				$('.tambah_detail').show("slow");
				$('#btn-submit').show('slow');
				$('input[name="doc_number"]').val('Doc Number Akan Otomatis di isikan Oleh system.');
				$('#no_data_item').show('slow');
				$(MainForm)[0].reset();
				flatpickr();
				break;

			case 'EDIT':
				reset_input();
				$('.list-data').hide('slow');
				$('.add-data').show('slow');
				$('input[name="state"]').val('EDIT');
				$('#title-add-hdr').html('Edit');
				$('.tambah_detail').show("slow");
				$('#btn-submit').show('slow');
				$('#no_data_item').hide('slow');
				break;

			case 'DETAIL':
				reset_input();
				$('.list-data').hide('slow');
				$('.add-data').show('slow');
				$('input[name="state"]').val('DETAIL');
				$('#title-add-hdr').html('Detail');
				$('.tambah_detail').hide("slow");
				$('#no_data_item').hide('slow');
				$('#btn-submit').hide('slow');
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
				url: $('meta[name="base_url"]').attr('content') + "FinanceAccounting/ReceiveInvoice/DT_listdata",
				dataType: "json",
				type: "POST",
			},
			columns: [{
					data: "SysId", // 0
					name: "SysId",
				},
				{
					data: "doc_number", // 1
					name: "doc_number",
				},
				{
					data: "doc_date", // 2
					name: "doc_date",
					render: function (data, type, row, meta) {
						return moment(data).format("DD MMMM YYYY");
					}
				},
				{
					data: "customer", // 3
					name: "customer",
				},
				{
					data: "total", // 4
					name: "total",
					render: function (data, type, row, meta) {
						return formatIdrAccounting(data);
					}
				},
				{
					data: "keterangan", // 5
					name: "keterangan",
				},
				{
					data: "Is_Active", // 6
					name: "Is_Active",
					render: function (data, type, row, meta) {
						if (data == 1) {
							return `<div class='d-flex justify-content-center'><span class="badge bg-success">Active</span></div>`;
						} else {
							return `<div class='d-flex justify-content-center'><span class="badge bg-danger">Cancel</span></div>`;
						}
					}
				},
			],
			order: [
				[0, "desc"]
			],
			columnDefs: [{
					className: "text-center",
					targets: [0, 1, 2, 6],
				},
				{
					className: "text-left",
					targets: []
				},
				{
					className: "text-right",
					targets: [4]
				},
				{
					visible: false,
					targets: [0]
				},
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
				text: `<i class="fas fa-plus fs-3"> Add RI</i>`,
				className: "bg-primary",
				action: function (e, dt, node, config) {
					form_state('ADD');
				}
			}, {
				text: `<i class="fas fa-edit fs-3"> Edit</i>`,
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
					} else if (RowData[0].Is_Active == 0) {
						Swal.fire({
							icon: 'warning',
							title: 'Ooppss...',
							text: 'Data tidak bisa di ubah karena status CANCEL !',
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
						});
					} else {
						Init_Edit(RowData[0].SysId, 'EDIT')
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
						Init_Edit(RowData[0].SysId, 'DETAIL')
					}
				}
			}, {
				text: `<i class="fas fa-print fs-3"> Print</i>`,
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
					} else if (RowData[0].Is_Active == 0) {
						Swal.fire({
							icon: 'warning',
							title: 'Ooppss...',
							text: 'Report cetak hanya bisa di tampilkan pada data yang telah legitimate (tidak cancel dan sudah approve)!',
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
						});
					} else {
						window.open($('meta[name="base_url"]').attr('content') + `FinanceAccounting/ReceiveInvoice/print/${RowData[0].SysId}`, "_blank");
					}
				}
			}, {
				text: `<i class="fas fa-times fs-3"> Cancel</i>`,
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
					} else if (RowData[0].Is_Active == 0) {
						Swal.fire({
							icon: 'warning',
							title: 'Ooppss...',
							text: 'Data tidak bisa di ubah karena status CANCEL !',
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
						});
					} else {
						Fn_Toggle_Status(parseInt(RowData[0].SysId))
					}
				}
			}, {
				text: `Export to :`,
				className: "btn disabled text-dark bg-white",
			}, {
				text: `<i class="far fa-file-excel"> Excel</i>`,
				extend: 'excelHtml5',
				title: $('title').text() + ' ~ ' + moment().format("YYYY-MM-DD"),
				className: "btn btn-success",
			}, {
				text: `<i class="far fa-file-pdf"> PDF</i>`,
				extend: 'pdfHtml5',
				title: $('title').text() + ' ~ ' + moment().format("YYYY-MM-DD"),
				className: "btn btn-danger",
				orientation: "landscape"
			}],
		}).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');
	}

	function Init_Edit(SysId, State) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "FinanceAccounting/ReceiveInvoice/edit",
			data: {
				sysid: SysId,
				state: State
			},
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					form_state(State);

					$('input[name="sysid_hdr"]').val(response.data_hdr.SysId);
					$('input[name="doc_number"]').val(response.data_hdr.doc_number);
					$('input[name="doc_date"]').val(moment(response.data_hdr.doc_date).format("DD MMMM YYYY"));
					$('textarea[name="keterangan"]').val(response.data_hdr.keterangan);
					$('input[name="total"]').val(formatIdrAccounting(response.data_hdr.total));
					$("#is_lunas").prop("checked", response.data_hdr.Is_Lunas);
					$('textarea[name="note_lunas"]').val(response.data_hdr.note_lunas);

					// DETAIL //
					var $tableItem = $('#table_item tbody');
					$tableItem.empty();

					var no = 1;
					$.each(response.data_dtl, function (index, rowData) {
						var $newRow = $('<tr>');

						// Buat kolom dengan input sesuai dengan data yang ada
						$newRow.append('<td><input type="hidden" name="id_invoice[]" value="' + rowData.id_invoice + '"><p class="mt-1">' + no + '</p></td>');
						$newRow.append('<td><input class="form-control form-control-sm" name="no_invoice[]" type="text" value="' + rowData.no_invoice + '" readonly></td>');
						$newRow.append('<td><input class="form-control form-control-sm number" name="item_amount[]" type="text" value="' + formatIdrAccounting(rowData.item_amount) + '" readonly></td>');
						$newRow.append('<td><input class="form-control form-control-sm number" type="text" name="amount_receive[]" id="amount_receive[]" value="' + formatIdrAccounting(rowData.amount_receive) + '"></td>');
						$newRow.append('<td><input class="form-control form-control-sm" readonly type="text" name="type_doc[]" id="type_doc[]" value="' + rowData.type_doc + '"></td>');
						$newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');

						// Masukkan baris baru ke dalam tabel tujuan
						$tableItem.append($newRow);

						no++
					});

					$("#id_customer").val(response.data_hdr.id_customer).trigger('change');
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

	function Fn_Toggle_Status(SysId) {
		Swal.fire({
			title: 'System message!',
			text: `Apakah anda yakin untuk merubah status data ini ?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, ubah!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: $('meta[name="base_url"]').attr('content') + "FinanceAccounting/ReceiveInvoice/Toggle_Status",
					type: "post",
					dataType: "json",
					data: {
						sysid: SysId,
						table: 'ttrx_hdr_receive_invoice'
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
	var selectedRows = {};

	$(document).on('click', '.tambah_detail', function () {
		selectedRows = {};
		reloadDataItem();
	});

	function reloadDataItem() {
		var columnDefs = [{
			data: 'id_invoice',
			render: function (data, type, row) {
				var isChecked = selectedRows[data] ? 'checked' : '';
				var btnCheck = '<input type="checkbox" id="basic_checkbox_' + data + '" class="filled-in checkbox_checked chk_select" value="' + data + '" ' + isChecked + '/> <label for="basic_checkbox_' + data + '">&nbsp;</label>';
				return btnCheck;
			},
		}, {
			data: "no_invoice",
			name: "no_invoice",
		}, {
			data: "os_receive",
			name: "os_receive",
			render: function (data, type, row, meta) {
				return formatIdrAccounting(data);
			}
		}, {
			data: "type_doc",
			name: "type_doc",
		}, ];

		var id_invoice = [];
		var id_invoice = $('input[name="id_invoice[]"]').map(function () {
			return $(this).val();
		}).get();

		var id_customer = $('#id_customer').val();
		var flag_lunas = $("#is_lunas").prop("checked");

		$("#DataTable_Modal_ListItem").DataTable({
			destroy: true,
			processing: false,
			serverSide: true,
			lengthMenu: [
				[10, 25, 50, 10000],
				[10, 25, 50, 'All']
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "FinanceAccounting/ReceiveInvoice/DT_modallistofitem?id_invoice=" + id_invoice + "&id_customer=" + id_customer + "&flag_lunas=" + flag_lunas,
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
			$.each(selectedRowsData, function (index, rowData) {
				var $newRow = $('<tr>');

				// Buat kolom dengan input sesuai dengan data yang ada
				$newRow.append('<td><input type="hidden" name="id_invoice[]" value="' + rowData.id_invoice + '"><p class="mt-1">' + no + '</p></td>');
				$newRow.append('<td><input class="form-control form-control-sm" name="no_invoice[]" type="text" value="' + rowData.no_invoice + '" readonly></td>');
				$newRow.append('<td><input class="form-control form-control-sm number" name="item_amount[]" type="text" value="' + formatIdrAccounting(rowData.os_receive) + '" readonly></td>');
				$newRow.append('<td><input class="form-control form-control-sm number" type="text" name="amount_receive[]" id="amount_receive[]" value="' + formatIdrAccounting(rowData.os_receive) + '"></td>');
				$newRow.append('<td><input class="form-control form-control-sm" name="type_doc[]" type="text" value="' + rowData.type_doc + '" readonly></td>');
				$newRow.append('<td><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');

				// Masukkan baris baru ke dalam tabel tujuan
				$tableItem.append($newRow);

				no++;

				set_total();
			});

			$('#no_data_item').hide('slow');
			$('#modal_list_item').modal('hide');
		});
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

		set_total();
	});

	$('#no_data_item').show('slow');
	$(document).on('click', '.remove_item_dtl', function () {
		var trIndex = $(this).closest("tr").index();

		$(this).closest("tr").remove();
		if (trIndex == 0) {
			$('#no_data_item').show('slow');
		}

		set_total();
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
		BtnSubmit.prop("disabled", true);
		var formDataa = new FormData(MainForm[0]);
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "FinanceAccounting/ReceiveInvoice/store",
			data: formDataa,
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: response.msg,
						showCancelButton: false,
					}).then((result) => {
						form_state('LOAD');
						location.reload(true);
					})
					//$(MainForm)[0].reset();
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

	$(document).on('keypress keyup blur', '.number', function (event) {
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

	function formatAritmatika(str) {
		return str ? str.replace(/,/g, '') : '0';
	}

	function set_total() {
		var total = 0;
		$('input[name="amount_receive[]"]').map(function () {
			var amount = parseFloat(formatAritmatika($(this).val()));
			total += amount;
		}).get();
		$('input[name="total"]').val(formatIdrAccounting(total));
	}

	$('body').on('keyup blur', $('input[name="amount_receive[]"]'), function () {
		set_total();
	});
});
