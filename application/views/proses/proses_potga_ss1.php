<ul class="nav nav-tabs navtab-bg" id="myTab">
    <li class="active">
        <a href="#proses_potga_ss1" class="proses_potga_ss1">Proses Potga SS1</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="proses_potga_ss1">
        <div class="panel-body">
            <div class="alert alert-primary">
                <h4>Gunakan Form Ini Hanya jika proses otomatis tidak berjalan.</h4>
            </div>
            <form id="fm_proses_potga_ss1" onsubmit="return false">
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="text" name="tanggal" id="tanggal" class="form-control" autocomplete="off" value="<?=date('d'); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Perusahaan</label>
                            <select id="kd_prsh" name="kd_prsh" class="form-control" required=""></select>
                        </div>
                    </div>
                </div>
				<a class="btn btn-info" onclick="view_potga_ss1()">View</a>
				<a class="btn btn-success" onclick="excel_potga_ss1()">Excel</a>
                <a class="btn btn-primary" onclick="proses_potga_ss1()">Proses</a>
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

$("#fm_proses_potga_ss1 #kd_prsh").select2({
    ajax: {
        url: situs + 'master/select_perusahaan',
        dataType: 'json',
        delay: 500
    }
}).on("select2:select", function(e) {
    s2data = e.params.data;

    $("#fm_proses_potga_ss1 #nm_prsh").val(s2data.nm_prsh);
});

function proses_potga_ss1() {
    konfirmasi = confirm("Anda yakin?");

    if (konfirmasi) {
        data_form = $("#fm_proses_potga_ss1").serialize();

        $.ajax({
            url: situs + "simpanan/proses_potga_ss1/",
            data: data_form,
            type: "post",
            beforeSend: function() {
                swal_progress();
                init_proses_potga_ss1();
            },
            success: function(data) {
                no_proses();
                clearInterval($progress_timeout);

                $("#dv_status").html(data);
            }
        });
    }
}

function init_proses_potga_ss1() {
    $.ajax({
        url: situs + "simpanan/init_progress_potga_ss1/",
        async: false,
        success: function() {
            $progress_timeout = setInterval(function() {
                get_proses_potga_ss1();
            }, 1000);
        }
    });
}

function get_proses_potga_ss1() {
    $.ajax({
        url: situs + "simpanan/get_progress_potga_ss1/",
        cache: false,
        dataType: "json",
        timeout: 0,
        success: function(data) {
            $("#swal_pg").html("<b>" + data.persen + "% (" + data.data_now + "/" + data.data_total + ") </b>");
        }
    });
}

function view_potga_ss1() {
    validasi = $('#fm_proses_potga_ss1').valid();

    if (validasi) {
        data_form = $('#fm_proses_potga_ss1').serialize();

        $.ajax({
            url: situs + "laporan/view_potga_ss1/tampilkan",
            data: data_form,
            type: "POST",
            beforeSend: function() {
                proses();
            },
            success: function(data) {
                no_proses();

                $("#dv_status").html(data);
            }
        });
    }
}

function excel_potga_ss1() {
    validasi = $('#fm_proses_potga_ss1').valid();

    if (validasi) {
        data_form = $('#fm_proses_potga_ss1').serialize();

        window.open(situs + "laporan/view_potga_ss1/excel?" + data_form);
    }
}
</script>