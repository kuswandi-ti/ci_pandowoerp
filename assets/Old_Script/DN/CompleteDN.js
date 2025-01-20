$(document).ready(function () {

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
			// select: true,
			"responsive": true,
			// dom: 'lBfrtip',
			// "oLanguage": {
			// 	"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			// },
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "CompleteDN/DT_Complete_DN",
				dataType: "json",
				type: "get",
				data: {
					from: $('#from').val(),
					to: $('#to').val(),
					customer: $('#customer').find(":selected").val()
				}
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
					visible: false,
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
					visible: false,
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
					data: "No_Loading",
					name: "No_Loading",
					render: function (data, type, row, meta) {
						if (data == null || data == '') {
							return `<span class="badge badge-danger">Not Yet Loading</span>`
						} else {
							return `<a href="javascript:void(0)" class="text-primary font-weight-bold detail-loading">${data}</a>`;
						}
					}
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
				[12, "desc"]
			],
			columnDefs: [{
					className: "text-center",
					targets: [0, 1, 2, 3, 4, , 5, 7, 8, 9, 10, 11, 13, 14, 15, 16],
				},
				{
					className: "text-left",
					targets: [13]
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
	}

	$(document).on('click', '.detail-loading', function () {
		var Row = $("#TableData").DataTable().row($(this).parents('tr')).data();

		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "LoadingFinish/M_PreviewLoading",
			data: {
				No_Loading: Row.No_Loading
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
				$('#m_preview_data_loading').modal('show');
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

	$('#do--filter').on('click', function () {
		Initialize_datatable()
	})

	Initialize_datatable()
});
