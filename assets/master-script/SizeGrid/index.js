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

	$('#search').on('click', function () {
		let item_id = $('#Item_ID').val();

		if (item_id.length == 0) {
			return Swal.fire({
				icon: 'warning',
				title: 'Oops...',
				text: 'Harap pilih item code terlebih dahulu',
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'OK',
				footer: '<a href="javascript:void(0)">Notification System</a>'
			});
		}
		Init_DT(item_id)
	})



	// function Init_DT(item_id) {
	var TableData = $("#DataTable").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
		lengthMenu: [
			[15, 50, 250, 500, 1000000000000000000],
			[15, 50, 250, 500, 'ALL']
		],
		select: true,
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "MasterData/SizeGrid/DT_size_grid",
			dataType: "json",
			type: "POST",
			// data: {
			// 	item_id: item_id
			// }
		},
		columns: [{
				data: "SysId",
				name: "SysId",
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: "Size_Code",
				name: "Size_Code",
			},
			{
				data: "Item_Height",
				name: "Item_Height",
			},
			{
				data: "Item_Width",
				name: "Item_Width",
			},
			{
				data: "Item_Length",
				name: "Item_Length",
			},
			{
				data: "Cubication",
				name: "Cubication",
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
			[1, 'asc'],
			[2, 'asc'],
			[3, 'asc'],
		],
		columnDefs: [{
				className: "text-center align-middle",
				targets: [0, 1, 2, 3, 4, 5, 6],
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
		"buttons": [{
			text: `<i class="fas fa-plus fs-3"></i> Tambah Ukuran`,
			className: "bg-primary",
			action: function (e, dt, node, config) {
				window.location.href = $('meta[name="base_url"]').attr('content') + "MasterData/SizeGrid/add"
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
	// }

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
						table: 'tmst_size_item_grid'
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
});
