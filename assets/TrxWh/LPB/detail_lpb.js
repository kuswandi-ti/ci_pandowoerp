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

	$.fn.editable.defaults.mode = 'inline';
	$.fn.editableform.buttons =
		'<button type="submit" class="btn btn-primary btn-sm editable-submit mt-1">' +
		'<i class="fa fa-fw fa-check"></i>' +
		'</button>&nbsp;&nbsp;' +
		'<button type="button" class="btn btn-warning btn-sm editable-cancel mt-1">' +
		'<i class="fa fa-fw fa-times"></i>' +
		'</button>';

	$('input').prop('disabled', true);
	$('textarea').prop('disabled', true);
	$('select').prop('disabled', true);

	var table = $("#tbl-dtl-lpb").DataTable({
		"responsive": true,
		"autoWidth": true,
		"ordering": false,
		"searching": false,
		dom: 'l<"row"<"col-6"f><"col-6 button-group-sm"B>>rtip',
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
			"targets": [3, 6],
			"render": $.fn.dataTable.render.number(',', '.', 2)
		}],
		"buttons": [{
				extend: 'excelHtml5',
				title: $('title').text(),
				className: "btn btn-sm btn-success",
			}, {
				extend: 'pdfHtml5',
				title: $('title').text(),
				className: "btn btn-sm btn-danger",
			}, {
				text: `<i class="fas fa-edit"></i> Ubah Harga Perukuran`,
				className: "btn btn-sm btn-warning float-right btn-harga",
			} //,{
			// text: `<i class="fas fa-plus"></i> LOT`,
			// className: "btn btn-sm btn-info float-right btn-add-lot",
			//}
		],
		"drawCallback": function (settings) {
			// Reinitialize any components inside the DataTable rows after draw
			$('.editable').editable();
		}
	}).buttons().container().appendTo('#tbl-dtl-lpb_wrapper .col-md-6:eq(0)');

	$(document).on('click', '.btn-harga', function () {
		$('#location').empty();
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/modal_form_edit_harga_lpb",
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
				});
			},
			success: function (response) {
				Swal.close();
				try {
					var jsonResponse = JSON.parse(response);
					if (jsonResponse.code == '505') {
						Swal.fire({
							icon: 'warning',
							title: 'Tidak Bisa Diedit',
							text: jsonResponse.msg,
							confirmButtonText: 'OK'
						});
					}
				} catch (e) {
					$('#location').html(response);
					$('#modal-update-harga-lpb').modal('show');
				}
			},
			error: function () {
				Swal.close();
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Terjadi kesalahan teknis segera lapor pada admin!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		});
	});

	$(document).on('click', '.print--lot', function () {
		let sysid = $(this).attr('data-pk');
		var Parent = $(this).parent();
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/update_Asprinted_single_lot",
			data: {
				sysid: sysid
			},
			beforeSend: function () {
				$(this).prop('disabled', true);
				Swal.fire({
					title: 'Loading....',
					html: '<div class="spinner-border text-primary"></div>',
					showConfirmButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false
				});
			},
			success: function (response) {
				Swal.close();
				if (response.code == 200) {
					window.open($('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/tempelan_single_lot/" + sysid, '_blank');
					Parent.html(`<button type="button" data-pk="${sysid}" title="sudah print" class="btn btn-sm bg-gradient-success print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>&nbsp;<a href="javascript:void(0)" data-toggle="tooltip" data-pk="${sysid}" class="btn btn-sm bg-gradient-danger btn-delete">&nbsp;<i class="fas fa-trash"></i>&nbsp;</a>`);
				} else if (response.code == 201) {
					return window.open($('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/tempelan_single_lot/" + sysid, '_blank');
				} else {
					return Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: response.msg,
						showConfirmButton: false,
						timer: 2500,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
			},
			error: function () {
				Swal.close();
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Terjadi kesalahan teknis segera lapor pada admin!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		});
	});

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
					url: $('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/delete_lot",
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
						});
					},
					success: function (response) {
						Swal.close();
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
						Swal.close();
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Terjadi kesalahan teknis segera lapor pada admin!',
							footer: '<a href="javascript:void(0)">Notifikasi System</a>'
						});
					}
				});
			}
		});
	});


	$('.editable_uang_bongkar').editable({
		ajaxOptions: {
			dataType: 'json'
		},
		type: 'number',
		url: $('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/store_editable_uang_bongkar",
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
				return Toast.fire({
					icon: 'success',
					title: 'Success!',
					text: 'Uang bongkar berhasil terupdate'
				});
				// location.reload();
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
		url: $('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/store_editable_kubikasi_pengiriman",
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
				return Toast.fire({
					icon: 'success',
					title: 'Success!',
					text: 'Jumlah kiriman berhasil terupdate'
				});
				// location.reload();
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
	});

	$('select[name="ukuran"]').select2({
		minimumInputLength: 0,
		placeholder: '-Pilih Item Raw Material Grade-',
		cache: true,
		ajax: {
			dataType: 'json',
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/select_item_grid",
			delay: 800,
			data: function (params) {
				return {
					search: params.term
				};
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
			}
		}
	});

	$('select#warehouse').prop('disabled', false);
	$('select[name="warehouse"]').select2({
		minimumInputLength: 0,
		allowClear: true,
		placeholder: '-Penempatan-',
		cache: true,
		ajax: {
			dataType: 'json',
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/select_placement_grid",
			delay: 800,
			data: function (params) {
				return {
					search: params.term
				};
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
			}
		}
	});

	$(document).on('click', 'a.detail--size', function () {
		let sysid = $(this).attr('data-pk');
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/StokBasah/modal_list_size_lot",
			data: {
				sysid: sysid,
			},
			beforeSend: function () {
				Swal.fire({
					title: 'Loading....',
					html: '<div class="spinner-border text-primary"></div>',
					showConfirmButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false
				});
			},
			success: function (response) {
				Swal.close();
				$('#location').html(response);
				$('#modal_detail_size_lot').modal('show');
			},
			error: function () {
				Swal.close();
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Terjadi kesalahan teknis segera lapor pada admin!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		});
	});
});
