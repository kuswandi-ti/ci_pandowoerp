$(document).ready(function () {
	$.fn.editable.defaults.mode = 'inline';
	$.fn.editableform.buttons =
		'<button type="submit" class="btn btn-primary btn-xs editable-submit mt-1">' +
		'<i class="fa fa-fw fa-check"></i> | Save' +
		'</button>&nbsp;&nbsp;' +
		'<button type="button" class="btn btn-warning btn-xs editable-cancel mt-1">' +
		'<i class="fa fa-fw fa-times"></i> | Cancel' +
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
			url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/select_supplier",
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
			url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/select_daerah",
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

	$('#finish--lpb').click(function (e) {
		e.preventDefault();
		if ($("#form_lpb").valid()) {
			Swal.fire({
				title: 'System Message!',
				text: `LPB ini akan dinyatakan selesai ?`,
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
			url: $('meta[name="base_url"]').attr('content') + "CheckGrading/update_lpb_as_selesai",
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
						window.location.href = $('meta[name="base_url"]').attr('content') + "TodayGrading";
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
				url: $('meta[name="base_url"]').attr('content') + "CheckGrading/update_lpb_still_buka",
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
							window.location.href = $('meta[name="base_url"]').attr('content') + "CheckGrading";
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
			url: $('meta[name="base_url"]').attr('content') + "CheckGrading/printAllLot",
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
					window.open($('meta[name="base_url"]').attr('content') + "DatabaseLpb/tempelan_lot_material/" + lpb, '_blank');
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

	$('#add-row').on('click', function () {
		let LastNo = $('#tbl-lpb>tbody>tr:last-child').find('td:eq(0)').html();
		let NewNo = parseInt(LastNo) + 1;
		let Lot = $('#noLPB').val() + '-' + NewNo;

		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/add_row_lpb_dtl",
			data: {
				lpb_hdr: $('#noLPB').val(),
				flag: NewNo,
				no_lot: Lot,
			},
			beforeSend: function () {
				$(this).prop("disabled", true);
				$('#remove-row').prop("disabled", true);
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
					var Row = `<tr class="default-row" data-pk="${response.sysid}">
                    <td align="center" class="nomor">${NewNo}</td>
                    <td align="center" class="lot">${Lot}</td>
                    <td align="center" class="ukuran">
                        <span class="form-group">
                            <select class="form-control form-control-xs" required name="ukuran[]" data-pk="${response.sysid}" style="width: 100%;"></select>
                        </span>
                    </td>
                    <td align="center" class="qty" data-pk="${response.sysid}">${response.qty}</td>
                    <td align="center"><button type="button" data-pk="${response.sysid}" title="belum print" class="btn btn-sm bg-gradient-danger print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button></td>
                </tr>`;
					$('#tbl-lpb tbody').append(Row);
					select2_ukuran_kayu();
					Init_editable_qty();
					$(this).prop("disabled", false);
					$('#remove-row').prop("disabled", false);
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
	})

	$('#remove-row').on('click', function () {
		var rowCount = $('#tbl-lpb tbody tr').length;
		var sysid = $('#tbl-lpb>tbody>tr:last-child').attr('data-pk');
		if (rowCount > 1) {
			$.ajax({
				dataType: "json",
				type: "POST",
				url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/delete_row_lpb_dtl",
				data: {
					sysid: sysid,
				},
				beforeSend: function () {
					$(this).prop("disabled", true);
					$('#add-row').prop("disabled", true);
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
						$(this).prop("disabled", false);
						$('#add-row').prop("disabled", false);
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
	$('#add-row-btm').on('click', function () {
		let LastNo = $('#tbl-lpb>tbody>tr:last-child').find('td:eq(0)').html();
		let NewNo = parseInt(LastNo) + 1;
		let Lot = $('#noLPB').val() + '-' + NewNo;

		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/add_row_lpb_dtl",
			data: {
				lpb_hdr: $('#noLPB').val(),
				flag: NewNo,
				no_lot: Lot,
			},
			beforeSend: function () {
				$(this).prop("disabled", true);
				$('#remove-row').prop("disabled", true);
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
					var Row = `<tr class="default-row" data-pk="${response.sysid}">
                    <td align="center" class="nomor">${NewNo}</td>
                    <td align="center" class="lot">${Lot}</td>
                    <td align="center" class="ukuran">
                        <span class="form-group">
                            <select class="form-control form-control-xs" required name="ukuran[]" data-pk="${response.sysid}" style="width: 100%;"></select>
                        </span>
                    </td>
                    <td align="center" class="qty" data-pk="${response.sysid}">${response.qty}</td>
                    <td align="center"><button type="button" data-pk="${response.sysid}" title="belum print" class="btn btn-sm bg-gradient-danger print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button></td>
                </tr>`;
					$('#tbl-lpb tbody').append(Row);
					select2_ukuran_kayu();
					Init_editable_qty();
					$(this).prop("disabled", false);
					$('#remove-row').prop("disabled", false);
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
	})

	$('#remove-row-btm').on('click', function () {
		var rowCount = $('#tbl-lpb tbody tr').length;
		var sysid = $('#tbl-lpb>tbody>tr:last-child').attr('data-pk');
		if (rowCount > 1) {
			$.ajax({
				dataType: "json",
				type: "POST",
				url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/delete_row_lpb_dtl",
				data: {
					sysid: sysid,
				},
				beforeSend: function () {
					$(this).prop("disabled", true);
					$('#add-row').prop("disabled", true);
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
						$(this).prop("disabled", false);
						$('#add-row').prop("disabled", false);
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

	$(document).on('click', '.print--lot', function () {
		let sysid = $(this).attr('data-pk');
		var Parent = $(this).parent();
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "CheckGrading/update_Asprinted_single_lot",
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
					window.open($('meta[name="base_url"]').attr('content') + "DatabaseLpb/tempelan_single_lot/" + sysid, '_blank');
					Parent.html(`<button type="button" data-pk="${sysid}" title="sudah print" class="btn btn-sm bg-gradient-success print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>`)
				} else if (response.code == 201) {
					window.open($('meta[name="base_url"]').attr('content') + "DatabaseLpb/tempelan_single_lot/" + sysid, '_blank');
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

	function Init_editable_qty() {
		$('.qty').editable({
			ajaxOptions: {
				dataType: 'json'
			},
			type: 'number',
			url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/store_editable_qty",
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
					console.log(response)
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

	function select2_ukuran_kayu() {
		$('select[name="ukuran[]"]').select2({
			minimumInputLength: 1,
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
		}).on('select2:select', function (evt) {
			let id_material = $(this).val();
			let sysid = $(this).attr('data-pk');
			let anchor = $(this).closest('td').next('td');
			anchor.empty();
			$.ajax({
				dataType: "json",
				type: "POST",
				url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/store_editable_supplier",
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
						anchor.html(response.std_qty);
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
				url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/select_placement_basah",
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
				url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/store_editable_placement",
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

	select2_ukuran_kayu();
	Initialize_select2_placement();
	Init_editable_qty();
})
