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
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/DT_Lpb_ToApprove",
			dataType: "json",
			type: "POST",
		},
		columns: [{
				data: "sysid",
				name: "sysid",
				visible: true,
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: "lpb",
				name: "lpb",
			},
			{
				data: "supplier",
				name: "supplier",
			},
			{
				data: "tgl_kirim",
				name: "tgl_kirim",
			},
			{
				data: "tgl_finish_sortir",
				name: "tgl_finish_sortir",
				render: function (data, type, row, meta) {
					if (row.tgl_finish_sortir == '0000-00-00') {
						return null;
					} else {
						return row.tgl_finish_sortir;
					}
				}
			},
			{
				data: "grader",
				name: "grader",
			},
			{
				data: "lot",
				name: "lot",
				searchable: false,
				orderable: false,
			},
			{
				data: "legalitas",
				name: "legalitas",
				searchable: false,
				orderable: false,
			},
			{
				data: "status_lpb",
				name: "status_lpb",
				visible: false
			},
			{
				data: "kubikasi",
				name: "kubikasi",
				searchable: false,
				orderable: false,
				render: function (data) {
					return roundToFourDecimals(data)
				}
			},
			{
				data: null,
				name: "handle",
				searchable: false,
				orderable: false,
				render: function (data, type, row, meta) {
					return `<a href="${$('meta[name="base_url"]').attr('content')}TrxWh//Lpb/report_tally_sheet/${row.lpb}" class="btn btn-sm bg-gradient-success" data-toggle="tooltip" title="" target="_blank" data-original-title="Draft Lembar Bahan Baku diterima"><i class="far fa-folder-open"></i> Tally Sheet</a>`
				}
			}

		],
		order: [
			[0, "desc"]
		],
		columnDefs: [{
				className: "text-center",
				targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
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
			text: `<i class="fas fa-check fs-3"></i> Approve`,
			className: "bg-success",
			attr: {
				title: "Approve Lpb",
				"data-toggle": "tooltip"
			},
			action: function (e, dt, node, config) {
				var RowData = dt.rows({
					selected: true
				}).data();
				if (RowData[0] != undefined) {
					Fn_Toggle_Status(RowData[0].sysid, RowData[0].lpb, 'SELESAI', 'Apakah anda yakin untuk melakukan approve pada data terpilih')
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: `Anda belum memilih data !`,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ok, Confirm',
						footer: '<a href="javascript:void(0)">Notification System</a>'
					});
				}
			}
		}, {
			text: `<i class="fas fa-undo-alt fs-3"></i> Revisi`,
			className: "bg-danger",
			attr: {
				title: "Revisi Lpb",
				"data-toggle": "tooltip"
			},
			action: function (e, dt, node, config) {
				var RowData = dt.rows({
					selected: true
				}).data();
				if (RowData[0] != undefined) {
					Fn_Toggle_Status(RowData[0].sysid, RowData[0].lpb, 'BUKA', 'Apakah anda yakin untuk Revisi dan  mengembalikan data terpilih ke proses grid')
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: `Anda belum memilih data !`,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ok, Confirm',
						footer: '<a href="javascript:void(0)">Notification System</a>'
					});
				}
			}
		}, {
			text: `<i class="fas fa-search fs-3"></i> View Detail`,
			className: "btn btn-info",
			attr: {
				title: "Detail Preview",
				"data-toggle": "tooltip"
			},
			action: function (e, dt, node, config) {
				var RowData = dt.rows({
					selected: true
				}).data();
				console.log(RowData)
				if (RowData.length == 0) {
					Swal.fire({
						icon: 'warning',
						title: 'Ooppss...',
						text: 'Silahkan pilih data untuk melihat detail !',
						footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
					});
				} else {
					Init_Show_Detail(RowData[0].lpb)
				}
			}
		}],
	}).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');

	function Fn_Toggle_Status(SysId, Lpb, Param, Action_quote) {
		Swal.fire({
			title: 'System message!',
			text: `${Action_quote} ?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: `Ya, Confirm!`
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/verify",
					type: "post",
					dataType: "json",
					data: {
						sysid: SysId,
						lpb: Lpb,
						Param: Param,
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
								html: response.msg,
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

	function Init_Show_Detail(lpb) {
		window.location.href = `${$('meta[name="base_url"]').attr('content')}TrxWh/ProcessGrid/check_detail_lpb/${lpb}/preview`
	}
});
