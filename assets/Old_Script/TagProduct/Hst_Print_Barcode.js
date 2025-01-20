$(document).ready(function () {
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		width: 300,
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.addEventListener('mouseenter', Swal.stopTimer)
			toast.addEventListener('mouseleave', Swal.resumeTimer)
		}
	})

	$('#from').datetimepicker({
		format: 'YYYY-MM-DD',
	});
	$('#to').datetimepicker({
		format: 'YYYY-MM-DD',
	});

	$('#filter-form').validate({
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

	$('#do--filter').click(function (e) {
		e.preventDefault();
		if ($("#filter-form").valid()) {
			// DataTable_Deskripsi()
			DT_hst_bcd_product();
			DT_hst_bcd_group();
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	function DT_hst_bcd_product() {
		$("#DataTable").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			paging: true,
			dom: 'lBfrtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "HistoryPrintBarcode/DataTable_hst_bcd_product",
				dataType: "json",
				type: "post",
				data: {
					"from": $('#from').val(),
					"to": $('#to').val()
				}
			},
			columns: [{
					data: "SysId",
					name: "SysId",
					orderable: false,
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				}, {
					data: "Barcode_Number",
					name: "Barcode_Number"
				}, {
					data: "Customer_Name",
					name: "Customer_Name"
				},
				{
					data: "Product_Code",
					name: "Product_Code"
				},
				{
					data: "Date_Prd",
					name: "Date_Prd"
				},
				{
					data: "Checker_Rakit",
					name: "Checker_Rakit"
				},
				{
					data: "Leader_Rakit",
					name: "Leader_Rakit"
				},
				{
					data: "Created_at",
					name: "Created_at"
				},
				{
					data: "IS_WASTING",
					name: "IS_WASTING",
					searching: false,
					render: function (data, type, row, meta) {
						if (data == '0') {
							return `<span class="badge badge-success" data-toggle="tooltip" title="QC Check : OK">OK</span>`;
						} else {
							return `<span class="badge badge-danger" data-toggle="tooltip" title="Barcode Terbuang/Tidak masuk Stok">WASTING</span>`;
						}
					}
				},
				{
					data: null,
					name: "Handle",
					orderable: false,
					render: function (data, type, row, meta) {
						if (row.IS_WASTING == '0') {
							return `<button class="btn btn-sm btn-info btn-print" data-pk="${row.SysId}" data-toggle="tooltip" title="Print Ulang Barcode Product"><i class="fas fa-print"></i> Print</button>`
						} else {
							return `<button class="btn btn-sm btn-secondary" data-pk="${row.SysId}" data-toggle="tooltip" title="Barcode dinyatakan tidak terpakai"><i class="far fa-trash-alt"></i> Print</button>`
						}
					}
				}
			],
			order: [
				[1, "desc"]
			],
			columnDefs: [{
					className: "text-center",
					targets: [0, 1, 4, 7, 8, 9],
				},
				{
					className: "text-left",
					targets: []
				}
			],
			autoWidth: false,
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
			"buttons": ["copy",
				{
					extend: 'csvHtml5',
					title: $('title').text() + '~' + $('#from').val() + ' sd ' + $('#to').val(),
					className: "btn btn-info",
				}, {
					extend: 'excelHtml5',
					title: $('title').text() + '~' + $('#from').val() + ' sd ' + $('#to').val(),
					className: "btn btn-success",
				}, {
					extend: 'pdfHtml5',
					title: $('title').text() + '~' + $('#from').val() + ' sd ' + $('#to').val(),
					className: "btn btn-danger",
					orientation: "landscape"
				}, "print"
			],
		}).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');
	}

	function DT_hst_bcd_group() {
		$("#DataTable_group").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			paging: true,
			dom: 'lBfrtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "HistoryPrintBarcode/DataTable_hst_bcd_group",
				dataType: "json",
				type: "post",
				data: {
					"from": $('#from').val(),
					"to": $('#to').val()
				}
			},
			columns: [{
					data: "FlagGrouping",
					name: "FlagGrouping",
					orderable: false,
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				}, {
					data: "min_barcode",
					name: "min_barcode"
				}, {
					data: "max_barcode",
					name: "max_barcode"
				},
				{
					data: "jumlah",
					name: "jumlah"
				},
				{
					data: "Customer_Name",
					name: "Customer_Name"
				},
				{
					data: "Product_Code",
					name: "Product_Code"
				},
				{
					data: "Date_Prd",
					name: "Date_Prd"
				},
				{
					data: "Checker_Rakit",
					name: "Checker_Rakit"
				},
				{
					data: "Leader_Rakit",
					name: "Leader_Rakit"
				},
				{
					data: "Created_at",
					name: "Created_at"
				},
				{
					data: null,
					name: "Handle",
					orderable: false,
					render: function (data, type, row, meta) {
						return `<button class="btn btn-sm btn-info btn-print-group" data-pk="${row.FlagGrouping}" data-toggle="tooltip" title="Print Ulang Barcode Product"><i class="fas fa-print"></i> Print</button>`
					}
				}
			],
			order: [
				[1, "desc"]
			],
			columnDefs: [{
					className: "text-center",
					targets: [0, 1, 4, 7, 8, 9, 10],
				},
				{
					className: "text-left",
					targets: []
				}
			],
			autoWidth: false,
			preDrawCallback: function () {
				$("#DataTable_group tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#DataTable_group tbody td").addClass("blurry");
				setTimeout(function () {
					$("#DataTable_group tbody td").removeClass("blurry");
				});
				$('[data-toggle="tooltip"]').tooltip();
			},
			"buttons": ["copy",
				{
					extend: 'csvHtml5',
					title: $('title').text() + '~' + $('#from').val() + ' sd ' + $('#to').val(),
					className: "btn btn-info",
				}, {
					extend: 'excelHtml5',
					title: $('title').text() + '~' + $('#from').val() + ' sd ' + $('#to').val(),
					className: "btn btn-success",
				}, {
					extend: 'pdfHtml5',
					title: $('title').text() + '~' + $('#from').val() + ' sd ' + $('#to').val(),
					className: "btn btn-danger",
					orientation: "landscape"
				}, "print"
			],
		}).buttons().container().appendTo('#DataTable_group .col-md-6:eq(0)');
	}

	$(document).on('click', '.btn-print', function () {
		var sysid = $(this).attr('data-pk');
		Swal.fire({
			title: 'Print Ulang ?',
			text: "Anda akan melakukan Print Ulang ?",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Print!',
			cancelButtonText: 'Batal!'
		}).then((result) => {
			if (result.isConfirmed) {
				window.open($('meta[name="base_url"]').attr('content') + "PrintBarcodeProduct/Print_Ulang/" + sysid, 'PrintUlangBcdImp', 'width=800,height=600');
			}
		})
	})

	$(document).on('click', '.btn-print-group', function () {
		var flagGroup = $(this).attr('data-pk');
		Swal.fire({
			title: 'Print Ulang ?',
			text: "Anda akan melakukan Print Ulang secara massive ?",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Print!',
			cancelButtonText: 'Batal!'
		}).then((result) => {
			if (result.isConfirmed) {
				window.open($('meta[name="base_url"]').attr('content') + "PrintBarcodeProduct/print/" + flagGroup, 'PrintUlangBcdImpGroup', 'width=800,height=600');
			}
		})
	})


	// end script
});
