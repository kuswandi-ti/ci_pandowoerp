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
			// DataTable_Deskripsi()
			DataTable_Lot()
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	function DataTable_Lot() {
		var DataTable_Lot = $("#DataTable_Lot").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			"aLengthMenu": [
				[500, 750, -1],
				[500, 750, 'ALL']
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/AllocationBundle/DataTable_alloc_prd_by_lot",
				dataType: "json",
				type: "GET",
				data: {
					"from": $('#from').val(),
					"to": $('#to').val(),
					"cc": $('#cc').find(":selected").val()
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
					render: function (data) {
						return `<a href="javascript:void(0)" class="detail--size"><u>${data}</u></a>`
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
					data: "supplier",
					name: "supplier",
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
					data: "tgl_finish_sortir",
					name: "tgl_finish_sortir",
					visible: false
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
					orderable: false,
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
					data: "cc_name",
					name: "cc_name"
				}, {
					data: "Harga_Lot",
					name: "Harga_Lot",

					orderable: false,
					render: function (data) {
						return formatIdr(data)
					}

				}
			],
			order: [
				[1, "asc"]
			],
			columnDefs: [{
					className: "text-center align-middle",
					targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
				},
				{
					className: "text-left",
					targets: []
				}
			],
			autoWidth: false,
			preDrawCallback: function () {
				$("#DataTable_Lot tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#DataTable_Lot tbody td").addClass("blurry");
				setTimeout(function () {
					$("#DataTable_Lot tbody td").removeClass("blurry");
				});
				$('[data-toggle="tooltip"]').tooltip();
			},
			"buttons": [{
				extend: 'csvHtml5',
				title: $('title').text() + '--' + $('#from').val() + ' sd ' + $('#to').val(),
				className: "btn btn-info",
			}, {
				extend: 'excelHtml5',
				title: $('title').text() + '--' + $('#from').val() + ' sd ' + $('#to').val(),
				className: "btn btn-success",
			}, {
				extend: 'pdfHtml5',
				title: $('title').text() + '--' + $('#from').val() + ' sd ' + $('#to').val(),
				className: "btn btn-danger",
			}],
		}).buttons().container().appendTo('#DataTable_Lot_wrapper .col-md-6:eq(0)');
	}

	// ============================================= SCRIPT NEED TRIGGER ==========================================//

	// $('#DataTable_Lot tbody').on('dblclick', 'tr', function () {
	// 	let RowData = $("#DataTable_Lot").DataTable().row($(this)).data();
	// 	$.ajax({
	// 		type: "GET",
	// 		url: $('meta[name="base_url"]').attr('content') + "TrxWh/AllocationBundle/dtl_HstDataLot",
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

	$('#DataTable_Lot tbody').on('click', 'a.detail--size', function () {
		let data = $('#DataTable_Lot').DataTable().row($(this).parents('tr')).data();

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

	DataTable_Lot()

})
