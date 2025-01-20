$(document).ready(function () {
// 	$('.select2').select2();
	
// 	// ------------------------------------ START FORM VALIDATION
// 	const MainForm = $('#main-form');
// 	const BtnSubmit = $('#btn-submit');
// 	MainForm.validate({
// 		errorElement: 'span',
// 		errorPlacement: function (error, element) {
// 			error.addClass('invalid-feedback');
// 			element.closest('.form-group').append(error);
// 		},
// 		highlight: function (element, errorClass, validClass) {
// 			$(element).addClass('is-invalid');
// 		},
// 		unhighlight: function (element, errorClass, validClass) {
// 			$(element).removeClass('is-invalid');
// 		}
// 	});
// 	$.validator.setDefaults({
// 		debug: true,
// 		success: 'valid'
// 	});

// 	$(BtnSubmit).click(function (e) {
// 		e.preventDefault();
// 		if (MainForm.valid()) {
// 			Swal.fire({
// 				title: 'Loading....',
// 				html: '<div class="spinner-border text-primary"></div>',
// 				showConfirmButton: false,
// 				allowOutsideClick: false,
// 				allowEscapeKey: false
// 			});
// 			Fn_Submit_Form(MainForm)
// 		} else {
// 			$('html, body').animate({
// 				scrollTop: ($('.error:visible').offset().top - 200)
// 			}, 400);
// 		}
// 	});

// 	function Fn_Submit_Form() {
// 		BtnSubmit.prop("disabled", true);
// 		var formDataa = new FormData(MainForm[0]);
// 		$.ajax({
// 			dataType: "json",
// 			type: "POST",
// 			url: $('meta[name="base_url"]').attr('content') + "MasterData/AkunInduk/update",
// 			data: formDataa,
// 			cache: false,
// 			contentType: false,
// 			processData: false,
// 			success: function (response) {
// 				Swal.close()
// 				if (response.code == 200) {
// 					$(MainForm)[0].reset();
// 					Swal.fire({
// 						icon: 'success',
// 						title: 'Success!',
// 						text: response.msg,
// 						showCancelButton: false,
// 					}).then((result) => {
// 						return window.location.href = $('meta[name="base_url"]').attr('content') + "MasterData/AkunInduk/";
// 					})
// 				} else {
// 					Swal.fire({
// 						icon: 'error',
// 						title: 'Oops...',
// 						text: response.msg,
// 						confirmButtonColor: '#3085d6',
// 						confirmButtonText: 'Ya, Confirm!',
// 						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
// 					});
// 				}
// 				BtnSubmit.prop("disabled", false);
// 			},
// 			error: function (xhr, status, error) {
// 				var statusCode = xhr.status;
// 				var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
// 				Swal.fire({
// 					icon: "error",
// 					title: "Error!",
// 					html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
// 				});
// 			}
// 		});
// 	}
// 	// ------------------------------------ END FORM VALIDATION

	$(document).on('click', '.btn-remove', function() {
		var sysid = $(this).attr('sysid');
		var id_parent = $(this).attr('id_parent');
		var no_bom = $(this).attr('no_bom');

		Swal.fire({
			title: 'System message!',
			text: `Apakah anda yakin untuk menghapus data ini ?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, hapus!'
		}).then((result) => {
			if (result.isConfirmed) {
				$('#btn-submit').prop("disabled", true);
				$.ajax({
					url: $('meta[name="base_url"]').attr('content') + "MasterData/BillOfMaterial/delete",
					type: "post",
					dataType: "json",
					data: {
						sysid: sysid,
						id_parent: id_parent,
						no_bom: no_bom,
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
						if (response.code == 200) {
							Swal.fire({
								icon: 'success',
								title: 'Success!',
								text: response.msg,
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'Yes, Confirm!'
							})
							// $("#DataTable").DataTable().ajax.reload(null, false);
							if (id_parent == 0) {
								window.location.href = $('meta[name="base_url"]').attr('content') + "MasterData/BillOfMaterial/"
							} else {
								location.reload();
							}							
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: response.msg,
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'Yes, Confirm!',
								footer: '<a href="javascript:void(0)">Notification System</a>'
							});
							$('#btn-submit').prop("disabled", false);
						}
					},
					error: function (xhr, status, error) {
						var statusCode = xhr.status;
						var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
						Swal.fire({
							icon: "error",
							title: "Error!",
							html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
						});
						$('#btn-submit').prop("disabled", false);
					}
				});
			}
		});
	});

	$(document).on('click', '.btn-add', function() {
		$('#id_parent').val($(this).attr('sysid'));
		$('#no_bom').val($(this).attr('no_bom'));

		$('#modal_add').modal('show');
	});

	$(document).on('click', '#btn-submit', function() {
		var no_bom = $('#no_bom').val();
		var id_item = $('#id_item option:selected').val();
		var id_parent = $('#id_parent').val();
		var qty = $('#qty').val();

		$('#btn-submit').prop("disabled", true);
		$.ajax({
			url: $('meta[name="base_url"]').attr('content') + "MasterData/BillOfMaterial/post",
			type: "post",
			dataType: "json",
			data: {
				no_bom: no_bom,
				id_item: id_item,
				id_parent: id_parent,
				qty: qty,
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
				if (response.code == 200) {
					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: response.msg,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Yes, Confirm!'
					})
					// $("#DataTable").DataTable().ajax.reload(null, false);
					location.reload();
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: response.msg,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Yes, Confirm!',
						footer: '<a href="javascript:void(0)">Notification System</a>'
					});
					$('#btn-submit').prop("disabled", false);
				}
			},
			error: function (xhr, status, error) {
				var statusCode = xhr.status;
				var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
				Swal.fire({
					icon: "error",
					title: "Error!",
					html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
				});
				$('#btn-submit').prop("disabled", false);
			}
		});
	});
})
