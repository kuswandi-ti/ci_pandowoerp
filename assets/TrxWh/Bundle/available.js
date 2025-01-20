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

	$('#do--filter').click(function (e) {
		Initialize_datatable()
	});

	function Initialize_datatable() {
		$("#DataTable").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			lengthChange: false,
			// "ordering": false,
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
				[-1],
				['ALL']
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/Bundle/DT_Lot_Avail",
				dataType: "json",
				type: "GET",
				data: {
					"material": $('#material').find(":selected").val()
				}
			},
			// "rowsGroup": [0, 1],
			columns: [{
					data: "lpb_hdr",
					name: "lpb_hdr",
				},
				{
					data: "supplier",
					name: "supplier",
				},
				{
					data: "no_lot",
					name: "no_lot",
					render: function (data) {
						return `<a href="javascript:void(0)" class="detail--size"><u>${data}</u></a>`
					}
				},
				{
					data: "kode",
					name: "kode",
					render: function (data, type, row, meta) {
						return row.kode + ' (' + row.deskripsi + ')';
					}
				},
				{
					data: "qty",
					name: "qty",
				},
				{
					data: "kubikasi",
					name: "kubikasi",
					orderable: false,
					render: function (data) {
						return roundToFourDecimals(data);
					}
				},
				{
					data: "grader",
					name: "grader",
				},
				{
					data: "tgl_kirim",
					name: "tgl_kirim",
				},
				{
					data: "warehouse",
					name: "warehouse",
				},
				{
					data: "into_oven",
					name: "into_oven",
					render: function (data, type, row, meta) {
						if (row.into_oven == '0') {
							status_lot = `<span class="badge badge-warning">${row.status_kayu}</span>`
						} else if (row.into_oven == '1') {
							status_lot = `<span class="badge badge-danger blink_me">${row.status_kayu}</span>`
						} else if (row.into_oven == '2') {
							status_lot = `<span class="badge badge-success">${row.status_kayu}</span>`
						} else if (row.into_oven == '3') {
							status_lot = `<span class="badge badge-primary">${row.status_kayu}</span>`
						} else {
							status_lot = `<span class="badge badge-info">${row.status_kayu}</span>`
						}
						return status_lot + `&nbsp;&nbsp;<a href="${$('meta[name="base_url"]').attr('content')}TrxWh/Lpb/preview_detail_lpb/${row.lpb_hdr}" class="btn btn-xs bg-gradient-primary" data-toggle="tooltip" title="Detail LPB Bundle"><i class="far fa-folder-open"></i></a>&nbsp;` +
							`<a href="${$('meta[name="base_url"]').attr('content')}TrxWh/Lpb/report_commercial_lpb/${row.lpb_hdr}" target="_blank" class="btn btn-xs bg-gradient-danger btn-print" data-pk="${row.sysid}" data-toggle="tooltip" title="Report Commercial Bundle"><i class="fas fa-print"></i></a>`;
					}
				},
			],
			order: [
				[7, "desc"]
			],
			columnDefs: [{
				className: "align-middle text-center",
				targets: [0, 1, 2, 5, 6, 8, 9],
			}],
			// "fnRowCallback": function (row, data, index) {
			//     $(row).find('td:eq(4)').css('font-size', '5pt');
			//     $(row).find('td:eq(7)').css('font-size', '5pt');
			// },
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
		}).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');
	}

	$(document).on('click', 'a.detail--size', function () {
		let data = $('#DataTable').DataTable().row($(this).parents('tr')).data();

		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/StokBasah/modal_list_size_lot",
			data: {
				sysid: data['sysid'],
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
				$('#modal_detail_size_lot').modal('show');
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

	// $('#DataTable tbody').on('dblclick', 'tr', function () {
	// 	let RowData = $("#DataTable").DataTable().row($(this)).data();
	// 	$.ajax({
	// 		type: "GET",
	// 		url: $('meta[name="base_url"]').attr('content') + "DatabaseLot/dtl_HstDataLot",
	// 		data: {
	// 			lot: RowData.no_lot
	// 		},
	// 		beforeSend: function () {
	// 			Swal.fire({
	// 				title: 'Loading....',
	// 				html: '<div class="spinner-border text-primary"></div>',
	// 				showConfirmButton: false,
	// 				allowOutsideClick: false,
	// 				allowEscapeKey: false
	// 			})
	// 		},
	// 		success: function (response) {
	// 			Swal.close()

	// 			$('#location').html(response);
	// 			$('#modal-detail-lpb').modal('show');
	// 		},
	// 		error: function () {
	// 			Swal.close()
	// 			Swal.fire({
	// 				icon: 'error',
	// 				title: 'Oops...',
	// 				text: 'Terjadi kesalahan teknis segera lapor pada admin!',
	// 				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
	// 			});
	// 		}
	// 	});
	// })

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
})
