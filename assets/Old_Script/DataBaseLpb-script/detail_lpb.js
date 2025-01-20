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

	$.fn.editable.defaults.mode = 'inline';
	$.fn.editableform.buttons =
		'<button type="submit" class="btn btn-primary btn-xs editable-submit mt-1">' +
		'<i class="fa fa-fw fa-check"></i> | Save' +
		'</button>&nbsp;&nbsp;' +
		'<button type="button" class="btn btn-warning btn-xs editable-cancel mt-1">' +
		'<i class="fa fa-fw fa-times"></i> | Cancel' +
		'</button>';

	$('input').prop('disabled', true)
	$('textarea').prop('disabled', true)
	$('select').prop('disabled', true)

	var table = $("#tbl-dtl-lpb").DataTable({
		"responsive": true,
		"lengthChange": true,
		"autoWidth": true,
		"ordering": false,
		"searching": false,
		"oLanguage": {
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
		},
		"pageLength": 500,
		"lengthChange": false,
		"columnDefs": [{
			"targets": [],
			"visible": false,
			"searchable": false
		}, {
			target: [3, 6],
			render: $.fn.dataTable.render.number(',', '.', 2)
		}],
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
			}, {
				extend: 'print',
				text: `<i class="fas fa-print"></i> Print`,
				className: "btn btn-dark",
			}, "colvis", {
				text: `<i class="fas fa-edit"></i> Ubah Harga Perukuran`,
				className: "btn btn-warning float-right btn-harga",
			}, {
				text: `<i class="fas fa-plus"></i> LOT`,
				className: "btn btn-info float-right btn-add-lot",
			}
		],
	}).buttons().container().appendTo('#tbl-dtl-lpb_wrapper .col-md-6:eq(0)');

	$(document).on('click', '.btn-harga', function () {
		$('#location').empty();
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "DatabaseLpb/modal_form_edit_harga_lpb",
			data: {
				lpb: $('#noLPB').val()
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
				$('#modal-update-harga-lpb').modal('show');
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

	$(document).on('click', '.btn-delete', function () {
		var sysid = $(this).attr('data-pk');
		var title_tooltip = $(this).attr("data-title");
		Swal.fire({
			title: `${title_tooltip} ?`,
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
					url: $('meta[name="base_url"]').attr('content') + "DatabaseLpb/delete_lot",
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
							$('#' + response.id).remove();
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

	$('.editable_price').editable({
		ajaxOptions: {
			dataType: 'json'
		},
		type: 'number',
		url: $('meta[name="base_url"]').attr('content') + "DatabaseLpb/store_editable_lot_price",
		title: 'price...',
		validate: function (value) {
			if ($.trim(value) == '') {
				return Toast.fire({
					icon: 'error',
					title: 'Peringatan!',
					text: 'Harga tidak boleh dikosongkan!'
				});
			}
			if ($.trim(value) == undefined) {
				return Toast.fire({
					icon: 'error',
					title: 'Peringatan!',
					text: 'Harga tidak valid!'
				});
			}
		},
		success: function (response, newValue) {
			if (response.code == 200) {
				Swal.fire({
					title: 'Loading....',
					html: '<div class="spinner-border text-primary"></div>',
					showConfirmButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false
				})
				location.reload();
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Terjadi kesalahan teknis segera lapor pada admin!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		}
	});
	$('.editable_uang_bongkar').editable({
		ajaxOptions: {
			dataType: 'json'
		},
		type: 'number',
		url: $('meta[name="base_url"]').attr('content') + "DatabaseLpb/store_editable_uang_bongkar",
		title: 'Uang Bongkar/Kubik...',
		validate: function (value) {
			if ($.trim(value) == '') {
				return Toast.fire({
					icon: 'error',
					title: 'Peringatan!',
					text: 'Uang bongkar/kubik tidak boleh dikosongkan!'
				});
			}
			if ($.trim(value) == undefined) {
				return Toast.fire({
					icon: 'error',
					title: 'Peringatan!',
					text: 'Uang bongkar/kubik tidak valid!'
				});
			}
		},
		success: function (response, newValue) {
			if (response.code == 200) {
				Swal.fire({
					title: 'Loading....',
					html: '<div class="spinner-border text-primary"></div>',
					showConfirmButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false
				})
				location.reload();
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Terjadi kesalahan teknis segera lapor pada admin!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		}
	});
	$('.editable_m3_kirim').editable({
		ajaxOptions: {
			dataType: 'json'
		},
		type: 'number',
		url: $('meta[name="base_url"]').attr('content') + "DatabaseLpb/store_editable_kubikasi_pengiriman",
		title: 'Total m3 pengiriman...',
		validate: function (value) {
			if ($.trim(value) == '') {
				return Toast.fire({
					icon: 'error',
					title: 'Peringatan!',
					text: 'Total m3 pengiriman tidak boleh dikosongkan!'
				});
			}
			if ($.trim(value) == undefined) {
				return Toast.fire({
					icon: 'error',
					title: 'Peringatan!',
					text: 'Total m3 pengiriman tidak valid!'
				});
			}
		},
		success: function (response, newValue) {
			if (response.code == 200) {
				Swal.fire({
					title: 'Loading....',
					html: '<div class="spinner-border text-primary"></div>',
					showConfirmButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false
				})
				location.reload();
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Terjadi kesalahan teknis segera lapor pada admin!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		}
	});

	$('.btn-add-lot').click(function () {
		$('#modal-add-lot').modal('show');
		$('#Qty').prop('disabled', false);
		$('#ukuran').prop('disabled', false);
		$('#lpb').prop('disabled', false);
		$('#sysid_hdr_').prop('disabled', false);
	})

	$('select[name="ukuran"]').select2({
		minimumInputLength: 0,
		// allowClear: true,
		placeholder: '-Ukuran Kayu-',
		cache: true,
		ajax: {
			dataType: 'json',
			url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/select_material_kayu",
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
		}
	})
})
