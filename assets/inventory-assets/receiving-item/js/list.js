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
        $('.tbl_add').hide();
        
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
                $('input[name="rr_no"]').val('RR Number Akan Otomatis di isikan Oleh system.');
                $('.frm-edit').hide();
                $('#no_data_item').show('slow');
                $('#btn-submit').show();                    
                $('#btn-browse-po').prop('disabled', false);
                $('.tbl_add').show();
                flatpickr();
                break;
    
            case 'EDIT':
                reset_input();
				$(MainForm)[0].reset();
                $('.list-data').hide();
                $('.add-data').show();
                $('input[name="state"]').val('EDIT');
                $('#title-add-hdr').html('Edit');
                $('.frm-edit').show();
                $('#no_data_item').hide('slow');
                flatpickr();
                break;
    
            case 'DETAIL':
                reset_input();
                $('.list-data').hide();
                $('.add-data').show();
                $('input[name="state"]').val('DETAIL');
                $('#title-add-hdr').html('Detail');
                $('#no_data_item').hide('slow');
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
    
    function reloadData(){
        $("#DataTable").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            dom: 'l<"row"<"col-5"f><"col-7"B>>rtip',
            lengthMenu: [
                [10, 25, 50, 10000],
                [10, 25, 50, 'All']
            ],
            select: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Inventory/ReceivingItem/DT_listdata",
                dataType: "json",
                type: "POST",
            },
            columns: [
                {
                    data: 'SysId', // gunakan 'null' karena kita akan menggunakan render function
                    render: function(data, type, row, meta) {
                        return meta.row + 1; // meta.row adalah indeks baris (mulai dari 0)
                    },
                },
                {
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
                    data: "Account_Name",
                },
				{
					data: "SysId",
					render: function (data, type, row, meta) {
						var button =
							'<button class="btn btn-success btn-sm" id="show_modal_faktur_bc" data-sysid="' +
							row.SysId +
							'">' +
							    '<i class="fas fa-file"></i>' +
							"</button>";
						return button;
					},
					createdCell: function (td, cellData, rowData, row, col) {
						$(td).addClass("text-center align-middle");
					},
				},
                {
                    data: "Receive_Status",
                    render: function (data, type, row, meta) {
                        return data ? data : '-';
                    }
                },
                {
                    data: "isCancel",
                    render: function (data, type, row, meta) {
                        if (data == 1) {
                            return `<div class='d-flex justify-content-center'><span class="badge bg-danger">Cancel</span></div>`;
                        } else {
                            return `<div class='d-flex justify-content-center'><span class="badge bg-warning">Open</span></div>`;
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
                if (data.isCancel == 1) {
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
                text: `<i class="fas fa-plus fs-3"></i>&nbsp; Add RR`,
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
                        Init_Edit_Detail_Revisi(RowData[0].SysId, 'EDIT')
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
                        Init_Edit_Detail_Revisi(RowData[0].SysId, 'DETAIL')
                    }
                }
            }, {
                text: `<i class="fas fa-print fs-3"></i>&nbsp; Print`,
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
                    } else if (RowData[0].isCancel == 1 || RowData[0].Approval_Status == 2 || RowData[0].Approval_Status == 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Report cetak hanya bisa di tampilkan pada data yang telah legitimate (tidak cancel dan sudah approve)!',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        window.open($('meta[name="base_url"]').attr('content') + "Inventory/ReceivingItem/export_pdf_rr/" + RowData[0].SysId, "_blank");
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
                    } else if(RowData[0].isCancel == 1) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ooppss...',
                            text: 'Data tidak bisa di ubah karena sudah Cancel !',
                            footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                        });
                    } else {
                        Fn_Toggle_Status_Close(RowData[0].SysId)
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

    function Init_Edit_Detail_Revisi(SysId, State) {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Inventory/ReceivingItem/GetDataEditRevisi",
			data: {
                sysid : SysId,
                state : State
            },
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
                    form_state(State);

                    $('#btn-browse-po').prop('disabled', true);

                    $('input[name="sysid"]').val(SysId);
                    $('input[name="state"]').val(State);
                    $('input[name="rr_no"]').val(response.data_hdr.RR_Number);
                    $('input[name="rr_date"]').val(moment(response.data_hdr.RR_Date).format("DD MMMM YYYY"));
                    $('input[name="vendor"]').val(response.data_hdr.Account_Name);
                    $('input[name="vendor_address"]').val(response.data_hdr.Vendor_Address);
                    $('select[name="transpot_with"]').val(response.data_hdr.Transport_With).trigger('change');
                    $('input[name="vendor_sn"]').val(response.data_hdr.DO_Numb_Suplier);
                    $('input[name="po_no"]').val(response.data_hdr.PO_Number);
                    $('input[name="po_date"]').val(moment(response.data_hdr.PO_Date).format("DD MMMM YYYY"));
                    $('input[name="nopol"]').val(response.data_hdr.No_Police_Vehicle);
                    $('input[name="vendor_sn_date"]').val(moment(response.data_hdr.SupplierSNDate).format("DD MMMM YYYY"));

                    $('input[name="bc_number"]').val(response.data_hdr.BC_Number);
                    $('input[name="bc_type_info"]').val(response.data_hdr.BC_Type_Info);
                    $('input[name="bc_number_info"]').val(response.data_hdr.BC_Number_Info);
                    $('input[name="bc_date_info"]').val(moment(response.data_hdr.BC_Date_Info || new Date()).format("DD MMMM YYYY"));
                    $('input[name="faktur_number"]').val(response.data_hdr.Faktur_Number);
                    $('input[name="faktur_number_info"]').val(response.data_hdr.Faktur_Number_Info);
                    $('input[name="faktur_date_info"]').val(moment(response.data_hdr.Faktur_Date_Info || new Date()).format("DD MMMM YYYY"));
                    
                    $('textarea[name="notes"]').val(response.data_hdr.RR_Notes);
                    
                    // DETAIL //
                    var $tableItem = $('#table_item tbody');
                    $tableItem.empty();

                    getSelect(function (warehouseOptions) {
                        var no = 1;
                        
                        $.each(response.data_dtl, function(index, rowData) {
                            var $newRow = $('<tr>');
    
                            $newRow.append('<td><p class="mt-1">'+ no +'</p></td>');
                            $newRow.append('<td><input class="form-control form-control-sm" name="item_code[]" type="text" value="' + rowData.Item_Code + '" readonly></td>');
                            $newRow.append('<td><input class="form-control form-control-sm" name="item_name[]" type="text" value="' + rowData.Item_Name + '" readonly></td>');
        
                            $newRow.append(`<td>
                                <input class="form-control form-control-sm" name="uom[]" type="text" value="`+ rowData.Uom +`" readonly>
                            </td>`);
                            
                            $newRow.append(`<td>
                                <select class="form-control form-control-sm select2 select-warehouse select2-warehouse`+ no + `" name="warehouse[]""></select>
                            </td>`);
                            
                            $newRow.append('<td><input class="form-control form-control-sm po_qty'+ no +'" type="text" name="po_qty[]" value="'+ (parseFloat(rowData.Qty) % 1 === 0 ? parseInt(rowData.Qty) : parseFloat(rowData.Qty).toFixed(6)) +'" readonly></td>');
                            
                            $newRow.append('<td><input class="form-control form-control-sm unit_price'+ no +'" type="text" value="'+ currencyFormat(rowData.Unit_Price) +'" data-no="'+ no +'" readonly></td>');
                            
                            $newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');
                            
                            // Inisialisasi Select2 untuk elemen select dalam baris baru
                            var $select2warehouse = $newRow.find('.select2-warehouse' + no);
                            $select2warehouse.html(warehouseOptions).select2();
                            
                            // Masukkan baris baru ke dalam tabel tujuan
                            $tableItem.append($newRow);
    
                            $select2warehouse.val(rowData.Warehouse_ID ? rowData.Warehouse_ID : '').trigger('change');

                            no++;
                        });
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

    function Fn_Toggle_Status_Close(SysId) {
        table = $("#DataTable").DataTable();
        data = table.rows('.selected').data()[0];
        
        $('input[name="sysid_cancel"]').val(SysId);
        $('textarea[name="reason"]').val('');

        if (data.isCancel == 1) {
            Confirm_Close();
        } else {
            $('#Close_Modal').modal('show');
        }
    }

    $("#DataTable").on('click', '#show_modal_faktur_bc', function() {
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Inventory/ReceivingItem/GetDataFakturBC",
			data: {
                sysid : $(this).data('sysid'),
            },
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
                    reset_input();

                    $('input[name="sysid_modal_faktur_bc"]').val(response.data_hdr.SysId);
                    $('input[name="bc_number"]').val(response.data_hdr.BC_Number);
                    $('select[name="bc_type_info"]').val(response.data_hdr.BC_Type_Info).trigger('change');
                    $('input[name="bc_number_info"]').val(response.data_hdr.BC_Number_Info);
                    $('input[name="bc_date_info"]').val(response.data_hdr.BC_Date_Info ? moment(response.data_hdr.BC_Date_Info).format("DD MMMM YYYY") : '');
                    $('input[name="faktur_number"]').val(response.data_hdr.Faktur_Number);
                    $('input[name="faktur_number_info"]').val(response.data_hdr.Faktur_Number_Info);
                    $('input[name="faktur_date_info"]').val(response.data_hdr.Faktur_Date_Info ? moment(response.data_hdr.Faktur_Date_Info).format("DD MMMM YYYY") : '');

                    $('#modal_faktur_bc').modal('show');
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
    });

    $(document).on('click', '#btn-submit-reason', function() {
        Confirm_Close();
    });

    function Confirm_Close() {
        $.ajax({
            url: $('meta[name="base_url"]').attr('content') + "Inventory/ReceivingItem/Toggle_Status_Close",
            type: "post",
            dataType: "json",
            data: {
                sysid: $('input[name="sysid_cancel"]').val(),
                reason: $('textarea[name="reason"]').val(),
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
                    });
                    
                    $('#Close_Modal').modal('hide');
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

    $(document).on('click', '#back', function() {
        form_state('BACK');
    });

    // ======================================================== //
    // --------------------- FUNGSI ADD ----------------- //
    $(document).on('click', '.tambah_item', function() {
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

    $('select[name="vendor_modal"]').on('change', function() {
        var val = $(this).val();

        if (val != null) {
            reloadDataItem(val);
        }
    });
    
    function reloadDataItem(vendor_id) {
        $('#modal_tambah_item .table-responsive').show();
        $('#select_item').show();

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
                data: "Item_Code",
            },
            {
                data: "Item_Name",
            },
            {
                data: "Item_Category",
            },
            {
                data: "Uom",
            },
            {
                data: "Effective_Date",
                render: function (data, type, row, meta) {
                    return moment(data).format("DD MMMM YYYY");
                }
            },
            {
                data: "currency_id",
            },
            {
                data: "Price",
                render: function (data, type, row, meta) {
                    var price = 0;
                    if (row.currency_id == 'IDR') {
                        price = parseInt(data, 10).toLocaleString('id-ID');
                    } else {
                        price = parseInt(data, 10).toLocaleString('en-US');
                    }
                    return price;
                }
            },
        ];

        var sysid_items = [];
        var sysid_items = $('input[name="sysid_item[]"]').map(function() {
            return $(this).val();
        }).get();
        
        if (vendor_id != $('input[name="vendor_id"]').val()) {
            sysid_items = [];
        }
    
        $("#DataTable_Modal_ListItem").DataTable({
            destroy: true,
            processing: false,
            serverSide: true,
            lengthMenu: [
                [10, 25, 50, 10000],
                [10, 25, 50, 'All']
            ],
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Inventory/ReceivingItem/DT_modallistpriceitem?sysid_items=" + sysid_items + "&vendor_id=" + vendor_id,
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

        $('#modal_tambah_item').modal('show');

        $(document).off('click', '#select_item'); // Hapus event handler sebelumnya
        $(document).on('click', '#select_item', function() {
            // Menambahkan data ke tabel tujuan (#table-item)
            var $tableItem = $('#table_item tbody');
            let length_list_dtl = Object.keys(selectedRows).length;
            
            if ($('input[name="vendor_modal"]').val() == '' || length_list_dtl == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ooppss...',
                    text: 'Harap isi vendor dan pilih item!',
                    footer: '<a href="javascript:void(0)" class="text-danger">Notifikasi System</a>'
                });
                return true;
            }
    
            var txt_vendor = $('select[name="vendor_modal"] option:selected').text();
            
            if (vendor_id != $('input[name="vendor_id"]').val()) {
                $('input[name="vendor_address_id"]').val('');
                $('input[name="vendor_address"]').val('');
                $('input[name="person_id"]').val('');
                $('input[name="person"]').val('');

                $tableItem.empty();
            }

            $('input[name="vendor_id"]').val(vendor_id);
            $('input[name="vendor"]').val(txt_vendor);


            var selectedRowsData = [];
    
            $.each(selectedRows, function(sysId, rowData) {
                if (rowData) {
                    selectedRowsData.push(rowData);
                }
            });
    
            // Kosongkan tabel tujuan sebelum memasukkan data baru
            var no = 1;
            if ($tableItem.children().length === 0) {
                $tableItem.empty();
            } else {
                var lastNumber = $('#table_item tbody tr:last td:first p').text().trim();
                no = parseInt(lastNumber)+1;
            }
            
            getSelect(function (costCenterOptions, base_tax, taxOptions) {
                $.each(selectedRowsData, function(index, rowData) {
                    var $newRow = $('<tr>');
    
                    var unit_price = rowData.Price;

                    var rate = $('input[name="rate"]').val();
                    rate = rate ? rate : 0;
                    var qty = 1;
                    
                    // unit_price.replace(".", "");
                    var base_unit_price  = unit_price * rate;
                    var total_price      = unit_price * qty;
                    var total_base_price = base_unit_price * qty;
    
                    // Buat kolom dengan input sesuai dengan data yang ada
                    $newRow.append('<td><input type="hidden" name="sysid_item[]" value="' + rowData.SysId + '"><p class="mt-1">'+ no +'</p></td>');
                    $newRow.append('<td><input class="form-control form-control-sm" name="item_code[]" type="text" value="' + rowData.Item_Code + '" readonly></td>');
                    $newRow.append('<td><input class="form-control form-control-sm" name="item_name[]" type="text" value="' + rowData.Item_Name + '" readonly></td>');

                    // <input type="hidden" name="uom_id[]" value="`+ rowData.Uom_Id +`">
                    $newRow.append(`<td>
                        <input class="form-control form-control-sm" name="uom[]" type="text" value="`+ rowData.Uom +`" readonly>
                    </td>`);
                    
                    $newRow.append(`<td>
                        <select class="form-control form-control-sm select2 select-costcenter select2-costcenter`+ no + `" name="costcenter[]" data-no="` + no + `"></select>
                    </td>`);
    
                    $newRow.append('<td><input class="form-control form-control-sm only-number qty qty'+ no +'" type="text" name="qty[]" value="1" data-no="'+ no +'"></td>');
    
                    $newRow.append(`
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm only-number discount_percent discount_percent`+ no +`" value="0" name="discount_percent[]" data-no="`+ no +`"> 
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    %
                                </div>
                            </div>
                        </div>
                    </td>`);

                    $newRow.append('<td><input class="form-control form-control-sm value_discount'+ no +'" name="value_discount[]" type="text" value="0.00" data-no="'+ no +'" readonly></td>');
    
                    var base_data_tax = '';
                    if ($('.base_tax').length === 0) {
                        base_data_tax = base_tax;
                    }
                    $newRow.append(`<td>
                        `+ base_data_tax +`
                        <select class="form-control form-control-sm select2 select-tax1 select2-tax1`+ no + `" name="type_tax1[]" data-no="` + no + `"></select>

                        <input class="value_tax1`+ no +`" name="value_tax1[]" type="hidden" value="">
                    </td>`);

                    $newRow.append(`<td>
                        <select class="form-control form-control-sm select2 select-tax2 select2-tax2`+ no + `" name="type_tax2[]" data-no="` + no + `"></select>
                        
                        <input class="value_tax2`+ no +`" name="value_tax2[]" type="hidden" value="">
                    </td>`);

                    $newRow.append('<td><input class="form-control form-control-sm unit_price unit_price'+ no + '" type="text" name="unit_price[]" value="' + currencyFormat(unit_price) + '" data-no="'+ no +'" readonly></td>');

                    $newRow.append('<td><input class="form-control form-control-sm base_unit_price'+ no + '" type="text" name="base_unit_price[]" value="' + currencyFormat(base_unit_price) + '" readonly></td>');

                    $newRow.append('<td><input class="form-control form-control-sm total_price total_price'+ no + '" type="text" name="total_price[]" value="' + currencyFormat(total_price) + '" data-no="' + no + '" readonly></td>');

                    $newRow.append('<td><input class="form-control form-control-sm total_base_price'+ no + '" type="text" name="total_base_price[]" value="' + currencyFormat(total_base_price) + '" readonly></td>');

                    $newRow.append('<td><textarea rows="2" class="form-control form-control-sm" name="remarks[]" placeholder="Tulis Notes ..."></textarea></td>');
                    
                    $newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');
    
                    // Inisialisasi Select2 untuk elemen select dalam baris baru
                    var $select2CostCenter = $newRow.find('.select2-costcenter' + no);
                    $select2CostCenter.html(costCenterOptions).select2();
                    
                    var $select2Tax1 = $newRow.find('.select2-tax1' + no);
                    $select2Tax1.html(taxOptions).select2();

                    var $select2Tax2 = $newRow.find('.select2-tax2' + no);
                    $select2Tax2.html(taxOptions).select2();
    
                    // Masukkan baris baru ke dalam tabel tujuan
                    $tableItem.append($newRow);
    
                    // Set nilai default mata uang
                    // $('.unit_price' + no).val(price);
                    no++;
                });

                $(".flatpickr").flatpickr({
                    dateFormat: "d F Y"
                });
                
                $(".flatpickr").removeAttr('readonly');
            });

            calculate_base_price($('input[name="rate"]').val());
            $('#no_data_item').hide('slow');
            $('.footer-table').show('slow');
            $('#modal_tambah_item').modal('hide');
        });
    }

    function calculateDiscountPrice(no) {
        var discount_percent = formatAritmatika($(".discount_percent" + no).val());
        var unit_price = formatAritmatika($(".unit_price" + no).val());
        var qty = formatAritmatika($(".qty" + no).val());
        var total_price = unit_price * qty;
    
        // Hitung jumlah diskon
        var discountAmount = (total_price * discount_percent) / 100;
        
        // Hitung harga setelah diskon
        var priceAfterDiscount = total_price - discountAmount;
    
        var select_tax_1 = $('.select2-tax1' + no).val();
        let taxAmount1 = 0;
        if (select_tax_1) {
            let base_tax = formatAritmatika($('.base_tax' + select_tax_1).val());
            
            taxAmount1 = (priceAfterDiscount * base_tax) / 100;
        }

        var select_tax_2 = $('.select2-tax2' + no).val();
        let taxAmount2 = 0;
        if (select_tax_2) {
            let base_tax    = formatAritmatika($('.base_tax' + select_tax_2).val());
            
            taxAmount2 = (priceAfterDiscount * base_tax) / 100;
        }

        priceAfterDiscount = priceAfterDiscount + taxAmount1 + taxAmount2;

        $(".value_discount" + no).val(currencyFormat(discountAmount));
        $(".total_price" + no).val(currencyFormat(priceAfterDiscount));
        $('.value_tax1' + no).val(taxAmount1);
        $('.value_tax2' + no).val(taxAmount2);
    }
    
    $("#table_item").on('keyup blur', '.qty, .discount_percent', function() {
        var no = $(this).data('no');

        calculateDiscountPrice(no);
    });

    $("#table_item").on('change', '.select-tax1, .select-tax2',function() {
        var no = $(this).data('no');
        
        calculateDiscountPrice(no);
    });
    
    $('input[name="rate"]').on('keyup blur', function() {
        if ($('#table_item tbody').children().length != 0) {
            var rate = $(this).val();
            
            calculate_base_price(rate);
        }
    });

    function calculate_base_price(rate) {
        $(".qty").each(function(){
            // Ambil nilai dari atribut data-no
            var no = $(this).data('no');
            var qty = $(this).val();
            var unit_price = formatAritmatika($('.unit_price' + no).val());
            var base_unit_price = unit_price * rate;

            $('.base_unit_price' + no).val(currencyFormat(base_unit_price));
            $('.total_base_price' + no).val(currencyFormat(base_unit_price * qty));
        });
    }

    $(document).on('click', '#calculate', function() {
        calculate_all();
    });

    function calculate_all() {
        let sum_total_price     = 0;
        let sum_value_tax_1     = 0;
        let sum_value_tax_2     = 0;
        $(".total_price").each(function(){
            var no = $(this).data('no');

            var value_tax_1 = $('.value_tax1' + no).val() ? $('.value_tax1' + no).val() : '0.00';
            var value_tax_2 = $('.value_tax2' + no).val() ? $('.value_tax2' + no).val() : '0.00';

            sum_total_price += +formatAritmatika($(this).val());
            sum_value_tax_1 += +formatAritmatika(value_tax_1);
            sum_value_tax_2 += +formatAritmatika(value_tax_2);
        });

        // console.log(sum_total_price);

        let total_amount = sum_total_price;

        // Hitung jumlah diskon
        var percent_discount_all = $('input[name="percent_discount_all"]').val();
        var discount = 0;
        if (percent_discount_all) {
            var discount = (total_amount * $('input[name="percent_discount_all"]').val()) / 100;
            discount = discount;
        }

        let grand_total = total_amount - discount;

        $('input[name="total_amount"]').val(currencyFormat(total_amount));
        $('input[name="total_discount"]').val(currencyFormat(discount));
        $('input[name="total_tax_1"]').val(currencyFormat(sum_value_tax_1));
        $('input[name="total_tax_2"]').val(currencyFormat(sum_value_tax_2));
        $('input[name="grand_total"]').val(currencyFormat(grand_total));
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
    $('.footer-table').hide('slow');
    $(document).on('click', '.remove_item_dtl', function() {
        var trIndex = $(this).closest("tr").index();
        
        $(this).closest("tr").remove();
        if(trIndex == 0) {
            $('#no_data_item').show('slow');
            $('.footer-table').hide('slow');
        }
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
        calculate_all();

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
			url: $('meta[name="base_url"]').attr('content') + "Inventory/ReceivingItem/store",
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
    
	$("#save_faktur_bc").click(function (e) {
		e.preventDefault();
        
        var formData = $("#form_faktur_bc").serialize();
        console.log(formData);
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Inventory/ReceivingItem/store_faktur_bc",
			data: formData,
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: response.msg,
						showCancelButton: false,
					}).then((result) => {
                        $('#modal_faktur_bc').modal('hide');
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
	});
    // ------------------- FUNGSI ADD - END ----------------- //
    // ======================================================== //

    // ----------------------- BROWSE DATA ------------------ //
    // -- Reload Data Person
    function reloadDataPO(){
        $("#table-po").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            lengthMenu: [
                [10, 25, 50, 10000],
                [10, 25, 50, 'All']
            ],
            select: true,
            ajax: {
                url: $('meta[name="base_url"]').attr('content') + "Inventory/ReceivingItem/DT_listdata_PO",
                dataType: "json",
                type: "POST",
            },
            columns: [
                {
                    data: "Doc_No",
                },
                {
                    data: "Doc_Date",
                    render: function (data, type, row, meta) {
                        return moment(data).format("DD MMMM YYYY");
                    }
                },
                {
                    data: "Account_Name"
                },
                {
                    data: "Address"
                },
                {
                    data: "ETA",
                    render: function (data, type, row, meta) {
                        return moment(data).format("DD MMMM YYYY");
                    }
                },
                {
                    data: "ETD",
                    render: function (data, type, row, meta) {
                        return moment(data).format("DD MMMM YYYY");
                    }
                },
                {
                    data: "Amount",
                    render: function (data, type, row, meta) {
                        return currencyFormat(data);
                    }
                },
                {
                    data: "Currency",
                },
                {
                    data: "Note",
                },
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
                $("#table-po tbody td").addClass("blurry");
            },
            language: {
                processing: '<i style="color:#4a4a4a" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"></span><p><span style="color:#4a4a4a" style="text-align:center" class="loading-text"></span> ',
            },
            drawCallback: function () {
                $("#table-po tbody td").addClass("blurry");
                setTimeout(function () {
                    $("#table-po tbody td").removeClass("blurry");
                });
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    }

	$("#btn-select-po").click(function (e) {
        table = $("#table-po").DataTable();
        data = table.rows('.selected').data()[0];
        
        $('input[name="po_no_id"]').val(data.SysId);
        $('input[name="po_no"]').val(data.Doc_No);
        $('input[name="po_date"]').val(moment(data.Doc_Date).format("DD MMMM YYYY"));
        $('input[name="isAsset"]').val(data.IsAsset);
        $('input[name="base_amount"]').val(data.Base_Amount);
        $('input[name="vendor_id"]').val(data.SysId_Vendor);
        $('input[name="vendor"]').val(data.Account_Name);
        $('input[name="vendor_address"]').val(data.Address);
	
		$.ajax({
			dataType: "json",
			type: "POST",
			url: $('meta[name="base_url"]').attr('content') + "Inventory/ReceivingItem/GetDataDtlPO",
			data: {
                sysid : data.SysId
            },
			success: function (response) {
				Swal.close()
				if (response.code == 200) {
                    // DETAIL //
                    var $tableItem = $('#table_item tbody');
                    $tableItem.empty();
                    
                    getSelect(function (warehouseOptions) {
                        var no = 1;
                        $.each(response.data_dtl, function(index, rowData) {
                            var $newRow = $('<tr>');
    
                            $newRow.append('<td><p class="mt-1">'+ no +'</p></td>');
                            $newRow.append('<td><input class="form-control form-control-sm" name="item_code[]" type="text" value="' + rowData.Item_Code + '" readonly></td>');
                            $newRow.append('<td><input class="form-control form-control-sm" name="item_name[]" type="text" value="' + rowData.Item_Name + '" readonly></td>');
        
                            $newRow.append(`<td>
                                <input class="form-control form-control-sm" name="uom[]" type="text" value="`+ rowData.Uom +`" readonly>
                            </td>`);
                            
                            $newRow.append('<td><input class="form-control form-control-sm po_qty'+ no +'" type="text" name="po_qty[]" value="'+ rowData.Qty_PO +'" readonly></td>');
                            
                            $newRow.append(`<td>
                                <select class="form-control form-control-sm select2 select-warehouse select2-warehouse`+ no + `" name="warehouse[]""></select>
                            </td>`);
        
                            $newRow.append('<td><input class="form-control form-control-sm outstanding'+ no +'" type="text" name="outstanding[]" value="'+ rowData.Qty_Outstanding +'" data-no="'+ no +'" readonly></td>');

                            $newRow.append('<td><input class="form-control form-control-sm balance'+ no +'" type="text" name="balance[]" value="0" data-no="'+ no +'" readonly></td>');
                            
                            $newRow.append('<td><input class="form-control form-control-sm received_now received_now'+ no + '" type="text" name="received_now[]" value="' +  rowData.Qty_Outstanding + '" data-no="'+ no +'"></td>');
                            
                            $newRow.append('<td><input class="form-control form-control-sm unit_price'+ no +'" type="text" value="'+ currencyFormat(rowData.Unit_Price) +'" data-no="'+ no +'" readonly></td>');
                            
                            $newRow.append('<td class="text-center"><a href="javascript:void(0);" class="remove_item_dtl"><span class="fa fa-times"></span></a></td>');
            
                            // Masukkan baris baru ke dalam tabel tujuan
                            $tableItem.append($newRow);
    
                            // Inisialisasi Select2 untuk elemen select dalam baris baru
                            var $select2warehouse = $newRow.find('.select2-warehouse' + no);
                            $select2warehouse.html(warehouseOptions).select2();
                            
                            no++;
                        });
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
        $("#POModal").modal("hide");
	});

    $("#table_item").on('keyup blur', '.received_now', function() {
        var no = $(this).data('no');

        var received_now    = $(this).val();
        var po_qty          = $(".outstanding" + no).val();
        
        let balance = po_qty - received_now;

        if (balance < 0) {
            toastr.error('Received Now Tidak Boleh Melebihi PO Quantity', 'Information', {
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

            balance = 0;
            $(".received_now" + no).val(po_qty);
        }
        
        $(".balance" + no).val(balance);
    });

    $("#btn-browse-po").click(function (event) {
        $('#POModal').modal('show');
        reloadDataPO();
    });
    // -- Reload Data Person - END
    // ------------------- BROWSE DATA - END ------------------ //

    // ----------- Fungsi Get Select For Detail --------- //
    function getSelect(callback) {
        $.ajax({
            dataType: "json",
            type: "POST",
            url: $('meta[name="base_url"]').attr('content') + "Inventory/ReceivingItem/getSelect",
            success: function (response) {
                if (response.code == 200) {
                    var option_warehouse = '';
                    $.each(response.warehouse, function (index, rowData) {
                        option_warehouse += '<option value="' + rowData.Warehouse_ID + '">' + rowData.Warehouse_Name + '</option>';
                    });

                    callback(option_warehouse); // Pass the data to the callback
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Gagal Mendapatkan Select Detail",
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
    // -------- Fungsi Get Select For Detail - END --------- //
    
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

    function formatAritmatika(str) {
        return str ? str.replace(/,/g, '') : '0';
    }

    function currencyFormat(num, decimal = 4) {
        return accounting.formatMoney(num, "", decimal, ",", ".");
    }

});
