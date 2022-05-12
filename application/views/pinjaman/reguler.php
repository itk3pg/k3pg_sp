<!-- <div class="nav-tabs-custom"> -->
<ul class="nav nav-tabs navtab-bg" id="myTab">
    <li class="active">
        <a href="#input" class="input">Pinjaman Reguler</a>
    </li>
    <li>
        <a href="#view" class="view">View Data</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="input">
        <form id="fm_input" onsubmit="return false">
            <div class="panel-body">
                <h4>Data Anggota</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>NAK</label>
                            <select id="no_ang" name="no_ang" class="form-control" required=""></select>
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
                        <div class="form-group">
                            <label>Gaji</label>
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="text" id="gaji" name="gaji" class="form-control number_format" onchange="tombol_mode(1);proses_perhitungan_reguler();" required="" value="0" data-rule-number="true" />
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
                            <label>Total Plafon</label>
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="text" id="plafon" name="plafon" class="form-control number_format" onchange="tombol_mode(1);proses_perhitungan_reguler();" required="" value="0" />
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
                            <div class="row" style="margin: 0px;">
                                <div class="col-md-3" style="padding: 0px;">
                                    <input type="text" id="kd_bagian" name="kd_bagian" class="form-control" readonly>
                                </div>
                                <div class="col-md-9" style="padding: 0 0 0 5px;">
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <h4>Data Pinjaman</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tanggal Pengajuan</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" id="tgl_pinjam" name="tgl_pinjam" class="form-control datepicker" onchange="tombol_mode(1);proses_perhitungan_reguler();" readonly="" required="" value="<?php echo date('d-m-Y'); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Pinjaman</label>
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="text" id="jml_pinjam" name="jml_pinjam" class="form-control number_format" onchange="tombol_mode(1);proses_perhitungan_reguler();" required="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jangka Pinjaman</label>
                            <div class="input-group">
                                <select id="tempo_bln" name="tempo_bln" class="form-control" onchange="tombol_mode(1);proses_perhitungan_reguler();" required="">
                                    <?php echo $tempo_bln; ?>
                                </select>
                                <div class="input-group-addon">Bulan</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jumlah Angsuran</label>
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="text" id="angsuran" name="angsuran" class="form-control number_format" readonly="" value="0" />
                                <input type="hidden" name="angsuran_edit" id="angsuran_edit" value="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Biaya Admin</label>
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="text" id="jml_biaya_admin" name="jml_biaya_admin" class="form-control number_format" readonly="" value="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Total Pembiayaan</label>
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="text" id="jml_omzet" name="jml_omzet" class="form-control number_format" readonly="" value="0" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="row" style="margin: 0px;">
                                <div class="col-md-6" style="padding: 0px;">
                                    <label>Margin</label>
                                    <div class="input-group">
                                        <input type="text" id="margin" name="margin" class="form-control" data-options="precision:3" readonly="" value="0" />
                                        <div class="input-group-addon">%</div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding: 0 0 0 5px;">
                                    <!-- <label>Jenis Margin</label> -->
                                    <input type="hidden" id="jenis_margin" name="jenis_margin" value="FLAT" readonly="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Margin</label>
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="text" id="jml_margin" name="jml_margin" class="form-control number_format" readonly="" value="0" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div id="div_tombol_simpan">
                        <div class="col-md-4 text-right">
                            <a href="javascript:void(0)" class="btn btn-default" onclick="reset_form()">
                                    <i class="fa fa-times"></i> Batal</a>
                            <a href="javascript:void(0)" class="btn btn-primary" onclick="simpan()">
                                    <i class="fa fa-save"></i> Simpan</a>
                        </div>
                        <div class="col-md-8">
                            <div class="callout" style="padding: 0px;">
                                <ul>
                                    <li>Klik tombol Simpan untuk menyimpan entri pinjaman, atau klik tombol Batal untuk membatalkan entri</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="div_tombol_proses">
                        <div class="col-md-4 text-right">
                            <a href="javascript:void(0)" class="btn btn-success" onclick="proses_perhitungan_reguler()">
                                    <i class="fa fa-refresh"></i> Proses</a>
                        </div>
                        <div class="col-md-8">
                            <div class="callout" style="padding: 0px;">
                                <ul>
                                    <li>Klik Tombol Proses untuk melakukan perhitungan jumlah pinjaman yang diterima</li>
                                    <li>Setelah tombol Proses diklik, maka akan tampil tombol simpan untuk menyimpan entri pinjaman</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="tab-pane" id="view">
        <div class="panel-body">
            <button class="btn btn-info btn-small" onclick="cetak_pinjaman()">
                <i class="fa fa-print"></i> Cetak Simulasi</button>
            <button class="btn btn-warning btn-small" onclick="edit_data()">
                <i class="fa fa-pencil"></i> Edit Data</button>
            <button class="btn btn-danger btn-small" onclick="hapus_data()">
                <i class="fa fa-trash"></i> Hapus Data</button>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-condensed table-striped table-hover nowrap" id="tabel_reguler" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tgl Pinjam</th>
                        <th>NAK</th>
                        <th>No. Pegawai</th>
                        <th>Nama</th>
                        <th>Perusahaan</th>
                        <th>Departemen</th>
                        <th>Bagian</th>
                        <th>Jml Pinjam</th>
                        <th>Tempo Bln</th>
                        <th>Margin</th>
                        <th>Jml Angsuran</th>
                        <th>Jml Biaya Admin</th>
                        <th>Jml Diterima</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!-- </div> -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">View Angsuran</h4>
            </div>
            <div class="modal-body">
                <div id="div_angsuran"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');

    if ($(this).hasClass("input")) {
        reset_form();
    } else
    if ($(this).hasClass("view")) {
        get_pinjaman_reguler();
    }
});

$("#fm_input #no_ang").select2({
    ajax: {
        url: situs + 'anggota/select_anggota_by_noang/0',
        dataType: 'json',
        delay: 500
    }
}).on("select2:select", function(e) {
    s2data = e.params.data;

    $("#fm_input #nm_ang").val(s2data.nm_ang);
    $("#fm_input #no_peg").val(s2data.no_peg);
    $("#fm_input #kd_prsh").val(s2data.kd_prsh);
    $("#fm_input #nm_prsh").val(s2data.nm_prsh);
    $("#fm_input #kd_dep").val(s2data.kd_dep);
    $("#fm_input #nm_dep").val(s2data.nm_dep);
    $("#fm_input #kd_bagian").val(s2data.kd_bagian);
    $("#fm_input #nm_bagian").val(s2data.nm_bagian);
    $("#fm_input #gaji").val(number_format(s2data.gaji, 2));
    $("#fm_input #plafon").val(number_format(s2data.plafon, 2));
    $("#fm_input #sisa_plafon").val(number_format(s2data.sisa_plafon, 2));

    proses_perhitungan_reguler();
});

function tombol_mode(mode) {
    if (mode) {
        $("#div_tombol_proses").show();
        $("#div_tombol_simpan").hide();
    } else {
        $("#div_tombol_proses").hide();
        $("#div_tombol_simpan").show();
    }
}

function reset_form() {
    url_simpan = situs + "pinjaman/add_simulasi_reguler";
    
    tombol_mode(1);
    clear_form("fm_input");
}

reset_form();

function proses_perhitungan_reguler() {
    validasi = $("#fm_input").valid();

    if (validasi) {
        data_form = $("#fm_input").serialize();

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
                tombol_mode(0);

                set_form("fm_input", res);
            }
        });
    }
}

function simpan() {
    validasi = $("#fm_input").valid();

    if (validasi) {
        $sisa_plafon = hapus_koma($("#sisa_plafon").val());
        $angsuran = hapus_koma($("#angsuran").val());

        // if ((parseFloat($sisa_plafon) - parseFloat($angsuran)) < 0) {
        //     alert("Sisa Plafon Pot. Gaji tidak mencukupi");
        //     return false;
        // }

        konfirmasi = confirm("Anda yakin data sudah benar?");

        if (konfirmasi) {
            data_form = $("#fm_input").serialize();

            $.ajax({
                url: url_simpan,
                data: data_form,
                type: "post",
                dataType: "json",
                beforeSend: function(xhr) {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        reset_form();
                    }
                }
            });
        }
    } else {
        alert("Lengkapi data yang dibutuhkan");
    }
}

function get_pinjaman_reguler() {
    url_tabel = situs + "pinjaman/get_simulasi_pinjaman_reguler";
    tabel_id = "tabel_reguler";

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
                data: "no_ang"
            }, {
                data: "no_peg"
            }, {
                data: "nm_ang"
            }, {
                data: "nm_prsh"
            }, {
                data: "nm_dep"
            }, {
                data: "nm_bagian"
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
                data: "margin",
                className: "text-center"
            }, {
                data: "angsuran",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "jml_biaya_admin",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "jml_diterima",
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

function edit_data() {
    row = $("#tabel_reguler").DataTable().row({
        selected: true
    }).data();

    if (row) {
        if (row.is_realisasi == "1") {
            pesan("Maaf, Pinjaman ini sudah direalisasi", 0, "error");
            return false;
        } else {
            url_simpan = situs + "pinjaman/edit_simulasi_reguler/" + row.no_pinjam;
            row.angsuran_edit = row.angsuran;

            set_form("fm_input", row);
            set_select2_value("#fm_input #no_ang", row.no_ang, row.no_ang);

            $("#myTab a.input").tab('show');

            proses_perhitungan_reguler();
        }
    } else {
        alert('Pilih data di tabel');
    }
}

function hapus_data() {
    row = $("#tabel_reguler").DataTable().row({
        selected: true
    }).data();

    if (row) {
        if (row.is_realisasi == "1") {
            pesan("Maaf, Pinjaman ini sudah direalisasi", 0, "error");
            return false;
        } else {
            konfirmasi = confirm("Anda yakin hapus data ini?");

            if (konfirmasi) {
                $.ajax({
                    url: situs + "pinjaman/delete_simulasi_pinjaman",
                    data: row,
                    type: "post",
                    dataType: "json",
                    beforeSend: function() {
                        proses();
                    },
                    success: function(res) {
                        pesan(res.msg, 1);

                        if (res.status) {
                            get_pinjaman_reguler();
                        }
                    }
                });
            }
        }
    } else {
        alert('Pilih data di tabel');
    }
}

function cetak_pinjaman() {
    row = $("#tabel_reguler").DataTable().row({
        selected: true
    }).data();

    if (row) {
        window.open(situs + "cetak/cetak_pinjaman/" + row.no_pinjam);
    } else {
        alert('Pilih data di tabel');
    }
}
</script>