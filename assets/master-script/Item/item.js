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

	var TableData = $("#DataTable").DataTable({
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
			url: $('meta[name="base_url"]').attr('content') + "MasterData/Item/DT_listofitem",
			dataType: "json",
			type: "POST",
		},
		columns: [
			// {
			//     data: "SysId",
			//     name: "SysId",
			//     render: function (data, type, row, meta) {
			//         return meta.row + meta.settings._iDisplayStart + 1;
			//     }
			// },
			{
				data: "Item_Code",
				name: "Item_Code",
			},
			{
				data: "Item_Name",
				name: "Item_Name",
			},
			{
				data: "Item_Color",
				name: "Item_Color",
			},
			{
				data: "Brand",
				name: "Brand",
			},
			{
				data: "Model",
				name: "Model",
			},
			{
				data: "Item_Category",
				name: "Item_Category",
			},
			{
				data: "Group_Name",
				name: "Group_Name",
			},
			{
				data: "Barcode_Pattern",
				name: "Barcode_Pattern",
				visible: false,
			},
			{
				data: "Uom",
				name: "Uom",
			},
			{
				data: "Default_Currency_Id",
				name: "Default_Currency_Id",
			},
			{
				data: "Is_Expenses",
				name: "Is_Expenses",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<i class="fas fa-check text-success"></i>`
					} else {
						return `<i class="fas fa-times text-danger"></i>`
					}
				}
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
			},
		],
		order: [
			[0, "desc"]
		],
		columnDefs: [{
				className: "text-center",
				targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
			},
			{
				className: "text-left",
				targets: []
			}
		],
		autoWidth: false,
		// responsive: true,
		rowCallback: function (row, data, index) {
			// Gantilah 'yourColumnName' dengan nama kolom Anda
			if (data.Is_Active == 0) {
				$(row).css('background-color', '#F8D7DA');
			}
		},
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
		"buttons": [{
			text: `<i class="fas fa-plus fs-3"></i> Add Item`,
			className: "bg-primary",
			action: function (e, dt, node, config) {
				window.location.href = $('meta[name="base_url"]').attr('content') + "MasterData/Item/add"
			}
		}, {
			text: `<i class="fas fa-paste fs-3"></i> Item Code Customer`,
			className: "btn bg-gradient-success",
			action: function (e, dt, node, config) {
				var RowData = dt.rows({
					selected: true
				}).data();
				if (RowData.length == 0) {
					Swal.fire({
						icon: 'warning',
						title: 'Ooppss...',
						text: 'Silahkan pilih data untuk Memanage Kode Item Customer !',
						footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
					});
				} else {
					Init_Modal_List_Item_Alias(RowData[0].Item_Code, RowData[0].Item_Name)
				}
			}
		}, {
			text: `<i class="fas fa-edit fs-3"></i> Edit`,
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
					Init_Show_Detail(RowData[0].SysId)
				}
			}
		}, {
			text: `<i class="fas fa-toggle-on"></i> Active/In-active`,
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
		}, {
			text: `Export to :`,
			className: "btn disabled text-dark bg-white",
		}, {
			text: `<i class="far fa-file-excel"></i>`,
			extend: 'excelHtml5',
			title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
			className: "btn btn-success",
		}, {
			text: `<i class="far fa-file-pdf"></i>`,
			extend: 'pdfHtml5',
			title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
			className: "btn btn-danger",
			orientation: "landscape"
		}],
	}).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');


	// $(document).on('click', '.main_image', function () {
	//     let SysId = $(this).attr('data-pk');
	//     $.ajax({
	//         type: "GET",
	//         url: $('meta[name="base_url"]').attr('content') + "Backend/OurProduct/ShowMainImage",
	//         data: {
	//             SysId: SysId
	//         },
	//         beforeSend: function () {
	//             Swal.fire({
	//                 title: 'Loading....',
	//                 html: '<div class="spinner-border text-primary"></div>',
	//                 showConfirmButton: false,
	//                 allowOutsideClick: false,
	//                 allowEscapeKey: false
	//             })
	//         },
	//         success: function (response) {
	//             Swal.close()
	//             $('#location').html(response);
	//             $('#ModalShowImage').modal('show');
	//         },
	//         error: function (xhr, status, error) {
	//             var statusCode = xhr.status;
	//             var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
	//             Swal.fire({
	//                 icon: "error",
	//                 title: "Error!",
	//                 html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
	//             });
	//         }
	//     });
	// })

	function Fn_Toggle_Status(SysId) {
		Swal.fire({
			title: 'System message!',
			text: `Apakah anda yakin untuk merubah status item ini ?`,
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
						table: 'tmst_item'
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

	function Init_Modal_List_Item_Alias(Item_Code, Item_Name) {
		$('#input-form').hide()
		$('.modal-title').text('Item Code Alias Milik Customer : ' + Item_Code)
		$('#Item_Code_Internal').val(Item_Code)
		$('#Item_Name_Internal').val(Item_Name)

		$("#DataTable_Alias").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
			lengthMenu: [
				[10000],
				['All']
			],
			select: true,
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "MasterData/Item/DT_list_item_alias",
				dataType: "json",
				type: "POST",
				data: {
					Item_Code: $('#Item_Code_Internal').val()
				}
			},
			columns: [{
					data: "ID",
					name: "ID",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					data: "Account_ID",
					name: "Account_ID",
					visible: false,
				},
				{
					data: "Account_Name",
					name: "Account_Name",
				},
				{
					data: "Item_CodeAlias",
					name: "Item_CodeAlias",
				},
				{
					data: "Item_NameAlias",
					name: "Item_NameAlias",
				}
			],
			order: [
				[0, "desc"]
			],
			columnDefs: [{
					className: "text-center",
					targets: [0, 3],
				},
				{
					className: "text-left",
					targets: []
				}
			],
			autoWidth: false,
			responsive: true,
			preDrawCallback: function () {
				$("#DataTable_Alias tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#DataTable_Alias tbody td").addClass("blurry");
				setTimeout(function () {
					$("#DataTable_Alias tbody td").removeClass("blurry");
				});
				$('[data-toggle="tooltip"]').tooltip();
			},
			initComplete: function (settings, json) {
				// ---------------
			},
			"buttons": [{
				text: `<i class="fas fa-plus fs-3"></i> Add`,
				className: "bg-primary",
				action: function (e, dt, node, config) {
					Fn_init_form_alias_add('ADD')
				}
			}, {
				text: `<i class="fas fa-edit fs-3"></i> Edit`,
				className: "bg-warning",
				action: function (e, dt, node, config) {
					var RowData = dt.rows({
						selected: true
					}).data();

					if (RowData.length == 0) {
						Swal.fire({
							icon: 'warning',
							title: 'Ooppss...',
							text: 'Silahkan pilih data untuk edit Kode Item Customer !',
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
						});
					} else {
						Fn_init_form_alias_edit('EDIT', RowData[0])
					}
				}
			}],
		}).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');
		$('#modal_item_code_alias').modal('show')
	}

	function Fn_init_form_alias_add(state) {
		$('#state').val(state);
		$('#input-form').show("slow");

		$('#SysId').val('');
		$("#Account_ID").select2().val('').trigger('change');
		$('#Account_ID').val('');
		$('#Item_CodeAlias').val('');
		$('#Item_NameAlias').val('');
	}

	function Fn_init_form_alias_edit(state, RowData) {
		$('#state').val(state);
		$('#input-form').show("slow");

		$('#SysId').val(RowData.ID);
		// $('#Account_ID').val(RowData.Account_ID);
		$("#Account_ID").select2().val(RowData.Account_ID).trigger('change');

		$('#Item_CodeAlias').val(RowData.Item_CodeAlias);
		$('#Item_NameAlias').val(RowData.Item_NameAlias);
	}

	$(document).on('click', '#cancel_form', function () {
		$('#input-form').hide("slow");
	})

	function Init_Show_Detail(SysId) {
		window.location.href = `${$('meta[name="base_url"]').attr('content')}MasterData/Item/edit/${SysId}`
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
			Fn_Submit_Form(DtlForm)
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	function Fn_Submit_Form() {
		BtnDtlSubmit.prop("disabled", true);
		var formDataa = new FormData(DtlForm[0]);
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "MasterData/Item/store_item_alias",
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
						$("#DataTable_Alias").DataTable().ajax.reload(null, false);
						$('#input-form').hide("slow");
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
});
