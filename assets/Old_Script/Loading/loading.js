$(document).ready(function () {
	// $('#container-list-barcode').hide();
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

	setTimeout(
		function () {
			$('.alert').hide();
		}, 3000);

	// setInterval(function () {
	// 	$('#no_barcode').focus()
	// }, 2000);

	$('#preview--data').on('click', function () {
		let barcode = $('#no_barcode').val();
		if (barcode == '' || barcode == undefined) {
			return Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'Scan/Input nomor barcode!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		} else {
			if (barcode.length < 16) {
				return Swal.fire({
					icon: 'error',
					title: 'Warning!',
					text: 'Panjang Karakter barcode kurang dari 16 Karakter!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			} else {
				$.ajax({
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "Loading/preview_detail_data_barcode",
					data: {
						barcode: barcode,
					},
					beforeSend: function () {
						Swal.fire({
							title: 'Loading....',
							html: '<div class="spinner-border text-primary"></div>',
							showConfirmButton: false,
							allowOutsideClick: false,
							allowEscapeKey: false
						})
						$(this).prop('disabled', true)
					},
					success: function (response) {
						Swal.close()
						$('#location').html(response);
						$('#modal-detail-barcode').modal('show');
						$(this).prop('disabled', false)
					},
					error: function (xhr, textStatus, error) {
						Swal.close()
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: error,
							footer: '<a href="javascript:void(0)">Notifikasi System</a>'
						});
					}
				});
			}
		}
	})

	// 
	var Tbl_Tmp_Loading = $("#Tbl_Tmp_Loading").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		paging: true,
		orderCellsTop: true,
		"searching": false,
		"lengthMenu": [
			[1000],
			[1000]
		],
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "Loading/DT_ttmp_loading",
			dataType: "json",
			type: "POST",
			data: {
				no_loading: $('#no_loading').text(),
			}
		},
		columns: [{
				data: "SysId",
				name: "SysId",
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: "Barcode_Value",
				name: "Barcode_Value",
			},
			{
				data: "do_at",
				name: "do_at",
			},
			{
				data: "do_by",
				name: "do_by",
			},
			{
				data: "SysId",
				name: "handle",
				render: function (data, type, row, meta) {
					return `<button class="btn btn-sm bg-gradient-danger btn-delete" data-pk="${row.SysId}"><i class="fas fa-trash"></i></button>`;
				}
			}
		],
		order: [
			[0, 'DESC']
		],
		columnDefs: [{
			className: "align-middle text-center",
			targets: [0, 1, 2, 3, 4],
		}],
		// autoWidth: false,
		responsive: true,
		preDrawCallback: function () {
			$("#Tbl_Tmp_Loading tbody td").addClass("blurry");
		},
		language: {
			processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
		},
		drawCallback: function () {
			$("#Tbl_Tmp_Loading tbody td").addClass("blurry");
			setTimeout(function () {
				$("#Tbl_Tmp_Loading tbody td").removeClass("blurry");
			});
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	$('#finish--loading').on('click', function () {
		let no_loading = $('#no_loading').text();
		Swal.fire({
			title: 'System Message!',
			text: `Apakah anda yakin ingin menyatakan selesai no.loading : ${no_loading} ?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes'
		}).then((result) => {
			if (result.isConfirmed) {
				Fn_Selesai_loading(no_loading)
			}
		})
	})

	function Fn_Selesai_loading(no_loding) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Loading/Selesai_Loading",
			data: {
				no_loading: no_loding,
				silang_product: $('#silang_product').val()
			},
			beforeSend: function () {
				Swal.fire({
					title: 'Loading....',
					html: '<div class="spinner-border text-primary"></div>',
					showConfirmButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false
				})
				$(this).prop('disabled', true)
			},
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					Swal.fire({
						icon: 'success',
						title: 'Success...',
						text: response.msg,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
					setTimeout(
						function () {
							location.href = $('meta[name="base_url"]').attr('content') + "Loading"
						}, 2500);
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: response.msg,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
			},
			error: function (xhr, textStatus, error) {
				Swal.close()
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: error,
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		});
	}


	$('#no_barcode').keypress(function (event) {
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if (keycode == '13') {
			let barcode = $('#no_barcode').val();
			Fn_Init_Store_Barcode(barcode)
		}
	});

	$('#submit--barcode').on('click', function () {
		let barcode = $('#no_barcode').val();
		Fn_Init_Store_Barcode(barcode)
	})

	function Fn_Init_Store_Barcode(barcode) {
		if (barcode == '' || barcode == undefined) {
			return Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'Scan/Input nomor barcode!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
		if (barcode.length < 16) {
			return Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'Panjang Karakter barcode kurang dari 16 Karakter!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
		if (barcode.length > 18) {
			return Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'Panjang Karakter barcode Lebih dari 18 Karakter!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Loading/Store_Barcode_Loading",
			data: {
				barcode: barcode,
				no_loading: $('#no_loading').text(),
				silang_product: $('#silang_product').val()
			},
			beforeSend: function () {
				Swal.fire({
					title: 'Loading....',
					html: '<div class="spinner-border text-primary"></div>',
					showConfirmButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false
				})
				$(this).prop('disabled', true)
			},
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					Tbl_Tmp_Loading.ajax.reload();
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: response.msg,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
				$('#no_barcode').val('');
				$('#no_barcode').focus()
			},
			error: function (xhr, textStatus, error) {
				Swal.close()
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: error,
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		});
	}

	$(document).on('click', '.btn-delete', function () {
		let SysId = $(this).attr("data-pk");
		Swal.fire({
			title: 'System Message!',
			text: `Apakah anda yakin ingin menghapus data loading ini ?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "Loading/Delete_TTmp_Barcode",
					data: {
						SysId: SysId
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
							Tbl_Tmp_Loading.ajax.reload(null, false)
						} else {
							Toast.fire({
								icon: 'error',
								title: response.msg
							});
						}
					},
					error: function (xhr, textStatus, error) {
						Swal.close()
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: error,
							footer: '<a href="javascript:void(0)">Notifikasi System</a>'
						});
					}
				});
			}
		})
	})

	$('.editable_qty_loading').editable({
		ajaxOptions: {
			dataType: 'json'
		},
		type: 'number',
		url: $('meta[name="base_url"]').attr('content') + "Loading/edit_qty_loading",
		title: 'Qty Loading...',
		validate: function (value) {
			if ($.trim(value) == '') {
				return Toast.fire({
					icon: 'error',
					title: 'Peringatan!',
					text: 'Qty Loading tidak boleh dikosongkan!'
				});
			}
			if ($.trim(value) == undefined) {
				return Toast.fire({
					icon: 'error',
					title: 'Peringatan!',
					text: 'Qty Loading pengiriman tidak valid!'
				});
			}
		},
		success: function (response, newValue) {
			if (response.code == 200) {
				Swal.fire({
					icon: 'success',
					title: 'Successfully!',
					text: response.msg,
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
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


	// function Init_Check_in_Ttmp() {
	// 	$.ajax({
	// 		dataType: "json",
	// 		type: "POST",
	// 		url: $('meta[name="base_url"]').attr('content') + "Loading/Check_in_TTmp",
	// 		data: {
	// 			no_loading: $('#no_loading').text(),
	// 		},
	// 		beforeSend: function () {
	// 			Swal.fire({
	// 				title: 'Loading....',
	// 				html: '<div class="spinner-border text-primary"></div>',
	// 				showConfirmButton: false,
	// 				allowOutsideClick: false,
	// 				allowEscapeKey: false
	// 			})
	// 			$(this).prop('disabled', true)
	// 		},
	// 		success: function (response) {
	// 			Swal.close()
	// 			if (response.code == 200) {
	// 				$('#container-list-barcode').show("slide", {
	// 					direction: "left"
	// 				}, 800);
	// 			}
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
	// }


	// Init_Check_in_Ttmp();
})
