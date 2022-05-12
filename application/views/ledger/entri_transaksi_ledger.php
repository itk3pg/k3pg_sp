<div class="nav-tabs-custom">
    <ul class="nav nav-tabs navtab-bg" id="myTab">
        <li class="active">
            <a href="#input" class="input">Entri</a>
        </li>
        <li>
            <a href="#view" class="view">View Data</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="input">
            <div class="panel panel-default panel-color">
                <div class="panel-body">
                    <form id="fm_data" onsubmit="return false">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>No. Ref</label>
                                    <input type="text" name="no_ref_bukti" id="no_ref_bukti" class="form-control" required="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" name="tgl_gl" id="tgl_gl" class="form-control datepicker" required="" value="<?php echo date('d-m-Y'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <input type="text" name="ket" id="ket" class="form-control" required="" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row" id="div_detail_ledger">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Kas/Bank</label>
                                    <select id="kd_kasbank" name="kd_kasbank" class="form-control" required="">
                                        <?php echo $kasbank; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Akun</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" name="kd_akun" id="kd_akun" class="form-control" required="" placeholder="Kode Perkiraan">
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" name="nm_akun" id="nm_akun" class="form-control" readonly="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Debet/Kredit</label>
                                    <select id="kredit_debet" name="kredit_debet" class="form-control">
                                        <option value="D">Debet</option>
                                        <option value="K">Kredit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">Rp</div>
                                        <input type="text" name="jumlah" id="jumlah" class="form-control number_format" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group text-center">
                                    <br>
                                    <button type="button" class="btn btn-primary" onclick="simpan()">Simpan</button>
                                    <button type="button" class="btn btn-default" onclick="batal()">Batal</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status Balance</label>
                                <h4 id="status_balance">Balance</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah Debet</label>
                                <div class="input-group">
                                    <div class="input-group-addon">Rp</div>
                                    <input type="text" name="jml_debet" id="jml_debet" class="form-control number_format" readonly="" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah Kredit</label>
                                <div class="input-group">
                                    <div class="input-group-addon">Rp</div>
                                    <input type="text" name="jml_kredit" id="jml_kredit" class="form-control number_format" readonly="" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-heading">
                    <div class="row">
                        <div class="pull-left">
                            <button class="btn btn-danger btn-small" onclick="del_detail()">
                                <i class="fa fa-trash"></i> Hapus</button>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table id="tabel_detail_ledger" class="table table-bordered table-condensed table-hover table-striped nowrap" width="100%">
                        <thead>
                            <tr>
                                <th width="50">No.</th>
                                <th>Bukti</th>
                                <th>Kas/Bank</th>
                                <th>Akun/Perkiraan</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="view">
            <div class="panel-heading">
                <div class="row">
                    <div class="pull-left">
                        <button class="btn btn-warning btn-small" onclick="edit()">
                            <i class="fa fa-pencil"></i> Edit</button>
                        <button class="btn btn-danger btn-small" onclick="del_header()">
                            <i class="fa fa-trash"></i> Hapus</button>
                    </div>
                    <div class="pull-right">
                        <form id="fm_periode" onsubmit="return false" class="form-inline">
                            <select id="bulan" name="bulan" class="form-control" onchange="get_data_header_ledger()">
                                <?php echo $bulan; ?>
                            </select>
                            <input type="text" name="tahun" id="tahun" class="form-control" value="<?php echo date('Y'); ?>" size="4" onchange="get_data_header_ledger()">
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <table id="tabel_header_ledger" class="table table-bordered table-condensed table-hover table-striped nowrap" width="100%">
                    <thead>
                        <tr>
                            <th width="50">No.</th>
                            <th>Tanggal</th>
                            <th>No. Referensi</th>
                            <th>Keterangan</th>
                            <th>Debet</th>
                            <th>Kredit</th>
                            <th>Status Balance</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
edit_mode = 0;

$('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');

    if ($(this).hasClass("input")) {
        get_data_no_ref();
    } else
    if ($(this).hasClass("view")) {
        get_data_header_ledger();
    }
});

function get_data_header_ledger() {
    $fm_periode = $("#fm_periode").serialize();
    url_tabel = situs + "ledger/get_header_ledger?" + $fm_periode;
    tabel_id = "tabel_header_ledger";

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
                data: "tgl_gl"
            }, {
                data: "no_ref_bukti"
            }, {
                data: "ket"
            }, {
                data: "debet",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "kredit",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "status_balance"
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

        $("#" + tabel_id).DataTable().off("draw.dt");
    }
}

function get_data_detail_ledger() {
    $fm_data = $("#fm_data").serialize();
    url_tabel = situs + "ledger/get_detail_ledger?" + $fm_data;
    tabel_id = "tabel_detail_ledger";

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
                data: "bukti_gl"
            }, {
                data: "kd_kasbank",
                defaultContent: "",
                render: function(data, type, row, meta) {
                    return "[" + data + "] " + row.nm_kasbank;
                }
            }, {
                data: "kd_akun",
                defaultContent: "",
                render: function(data, type, row, meta) {
                    return "[" + data + "] " + row.nm_akun;
                }
            }, {
                data: "debet",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "kredit",
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

        $("#" + tabel_id).DataTable().off("draw.dt");
    }
}

var ev_no_ref = 1;

$("#fm_data #no_ref_bukti").focus().on("change", function() {
    if (ev_no_ref == 0) {
        ev_no_ref = 1;

        get_data_no_ref();
    }
}).keydown(function(e) {
    if (e.which == 13) {
        if (ev_no_ref == 0) {
            ev_no_ref = 1;

            get_data_no_ref();
        }
    } else {
        ev_no_ref = 0;
    }
});

function get_data_no_ref() {
    $no_ref_bukti = $("#fm_data #no_ref_bukti").val();

    if ($no_ref_bukti) {
        $.ajax({
            url: situs + 'ledger/get_ledger_by_no_ref',
            data: "no_ref_bukti=" + $no_ref_bukti,
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
                proses();
            },
            success: function(data) {
                no_proses();

                if (typeof(data) != "undefined") {
                    data_ledger = data;

                    $("#fm_data #tgl_gl").val(data_ledger.tgl_gl);
                    $("#fm_data #ket").val(data_ledger.ket);
                    $("#status_balance").html(data_ledger.status_balance);
                    $("#jml_debet").val(data_ledger.debet).trigger("change");
                    $("#jml_kredit").val(data_ledger.kredit).trigger("change");

                    $('#div_detail_ledger input').val('');
                }

                get_data_detail_ledger();
            }
        });
    }
}

var ev_akun = 1;

$("#fm_data #kd_akun").on("change", function() {
    if (ev_akun == 0) {
        ev_akun = 1;

        get_data_akun();
    }
}).keydown(function(e) {
    if (e.which == 13) {
        if (ev_akun == 0) {
            ev_akun = 1;

            get_data_akun();
        }
    } else {
        ev_akun = 0;
    }
});

function get_data_akun() {
    $kd_akun = $("#fm_data #kd_akun").val();

    if ($kd_akun) {
        $.ajax({
            url: situs + 'master/select_akun',
            data: "q=" + $kd_akun,
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
                proses();
            },
            success: function(data) {
                no_proses();

                if (typeof(data) != "undefined" && data.results.length > 0) {
                    data_akun = data.results[0];

                    $("#fm_data #nm_akun").val(data_akun.nm_akun);
                    $("#fm_data #kd_akun").focus();
                } else {
                    $("#fm_data #nm_akun").val('');
                    $("#fm_data #kd_akun").val('').focus();
                }
            }
        });
    }
}

// $("#fm_data #no_ang").select2({
//     ajax: {
//         url: situs + 'anggota/select_nasabah_by_noang/0',
//         dataType: 'json',
//         delay: 500
//     }
// }).on("select2:select", function(e) {
//     s2data = e.params.data;

//     $("#fm_data #nm_ang").val(s2data.nm_ang);
//     $("#fm_data #no_peg").val(s2data.no_peg);
//     $("#fm_data #kd_prsh").val(s2data.kd_prsh);
//     $("#fm_data #nm_prsh").val(s2data.nm_prsh);
//     $("#fm_data #kd_dep").val(s2data.kd_dep);
//     $("#fm_data #nm_dep").val(s2data.nm_dep);
//     $("#fm_data #kd_bagian").val(s2data.kd_bagian);
//     $("#fm_data #nm_bagian").val(s2data.nm_bagian);

//     get_data_ledger();
//     cek_saldo_simpanan_sukarela1();
//     get_ktp();
// });

function simpan() {
    validasi = $('#fm_data').valid();

    if (validasi) {
        konfirmasi = confirm("Anda yakin data sudah benar?");

        if (konfirmasi) {
            data_input = $('#fm_data').serialize() + "&nm_kasbank=" + $('#kd_kasbank option:selected').text();

            $.ajax({
                url: situs + 'ledger/add_ledger',
                data: data_input,
                dataType: "JSON",
                type: "POST",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_data_no_ref();

                        $('#kd_akun').focus();
                        $("#kredit_debet").val('D');
                    }
                }
            });
        }
    }
}

function batal() {
    $('#div_detail_ledger input').val('');
}

function del_detail() {
    if ($.fn.DataTable.isDataTable("#tabel_detail_ledger")) {
        row = $('#tabel_detail_ledger').DataTable().row({
            selected: true
        }).data();

        if (row) {
            prompt = confirm("Anda Yakin Ingin Menghapus Data Ini?");

            if (prompt) {
                $.ajax({
                    url: situs + "ledger/del_detail_ledger",
                    data: row,
                    dataType: "JSON",
                    type: "POST",
                    beforeSend: function() {
                        proses();
                    },
                    success: function(res) {
                        pesan(res.msg, 1);

                        if (res.status) {
                            get_data_no_ref();
                        }
                    }
                });
            }
        } else {
            alert("Pilih data di tabel");
        }
    }
}

function edit() {
    if ($.fn.DataTable.isDataTable("#tabel_header_ledger")) {
        row = $('#tabel_header_ledger').DataTable().row({
            selected: true
        }).data();

        if (row) {
            $('#myTab a.input').tab('show');
            $('#fm_data #no_ref_bukti').val(row.no_ref_bukti).trigger('change');

            get_data_no_ref();
        } else {
            alert("Pilih data di tabel");
        }
    }
}

function del_header() {
    if ($.fn.DataTable.isDataTable("#tabel_header_ledger")) {
        row = $('#tabel_header_ledger').DataTable().row({
            selected: true
        }).data();

        if (row) {
            prompt = confirm("Anda Yakin Ingin Menghapus Data Ini?");

            if (prompt) {
                $.ajax({
                    url: situs + "ledger/del_header_ledger",
                    data: row,
                    dataType: "JSON",
                    type: "POST",
                    beforeSend: function() {
                        proses();
                    },
                    success: function(res) {
                        pesan(res.msg, 1);

                        if (res.status) {
                            get_data_header_ledger();
                        }
                    }
                });
            }
        } else {
            alert("Pilih data di tabel");
        }
    }
}
</script>