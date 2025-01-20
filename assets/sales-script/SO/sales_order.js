$(document).ready(function () {
	// Default tampilan dan value
	let qtyShpObject = {};
	form_state("LOAD");
	$("#btn-submit").hide();
	let selectedOption = $("#currency option:selected");
	let tableAddress = $("#table-address").DataTable({
		columns: [
			{
				data: "SysId",
				visible: false,
			},
			{
				data: null,
				orderable: false,
				searchable: false,
				defaultContent: "<input type='radio' name='selectAddress'>",
			},
			{
				data: "Address",
			},
			{
				data: "Area",
			},
			{
				data: "Description",
			},
		],
		responsive: true,
		autoWidth: false,
	});
	//Membuat nilai default currency IDR = 1
	updateCurrencyFields(selectedOption);
	$(document).on("keypress keyup", ".only-number", function (event) {
		var inputVal = $(this).val();
		$(this).val(inputVal.replace(/[^\d.,]/g, ""));
		if (
			(event.which !== 44 || inputVal.indexOf(",") !== -1) &&
			(event.which !== 46 || inputVal.indexOf(".") !== -1) &&
			(event.which < 48 || event.which > 57)
		) {
			event.preventDefault();
		}
	});
	// GROUP FUNCTION JQUERY FJQ
	// Kirim data header dan detail ke controller untuk dikelola dan disimpan kedalam tabel
	$("#main-form").on("submit", function (event) {
		event.preventDefault();
		if (validateForm()) {
			let formData = $(this).serialize();
			$.ajax({
				url:
					$('meta[name="base_url"]').attr("content") + "Sales/SalesOrder/store",
				type: "POST",
				data: formData,
				dataType: "json",
				beforeSend: function () {
					// Disable form elements or show loading indicator
					$("#submit-button").prop("disabled", true); // Contoh jika ada tombol submit dengan ID submit-button
					Swal.fire({
						title: "Loading....",
						html: '<div class="spinner-border text-primary"></div>',
						showConfirmButton: false,
						allowOutsideClick: false,
						allowEscapeKey: false,
					});
				},
				success: function (response) {
					Swal.close();
					if (response.code == 200) {
						Swal.fire({
							icon: "success",
							title: "Success!",
							text: response.msg,
							showCancelButton: false,
						}).then((result) => {
							form_state("LOAD");
						});
					} else {
						// Tampilkan pesan error atau notifikasi jika ada
						Swal.fire({
							icon: "warning",
							title: "Oops...",
							text: response.msg,
							footer: '<a href="javascript:void(0)">System Notification</a>',
						});
					}
				},
				error: function (xhr, status, error) {
					Swal.close();
					// Handle error response
					Swal.fire({
						icon: "error",
						title: "Oops...",
						text: "An error occurred. Please contact your administrator!",
						footer: '<a href="javascript:void(0)">System Notification</a>',
					});
				},
				complete: function () {
					// Enable form elements or hide loading indicator
					$("#btn-submit").prop("disabled", false); // Contoh jika ada tombol submit dengan ID submit-button
				},
			});
		}
	});
	// HERE
	function validateForm() {
		var isValid = true;
		var errorMessage = " harus diisi.";

		function showErrorMessage(element, message) {
			var errorElement = $('<div class="invalid-feedback"></div>').text(
				message
			);
			$(element).addClass("is-invalid");
			$(element).parent().append(errorElement);
		}

		function clearErrorMessages() {
			$(".invalid-feedback").remove();
			$(".form-control").removeClass("is-invalid");
		}

		clearErrorMessages();

		// Validate Tanggal SO
		var tanggalSO = $("#tanggal-so");
		if (tanggalSO.val().trim() === "") {
			isValid = false;
			showErrorMessage(tanggalSO, "Tanggal SO" + errorMessage);
		}

		// Validate Nama Customer
		var namaCustomer = $("#nama-customer");
		if (namaCustomer.val().trim() === "") {
			isValid = false;
			showErrorMessage(namaCustomer, "Nama Customer" + errorMessage);
		}

		// Validate Alamat Customer
		var alamatCustomer = $("#alamat-customer");
		if (alamatCustomer.val().trim() === "") {
			isValid = false;
			showErrorMessage(alamatCustomer, "Alamat Customer" + errorMessage);
		}

		// Validate Nomer PO Customer
		var nomerPOCustomer = $("#nomer-po-customer");
		if (nomerPOCustomer.val().trim() === "") {
			isValid = false;
			showErrorMessage(nomerPOCustomer, "Nomer PO Customer" + errorMessage);
		}

		// Validate Tanggal PO Customer
		var tanggalPOCustomer = $("#tanggal-po-customer");
		if (tanggalPOCustomer.val().trim() === "") {
			isValid = false;
			showErrorMessage(tanggalPOCustomer, "Tanggal PO Customer" + errorMessage);
		}

		// Validate Tanggal Pengiriman
		var tanggalPengiriman = $("#tanggal-pengiriman");
		if (tanggalPengiriman.val().trim() === "") {
			isValid = false;
			showErrorMessage(tanggalPengiriman, "Tanggal Pengiriman" + errorMessage);
		}

		// Validate Term of Payment
		var termOfPayment = $("#term-of-payment");
		if (termOfPayment.val().trim() === "" || termOfPayment.val() <= 0) {
			isValid = false;
			showErrorMessage(
				termOfPayment,
				"Term Of Payment harus diisi dengan nilai yang valid."
			);
		}

		// Validate Unit TOP
		var unitTOP = $("#unit-top");
		if (unitTOP.val().trim() === "") {
			isValid = false;
			showErrorMessage(unitTOP, "Unit TOP" + errorMessage);
		}

		// Validate Dokumen TOP
		var dokumenTOP = $("#dokumen-top");
		if (dokumenTOP.val().trim() === "") {
			isValid = false;
			showErrorMessage(dokumenTOP, "Dokumen TOP" + errorMessage);
		}

		// Validate Rate Currency
		var rateCurrency = $("#rate-currency");
		if (rateCurrency.val().trim() === "" || rateCurrency.val() <= 0) {
			isValid = false;
			showErrorMessage(
				rateCurrency,
				"Rate Currency harus diisi dengan nilai yang valid."
			);
		}

		// Validate Only Number Fields in the #table-detail-item table
		let detail = false;
		// Validate Only Number Fields in the #table-detail-item table, excluding .input-unit-price and .input-persentase-discount
		$("#table-detail-item .only-number").each(function () {
			// Cek apakah elemen memiliki class .input-unit-price atau .input-persentase-discount
			if (
				$(this).hasClass("input-unit-price") ||
				$(this).hasClass("input-persentase-discount")
			) {
				return; // Lewati validasi untuk elemen dengan class .input-unit-price atau .input-persentase-discount
			}

			let value = $(this).val().trim();

			// Cek apakah nilai kosong, nilai 0 (termasuk 0.0000), atau dimulai dengan angka 0 dan desimalnya semua 0000
			if (
				value === "" ||
				parseFloat(value) === 0 ||
				(value.startsWith("0") && value.endsWith(".0000"))
			) {
				isValid = false;
				detail = true;
				showErrorMessage(this, "");
			}
		});

		let rowCount = $("#table-detail-item tbody tr").length;

		if (rowCount === 0) {
			isValid = false;
		}

		if (!isValid) {
			Swal.fire({
				icon: "error",
				title: "Oops...",
				text: "Lengkapi semua data dan pilih minimal 1 item!",
				confirmButtonColor: "#3085d6",
				confirmButtonText: "Tutup",
			}).then(function () {
				// Scroll ke elemen pertama yang memiliki error (is-invalid) setelah SweetAlert ditutup
				if (!detail) {
					$("html, body").animate(
						{
							scrollTop: $(".is-invalid").first().offset().top - 100, // Men-scroll ke atas elemen yang error
						},
						500
					);
				}
			});
		}

		return isValid;
	}
	// Cari item berdasarkan categori
	// xxx develove
	let selectedItemRows = {};
	//
	$("#table-select-item").on("change", "input[type='checkbox']", function () {
		// Ambil baris terkait
		let row = $(this).closest("tr");
		let table = $("#table-select-item").DataTable();
		let rowData = table.row(row).data();

		// Ambil nilai dari setiap kolom
		let itemCode = rowData.Item_Code;

		// Jika checkbox dicentang, tambahkan data ke selectedItemRows
		if (this.checked) {
			row.addClass("table-primary text-white");
			// 111
			selectedItemRows[itemCode] = {
				checked: $(this).prop("checked"),
				itemCode: rowData.Item_Code,
				itemName: rowData.Item_Name,
				itemColor: rowData.Item_Color,
				model: rowData.Model,
				brand: rowData.Brand,
				dimensionInfo: rowData.Dimension_Info,
				weightInfo: rowData.Weight_Info,
				sysId: rowData.SysId,
				unitTypeId: rowData.Unit_Type_ID,
				uom: rowData.Uom,
				sourceSysId: rowData.sourceSysId,
				codeSource: rowData.Code_Source,
				sourceName: rowData.Source_Name,
				qty: "0.0000", // Default quantity set to 0
				note: "", // Default note
				unitPrice: "0.0000", // Default unit price
				persentaseDisc: "0.0000", // Default percentage discount
				discValue: "0.0000", // Default discount value
				amountItem: "0.0000", // Default amount item
				tax1: "", // Kosongkan tax1
				tax2: "", // Kosongkan tax2
			};
		} else {
			// Jika checkbox tidak dicentang, hapus data dari selectedItemRows
			row.removeClass("table-primary text-white");

			// Hapus data terkait dari selectedItemRows berdasarkan itemCode
			delete selectedItemRows[itemCode];
		}
		$("#close-btn-modal-table-select-item").hide();
	});

	//

	function triggerSearch(isManual = false) {
		let customerId = $("#select-customer option:selected").val();
		let sysId = $("#select-category").val();

		// Hanya lakukan validasi jika tombol di-click secara manual (isManual == true)
		if (isManual) {
			const conditions = [
				{
					check: customerId === "",
					message: "Pilih nama customer!",
				},
				{
					check: sysId === "",
					message: "Pilih item category!",
				},
			];

			for (const condition of conditions) {
				if (condition.check) {
					return Swal.fire({
						icon: "warning",
						title: "Oops...",
						text: condition.message,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>',
					});
				}
			}
		}

		// Jika DataTable sudah ada, hancurkan dulu untuk inisialisasi ulang
		if ($.fn.DataTable.isDataTable("#table-select-item")) {
			$("#table-select-item").DataTable().destroy();
		}

		// Kosongkan tabel sebelum mengisi ulang data
		$("#table-select-item").empty();
		$("#table-select-item").append(`
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Item Code</th>
                <th class="text-center">Item Name</th>
                <th class="text-center">Color</th>
                <th class="text-center">Model</th>
                <th class="text-center">Brand</th>
                <th class="text-center">Dimensions</th>
                <th class="text-center">Weight</th>
                <th class="text-center" style="display: none;">SysId</th>
                <th class="text-center" style="display: none;">Unit_Type_ID</th>
                <th class="text-center" style="display: none;">Uom</th>
                <th class="text-center" style="display: none;">sourceSysId</th>
                <th class="text-center" style="display: none;">Code_Source</th>
                <th class="text-center" style="display: none;">Source_Name</th>
            </tr>
        </thead>
        <tbody></tbody>
    `); // Tambahkan kembali header setelah membersihkan tabel

		// Inisialisasi DataTable
		$("#table-select-item").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			language: {
				processing:
					'<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span>',
			},
			ajax: {
				data: {
					sysid_item_category: sysId, // Gantilah dengan nilai sysId yang sesuai
				},
				url:
					$('meta[name="base_url"]').attr("content") +
					"Sales/SalesOrder/DT_modallistofitem",
				type: "POST",
				dataType: "json",
			},
			columns: [
				{
					data: "SysId",
					render: function (data, type, row, meta) {
						return `<input type="checkbox" class="check-item" value="${data}">`;
					},
					className: "text-center",
				},
				{
					data: "Item_Code",
					className: "text-center",
				},
				{
					data: "Item_Name",
				},
				{
					data: "Item_Color",
					className: "text-center",
				},
				{
					data: "Model",
					className: "text-center",
				},
				{
					data: "Brand",
					className: "text-center",
				},
				{
					data: "Dimension_Info",
					className: "text-center",
				},
				{
					data: "Weight_Info",
					className: "text-center",
				},
				{
					data: "SysId",
					visible: false,
					className: "text-center",
				},
				{
					data: "Unit_Type_ID",
					visible: false,
					className: "text-center",
				},
				{
					data: "Uom",
					visible: false,
					className: "text-center",
				},
				{
					data: "sourceSysId",
					visible: false,
					className: "text-center",
				},
				{
					data: "Code_Source",
					visible: false,
					className: "text-center",
				},
				{
					data: "Source_Name",
					visible: false,
					className: "text-center",
				},
			],
			lengthMenu: [
				[10, 25, 50, 10000],
				[10, 25, 50, "All"],
			],
			drawCallback: function () {
				cheking_list_item();
			},
		});
	}

	//
	// Event ketika modal ditampilkan
	$("#exampleModal").on("shown.bs.modal", function () {
		// Otomatis klik tombol #btn-search-item tanpa validasi
		$("#close-btn-modal-table-select-item").show();
		triggerSearch(false);
	});
	//
	// Event untuk tombol #btn-search-item dengan validasi
	$("#btn-search-item").on("click", function () {
		// Panggil triggerSearch dengan isManual = true untuk validasi
		triggerSearch(true);
	});

	// Pindahkan item yang di select ke tabel detail
	$("#btn-select-item").click(function () {
		// BUG
		if (Object.keys(selectedItemRows).length === 0) {
			Swal.fire({
				icon: "warning",
				title: "Oops...",
				text: "Pilih setidaknya satu item untuk ditambahkan!",
				footer: '<a href="javascript:void(0)">Notifikasi System</a>',
			});
		} else {
			// 222
			// Replace `selectedItemRows` with your actual data source
			renderDynamicTable("#table-detail-item", selectedItemRows);

			// Handle customer selection details
			let selectedOption = $("#select-customer option:selected");
			$("#nama-customer").val(selectedOption.text());
			$("#customer-id").val(selectedOption.data("account-id"));
			$("#customer-code").val(selectedOption.data("account-code"));
			$("#account-npwp").val(selectedOption.data("account-npwp"));

			$("#exampleModal").modal("hide");
			$("#no_data_item").hide("slow");
		}
	});

	// Hapus item di tabel datail dan kembalikan ke tabel select item
	$(document).on("click", ".btn-delete-item", function () {
		//
		let row = $(this).closest("tr");
		// Ambil itemCode dari kolom pertama dalam baris
		let itemCode = row.find("td:first").text().trim();

		// Cek apakah itemCode ada di dalam qtyShpObject
		if (qtyShpObject.hasOwnProperty(itemCode)) {
			let qtyShp = qtyShpObject[itemCode];

			// Jika ditemukan, tampilkan SweetAlert dan hentikan penghapusan
			Swal.fire({
				title: "Tidak Dapat Dihapus",
				text: `Item dengan kode ${itemCode} memiliki jumlah pengiriman sebesar ${parseFloat(
					qtyShp
				)}. Item ini tidak dapat dihapus.`,
				icon: "warning",
				confirmButtonText: "OK",
			});

			return; // Hentikan proses penghapusan
		}

		// Jika itemCode tidak ditemukan dalam qtyShpObject, lanjutkan dengan penghapusan

		// Hapus item dari selectedItemRows
		delete selectedItemRows[itemCode];

		row.remove();
		//
		let tableDetail = $("#table-detail-item");
		if (tableDetail.find("tbody").children().length > 0) {
			$("#no_data_item").hide("slow");
		} else {
			$("#no_data_item").show("slow");
		}
	});
	// Tampilkan list alamat customer berdasarkan sysID nama customer
	$("#btn-list-address").click(function () {
		let currentSysId = $("#alamat-customer-id").val();
		let customerCode = $("#customer-code").val();

		if (customerCode == "") {
			Swal.fire({
				icon: "warning",
				title: "Ooppss...",
				text: "Silahkan pilih nama customer terlebih dahulu!",
				footer:
					'<a href="javascript:void(0)" class="text-info">Informasi System</a>',
			});
			return;
		}

		$.ajax({
			url:
				$('meta[name="base_url"]').attr("content") +
				"Sales/SalesOrder/DT_listofaccount_address",
			type: "POST",
			data: {
				customer_code: customerCode,
			},
			dataType: "json",
			success: function (response) {
				if (response.code === 200) {
					let data = response.data;
					let noAlamat = false;
					tableAddress.clear().rows.add(data).draw();

					$("#table-address tbody tr").each(function () {
						let rowData = tableAddress.row(this).data();
						if (typeof rowData == "undefined") {
							Swal.fire({
								icon: "info",
								title: "Informasi",
								text: "Customer belum memiliki alamat",
								footer:
									'<a href="javascript:void(0)" class="text-info">Informasi System</a>',
							});
							noAlamat = true;
							return;
						}
						let sysid = rowData.SysId;
						if (sysid === currentSysId) {
							$(this).find('input[type="radio"]').prop("checked", true);
							$(this).addClass("table-primary");
						}
					});

					if (!noAlamat) {
						$("#addressModal").modal("show");
					}
				} else {
					Swal.fire({
						icon: "error",
						title: "Gagal",
						text: response.msg,
						footer:
							'<a href="javascript:void(0)" class="text-info">Informasi System</a>',
					});
				}
			},
			error: function (xhr, status, error) {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Terjadi kesalahan dalam mengambil data. Silakan coba lagi.",
					footer:
						'<a href="javascript:void(0)" class="text-info">Informasi System</a>',
				});
			},
		});
	});

	// Pindahkan alamat customer yang di select ke text area
	$("#btn-select-address").click(function (e) {
		let selectedRow = $('input[name="selectAddress"]:checked').closest("tr");
		let rowData = tableAddress.row(selectedRow).data();
		let selectedAddress = rowData.Address;
		let selectedSysId = rowData.SysId;
		// Tampilkan address di textarea dan simpan SysId di data atribut
		$("#alamat-customer").val(selectedAddress).data("sysid", selectedSysId);
		$("#alamat-customer-id").val(selectedSysId);
		// Tutup modal
		$("#addressModal").modal("hide");
	});
	// Update currency jika yang dipilih bukan default / IDR
	$("#currency").change(function () {
		var selectedOption = $(this).find("option:selected");
		updateCurrencyFields(selectedOption);
	});
	//
	$(document).on("input", ".input-note", function () {
		// Ambil baris terkait
		let currentRow = $(this).closest("tr");
		let note = $(this).val();

		// Ambil itemCode dari input yang diubah
		let itemCode = currentRow.find("input[name*='Item_Code']").val();

		// Perbarui objek selectedItemRows
		if (selectedItemRows[itemCode]) {
			selectedItemRows[itemCode].note = note;
		}
	});
	// ARITMATIKA
	// Menghitung QTY setiap kali diinput
	// xxx
	$(document).on("keyup", ".input-qty", function () {
		// formatInput(this);
		let currentRow = $(this).closest("tr");
		let qty = formatAritmatika($(this).val());
		// formatAritmatika;
		// let qty = formatAritmatika($(this).val().replace(/\./g, "")) ;
		let unitPrice = formatAritmatika(currentRow.find("td:eq(7) input").val());
		// let unitPrice =
		// 	formatAritmatika(currentRow.find("td:eq(7) input").val().replace(/\./g, "")) ||
		// 	0;
		let persentaseDisc = formatAritmatika(
			currentRow.find("td:eq(8) input").val()
		);
		// let persentaseDisc =
		// 	formatAritmatika(currentRow.find("td:eq(8) input").val().replace(/\./g, "")) ||
		// 	0;
		//
		calculateTotalDiscItem(currentRow, qty, unitPrice, persentaseDisc);
	});
	//
	$(document).on("keyup", "#discount-percentage", function () {
		toggleVisibility("#btn-submit", "hide");
		setTimeout(function () {
			toggleVisibility("#btn-calculate", "show");
		}, 500);
	});
	// Menghitung UNIT PRICE setiap kali diinput
	$(document).on("keyup", ".input-unit-price", function () {
		// formatInput(this);
		let currentRow = $(this).closest("tr");
		let qty = formatAritmatika(currentRow.find("td:eq(5) input").val());
		// let qty =
		// 	formatAritmatika(currentRow.find("td:eq(5) input").val().replace(/\./g, "")) ||
		// 	0;
		let unitPrice = formatAritmatika($(this).val());
		// let unitPrice = formatAritmatika($(this).val().replace(/\./g, "")) ;
		let persentaseDisc = formatAritmatika(
			currentRow.find("td:eq(8) input").val()
		);
		// let persentaseDisc =
		// 	formatAritmatika(currentRow.find("td:eq(8) input").val().replace(/\./g, "")) ||
		// 	0;
		//
		//
		calculateTotalDiscItem(currentRow, qty, unitPrice, persentaseDisc);
		//
	});
	// Menghitung PERSENTASE DISKON setiap kali diinput
	$(document).on("keyup", ".input-persentase-discount", function () {
		// formatInput(this);
		let currentRow = $(this).closest("tr");
		let qty = formatAritmatika(currentRow.find("td:eq(5) input").val());
		// let qty =
		// 	formatAritmatika(currentRow.find("td:eq(5) input").val().replace(/\./g, "")) ||
		// 	0;
		let unitPrice = formatAritmatika(currentRow.find("td:eq(7) input").val());
		// let unitPrice =
		// 	formatAritmatika(
		// 		currentRow.find("td:eq(7) input").val().replace(/\./g, "")
		// 	) ;
		let persentaseDisc = formatAritmatika($(this).val());
		// let persentaseDisc = formatAritmatika($(this).val().replace(/\./g, "")) ;s
		//
		calculateTotalDiscItem(currentRow, qty, unitPrice, persentaseDisc);
		//
	});
	// Menghitung PAJAK setiap kali dipilih
	// xxx PAJAK
	$("#table-detail-item tbody").on("change", ".tax1, .tax2", function () {
		updateSelectedItemRowTaxs();
	});

	function updateSelectedItemRowTaxs() {
		// Iterate over each row in the table
		$("#table-detail-item tbody tr").each(function () {
			let itemCode = $(this)
				.find("input[name^='details['][name$='[Item_Code]']")
				.val();
			let tax1Rate = formatAritmatika(
				$(this).find(".tax1 option:selected").val()
			);
			let tax2Rate = formatAritmatika(
				$(this).find(".tax2 option:selected").val()
			);
			// alert(tax1Rate);
			if (selectedItemRows[itemCode]) {
				// Update the `selectedItemRows` object with the new tax values
				selectedItemRows[itemCode].tax1 = tax1Rate;
				selectedItemRows[itemCode].tax2 = tax2Rate;
			}
		});

		toggleVisibility("#btn-submit", "hide");
		setTimeout(function () {
			toggleVisibility("#btn-calculate", "show");
		}, 500);
	}

	// Ketika pengguna mengetik atau kehilangan fokus (blur) pada .input-qty
	$(document).on("keyup", ".input-qty", function () {
		let currentRow = $(this).closest("tr");
		let qty = formatAritmatika($(this).val());
		let unitPrice = formatAritmatika(currentRow.find("td:eq(7) input").val());
		let persentaseDisc = formatAritmatika(
			currentRow.find("td:eq(8) input").val()
		);

		calculateTotalDiscItem(currentRow, qty, unitPrice, persentaseDisc);
	});

	// Kalkulasikan QTY, UNIT PRICE, PERSENTASE DISKON, dan PAJAK yang dipilih
	$("#btn-calculate").click(function () {
		if (Object.keys(selectedItemRows).length === 0) {
			Swal.fire({
				icon: "warning",
				title: "Oops...",
				text: "Pilih setidaknya satu item untuk ditambahkan!",
				footer: '<a href="javascript:void(0)">Notifikasi System</a>',
			});
			return;
		}

		let isCalculationComplete = true;

		$("#table-detail-item tbody tr").each(function () {
			let currentRow = $(this);
			let itemCode = currentRow.find("td:eq(0)").text().trim(); // Ambil itemCode dari kolom pertama
			let qty = formatAritmatika(currentRow.find("td:eq(5) input").val());
			let unitPrice = formatAritmatika(currentRow.find("td:eq(7) input").val());
			let persentaseDisc = formatAritmatika(
				currentRow.find("td:eq(8) input").val()
			);

			if (isNaN(qty) || isNaN(unitPrice) || isNaN(persentaseDisc)) {
				isCalculationComplete = false; // Jika ada yang tidak valid, berhenti
				return false; // Keluar dari loop
			}

			if (qtyShpObject.hasOwnProperty(itemCode)) {
				let qtyShp = qtyShpObject[itemCode];

				if (parseFloat(qty) < parseFloat(qtyShp)) {
					Swal.fire({
						icon: "error",
						title: "Qty Tidak Valid",
						text: `Jumlah item dengan kode ${itemCode} tidak boleh kurang dari ${parseFloat(
							qtyShp
						)}.`,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>',
					});
					isCalculationComplete = false;
					return false; // Keluar dari loop
				}
			}

			calculateTotalDiscItem(currentRow, qty, unitPrice, persentaseDisc, true);
		});
		// Jika perhitungan selesai untuk semua baris, lakukan toggle tombol
		if (isCalculationComplete) {
			toggleVisibility("#btn-calculate", "hide");
			setTimeout(function () {
				toggleVisibility("#btn-submit ", "show");
			}, 500);
		}
		// Lakukan perhitungan pajak setelah semua baris selesai dihitung
		calculateTax();

		let discountAll = $("#discount-percentage").val();

		$("#discount-percentage").val(roundToFourDecimals(discountAll));
	});

	// Kembali ke tampilan sebelumnya
	$(document).on("click", "#back", function () {
		form_state("BACK");
	});

	$("#table-address tbody").on("click", 'input[type="radio"]', function () {
		$("#table-address tbody tr").removeClass("table-primary");
		$(this).closest("tr").addClass("table-primary");
	});

	function updateCurrencyFields(selectedOption) {
		$("#currency-name").val(selectedOption.data("currency-name"));
		$("#currency-symbol").val(selectedOption.data("currency-symbol"));
		$("#rate-currency").val(selectedOption.data("currency-default"));
	}
	// Function ini dipanggil setiap kali user akan menambah item
	// YANG berfungsi untuk menyusun ulang baris data yang ada di tabel select item.
	// function reload_on_delete() {
	// 	let table = $("#table-select-item").DataTable();
	// 	table.order([0, "asc"]).draw();
	// }
	// YANG berfungsi untuk menghapus baris data yang ada di tabel detail item agar tidak ditampilkan di tabel select item.
	function cheking_list_item() {
		// Ambil semua nilai checkbox dari table-detail-item
		let checkboxValues = [];
		$("#table-detail-item tbody .btn-delete-item").each(function () {
			checkboxValues.push($(this).val());
		});
		// Iterasi baris pada table-select-item
		$("#table-select-item tbody tr").each(function () {
			let table = $("#table-select-item").DataTable();
			let rowData = table.row(this).data();

			if (rowData) {
				let itemCode = rowData.Item_Code;
				let checkbox = $(this).find("input[type='checkbox']");
				let checkboxValue = checkbox.val();
				// Hapus baris jika checkboxValue ada di checkboxValues
				if (checkboxValues.includes(checkboxValue)) {
					$(this).remove();
				} else {
					// Set status checkbox dan tambahkan/hapus kelas berdasarkan selectedItemRows
					if (selectedItemRows[itemCode]) {
						checkbox.prop("checked", selectedItemRows[itemCode].checked);
						if (selectedItemRows[itemCode].checked) {
							$(this).addClass("table-primary text-white");
						} else {
							$(this).removeClass("table-primary text-white");
						}
					} else {
						$(this).removeClass("table-primary text-white");
					}
				}
			}
		});
	}
	// Function ini dipanggil setiap kali user melakukan tindakan seperti ADD dan EDIT data

	// Function untuk menampilkan list sales order
	// Variabel global untuk menyimpan data yang terpilih

	function reloadData() {
		let selectedSalesOrders = {}; // Object to store selected Sales Orders

		const dataTable = $("#DataTable").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
			lengthMenu: [
				[10, 25, 50, 10000],
				[10, 25, 50, "All"],
			],
			ajax: {
				url:
					$('meta[name="base_url"]').attr("content") +
					"Sales/SalesOrder/DT_listdata",
				dataType: "json",
				type: "POST",
			},
			columns: [
				{
					data: "SO_Number",
					createdCell: function (td) {
						$(td).addClass("text-center vertical-align-middle");
					},
				},
				{
					data: "SO_Date",
					render: function (data) {
						return data.substring(0, 10);
					},
					createdCell: function (td) {
						$(td).addClass("text-center vertical-align-middle");
					},
				},
				{
					data: "Customer_Name",
					createdCell: function (td) {
						$(td).addClass("text-center vertical-align-middle");
					},
				},
				{
					data: "PO_Number",
					createdCell: function (td) {
						$(td).addClass("text-center vertical-align-middle");
					},
				},
				{
					data: "Amount",
					createdCell: function (td) {
						$(td).addClass("text-center vertical-align-middle");
					},
					render: function (data) {
						return formatIdrAccounting(formatAritmatika(data));
					},
				},
				{
					data: "Currency_Symbol",
					createdCell: function (td) {
						$(td).addClass("text-center text-uppercase vertical-align-middle");
					},
				},
				{
					data: "SO_DeliveryDate",
					render: function (data) {
						return data.substring(0, 10);
					},
					createdCell: function (td) {
						$(td).addClass("text-center vertical-align-middle");
					},
				},
				{
					data: "Approve",
					render: function (data) {
						return data == 1
							? '<div class="d-flex justify-content-center"><i class="fas fa-check text-success"></i></div>'
							: data == 2
							? '<div class="d-flex justify-content-center"><i class="fas fa-times text-danger"></i></div>'
							: '<div class="d-flex justify-content-center"><i class="fas fa-question text-warning"></i></div>';
					},
					createdCell: function (td) {
						$(td).addClass("text-center vertical-align-middle");
					},
				},
				{
					data: "Is_Close",
					render: function (data) {
						return data == 1
							? '<div class="d-flex justify-content-center"><span class="badge bg-success">Closed</span></div>'
							: '<div class="d-flex justify-content-center"><span class="badge bg-warning">Open</span></div>';
					},
					createdCell: function (td) {
						$(td).addClass("text-center vertical-align-middle");
					},
				},
			],
			order: [[0, "desc"]],
			columnDefs: [
				{
					targets: "_all",
				},
			],
			autoWidth: false,
			preDrawCallback: function () {
				$("#DataTable tbody td").addClass("blurry");
			},
			language: {
				processing:
					'<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" class="loading-text"></span></p>',
			},
			initComplete: function () {
				$("#DataTable tbody").on("click", "tr", function () {
					const rowData = dataTable.row(this).data();

					if (rowData && rowData.SysId) {
						const sysId = rowData.SysId;

						// Check if the row is already selected
						if ($(this).hasClass("table-primary")) {
							// Deselect the row and return to warning if previously had warning
							if ($(this).hasClass("table-warning-selected")) {
								$(this)
									.removeClass("table-primary text-white")
									.addClass("table-warning");
							} else {
								// Just remove the primary selection
								$(this).removeClass("table-primary text-white");
							}

							// Remove from selectedSalesOrders
							delete selectedSalesOrders[sysId];
						} else {
							// Deselect all rows and remove the background color, while restoring warning color
							$("#DataTable tbody tr").each(function () {
								if ($(this).hasClass("table-warning-selected")) {
									$(this)
										.addClass("table-warning")
										.removeClass(
											"table-primary text-white table-warning-selected"
										);
								} else {
									$(this).removeClass("table-primary text-white");
								}
							});

							selectedSalesOrders = {};

							// Select the clicked row and store the data
							if ($(this).hasClass("table-warning")) {
								$(this)
									.removeClass("table-warning")
									.addClass("table-primary text-white table-warning-selected");
							} else {
								$(this).addClass("table-primary text-white");
							}

							selectedSalesOrders[sysId] = rowData;
						}
					} else {
						console.error("Row data or SysId is undefined", rowData);
					}
				});
			},
			drawCallback: function () {
				// Kosongkan selectedSalesOrders yang tidak sesuai dengan hasil pencarian
				$("#DataTable tbody tr").each(function () {
					const rowData = dataTable.row(this).data();
					// Log row data on draw
					if (rowData && selectedSalesOrders[rowData.SysId]) {
						// Jika SysId ditemukan dalam hasil pencarian, tetap simpan di selectedSalesOrders

						if (selectedSalesOrders[rowData.SysId]) {
							$(this)
								.removeClass("table-warning")
								.addClass("table-primary text-white table-warning-selected");
						}
					} else {
						// Hapus SysId yang tidak ada dalam hasil pencarian
						for (const sysId in selectedSalesOrders) {
							if (selectedSalesOrders.hasOwnProperty(sysId) && !rowData) {
								delete selectedSalesOrders[sysId];
							}
						}
					}
					if (rowData && rowData.Is_Close == 1) {
						$(this).addClass("table-warning");
					}
					// Terapkan background kuning untuk baris di mana Is_Close == 1
				});

				$("#DataTable tbody td").addClass("blurry");
				setTimeout(function () {
					$("#DataTable tbody td").removeClass("blurry");
				}, 100); // Tambahkan delay untuk memastikan rendering

				$('[data-toggle="tooltip"]').tooltip();
			},

			buttons: [
				{
					text: `<i class="fas fa-plus fs-3"></i> ADD Sales Order`,
					className: "bg-primary",
					action: function () {
						form_state("ADD");
					},
				},
				{
					text: `<i class="fas fa-search"></i> View Detail`,
					className: "btn btn-info",
					action: function () {
						if (Object.keys(selectedSalesOrders).length === 0) {
							Swal.fire({
								icon: "warning",
								title: "Ooppss...",
								text: "Silahkan pilih data untuk melihat detail !",
								footer:
									'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
							});
						} else {
							let selectedRow = Object.values(selectedSalesOrders)[0];
							let sysId = selectedRow.SysId;
							let url =
								$('meta[name="base_url"]').attr("content") +
								"Sales/SalesOrder/detail/" +
								sysId;
							window.location.href = url;
						}
					},
				},
				{
					// DEV HERE
					text: `<i class="fas fa-edit fs-3"></i> Edit`,
					className: "btn btn-warning",
					action: function () {
						if (Object.keys(selectedSalesOrders).length === 0) {
							Swal.fire({
								icon: "warning",
								title: "Ooppss...",
								text: "Silahkan pilih data untuk melihat detail !",
								footer:
									'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
							});
						} else {
							let selectedRow = Object.values(selectedSalesOrders)[0];
							let sysId = selectedRow.SysId;
							let Approve = selectedRow.Approve;
							let isClose = selectedRow.Is_Close;
							let message =
								Approve == 1
									? "Data sudah diapprove"
									: Approve == 2
									? "Data sudah diriject"
									: isClose == 1
									? "Data sudah diclose"
									: null;
							if (message) {
								Swal.fire({
									icon: "info",
									title: "Informasi",
									text: message,
									footer:
										'<a href="javascript:void(0)" class="text-info">Informasi System</a>',
								});
								return;
							}
							Init_Edit_Revisi(sysId, "EDIT");
						}
					},
				},
				{
					text: `<i class="fas fa-random fs-3"></i> Revisi`,
					className: "btn btn-success",
					action: function () {
						let selectedRow = Object.values(selectedSalesOrders)[0];
						let sysId = selectedRow.SysId;
						let Approve = selectedRow.Approve;
						let isClose = selectedRow.Is_Close;
						// let message = isClose == 1 ? "Data sudah di close" : null;
						let message =
							isClose == 1
								? "Data sudah di close"
								: Approve == 2
								? "Data sudah di reject"
								: null;
						if (message) {
							Swal.fire({
								icon: "info",
								title: "Informasi",
								text: message,
								footer:
									'<a href="javascript:void(0)" class="text-info">Informasi System</a>',
							});
							return;
						}
						Init_Edit_Revisi(sysId, "REVISI");
					},
				},
				{
					text: `<i class="fas fa-print fs-3"></i> Print`,
					className: "btn bg-gradient-success",
					action: function () {
						if (Object.keys(selectedSalesOrders).length === 0) {
							Swal.fire({
								icon: "warning",
								title: "Ooppss...",
								text: "Silahkan pilih data terlebih dahulu untuk mencetak report!",
								footer:
									'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
							});
						} else {
							let selectedRow = Object.values(selectedSalesOrders)[0];
							let sysId = selectedRow.SysId;
							let Approve = selectedRow.Approve;
							let isClose = selectedRow.Is_Close;

							// Check approval and close status
							if (isClose == 1 || Approve == 2 || Approve == 0) {
								Swal.fire({
									icon: "warning",
									title: "Ooppss...",
									text: "Report cetak hanya bisa di tampilkan pada data yang telah legitimate (tidak close dan sudah approve)!",
									footer:
										'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
								});
							} else {
								// Open PDF report in a new tab
								window.open(
									$('meta[name="base_url"]').attr("content") +
										"Sales/SalesOrder/export_pdf_so/" +
										sysId,
									"_blank"
								);
								//
							}
						}
					},
				},
				{
					text: `<i class="fa fa-times fs-3"></i>  <small><i>Close</i></small>`,
					className: "btn btn-dark",
					action: function () {
						if (Object.keys(selectedSalesOrders).length === 0) {
							Swal.fire({
								icon: "warning",
								title: "Ooppss...",
								text: "Silahkan pilih data untuk merubah status !",
								footer:
									'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
							});
						} else {
							let selectedRow = Object.values(selectedSalesOrders)[0];
							let sysId = selectedRow.SysId;
							let isClose = selectedRow.Is_Close;

							if (isClose == 1) {
								Swal.fire({
									icon: "info",
									title: "Informasi",
									text: "Data sudah di close.",
									footer:
										'<a href="javascript:void(0)" class="text-info">Informasi System</a>',
								});
								return;
							}
							Fn_Closed_Status(sysId);
						}
					},
				},
				// ,
				// {
				// 	text: `Export to :`,
				// 	className: "btn disabled text-dark bg-white",
				// },
				// {
				// 	text: `<i class="far fa-file-excel"></i>`,
				// 	extend: "excelHtml5",
				// 	title: $("#table-title").text() + "~" + moment().format("YYYY-MM-DD"),
				// 	className: "btn btn-success",
				// },
				// {
				// 	text: `<i class="far fa-file-pdf"></i>`,
				// 	extend: "pdfHtml5",
				// 	title: $("#table-title").text() + "~" + moment().format("YYYY-MM-DD"),
				// 	className: "btn btn-danger",
				// 	orientation: "landscape",
				// },
			],
		});

		// Attach buttons to the DataTable
		dataTable
			.buttons()
			.container()
			.appendTo("#DataTable_wrapper .col-md-6:eq(0)");
	}

	function form_state(state) {
		$(".add-data").hide();
		switch (state) {
			case "LOAD":
				$(".list-data").show("slow");
				$('input[name="state"]').val("");
				reloadData();
				break;
			case "ADD":
				$("#action-tittle").text("ADD");
				reset_input();
				setFormState(false, false);
				$('input[name="state"]').val("ADD");
				$(".list-data").hide("slow");
				$(".add-data").show("slow");
				$(".select2-no-ajx")
					.select2()
					.on("select2:open", function () {
						let $searchField = $(this)
							.data("select2")
							.$dropdown.find(".select2-search__field");
						$searchField.attr("placeholder", "Search...");
					});
				flatpickr();
				break;
			case "EDIT":
				$("#action-tittle").text("Edit");
				reset_input();
				setFormState(false, false);
				$('input[name="state"]').val("EDIT");
				$(".list-data").hide("slow");
				$(".add-data").show("slow");
				break;
			case "REVISI":
				$("#action-tittle").text("Revisi");
				reset_input();
				setFormState(true, true);
				$('input[name="state"]').val("REVISI");
				$(".list-data").hide("slow");
				$(".add-data").show("slow");
				break;
			case "BACK":
				reloadData();
				$(".list-data").show("slow");
				$(".add-data").hide("slow");
				break;
		}
	}

	// Function Untuk merubah setatus dari open ke close
	function Fn_Closed_Status(sys_id) {
		Swal.fire({
			title: "System message!",
			text: `Apakah anda yakin untuk merubah status item ini menjadi close ?`,
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, ubah!",
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url:
						$('meta[name="base_url"]').attr("content") +
						"Sales/SalesOrder/close_status",
					type: "post",
					dataType: "json",
					data: {
						sys_id: sys_id,
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
						if (response.code == 200) {
							Swal.fire({
								icon: "success",
								title: "Success!",
								text: response.msg,
								confirmButtonColor: "#3085d6",
								confirmButtonText: "Yes, Confirm!",
							});

							// Reload DataTable and apply table-warning to the closed row
							const dataTable = $("#DataTable").DataTable();
							dataTable.ajax.reload(function () {
								// Apply warning class to the closed row
								const row = $("#DataTable tbody tr").filter(function () {
									const rowData = dataTable.row(this).data();
									return rowData && rowData.SysId === sys_id;
								});

								if (row.length) {
									row
										.removeClass(
											"table-primary text-white table-warning-selected"
										)
										.addClass("table-warning");
								}
							}, false);
						} else {
							Swal.fire({
								icon: "error",
								title: "Oops...",
								text: response.msg,
								confirmButtonColor: "#3085d6",
								confirmButtonText: "Yes, Confirm!",
								footer: '<a href="javascript:void(0)">Notification System</a>',
							});
						}
					},
					error: function (xhr, status, error) {
						let statusCode = xhr.status;
						let errorMessage =
							xhr.responseJSON && xhr.responseJSON.message
								? xhr.responseJSON.message
								: xhr.responseText
								? xhr.responseText
								: "Terjadi kesalahan: " + error;
						Swal.fire({
							icon: "error",
							title: "Error!",
							html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
						});
					},
				});
			}
		});
	}

	// DEV HERE
	// Function Untuk menampilkan data yang dipilih untuk diedit
	function Init_Edit_Revisi(sys_id, State) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr("content") + "Sales/SalesOrder/edit",
			data: {
				sys_id: sys_id,
				state: State,
			},
			success: function (response) {
				if (response.code === 200) {
					form_state(State);

					// Assign response.qty_shp ke objek kosong
					qtyShpObject = response.qty_shp;

					// Data header
					$('select[name="currency"]')
						.select2()
						.val(response.data_hdr.CurrencyType_Id)
						.trigger("change");

					// $('select[name="select-customer"]')
					// 	.select2()
					// 	.val(response.data_hdr.Customer_Id)
					// 	.trigger("change");

					$("#unit-top").val(response.data_hdr.TOP_unit).trigger("change");
					$("#dokumen-top").val(response.data_hdr.TOP_Doc).trigger("change");
					// Input hedden
					$("#customer-id").val(response.data_hdr.Customer_Id);
					$("#customer-code").val(response.data_hdr.Customer_Code);
					$("#alamat-customer-id").val(response.data_hdr.CustomerAddress_Id);
					$("#account-npwp").val(response.data_hdr.NPWP);
					$("#so-sysId").val(response.data_hdr.SysId);
					$("#so-rev").val(response.data_hdr.SO_Rev);
					// Reset specific input fields by ID
					$("#nomer-so").val(response.data_hdr.SO_Number);
					$('input[name="tanggal_so"]').val(
						moment(response.data_hdr.SO_Date).format("DD MMMM YYYY")
					);
					$("#nama-customer").val(response.data_hdr.Customer_Name);
					$("#alamat-customer").val(response.data_hdr.Customer_Address);
					$("#nomer-po-customer").val(response.data_hdr.PO_Number);
					$('input[name="tanggal_po_customer"]').val(
						moment(response.data_hdr.PO_Date).format("DD MMMM YYYY")
					);
					$('input[name="tanggal_pengiriman"]').val(
						moment(response.data_hdr.SO_DeliveryDate).format("DD MMMM YYYY")
					);
					$("#term-of-payment").val(response.data_hdr.Term_Of_Payment);
					$("#rate-currency").val(parseFloat(response.data_hdr.Currency_Rate));
					//
					$("#discount-percentage").val(
						roundToFourDecimals(
							formatAritmatika(response.data_hdr.Discount_Persen)
						)
					);
					$("#keterangan").val(response.data_hdr.Remarks);
					//
					$("#total_tax").val(roundToFourDecimals(response.total_tax));
					$("#total-amount").val(roundToFourDecimals(response.total_amount));
					//
					// Mapping data_dtl to match the format expected by renderDynamicTable
					response.data_dtl.forEach((item) => {
						selectedItemRows[item.Item_Code] = {
							checked: true, // or false, depending on your logic
							itemCode: item.Item_Code,
							itemName: item.Item_Name,
							itemColor: item.Item_Color,
							model: item.Model || "-", // Assuming you have a model field
							brand: item.Brand || "-",
							dimensionInfo: item.Dimension_Info, // Assuming you have dimension info field
							weightInfo: item.Weight_Info, // Assuming you have weight info field
							sysId: item.SysId_Item,
							unitTypeId: item.Unit_Id,
							uom: item.Uom,
							sourceSysId: item.Cs_SysId_Item,
							codeSource: item.Cs_Item_Code,
							sourceName: item.Cs_Item_Name,
							qty: roundToFourDecimals(formatAritmatika(item.Qty)), // Convert to integer
							note: item.Note,
							unitPrice: roundToFourDecimals(formatAritmatika(item.Item_Price)), // Convert to integer
							persentaseDisc: roundToFourDecimals(
								formatAritmatika(item.Discount)
							), // Convert to integer
							discValue: roundToFourDecimals(
								formatAritmatika(item.Discount_Amount)
							), // Convert to integer
							amountItem: roundToFourDecimals(
								formatAritmatika(item.Amount_Detail)
							), // Convert to integer
							tax1: item.Type_Tax_1, // Convert to integer
							tax2: item.Type_Tax_2, // Convert to integer
						};
					});

					//
					// Populate table with mapped data

					renderDynamicTable("#table-detail-item", selectedItemRows);

					$("#no_data_item").hide();
					// $("#btn-calculate").click();
				} else {
					Swal.fire({
						icon: "error",
						title: "Oops...",
						text: response.msg,
						footer: '<a href="javascript:void(0)">Notifikasi System</a>',
					});
				}
			},
			error: function (xhr, status, error) {
				Swal.fire({
					icon: "error",
					title: "Error!",
					html: `Kode HTTP: ${xhr.status}<br>Pesan: ${
						xhr.responseText || error
					}`,
				});
			},
		});
	}

	function renderDynamicTable(tableSelector, data) {
		//
		const $tableBody = $(tableSelector).find("tbody");
		$tableBody.empty(); // Clear any existing rows

		// Convert object data to an array using Object.values()
		let dataArray = Object.values(data);

		let rowCount = 0;

		dataArray.forEach((item) => {
			let amountDisc =
				formatAritmatika(item.amountItem) - formatAritmatika(item.discValue);
			let newRow = $(
				"<tr class='odd'>" +
					"<td class='text-center' style='vertical-align: middle;'>" +
					item.itemCode +
					"</td>" +
					"<td class='text-center' style='vertical-align: middle;'>" +
					item.itemName +
					"</td>" +
					"<td class='text-center' style='vertical-align: middle;'><div style='font-size: 0.7rem;' class='input-group input-group-sm'><input type='text' name='details[" +
					rowCount +
					"][note]' class='form-control form-control-sm input-note p-1 text-center' value='" +
					item.note +
					"'></div></td>" +
					"<td class='text-center' style='vertical-align: middle;'>" +
					item.itemColor +
					"</td>" +
					"<td class='text-center' style='vertical-align: middle;'>" +
					item.brand +
					"</td>" +
					"<td class='text-center' style='vertical-align: middle;'><div class='input-group input-group-sm'><input required style='font-size: 0.7rem;' type='text' name='details[" +
					rowCount +
					"][detail_qty]' class='form-control form-control-sm input-qty p-1 only-number input-decimal text-center' value='" +
					item.qty +
					"' required></div></td>" +
					"<td class='text-center text-uppercase' style='vertical-align: middle;' class='text-uppercase'>" +
					item.uom +
					"</td>" +
					"<td class='text-center' style='vertical-align: middle;'><div class='input-group input-group-sm'><input style='font-size: 0.7rem;' type='text' name='details[" +
					rowCount +
					"][detail_unit_price]' class='form-control form-control-sm input-unit-price p-1 only-number input-decimal text-center' value='" +
					item.unitPrice +
					"' required></div></td>" +
					"<td class='text-center' style='vertical-align: middle;'><div class='input-group input-group-sm'><input style='font-size: 0.7rem;' type='text' name='details[" +
					rowCount +
					"][detail_persentase_discount]' class='form-control form-control-sm input-persentase-discount p-1 only-number input-decimal text-center' value='" +
					item.persentaseDisc +
					"'></div></td>" +
					"<td class='text-center' style='vertical-align: middle;'><div class='input-group input-group-sm'><input style='font-size: 0.7rem;' type='text' name='details[" +
					rowCount +
					"][disc_value]' class='form-control form-control-sm p-1 text-center' value='" +
					item.discValue +
					"' readonly></div></td>" +
					"<td class='text-center' style='vertical-align: middle;'><div class='input-group input-group-sm'><input style='font-size: 0.7rem;' type='text' name='details[" +
					rowCount +
					"][amount_item]' class='form-control form-control-sm p-1 text-center read-only-amount' value='" +
					roundToFourDecimals(amountDisc) +
					"' readonly></div></td>" +
					"<td class='text-center' style='vertical-align: middle;'><div class='input-group input-group-sm'><select style='font-size: 0.7rem;' name='details[" +
					rowCount +
					"][tax1]' class='form-control form-control-sm tax1 p-1 text-center' id='tax1-" +
					rowCount +
					"'></select></div></td>" +
					"<td class='text-center' style='vertical-align: middle;'><div class='input-group input-group-sm'><select style='font-size: 0.7rem;' name='details[" +
					rowCount +
					"][tax2]' class='form-control form-control-sm tax2 p-1 text-center' id='tax2-" +
					rowCount +
					"'></select></div></td>" +
					"<td class='text-center'><button type='button' id='btn-delete-item' class='btn btn-sm btn-link text-danger text-center btn-delete-item' value='" +
					item.itemCode +
					"'><span class='fa fa-times'></span></button></td>" +
					"<input type='hidden' name='details[" +
					rowCount +
					"][Unit_Id]' value='" +
					item.unitTypeId +
					"' />" +
					"<input type='hidden' name='details[" +
					rowCount +
					"][Item_Code]' value='" +
					item.itemCode +
					"' />" +
					"<input type='hidden' name='details[" +
					rowCount +
					"][Item_Name]' value='" +
					item.itemName +
					"' />" +
					"<input type='hidden' name='details[" +
					rowCount +
					"][SysId_Item]' value='" +
					item.sysId +
					"' />" +
					"<input type='hidden' name='details[" +
					rowCount +
					"][Cs_SysId_Item]' value='" +
					item.sourceSysId +
					"' />" +
					"<input type='hidden' name='details[" +
					rowCount +
					"][Cs_Item_Code]' value='" +
					item.codeSource +
					"' />" +
					"<input type='hidden' name='details[" +
					rowCount +
					"][Cs_Item_Name]' value='" +
					item.sourceName +
					"' />" +
					"</tr>"
			);

			$tableBody.append(newRow);

			// Fetch tax options using the provided callback
			if (typeof fetchTaxOptions === "function") {
				fetchTaxOptions($("#tax1-" + rowCount), item.tax1, "tax1");
				fetchTaxOptions($("#tax2-" + rowCount), item.tax2, "tax2");
			}

			rowCount++;
		});

		// Additional post-processing if needed
	}

	function fetchTaxOptions($selectElement, selectedTax, taxType) {
		$selectElement.empty(); // Kosongkan opsi select terlebih dahulu

		// Tambahkan opsi "None" paling atas
		let noneOption = $("<option></option>").val("").text("None");
		$selectElement.append(noneOption);

		// Iterasi melalui taxOptions untuk menambahkan opsi lainnya
		taxOptions.forEach(function (tax) {
			let option = $("<option></option>").val(tax.Tax_Id).text(tax.Tax_Code);

			// Cek apakah taxType adalah 'tax1' atau 'tax2', dan bandingkan dengan selectedTax
			if (taxType === "tax1" && selectedTax === tax.Tax_Id) {
				option.attr("selected", "selected"); // Tandai opsi terpilih untuk tax1
			} else if (taxType === "tax2" && selectedTax === tax.Tax_Id) {
				option.attr("selected", "selected"); // Tandai opsi terpilih untuk tax2
			}

			$selectElement.append(option);
		});
	}

	function calculateTotalDiscItem(
		currentRow,
		qty,
		unitPrice,
		persentaseDisc,
		cal = false
	) {
		let value = qty * unitPrice;
		let discValue = value * (persentaseDisc / 100);
		//
		let qtyFormat = roundToFourDecimals(qty);
		let unitePriceFormat = roundToFourDecimals(unitPrice);
		let persentaseDiscFormat = roundToFourDecimals(persentaseDisc);
		let discValueFormat = roundToFourDecimals(discValue);
		// Perbarui nilai di objek selectedItemRows
		let itemCode = currentRow.find("input[name*='Item_Code']").val();
		if (selectedItemRows[itemCode]) {
			selectedItemRows[itemCode].qty = qtyFormat;
			selectedItemRows[itemCode].unitPrice = unitePriceFormat;
			selectedItemRows[itemCode].persentaseDisc = persentaseDiscFormat;
			selectedItemRows[itemCode].discValue = discValueFormat;
		}

		calculateAmountItem(currentRow, value, discValue);

		if (cal) {
			currentRow.find("td:eq(5) input").val(qtyFormat);
			currentRow.find("td:eq(7) input").val(unitePriceFormat);
			currentRow.find("td:eq(8) input").val(persentaseDiscFormat);
			//
			$("#discount-percentage").val($("#discount-percentage").val());
		} else {
			toggleVisibility("#btn-submit", "hide");
			setTimeout(function () {
				toggleVisibility("#btn-calculate", "show");
			}, 500);
		}

		currentRow.find("td:eq(9) input").val(discValueFormat); // format tampilan
	}
	// Yang berfungsi untuk menghitung amount item
	function calculateAmountItem(currentRow, value, discValue) {
		let amount = value - discValue;
		amount = roundToFourDecimals(amount);
		currentRow.find("td:eq(10) input").val(amount); // format tampilan
		//
		// Perbarui nilai di objek selectedItemRows
		let itemCode = currentRow.find("input[name*='Item_Code']").val();
		if (selectedItemRows[itemCode]) {
			selectedItemRows[itemCode].amountItem = amount;
		}
		//
		calculateTotalAmount();
	}
	// Yang berfungsi untuk menghitung total amount summary
	function calculateTotalAmount() {
		let totalAmount = 0;

		$("#table-detail-item tbody tr").each(function () {
			let amount = parseFloat(
				formatAritmatika($(this).find("td:eq(10) input").val())
			);
			if (!isNaN(amount)) {
				totalAmount += amount;
			}
		});
		totalAmount = roundToFourDecimals(totalAmount);
		$("#total-amount").val(totalAmount);
		// format tampilan
	}
	//
	// Yang berfungsi untuk mengambil nilai pajak sesuai dengan yang dipilih
	// function fetchTaxOptions(selectElement, selectedValue) {
	// 	$.ajax({
	// 		type: "GET",
	// 		url:
	// 			$('meta[name="base_url"]').attr("content") +
	// 			"Sales/SalesOrder/DT_listoftax",
	// 		dataType: "json",
	// 		beforeSend: function () {
	// 			Swal.fire({
	// 				title: "Loading....",
	// 				html: '<div class="spinner-border text-primary"></div>',
	// 				showConfirmButton: false,
	// 				allowOutsideClick: false,
	// 				allowEscapeKey: false,
	// 			});
	// 		},
	// 		success: function (response) {
	// 			Swal.close();
	// 			if (response.code === 200) {
	// 				selectElement.empty();
	// 				selectElement.append(
	// 					'<option class="p-1" style="font-size: 0.7rem;" value="0">None</option>'
	// 				);
	// 				$.each(response.data, function (index, tax) {
	// 					let isSelected = tax.Tax_Id == selectedValue ? "selected" : "";
	// 					selectElement.append(
	// 						`<option class="p-1" style="font-size: 0.7rem;" value="${tax.Tax_Id}" ${isSelected}>${tax.Tax_Code}</option>`
	// 					);
	// 				});
	// 			} else {
	// 				Swal.fire({
	// 					icon: "error",
	// 					title: "Oops...",
	// 					text: response.msg,
	// 					footer: '<a href="javascript:void(0)">Notifikasi System</a>',
	// 				});
	// 			}
	// 		},
	// 		error: function () {
	// 			Swal.close();
	// 			Swal.fire({
	// 				icon: "error",
	// 				title: "Oops...",
	// 				text: "Terjadi kesalahan teknis. Mohon coba lagi nanti.",
	// 				footer: '<a href="javascript:void(0)">Notifikasi System</a>',
	// 			});
	// 		},
	// 	});
	// }
	// Yang berfungsi untuk menghitung nilai pajak sesuai dengan yang dipilih
	function calculateTax() {
		let postData = {
			tax1Rate: [],
			tax2Rate: [],
			amount: [],
		};

		// Iterasi setiap baris dalam tabel dengan id #table-detail-item
		$("#table-detail-item tbody tr").each(function () {
			let tax1Rate = formatAritmatika(
				$(this).find(".tax1 option:selected").val()
			);
			let tax2Rate = formatAritmatika(
				$(this).find(".tax2 option:selected").val()
			);
			let amount = formatAritmatika($(this).find(".read-only-amount").val());

			// Mengecek apakah tax1Rate dan tax2Rate dipilih
			if (tax1Rate || tax2Rate) {
				// Menambahkan nilai tax1Rate, tax2Rate, dan amount ke array postData
				postData.tax1Rate.push(tax1Rate);
				postData.tax2Rate.push(tax2Rate);
				postData.amount.push(amount);
			}
		});

		// Mengirim request AJAX untuk menghitung pajak berdasarkan data yang dikirim
		$.ajax({
			type: "POST",
			url:
				$('meta[name="base_url"]').attr("content") +
				"Sales/SalesOrder/calculate_tax",
			data: postData, // Mengirim objek postData
			dataType: "json",
			success: function (response) {
				if (response.code === 200) {
					let total_tax = roundToFourDecimals(response.result);
					$("#total_tax").val(total_tax);
				} else {
					console.error("Error dalam perhitungan pajak:", response.msg);
				}
			},
			error: function (xhr, status, error) {
				console.error("Terjadi error:", error);
			},
		});
	}

	//
	function reset_input() {
		selectedItemRows = {};
		// Reset select inputs in the modal
		$("#select-customer").val("").trigger("change");
		$("#select-category").val("").trigger("change");

		// Clear modal table body
		$("#table-select-item tbody").html("");

		// Reset semua input text
		$("input:text").val("");

		// Reset semua input hidden
		$('input[type="hidden"]').val("");

		// Reset select2 dropdowns
		// $('select[name="vendor"]').select2().val("").trigger("change");
		$('select[name="currency"]').select2().val("IDR").trigger("change");
		$('select[name="unit-top"]').val("").trigger("change");
		$('select[name="dokumen-top"]').val("").trigger("change");
		// Reset specific input fields by ID

		$("#nama-customer").val("");
		$("#alamat-customer").val("");
		$("#nomer-po-customer").val("");
		$("#tanggal-po-customer").val("");
		$("#tanggal-pengiriman").val("");
		$("#term-of-payment").val("");
		$("#unit-top").val("").trigger("change");
		$("#dokumen-top").val("").trigger("change");
		$("#rate-currency").val("1");
		$("#total-amount").val("0.0000");
		$("#discount-percentage").val("0.0000");
		$("#total_tax").val("0.0000");
		$("#keterangan").val("");

		// Clear table rows
		$("#table_item tbody").html("");
		$("#table-detail-item tbody").html("");

		// Hide "no data" message
		$("#no_data_item").show();
	}
	//

	function setFormState(isDisabled, isReadonly) {
		$("#tanggal-so").prop("readonly", isReadonly);
		$("#btn-list-address").prop("disabled", isDisabled);
	}

	function toggleVisibility(selector, action) {
		if (action === "show") {
			$(selector).show("slow");
		} else if (action === "hide") {
			$(selector).hide("slow");
		} else if (action === "toggle") {
			$(selector).toggle("slow");
		} else {
			console.error('Invalid action. Use "show", "hide", or "toggle".');
		}
	}
	//
	function formatAritmatika(str) {
		return str ? str.replace(/,/g, "") : "0";
	}

	$(document)
		.on("focus", ".input-decimal", function () {
			var value = $(this).val();

			// Jika nilai adalah "0.00", kosongkan input
			if (value == "0.0000") {
				$(this).val("");
			} else {
				// Jika nilai mengandung ".00", hapus bagian desimal ".00" saja tanpa mengubah bilangan utamanya
				// Cek apakah nilai memiliki desimal
				if (value.includes(".")) {
					// Hapus angka nol yang tidak diperlukan setelah koma
					let formattedValue = parseFloat(value).toString();
					$(this).val(formatAritmatika(formattedValue));
				}
			}
		})
		.on("blur", ".input-decimal", function () {
			var value = $(this).val();

			// Jika input kosong, setel kembali ke "0.00"
			if (value == "") {
				$(this).val("0.0000");
			} else {
				$(this).val(roundToFourDecimals(formatAritmatika(value))); // Tambahkan ".0000" jika tidak ada desimal
			}
		});

	// $(document)
	// 	.on("focus", ".input-discount", function () {
	// 		var value = $(this).val();

	// 		// Kosongkan input jika nilainya 0
	// 		if (value == "0") {
	// 			$(this).val("");
	// 		}
	// 	})
	// 	.on("blur", ".input-discount", function () {
	// 		var value = $(this).val();

	// 		// Jika input kosong, setel kembali ke "0"
	// 		if (value == "") {
	// 			$(this).val("0");
	// 		} else {
	// 			// Ubah menjadi bilangan bulat dan pastikan tidak melebihi 100
	// 			var discountValue = parseInt(value);
	// 			if (discountValue > 100) {
	// 				$(this).val("100"); // Batasi nilai maksimum menjadi 100
	// 			} else {
	// 				$(this).val(discountValue); // Set nilai yang dimasukkan
	// 			}
	// 		}
	// 	});

	// $(document)
	// 	.on("focus", ".input-integer", function () {
	// 		var value = $(this).val();

	// 		// Jika nilai adalah "0", kosongkan input
	// 		if (value == "0") {
	// 			$(this).val("");
	// 		}
	// 	})
	// 	.on("blur", ".input-integer", function () {
	// 		var value = $(this).val();

	// 		// Jika input kosong, setel kembali ke "0"
	// 		if (value == "") {
	// 			$(this).val("0");
	// 		} else {
	// 			// Jika input berisi angka, pastikan bahwa itu adalah integer
	// 			$(this).val(parseInt(value)); // Ubah menjadi bilangan bulat jika tidak kosong
	// 		}
	// 	});

	// function currencyFormat(num, decimal = 4) {
	// 	return accounting.formatMoney(num, "", decimal, ",", ".");
	// }
});
