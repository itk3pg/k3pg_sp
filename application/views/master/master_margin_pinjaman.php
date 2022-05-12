<div class="panel panel-default panel-color">
    <div class="panel-heading">
        <button class="btn btn-success btn-small" onclick="add()">
            <i class="fa fa-plus"></i> Tambah</button>
        <button class="btn btn-warning btn-small" onclick="edit()">
            <i class="fa fa-pencil"></i> Edit</button>
        <button class="btn btn-danger btn-small" onclick="del()">
            <i class="fa fa-trash"></i> Hapus</button>
    </div>
    <div class="panel-body">
        <table id="tabel_margin_pinjaman" class="table table-bordered table-condensed table-hover table-striped nowrap" width="100%">
            <thead>
                <tr>
                    <th width="50">No.</th>
                    <th>Kode Jenis Pinjaman</th>
                    <th>Jenis Pinjaman</th>
                    <th>Jangka (Bulan)</th>
                    <th>Jangka PHT (Bulan)</th>
                    <th>Jenis Margin</th>
                    <th>Margin</th>
                    <th>Tgl. Berlaku</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="width: 30%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Margin Pinjaman</h4>
            </div>
            <div class="modal-body">
                <form id="fm_modal" onsubmit="return false">
                    <div class="form-group">
                        <label>Jenis Pinjaman</label>
                        <select id="kd_jns_pinjaman" name="kd_jns_pinjaman" class="form-control" required=""></select>
                        <input type="hidden" id="nm_jns_pinjaman" name="nm_jns_pinjaman">
                    </div>
                    <div class="form-group">
                        <label>Jangka</label>
                        <div class="input-group">
                            <select id="tempo_bln" name="tempo_bln" class="form-control" required="">
                                <?php echo $tempo_bln; ?>
                            </select>
                            <div class="input-group-addon">Bulan</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jangka PHT (Bulan)</label>
                        <div class="row" style="margin: 0px;">
                            <div class="col-md-5" style="padding: 0px;">
                                <input type="text" name="bln_awal" id="bln_awal" class="form-control" required="">
                            </div>
                            <div class="col-md-2 text-center">
                                <label>s.d.</label>
                            </div>
                            <div class="col-md-5" style="padding: 0 0 0 5px;">
                                <input type="text" name="bln_akhir" id="bln_akhir" class="form-control" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Margin</label>
                        <div class="input-group">
                            <input type="text" id="rate" name="rate" class="form-control" data-rule-number="true" required>
                            <div class="input-group-addon">%</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tgl. Berlaku</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" name="tgl_berlaku" id="tgl_berlaku" class="form-control datepicker" required="" readonly="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="simpan()">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
edit_mode = 0;

function get_margin_pinjaman() {
    url_tabel = situs + "master/get_margin_pinjaman";
    tabel_id = "tabel_margin_pinjaman";

    if ($.fn.DataTable.isDataTable("#" + tabel_id)) {
        $("#" + tabel_id).DataTable().ajax.url(url_tabel).load(function() {
            // $('#tabel_piutang').DataTable().responsive.recalc().responsive.rebuild();
        }, false);
    } else {
        $("#" + tabel_id).DataTable({
            scrollY: 350,
            scrollX: true,
            ordering: false,
            paging: true,
            searching: true,
            select: 'single',
            processing: true,
            serverSide: true,
            ajax: url_tabel,
            columns: [{
                data: "nomor",
                className: "text-right"
            }, {
                data: "kd_jns_pinjaman"
            }, {
                data: "nm_jns_pinjaman"
            }, {
                data: "tempo_bln"
            }, {
                render: function(data, type, row) {
                    return row.bln_awal + " s.d. " + row.bln_akhir;
                }
            }, {
                data: "jenis_rate"
            }, {
                data: "rate"
            }, {
                data: "tgl_berlaku"
            }],
            initComplete: function() {
                var input = $("#" + tabel_id + "_filter input").unbind(),
                    self = this.api(),
                    $searchButton = $('<button>').addClass('btn btn-primary').text('Cari').click(function() {
                        self.search(input.val()).draw();
                    }),
                    $clearButton = $('<button>').addClass('btn btn-default').text('Reset').click(function() {
                        input.val('');
                        self.search('').draw();
                        // $searchButton.click();
                    });

                $("#" + tabel_id + "_filter").append("&nbsp;", $searchButton, "&nbsp;", $clearButton);
                $("#" + tabel_id + "_filter input").keyup(function(e) {
                    if (e.keyCode == "13") {
                        self.search(input.val()).draw();
                    }
                });
            }
        });
    }
}

setTimeout(function() {
    get_margin_pinjaman();
}, 700);

$("#fm_modal #kd_jns_pinjaman").select2({
    ajax: {
        url: situs + 'master/select_jenis_pinjaman',
        dataType: 'json',
        delay: 500
    }
}).on("select2:select", function(e) {
    var s2data = e.params.data;

    $("#fm_modal #nm_jns_pinjaman").val(s2data.nm_jns_pinjaman);

    set_jangka(s2data.kd_jns_pinjaman);
});

function set_jangka($kd_jns_pinjaman) {
    $("#fm_modal").validate().destroy();

    if ($kd_jns_pinjaman == "3") {
        $("#tempo_bln").attr("disabled", true).val("");
        $("#bln_awal, #bln_akhir").removeAttr("readonly").val("");

        $("#fm_modal").validate({
            rules: {
                tempo_bln: {
                    required: false
                },
                bln_awal: {
                    required: true,
                    number: true
                },
                bln_akhir: {
                    required: true,
                    number: true
                }
            }
        });
    } else {
        $("#tempo_bln").removeAttr("disabled");
        $("#bln_awal, #bln_akhir").attr("readonly", true).val("0");

        $("#fm_modal").validate({
            rules: {
                tempo_bln: {
                    required: true
                },
                bln_awal: {
                    required: false
                },
                bln_akhir: {
                    required: false
                }
            }
        });
    }

    $("#fm_modal").valid();
}

function add() {
    dataUrl = situs + 'master/add_margin_pinjaman';
    edit_mode = 0;

    clear_form('fm_modal');
    $('#myModal').modal('show');
    $('#myModal').on('shown.bs.modal', function() {});
}

function edit() {
    row = $('#tabel_margin_pinjaman').DataTable().row({
        selected: true
    }).data();

    if (row) {
        dataUrl = situs + 'master/edit_margin_pinjaman/' + row.id_rate_pinjaman;
        edit_mode = 1;

        set_jangka(row.kd_jns_pinjaman);

        set_form('fm_modal', row);

        set_select2_value("#fm_modal #kd_jns_pinjaman", row.kd_jns_pinjaman, row.nm_jns_pinjaman);

        $('#myModal').modal('show');
        $('#myModal').on('shown.bs.modal', function() {});
    } else {
        alert("Pilih data di tabel");
    }
}

function simpan() {
    validasi = $('#fm_modal').valid();

    if (validasi) {
        data_input = $('#fm_modal').serialize();

        $.ajax({
            url: dataUrl,
            data: data_input,
            dataType: "JSON",
            type: "POST",
            beforeSend: function() {
                proses();
            },
            success: function(res) {
                pesan(res.msg, 1);

                if (res.status) {
                    if (edit_mode) {
                        $('#myModal').modal('hide');
                    } else {
                        clear_form("fm_modal");
                    }

                    get_margin_pinjaman();
                }
            }
        });
    }
}

function del() {
    row = $('#tabel_margin_pinjaman').DataTable().row({
        selected: true
    }).data();

    if (row) {
        prompt = confirm("Anda Yakin Ingin Menghapus Data Ini?");

        if (prompt) {
            $.ajax({
                url: situs + "master/del_margin_pinjaman",
                data: "id_rate_pinjaman=" + row.id_rate_pinjaman,
                dataType: "JSON",
                type: "POST",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_margin_pinjaman();
                    }
                }
            });
        }
    } else {
        alert("Pilih data di tabel");
    }
}
</script>