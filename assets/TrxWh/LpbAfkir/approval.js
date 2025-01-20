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
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/LpbAfkir/DT_List_Lpb_Afkir_Approval",
			dataType: "json",
			type: "POST",
		},
		columns: [{
				data: "SysId",
				name: "SysId",
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: "Doc_Afkir",
				name: "Doc_Afkir",
			},
			{
				data: "Date_Afkir",
				name: "Date_Afkir",
			},
			{
				data: "tot_Pcs",
				name: "tot_Pcs",
				render: function (data) {
					return roundToTwoDecimals(data)
				}
			},
			{
				data: "tot_Cubication",
				name: "tot_Cubication",
				render: function (data) {
					return roundToFourDecimals(data)
				}
			},
			{
				data: "Note",
				name: "Note",
			},
			{
				data: "Is_Cancel",
				name: "Is_Cancel",
				render: function (data, type, row, meta) {
					if (data == 1) {
						return `<span class="badge badge-danger">cancel</span>`
					} else {
						return `<span class="badge badge-success">open</span>`
					}
				}
			},
			{
				data: "Is_Approve",
				name: "Is_Approve",
				render: function (data, type, row, meta) {
					if (data == 0) {
						return `<i class="fas fa-question text-dark"></i>`
					} else if (data == 1) {
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
				targets: [0, 1, 2, 5, 6, 7],
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
			text: `<i class="fas fa-check fs-3"></i> Approve`,
			className: "bg-success",
			action: function (e, dt, node, config) {
				var RowData = dt.rows({
					selected: true
				}).data();
				Fn_Toggle_Status(RowData[0].SysId, 1, 'Approve')
			}
		}, {
			text: `<i class="fas fa-times fs-3"></i> Reject`,
			className: "bg-danger",
			action: function (e, dt, node, config) {
				var RowData = dt.rows({
					selected: true
				}).data();
				Fn_Toggle_Status(RowData[0].SysId, 2, 'Reject')
			}
		}, {
			text: `<i class="fas fa-search fs-3"></i> View Detail`,
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
		}],
	}).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');


	function Fn_Toggle_Status(SysId, Param, Action_quote) {
		Swal.fire({
			title: 'System message!',
			text: `Apakah anda yakin untuk melakukan ${Action_quote} pada data terpilih ?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: `Ya, ${Action_quote}!`
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: $('meta[name="base_url"]').attr('content') + "TrxWh/LpbAfkir/verify",
					type: "post",
					dataType: "json",
					data: {
						SysId: SysId,
						Param: Param
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
		window.location.href = `${$('meta[name="base_url"]').attr('content')}TrxWh/LpbAfkir/edit/${SysId}/preview`
	}


})
