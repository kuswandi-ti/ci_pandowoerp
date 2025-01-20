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
			DataTable_Lot();
			DataTable_Desc();
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
			dom: 'lBfrtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			"aLengthMenu": [
				[500, 750, -1],
				[500, 750, 'ALL']
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/EntryKd/DataTable_HstOven_by_Lot",
				dataType: "json",
				type: "GET",
				data: {
					"from": $('#from').val(),
					"to": $('#to').val(),
					"oven": $('#oven').find(":selected").val(),
					"material": $('#material').find(":selected").val()
				}
			},
			columns: [{
				data: "sysid",
				name: "sysid",
				visible: true,
				orderable: false,
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			}, {
				data: "no_lot",
				name: "no_lot",
				render: function (data) {
					return `<a href="javascript:void(0)" class="detail--size"><u>${data}</u></a>`
				}
			}, {
				data: "kode",
				name: "kode",
			}, {
				data: "deskripsi",
				name: "deskripsi",
			}, {
				data: "supplier",
				name: "supplier",
			}, {
				data: "grader",
				name: "grader",
			}, {
				data: "tgl_kirim",
				name: "tgl_kirim",
			}, {
				data: "qty",
				name: "qty",
			}, {
				data: "kubikasi",
				name: "kubikasi",
				render: function (data) {
					return roundToFourDecimals(data)
				}
			}, {
				data: "nama_oven",
				name: "nama_oven"
			}, {
				data: "timer",
				name: "timer",
				orderable: false
			}, {
				data: "time_in",
				name: "time_in",
				render: function (data, type, row, meta) {
					return `<small class="badge badge-danger">${row.time_in.substring(0, 16)} </small>`;
				}
			}, {
				data: "time_out",
				name: "time_out",
				render: function (data, type, row, meta) {
					if (row.time_out) {
						return `<small class="badge badge-success"> ${row.time_out.substring(0, 16)}</small>`;
					} else {
						return null;
					}
				}
			}, {
				data: "status_kayu",
				name: "status_kayu",
				render: function (data, type, row, meta) {
					if (row.into_oven == 0) {
						return `<span class="badge badge-warning">${row.status_kayu}</span>`
					} else if (row.into_oven == 1) {
						return `<span class="badge badge-danger">${row.status_kayu}</span>`
					} else if (row.into_oven == 2) {
						return `<span class="badge badge-success">${row.status_kayu}</span>`
					} else if (row.into_oven == 3) {
						return `<span class="badge badge-primary">${row.status_kayu}</span>`
					} else {
						return `<span class="badge badge-info">${row.status_kayu}</span>`
					}
				}
			}],
			order: [
				[6, "asc"]
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
				}, "print"
			],
		}).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');
	}

	function DataTable_Desc() {
		$("#DataTable_Deskripsi").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			dom: 'lBfrtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			"aLengthMenu": [
				[500, 750, -1],
				[500, 750, 'ALL']
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/EntryKd/DataTable_HstOven_by_Desc",
				dataType: "json",
				type: "GET",
				data: {
					"from": $('#from').val(),
					"to": $('#to').val(),
					"oven": $('#oven').find(":selected").val(),
					"material": $('#material').find(":selected").val()
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
				data: "Size_Code",
				name: "Size_Code",
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
				render: function (data) {
					return roundToFourDecimals(data)
				}
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
				targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
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
			"buttons": [{
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
			}],
		}).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');
	}

	// ==================================== NEED TRIGGER ========================================== //

	$('#DataTable_Deskripsi tbody').on('click', 'button.btn-detail-lot', function () {
		let data = $('#DataTable_Deskripsi').DataTable().row($(this).parents('tr')).data();
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/EntryKd/M_Hst_Lot_By_Material",
			data: {
				sysid_material: data['sysid_material'],
				sysid_size: data['Id_Size_Item'],
				material: $('#material').find(":selected").val(),
				status: 1,
				title: 'HISTORY BUNDLE MASUK KILN',
				from: $('#from').val(),
				to: $('#to').val(),
				oven: $('#oven').find(":selected").val(),

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


})
