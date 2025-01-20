$(function () {
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		width: 600,
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.addEventListener('mouseenter', Swal.stopTimer)
			toast.addEventListener('mouseleave', Swal.resumeTimer)
		}
	})
	$('#container-detail-barcode').hide();
	$('#submit--lotNo').on('click', function () {
		let barcode = $('#no_barcode').val();
		let placement = $('#placement').find(":selected").val();
		if (placement == '' || placement == null || placement == undefined) {
			return Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'pilih Penempatan!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
		if (barcode == '') {
			Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'Scan/Input nomor barcode!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		} else {
			if (barcode.length < 9) {
				Swal.fire({
					icon: 'error',
					title: 'Warning!',
					text: 'Panjang Karakter barcode kurang dari 9 huruf!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			} else {
				$.ajax({
					dataType: "json",
					type: "GET",
					url: $('meta[name="base_url"]').attr('content') + "BarcodeOutOven/update_out_oven",
					data: {
						barcode: barcode,
						placement: placement
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
								title: 'Nomor lot ' + barcode + ' dinyatakan keluar oven!'
							});
							$('#no_barcode').val('');
							$('input#no_barcode').focus();
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: response.msg,
								footer: '<a href="javascript:void(0)">Notifikasi System</a>'
							});
						}
						$(this).prop('disabled', false)
						$('#container-detail-barcode').hide();
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

	$('#preview--lot').on('click', function () {
		let barcode = $('#no_barcode').val();
		if (barcode == '') {
			Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'Scan/Input nomor barcode!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		} else {
			if (barcode.length < 9) {
				Swal.fire({
					icon: 'error',
					title: 'Warning!',
					text: 'Panjang Karakter barcode kurang dari 9 huruf!',
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			} else {
				$('#container-detail-barcode').hide();
				$.ajax({
					dataType: "json",
					type: "GET",
					url: $('meta[name="base_url"]').attr('content') + "BarcodeOutOven/preview_detail_lot",
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
							$("#SUPPLIER").html(response.supplier)
							$("#LPB").html(response.lpb)
							$("#LOT").html(response.lot)
							$("#MATERIAL").html(response.material)
							$("#QTY").html(response.qty)
							$("#GRADER").html(response.grader)
							$("#TIME-IN").html(response.time_in)
							$("#KUBIKASI").html(response.kubikasi)
							$("#STATUS").html(response.status)
							$("#OVEN").html(response.oven)
							$("#TIMER").html(response.timer)
							$('#container-detail-barcode').show("slide", {
								direction: "left"
							}, 1000);
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'Nomor Lot tidak terdaftar dalam system!',
								footer: '<a href="javascript:void(0)">Notifikasi System</a>'
							});
							$('#container-detail-barcode').hide();
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
})
