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

	function formatRupiah(moneyy) {
		let money = parseFloat(moneyy)
		return new Intl.NumberFormat('id-ID', {
			style: 'currency',
			currency: 'IDR'
		}).format(money);
	}

	var TableData = $("#TableData").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		paging: true,
		select: true,
		"responsive": true,
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "ApprovalInvoice/DT_Approval_Invoice",
			dataType: "json",
			type: "post",
		},
		columns: [{
				data: "SysId",
				name: "SysId",
				orderable: false,
				visible: false,
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			},
			{
				data: "Approve",
				name: "Approve",
				orderable: false,
				render: function (data, type, row, meta) {
					return `<div class="btn btn-group">
						<button data-toggle="tooltip" title="Approve" class="btn bg-gradient-success btn-sm btn-approve" data-pk="${row.Invoice_Number}"><i class="fas fa-check"></i></button>&nbsp;
                        <button data-toggle="tooltip" title="Detail Invoice" class="btn bg-gradient-primary btn-sm btn-detail" data-pk="${row.SysId}"><i class="fas fa-eye"></i></button>&nbsp;
                        <button data-toggle="tooltip" title="Reject" class="btn bg-gradient-danger btn-sm btn-reject" data-pk="${row.Invoice_Number}"><i class="fas fa-times"></i></button>
                        </div>`
				}
			},
			{
				data: "Invoice_Number",
				name: "Invoice_Number",
				render: function (data, type, row, meta) {
					return `<a href="javascript:void(0)" data-toggle="tooltip" title="Detail Invoice" class="btn-detail" data-pk="${row.SysId}">${data}</a>`
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
				data: "DN_Number",
				name: "DN_Number",
				render: function (data) {
					return `<a href="${$('meta[name="base_url"]').attr('content')}CompleteDN/Print_DN/${data}?preview=true"
					onclick="window.open('${$('meta[name="base_url"]').attr('content')}CompleteDN/Print_DN/${data}?preview=true','popup','width=600,height=800'); return false;" target="popup" data-toggle="tooltip" title="detail DN" rel="noopener">${data}</a>`;
				}
			},
			{
				data: "No_PO_Customer",
				name: "No_PO_Customer",
			},
			{
				data: "SO_Number",
				name: "SO_Number",
			},
			{
				data: "Invoice_Date",
				name: "Invoice_Date",
			},
			{
				data: "Due_Date",
				name: "Due_Date",
			},
			{
				data: "Item_Amount",
				name: "Item_Amount",
				render: function (data, type, row, meta) {
					return `<pre>${formatRupiah(data)}</pre>`
				}
			},
			{
				data: "PPN",
				name: "PPN",
			},
			{
				data: "PPN_Amount",
				name: "PPN_Amount",
				render: function (data, type, row, meta) {
					return `<pre>${formatRupiah(data)}</pre>`
				}
			},
			{
				data: "Invoice_Amount",
				name: "Invoice_Amount",
				render: function (data, type, row, meta) {
					return `<pre>${formatRupiah(data)}</pre>`
				}
			},
			{
				data: "NPWP",
				name: "NPWP",
			},
			{
				data: "Customer_Address",
				name: "Customer_Address",
				render: function (data, type, row, meta) {
					return `<pre>${data}</pre>`
				}
			},
		],
		"order": [
			[0, "desc"]
		],
		columnDefs: [{
				className: "text-center",
				targets: [0, 1, 2, 3, 4, , 5, 7, 8, 9, 10, 11, 13, 14],
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

	$(document).on('click', '.btn-detail', function () {
		let SysId = $(this).attr('data-pk');

		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "ApprovalInvoice/M_Dtl_Invoice",
			data: {
				SysId_Invoice: SysId
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
				$('#location-detail').html(response);
				$('#modal-detail-invoice').modal('show');
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
	})

	$(document).on('click', '.btn-approve', function () {
		var Invoice_Number = $(this).attr('data-pk');
		Swal.fire({
			title: 'Approve ?',
			text: `Anda akan melakukan approve pada invoice ${Invoice_Number}`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#067EFF',
			cancelButtonColor: '#DD3B4B',
			confirmButtonText: 'Ya, Approve',
			cancelButtonText: 'Cancel'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "ApprovalInvoice/Approve_Invoice",
					data: {
						Invoice_Number: Invoice_Number
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
							TableData.ajax.reload(null, false)
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

	$(document).on('click', '.btn-reject', function () {
		var Invoice_Number = $(this).attr('data-pk');
		Swal.fire({
			title: 'Approve ?',
			text: `Anda akan melakukan reject pada invoice ${Invoice_Number}`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#067EFF',
			cancelButtonColor: '#DD3B4B',
			confirmButtonText: 'Ya, Reject',
			cancelButtonText: 'Cancel'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "ApprovalInvoice/Reject_Invoice",
					data: {
						Invoice_Number: Invoice_Number
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
							TableData.ajax.reload(null, false)
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
});
