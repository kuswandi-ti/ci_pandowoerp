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
			url: $('meta[name="base_url"]').attr('content') + "MasterData/Account/DT_list_account_need_verification",
			dataType: "json",
			type: "POST",
			data: {
				IdentityPattern: $('#IdentityPattern').val()
			}
		},
		columns: [
			// {
			//     data: "SysId",
			//     name: "SysId",
			//     render: function (data, type, row, meta) {
			//         return meta.row + meta.settings._iDisplayStart + 1;
			//     }
			// },, AccountTitle_Code, 
			{
				data: "Account_Code",
				name: "Account_Code",
			},
			{
				data: "Account_Name",
				name: "Account_Name",
			},
			{
				data: "Account_Address",
				name: "Account_Address",
			},
			{
				data: "Account_Phone1",
				name: "Account_Phone1",
			},
			{
				data: "Account_EmailAddress",
				name: "Account_EmailAddress",
			},
			{
				data: "TaxFileNumber",
				name: "TaxFileNumber",
			},
			{
				data: "BankCurrencyID",
				name: "BankCurrencyID",
			},
			{
				data: "Is_Verified",
				name: "Is_Verified",
				render: function (data, type, row, meta) {
					return `<i class="fas fa-question text-dark"></i>`

				}
			},
		],
		order: [
			[0, "desc"]
		],
		columnDefs: [{
			className: "text-center",
			targets: [0, 1, 2, 3, 4, 5, 6, 7],
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
			text: `<i class="fas fa-check"></i> Approve`,
			className: "btn btn-success",
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
					Fn_Toggle_Status(RowData[0].SysId, 1)
				}
			}
		}, {
			text: `<i class="fas fa-times"></i> Reject`,
			className: "btn btn-danger",
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
					Fn_Toggle_Status(RowData[0].SysId, 2)
				}
			}
		}, {
			text: `<i class="fas fa-search"></i> View Detail`,
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

	function Fn_Toggle_Status(SysId, $param) {
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
					url: $('meta[name="base_url"]').attr('content') + "MasterData/Account/verify",
					type: "post",
					dataType: "json",
					data: {
						sysid: SysId,
						is_verified: $param
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
		window.location.href = `${$('meta[name="base_url"]').attr('content')}/MasterData/Account/edit/${SysId}`
	}
});
