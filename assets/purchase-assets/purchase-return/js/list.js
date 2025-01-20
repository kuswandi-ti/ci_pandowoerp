$(document).ready(function () {
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

	form_state('LOAD');

	function form_state(state) {
		switch (state) {
			case 'LOAD':
				$('.list-data').show("slow");
				$('.add-data').hide("slow");
				$('input[name="state"]').val('');
				reloadData();
				break;

			case 'ADD':
				reset_input();
				$(MainForm)[0].reset();
				$('.list-data').hide("slow");
				$('.add-data').show("slow");
				$('input[name="state"]').val('ADD');
				$('#title-add-hdr').html('Add');
				$('input[name="rr_no"]').val('PR Number Akan Otomatis di isikan Oleh system.');
				$('#no_data_item').show('slow');
				$('#btn-submit').show();
				$('#btn-browse-rr').prop('disabled', false);
				$('.th-add').show();
				$('.th-qty-rr').html('Qty RR');
				$('.txt_add').html('Add');
				flatpickr();
				break;

			case 'EDIT':
				reset_input();
				$(MainForm)[0].reset();
				$('.list-data').hide();
				$('.add-data').show();
				$('input[name="state"]').val('EDIT');
				$('#title-add-hdr').html('Edit');
				$('#no_data_item').hide('slow');
				$('.txt_add').html('Edit');
				$('.th-qty-rr').html('Qty PR Sebelumnya');
				flatpickr();
				break;

			case 'DETAIL':
				reset_input();
				$('.list-data').hide();
				$('.add-data').show();
				$('input[name="state"]').val('DETAIL');
				$('#title-add-hdr').html('Detail');
				$('#no_data_item').hide('slow');
				$('.txt_add').html('Detail');
				flatpickr();
				break;

			case 'BACK':
				$('.list-data').show("slow");
				$('.add-data').hide("slow");
				break;
		}
	}

	function reset_input() {
		$("input:text").val('');
		$('input[type="hidden"]').val('');
		$('#table_item tbody').html('');
		$('.select2').val('').trigger('change');
		$('select[name="currency"]').val('IDR').trigger('change');
	}

	function reloadData() {
		$("#DataTable").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			dom: 'l<"row"<"col-6"f><"col-6"B>>rtip',
			lengthMenu: [
				[10, 25, 50, 10000],
				[10, 25, 50, 'All']
			],
			select: true,
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseReturn/DT_listdata_return",
				dataType: "json",
				type: "POST",
			},
			columns: [{
					data: 'SysId', // gunakan 'null' karena kita akan menggunakan render function
					render: function (data, type, row, meta) {
						return meta.row + 1; // meta.row adalah indeks baris (mulai dari 0)
					},
				},
				{
					data: "PR_Number",
				},
				{
					data: "PR_Date",
					render: function (data, type, row, meta) {
						return moment(data).format("DD MMMM YYYY");
					}
				},
				{
					data: "Account_Name",
				},
				{
					data: "RR_Number",
				},
				{
					data: "PO_Number",
				},
                {
                	data: "Approve_Date",
                	render: function (data, type, row, meta) {
                		return data == null ? '-' : moment(data).format("DD MMMM YYYY");
                	}
                },
                {
                    data: "IsCancel",
                    render: function (data, type, row, meta) {
                        if (data == 1) {
                            return `<div class='d-flex justify-content-center'><span class="badge bg-danger">Cancel</span></div>`;
                        } else {
                            return `<div class='d-flex justify-content-center'><span class="badge bg-warning">Active</span></div>`;
                        }
                    }
                },
				{
					data: "Approval_Status",
					render: function (data, type, row, meta) {
						if (data == 0) {
							return `<i class="fas fa-question text-dark"></i>`
						} else if (data == 1) {
							return `<i class="fas fa-check text-success"></i>`
						} else {
							return `<i class="fas fa-times text-danger"></i>`
						}
					}
				},
			],
			order: [
				[0, "desc"]
			],
			columnDefs: [{
					className: "text-center",
					targets: "_all",
				},
				{
					className: "text-left",
					targets: []
				}
			],
			autoWidth: false,
			// responsive: true,
            rowCallback: function (row, data, index) {
                // Gantilah 'yourColumnName' dengan nama kolom Anda
                if (data.IsCancel == 1) {
                    $(row).css('background-color', '#F8D7DA');
                }
            },
			preDrawCallback: function () {
				$("#DataTable tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#DataTable tbody td").addClass("blurry");
				setTimeout(function () {
					$("#DataTable tbody td").removeClass("blurry");
				});
				$('[data-toggle="tooltip"]').tooltip();
			},
			initComplete: function (settings, json) {
				// ---------------
			},
			"buttons": [{
				text: `<i class="fas fa-plus fs-3"></i>&nbsp; Add PR`,
				className: "bg-primary",
				action: function (e, dt, node, config) {
					form_state('ADD');
				}
			}, {
				text: `<i class="fas fa-edit fs-3"></i>&nbsp; Edit`,
				className: "btn btn-warning",
				action: function (e, dt, node, config) {
					var RowData = dt.rows({
						selected: true
					}).data();
					if (RowData.length == 0) {
						Swal.fire({
							icon: 'warning',
							title: 'Ooppss...',
							text: 'Silahkan pilih data untuk edit data !',
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
						});
					} else {
						Init_Edit_Detail(RowData[0].SysId, 'EDIT')
					}
				}
			}, {
				text: `<i class="fas fa-search fs-3"></i>&nbsp; View Detail`,
				className: "btn btn-info",
				action: function (e, dt, node, config) {
					var RowData = dt.rows({
						selected: true
					}).data();
					if (RowData.length == 0) {
						Swal.fire({
							icon: 'warning',
							title: 'Ooppss...',
							text: 'Silahkan pilih data untuk melihat detail data !',
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
						});
					} else if(RowData[0].IsClose == 1) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Data tidak bisa di ubah karena sudah Close !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
						Init_Edit_Detail(RowData[0].SysId, 'DETAIL')
					}
				}
			}, {
                text: `<i class="fa fa-times fs-3"></i>&nbsp; Cancel`,
                className: "btn btn-dark",
                action: function (e, dt, node, config) {
                    var RowData = dt.rows({
                        selected: true
                    }).data();
                    if (RowData.length == 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Silahkan pilih data untuk merubah status !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else if (RowData[0].IsCancel == 1) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Data tidak bisa di ubah karena sudah Cancel !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        Fn_Toggle_Status_Cancel(RowData[0].PR_Number)
                    }
                }
            }, {
				text: `Export to :`,
				className: "btn disabled text-dark bg-white",
			}, {
				text: `<i class="far fa-file-excel"></i>`,
				extend: 'excelHtml5',
				title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
				className: "btn btn-success",
			}, {
				text: `<i class="far fa-file-pdf"></i>`,
				extend: 'pdfHtml5',
				title: $('#table-title').text() + '~' + moment().format("YYYY-MM-DD"),
				className: "btn btn-danger",
				orientation: "landscape"
			}],
		}).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');
	}

	function Init_Edit_Detail(SysId, State) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseReturn/GetDataDetailReturn",
			data: {
				sysid: SysId,
				state: State
			},
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					form_state(State);

					$('#btn-browse-rr').prop('disabled', true);

					$('.th-add').hide();
					$('input[name="sysid"]').val(response.data_hdr.SysId);
					$('input[name="rr_number"]').val(response.data_hdr.RR_Number);
					$('input[name="pr_no"]').val(response.data_hdr.PR_Number);
					$('input[name="po_no"]').val(response.data_hdr.PO_Number);
					$('input[name="vendor_id"]').val(response.data_hdr.Account_ID);
					$('input[name="vendor"]').val(response.data_hdr.Account_Name);
					$('input[name="vendor_address"]').val(response.data_hdr.Vendor_Address);
					$('textarea[name="notes"]').val(response.data_hdr.Notes);
					
					// DETAIL //
					var $tableItem = $('#table_item tbody');
					$tableItem.empty();

					let no = 1;
					$.each(response.data_dtl, function (index, rowData) {
						var $newRow = $('<tr>');

						$newRow.append('<td><p class="mt-1">' + no + '</p></td>');
						$newRow.append(`<td>
							<input type="hidden" name="sysid_dtl[]" value="` + rowData.SysId + `">
							<input class="form-control form-control-sm" type="text" value="` + rowData.Item_Code + `" readonly>
						</td>`);
						$newRow.append('<td><input class="form-control form-control-sm" type="text" value="' + rowData.Item_Name + '" readonly></td>');
						$newRow.append(`<td>
							<input class="form-control form-control-sm" type="text" value="` + rowData.Uom + `" readonly>
						</td>`);
						$newRow.append(`<td>
							<input class="form-control form-control-sm outstanding`+ no +`" type="text" value="`+ (State == 'EDIT' ? parseFloat(rowData.Qty) : parseFloat(rowData.Qty_RR)) +`" readonly>
						</td>`);
						$newRow.append('<td><input class="form-control form-control-sm return_qty return_qty' + no + '" type="number" min="1" max="' + (parseFloat(rowData.Qty) - parseFloat(1)) + '" name="return_qty[]" value="'+ parseFloat(rowData.Qty) +'" data-no="' + no + '"></td>');
						$newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');

						// Masukkan baris baru ke dalam tabel tujuan
						$tableItem.append($newRow);

						no++;
					});

					if (State == 'DETAIL') {
						$('#btn-submit').hide();
					} else {
						$('#btn-submit').show();
					}
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: response.msg,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ya, Confirm!',
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
			},
			error: function (xhr, status, error) {
				var statusCode = xhr.status;
				var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
				Swal.fire({
					icon: "error",
					title: "Error!",
					html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
				});
			}
		});
	}

    function Fn_Toggle_Status_Cancel(PR_Number) {
        Swal.fire({
            title: 'System message!',
            text: `Apakah anda yakin untuk merubah status cancel pr ini ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, ubah!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseReturn/Toggle_Status_Cancel",
                    type: "post",
                    dataType: "json",
                    data: {
                        pr_number: PR_Number,
                    },
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Loading....',
                            html: '<div class="spinner-border text-primary"></div>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        })
                    },
                    success: function (response) {
                        if (response.code == 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.msg,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Yes, Confirm!'
                            })
                            $("#DataTable").DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.msg,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Yes, Confirm!',
                                footer: '<a href="javascript:void(0)">Notification System</a>'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        var statusCode = xhr.status;
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
                        });
                    }
                });
            }
        })
    }

	$(document).on('click', '#back', function () {
		form_state('BACK');
	});

	// ======================================================== //
	// --------------------- FUNGSI ADD ----------------- //
	$(document).on('click', '.tambah_item', function () {
		var vendor_id = $('input[name="vendor_id"]').val();
		var select_vendor = '';

		if ($('input[name="state"]').val() == 'ADD') {
			$('.vendor_input_modal_item').show();
		} else {
			$('.vendor_input_modal_item').hide();
		}

		$('#modal_tambah_item .table-responsive').hide();
		if (vendor_id) {
			select_vendor = vendor_id;
			reloadDataItem(vendor_id);
		}

		$('select[name="vendor_modal"]').val(select_vendor).trigger('change');

		if (select_vendor == '') {
			$('#select_item').hide();
		}

		$('#modal_tambah_item').modal('show');
	});

	var selectedRows = {};
	// Bersihkan selectedRows saat modal ditutup
	$('#modal_tambah_item').on('hidden.bs.modal', function () {
		selectedRows = {}; // Reset the selectedRows object
		$('#table_item tbody tr').removeClass('selected'); // Remove 'selected' class from all rows
		$('#table_item tbody input[type="checkbox"]').prop('checked', false); // Uncheck all checkboxes
	});

	$('select[name="vendor_modal"]').on('change', function () {
		var val = $(this).val();

		if (val != null) {
			reloadDataItem(val);
		}
	});

	$("#DataTable_Modal_ListItem tbody").on("click", ".chk_select", function () {
		var sysId = $(this).val();
		var row = $(this).closest('tr');
		var rowData = $('#DataTable_Modal_ListItem').DataTable().row(row).data();

		if ($(this).is(':checked')) {
			selectedRows[sysId] = rowData;
		} else {
			delete selectedRows[sysId];
		}

		if ($(this).parents("tr").hasClass("selected")) {
			$(this).parents("tr").removeClass("selected");
		} else {
			$(this).parents("tr").addClass("selected");
		}
	});

	$('#no_data_item').show('slow');
	$('.footer-table').hide('slow');
	$(document).on('click', '.remove_item_dtl', function () {
		var trIndex = $(this).closest("tr").index();

		$(this).closest("tr").remove();
		if (trIndex == 0) {
			$('#no_data_item').show('slow');
			$('.footer-table').hide('slow');
		}
	});

	$('#search-list-item').keyup(function () {
		var found = false;

		var searchText = $(this).val().toLowerCase();
		$('#table_item tbody tr').each(function () {
			var Item_Code = $(this).find('td:eq(1) input').val().toLowerCase(); // Ambil nilai dari input di kolom Item_Code
			var Item_Name = $(this).find('td:eq(2) input').val().toLowerCase();

			if (Item_Code.includes(searchText) || Item_Name.includes(searchText)) {
				$(this).show();
				found = true;
			} else {
				$(this).hide();
			}
		});

		if (!found) {
			$('#no_data_item').show();
		} else {
			$('#no_data_item').hide();
		}
	});
	// --------- ADD SAVE --------- //

	// ------------------------------------ START FORM VALIDATION
	const MainForm = $('#main-form');
	const BtnSubmit = $('#btn-submit');
	MainForm.validate({
		errorElement: 'span',
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			element.closest('.form-group').append(error);
		},
		highlight: function (element, errorClass, validClass) {
			$(element).addClass('is-invalid');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).removeClass('is-invalid');
		}
	});
	$.validator.setDefaults({
		debug: true,
		success: 'valid'
	});

	$(BtnSubmit).click(function (e) {
		e.preventDefault();
		if (MainForm.valid()) {
			Swal.fire({
				title: 'Loading....',
				html: '<div class="spinner-border text-primary"></div>',
				showConfirmButton: false,
				allowOutsideClick: false,
				allowEscapeKey: false
			});
			Fn_Submit_Form(MainForm)
		} else {
			$('html, body').animate({
				scrollTop: ($('.error:visible').offset().top - 200)
			}, 400);
		}
	});
	// ------------------------------------ END FORM VALIDATION

	function Fn_Submit_Form() {
		if ($('#table_item tbody').children().length === 0) {
			Swal.fire({
				icon: 'warning',
				title: 'Ooppss...',
				text: 'Detail Item Tidak Boleh Kosong!',
				footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
			});

			return true;
		}

		BtnSubmit.prop("disabled", true);
		var formDataa = new FormData(MainForm[0]);

		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseReturn/store",
			data: formDataa,
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					$(MainForm)[0].reset();
					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: response.msg,
						showCancelButton: false,
					}).then((result) => {
						form_state('LOAD');
					})
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: response.msg,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ya, Confirm!',
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
				BtnSubmit.prop("disabled", false);
			},
			error: function (xhr, status, error) {
				var statusCode = xhr.status;
				var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
				Swal.fire({
					icon: "error",
					title: "Error!",
					html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
				});

				BtnSubmit.prop("disabled", false);
			}
		});
	}
	// ------- ADD SAVE - END --------- //
	// ------------------- FUNGSI ADD - END ----------------- //
	// ======================================================== //

	// ----------------------- BROWSE DATA ------------------ //
	// -- Reload Data Person
	function reloadDataRR() {
		$("#table-browse-rr").DataTable({
			destroy: true,
			processing: true,
			serverSide: true,
			lengthMenu: [
				[10, 25, 50, 10000],
				[10, 25, 50, 'All']
			],
			select: true,
			ajax: {
				url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseReturn/DT_listdata_browse_rr",
				dataType: "json",
				type: "POST",
			},
			columns: [{
                    data: "RR_Number",
                },
				{
                    data: "RR_Date",
					render: function (data, type, row, meta) {
                        return moment(data).format("DD MMMM YYYY");
					}
				},
				{
					data: "PO_Number",
				},
                {
                    data: "Name_Vendor",
                },
                {
                    data: "Address_Vendor",
                },
                {
                    data: "RR_Notes",
                }
			],
			order: [
				[0, "desc"]
			],
			columnDefs: [{
					className: "text-center",
					targets: ["_all"],
				},
				{
					className: "text-left",
					targets: []
				}
			],
			autoWidth: false,
			// responsive: true,
			preDrawCallback: function () {
				$("#table-browse-rr tbody td").addClass("blurry");
			},
			language: {
				processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
			},
			drawCallback: function () {
				$("#table-browse-rr tbody td").addClass("blurry");
				setTimeout(function () {
					$("#table-browse-rr tbody td").removeClass("blurry");
				});
				$('[data-toggle="tooltip"]').tooltip();
			},
		});
	}

	$("#btn-select-rr").click(function (e) {
		table = $("#table-browse-rr").DataTable();
		data = table.rows('.selected').data()[0];

		$('input[name="rr_number"]').val(data.RR_Number);
		$('input[name="po_no"]').val(data.PO_Number);
		$('input[name="vendor_id"]').val(data.Account_ID);
		$('input[name="vendor"]').val(data.Name_Vendor);
		$('input[name="vendor_address"]').val(data.Address_Vendor);

		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Purchase/PurchaseReturn/GetDataDtlRR",
			data: {
				sysid: data.RR_Number
			},
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					// DETAIL //
					var $tableItem = $('#table_item tbody');
					$tableItem.empty();

					var no = 1;
					$.each(response.data_dtl, function (index, rowData) {
						var $newRow = $('<tr>');

						$newRow.append('<td><p class="mt-1">' + no + '</p></td>');
						$newRow.append('<td><input class="form-control form-control-sm" name="item_code[]" type="text" value="' + rowData.Item_Code + '" readonly></td>');
						$newRow.append('<td><input class="form-control form-control-sm" name="item_name[]" type="text" value="' + rowData.Item_Name + '" readonly></td>');
						$newRow.append(`<td>
							<input type="hidden" name="warehouse_id[]" value="`+ rowData.Warehouse_ID +`">
							<input class="form-control form-control-sm" type="text" value="` + rowData.Uom + `" readonly>
						</td>`);
						$newRow.append('<td><input class="form-control form-control-sm rr_qty' + no + '" type="text" name="qty_rr[]" value="' + parseFloat(rowData.Qty_RR) + '" readonly></td>');
						$newRow.append('<td><input class="form-control form-control-sm outstanding'+ no +'" type="text" value="' + parseFloat(rowData.Qty_Outstanding) + '" readonly></td>');
						$newRow.append('<td><input class="form-control form-control-sm balance'+ no +'" type="text" value="' + (parseFloat(rowData.Qty_Outstanding) - parseFloat(1)) + '" readonly></td>');
						$newRow.append('<td><input class="form-control form-control-sm return_qty return_qty' + no + '" type="number" min="1" max="' + (parseFloat(rowData.Qty_Outstanding) - parseFloat(1)) + '" name="return_qty[]" value="1" data-no="' + no + '"></td>');
						$newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');
						// Masukkan baris baru ke dalam tabel tujuan
						$tableItem.append($newRow);
						no++;
					});

					$('#no_data_item').hide();
					// DETAIL - END //
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: response.msg,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ya, Confirm!',
						footer: '<a href="javascript:void(0)">Notifikasi System</a>'
					});
				}
			},
			error: function (xhr, status, error) {
				var statusCode = xhr.status;
				var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : xhr.responseText ? xhr.responseText : "Terjadi kesalahan: " + error;
				Swal.fire({
					icon: "error",
					title: "Error!",
					html: `Kode HTTP: ${statusCode}<br\>Pesan: ${errorMessage}`,
				});
			}
		});
		$("#browse_rr_modal").modal("hide");
	});

	$("#table_item").on('keyup blur', '.return_qty', function () {
		var no = $(this).data('no');

		var state 		= $('input[name="state"]').val();
		var return_qty 	= $(this).val();
		var outstanding = $(".outstanding" + no).val();

		let diff = outstanding - return_qty;

		if (state == 'ADD' && diff == 0) {
			toastr.error('Balance Tidak Boleh 0!', 'Information', {
				closeButton: true,
				progressBar: true,
				positionClass: 'toast-top-right',
				timeOut: '3500',
				extendedTimeOut: '1000',
				showDuration: '300',
				hideDuration: '1000',
				hideEasing: 'linear',
				hideMethod: 'fadeOut'
			});

			$(this).val(1);
			$(".balance" + no).val(outstanding - 1);

			return true;
		}

		if (diff < 0) {
			var msg_diff = state == 'ADD' ? 'QTY Return Tidak Boleh Melebihi Qty RR' : 'Qty Return Tidak Boleh Lebih dengan Qty Sebelumnya';

			toastr.error(msg_diff, 'Information', {
				closeButton: true,
				progressBar: true,
				positionClass: 'toast-top-right',
				timeOut: '3500',
				extendedTimeOut: '1000',
				showDuration: '300',
				hideDuration: '1000',
				hideEasing: 'linear',
				hideMethod: 'fadeOut'
			});

			// Kondisi add itu qty rr tapi kalau edit itu qty pr sebelumnya
			let val_diff = state == 'ADD' ? 1 : outstanding;
			$(this).val(val_diff);
			$(".balance" + no).val(state == 'ADD' ? outstanding - 1 : 0);

			return true;
		}

		$(".balance" + no).val(diff);
	});

	$("#btn-browse-rr").click(function (event) {
		$('#browse_rr_modal').modal('show');
		reloadDataRR();
	});
	// -- Reload Data Person - END
	// ------------------- BROWSE DATA - END ------------------ //

	// ----------------- DEFINISI PRICE -------------------- //
	$(document).on('keypress keyup blur', '.only-number', function (event) {
		var inputVal = $(this).val();
		// Mengizinkan hanya digit, titik (.) dan koma (,)
		$(this).val(inputVal.replace(/[^\d.,]/g, ""));

		// Pengecekan untuk mencegah lebih dari satu titik atau koma
		if (
			(event.which !== 44 || inputVal.indexOf(",") !== -1) &&
			(event.which !== 46 || inputVal.indexOf(".") !== -1) &&
			(event.which < 48 || event.which > 57)
		) {
			event.preventDefault();
		}
	});
	// ------------ DEFINISI PRICE - END --------------- //
});
