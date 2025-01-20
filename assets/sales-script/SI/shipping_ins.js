// Function ini dipanggil setiap kali user melakukan tindakan seperti ADD dan EDIT data
// Buat elemen span untuk setiap nomor SO
let selectedSysIds = new Set(); // Set untuk menyimpan SysId yang dipilih
let deletedSysIds = new Set(); // Set untuk menyimpan SysId yang dihapus
let qtyShippedValues = {};
let freeValues = {};
let continueState = false;
//

// Setelah flatpickr dihapus, tambahkan atribut readonly

const Toast = Swal.mixin({
	toast: true,
	position: "top-end",
	width: 600,
	showConfirmButton: false,
	timer: 3000,
	timerProgressBar: true,
	didOpen: (toast) => {
		toast.addEventListener("mouseenter", Swal.stopTimer);
		toast.addEventListener("mouseleave", Swal.resumeTimer);
	},
});

//
form_state("LOAD");
//
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
//
//
// xx
$(document).on("click", ".btn-add-wh", function () {
	//
	let itemCode = $(this).data("item-code");
	//
	let itemName = $(this).data("item-name");
	let note = $(this).data("note");
	let color = $(this).data("color");
	let brand = $(this).data("brand");
	let dimension = $(this).data("dimension");
	let weight = $(this).data("weight");
	//
	let hdr_so = $(this).data("hdr-so");
	let dtl_so = $(this).data("dtl-so");
	let data_qty_order = $(this).data("qty-order");
	let data_qty_ost = $(this).data("qty-ost");
	// alert(data_qty_order);
	// Set nilai-nilai ke dalam tabel detail item
	$("#dtl-item-code").text(itemCode);
	$("#dtl-item-name").text(itemName);
	$("#dtl-note").text(note);
	$("#dtl-color").text(color);
	$("#dtl-brand").text(brand);
	$("#dtl-dimension").text(dimension);
	$("#dtl-weight").text(weight);
	//
	$("#hdr-so-sysId").val(hdr_so);
	$("#dtl-so-sysId").val(dtl_so);
	$("#qty-order-validate").text(parseFloat(data_qty_order));
	$("#qty-ost-validate").text(parseFloat(data_qty_ost));
	// // Tampilkan nilai-nilai di dalam tabel detail item
	$("#list-stock-item-modal").modal("show");
	//
	Initialize_DataTable_Stok(itemCode, dtl_so);
	//
});
//
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
			reset_input(true);
			cekArea();
			appendBadgeMultipleSO("Pilih Nama Customer", true);
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

function appendBadgeMultipleSO(message, disabled = false) {
	// Kosongkan dulu elemen untuk menghindari duplikasi
	$("#info-multiple-so").empty();

	$("#info-multiple-so").show();
	// Membuat badge dengan pesan dinamis dan menambahkannya ke elemen dengan ID 'list-multiple-so'
	$("#info-multiple-so").append(`
        <span class="badge badge-info" id="no_data_item" ${
					disabled ? 'style="pointer-events: none;"' : ""
				}>
            ${message}
        </span>
    `);
}

// Contoh penggunaan:

function reset_input(all = false) {
	// Mengosongkan selectedWarehouses
	selectedWarehouses.length = 0; // Jika array
	qtyShippedValues = {};
	deletedSysIds.clear();
	// Reset semua input text dan textarea
	$("#main-form input:text").val("");
	$("#main-form textarea").val("");

	// Reset semua input hidden
	$("#main-form input[type='hidden']").val("");

	// Reset select2 dropdowns
	if (all) {
		// Set select dropdown to "mobil" for sailing
		$("#main-form select").val("").trigger("change");
		$("#sailing").val("mobil").trigger("change");
	}

	// Reset specific input fields by ID
	$("#nomer-shipping").val("");
	$("#tanggal-shipping").val("");
	$("#alamat-customer").val("");
	$("#tanggal-pengiriman").val("");
	$("#port-of-loading").val("");
	$("#place-of-delivery").val("");
	$("#carrier").val("");
	$("#NotifeParty").val("");
	$("#NotifePartyAddress").val("");
	$("#ShippingMarks").val("");
	$("#LCNo").val("");
	$("#LCDate").val("");
	$("#LCBank").val("");

	// Clear table rows
	$("#detail-table tbody").html("");

	// Hide "no data" message if any
	$("#no_data_item").show();

	// Update label to "Nomor"

	// Reset sailing select to default value
}

// Customer to show address and SO multiple
$("#select-customer").on("change", function () {
	// Get selected value
	let selectedValue = $(this).val();
	// Clear existing content
	$("#list-multiple-so").empty();
	selectedSysIds.clear();
	appendBadgeMultipleSO("Pilih nama Customer", true);
	// If the selected value is empty, exit the function and don't run AJAX
	if (!selectedValue) {
		return;
	}
	// Panggilan AJAX pertama untuk mengambil data akun dan header SO
	$.ajax({
		type: "POST",
		url:
			$('meta[name="base_url"]').attr("content") +
			"Sales/ShippingIns/DT_listdata_SO",
		data: {
			account_id: selectedValue,
		},
		dataType: "json",
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
			// $("#detail-table tbody").empty();
			Swal.close();
			// Cek jika responsenya bernilai false atau 0
			// Cek jika response.sales_orders adalah objek atau array kosong
			if (
				!response.sales_orders ||
				Object.keys(response.sales_orders).length === 0
			) {
				// Tangani respons yang bernilai false atau kosong
				Swal.fire({
					icon: "error",
					title: "Opsss!",
					text: "Customer yang dipilih harus sudah mempunyai Sales Order yang telah terverifikasi dan tidak close.",
				});
				appendBadgeMultipleSO("No Data", true);
				return; // Hentikan eksekusi jika respons tidak valid
			}

			//
			let account = response.account;
			//
			$("#customer-id").val(account.SysId);
			$("#customer-code").val(account.Account_Code);
			$("#account-npwp").val(account.TaxFileNumber);
			// alert(account.Account_Code);
			let soOrders = response.sales_orders;
			// Menambahkan badge untuk setiap SO Order
			// Membuat Set untuk menyimpan SysId yang sudah diproses
			let processedSysIds = new Set();
			let hasOutstandingItems = false;
			//
			let processedIndex = 0; // Variabel untuk menghitung index item yang memenuhi syarat
			//
			$.each(soOrders, function (index, so) {
				// Mengecek jika Qty_ost_so lebih dari 0
				// alert(so.Qty_ost_so);
				if (so.Qty_ost_so > 0) {
					// Pengecekan kedua untuk memeriksa apakah SysId sudah diproses
					if (!processedSysIds.has(so.SysId)) {
						// Menambahkan SysId ke dalam Set setelah diproses
						processedSysIds.add(so.SysId);
						//
						processedIndex++;
						//
						hasOutstandingItems = true; // Ada item outstanding
						let badgeClass = selectedSysIds.has(Number(so.SysId))
							? "badge-success"
							: "badge-light";
						let soNumberHtml = `
							<span class="badge ${badgeClass}" data-sys-id="${so.SysId}">
								${so.SO_Number}
								<div class="badge-number">${processedIndex}</div>
							</span>
						`;
						$("#list-multiple-so").append(soNumberHtml);
					}
				}
				// Jika tidak ada item outstanding, tampilkan badge "Lanjutkan"
			});

			if (hasOutstandingItems) {
				$("#info-multiple-so").hide();
				let continueBadgeHtml = `
					<div class="d-flex"> 
						<span class="badge badge-secondary badge-continue">
							<i class="fas fa-search"></i>
						</span>
						<span class="badge badge-warning ml-2 badge-reset d-none">
							<i class="fas fa-sync"></i>
						</span>
					</div>
				`;
				$("#list-multiple-so").append(continueBadgeHtml);
			} else {
				appendBadgeMultipleSO(
					"Tidak ada Item Sales Order Outstanding yang Tersedia",
					true
				);

				Swal.fire({
					icon: "info",
					title: "Opsss!",
					text: "Customer yang dipilih tidak mempunyai sales order outstanding.",
				});
				return; // Hentikan eksekusi jika respons tidak valid
			}
			// Menambahkan event listener untuk mengubah warna pada klik badge
			// Append the HTML content to a target element, e.g., #targetElement
			// Modify the classes after appending the HTML content

			//
			let state = $("#state").val();
			if (state === "EDIT") {
				$(".badge-continue")
					.removeClass("badge-danger")
					.addClass("badge-secondary ");
				$(".badge-continue").prop("disabled", true);
			}
		},
		error: function (xhr, status, error) {
			console.error("AJAX error: " + status + " - " + error);
		},
	});
});
//
//  BUG here
$(document).on(
	"click",
	".badge:not(.badge-continue):not(.badge-reset)",
	function () {
		let sysId = $(this).data("sys-id");

		// Jika badge sedang dalam status "selected" dan hanya ada satu item di selectedSysIds, tidak boleh dihapus
		if ($(this).hasClass("badge-success") && selectedSysIds.size === 1) {
			Swal.fire({
				icon: "warning",
				title: "Peringatan",
				text: "Anda harus memilih minimal 1 dokumen Sales Order (SO).",
				confirmButtonText: "OK",
			});
			return;
		}

		// Jika ada lebih dari 1 item yang dipilih, lakukan toggle class
		$(this).toggleClass("badge-light badge-success");

		// Tambah atau hapus sysId dari selectedSysIds sesuai status badge
		if ($(this).hasClass("badge-success")) {
			selectedSysIds.add(sysId);
		} else {
			selectedSysIds.delete(sysId);
		}

		// Aktifkan tombol lanjut
		$(".badge-continue")
			.removeClass("badge-secondary")
			.addClass("badge-danger");
		$(".badge-continue").prop("disabled", false);
	}
);

//
// Menambahkan event listener khusus untuk badge 'Lanjutkan' xxx
$(document).on("click", ".badge-continue", function () {
	$(this).addClass("badge-secondary").removeClass("badge-danger");
	$(this).prop("disabled", true); // Nonaktifkan badge
	//
	let selectedSysIdsArray = Array.from(selectedSysIds);
	//
	if (selectedSysIdsArray.length === 0) {
		Swal.fire({
			icon: "warning",
			title: "Peringatan",
			text: "Anda harus memilih minimal 1 dokumen Sales Order (SO) untuk melanjutkan.",
			confirmButtonText: "OK",
		});
		continueState = false;
		return;
	}
	// console.log(selectedSysIdsArray);
	$.ajax({
		type: "POST",
		url:
			$('meta[name="base_url"]').attr("content") +
			"Sales/ShippingIns/DT_listdata_SO_details",
		data: {
			header_ids: selectedSysIdsArray,
		},
		dataType: "json",
		success: function (detailsResponse) {
			let anyItemsDisplayed = false;
			continueState = true;
			let tableBody = $("#detail-table tbody");
			// Buat set untuk menyimpan detailSoSysId yang baru
			let newDetailSoSysIds = new Set(
				detailsResponse.map((item) => item.sysId_dtl_so)
			);

			// Hapus kunci yang tidak ada dalam data baru
			for (let key in qtyShippedValues) {
				if (!newDetailSoSysIds.has(key)) {
					delete qtyShippedValues[key];
				}
			}

			for (let key in freeValues) {
				if (!newDetailSoSysIds.has(key)) {
					delete freeValues[key];
				}
			}
			//
			tableBody.empty();
			//
			// console.table(detailsResponse);
			$.each(detailsResponse, function (index, item) {
				// Jangan tampilkan data yang telah dihapus
				if (deletedSysIds.has(Number(item.sysId_dtl_so))) {
					return;
				}
				// Filter selectedWarehouses berdasarkan Set selectedSysIds
				selectedWarehouses = selectedWarehouses.filter(function (warehouse) {
					// Bandingkan dengan Set selectedSysIds
					return selectedSysIds.has(Number(warehouse.soSysIdHdr));
				});

				// Filter selectedUnits berdasarkan hdrSo yang ada di selectedSysIds
				selectedUnits = Object.keys(selectedUnits)
					.filter(function (sysId) {
						// Bandingkan hdrSo di dalam selectedUnits dengan Set selectedSysIds
						return selectedSysIds.has(Number(selectedUnits[sysId].hdrSo));
					})
					.reduce(function (acc, sysId) {
						acc[sysId] = selectedUnits[sysId];
						return acc;
					}, {});

				// Jangan tampilkan item dengan Qty_ost_so = 0 BUG
				if (item.Qty_ost_so == 0) {
					return;
				} else {
					// Jika ada item yang memiliki Qty_ost_so > 0, set flag ke true
					anyItemsDisplayed = true;
					// xxx
					// console.table(qtyShippedValues);
					// console.log(item.sysId_dtl_so);
					let qtyShippedValue =
						qtyShippedValues[item.sysId_dtl_so] !== undefined
							? qtyShippedValues[item.sysId_dtl_so]
							: "";
					// console.log(qtyShippedValue); // Jika item.sysId_dtl_so tidak ditemukan, ini akan mengembalikan 82
					// Filter selectedWarehouses untuk item saat ini
					let itemWarehouses = selectedWarehouses.filter(
						(wh) =>
							wh.ItemCode === item.Item_Code && wh.soSysId === item.sysId_dtl_so
					);
					//
					//
					// let freeValue = freeValues[item.sysId_dtl_so] || "";
					let row = `
												<tr data-sys-id="${item.sysId_dtl_so}">
												<td class="text-center vertical-align-middle">
													${item.SO_Number}
													<input type="hidden" name="sysId_dtl_so[]" value="${item.sysId_dtl_so}">
													<input type="hidden" name="so_number[]" value="${item.SO_Number}">
												</td>
												<td class="text-center vertical-align-middle">
													${item.Item_Code}
													<input type="hidden" name="item_code[]" value="${item.Item_Code}">
												</td>
												<td class="text-center vertical-align-middle">
													${item.Item_Name}
													<input type="hidden" name="item_name[]" value="${item.Item_Name}">
													<input type="hidden" name="amount[]" value="${item.Amount}">
												</td>
												<td class="text-center vertical-align-middle">${item.note}</td>
												<td class="text-center vertical-align-middle">${item.Item_Color}</td>
												<td class="text-center v">${item.Brand}</td>
												<td class="text-center vertical-align-middle">
													${item.Dimension_Info}
													<input type="hidden" name="dimension[]" value="${item.Dimension_Info}">
												</td>
												<td class="text-center vertical-align-middle">${item.Weight_Info}</td>
												<td class="text-center vertical-align-middle">
													${parseFloat(item.qty)}
													<input type="hidden" name="qty[]" value="${item.qty}">
												</td>
												<td class="text-center vertical-align-middle qty-ost" data-sys-id="${
													item.sysId_dtl_so
												}">
													${parseFloat(item.Qty_ost_so)}
												</td>
												<td class="text-center vertical-align-middle">
													<input required readonly type="text" class="text-center qty-shipped-input form-control form-control-sm only-number" name="qty_shipped[]" data-sys-id="${
														item.sysId_dtl_so
													}" data-qty-order="${item.Qty_order}" data-qty-shp="${
						item.Tot_qty_shp
					}" data-qty-ost="${item.Qty_ost_so}" value="${qtyShippedValue}">
												</td>
												<td class="text-center vertical-align-middle text-uppercase">
													${item.Uom}
													<input type="hidden" name="uom[]" value="${item.Unit_Type_ID}">
												</td>
												//
												<td class="text-center vertical-align-middle">
													<input type="text" class="form-control form-control-sm text-center only-number" name="Secondary_Qty[]" value="" placeholder="0">
												</td>
												  <td class="text-center vertical-align-middle">
													<select class="form-control form-control-sm text-center" name="Secondary_Uom[]">
														<option value="" disabled selected>None</option>
													</select>
												</td>
												// 
												<td class="text-center vertical-align-middle" style="border-right: none;">
													${
														itemWarehouses.length > 0
															? itemWarehouses
																	.map(
																		(wh) => `
																<div class="d-flex align-items-center my-1">
																	<button type="button" class="btn btn-outline-danger btn-sm mr-1 btn-minus" data-item-code="${
																		wh.ItemCode
																	}" data-warehouse-id="${wh.warehouseId}">
																		<i class="fas fa-minus"></i>
																	</button>
																	<input class="mr-1 form-control form-control-sm text-center" type="hidden" readonly name="wh_id[]" value="${
																		item.sysId_dtl_so +
																		"-" +
																		wh.ItemCode +
																		"#" +
																		wh.warehouseId +
																		"=" +
																		parseFloat(wh.qty)
																	}">
																	<input class="mr-1 form-control form-control-sm text-center" type="text" readonly name="" value="${
																		wh.warehouseCode
																	}">
																	<input class="ml-1 form-control form-control-sm text-center" type="text" readonly name="stock_item[]" value="${parseFloat(
																		wh.qty
																	)}">
																</div>
															`
																	)
																	.join("")
															: `<div class="d-flex justify-content-center">
																	<span style="opacity: 0.5; pointer-events: none; class="badge badge-info">No Data Click Icon Plus</span>
																</div>`
													}
												</td>
												//
												<td class="vertical-align-middle" style="border-left: none;">
													<button
													    data-hdr-so="${item.sysId_hdr_so}"
													    data-dtl-so="${item.sysId_dtl_so}"
													    data-item-code="${item.Item_Code}"
														data-item-name="${item.Item_Name}"
														data-note="${item.note}"
														data-color="${item.Item_Color}"
														data-brand="${item.Brand}"
														data-dimension="${item.Dimension_Info}"
														data-weight="${item.Weight_Info}"
														data-qty-order="${item.qty}"
														data-qty-ost="${item.Qty_ost_so}"
														type="button"
														class="btn btn-sm btn-success ml-1 btn-add-wh">
														<i class="fas fa-plus"></i>
													</button>
												</td>"
												//
										
												<td class="text-center vertical-align-middle">
													<button type="button" class="btn btn-danger btn-sm delete-row" data-sys-id="${
														item.sysId_dtl_so
													}">
														<i class="fas fa-trash-alt"></i>
													</button>
												</td>
											</tr>
										`;

					tableBody.append(row); // Get the select element that was just added and populate it

					let unitSelect = tableBody
						.find('select[name="Secondary_Uom[]"]')
						.last();
					//
					populateUnitSelect(
						unitSelect,
						unitTypeOptions,
						item.sysId_dtl_so,
						item.sysId_hdr_so
					);

					// Get the input element for Secondary_Qty and populate it
					let qtyInput = tableBody.find('input[name="Secondary_Qty[]"]').last();
					populateSecondaryQty(qtyInput, item.sysId_dtl_so, item.sysId_hdr_so);
				}
			});
			//

			//
			// Jika tidak ada item yang ditampilkan, tampilkan SweetAlert
			if (anyItemsDisplayed) {
				$(".badge-reset").removeClass("d-none");
				// Swal.fire({
				// 	icon: "info",
				// 	title: "Tidak Ada Item Outstanding",
				// 	text: "Item Sales order yang dipilih tidak mempunyai item outstanding.",
				// });
			} else {
			}
			//
		},
	});
});

let selectedUnits = {}; // Objek untuk menyimpan nilai unit yang dipilih berdasarkan itemSysId

function populateUnitSelect(
	selectElement,
	unitTypeOptions,
	itemSysId,
	sysIdHdrSo
) {
	// Hapus semua opsi yang ada sebelumnya (untuk menghindari duplikasi saat pengeditan)
	selectElement.empty();

	// Tambahkan opsi placeholder default
	selectElement.append('<option value="" disabled selected>None</option>');

	// Populasi opsi dropdown dengan unit yang ada
	unitTypeOptions.forEach(function (unit) {
		let option = $("<option></option>")
			.val(unit.Uom)
			.text(`${unit.Unit_Name} - ${unit.Uom}`);

		// Jika ada nilai unit yang disimpan di selectedUnits, bandingkan dan tandai sebagai selected
		if (
			selectedUnits[itemSysId] &&
			selectedUnits[itemSysId].unit === unit.Uom
		) {
			option.prop("selected", true); // Tandai sebagai selected jika cocok
		}

		//
		selectElement.append(option);
	});

	// Event listener untuk menyimpan nilai unit yang dipilih ke dalam objek selectedUnits
	selectElement
		.off("change.populateUnit")
		.on("change.populateUnit", function () {
			let selectedValue = $(this).val();
			selectedUnits[itemSysId] = {
				unit: selectedValue,
				hdrSo: sysIdHdrSo,
			};
		});
}

function populateSecondaryQty(inputElement, itemSysId, hdrSo) {
	// Jika selectedUnits memiliki nilai qty yang tersimpan untuk itemSysId, atur value input
	if (selectedUnits[itemSysId] && selectedUnits[itemSysId].qty) {
		inputElement.val(selectedUnits[itemSysId].qty);
	}

	// Event listener untuk menyimpan nilai qty yang dimasukkan ke dalam objek selectedUnits
	inputElement.off("input.populateQty").on("input.populateQty", function () {
		let selectedQty = $(this).val();
		// Simpan nilai qty dan hdrSo ke dalam selectedUnits
		if (!selectedUnits[itemSysId]) {
			selectedUnits[itemSysId] = {};
		}
		selectedUnits[itemSysId].qty = selectedQty;
		selectedUnits[itemSysId].hdrSo = hdrSo; // Simpan juga hdrSo
	});
}

// xxx
$(document).on("input", ".qty-shipped-input", function () {
	//
	let qtyOrder = parseFloat($(this).data("qty-order"));
	let qtyShp = parseFloat($(this).data("qty-shp"));
	let qtyOst = parseFloat($(this).data("qty-ost"));

	let currentValue = parseFloat($(this).val()) || 0;
	let sysId = $(this).data("sys-id");
	let value = $(this).val();

	// alert(qtyOst + "" + currentValue);
	let state = $("#state").val();
	// let resultQtyOst = NULL;
	if (state == "EDIT" && !continueState) {
		checkQtyOst = qtyOrder;
	} else {
		checkQtyOst = qtyOst;
	}

	if (currentValue > checkQtyOst) {
		Swal.fire({
			icon: "warning",
			title: "Ups...",
			text: "Jumlah yang dikirim tidak boleh melebihi jumlah yang tersedia.",
			footer: '<a href="javascript:void(0)">Notifikasi Sistem</a>',
		});

		if (state == "EDIT") {
			$(this).val(qtyOst + qtyShp);
			value = qtyOst + qtyShp;
		} else {
			$(this).val(qtyOst);
			value = qtyOst;
		}
		resultQtyOst = 0;
	} else {
		// Update qty-ost dengan mengurangi nilai qty-shipped
		resultQtyOst = checkQtyOst - currentValue;
	}
	// Perbarui nilai Qty_ost_so di dalam elemen <td> terkait dengan kelas qty-ost
	$(`td.qty-ost[data-sys-id="${sysId}"]`).text(resultQtyOst);
	qtyShippedValues[sysId] = value;
});

$(document).on("click", ".delete-row", function () {
	// Ambil sysId langsung dari tombol yang diklik
	let sysId = $(this).data("sys-id");
	// Hapus dari set dan objek nilai input
	// selectedSysIds.delete(sysId);
	delete qtyShippedValues[sysId];
	delete freeValues[sysId];

	selectedWarehouses = selectedWarehouses.filter(
		(item) => Number(item.soSysId) !== Number(sysId)
	);
	//
	// alert(sysId);
	deletedSysIds.add(sysId);
	// Hapus baris dari tabel
	$(this).closest("tr").remove();
	$(".badge-reset").removeClass("d-none");
	//
	Toast.fire({
		icon: "success",
		title:
			"Data Detail berhasil dihapus. Lakukan reset untuk mengembalikan baris yang telah dihapus.",
	});
});

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

//
$("#btn-list-address").click(function () {
	//
	let currentSysId = $("#alamat-customer-id").val();
	let customerCode = $("#customer-code").val();
	//
	$.ajax({
		url:
			$('meta[name="base_url"]').attr("content") +
			"Sales/ShippingIns/DT_listofaccount_address", // Ganti dengan URL yang sesuai
		type: "POST",
		data: {
			customer_code: customerCode,
		},
		success: function (response) {
			let data = JSON.parse(response);
			let noAlamat = false;

			tableAddress.clear().rows.add(data).draw();
			$("#table-address tbody tr").each(function () {
				let rowData = tableAddress.row(this).data();
				if (typeof rowData == "undefined") {
					Swal.fire({
						icon: "info",
						title: "Informasi",
						text: "Costumer belum memiliki alamat",
						footer:
							'<a href="javascript:void(0)" class="text-info">Informasi System</a>',
					});
					noAlamat = true;
					return;
				}
				let sysid = rowData.SysId; // SysId dari data baris
				// alert(sysid);
				if (sysid === currentSysId) {
					$(this).find('input[type="radio"]').prop("checked", true);
					$(this).addClass("table-primary");
				}
			});
			if (!noAlamat) {
				$("#addressModal").modal("show"); // Menampilkan modal setelah data diterima
			}
		},
		error: function (xhr, status, error) {
			// NOTE
			console.log("Error: " + error);
		},
	});
});

$("#table-address tbody").on("click", 'input[type="radio"]', function () {
	$("#table-address tbody tr").removeClass("table-primary");
	$(this).closest("tr").addClass("table-primary");
});

//
$("#btn-select-address").click(function (e) {
	let selectedRow = $('input[name="selectAddress"]:checked').closest("tr");
	let rowData = tableAddress.row(selectedRow).data();
	let selectedAddress = rowData.Address;
	let selectedSysId = rowData.SysId;
	let area = rowData.Area;
	// Tampilkan address di textarea dan simpan SysId di data atribut
	$("#alamat-customer").val(selectedAddress).data("sysid", selectedSysId);
	$("#alamat-customer-id").val(selectedSysId);
	$("#area").val(area);
	cekArea(area);
	// Tutup modal
	$("#export-label").text(selectedAddress);
	$("#export-radio").prop("disabled", false).prop("checked", true);
	$("#addressModal").modal("hide");
});

//
function cekArea(area = "") {
	$(".export-field").each(function () {
		let defaultPlaceholder;

		switch (area) {
			case "Domestic":
				// Hapus class flatpickr jika ada
				$(this).removeClass("flatpickr");

				// Set disabled dan ubah type menjadi text
				$(this).attr("disabled", true).attr("type", "text");

				// Ubah placeholder menjadi "Khusus untuk transaksi ekspor"
				$(this).attr("placeholder", "Khusus untuk transaksi ekspor");
				break;

			case "OverSeas":
				// Hapus disabled jika sebelumnya di-set
				$(this).removeAttr("disabled");

				// Tentukan placeholder sesuai dengan field tertentu
				switch ($(this).attr("id")) {
					case "NotifeParty":
						defaultPlaceholder = "Masukan Pihak Penerima Pemberitahuan";
						break;
					case "NotifePartyAddress":
						defaultPlaceholder = "Masukan Alamat Pihak Penerima Pemberitahuan";
						break;
					case "ShippingMarks":
						defaultPlaceholder = "Masukan Tanda Pengiriman";
						break;
					case "LCNo":
						defaultPlaceholder = "Masukan Nomor LC";
						break;
					case "LCDate":
						defaultPlaceholder = "Masukan Tanggal LC";
						break;
					case "LCBank":
						defaultPlaceholder = "Masukan Bank LC";
						break;
					default:
						defaultPlaceholder = "Masukan Data";
						break;
				}

				// Set placeholder yang sesuai
				$(this).attr("placeholder", defaultPlaceholder);

				// Tambahkan kembali class flatpickr jika diperlukan
				$(this).addClass("flatpickr");
				break;

			default:
				$(this).removeClass("flatpickr");
				// Set disabled dan type text
				$(this).attr("disabled", true).attr("type", "text");

				// Set placeholder menjadi "Silahkan pilih alamat"
				$(this).attr("placeholder", "Silahkan pilih alamat pengiriman");
				break;
		}
	});
}

// Kirim data header dan detail ke controller untuk dikelola dan disimpan kedalam tabel
$("#main-form").on("submit", function (event) {
	event.preventDefault();
	// let tests = true;
	if (validateForm()) {
		let formData = $(this).serialize();
		$.ajax({
			url:
				$('meta[name="base_url"]').attr("content") + "Sales/ShippingIns/store",
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

//
function validateForm() {
	let isValid = true;
	let errorMessage = " harus diisi.";

	// Validasi apakah masih ada teks "No Data Click Icon Plus"
	// Cek apakah masih ada teks "No Data Click Icon Plus" secara lebih spesifik
	let noDataElements = $("#detail-table td").filter(function () {
		return $(this).text().trim() === "No Data Click Icon Plus";
	});

	if (noDataElements.length > 0) {
		isValid = false;
		Swal.fire({
			icon: "error",
			title: "Oops...",
			text: "Terdapat item tidak memiliki data warehouse yang valid. Klik ikon plus untuk menambah data warehouse, atau hapus baris tersebut.",
			confirmButtonColor: "#3085d6",
			confirmButtonText: "Tutup",
		});
		return isValid; // Langsung return agar tidak melanjutkan validasi lainnya
	}

	function showErrorMessage(element, message) {
		let errorElement = $('<div class="invalid-feedback"></div>').text(message);
		$(element).addClass("is-invalid");
		$(element).parent().append(errorElement);
	}

	function clearErrorMessages() {
		$(".invalid-feedback").remove();
		$(".form-control").removeClass("is-invalid");
	}

	// Helper function to check if the element is disabled
	function isElementDisabled(element) {
		return $(element).is(":disabled");
	}

	clearErrorMessages();

	// Validate Tanggal Shipping (skip if disabled)
	let tanggalShipping = $("#tanggal-shipping");
	if (
		!isElementDisabled(tanggalShipping) &&
		tanggalShipping.val().trim() === ""
	) {
		isValid = false;
		showErrorMessage(tanggalShipping, "Tanggal Shipping" + errorMessage);
	}

	// Validate Nama Customer (skip if disabled)
	let selectCustomer = $("#select-customer");
	if (
		!isElementDisabled(selectCustomer) &&
		selectCustomer.val().trim() === ""
	) {
		isValid = false;
		showErrorMessage(selectCustomer, "Nama Customer" + errorMessage);
	}

	// Validate Alamat Pengiriman (skip if disabled)
	let alamatCustomer = $("#alamat-customer");
	if (
		!isElementDisabled(alamatCustomer) &&
		alamatCustomer.val().trim() === ""
	) {
		isValid = false;
		showErrorMessage(alamatCustomer, "Alamat Pengiriman" + errorMessage);
	}

	// Validate Tanggal Pengiriman (skip if disabled)
	let tanggalPengiriman = $("#tanggal-pengiriman");
	if (
		!isElementDisabled(tanggalPengiriman) &&
		tanggalPengiriman.val().trim() === ""
	) {
		isValid = false;
		showErrorMessage(tanggalPengiriman, "Tanggal Pengiriman" + errorMessage);
	}

	// Validate Port of Loading (skip if disabled)
	let portOfLoading = $("#port-of-loading");
	if (!isElementDisabled(portOfLoading) && portOfLoading.val().trim() === "") {
		isValid = false;
		showErrorMessage(portOfLoading, "Tempat Muat" + errorMessage);
	}

	// Validate Place of Delivery (skip if disabled)
	let placeOfDelivery = $("#place-of-delivery");
	if (
		!isElementDisabled(placeOfDelivery) &&
		placeOfDelivery.val().trim() === ""
	) {
		isValid = false;
		showErrorMessage(placeOfDelivery, "Tempat Pengiriman" + errorMessage);
	}

	// Validate Carrier (skip if disabled)
	let carrier = $("#carrier");
	if (!isElementDisabled(carrier) && carrier.val().trim() === "") {
		isValid = false;
		showErrorMessage(carrier, "NO Pol Kendaraan" + errorMessage);
	}

	// Validate Sailing (skip if disabled)
	let sailing = $("#sailing");
	let selectedTransport = sailing.val(); // Mendapatkan nilai yang dipilih
	if (!isElementDisabled(sailing) && selectedTransport === "") {
		isValid = false;
		showErrorMessage(sailing, "Kendaraan" + errorMessage);
	}

	// Validate Other Transport (wajib jika opsi "Lainnya" dipilih)
	if (selectedTransport === "other") {
		let otherTransport = $("#other_transport");
		if (otherTransport.val().trim() === "") {
			isValid = false;
			showErrorMessage(
				otherTransport,
				"Jenis Pengangkut Lainnya" + errorMessage
			);
		}
	}
	// Validate NotifeParty (skip if disabled)
	let notifeParty = $("#NotifeParty");
	if (!isElementDisabled(notifeParty) && notifeParty.val().trim() === "") {
		isValid = false;
		showErrorMessage(
			notifeParty,
			"Pihak Penerima Pemberitahuan" + errorMessage
		);
	}

	// Validate NotifePartyAddress (skip if disabled)
	let notifePartyAddress = $("#NotifePartyAddress");
	if (
		!isElementDisabled(notifePartyAddress) &&
		notifePartyAddress.val().trim() === ""
	) {
		isValid = false;
		showErrorMessage(
			notifePartyAddress,
			"Alamat Pihak Penerima Pemberitahuan" + errorMessage
		);
	}

	// Validate ShippingMarks (skip if disabled)
	let shippingMarks = $("#ShippingMarks");
	if (!isElementDisabled(shippingMarks) && shippingMarks.val().trim() === "") {
		isValid = false;
		showErrorMessage(shippingMarks, "Tanda Pengiriman" + errorMessage);
	}

	// Validate LCNo (skip if disabled)
	let lcNo = $("#LCNo");
	if (!isElementDisabled(lcNo) && lcNo.val().trim() === "") {
		isValid = false;
		showErrorMessage(lcNo, "Nomor LC" + errorMessage);
	}

	// Validate LCDate (skip if disabled)
	let lcDate = $("#LCDate");
	if (!isElementDisabled(lcDate) && lcDate.val().trim() === "") {
		isValid = false;
		showErrorMessage(lcDate, "Tanggal LC" + errorMessage);
	}

	// Validate LCBank (skip if disabled)
	let lcBank = $("#LCBank");
	if (!isElementDisabled(lcBank) && lcBank.val().trim() === "") {
		isValid = false;
		showErrorMessage(lcBank, "Bank LC" + errorMessage);
	}

	// Validate if there is at least one item in the detail table
	let rowCount = $("#detail-table tbody tr").length;
	if (rowCount === 0) {
		isValid = false;
	}

	if (!isValid) {
		Swal.fire({
			icon: "error",
			title: "Oops...",
			text: "Lengkapi semua data dan pilih minimal 1 Document SO",
			confirmButtonColor: "#3085d6",
			confirmButtonText: "Tutup",
		});
	}

	return isValid;
}

//
$(document).on("click", ".badge-reset", function () {
	if (deletedSysIds.size === 0) {
		// Jika tidak ada data yang dihapus, tampilkan pesan ini
		Toast.fire({
			icon: "info",
			title: "Tidak ada data detail yang dihapus untuk dikembalikan.",
		});
		return;
	}
	// Hapus data dari selectedUnits berdasarkan deletedSysIds
	deletedSysIds.forEach(function (sysId) {
		if (selectedUnits.hasOwnProperty(sysId)) {
			delete selectedUnits[sysId];
		}
	});

	deletedSysIds.clear();
	Toast.fire({
		icon: "success",
		title: "Mengembalikan seluru data detail yang dihapus.",
	});
	$(".badge-continue").prop("disabled", false); // Aktifkan badge kembali
	// Jalankan fungsi untuk memuat ulang data setelah reset
	$(".badge-continue").trigger("click");
});

//
$(document).on("keypress keyup blur", ".not-zero-start", function (event) {
	let inputVal = $(this).val();

	// Menghapus angka nol di awal input
	$(this).val(inputVal.replace(/^0+/, ""));

	// Mencegah input angka nol di awal
	if (inputVal === "" && event.which === 48) {
		event.preventDefault();
	}
});

//
function reloadData() {
	let selectedShippingOrders = {}; // Object to store selected Shipping Orders
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
				"Sales/ShippingIns/DT_listdata",
			dataType: "json",
			type: "POST",
		},
		columns: [
			{
				data: "ShipInst_Number",
				createdCell: function (td) {
					$(td).addClass("text-center align-middle");
				},
			},
			{
				data: "ShipInst_Date",
				render: function (data) {
					return data.substring(0, 10);
				},
				createdCell: function (td) {
					$(td).addClass("text-center align-middle");
				},
			},
			{
				data: "Address",
				createdCell: function (td) {
					$(td).addClass("align-middle");
				},
			},
			{
				data: "ExpectedDeliveryDate",
				render: function (data) {
					return data.substring(0, 10);
				},
				createdCell: function (td) {
					$(td).addClass("text-center align-middle");
				},
			},
			{
				data: "SysId",
				render: function (data, type, row) {
					let button =
						'<button class="btn btn-success btn-sm btn-details" data-sysid="' +
						row.SysId +
						'">' +
						'<i class="fas fa-file"></i>' +
						"</button>";
					return button;
				},
				createdCell: function (td) {
					$(td).addClass("text-center align-middle");
				},
			},
			{
				data: "Approve",
				render: function (data) {
					return data == 1
						? '<div class="d-flex justify-content-center"><i class="fas fa-check text-success"></i></div>'
						: '<div class="d-flex justify-content-center"><i class="fas fa-question text-danger"></i></div>';
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
						delete selectedShippingOrders[sysId];
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

						selectedShippingOrders = {};

						if ($(this).hasClass("table-warning")) {
							$(this)
								.removeClass("table-warning")
								.addClass("table-primary text-white table-warning-selected");
						} else {
							$(this).addClass("table-primary text-white");
						}

						selectedShippingOrders[sysId] = rowData;
					}
				} else {
					console.error("Row data or SysId is undefined", rowData);
				}
			});

			// Event handler for buttons with data-sysid
			$("#DataTable").on("click", "button[data-sysid]", function (e) {
				e.stopPropagation(); // Prevent the row click event from triggering
				let sysId = $(this).data("sysid");
				// Send AJAX request to fetch additional details
				$.ajax({
					type: "POST",
					url:
						$('meta[name="base_url"]').attr("content") +
						"Sales/ShippingIns/DT_PEB_BC",
					data: {
						sys_id: sysId,
					},
					dataType: "json",
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
						Swal.close();

						if (response.status === "success") {
							let data = response.data[0]; // Assuming the first item in the array is the one we need
							// Validasi Is_Cancel
							if (Number(data.Is_Cancel) === 1) {
								Swal.fire({
									icon: "error",
									title: "Oops!!",
									text: "Data sudah dicancel!",
								});
								return;
							}
							// Validasi isExport
							if (Number(data.isExport) === 1) {
								// Update input values based on the response data
								$("#sysid-for-update-peb-bc").val(data.SysId);
								$("#PEB-Number").val(data.PEB_Number);
								$("#PEB-Date").val(data.PEB_Date);
								$("#PEB-Receiver").val(data.PEB_Receiver);
								$("#PEB-Country").val(data.PEB_Country);
								$("#PEB-Amount").val(parseFloat(data.PEB_Amount) || "");
								$("#PEB-Volume").val(parseFloat(data.PEB_Volume) || "");
								$("#PEB-Netto").val(parseFloat(data.PEB_Netto) || "");
								$("#PEB-Merk").val(data.PEB_Merk);
								$("#PEB-PackageNumber").val(data.PEB_PackageNumber);
								$("#BC_Type").val(data.BC_Type);
								$("#BC-Number").val(data.BC_Number);
								$("#BC-Date").val(data.BC_Date);

								// Show the modal
								$("#detail_PEB_BC").modal("show");
							} else {
								// Jika isExport != 1, berikan pesan bahwa ini bukan transaksi ekspor
								Swal.fire({
									icon: "warning",
									title: "Oops!!",
									text: "Khusus untuk transaksi ekspor.",
								});
							}
						} else {
							Swal.fire({
								icon: "error",
								title: "Error",
								text: "Data not found!",
							});
						}
					},
					error: function (xhr, status, error) {
						Swal.close();
						console.error("AJAX error: " + status + " - " + error);
					},
				});
			});
		},
		drawCallback: function () {
			// Kosongkan selectedShippingOrders yang tidak sesuai dengan hasil pencarian
			$("#DataTable tbody tr").each(function () {
				const rowData = dataTable.row(this).data();

				// Log row data on draw
				if (rowData && selectedShippingOrders[rowData.SysId]) {
					// Jika SysId ditemukan dalam hasil pencarian, tetap simpan di selectedShippingOrders
					if (selectedShippingOrders[rowData.SysId]) {
						$(this)
							.removeClass("table-warning")
							.addClass("table-primary text-white table-warning-selected");
					}
				} else {
					// Hapus SysId yang tidak ada dalam hasil pencarian
					for (const sysId in selectedShippingOrders) {
						if (selectedShippingOrders.hasOwnProperty(sysId) && !rowData) {
							delete selectedShippingOrders[sysId];
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
				text: `<i class="fas fa-plus fs-3"></i> ADD Surat Jalan`,
				className: "bg-primary",
				action: function () {
					form_state("ADD");
				},
			},
			{
				text: `<i class="fas fa-search"></i> View Detail`,
				className: "btn btn-info",
				action: function () {
					if (Object.keys(selectedShippingOrders).length === 0) {
						Swal.fire({
							icon: "warning",
							title: "Ooppss...",
							text: "Silahkan pilih data untuk melihat detail!",
							footer:
								'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
						});
					} else {
						let selectedRow = Object.values(selectedShippingOrders)[0];
						let sysId = selectedRow.SysId;
						let url =
							$('meta[name="base_url"]').attr("content") +
							"Sales/ShippingIns/detail/" +
							sysId;
						window.location.href = url;
					}
				},
			},
			{
				text: `<i class="fas fa-edit fs-3"></i> Edit`,
				className: "btn btn-warning",
				action: function () {
					if (Object.keys(selectedShippingOrders).length === 0) {
						Swal.fire({
							icon: "warning",
							title: "Ooppss...",
							text: "Silahkan pilih data untuk melihat detail!",
							footer:
								'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
						});
					} else {
						let selectedRow = Object.values(selectedShippingOrders)[0];
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
				text: `<i class="fas fa-print fs-3"></i> Print Surat Jalan `,
				className: "btn bg-gradient-success",
				action: function () {
					if (Object.keys(selectedShippingOrders).length === 0) {
						Swal.fire({
							icon: "warning",
							title: "Ooppss...",
							text: "Silahkan pilih data terlebih dahulu untuk mencetak report!",
							footer:
								'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
						});
					} else {
						let selectedRow = Object.values(selectedShippingOrders)[0];
						let sysId = selectedRow.SysId + "." + "0";
						let Approve = selectedRow.Approve;
						let isCancel = selectedRow.Is_Cancel;

						// Check approval and close status
						if (isCancel == 1 || Approve == 2 || Approve == 0) {
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
									"Sales/ShippingIns/export_pdf_si/" +
									sysId,
								"_blank"
							);
							//
						}
					}
				},
			},
			{
				text: `<i class="fas fa-print fs-3"></i> Print Comm Invoice `,
				className: "btn bg-gradient-success",
				action: function () {
					if (Object.keys(selectedShippingOrders).length === 0) {
						Swal.fire({
							icon: "warning",
							title: "Ooppss...",
							text: "Silahkan pilih data terlebih dahulu untuk mencetak report!",
							footer:
								'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
						});
					} else {
						let selectedRow = Object.values(selectedShippingOrders)[0];
						let sysId = selectedRow.SysId + "." + "1";
						let Approve = selectedRow.Approve;
						let isCancel = selectedRow.Is_Cancel;

						// Check approval and close status
						if (isCancel == 1 || Approve == 2 || Approve == 0) {
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
									"Sales/ShippingIns/export_pdf_si/" +
									sysId,
								"_blank"
							);
							//
						}
					}
				},
			},
			{
				text: `<i class="fa fa-times fs-3"></i> <small><i>Cancel</i></small>`,
				className: "btn btn-dark",
				action: function () {
					if (Object.keys(selectedShippingOrders).length === 0) {
						Swal.fire({
							icon: "warning",
							title: "Ooppss...",
							text: "Silahkan pilih data untuk merubah status!",
							footer:
								'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
						});
					} else {
						let selectedRow = Object.values(selectedShippingOrders)[0];
						let sysId = selectedRow.SysId;
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

//
$("#save-peb-bc").on("click", function (event) {
	event.preventDefault();

	// Serialize form data from the modal
	let formData = $("#peb_bc_form").serialize();
	$.ajax({
		url:
			$('meta[name="base_url"]').attr("content") +
			"Sales/ShippingIns/Update_PEB_BC", // Ganti dengan URL yang sesuai untuk metode Update_PEB_BC
		type: "POST",
		data: formData,
		dataType: "json",
		beforeSend: function () {
			// Disable form elements or show loading indicator
			$("#add-warehouse").prop("disabled", true); // Nonaktifkan tombol untuk mencegah pengiriman ganda
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
					title: "Selamat!...",
					text: response.msg,
					footer: '<a href="javascript:void(0)">System Notification</a>',
				});
				$("#detail_PEB_BC").modal("hide");
			} else {
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
			$("#add-warehouse").prop("disabled", false); // Aktifkan kembali tombol
		},
	});
});
//

//
let selectedWarehouses = [];
// HERE
function Initialize_DataTable_Stok(providedItemCode, providedSoSysID) {
	// Log item code
	$("#table-stock-item").DataTable({
		destroy: true,
		processing: true,
		serverSide: true,
		lengthMenu: [
			[10, 25, 50, 10000],
			[10, 25, 50, "All"],
		],
		ajax: {
			url:
				$('meta[name="base_url"]').attr("content") +
				"Sales/ShippingIns/DT_listofstock_item",
			dataType: "json",
			type: "POST",
			data: {
				Item_Code: providedItemCode,
			},
			dataSrc: function (json) {
				// Lakukan filtering
				// Warning
				const filteredData = json.data.filter(
					(item) => parseFloat(item.Item_Qty) !== 0
				);
				return filteredData;
			},
		},
		columns: [
			{
				data: "Warehouse_ID",
				render: function (data, type, row, meta) {
					return `<input type="checkbox" class="check-item" value="${data}">`;
				},
				createdCell: function (td, cellData, rowData, row, col) {
					$(td).addClass("text-center vertical-align-middle");
				},
			},
			{
				data: "Warehouse_Code",
				createdCell: function (td, cellData, rowData, row, col) {
					$(td).addClass("text-center vertical-align-middle");
				},
			},
			{
				data: "Warehouse_Name",
				createdCell: function (td, cellData, rowData, row, col) {
					$(td).addClass("text-center vertical-align-middle");
				},
			},
			{
				data: "Item_Qty",
				render: function (data, type, row) {
					return parseFloat(data);
				},
				createdCell: function (td, cellData, rowData, row, col) {
					$(td).addClass("text-center vertical-align-middle");
				},
			},
			{
				data: "Uom",
				createdCell: function (td, cellData, rowData, row, col) {
					$(td).addClass("text-center vertical-align-middle text-uppercase");
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
			$("#table-stock-item tbody").empty(); // Kosongkan tabel sebelum menambahkan baris baru
			$("#DataTable tbody td").addClass("blurry");
			$("#selected-warehouses-table tbody").empty(); // Kosongkan tabel sebelum menambahkan baris baru
		},
		language: {
			processing:
				'<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
		},
		drawCallback: function () {
			let api = this.api();
			let filteredData = api
				.rows({
					filter: "applied",
				})
				.data();

			// Periksa apakah ini adalah pencarian internal DataTable
			let searchValue = $("#table-stock-item_filter input").val();

			// Jika data kosong dan tidak sedang mencari melalui search DataTable, tampilkan alert dan kosongkan tabel
			if (filteredData.length === 0 && searchValue.trim() === "") {
				Swal.fire({
					title: "Tidak Ada Data",
					text: "Tidak ditemukan data untuk item yang diminta silahkan hapus.",
					icon: "warning",
					confirmButtonText: "OK",
				}).then((result) => {
					if (result.isConfirmed) {
						$("#list-stock-item-modal").modal("hide");
					}
				});
			} else {
				// Ketika data sudah diambil, ubah tampilan menjadi dua kolom
				$("#left-container").removeClass("col-12").addClass("col-8");
				$("#right-container")
					.removeClass("col-12")
					.addClass("col-12")
					.show("slow");
				$("#right-container-parent").show("slow");
			}
			//
			selectedWarehouses = selectedWarehouses.filter(function (warehouse) {
				return warehouse.qty > 0;
			});
			// Tandai checkbox yang sudah dipilih sebelumnya
			selectedWarehouses.forEach(function (warehouse) {
				// Ambil warehouseId dari objek xxx
				let warehouseId = warehouse.warehouseId;
				let itemCode = warehouse.ItemCode;
				let soSysId = warehouse.soSysId;
				// Cek dan tambahkan kelas pada elemen yang sesuai
				if (
					itemCode === providedItemCode &&
					Number(soSysId) === providedSoSysID
				) {
					$(`input.check-item[value="${warehouseId}"]`).prop("checked", true);
					$(`input.check-item[value="${warehouseId}"]`)
						.closest("tr")
						.addClass("table-primary text-white");
				}
			});

			//
			$("#table-stock-item tbody tr").each(function () {
				let row = $(this);
				let Warehouse_Code = row.find("td:eq(1)").text();
				let qtyCell = row.find("td:eq(3)");
				let currentQty = parseFloat(qtyCell.text());
				// console.table(selectedWarehouses);
				// Temukan item yang sesuai di selectedWarehouses
				let matchingItems = selectedWarehouses.filter(function (item) {
					return (
						item.warehouseCode === Warehouse_Code &&
						item.ItemCode === providedItemCode
					);
				});

				if (matchingItems.length > 0) {
					// Jumlahkan qty dari semua item yang cocok
					let totalQty = matchingItems.reduce(function (acc, item) {
						return acc + item.qty;
					}, 0);

					// Menghitung nilai originalQty berdasarkan currentQty + totalQty
					let originalQty = currentQty - totalQty;

					// Update nilai qty pada sel tabel
					qtyCell.text(currentQty - totalQty);

					// Jika perlu, update originalQty untuk semua item yang cocok
					matchingItems.forEach(function (item) {
						item.originalQty = originalQty;
					});
				} else {
					console.log("No matching items found.");
				}
			});

			// Tambahkan baris yang dipilih ke tabel #selected-warehouses-table

			$("#table-stock-item tbody tr").each(function () {
				let row = $(this);
				let Warehouse_Code = row.find("td:eq(1)").text();
				// Temukan item yang sesuai di selectedWarehouses
				let selectedWarehouse = selectedWarehouses.find(function (item) {
					return (
						item.warehouseCode === Warehouse_Code &&
						item.ItemCode === providedItemCode &&
						Number(item.soSysId) === providedSoSysID
					);
				});
				if (selectedWarehouse) {
					let newRow = `
                    <tr data-warehouse-id="${selectedWarehouse.warehouseId}">
                        <td class="text-center vertical-align-middle">${selectedWarehouse.warehouseName}</td>
                        <td class="text-center vertical-align-middle">
                            <input type="text" class="only-number  form-control form-control-sm qty-input text-center" value="${selectedWarehouse.qty}" max="${selectedWarehouse.originalQty}">
                        </td>
                    </tr>
                `;
					$("#selected-warehouses-table tbody").append(newRow);
				}
			});

			$("#close-btn-modal-list-gudang").show();
		},
	});
}
//
// Event listener untuk kotak centang
$("#table-stock-item tbody").on("change", ".check-item", function () {
	let isChecked = $(this).prop("checked");
	let row = $(this).closest("tr");
	let warehouseId = $(this).val();
	let warehouseCode = row.find("td:eq(1)").text(); // Ambil kode gudang dari kolom kedua
	let warehouseName = row.find("td:eq(2)").text(); // Ambil nama gudang dari kolom ketiga
	let originalQty = parseFloat(row.find("td:eq(3)").text()); // Ambil QTY dari kolom keempat
	//
	let soSysIdHdr = $("#hdr-so-sysId").val();
	// ini detail
	let soSysId = $("#dtl-so-sysId").val();

	//
	if (isChecked) {
		//
		$(row).addClass("table-primary text-white");
		// Tambahkan nama gudang dan input QTY ke dalam array selectedWarehouses
		// xxx
		let itemCode = $("#dtl-item-code").text();
		selectedWarehouses.push({
			soSysIdHdr: soSysIdHdr,
			soSysId: soSysId,
			ItemCode: itemCode,
			warehouseId: warehouseId,
			warehouseCode: warehouseCode,
			warehouseName: warehouseName,
			originalQty: originalQty,
			qty: 0,
		});
		//

		//
		// Tambahkan baris ke tabel gudang yang dipilih dengan input form-control
		let newRow = `<tr data-warehouse-id="${warehouseId}">
                        <td class="text-center vertical-align-middle">${warehouseName}</td>
                        <td class="text-center vertical-align-middle"><input type="text" class="only-number form-control form-control-sm qty-input text-center" value="" max="${originalQty}"></td>
                     </tr>`;
		$("#selected-warehouses-table tbody").append(newRow);
	} else {
		// Cek apakah ini adalah item terakhir yang akan di-uncheck
		if (selectedWarehouses.length === 1) {
			// Jika item yang akan di-uncheck adalah satu-satunya, tampilkan peringatan menggunakan SweetAlert2
			Swal.fire({
				icon: "warning",
				title: "Gudang Tidak Boleh Kosong",
				text: "Setidaknya satu gudang harus dipilih!",
			});
			// Batalkan uncheck dengan mengatur kembali checkbox menjadi tercentang
			$(this).prop("checked", true);
			return;
		}

		$(row).removeClass("table-primary text-white");
		// Hapus data dari array selectedWarehouses
		selectedWarehouses = selectedWarehouses.filter(function (item) {
			return item.warehouseName !== warehouseName;
		});

		// Hapus baris dari tabel gudang yang dipilih
		let qtySelectedWarehouse = 0;
		$("#selected-warehouses-table tbody tr").each(function () {
			if ($(this).find("td:eq(0)").text() === warehouseName) {
				qtySelectedWarehouse =
					parseFloat($(this).find("td:eq(1) input.qty-input").val()) || 0;
				$(this).remove();
				return false;
			}
		});

		// Kembalikan stok di tabel "table-stock-item" ke nilai awal
		row.find("td:eq(3)").text(originalQty + qtySelectedWarehouse);
	}

	$("#close-btn-modal-list-gudang").hide();
	//
});
// xxx

//
// Tambahkan event listener untuk tombol minus setelah data ditambahkan
//
$(document).on("click", ".btn-minus", function () {
	//
	let warehouseId = $(this).data("warehouse-id");
	//
	let itemCode = $(this).data("item-code");

	// Menghitung jumlah item dengan kode yang sama
	let itemCount = $(`.btn-minus[data-item-code="${itemCode}"]`).length;

	// Cek apakah hanya satu item yang tersisa
	if (itemCount <= 1) {
		Swal.fire({
			icon: "warning",
			title: "Peringatan",
			text: "Data tidak dapat dihapus karena hanya tersisa satu untuk Item Code ini.",
		});
		return;
	}
	//
	$(this).closest("div.d-flex").remove();
	//
	$("#table-stock-item tbody tr").each(function () {
		if (
			Number($(this).find("input.check-item").val()) === Number(warehouseId)
		) {
			// ID yang ingin dihapus
			// Menghapus objek dengan warehouseId tertentu
			selectedWarehouses = selectedWarehouses.filter(
				(item) => Number(item.warehouseId) !== Number(warehouseId)
			);
		}
	});

	$("#selected-warehouses-table tbody tr").each(function () {
		if ($(this).data("warehouse-id") === warehouseId) {
			$(this).remove();
			return false;
		}
	});
});
// xxx BUGS
let tempQty = 0;
let isCorrected = false; // Flag untuk mendeteksi apakah input sudah dikoreksi
let lastQty = 0; // Menyimpan nilai qty sebelum diubah

$("#selected-warehouses-table").on("input", ".qty-input", function () {
	if (isCorrected) {
		isCorrected = false; // Reset flag setelah dikoreksi
		return; // Keluar jika input sudah dikoreksi sebelumnya
	}

	let row = $(this).closest("tr");
	let warehouseName = row.find("td:eq(0)").text();
	let newQty = parseFloat($(this).val()) || 0;
	let soSysId = $("#dtl-so-sysId").val();
	//
	// Temukan item yang sesuai di selectedWarehouses dan perbarui qty
	let selectedWarehouse = selectedWarehouses.find(function (item) {
		return item.warehouseName === warehouseName && item.soSysId === soSysId;
	});

	// console.log(selectedWarehouse);

	if (selectedWarehouse) {
		let originalQty = selectedWarehouse.originalQty;
		// Periksa apakah kuantitas baru melebihi stok asli
		// Debugging check1;
		// console.log(originalQty);
		if (newQty > originalQty) {
			Swal.fire({
				icon: "error",
				title: "Error",
				text: "Jumlah baru melebihi stok yang tersedia.",
				confirmButtonText: "OK",
			});

			// Reset input ke originalQty
			$(this).val(originalQty);
			newQty = originalQty; // Set newQty kembali ke stok asli
			// Simpan kuantitas terakhir
			lastQty = newQty;
			isCorrected = true; // Set flag agar tidak ada penggandaan
		}
		// Debugging check2;
		// console.log("Perhitungan: " + lastQty + " - " + newQty);
		// Kurangi stok di tabel "table-stock-item" hanya jika nilainya valid
		if (!isCorrected) {
			let difference = lastQty - newQty; // Hitung selisih stok yang berkurang
			selectedWarehouse.qty = newQty; // Set nilai kuantitas yang baru
			// // Debugging check3;
			// console.log("Selisih: " + difference);
			$("#table-stock-item tbody tr").each(function () {
				let stockRow = $(this);
				let stockWarehouseName = stockRow.find("td:eq(2)").text();

				if (stockWarehouseName === warehouseName) {
					// Update stok di tabel
					let updatedQty =
						parseFloat(stockRow.find("td:eq(3)").text()) + difference;
					stockRow.find("td:eq(3)").text(
						parseFloat(updatedQty)
							.toFixed(4)
							.replace(/\.?0+$/, "")
					);
					// console.log(updatedQty);
				}
			});
		} else {
			let difference = lastQty - newQty; // Hitung selisih stok yang berkurang
			selectedWarehouse.qty = newQty; // Set nilai kuantitas yang baru
			// Bugging3
			// console.log("Selisih: " + difference);
			$("#table-stock-item tbody tr").each(function () {
				let stockRow = $(this);
				let stockWarehouseName = stockRow.find("td:eq(2)").text();

				if (stockWarehouseName === warehouseName) {
					// Update stok di tabel
					let updatedQty = parseFloat(difference);
					stockRow.find("td:eq(3)").text(
						parseFloat(updatedQty)
							.toFixed(4)
							.replace(/\.?0+$/, "")
					);
					// console.log(updatedQty);
				}
			});
		}
	}

	// Simpan kuantitas terakhir
	lastQty = newQty;

	$("#close-btn-modal-list-gudang").hide();
});

// Event listener untuk focus dan click
$("#selected-warehouses-table").on("focus click", ".qty-input", function () {
	//
	//
	let row = $(this).closest("tr");
	let warehouseName = row.find("td:eq(0)").text();
	let soSysId = $("#dtl-so-sysId").val();

	// Temukan item yang sesuai di selectedWarehouses dan perbarui qty
	let selectedWarehouse = selectedWarehouses.find(function (item) {
		return item.warehouseName === warehouseName && item.soSysId === soSysId;
	});

	// Simpan kuantitas awal di tempQty sebelum diubah
	tempQty = selectedWarehouse ? selectedWarehouse.qty : 0;
	lastQty = tempQty; // Simpan nilai terakhir untuk perbandingan nanti

	// Tambahkan highlight pada tabel stok
	$("#table-stock-item tbody tr").each(function () {
		let stockRow = $(this);
		let stockWarehouseName = stockRow.find("td:eq(2)").text();

		if (stockWarehouseName === warehouseName) {
			stockRow.addClass("table-success text-white");

			setTimeout(function () {
				stockRow.removeClass("table-success text-white");
			}, 2000);
		}
	});
});

//

$("#add-warehouse").on("click", function () {
	if ($("#selected-warehouses-table tbody tr").length === 0) {
		// Jika tidak ada baris, tampilkan SweetAlert dan hentikan eksekusi
		Swal.fire({
			icon: "warning",
			title: "Tidak ada gudang yang dipilih!",
			text: "Silakan pilih minimal satu gudang.",
		});
		return; // Hentikan eksekusi jika tidak ada baris
	}

	let hasZeroQty = false;
	// let moreQty = false;
	let moreQtyOst = false;
	let itemCode = $("#dtl-item-code").text();
	let soSysId = $("#dtl-so-sysId").val();
	//
	let qtyOrderValidate = $("#qty-order-validate").text();
	let qtyOstValidate = $("#qty-ost-validate").text();
	//
	//
	$("#selected-warehouses-table tbody tr").each(function () {
		//
		let qty = parseFloat($(this).find("input.qty-input").val()) || 0;
		// Update qty dalam selectedWarehouses
		// Cari dan update qty dalam selectedWarehouses
		//
		// BUG xxx
		if (qty === 0) {
			hasZeroQty = true;
			return false; // Keluar dari each loop jika ditemukan qty yang 0
		}
		//
		// if (qty > qtyOrderValidate) {
		// 	moreQty = true;
		// 	return false; // Keluar dari each loop jika ditemukan qty yang 0
		// }
		//
		if (qty > qtyOstValidate) {
			moreQtyOst = true;
			return false; // Keluar dari each loop jika ditemukan qty yang 0
		}
	});
	//
	//
	if (hasZeroQty) {
		Swal.fire({
			icon: "error",
			title: "Error",
			text: "Isi QTY",
		});
		return;
	}
	//
	// if (moreQty) {
	// 	Swal.fire({
	// 		icon: "error",
	// 		title: "Error",
	// 		text: `Qty tidak boleh lebih dari item yang di order yaitu: ${qtyOrderValidate} item`,
	// 	});
	// 	return;
	// }
	//
	if (moreQtyOst) {
		Swal.fire({
			icon: "error",
			title: "Error",
			text: `Qty tidak boleh lebih dari Qty Outstanding yaitu: ${qtyOstValidate} item`,
		});
		return;
	}
	// Filter selectedWarehouses untuk hanya menyertakan item dengan itemCode yang sama
	let filteredWarehouses = selectedWarehouses.filter(function (item) {
		return (
			item.ItemCode === itemCode && Number(item.soSysId) === Number(soSysId)
		);
	});

	// Hapus data dengan itemCode yang sama dari selectedWarehouses

	// Ambil data baru yang akan ditambahkan
	let newData = filteredWarehouses.map((item) => ({
		sysId_dtl_so: $("#dtl-so-sysId").val(),
		Warehouse_Id: item.warehouseId,
		Warehouse_Code: item.warehouseCode,
		Item_Qty: item.qty,
		Item_Code: itemCode,
		Item_Name: item.warehouseName, // Ubah sesuai kebutuhan
		note: "Note 2", // Ubah sesuai kebutuhan
		Item_Color: "Blue", // Ubah sesuai kebutuhan
		Brand: "Brand 2", // Ubah sesuai kebutuhan
		Dimension_Info: "20x20", // Ubah sesuai kebutuhan
		Weight_Info: "2kg", // Ubah sesuai kebutuhan
	}));
	//
	// Temukan baris dengan data-sys-id yang sesuai
	let row = $(`tr[data-sys-id='${newData[0].sysId_dtl_so}']`);

	if (row.length) {
		// Sembunyikan tombol "Add" atau "Plus" di semua baris
		// Kosongkan kolom yang sesuai
		let columnToUpdate = row.find("td").eq(-3);
		columnToUpdate.empty();

		// letiabel untuk menyimpan konten baru
		let newContent = "";

		// Tambahkan data baru ke dalam kolom
		newData.forEach((item, index) => {
			newContent += `
                <div class="d-flex align-items-center my-1">
                    <button type="button" class="btn btn-outline-danger btn-sm mr-1 btn-minus" data-item-code="${
											item.Item_Code
										}" data-warehouse-id="${item.Warehouse_Id}">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input class="mr-1 form-control form-control-sm text-center" type="hidden" readonly name="wh_id[]" value="${
											item.sysId_dtl_so +
											"-" +
											item.Item_Code +
											"#" +
											item.Warehouse_Id +
											"=" +
											item.Item_Qty
										}">
                    <input class="mr-1 form-control form-control-sm text-center" type="text" readonly name="" value="${
											item.Warehouse_Code
										}">
                    <input class="ml-1 form-control form-control-sm text-center only-number" type="text" readonly name="stock_item[]" value="${
											item.Item_Qty
										}">
                </div>
            `;
		});

		// Tambahkan konten baru ke dalam kolom yang kosong
		columnToUpdate.append(newContent);
		let totalItemQty = newData.reduce(
			(total, item) => total + item.Item_Qty,
			0
		);
		row.find(".qty-shipped-input").val(totalItemQty);
		//
		let sysId_dtl_so = newData[0].sysId_dtl_so;
		qtyShippedValues[sysId_dtl_so] = totalItemQty;
	}
	$("#list-stock-item-modal").modal("hide");
});

//

function Init_Edit(sys_id) {
	// alert(sys_id);
	$.ajax({
		dataType: "json",
		type: "POST",
		url: $('meta[name="base_url"]').attr("content") + "Sales/ShippingIns/edit",
		data: {
			sys_id: sys_id,
		},
		success: function (response) {
			Swal.close();
			// console.table(response);
			if (response.code == 200) {
				form_state("EDIT");
				let data_hdr = response.data_hdr;
				let data_dtl = response.data_dtl;
				//
				//
				$("#so-sysId").val(data_hdr.SysId);
				// Isi data header
				$("#nomer-shipping").val(data_hdr.ShipInst_Number);
				$("#tanggal-shipping").val(
					moment(data_hdr.ShipInst_Date).format("DD MMMM YYYY")
				);
				//
				$("#select-customer").val(data_hdr.Account_ID).trigger("change");
				$("#alamat-customer-id").val(data_hdr.ShipToAddress_ID);
				$("#alamat-customer").val(data_hdr.Address);
				$("#tanggal-pengiriman").val(
					moment(data_hdr.ExpectedDeliveryDate).format("DD MMMM YYYY")
				);
				$("#port-of-loading").val(data_hdr.PortOfLoading);
				$("#place-of-delivery").val(data_hdr.PlaceOfDelivery);

				// Cek apakah Sailing ada di dropdown
				let sailingValue = data_hdr.Sailing;
				let isValueInDropdown =
					$("#sailing option[value='" + sailingValue + "']").length > 0;

				if (isValueInDropdown) {
					// Jika nilai sesuai dengan salah satu opsi di dropdown, pilih opsi tersebut
					$("#sailing").val(sailingValue).trigger("change");
					$("#other_transport").val(""); // Kosongkan input lainnya
				} else {
					// Jika tidak sesuai, pilih "Lainnya" dan isi inputan lain
					$("#sailing").val("other").trigger("change");
					$("#other_transport").val(sailingValue); // Isi dengan nilai dari data_hdr
				}
				$("#carrier").val(data_hdr.Carrier);
				$("#PEB-Number").val(data_hdr.PEB_Number);
				$("#PEB-Date").val(moment(data_hdr.PEB_Date).format("DD MMMM YYYY"));
				$("#PEB-Receiver").val(data_hdr.PEB_Receiver);
				$("#PEB-Country").val(data_hdr.PEB_Country).trigger("Indonesia");
				$("#PEB-Amount").val(parseFloat(data_hdr.PEB_Amount));
				$("#PEB-Volume").val(parseFloat(data_hdr.PEB_Volume));
				$("#PEB-Netto").val(parseFloat(data_hdr.PEB_Netto));
				$("#PEB-Merk").val(data_hdr.PEB_Netto);
				$("#PEB-PackageNumber").val(data_hdr.PEB_PackageNumber);
				//
				//
				let area = data_hdr.isExport == 0 ? "Domestic" : "OverSeas";
				$("#area").val(area);
				cekArea(area);
				//
				let sysIdDtlSoValues = [];
				//
				selectedSysIds.clear();
				$.each(data_dtl, function (index, item) {
					// console.log("Type of item.sysId_hdr_so:", typeof item.sysId_hdr_so);
					selectedSysIds.add(Number(item.sysId_hdr_so));
					// selectedSysIds.add(item.sysId_hdr_so);
					qtyShippedValues[item.sysId_dtl_so] = parseFloat(item.Qty_Shiped);
				});
				//
				// Isi data detail
				let tableBody = $("#detail-table tbody");
				// console.table(data_dtl);
				tableBody.empty(); // Kosongkan isi tabel terlebih dahulu
				$.each(data_dtl, function (index, item) {
					let qtyShippedValue = parseFloat(item.Qty_Shiped) || ""; // Atur nilai qty shipped
					//
					let warehouseDetails = "";

					if (item.Warehouse_Qty && typeof item.Warehouse_Qty === "object") {
						$.each(item.Warehouse_Qty, function (warehouseId, details) {
							warehouseDetails += `
									<div class="d-flex align-items-center my-1">
										<button type="button" class="btn btn-outline-danger btn-sm mr-1 btn-minus"
										 data-item-code="${item.Item_Code}" data-warehouse-id="${warehouseId}">
											<i class="fas fa-minus"></i>
										</button>

										<input class="mr-1 form-control form-control-sm text-center" type="hidden" readonly name="wh_id[]" value="${
											item.sysId_dtl_so +
											"-" +
											item.Item_Code +
											"#" +
											warehouseId +
											"=" +
											details.qty
										}">
										<input class="mr-1 form-control form-control-sm text-center" type="text" readonly name="warehouse_code[]" value="${
											details.warehouse_code
										}">
										<input class="ml-1 form-control form-control-sm text-center only-number" type="text" readonly name="stock_item[]" value="${
											details.qty
										}">
									</div>
								`;
							//

							selectedWarehouses.push({
								soSysIdHdr: item.sysId_hdr_so,
								soSysId: item.sysId_dtl_so,
								ItemCode: item.Item_Code,
								warehouseId: warehouseId,
								warehouseCode: details.warehouse_code,
								warehouseName: details.warehouse_name,
								originalQty: details.qty,
								qty: details.qty,
							});
							//
						});
					}
					// Qty_ost_so;
					// Push the sysId_dtl_so value into the array
					sysIdDtlSoValues.push(item.sysId_dtl_shp);
					//
					tableBody.append(`
				        <tr data-sys-id="${item.sysId_dtl_so}">
				            <td class="text-center vertical-align-middle">
				                ${item.SO_Number}
								<input type="hidden" name="sysId_dtl_so[]" value="${item.sysId_dtl_so}">
				                <input type="hidden" name="so_number[]" value="${
													item.SO_Number
												}">
				            </td>
				            <td class="text-center vertical-align-middle">
				                ${item.Item_Code}
				                <input type="hidden" name="item_code[]" value="${
													item.Item_Code
												}">
				            </td>
				            <td class="text-center vertical-align-middle">
				                ${item.Item_Name}
				                <input type="hidden" name="item_name[]" value="${
													item.Item_Name
												}">
								<input type="hidden" name="amount[]" value="${item.Amount}">
				            </td>
				            <td class="text-center vertical-align-middle">${item.Note}</td>
				            <td class="text-center vertical-align-middle">${
											item.Item_Color
										}</td>
				            <td class="text-center vertical-align-middle">${item.Brand}</td>
				            <td class="text-center vertical-align-middle">
				                ${item.Dimension_Info}
				            	<input type="hidden" name="dimension[]" value="${
												item.Dimension_Info
											}">
				            </td>
				            <td class="text-center vertical-align-middle">${
											item.Weight_Info
										}</td>
				            <td class="text-center vertical-align-middle">
				                ${item.Qty_order}
				                <input type="hidden" name="qty[]" value="${parseFloat(
													item.Qty_order
												)}">
				            </td>
							<td class="text-center vertical-align-middle qty-ost" data-sys-id="${
								item.sysId_dtl_so
							}">
								    ${parseFloat(item.Qty_ost_so)}
							</td>
							<td class="vertical-align-middle">
								<input readonly type="number" class="qty-shipped-input form-control form-control-sm text-center only-number" name="qty_shipped[]" data-sys-id="${
									item.sysId_dtl_so
								}" data-qty-order="${item.Qty_order}" data-qty-shp="${item.Tot_qty_shp}" data-qty-ost="${item.Qty_ost_so}" value="${qtyShippedValue}">
							</td>
				            <td class="text-center vertical-align-middle text-uppercase">
				                ${item.Uom}
				                <input type="hidden" name="uom[]" value="${item.Unit_Type_ID}">
				            </td>
							<td class="text-center vertical-align-middle">
								<input type="text" class="form-control form-control-sm text-center" name="Secondary_Qty[]" value="${
									parseFloat(item.Secondary_Qty) || ""
								}" placeholder="0">
							</td>
				           <td class="text-center vertical-align-middle">
													<select class="form-control form-control-sm text-center" name="Secondary_Uom[]">
														<option value="" disabled selected>None</option>
													</select>
												</td>
							  <td class="text-center vertical-align-middle" style="border-right: none;">
								${warehouseDetails}
							</td>
							<td class="text-center vertical-align-middle" style="border-left: none;">
													<button
													    data-hdr-so="${item.sysId_hdr_so}" 
													    data-dtl-so="${item.sysId_dtl_so}"
													    data-item-code="${item.Item_Code}"
														data-item-name="${item.Item_Name}"
														data-note="${item.note}"
														data-color="${item.Item_Color}"
														data-brand="${item.Brand}"
														data-dimension="${item.Dimension_Info}"
														data-weight="${item.Weight_Info}"
														data-qty-order="${item.Qty_order}"
														data-qty-ost="${parseFloat(item.Qty_ost_so)}"
														type="button"
														class="btn btn-sm btn-success ml-1 btn-add-wh">
														<i class="fas fa-plus"></i>
													</button>
							</td>"
				            <td class="text-center vertical-align-middle">
				           <button type="button" class="btn btn-danger btn-sm delete-row" data-sys-id="${
											item.sysId_dtl_so
										}">
														<i class="fas fa-trash-alt"></i>
													</button>	
				            </td>
				        </tr>
				    `);
					//
					// After the loop, set the value of the input element
					$("#si-dtl-sysId").val(sysIdDtlSoValues.join(","));
					//
					// Panggil fungsi populateUnitSelect untuk mengisi dropdown

					// Simpan nilai ke objek selectedUnits
					// Simpan nilai ke objek selectedUnits
					selectedUnits[item.sysId_dtl_so] = {
						unit: item.Secondary_Uom,
						hdrSo: item.sysId_hdr_so,
						qty: parseFloat(item.Secondary_Qty) || "", // Simpan juga qty
					};

					// Cari elemen select yang terakhir ditambahkan
					let unitSelect = tableBody
						.find('select[name="Secondary_Uom[]"]')
						.last();

					populateUnitSelect(
						unitSelect,
						unitTypeOptions,
						item.sysId_dtl_so,
						item.sysId_hdr_so
					);

					// Cari elemen input untuk Secondary_Qty yang terakhir ditambahkan
					let qtyInput = tableBody.find('input[name="Secondary_Qty[]"]').last();

					// Panggil fungsi populateSecondaryQty untuk mengisi input qty
					populateSecondaryQty(qtyInput, item.sysId_dtl_so, item.sysId_hdr_so);
				});
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
//
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
//
$("#sailing").on("change", function () {
	let selectedValue = $(this).val();
	$("#carrier").val("");
	// Aktifkan kembali input carrier
	if (selectedValue === "mobil") {
		$("#carrierLabel").text("Nomor Polisi Kendaraan");
		$("#carrier").attr("placeholder", "Masukan NO Pol Kendaraan");
		$("#other_transport").val("");
	} else if (selectedValue === "kapal laut") {
		$("#carrierLabel").text("Nomor Identifikasi Kapal");
		$("#carrier").attr("placeholder", "Masukan Nomor Identifikasi Kapal");
		$("#other_transport").val("");
	} else if (selectedValue === "other") {
		$("#carrierLabel").text("Nomor Identifikasi Pengangkut");
		$("#carrier").attr("placeholder", "Masukan Nomor Identifikasi Pengangkut");
	}
	// Tampilkan input tambahan untuk opsi "Lainnya" jika dipilih
	$("#otherInputDiv").toggle(selectedValue === "other");
});

//
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
					"Sales/ShippingIns/cancel_status",
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

//
$(document).on("click", "#back", function () {
	form_state("BACK");
});
//
function showAlert(title, text, icon) {
	Swal.fire({
		icon: icon,
		title: title,
		text: text,
		footer:
			'<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
	});
}
