$(document).ready(function () {
	function Initialize_select2_item_group(category) {
		$('#Item_Category_Group').select2({
			minimumInputLength: 0,
			allowClear: true,
			placeholder: '-Item Group-',
			cache: true,
			ajax: {
				dataType: 'json',
				url: $('meta[name="base_url"]').attr('content') + "MasterData/Item/select_item_group_report",
				delay: 800,
				data: function (params) {
					return {
						search: params.term,
						category: category
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
				}
			}
		})
	}

	function Init_select2_warehouse(category) {
		$('#Warehouse').select2({
			minimumInputLength: 0,
			allowClear: true,
			placeholder: '-Gudang-',
			cache: true,
			ajax: {
				dataType: 'json',
				url: $('meta[name="base_url"]').attr('content') + "MasterData/Item/select_warehouse_report",
				delay: 800,
				data: function (params) {
					return {
						search: params.term,
						category: category
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
				}
			}
		})
	}

	$('#Item_Category').on('select2:select', function () {
		$('#Item_Category_Group').val(null).trigger('change'), Initialize_select2_item_group($('#Item_Category').val()), Init_select2_warehouse($('#Item_Category').val());

	})

	$('#btn-submit').on('click', function () {
		var StartDate = $('#from').val();
		var EndDate = $('#to').val();
		var item_category = $('#Item_Category').val();
		var item_category_group = $('#Item_Category_Group').val();
		var Warehouse = $('#Warehouse').val();
		var source_value = $('input[name="source_value"]:checked').val();

		if (item_category == '' || item_category == null || item_category == undefined) {
			return Swal.fire({
				icon: 'warning',
				title: 'Ooppss...',
				text: 'Pilih Item Category!',
				footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
			});
		}
		if (source_value == '' || source_value == null || source_value == undefined) {
			return Swal.fire({
				icon: 'warning',
				title: 'Ooppss...',
				text: 'Pilih sumber nilai harga barang!',
				footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
			});
		}

		window.open($('meta[name="base_url"]').attr('content') +
			`Inventory/Report/print_loipw?StartDate=${StartDate}&EndDate=${EndDate}&item_category=${item_category}&item_category_group=${item_category_group}&Warehouse=${Warehouse}&source_value=${source_value}`,
			'WindowReport-printloipw',
			'width=800,height=600');
	})
});
