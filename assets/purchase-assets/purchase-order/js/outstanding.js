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

	var table = $("#DataTable").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
		lengthMenu: [
			[10, 25, 50, 10000],
			[10, 25, 50, 'All']
		],
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/DT_listdata_outstanding",
			dataType: "json",
			type: "POST",
		},
		columns: [
			{
				data: "Doc_No",
				render: function (data, type, row, meta) {
					return '<a href="javascript:void(0);" id="detail_preview" data-docno="'+ data +'" data-itemcode="'+ row.Item_Code +'">'+ data +'</a>';
				}
			},
			{
				data: "Item_Code",
			},
			{
				data: "Item_Name",
			},
			{
				data: "Account_Name",
			},
			{
				data: "Qty_PO",
			},
			{
				data: "Total_Qty_RR",
				render: function (data, type, row, meta) {
					return parseFloat(data);
				}
			},
			{
				data: "Qty_Outstanding",
			},
			{
				data: "Currency",
			},
			{
				data: null,
				render: function (data, type, row, meta) {
					var qtyPO = parseFloat(row.Qty_PO);
					var unitPrice = parseFloat(row.Unit_Price);

					if (!isNaN(qtyPO) && !isNaN(unitPrice)) {
						var result = qtyPO * unitPrice;
						return currencyFormat(result);
					} else {
						return 'N/A'; // Jika ada nilai yang tidak valid
					}
				}
			},
			{
				data: null,
				render: function (data, type, row, meta) {
					var qty = parseFloat(row.Total_Qty_RR);
					var unitPrice = parseFloat(row.Unit_Price);

					if (!isNaN(qty) && !isNaN(unitPrice)) {
						var result = qty * unitPrice;
						return currencyFormat(result);
					} else {
						return 'N/A'; // Jika ada nilai yang tidak valid
					}
				}
			},
			{
				data: null,
				render: function (data, type, row, meta) {
					var qtyOutstanding = parseFloat(row.Qty_Outstanding);
					var unitPrice = parseFloat(row.Unit_Price);

					if (!isNaN(qtyOutstanding) && !isNaN(unitPrice)) {
						var result = qtyOutstanding * unitPrice;
						return currencyFormat(result); // Menampilkan hasil dengan dua angka di belakang koma
					} else {
						return 'N/A'; // Jika ada nilai yang tidak valid
					}
				}
			},
		],
		order: [
			[0, "desc"]
		],
		columnDefs: [{
				className: "text-center",
				targets: "_all",
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
		}],
	}).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');

    $('#DataTable tbody').on('click', '#detail_preview', function () {
		$('#txt_doc').html($(this).data('docno'));
		$('#modal-list-dtl').modal('show');
		table_detail($(this).data('docno'), $(this).data('itemcode'));
    });
    
    function currencyFormat(num, decimal = 2) {
        return accounting.formatMoney(num, "", decimal, ",", ".");
    }

	function table_detail(PO_NO, ItemCode) {
		$("#DataTableDetail").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			lengthMenu: [
				[10, 25, 50, 10000],
				[10, 25, 50, 'All']
			],
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/DT_listdata_detail_os",
				dataType: "json",
				type: "POST",
				data: {
					po_no : PO_NO,
					item_code : ItemCode
				}
			},
			columns: [
				{
					data: 'RR_Number', // gunakan 'null' karena kita akan menggunakan render function
					render: function(data, type, row, meta) {
						return meta.row + 1; // meta.row adalah indeks baris (mulai dari 0)
					},
				},
				{
					data: "RR_Number",
				},
				{
					data: "RR_Date",
					render: function (data, type, row, meta) {
						return data ? moment(data).format("DD MMMM YYYY") : '-';
					}
				},
				{
					data: "Qty",
					render: function (data, type, row, meta) {
						return parseFloat(data);
					}
				},
				{
					data: null,
					render: function (data, type, row, meta) {
						var qtyRR = parseFloat(row.Qty);
						var unitPrice = parseFloat(row.Unit_Price);
	
						if (!isNaN(qtyRR) && !isNaN(unitPrice)) {
							var result = qtyRR * unitPrice;
							return currencyFormat(result);
						} else {
							return 'N/A'; // Jika ada nilai yang tidak valid
						}
					}
				},
			],
			order: [
				[0, "desc"]
			],
			columnDefs: [{
					className: "text-center",
					targets: "_all",
				},
				{
					className: "text-left",
					targets: []
				}
			],
			autoWidth: false,
			// responsive: true,
			preDrawCallback: function () {
				$("#DataTableDetail tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#DataTableDetail tbody td").addClass("blurry");
				setTimeout(function () {
					$("#DataTableDetail tbody td").removeClass("blurry");
				});
				$('[data-toggle="tooltip"]').tooltip();
			},
		});
	}
});
