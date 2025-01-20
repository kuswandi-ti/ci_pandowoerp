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

	var TableData = $("#TableData").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		paging: true,
		// select: true,
		"responsive": true,
		// dom: 'lBfrtip',
		// "oLanguage": {
		// 	"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
		// },
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "DnOutstanding/DT_OutStanding_DN",
			dataType: "json",
			type: "post",
		},
		columns: [{
				data: "SysId_Hdr",
				name: "SysId_Hdr",
				orderable: false,
				visible: false,
			},
			{
				data: "SysId_Dtl",
				name: "SysId_Dtl",
				orderable: false,
				render: function (data, type, row, meta) {
					return `<div class="btn btn-group">
						<button data-toggle="tooltip" title="DELETE DN" class="btn bg-gradient-danger btn-sm btn-delete" data-pk="${row.SysId_Hdr}"><i class="fas fa-trash-alt"></i></button>&nbsp;
						<button data-toggle="tooltip" title="List data loading per-product" class="btn bg-gradient-info btn-sm btn-loading" data-pk="${data}"><i class="fas fa-truck-loading"></i></button>&nbsp;
						<a href="${$('meta[name="base_url"]').attr('content')}CompleteDN/Print_DN/${row.DN_Number}" target="_blank" data-toggle="tooltip" title="Print Dn" class="btn bg-gradient-danger btn-sm btn-print" data-pk="${data}"><i class="fas fa-print"></i></a>
						</div>`
				}
			},
			{
				data: "DN_Number",
				name: "DN_Number",
				render: function (data, type, row, meta) {
					return `<pre>${data}</pre>`
				}
			},
			{
				data: "Customer_Code",
				name: "Customer_Code",
			},
			{
				data: "Customer_Name",
				name: "Customer_Name",
			},
			{
				data: "No_PO_Customer",
				name: "No_PO_Customer",
				render: function (data, type, row, meta) {
					return `<pre>${data}</pre>`
				}
			},
			{
				data: "No_PO_Internal",
				name: "No_PO_Internal",
				render: function (data, type, row, meta) {
					return `<pre>${data}</pre>`
				}
			},
			{
				data: "Product_Code",
				name: "Product_Code",
			},
			{
				data: "Product_Name",
				name: "Product_Name",
			},
			{
				data: "Qty",
				name: "Qty",
			},
			{
				data: "Uom",
				name: "Uom",
			},
			{
				data: "Send_Date",
				name: "Send_Date",
			},
			{
				data: "Complete_Address",
				name: "Complete_Address",
				render: function (data, type, row, meta) {
					return `<pre>${data}</pre>`
				}
			},
			{
				data: "Att_To",
				name: "Att_To",
			},
			{
				data: "Vehicle_Police_Number",
				name: "Vehicle_Police_Number",
			},
			{
				data: "Driver_Name",
				name: "Driver_Name",
			},
			{
				data: "Remark",
				name: "Remark",
			},
		],
		"order": [
			[11, "desc"]
		],
		columnDefs: [{
				className: "text-center",
				targets: [0, 1, 2, 3, 4, , 5, 7, 8, 9, 10, 11, 13, 14, 15, 16],
			},
			{
				className: "text-left",
				targets: []
			}
		],
		autoWidth: false,
		preDrawCallback: function () {
			$("#TableData tbody td").addClass("blurry");
		},
		language: {
			processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
		},
		drawCallback: function () {
			$("#TableData tbody td").addClass("blurry");
			setTimeout(function () {
				$("#TableData tbody td").removeClass("blurry");
			});
			$('[data-toggle="tooltip"]').tooltip();
		}
	})

	$(document).on('click', '.btn-loading', function () {
		let SysId_Dtl = $(this).attr('data-pk');

		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "DnOutstanding/Dn_Vs_Loading",
			data: {
				SysId_Dtl: SysId_Dtl
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
				$('#location').html(response);
				$('#m_list_loading_product').modal('show');
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
	});

	$(document).on('click', '.btn-delete', function () {
		let SysId = $(this).attr('data-pk');
		var data = TableData.row($(this).parents('tr')).data();
		Swal.fire({
			title: 'Delete data',
			text: `Anda akan menghapus data DN : ${data.DN_Number}, beserta item yang ada di dalamnya !`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Delete!',
			cancelButtonText: 'Cancel'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "DnOutstanding/Delete_DN",
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
						Swal.close()
						if (response.code == 200) {
							Toast.fire({
								icon: 'success',
								title: response.msg
							});
							$("#TableData").DataTable().ajax.reload(null, false)
						} else {
							Toast.fire({
								icon: 'error',
								title: response.msg
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

})
