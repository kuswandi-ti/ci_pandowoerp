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
			url: $('meta[name="base_url"]').attr('content') + "StockMtrlKering/DataTable_Stock_material_kering_by_deskripsi",
			dataType: "json",
			type: "GET",
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
				orientation: 'landscape',
				customize: function (doc) {
					doc.defaultStyle.fontSize = 8; //<-- set fontsize to 16 instead of 10 
				}
			}, "print"
		],
	}).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');

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
			url: $('meta[name="base_url"]').attr('content') + "StockMtrlKering/DataTable_Stock_material_kering_by_lot",
			dataType: "json",
			type: "GET",
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
					return `<small class="badge badge-danger">${row.time_in.substring(0, 16)} </small><br /> S/D <br />` +
						`<small class="badge badge-info"> ${row.time_out.substring(0, 16)}</small>`
				}
			}, {
				data: "timer",
				name: "timer",
				render: function (data, type, row, meta) {
					return `<span class="badge badge-default">${row.timer}</span>`
				}
			}, {
				data: "lokasi",
				name: "lokasi",
			}, {
				data: null,
				name: "action_manual_open",
				orderable: false,
				render: function (data, type, row, meta) {
					return `<button class="btn btn-xs bg-gradient-purple btn-to-prd" title="Redirect to Production!"> &nbsp;<i class="fas fa-pallet"></i>&nbsp; </button>`
				}
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
				orientation: 'landscape',
			}, "print"
		],
	}).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');


	// ============================================= SCRIPT NEED TRIGGER ==========================================//

	$('#DataTable_Deskripsi tbody').on('click', 'button.btn-detail-lot', function () {
		let data = $('#DataTable_Deskripsi').DataTable().row($(this).parents('tr')).data();
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "DatabaseLot/modal_list_lot_by_deskripsi_kering",
			data: {
				sysid_material: data['sysid_material'],
				status: 2,
				title: 'KAYU KERING'
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

	let inputOptionsPromise = new Promise(function (resolve) {
		$.getJSON($('meta[name="base_url"]').attr('content') + "Master/List_Product_Active", function (data) {
			resolve(data)
		});
	})

	$('#DataTable_Lot tbody').on('click', 'button.btn-to-prd', function () {
		let data = $('#DataTable_Lot').DataTable().row($(this).parents('tr')).data();

		Swal.fire({
			title: '-Pilih Product-',
			input: 'select',
			inputOptions: inputOptionsPromise,
			inputPlaceholder: 'Pilih Product',
			showCancelButton: true,
		}).then((result) => {
			if (result.value) {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "StockMtrlKering/use_to_prd_manual",
					data: {
						barcode: data['no_lot'],
						product: result.value
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
							Toast.fire({
								icon: 'success',
								title: 'Nomor lot : ' + data['no_lot'] + ' telah di nyatakan digunakan produksi!'
							});
							$('#DataTable_Lot').DataTable().ajax.reload(null, false);
							$('#DataTable_Deskripsi').DataTable().ajax.reload(null, false);
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: response.msg,
								footer: '<a href="javascript:void(0)">Notifikasi System</a>'
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
			} else if (result.value != "") {
				console.log('button cancel clicked !')
			} else {
				Swal.fire('Harap pilih product !')
			}
		});
	})

})
