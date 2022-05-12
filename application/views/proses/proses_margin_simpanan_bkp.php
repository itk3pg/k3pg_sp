<div class="nav-tabs-custom">
    <ul class="nav nav-tabs" id="myTab">
        <li class="active">
            <a href="#margin_sukarela2" class="margin_sukarela2">Margin Sukarela 2</a>
        </li>
        <li>
            <a href="#margin_sukarela" class="margin_sukarela">Margin Sukarela</a>
        </li>
        <li>
            <a href="#margin_syariah" class="margin_syariah">Margin Syariah</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="margin_sukarela2">
            <div class="panel-body">
                <form id="fm_ss2" onsubmit="return false">
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
                        <div class="col-md-6"></div>
                    </div>
                    <a class="btn btn-primary" onclick="proses_margin('ss2')">Proses</a>
                </form>
            </div>
            <div class="panel-footer" id="dv_status_ss2">
                <h5>Ready!</h5>
            </div>
        </div>
        <div class="tab-pane" id="margin_sukarela">
            <div class="panel-body">
                <form id="fm_ss" onsubmit="return false">
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
                        <div class="col-md-6"></div>
                    </div>
                    <a class="btn btn-primary" onclick="proses_margin('ss')">Proses</a>
                </form>
            </div>
            <div class="panel-footer" id="dv_status_ss">
                <h5>Ready!</h5>
            </div>
        </div>
        <div class="tab-pane" id="margin_syariah">
            <div class="panel-body">
                <form id="fm_syariah" onsubmit="return false">
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
                            <div class="form-group">
                                <label>Total Modal yang digunakan</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        Rp
                                    </div>
                                    <input type="text" name="total_modal" id="total_modal" class="form-control" precision="2" groupSeparator="," required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>15% dari Laba Kotor</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        Rp
                                    </div>
                                    <input type="text" name="laba_kotor15" id="laba_kotor15" class="form-control" precision="2" groupSeparator="," required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Proses Margin</label>
                                <input type="text" name="tanggal_simpan" id="tanggal_simpan" class="form-control form-control" required="">
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <a class="btn btn-primary" onclick="proses_margin('syariah')">Proses</a>
                </form>
            </div>
            <div class="panel-footer" id="dv_status_syariah">
                <h5>Ready!</h5>
            </div>
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
        if ($jenis_simpanan == "ss2") {
            url_proses = situs + "simpanan/proses_margin/ss2";
            data_form = $("#fm_ss2").serialize();
            dv_status = "dv_status_ss2";
        }
        if ($jenis_simpanan == "ss") {
            url_proses = situs + "simpanan/proses_margin/ss";
            data_form = $("#fm_ss").serialize();
            dv_status = "dv_status_ss";
        }
        if ($jenis_simpanan == "syariah") {
            url_proses = situs + "simpanan/proses_margin/syariah";
            data_form = $("#fm_syariah").serialize();
            dv_status = "dv_status_syariah";
        }

        $.ajax({
            url: situs + "simpanan/proses_margin/" + $jenis_simpanan,
            data: data_form,
            type: "post",
            beforeSend: function() {
                swal_progress();
                init_proses_margin($jenis_simpanan);
            },
            success: function(data) {
                $progress_timeout = stop_interval($progress_timeout);
                no_proses();

                $("#" + dv_status).html(data);

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
</script>