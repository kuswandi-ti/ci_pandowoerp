$(document).ready(function () {

	$('#customer').select2();

	var table = $("#tbl-master").DataTable({
		// "responsive": true,
		"lengthChange": true,
		"autoWidth": true,
		"oLanguage": {
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
		},
		orderCellsTop: true,
		fixedHeader: {
			header: true,
			headerOffset: 48
		},
		"buttons": ["copy",
			{
				extend: 'csvHtml5',
				title: $('title').text(),
				className: "btn btn-info",
			}, {
				extend: 'excelHtml5',
				title: $('title').text(),
				className: "btn btn-success",
			}, {
				extend: 'pdfHtml5',
				title: $('title').text(),
				className: "btn btn-danger",
			}, "print", "colvis"
		],
	}).buttons().container().appendTo('#tbl-master_wrapper .col-md-6:eq(0)');

	$(document).on('click', '.is-active', function () {
		var this_is = $(this);
		Swal.fire({
			title: 'System Message!',
			text: `Apakah anda yakin untuk merubah status product ini ?`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "Master/toggle_status_product",
					data: {
						sysid: $(this).attr('data-pk')
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
							Swal.fire({
								icon: 'success',
								title: 'Success...',
								text: response.msg,
								footer: '<a href="javascript:void(0)" class="text-info">Notifikasi System</a>'
							});

							if (response.is_active == 1) {
								this_is.removeClass('bg-gradient-secondary');
								this_is.addClass('bg-gradient-success');
								this_is.html(`<i class="fas fa-check-circle"></i>`);
							} else {
								this_is.removeClass('bg-gradient-success');
								this_is.addClass('bg-gradient-secondary');
								this_is.html(`<i class="fas fa-times-circle"></i>`);
							}

						} else {
							Swal.fire({
								icon: 'warning',
								title: 'Warning!',
								text: response.msg,
								footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
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

	$("#tbl-master").on('click', 'tbody .btn-edit', function () {
		let sysid = $(this).attr('data-pk');
		$('#location').empty();
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "Master/popup_form_edit_hdr_product",
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

				$('#location').html(response);
				$('#modal-edit').modal('show');
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

	$("#tbl-master").on('click', 'tbody .btn-detail', function () {
		let sysid = $(this).attr('data-pk');
		$('#location').empty();
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "Master/popup_form_structure_hdr_product",
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

				$('#location').html(response);
				$('#modal-detail').modal('show');
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

	$("#tbl-master").on('click', 'tbody .btn-list', function () {
		let sysid = $(this).attr('data-pk');
		$('#location').empty();
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "Master/list_struktur_product",
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

				$('#location').html(response);
				$('#modal-list').modal('show');
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

	$("#tbl-master").on('click', 'tbody .btn-delete', function () {
		var sysid = $(this).attr('data-pk');
		Swal.fire({
			title: 'Hapus data ?',
			text: "data yang sudah dihapus tidak dapat dikembalikan!",
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
					url: $('meta[name="base_url"]').attr('content') + "Master/delete_mst_product",
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
							Swal.fire({
								icon: 'success',
								title: 'Deleted...',
								text: response.msg,
								footer: '<a href="javascript:void(0)">Notifikasi System</a>'
							});
							location.reload()
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Not Deleted...',
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
			}
		})
	});
})
