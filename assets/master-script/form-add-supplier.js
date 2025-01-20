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
	$('#form_add').validate({
		rules: {
			nama: {
				required: true,
			}
		},
		messages: {
			nama: {
				required: "Nama supplier tidak boleh kosong!",
			}
		},
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
		if ($("#form_add").valid()) {
			Fn_store_add_form($('#form_add').serialize());
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});


	function Fn_store_add_form(DataForm) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Master/store_add_supplier",
			data: DataForm,
			beforeSend: function () {
				$("#submit-form").prop("disabled", true);
				$("#submit-form").html(
					`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
				)
			},
			success: function (response) {
				if (response.code == 200) {
					Toast.fire({
						icon: 'success',
						title: response.msg
					});
					$('#form_add')[0].reset();
					$("#submit-form").prop("disabled", false);
					$("#submit-form").html(
						`<i class="fab fa-wpforms"></i> | SUBMIT`
					)
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: response.msg,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
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
	}
})
