// Function ini dipanggil setiap kali user melakukan tindakan seperti ADD dan EDIT data
//

//
form_state("LOAD");
//
let no_shipping_checkd = "";
let for_edt = false;
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
			// for_edt = false;
			no_shipping_checkd = "";
			$("#main-form select").val("").trigger("change");
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
			$("#section-before-chose-si").removeClass("d-none");
			showBackInDown("#section-before-chose-si");
			//
			$("#section-after-chose-si").removeClass("d-none");
			showBackInDown("#section-after-chose-si");
			$("#btn-submit").removeAttr("disabled");
			//
			for_edt = true;

			$('input[name="state"]').val("EDIT");
			$(".list-data").hide("slow");
			$(".add-data").show("slow");
			$("#select-customer").attr("disabled", true);
			break;
		case "BACK":
			$(".list-data").show("slow");
			$(".add-data").hide("slow");
			break;
	}
}
//

// xxx
$("#select-customer").on("change", function () {
	let alamatCustomer = $("#alamat-customer");
	// Dapatkan nilai customer yang dipilih
	let selectedCustomerId = $(this).val();
	let state = $("#state").val();

	// Cek apakah nilai yang dipilih kosong
	if (!selectedCustomerId) {
		alamatCustomer.text("");
		reset_input();
		// Jika kosong, tampilkan peringatan atau lakukan aksi lain, lalu keluar dari fungsi
		return; // Hentikan eksekusi jika tidak ada customer yang dipilih
	}
	$("#section-before-chose-si").removeClass("d-none");
	showBackInDown("#section-before-chose-si");
	// Jika nilai valid, teruskan dengan pengaturan nilai dan AJAX
	$("#customer-id").val(selectedCustomerId);

	// Lanjutkan dengan AJAX untuk mengambil data shipping berdasarkan customer ID
	$.ajax({
		type: "POST",
		url: $('meta[name="base_url"]').attr("content") +
			"Sales/SalesInv/DT_listdata_shipping",
		data: {
			Account_ID: selectedCustomerId,
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

			let tableBody = $("#shipping-table tbody");
			tableBody.empty();

			let allHasInvoice = true; // Variabel untuk memeriksa apakah semua item sudah memiliki Invoice_Number

			$.each(response.shipping, function (index, item) {
				// Menampilkan alamat pelanggan, jika tidak ada alamat, tampilkan "Alamat tidak tersedia"
				alamatCustomer.text(item.ShipToAddress || "Alamat tidak tersedia");

				// Jika state === "edit", dan ShipInst_Number tidak sama, lewati item ini
				if (
					state === "EDIT" &&
					item.ShipInst_Number.toString() !== no_shipping_checkd.toString()
				) {
					return true; // Melanjutkan ke item berikutnya
				}

				if (item.Invoice_Number) {
					return; // Skip ke item berikutnya jika sudah ada Invoice_Number
				} else {
					allHasInvoice = false; // Jika ada yang tidak memiliki Invoice_Number, ubah variabel
				}

				// Buat variabel untuk status jika SI_Number ada dan invoice belum di-close
				let isInvoice = item.SI_Number ? true : false;

				// Buat baris tabel dengan radio button dan icon copy untuk menyalin ShipInst_Number
				let row = `<tr class="${
					item.ShipInst_Number.toString() === no_shipping_checkd.toString()
						? "bg-primary" // Warna biru jika dipilih
						: ""
				}">
				<td class="text-center">
					<input type="radio" name="select-item" class="select-item vertical-align-middle m-0 ${
						item.ShipInst_Number === no_shipping_checkd
							? "bg-primary"
							: isInvoice
							? "bg-warning"
							: ""
					}" value="${item.ShipInst_Number}" ${
					item.ShipInst_Number === no_shipping_checkd
						? "checked"
						: state === "edit"
						? "disabled"
						: isInvoice
						? "disabled" // Disable radio button jika sudah ada di invoice dan belum di-close
						: ""
				}>
					</td>
					<td class="text-center vertical-align-middle">${
						item.ShipInst_Number
					} <i class="fa fa-copy copy-icon" style="cursor: pointer;" data-shipinst-number="${
					item.ShipInst_Number
				}"></i></td>
					<td class="text-center vertical-align-middle">
						 ${
								state === "ADD"
									? isInvoice
										? `<span class="badge bg-warning text-dark">Sudah memiliki invoice (Menunggu persetujuan)</span>`
										: '<span class="badge bg-secondary">Belum memiliki invoice</span>'
									: '<span class="badge bg-danger">Data tidak dapat diedit</span>' // Jika state bukan "ADD"
							}
					</td>
				</tr>`;

				// Tambahkan row ke tabel
				tableBody.append(row);
			});

			if (allHasInvoice) {
				Swal.fire({
					icon: "error",
					title: "Opsss!",
					text: "Customer yang dipilih harus sudah mempunyai Shipping yang telah terverifikasi.",
				});
				alamatCustomer.text(""); // Kosongkan alamat
				reset_input(); // Fungsi untuk membersihkan input jika ada
				return; // Hentikan eksekusi jika respons tidak valid
			}
		},
		error: function (xhr, status, error) {
			console.error("AJAX error:", status, error);
		},
	});
});

//
// Fungsi untuk menyalin ShipInst_Number saat ikon copy diklik
function copyToClipboard(shipInstNumber) {
	// Membuat elemen input sementara untuk menyalin teks
	const tempInput = document.createElement("input");
	tempInput.value = shipInstNumber;
	document.body.appendChild(tempInput);
	tempInput.select();
	document.execCommand("copy");
	document.body.removeChild(tempInput);

	// Menampilkan alert atau pesan konfirmasi (optional)
	Swal.fire({
		icon: "success",
		title: "Tersalin!",
		text: `Nomor Shipping: ${shipInstNumber} berhasil disalin ke clipboard.`,
		timer: 1500,
		showConfirmButton: false,
	});
}

// Event listener untuk semua elemen dengan kelas .copy-icon
$(document).on("click", ".copy-icon", function () {
	const shipInstNumber = $(this).data("shipinst-number");
	copyToClipboard(shipInstNumber);
});
// Fungsi untuk menampilkan dengan animasi backInDown
function showBackInDown(element) {
	$(element)
		.removeClass("animate__backOutUp")
		.addClass("animate__backInDown")
		.show();
}

// Fungsi untuk menyembunyikan dengan animasi backOutUp
function hideBackOutUp(element, callback) {
	$(element)
		.removeClass("animate__backInDown")
		.addClass("animate__backOutUp")
		.delay(600)
		.hide(0, callback);
}

// Event handler ketika ada perubahan pada radio button
$(document).on("change", ".select-item", function () {
	let selectedShipInstNumber = $(this).val();

	// Sembunyikan elemen dengan animasi backOutUp terlebih dahulu, baru panggil AJAX setelah hide selesai
	hideBackOutUp("#section-after-chose-si", function () {
		// Setelah elemen disembunyikan, panggil AJAX untuk mengganti konten
		initialFormInput(selectedShipInstNumber);
	});

	// Hapus class bg-primary dari semua baris
	$("#shipping-table tbody tr").removeClass("bg-primary");

	// Tambahkan class bg-primary ke baris yang dipilih
	$(this).closest("tr").addClass("bg-primary");
});

// Fungsi untuk inisialisasi data form dari AJAX
function initialFormInput(selectedShipInstNumber) {
	$.ajax({
		type: "POST",
		url: $('meta[name="base_url"]').attr("content") +
			"Sales/SalesInv/DT_sales_shipping",
		data: {
			SINumber: selectedShipInstNumber,
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
			Swal.close(); // Menutup SweetAlert setelah proses berhasil
			if (response.code === 200 && response.data.length > 0) {
				let emptyMsg = "Khusus untuk transaksi ekspor";
				// Tampilkan elemen dengan animasi backInDown setelah data berhasil diambil
				$("#section-after-chose-si").removeClass("d-none");
				// xxx
				showBackInDown("#section-after-chose-si");
				$("#btn-submit").removeAttr("disabled");
				// Mengisi form dengan data yang diterima
				const shipment = response.data[0];
				const details = shipment.details || [];
				// console.table(details);
				$("#SI-Number").val(shipment.header.ShipInst_Number);
				//
				$("#NotifeParty")
					.val(shipment.header.NotifeParty || "")
					.attr("placeholder", emptyMsg);
				$("#NotifePartyAddress")
					.val(shipment.header.NotifePartyAddress || "")
					.attr("placeholder", emptyMsg);
				$("#port-of-loading")
					.val(shipment.header.PortOfLoading || "")
					.attr("placeholder", emptyMsg);
				$("#place-of-delivery")
					.val(shipment.header.PlaceOfDelivery || "")
					.attr("placeholder", emptyMsg);
				$("#LCNo")
					.val(shipment.header.LCNo || "")
					.attr("placeholder", emptyMsg);
				$("#LCBank")
					.val(shipment.header.LCBank || "")
					.attr("placeholder", emptyMsg);
				$("#LCDate")
					.val(
						shipment.header.LCDate ?
						moment(shipment.header.LCDate).format("DD MMMM YYYY") :
						""
					)
					.attr("placeholder", emptyMsg);
				$("#carrier")
					.val(shipment.header.Carrier || "")
					.attr("placeholder", emptyMsg);
				$("#sailing")
					.val(shipment.header.Sailing || "")
					.attr("placeholder", emptyMsg);
				$("#ShippingMarks")
					.val(shipment.header.ShippingMarks || "")
					.attr("placeholder", emptyMsg);

				// Kosongkan tabel dan tambahkan data detail
				let tableBody = $("#table-detail-item tbody");
				tableBody.empty(); // Kosongkan tabel sebelum menambahkan data
				$("#discount-percentage").val(
					formatIdrAccounting(shipment.header.Discount_Persen) || "0"
				);

				let soNumbersSet = new Set();
				$.each(details, function (index, item) {
					if (item.SO_Number) {
						soNumbersSet.add(item.SO_Number);
					}
				});
				appendTableRows(details);

				let soNumbersString = Array.from(soNumbersSet).join(",");
				$("#HDR-SO-Number").val(soNumbersString);

				calculateTotalAmount();
				if (response.total_tax) {
					$("#total_tax").val(formatIdrAccounting(response.total_tax));
				}

				if (details.length === 0) {
					$("#no_data_item").show();
				} else {
					$("#no_data_item").hide();
				}
			} else {
				console.log("No data found");
			}
		},
		error: function (xhr, status, error) {
			console.error("AJAX error:", status, error);
		},
	});
}

//
$("#main-form").on("submit", function (event) {
	event.preventDefault();
	if (validateForm()) {
		let formData = $(this).serialize();
		$.ajax({
			url: $('meta[name="base_url"]').attr("content") + "Sales/SalesInv/store",
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
function showCancelReasonModal(invoiceId) {
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
			Fn_Cancel_Status(invoiceId, result.value);
		}
	});
}
//
function Fn_Cancel_Status(invoiceId, reason) {
	$.ajax({
		url: $('meta[name="base_url"]').attr("content") +
			"Sales/SalesInv/cancel_status",
		type: "post",
		dataType: "json",
		data: {
			invoice_Id: invoiceId,
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
			// alert(response);
			if (response.code == 200) {
				Swal.fire({
					icon: "success",
					title: "Success!",
					text: response.msg,
					confirmButtonColor: "#3085d6",
					confirmButtonText: "Yes, Confirm!",
				});
				$("#DataTable").DataTable().ajax.reload(null, false);
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
				xhr.responseJSON && xhr.responseJSON.message ?
				xhr.responseJSON.message :
				xhr.responseText ?
				xhr.responseText :
				"Terjadi kesalahan: " + error;
			Swal.fire({
				icon: "error",
				title: "Error!",
				html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
			});
		},
	});
}
//
function reloadData() {
	let selectedInvoices = {}; // Object to store selected Invoices

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
			url: $('meta[name="base_url"]').attr("content") +
				"Sales/SalesInv/DT_listdata",
			dataType: "json",
			type: "POST",
		},
		columns: [{
				data: "Invoice_Number",
				createdCell: function (td) {
					$(td).addClass("text-center align-middle");
				},
			},
			{
				data: "Invoice_Date",
				render: function (data) {
					return data.substring(0, 10);
				},
				createdCell: function (td) {
					$(td).addClass("text-center align-middle");
				},
			},
			{
				data: "SI_Number",
				createdCell: function (td) {
					$(td).addClass("text-center align-middle");
				},
			},
			{
				data: "Due_Date",
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
			{
				data: "Invoice_Status",
				createdCell: function (td) {
					$(td).addClass("text-center align-middle");
				},
			},
			{
				data: "Approve",
				render: function (data) {
					return data == 1 ?
						'<div class="d-flex justify-content-center"><i class="fas fa-check text-success"></i></div>' :
						data == 2 ?
						'<div class="d-flex justify-content-center"><i class="fas fa-times text-danger"></i></div>' :
						'<div class="d-flex justify-content-center"><i class="fas fa-question text-warning"></i></div>';
				},
				createdCell: function (td) {
					$(td).addClass("text-center align-middle");
				},
			},
			{
				data: "Is_Cancel",
				render: function (data) {
					return data == 1 ?
						'<div class="d-flex justify-content-center"><span class="badge bg-success">Canceled</span></div>' :
						'<div class="d-flex justify-content-center"><span class="badge bg-warning">Open</span></div>';
				},
				createdCell: function (td) {
					$(td).addClass("text-center align-middle");
				},
			},
		],
		order: [
			[0, "desc"]
		],
		columnDefs: [{
			targets: "_all",
		}, ],
		autoWidth: false,
		preDrawCallback: function () {
			$("#DataTable tbody td").addClass("blurry");
		},
		language: {
			processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" class="loading-text"></span> ',
		},
		initComplete: function () {
			$("#DataTable tbody").on("click", "tr", function () {
				const rowData = dataTable.row(this).data();

				if (rowData && rowData.Invoice_ID) {
					const invoiceID = rowData.Invoice_ID;

					if ($(this).hasClass("table-primary")) {
						$(this).removeClass("table-primary text-white");
						delete selectedInvoices[invoiceID];
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

						selectedInvoices = {};

						if ($(this).hasClass("table-warning")) {
							$(this)
								.removeClass("table-warning")
								.addClass("table-primary text-white table-warning-selected");
						} else {
							$(this).addClass("table-primary text-white");
						}

						selectedInvoices[invoiceID] = rowData;
					}
				} else {}
			});
		},
		drawCallback: function () {
			// Tambahkan efek blur sementara
			$("#DataTable tbody td").addClass("blurry");
			setTimeout(function () {
				$("#DataTable tbody td").removeClass("blurry");
			}, 100); // Beri delay untuk memastikan rendering selesai

			// Iterasi setiap baris di tabel
			$("#DataTable tbody tr").each(function () {
				const rowData = dataTable.row(this).data();

				if (rowData) {
					// Terapkan background kuning untuk baris yang memiliki Is_Cancel == 1
					if (rowData.Is_Cancel == 1) {
						$(this).addClass("table-warning");
					}

					// Jika Invoice_ID ditemukan dalam selectedInvoices, tambahkan kelas "table-primary"
					if (selectedInvoices[rowData.Invoice_ID]) {
						$(this)
							.removeClass("table-warning") // Pastikan untuk menghapus warna peringatan
							.addClass("table-primary text-white table-warning-selected");
					}
				} else {
					// Jika rowData tidak ditemukan, hapus Invoice_ID yang tidak sesuai dari selectedInvoices
					for (const invoiceId in selectedInvoices) {
						if (selectedInvoices.hasOwnProperty(invoiceId) && !rowData) {
							delete selectedInvoices[invoiceId];
						}
					}
				}
			});

			// Inisialisasi tooltip
			$('[data-toggle="tooltip"]').tooltip();
		},
		buttons: [{
				text: `<i class="fas fa-plus fs-3"></i> ADD Sales INV`,
				className: "bg-primary",
				action: function () {
					form_state("ADD");
				},
			},
			{
				text: `<i class="fas fa-search"></i> View Detail`,
				className: "btn btn-info",
				action: function () {
					if (Object.keys(selectedInvoices).length === 0) {
						Swal.fire({
							icon: "warning",
							title: "Ooppss...",
							text: "Silahkan pilih data untuk melihat detail!",
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
						});
					} else {
						let selectedRow = Object.values(selectedInvoices)[0];
						let invoiceID = selectedRow.Invoice_ID;
						let url =
							$('meta[name="base_url"]').attr("content") +
							"Sales/SalesInv/detail/" +
							invoiceID;
						window.location.href = url;
					}
				},
			},
			{
				text: `<i class="fas fa-edit fs-3"></i> Edit`,
				className: "btn btn-warning",
				action: function () {
					if (Object.keys(selectedInvoices).length === 0) {
						Swal.fire({
							icon: "warning",
							title: "Ooppss...",
							text: "Silahkan pilih data untuk melihat detail!",
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
						});
					} else {
						let selectedRow = Object.values(selectedInvoices)[0];
						let invoiceID = selectedRow.Invoice_ID;
						let Approve = selectedRow.Approve;
						let isCancel = selectedRow.Is_Cancel;
						let message =
							Approve == 1 ?
							"Data sudah diapprove" :
							Approve == 2 ?
							"Data sudah diriject" :
							isCancel == 1 ?
							"Data sudah dicancel" :
							null;

						if (message) {
							Swal.fire({
								icon: "info",
								title: "Informasi",
								text: message,
								footer: '<a href="javascript:void(0)" class="text-info">Informasi System</a>',
							});
							return;
						}
						Init_Edit(invoiceID);
					}
				},
			},
			{
				text: `<i class="fas fa-print fs-3"></i> Print sales inv`,
				className: "btn bg-gradient-success",
				action: function () {
					if (Object.keys(selectedInvoices).length === 0) {
						Swal.fire({
							icon: "warning",
							title: "Ooppss...",
							text: "Silahkan pilih data terlebih dahulu untuk mencetak report!",
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
						});
					} else {
						let selectedRow = Object.values(selectedInvoices)[0];
						let Invoice_ID = selectedRow.Invoice_ID;
						let Approve = selectedRow.Approve;
						let isCancel = selectedRow.Is_Cancel;

						// Check approval and close status
						if (isCancel == 1 || Approve == 2 || Approve == 0) {
							Swal.fire({
								icon: "warning",
								title: "Ooppss...",
								text: "Report cetak hanya bisa di tampilkan pada data yang telah legitimate (tidak close dan sudah approve)!",
								footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
							});
						} else {
							// Open PDF report in a new tab
							window.open(
								$('meta[name="base_url"]').attr("content") +
								"Sales/SalesInv/export_pdf_so_inv/" +
								Invoice_ID,
								"_blank"
							);
							//
						}
					}
				},
			},
			{
				text: `<i class="fas fa-print fs-3"></i> Print Comm Inv`,
				className: "btn bg-gradient-success",
				action: function () {
					if (Object.keys(selectedInvoices).length === 0) {
						Swal.fire({
							icon: "warning",
							title: "Ooppss...",
							text: "Silahkan pilih data terlebih dahulu untuk mencetak report!",
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
						});
					} else {
						let selectedRow = Object.values(selectedInvoices)[0];
						let Invoice_Number = selectedRow.Invoice_Number + "." + "1";
						let Approve = selectedRow.Approve;
						let isCancel = selectedRow.Is_Cancel;

						// Check approval and close status
						if (isCancel == 1 || Approve == 2 || Approve == 0) {
							Swal.fire({
								icon: "warning",
								title: "Ooppss...",
								text: "Report cetak hanya bisa di tampilkan pada data yang telah legitimate (tidak close dan sudah approve)!",
								footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
							});
						} else {
							window.open(
								$('meta[name="base_url"]').attr("content") +
								"Sales/ShippingIns/export_pdf_si/" +
								Invoice_Number,
								"_blank"
							);
							// Open PDF report in a new tab
							window.open(
								$('meta[name="base_url"]').attr("content") +
								"Sales/SalesInv/export_pdf_so_inv/" +
								Invoice_ID,
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
					if (Object.keys(selectedInvoices).length === 0) {
						Swal.fire({
							icon: "warning",
							title: "Ooppss...",
							text: "Silahkan pilih data untuk merubah status!",
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>',
						});
					} else {
						let selectedRow = Object.values(selectedInvoices)[0];
						let invoiceID = selectedRow.Invoice_ID;
						let isCancel = selectedRow.Is_Cancel;

						if (isCancel == 1) {
							Swal.fire({
								icon: "info",
								title: "Informasi",
								text: "Data sudah di cancel.",
								footer: '<a href="javascript:void(0)" class="text-info">Informasi System</a>',
							});
							return;
						}
						showCancelReasonModal(invoiceID);
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
// xx
function Init_Edit(invoice_ID) {
	$.ajax({
		dataType: "json",
		type: "POST",
		url: $('meta[name="base_url"]').attr("content") + "Sales/SalesInv/edit",
		data: {
			invoice_ID: invoice_ID,
		},
		success: function (response) {
			Swal.close();
			if (response.code == 200) {
				form_state("EDIT");
				// Populate header data
				$("#invoice-id-edit").val(response.data_hdr.Invoice_ID);
				$("#invoice-number-edit").val(response.data_hdr.Invoice_Number);
				no_shipping_checkd = response.data_hdr.SI_Number;
				$("#select-customer")
					.val(response.data_hdr.Account_ID)
					.trigger("change");
				$("#SI-Number").val(response.data_hdr.SI_Number);
				$("#HDR-SO-Number").val(response.data_hdr.SO_Number);
				$("#nomer-shipping").val(response.data_hdr.Invoice_Number);
				$("#NotifeParty").val(response.data_hdr.NotifeParty);
				$("#NotifePartyAddress").val(response.data_hdr.NotifePartyAddress);
				$("#port-of-loading").val(response.data_hdr.PortOfLoading);
				$("#place-of-delivery").val(response.data_hdr.PlaceOfDelivery);
				$("#invoice-date").val(
					moment(response.data_hdr.Invoice_Date).format("DD MMMM YYYY")
				);
				$("#due-date").val(
					moment(response.data_hdr.Due_Date).format("DD MMMM YYYY")
				);
				$("#tax-date").val(
					moment(response.data_hdr.Tax_Date).format("DD MMMM YYYY")
				);
				$("#taxdocnumpph").val(response.data_hdr.TaxDocNumPPh);
				$("#notes").val(response.data_hdr.Notes);
				$("#invoice-print-date").val(
					moment(response.data_hdr.InvoicePrintDate).format("DD MMMM YYYY")
				);
				$("#price-type").val(response.data_hdr.PriceType).trigger("change");
				$("#LCNo").val(response.data_hdr.LC_Number);
				$("#LCDate").val(
					response.data_hdr.LC_Date ?
					moment(response.data_hdr.LC_Date).format("DD MMMM YYYY") :
					""
				);
				$("#LCBank").val(response.data_hdr.LC_Bank);
				$("#carrier").val(response.data_hdr.Carrier);
				$("#sailing").val(response.data_hdr.Sailing);
				$("#ShippingMarks").val(response.data_hdr.ShippingMarks);
				$("#discount-percentage").val(
					currencyFormat(response.data_hdr.TransactionDiscountPresentase)
				);

				// Discount;

				// Mapping details to correct format
				let mappedDetails = response.data_dtl.map((item) => ({
					ShipInst_Number: item.Invoice_Number,
					SO_Number: item.SO_Number,
					Item_Code: item.Item_Code,
					Item_Name: item.Item_Description,
					Item_Color: item.Item_Color,
					Brand: item.Brand,
					Qty_Invoiced: item.Qty_Invoiced,
					Uom: item.Uom, // Adjust as necessary
					Item_Price: item.UnitPrice,
					Discount: item.Disc_percentage,
					Final_Discount: item.Disc_Value,
					Final_Amount: item.TotalPrice - item.Disc_Value,
					Tax1_Id: item.Tax_Code1,
					Tax1_Code: item.Tax_Code1,
					Tax2_Id: item.Tax_Code2,
					Tax2_Code: item.Tax_Code2,
					Dimension: item.Product_Size,
					isFreeItem: item.isFreeItem,
					Value_Tax_1: item.Tax_Amount1,
					Value_Tax_2: item.Tax_Amount2,
				}));

				// console.table(mappedDetails);
				// Use the mapped details to append rows
				appendTableRows(mappedDetails);

				$("#total_tax").val(formatIdrAccounting(response.data_hdr.Tax_Amount));
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
			var statusCode = xhr.status;
			var errorMessage =
				xhr.responseJSON && xhr.responseJSON.message ?
				xhr.responseJSON.message :
				xhr.responseText ?
				xhr.responseText :
				"Terjadi kesalahan: " + error;
			Swal.fire({
				icon: "error",
				title: "Error!",
				html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
			});
		},
	});
}

//
function validateForm() {
	var isValid = true;
	var errorMessage = " harus diisi.";

	function showErrorMessage(element, message) {
		var errorElement = $('<div class="invalid-feedback"></div>').text(message);
		$(element).addClass("is-invalid");
		$(element).parent().append(errorElement);
	}

	function clearErrorMessages() {
		$(".invalid-feedback").remove();
		$(".form-control").removeClass("is-invalid");
	}

	clearErrorMessages();

	// Validate Nomor Invoice (Nomor Shipping)
	// var nomorShipping = $("#nomer-shipping");
	// if (nomorShipping.val().trim() === "") {
	// 	isValid = false;
	// 	showErrorMessage(nomorShipping, "Nomor Invoice" + errorMessage);
	// }

	// Validate Nama Customer
	var selectCustomer = $("#select-customer");
	if (selectCustomer.val().trim() === "") {
		isValid = false;
		showErrorMessage(selectCustomer, "Nama Customer" + errorMessage);
	}

	// Validate Alamat Customer
	var alamatCustomer = $("#alamat-customer");
	if (alamatCustomer.val().trim() === "") {
		isValid = false;
		showErrorMessage(alamatCustomer, "Alamat Customer" + errorMessage);
	}

	// Validate Tanggal Faktur
	var invoiceDate = $("#invoice-date");
	if (invoiceDate.val().trim() === "") {
		isValid = false;
		showErrorMessage(invoiceDate, "Tanggal Faktur" + errorMessage);
	}

	// Validate Tanggal Jatuh Tempo
	var dueDate = $("#due-date");
	if (dueDate.val().trim() === "") {
		isValid = false;
		showErrorMessage(dueDate, "Tanggal Jatuh Tempo" + errorMessage);
	}

	// Validate Tanggal Pajak
	var taxDate = $("#tax-date");
	if (taxDate.val().trim() === "") {
		isValid = false;
		showErrorMessage(taxDate, "Tanggal Pajak" + errorMessage);
	}

	// Validate Nomor Dokumen Pajak PPh
	// var taxDocNumPph = $("#taxdocnumpph");
	// if (taxDocNumPph.val().trim() === "") {
	// 	isValid = false;
	// 	showErrorMessage(taxDocNumPph, "Nomor Dokumen Pajak PPh" + errorMessage);
	// }

	// // Validate Catatan
	// var notes = $("#notes");
	// if (notes.val().trim() === "") {
	// 	isValid = false;
	// 	showErrorMessage(notes, "Catatan" + errorMessage);
	// }

	// Validate Tanggal Cetak Faktur
	var invoicePrintDate = $("#invoice-print-date");
	if (invoicePrintDate.val().trim() === "") {
		isValid = false;
		showErrorMessage(invoicePrintDate, "Tanggal Cetak Faktur" + errorMessage);
	}

	// Validate Tipe Harga
	var priceType = $("#price-type");
	if (priceType.val().trim() === "") {
		isValid = false;
		showErrorMessage(priceType, "Tipe Harga" + errorMessage);
	}

	var rate = $("#rate-currency");
	if (rate.val().trim() === "") {
		isValid = false;
		showErrorMessage(priceType, "Rate Currency" + errorMessage);
	}
	// Validate if there is at least one item in the detail table

	// Validate each row in the detail table
	// $("#detail-table tbody tr").each(function () {
	// 	// Check badge in warehouse column
	// 	let badgeText = $(this).find("td:nth-child(13) .badge").text().trim();
	// 	if (badgeText === "No Data") {
	// 		isValid = false;
	// 		return false; // Exit loop if found
	// 	}
	// });

	if (!isValid) {
		Swal.fire({
			icon: "error",
			title: "Oops...",
			text: "Lengkapi semua data!",
			confirmButtonColor: "#3085d6",
			confirmButtonText: "Tutup",
		});
	}

	return isValid;
}
//

//

//
function calculateTotalAmount() {
	let totalAmount = 0;

	$("#table-detail-item tbody tr").each(function () {
		let amount = parseFloat(
			formatAritmatika($(this).find("td:eq(12) input").val())
		);
		if (!isNaN(amount)) {
			totalAmount += amount;
		}
	});

	$("#total-amount").val(formatIdrAccounting(totalAmount));
	// format tampilan
}
//

// Attach event listener to radio buttons within the shipping-table
//
function formatAritmatika(str) {
	return str ? str.replace(/,/g, "") : "0";
}

function currencyFormat(num, decimal = 4) {
	return accounting.formatMoney(num, "", decimal, ",", ".");
}
// DETAIL TABLE
function appendTableRows(details) {
	let tableBody = $("#table-detail-item tbody");
	tableBody.empty(); // Kosongkan tabel sebelum menambahkan data
	// console.table(details)
	$.each(details, function (index, item) {
		let row = `<tr>
					<td class="text-center">${index + 1}</td>
					<td class="text-center">${item.ShipInst_Number || ""}</td>
					<td class="text-center">${item.SO_Number || ""}</td>
					<td class="text-center">${item.Item_Code || ""}</td>
					<td class="text-center">${item.Item_Name || ""}</td>
					<td class="text-center">${item.Item_Color || ""}</td>
					<td class="text-center">${item.Brand || ""}</td>
					<td class="text-center">${parseFloat(item.Qty_Invoiced) || ""}</td>
					<td class="text-center text-uppercase">${item.Uom || ""}</td>
					<td class="text-center">
						<input type="text" class="form-control form-control-sm text-center" name="item_price[]" value="${formatIdrAccounting(
							item.Item_Price
						)}" readonly />
					</td>
					<td class="text-center">
						<input type="text" class="form-control form-control-sm text-center input-persentase-discount" name="discount[]" value="${formatIdrAccounting(
							item.Discount
						)}" readonly />
					</td>
					<td class="text-center">
						<input type="text" class="form-control form-control-sm text-center" name="disc_value[]" value="${formatIdrAccounting(
							item.Final_Discount
						)}" readonly />
					</td>
					<td class="text-center">
						<input type="text" class="form-control form-control-sm text-center" name="amount_detail[]" value="${formatIdrAccounting(
							item.Final_Amount
						)}" readonly />
					</td>
					<td class="text-center" data-tax1-id="${item.Tax1_Id}">
						<input type="text" class="form-control form-control-sm text-center tax1" name="tax1_code[]" value="${
							item.Tax1_Code
						}" readonly />
					</td>
					<td class="text-center" data-tax2-id="${item.Tax2_Id}">
						<input type="text" class="form-control form-control-sm text-center tax2" name="tax2_code[]"   value="${
							item.Tax2_Code
						}" readonly />
					</td>
					<!-- Hidden inputs for SO_Number, Item_Code, etc -->
					<input type="hidden" name="dtl_so_number[]" value="${item.SO_Number}" />
					<input type="hidden" name="item_code[]" value="${item.Item_Code}" />
					<input type="hidden" name="item_name[]" value="${item.Item_Name}" />
					<input type="hidden" name="item_color[]" value="${item.Item_Color}" />
					<input type="hidden" name="brand[]" value="${item.Brand}" />
					<input type="hidden" name="qty[]" value="${item.Qty}" />
					<input type="hidden" name="product_size[]" value="${item.Dimension}" />
					<input type="hidden" name="is_free[]" value="${item.isFreeItem}" />
					// 
					// 
					<input type="hidden" name="Tax_Amount1[]" value="${item.Value_Tax_1}" />
					<input type="hidden" name="Tax_Amount2[]" value="${item.Value_Tax_2}" />
				</tr>`;

		tableBody.append(row);
	});

	calculateTotalAmount();
}

function reset_input() {
	$("#select-customer").removeAttr("disabled");
	$("#btn-submit").attr("disabled", true);
	$("#section-before-chose-si").addClass("d-none");
	$("#section-after-chose-si").addClass("d-none");
	// Generate the current date in the format YYYYMMDD
	var today = new Date();
	var year = today.getFullYear();
	var month = ("0" + (today.getMonth() + 1)).slice(-2); // Adding 1 because getMonth() is zero-based
	var day = ("0" + today.getDate()).slice(-2);
	var formattedDate = year + month + day;

	// Reset all text inputs and textareas
	$("#main-form input:text").val("");
	$("#main-form textarea").not("#alamat-customer").val("");

	// Reset all hidden inputs
	$("#main-form input[type='hidden']").not("[name='state']").val("");

	// Reset select2 dropdowns and set currency back to IDR
	$("#main-form select").not("#select-customer").val("").trigger("change");
	$('select[name="currency"]').select2().val("IDR").trigger("change");

	// Reset specific input fields by ID
	$("#nomer-shipping").val("INV" + formattedDate + "-xxxxxxx"); // Default format for invoice
	// $("#alamat-customer").val("");
	$("#port-of-loading").val("");
	$("#place-of-delivery").val("");
	$("#carrier").val("");
	$("#sailing").val("");
	$("#NotifeParty").val("");
	$("#NotifePartyAddress").val("");
	$("#ShippingMarks").val("");
	$("#LCNo").val("");
	$("#LCDate").val("");
	$("#LCBank").val("");
	$("#invoice-date").val("");
	$("#due-date").val("");
	$("#tax-date").val("");
	$("#taxdocnumpph").val("");
	$("#notes").val("");
	$("#invoice-print-date").val("");
	$("#rate-currency").val("1"); // Default currency rate
	$("#total-amount").val("0.00");
	$("#discount-percentage").val("0.00");
	$("#total_tax").val("0.00");

	// Clear the shipping table rows
	$("#shipping-table tbody").html("");

	// Clear table rows for detailed items
	$("#table-detail-item tbody").html("");

	// Show "no data" message if any
	$("#no_data_item").show();
}

$(document).on("click", "#back", function () {
	form_state("BACK");
});

function formatAritmatika(str) {
	return str ? str.replace(/,/g, "") : "0";
}
