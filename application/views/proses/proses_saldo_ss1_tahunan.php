<ul class="nav nav-tabs navtab-bg" id="myTab">
    <li class="active">
        <a href="#proses_saldo_simp_tahunan" class="proses_saldo_simp_tahunan">Proses Saldo Simp. Tahunan</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="proses_saldo_simp_tahunan">
        <div class="panel-body">
            <div class="alert alert-primary">
                <h4>Gunakan Form Ini Hanya jika proses otomatis tidak berjalan.</h4>
            </div>
            <form id="fm_proses_saldo_simp_tahunan" onsubmit="return false">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Periode</label>
                            <input type="text" name="tahun" id="tahun" class="form-control" value="<?php echo date('Y'); ?>" maxlength="4" size="4" placeholder="Tahun">
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary" onclick="proses_saldo_simp_tahunan()">Proses</a>
            </form>
        </div>
        <div class="panel-footer" id="dv_status">
            <h5>Ready!</h5>
        </div>
    </div>
</div>
<script type="text/javascript">
var $progress_timeout = null

$('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
});

function proses_saldo_simp_tahunan() {
    konfirmasi = confirm("Anda yakin?");

    if (konfirmasi) {
        data_form = $("#fm_proses_saldo_simp_tahunan").serialize();

        $.ajax({
            url: situs + "simpanan/proses_saldo_simp_tahunan/",
            data: data_form,
            type: "post",
            beforeSend: function() {
                swal_progress();
                init_proses_saldo_simp_tahunan();
            },
            success: function(data) {
                no_proses();
                clearInterval($progress_timeout);

                $("#dv_status").html(data);
            }
        });
    }
}

function init_proses_saldo_simp_tahunan() {
    $.ajax({
        url: situs + "simpanan/init_progress_saldo_simp_tahunan/",
        async: false,
        success: function() {
            $progress_timeout = setInterval(function() {
                get_proses_saldo_simp_tahunan();
            }, 1000);
        }
    });
}

function get_proses_saldo_simp_tahunan() {
    $.ajax({
        url: situs + "simpanan/get_progress_saldo_simp_tahunan/",
        cache: false,
        dataType: "json",
        timeout: 0,
        success: function(data) {
            $("#swal_pg").html("<b>" + data.persen + "% (" + data.data_now + "/" + data.data_total + ") </b>");
        }
    });
}
</script>