$(document).ready(function () {

	$('#legalitas').on('change', function () {
		var legalitasVal = $(this).val();
		var url_account;
		var text_placeholder;

		// Jika nilai adalah 'SALES RETURN', ubah label Supplier menjadi Customer
		if (legalitasVal === 'SALES RETURN') {
			$('label[for="supplier"]').text('Customer :');
			url_account = 'TrxWh/Lpb/select_customer';  // Mengisi URL untuk customer
			text_placeholder = '-Pilih Customer-';      // Placeholder untuk customer
			$('#el-srno').show()
		} else {
			// Jika bukan 'SALES RETURN', ubah kembali menjadi Supplier
			$('label[for="supplier"]').text('Supplier :');
			url_account = 'TrxWh/Lpb/select_supplier';  // Mengisi URL untuk supplier
			text_placeholder = '-Pilih Supplier-';      // Placeholder untuk supplier
			$('#el-srno').hide()
		}

		$('#supplier').val(null).trigger('change');

		// Inisialisasi select2 dengan konfigurasi AJAX untuk dynamic dropdown
		$('select[name="supplier"]').select2({
			minimumInputLength: 0,
			allowClear: true,
			placeholder: text_placeholder,  // Menggunakan placeholder yang sesuai
			ajax: {
				dataType: 'json',
				url: $('meta[name="base_url"]').attr('content') + url_account, // URL API dinamis berdasarkan kondisi
				delay: 800,
				data: function (params) {
					return {
						search: params.term  // Mengirim parameter pencarian
					};
				},
				processResults: function (data, page) {
					return {
						results: $.map(data, function (obj) {
							return {
								id: obj.id,
								text: obj.text
							};
						})
					};
				},
				cache: true
			}
		});
	});


	$('select[name="daerah"]').select2({
		minimumInputLength: 0,
		allowClear: true,
		placeholder: '-Pilih Asal-',
		ajax: {
			dataType: 'json',
			url: $('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/select_daerah",
			delay: 800,
			data: function (params) {
				return {
					search: params.term
				}
			},
			processResults: function (data, page) {
				return {
					results: $.map(data, function (obj) {
						return {
							id: obj.id,
							text: obj.text
						};
					})
				};
			},
			cache: true
		}
	})

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
	// ============================== script need trigger
	$(document).on("select2:open", () => {
		document.querySelector(".select2-container--open .select2-search__field").focus()
	})

	$('#add-row').on('click', function () {
		let LastNo = $('#tbl-lpb>tbody>tr:last-child').find('td:eq(0)').html();
		let BeLpb = $('#BeLpb').val();
		// let BeLot = $('#BeLot').val();
		let No = parseInt(LastNo) + 1;
		// let NewCountLot = parseInt(BeLot) + parseInt(LastNo);
		let Be_lot = BeLpb + '-' + No;

		var Row = `<tr>
            <td align="center" class="nomor">${No}</td>
            <td align="center" class="lot">
                <span class="form-group">
                    ${Be_lot}
                </span>
            </td>
            <td align="center" class="ukuran">
                <span class="form-group">
                    <select class="form-control form-control-xs" name="ukuran[]" required style="width: 100%;"></select>
                </span>
            </td>
            <td align="center" class="qty">
                <span class="form-group">
                    <input type="number" class="form-control form-control-xs text-center onlyfloat" min="1" required placeholder="Qty..." name="qty[]">
                </span>
            </td>
        </tr>`;
		$('#tbl-lpb tbody').append(Row);
		select2_ukuran_kayu();
		$(".readonly").keydown(function (event) {
			return false;
		});
		$('.onlyfloat').keypress(function (eve) {
			if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
				eve.preventDefault();
			}
		});
	})

	$('#remove-row').on('click', function () {
		var rowCount = $('#tbl-lpb tbody tr').length;
		if (rowCount > 1) {
			$('#tbl-lpb>tbody>tr:last-child').remove();
		} else {
			Toast.fire({
				icon: 'error',
				title: 'Baris terakhir dapat di hapus!'
			});
		}
	})

	$(".readonly").keydown(function (event) {
		return false;
	});

	$('.onlyfloat').keypress(function (eve) {
		if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
			eve.preventDefault();
		}
	});

	function select2_ukuran_kayu() {
		$('select[name="ukuran[]"]').select2({
			minimumInputLength: 2,
			allowClear: true,
			placeholder: '-Ukuran Kayu-',
			ajax: {
				dataType: 'json',
				url: $('meta[name="base_url"]').attr('content') + "ReceiveMaterial/select_material_kayu",
				delay: 800,
				data: function (params) {
					return {
						search: params.term
					}
				},
				processResults: function (data, page) {
					return {
						results: $.map(data, function (obj) {
							return {
								id: obj.id,
								text: obj.text
							};
						})
					};
				},
				cache: true
			}
		})
	}

	select2_ukuran_kayu();
})
