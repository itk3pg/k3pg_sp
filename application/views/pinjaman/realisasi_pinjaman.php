<div class="nav-tabs-custom">
    <ul class="nav nav-tabs navtab-bg" id="myTab">
        <li class="active">
            <a href="#input" class="input">Belum Realisasi</a>
        </li>
        <li>
            <a href="#view" class="view">Sudah Realisasi</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="input">
            <div class="panel-body">
                <div class="pull-right">
                    <button class="btn btn-danger btn-small" onclick="hapus_simulasi_pinjaman()">
                        <i class="fa fa-trash"></i> Hapus Pengajuan Pinjaman</button>
                </div>
                <button class="btn btn-info btn-small" onclick="cetak_pinjaman('tabel_belum_realisasi')">
                    <i class="fa fa-print"></i> Cetak Pinjaman</button>
                <button class="btn btn-success btn-small" onclick="realisasi_pinjaman()">
                    <i class="fa fa-check"></i> Realisasi Pinjaman</button>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-condensed table-striped table-hover nowrap" id="tabel_belum_realisasi" width="100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tgl Pinjam</th>
                            <th>Jenis Pinjaman</th>
                            <th>NAK</th>
                            <th>No. Pegawai</th>
                            <th>Nama</th>
                            <th>Kode Prsh</th>
                            <th>Perusahaan</th>
                            <th>Kode Dep</th>
                            <th>Departemen</th>
                            <th>Jml Pinjam</th>
                            <th>Tempo Bln</th>
                            <th>Jml Biaya Admin</th>
                            <th>Jml Margin</th>
                            <th>Jml Angsuran</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="view">
            <div class="panel-body">
                <div class="pull-right">
                    <button class="btn btn-danger btn-small" onclick="hapus_realisasi_pinjaman()">
                        <i class="fa fa-trash"></i> Hapus Realisasi Pinjaman</button>
                </div>
                <button class="btn btn-info btn-small" onclick="cetak_pinjaman_terealisasi('tabel_sudah_realisasi')">
                    <i class="fa fa-print"></i> Cetak Pinjaman</button>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-condensed table-striped table-hover nowrap" id="tabel_sudah_realisasi" width="100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tgl Pinjam</th>
                            <th>Jenis Pinjaman</th>
                            <th>NAK</th>
                            <th>No. Pegawai</th>
                            <th>Nama</th>
                            <th>Kode Prsh</th>
                            <th>Perusahaan</th>
                            <th>Kode Dep</th>
                            <th>Departemen</th>
                            <th>Jml Pinjam</th>
                            <th>Tempo Bln</th>
                            <th>Jml Biaya Admin</th>
                            <th>Jml Margin</th>
                            <th>Jml Angsuran</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Realisasi Pinjaman</h4>
            </div>
            <div class="modal-body">
                <form id="fm_modal" onsubmit="return false">
                    <div class="panel-body">
                        <h4>Data Anggota</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>NAK</label>
                                    <br>
                                    <input type="text" id="no_ang" name="no_ang" class="form-control" readonly="">
                                </div>
                                <div class="form-group">
                                    <label>Perusahaan</label>
                                    <div class="row">
                                        <div class="col-md-3" style="padding-right: 0px;">
                                            <input type="text" id="kd_prsh" name="kd_prsh" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" id="nm_prsh" name="nm_prsh" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Gaji</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">Rp</div>
                                        <input type="text" id="gaji" name="gaji" class="form-control number_format" readonly="" value="0" />
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
                                    <div class="row">
                                        <div class="col-md-3" style="padding-right: 0px;">
                                            <input type="text" id="kd_dep" name="kd_dep" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" id="nm_dep" name="nm_dep" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Total Plafon</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">Rp</div>
                                        <input type="text" id="plafon" name="plafon" class="form-control number_format" readonly="" value="0" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>No. Pegawai</label>
                                    <input type="text" id="no_peg" name="no_peg" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Bagian</label>
                                    <div class="row">
                                        <div class="col-md-3" style="padding-right: 0px;">
                                            <input type="text" id="kd_bagian" name="kd_bagian" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" id="nm_bagian" name="nm_bagian" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Sisa Plafon</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">Rp</div>
                                        <input type="text" id="sisa_plafon" name="sisa_plafon" class="form-control number_format" readonly="" value="0" />
                                    </div>
                                    <input type="hidden" id="plafon_terpakai" name="plafon_terpakai" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <h4>Data Pinjaman</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Realisasi</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" id="tgl_realisasi" name="tgl_realisasi" class="form-control datepicker" required="" readonly="" />
                                    </div>
                                    <input type="hidden" name="tgl_pinjam" id="tgl_pinjam">
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Pinjaman</label>
                                    <input type="text" id="jml_pinjam" name="jml_pinjam" class="form-control number_format" required="" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Margin (%)</label>
                                    <div class="input-group">
                                        <input type="text" id="margin" name="margin" class="form-control" value="0" readonly="" />
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-info" onclick="open_ganti_margin()"><i class="fa fa-pencil"></i> Ganti Margin</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="is_ganti_margin" id="is_ganti_margin" value="0">
                                    <input type="hidden" name="angsuran_edit" id="angsuran_edit" value="0">
                                    <input type="hidden" name="jml_margin" id="jml_margin" value="0">
                                    <input type="hidden" name="jml_diterima" id="jml_diterima" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Jml. Biaya Admin</label>
                                    <input type="text" name="jml_biaya_admin" id="jml_biaya_admin" class="form-control number_format" readonly="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6" style="padding-right: 0px;">
                                            <label>Tgl. Angsuran</label>
                                            <input type="text" id="tgl_angs" name="tgl_angs" class="form-control" readonly="" value="-" />
                                        </div>
                                        <div class="col-md-6">
                                            <label>Tgl. Jatuh Tempo</label>
                                            <input type="text" id="tgl_jt" name="tgl_jt" class="form-control" readonly="" value="-" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="div_realisasi_vars"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpan_realisasi();">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalAngsuran" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width: 80%;width: max-content; width: -moz-max-content;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">View Angsuran</h4>
            </div>
            <div class="modal-body">
                <div id="div_angsuran"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalotorisasi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="true">
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
<script type="text/javascript">
var jenis_pinjaman;

$('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');

    if ($(this).hasClass("input")) {
        get_pinjaman_belum_realisasi();
    } else
    if ($(this).hasClass("view")) {
        get_pinjaman_sudah_realisasi();
    }
});

$('#myModalAngsuran, #myModalotorisasi').on('hidden.bs.modal', function() {
    $("body").addClass("modal-open");
});

function get_pinjaman_belum_realisasi() {
    url_tabel = situs + "pinjaman/get_pinjaman_belum_realisasi";
    tabel_id = "tabel_belum_realisasi";

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
                data: "tgl_pinjam"
            }, {
                data: "nm_pinjaman"
            }, {
                data: "no_ang"
            }, {
                data: "no_peg"
            }, {
                data: "nm_ang"
            }, {
                data: "kd_prsh"
            }, {
                data: "nm_prsh"
            }, {
                data: "kd_dep"
            }, {
                data: "nm_dep"
            }, {
                data: "jml_pinjam",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "tempo_bln",
                className: "text-center"
            }, {
                data: "jml_biaya_admin",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "jml_margin",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "angsuran",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
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
                    }
                });
            }
        });
    }
}

get_pinjaman_belum_realisasi();

function get_pinjaman_sudah_realisasi() {
    url_tabel = situs + "pinjaman/get_pinjaman_sudah_realisasi";
    tabel_id = "tabel_sudah_realisasi";

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
                data: "tgl_pinjam"
            }, {
                data: "nm_pinjaman"
            }, {
                data: "no_ang"
            }, {
                data: "no_peg"
            }, {
                data: "nm_ang"
            }, {
                data: "kd_prsh"
            }, {
                data: "nm_prsh"
            }, {
                data: "kd_dep"
            }, {
                data: "nm_dep"
            }, {
                data: "jml_pinjam",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "tempo_bln",
                className: "text-center"
            }, {
                data: "jml_biaya_admin",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "jml_margin",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "angsuran",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
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
                    }
                });
            }
        });
    }
}

function realisasi_pinjaman() {
    row = $("#tabel_belum_realisasi").DataTable().row({
        selected: true
    }).data();

    if (row) {
        proses();

        $('#myModal').modal('show');
        $('#myModal').on('shown.bs.modal', function() {
            $('#is_ganti_margin').val('0');

            jenis_pinjaman = row.kd_pinjaman;
            jns_potong_bunga = row.jns_potong_bunga;

            if (row.kd_pinjaman == "4") {
                row.angsuran_edit = row.angsuran;
            }

            do_realisasi_vars(row.kd_pinjaman, row);

            no_proses();
        });
    } else {
        alert('Pilih data di tabel');
    }
}

function get_plafon($no_ang) {
    data_ajax = "no_ang=" + $no_ang;

    $.ajax({
        url: situs + "anggota/select_anggota_dari_noang",
        data: data_ajax,
        // type: "post",
        dataType: "json",
        success: function(data) {
            $("#fm_modal #plafon").val(data[0].plafon).trigger("change");
        }
    });
}

function do_realisasi_vars($jenis_pinjaman, $dataset) {
    $.ajax({
        url: situs + "pinjaman/get_realisasi_vars",
        type: "post",
        data: "jenis_pinjaman=" + $jenis_pinjaman + "&no_ang=" + $dataset.no_ang,
        success: function(data) {
            $("#div_realisasi_vars").html(data);

            set_form('fm_modal', $dataset);
            get_plafon($dataset.no_ang);
        }
    });
}

function proses_perhitungan_reguler() {
    validasi = $("#fm_modal").valid();

    if (validasi) {
        data_form = $("#fm_modal").serialize() + "&mode=realisasi";

        $.ajax({
            url: situs + "pinjaman/proses_perhitungan_reguler",
            data: data_form,
            type: 'post',
            dataType: 'json',
            beforeSend: function(xhr) {
                proses();
            },
            success: function(res) {
                no_proses();

                set_form("fm_modal", res);
            }
        });
    }
}

function proses_perhitungan_kkb() {
    validasi = $("#fm_modal").valid();

    if (validasi) {
        data_form = $("#fm_modal").serialize() + "&mode=realisasi";

        $.ajax({
            url: situs + "pinjaman/proses_perhitungan_kkb",
            data: data_form,
            type: 'post',
            dataType: 'json',
            beforeSend: function(xhr) {
                proses();
            },
            success: function(res) {
                no_proses();

                set_form("fm_modal", res);
            }
        });
    }
}

function tampilkan_perhitungan_angsuran_kkb() {
    validasi = $("#fm_modal").valid();

    if (validasi) {
        $data_form = $("#fm_modal").serialize() + "&mode=realisasi";
        $('#myModalAngsuran').modal('show');

        $.ajax({
            url: situs + "pinjaman/view_angsuran_kkb",
            data: $data_form,
            type: "post",
            beforeSend: function() {
                proses();
            },
            success: function(data) {
                no_proses();

                $("#div_angsuran").html(data);
            }
        });
    }
}

function proses_perhitungan_pht() {
    validasi = $("#fm_modal").valid();

    if (validasi) {
        data_form = $("#fm_modal").serialize() + "&mode=realisasi";

        $.ajax({
            url: situs + "pinjaman/proses_perhitungan_pht",
            data: data_form,
            type: 'post',
            dataType: 'json',
            beforeSend: function(xhr) {
                proses();
            },
            success: function(res) {
                no_proses();

                set_form("fm_modal", res);
            }
        });
    }
}

function proses_perhitungan_kpr() {
    validasi = $("#fm_modal").valid();

    if (validasi) {
        data_form = $("#fm_modal").serialize() + "&mode=realisasi";

        $.ajax({
            url: situs + "pinjaman/proses_perhitungan_kpr",
            data: data_form,
            type: 'post',
            dataType: 'json',
            beforeSend: function(xhr) {
                proses();
            },
            success: function(res) {
                no_proses();

                set_form("fm_modal", res);
            }
        });
    }
}

function tampilkan_perhitungan_angsuran_kpr() {
    validasi = $("#fm_modal").valid();

    if (validasi) {
        $data_form = $("#fm_modal").serialize() + "&mode=realisasi";
        $('#myModalAngsuran').modal('show');

        $.ajax({
            url: situs + "pinjaman/view_angsuran_kpr",
            data: $data_form,
            type: "post",
            beforeSend: function() {
                proses();
            },
            success: function(data) {
                no_proses();

                $("#div_angsuran").html(data);
            }
        });
    }
}

function simpan_realisasi() {
    validasi = $("#fm_modal").valid();

    if (validasi) {
        if (jenis_pinjaman == "1" || jenis_pinjaman == "3") {
            $sisa_plafon = hapus_koma($("#fm_modal #sisa_plafon").val());
            $angsuran = hapus_koma($("#fm_modal #angsuran").val());
            $is_agunan = $("#is_agunan").val();

            if (jenis_pinjaman == "1" && $is_agunan == "1") {} else if (jenis_pinjaman == "3" && jns_potong_bunga == "POTONG") {} else {
                if ((parseFloat($sisa_plafon) - parseFloat($angsuran)) < 0) {
                    alert("Sisa Plafon Pot. Gaji tidak mencukupi");
                    return false;
                }
            }
        }

        if (jenis_pinjaman == "2" || jenis_pinjaman == "4") {
            $sisa_plafon = hapus_koma($("#fm_modal #sisa_plafon").val());
            $angsuran = hapus_koma($("#fm_modal #angsuran").val());

            if ((parseFloat($sisa_plafon) - parseFloat($angsuran)) < 0) {
                alert("Sisa Plafon Pot. Gaji tidak mencukupi");
                return false;
            }

            jml_saldo_akhir = hapus_koma($("#fm_modal #saldo_akhir").val());

            if (parseFloat(jml_saldo_akhir) > 0) {
                alert("Jumlah Saldo Akhir/Sisa Pinjaman Tidak boleh Lebih dari 0");
                return false;
            }
        }

        konfirmasi = confirm("Anda yakin merealisasikan pinjaman ini?");

        if (konfirmasi) {
            data_form = get_form_array('fm_modal');
            data_ajax = row;
            data_ajax['tgl_realisasi'] = data_form['tgl_realisasi'];
            data_ajax['tgl_angs'] = data_form['tgl_angs'];
            data_ajax['tgl_jt'] = data_form['tgl_jt'];
            data_ajax['mode'] = "realisasi";
            data_ajax['jml_pinjam'] = data_form['jml_pinjam'];
            data_ajax['angsuran'] = data_form['angsuran'];
            data_ajax['jml_margin'] = data_form['jml_margin'];
            data_ajax['jml_biaya_admin'] = data_form['jml_biaya_admin'];
            data_ajax['jml_diterima'] = data_form['jml_diterima'];
            data_ajax['margin'] = data_form['margin'];
            data_ajax['tempo_bln'] = data_form['tempo_bln'];
            data_ajax['is_ganti_margin'] = data_form['is_ganti_margin'];

            if (jenis_pinjaman == "2" || jenis_pinjaman == "4") {
                data_ajax['persen_angsuran'] = data_form['persen_angsuran'];
                data_ajax['min_angsuran'] = data_form['min_angsuran'];
                data_ajax['max_angsuran'] = data_form['max_angsuran'];
                data_ajax['jml_min_angsuran'] = data_form['jml_min_angsuran'];
                data_ajax['jml_max_angsuran'] = data_form['jml_max_angsuran'];
            }

            $.ajax({
                url: situs + "pinjaman/realisasi_pinjaman",
                data: data_ajax,
                type: "post",
                dataType: "json",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_pinjaman_belum_realisasi();

                        $("#myModal").modal('hide');
                    }
                }
            });
        }
    }
}

function cetak_pinjaman($tabel_id) {
    row = $("#" + $tabel_id).DataTable().row({
        selected: true
    }).data();

    if (row) {
        window.open(situs + "cetak/cetak_pinjaman/" + row.no_pinjam);
    } else {
        alert('Pilih data di tabel');
    }
}

function cetak_pinjaman_terealisasi($tabel_id) {
    row = $("#" + $tabel_id).DataTable().row({
        selected: true
    }).data();

    if (row) {
        dataRow = base64_encode(JSON.stringify(row));
        // $noPinjam = base64_encode();

        window.open(situs + "cetak/cetak_pinjaman_sudah_realisasi?data=" + dataRow);
    } else {
        alert('Pilih data di tabel');
    }
}

function hapus_simulasi_pinjaman() {
    row = $("#tabel_belum_realisasi").DataTable().row({
        selected: true
    }).data();

    if (row) {
        konfirmasi = confirm("Anda Yakin?");

        if (konfirmasi) {
            $.ajax({
                url: situs + "pinjaman/delete_simulasi_pinjaman",
                data: row,
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    proses()
                },
                success: function(data) {
                    pesan(data.msg);

                    if (data.status) {
                        get_pinjaman_belum_realisasi();
                    }
                }
            });
        }
    }
}

function hapus_realisasi_pinjaman() {
    row = $("#tabel_sudah_realisasi").DataTable().row({
        selected: true
    }).data();

    if (row) {
        konfirmasi = confirm("Anda Yakin?");

        if (konfirmasi) {
            $.ajax({
                url: situs + "pinjaman/hapus_realisasi_pinjaman",
                data: row,
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    proses()
                },
                success: function(data) {
                    pesan(data.msg);

                    if (data.status) {
                        get_pinjaman_sudah_realisasi();
                    }
                }
            });
        }
    }
}

function open_ganti_margin() {
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
                        $('#is_ganti_margin').val('1');
                        $('#fm_modal #margin').val(data.margin).trigger("change");
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
</script>