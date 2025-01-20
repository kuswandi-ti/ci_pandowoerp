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
    });
    
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
                $('.list-data').hide("slow");
                $('.add-data').show("slow");
                $('input[name="state"]').val('ADD');
				$('#title-add-hdr').html('Add');
				$('.tambah_detail').show("slow");
				$('#btn-submit').show('slow');
                $('input[name="doc_no"]').val('Doc Number Akan Otomatis di isikan Oleh system.');
                $('#no_data_item').show('slow');
				$(MainForm)[0].reset();
                flatpickr();
                break;
    
            case 'EDIT':
                reset_input();
                $('.list-data').hide("slow");
                $('.add-data').show("slow");
                $('input[name="state"]').val('EDIT');
				$('#title-add-hdr').html('Add');
				$('.tambah_detail').show("slow");
				$('#btn-submit').show('slow');
                $('#no_data_item').hide('slow');
                break;

			case 'DETAIL':
				reset_input();
				$('.list-data').hide('slow');
				$('.add-data').show('slow');
				$('input[name="state"]').val('DETAIL');
				$('#title-add-hdr').html('Detail');
				$('.tambah_detail').hide("slow");
				$('#no_data_item').hide('slow');
				$('#btn-submit').hide('slow');
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
    }
    
    function reloadData(){
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
                url: $('meta[name="base_url"]').attr('content') + "FinanceAccounting/JurnalPenyesuaian/DT_listdata",
                dataType: "json",
                type: "POST",
            },
            columns: [
				{
                    data: "SysId",
					name: "SysId",
                }, {
                    data: "no_jurnal",
					name: "no_jurnal",
                }, {
                    data: "tgl_jurnal",
					name: "tgl_jurnal",
                    render: function (data, type, row, meta) {
                        return moment(data).format("DD MMMM YYYY");
                    }
                }, {
                    data: "reff_desc",
					name: "reff_desc",
                    render: function (data, type, row, meta) {
                        return data ? data : '-';
                    }
                }, {
					data: "isCancel",
					name: "isCancel",
					render: function (data, type, row, meta) {
                        if (data == 0) {
                            return `<div class='d-flex justify-content-center'><span class="badge bg-success">Active</span></div>`;
                        } else {
                            return `<div class='d-flex justify-content-center'><span class="badge bg-danger">Cancel</span></div>`;
                        }
                    }
				}, {
					data: "debit",
					name: "debit",
					searchable: false,
					orderable: false,
					render: function (data, type, row, meta) {
						return formatIdrAccounting(data);
					}
				}, {
					data: "credit",
					name: "credit",
					searchable: false,
					orderable: false,
					render: function (data, type, row, meta) {
						return formatIdrAccounting(data);
					}
				}, {
                    data: "keterangan",
					name: "keterangan",
                }, {
					data: "no_jurnal_cancel",
					name: "no_jurnal_cancel",
				},
            ],
            order: [
                [0, "desc"]
            ],
            columnDefs: [{
                className: "text-center",
                targets: [0, 1, 2, 8],
            },
            {
                className: "text-left",
                targets: []
            },
			{
                className: "text-right",
                targets: [5, 6]
            },
			{
				visible: false,
				targets: [0]
			},
            ],
            autoWidth: false,
            // responsive: true,
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
                text: `<i class="fas fa-plus fs-3"> Add Jurnal</i>`,
                className: "bg-primary",
                action: function (e, dt, node, config) {
                    form_state('ADD');
                }
            }, {
                text: `<i class="fas fa-edit fs-3"> Edit</i>`,
                className: "btn btn-warning",
                action: function (e, dt, node, config) {
                    var RowData = dt.rows({
                        selected: true
                    }).data();
                    if (RowData.length == 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Silahkan pilih data untuk melihat detail !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else if(RowData[0].isCancel == 1) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Data tidak bisa di ubah karena status CANCEL !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        Init_Edit(RowData[0].SysId, 'EDIT')
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
                    } else {
                        Init_Edit(RowData[0].SysId, 'DETAIL')
                    }
                }
            }, {
				text: `<i class="fas fa-print fs-3"> Print</i>`,
				className: "btn bg-gradient-success",
				action: function (e, dt, node, config) {
					var RowData = dt.rows({
						selected: true
					}).data();
					if (RowData.length == 0) {
						return Swal.fire({
							icon: 'warning',
							title: 'Ooppss...',
							text: 'Silahkan pilih data terlebih dahulu !',
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
						});
					} else if (RowData[0].Is_Cancel == 1 || RowData[0].Is_Approve == 2 || RowData[0].Is_Approve == 0) {
						Swal.fire({
							icon: 'warning',
							title: 'Ooppss...',
							text: 'Report cetak hanya bisa di tampilkan pada data yang telah legitimate (tidak cancel dan sudah approve)!',
							footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
						});
					} else {
						window.open($('meta[name="base_url"]').attr('content') + `FinanceAccounting/JurnalPenyesuaian/print/${RowData[0].SysId}`, "_blank");
					}
				}
			}, {
				text: `<i class="fas fa-times fs-3"> Cancel</i>`,
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
                    } else if(RowData[0].isCancel == 1) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Data tidak bisa di ubah karena status CANCEL !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        Fn_Toggle_Status(parseInt(RowData[0].SysId))
                    }
                }
			}, {
                text: `Export to :`,
                className: "btn disabled text-dark bg-white",
            }, {
                text: `<i class="far fa-file-excel"> Excel</i>`,
                extend: 'excelHtml5',
                title: $('title').text() + ' ~ ' + moment().format("YYYY-MM-DD"),
                className: "btn btn-success",
            }, {
                text: `<i class="far fa-file-pdf"> PDF</i>`,
                extend: 'pdfHtml5',
                title: $('title').text() + ' ~ ' + moment().format("YYYY-MM-DD"),
                className: "btn btn-danger",
                orientation: "landscape"
            }],
        }).buttons().container().appendTo('#TableData_wrapper .col-md-6:eq(0)');
    }

	function Fn_Toggle_Status(SysId) {
		Swal.fire({
			title: 'System message!',
			text: `Apakah anda yakin untuk merubah status data ini ?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, ubah!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: $('meta[name="base_url"]').attr('content') + "FinanceAccounting/HelperFinanceAccounting/CancelJurnal",
					type: "post",
					dataType: "json",
					data: {
						sysid: SysId,
						table: 'ttrx_hdr_jurnal'
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

    function Init_Edit(SysId, State) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "FinanceAccounting/JurnalPenyesuaian/edit",
			data: {
                sysid : SysId,
                state: State
            },
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
                    form_state(State);

                    $('input[name="sysid_hdr"]').val(response.data_hdr.SysId);
					$('input[name="no_jurnal"]').val(response.data_hdr.no_jurnal);
                    $('input[name="tgl_jurnal"]').val(moment(response.data_hdr.tgl_jurnal).format("DD MMMM YYYY"));
					$('input[name="reff_desc"]').val(response.data_hdr.reff_desc);
                    $('textarea[name="keterangan"]').val(response.data_hdr.keterangan);
					$('input[name="total_debit"]').val(formatIdrAccounting(response.data_hdr.debit));
					$('input[name="total_kredit"]').val(formatIdrAccounting(response.data_hdr.credit));

                    // DETAIL //
                    var $tableItem = $('#table_item tbody');
                    $tableItem.empty();

                    var no = 1;
                    $.each(response.data_dtl, function(index, rowData) {
                        var $newRow = $('<tr>');

                        // Buat kolom dengan input sesuai dengan data yang ada
                        $newRow.append('<td><input type="hidden" name="id_coa[]" value="' + rowData.id_coa + '"><p class="mt-1">'+ no +'</p></td>');
                        $newRow.append('<td><input class="form-control form-control-sm" name="kode_akun[]" type="text" value="' + rowData.kode_akun + '" readonly></td>');
                        $newRow.append('<td><input class="form-control form-control-sm" name="nama_akun[]" type="text" value="' + rowData.nama_akun + '" readonly></td>');
                        $newRow.append('<td><input class="form-control form-control-sm number" type="text" name="debit[]" value="'+ formatIdrAccounting(rowData.debit) +'"></td>');
						$newRow.append('<td><input class="form-control form-control-sm number" type="text" name="credit[]" value="'+ formatIdrAccounting(rowData.credit) +'"></td>');
						$newRow.append('<td><input class="form-control form-control-sm" name="note[]" type="text" value="' + rowData.note + '"></td>');
                        $newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');

                        // Masukkan baris baru ke dalam tabel tujuan
                        $tableItem.append($newRow);

                        no++
                    });

                    number_text();
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

    $(document).on('click', '#back', function() {
        form_state('BACK');
    });

    // ======================================================== //
    // --------------------- FUNGSI ADD ----------------- //
    var selectedRows = {};

    $(document).on('click', '.tambah_detail', function() {
        selectedRows = {};
        reloadDataItem();
    });

    function reloadDataItem() {
        var columnDefs = [
            {
                data: 'SysId',
                render: function(data, type, row) {
                    var isChecked = selectedRows[data] ? 'checked' : '';
                    var btnCheck = '<input type="checkbox" id="basic_checkbox_' + data + '" class="filled-in checkbox_checked chk_select" value="' + data + '" ' + isChecked + '/> <label for="basic_checkbox_' + data + '">&nbsp;</label>';
                    return btnCheck;
                },
            },
            {
                data: "kode_akun",
				name: "kode_akun",
            },
            {
                data: "nama_akun",
				name: "nama_akun",
            },
        ];

        var id_coa = [];
        var id_coa = $('input[name="id_coa[]"]').map(function() {
            return $(this).val();
        }).get();
		var id_akun_induk = $('#filter_akun_induk').val();
    
        $("#DataTable_Modal_ListItem").DataTable({
            destroy: true,
            processing: false,
            serverSide: true,
            lengthMenu: [
                [10, 25, 50, 10000],
                [10, 25, 50, 'All']
            ],
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "FinanceAccounting/JurnalPenyesuaian/DT_modallistofitem?id_coa=" + id_coa + "&id_akun_induk=" + id_akun_induk,
                dataType: "json",
                type: "POST",
            },
            columns: columnDefs,
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
            }],
            autoWidth: false,
            preDrawCallback: function() {
                $("#DataTable_Modal_ListItem tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function() {
                $("#DataTable_Modal_ListItem tbody td").addClass("blurry");
                setTimeout(function() {
                    $("#DataTable_Modal_ListItem tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();

                // Menandai checkbox yang sudah dipilih
                for (var key in selectedRows) {
                    if (selectedRows.hasOwnProperty(key)) {
                        $('#basic_checkbox_' + key).prop('checked', true);
                        $('#basic_checkbox_' + key).closest('tr').addClass('selected');
                    }
                }
            },
            initComplete: function(settings, json) {
                // ---------------
            },
        });

        $('#modal_list_item').modal('show');

        $(document).off('click', '#select_item'); // Hapus event handler sebelumnya
        $(document).on('click', '#select_item', function() {
            var selectedRowsData = [];
    
            $.each(selectedRows, function(sysId, rowData) {
                if (rowData) {
                    selectedRowsData.push(rowData);
                }
            });
    
            // Menambahkan data ke tabel tujuan (#table-item)
            var $tableItem = $('#table_item tbody');

            // Kosongkan tabel tujuan sebelum memasukkan data baru
            var no = 1;
            if ($tableItem.children().length === 0) {
                $tableItem.empty();
            } else {
                var lastNumber = $('#table_item tbody tr:last td:first p').text().trim();
                no = parseInt(lastNumber)+1;
            }
            
            // Iterasi melalui selectedRows dan buat baris baru di tabel tujuan
            $.each(selectedRowsData, function(index, rowData) {
                var $newRow = $('<tr>');

                // Buat kolom dengan input sesuai dengan data yang ada
                $newRow.append('<td><input type="hidden" name="id_coa[]" value="' + rowData.SysId + '"><p class="mt-1">'+ no +'</p></td>');
                $newRow.append('<td><input class="form-control form-control-sm" name="kode_akun[]" type="text" value="' + rowData.kode_akun + '" readonly></td>');
                $newRow.append('<td><input class="form-control form-control-sm" name="nama_akun[]" type="text" value="' + rowData.nama_akun + '" readonly></td>');
                $newRow.append('<td><input class="form-control form-control-sm number" type="text" name="debit[]" value=0></td>');
				$newRow.append('<td><input class="form-control form-control-sm number" type="text" name="credit[]" value=0></td>');
				$newRow.append('<td><input class="form-control form-control-sm" name="note[]" type="text" value=""></td>');
                $newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');

                // Masukkan baris baru ke dalam tabel tujuan
                $tableItem.append($newRow);
                
                no++;
            });   
			
			number_text();

            $('#no_data_item').hide('slow');
            $('#modal_list_item').modal('hide');
        });
    }

    function number_text() {
        // Handle event keydown untuk Ctrl+A
        $('.number').on('keydown', function(e) {
            if (e.ctrlKey && e.key === 'a') {
                e.preventDefault(); // Mencegah default behavior dari Ctrl+A
                var input = $(this)[0];
                var startPos = input.selectionStart;
                var endPos = input.selectionEnd;

                // Jika seleksi dimulai dari awal dan berakhir di akhir, reset nilai ke 'Rp. 0' atau '$0'
                if (startPos === 0 && endPos === input.value.length) {
                    if (input.value.includes('Rp.')) {
                        $(this).val('Rp. 0');
                    } else if (input.value.includes('$')) {
                        $(this).val('$0');
                    }
                }
            }
        });
    }

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
    $(document).on('click', '.remove_item_dtl', function() {
        var trIndex = $(this).closest("tr").index();
        
        $(this).closest("tr").remove();
        if(trIndex == 0) {
            $('#no_data_item').show('slow');
        }

		set_total_debit();
		set_total_credit();
    });
    
    $('#search-list-item').keyup(function() {
        var found = false;
        
        var searchText = $(this).val().toLowerCase();
        $('#table_item tbody tr').each(function() {
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
		BtnSubmit.prop("disabled", true);
		var formDataa = new FormData(MainForm[0]);
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "FinanceAccounting/JurnalPenyesuaian/store",
			data: formDataa,
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				Swal.close()
				if (response.code == 200) {					
					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: response.msg,
						showCancelButton: false,
					}).then((result) => {
                        form_state('LOAD');
						location.reload(true);
					})
					//$(MainForm)[0].reset();
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
			}
		});
	}
    // ------- ADD SAVE - END --------- //
    // ------------------- FUNGSI ADD - END ----------------- //
    // ======================================================== //

	$('#filter_akun_induk').change(function() {
		reloadDataItem();
	});

	function formatAritmatika(str) {
        return str ? str.replace(/,/g, '') : '0';
    }

	function set_total_debit() {
		var total_debit = 0;
		$('input[name="debit[]"]').map(function () {
			var debit = parseFloat(formatAritmatika($(this).val()));
			total_debit += debit;
		}).get();
		$('input[name="total_debit"]').val(formatIdrAccounting(total_debit));
	}

	function set_total_credit() {
		var total_credit = 0;
		$('input[name="credit[]"]').map(function () {
			var credit = parseFloat(formatAritmatika($(this).val()));
			total_credit += credit;
		}).get();
		$('input[name="total_kredit"]').val(formatIdrAccounting(total_credit));
	}

	$('body').on('keyup blur', $('input[name="debit[]"]'), function () {
		set_total_debit();
	});

	$('body').on('keyup blur', $('input[name="credit[]"]'), function () {
		set_total_credit();
	});
});
