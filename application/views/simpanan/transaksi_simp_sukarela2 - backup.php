<div class="panel panel-default panel-color">
    <div class="panel-body">
        <div class="row">
            <form id="fm_data" onsubmit="return false">
                <div class="row" id="div_anggota">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>NAK</label>
                            <input type="text" name="no_ang" id="no_ang" class="form-control" data-rule-required="true" autocomplete="off" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label>No. Pegawai</label>
                            <input type="text" id="no_peg" name="no_peg" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Perusahaan</label>
                            <div class="row" style="margin: 0px;">
                                <div class="col-md-2" style="padding: 0px;">
                                    <input type="text" id="kd_prsh" name="kd_prsh" class="form-control" readonly>
                                </div>
                                <div class="col-md-10" style="padding: 0 0 0 5px;">
                                    <input type="text" id="nm_prsh" name="nm_prsh" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nama Anggota</label>
                            <input type="text" id="nm_ang" name="nm_ang" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Departemen</label>
                            <div class="row" style="margin: 0px;">
                                <div class="col-md-3" style="padding: 0px;">
                                    <input type="text" id="kd_dep" name="kd_dep" class="form-control" readonly>
                                </div>
                                <div class="col-md-9" style="padding: 0 0 0 5px;">
                                    <input type="text" id="nm_dep" name="nm_dep" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Bagian</label>
                            <div class="row" style="margin: 0px;">
                                <div class="col-md-3" style="padding: 0px;">
                                    <input type="text" id="kd_bagian" name="kd_bagian" class="form-control" readonly>
                                </div>
                                <div class="col-md-9" style="padding: 0 0 0 5px;">
                                    <input type="text" id="nm_bagian" name="nm_bagian" class="form-control" readonly>
                                    <input type="hidden" name="status_keluar" id="status_keluar">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img src="<?php echo base_url('aset/gambar/no-image.png'); ?>" id="gambar_ktp" style="height: 250px" class="img-thumbnail" />
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Tanggal</label>
                                    <input type="text" name="tgl_simpan" id="tgl_simpan" class="form-control datepicker" onchange="cek_margin_simpanan_sukarela2('')" value="<?php echo date('d-m-Y'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>Jangka Waktu</label>
                                    <select id="tempo_bln" name="tempo_bln" class="form-control" required="" onchange="cek_margin_simpanan_sukarela2('')">
                                        <?php echo $tempo_bln; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Margin(%)</label>
                            <div class="input-group">
                                <input type="text" name="margin" id="margin" class="form-control" required="" onchange="cek_margin_simpanan_sukarela2('edit-margin')" data-rule-number="true" autocomplete="off" readonly="">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-info" onclick="open_ganti_margin()"><i class="fa fa-pencil"></i> Ganti Margin</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Margin per Bulan</label>
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="text" name="jml_margin_bln" id="jml_margin_bln" class="form-control number_format" readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jumlah</label>
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="text" id="jml_simpanan" name="jml_simpanan" class="form-control number_format" required="" onchange="cek_margin_simpanan_sukarela2('')" data-rule-number="true">
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <br>
                            <button type="button" class="btn btn-primary" onclick="simpan()">Simpan</button>
                            <button type="button" class="btn btn-default" onclick="batal()">Batal</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>No. Sertifikat SS2</label>
                            <input type="text" name="no_ss2" id="no_ss2" class="form-control" autocomplete="off" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label>Jumlah Simpanan Aktif</label>
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="text" id="saldo_akhir" name="saldo_akhir" class="form-control input-lg number_format" readonly="" value="0" style="font-weight: bolder;">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-heading">
        <div class="pull-right">
            <button class="btn btn-info btn-small" onclick="cetak_ss2()"> <i class="fa fa-print"></i> Cetak</button>
        </div>
        <button class="btn btn-danger btn-small" onclick="del()"> <i class="fa fa-trash"></i> Hapus</button>
        <button class="btn btn-warning btn-small" onclick="open_no_ss2()"> Update No. SS2</button>
        <button class="btn btn-primary btn-small" onclick="open_tarik_dipercepat()"> <i class="fa fa-money"></i> Penarikan dipercepat</button>
        <button class="btn btn-warning btn-small" onclick="open_edit_tglDebet()"> Edit Tgl Debet</button>
        <!-- <button class="btn btn-primary btn-small" onclick="open_edit_jangka()"> <i class="fa fa-pencil"></i> Edit Jangka SS2</button> -->
    </div>
    <div class="panel-body">
        <table id="tabel_sukarela2" class="table table-bordered table-condensed table-hover table-striped nowrap" width="100%">
            <thead>
                <tr>
                    <th width="50">No.</th>
                    <th>Status</th>
                    <th>Bukti Simpanan</th>
                    <th>No. SS2</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Jangka</th>
                    <th>Tgl JT</th>
                    <th>Margin</th>
                    <th>Margin per Bulan</th>
                    <th>Umur (Bulan)</th>
                    <th>Margin Masuk Ke SS1</th>
                    <th>Tgl. Debet</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Penarikan Dipercepat</h4>
            </div>
            <div class="modal-body">
                <form id="form_dipercepat">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Bukti Simpanan</label>
                                <input type="text" name="no_simpan" id="no_simpan" class="form-control" readonly="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal JT</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" name="tgl_jt" id="tgl_jt" class="form-control" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" name="no_ang" id="no_ang">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jumlah Simpanan</label>
                                <div class="input-group">
                                    <div class="input-group-addon">Rp</div>
                                    <input type="text" name="jml_simpanan" id="jml_simpanan" class="form-control number_format" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Umur Simpanan</label>
                                <div class="input-group">
                                    <input type="text" name="umur_bulan" id="umur_bulan" class="form-control text-center" readonly="">
                                    <div class="input-group-addon">Bulan</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Margin Masuk ke SS1</label>
                                <div class="input-group">
                                    <div class="input-group-addon">Rp</div>
                                    <input type="text" name="jml_debet" id="jml_debet" class="form-control number_format" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Penarikan</label>
                                <input type="text" name="tgl_debet" id="tgl_debet" class="form-control datepicker" required="">
                            </div>
                        </div>
                        <input type="hidden" name="jml_denda" id="jml_denda">
                        <!-- <div class="col-md-3">
                            <div class="form-group">
                                <label>Jumlah Denda</label>
                                <div class="input-group">
                                    <div class="input-group-addon">Rp</div>
                                    <input type="text" name="jml_denda" id="jml_denda" class="form-control number_format" readonly="">
                                </div>
                            </div>
                        </div> -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="tarik_dipercepat()">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalss2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Nomor Sertifikat SS2</h4>
            </div>
            <div class="modal-body">
                <form id="form_noss2">
                    <div class="form-group">
                        <label>No. Sertifikat SS2</label>
                        <input type="text" name="no_ss2" id="no_ss2" class="form-control" autocomplete="off" style="text-transform: uppercase;">
                        <input type="hidden" name="no_simpan" id="no_simpan">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpan_no_ss2()">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalotorisasi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="true" style="z-index: 1060">
    <div class="modal-dialog modal-sm" style="width: 80%;">
        <div class="modal-content b-0 p-0">
            <div class="panel panel-fill panel-info">
                <div class="panel-heading">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="panel-title">Otorisasi Ganti Margin</h3>
                </div>
                <div class="panel-body">
                    <form id="fm_otorisasi">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="passwd" id="passwd" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label>Margin (%)</label>
                            <div class="input-group">
                                <input type="text" name="margin_baru" id="margin_baru" class="form-control number_format" required="" data-rule-number="true" autocomplete="off">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-success" onclick="do_ganti_margin()">Ganti Margin</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModaltgldebet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Edit Tgl Debet</h4>
            </div>
            <div class="modal-body">
                <form id="form_tgldebet">
                    <div class="form-group">
                        <label>Tanggal Debet</label>
                        <input type="text" name="tgl_debet" id="tgl_debet" class="form-control datepicker" required="">
                        <input type="hidden" name="no_simpan" id="no_simpan">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpan_tgldebet()">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModaljangka" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Edit Jangka</h4>
            </div>
            <div class="modal-body">
                <form id="form_jangka">
                    <div class="form-group">
                        <label>No. SS2</label>
                        <input type="text" name="no_ss2" id="no_ss2" class="form-control" readonly="">
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="text" name="jml_simpanan" id="jml_simpanan" class="form-control number_format" readonly="">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Tanggal Simpan</label>
                                <input type="text" name="tgl_simpan_jangka" id="tgl_simpan_jangka" class="form-control datepicker" required="">
                                <input type="hidden" name="no_simpan" id="no_simpan">
                            </div>
                            <div class="col-md-6">
                                <label>Jangka</label>
                                <select id="tempo_bln" name="tempo_bln" class="form-control" required="" onchange="cek_margin_simpanan_sukarela2_jangka('')">
                                    <?php echo $tempo_bln; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Margin(%)</label>
                        <div class="input-group">
                            <input type="text" name="margin" id="margin" class="form-control" required="" onchange="cek_margin_simpanan_sukarela2_jangka('edit-margin')" data-rule-number="true" autocomplete="off" readonly="">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-info" onclick="open_ganti_margin_jangka()"><i class="fa fa-pencil"></i> Ganti Margin</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Margin per Bulan</label>
                        <div class="input-group">
                            <div class="input-group-addon">Rp</div>
                            <input type="text" name="jml_margin_bln" id="jml_margin_bln" class="form-control number_format" readonly="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpan_jangka()">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalotorisasiJangka" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="true" style="z-index: 1060">
    <div class="modal-dialog modal-sm" style="width: 80%;">
        <div class="modal-content b-0 p-0">
            <div class="panel panel-fill panel-info">
                <div class="panel-heading">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="panel-title">Otorisasi Ganti Margin</h3>
                </div>
                <div class="panel-body">
                    <form id="fm_otorisasi_jangka">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="passwd" id="passwd" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label>Margin (%)</label>
                            <div class="input-group">
                                <input type="text" name="margin_baru" id="margin_baru" class="form-control number_format" required="" data-rule-number="true" autocomplete="off">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-success" onclick="do_ganti_margin_jangka()">Ganti Margin</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
edit_mode = 0;

function get_transaksi_simpanan() {
    $fm_data = $("#fm_data").serialize();
    url_tabel = situs + "simpanan/get_simpanan_sukarela2?" + $fm_data;
    tabel_id = "tabel_sukarela2";

    if ($.fn.DataTable.isDataTable("#" + tabel_id)) {
        $("#" + tabel_id).DataTable().ajax.url(url_tabel).load(function() {
            // $('#tabel_piutang').DataTable().responsive.recalc().responsive.rebuild();
        }, false);
    } else {
        $("#" + tabel_id).DataTable({
            scrollY: 350,
            scrollX: true,
            ordering: false,
            paging: true,
            searching: true,
            select: 'single',
            processing: true,
            serverSide: true,
            ajax: url_tabel,
            columns: [{
                data: "nomor",
                className: "text-right"
            }, {
                data: "is_debet",
                render: function(data, type, row, meta) {
                    if (data == "0") {
                        return "<i class=\"fa fa-check\"></i> Aktif";
                    } else {
                        $status = "";

                        if (row.is_dipercepat == "1") {
                            $status += "<i class=\"fa fa-info-circle\"></i> Dipercepat";
                        } else {
                            $status += "<i class=\"fa fa-info-circle\"></i> Expired";
                        }

                        if (row.is_diperpanjang == "1") {
                            $status += ", diperpanjang";
                        }

                        return $status;
                    }
                }
            }, {
                data: "no_simpan"
            }, {
                data: "no_ss2"
            }, {
                data: "tgl_simpan"
            }, {
                data: "jml_simpanan",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "tempo_bln",
                className: "text-center"
            }, {
                data: "tgl_jt",
                className: "text-center"
            }, {
                data: "margin",
                className: "text-center"
            }, {
                data: "jml_margin_bln",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "umur_bulan",
                className: "text-center"
            }, {
                data: "jml_debet",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "tgl_debet"
            }],
            initComplete: function() {
                var input = $("#" + tabel_id + "_filter input").unbind(),
                    self = this.api(),
                    $searchButton = $('<button>').addClass('btn btn-primary').text('Cari').click(function() {
                        self.search(input.val()).draw();
                    }),
                    $clearButton = $('<button>').addClass('btn btn-default').text('Reset').click(function() {
                        input.val('');
                        self.search('').draw();
                        // $searchButton.click();
                    });

                $("#" + tabel_id + "_filter").append("&nbsp;", $searchButton, "&nbsp;", $clearButton);
                $("#" + tabel_id + "_filter input").keyup(function(e) {
                    if (e.keyCode == "13") {
                        self.search(input.val()).draw();
                        $("html, body").animate({ scrollTop: $(document).height() }, 0);
                    }
                });
            }
        });

        $("#" + tabel_id).DataTable().off("draw.dt");
    }
}

var ev_get_anggota = 1;

$("#fm_data #no_ang").focus().on("change", function() {
    if (ev_get_anggota == 0) {
        ev_get_anggota = 1;

        get_anggota();
        get_transaksi_simpanan();
    }
}).keydown(function(e) {
    if (e.which == 13) {
        if (ev_get_anggota == 0) {
            ev_get_anggota = 1;

            get_anggota();
            get_transaksi_simpanan();
        }
    } else {
        ev_get_anggota = 0;
    }
});

function get_anggota() {
    $no_ang = $("#fm_data #no_ang").val();

    if ($no_ang) {
        $.ajax({
            url: situs + 'anggota/select_nasabah_by_noang',
            data: "q=" + $no_ang,
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
                proses();
            },
            success: function(data) {
                if (typeof(data.results) != "undefined" && data.results.length > 0) {
                    no_proses();
                    data_nasabah = data.results;

                    $("#fm_data #nm_ang").val(data_nasabah[0].nm_ang);
                    $("#fm_data #no_peg").val(data_nasabah[0].no_peg);
                    $("#fm_data #kd_prsh").val(data_nasabah[0].kd_prsh);
                    $("#fm_data #nm_prsh").val(data_nasabah[0].nm_prsh);
                    $("#fm_data #kd_dep").val(data_nasabah[0].kd_dep);
                    $("#fm_data #nm_dep").val(data_nasabah[0].nm_dep);
                    $("#fm_data #kd_bagian").val(data_nasabah[0].kd_bagian);
                    $("#fm_data #nm_bagian").val(data_nasabah[0].nm_bagian);
                    $("#fm_data #status_keluar").val(data_nasabah[0].status_keluar);

                    cek_saldo_simpanan_sukarela2();
                    cek_margin_simpanan_sukarela2();
                    get_ktp();
                } else {
                    $("#fm_data #no_ang").val('');
                    pesan('Data tidak ditemukan');
                }
            }
        });
    }
}

function get_ktp() {
    data_ajax = $("#fm_data").serialize();

    $.ajax({
        url: situs + "anggota/get_ktp",
        data: data_ajax,
        type: 'post',
        beforeSend: function() {
            // proses();
        },
        success: function(data) {
            $("#gambar_ktp").attr("src", data);
        }
    });
}

function cek_saldo_simpanan_sukarela2() {
    $data_form = $("#fm_data").serialize();

    $.ajax({
        url: situs + "simpanan/cek_saldo_simpanan_sukarela2",
        data: $data_form,
        type: 'post',
        success: function(data) {
            $("#saldo_akhir").val(data).trigger("change");
        }
    });
}

function cek_margin_simpanan_sukarela2($act) {
    $data_form = $("#fm_data").serialize() + "&act=" + $act;

    $.ajax({
        url: situs + "simpanan/get_margin_simpanan_berlaku",
        data: $data_form,
        type: 'post',
        dataType: 'json',
        success: function(data) {
            $("#fm_data #margin").val(data.margin);
            $("#fm_data #jml_margin_bln").val(data.margin_per_bulan).trigger("change");
        }
    });
}

function open_ganti_margin() {
    // if ($mode == 'gantijangka') {
    //     $('#myModalotorisasi').on('hidden.bs.modal', function() {
    //         $("body").addClass("modal-open");
    //     });
    // } else {
    //     $('#myModalotorisasi').off('hidden.bs.modal');
    // }

    $('#myModalotorisasi').modal('show');

    clear_form('fm_otorisasi');
    $("#fm_otorisasi #passwd").focus();
}

function do_ganti_margin() {
    validasi = $("#fm_otorisasi").valid();

    if (validasi) {
        konfirmasi = confirm("Anda Yakin?");

        if (konfirmasi) {
            data_ajax = $("#fm_otorisasi").serialize();

            $.ajax({
                url: situs + "setting/cek_otorisasi_sp",
                data: data_ajax,
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    proses();
                },
                success: function(data) {
                    if (data.status) {
                        $('#fm_data #margin').val(data.margin).trigger("change");
                        $('#myModalotorisasi').modal('hide');
                        no_proses();
                    } else {
                        pesan('Maaf Password otorisasi tidak benar');
                    }
                }
            });
        }
    }
}

function simpan() {
    validasi = $('#fm_data').valid();

    if (validasi) {
        if ($("#status_keluar").val() == "1") {
            alert("Anggota/Nasabah sudah berstatus keluar");
            return false;
        }

        konfirmasi = confirm("Anda yakin data sudah benar?");

        if (konfirmasi) {
            data_input = $('#fm_data').serialize();

            $.ajax({
                url: situs + 'simpanan/add_transaksi_simpanan_sukarela2',
                data: data_input,
                dataType: "JSON",
                type: "POST",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        if (edit_mode) {
                            $('#myModal').modal('hide');
                        } else {
                            $("#tempo_bln, #margin, #margin_per_bulan").val('');
                            $("#jml_simpanan").val('');
                            $("#div_anggota input").val('');
                            $("#no_ang").focus();
                            $("#jml_margin_bln, #saldo_akhir").val('0').trigger("change");

                            cek_saldo_simpanan_sukarela2();
                        }

                        get_transaksi_simpanan();
                    }
                }
            });
        }
    }
}

function batal() {
    $("#kd_jns_transaksi").val('').trigger("change");
    $("#nm_jns_transaksi").val('');
    $("#kredit_debet").val('');
    $("#jumlah").val('');
}

function del() {
    if ($.fn.DataTable.isDataTable("#tabel_sukarela2")) {
        row = $('#tabel_sukarela2').DataTable().row({
            selected: true
        }).data();

        if (row) {
            prompt = confirm("Anda Yakin Ingin Menghapus Data Ini?");

            if (prompt) {
                $.ajax({
                    url: situs + "simpanan/del_transaksi_simpanan_sukarela2",
                    data: row,
                    dataType: "JSON",
                    type: "POST",
                    beforeSend: function() {
                        proses();
                    },
                    success: function(res) {
                        pesan(res.msg, 1);

                        if (res.status) {
                            get_transaksi_simpanan();
                            cek_saldo_simpanan_sukarela2();
                        }
                    }
                });
            }
        } else {
            alert("Pilih data di tabel");
        }
    }
}

// function perpanjang_simpanan() {
//     if ($.fn.DataTable.isDataTable("#tabel_sukarela2")) {
//         row = $('#tabel_sukarela2').DataTable().row({
//             selected: true
//         }).data();

//         if (row) {
//             if (row.is_debet == "0") {
//                 alert("Maaf, simpanan ini masih aktif");
//                 return false;
//             }

//             if (row.is_diperpanjang == "1") {
//                 alert("Maaf, simpanan ini sudah diperpanjang");
//                 return false;
//             }

//             prompt = confirm("Anda Yakin Ingin Perpanjang Simpanan Sukarela2 Ini?");

//             if (prompt) {
//                 $.ajax({
//                     url: situs + "simpanan/perpanjang_ss2",
//                     data: row,
//                     dataType: "JSON",
//                     type: "POST",
//                     beforeSend: function() {
//                         proses();
//                     },
//                     success: function(res) {
//                         pesan(res.msg, 1);

//                         if (res.status) {
//                             get_transaksi_simpanan();
//                             cek_saldo_simpanan_sukarela2();
//                         }
//                     }
//                 });
//             }
//         } else {
//             alert("Pilih data di tabel");
//         }
//     }
// }

function open_tarik_dipercepat() {
    if ($.fn.DataTable.isDataTable("#tabel_sukarela2")) {
        data_dipercepat = $('#tabel_sukarela2').DataTable().row({
            selected: true
        }).data();

        if (data_dipercepat) {
            if (data_dipercepat.is_debet == "1") {
                alert("Maaf, simpanan ini sudah tidak aktif");
                return false;
            }

            $("#myModal").modal('show');

            data_dipercepat.jml_denda = 0;

            // if (data_dipercepat.umur_bulan < 1) {
            //     if (data_dipercepat.umur_hari <= 5) {
            //     } else {
            //         data_dipercepat.jml_denda = 25000;
            //     }
            // } else {
            //     data_dipercepat.jml_denda = parseFloat(data_dipercepat.jml_debet) * 0.2;
            // }

            set_form("form_dipercepat", data_dipercepat);
        } else {
            alert("Pilih data di tabel");
        }
    }
}

function tarik_dipercepat() {
    if ($("#form_dipercepat").valid()) {
        prompt = confirm("Anda Yakin Ingin Tarik Dipercepat SS2 Ini?");

        if (prompt) {
            data_form_dipercepat = $("#form_dipercepat").serialize();

            $.ajax({
                url: situs + "simpanan/ss2_dipercepat",
                data: data_form_dipercepat,
                dataType: "JSON",
                type: "POST",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_transaksi_simpanan();
                        cek_saldo_simpanan_sukarela2();

                        $("#myModal").modal('hide');
                    }
                }
            });
        }
    }
}

function open_no_ss2() {
    if ($.fn.DataTable.isDataTable("#tabel_sukarela2")) {
        data_noss2 = $('#tabel_sukarela2').DataTable().row({
            selected: true
        }).data();

        if (data_noss2) {
            $("#myModalss2").modal('show');

            set_form("form_noss2", data_noss2);
        } else {
            alert("Pilih data di tabel");
        }
    }
}

function simpan_no_ss2() {
    data_form_noss2 = $("#form_noss2").serialize();

    validasi = $("#form_noss2").valid();

    if (validasi) {
        prompt = confirm("Anda Yakin data sudah benar?");

        if (prompt) {
            $.ajax({
                url: situs + "simpanan/simpan_no_ss2",
                data: data_form_noss2,
                dataType: "JSON",
                type: "POST",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_transaksi_simpanan();
                        cek_saldo_simpanan_sukarela2();

                        $("#myModalss2").modal('hide');
                    }
                }
            });
        }
    }
}

function open_edit_tglDebet() {
    if ($.fn.DataTable.isDataTable("#tabel_sukarela2")) {
        data_noss2 = $('#tabel_sukarela2').DataTable().row({
            selected: true
        }).data();

        if (data_noss2) {
            if (data_noss2.tgl_debet != null) {
                $("#myModaltgldebet").modal('show');

                $('#form_tgldebet #no_simpan').val(data_noss2.no_simpan);
                $('#form_tgldebet #tgl_debet').val('');
            } else {
                alert('Data ini masih aktif');
            }
        } else {
            alert("Pilih data di tabel");
        }
    }
}

function simpan_tgldebet() {
    data_form_noss2 = $("#form_tgldebet").serialize();

    validasi = $("#form_tgldebet").valid();

    if (validasi) {
        prompt = confirm("Anda Yakin data sudah benar?");

        if (prompt) {
            $.ajax({
                url: situs + "simpanan/simpan_tgldebet",
                data: data_form_noss2,
                dataType: "JSON",
                type: "POST",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_transaksi_simpanan();
                        cek_saldo_simpanan_sukarela2();

                        $("#myModaltgldebet").modal('hide');
                    }
                }
            });
        }
    }
}

function open_edit_jangka() {
    if ($.fn.DataTable.isDataTable("#tabel_sukarela2")) {
        data_noss2 = $('#tabel_sukarela2').DataTable().row({
            selected: true
        }).data();

        if (data_noss2) {
            if (data_noss2.tgl_debet != null) {
                alert('Maaf, Simpanan ini sudah tidak aktif');
            } else {
                $('#form_jangka #no_simpan').val(data_noss2.no_simpan);
                $('#form_jangka #no_ss2').val(data_noss2.no_ss2);
                $('#form_jangka #jml_simpanan').val(data_noss2.jml_simpanan).trigger('change');

                $("#myModaljangka").modal('show');
            }
        } else {
            alert("Pilih data di tabel");
        }
    }
}

function cek_margin_simpanan_sukarela2_jangka($act) {
    $data_form = $("#form_jangka").serialize() + "&act=" + $act;

    $.ajax({
        url: situs + "simpanan/get_margin_simpanan_berlaku",
        data: $data_form,
        type: 'post',
        dataType: 'json',
        success: function(data) {
            $("#form_jangka #margin").val(data.margin);
            $("#form_jangka #jml_margin_bln").val(data.margin_per_bulan).trigger("change");
        }
    });
}

$('#myModalotorisasiJangka').on('hidden.bs.modal', function() {
    $("body").addClass("modal-open");
});

function open_ganti_margin_jangka() {
    $('#myModalotorisasiJangka').modal('show');

    clear_form('fm_otorisasi_jangka');
    $("#fm_otorisasi_jangka #passwd").focus();
}

function do_ganti_margin_jangka() {
    validasi = $("#fm_otorisasi_jangka").valid();

    if (validasi) {
        konfirmasi = confirm("Anda Yakin?");

        if (konfirmasi) {
            data_ajax = $("#fm_otorisasi_jangka").serialize();

            $.ajax({
                url: situs + "setting/cek_otorisasi_sp",
                data: data_ajax,
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    proses();
                },
                success: function(data) {
                    if (data.status) {
                        $('#form_jangka #margin').val(data.margin).trigger("change");
                        $('#myModalotorisasiJangka').modal('hide');
                        no_proses();
                    } else {
                        pesan('Maaf Password otorisasi tidak benar');
                    }
                }
            });
        }
    }
}

function simpan_jangka() {
    validasi = $('#form_jangka').valid();

    if (validasi) {
        konfirmasi = confirm("Anda Yakin?");

        if (konfirmasi) {
            data_ajax = $("#form_jangka").serialize();

            $.ajax({
                url: situs + "simpanan/simpan_ubah_jangka_ss2",
                data: data_ajax,
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    proses();
                },
                success: function(data) {
                    if (data.status) {
                        $('#form_jangka #margin').val(data.margin).trigger("change");
                        $('#myModalotorisasiJangka').modal('hide');
                        no_proses();
                    } else {
                        pesan('Maaf Password otorisasi tidak benar');
                    }
                }
            });
        }
    }
}

function cetak_ss2() {
    row = $('#tabel_sukarela2').DataTable().row({
        selected: true
    }).data();

    if (row) {
        $data_ajax = JSON.stringify(row);

        window.open(situs + "cetak/cetak_sertifikat_ss2?data=" + base64_encode($data_ajax));
    } else {
        alert("Pilih data di tabel");
    }
}
</script>