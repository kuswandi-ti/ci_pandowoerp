$(document).ready(function () {
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		width: 300,
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.addEventListener('mouseenter', Swal.stopTimer)
			toast.addEventListener('mouseleave', Swal.resumeTimer)
		}
	})

	$(".readonly").keydown(function (event) {
		return false;
	});

	$('#from').datetimepicker({
		format: 'YYYY-MM-DD',
	});
	$('#to').datetimepicker({
		format: 'YYYY-MM-DD',
	});

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
			DataTable_Deskripsi()
			DataTable_Lot()
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	function DataTable_Deskripsi() {
		var DataTable_Deskripsi = $("#DataTable_Deskripsi").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			dom: 'lBfrtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			"aLengthMenu": [
				[500, 750, 999],
				[500, 750, 999]
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "AllocPrd/DataTable_alloc_prd_by_deskripsi",
				dataType: "json",
				type: "GET",
				data: {
					"from": $('#from').val(),
					"to": $('#to').val(),
					"product": $('#product').find(":selected").val()
				}
			},
			columns: [{
					data: "sysid_material",
					name: "sysid_material",
					visible: true,
					orderable: false,
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					data: "deskripsi",
					name: "deskripsi",
				},
				{
					data: "kode",
					name: "kode",
				},
				{
					data: "tebal",
					name: "tebal",
				},
				{
					data: "lebar",
					name: "lebar",
				},
				{
					data: "panjang",
					name: "panjang",
				},
				{
					data: "row_lot",
					name: "row_lot",
				},
				{
					data: "t_qty",
					name: "t_qty",
				},
				{
					data: "kubikasi",
					name: "kubikasi",
				}, {
					data: null,
					name: "detail_lot",
					orderable: false,
					render: function (data, type, row, meta) {
						return `<button class="btn btn-xs bg-gradient-info btn-detail-lot"><i class="fas fa-align-left"></i> Lot</button>`
					}
				}
			],
			order: [
				[1, "asc"]
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
					title: $('title').text() + '~' + moment().format('LL'),
					className: "btn btn-info",
				}, {
					extend: 'excelHtml5',
					title: $('title').text() + '~' + moment().format('LL'),
					className: "btn btn-success",
				}, {
					extend: 'pdfHtml5',
					title: $('title').text() + '~' + moment().format('LL'),
					className: "btn btn-danger",
				}, "print"
			],
		}).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');
	}

	function DataTable_Lot() {
		var DataTable_Lot = $("#DataTable_Lot").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			dom: 'lBfrtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			"aLengthMenu": [
				[500, 750, 999],
				[500, 750, 999]
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "AllocPrd/DataTable_alloc_prd_by_lot",
				dataType: "json",
				type: "GET",
				data: {
					"from": $('#from').val(),
					"to": $('#to').val(),
					"product": $('#product').find(":selected").val()
				}
			},
			columns: [{
					data: "sysid_material",
					name: "sysid_material",
					visible: true,
					orderable: false,
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					data: "no_lot",
					name: "no_lot",
				},
				{
					data: "deskripsi",
					name: "deskripsi",
				},
				{
					data: "kode",
					name: "kode",
				},
				{
					data: "supplier",
					name: "supplier",
				},
				{
					data: "grader",
					name: "grader",
				},
				// {
				//     data: "tgl_kirim",
				//     name: "tgl_kirim",
				//     visible: false
				// },
				{
					data: "tgl_finish_sortir",
					name: "tgl_finish_sortir",
				},
				{
					data: "qty",
					name: "qty",
				},
				{
					data: "kubikasi",
					name: "kubikasi",
				},
				{
					data: "nama_oven",
					name: "nama_oven",
				},
				{
					data: "time_oven",
					name: "time_oven",
					orderable: false,
					render: function (data, type, row, meta) {
						return `<small class="badge badge-danger">${row.time_in.substring(0, 16)} </small><br /><small>s/d</small><br />` +
							`<small class="badge badge-info"> ${row.time_out.substring(0, 16)}</small>`
					}
				}, {
					data: "timer",
					name: "timer",
					render: function (data, type, row, meta) {
						return `<span class="badge badge-default">${row.timer}</span>`
					}
				}, {
					data: 'time_alloc',
					name: "time_alloc",
					render: function (data, type, row, meta) {
						return `<small class="font-weight-bold">${row.time_alloc.substring(0, 16)}</small>`
					}
				}, {
					data: "nama_product",
					name: "nama_product"
				}
			],
			order: [
				[1, "asc"]
			],
			columnDefs: [{
					className: "text-center",
					targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
				},
				{
					className: "text-left",
					targets: []
				}
			],
			autoWidth: false,
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
					title: $('title').text() + '~' + moment().format('LL'),
					className: "btn btn-info",
				}, {
					extend: 'excelHtml5',
					title: $('title').text() + '~' + moment().format('LL'),
					className: "btn btn-success",
				}, {
					extend: 'pdfHtml5',
					title: $('title').text() + '~' + moment().format('LL'),
					className: "btn btn-danger",
				}, "print"
			],
		}).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');
	}

	// ============================================= SCRIPT NEED TRIGGER ==========================================//

	$('#DataTable_Deskripsi tbody').on('click', 'button.btn-detail-lot', function () {
		let data = $('#DataTable_Deskripsi').DataTable().row($(this).parents('tr')).data();
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "DatabaseLot/modal_list_lot_by_deskripsi_alloc_prd",
			data: {
				sysid_material: data['sysid_material'],
				status: 3,
				title: 'Material Ter-Alokasi Ke Produksi',
				"from": $('#from').val(),
				"to": $('#to').val(),
				"product": $('#product').find(":selected").val()
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
				$('#modal_list_lot_by_deskripsi').modal('show');
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

	// $('#DataTable_Lot tbody').on('click', 'button.btn-to-prd', function () {
	// 	let data = $('#DataTable_Lot').DataTable().row($(this).parents('tr')).data();

	// 	Swal.fire({
	// 		title: "Apakah anda yakin!",
	// 		text: `Untuk menggunakan LOT : ${data['no_lot']}, ke produksi ?`,
	// 		icon: 'question',
	// 		showCancelButton: true,
	// 		confirmButtonColor: '#3085d6',
	// 		cancelButtonColor: '#d33',
	// 		confirmButtonText: 'Ya, gunakan!',
	// 		cancelButtonText: 'Batal!'
	// 	}).then((result) => {
	// 		if (result.isConfirmed) {
	// 			$.ajax({
	// 				dataType: "json",
	// 				type: "POST",
	// 				url: $('meta[name="base_url"]').attr('content') + "StockMtrlKering/use_to_prd_manual",
	// 				data: {
	// 					barcode: data['no_lot']
	// 				},
	// 				beforeSend: function () {
	// 					Swal.fire({
	// 						title: 'Loading....',
	// 						html: '<div class="spinner-border text-primary"></div>',
	// 						showConfirmButton: false,
	// 						allowOutsideClick: false,
	// 						allowEscapeKey: false
	// 					})
	// 				},
	// 				success: function (response) {
	// 					Swal.close()
	// 					if (response.code == 200) {
	// 						Toast.fire({
	// 							icon: 'success',
	// 							title: 'Nomor lot : ' + data['no_lot'] + ' telah di nyatakan digunakan produksi!'
	// 						});
	// 						$('#DataTable_Lot').DataTable().ajax.reload(null, false);
	// 						$('#DataTable_Deskripsi').DataTable().ajax.reload(null, false);
	// 					} else {
	// 						Swal.fire({
	// 							icon: 'error',
	// 							title: 'Oops...',
	// 							text: response.msg,
	// 							footer: '<a href="javascript:void(0)">Notifikasi System</a>'
	// 						});
	// 					}
	// 				},
	// 				error: function () {
	// 					Swal.close()
	// 					Swal.fire({
	// 						icon: 'error',
	// 						title: 'Oops...',
	// 						text: 'Terjadi kesalahan teknis segera lapor pada admin!',
	// 						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
	// 					});
	// 				}
	// 			});
	// 		}
	// 	})
	// })

	$('#DataTable_Lot tbody').on('dblclick', 'tr', function () {
		let RowData = $("#DataTable_Lot").DataTable().row($(this)).data();
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

})
