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

	function Initialize_datatable() {
		// console.log($('#from').val());
		$("#DataTable").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			// select: true,
			dom: 'lBfrtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			fixedHeader: {
				header: true,
				headerOffset: 48
			},
			lengthChange: false,
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "DatabaseLpb/DataTable_DataBase_Lpb",
				dataType: "json",
				type: "GET",
				data: {
					"from": $('#from').val(),
					"to": $('#to').val()
				}
			},
			columns: [{
				data: "sysid",
				name: "sysid",
				visible: true,
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: "lpb",
				name: "lpb",
			},
			{
				data: "supplier",
				name: "supplier",
			},
			{
				data: "tgl_finish_sortir",
				name: "tgl_finish_sortir",
			},
			{
				data: "grader",
				name: "grader",
			},
			{
				data: "lot",
				name: "lot",
				searchable: false,
				orderable: false,
			},
			{
				data: "kubikasi",
				name: "kubikasi",
				searchable: false,
				orderable: false,
			}, {
				data: "pcs",
				name: "pcs",
				searchable: false,
				orderable: false,
			}, {
				data: "amount",
				name: "amount",
				searchable: false,
				orderable: false,
			},
			{
				data: null,
				name: "handle",
				searchable: false,
				orderable: false,
				render: function (data, type, row, meta) {
					return `<a href="javascript:void(0)" class="btn btn-xs bg-gradient-warning btn-history" data-pk="${row.lpb}" data-toggle="tooltip" title="History Activity LPB"><i class="fas fa-history"></i> History</a>&nbsp;` +
						`<a href="${$('meta[name="base_url"]').attr('content')}DatabaseLpb/preview_detail_lpb/${row.lpb}" class="btn btn-xs bg-gradient-primary" data-toggle="tooltip" title="Detail LPB"><i class="far fa-folder-open"></i> Detail</a>&nbsp;` +
						`<a href="${$('meta[name="base_url"]').attr('content')}DatabaseLpb/report_commercial_lpb/${row.lpb}" target="_blank" class="btn btn-xs bg-gradient-danger btn-print" data-pk="${row.sysid}" data-toggle="tooltip" title="Report Commercial LPB"><i class="fas fa-print"></i> Report</a>`;
				}
			},
			],
			order: [
				[0, "desc"]
			],
			columnDefs: [{
				className: "text-center",
				targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
			},
			{
				className: "text-left",
				targets: []
			}
			],
			autoWidth: false,
			responsive: true,
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
			"buttons": ["copy",
				{
					extend: 'csvHtml5',
					title: $('title').text() + '~' + $('#from').val() + ' sd ' + $('#to').val(),
					className: "btn btn-info",
				}, {
					extend: 'excelHtml5',
					title: $('title').text() + '~' + $('#from').val() + ' sd ' + $('#to').val(),
					className: "btn btn-success",
				}, {
					extend: 'pdfHtml5',
					title: $('title').text() + '~' + $('#from').val() + ' sd ' + $('#to').val(),
					className: "btn btn-danger",
					orientation: "landscape"
				}, "print", "colvis"
			],
		}).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');
	}

	$(".readonly").keydown(function (event) {
		return false;
	});

	$('.datepicker').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd',
		todayHighlight: true,
		orientation: 'bottom',
	});

	$('#do--filter').on('click', function () {
		Initialize_datatable()
	})

	Initialize_datatable()

	$(document).on('click', '.btn-history', function () {
		let lpb = $(this).attr('data-pk');
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "DatabaseLpb/modal_history",
			data: {
				lpb: lpb
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
				$('#modal-history-lpb').modal('show');
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
});
