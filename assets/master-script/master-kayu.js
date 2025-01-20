$(document).ready(function () {

	$('#Tbl-material-kayu thead tr')
		.clone(true)
		.addClass('filters')
		.appendTo('#Tbl-material-kayu thead');

	var table = $("#Tbl-material-kayu").DataTable({
		"responsive": true,
		"lengthChange": true,
		"autoWidth": true,
		"select": true,
		// dom: 'Bfrtip',
		"oLanguage": {
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
		},
		orderCellsTop: true,
		fixedHeader: {
			header: true,
			headerOffset: 48
		},
		initComplete: function () {
			this.api().columns().every(function (index, val) {
				var column = this;
				var select = $('<select style="width: 100%;" class="select2 form-control form-control-sm form-control-border"><option value=""></option></select>')
					.appendTo($('.filters th:eq(' + index + ')').empty())
					.on('change', function () {
						var val = $.fn.dataTable.util.escapeRegex(
							$(this).val()
						);
						column
							.search(val ? '^' + val + '$' : '', true, false)
							.draw();
					});
				column.data().unique().sort().each(function (d, j) {
					select.append('<option value="' + d + '">' + d + '</option>')
				});
			});
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
	}).buttons().container().appendTo('#Tbl-material-kayu_wrapper .col-md-6:eq(0)');


	$('.select2').select2({
		tags: true,
		placeholder: "-Filter-",
		allowClear: true
	})

	// ================================== Script NEED TRIGGER

	$(document).on('click', '.is-active', function () {
		var this_is = $(this);
		Swal.fire({
			title: 'System Message!',
			text: `Apakah anda yakin untuk merubah status material ini ?`,
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
					url: $('meta[name="base_url"]').attr('content') + "Master/toggle_status_material",
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
								this_is.removeClass('bg-gradient-danger');
								this_is.addClass('bg-gradient-success');
								this_is.html(`<i class="fas fa-check-circle"></i>`);
							} else {
								this_is.removeClass('bg-gradient-success');
								this_is.addClass('bg-gradient-danger');
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

	$(document).on('click', '.btn-edit', function () {
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "Master/modal_edit_material_kayu",
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

				$('#location').html(response);
				$('#modal-edit-material-kayu').modal('show');
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

	$(document).on('click', '.history', function () {
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "Master/modal_price_history",
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

				$('#location').html(response);
				$('#modal-price-history').modal('show');
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
