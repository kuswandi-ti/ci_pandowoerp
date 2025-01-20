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

	$('.datepicker').datetimepicker({
		format: 'YYYY-MM-DD',
		autoclose: true,
		allowClear: true,
		todayHighlight: true,
		orientation: 'bottom',
	});

	$('.select2').select2();

	$('#product').prop('disabled', true);


	$('#form_print_barcode_product').validate({
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

	$('#submit-form').click(function (e) {
		e.preventDefault();
		let qty = $('select[name="qty"]').find(':selected').val();
		if ($("#form_print_barcode_product").valid()) {
			Swal.fire({
				title: 'System Message!',
				text: `Print barcode product sebanyak ${qty} Pcs ?`,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			}).then((result) => {
				if (result.isConfirmed) {
					Init_store_barcode_product()
				}
			})
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	// $('select[name="inspector"]').val(null).trigger('change');
	function Init_store_barcode_product() {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "PrintBarcodeProduct/Store_Barcode_Product",
			data: $('#form_print_barcode_product').serialize(),
			beforeSend: function () {
				$('#submit-form').prop("disabled", true);
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
					window.open($('meta[name="base_url"]').attr('content') + "PrintBarcodeProduct/print/" + response.flag, 'PrintPageBarcodeImp', 'width=800,height=600');
					$('#form_print_barcode_product')[0].reset();
					$('#customer').val(null).trigger('change');
					$('#product').val(null).trigger('change');
					$('#qty').val(null).trigger('change');
					$('#submit-form').prop("disabled", false);

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




	// ===================== utility
	$('select[name="customer"]').select2({
		minimumInputLength: 0,
		allowClear: true,
		placeholder: '-Pilih Supplier-',
		ajax: {
			dataType: 'json',
			url: $('meta[name="base_url"]').attr('content') + "PrintBarcodeProduct/select_customer",
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
	}).on('select2:select', function (evt) {
		$('#product').prop('disabled', false);
		Init_Seelect2_Product()

	});

	function Init_Seelect2_Product() {
		$('select[name="product"]').select2({
			minimumInputLength: 0,
			allowClear: true,
			placeholder: '-Pilih Product-',
			ajax: {
				dataType: 'json',
				url: $('meta[name="base_url"]').attr('content') + "PrintBarcodeProduct/select_product/" + $('select[name="customer"]').find(':selected').val(),
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
	}

	$('select[name="leader_rakit"]').select2({
		minimumInputLength: 0,
		allowClear: true,
		placeholder: '-Pilih Leader Rakit-',
		ajax: {
			dataType: 'json',
			url: $('meta[name="base_url"]').attr('content') + "PrintBarcodeProduct/select_leader_rakit",
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

	$('select[name="checker_rakit"]').select2({
		minimumInputLength: 0,
		allowClear: true,
		placeholder: '-Pilih Leader Rakit-',
		ajax: {
			dataType: 'json',
			url: $('meta[name="base_url"]').attr('content') + "PrintBarcodeProduct/select_checker_rakit",
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
})
