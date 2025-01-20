$(document).ready(function () {
	$(".readonly").keydown(function (event) {
		return false;
	});

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
			DataTable_Detail()
			// DataTable_Summarize()
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	function DataTable_Detail() {
		var DataTable_Detail = $("#DataTable_Detail").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			"aLengthMenu": [
				[1000000000],
				['ALL']
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/Adjustment/DT_report_adj_detail",
				dataType: "json",
				type: "POST",
				data: {
					"from": $('#from').val(),
					"to": $('#to').val(),
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
				},
				{
					data: "DocNo",
					name: "DocNo",
				},
				{
					data: "DocDate",
					name: "DocDate",
				},
				{
					data: "Item_Code",
					name: "Item_Code",
				},
				{
					data: "Item_Name",
					name: "Item_Name",
				},
				{
					data: "Uom",
					name: "Uom",
				},
				{
					data: "Qty",
					name: "Qty",
					render: function (data) {
						return roundToFourDecimals(data)
					}
				},
				{
					data: "Aritmatics",
					name: "Aritmatics",
					render: function (data) {
						if (data == '+') {
							return 'Adjustment (+)';
						} else {
							return 'Adjustment (-)';
						}
					}
				},
				{
					data: "Item_Price",
					name: "Item_Price",
					render: function (data) {
						return formatIdr(data)
					}
				},
				{
					data: "Base_Amount",
					name: "Base_Amount",
					render: function (data) {
						return formatIdr(data)
					}
				},
				{
					data: "Warehouse_Name",
					name: "Warehouse_Name",
				},
				{
					data: "nama_cost_center",
					name: "nama_cost_center",
				}
			],
			order: [
				[2, "desc"],
				[4, "asc"]
			],
			columnDefs: [{
					className: "text-center align-middle",
					targets: [0, 1, 2, 3, 4, 5, 7, 10, 11],
				},
				{
					className: "text-left",
					targets: []
				}
			],
			autoWidth: false,
			preDrawCallback: function () {
				$("#DataTable_Detail tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#DataTable_Detail tbody td").addClass("blurry");
				setTimeout(function () {
					$("#DataTable_Detail tbody td").removeClass("blurry");
				});
				$('[data-toggle="tooltip"]').tooltip();
			},
			"buttons": [{
				extend: 'csvHtml5',
				title: $('#card-title').text() + '--' + $('#from').val() + ' sd ' + $('#to').val(),
				className: "btn btn-info",
			}, {
				extend: 'excelHtml5',
				title: $('#card-title').text() + '--' + $('#from').val() + ' sd ' + $('#to').val(),
				className: "btn btn-success",
			}, {
				extend: 'pdfHtml5',
				title: $('#card-title').text() + '--' + $('#from').val() + ' sd ' + $('#to').val(),
				className: "btn btn-danger",
			}],
		}).buttons().container().appendTo('#DataTable_Detail_wrapper .col-md-6:eq(0)');
	}

	DataTable_Detail()
})
