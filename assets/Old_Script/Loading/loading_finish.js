$(document).ready(function () {

	function Init_DT(no_loading) {
		$("#Tbl_list_Loading").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			paging: true,
			orderCellsTop: true,
			dom: 'lBfrtip',
			"oLanguage": {
				"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
			},
			"lengthMenu": [
				[1000],
				[1000]
			],
			orderCellsTop: true,
			fixedHeader: {
				header: true,
				headerOffset: 48
			},
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "LoadingFinish/DT_Barcode_loading",
				dataType: "json",
				type: "POST",
				data: {
					no_loading: no_loading,
				}
			},
			columns: [{
					data: "SysId",
					name: "SysId",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					data: "Barcode_Value",
					name: "Barcode_Value",
				},
				{
					data: "do_at",
					name: "do_at",
				},
				{
					data: "do_by",
					name: "do_by",
				},
			],
			order: [
				[1, 'ASC']
			],
			columnDefs: [{
				className: "align-middle text-center",
				targets: [0, 1, 2, 3],
			}],
			// autoWidth: false,
			responsive: true,
			preDrawCallback: function () {
				$("#Tbl_list_Loading tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#Tbl_list_Loading tbody td").addClass("blurry");
				setTimeout(function () {
					$("#Tbl_list_Loading tbody td").removeClass("blurry");
				});
				$('[data-toggle="tooltip"]').tooltip();
			},
			"buttons": ["copy",
				{
					extend: 'csvHtml5',
					title: 'NO. GROUP LIST BARCODE : ' + $('#no_loading').text() + '\n' + $('#customer_name').text() + '\n' + $('#Deskripsi_Product').val(),
					className: "btn btn-info",
				}, {
					extend: 'excelHtml5',
					title: 'NO. GROUP LIST BARCODE : ' + $('#no_loading').text() + '\n' + $('#customer_name').text() + '\n' + $('#Deskripsi_Product').val(),
					className: "btn btn-success",
				}, {
					extend: 'pdfHtml5',
					title: 'NO. GROUP LIST BARCODE : ' + $('#no_loading').text() + '\n' + $('#customer_name').text() + '\n' + $('#Deskripsi_Product').val(),
					className: "btn btn-danger",
					// orientation: "landscape",
					customize: function (doc) {
						doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
						doc.content.splice(0, 1);
						doc.pageMargins = [20, 70, 20, 30];
						doc.defaultStyle.fontSize = 7;
						doc.styles.tableHeader.fontSize = 7;
						doc['header'] = (function () {
							return {
								columns: [{
									alignment: 'center',
									italics: true,
									text: 'NO. GROUP LIST BARCODE : ' + $('#no_loading').text() + '\n' + $('#customer_name').text() + '\n' + $('#Deskripsi_Product').val(),
									fontSize: 12,
									margin: [10, 0]
								}],
								margin: 20
							}
						});
						doc['footer'] = (function (page, pages) {
							return {
								columns: [
									'',
									{
										alignment: 'right',
										text: [{
												text: page.toString(),
												italics: true
											},
											' of ',
											{
												text: pages.toString(),
												italics: true
											}
										]
									}
								],
								margin: [10, 0]
							}
						});
						var objLayout = {};
						objLayout['hLineWidth'] = function (i) {
							return .5;
						};
						objLayout['vLineWidth'] = function (i) {
							return .5;
						};
						objLayout['hLineColor'] = function (i) {
							return '#aaa';
						};
						objLayout['vLineColor'] = function (i) {
							return '#aaa';
						};
						objLayout['paddingLeft'] = function (i) {
							return 4;
						};
						objLayout['paddingRight'] = function (i) {
							return 4;
						};
						doc.content[0].layout = objLayout;
					}
				},
				"print"
			],
		}).buttons().container().appendTo('#Tbl_list_Loading .col-md-6:eq(0)');
	}

	Init_DT($('#no_loading').text())
});
