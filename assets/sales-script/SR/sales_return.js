$(document).ready(function () {
	// Function ini dipanggil setiap kali user melakukan tindakan seperti ADD dan EDIT data
	let salesReturnData = "";
	let selectedShippingDocuments = {};
	let selectedWarehouses = {};
	let selectedItemRows = {};
	let no_shipping_checked = "";
	let state = "";

	$("#from").datetimepicker({
		format: "YYYY-MM-DD",
	});
	$("#to").datetimepicker({
		format: "YYYY-MM-DD",
	});

	// const Toast = Swal.mixin({
	// 	toast: true,
	// 	position: "top-end",
	// 	width: 600,
	// 	showConfirmButton: false,
	// 	timer: 3000,
	// 	timerProgressBar: true,
	// 	didOpen: (toast) => {
	// 		toast.addEventListener("mouseenter", Swal.stopTimer);
	// 		toast.addEventListener("mouseleave", Swal.resumeTimer);
	// 	},
	// });

	//
	form_state("LOAD");
	//

	// Kembali ke tampilan sebelumnya
	$(document).on("click", "#back", function () {
		form_state("BACK");
	});

	//
	function form_state(state) {
		$(".add-data").hide();
		switch (state) {
			case "LOAD":
				$(".list-data").show("slow");
				state = "";
				$('input[name="state"]').val("");
				reloadData();
				break;
			case "ADD":
				$("#action-tittle").text("ADD");
				reset_input(true);
				$('input[name="state"]').val("ADD");
				state = "ADD";
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
				reset_input();
				state = "EDIT";
				$("#no_data_item").hide();
				$("#action-tittle").text("Edit");
				$('input[name="state"]').val("EDIT");
				$(".list-data").hide("slow");
				$(".add-data").show("slow");
				reloadData();
				break;
			case "BACK":
				$(".list-data").show("slow");
				$(".add-data").hide("slow");
				break;
		}
	}

	function fetchSalesReturnData(selectedShipInstNumbers) {
		return $.ajax({
			url:
				$('meta[name="base_url"]').attr("content") +
				"Sales/SalesReturn/getSalesReturnData", // Endpoint baru untuk mengambil data sales_return
			type: "POST",
			dataType: "json",
			data: {
				selectedShipInstNumbers: JSON.stringify(selectedShipInstNumbers),
			}, // Kirim sebagai objek
			success: function (response) {
				salesReturnData = response.sales_return; // Simpan data sales_return ke variabel global
				console.table(salesReturnData);
			},
			error: function (xhr, status, error) {
				console.error("Error fetching sales return data:", error);
			},
		});
	}

	// Contoh penggunaan:

	// Dev Here
	// Customer to show address and SO multiple
	$("#select-customer").on("change", function () {});

	// Start SJ
	$("#show-list-sj-doc").click(function () {
		Swal.fire({
			title: "Loading...",
			html: '<div class="spinner-border text-primary"></div>',
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false,
		});

		setTimeout(function () {
			Swal.close();
			loadShippingData();
		}, 500); // 500 ms adalah waktu jeda untuk menunggu swal benar-benar tertutup
	});

	$("#do--filter").click(function () {
		loadShippingData();
	});

	function loadShippingData() {
		const selectedValue = $("#select-customer").val();
		const fromDate = $("#from").val();
		const toDate = $("#to").val();

		if (!selectedValue) {
			Swal.fire({
				title: "Pilih Customer!",
				text: "Silakan pilih customer terlebih dahulu.",
				icon: "warning",
				confirmButtonText: "OK",
			});
			return;
		}

		$("#close-btn-modal-table-list-sj-doc").show();
		$("#modal-list-sj-doc").modal("show");

		// Kosongkan tabel sebelum mengisi ulang data
		$("#table-list-sj-doc").empty();
		$("#table-list-sj-doc").append(`
        <thead>
            <tr class='border-0'>
                <th style="width: 3%;" class="text-center border-top-0 border-bottom-0">#</th>
                <th class="text-center border-top-0 border-bottom-0">Nomor SJ</th>
            </tr>
        </thead>
        <tbody></tbody>
    `);

		// Tampilkan loading
		$("#table-list-sj-doc tbody").html(`
        <tr>
            <td colspan="3" class="text-center">
                <i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-2x fa-fw"></i>
                <p style="color:#4a4a4a">Loading data...</p>
            </td>
        </tr>
    `);

		// Jika DataTable sudah ada, hancurkan dulu untuk inisialisasi ulang
		if ($.fn.DataTable.isDataTable("#table-list-sj-doc")) {
			$("#table-list-sj-doc").DataTable().destroy();
		}
		// Inisialisasi ulang DataTable
		$("#table-list-sj-doc").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			ajax: {
				url:
					$('meta[name="base_url"]').attr("content") +
					"Sales/SalesReturn/DT_listdata_shipping",
				data: {
					Account_ID: selectedValue,
					From_Date: fromDate,
					To_Date: toDate,
				},
				type: "POST",
				dataType: "json",
			},
			columns: [
				{
					data: "SysId",
					render: function (data) {
						return `<input type="checkbox" class="check-item" value="${data}">`;
					},
					className: "text-center",
				},
				{
					data: "ShipInst_Number",
					className: "text-center",
				},
				// {
				// 	data: "Account_Name",
				// 	className: "text-center",
				// },
			],
			lengthMenu: [
				[10, 25, 50, 10000],
				[10, 25, 50, "All"],
			],
			language: {
				processing:
					'<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span>',
			},
			preDrawCallback: function () {
				$("#table-list-sj-doc tbody td").addClass("blurry");
			},
			drawCallback: function () {
				// Tambahkan efek blur sementara
				$("#table-list-sj-doc tbody td").addClass("blurry");
				setTimeout(function () {
					$("#table-list-sj-doc tbody td").removeClass("blurry");
				}, 100); // Beri delay untuk memastikan rendering selesai
				// Debugging untuk memastikan fungsi dipanggil
				cheking_list_sj_doc();
			},
		});
	}

	// Event listener untuk checkbox
	$("#table-list-sj-doc").on("change", "input[type='checkbox']", function () {
		let row = $(this).closest("tr");
		let table = $("#table-list-sj-doc").DataTable();
		let rowData = table.row(row).data();

		// Ambil SysId dan ShipInst_Number
		let sysId = rowData.SysId;
		let shipInstNumber = rowData.ShipInst_Number;

		if (this.checked) {
			row.addClass("table-primary text-white");

			// Tambahkan ke selectedShippingDocuments
			selectedShippingDocuments[sysId] = {
				checked: true,
				shipInstNumber: shipInstNumber,
			};
		} else {
			row.removeClass("table-primary text-white");

			// Hapus dari selectedShippingDocuments
			delete selectedShippingDocuments[sysId];
		}

		// Mengelola tombol close
		$("#close-btn-modal-table-list-sj-doc").hide();
	});

	function cheking_list_sj_doc() {
		// Ambil semua nilai checkbox dari table-detail-item
		let checkboxValues = [];
		$("#table-list-sj-doc tbody .btn-delete-item").each(function () {
			checkboxValues.push($(this).val());
		});
		// Iterasi baris pada table-select-item
		$("#table-list-sj-doc tbody tr").each(function () {
			let table = $("#table-list-sj-doc").DataTable();
			let rowData = table.row(this).data();

			if (rowData) {
				let checkbox = $(this).find("input[type='checkbox']");
				let checkboxValue = checkbox.val();
				// Set status checkbox dan tambahkan/hapus kelas berdasarkan selectedShippingDocuments
				if (selectedShippingDocuments[rowData.SysId]) {
					checkbox.prop("checked", true);
					$(this).addClass("table-primary text-white");
				} else {
					checkbox.prop("checked", false);
					$(this).removeClass("table-primary text-white");
				}

				// Hapus baris jika checkboxValue ada di checkboxValues
				if (checkboxValues.includes(checkboxValue)) {
					$(this).remove();
				}
			}
		});
	}

	// Event listener untuk tombol Select
	$("#btn-select-sj-doc").on("click", function () {
		// Panggil fungsi untuk menambahkan shipping documents ke tabel
		addSelectedShippingDocsToTable();
	});

	function addSelectedShippingDocsToTable(edit = false, siNumber = "") {
		let shippingTableBody = $("#shipping-table").find("tbody");
		shippingTableBody.empty();
		// Hitung jumlah baris yang ada untuk menentukan nomor urut berikutnya
		let rowCount = shippingTableBody.find("tr").length + 1;

		Object.keys(selectedShippingDocuments).forEach(function (SysId) {
			let shippingDoc = selectedShippingDocuments[SysId];
			// Cek apakah shipping document sudah ada di tabel
			if (shippingTableBody.find(`tr[data-sysid="${SysId}"]`).length === 0) {
				// Disable tombol delete jika edit mode aktif
				let deleteButtonDisabled = edit
					? "disabled" // Disable button if in edit mode
					: ""; // Enable button if not in edit mode

				// Tambahkan baris baru ke tabel shipping dengan data dari selectedShippingDocuments
				let row = `
                <tr data-sysid="${SysId}">
                    <td class="text-center" style='vertical-align: middle;'>${rowCount}</td>
                    <td class="text-center" style='vertical-align: middle;'>${shippingDoc.shipInstNumber}</td>
                    <td class="text-center" style='vertical-align: middle;'>
                        <button data-sysid="${SysId}" data-si="${shippingDoc.shipInstNumber}" type='button' class='p-0 m-0 btn-delete-shipping btn btn-sm btn-link text-danger text-center' ${deleteButtonDisabled}>
                            <span class='fa fa-times'></span>
                        </button>
                    </td>
                </tr>`;

				// Tambahkan row ke dalam tabel shipping
				shippingTableBody.append(row);
				rowCount++; // Increment nomor urut
			}
		});

		$("#modal-list-sj-doc").modal("hide");

		// Mengambil nomor shipping ke dalam array
		const shippingNumbers = Object.values(selectedShippingDocuments).map(
			(doc) => doc.shipInstNumber
		);

		// Menyembunyikan modal
		$("#modal-list-sj-doc").modal("hide");

		hideBackOutUp("#section-after-chose-si", function () {
			// Hapus item terkait dari selectedItemRows berdasarkan siNumber
			Object.keys(selectedItemRows).forEach(function (ItemSysId) {
				if (selectedItemRows[ItemSysId].siNumber === siNumber) {
					delete selectedItemRows[ItemSysId];
					// Hapus baris dari tabel detail item berdasarkan ItemSysId
					$(
						`#table-detail-item tbody tr[data-item-sysid="${ItemSysId}"]`
					).remove();
				}
			});

			// Cek apakah tabel detail item masih ada baris setelah penghapusan
			let tableDetail = $("#table-detail-item");
			if (tableDetail.find("tbody").children().length > 0) {
				$("#no_data_item").hide("slow"); // Sembunyikan pesan "no data" jika ada baris
			} else {
				$("#no_data_item").show("slow"); // Tampilkan pesan "no data" jika tidak ada baris
			}

			// Setelah elemen disembunyikan, panggil AJAX untuk mengganti konten
			initialFormInput(shippingNumbers); // Mengirim array nomor shipping
		});

		$("#shipping-table").show();
	}

	$(document).on("click", ".btn-delete-shipping", function () {
		// Ambil referensi ke tabel body
		let shippingTableBody = $("#shipping-table").find("tbody");

		// Cek apakah ada lebih dari satu baris
		if (shippingTableBody.children().length <= 1) {
			// Tampilkan peringatan jika hanya ada satu baris
			Swal.fire({
				title: "Tidak bisa menghapus!",
				text: "Minimal harus ada satu data di tabel.",
				icon: "warning",
				confirmButtonText: "OK",
			});
			return; // Hentikan fungsi jika tidak bisa menghapus
		}

		// Konfirmasi sebelum menghapus
		Swal.fire({
			title: "Yakin ingin menghapus?",
			text: "Item terkait dengan pengiriman ini akan dihapus.",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Ya, hapus!",
			cancelButtonText: "Batal",
		}).then((result) => {
			if (result.isConfirmed) {
				// Hapus baris dari tabel
				let row = $(this).closest("tr");
				// Hapus baris dari tabel
				// Ambil SysId dari data atribut tombol
				let sysId = $(this).data("sysid");
				// Ambil siNumber dari atribut data-si pada tombol
				let siNumber = $(this).data("si");

				// Hapus item dari selectedShippingDocuments
				delete selectedShippingDocuments[sysId];

				// Hapus baris dari tabel
				row.remove();

				addSelectedShippingDocsToTable("", siNumber);
				updateRowNumbers();

				// Notifikasi sukses penghapusan
				// Swal.fire({
				// 	title: "Dihapus!",
				// 	text: "Item terkait dengan pengiriman ini telah dihapus.",
				// 	icon: "success",
				// 	confirmButtonText: "OK",
				// });
			}
		});
	});

	function updateRowNumbers() {
		let shippingTableBody = $("#shipping-table").find("tbody");

		shippingTableBody.find("tr").each(function (index) {
			$(this)
				.find("td:first")
				.text(index + 1); // Atur nomor urut berdasarkan index
		});
	}

	// End SJ doc

	// -----------------------------------------------------------------------------------------------

	function initialFormInput(selectedShipInstNumbers, edit = false) {
		// Tampilkan SweetAlert loading
		if (!edit) {
			// Hapus display: none dan tampilkan elemen
			// selectedItemRows = {};
			// $("#table-detail-item tbody").empty();
			// Sembunyikan elemen dengan animasi fadeOut
			$("#section-after-chose-si").fadeOut();
			Swal.fire({
				title: "Loading....",
				html: '<div class="spinner-border text-primary"></div>',
				showConfirmButton: false,
				allowOutsideClick: false,
				allowEscapeKey: false,
				didOpen: () => {
					// Simulasikan proses loading dengan timeout
					setTimeout(() => {
						// Setelah loading selesai, sembunyikan SweetAlert dan tampilkan elemen dengan animasi fadeIn
						Swal.close();
						$("#section-after-chose-si").removeClass("d-none");
						$("#section-after-chose-si").fadeIn();
						addItemToTable();
					}, 2000); // Ganti 2000 dengan waktu loading yang sebenarnya
				},
			});
		}

		$("#show-list-item").click(function (e) {
			$("#close-btn-modal-table-select-item").show();
			// e.preventDefault();
			$("#modal-list-item").modal("show");
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
					<th class="text-center">SJ Number</th>
					<th class="text-center">SO Number</th>
					<th class="text-center">Item Code</th>
					<th class="text-center">Item Name</th>
					<th class="text-center">Color</th>
					<th class="text-center">Model</th>
					<th class="text-center">Brand</th>
					<th class="text-center">Dimensions</th>
					<th class="text-center" style="display: none;"></th>
					<th class="text-center" style="display: none;"></th>
					<th class="text-center" style="display: none;"></th>
				</tr>
			</thead>
			<tbody></tbody>
		 `);

			// Inisialisasi ulang DataTable
			//
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
						selectedShipInstNumbers: JSON.stringify(selectedShipInstNumbers),
					},
					url:
						$('meta[name="base_url"]').attr("content") +
						"Sales/SalesReturn/DT_modallistofitem", // Gantilah URL dengan yang sesuai di projectmu
					type: "POST",
					dataType: "json",
				},
				columns: [
					{
						data: "ItemSysId",
						render: function (data, type, row, meta) {
							return `<input type="checkbox" class="check-item" value="${data}">`;
						},
						className: "text-center",
					},
					{
						data: "ShipInst_Number",
						className: "text-center",
					},
					{
						data: "SO_Number",
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
					// {
					// 	data: "Dimension_Info",
					// 	className: "text-center",
					// },
					{
						// Kolom untuk menyimpan Uom (hidden)
						data: "Uom",
						className: "text-center",
						visible: false, // Kolom ini disembunyikan
						searchable: false, // Kolom ini tidak ikut dalam pencarian
					},
					{
						// Kolom untuk menyimpan Uom (hidden)
						data: "Currency_Symbol",
						className: "text-center",
						visible: false, // Kolom ini disembunyikan
						searchable: false, // Kolom ini tidak ikut dalam pencarian
					},
					{
						// Kolom untuk menyimpan Uom (hidden)
						data: "Item_Price",
						className: "text-center",
						visible: false, // Kolom ini disembunyikan
						searchable: false, // Kolom ini tidak ikut dalam pencarian
					},
				],
				lengthMenu: [
					[10, 25, 50, 10000],
					[10, 25, 50, "All"],
				],
				drawCallback: function () {
					cheking_list_item();
					// Ini adalah callback function yang bisa kamu ganti dengan kebutuhanmu
				},
			});
		});
		fetchSalesReturnData(selectedShipInstNumbers);
	}

	// -----------------------------------------------------------------------------------------------

	// Start Detail Item

	function Init_Edit(sys_id) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url:
				$('meta[name="base_url"]').attr("content") + "Sales/SalesReturn/edit",
			data: {
				sys_id: sys_id,
			},
			success: function (response) {
				Swal.close();
				if (response.code == 200) {
					form_state("EDIT");
					let data_hdr = response.data_hdr;
					let data_dtl = response.data_dtl;
					// Isi data header hedden
					$("#sr-sysId").val(data_hdr.SysId);
					// Isi data header ke input form
					$("#nomer-sr").val(data_hdr.SR_Number);
					$("#SR-Date").val(
						moment(data_hdr.SalesReturnDate).format("DD MMMM YYYY")
					);
					$("#select-customer").val(data_hdr.Account_ID).trigger("change");
					// Set select-customer as disabled (read-only)
					$("#select-customer").prop("disabled", true);
					//
					// Misalkan ini adalah data si_numbers yang diterima dari response atau proses sebelumnya
					let si_numbers = response.si_numbers; // Array dari si_numbers yang diterima

					// Set sysId menjadi 0 seperti yang Anda inginkan -- dummy
					let sysId = 0;

					// Loop melalui setiap SI_Number dan simpan ke dalam selectedShippingDocuments
					si_numbers.forEach(function (shipInstNumber) {
						selectedShippingDocuments[sysId] = {
							checked: true,
							shipInstNumber: shipInstNumber,
						};

						// Jika Anda ingin menyimpan lebih dari satu, Anda bisa mengubah key dari selectedShippingDocuments
						// berdasarkan index atau lainnya agar tidak tertimpa
						sysId++; // Incremen sysId jika ingin berbeda setiap kali, atau tetap gunakan 0 untuk key yang sama
					});
					$("#btn-add-multiple-sj-doc").hide();
					addSelectedShippingDocsToTable(true, "");
					updateRowNumbers();
					// initialFormInput(no_shipping_checked, true);
					initialFormInput(si_numbers, true);
					$("#section-after-chose-si").removeClass("d-none").show();

					$.each(data_dtl, function (index, item) {
						// Ambil nilai ItemSysId dari responseData
						let ItemSysId = item.ItemSysId;

						// Masukkan item detail ke selectedItemRows
						// Masukkan data hasil response ke dalam selectedItemRows
						selectedItemRows[ItemSysId] = {
							checked: true, // Asumsikan data yang diterima pasti dicentang
							// ItemSysId: item.SysId || "", // Gunakan nilai dari response, jika tidak ada kosongkan
							ItemSysId: item.ItemSysId || "", // Gunakan nilai dari response, jika tidak ada kosongkan
							siNumber: item.SI_Number || "", // Gunakan nilai dari response, jika tidak ada kosongkan
							soNumber: item.SO_Number || "", // Gunakan nilai dari response, jika tidak ada kosongkan
							itemCode: item.Item_Code || "", // Gunakan nilai dari response, jika tidak ada kosongkan
							itemName: item.Item_Name || "", // Gunakan nilai dari response, jika tidak ada kosongkan
							itemColor: item.Item_Color || "-", // Jika tidak ada, default menjadi '-'
							model: item.Model || "-", // Jika tidak ada, default menjadi '-'
							brand: item.Brand || "-", // Jika tidak ada, default menjadi '-'
							dimensionInfo: item.Dimension_Info || "-", // Jika tidak ada, default menjadi '-'
							weightInfo: item.Weight_Info || "-", // Jika tidak ada, default menjadi '-'
							uom: item.Uom || "pcs", // Jika tidak ada, default menjadi 'pcs'
							qty: item.Qty || "0.0000", // Jika tidak ada, default quantity menjadi 0
							Currency_Symbol: item.Currency_Symbol,
							Item_Price: item.Item_Price,
							Qty_Info: item.Qty_Info || "0.0000",
							warehouse_id: item.warehouse_id || "", // Tambahkan warehouse_id jika ada
						};
					});
					// Panggil fungsi untuk menambahkan item ke tabel

					addItemToTable();
				} else {
					Swal.fire({
						icon: "error",
						title: "Oops...",
						text: response.msg,
						confirmButtonColor: "#3085d6",
						confirmButtonText: "Ya, Confirm!",
						footer: '<a href="javascript:void(0)">Notifikasi System</a>',
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

	$("#table-select-item").on("change", "input[type='checkbox']", function () {
		// Ambil baris terkait dari checkbox yang dipilih
		let row = $(this).closest("tr");
		let table = $("#table-select-item").DataTable();
		let rowData = table.row(row).data();

		// Ambil nilai dari kolom-kolom di row yang terpilih
		let ItemSysId = rowData.ItemSysId;
		// Jika checkbox dicentang, tambahkan data ke selectedItemRows
		if (this.checked) {
			row.addClass("table-primary text-white"); // Tambahkan kelas CSS untuk highlight baris

			// Menyimpan data dari item yang dipilih
			selectedItemRows[ItemSysId] = {
				checked: $(this).prop("checked"),
				ItemSysId: ItemSysId,
				siNumber: rowData.ShipInst_Number,
				soNumber: rowData.SO_Number,
				itemCode: rowData.Item_Code,
				itemName: rowData.Item_Name,
				itemColor: rowData.Item_Color,
				model: rowData.Model,
				brand: rowData.Brand,
				dimensionInfo: rowData.Dimension_Info,
				weightInfo: rowData.Weight_Info,
				uom: rowData.Uom,
				Currency_Symbol: rowData.Currency_Symbol,
				Item_Price: rowData.Item_Price,
				Qty_Info: rowData.Qty_Info,
				qty: "0.0000", // Default quantity set to 0
			};
		} else {
			// Jika checkbox tidak dicentang, hapus data dari selectedItemRows
			row.removeClass("table-primary text-white");

			// Hapus item dari selectedItemRows berdasarkan itemCode
			delete selectedItemRows[ItemSysId];
		}

		// Sembunyikan tombol close (atau kamu bisa mengganti ini sesuai kebutuhan)
		$("#close-btn-modal-table-select-item").hide();
	});

	$("#btn-select-item").on("click", function () {
		// Ambil referensi tabel detail item

		// Panggil fungsi untuk menambahkan item ke tabel
		addItemToTable();
		// Mengisi dropdown warehouse
		// let warehouseSelect = tableDetail
		// 	.find('select[name="Warehouse_Selection[]"]')
		// 	.last();

		// populateWarehouseSelect(warehouseSelect, warehouseOptions, item.ItemSysId);
		// Setelah data ditambahkan ke tabel detail, kamu bisa menutup modal atau memberikan notifikasi
		$("#modal-list-item").modal("hide");
		$("#no_data_item").hide("slow");
	});

	function addItemToTable() {
		console.table(selectedItemRows);

		let tableDetail = $("#table-detail-item").find("tbody");
		Object.keys(selectedItemRows).forEach(function (ItemSysId) {
			let item = selectedItemRows[ItemSysId];
			// Cek apakah item sudah ada di tabel detail
			if (
				tableDetail.find(`tr[data-item-sysId="${item.ItemSysId}"]`).length === 0
			) {
				// console.table(item);
				// Tambahkan baris baru ke tabel detail item dengan data dari selectedItemRows
				let row = `
                <tr data-item-sysId="${item.ItemSysId}">
				  <td class="text-center" style='vertical-align: middle;'>
                        ${item.siNumber}
                        <input type="hidden" name="siNumber[]" value="${
													item.siNumber
												}">
                    </td>
                    <td class="text-center" style='vertical-align: middle;'>
                        ${item.soNumber}
                        <input type="hidden" name="soNumber[]" value="${
													item.soNumber
												}">
                    </td>
                    <td class="text-center" style='vertical-align: middle;'>
                        ${item.itemCode}
                        <input type="hidden" name="itemCode[]" value="${
													item.itemCode
												}">
                    </td>
                    <td class="text-center" style='vertical-align: middle;'>
                        ${item.itemName}
                        <input type="hidden" name="itemName[]" value="${
													item.itemName
												}">
                    </td>
                    <td class="text-center" style='vertical-align: middle;'>
                        ${item.itemColor}
                    </td>
                    <td class="text-center" style='vertical-align: middle;'>
                        ${item.brand}
                    </td>
                    <td class="text-center text-uppercase" style='vertical-align: middle;'>
                        ${item.uom} <!-- Kolom UOM tanpa input hidden -->
                    </td>
   					<td class="text-center text-uppercase" style='vertical-align: middle;'>
                        ${
													item.Currency_Symbol +
													". " +
													formatIdrAccounting(item.Item_Price)
												} <!-- Kolom UOM tanpa input hidden -->
                    </td>
					  <td class="text-center text-uppercase" style='vertical-align: middle;'>
                    ${item.Qty_Info} <!-- Kolom Qty Info -->
					</td>
					<td class="" style='vertical-align: middle;'>
						<input type="text" name="qty[]" value="${item.qty}" 
							class="only-number input-decimal form-control form-control-sm text-center qty-input"
							data-qty="${item.qty}" data-qty-info="${item.Qty_Info}" data-item-sysid="${
					item.ItemSysId
				}">
					</td>
                    <td class="" style='vertical-align: middle;'>
                        <select class="form-control form-control-sm text-center" name="Warehouse_Selection[]">
                            <option value="" disabled selected>---Pilih Warehouse---</option>
                        </select>
                    </td>
                    <td class="text-center" style='vertical-align: middle;'>
                        <button data-dtl-shp="${item.ItemSysId}" value="${
					item.ItemSysId
				}" type='button' class='btn-delete-item btn btn-sm btn-link text-danger text-center'>
                            <span class='fa fa-times'></span>
                        </button>
                    </td>
                </tr>`;

				// Tambahkan row ke dalam tabel detail
				tableDetail.append(row);

				// Mengisi dropdown warehouse
				let warehouseSelect = tableDetail
					.find('select[name="Warehouse_Selection[]"]')
					.last();

				populateWarehouseSelect(
					warehouseSelect,
					warehouseOptions,
					item.ItemSysId,
					item.warehouse_id // Pilih warehouse berdasarkan warehouse_id dari item
				);
			}
		});

		// Event listener untuk validasi qty dev
		$(".qty-input").on("input", function () {
			let inputQty = parseFloat($(this).val());
			let soNumber = $(this)
				.closest("tr")
				.find("input[name='soNumber[]']")
				.val();
			let itemCode = $(this)
				.closest("tr")
				.find("input[name='itemCode[]']")
				.val();
			let qtyShipped = parseFloat($(this).data("qty-info")); // Mengambil Qty Shipped dari data-qty-info
			//untuk edit
			let qty = parseFloat($(this).data("qty"));

			// Ambil UOM dari kolom tersembunyi di tabel
			let uom = $(this)
				.closest("tr")
				.find("td:eq(6)") // Kolom UOM berada pada urutan ke-8 (dimulai dari 0)
				.text();

			// alert(uom);
			// Mencari data yang sesuai di salesReturnData berdasarkan SO_Number dan Item_Code
			let matchedData = salesReturnData.filter(
				(item) => item.SO_Number === soNumber && item.Item_Code === itemCode
			);
			// console.table(matchedData);
			// Mengelompokkan berdasarkan status approval
			let approvedQty = 0;
			let pendingQty = 0;

			matchedData.forEach((item) => {
				if (item.Approve == 1) {
					approvedQty += parseFloat(item.Qty); // Total Qty yang sudah di-approve
				} else if (item.Approve == 0) {
					pendingQty += parseFloat(item.Qty); // Total Qty yang menunggu approval
				}
			});

			// Total Sales Return: approved + pending
			let totalSalesReturn = approvedQty + pendingQty;

			// Hitung total available qty yang bisa di-return
			let totalAvailableQty = qtyShipped - totalSalesReturn;
			state = $("#state").val();
			if (state == "EDIT") {
				totalAvailableQty += qty;
			}

			// Membuat pesan dinamis
			let message = `Jumlah yang diinput (${inputQty} ${uom}) melebihi batas. Item ini memiliki Sales Return sebanyak ${totalSalesReturn} ${uom}, dengan ${approvedQty} ${uom} yang sudah di-approve dan ${pendingQty} ${uom} masih menunggu approval,`;

			// Hanya tambahkan bagian ini jika ada pendingQty
			if (qty > 0) {
				message += ` termasuk dalam item ini.`;
			}

			message += ` Dari total QTY yang dikirim sebanyak ${qtyShipped} ${uom}, jadi sisa QTY yang dapat di-return adalah ${totalAvailableQty} ${uom}.`;

			if (inputQty > totalAvailableQty) {
				Swal.fire({
					icon: "warning",
					title: "Melebihi Batas Qty",
					text: message,
					confirmButtonColor: "#3085d6",
					confirmButtonText: "Oke",
				});
				// Reset ke nilai maksimal yang diperbolehkan
				$(this).val(totalAvailableQty);
			}
		});
	}

	function cheking_list_item() {
		// Ambil semua nilai checkbox dari table-detail-item
		$("#table-select-item tbody tr").each(function () {
			let table = $("#table-select-item").DataTable();
			let rowData = table.row(this).data();

			if (rowData) {
				let ItemSysId = rowData.ItemSysId;
				let checkbox = $(this).find("input[type='checkbox']");

				// Set status checkbox dan tambahkan/hapus kelas berdasarkan selectedItemRows
				if (selectedItemRows[ItemSysId]) {
					checkbox.prop("checked", selectedItemRows[ItemSysId].checked);
					if (selectedItemRows[ItemSysId].checked) {
						$(this).addClass("table-primary text-white");
					} else {
						$(this).removeClass("table-primary text-white");
					}
				} else {
					$(this).removeClass("table-primary text-white");
				}
			}
		});
	}

	function populateWarehouseSelect(
		selectElement,
		warehouseOptions,
		itemSysId,
		selectedWarehouseId = null
	) {
		// Hapus semua opsi yang ada sebelumnya
		selectElement.empty();

		// Tambahkan opsi placeholder default
		selectElement.append(
			'<option value="" disabled selected>---Pilih Warehouse---</option>'
		);

		// Looping melalui data warehouse dan tambahkan ke dropdown
		for (let warehouseID in warehouseOptions) {
			let option = $("<option></option>")
				.val(warehouseID)
				.text(warehouseOptions[warehouseID]);

			// Jika ada `selectedWarehouseId` dan cocok dengan `warehouseID`, tandai sebagai terpilih
			if (selectedWarehouseId && warehouseID == selectedWarehouseId) {
				option.attr("selected", "selected");
			}

			selectElement.append(option);
		}

		// Event listener untuk menyimpan pilihan warehouse
		selectElement.on("change", function () {
			let selectedWarehouse = $(this).val();
			selectedWarehouses[itemSysId] = selectedWarehouse;
		});
	}

	$(document).on("click", ".btn-delete-item", function () {
		// Hapus baris dari tabel
		let row = $(this).closest("tr");
		// Ambil itemCode dari kolom pertama dalam baris
		let dtlShpSysId = $(this).data("dtl-shp");

		// Hapus item dari selectedItemRows
		delete selectedItemRows[dtlShpSysId];
		row.remove();
		//
		let tableDetail = $("#table-detail-item");
		if (tableDetail.find("tbody").children().length > 0) {
			$("#no_data_item").hide("slow");
		} else {
			$("#no_data_item").show("slow");
		}
	});

	// End Detail Item

	// start form

	$("#main-form").on("submit", function (event) {
		event.preventDefault();

		// let tests = true;
		if (validateForm()) {
			let formData = $(this).serialize();
			$.ajax({
				url:
					$('meta[name="base_url"]').attr("content") +
					"Sales/SalesReturn/store",
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
					//
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

	function validateForm() {
		var isValid = true;
		var errorMessage = " harus diisi.";

		function showErrorMessage(element, message) {
			var errorElement = $(
				'<div class="invalid-feedback text-start"></div>'
			).text(message);
			$(element).addClass("is-invalid");
			$(element).parent().append(errorElement);
		}

		function clearErrorMessages() {
			$(".invalid-feedback").remove();
			$(".form-control").removeClass("is-invalid");
		}

		clearErrorMessages();

		// Validate Tanggal Sales Return
		var srDate = $("#SR-Date");
		if (srDate.val().trim() === "") {
			isValid = false;
			showErrorMessage(srDate, "Tanggal Sales Return" + errorMessage);
		}

		// Validate Nama Customer
		var namaCustomer = $("#select-customer");
		if (namaCustomer.val().trim() === "") {
			isValid = false;
			showErrorMessage(namaCustomer, "Nama Customer" + errorMessage);
		}

		// // Validate Keterangan
		// var keterangan = $("#keterangan");
		// if (keterangan.val().trim() === "") {
		// 	isValid = false;
		// 	showErrorMessage(keterangan, "Keterangan" + errorMessage);
		// }

		// Validate detail items: qty dan warehouse
		$("#table-detail-item tbody tr").each(function () {
			var qtyInput = $(this).find("input[name='qty[]']");
			var warehouseSelect = $(this).find(
				"select[name='Warehouse_Selection[]']"
			);

			// Validasi qty
			if (qtyInput.val().trim() === "" || parseFloat(qtyInput.val()) <= 0) {
				isValid = false;
				showErrorMessage(qtyInput, "Qty" + errorMessage);
			}

			// Validasi warehouse
			if (
				warehouseSelect.val() === null ||
				warehouseSelect.val().trim() === ""
			) {
				isValid = false;
				showErrorMessage(warehouseSelect, "Warehouse" + errorMessage);
			}
		});

		// Periksa apakah ada baris item di tabel detail
		var rowCount = $("#table-detail-item tbody tr").length;
		if (rowCount === 0) {
			isValid = false;
			Swal.fire({
				icon: "error",
				title: "Oops...",
				text: "Pilih minimal 1 item!",
				confirmButtonColor: "#3085d6",
				confirmButtonText: "Tutup",
			});
		}

		if (!isValid) {
			Swal.fire({
				icon: "error",
				title: "Oops...",
				text: "Lengkapi semua data dan pastikan qty dan warehouse diisi!",
				confirmButtonColor: "#3085d6",
				confirmButtonText: "Tutup",
			}).then(function () {
				$("html, body").animate(
					{
						scrollTop: $(".is-invalid").first().offset().top - 100,
					},
					500
				);
			});
		}

		return isValid;
	}

	function reset_input() {
		// Mengosongkan selectedWarehouses, selectedItemRows, dan no_shipping_checked
		selectedWarehouses = {};
		selectedItemRows = {};
		selectedShippingDocuments = {};
		no_shipping_checked = "";
		// Tambahkan kembali kelas display: none (d-none) ke elemen
		$("#section-after-chose-si").addClass("d-none");
		// Reset field berdasarkan ID satu per satu
		$("#nomer-sr").val(""); // Reset nomor sales return
		$("#SR-Date").val(""); // Reset tanggal sales return
		$("#select-customer").val("").trigger("change"); // Reset dropdown customer

		// Aktifkan kembali select-customer jika dinonaktifkan
		$("#select-customer").prop("disabled", false);
		$("#btn-add-multiple-sj-doc").show();
		$("#note").val(""); // Reset textarea keterangan

		// Clear table rows satu per satu
		$("#shipping-table tbody").html(""); // Kosongkan table shipping
		$("#table-detail-item tbody").html(""); // Kosongkan table detail item

		// Tampilkan kembali pesan "Tidak Ada Data"
		$("#no_data_item").show();

		// Jika ada field lain, reset satu per satu sesuai ID-nya
	}

	// end form

	// start table show data

	function reloadData() {
		let selectedSlsReturn = {}; // Object to store selected Shipping Orders
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
					"Sales/SalesReturn/DT_listdata",
				dataType: "json",
				type: "POST",
			},
			columns: [
				{
					data: "SR_Number",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "SR_Date",
					render: function (data) {
						return data.substring(0, 10);
					},
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Account_Name",
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
					},
				},
				// {
				// 	data: "SI_Number",
				// 	createdCell: function (td) {
				// 		$(td).addClass("text-center align-middle");
				// 	},
				// },
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
						$(td).addClass("text-center align-middle");
					},
				},
				{
					data: "Is_Cancel",
					render: function (data) {
						return data == 1
							? '<div class="d-flex justify-content-center"><span class="badge bg-success">Canceled</span></div>'
							: '<div class="d-flex justify-content-center"><span class="badge bg-warning">Open</span></div>';
					},
					createdCell: function (td) {
						$(td).addClass("text-center align-middle");
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
					'<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" class="loading-text"></span> ',
			},
			initComplete: function () {
				$("#DataTable tbody").on("click", "tr", function () {
					const rowData = dataTable.row(this).data();

					if (rowData && rowData.SysId) {
						const sysId = rowData.SysId;

						if ($(this).hasClass("table-primary")) {
							$(this).removeClass("table-primary text-white");
							delete selectedSlsReturn[sysId];
						} else {
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

							selectedSlsReturn = {};

							if ($(this).hasClass("table-warning")) {
								$(this)
									.removeClass("table-warning")
									.addClass("table-primary text-white table-warning-selected");
							} else {
								$(this).addClass("table-primary text-white");
							}

							selectedSlsReturn[sysId] = rowData;
						}
					} else {
						console.error("Row data or SysId is undefined", rowData);
					}
				});

				// Event handler for buttons with data-sysid
			},
			drawCallback: function () {
				// Kosongkan selectedSlsReturn yang tidak sesuai dengan hasil pencarian
				$("#DataTable tbody tr").each(function () {
					const rowData = dataTable.row(this).data();

					// Log row data on draw
					if (rowData && selectedSlsReturn[rowData.SysId]) {
						// Jika SysId ditemukan dalam hasil pencarian, tetap simpan di selectedSlsReturn
						if (selectedSlsReturn[rowData.SysId]) {
							$(this)
								.removeClass("table-warning")
								.addClass("table-primary text-white table-warning-selected");
						}
					} else {
						// Hapus SysId yang tidak ada dalam hasil pencarian
						for (const sysId in selectedSlsReturn) {
							if (selectedSlsReturn.hasOwnProperty(sysId) && !rowData) {
								delete selectedSlsReturn[sysId];
							}
						}
					}

					// Terapkan background kuning untuk baris di mana Is_Cancel == 1
					if (rowData && rowData.Is_Cancel == 1) {
						$(this).addClass("table-warning");
					}
				});

				// Add blur effect and remove it after a short delay
				$("#DataTable tbody td").addClass("blurry");
				setTimeout(function () {
					$("#DataTable tbody td").removeClass("blurry");
				}, 100); // Tambahkan delay untuk memastikan rendering selesai

				// Initialize tooltips
				$('[data-toggle="tooltip"]').tooltip();
			},
			buttons: [
				{
					text: `<i class="fas fa-plus fs-3"></i> ADD Sales Return`,
					className: "bg-primary",
					action: function () {
						form_state("ADD");
					},
				},
				{
					text: `<i class="fas fa-search"></i> View Detail`,
					className: "btn btn-info",
					action: function () {
						if (Object.keys(selectedSlsReturn).length === 0) {
							Swal.fire({
								icon: "warning",
								title: "Ooppss...",
								text: "Silahkan pilih data untuk melihat detail!",
								footer:
									'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
							});
						} else {
							let selectedRow = Object.values(selectedSlsReturn)[0];
							let sysId = selectedRow.SysId;
							let url =
								$('meta[name="base_url"]').attr("content") +
								"Sales/SalesReturn/detail/" +
								sysId;
							window.location.href = url;
						}
					},
				},
				{
					text: `<i class="fas fa-edit fs-3"></i> Edit`,
					className: "btn btn-warning",
					action: function () {
						if (Object.keys(selectedSlsReturn).length === 0) {
							Swal.fire({
								icon: "warning",
								title: "Ooppss...",
								text: "Silahkan pilih data untuk melihat detail!",
								footer:
									'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
							});
						} else {
							let selectedRow = Object.values(selectedSlsReturn)[0];
							let sysId = selectedRow.SysId;
							let Approve = selectedRow.Approve;
							let isCancel = selectedRow.Is_Cancel;
							let message =
								Approve == 1
									? "Data sudah diapprove"
									: Approve == 2
									? "Data sudah diriject"
									: isCancel == 1
									? "Data sudah dicancel"
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
							Init_Edit(sysId);
						}
					},
				},
				{
					text: `<i class="fa fa-times fs-3"></i> <small><i>Cancel</i></small>`,
					className: "btn btn-dark",
					action: function () {
						if (Object.keys(selectedSlsReturn).length === 0) {
							Swal.fire({
								icon: "warning",
								title: "Ooppss...",
								text: "Silahkan pilih data untuk merubah status!",
								footer:
									'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
							});
						} else {
							let selectedRow = Object.values(selectedSlsReturn)[0];
							let sysId = selectedRow.SysId;
							// let Approve = selectedRow.Approve;
							let isCancel = selectedRow.Is_Cancel;

							if (isCancel == 1) {
								Swal.fire({
									icon: "info",
									title: "Informasi",
									text: "Data sudah di cancel.",
									footer:
										'<a href="javascript:void(0)" class="text-info">Informasi System</a>',
								});
								return;
							}
							showCancelReasonModal(sysId);
						}
					},
				},
			],
		});

		dataTable
			.buttons()
			.container()
			.appendTo("#DataTable_wrapper .col-md-6:eq(0)");
	}

	function showCancelReasonModal(sysId) {
		Swal.fire({
			title: "Alasan Cancel:",
			input: "textarea",
			// inputLabel: "Masukkan alasan pembatalan:",
			// text: "Alasan Pembatalan:",
			inputPlaceholder: "Masukkan alasan cancel shipping",
			showCancelButton: true,
			confirmButtonText: "Submit",
			preConfirm: (reason) => {
				if (!reason) {
					Swal.showValidationMessage("Reason is required");
				} else {
					return reason;
				}
			},
		}).then((result) => {
			if (result.isConfirmed) {
				Fn_Cancel_Status(sysId, result.value);
			}
		});
	}

	function Fn_Cancel_Status(sys_id, reason) {
		Swal.fire({
			title: "System message!",
			text: `Apakah Anda yakin untuk membatalkan item ini?`,
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, batalkan!",
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url:
						$('meta[name="base_url"]').attr("content") +
						"Sales/SalesReturn/cancel_status",
					type: "post",
					dataType: "json",
					data: {
						sys_id: sys_id,
						reason: reason,
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

							// Reload DataTable and apply table-warning to the canceled row
							const dataTable = $("#DataTable").DataTable();
							dataTable.ajax.reload(function () {
								// Apply warning class to the canceled row
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

	// end table show data

	// other

	function hideBackOutUp(element, callback) {
		$(element)
			.removeClass("animate__backInDown")
			.addClass("animate__backOutUp")
			.delay(600)
			.hide(0, callback);
	}

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
});
