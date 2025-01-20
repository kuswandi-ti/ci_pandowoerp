$(document).ready(function () {


	$('#otomatis_ic').click(function () {
		$('#item_code').attr('readonly', 'readonly');
		$('#item_code').removeAttr('required');
		$('#item_code').val('');
	});

	$('#manual_ic').click(function () {
		$('#item_code').removeAttr('readonly');
		$('#item_code').attr('required', 'required');
	});

	$('#non_grid').click(function () {
		// 	$('#item_length').removeAttr('required');
		// 	$('#item_width').removeAttr('required');
		// 	$('#item_height').removeAttr('required');
		// 	$('#item_length').val('');
		// 	$('#item_width').val('');
		// 	$('#item_height').val('');
		// 	$('#Id_Pki').removeAttr('required');
		// 	$('#Id_Pki').val('');

		$('#Grid_Pattern_Code').removeAttr('required');
		$('#Grid_Pattern_Code').val('');
		$('#location-grid-pattern-code').hide(100)
	});

	$('#grid').click(function () {
		// 	$('#item_length').attr('required', 'required');
		// 	$('#item_width').attr('required', 'required');
		// 	$('#item_height').attr('required', 'required');
		// 	$('#Id_Pki').attr('required');

		$('#Grid_Pattern_Code').attr('required');
		$('#location-grid-pattern-code').show(100)
	})


	$('#uom_id').select2()
	$('#Item_Category_Group').select2()
	$('#Default_Warehouse_Id').select2()


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
			url: $('meta[name="base_url"]').attr('content') + "MasterData/Item/post",
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
						showCancelButton: false,
					}).then((result) => {
						return window.location.href = $('meta[name="base_url"]').attr('content') + "MasterData/Item/index";
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
	// ------------------------------------ Start Utility Form
	$('#calculate-volume').click(function () {
		// Ambil nilai dari input
		var length = parseFloat($('#item_length').val());
		var width = parseFloat($('#item_width').val());
		var height = parseFloat($('#item_height').val());

		// Hitung volume dalam cm³
		var volumeCm = length * width * height;

		// Konversi volume ke m³ (1 m³ = 1.000.000 cm³)
		var volumeM = volumeCm / 1000000;

		// Tampilkan hasil
		$('#Volume_M3').val(volumeM);
	});

	function Initialize_select2_item_group() {
		$('#Item_Category_Group').select2({
			minimumInputLength: 0,
			allowClear: true,
			placeholder: '-Item Group-',
			cache: true,
			ajax: {
				dataType: 'json',
				url: $('meta[name="base_url"]').attr('content') + "MasterData/Item/select_item_group",
				delay: 800,
				data: function (params) {
					return {
						search: params.term,
						category: $('#Item_Category').val()
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
				}
			}
		})
	}

	$('#Item_Category').on('change', function () {
		$('#Item_Category_Group').val(null).trigger('change'), Initialize_select2_item_group()
	})
})
