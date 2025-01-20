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
			url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseReturn/DT_listdata_approval",
			dataType: "json",
			type: "POST",
		},
		columns: [
			{
				data: "PR_Number",
			},
			{
				data: "PR_Date",
				render: function (data, type, row, meta) {
					return moment(data).format("DD MMMM YYYY");
				}
			},
			{
				data: "RR_Number",
			},
			{
				data: "Account_Name",
			}
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
					Fn_Toggle_Status(RowData[0].PR_Number, RowData[0].RR_Number, 1)
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
					Fn_Toggle_Status(RowData[0].PR_Number, RowData[0].RR_Number, 2)
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

	function Fn_Toggle_Status(PR_Number, RR_Number, $param) {
		Swal.fire({
			title: 'System message!',
			text: `Apakah anda yakin untuk merubah status PR ini ?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, ubah!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseReturn/verify",
					type: "post",
					dataType: "json",
					data: {
						pr_number: PR_Number,
						rr_number: RR_Number,
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
			url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseReturn/GetDataDetailReturn",
			data: {
                sysid : SysId,
				state : 'DETAIL'
            },
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
                    $('.desc_pr_number').html(response.data_hdr.RR_Number);
                    $('#desc_pr_date').html(moment(response.data_hdr.RR_Date).format("DD MMMM YYYY"));
                    $('#desc_vendor').html(response.data_hdr.Account_Name);
                    $('#desc_alamat_vendor').html(response.data_hdr.Vendor_Address);
                    $('#desc_rr_number').html(response.data_hdr.RR_Number);
                    $('#desc_catatan').html(response.data_hdr.RR_Notes ? response.data_hdr.Notes : '-');

                    // DETAIL //
                    var $tableDtl = $('#tbl-modal-dtl tbody');
                    $tableDtl.empty();

                    var no = 1;
                    $.each(response.data_dtl, function(index, rowData) {
						var $newRow = $('<tr>');

                        $newRow.append('<td>'+ no +'</td>');
                        $newRow.append('<td>'+ rowData.Item_Code +'</td>');
                        $newRow.append('<td>'+ rowData.Item_Name +'</td>');
                        $newRow.append('<td class="text-center">'+ rowData.Uom +'</td>');
                        $newRow.append('<td class="text-right">'+ (parseFloat(rowData.Qty) % 1 === 0 ? parseInt(rowData.Qty) : parseFloat(rowData.Qty).toFixed(2)) +'</td>');

                        // Masukkan baris baru ke dalam tabel tujuan
                        $tableDtl.append($newRow);

                        no++
                    });
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
});
