$(document).ready(function () {
	let selectedInvoices = {}; // Objek untuk menyimpan pilihan checkbox

	const dataTable = $("#DataTable").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
		lengthMenu: [
			[10, 25, 50, 10000],
			[10, 25, 50, "All"],
		],
		select: {
			style: "multi",
			selector: 'td:first-child input[type="checkbox"]',
		},
		ajax: {
			url:
				$('meta[name="base_url"]').attr("content") +
				"Sales/SalesInv/DT_listdata_approval",
			dataType: "json",
			type: "POST",
		},
		columns: [
			{
				data: null,
				orderable: false,
				className: "text-center",
				render: function (data, type, row) {
					const isChecked = selectedInvoices[row.Invoice_ID] ? "checked" : "";
					return `<input type="checkbox" class="select-checkbox" data-id="${row.Invoice_ID}" ${isChecked}>`;
				},
			},
			{ data: "Invoice_Number" },
			{
				data: "Invoice_Date",
				render: function (data) {
					return moment(data).format("DD MMMM YYYY");
				},
			},
			{
				data: "Due_Date",
				render: function (data) {
					return moment(data).format("DD MMMM YYYY");
				},
			},
			{ data: "Account_Name" },
			{ data: "Invoice_Status" },
		],
		order: [[1, "desc"]],
		columnDefs: [{ className: "text-center", targets: "_all" }],
		autoWidth: false,
		preDrawCallback: function () {
			$("#DataTable tbody td").addClass("blurry");
		},
		language: {
			processing:
				'<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span>',
		},
		drawCallback: function () {
			$("#DataTable tbody td").addClass("blurry");
			setTimeout(function () {
				$("#DataTable tbody td").removeClass("blurry");
			}, 1000);
			$('[data-toggle="tooltip"]').tooltip();
			updateCheckboxSelections();
		},
		buttons: [
			{
				text: `<i class="fas fa-check"></i> Approve`,
				className: "btn btn-success",
				action: function () {
					handleRowSelection(1, "merubah status");
				},
			},
			{
				text: `<i class="fas fa-times"></i> Riject`,
				className: "btn btn-danger",
				action: function () {
					handleRowSelection(2, "merubah status");
				},
			},
			{
				text: `<i class="fas fa-search"></i> View Detail`,
				className: "btn btn-warning",
				action: function () {
					viewDetails();
				},
			},
		],
	});

	// Fungsi untuk memperbarui pilihan checkbox dan latar belakang saat paginasi
	function updateCheckboxSelections() {
		$("#DataTable tbody input[type='checkbox']").each(function () {
			const id = $(this).data("id");
			const $row = $(this).closest("tr");
			if (selectedInvoices[id]) {
				$(this).prop("checked", true);
				$row.addClass("table-primary");
			} else {
				$row.removeClass("table-primary");
			}
		});
	}

	// Event listener untuk perubahan pada checkbox
	$(document).on(
		"change",
		'#DataTable tbody input[type="checkbox"]',
		function () {
			const id = $(this).data("id");
			const $row = $(this).closest("tr");

			if ($(this).is(":checked")) {
				selectedInvoices[id] = true;
				$row.addClass("table-primary");
			} else {
				delete selectedInvoices[id];
				$row.removeClass("table-primary");
			}
		}
	);

	// Fungsi untuk menangani tindakan pada baris yang dipilih
	function handleRowSelection(status, actionText) {
		let selectedIds = Object.keys(selectedInvoices);
		if (selectedIds.length === 0) {
			Swal.fire({
				icon: "warning",
				title: "Ooppss...",
				text: `Silahkan pilih data untuk ${actionText}!`,
				footer:
					'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
			});
			return;
		}

		Swal.fire({
			title: "System message!",
			text: `Apakah anda yakin untuk ${actionText}?`,
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, ubah!",
		}).then((result) => {
			if (result.isConfirmed) {
				updateStatus(selectedIds, status);
			}
		});
	}

	// Fungsi untuk memperbarui status menggunakan AJAX
	function updateStatus(ids, status) {
		$.ajax({
			url: $('meta[name="base_url"]').attr("content") + "Sales/SalesInv/verify",
			type: "POST",
			dataType: "json",
			data: {
				invoice_Ids: ids,
				is_verified: status,
			},
			beforeSend: function () {
				Swal.fire({
					title: "Loading....",
					html: '<div class="spinner-border text-primary"></div>',
					showConfirmButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false,
				});
			},
			success: function (response) {
				if (response.code === 200) {
					Swal.fire({
						icon: "success",
						title: "Success!",
						text: response.msg,
						confirmButtonColor: "#3085d6",
						confirmButtonText: "Yes, Confirm!",
					});
					dataTable.ajax.reload(null, false);
					selectedInvoices = {};
				} else {
					showErrorMessage(response.msg);
				}
			},
			error: function (xhr, status, error) {
				const statusCode = xhr.status;
				const errorMessage =
					xhr.responseJSON && xhr.responseJSON.message
						? xhr.responseJSON.message
						: xhr.responseText || `Terjadi kesalahan: ${error}`;
				showErrorMessage(errorMessage, statusCode);
			},
		});
	}

	// Fungsi untuk melihat detail
	function viewDetails() {
		const selectedIds = Object.keys(selectedInvoices);
		if (selectedIds.length === 0) {
			Swal.fire({
				icon: "warning",
				title: "Ooppss...",
				text: "Silahkan pilih data untuk melihat detail!",
				footer:
					'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
			});
			return;
		}

		const url = `${$('meta[name="base_url"]').attr(
			"content"
		)}Sales/SalesInv/detail/${selectedIds[0]}`;
		window.location.href = url;
	}

	// Fungsi untuk menampilkan pesan error
	function showErrorMessage(message, statusCode) {
		Swal.fire({
			icon: "error",
			title: "Error!",
			html: `Kode HTTP: ${statusCode || "N/A"}<br>Pesan: ${message}`,
		});
	}
});
