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
			url: $('meta[name="base_url"]').attr('content') + "MasterData/ChartOfAccount/DT_list_chartofaccount",
			dataType: "json",
			type: "POST",
			data: {
				IdentityPattern: $('#IdentityPattern').val()
			}
		},
		columns: [
			{
				data: "SysId",
				name: "SysId",
			},			
			{
				data: "kode_akun",
				name: "kode_akun",
			},
			{
				data: "nama_akun",
				name: "nama_akun",
			},
			{
				data: "akun_induk",
				name: "akun_induk",
			},
			{
				data: "Is_Active",
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
			targets: [0, 1, 4],
		},
		{
			visible: false,
			targets: [0]
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
			text: `<i class="fas fa-plus fs-3"> Add COA</i>`,
			className: "bg-primary",
			action: function (e, dt, node, config) {
				window.location.href = $('meta[name="base_url"]').attr('content') + "MasterData/ChartOfAccount/add/"
			}
		}, 
		// {
		// 	text: `<i class="fas fa-edit fs-3"></i>`,
		// 	className: "btn btn-warning",
		// 	action: function (e, dt, node, config) {
		// 		var RowData = dt.rows({
		// 			selected: true
		// 		}).data();
		// 		if (RowData.length == 0) {
		// 			Swal.fire({
		// 				icon: 'warning',
		// 				title: 'Ooppss...',
		// 				text: 'Silahkan pilih data untuk melihat detail !',
		// 				footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
		// 			});
		// 		} else {
		// 			Init_Show_Detail(RowData[0].SysId)
		// 		}
		// 	}
		// }, 
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
					Fn_Toggle_Status(parseInt(RowData[0].SysId))
				}
			}
		}, {
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
						table: 'tmst_chart_of_account'
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

	function Init_Show_Detail(sysid) {
		window.location.href = `${$('meta[name="base_url"]').attr('content')}MasterData/ChartOfAccount/edit/${sysid}`
	}
});
