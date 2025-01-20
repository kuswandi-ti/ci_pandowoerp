$(document).ready(function () {
	reloadData();
	function reloadData() {
		let selectedShippingOrders = {}; // Object to store selected Shipping Orders
		const dataTable = $("#DataTable").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
			lengthMenu: [
				[10, 25, 50, 10000],
				[10, 25, 50, "All"],
			],
			ajax: {
				url:
					$('meta[name="base_url"]').attr("content") +
					"Sales/ShippingIns/DT_listdata_monitoring_item",
				dataType: "json",
				type: "POST",
			},
			columns: [
				{
					data: "Nomor_SO",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Item_Code",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Item_Name",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Note",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Color",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Brand",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Dimension",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Weight",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Qty_Shipped",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Uom",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle text-uppercase");
					},
				},
				{
					data: "Qty_Secondary",
					render: function (data) {
						// Cek apakah data kosong, null, atau bernilai 0/0.0000
						return data && parseFloat(data) !== 0 ? data : "-";
					},
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Uom_Secondary",
					render: function (data) {
						// Jika data kosong atau null, kembalikan "-"
						return data ? data : "-";
					},
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Warehouse_Qty", // Expecting data in the format 'Warehouse_Code:Qty, Warehouse_Code:Qty, ...'
					render: function (data, type, row) {
						if (data) {
							const warehouses = data.split(", ");
							let warehouseHtml = "";
							warehouses.forEach(function (warehouse) {
								const [code, qty] = warehouse.split("|");
								warehouseHtml += `
                    <div class="input-group input-group-sm mb-1">
                        <input class="form-control form-control-sm text-center" readonly value="${
													code || "-"
												}">
                        <input class="ml-2 form-control form-control-sm text-center" readonly value="${
													qty || "-"
												}">
                    </div>`;
							});
							return warehouseHtml;
						}
						return "-";
					},
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
			],
			order: [[0, "desc"]],
			columnDefs: [
				{
					targets: "_all",
				},
			],
			autoWidth: false,
			preDrawCallback: function () {
				$("#DataTable tbody td").addClass("blurry");
			},
			language: {
				processing:
					'<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" class="loading-text"></span> ',
			},
			initComplete: function () {
				//-------------
			},
			drawCallback: function () {
				// Add blur effect and remove it after a short delay
				$("#DataTable tbody td").addClass("blurry");
				setTimeout(function () {
					$("#DataTable tbody td").removeClass("blurry");
				}, 100); // Tambahkan delay untuk memastikan rendering selesai

				// Initialize tooltips
				$('[data-toggle="tooltip"]').tooltip();
			},
			buttons: [
				{
					extend: "csvHtml5",
					title: $("title").text() + "~" + moment().format("YYYY-MM-DD"),
					className: "btn btn-info",
				},
				{
					extend: "excelHtml5",
					title: $("title").text() + "~" + moment().format("YYYY-MM-DD"),
					className: "btn btn-success",
				},
				{
					extend: "pdfHtml5",
					title: $("title").text() + "~" + moment().format("YYYY-MM-DD"),
					className: "btn btn-danger",
					orientation: "landscape",
				},
			],
		});

		dataTable
			.buttons()
			.container()
			.appendTo("#DataTable_wrapper .col-md-6:eq(0)");
	}
});
