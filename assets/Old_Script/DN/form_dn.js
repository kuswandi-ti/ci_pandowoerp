$(document).ready(function () {
	$('.datepicker').datetimepicker({
		format: 'YYYY-MM-DD',
		autoclose: true,
		allowClear: true,
		todayHighlight: true,
		orientation: 'bottom',
	});

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

	$('#btn--customer').on('click', function () {
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "Master/M_List_Customer",
			// data: {
			// 	sysid: $('#customer').val()
			// },
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
				$('#location-modal-customer').html(response);
				$('#modal-list-customer').modal('show');
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

	$('#btn--po').on('click', function () {
		if ($('#customer_code').val() == null || $('#customer_code').val() == '') {
			return Toast.fire({
				icon: 'error',
				title: "You must select customer first !"
			});
		}
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "SalesOrder/M_List_SO_Outstanding_Customer_Pick",
			data: {
				customer_code: $('#customer_code').val()
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
				$('#location-modal-po').html(response);
				$('#modal-list-po').modal('show');
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

	$('#btn--kendaraan').on('click', function () {
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "Master/M_List_kendaraan",
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
				$('#location-modal-vehicle').html(response);
				$('#modal-list-kendaraan').modal('show');
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

	$('#btn--driver').on('click', function () {
		if ($('#no_kendaraan').val() == null || $('#no_kendaraan').val() == '') {
			return Toast.fire({
				icon: 'error',
				title: "You must select vehicle first !"
			});
		}
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "Master/M_List_Supir",
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
				$('#location-modal-driver').html(response);
				$('#modal-list-driver').modal('show');
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

	$('#form_hdr_dn').validate({
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

	$('#generate--dn').click(function (e) {
		e.preventDefault();
		let po_number = $('#no_po_customer').val();
		if ($("#form_hdr_dn").valid()) {
			Swal.fire({
				title: 'System Message !',
				text: `Are you sure to create DN from PO Number ${po_number} ?`,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			}).then((result) => {
				if (result.isConfirmed) {
					Init_Form_Hdr_dn($('#form_hdr_dn'))
				}
			})
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	function Init_Form_Hdr_dn(DataForm) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "CreateDeliveryNote/Store_Hdr_Tmp_DN",
			data: DataForm.serialize(),
			beforeSend: function () {
				$('#generate--dn').prop("disabled", true);
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
					Fn_Disable_Form_Hdr()
					$('#generate--dn').hide();
					$('#DN_Number').val(response.No_DN);
					$('#DN_Number_Show').val(response.No_DN);
					Append_First_Row_Item_Dn(response);
					$('#location-detail-form').show();
				} else {
					Toast.fire({
						icon: 'error',
						title: response.msg
					});
					$('#submit--hdr').prop("disabled", false);
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

	function Fn_Disable_Form_Hdr() {
		$("#form_hdr_dn input").attr('readonly', 'readonly');
		$("#form_hdr_dn textarea").prop("disabled", true);
		$("#form_hdr_dn select").prop("disabled", true);
		$("#form_hdr_dn button").prop("disabled", true);
	}

	function Append_First_Row_Item_Dn(response) {
		var Row = `<tr class="default-row" data-pk="${response.SysId_Dtl}" id="row_${response.SysId_Dtl}">
						<td>${response.Flag}</td>
						<td>
							<select class="form-control form-control-xs" style="width: 100%;" name="Product[]" data-pk="${response.SysId_Dtl}">
								<option selected disabled>-Choose-</option>
							</select>
						</td>
						<td>
							<input type="text" class="form-control form-control-xs text-center" style="width: 100%;" name="Uom[]" data-pk="${response.SysId_Dtl}" readonly placeholder="Uom...">
						</td>
						<td>
							<input type="text" class="form-control form-control-xs text-center" style="width: 100%;" name="Price[]" data-pk="${response.SysId_Dtl}" readonly value="Price...">
						</td>
						<td class="text-center">
							<u><a href="javascript:void(0)" data-pk="${response.SysId_Dtl}" class="editable_qty">0</a></u>
						</td>
					</tr>`;

		$('#tbl-item-dn tbody').append(Row);
		Init_X_Editable_Qty()
		Init_Select_Dua_Product()
	}

	$('#add-row').on('click', function () {
		let LastNo = $('#tbl-item-dn>tbody>tr:last-child').find('td:eq(0)').html();
		let NewNo = parseInt(LastNo) + 1;

		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "CreateDeliveryNote/add_row_tmp_detail_dn",
			data: {
				DN_Number: $('#DN_Number').val(),
				Flag: NewNo,
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
					var Row = `<tr class="default-row" data-pk="${response.SysId}" id="row_${response.SysId}">
                            <td>${NewNo}</td>
                            <td>
                                <select class="form-control form-control-xs" style="width: 100%;" name="Product[]" data-pk="${response.SysId}">
                                    <option selected disabled>-Choose-</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-xs text-center" style="width: 100%;" name="Uom[]" data-pk="${response.SysId}" readonly placeholder="Uom...">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-xs text-center" style="width: 100%;" name="Price[]" data-pk="${response.SysId}" readonly value="Price...">
                            </td>
                            <td class="text-center">
                                <u><a href="javascript:void(0)" data-pk="${response.SysId}" class="editable_qty">0</a></u>
                            </td>
                        </tr>`;

					$('#tbl-item-dn tbody').append(Row);
					Init_X_Editable_Qty()
					Init_Select_Dua_Product()
				} else {
					return Toast.fire({
						icon: 'error',
						title: 'Warning!',
						text: response.msg
					});
				}
				Swal.close()
				$(this).prop("disabled", false);
				$('#remove-row').prop("disabled", false);
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
		var rowCount = $('#tbl-item-dn tbody tr').length;
		var sysid = $('#tbl-item-dn>tbody>tr:last-child').attr('data-pk');
		if (rowCount > 1) {
			$.ajax({
				dataType: "json",
				type: "POST",
				url: $('meta[name="base_url"]').attr('content') + "CreateDeliveryNote/delete_row_detail_dn",
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
						$('#tbl-item-dn>tbody>tr:last-child').remove();
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
				title: `You can't delete last row!`
			});
		}
	})

	function Init_X_Editable_Qty() {
		$('.editable_qty').editable({
			ajaxOptions: {
				dataType: 'json'
			},
			type: 'number',
			url: $('meta[name="base_url"]').attr('content') + "CreateDeliveryNote/store_editable_detail_qty_dn",
			title: 'Quantity...',
			validate: function (value) {
				if ($.trim(value) == '') {
					return Toast.fire({
						icon: 'error',
						title: 'Warning!',
						text: 'Qty minimum 1 !'
					});
				}
				if ($.trim(value) == undefined) {
					return Toast.fire({
						icon: 'error',
						title: 'Warning!',
						text: 'Quantity not valid !'
					});
				}
			},
			success: function (response, newValue) {
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
					return false;
				}
			}
		});
	}

	function formatRupiah(moneyy) {
		let money = parseFloat(moneyy)
		return new Intl.NumberFormat('id-ID', {
			style: 'currency',
			currency: 'IDR'
		}).format(money);
	}

	function Init_Select_Dua_Product() {
		$('select[name="Product[]"]').select2({
			minimumInputLength: 0,
			allowClear: true,
			placeholder: '-Product-',
			cache: true,
			ajax: {
				dataType: 'json',
				url: $('meta[name="base_url"]').attr('content') + "CreateDeliveryNote/select_product_customer",
				delay: 500,
				data: function (params) {
					return {
						search: params.term,
						no_po_internal: $('#no_po_internal').val()
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
			let id_product = $(this).val();
			let elem = $(this);
			let sysid = $(this).attr('data-pk');
			let flag = $(this).closest('td').prev('td').text();
			let uom = $(this).closest('td').next('td');
			let price = $(this).closest('td').next('td').next('td');
			$.ajax({
				dataType: "json",
				type: "POST",
				url: $('meta[name="base_url"]').attr('content') + "CreateDeliveryNote/Get_utility_detail_product",
				data: {
					sysid: sysid,
					id_product: id_product,
					flag: flag,
					no_po_internal: $('#no_po_internal').val()

				},
				success: function (response) {
					if (response.code == 200) {
						Toast.fire({
							icon: 'success',
							title: response.msg
						});
						uom.find('input').val(response.uom);
						price.find('input').val(formatRupiah(response.price));
					} else {
						Toast.fire({
							icon: 'error',
							title: response.msg
						});
						elem.val(null).trigger('change');
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

	$(document).on('click', '#submit-finish-dn', function () {
		let TotRow = $('select[name="Product[]"]');

		let allItems = [];
		TotRow.each(function (index, elem) {
			if ($(this).val() == null) {
				return Swal.fire({
					icon: 'warning',
					title: 'Oops...',
					text: `Please select product on row ${index + 1} !`,
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
			allItems.push(index)
		});

		if (allItems.length == TotRow.length) {
			Fn_Resgister_Dn($(this))
		}
	})

	function Fn_Resgister_Dn(btn_action) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "CreateDeliveryNote/Store_Complete_Dn",
			data: {
				DN_Number: $('#DN_Number').val(),
			},
			beforeSend: function () {
				$(btn_action).prop("disabled", true);
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
						title: 'System Message!',
						text: response.msg,
						icon: 'success',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Confirm'
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.href = $('meta[name="base_url"]').attr('content') + "DnOutstanding";
						}
					})
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: response.msg,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
					$(btn_action).prop("disabled", false);
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
