$(document).ready(function () {
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		width: 300,
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.addEventListener('mouseenter', Swal.stopTimer)
			toast.addEventListener('mouseleave', Swal.resumeTimer)
		}
	})

	$('.onlyfloat').keypress(function (event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
		var input = $(this).val();
		if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
			event.preventDefault();
		}
	});

	$(".readonly").keydown(function (event) {
		return false;
	});

	$('#Doc_Date').datetimepicker({
		format: 'YYYY-MM-DD',
		autoclose: true,
		allowClear: true,
		todayHighlight: true,
		orientation: 'bottom',
		showClose: true,
		buttons: {
			showClose: true,
		}
	});

	// $('.form-select').select2();

	$('#back').click(function (e) {
		e.preventDefault();
		window.location.href = $('meta[name="base_url"]').attr('content') + 'Dashboard';
	});

	// ----------------------- SELECT2

	$.ajax({
		url: $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/get_master_pj_oven",
		type: 'POST',
		dataType: 'json',
		success: function (res) {
			$.each(res, function (index, value) {
				var option = $('<option/>', {
					value: value.id,
					text: value.text
				});
				if (index === 1) {
					option.attr('selected', 'selected');
				}
				$('.pjoven').append(option);
			});
		},
		error: function () {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Terjadi kesalahan teknis saat mengambil data penjaga oven !',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
	});

	$.ajax({
		url: $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/get_master_maintenance",
		type: 'POST',
		dataType: 'json',
		success: function (res) {
			$.each(res, function (index, value) {
				var option = $('<option/>', {
					value: value.id,
					text: value.text
				});
				if (index === 1) {
					option.attr('selected', 'selected');
				}
				$('.maintenance').append(option);
			});
		},
		error: function () {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Terjadi kesalahan teknis saat mengambil data orang maintenance !',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
	});

	$.ajax({
		url: $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/get_master_teknik",
		type: 'POST',
		dataType: 'json',
		success: function (res) {
			$.each(res, function (index, value) {
				var option = $('<option/>', {
					value: value.id,
					text: value.text
				});
				if (index === 1) {
					option.attr('selected', 'selected');
				}
				$('.teknik').append(option);
			});
		},
		error: function () {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Terjadi kesalahan teknis saat mengambil data orang teknik !',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
	});

	// ---------------SCRIPT NEED TRIGGER
	$('#form_hdr').validate({
		rules: {
			R_Boiler_Pj_Oven: {
				required: true,
			}
		},
		messages: {
			R_Boiler_Pj_Oven: {
				required: "R_Boiler_Pj_Oven tidak boleh kosong!",
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

	$('#run_save').click(function (e) {
		e.preventDefault();
		if ($("#form_hdr").valid()) {
			Fn_store_add_form($('#form_hdr').serialize());
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
			url: $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/store_hdr_temperature",
			data: DataForm,
			beforeSend: function () {
				$("#run_save").prop("disabled", true);
				$("#run_save").html(
					`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ...`
				)
			},
			success: function (response) {
				if (response.code == 200) {
					// $('.select2').val(null).trigger('change');
					$('#form_hdr')[0].reset();

					Swal.fire({
						icon: 'success',
						title: 'Success...',
						text: response.msg,
						footer: '<a href="javascript:void(0)" class="text-info">Notifikasi System IMP-SYS</a>'
					}).then((result) => {
						if (result.isConfirmed === true) {
							window.location.href = $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/index/" + response.id_oven;
						}
					})
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
