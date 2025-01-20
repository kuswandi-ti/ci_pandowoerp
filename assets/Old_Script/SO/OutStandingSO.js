$(document).ready(function () {
	var TableData = $("#TableData").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		paging: true,
		select: true,
		// "responsive": true,
		dom: 'lBfrtip',
		"oLanguage": {
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
		},
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "SoOutstanding/DT_SO_OutStanding",
			dataType: "json",
			type: "post",
		},

		columns: [{
				data: "SO_SysId_Hdr",
				name: "SO_SysId_Hdr",
				orderable: false,
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			}, {
				data: "SO_Number",
				name: "SO_Number",
				render: function (data, type, row, meta) {
					return `<pre><a href="javascript:void(0)" class="btn-preview font-weight-bold" data-pk="${row.SO_SysId_Hdr}">${data}</a></pre>`
				}
			}, {
				data: "No_Po_Customer",
				name: "No_Po_Customer",
				render: function (data, type, row, meta) {
					return `<pre>${data}</pre>`
				}
			},
			{
				data: "Status_SO",
				name: "Status_SO"
			},
			{
				data: "Customer_Code",
				name: "Customer_Code"
			},
			{
				data: "Customer_Name",
				name: "Customer_Name"
			},
			{
				data: "Flag",
				name: "Flag"
			},
			{
				data: "Product_Code",
				name: "Product_Code"
			},
			{
				data: "Product_Name",
				name: "Product_Name"
			},
			{
				data: "Product_Price",
				name: "Product_Price",
				render: function (data, type, row, meta) {
					return formatRupiah(data)
				}
			},
			{
				data: "Qty_SO",
				name: "Qty_SO"
			},
			{
				data: "Amount_SO_PerItem",
				name: "Amount_SO_PerItem",
				render: function (data, type, row, meta) {
					return formatRupiah(data)
				}
			},
			{
				data: "Qty_SJ",
				name: "Qty_SJ"
			},
			{
				data: "Amount_SJ",
				name: "Amount_SJ",
				render: function (data, type, row, meta) {
					return formatRupiah(data)
				}
			},
			{
				data: "Uom",
				name: "Uom"
			},
			{
				data: "Qty_SO_OutStanding",
				name: "Qty_SO_OutStanding",
				render: function (data, type, row, meta) {
					return `<pre>${data}</pre>`
				}
			},
			{
				data: "Outstanding_Amount_SO",
				name: "Outstanding_Amount_SO",
				render: function (data, type, row, meta) {
					return formatRupiah(data)
				}
			},
			{
				data: "Tgl_Terbit",
				name: "Tgl_Terbit"
			},
			{
				data: "Term_Of_Payment",
				name: "Term_Of_Payment"
			},
			{
				data: "Remark_TOP",
				name: "Remark_TOP"
			},
			{
				data: "Term_Of_Delivery",
				name: "Term_Of_Delivery"
			},
			{
				data: "Customer_Address",
				name: "Customer_Address",
				render: function (data, type, row, meta) {
					return `<pre>${data}</pre>`
				}
			},
			{
				data: "Koresponden",
				name: "Koresponden"
			},
			{
				data: "Note",
				name: "Note"
			},
		],
		"order": [
			[20, "asc"],
		],
		autoWidth: false,
		columnDefs: [{
				className: "text-left",
				targets: [9, 10, 11, 12, 13, 16]
			},
			{
				className: "font-weight-bold text-info",
				targets: []
			}
		],
		"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			if (aData.Qty_SO_OutStanding == 0) {
				$('td', nRow).css('background-color', '#72C585');
			}
		},
		preDrawCallback: function () {
			$("#TableData tbody td").addClass("blurry");
		},
		language: {
			processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
		},
		drawCallback: function () {
			$("#TableData tbody td").addClass("blurry");
			setTimeout(function () {
				$("#TableData tbody td").removeClass("blurry");
			});
			$('[data-toggle="tooltip"]').tooltip();
		},
		"buttons": ["copy",
			{
				extend: 'csvHtml5',
				title: $('title').text() + '~' + moment().format("YYYY-MM-DD"),
				className: "btn btn-info",
			}, {
				extend: 'excelHtml5',
				title: $('title').text() + '~' + moment().format("YYYY-MM-DD"),
				className: "btn btn-success",
			}, {
				extend: 'pdfHtml5',
				title: $('title').text() + '~' + moment().format("YYYY-MM-DD"),
				className: "btn btn-danger",
				orientation: "landscape"
			}, "print"
		],
	}).buttons().container().appendTo('#TableData .col-md-6:eq(0)');

	$(document).on('click', '.btn-preview', function () {
		let SysId = $(this).attr('data-pk');

		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "SalesOrder/preview_sales_order",
			data: {
				SysId: SysId
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
				$('#m_preview_sales_order').modal('show');
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
	});

	function formatRupiah(moneyy) {
		let money = parseFloat(moneyy)
		return new Intl.NumberFormat('id-ID', {
			style: 'currency',
			currency: 'IDR'
		}).format(money);
	}
})
