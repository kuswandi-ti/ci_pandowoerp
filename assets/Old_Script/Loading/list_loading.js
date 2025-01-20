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
	});

	$("#tbl-loading-on-going").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		paging: true,
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
			[15, 30, 90, 1000],
			[15, 30, 90, 1000]
		],
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "Loading/DT_On_Going_Loading",
			dataType: "json",
			type: "POST",
		},
		columns: [{
				data: "SysId",
				name: "SysId",
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: "No_loading",
				name: "No_loading",
			},
			{
				data: "Customer_Name",
				name: "Customer_Name",
			},
			{
				data: "Nama",
				name: "Nama",
			},
			{
				data: "Qty_Loading",
				name: "Qty_Loading",
			},
			{
				data: "Created_by",
				name: "Created_by",
			},
			{
				data: "STATUS",
				name: "STATUS",
				render: function (data, type, row, meta) {
					return `<button class="btn btn-sm bg-gradient-warning blink_me">LOADING</button>`;
				}
			},
			{
				data: "SysId",
				name: "handle",
				render: function (data, type, row, meta) {
					return `<a class="btn btn-sm btn-success btn-continue" data-toggle="tooltip" title="Lanjutkan Loading" href="Loading/loading_product/${row.No_loading}"><i class="fas fa-truck-loading"></i></a>&nbsp;` +
						`<button class="btn btn-sm btn-danger btn-delete" data-toggle="tooltip" title="Hapus Loading" data-pk="${row.SysId}"><i class="fas fa-trash-alt"></i></button>&nbsp;`;
				}
			},
		],
		order: [
			[0, 'DESC']
		],
		columnDefs: [{
			className: "align-middle text-center",
			targets: [0, 1, 2, 3, 5, 6, 7],
		}],
		// autoWidth: false,
		responsive: true,
		preDrawCallback: function () {
			$("#Tbl-FinishGood tbody td").addClass("blurry");
		},
		language: {
			processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
		},
		drawCallback: function () {
			$("#Tbl-FinishGood tbody td").addClass("blurry");
			setTimeout(function () {
				$("#Tbl-FinishGood tbody td").removeClass("blurry");
			});
			$('[data-toggle="tooltip"]').tooltip();
		},
		"buttons": ["copy",
			{
				extend: 'csvHtml5',
				title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
				className: "btn btn-info",
			}, {
				extend: 'excelHtml5',
				title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
				className: "btn btn-success",
			}, {
				extend: 'pdfHtml5',
				title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
				className: "btn btn-danger",
				orientation: "landscape"
			}, "print"
		],
	}).buttons().container().appendTo('#Tbl-FinishGood .col-md-6:eq(0)');

	$(document).on('click', '.btn-delete', function () {
		var sysid = $(this).attr('data-pk');
		Swal.fire({
			title: 'Hapus data ?',
			text: "Data yang sudah dihapus tidak dapat dikembalikan!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Hapus!',
			cancelButtonText: 'Batal!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "Loading/delete_loading",
					data: {
						sysid: sysid
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
								title: response.msg
							});
							$("#tbl-loading-on-going").DataTable().ajax.reload(null, false)
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
		})
	})

});
