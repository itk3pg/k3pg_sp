<ul class="nav nav-tabs navtab-bg" id="myTab">
    <li class="active">
        <a href="#proses_pajak_ss1" class="proses_pajak_ss1">Sinkron Bridging</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="proses_pajak_ss1">
        <div class="panel-body">
            <div class="alert alert-primary">
                
            </div>
            <form id="fm_proses_pajak_ss1" onsubmit="return false">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Periode</label>
                            <div class="row" style="margin: 0px;">
                                <div class="col-md-8" style="padding: 0px;">
                                   <input type="text" name="tanggal" id="tanggal" class="form-control datepicker" required="" value="<?php echo date('d-m-Y'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary" onclick="sinkron_bridging()">Proses</a>
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

function sinkron_bridging() {
    konfirmasi = confirm("Anda yakin?");

    if (konfirmasi) {
        data_form = $("#fm_proses_pajak_ss1").serialize();

        $.ajax({
            url: situs + "bridging/sinkron_bridging_ss1/",
            data: data_form,
            type: "post",
            beforeSend: function() {
                swal_progress();
                //init_proses_pajak_ss1();
            },
            success: function(data) {
                no_proses();
                clearInterval($progress_timeout);

                $("#dv_status").html(data);
				alert("Data Berhasil Disinkron");
            }
        });
    }
}

</script>