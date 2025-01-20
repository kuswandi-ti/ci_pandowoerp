$(document).ready(function () {
	$(document).on('click', '#btn-submit', function () {
		let StartDate = $('#startDate').val();
		let EndDate = $('#endDate').val();
		let Wh = $('#Warehouse').val();
		let created_by = $('#created_by').val();
		let approved_by = $('#approved_by').val();

		if (approved_by == '' || approved_by == null || approved_by == undefined) {
			return Swal.fire({
				icon: 'warning',
				title: 'Ooppss...',
				text: 'lengkapi data disetujui oleh!',
				footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
			});
		}



		window.open($('meta[name="base_url"]').attr('content') +
			`TrxWh/NotaHasilProduksi/print_daily_report?StartDate=${StartDate}&EndDate=${EndDate}&Wh=${Wh}&created_by=${created_by}&approved_by=${approved_by}`,
			'WindowReport-DailyReportNhp',
			'width=800,height=600');
	})
})
