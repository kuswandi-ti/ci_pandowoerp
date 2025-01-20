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

	$('.datepicker').datetimepicker({
		format: 'YYYY-MM-DD',
		autoclose: true,
		allowClear: true,
		todayHighlight: true,
		orientation: 'bottom',
	});
	$('select[name="customer"]').select2({
		minimumInputLength: 0,
		allowClear: true,
		placeholder: 'All',
		ajax: {
			dataType: 'json',
			url: $('meta[name="base_url"]').attr('content') + "PrintBarcodeProduct/select_customer",
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
			cache: true
		}
	})


	function Initialize_datatable() {
		$("#TableData").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			paging: true,
			"responsive": true,
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "SalesOrder/DT_Database_PO",
				dataType: "json",
				type: "get",
				data: {
					from: $('#from').val(),
					to: $('#to').val(),
					customer: $('#customer').find(":selected").val()
				}
			},
			columns: [{
				data: "SysId",
				name: "SysId",
				orderable: false,
				visible: false,
			}, {
				data: "Doc_No_Internal",
				name: "Doc_No_Internal",
				render: function (data, type, row, meta) {
					return `<pre>${data}</pre>`
				}
			}, {
				data: "No_Po_Customer",
				name: "No_Po_Customer",
				render: function (data, type, row, meta) {
					return `<pre>${data}</pre>`
				}
			},
			{
				data: "Status_PO",
				name: "Status_PO",
				orderable: false,
				render: function (data, type, row, meta) {
					if (parseFloat(row.tot_qty_dn) >= parseFloat(row.tot_qty_Order)) {
						return `<a href="javascript:void(0)" class="btn btn-sm btn-dark">COMPLETE</a>`;
					} else {
						return `<a href="javascript:void(0)" class="btn btn-sm btn-success blink_me">OPEN</a>`;
					}
				}
			},
			{
				data: null,
				name: "Handle",
				orderable: false,
				render: function (data, type, row, meta) {
					if (parseFloat(row.tot_qty_dn) >= parseFloat(row.tot_qty_Order)) {
						return `<div class="btn-group">
						<a href="javascript:void(0)" disabled class="btn disabled btn-sm btn-secondary" data-toggle="tooltip" title="Preview detail SO"><i class="fas fa-desktop"></i></a>&nbsp;&nbsp;
						<a href="javascript:void(0)" disabled class="btn disabled btn-sm btn-secondary" data-toggle="tooltip" title="List Delivery Note"><i class="fas fa-people-carry"></i></a>&nbsp;&nbsp;
						<a href="javascript:void(0)" disabled class="btn disabled btn-sm btn-secondary" data-toggle="tooltip" title="Edit Header SO"><i class="far fa-edit"></i></a>&nbsp;&nbsp;
						<a href="javascript:void(0)" disabled class="btn disabled btn-sm btn-secondary" data-toggle="tooltip" title="Edit Detail SO"><i class="far fa-copy"></i></a>&nbsp;&nbsp;
						<a href="javascript:void(0)" disabled class="btn disabled btn-sm btn-secondary" data-toggle="tooltip" title="Hapus SO"><i class="far fa-trash-alt"></i></a>
					</div>`;
					} else {
						return `<div class="btn-group">
						<a href="javascript:void(0)" class="btn btn-sm bg-gradient-primary btn-preview" data-pk="${row.SysId}" data-toggle="tooltip" title="Preview detail SO"><i class="fas fa-desktop"></i></a>&nbsp;&nbsp;
						<a href="javascript:void(0)" class="btn btn-sm bg-gradient-info btn-list-dn" data-pk="${row.SysId}" data-toggle="tooltip" title="List Delivery Note"><i class="fas fa-people-carry"></i></a>&nbsp;&nbsp;
						<a href="javascript:void(0)" class="btn btn-sm bg-gradient-warning btn-edit-hdr" data-pk="${row.SysId}" data-toggle="tooltip" title="Edit Header SO"><i class="far fa-edit"></i></a>&nbsp;&nbsp;
						<a href="javascript:void(0)" class="btn btn-sm bg-gradient-warning btn-edit-dtl" data-pk="${row.SysId}" data-toggle="tooltip" title="Edit Detail SO"><i class="far fa-copy"></i></a>&nbsp;&nbsp;
						<a href="javascript:void(0)" class="btn btn-sm bg-gradient-danger btn-delete" data-pk="${row.SysId}" data-toggle="tooltip" title="Hapus SO"><i class="far fa-trash-alt"></i></a>
					</div>`;
					}
				}
			},
			{
				data: "Customer_Code",
				name: "Customer_Code"
			},
			{
				data: "Customer_Name",
				name: "Customer_Name"
			},
			{
				data: "Tgl_Terbit",
				name: "Tgl_Terbit"
			},
			{
				data: "Term_Of_Payment",
				name: "Term_Of_Payment"
			},
			{
				data: "Remark_TOP",
				name: "Remark_TOP"
			},
			{
				data: "Term_Of_Delivery",
				name: "Term_Of_Delivery"
			},
			{
				data: "Customer_Address",
				name: "Customer_Address",
				render: function (data, type, row, meta) {
					return `<pre>${data}</pre>`
				}
			},
			{
				data: "Koresponden",
				name: "Koresponden"
			},
			{
				data: "Note",
				name: "Note"
			}
			],
			"order": [
				[3, "desc"],
				[10, "asc"]
			],
			columnDefs: [{
				className: "text-center",
				targets: [0, 1, 2, 3, 4, , 5, 7, 8, 9, 10, 11, 12, 13],
			},
			{
				className: "text-left",
				targets: []
			}, {
				targets: 4,
				"createdCell": function (td, cellData, rowData, row, col) {
					if (rowData.Status_PO == 'CLOSE') {
						$(td).css('background-color', 'black')
					}
				}
			},
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
	}

	$(document).on('click', '.btn-preview', function () {
		let SysId = $(this).attr('data-pk');

		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "SalesOrder/preview_sales_order",
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
				$('#location').html(response);
				$('#m_preview_sales_order').modal('show');
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

	$(document).on('click', '.btn-list-dn', function () {
		let SysId = $(this).attr('data-pk');

		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "SalesOrder/M_List_Dn_So",
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
				$('#location').html(response);
				$('#m_preview_so_dn').modal('show');
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

	$(document).on('click', '.btn-edit-hdr', function () {
		let SysId = $(this).attr('data-pk');

		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "SalesOrder/M_Edit_Hdr_So",
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
				$('#location').html(response);
				$('#m_edit_so').modal('show');
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

	$(document).on('click', '.btn-edit-dtl', function () {
		let SysId = $(this).attr('data-pk');

		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "SalesOrder/M_Edit_Dtl_So",
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
				$('#location').html(response);
				$('#m_edit_dtl_so').modal('show');
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

		Swal.fire({
			title: 'Delete data ?',
			text: "Are you sure to delete !",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Delete!',
			cancelButtonText: 'Cancel!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "SalesOrder/Delete_SO",
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

	$('#do--filter').on('click', function () {
		Initialize_datatable()
	})

	Initialize_datatable()

})
