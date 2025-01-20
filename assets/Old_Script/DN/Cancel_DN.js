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

	$('#btn--search--dn').on('click', function () {
		if ($('#dn_number').val() == '' || $('#dn_number').val() == null || $('#dn_number').val() == undefined) {
			return Swal.fire({
				icon: 'warning',
				title: 'Oops...',
				text: 'Please Input a valid Delivery Note number !',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}

		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "CancelDN/Check_Valid_DN",
			data: {
				dn_number: $('#dn_number').val(),
			},
			beforeSend: function () {
				$('#btn--search--dn').prop("disabled", true);
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
					$('#dn_number').prop('readonly', true)
					$('#elem-select-product').show();
					$('#elem-submit').show();
					Init_Select_Dua_Product()
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: response.msg,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
					$('#btn--search--dn').prop("disabled", false);
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

	function Init_Select_Dua_Product() {
		$('select[name="product"]').select2({
			minimumInputLength: 0,
			allowClear: true,
			placeholder: '-Product-',
			cache: true,
			ajax: {
				dataType: 'json',
				url: $('meta[name="base_url"]').attr('content') + "CancelDN/select_product_dn",
				delay: 500,
				data: function (params) {
					return {
						search: params.term,
						dn_number: $('#dn_number').val()
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
	}

	$('#btn-show-list-loading').on('click', function () {
		let product = $('#product').val();

		if (product == '' || product == null || product == undefined) {
			return Swal.fire({
				icon: 'warning',
				title: 'Oops...',
				text: 'Please select the product, to continue the cancellation/swap process !',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}

		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "CancelDN/Check_Loading_Number",
			data: {
				dn_number: $('#dn_number').val(),
				product: product
			},
			beforeSend: function () {
				$('#btn-show-list-loading').prop("disabled", true);
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
					Init_DataTable_List_Loading(response.loading_number)
					$('#no_loading_span').text(response.loading_number)
					$('#no_loading').val(response.loading_number)
					$('#no_dn').val($('#dn_number').val())
					$('#product_id').val($('#product').val())
					$('select[name="product"]').prop('disabled', true)
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: response.msg,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
					$('#btn-show-list-loading').prop("disabled", false);
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

	function Init_DataTable_List_Loading(no_loading) {
		$("#Tbl_list_Loading").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			paging: false,
			ordering: false,
			searching: false,
			orderCellsTop: true,
			fixedHeader: {
				header: true,
				headerOffset: 48
			},
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "LoadingFinish/DT_Barcode_loading",
				dataType: "json",
				type: "POST",
				data: {
					no_loading: no_loading
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
					render: function (data, type, row, meta) {
						return `<input class="form-control form-control-sm text-center" readonly name="barcode[]" value="${data}">`;
					}
				},
				{
					data: "Barcode_Value",
					name: "Barcode_Value",
					render: function (data, type, row, meta) {
						return `<div class="form-group clearfix">
									<div class="icheck-success d-inline">
										<input type="radio" id="OK_${data}" name="action_${data}" checked value="OK">
										<label for="OK_${data}">
											OK
										</label>
									</div>
									&nbsp;
									<div class="icheck-danger d-inline" style="border-left: solid grey 1px; padding-left: 6px;">
										<input type="radio" id="RIJECT_${data}" name="action_${data}" value="RIJECT">
										<label for="RIJECT_${data}">
											RIJECT
										</label>
									</div>
									&nbsp;
									<div class="icheck-warning d-inline" style="border-left: solid grey 1px; padding-left: 6px;">
										<input type="radio" id="${data}" name="action_${data}" value="SS">
										<label for="SS_${data}">
											SWAP STOK
										</label>
									</div>
									&nbsp;
									<div class="icheck-danger d-inline" style="border-left: solid grey 1px; padding-left: 6px;">
										<input type="radio" id="${data}" name="action_${data}" value="SR">
										<label for="SR_${data}">
											SWAP AND RIJECT
										</label>
									</div>
								</div>`;
					}
				},
				{
					data: "Barcode_Value",
					name: "Barcode_Value",
					render: function (data, type, row, meta) {
						return `<input class="form-control form-control-sm text-center" name="subs[]" value="">`;
					}
				},
			],
			order: [
				[1, 'ASC']
			],
			columnDefs: [{
				className: "align-middle text-center",
				targets: [0, 1, 2, 3],
			}],
			// autoWidth: false,
			responsive: true,
			preDrawCallback: function () {
				$("#Tbl_list_Loading tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#Tbl_list_Loading tbody td").addClass("blurry");
				setTimeout(function () {
					$("#Tbl_list_Loading tbody td").removeClass("blurry");
				});
				$('[data-toggle="tooltip"]').tooltip();
				$('#card-list-barcode').show();
			}
		})
	}

	$('#btn-complete-form').on('click', function () {
		Swal.fire({
			title: 'System Message !',
			text: `Are you sure to edit data loading ${$('#no_loading').val()} ?`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes'
		}).then((result) => {
			if (result.isConfirmed) {
				Init_Form_Swap_or_Cancel_Item_Loading($('#form-list-loading'))
			}
		})
	})

	function Init_Form_Swap_or_Cancel_Item_Loading(formData) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "CancelDN/Store_CancelOrSwap_Item_Loading",
			data: formData.serialize(),
			beforeSend: function () {
				$('#btn-complete-form').prop("disabled", true);
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
					window.location.href = $('meta[name="base_url"]').attr('content') + "CompleteDN"
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: response.msg,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
					$('#btn-complete-form').prop("disabled", false);
				}
			},
			error: function () {
				Swal.close()
				$('#btn-complete-form').prop("disabled", false);
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
