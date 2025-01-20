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
			[50, 10000],
			[50, 'All']
		],
		select: true,
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "MasterData/Warehouse/DT_list_warehouse",
			dataType: "json",
			type: "POST",
		},
		columns: [{
				data: "Warehouse_ID",
				name: "Warehouse_ID",
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: "Warehouse_Code",
				name: "Warehouse_Code",
			},
			{
				data: "Warehouse_Name",
				name: "Warehouse_Name",
			},
			{
				data: "Item_Category",
				name: "Item_Category",
			},
			{
				data: "Description",
				name: "Description",
			},
			{
				data: "Is_Entry_Wh",
				name: "Is_Entry_Wh",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<i class="fas fa-check text-success"></i>`
					} else {
						return `<i class="fas fa-times text-danger"></i>`
					}
				}
			}, {
				data: "Is_Source_Allocation",
				name: "Is_Source_Allocation",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<i class="fas fa-check text-success"></i>`
					} else {
						return `<i class="fas fa-times text-danger"></i>`
					}
				}
			}, {
				data: "Is_Source_Shp",
				name: "Is_Source_Shp",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<i class="fas fa-check text-success"></i>`
					} else {
						return `<i class="fas fa-times text-danger"></i>`
					}
				}
			}, {
				data: "Is_Afkir",
				name: "Is_Afkir",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<i class="fas fa-check text-success"></i>`
					} else {
						return `<i class="fas fa-times text-danger"></i>`
					}
				}
			}, {
				data: "Is_Trading_Wh",
				name: "Is_Trading_Wh",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<i class="fas fa-check text-success"></i>`
					} else {
						return `<i class="fas fa-times text-danger"></i>`
					}
				}
			}, {
				data: "Is_Receive_Grid",
				name: "Is_Receive_Grid",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<i class="fas fa-check text-success"></i>`
					} else {
						return `<i class="fas fa-times text-danger"></i>`
					}
				}
			}, {
				data: "Is_Kiln",
				name: "Is_Kiln",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<i class="fas fa-check text-success"></i>`
					} else {
						return `<i class="fas fa-times text-danger"></i>`
					}
				}
			}, {
				data: "Is_Wh_After_Kiln",
				name: "Is_Wh_After_Kiln",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<i class="fas fa-check text-success"></i>`
					} else {
						return `<i class="fas fa-times text-danger"></i>`
					}
				}
			}, {
				data: "Is_Active",
				name: "Is_Active",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<button class="badge badge-success"><i class="fas fa-check"></i> Active</button>`
					} else {
						return `<button class="badge badge-danger"><i class="fas fa-times"></i> not-active</button>`
					}
				}
			}
		],
		order: [
			[0, "desc"]
		],
		columnDefs: [{
				className: "text-center",
				targets: [0, 5, 6, 7, 8, 9, 10, 11],
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
				$(row).css('background-color', '#525c66');
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
			text: `<i class="fas fa-plus fs-3"></i> Add Warehouse`,
			className: "bg-primary",
			action: function (e, dt, node, config) {
				window.location.href = $('meta[name="base_url"]').attr('content') + "MasterData/Warehouse/add"
			}
		}, {
			text: `<i class="fas fa-edit fs-3"></i>`,
			className: "btn disabled text-dark bg-white",
			action: function (e, dt, node, config) {

			}
		}, {
			text: `<i class="fas fa-toggle-on"></i> Active/In-Active`,
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
					Fn_Toggle_Status(RowData[0].Warehouse_ID)
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

	function Fn_Toggle_Status(Warehouse_ID) {
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
					url: $('meta[name="base_url"]').attr('content') + "MasterData/Warehouse/Toggle_Status",
					type: "post",
					dataType: "json",
					data: {
						Warehouse_ID: Warehouse_ID,
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

	function Init_Show_Detail(SysId) {
		window.location.href = `${$('meta[name="base_url"]').attr('content')}/MasterData/Item/edit/${SysId}`
	}
});
