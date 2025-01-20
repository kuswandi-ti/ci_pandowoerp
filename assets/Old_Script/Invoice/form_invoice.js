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

	function formatRupiah(moneyy) {
		let money = parseFloat(moneyy)
		return new Intl.NumberFormat('id-ID', {
			style: 'currency',
			currency: 'IDR'
		}).format(money);
	}

	var TableDtl = $("#tbl-item-inv").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		paging: false,
		searching: false,
		"responsive": false,
		ajax: {
			url: $('meta[name="base_url"]').attr('content') + "CreateInvoice/DT_Dtl_Item_Invoice",
			dataType: "json",
			type: "post",
		},
		columns: [{
				data: "Product_Code",
				name: "Product_Code",
				orderable: false,
			},
			{
				data: "Product_Name",
				name: "Product_Name",
				orderable: false,
			},
			{
				data: "Uom",
				name: "Uom",
				orderable: false,
			},
			{
				data: "Product_Price",
				name: "Product_Price",
				orderable: false,
			},
			{
				data: "Qty",
				name: "Qty",
				orderable: false,
			},
			{
				data: "Amount_Item",
				name: "Amount_Item",
				orderable: false,
			},
			{
				data: null,
				name: "handle",
				orderable: false,
				visible: false,
				render: function (data, type, row, meta) {
					return `<div class="btn btn-group">
						<button type="button" data-toggle="tooltip" title="Delete Item" class="btn bg-gradient-danger btn-xs btn-delete-item" data-pk="${row.SysId}"><i class="fas fa-trash"></i></button>
						</div>`
				}
			},
		],
		"order": [
			[0, "asc"]
		],
		columnDefs: [{
			className: "text-center",
			targets: [0, 1, 2, 3, 4, 5, 6],
		}, ],
		autoWidth: false,
		preDrawCallback: function () {
			$("#tbl-item-inv tbody td").addClass("blurry");
		},
		language: {
			processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
		},
		drawCallback: function () {
			$("#tbl-item-inv tbody td").addClass("blurry");
			setTimeout(function () {
				$("#tbl-item-inv tbody td").removeClass("blurry");
			});
			$('[data-toggle="tooltip"]').tooltip();
		}
	})

	// generate--invoice
	$('#form_hdr_invoice').validate({
		errorElement: 'span',
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			element.closest('.form-group').append(error);
		},
		highlight: function (element, errorClass, validClass) {
			$(element).addClass('is-invalid');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).removeClass('is-invalid');
		}
	});
	$.validator.setDefaults({
		debug: true,
		success: 'valid'
	});

	$('#generate--invoice').click(function (e) {
		e.preventDefault();
		let Dn = $('#DN').val();
		if ($("#form_hdr_invoice").valid()) {
			Swal.fire({
				title: 'System Message !',
				text: `Are you sure to create Invoice from Delivery Note : ${Dn} ?`,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			}).then((result) => {
				if (result.isConfirmed) {
					Init_Form_Hdr_Inv($('#form_hdr_invoice'))
				}
			})
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});

	function Init_Form_Hdr_Inv(DataForm) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "CreateInvoice/Store_Hdr_Tmp_Invoice",
			data: DataForm.serialize(),
			beforeSend: function () {
				$('#generate--invoice').prop("disabled", true);
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
					Fn_Disable_Form_Hdr()
					$('#generate--invoice').hide();

					$('#Invoice_Number_Show').val(response.Invoice_Number);
					$('#Invoice_Number').val(response.Invoice_Number);
					$('#Item_Amount').val(response.Item_Amount);
					$('#PPN').val(response.PPN);
					$('#PPN_Amount').val(response.PPN_Amount);
					$('#Invoice_Amount').val(response.Invoice_Amount);
					TableDtl.ajax.reload(null, false);
					$('#location-detail-form').show();
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: response.msg,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
				$('#submit--hdr').prop("disabled", false);
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

	function Fn_Disable_Form_Hdr() {
		$("#form_hdr_invoice input").attr('readonly', 'readonly');
		$("#form_hdr_invoice textarea").prop("disabled", true);
		$("#form_hdr_invoice select").prop("disabled", true);
		$("#form_hdr_invoice button").prop("disabled", true);
	}

	if ($('#Invoice_Number').val() != 'NEW') {
		Fn_Disable_Form_Hdr()
	}


	$('#btn--customer').on('click', function () {
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "Master/M_List_Customer",
			// data: {
			// 	sysid: $('#customer').val()
			// },
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
				$('#location-modal-customer').html(response);
				$('#modal-list-customer').modal('show');
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

	$('#btn--dn').on('click', function () {
		if ($('#customer_code').val() == null || $('#customer_code').val() == '') {
			return Toast.fire({
				icon: 'error',
				title: "You must select customer first !"
			});
		}
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "CompleteDN/M_DN_vs_Inv_Outstanding",
			data: {
				customer_code: $('#customer_code').val()
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
				$('#location-modal-dn-outstanding').html(response);
				$('#modal-dn-outstanding').modal('show');
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

	$('#btn--list--address').on('click', function () {
		if ($('#customer_code').val() == null || $('#customer_code').val() == '' || $('#customer_code').val() == undefined) {
			return Swal.fire({
				icon: 'warning',
				title: 'Oops...',
				text: 'You have to choose the customer first!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
		$.ajax({
			type: "GET",
			url: $('meta[name="base_url"]').attr('content') + "Master/List_Address_Customer_Pick",
			data: {
				sysid: $('#id_customer').val()
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
				$('#location-modal-address').html(response);
				$('#modal-list-address').modal('show');
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

	$('#cancel-invoice').on('click', function () {
		Swal.fire({
			title: 'Hapus data ?',
			text: "data yang sudah dihapus tidak dapat dikembalikan!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Hapus!',
			cancelButtonText: 'Batal!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					dataType: "json",
					type: "POST",
					url: $('meta[name="base_url"]').attr('content') + "CreateInvoice/delete_tmp_invoice",
					data: {
						Invoice_Number: $('#Invoice_Number').val()
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
							Swal.fire({
								title: 'System Message !',
								text: response.msg,
								icon: 'success',
								showCancelButton: false,
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'Yes'
							}).then((result) => {
								if (result.isConfirmed) {
									window.location.href = $('meta[name="base_url"]').attr('content') + "CreateInvoice"
								}
							})
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

	$('#submit-finish-invoice').on('click', function () {
		Swal.fire({
			title: 'System Message !',
			text: `Are you sure to save this invoice ?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			confirmButtonText: 'Yes'
		}).then((result) => {
			if (result.isConfirmed) {
				Fn_Save_Invoice($('#Invoice_Number').val())
			}
		})
	})

	function Fn_Save_Invoice(Invoice_Number) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "CreateInvoice/Store_Invoice",
			data: {
				Invoice_Number: Invoice_Number
			},
			beforeSend: function () {
				$('#submit-finish-invoice').prop("disabled", true);
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
					Swal.fire({
						title: 'System Message !',
						text: response.msg,
						icon: 'success',
						showCancelButton: false,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Yes'
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.href = $('meta[name="base_url"]').attr('content') + "CreateInvoice"
						}
					})
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						text: response.msg,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
				$('#submit--hdr').prop("disabled", false);
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
