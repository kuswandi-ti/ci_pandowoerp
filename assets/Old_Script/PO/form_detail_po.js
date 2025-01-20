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
		'<button type="submit" class="btn btn-primary btn-xs editable-submit mt-1">' +
		'<i class="fa fa-fw fa-check"></i> | Save' +
		'</button>&nbsp;&nbsp;' +
		'<button type="button" class="btn btn-warning btn-xs editable-cancel mt-1">' +
		'<i class="fa fa-fw fa-times"></i> | Cancel' +
		'</button>';

	$('#add-row').on('click', function () {
		let LastNo = $('#tbl-detail-po>tbody>tr:last-child').find('td:eq(0)').html();
		let NewNo = parseInt(LastNo) + 1;

		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "InputPO/add_row_tmp_detail_po",
			data: {
				no_po_internal: $('#No_Po_Internal').val(),
				flag: NewNo,
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

					$('#tbl-detail-po tbody').append(Row);
					Init_X_Editable_Qty()
					Init_Select_Dua_Product()
					$(this).prop("disabled", false);
					$('#remove-row').prop("disabled", false);
					Swal.close()
				} else {
					$(this).prop("disabled", false);
					$('#remove-row').prop("disabled", false);
					return Toast.fire({
						icon: 'error',
						title: 'Warning!',
						text: response.msg
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
	})

	$('#remove-row').on('click', function () {
		var rowCount = $('#tbl-detail-po tbody tr').length;
		var sysid = $('#tbl-detail-po>tbody>tr:last-child').attr('data-pk');
		if (rowCount > 1) {
			$.ajax({
				dataType: "json",
				type: "POST",
				url: $('meta[name="base_url"]').attr('content') + "InputPO/delete_row_detail_po",
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
						$('#tbl-detail-po>tbody>tr:last-child').remove();
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

	function Fn_Resgister_Po() {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "InputPO/Store_Complete_Po",
			data: {
				No_Po_Internal: $('#No_Po_Internal').val(),
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
					Swal.fire({
						title: 'System Message!',
						text: response.msg,
						icon: 'success',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Confirm'
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.href = $('meta[name="base_url"]').attr('content') + "SalesOrder";
						}
					})
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: response.msg,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
					$(this).prop("disabled", false);
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

	$(document).on('click', '#submit-form', function () {
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
			Fn_Resgister_Po()
		}
	})



	function Init_X_Editable_Qty() {
		$('.editable_qty').editable({
			ajaxOptions: {
				dataType: 'json'
			},
			type: 'number',
			url: $('meta[name="base_url"]').attr('content') + "InputPO/store_editable_detail_qty_order",
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
						title: 'Quantity order updated !'
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
				url: $('meta[name="base_url"]').attr('content') + "InputPO/select_product_customer",
				delay: 800,
				data: function (params) {
					return {
						search: params.term,
						customer_id: $('#customer').val()
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
				url: $('meta[name="base_url"]').attr('content') + "InputPO/Get_utility_detail_product",
				data: {
					sysid: sysid,
					id_product: id_product,
					flag: flag
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

	Init_Select_Dua_Product();
	Init_X_Editable_Qty();
})
