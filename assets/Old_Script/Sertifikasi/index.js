$(document).ready(function () {
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

	// setInterval(function () {
	// 	$('#no_barcode').focus()
	// }, 2000);

	$('#no_barcode').keypress(function (event) {
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if (keycode == '13') {
			let barcode = $('#no_barcode').val();
			Fn_Init_Store_Barcode(barcode)
		}
	});

	$('#submit--barcode').on('click', function () {
		let barcode = $('#no_barcode').val();
		Fn_Init_Store_Barcode(barcode)
	})

	function Fn_Init_Store_Barcode(barcode) {
		if (barcode == '' || barcode == undefined) {
			return Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'Scan/Input nomor barcode!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
		if (barcode.length < 16) {
			return Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'Panjang Karakter barcode kurang dari 16 Karakter!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
		if (barcode.length > 18) {
			return Swal.fire({
				icon: 'error',
				title: 'Warning!',
				text: 'Panjang Karakter barcode Lebih dari 18 Karakter!',
				footer: '<a href="javascript:void(0)">Notifikasi System</a>'
			});
		}
		$.ajax({
			dataType: "Html",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "SertifikasiProduct/Preview_Data_Sertifikasi",
			data: {
				barcode: barcode,
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
				$('#location').html(response);
				$(this).prop('disabled', false)
				$('#no_barcode').val('');
				$('#no_barcode').focus()
			},
			error: function (xhr, textStatus, error) {
				Swal.close()
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: error,
					footer: '<a href="javascript:void(0)">Notifikasi System</a>'
				});
			}
		});
	}
})
