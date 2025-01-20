<div class="modal fade" id="modal_detail_size_lot" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title"><?= $title_modal ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <?php $li = $lpb_dtl; ?>
                    <table class="table table-sm display compact dt-nowrap table-bordered" id="tbl-lpb">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">No. Bundle</th>
                                <th class="text-center text-white" style="max-width: 150px;">Jenis/Item</th>
                                <th class="text-center text-white" style="min-width: 150px;">Kubikasi (M3)</th>

                                <th class=" text-center" style="min-width: 50px;"><i class="fas fa-print text-white"></i></th>
                                <th class="text-center text-white" style="width: 15%;">Penempatan</th>
                            </tr>
                        </thead>
                        <tbody id="main-tbody">
                            <tr class="default-row border border-primary" data-pk="<?= $li->sysid ?>">
                                <td class="nomor text-center align-middle"><?= $li->flag ?></td>
                                <td class="lot text-center align-middle"><?= $li->no_lot ?></td>
                                <td class="ukuran text-center align-middle"><?= $li->deskripsi ?> (<?= $li->kode ?>)</td>
                                <td class="text-center align-middle" data-pk="<?= $li->sysid ?>"><?= floatval($li->kubikasi) ?></td>
                                <td class="text-center align-middle">
                                    <?php if ($li->lot_printed == 1) : ?>
                                        <button type="button" data-pk="<?= $li->sysid ?>" title="sudah print" class="btn btn-xs bg-gradient-success print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>
                                    <?php else : ?>
                                        <button type="button" data-pk="<?= $li->sysid ?>" title="belum print" class="btn btn-xs bg-gradient-danger print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>
                                    <?php endif; ?>
                                </td>
                                <!-- <php if ($checker > 0) : ?> -->
                                <td class="text-center align-middle">
                                    <span class="form-group">
                                        <select class="form-control form-control-xs" required data-pk="<?= $li->sysid ?>" style="width: 100%;">
                                            <option value="<?= $li->placement ?>" selected><?= $li->Warehouse_Name ?></option>
                                        </select>
                                    </span>
                                </td>
                                <!-- <php endif; ?> -->
                            </tr>
                            <tr>
                                <td colspan="6" class="bg-light">
                                    <table cellpadding="5" cellspacing="0" border="0" class="ml-4 my-2 table-mini" style="width: 85vh;">
                                        <thead>
                                            <th>No.</th>
                                            <th>Ukuran</th>
                                            <th>Qty Diterima</th>
                                            <th>M3 Diterima</th>
                                            <th>Qty Afkir</th>
                                            <th>M3 Afkir</th>
                                            <th>Qty Available</th>
                                            <th>M3 Available</th>
                                        </thead>
                                        <tbody class="bg-white">
                                            <?php
                                            $childs = $this->db->get_where('qview_dtl_size_item_lpb', ['Id_Lot' => $li->sysid])->result()
                                            ?>
                                            <?php foreach ($childs as $child) : ?>
                                                <tr data-pk="<?= $child->SysId ?>">
                                                    <td class="text-center align-middle"><?= $child->flag ?></td>
                                                    <td class="align-middle text-center"><?= $child->Size_Code ?></td>
                                                    <td class="align-middle text-right" data-pk="<?= $child->SysId ?>" sty><?= floatval($child->Qty) ?></td>
                                                    <td class="align-middle text-right" data-pk="<?= $child->SysId ?>" sty><?= floatval($child->Qty) * floatval($child->Cubication) ?></td>
                                                    <td class="align-middle text-right" data-pk="<?= $child->SysId ?>" sty><?= floatval($child->Qty_Afkir) ?></td>
                                                    <td class="align-middle text-right" data-pk="<?= $child->SysId ?>" sty><?= floatval($child->Qty_Afkir) * floatval($child->Cubication) ?></td>
                                                    <td class="align-middle text-right" data-pk="<?= $child->SysId ?>" sty><?= floatval($child->Qty_Usable) ?></td>
                                                    <td class="align-middle text-right" data-pk="<?= $child->SysId ?>" sty><?= floatval($child->Qty_Usable) * floatval($child->Cubication) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="container-fluid">
                    <p class="text-center font-weight-bold bg-warning" style="margin-bottom: -5px;">Identitas Lot</p>
                    <table class="table-sm table-striped table-bordered display compact" id="tbl_identity_lot" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="col text-center text-white">NO.LPB</th>
                                <th class="col text-center text-white">SUPPLIER</th>
                                <th class="col text-center text-white">MATERIAL</th>
                                <!-- <th class="col text-center text-white">TEBAL</th>
                                <th class="col text-center text-white">LEBAR</th>
                                <th class="col text-center text-white">PANJANG</th> -->
                                <th class="col text-center text-white">GRADER</th>
                                <th class="col text-center text-white">LEGALITAS</th>
                                <th class="col text-center text-white">PENILAIAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td class="text-center"><?= $dtl->lpb ?></td>
                            <td class="text-center"><?= $dtl->nama ?></td>
                            <td class="text-center"><?= $dtl->deskripsi ?></td>
                            <!-- <td class="text-center"><= floatval($dtl->tebal) . 'CM' ?></td>
                            <td class="text-center"><= floatval($dtl->lebar) . 'CM' ?></td>
                            <td class="text-center"><= floatval($dtl->panjang) . 'CM' ?></td> -->
                            <td class="text-center"><?= $dtl->grader ?></td>
                            <td class="text-center"><?= $dtl->legalitas ?></td>
                            <td class="text-center"><?= $dtl->penilaian ?></td>
                        </tbody>
                    </table>
                </div>
                <hr class="devider" style="border: solid black 1px;">
                <div class="container-fluid">
                    <p class="text-center font-weight-bold bg-warning" style="margin-bottom: -5px;">Detail Proses Oven</p>
                    <table class="table-sm table-striped table-bordered display compact" id="tbl_hst_oven" style="width: 100%;">
                        <thead style="background-color: #3B6D8C;">
                            <tr>
                                <th class="col text-center text-white"><i class="fas fa-map-marker-alt"></i> OVEN</th>
                                <th class="col text-center text-white"><i class="fas fa-calendar"></i> WAKTU MASUK</th>
                                <th class="col text-center text-white"><i class="fas fa-clipboard-list"></i> REMARK MASUK</th>
                                <th class="col text-center text-white"><i class="fas fa-user"></i> MASUK</th>
                                <th class="col text-center text-white"><i class="fas fa-clock"></i> TIMER</th>
                                <th class="col text-center text-white"><i class="fas fa-calendar"></i> WAKTU KELUAR</th>
                                <th class="col text-center text-white"><i class="fas fa-user"></i> KELUAR</th>
                                <th class="col text-center text-white"><i class="fas fa-clipboard-list"></i> REMARK KELUAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td class="text-center"><?= $dtl->oven ?></td>
                            <td class="text-center"><?= $dtl->masuk_oven_pada ?></td>
                            <td class="text-center"><?= $dtl->remark_into_oven ?>&nbsp;</td>
                            <td class="text-center"><?= $dtl->masuk_oven_oleh ?></td>
                            <td class="text-center">
                                <?php
                                if ($dtl->masuk_oven_pada == '') {
                                    echo null;
                                } else {
                                    $datetime1 = new DateTime($dtl->masuk_oven_pada);
                                    $datetime2 = new DateTime($dtl->keluar_oven_pada);
                                    $interval = $datetime2->diff($datetime1);
                                    echo $interval->format('%d') . " Hari, " . $interval->format('%h') . " Jam";
                                }
                                ?>
                            </td>
                            <td class="text-center"><?= $dtl->keluar_oven_pada ?></td>
                            <td class="text-center"><?= $dtl->keluar_oven_oleh ?></td>
                            <td class="text-center"><?= $dtl->remark_out_of_oven ?></td>

                        </tbody>
                    </table>
                </div>
                <hr class="devider" style="border: solid black 1px;">
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-center font-weight-bold bg-warning" style="margin-bottom: -5px;">Detail Alokasi Produksi</p>
                        <table class="table-sm table-striped table-bordered display compact" id="tbl_hst_alloc" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="col text-center text-white"><i class="fas fa-user"></i> ALOKASI</th>
                                    <th class="col text-center text-white"><i class="fas fa-calendar"></i> WAKTU ALOKASI</th>
                                    <th class="col text-center text-white"><i class="fas fa-clipboard-list"></i> REMARK ALOKASI</th>
                                    <th class="col text-center text-white">ALLOCATED TO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td class="text-center"><?= $dtl->alokasi_oleh ?>&nbsp;</td>
                                <td class="text-center"><?= $dtl->alokasi_pada ?></td>
                                <td class="text-center"><?= $dtl->remark_to_prd ?></td>
                                <td class="text-center"><?= $dtl->nama_cost_center ?></td>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <p class="text-center font-weight-bold bg-warning" style="margin-bottom: -5px;">Informasi Tambahan</p>
                        <table class="table-sm table-striped table-bordered display compact" id="tbl_qty" style="width: 100%;">
                            <thead style="background-color: #3B6D8C;">
                                <tr>
                                    <th class="col text-center text-white">NO. DOC LEGALITAS</th>
                                    <th class="col text-center text-white">ASAL DAERAH</th>
                                    <th class="col text-center text-white">TOTAL Pcs KIRIMAN</th>
                                    <th class="col text-center text-white">TOTAL KUBIKASI KIRIMAN</th>
                                    <th class="col text-center text-white">UANG BONGKAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td class="text-center"><?= $dtl->no_legalitas ?></td>
                                <td class="text-center"><?= $dtl->asal_kiriman ?></td>
                                <td class="text-center"><?= $this->help->FormatIdr($dtl->jumlah_pcs_kiriman) ?></td>
                                <td class="text-center"><?= $this->help->FormatIdr($dtl->jumlah_kiriman) ?></td>
                                <td class="text-center"><?= $this->help->FormatIdr($dtl->tanggungan_uang_bongkar) ?></td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer float-right">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(document).ready(function() {
        $(document).on('click', '.print--lot', function() {
            let sysid = $(this).attr('data-pk');
            var Parent = $(this).parent();
            $.ajax({
                dataType: "json",
                type: "POST",
                url: $('meta[name="base_url"]').attr('content') + "TrxWh/ProcessGrid/update_Asprinted_single_lot",
                data: {
                    sysid: sysid
                },
                beforeSend: function() {
                    $(this).prop('disabled', true);
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
                        window.open($('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/tempelan_single_lot/" + sysid, '_blank');
                        Parent.html(`<button type="button" data-pk="${sysid}" title="sudah print" class="btn btn-sm bg-gradient-success print--lot">&nbsp;<i class="fas fa-print"></i>&nbsp;</button>`)
                    } else if (response.code == 201) {
                        window.open($('meta[name="base_url"]').attr('content') + "TrxWh/Lpb/tempelan_single_lot/" + sysid, '_blank');
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: response.msg,
                            showConfirmButton: false,
                            timer: 2500,
                            footer: '<a href="javascript:void(0)">Notifikasi System</a>'
                        });
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
        })
    })
    $(document).ready(function() {
        $('#tbl_identity_lot').DataTable({
            "ordering": false,
            "searching": false,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "info": false,
        });
        $('#tbl_hst_oven').DataTable({
            "ordering": false,
            "searching": false,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "info": false,
        });
        $('#tbl_hst_alloc').DataTable({
            "ordering": false,
            "searching": false,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "info": false,
        });
        $('#tbl_qty').DataTable({
            "ordering": false,
            "searching": false,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "info": false,
        });
    })
</script>