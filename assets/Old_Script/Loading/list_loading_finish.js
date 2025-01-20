$(document).ready(function () {

	$("#tbl-loading-on-going").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		paging: true,
		dom: 'lBfrtip',
		"oLanguage": {
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
		},
		orderCellsTop: true,
		fixedHeader: {
			header: true,
			headerOffset: 48
		},
		"lengthMenu": [
			[15, 30, 90, 1000],
			[15, 30, 90, 1000]
		],
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "LoadingFinish/DT_Loading",
			dataType: "json",
			type: "POST",
		},
		columns: [{
				data: "SysId",
				name: "SysId",
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: "No_loading",
				name: "No_loading",
			},
			{
				data: "Customer_Name",
				name: "Customer_Name",
			},
			{
				data: "Nama",
				name: "Nama",
			},
			{
				data: "Qty_Loading",
				name: "Qty_Loading",
			},
			{
				data: "Created_by",
				name: "Created_by",
			},
			{
				data: "STATUS",
				name: "STATUS",
				render: function (data, type, row, meta) {
					return `<button class="btn btn-sm bg-gradient-warning blink_me">SELESAI</button>`;
				}
			},
			{
				data: "SysId",
				name: "handle",
				render: function (data, type, row, meta) {
					return `<a class="btn btn-sm btn-success btn-continue" data-toggle="tooltip" title="Detail Loading" href="LoadingFinish/PreviewLoading/${row.No_loading}"><i class="fas fa-list"></i></a>`;
				}
			},
		],
		order: [
			[0, 'DESC']
		],
		columnDefs: [{
			className: "align-middle text-center",
			targets: [0, 1, 2, 3, 5, 6, 7],
		}],
		// autoWidth: false,
		responsive: true,
		preDrawCallback: function () {
			$("#Tbl-FinishGood tbody td").addClass("blurry");
		},
		language: {
			processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
		},
		drawCallback: function () {
			$("#Tbl-FinishGood tbody td").addClass("blurry");
			setTimeout(function () {
				$("#Tbl-FinishGood tbody td").removeClass("blurry");
			});
			$('[data-toggle="tooltip"]').tooltip();
		},
		"buttons": ["copy",
			{
				extend: 'csvHtml5',
				title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
				className: "btn btn-info",
			}, {
				extend: 'excelHtml5',
				title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
				className: "btn btn-success",
			}, {
				extend: 'pdfHtml5',
				title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
				className: "btn btn-danger",
				orientation: "landscape"
			}, "print"
		],
	}).buttons().container().appendTo('#Tbl-FinishGood .col-md-6:eq(0)');

});
