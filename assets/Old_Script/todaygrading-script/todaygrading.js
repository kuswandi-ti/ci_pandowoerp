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

	var table = $("#DataTable-todayLpb").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		// select: true,
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "TodayGrading/DataTable_today_lpb",
			dataType: "json",
			type: "POST",
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
				data: "tgl_kirim",
				name: "tgl_kirim",
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
			},
			{
				data: "legalitas",
				name: "legalitas",
			},
			{
				data: null,
				name: "handle",
				searchable: false,
				orderable: false,
				render: function (data, type, row, meta) {
					return `<button type="button" class="btn btn-xs bg-gradient-warning detail-popup-lpb" data-toggle="tooltip" title="Detail LPB"><i class="fas fa-eye"></i> Detail</button> &nbsp;`;
				}
			},
		],
		order: [
			[0, "desc"]
		],
		columnDefs: [{
				className: "text-center",
				targets: [0, 1, 2, 3, 4, 5, 6, 7, 8],
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
	});

	$('#DataTable-todayLpb').on('click', 'tbody .detail-popup-lpb', function () {
		var rowData = table.row($(this).closest('tr')).data();

		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "DatabaseLpb/popup_detail_lpb",
			data: {
				lpb: rowData.lpb
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
				$('#modal-detail-lpb').modal('show');
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

	// + `<a href="${$('meta[name="base_url"]').attr('content')}DatabaseLpb/report_commercial_lpb/${row.lpb}" target="_blank" class="btn btn-xs bg-gradient-danger btn-print" data-pk="${row.sysid}" data-toggle="tooltip" title="Report Commercial LPB"><i class="fas fa-print"></i> Report</a>`
});
