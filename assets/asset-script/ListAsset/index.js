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

	form_state('LOAD');

	function form_state(state) {
		switch (state) {
			case 'LOAD':
				$('.list-data').show("slow");
				$('.add-data').hide("slow");
				$('input[name="state"]').val('');
				reloadData();
				break;

			case 'ADD':
				reset_input();
				$('.list-data').hide("slow");
				$('.add-data').show("slow");
				$('input[name="state"]').val('ADD');
				$('input[name="doc_number"]').val('Doc Number Akan Otomatis di isikan Oleh system.');
				$('#no_data_item').show('slow');
				$(MainForm)[0].reset();
				flatpickr();
				break;

			case 'EDIT':
				reset_input();
				$('.list-data').hide();
				$('.add-data').show();
				$('input[name="state"]').val('EDIT');
				$('#no_data_item').hide('slow');
				break;

			case 'BACK':
				$('.list-data').show("slow");
				$('.add-data').hide("slow");
				break;
		}
	}

	function reset_input() {
		$("input:text").val('');
		$('input[type="hidden"]').val('');
		$('#table_item tbody').html('');
	}

	function reloadData() {
		$("#DataTable").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
			lengthMenu: [
				[10, 25, 50, 10000],
				[10, 25, 50, 'All']
			],
			select: true,
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "Asset/ListAsset/DT_listdata",
				dataType: "json",
				type: "POST",
			},
			columns: [
				{
					data: "sysid", // 0
					name: "sysid",
				},
				{
					data: "no_asset", // 1
					name: "no_asset",
				},
				{
					data: "item_code", // 2
					name: "item_code",
				},
				{
					data: "item_name", // 3
					name: "item_name",
				},
				{
					data: "tgl_perolehan", // 4
					name: "tgl_perolehan",
					render: function (data, type, row, meta) {
						return moment(data).format("DD MMMM YYYY");
					}
				},
				{
					data: "tahun_perolehan", // 5
					name: "tahun_perolehan",
				},
				{
					data: "harga_perolehan", // 6
					name: "harga_perolehan",
					render: function (data, type, row, meta) {
						return formatIdrAccounting(data);
					}
				},
				{
					data: "masa_tahun_pakai", // 7
					name: "masa_tahun_pakai",
				},
				{
					data: "nilai_penyusutan", // 8
					name: "nilai_penyusutan",
				},
				{
					data: "Is_Active", // 9
					name: "Is_Active",
					render: function (data, type, row, meta) {
						if (data == 1) {
							return `<div class='d-flex justify-content-center'><span class="badge bg-success">Active</span></div>`;
						} else {
							return `<div class='d-flex justify-content-center'><span class="badge bg-danger">In-Active</span></div>`;
						}
					}
					// render: function (data, type, row, meta) {
					// 	if (data == 1) {
					// 		return `<i class="fas fa-check text-success"></i>`
					// 	} else {
					// 		return `<i class="fas fa-times text-danger"></i>`
					// 	}
					// }
				},
			],
			order: [
				[0, "desc"]
			],
			columnDefs: [{
				className: "text-center",
				targets: [0, 1, 2, 4, 5, 7, 8, 9],
			},
			{
				className: "text-left",
				targets: []
			},
			{
				className: "text-right",
				targets: [6]
			},
			{
				visible: false,
				targets: [0]
			},
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
			"buttons": [
			// 	{
			// 	text: `<i class="fas fa-plus fs-3"></i>`,
			// 	className: "bg-primary",
			// 	action: function (e, dt, node, config) {
			// 		form_state('ADD');
			// 	}
			// }, 
			{
				text: `<i class="fas fa-edit fs-3"> Edit</i>`,
				className: "btn btn-warning",
				action: function (e, dt, node, config) {
					var RowData = dt.rows({
						selected: true
					}).data();
					if (RowData.length == 0) {
						Swal.fire({
							icon: 'warning',
							title: 'Ooppss...',
							text: 'Silahkan pilih data untuk melihat detail !',
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
						});
					} else {
						Init_Edit(RowData[0].sysid)
					}
				}
			},
			{
				text: `<i class="fas fa-toggle-on"> Active / In-Active</i>`,
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
						Fn_Toggle_Status(parseInt(RowData[0].sysid))
					}
				}
			},
			{
				text: `Export to :`,
				className: "btn disabled text-dark bg-white",
			}, {
				text: `<i class="far fa-file-excel"> Excel</i>`,
				extend: 'excelHtml5',
				title: $('title').text() + ' ~ ' + moment().format("YYYY-MM-DD"),
				className: "btn btn-success",
			}, {
				text: `<i class="far fa-file-pdf"> PDF</i>`,
				extend: 'pdfHtml5',
				title: $('title').text() + ' ~ ' + moment().format("YYYY-MM-DD"),
				className: "btn btn-danger",
				orientation: "landscape"
			}],
		}).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');
	}

	function Init_Edit(SysId) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Asset/ListAsset/edit",
			data: {
				sysid: SysId
			},
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					form_state('EDIT');

					$('input[name="sysid"]').val(response.data.sysid);
					$('input[name="no_asset"]').val(response.data.no_asset);
					$('input[name="tgl_perolehan"]').val(moment(response.data.tgl_perolehan).format("DD MMMM YYYY"));
					$('input[name="tahun_perolehan"]').val(response.data.tahun_perolehan);
					$('input[name="harga_perolehan"]').val(formatIdrAccounting(response.data.harga_perolehan));
					$('input[name="masa_tahun_pakai"]').val(response.data.masa_tahun_pakai);
					$('input[name="nilai_penyusutan"]').val(response.data.nilai_penyusutan);
					$('input[name="item_code"]').val(response.data.item_code);
					$('input[name="item_name"]').val(response.data.item_name);
					$('input[name="uom"]').val(response.data.uom);
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

	function Fn_Toggle_Status(SysId) {
		Swal.fire({
			title: 'System message!',
			text: `Apakah anda yakin untuk merubah status data ini ?`,
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
						table: 'tmst_item_asset'
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

	$(document).on('click', '#back', function () {
		form_state('BACK');
	});

	// ======================================================== //
	// --------------------- FUNGSI ADD ----------------- //
	

	// --------- ADD SAVE --------- //

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
	// ------------------------------------ END FORM VALIDATION

	function Fn_Submit_Form() {
		BtnSubmit.prop("disabled", true);
		var formDataa = new FormData(MainForm[0]);
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Asset/ListAsset/store",
			data: formDataa,
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: response.msg,
						showCancelButton: false,
					}).then((result) => {
						form_state('LOAD');
					})
					//$(MainForm)[0].reset();
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
				BtnSubmit.prop("disabled", false);
			}
		});
	}
	// ------- ADD SAVE - END --------- //
	// ------------------- FUNGSI ADD - END ----------------- //
	// ======================================================== //
});
