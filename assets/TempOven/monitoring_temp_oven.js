$(document).ready(function () {
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		width: 300,
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.addEventListener('mouseenter', Swal.stopTimer)
			toast.addEventListener('mouseleave', Swal.resumeTimer)
		}
	})

	// ----------------------------------------------------------------
	var TableData = $('#DataTable').DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		// select: true,
		dom: 'lBfrtip',
		"oLanguage": {
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
		},
		"aLengthMenu": [
			[15, 25, 50, 100],
			[15, 25, 50, 100]
		],
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/DataTable_Monitoring_Hdr_Temp",
			dataType: "json",
			type: "GET",
		},
		columns: [{
			data: 'SysId',
			name: "SysId",
			orderable: false,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			}
		}, {
			data: "Doc_No",
			name: "Doc_No",
		}, {
			data: "nama_oven",
			name: "nama_oven",
		}, {
			data: "Doc_Date",
			name: "Doc_Date",
		}, {
			data: "Doc_Status",
			name: "Doc_Status",
			render: function (data, type, row, meta) {
				if (data == 'RUN') {
					return `<button class="btn btn-xs bg-gradient-success">${data}</button>`;
				} else if (data == 'WAIT') {
					return `<button class="btn btn-xs bg-gradient-warning">${data}</button>`;
				} else if (data == 'OFF') {
					return `<button class="btn btn-xs bg-gradient-danger">${data}</button>`;
				} else {
					return `<button class="btn btn-xs bg-gradient-primary">${data}</button>`;
				}
			}
		}, {
			data: "nama_pj_oven",
			name: "nama_pj_oven",
		}, {
			data: "nama_maintenance",
			name: "nama_maintenance",
		}, {
			data: "nama_m_teknik",
			name: "nama_m_teknik",
		}, {
			data: "SysId",
			name: "SysId",
			render: function (data, type, row, meta) {
				if (row.Doc_Status != 'RUN') {
					// `<button class="btn btn-xs bg-gradient-danger btn-delete" data-pk="${row.SysId}" data-toggle="tooltip" title="Delete"><i class="fas fa-trash"></i></button>&nbsp;` +
					return `<button class="btn btn-xs bg-gradient-success btn-run-again" data-pk="${row.SysId}" data-toggle="tooltip" title="Jalankan Ulang"><i class="fas fa-recycle"></i></button>&nbsp;` +
						`<button class="btn btn-xs bg-gradient-primary btn-print-hdr" data-pk="${row.SysId}" data-toggle="tooltip" title="Print Form Checklist"><i class="fas fa-clipboard-check"></i></button>&nbsp;` +
						`<button class="btn btn-xs bg-gradient-primary btn-print-dtl" data-pk="${row.SysId}" data-toggle="tooltip" title="Print List Temperatur"><i class="fas fa-file-alt"></i></button>`
				}
			}
		}],
		order: [
			[2, "desc"]
		],
		columnDefs: [{
			className: "text-center",
			targets: [0, 1, 2, 3, 4, 5, 6, 7, 8],
		}],
		autoWidth: false,
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
		"buttons": ["copy",
			{
				extend: 'csvHtml5',
				title: $('title').text() + '_' + moment().format('LL'),
				className: "btn btn-info",
			}, {
				extend: 'excelHtml5',
				title: $('title').text() + '_' + moment().format('LL'),
				className: "btn btn-success",
			}, {
				extend: 'pdfHtml5',
				title: $('title').text() + '_' + moment().format('LL'),
				className: "btn btn-danger",
			}, "print"
		],
	}).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');

	$(document).on('click', '.btn-delete', function () {
		let SysId = $(this).attr('data-pk');
		Swal.fire({
			title: 'System Message!',
			text: `Apakah anda yakin untuk menghapus data dokumen temperatur ini ?`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/delete_doc_temperature",
					data: {
						SysId: SysId,
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
						Swal.close()
						if (response.code == 200) {
							Toast.fire({
								icon: 'success',
								title: response.msg
							});
							$("#DataTable").DataTable().ajax.reload(null, false)
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Ooops...',
								text: response.msg,
								footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
							});
						}
					},
					error: function () {
						Swal.close()
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Terjadi kesalahan teknis segera lapor pada admin!',
							footer: '<a href="javascript:void(0)">Notifikasi System</a>'
						});
					}
				});
			}
		})
	})

	$(document).on('click', '.btn-run-again', function () {
		let SysId = $(this).attr('data-pk');
		Swal.fire({
			title: 'System Message!',
			text: `Apakah anda yakin untuk menjalankan ulang pencatatan temperatur pada dokumen ini ?`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/re_run_temp_doc",
					data: {
						SysId: SysId,
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
						Swal.close()
						if (response.code == 200) {
							Toast.fire({
								icon: 'success',
								title: response.msg
							});
							$("#DataTable").DataTable().ajax.reload(null, false)
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Ooops...',
								text: response.msg,
								footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
							});
						}
					},
					error: function () {
						Swal.close()
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Terjadi kesalahan teknis segera lapor pada admin!',
							footer: '<a href="javascript:void(0)">Notifikasi System</a>'
						});
					}
				});
			}
		})
	});

	$(document).on('click', '.btn-print-hdr', function () {
		let SysId = $(this).attr('data-pk');
		return window.open($('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/Print_FormChecklist_Oven/" + SysId, 'WindowReport-Oven-Hdr', 'width=800,height=600');
	});

	$(document).on('click', '.btn-print-dtl', function () {
		let SysId = $(this).attr('data-pk');
		return window.open($('meta[name="base_url"]').attr('content') + "TempOven/TemperaturOven/Print_List_Temperature/" + SysId, 'WindowReport-Oven-Hdr', 'width=600,height=750');
	});
})
