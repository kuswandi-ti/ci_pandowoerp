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
			[10, 25, 50, 10000],
			[10, 25, 50, 'All']
		],
		select: true,
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "Purchase/VendorPrice/DT_listdata_price_list",
			dataType: "json",
			type: "POST",
		},
		columns: [
			{
				data: "Account_Name",
			},
			{
				data: "Item_Code",
			},
			{
				data: "Item_Name",
			},
			{
				data: "VPR_Number",
			},
			{
				data: "Price",
				render: function (data, type, row, meta) {
					return formatIdrAccounting(data);
				}
			},
			{
				data: "Effective_Date",
				render: function (data, type, row, meta) {
					return moment(data).format("DD MMMM YYYY");
				}
			},
		],
		order: [
			[1, "desc"]
		],
		columnDefs: [{
			className: "text-center",
			targets: "_all",
		},
		{
			className: "text-left",
			targets: []
		}
		],
		autoWidth: false,
		// responsive: true,
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
});
