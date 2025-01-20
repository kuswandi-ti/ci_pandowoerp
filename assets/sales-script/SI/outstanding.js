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
					"Sales/ShippingIns/DT_listdata_ost",
				dataType: "json",
				type: "POST",
			},
			columns: [
				{
					data: "ShipInst_Number",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "ShipInst_Date",
					render: function (data) {
						return data.substring(0, 10);
					},
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Address",
					createdCell: function (td) {
						$(td).addClass("align-middle");
					},
				},
				{
					data: "ExpectedDeliveryDate",
					render: function (data) {
						return data.substring(0, 10);
					},
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				// {
				// 	data: "SysId",
				// 	render: function (data, type, row) {
				// 		let button =
				// 			'<button class="btn btn-success btn-sm btn-details" data-sysid="' +
				// 			row.SysId +
				// 			'">' +
				// 			'<i class="fas fa-file"></i>' +
				// 			"</button>";
				// 		return button;
				// 	},
				// 	createdCell: function (td) {
				// 		$(td).addClass("text-center align-middle");
				// 	},
				// },
				// {
				// 	data: "Approve",
				// 	render: function (data) {
				// 		return data == 1
				// 			? '<div class="d-flex justify-content-center"><i class="fas fa-check text-success"></i></div>'
				// 			: '<div class="d-flex justify-content-center"><i class="fas fa-question text-danger"></i></div>';
				// 	},
				// 	createdCell: function (td) {
				// 		$(td).addClass("text-center align-middle");
				// 	},
				// },
				// {
				// 	data: "Is_Cancel",
				// 	render: function (data) {
				// 		return data == 1
				// 			? '<div class="d-flex justify-content-center"><span class="badge bg-success">Canceled</span></div>'
				// 			: '<div class="d-flex justify-content-center"><span class="badge bg-warning">Open</span></div>';
				// 	},
				// 	createdCell: function (td) {
				// 		$(td).addClass("text-center align-middle");
				// 	},
				// },
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
				$("#DataTable tbody").on("click", "tr", function () {
					const rowData = dataTable.row(this).data();

					if (rowData && rowData.SysId) {
						const sysId = rowData.SysId;

						if ($(this).hasClass("table-primary")) {
							$(this).removeClass("table-primary text-white");
							delete selectedShippingOrders[sysId];
						} else {
							$("#DataTable tbody tr").each(function () {
								if ($(this).hasClass("table-warning-selected")) {
									$(this)
										.addClass("table-warning")
										.removeClass(
											"table-primary text-white table-warning-selected"
										);
								} else {
									$(this).removeClass("table-primary text-white");
								}
							});

							selectedShippingOrders = {};

							if ($(this).hasClass("table-warning")) {
								$(this)
									.removeClass("table-warning")
									.addClass("table-primary text-white table-warning-selected");
							} else {
								$(this).addClass("table-primary text-white");
							}

							selectedShippingOrders[sysId] = rowData;
						}
					} else {
						console.error("Row data or SysId is undefined", rowData);
					}
				});

				// Event handler for buttons with data-sysid
			},
			drawCallback: function () {
				// Kosongkan selectedShippingOrders yang tidak sesuai dengan hasil pencarian
				$("#DataTable tbody tr").each(function () {
					const rowData = dataTable.row(this).data();

					// Log row data on draw
					if (rowData && selectedShippingOrders[rowData.SysId]) {
						// Jika SysId ditemukan dalam hasil pencarian, tetap simpan di selectedShippingOrders
						if (selectedShippingOrders[rowData.SysId]) {
							$(this)
								.removeClass("table-warning")
								.addClass("table-primary text-white table-warning-selected");
						}
					} else {
						// Hapus SysId yang tidak ada dalam hasil pencarian
						for (const sysId in selectedShippingOrders) {
							if (selectedShippingOrders.hasOwnProperty(sysId) && !rowData) {
								delete selectedShippingOrders[sysId];
							}
						}
					}

					// Terapkan background kuning untuk baris di mana Is_Cancel == 1
					if (rowData && rowData.Is_Cancel == 1) {
						$(this).addClass("table-warning");
					}
				});

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
					text: `<i class="fas fa-search"></i> View Detail`,
					className: "btn btn-info",
					action: function () {
						if (Object.keys(selectedShippingOrders).length === 0) {
							Swal.fire({
								icon: "warning",
								title: "Ooppss...",
								text: "Silahkan pilih data untuk melihat detail!",
								footer:
									'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
							});
						} else {
							let selectedRow = Object.values(selectedShippingOrders)[0];
							let sysId = selectedRow.SysId;
							let url =
								$('meta[name="base_url"]').attr("content") +
								"Sales/ShippingIns/detail/" +
								sysId;
							window.location.href = url;
						}
					},
				},
				{
					text: `<i class="fas fa-print fs-3"></i> Print Surat Jalan `,
					className: "btn bg-gradient-success",
					action: function () {
						if (Object.keys(selectedShippingOrders).length === 0) {
							Swal.fire({
								icon: "warning",
								title: "Ooppss...",
								text: "Silahkan pilih data terlebih dahulu untuk mencetak report!",
								footer:
									'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
							});
						} else {
							let selectedRow = Object.values(selectedShippingOrders)[0];
							let sysId = selectedRow.SysId + "." + "0";
							let Approve = selectedRow.Approve;
							let isCancel = selectedRow.Is_Cancel;

							// Check approval and close status
							if (isCancel == 1 || Approve == 2 || Approve == 0) {
								Swal.fire({
									icon: "warning",
									title: "Ooppss...",
									text: "Report cetak hanya bisa di tampilkan pada data yang telah legitimate (tidak close dan sudah approve)!",
									footer:
										'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
								});
							} else {
								// Open PDF report in a new tab
								window.open(
									$('meta[name="base_url"]').attr("content") +
										"Sales/ShippingIns/export_pdf_si/" +
										sysId,
									"_blank"
								);
								//
							}
						}
					},
				},
				{
					text: `<i class="fas fa-print fs-3"></i> Print Comm Invoice `,
					className: "btn bg-gradient-success",
					action: function () {
						if (Object.keys(selectedShippingOrders).length === 0) {
							Swal.fire({
								icon: "warning",
								title: "Ooppss...",
								text: "Silahkan pilih data terlebih dahulu untuk mencetak report!",
								footer:
									'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
							});
						} else {
							let selectedRow = Object.values(selectedShippingOrders)[0];
							let sysId = selectedRow.SysId + "." + "1";
							let Approve = selectedRow.Approve;
							let isCancel = selectedRow.Is_Cancel;

							// Check approval and close status
							if (isCancel == 1 || Approve == 2 || Approve == 0) {
								Swal.fire({
									icon: "warning",
									title: "Ooppss...",
									text: "Report cetak hanya bisa di tampilkan pada data yang telah legitimate (tidak close dan sudah approve)!",
									footer:
										'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
								});
							} else {
								// Open PDF report in a new tab
								window.open(
									$('meta[name="base_url"]').attr("content") +
										"Sales/ShippingIns/export_pdf_si/" +
										sysId,
									"_blank"
								);
								//
							}
						}
					},
				},
			],
		});

		dataTable
			.buttons()
			.container()
			.appendTo("#DataTable_wrapper .col-md-6:eq(0)");
	}
});
