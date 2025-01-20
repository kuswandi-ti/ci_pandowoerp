$(document).ready(function () {
	$('.select2').select2()
	$('#detail-form-contact').hide()

	$('#cancel-contact').click(function () {
		$('#table-contact').show(1000)
		$('#detail-form-contact').hide(1000);
	})

	var TableData = $("#DataTable").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		dom: '<"row"<"col-6"f><"col-6"B>>rtip',
		select: true,
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "MasterData/Account/DT_shipment_address",
			dataType: "json",
			type: "POST",
			data: {
				Account_Code: $('#Code').val()
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
			data: "Address",
			name: "Address",
		},
		{
			data: "Area",
			name: "Area",
		},
		{
			data: "Description",
			name: "Description",
		},
		{
			data: "Is_Active",
			name: "Is_Active",
			render: function (data, type, row, meta) {
				if (data == 1) {
					return `<i class="fas fa-check text-success"></i>`
				} else {
					return `<i class="fas fa-times text-danger"></i>`
				}
			}
		}
		],
		order: [
			[0, "desc"]
		],
		columnDefs: [{
			className: "text-center",
			targets: [0, 1, 2, 3, 4],
		},
		{
			className: "text-left",
			targets: []
		}
		],
		autoWidth: false,
		// responsive: true,
		preDrawCallback: function () {
			$("#DataTable tbody td").addClass("blurry");
		},
		language: {
			processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
		},
		drawCallback: function () {
			$("#DataTable tbody td").addClass("blurry");
			setTimeout(function () {
				$("#DataTable tbody td").removeClass("blurry");
			});
			$('[data-toggle="tooltip"]').tooltip();
		},
		initComplete: function (settings, json) {
			// ---------------
		},
		"buttons": [, {
			text: `Toolbar Action :`,
			className: "btn disabled text-dark bg-white",
		}, {
				text: `<i class="fas fa-plus fs-3"></i>`,
				className: "bg-primary",
				action: function (e, dt, node, config) {
					$('#detail-form').show();
				}
			}, {
				text: `<i class="fas fa-toggle-on"></i>`,
				className: "btn btn-dark",
				action: function (e, dt, node, config) {
					var RowData = dt.rows({
						selected: true
					}).data();
					if (RowData.length == 0) {
						Swal.fire({
							icon: 'warning',
							title: 'Ooppss...',
							text: 'Silahkan pilih data untuk merubah status !',
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
						});
					} else {
						Fn_Toggle_Status(RowData[0].SysId)
					}
				}
			}],
	}).buttons().container().appendTo('#DataTable_wrapper .col-md-6:eq(0)');


	var TableDataContact = $("#DataTable-Contact").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		dom: '<"row"<"col-6"f><"col-6"B>>rtip',
		select: true,
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "MasterData/Account/DT_account_contact",
			dataType: "json",
			type: "POST",
			data: {
				SysId: $('#Code').val()
			}
		},
		columns: [{
			data: "Contact_Name",
			name: "Contact_Name",
		},
		{
			data: "Contact_Initial_Name",
			name: "Contact_Initial_Name",
		},
		{
			data: "Gender",
			name: "Gender",
		},
		{
			data: "Job_title",
			name: "Job_title",
		},
		{
			data: "Email_Address",
			name: "Email_Address",
		},
		{
			data: "Telephone",
			name: "Telephone",
		},
		{
			data: "Mobile_Phone",
			name: "Mobile_Phone",
		},
		{
			data: "Country",
			name: "Country",
		},
		{
			data: "State",
			name: "State",
		},
		{
			data: "City",
			name: "City",
		},
		{
			data: "Home_Address",
			name: "Home_Address",
		},
		{
			data: "Fax",
			name: "Fax",
		},
		{
			data: "Note",
			name: "Note",
		},
		{
			data: "Is_Active",
			name: "Is_Active",
			render: function (data, type, row, meta) {
				if (data == 1) {
					return `<i class="fas fa-check text-success"></i>`
				} else {
					return `<i class="fas fa-times text-danger"></i>`
				}
			}
		}
		],
		order: [
			[0, "desc"]
		],
		columnDefs: [{
			className: "text-center",
			targets: [0, 1, 2, 3, 4, 13],
		},
		{
			className: "text-left",
			targets: []
		}
		],
		autoWidth: false,
		// responsive: true,
		preDrawCallback: function () {
			$("#DataTable-Contact tbody td").addClass("blurry");
		},
		language: {
			processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
		},
		drawCallback: function () {
			$("#DataTable-Contact tbody td").addClass("blurry");
			setTimeout(function () {
				$("#DataTable-Contact tbody td").removeClass("blurry");
			});
			$('[data-toggle="tooltip"]').tooltip();
		},
		initComplete: function (settings, json) {
			// ---------------
		},
		"buttons": [, {
			text: `Toolbar Action :`,
			className: "btn disabled text-dark bg-white",
		}, {
				text: `<i class="fas fa-plus fs-3"></i>`,
				className: "bg-primary",
				action: function (e, dt, node, config) {
					$('#table-contact').hide(1000)
					$('#detail-form-contact').show(1000);
				}
			}, {
				text: `<i class="fas fa-toggle-on"></i>`,
				className: "btn btn-dark",
				action: function (e, dt, node, config) {
					var RowData = dt.rows({
						selected: true
					}).data();
					if (RowData.length == 0) {
						Swal.fire({
							icon: 'warning',
							title: 'Ooppss...',
							text: 'Silahkan pilih data untuk merubah status !',
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
						});
					} else {
						Fn_Toggle_Status_contact(RowData[0].SysId)
					}
				}
			}],
	}).buttons().container().appendTo('#DataTable-Contact_wrapper .col-md-6:eq(0)');

	function Fn_Toggle_Status_contact(SysId) {
		Swal.fire({
			title: 'System message!',
			text: `Apakah anda yakin untuk merubah status Account ini ?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, ubah!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: $('meta[name="base_url"]').attr('content') + "MasterData/HelperMaster/Toggle_Status",
					type: "post",
					dataType: "json",
					data: {
						sysid: SysId,
						table: 'tmst_account_contact'
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
							$("#DataTable").DataTable().ajax.reload(null, false);
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: response.msg,
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'Yes, Confirm!',
								footer: '<a href="javascript:void(0)">Notification System</a>'
							});
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
					}
				});
			}
		})
	}
	// ------------------------------------ START FORM VALIDATION
	const DtlForm = $('#detail-form');
	const BtnDtlSubmit = $('#submit-detail-form');
	DtlForm.validate({
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

	$(BtnDtlSubmit).click(function (e) {
		e.preventDefault();
		if (DtlForm.valid()) {
			Swal.fire({
				title: 'Loading....',
				html: '<div class="spinner-border text-primary"></div>',
				showConfirmButton: false,
				allowOutsideClick: false,
				allowEscapeKey: false
			});
			Fn_Submit_Form_Addr(DtlForm)
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	function Fn_Submit_Form_Addr() {
		BtnDtlSubmit.prop("disabled", true);
		var formDataa = new FormData(DtlForm[0]);
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "MasterData/Account/post_address",
			data: formDataa,
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					$(DtlForm)[0].reset();
					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: response.msg,
						showCancelButton: false,
					}).then((result) => {
						$("#DataTable").DataTable().ajax.reload(null, false);
						$(DtlForm)[0].reset();
						$('#detail-form').hide()
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
				BtnDtlSubmit.prop("disabled", false);
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

	// ------------------------------------------------------------------

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
			url: $('meta[name="base_url"]').attr('content') + "MasterData/Account/update",
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
						return window.location.href = $('meta[name="base_url"]').attr('content') + `MasterData/Account/${response.page}`;
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
	// -------------------------------------------------------------------------------------
	const ContactForm = $('#detail-form-contact');
	const BtnContactForm = $('#submit-contact');
	ContactForm.validate({
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

	$(BtnContactForm).click(function (e) {
		e.preventDefault();
		if (ContactForm.valid()) {
			Swal.fire({
				title: 'Loading....',
				html: '<div class="spinner-border text-primary"></div>',
				showConfirmButton: false,
				allowOutsideClick: false,
				allowEscapeKey: false
			});
			Fn_Submit_Form_Contact(ContactForm)
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	function Fn_Submit_Form_Contact() {
		BtnContactForm.prop("disabled", true);
		var formDataa = new FormData(ContactForm[0]);
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "MasterData/Account/post_contact",
			data: formDataa,
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					$(ContactForm)[0].reset();
					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: response.msg,
						showCancelButton: false,
					}).then((result) => {
						$("#DataTable-Contact").DataTable().ajax.reload(null, false);
						$(ContactForm)[0].reset();
						$('#detail-form-contact').hide(1000);
						$('#table-contact').show(1000)
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
				BtnContactForm.prop("disabled", false);
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
})
