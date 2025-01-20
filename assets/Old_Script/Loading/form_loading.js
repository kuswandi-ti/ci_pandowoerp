$(document).ready(function () {
	// ===================== utility
	$('select[name="customer"]').select2({
		minimumInputLength: 0,
		allowClear: true,
		placeholder: '-Pilih Supplier-',
		ajax: {
			dataType: 'json',
			url: $('meta[name="base_url"]').attr('content') + "PrintBarcodeProduct/select_customer",
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
	}).on('select2:select', function (evt) {
		$('#product').prop('disabled', false);
		Init_Seelect2_Product()
	});

	function Init_Seelect2_Product() {
		$('select[name="product"]').select2({
			minimumInputLength: 0,
			allowClear: true,
			placeholder: '-Pilih Product-',
			ajax: {
				dataType: 'json',
				url: $('meta[name="base_url"]').attr('content') + "PrintBarcodeProduct/select_product/" + $('select[name="customer"]').find(':selected').val(),
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
});
