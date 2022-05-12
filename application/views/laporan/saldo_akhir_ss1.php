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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Periode Simpanan</label>
                            <input type="text" name="tgl_simpan" id="tgl_simpan" class="form-control datepicker" required="" value="<?php echo date('d-m-Y') ?>">
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary" onclick="tampilkan()">Tampilkan</a>
                <a class="btn btn-danger" onclick="cetak()">Cetak</a>
                <a class="btn btn-success" onclick="excel()">Excel</a>
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
            url: situs + "laporan/saldo_akhir_ss1/tampilkan",
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

        window.open(situs + "laporan/saldo_akhir_ss1/cetak?data=" + data_form);
    }
}

function excel() {
    validasi = $('#fm_data').valid();

    if (validasi) {
        data_form = $('#fm_data').serialize();

        window.open(situs + "laporan/saldo_akhir_ss1/excel?" + data_form);
    }
}
</script>