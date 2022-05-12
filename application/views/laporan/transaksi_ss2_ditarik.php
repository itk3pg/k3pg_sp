<ul class="nav nav-tabs navtab-bg" id="myTab">
    <li class="active">
        <a href="#laporan" class="laporan">
            <?php echo $judul_menu; ?>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="laporan">
        <div class="panel-body">
            <form id="fm_data" onsubmit="return false">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Periode</label>
                            <div class="row form-inline" style="margin: 0px;">
                                <input type="text" id="tgl_awal" name="tgl_awal" class="form-control" value="01" size="2" required="" /> s.d. <input type="text" id="tgl_akhir" name="tgl_akhir" class="form-control" value="<?php echo date('t'); ?>" size="2" required="" />
                                <select id="bulan" name="bulan" class="form-control">
                                    <?php echo $bulan; ?>
                                </select>
                                <input type="text" name="tahun" id="tahun" class="form-control" value="<?php echo date('Y'); ?>" maxlength="4" size="4" placeholder="Tahun">
                            </div>
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary" onclick="tampilkan()">Tampilkan</a>
                <a class="btn btn-danger" onclick="cetak()">Cetak</a>
            </form>
        </div>
        <div class="panel-body" id="div_laporan">
            <h5>Ready!</h5>
        </div>
    </div>
</div>
<script type="text/javascript">
laporan_mode();

$("#bulan, #tahun").on('change', function() {
    var_tahun = $("#tahun").val();
    var_bulan = $("#bulan").val();

    var_tgl_akhir_bulan = new Date(var_tahun, var_bulan, 0).getDate();

    $("#tgl_akhir").val(var_tgl_akhir_bulan);
});

function tampilkan() {
    validasi = $('#fm_data').valid();

    if (validasi) {
        data_form = $('#fm_data').serialize();

        $.ajax({
            url: situs + "laporan/transaksi_ss2_ditarik/tampilkan",
            data: data_form,
            type: "POST",
            beforeSend: function() {
                proses();
            },
            success: function(data) {
                no_proses();

                $("#div_laporan").html(data);
            }
        });
    }
}

function cetak() {
    validasi = $('#fm_data').valid();

    if (validasi) {
        data_form = base64_encode(JSON.stringify(get_form_array('fm_data')));

        window.open(situs + "laporan/transaksi_ss2_ditarik/cetak?data=" + data_form);
    }
}
</script>