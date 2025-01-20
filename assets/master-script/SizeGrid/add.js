$(document).ready(function () {
	$('.select2').select2()


	// ------------------------------------ START FORM VALIDATION
	const MainForm = $('#main-form');
	const BtnSubmit = $('#btn-submit');
	MainForm.validate({
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

	$(BtnSubmit).click(function (e) {
		e.preventDefault();
		if (MainForm.valid()) {
			Swal.fire({
				title: 'Loading....',
				html: '<div class="spinner-border text-primary"></div>',
				showConfirmButton: false,
				allowOutsideClick: false,
				allowEscapeKey: false
			});
			Fn_Submit_Form(MainForm)
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	function Fn_Submit_Form() {
		BtnSubmit.prop("disabled", true);
		var formDataa = new FormData(MainForm[0]);
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "MasterData/SizeGrid/post",
			data: formDataa,
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					$(MainForm)[0].reset();
					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: response.msg,
						showCancelButton: true,
						confirmButtonColor: "#3085d6",
						cancelButtonColor: "#d33",
						confirmButtonText: "List Ukuran",
						cancelButtonText: "Input Lagi",
					}).then((result) => {
						return window.location.href = $('meta[name="base_url"]').attr('content') + "MasterData/SizeGrid/index";
					})
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: response.msg,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ya, Confirm!',
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
				BtnSubmit.prop("disabled", false);
			},
			error: function (xhr, status, error) {
				var statusCode = xhr.status;
				var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
				Swal.fire({
					icon: "error",
					title: "Error!",
					html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
				});
			}
		});
	}
	// ------------------------------------ END FORM VALIDATION

	$('select#Item_ID').select2({
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
	})

	$('#calculate-volume').click(function () {
		// Ambil nilai dari input
		var length = parseFloat($('#Item_Length').val());
		var width = parseFloat($('#Item_Width').val());
		var height = parseFloat($('#Item_Height').val());

		// Hitung volume dalam cm³
		var volumeCm = length * width * height;

		// Konversi volume ke m³ (1 m³ = 1.000.000 cm³)
		var volumeM = volumeCm / 1000000;

		// Tampilkan hasil
		$('#Cubication').val(volumeM);
	});
})
