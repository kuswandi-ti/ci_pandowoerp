$(document).ready(function () {

	$('#do-filter').click(function () {
		Init_DT()
	})

	function Init_DT() {
		var Tbl_Stok = $("#Tbl_Stok").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			paging: true,
			dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			orderCellsTop: true,
			fixedHeader: {
				header: true,
				headerOffset: 48
			},
			"lengthMenu": [
				[15, 50, 1000000000],
				[15, 50, 'ALL']
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "Inventory/Stok/DT_Stok",
				dataType: "json",
				type: "POST",
				data: {
					Warehouse: $('#Warehouse').val()
				}
			},
			columns: [{
				data: "Warehouse_ID",
				name: "Warehouse_ID",
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: "Warehouse_Code",
				name: "Warehouse_Code",
			},
			{
				data: "Warehouse_Name",
				name: "Warehouse_Name",
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
				data: "Item_Qty",
				name: "Item_Qty",
				render: function (data, type, row, meta) {
					return parseFloat(row.Item_Qty);
				}
			},
			{
				data: "Uom",
				name: "Uom",
			},
			{
				data: "Warehouse_ID",
				name: "handle",
				render: function (data, type, row, meta) {
					return `<button class="btn btn-xs bg-gradient-warning btn-ttrx" data-toggle="tooltip" title="History Transaksi ${row.Item_Code} di ${row.Warehouse_Name}"><i class="fas fa-random"></i> List Transaksi</button>`
				}
			},
			],
			order: [
				[2, 'ASC']
			],
			columnDefs: [{
				className: "align-middle text-center",
				targets: [0, 1, 2, 7],
			}],
			// autoWidth: false,
			responsive: true,
			preDrawCallback: function () {
				$("#Tbl_Stok tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#Tbl_Stok tbody td").addClass("blurry");
				setTimeout(function () {
					$("#Tbl_Stok tbody td").removeClass("blurry");
				});
				$('[data-toggle="tooltip"]').tooltip();
			},
			"buttons": [{
				extend: 'csvHtml5',
				title: $('title').text() + ` \ ` + moment().format("YYYY-MM-DD"),
				className: "btn btn-info",
			}, {
				extend: 'excelHtml5',
				title: $('title').text() + ` \ ` + moment().format("YYYY-MM-DD"),
				className: "btn btn-success",
			}, {
				extend: 'pdfHtml5',
				title: $('title').text() + ` \ ` + moment().format("YYYY-MM-DD"),
				className: "btn btn-danger",
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5]
				}
				// orientation: "landscape"
			}],
		}).buttons().container().appendTo('#Tb-Stok .col-md-6:eq(0)');
	}

	function Init_DT_Trx() {
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
				url: $('meta[name="base_url"]').attr('content') + "Inventory/Stok/DT_Hst_Trx",
				dataType: "json",
				type: "POST",
				data: {
					Warehouse_ID: $('#Warehouse_ID').val(),
					Item_Code: $('#Item_Code').val(),
					from: $('#from').val(),
					to: $('#to').val(),
				}
			},
			columns: [{
				data: "DocNo",
				name: "DocNo",
				orderable: false,
			}, {
				data: "Trans_Type",
				name: "Trans_Type",
				orderable: false,
			},
			{
				data: "DocDate",
				name: "DocDate",
				orderable: false,
			},
			{
				data: "Begin_Balance",
				name: "Begin_Balance",
				orderable: false,
				render: function (data) {
					return parseFloat(data)
				}
			},
			{
				data: "Qty_Adjust_Plus",
				name: "Qty_Adjust_Plus",
				orderable: false,
				render: function (data) {
					return parseFloat(data)
				}
			},
			{
				data: "Qty_Adjust_Min",
				name: "Qty_Adjust_Min",
				orderable: false,
				render: function (data) {
					return parseFloat(data)
				}
			}, {
				data: "End_Balance",
				name: "End_Balance",
				orderable: false,
				render: function (data) {
					return parseFloat(data)
				}
			}, {
				data: "Created_Time",
				name: "Created_Time",
				orderable: false,
			}
			],
			order: [
				[7, 'DESC']
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
				title: $('.modal-title').html() + ` -- ` + $('#from').val() + 'sd' + $('#to').val(),
				className: "btn btn-info",
			}, {
				extend: 'excelHtml5',
				title: $('.modal-title').html() + ` -- ` + $('#from').val() + 'sd' + $('#to').val(),
				className: "btn btn-success",
			}, {
				extend: 'pdfHtml5',
				title: $('.modal-title').html() + ` -- ` + $('#from').val() + 'sd' + $('#to').val(),
				className: "btn btn-danger",
				orientation: "landscape"
			}],
		}).buttons().container().appendTo('#tbl_history_stok .col-md-6:eq(0)');
	}


	$('#Tbl_Stok tbody').on('click', 'button.btn-ttrx', function () {

		var data = $("#Tbl_Stok").DataTable().row($(this).parents('tr')).data();
		$('.modal-title').html(`History Transaksi ${data.Item_Name} (${data.Item_Code})`)


		$('#Warehouse_ID').val(data.Warehouse_ID)
		$('#Item_Code').val(data.Item_Code)
		Init_DT_Trx()


		$('#modal-history-transaksi').modal('show');
	});

	Init_DT()

})
