$(document).ready(function () {
	$.fn.editable.defaults.mode = 'inline';
	$.fn.editableform.buttons =
		'<button type="submit" class="btn btn-primary btn-xs editable-submit mt-1">' +
		'<i class="fa fa-fw fa-check"></i>' +
		'</button>&nbsp;' +
		'<button type="button" class="btn btn-warning btn-xs editable-cancel mt-1">' +
		'<i class="fa fa-fw fa-times"></i>' +
		'</button>';
	const Toast = Swal.mixin({
		toast: true,
		position: 'top',
		width: 300,
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.addEventListener('mouseenter', Swal.stopTimer)
			toast.addEventListener('mouseleave', Swal.resumeTimer)
		}
	})

	$('select[name="supplier"]').select2({
		minimumInputLength: 1,
		allowClear: true,
		placeholder: '-Pilih Supplier-',
		ajax: {
			dataType: 'json',
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/select_supplier",
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
			cache: true
		}
	})

	$('select[name="daerah"]').select2({
		minimumInputLength: 1,
		allowClear: true,
		placeholder: '-Pilih Asal-',
		ajax: {
			dataType: 'json',
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/select_daerah",
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
			cache: true
		}
	})

	$(".readonly").keydown(function (event) {
		return false;
	});

	$('.datepicker').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd',
		todayHighlight: true,
		orientation: 'bottom',
	});

	// =================== NEED TRIGGER


	$('#form_lpb').validate({
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

	$('#btn-send-approval').click(function (e) {
		e.preventDefault();
		if ($("#form_lpb").valid()) {
			let lpb = $(this).attr('data-pk');
			Swal.fire({
				title: 'System Message!',
				text: `Untuk mengirimkan ${lpb} ke proses approval ?`,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			}).then((result) => {
				if (result.isConfirmed) {
					Finish_Grid_initialize()
				}
			})
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	function Finish_Grid_initialize() {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/save_and_send_to_approval",
			data: $('#form_lpb').serialize(),
			beforeSend: function () {
				$(this).prop("disabled", true);
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
						title: 'Success!',
						text: response.msg,
						showConfirmButton: false,
						allowOutsideClick: false,
						allowEscapeKey: false
					})
					setTimeout(function () {
						window.location.href = $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/index";
					}, 2000);
				} else {
					Swal.fire({
						icon: 'info',
						title: 'Oops...',
						text: response.msg,
						showConfirmButton: true,
						allowOutsideClick: true,
						allowEscapeKey: true
					})
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

	$('#save--lpb').click(function (e) {
		e.preventDefault();
		if ($("#form_lpb").valid()) {
			$.ajax({
				dataType: "json",
				type: "POST",
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/update_lpb_still_buka",
				data: $('#form_lpb').serialize(),
				beforeSend: function () {
					$(this).prop("disabled", true);
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
							title: 'Success!',
							text: response.msg,
							showConfirmButton: false,
							allowOutsideClick: false,
							allowEscapeKey: false
						})
						setTimeout(function () {
							window.location.href = $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/index";
						}, 2000);
					} else {
						Swal.fire({
							icon: 'info',
							title: 'Oops...',
							text: response.msg,
							showConfirmButton: true,
							allowOutsideClick: true,
							allowEscapeKey: true
						})
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
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	$('#print-lot-number').on('click', function () {
		var lpb = $('#noLPB').val();
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/printAllLot",
			data: {
				lpb: lpb
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
				console.log(response);
				Swal.close()
				if (response.code == 200) {
					location.reload();
					window.open($('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/tempelan_lot_material/" + lpb, '_blank');
				} else {
					Swal.fire({
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

	$(document).on("select2:open", () => {
		document.querySelector(".select2-container--open .select2-search__field").focus()
	})

	$(document).on('click', '.add-row', function () {
		let LastNo = $('#tbl-lpb>tbody#main-tbody>tr.row-lot:last').find('td:eq(0)').html();
		let NewNo = parseInt(LastNo) + 1;
		let Lot = $('#noLPB').val() + '-' + NewNo;

		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/add_row_lpb_dtl",
			data: {
				lpb_hdr: $('#noLPB').val(),
				flag: NewNo,
				no_lot: Lot,
			},
			beforeSend: function () {
				$(this).prop("disabled", true);
				Swal.fire({
					title: 'Loading....',
					html: '<div class="spinner-border text-primary"></div>',
					showConfirmButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false
				})
			},
			success: function (response) {
				if (response.code == 200) {
					var Row = `<tr class="row-lot" data-pk="${response.id_lot}">
									<td class="nomor text-center align-middle">${NewNo}</td>
									<td class="lot text-center align-middle">${Lot}</td>
									<td class="ukuran text-center align-middle">
										<span class="form-group">
											<select class="form-control form-control-xs" required name="ukuran[]" data-pk="${response.id_lot}" style="width: 100%;">
												
											</select>
										</span>
									</td>
									<td class="text-center align-middle">
										<button type="button" data-pk="${response.id_lot}" title="belum print" class="btn btn-xs bg-gradient-danger print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>
									</td>
									<td class="text-center align-middle">
										<span class="form-group">
											<select class="form-control form-control-xs" required name="placement[]" data-pk="${response.id_lot}" style="width: 100%;">
											
											</select>
										</span>
                                    </td>
								</tr>
								<tr>
									<td colspan="6" class="bg-light">
										<table cellpadding="5" cellspacing="0" border="0" class="ml-4 my-2 table-mini" style="width: 70vh;">
											<tbody class="bg-white">
													<tr data-pk="${response.id_size}">
														<td class="text-center align-middle">1</td>
														<td class="align-middle text-center">
															<span class="form-group">
																<select class="form-control form-control-xs w-50" required name="ukuran-child[]" data-pk="${response.id_size}">
																</select>
															</span>
														</td>
														<td class="qty-child text-primary align-middle text-center w-25" data-pk="${response.id_size}">0</td>
													</tr>
											</tbody>
										</table>
										<div class="ml-4 my-2">
											<button type="button" class="btn btn-xs btn-success add-child" data-pk="${response.id_lot}">
												&nbsp;&nbsp;<i class="fas fa-plus"></i>&nbsp;&nbsp;
											</button> &nbsp;
											<button type="button" class="btn btn-xs btn-danger remove-child" data-pk="${response.id_lot}">
												&nbsp;&nbsp;<i class="fas fa-trash"></i>&nbsp;&nbsp;
											</button>
										</div>
									</td>
								</tr>`;
					$('#tbl-lpb #main-tbody').append(Row);
					select2_ukuran_kayu();
					// Init_editable_qty(); // racuk
					$(this).prop("disabled", false);
					select2_ukuran_child()
					Init_editable_child_qty()
					Swal.close()
					Initialize_select2_placement();
				}
			},
			error: function () {
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Terjadi kesalahan teknis segera lapor pada admin!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		});
	})

	$(document).on('click', '.remove-row', function () {
		var rowCount = $('#tbl-lpb tbody tr.row-lot').length;
		var sysid = $('#tbl-lpb>tbody#main-tbody>tr.row-lot:last').attr('data-pk');
		if (rowCount > 1) {
			$.ajax({
				dataType: "json",
				type: "POST",
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/delete_row_lpb_dtl",
				data: {
					sysid: sysid,
				},
				beforeSend: function () {
					$(this).prop("disabled", true);
					Swal.fire({
						title: 'Loading....',
						html: '<div class="spinner-border text-primary"></div>',
						showConfirmButton: false,
						allowOutsideClick: false,
						allowEscapeKey: false
					})
				},
				success: function (response) {
					if (response.code == 200) {
						$('#tbl-lpb>tbody>tr:last-child').remove();
						$('#tbl-lpb>tbody#main-tbody>tr.row-lot:last').remove()
						$(this).prop("disabled", false);
						Swal.close()
					}
				},
				error: function () {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Terjadi kesalahan teknis segera lapor pada admin!',
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
			});
		} else {
			Toast.fire({
				icon: 'error',
				title: 'Baris terakhir tidak dapat di hapus!'
			});
		}
	})

	$(document).on('click', '.add-child', function () {
		let tbody = $(this).closest('td').find('tbody');
		let sysid_lot = $(this).attr('data-pk');
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/add_row_size_lot",
			data: {
				sysid_lot: sysid_lot
			},
			beforeSend: function () {
				$(this).prop("disabled", true);
				$('#remove-child').prop("disabled", true);
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
					let newRow = '<tr data-pk="' + response.sysid_size + '">' +
						'<td class="text-center align-middle">' + response.flag + '</td>' +
						'<td class="align-middle text-center">' +
						'<span class="form-group">' +
						'<select class="form-control form-control-xs w-50" required name="ukuran-child[]" data-pk="' + response.sysid_size + '">' +
						'<option value="" selected>- Select Size -</option>' +
						'</select>' +
						'</span>' +
						'</td>' +
						'<td class="qty-child text-primary align-middle text-center w-25" data-pk="' + response.sysid_size + '">0</td>' +
						'</tr>';
					tbody.append(newRow);
					$(this).prop("disabled", false);
					$('#remove-child').prop("disabled", false);
					select2_ukuran_child();
					Init_editable_child_qty()
				}
			},
			error: function () {
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Terjadi kesalahan teknis segera lapor pada admin!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		});
	});

	$(document).on('click', '.remove-child', function () {
		var tbody = $(this).closest('td').find('tbody');
		var rows = tbody.find('tr');
		let LastRow = rows.last()
		let SysId = LastRow.attr('data-pk');
		if (rows.length > 1) {
			$.ajax({
				dataType: "json",
				type: "POST",
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/remove_row_size_lot",
				data: {
					SysId: SysId
				},
				beforeSend: function () {
					$(this).prop("disabled", true);
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
						rows.last().remove();
					}
					$(this).prop("disabled", false);
					$('#remove-child').prop("disabled", false);
				},
				error: function () {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Terjadi kesalahan teknis segera lapor pada admin!',
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
			});
		} else {
			// Jika hanya ada satu baris, mungkin Anda ingin menampilkan pesan atau melakukan tindakan lain
			Swal.fire({
				icon: 'warning',
				title: 'Oops...',
				text: 'Bundle minimal memiliki 1 jenis ukuran !',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
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
				})
			},
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					window.open($('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/tempelan_single_lot/" + sysid, '_blank');
					Parent.html(`<button type="button" data-pk="${sysid}" title="sudah print" class="btn btn-sm bg-gradient-success print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>`)
				} else if (response.code == 201) {
					window.open($('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/tempelan_single_lot/" + sysid, '_blank');
				} else {
					Swal.fire({
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

	// ========================= FUNCTION

	// function Init_editable_qty() {
	// 	$('.qty').editable({
	// 		ajaxOptions: {
	// 			dataType: 'json'
	// 		},
	// 		type: 'number',
	// 		url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/store_editable_qty",
	// 		title: 'Qty...',
	// 		validate: function (value) {
	// 			if ($.trim(value) == '') {
	// 				return Toast.fire({
	// 					icon: 'error',
	// 					title: 'Peringatan!',
	// 					text: 'Qty tidak boleh dikosongkan!'
	// 				});
	// 			}
	// 			if ($.trim(parseInt(value)) < 1) {
	// 				return Toast.fire({
	// 					icon: 'error',
	// 					title: 'Peringatan!',
	// 					text: 'minimum qantity 1 pcs!'
	// 				});
	// 			}
	// 		},
	// 		success: function (response, newValue) {
	// 			if (response.code == 200) {
	// 				Toast.fire({
	// 					icon: 'success',
	// 					title: response.msg
	// 				});
	// 				Fn_Recalculate_m3_Lpb();
	// 			} else {
	// 				Swal.fire({
	// 					icon: 'error',
	// 					title: 'Oops...',
	// 					text: 'Terjadi kesalahan teknis segera lapor pada admin!',
	// 					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
	// 				});
	// 			}
	// 		}
	// 	});
	// }

	// $(document).on('click', '.qty', function () { // kayu racuk
	// 	setTimeout(function () {
	// 		if (parseFloat($('input.form-control.input-mini').val()) == 0) {
	// 			$('input.form-control.input-mini').val('')
	// 		}
	// 	}, 250);
	// })

	function Fn_Recalculate_m3_Lpb() {
		let Lpb = $('#noLPB').val();

		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/Recalculate_cubication_lpb",
			data: {
				Lpb: Lpb,
			},
			success: function (response) {
				$('#TotKubikasi').val(response.kubikasi)
			},
			error: function () {
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Terjadi kesalahan teknis segera lapor pada admin!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		});
	}

	function select2_ukuran_kayu() {
		$('select[name="ukuran[]"]').select2({
			minimumInputLength: 0,
			// allowClear: true,
			placeholder: '-Pilih Item-',
			cache: true,
			ajax: {
				dataType: 'json',
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/select_item_grid",
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
		}).on('select2:select', function (evt) {
			let id_material = $(this).val();
			let sysid = $(this).attr('data-pk');
			// let anchor = $(this).closest('td').next('td');
			// anchor.empty();
			$.ajax({
				dataType: "json",
				type: "POST",
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/store_material_dtl_lpb",
				data: {
					sysid: sysid,
					sysid_material: id_material
				},
				success: function (response) {
					if (response.code == 200) {
						Toast.fire({
							icon: 'success',
							title: response.msg
						});
						// anchor.find('.qty').html(response.std_qty);
						// anchor.html(response.std_qty);
					} else if (response.code == 201) {
						Swal.fire({
							title: 'Loading....',
							html: '<div class="spinner-border text-primary"></div>',
							showConfirmButton: false,
							allowOutsideClick: false,
							allowEscapeKey: false
						})
						location.reload()
					} else {
						Toast.fire({
							icon: 'error',
							title: response.msg
						});
					}
				},
				error: function () {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Terjadi kesalahan teknis segera lapor pada admin!',
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
			});

		});
	}

	function Initialize_select2_placement() {
		$('select[name="placement[]"]').select2({
			minimumInputLength: 0,
			// allowClear: true,
			placeholder: '-Penempatan-',
			cache: true,
			ajax: {
				dataType: 'json',
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/select_placement_grid",
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
		}).on('select2:select', function (evt) {
			let placement = $(this).val();
			let sysid = $(this).attr('data-pk');
			$.ajax({
				dataType: "json",
				type: "POST",
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/store_editable_placement",
				data: {
					sysid: sysid,
					placement: placement
				},
				success: function (response) {
					if (response.code == 200) {
						Toast.fire({
							icon: 'success',
							title: response.msg
						});
					} else {
						Toast.fire({
							icon: 'error',
							title: response.msg
						});
					}
				},
				error: function () {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Terjadi kesalahan teknis segera lapor pada admin!',
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
			});

		});
	}

	function select2_ukuran_child() {
		$('select[name="ukuran-child[]"]').each(function () {
			// let sysid = $(this).attr('data-pk');
			$(this).select2({
				minimumInputLength: 2,
				// allowClear: true,
				placeholder: '-Ukuran Kayu-',
				cache: true,
				ajax: {
					dataType: 'json',
					url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/select_size_item",
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
			});
		}).on('select2:select', function (evt) {
			let size_id = $(this).val();
			let sysid = $(this).attr('data-pk');
			// anchor.empty();
			$.ajax({
				dataType: "json",
				type: "POST",
				url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/store_size_lot",
				data: {
					sysid: sysid,
					size_id: size_id
				},
				success: function (response) {
					if (response.code == 200) {
						Toast.fire({
							icon: 'success',
							title: response.msg
						});
						Fn_Recalculate_m3_Lpb()
					} else {
						Toast.fire({
							icon: 'error',
							title: response.msg
						});
					}
				},
				error: function () {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Terjadi kesalahan teknis segera lapor pada admin!',
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
			});
		});
	}

	// $(document).on('click', '.btn-send-approval', function () {
	// 	let lpb = $(this).attr('data-pk');
	// 	Swal.fire({
	// 		title: 'Apakah anda yakin ?',
	// 		text: `Untuk mengirimkan ${lpb} ke proses approval ?`,
	// 		icon: 'info',
	// 		showCancelButton: true,
	// 		confirmButtonColor: '#3085d6',
	// 		cancelButtonColor: '#d33',
	// 		confirmButtonText: 'Ya, Ajukan!',
	// 		cancelButtonText: 'Batal!'
	// 	}).then((result) => {
	// 		if (result.isConfirmed) {
	// 			$.ajax({
	// 				dataType: "json",
	// 				type: "POST",
	// 				url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/save_and_send_to_approval",
	// 				data: {
	// 					lpb: lpb
	// 				},
	// 				beforeSend: function () {
	// 					Swal.fire({
	// 						title: 'Loading....',
	// 						html: '<div class="spinner-border text-primary"></div>',
	// 						showConfirmButton: false,
	// 						allowOutsideClick: false,
	// 						allowEscapeKey: false
	// 					})
	// 				},
	// 				success: function (response) {
	// 					Swal.close()
	// 					if (response.code == 200) {
	// 						Swal.fire({
	// 							title: "System Message!",
	// 							text: response.msg,
	// 							icon: "success",
	// 							confirmButtonText: "Yes, Confirm!",
	// 						}).then((result) => {
	// 							if (result.isConfirmed) {
	// 								window.location.href =
	// 									$('meta[name="base_url"]').attr("content") + "TrxWh/ProcessGrid/index";
	// 							}
	// 						});

	// 					} else {
	// 						Swal.fire({
	// 							icon: 'warning',
	// 							title: 'Perhatian!!!',
	// 							text: response.msg,
	// 							footer: '<a href="javascript:void(0)">Notifikasi System</a>'
	// 						});
	// 					}
	// 				},
	// 				error: function () {
	// 					Swal.close()
	// 					Swal.fire({
	// 						icon: 'error',
	// 						title: 'Oops...',
	// 						text: 'Terjadi kesalahan teknis segera lapor pada admin!',
	// 						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
	// 					});
	// 				}
	// 			});
	// 		}
	// 	})
	// })

	function Init_editable_child_qty() {
		$('.qty-child').editable({
			ajaxOptions: {
				dataType: 'json'
			},
			type: 'number',
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/store_editable_child_qty",
			title: 'Qty...',
			validate: function (value) {
				if ($.trim(value) == '') {
					return Toast.fire({
						icon: 'error',
						title: 'Peringatan!',
						text: 'Qty tidak boleh dikosongkan!'
					});
				}
				if ($.trim(parseInt(value)) < 1) {
					return Toast.fire({
						icon: 'error',
						title: 'Peringatan!',
						text: 'minimum qantity 1 pcs!'
					});
				}
			},
			success: function (response, newValue) {
				if (response.code == 200) {
					Toast.fire({
						icon: 'success',
						title: response.msg
					});
					Fn_Recalculate_m3_Lpb();
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
	}

	$(document).on('click', '.qty-child', function () {
		setTimeout(function () {
			if (parseFloat($('input.form-control.input-mini').val()) == 0) {
				$('input.form-control.input-mini').val('')
			}
		}, 250);
	})

	Init_editable_child_qty()
	select2_ukuran_child()
	select2_ukuran_kayu();
	Initialize_select2_placement();
	// Init_editable_qty();
	Fn_Recalculate_m3_Lpb()

})
