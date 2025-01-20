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
			url: $('meta[name="base_url"]').attr('content') + "FinanceAccounting/BukuBesar/DT_List_BukuBesar",
			dataType: "json",
			type: "POST",
			data: {
				IdentityPattern: $('#IdentityPattern').val()
			}
		},
		columns: [
			{
				data: "id_dtl", // 0
				name: "id_dtl",
			},
			{
				data: "id_hdr", // 1
				name: "id_hdr",
			},
			{
				data: "no_jurnal", // 2
				name: "no_jurnal",
			},
			{
				data: "tgl_jurnal", // 3
				name: "tgl_jurnal",
			},
			{
				data: "reff_desc", // 4
				name: "reff_desc",
			},
			{
				data: "akun", // 5
				name: "akun",
			},
			{
				data: "debit", // 6
				name: "debit",
			},
			{
				data: "credit", // 7
				name: "credit",
			},
			{
				data: "keterangan", // 8
				name: "keterangan",
			},
		],
		order: [
			[0, "desc"]
		],
		columnDefs: [{
			className: "text-center",
			targets: [0, 1, 2],
		},
		{
			visible: false,
			targets: [0, 1]
		},
		{
			className: "text-left",
			targets: []
		},
		{
			className: "text-right",
			targets: [3, 6, 7]
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
