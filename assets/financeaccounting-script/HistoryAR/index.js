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

	function Initialize_Datatable() {
		$("#DataTable").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			// select: true,
			dom: 'lBfrtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			fixedHeader: {
				header: true,
				headerOffset: 48
			},
			lengthChange: false,
			paging: false,
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "FinanceAccounting/HistoryAR/DT_List_History_AR",
				dataType: "json",
				type: "GET",
				data: {
					"id_customer": $('#id_customer').val()
				}
			},			
			columns: [{
					data: "id_customer",
					name: "id_customer",
					visible: true,
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				}, {
					data: "customer",
					name: "customer",
				}, {
					data: "no_doc_customer",
					name: "no_doc_customer",
				}, {
					data: "invoice_amount",
					name: "invoice_amount",
					searchable: false,
					orderable: false,
					render: function (data, type, row, meta) {
						return formatIdrAccounting(data);
					}
				}, {
					data: "doc_no_ri",
					name: "doc_no_ri",
				}, {
					data: "amount_receive",
					name: "amount_receive",
					searchable: false,
					orderable: false,
					render: function (data, type, row, meta) {
						return formatIdrAccounting(data);
					}
				},
			],
			order: [
				[0, "desc"]
			],
			columnDefs: [{
					className: "text-center align-middle",
					targets: [0],
				}, {
					className: "text-right",
					targets: [3, 5]
				}, {
					className: "text-center",
					targets: [2, 4]
				}
			],
			autoWidth: false,
			responsive: false,
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
					title: $('title').text() + '~' + ' - ' + $('select[name=id_customer] option:selected').text(),
					className: "btn btn-info",
				}, {
					extend: 'excelHtml5',
					title: $('title').text() + '~' + ' - ' + $('select[name=id_customer] option:selected').text(),
					className: "btn btn-success",
				}, {
					extend: 'pdfHtml5',
					title: $('title').text() + '~' + ' - ' + $('select[name=id_customer] option:selected').text(),
					className: "btn btn-danger",
					orientation: "landscape"
				}
			],
		}).buttons().container().appendTo('#DataTable .col-md-6:eq(0)');
	}

	$('#do--filter').on('click', function () {
		Initialize_Datatable()
	})

	Initialize_Datatable()
});
