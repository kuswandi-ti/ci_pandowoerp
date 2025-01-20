$(document).ready(function () {

	var table = $("#tbl-master-karyawan").DataTable({
		"responsive": true,
		"lengthChange": true,
		"autoWidth": true,
		"select": true,
		"oLanguage": {
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
		},
		orderCellsTop: true,
		fixedHeader: {
			header: true,
			headerOffset: 48
		},
		columnDefs: [{
			className: "text-center",
			targets: [0, 1, 2, 3, 4],
		}],
	})

	$('.select2').select2({
		tags: true,
		placeholder: "-Filter-",
		allowClear: true
	})

	// ================================== Script NEED TRIGGER

	$(document).on('click', '.btn-delete', function () {
		var this_is = $(this);
		Swal.fire({
			title: 'System Message!',
			text: `Apakah anda yakin untuk menghapus authority checker ini ?`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Hapus!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "Master/delete_authority_checker",
					data: {
						nik: $(this).attr('data-pk')
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
							table
								.row(this_is.parents('tr'))
								.remove()
								.draw();
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

	$('#btn--add').on('click', function () {
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "Master/modal_list_karyawan",
			data: {
				action: $(this).attr('data-pk')
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
				$('#modal-list-karyawan').modal('show');
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
