<form id="fm_data">
    <div class="box panel-info">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>NAK</label>
                        <br>
                        <input type="text" id="no_ang" name="no_ang" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Perusahaan Asal</label>
                        <div class="row" style="margin: 0px;">
                            <div class="col-md-2" style="padding: 0px;">
                                <input type="text" id="kd_prsh" name="kd_prsh" class="form-control" readonly>
                            </div>
                            <div class="col-md-10" style="padding: 0 0 0 5px;">
                                <input type="text" id="nm_prsh" name="nm_prsh" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Periode</label>
                        <div class="row" style="margin: 0px">
                            <div class="col-md-9" style="padding: 0px">
                                <select name="bulan" id="bulan" class="form-control">
                                    <?php echo $bulan; ?>
                                </select>
                            </div>
                            <div class="col-md-3" style="padding: 0 0 0 5px">
                                <input type="text" name="tahun" id="tahun" class="form-control" value="<?php echo date('Y'); ?>">
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
                        <label>Departemen Asal</label>
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
                        <label>Bagian Asal</label>
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
        </div>
        <div class="panel-body">
            <div style="margin: 0px;">
                <a href="javascript: void(0)" class="btn btn-primary btn-small" onclick="tampilkan()">
                    <i class="fa fa-search"></i> Tampilkan</a>
            </div>
        </div>
        <hr>
        <div class="row" style="margin: 0px">
            <div id="div_view" class="col-md-12"></div>
        </div>
    </div>
</form>
<script type="text/javascript">

$("#no_ang").combogrid({
    mode: 'remote',
    url: situs + "anggota/select_anggota/0",
    idField: 'no_ang',
    textField: 'no_ang',
    panelWidth: 800,
    editable: true,
    required: true,
    readonly: false,
    selectOnNavigation: false,
    hasDownArrow: true,
    fitColumns: true,
    columns: [
        [
            { field: 'no_ang', title: 'NAK', width: 15 },
            { field: 'no_peg', title: 'No. Pegawai', width: 15 },
            { field: 'nm_ang', title: 'Nama Anggota', width: 50 },
            { field: 'nm_prsh', title: 'Perusahaan', width: 25 },
            { field: 'nm_dep', title: 'Departemen', width: 25 },
            { field: 'nm_bagian', title: 'Bagian', width: 25 }
        ]
    ],
    onLoadSuccess: function() {
        g = $(this).combogrid('grid');

        if (g.datagrid('getData').total > 0) {
            g.datagrid('highlightRow', 0);
        }
    },
    onSelect: function(index, row) {
        $("#nm_ang").val(row.nm_ang);
        $("#no_peg").val(row.no_peg);
        $("#kd_prsh").val(row.kd_prsh);
        $("#nm_prsh").val(row.nm_prsh);
        $("#kd_dep").val(row.kd_dep);
        $("#nm_dep").val(row.nm_dep);
        $("#kd_bagian").val(row.kd_bagian);
        $("#nm_bagian").val(row.nm_bagian);
    }
});

function tampilkan() {
    if ($('#fm_data').valid()) {
        data_form = $('#fm_data').serialize();

        $.ajax({
            url: situs + "simpanan/view_saldo_simpanan/",
            data: data_form,

            type: "POST",
            beforeSend: function() {
                proses();
            },
            success: function(data) {
                no_proses();

                $("#div_view").html(data);
            }
        });
    }
}

function batal() {
    clear_form('fm_data');
    $("html").animate({ scrollTop: 0 }, 500);
    $("#status_anggota").html('');
}

batal();
</script>