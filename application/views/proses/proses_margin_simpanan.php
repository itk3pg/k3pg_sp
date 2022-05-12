<ul class="nav nav-tabs navtab-bg" id="myTab">
    <li class="active">
        <a href="#proses_margin_simpanan" class="proses_margin_simpanan">Proses Margin Simpanan</a>
    </li>
	<li>
		<a href="#proses_margin_ss2" class="proses_margin_ss2">Proses Margin Simpanan Pertanggal</a>
    </li>
	<li>
		<a href="#proses_margin_ss1_ang" class="proses_margin_ss1_ang">Proses Margin Simpanan PerAnggota</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="proses_margin_simpanan">
        <div class="panel-body">
            <div class="alert alert-primary">
                <h4>Gunakan Form Ini Hanya jika proses otomatis tidak berjalan.</h4>
            </div>
            <form id="fm_proses_margin" onsubmit="return false">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Periode</label>
                            <div class="row" style="margin: 0px;">
                                <div class="col-md-8" style="padding: 0px;">
                                    <select id="bulan" name="bulan" class="form-control">
                                        <?php echo $bulan; ?>
                                    </select>
                                </div>
                                <div class="col-md-4" style="padding: 0 0 0 5px;">
                                    <input type="text" name="tahun" id="tahun" class="form-control" value="<?php echo date('Y'); ?>" maxlength="4" size="4" placeholder="Tahun">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jenis Simpanan</label>
                            <select id="jenis_simpanan" name="jenis_simpanan" class="form-control">
                                <option value="SS1">Simpanan Sukarela 1</option>
                                <option value="SS2">Simpanan Sukarela 2</option>
                            </select>
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary" onclick="proses_margin($('#jenis_simpanan').val())">Proses</a>
            </form>
        </div>
        <div class="panel-footer" id="dv_status">
            <h5>Ready!</h5>
        </div>
    </div>
	
	<div class="tab-pane" id="proses_margin_ss2">
        <div class="panel-body">
            <div class="alert alert-primary">
                <h4>Gunakan Form Ini Hanya jika proses otomatis tidak berjalan.</h4>
            </div>
            <form id="fm_proses_margin_tgl" onsubmit="return false">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Periode</label>
                            <div class="row" style="margin: 0px;">
                                <div class="col-md-8">
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" placeholder="Tanggal">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jenis Simpanan</label>
                            <select id="jenis_simpanan" name="jenis_simpanan" class="form-control">
                                <option value="SS2">Simpanan Sukarela 2</option>
                            </select>
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary" onclick="proses_margin_tgl($('#jenis_simpanan').val())">Proses</a>
            </form>
        </div>
        <div class="panel-footer" id="dv_status">
            <h5>Ready!</h5>
        </div>
    </div>
	<!-- margin ss1 anggota -->
	<div class="tab-pane" id="proses_margin_ss1_ang">
        <div class="panel-body">
            <div class="alert alert-primary">
                <h4>Gunakan Form Ini Hanya jika proses otomatis tidak berjalan.</h4>
            </div>
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
				<div class="row">
					<div class="col-md-4">
                        <div class="form-group">
                            <label>Periode</label>
                            <div class="row" style="margin: 0px;">
                                <div class="col-md-8" style="padding: 0px;">
                                    <select id="bulan" name="bulan" class="form-control">
                                        <?php echo $bulan; ?>
                                    </select>
                                </div>
                                <div class="col-md-4" style="padding: 0 0 0 5px;">
                                    <input type="text" name="tahun" id="tahun" class="form-control" value="<?php echo date('Y'); ?>" maxlength="4" size="4" placeholder="Tahun">
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Jenis Simpanan</label>
                            <select id="jns_simpanan" name="jns_simpanan" class="form-control">
								<option value="SS1">Simpanan Sukarela 1</option>
                                <option value="SS2">Simpanan Sukarela 2</option>
                            </select>
						</div>
					</div>
				</div>
                <a class="btn btn-primary" onclick="margin_ss1_anggota()">Proses</a>
            </form>
        </div>
        <div class="panel-footer" id="dv_status">
            <h5>Ready!</h5>
        </div>
    </div>
</div>
<script type="text/javascript">
var $progress_timeout = null;
var $jenis_simpanan = null;

$('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
});


function proses_margin($jenis_simpanan) {
    if ($jenis_simpanan == "syariah") {
        validasi = $("#fm_syariah").valid();

        if (!validasi) return false;
    }

    konfirmasi = confirm("Anda yakin?");

    if (konfirmasi) {
        data_form = $("#fm_proses_margin").serialize();

        $.ajax({
            url: situs + "simpanan/proses_margin/" + $jenis_simpanan,
            data: data_form,
            type: "post",
            beforeSend: function() {
                swal_progress();
                init_proses_margin($jenis_simpanan);
            },
            success: function(data) {
                no_proses();
                clearInterval($progress_timeout);

                $("#dv_status").html(data);
            }
        });
    }
}

function proses_margin_tgl($jenis_simpanan) {
    if ($jenis_simpanan == "syariah") {
        validasi = $("#fm_syariah").valid();

        if (!validasi) return false;
    }

    konfirmasi = confirm("Anda yakin?");

    if (konfirmasi) {
        data_form = $("#fm_proses_margin_tgl").serialize();

        $.ajax({
            url: situs + "simpanan/proses_margin_tgl/" + $jenis_simpanan,
            data: data_form,
            type: "post",
            beforeSend: function() {
                swal_progress();
                //init_proses_margin($jenis_simpanan);
            },
            success: function(data) {
                no_proses();
                clearInterval($progress_timeout);

                $("#dv_status").html(data);
            }
        });
    }
}

function init_proses_margin($jenis_simpanan) {
    $.ajax({
        url: situs + "simpanan/init_progress_margin/" + $jenis_simpanan,
        async: false,
        success: function() {
            $progress_timeout = setInterval(function() {
                get_proses_margin($jenis_simpanan);
            }, 1000);
        }
    });
}

function get_proses_margin($jenis_simpanan) {
    $.ajax({
        url: situs + "simpanan/get_progress_margin/" + $jenis_simpanan,
        cache: false,
        dataType: "json",
        timeout: 0,
        success: function(data) {
            $("#swal_pg").html("<b>" + data.persen + "% (" + data.data_now + "/" + data.data_total + ") </b>");
        }
    });
}

// --- data anggota ---
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

function margin_ss1_anggota() {
	konfirmasi = confirm("Anda yakin?");

    if (konfirmasi) {
        data_form = $("#fm_data").serialize();

        $.ajax({
            url: situs + "simpanan/proses_bungass1_ang/",
            data: data_form,
            type: "post",
            beforeSend: function() {
				if ($('[name="no_ang"]').val() == '') {
					alert("No.anggota Harus Diisi");
					return false;
				}
                swal_progress();
       
            },
            success: function(data) {
                no_proses();
                alert("Data Berhasil Disinkron");
            }
        });
    }
}
</script>