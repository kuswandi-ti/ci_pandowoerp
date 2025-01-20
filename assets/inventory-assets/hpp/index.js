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

	var TableData = $("#DataTable").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
		lengthMenu: [
			[15, 25, 50, 10000],
			[15, 25, 50, 'All']
		],
		select: true,
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "MasterData/Item/DT_listofitem_fg",
			dataType: "json",
			type: "POST",
		},
		columns: [
			// {
			//     data: "SysId",
			//     name: "SysId",
			//     render: function (data, type, row, meta) {
			//         return meta.row + meta.settings._iDisplayStart + 1;
			//     }
			// },
			{
				data: "Item_Code",
				name: "Item_Code",
			},
			{
				data: "Item_Name",
				name: "Item_Name",
			},
			{
				data: "Spesific_Price_Fg",
				name: "Spesific_Price_Fg",
				render: function (data, type, row, meta) {
					return formatIdr(data)
				}
			},
			{
				data: "Default_Currency_Id",
				name: "Default_Currency_Id",
			},
			{
				data: "Item_Color",
				name: "Item_Color",
			},
			{
				data: "Brand",
				name: "Brand",
			},
			{
				data: "Model",
				name: "Model",
			},
			{
				data: "Item_Category",
				name: "Item_Category",
			},
			{
				data: "Group_Name",
				name: "Group_Name",
			},
			{
				data: "Barcode_Pattern",
				name: "Barcode_Pattern",
				visible: false,
			},
			{
				data: "Uom",
				name: "Uom",
			},
			{
				data: "Is_Expenses",
				name: "Is_Expenses",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<i class="fas fa-check text-success"></i>`
					} else {
						return `<i class="fas fa-times text-danger"></i>`
					}
				}
			},
			{
				data: "Is_Active",
				name: "Is_Active",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<i class="fas fa-check text-success"></i> Active`
					} else {
						return `<i class="fas fa-times text-danger"></i> Not-Active`
					}
				}
			},
		],
		order: [
			[0, "desc"]
		],
		columnDefs: [{
			className: "text-center",
			targets: [2, 10, 11, 12],
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
			if (data.Is_Active == 0) {
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
			text: `<i class="fas fa-edit fs-3"></i> Ubah Hpp`,
			className: "bg-warning",
			action: function (e, dt, node, config) {
				var RowData = dt.rows({
					selected: true
				}).data();
				if (RowData.length == 0) {
					Swal.fire({
						icon: 'warning',
						title: 'Ooppss...',
						text: 'Silahkan pilih data yang akaan anda ubah !',
						footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
					});
				} else {
					Init_Modal_Change_Detail(RowData[0], 'Edit')
				}
			}
		}, {
			text: `<i class="fas fa-history fs-3"></i> History Perubahan`,
			className: "btn bg-gradient-success",
			action: function (e, dt, node, config) {
				var RowData = dt.rows({
					selected: true
				}).data();
				if (RowData.length == 0) {
					Swal.fire({
						icon: 'warning',
						title: 'Ooppss...',
						text: 'Silahkan pilih data untuk Memanage Kode Item Customer !',
						footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
					});
				} else {
					Init_Modal_Hst(RowData[0])
				}
			}
		}, {
			text: `<i class="fas fa-search fs-3"></i> Detail Hpp`,
			className: "bg-primary",
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
					Init_Modal_Change_Detail(RowData[0], 'Preview')
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

	// ------------------------------------ START FORM VALIDATION

	function Init_Modal_Change_Detail(Datas, action) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Inventory/Hpp/GetDataLastPriceHpp",
			data: {
				Item_Code: Datas.Item_Code,
			},
			success: function (response) {
				$('#Hpp_Date').val(response.data.Hpp_Date)
				console.log(response.data.Note)
				$('#Note').text(response.data.Note)
			},
			error: function (xhr, status, error) {
				alert('Gagal mendapatkan data harga terakhir !')
			}
		});

		$('#title-modal-edit').html('Harga Pokok Produksi : ' + Datas.Item_Name)

		$('#SysId_Item').val(Datas.SysId)
		$('#Item_Code').val(Datas.Item_Code)
		$('#Hpp').val(parseFloat(Datas.Spesific_Price_Fg))


		if (action == 'Preview') {
			$('#btn-submit').hide();
		} else {
			$('#btn-submit').show();
		}

		$('#modal-form-edit').modal('show');
	}


	// ------------------------------- Form Validation

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
		$('#modal-form-edit').modal('hide');
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Inventory/Hpp/store",
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
					})
					$("#DataTable").DataTable().ajax.reload(null, false);
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

	function Init_DT_Hst(item_code) {
		var Tbl_Hst_Trx = $("#tbl_history_stok").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			paging: true,
			dom: 'l<"row"<"col-6"><"col-6"B>>rtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			orderCellsTop: true,
			fixedHeader: {
				header: true,
				headerOffset: 48
			},
			"lengthMenu": [
				[15, 50, 10000000],
				[15, 50, 'ALL']
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "Inventory/Hpp/DT_Hst_Hpp",
				dataType: "json",
				type: "POST",
				data: {
					Item_Code: item_code,
				}
			},
			columns: [{
				data: "SysId",
				name: "SysId",
				visible: true,
				orderable: false,
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			}, {
				data: "Hpp_Date",
				name: "Hpp_Date",
			}, {
				data: "Hpp",
				name: "Hpp",
				render: function (data) {
					return formatIdr(data)
				}
			},
			{
				data: "Note",
				name: "Note",
				orderable: false,
			},
			{
				data: "Created_at",
				name: "Created_at",
			},
			{
				data: "nama",
				name: "nama",
				orderable: false,
			}
			],
			order: [
				[3, 'DESC']
			],
			columnDefs: [{
				className: "align-middle text-center",
				targets: [0, 1,],
			}],
			// autoWidth: false,
			responsive: true,
			preDrawCallback: function () {
				$("#tbl_history_stok tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#tbl_history_stok tbody td").addClass("blurry");
				setTimeout(function () {
					$("#tbl_history_stok tbody td").removeClass("blurry");
				});
				$('[data-toggle="tooltip"]').tooltip();
			},
			"buttons": [{
				extend: 'csvHtml5',
				title: $('#modal-title-hst').html(),
				className: "btn btn-info",
			}, {
				extend: 'excelHtml5',
				title: $('#modal-title-hst').html(),
				className: "btn btn-success",
			}, {
				extend: 'pdfHtml5',
				title: $('#modal-title-hst').html(),
				className: "btn btn-danger",
				orientation: "landscape"
			}],
		}).buttons().container().appendTo('#tbl_history_stok .col-md-6:eq(0)');
	}



	function Init_Modal_Hst(data) {
		$('#modal-title-hst').html(`History Perubahan Hpp : ${data.Item_Name} (${data.Item_Code})`)
		Init_DT_Hst(data.Item_Code)


		$('#modal-history-transaksi').modal('show');
	}



});
