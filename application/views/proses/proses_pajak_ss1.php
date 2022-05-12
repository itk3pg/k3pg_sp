<ul class="nav nav-tabs navtab-bg" id="myTab">
    <li class="active">
        <a href="#proses_pajak_ss1" class="proses_pajak_ss1">Proses Pajak SS1</a>
    </li>
	<li>
		<a href="#proses_pajak_ss2" class="proses_margin_ss2">Proses Pajak SS2</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="proses_pajak_ss1">
        <div class="panel-body">
            <div class="alert alert-primary">
                <h4>Gunakan Form Ini Hanya jika proses otomatis tidak berjalan.</h4>
            </div>
            <form id="fm_proses_pajak_ss1" onsubmit="return false">
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
                </div>
                <a class="btn btn-primary" onclick="proses_pajak_ss1()">Proses</a>
            </form>
        </div>
        <div class="panel-footer" id="dv_status">
            <h5>Ready!</h5>
        </div>
    </div>
	
	<div class="tab-pane" id="proses_pajak_ss2">
        <div class="panel-body">
            <div class="alert alert-primary">
                <h4>Gunakan Form Ini Hanya jika proses otomatis tidak berjalan.</h4>
            </div>
            <form id="fm_proses_pajak_ss2" onsubmit="return false">
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
                </div>
                <a class="btn btn-primary" onclick="proses_pajak_ss2()">Proses</a>
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

function proses_pajak_ss1() {
    konfirmasi = confirm("Anda yakin?");

    if (konfirmasi) {
        data_form = $("#fm_proses_pajak_ss1").serialize();

        $.ajax({
            url: situs + "simpanan/proses_pajak_ss1/",
            data: data_form,
            type: "post",
            beforeSend: function() {
                swal_progress();
                init_proses_pajak_ss1();
            },
            success: function(data) {
                no_proses();
                clearInterval($progress_timeout);

                $("#dv_status").html(data);
            }
        });
    }
}

function proses_pajak_ss2() {
    konfirmasi = confirm("Anda yakin?");

    if (konfirmasi) {
        data_form = $("#fm_proses_pajak_ss2").serialize();

        $.ajax({
            url: situs + "simpanan/proses_pajak_ss2/",
            data: data_form,
            type: "post",
            beforeSend: function() {
                swal_progress();
                //init_proses_pajak_ss1();
            },
            success: function(data) {
                no_proses();
               // clearInterval($progress_timeout);
				alert("proses sinkron pajak ss2 berhasil");
                $("#dv_status").html(data);
            }
        });
    }
}


function init_proses_pajak_ss1() {
    $.ajax({
        url: situs + "simpanan/init_progress_pajak_ss1/",
        async: false,
        success: function() {
            $progress_timeout = setInterval(function() {
                get_proses_pajak_ss1();
            }, 1000);
        }
    });
}

function get_proses_pajak_ss1() {
    $.ajax({
        url: situs + "simpanan/get_progress_pajak_ss1/",
        cache: false,
        dataType: "json",
        timeout: 0,
        success: function(data) {
            $("#swal_pg").html("<b>" + data.persen + "% (" + data.data_now + "/" + data.data_total + ") </b>");
        }
    });
}
</script>