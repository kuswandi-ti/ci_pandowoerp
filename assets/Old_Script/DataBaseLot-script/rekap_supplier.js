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

	$('select[name="supplier"]').select2({
		minimumInputLength: 0,
		allowClear: true,
		placeholder: '-Pilih Supplier-',
		ajax: {
			dataType: 'json',
			url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/select_supplier",
			delay: 800,
			data: function (params) {
				return {
					search: params.term
				}
			},
			processResults: function (data, page) {
				return {
					results: $.map(data, function (obj) {
						return {
							id: obj.id,
							text: obj.text
						};
					})
				};
			},
			cache: true
		}
	})

	$('#filter-form').validate({
		errorElement: 'span',
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			element.closest('.form-group').append(error);
		},
		highlight: function (element, errorClass, validClass) {
			$(element).addClass('is-invalid');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).removeClass('is-invalid');
		}
	});
	$.validator.setDefaults({
		debug: true,
		success: 'valid'
	});

	$('#do--filter').click(function (e) {
		e.preventDefault();
		if ($("#filter-form").valid()) {
			Initialize_datatable()
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	function Initialize_datatable() {
		$("#DataTable").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			// select: true,
			"ordering": false,
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
				[100000, 25, 50],
				['All', 25, 50]
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "MonitoringMaterialSupplier/Rekap_Material_Supplier",
				dataType: "json",
				type: "GET",
				data: {
					"from": $('#from').val(),
					"to": $('#to').val(),
					"supplier": $('#supplier').val(),
				}
			},
			// "rowsGroup": [0, 1],
			columns: [{
					data: "lpb_hdr",
					name: "lpb_hdr",
					// render: function (data, type, row, meta) {
					// 	return row.lpb_hdr + `<br/><a href="${$('meta[name="base_url"]').attr('content')}DatabaseLpb/preview_detail_lpb/${row.lpb_hdr}" class="btn btn-xs bg-gradient-primary" data-toggle="tooltip" title="Detail LPB"><i class="far fa-folder-open"></i></a>&nbsp;` +
					// 		`<a href="${$('meta[name="base_url"]').attr('content')}DatabaseLpb/report_commercial_lpb/${row.lpb_hdr}" target="_blank" class="btn btn-xs bg-gradient-danger btn-print" data-pk="${row.sysid}" data-toggle="tooltip" title="Report Commercial LPB"><i class="fas fa-print"></i></a>`;
					// }
				},
				{
					data: "supplier",
					name: "supplier",
					visible: false,
				},
				{
					data: "no_lot",
					name: "no_lot",
				},
				{
					data: "kode",
					name: "kode",
				},
				{
					data: "harga_per_pcs",
					name: "harga_per_pcs",
				},
				{
					data: "qty",
					name: "qty",
				},
				{
					data: "kubikasi",
					name: "kubikasi",
					orderable: false,
				},
				{
					data: "subtotal",
					name: "subtotal",
				},
				{
					data: "grader",
					name: "grader",
				},
				{
					data: "penilaian",
					name: "penilaian",
				},
				{
					data: "tgl_finish_sortir",
					name: "tgl_finish_sortir",
				},
				{
					data: "into_oven",
					name: "into_oven",
					render: function (data, type, row, meta) {
						if (row.into_oven == '0') {
							return `<span class="badge badge-warning">${row.status_kayu}</span>`
						} else if (row.into_oven == '1') {
							return `<span class="badge badge-danger">${row.status_kayu}</span>`
						} else if (row.into_oven == '2') {
							return `<span class="badge badge-success">${row.status_kayu}</span>`
						} else if (row.into_oven == '3') {
							return `<span class="badge badge-primary">${row.status_kayu}</span>`
						} else {
							return `<span class="badge badge-info">${row.status_kayu}</span>`
						}
					}
				},
			],
			columnDefs: [{
				className: "align-middle text-center",
				targets: [0, 1, 2, 3, 5, 6, 8, 9, 10, 11],
			}, {
				className: " font-italic font-weight-bold",
				targets: [4, 7],
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
					title: $('#supplier').text() + '_' + $('#from').val() + '_S.D_' + $('#to').val(),
					className: "btn btn-info",
				}, {
					extend: 'excelHtml5',
					footer: true,
					title: $('#supplier').text() + '_' + $('#from').val() + '_S.D_' + $('#to').val(),
					className: "btn btn-success",
				}, {
					extend: 'pdfHtml5',
					footer: true,
					orientation: 'landscape',
					pageSize: 'A4',
					title: $('#supplier').text() + '_' + $('#from').val() + '_S.D_' + $('#to').val(),
					className: "btn btn-danger",
				}, "print"
			],
			"initComplete": function () {
				Sum_Tot_Filter_Result();
			}
		}).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');
	}

	const rupiah = (number) => {
		return new Intl.NumberFormat("id-ID", {
			style: "currency",
			currency: "IDR"
		}).format(number);
	}
	$('#footer').hide()

	function Sum_Tot_Filter_Result() {
		$.ajax({
			type: "get",
			url: $('meta[name="base_url"]').attr('content') + "MonitoringMaterialSupplier/Summary_Material_supplier",
			dataType: "json",
			data: {
				"from": $('#from').val(),
				"to": $('#to').val(),
				"supplier": $('#supplier').val(),
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
				if (response.code == 200) {
					$('#tot-kubikasi').text(response.kubikasi + ' m³')
					$('#tot-rupiah').text(rupiah(response.rupiah))
					$('#footer').show()
				} else {
					Toast.fire({
						icon: 'error',
						title: response.msg
					});
				}
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
	}

	$('#DataTable tbody').on('dblclick', 'tr', function () {
		let RowData = $("#DataTable").DataTable().row($(this)).data();
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "DatabaseLot/dtl_HstDataLot",
			data: {
				lot: RowData.no_lot
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

	$(".readonly").keydown(function (event) {
		return false;
	});

	$('.datepicker').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd',
		todayHighlight: true,
		orientation: 'bottom',
	});
})
