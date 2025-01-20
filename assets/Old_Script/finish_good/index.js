$(document).ready(function () {
	var Tbl_fg = $("#Tbl-FinishGood").DataTable({
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
			url: $('meta[name="base_url"]').attr('content') + "FinishGood/DataTable_FG",
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
				data: "Customer_Name",
				name: "Customer_Name",
			},
			{
				data: "Product_Code",
				name: "Product_Code",
			},
			{
				data: "Nama",
				name: "Nama",
			},
			{
				data: "Qty",
				name: "Qty",
			},
			{
				data: "Uom",
				name: "Uom",
			},
			{
				data: "SysId",
				name: "handle",
				render: function (data, type, row, meta) {
					return `<button class="btn btn-sm btn-warning btn-ttrx" data-pk="${row.Product_Code}" data-toggle="tooltip" title="Lihat Transaksi ${row.Product_Code}"><i class="fas fa-random"></i></button>&nbsp;` + `<button class="btn btn-sm btn-primary btn-stok" data-pk="${row.Product_Code}" data-toggle="tooltip" title="List Stok ${row.Product_Code}"><i class="fas fa-boxes"></i></button>`;
				}
			},
		],
		order: [
			[2, 'ASC']
		],
		columnDefs: [{
			className: "align-middle text-center",
			targets: [0, 1, 2, 3, 5, 6],
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
			}, "print"
		],
	}).buttons().container().appendTo('#Tbl-FinishGood .col-md-6:eq(0)');


	$(document).on('click', '.btn-ttrx', function () {
		let pc = $(this).attr('data-pk');

		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "FinishGood/popup_detail_ttrx",
			data: {
				product_code: pc
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
				Swal.close()
				$('#location').html(response);
				$('#modal-detail-ttrx').modal('show');
			},
			error: function () {
				Swal.close()
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Terjadi kesalahan teknis segera lapor pada admin!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		});
	})

	$(document).on('click', '.btn-stok', function () {
		let pc = $(this).attr('data-pk');

		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "FinishGood/popup_detail_stok",
			data: {
				product_code: pc
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
				Swal.close()
				$('#location').html(response);
				$('#modal-detail-stok').modal('show');
			},
			error: function () {
				Swal.close()
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Terjadi kesalahan teknis segera lapor pada admin!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		});
	})

})
