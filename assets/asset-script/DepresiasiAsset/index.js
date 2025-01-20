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

	function Initialize_datatable() {
		$("#DataTable").DataTable({
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
				url: $('meta[name="base_url"]').attr('content') + "Asset/DepresiasiAsset/DT_List_Depresiasi_Asset",
				dataType: "json",
				type: "POST",
				data: {
					"tgl_finish": $('#tgl_finish').val()
				}
			},
			columns: [{
				data: "sysid", // 0
				name: "sysid",
				visible: false,
			}, {
				data: "no_asset", // 1
				name: "no_asset",
			}, {
				data: "item_code", // 2
				name: "item_code",
			}, {
				data: "item_name", // 3
				name: "item_name",
			}, {
				data: "tgl_perolehan", // 4
				name: "tgl_perolehan",
				render: function (data, type, row, meta) {
					return moment(data).format("DD MMMM YYYY");
				}
			}, {
				data: "tahun_perolehan", // 5
				name: "tahun_perolehan",
			}, {
				data: "harga_perolehan", // 6
				name: "harga_perolehan",
				render: function (data, type, row, meta) {
					return formatIdrAccounting(data);
				}
			}, {
				data: "masa_tahun_pakai", // 7
				name: "masa_tahun_pakai",
			}, {
				data: "nilai_penyusutan", // 8
				name: "nilai_penyusutan",
			}, {
				data: "lama_asset_terpakai_tahun", // 9
				name: "lama_asset_terpakai_tahun",
			}, {
				data: "nilai_asset_berkurang", // 10
				name: "nilai_asset_berkurang",
				render: function (data, type, row, meta) {
					return formatIdrAccounting(data);
				}
			}, {
				data: "nilai_asset_sisa", // 11
				name: "nilai_asset_sisa",
				render: function (data, type, row, meta) {
					return formatIdrAccounting(data);
				}
			}, {
				data: "is_active", // 12
				name: "Is_Active",
				render: function (data, type, row, meta) {
						if (data == 1) {
							return `<div class='d-flex justify-content-center'><span class="badge bg-success">Active</span></div>`;
						} else {
							return `<div class='d-flex justify-content-center'><span class="badge bg-danger">In-Active</span></div>`;
						}
					}
				// render: function (data, type, row, meta) {
				// 	if (data == 1) {
				// 		return `<i class="fas fa-check text-success"></i>`
				// 	} else {
				// 		return `<i class="fas fa-times text-danger"></i>`
				// 	}
				// }
			}],
			order: [
				[0, "desc"]
			],
			columnDefs: [{
					className: "text-center",
					targets: [0, 1, 2, 3, 4, 5, 7, 8, 9, 12],
				},
				{
					className: "text-right",
					targets: [6, 10, 11]
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
			"buttons": ["copy",
				{
					extend: 'csvHtml5',
					title: $('title').text() + ' ~ ' + $('#tgl_finish').val(),
					className: "btn btn-info",
				}, {
					extend: 'excelHtml5',
					title: $('title').text() + ' ~ ' + moment().format("YYYY-MM-DD"),
					className: "btn btn-success",
				}, {
					extend: 'pdfHtml5',
					title: $('title').text() + ' ~ ' + moment().format("YYYY-MM-DD"),
					className: "btn btn-danger",
					orientation: "landscape"
				}
			],
		}).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');
	}

	$('.datepicker').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd',
		todayHighlight: true,
		orientation: 'bottom',
	});

	$('#do--filter').on('click', function () {
		Initialize_datatable()
	})

	Initialize_datatable();
});
