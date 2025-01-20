<div class="modal fade" id="m_edit_so" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <form id="form_hdr_po" method="post" action="#">
                <div class="modal-header">
                    <h5 class="modal-title">NO. PO CUSTOMER : <?= $Hdr->No_Po_Customer ?> <u>(<?= $Hdr->Doc_No_Internal ?>)</u></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="SysId" id="SysId" value="<?= $Hdr->SysId ?>">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Purchase Order Number :</label>
                                                <input type="text" class="form-control form-control-sm" readonly name="po_number" id="po_number" value="<?= $Hdr->No_Po_Customer ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Customer :</label>
                                                <select class="form-control form-control-sm" required name="customer" id="customer">
                                                    <option selected value="<?= $Hdr->ID_Customer ?>"><?= $Cust->Customer_Name ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Date Document Receive :</label>
                                                <input type="text" readonly class="form-control form-control-sm datepicker" data-toggle="datetimepicker" data-target="#tgl_terbit" name="tgl_terbit" id="tgl_terbit" value="<?= $Hdr->Tgl_Terbit ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Term Of Payment :</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" min="7" maxlength="3" class="form-control form-control-sm" name="term_of_payment" id="term_of_payment" placeholder="Term Of Payment..." value="<?= $Hdr->Term_Of_Payment ?>" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><b>DAY</b> &nbsp;&nbsp; <i class="fas fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label><?= $PPn->Name ?> :</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="PPn" id="PPn" type="checkbox" <?php if ($Hdr->PPn != '0') echo 'checked' ?> value="<?= floatval($PPn->Persentase) ?>">
                                                    <label class="form-check-label"><?= floatval($PPn->Persentase) ?> %</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Condition TOP :</label>
                                                <select class="form-control form-control-sm" required name="condition_top" id="condition_top">
                                                    <option <?php if ($Hdr->Remark_TOP == 'AFTER INVOICE RECEIVED') echo 'selected'; ?> value="AFTER INVOICE RECEIVED">AFTER INVOICE RECEIVED</option>
                                                    <option <?php if ($Hdr->Remark_TOP == 'AFTER PO CLOSE') echo 'selected'; ?> value="AFTER PO CLOSE">AFTER PO CLOSE</option>
                                                    <option <?php if ($Hdr->Remark_TOP == 'AFTER GOODS RECEIVED NOTE') echo 'selected'; ?> value="AFTER GOODS RECEIVED NOTE">AFTER GOODS RECEIVED NOTE</option>
                                                    <option <?php if ($Hdr->Remark_TOP == 'AFTER PO RECEIVED') echo 'selected'; ?> value="AFTER PO RECEIVED">AFTER PO RECEIVED</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Term Of Delivery :</label>
                                                <input type="text" readonly class="form-control form-control-sm datepicker" data-toggle="datetimepicker" data-target="#term_of_delivery" name="term_of_delivery" id="term_of_delivery" placeholder="Term of delivery..." value="<?= $Hdr->Term_Of_Delivery ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Customer Address :</label>
                                                <input type="hidden" id="id_address" name="id_address" value="<?= $Hdr->ID_Address ?>">
                                                <div class="input-group input-group-sm">
                                                    <textarea class="form-control form-control-sm" readonly name="customer_address" id="customer_address" placeholder="Customer Address" required rows="3"><?= $Hdr->Customer_Address ?></textarea>
                                                    <div class="input-group-append">
                                                        <button type="button" id="btn--list--address" class="btn btn-success">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Koresponden :</label>
                                                <textarea class="form-control form-control-sm" name="koresponden" id="koresponden" placeholder="Name Sender/Contact/Email/Hp/WhatsApp & Etc...." required rows="3"><?= $Hdr->Koresponden ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Note :</label>
                                                <textarea class="form-control form-control-sm" name="note" id="note" placeholder="Note..." required rows="3"><?= $Hdr->Note ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submit--hdr"><i class="fas fa-save"></i> | Submit Change</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(function() {
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

        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            autoclose: true,
            allowClear: true,
            todayHighlight: true,
            orientation: 'bottom',
        });

        $('select[name="customer"]').select2({
            minimumInputLength: 0,
            allowClear: true,
            placeholder: '-Pilih Supplier-',
            ajax: {
                dataType: 'json',
                url: $('meta[name="base_url"]').attr('content') + "PrintBarcodeProduct/select_customer",
                delay: 800,
                data: function(params) {
                    return {
                        search: params.term
                    }
                },
                processResults: function(data, page) {
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.text
                            };
                        })
                    };
                },
                cache: true
            }
        })

        $('#form_hdr_po').validate({
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
        $.validator.setDefaults({
            debug: true,
            success: 'valid'
        });

        $('#submit--hdr').click(function(e) {
            e.preventDefault();
            let po_number = $('#po_number').val();
            if ($("#form_hdr_po").valid()) {
                Swal.fire({
                    title: 'System Message!',
                    text: `Apakah anda yakin untuk Melakukan update data PO ${po_number} ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Init_Form_Hdr_PO()
                    }
                })
            } else {
                $('html, body').animate({
                    scrollTop: ($('.error:visible').offset().top - 200)
                }, 400);
            }
        });

        function Init_Form_Hdr_PO() {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: $('meta[name="base_url"]').attr('content') + "SalesOrder/Store_Update_So",
                data: $('#form_hdr_po').serialize(),
                beforeSend: function() {
                    $('#submit--hdr').prop("disabled", true);
                    Swal.fire({
                        title: 'Loading....',
                        html: '<div class="spinner-border text-primary"></div>',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    })
                },
                success: function(response) {
                    Swal.close()
                    if (response.code == 200) {
                        Toast.fire({
                            icon: 'success',
                            title: response.msg
                        });
                        $('#m_edit_so').modal('hide');
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.msg
                        });
                        $('#submit--hdr').prop("disabled", false);
                        $("#TableData").DataTable().ajax.reload(null, false)
                    }
                },
                error: function() {
                    Swal.close()
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan teknis segera lapor pada admin!',
                        footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                    });
                }
            });
        }

        $('#btn--list--address').on('click', function() {
            if ($('#customer').val() == null || $('#customer').val() == '' || $('#customer').val() == undefined) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'You have to choose the customer first!',
                    footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                });
            }
            $.ajax({
                type: "GET",
                url: $('meta[name="base_url"]').attr('content') + "Master/List_Address_Customer_Pick",
                data: {
                    sysid: $('#customer').val()
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Loading....',
                        html: '<div class="spinner-border text-primary"></div>',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    })
                },
                success: function(response) {
                    Swal.close()
                    $('#location-2').html(response);
                    $('#modal-list-address').modal('show');
                },
                error: function() {
                    Swal.close()
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan teknis segera lapor pada admin!',
                        footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                    });
                }
            });
        })
    })
</script>