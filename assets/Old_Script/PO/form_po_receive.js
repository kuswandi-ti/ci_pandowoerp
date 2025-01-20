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

	$('#form_hdr_po').validate({
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

	$('#submit--hdr').click(function (e) {
		e.preventDefault();
		let po_number = $('#po_number').val();
		if ($("#form_hdr_po").valid()) {
			Swal.fire({
				title: 'System Message!',
				text: `Apakah anda yakin untuk mendaftarkan PO ${po_number} ?`,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			}).then((result) => {
				if (result.isConfirmed) {
					Init_Form_Hdr_PO()
				}
			})
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	$('#btn--list--address').on('click', function () {
		if ($('#customer').val() == null || $('#customer').val() == '' || $('#customer').val() == undefined) {
			return Swal.fire({
				icon: 'warning',
				title: 'Oops...',
				text: 'You have to choose the customer first!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "Master/List_Address_Customer_Pick",
			data: {
				sysid: $('#customer').val()
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
				$('#modal-list-address').modal('show');
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

	function Init_Form_Hdr_PO() {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "InputPO/Store_Hdr_PO",
			data: $('#form_hdr_po').serialize(),
			beforeSend: function () {
				$('#submit--hdr').prop("disabled", true);
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
					$('#submit--hdr').hide();
					$('#no_Doc').val(response.No_Doc);
					// $('#No_Po_Internal_Span').text(response.No_Doc);
					// $('#No_Po_Internal').val(response.No_Doc);
					Fn_Call_Form_Detail_Item_PO(response.No_Doc)

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

	function Fn_Call_Form_Detail_Item_PO(No_Doc) {
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "InputPO/Call_Form_Detail_Item_PO",
			data: {
				No_Doc: No_Doc
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
				$('#card-detail-item').html(response);
				$('#card-detail-item').show("slide", {
					direction: "left"
				}, 800);
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

	$('select[name="customer"]').select2({
		minimumInputLength: 0,
		allowClear: true,
		placeholder: 'Choose',
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
	})

	function Fn_Disable_Form_Hdr() {
		$('#form_hdr_po input').attr('readonly', 'readonly');
		$('#form_hdr_po textarea').prop("disabled", true);
		$('#form_hdr_po select').prop("disabled", true);
	}
})
