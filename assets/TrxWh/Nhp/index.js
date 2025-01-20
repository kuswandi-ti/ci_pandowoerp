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
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/NotaHasilProduksi/DT_List_Nap",
			dataType: "json",
			type: "POST",
		},
		columns: [{
			data: "SysId",
			name: "SysId",
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			}
		}, {
			data: "DocNo",
			name: "DocNo",
		}, {
			data: "DocDate",
			name: "DocDate",
		}, {
			data: "Note",
			name: "Note",
		}, {
			data: "isCancel",
			name: "isCancel",
			render: function (data, type, row, meta) {
				if (data == 1) {
					return `<span class="badge badge-danger">cancel</span>`
				} else {
					return `<span class="badge badge-success">open</span>`
				}
			}
		}, {
			data: "Approval_Status",
			name: "Approval_Status",
			render: function (data, type, row, meta) {
				if (data == 0) {
					return `<i class="fas fa-question text-dark"></i>`
				} else if (data == 1) {
					return `<i class="fas fa-check text-success"></i>`
				} else {
					return `<i class="fas fa-times text-danger"></i>`
				}
			}
		},],
		order: [
			[0, "desc"]
		],
		columnDefs: [{
			className: "text-center align-middle",
			targets: [0, 1, 2, 4, 5],
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
			if (data.isCancel == 1) {
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
			text: `<i class="fas fa-plus fs-3"></i> Add Nota`,
			className: "bg-primary",
			action: function (e, dt, node, config) {
				window.location.href = $('meta[name="base_url"]').attr('content') + "TrxWh/NotaHasilProduksi/add"
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
				} else if (RowData[0].isCancel == 1 || RowData[0].Approval_Status == 2 || RowData[0].Approval_Status == 1) {
					Swal.fire({
						icon: 'warning',
						title: 'Ooppss...',
						text: 'Data tidak dapat di ubah !',
						footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
					});
				} else {
					window.location.href = `${$('meta[name="base_url"]').attr('content')}TrxWh/NotaHasilProduksi/edit/${RowData[0].SysId}/update`
				}
			}
		}, {
			text: `<i class="fas fa-search fs-3"></i> View Detail`,
			className: "btn btn-info",
			action: function (e, dt, node, config) {
				var RowData = dt.rows({
					selected: true
				}).data();
				if (RowData.length == 0) {
					Swal.fire({
						icon: 'warning',
						title: 'Ooppss...',
						text: 'Pilih data terlebih dahulu !',
						footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
					});
				} else {
					window.location.href = `${$('meta[name="base_url"]').attr('content')}TrxWh/NotaHasilProduksi/edit/${RowData[0].SysId}/preview`
				}
			}
		},
		// {
		// 	text: `<i class="fas fa-print fs-3"></i>`,
		// 	className: "btn bg-gradient-success",
		// 	action: function (e, dt, node, config) {
		// 		var RowData = dt.rows({
		// 			selected: true
		// 		}).data();
		// 		if (RowData.length == 0) {
		// 			return Swal.fire({
		// 				icon: 'warning',
		// 				title: 'Ooppss...',
		// 				text: 'Silahkan pilih data terlebih dahulu !',
		// 				footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
		// 			});
		// 		} else if (RowData[0].isCancel == 1 || RowData[0].Approval_Status == 2 || RowData[0].Approval_Status == 0) {
		// 			Swal.fire({
		// 				icon: 'warning',
		// 				title: 'Ooppss...',
		// 				text: 'Report cetak hanya bisa di tampilkan pada data yang telah legitimate (tidak cancel dan sudah approve)!',
		// 				footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
		// 			});
		// 		} else {
		// 			alert('dalam tahap development');
		// 			// window.open($('meta[name="base_url"]').attr('content') + `TrxWh/LpbAfkir/print_tempelan/${RowData[0].SysId}`, 'WindowReport-LpbAfkir', 'width=800,height=600');
		// 		}
		// 	}
		// }, 
		{
			text: `<i class="fas fa-times"></i> Cancel`,
			className: "btn btn-dark",
			action: function (e, dt, node, config) {
				var RowData = dt.rows({
					selected: true
				}).data();
				if (RowData.length == 0 || RowData[0].isCancel == 1) {
					Swal.fire({
						icon: 'warning',
						title: 'Ooppss...',
						text: 'Silahkan pilih data yang akan di cancel !',
						footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
					});
				} else {
					Fn_Cancel(RowData[0].SysId)
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
		}
		],
	}).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');

	function Fn_Cancel(SysId) {
		Swal.fire({
			title: 'System message!',
			text: `Apakah anda yakin untuk melakukan cancel pada dokumen ini ?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Cancel!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: $('meta[name="base_url"]').attr('content') + "TrxWh/NotaHasilProduksi/Cancel",
					type: "post",
					dataType: "json",
					data: {
						SysId: SysId
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
});
