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
			url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/DT_listdata_approval",
			dataType: "json",
			type: "POST",
		},
		columns: [
			{
				data: "Doc_No",
			},
			{
				data: "Doc_Rev",
				render: function (data, type, row, meta) {
					var txt_rev = '01.0';

					return data ? txt_rev + data : txt_rev;
				}
			},
			{
				data: "Doc_Date",
				render: function (data, type, row, meta) {
					return moment(data).format("DD MMMM YYYY");
				}
			},
			{
				data: "Account_Name", // Name Vendor
			},
			{
				data: "Address",
			},
			{
				data: "ETA",
				render: function (data, type, row, meta) {
					return moment(data).format("DD MMMM YYYY");
				}
			},
			{
				data: "ETD",
				render: function (data, type, row, meta) {
					return moment(data).format("DD MMMM YYYY");
				}
			},
			{
				data: "Currency",
			},
			{
				data: "Amount",
				render: function (data, type, row, meta) {
					return formatIdrAccounting(data);
				}
			},
			{
				data: "Note",
				render: function (data, type, row, meta) {
					return data ? data : '-';
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
			text: `<i class="fas fa-check"></i>&nbsp; Approve`,
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
			text: `<i class="fas fa-times"></i>&nbsp; Reject`,
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
			text: `<i class="fas fa-search"></i>&nbsp; View Detail`,
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
			text: `Apakah anda yakin untuk merubah status PO ini ?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, ubah!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/verify",
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
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseOrder/detail",
			data: {
                sysid : SysId
            },
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
                    $('#desc_doc_number').html(response.data_hdr.Doc_No);
                    $('#desc_doc_date').html(moment(response.data_hdr.Doc_Date).format("DD MMMM YYYY"));
                    $('#desc_vendor').html(response.data_hdr.Account_Name);
                    $('#desc_person').html(response.data_hdr.Contact_Name);
                    $('#desc_alamat_vendor').html(response.data_hdr.Address);
                    $('#desc_eta').html(moment(response.data_hdr.ETA).format("DD MMMM YYYY"));
                    $('#desc_currency').html(response.data_hdr.Currency);
                    $('#desc_rate_currency').html(response.data_hdr.Rate);
                    $('#desc_etd').html(moment(response.data_hdr.etd).format("DD MMMM YYYY"));
                    $('#desc_is_import').html(response.data_hdr.IsImport == 1 ? 'Ya' : 'Tidak');
                    $('#desc_catatan').html(response.data_hdr.Note ? response.data_hdr.Note : '-');

                    // DETAIL //
                    var $tableDtl = $('#tbl-modal-dtl tbody');
                    $tableDtl.empty();

					let total_amount = 0;
					let total_price = 0;
                    var no = 1;
                    $.each(response.data_dtl, function(index, rowData) {
						var unit_price = rowData.Unit_Price;
        
						var qty = rowData.Qty;
						
						total_price 	 	 = rowData.Total_Price;
						var discount         = rowData.Discount;
						var discount_value   = (total_price * discount) / 100;

						var tax1 = rowData.type_tax_1 ? rowData.Tax_Code1 : '-';
						var tax2 = rowData.type_tax_2 ? rowData.Tax_Code2 : '-';

						var $newRow = $('<tr>');

                        $newRow.append('<td>'+ no +'</td>');
                        $newRow.append('<td>'+ rowData.Item_Code +'</td>');
                        $newRow.append('<td>'+ rowData.Item_Name +'</td>');
                        $newRow.append('<td>'+ rowData.Uom +'</td>');
                        $newRow.append('<td>'+ rowData.Nama_Cost_Center +'</td>');
                        $newRow.append('<td>'+ qty +'</td>');
                        $newRow.append('<td>'+ discount +'</td>');
                        $newRow.append('<td>'+ formatIdrAccounting(discount_value) +'</td>');
                        $newRow.append('<td>'+ tax1 +'</td>');
                        $newRow.append('<td>'+ tax2 +'</td>');
                        $newRow.append('<td>'+ formatIdrAccounting(unit_price) +'</td>');
                        $newRow.append('<td>'+ formatIdrAccounting(rowData.Base_UnitPrice) +'</td>');
                        $newRow.append('<td>'+ formatIdrAccounting(total_price) +'</td>');
                        $newRow.append('<td>'+ formatIdrAccounting(rowData.Base_TotalPrice) +'</td>');
                        $newRow.append('<td>'+ rowData.Remark +'</td>');
						
						total_amount += parseFloat(total_price);

                        // Masukkan baris baru ke dalam tabel tujuan
                        $tableDtl.append($newRow);

                        no++
                    });
					
					let discount_percent_all    = response.data_hdr.Discount;
					let discount_value_all   	= (total_amount * discount_percent_all) / 100;

                    $('#v_total_amount').html(formatIdrAccounting(total_amount));
                    $('#v_discount_percent').html('('+ response.data_hdr.Discount + '%)');
                    $('#v_discount').html(formatIdrAccounting(discount_value_all));
                    $('#v_tax_1').html(formatIdrAccounting(response.data_hdr.Value_Tax_1));
                    $('#v_tax_2').html(formatIdrAccounting(response.data_hdr.Value_Tax_2));
                    $('#v_grand_total').html(formatIdrAccounting(response.data_hdr.Amount));
                    // DETAIL - END //

                    $('#modal-list-dtl').modal('show');
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: response.msg,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ya, Confirm!',
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
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
    
    function convertToIDR($input) {
        var value = $input;

        // Hilangkan karakter non-numeric dan 'Rp.'
        var numericValue = value.replace(/[^\d]/g, '');

        // Jika nilai numerik kosong, set nilai ke '0'
        if (numericValue === '') {
            numericValue = '0';
        }

        // Konversi ke angka dan format dengan separator ribuan
        var formattedValue = parseInt(numericValue, 10).toLocaleString('id-ID');

        // Perbarui nilai input dengan format IDR
        return 'Rp. ' + formattedValue;
    }

    function convertToUSD($input) {
        var value = $input;

        // Hilangkan karakter non-numeric dan '$'
        var numericValue = value.replace(/[^\d]/g, '');

        // Jika nilai numerik kosong, set nilai ke '0'
        if (numericValue === '') {
            numericValue = '0';
        }

        // Konversi ke angka dan format dengan separator ribuan
        var formattedValue = parseInt(numericValue, 10).toLocaleString('en-US');

        // Perbarui nilai input dengan format USD
        return '$' + formattedValue;
    }
});
