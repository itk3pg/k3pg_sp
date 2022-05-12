<div class="panel panel-default panel-color">
    <div class="panel-body">
        <div class="row">
            <form id="fm_data" onsubmit="return false">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>NAK</label>
                            <input type="text" name="no_ang" id="no_ang" class="form-control" data-rule-required="true" autocomplete="off" style="text-transform: uppercase;">
                            <!-- <select id="no_ang" name="no_ang" class="form-control" required=""></select> -->
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Awal</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" name="tgl_awal" id="tgl_awal" class="form-control datepicker" readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Akhir</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" name="tgl_akhir" id="tgl_akhir" class="form-control datepicker" readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tampilkan data</label>
                            <select name="mode_data" id="mode_data" class="form-control">
                                <option value="belumcetak">Belum Tercetak</option>
                                <option value="semuadata">Semua Data</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Mulai Baris Ke</label>
                            <input type="text" name="baris_ke" id="baris_ke" class="form-control" data-rule-number="true" value="1">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-heading text-center">
        <button type="button" class="btn btn-primary" onclick="tampilkan()"><i class="fa fa-search"></i> Tampilkan</button>
        <button class="btn btn-info btn-small" onclick="cetak_semua_data()"> <i class="fa fa-print"></i> Cetak Buku</button>
    </div>
    <br>
    <div class="panel-body" id="div_data_cetak" style="max-height: 500px;overflow: auto;"></div>
</div>
<script type="text/javascript">
var mode_cetak = "off";

$(window).on("focus", function() {
    set_status_cetak();
});

var ev_get_anggota = 1;

$("#fm_data #no_ang").focus().on("change", function() {
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
    $no_ang = $("#fm_data #no_ang").val();

    $.ajax({
        url: situs + 'anggota/select_nasabah_by_noang',
        data: "q=" + $no_ang,
        type: 'post',
        dataType: 'json',
        beforeSend: function() {

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
            } else {
                $("#fm_data #no_ang").val('');
                pesan('Data tidak ditemukan');
            }
        }
    });
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
// });

function tampilkan() {
    validasi = $('#fm_data').valid();

    if (validasi) {
        $("#div_data_cetak").html('');
        
        var_mode_data = $("#mode_data").val();

        if (var_mode_data == "semuadata") {
            var_tgl_awal = $("#tgl_awal").val();
            var_tgl_akhir = $("#tgl_akhir").val();

            if (var_tgl_awal == "") {
                alert("Tanggal Awal harap diisi");
                return false;
            }

            if (var_tgl_akhir == "") {
                alert("Tanggal Akhir harap diisi");
                return false;
            }
        }

        $data_ajax = JSON.stringify(get_form_array('fm_data'));

        $.ajax({
            url: situs + "cetak/get_data_cetak_buku_ss1",
            data: "data=" + base64_encode($data_ajax),
            type: 'post',
            beforeSend: function() {
                $("#div_data_cetak").html('<center><i class=\"fa fa-spinner fa-pulse\"></i> Loading ... </center>');
            },
            success: function(data) {
                $("#div_data_cetak").html(data);

                if ($("#mode_data").val() == "semuadata") {
                    $("html, body").animate({ scrollTop: $("body").height() }, 100);
                    $("#div_data_cetak").animate({ scrollTop: $("#div_data_cetak table").height() }, 100);
                }
            }
        });
    }
}

function cetak_semua_data() {
    validasi = $('#fm_data').valid();

    if (validasi) {
        tampilkan();
        $data_ajax = JSON.stringify(get_form_array('fm_data'));

        window.open(situs + "cetak/cetak_buku_ss1?data=" + base64_encode($data_ajax));
        mode_cetak = "all";
    }
}

function set_status_cetak() {
    if (mode_cetak != "off") {
        swal({
            title: 'Apakah Data Sudah Tercetak di Buku?',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, data sudah tercetak!',
            cancelButtonText: 'Tidak, data belum tercetak!',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false
        }).then((result) => {
            if (result.value) {
                dataSetCetak = $("#fm_data").serialize();

                $.ajax({
                    url: situs + "cetak/set_status_cetak_ss1",
                    data: dataSetCetak,
                    type: 'post',
                    success: function(data) {
                        tampilkan();
                        // pesan("Data berhasil dicetak");
                    }
                });
            }
        });

        mode_cetak = "off";
    }
    // if (mode_cetak != "off") {
    //     konfirmasi = confirm("Apakah data sudah tercetak?");

    //     if (konfirmasi) {
    //         dataSetCetak = $("#fm_data").serialize();

    //         $.ajax({
    //             url: situs + "cetak/set_status_cetak_ss1",
    //             data: dataSetCetak,
    //             type: 'post',
    //             success: function(data) {
    //                 tampilkan();
    //                 pesan("Data berhasil dicetak");
    //             }
    //         });
    //     }

    //     mode_cetak = "off";
    // }
}
</script>