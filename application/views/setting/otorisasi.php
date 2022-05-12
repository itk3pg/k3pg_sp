<div class="panel panel-default panel-color">
    <div class="panel-body">
        <form id="fm_input" onsubmit="return false">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Password Otorisasi</label>
                        <input type="password" name="passwd" id="passwd" class="form-control" required="" value="<?php echo $password; ?>">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="panel-footer">
        <button class="btn btn-primary">
            <i class="fa fa-save"></i> Simpan
        </button>
    </div>
</div>
<script type="text/javascript">
$("button").on("click", function() {
    var data_ajax = $("#fm_input").serialize();

    $.ajax({
        url: situs + "setting/set_password_otorisasi_sp",
        data: data_ajax,
        type: 'post',
        success: function(data) {
            pesan('Password berhasil disetting');
        }
    })
});
</script>