$(document).ready(function () {
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		width: 300,
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.addEventListener('mouseenter', Swal.stopTimer)
			toast.addEventListener('mouseleave', Swal.resumeTimer)
		}
	})
	$('#container-location').hide();

	$('#preview--data').on('click', function () {
		let barcode = $('#no_barcode').val();
		if (barcode == '') {
			return Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'Scan/Input nomor barcode!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		} else {
			if (barcode.length < 16) {
				return Swal.fire({
					icon: 'error',
					title: 'Warning!',
					text: 'Panjang Karakter barcode kurang dari 16 Karakter!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			} else {
				$('#container-location').hide();
				$.ajax({
					dataType: "json",
					type: "GET",
					url: $('meta[name="base_url"]').attr('content') + "WasteBarcode/preview_detail_data_barcode",
					data: {
						barcode: barcode
					},
					beforeSend: function () {
						Swal.fire({
							title: 'Loading....',
							html: '<div class="spinner-border text-primary"></div>',
							showConfirmButton: false,
							allowOutsideClick: false,
							allowEscapeKey: false
						})
						$(this).prop('disabled', true)
					},
					success: function (response) {
						Swal.close()
						if (response.code == 200) {
							$('#CUSTOMER').html(response.Customer_Name)
							$('#CUSRTOMER_CODE').html(response.Customer_Code)
							$('#NUMBER').html(response.Barcode_Number)
							$('#BARCODE').html(response.Barcode_Value)
							$('#TGL_PRD').html(response.Date_Prd)
							$('#PRODUCTCODE').html(response.Product_Code)
							$('#PRODUCTNAME').html(response.Product_Name)
							$('#LDRRAKIT').html(response.Leader_Rakit)
							$('#CHECKER').html(response.Checker_Rakit)
							$('#PRINT').html(response.Created_by)
							$('#STATUS').html(response.IS_WASTING)
							$('#container-location').show("slide", {
								direction: "left"
							}, 1000);
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: response.msg,
								footer: '<a href="javascript:void(0)">Notifikasi System</a>'
							});
							$('#container-location').hide();
						}
						$(this).prop('disabled', false)
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
		}
	})

	$('#submit--barcode').on('click', function () {
		let barcode = $('#no_barcode').val();
		let info = $('#info').val();
		if (info == '' || info == undefined) {
			return Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'Harap Mengisi Keterangan!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
		if (barcode == '') {
			return Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'Scan/Input nomor barcode!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		} else {
			if (barcode.length < 16) {
				return Swal.fire({
					icon: 'error',
					title: 'Warning!',
					text: 'Panjang Karakter barcode kurang dari 16 Karakter!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			} else {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "WasteBarcode/update_as_wasting",
					data: {
						barcode: barcode,
						info: info,
					},
					beforeSend: function () {
						Swal.fire({
							title: 'Loading....',
							html: '<div class="spinner-border text-primary"></div>',
							showConfirmButton: false,
							allowOutsideClick: false,
							allowEscapeKey: false
						})
						$(this).prop('disabled', true)
					},
					success: function (response) {
						Swal.close()
						if (response.code == 200) {
							Toast.fire({
								icon: 'success',
								title: 'Nomor barcode : ' + barcode + ' dinyatakan kadaluarsa/wasting !'
							});
							$('#no_barcode').val('');
							$('input#no_barcode').focus();
							$("#DataTable_Wasting").DataTable().ajax.reload();
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: response.msg,
								footer: '<a href="javascript:void(0)">Notifikasi System</a>'
							});
						}
						$(this).prop('disabled', false)
						$('#container-location').hide();
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
		}
	})

	$("#DataTable_Wasting").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		paging: true,
		dom: 'lBfrtip',
		"oLanguage": {
			"sInfo": "Showing _START_ to _END_ of _TOTAL_ items."
		},
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "WasteBarcode/History_wasting_barcode",
			dataType: "json",
			type: "post",
		},
		columns: [{
				data: "SysId",
				name: "SysId",
				orderable: false,
				render: function (data, type, row, meta) {
					return meta.row + meta.settings._iDisplayStart + 1;
				}
			}, {
				data: "Barcode_Number",
				name: "Barcode_Number"
			}, {
				data: "Customer_Name",
				name: "Customer_Name"
			},
			{
				data: "Product_Code",
				name: "Product_Code"
			},
			{
				data: "Date_Prd",
				name: "Date_Prd"
			},
			{
				data: "Checker_Rakit",
				name: "Checker_Rakit"
			},
			{
				data: "Leader_Rakit",
				name: "Leader_Rakit"
			},
			{
				data: "Created_at",
				name: "Created_at"
			},
			{
				data: "IS_WASTING",
				name: "IS_WASTING",
				searching: false,
				render: function (data, type, row, meta) {
					if (data == '0') {
						return `<span class="badge badge-success" data-toggle="tooltip" title="Product Masuk Sebagai Stok">STOK</span>`;
					} else {
						return `<span class="badge badge-danger" data-toggle="tooltip" title="Barcode Terbuang/Tidak masuk Stok">WASTING</span>`;
					}
				}
			}, {
				data: "do_at",
				name: "do_at"
			}, {
				data: "do_by",
				name: "do_by"
			},
		],
		order: [
			[9, "desc"]
		],
		columnDefs: [{
				className: "text-center",
				targets: [0, 1, 4, 7, 8, 9, 10],
			},
			{
				className: "text-left",
				targets: []
			}
		],
		autoWidth: false,
		preDrawCallback: function () {
			$("#DataTable_Wasting tbody td").addClass("blurry");
		},
		language: {
			processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
		},
		drawCallback: function () {
			$("#DataTable_Wasting tbody td").addClass("blurry");
			setTimeout(function () {
				$("#DataTable_Wasting tbody td").removeClass("blurry");
			});
			$('[data-toggle="tooltip"]').tooltip();
		},
		"buttons": ["copy",
			{
				extend: 'csvHtml5',
				title: $('title').text() + '~' + $('#from').val() + ' sd ' + $('#to').val(),
				className: "btn btn-info",
			}, {
				extend: 'excelHtml5',
				title: $('title').text() + '~' + $('#from').val() + ' sd ' + $('#to').val(),
				className: "btn btn-success",
			}, {
				extend: 'pdfHtml5',
				title: $('title').text() + '~' + $('#from').val() + ' sd ' + $('#to').val(),
				className: "btn btn-danger",
				orientation: "landscape"
			}, "print"
		],
	}).buttons().container().appendTo('#DataTable_Wasting .col-md-6:eq(0)');





})
