<!-- <div class="nav-tabs-custom"> -->
<ul class="nav nav-tabs navtab-bg" id="myTab">
    <li class="active">
        <a href="#input" class="input">Entri/Update Nasabah</a>
    </li>
    <li>
        <a href="#view" class="view">View Data</a>
    </li>
    <li>
        <a href="#upload_ktp" class="upload_ktp">Upload KTP</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="input">
        <form id="fm_input" onsubmit="return false;">
            <div class="panel-body">
                <h4>Data Anggota</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>NAK</label>
                            <input type="text" name="no_ang" id="no_ang" class="form-control" data-rule-required="true" autocomplete="off" style="text-transform: uppercase;">
                            <!-- <select id="no_ang" name="no_ang" class="form-control select2_ang" required=""></select> -->
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
                    </div>
                </div>
                <hr>
                <div id="div_tabel_nasabah"></div>
            </div>
        </form>
    </div>
    <div class="tab-pane" id="view">
        <div class="panel-body">
            <div class="pull-left">
                <button class="btn btn-success btn-small" onclick="add_data()">
                    <i class="fa fa-plus"></i> Tambah Data</button>
                <button class="btn btn-warning btn-small" onclick="edit_data()">
                    <i class="fa fa-pencil"></i> Edit Data</button>
                <button class="btn btn-danger btn-small" onclick="hapus_data()">
                    <i class="fa fa-trash"></i> Hapus Data</button>
            </div>
            <div class="pull-right">
                <button class="btn btn-info btn-small" onclick="cetak_identitas_buku()">
                    <i class="fa fa-print"></i> Cetak ID Buku</button>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-condensed table-striped table-hover nowrap" id="tabel_nasabah" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>NAK</th>
                        <th>No. Pegawai</th>
                        <th>Nama</th>
                        <th>Perusahaan</th>
                        <th>Departemen</th>
                        <th>Bagian</th>
                        <th>Telp</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="tab-pane" id="upload_ktp">
        <form id="fm_ktp" onsubmit="return false">
            <div class="panel-body">
                <h4>Data Nasabah</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>NAK</label>
                            <input type="text" name="no_ang" id="no_ang" class="form-control" data-rule-required="true" autocomplete="off" style="text-transform: uppercase;">
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
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Upload KTP</label>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <label class="btn btn-info">
                                        <i class="fa fa-file"></i> Pilih File
                                        <input type="file" name="file_ktp" id="file_ktp" style="display: none;">
                                    </label>
                                </div>
                                <input type="text" name="nm_file" id="nm_file" class="form-control" readonly="">
                            </div>
                            <br>
                            <div class="pull-left">
                                <button type="button" class="btn btn-success" onclick="upload_ktp()"><i class="fa fa-upload"></i> Upload</button>
                            </div>
                            <div class="pull-right">
                                <button type="button" class="btn btn-danger" onclick="hapus_ktp()"><i class="fa fa-trash"></i> Hapus Gambar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <center>
                            <img src="<?php echo base_url('aset/gambar/no-image.png'); ?>" id="gambar_ktp" style="height: 250px" class="img-thumbnail" />
                        </center>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 50%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Data Nasabah</h4>
            </div>
            <div class="modal-body">
                <form id="fm_modal" onsubmit="return false">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NAK</label>
                                <input id="no_ang" name="no_ang" class="form-control" required="">
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Anggota</label>
                                <input type="text" id="nm_ang" name="nm_ang" class="form-control" required="">
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea id="alm_rmh" name="alm_rmh" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label>No. Telp/Hp</label>
                                <input type="text" id="tlp_hp" name="tlp_hp" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. Pegawai</label>
                                <input type="text" id="no_peg" name="no_peg" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Departemen</label>
                                <input type="text" id="nm_dep" name="nm_dep" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Bagian</label>
                                <input type="text" id="nm_bagian" name="nm_bagian" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpan_nasabah()">Simpan</button>
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
        $("#fm_input #no_ang").focus();
    } else
    if ($(this).hasClass("view")) {
        get_nasabah();
    }
    if ($(this).hasClass("upload_ktp")) {
        $("#fm_ktp #no_ang").focus();
    }
});

var ev_get_anggota = 1;

$("#fm_input #no_ang").focus().on("change", function() {
    if (ev_get_anggota == 0) {
        ev_get_anggota = 1;

        get_anggota();
    }
}).keydown(function(e) {
    if (e.which == 13) {
        if (ev_get_anggota == 0) {
            ev_get_anggota = 1;

            get_anggota();
        }
    } else {
        ev_get_anggota = 0;
    }
});

function get_anggota() {
    $no_ang = $("#fm_input #no_ang").val();

    if ($no_ang) {
        $.ajax({
            url: situs + 'anggota/select_anggota_by_noang',
            data: "q=" + $no_ang,
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
                proses();
            },
            success: function(data) {
                no_proses();

                if (typeof(data.results) != "undefined" && data.results.length > 0) {
                    data_nasabah = data.results;

                    $("#fm_input #nm_ang").val(data_nasabah[0].nm_ang);
                    $("#fm_input #no_peg").val(data_nasabah[0].no_peg);
                    $("#fm_input #kd_prsh").val(data_nasabah[0].kd_prsh);
                    $("#fm_input #nm_prsh").val(data_nasabah[0].nm_prsh);
                    $("#fm_input #kd_dep").val(data_nasabah[0].kd_dep);
                    $("#fm_input #nm_dep").val(data_nasabah[0].nm_dep);
                    $("#fm_input #kd_bagian").val(data_nasabah[0].kd_bagian);
                    $("#fm_input #nm_bagian").val(data_nasabah[0].nm_bagian);

                    get_tabel_nasabah();
                    return false;
                } else {
                    $("#fm_input #no_ang").val('');
                    pesan('Data tidak ditemukan');
                }
            }
        });
    }
}

$("#fm_ktp #no_ang").focus().on("change", function() {
    if (ev_get_anggota == 0) {
        ev_get_anggota = 1;

        get_nasabah_by_noang();
    }
}).keydown(function(e) {
    if (e.which == 13) {
        if (ev_get_anggota == 0) {
            ev_get_anggota = 1;

            get_nasabah_by_noang();
        }
    } else {
        ev_get_anggota = 0;
    }
});

function get_nasabah_by_noang() {
    $no_ang = $("#fm_ktp #no_ang").val();

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
                no_proses();

                if (typeof(data.results) != "undefined" && data.results.length > 0) {
                    data_nasabah = data.results;

                    $("#fm_ktp #nm_ang").val(data_nasabah[0].nm_ang);
                    $("#fm_ktp #no_peg").val(data_nasabah[0].no_peg);
                    $("#fm_ktp #kd_prsh").val(data_nasabah[0].kd_prsh);
                    $("#fm_ktp #nm_prsh").val(data_nasabah[0].nm_prsh);
                    $("#fm_ktp #kd_dep").val(data_nasabah[0].kd_dep);
                    $("#fm_ktp #nm_dep").val(data_nasabah[0].nm_dep);
                    $("#fm_ktp #kd_bagian").val(data_nasabah[0].kd_bagian);
                    $("#fm_ktp #nm_bagian").val(data_nasabah[0].nm_bagian);

                    get_ktp();

                    url_hapus = situs + "anggota/hapus_ktp/" + data_nasabah[0].no_ang;
                } else {
                    $("#fm_ktp #no_ang").val('');
                    pesan('Data tidak ditemukan');
                }
            }
        });
    }
}

function get_tabel_nasabah() {
    data_ajax = $("#fm_input").serialize();

    $.ajax({
        url: situs + "anggota/get_tabel_nasabah",
        data: data_ajax,
        type: 'post',
        beforeSend: function() {
            $("#div_tabel_nasabah").html("<center>Harap Tunggu .. </center>");
        },
        success: function(data) {
            $("#div_tabel_nasabah").html(data);
        }
    });
}

function simpan() {
    validasi = $("#fm_input").valid();

    if (validasi) {
        konfirmasi = confirm("Anda yakin data sudah benar?");

        if (konfirmasi) {
            data_form = $("#fm_input").serialize();

            $.ajax({
                url: situs + "anggota/add_edit_nasabah",
                data: data_form,
                type: "post",
                dataType: "json",
                beforeSend: function(xhr) {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);
                }
            });
        }
    }
}

function get_nasabah() {
    url_tabel = situs + "anggota/get_nasabah";
    tabel_id = "tabel_nasabah";

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
                data: "tlp_hp"
            }, {
                data: "alm_rmh"
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

function add_data() {
    dataUrl = situs + 'anggota/add_nasabah';
    edit_mode = 0;

    clear_form('fm_modal');
    $('#myModal').modal('show');
}

function edit_data() {
    row = $('#tabel_nasabah').DataTable().row({
        selected: true
    }).data();

    if (row) {
        dataUrl = situs + 'anggota/edit_nasabah/' + row.id_ang;
        edit_mode = 1;

        set_form('fm_modal', row);
        $('#myModal').modal('show');
    } else {
        alert("Pilih data di tabel");
    }
}

function simpan_nasabah() {
    validasi = $("#fm_modal").valid();

    if (validasi) {
        konfirmasi = confirm("Anda yakin data sudah benar?");

        if (konfirmasi) {
            data_form = $("#fm_modal").serialize();

            $.ajax({
                url: dataUrl,
                data: data_form,
                type: "post",
                dataType: "json",
                beforeSend: function(xhr) {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        if (edit_mode) {
                            $('#myModal').modal('hide');
                        } else {
                            clear_form('fm_modal');
                        }
                        get_nasabah();
                    }
                }
            });
        }
    }
}

function hapus_data() {
    row = $("#tabel_nasabah").DataTable().row({
        selected: true
    }).data();

    if (row) {
        konfirmasi = confirm("Anda yakin hapus data ini?");

        if (konfirmasi) {
            $.ajax({
                url: situs + "anggota/del_nasabah",
                data: row,
                type: "post",
                dataType: "json",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_nasabah();
                    }
                }
            });
        }
    } else {
        alert('Pilih data di tabel');
    }
}

function cetak_identitas_buku() {
    row = $("#tabel_nasabah").DataTable().row({
        selected: true
    }).data();

    if (row) {
        $data_ajax = JSON.stringify(row);

        window.open(situs + "cetak/cetak_id_buku?data=" + base64_encode($data_ajax));
    } else {
        alert('Pilih data di tabel');
    }
}

url_hapus = "";

$("#file_ktp").on("change", function() {
    nama_file = $(this).get(0).files[0].name;

    $("#nm_file").val(nama_file);
});

function get_ktp() {
    data_ajax = $("#fm_ktp").serialize();

    $.ajax({
        url: situs + "anggota/get_ktp",
        data: data_ajax,
        type: 'post',
        beforeSend: function() {
            // proses();
        },
        success: function(data) {
            $("#gambar_ktp").attr("src", data);

            // no_proses();
        }
    });
}

function upload_ktp() {
    validasi = $("#fm_ktp").valid();

    if (validasi) {
        if ($("#file_ktp").get(0).files.length < 1) {
            alert("Pilih File KTP");
            return false;
        }

        konfirmasi = confirm("Anda yakin data sudah benar?");

        if (konfirmasi) {
            data_form = new FormData($("#fm_ktp")[0]);

            $.ajax({
                url: situs + "anggota/upload_ktp",
                data: data_form,
                type: "post",
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function(xhr) {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_ktp();

                        $("#file_ktp, #nm_file").val('');
                    }
                }
            });
        }
    }
}

function hapus_ktp() {
    if (url_hapus == "") {
        alert('Pilih Nasabah dulu');
    } else {
        konfirmasi = confirm("Anda yakin?");

        if (konfirmasi) {
            $.ajax({
                url: url_hapus,
                dataType: 'json',
                beforeSend: function(xhr) {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_ktp();
                    }
                }
            });
        }
    }
}
</script>