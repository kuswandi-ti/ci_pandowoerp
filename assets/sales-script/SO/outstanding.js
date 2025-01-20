$(document).ready(function () {
	$("#DataTable")
		.DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
			aLengthMenu: [
				[15, 25, 50, 10000],
				[15, 25, 50, "All"],
			],

			ajax: {
				url:
					$('meta[name="base_url"]').attr("content") +
					"Sales/SalesOrder/DT_listdata_outstanding",
				dataType: "json",
				type: "POST",
			},
			columns: [
				{
					data: "SO_Number",
					render: function (data, type, row, meta) {
						return (
							'<a type="button" class="btn custom-btn-link so-number-btn"">' +
							data +
							"</a>"
						);
					},
				},
				{
					data: "PO_Number",
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Customer_Name",
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Item_Code",
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Item_Name",
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Item_Price",
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-end align-middle");
					},
					render: function (data, type, row, meta) {
						return formatIdr(data); // Use formatIdr for decimal 2 digit
					},
				},
				{
					data: "Currency",
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-end align-middle text-capitalize");
					},
				},
				{
					data: "Qty_so",
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-end align-middle");
					},
					render: function (data, type, row, meta) {
						return roundToFourDecimals(data); // Use roundToFourDecimals for qty
					},
				},
				{
					data: "Tot_value_item_so",
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-end align-middle");
					},
					render: function (data, type, row, meta) {
						return formatIdr(data); // Use formatIdr for decimal 2 digit
					},
				},
				{
					data: "Tot_qty_shp",
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-end align-middle");
					},
					render: function (data, type, row, meta) {
						return roundToFourDecimals(data); // Use roundToFourDecimals for qty
					},
				},
				{
					data: "Sum_value_shp",
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-end align-middle");
					},
					render: function (data, type, row, meta) {
						return formatIdr(data); // Use formatIdr for decimal 2 digit
					},
				},
				{
					data: "Qty_ost_so",
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-end align-middle");
					},
					render: function (data, type, row, meta) {
						return roundToFourDecimals(data); // Use roundToFourDecimals for qty
					},
				},
				{
					data: "Value_ost_so",
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-end align-middle");
					},
					render: function (data, type, row, meta) {
						return formatIdr(data); // Use formatIdr for decimal 2 digit
					},
				},
			],
			order: [[0, "desc"]],
			columnDefs: [
				{
					className: "text-center",
					targets: "_all",
				},
				{
					className: "text-left",
					targets: [],
				},
			],
			autoWidth: false,
			// responsive: true,
			preDrawCallback: function () {
				$("#DataTable tbody td").addClass("blurry");
			},
			language: {
				processing:
					'<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
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
		})
		.buttons()
		.container()
		.appendTo("#TableData_wrapper .col-md-6:eq(0)");
	//
	$(document).on("click", ".so-number-btn", function () {
		let soNumber = $(this).text();
		let itemCode = $(this).closest("tr").find("td:eq(3)").text();
		$.ajax({
			type: "GET",
			url:
				$('meta[name="base_url"]').attr("content") +
				"Sales/SalesOrder/modal_detail_shipping",
			data: {
				so_number: soNumber,
				item_code: itemCode,
			},
			beforeSend: function () {
				Swal.fire({
					title: "Loading....",
					html: '<div class="spinner-border text-primary"></div>',
					showConfirmButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false,
				});
			},
			success: function (response) {
				Swal.close();
				$("#location").html(response);
				$("#modal_list_shipping").modal("show");
			},
			error: function (xhr, status, error) {
				Swal.close();
				console.error("AJAX error: " + status + " - " + error);
			},
		});

		//
	});
});
