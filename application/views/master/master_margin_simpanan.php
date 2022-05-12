<div class="panel panel-default panel-color">
    <div class="panel-heading">
        <button class="btn btn-success btn-small" onclick="add()">
            <i class="fa fa-plus"></i> Tambah</button>
        <!-- <button class="btn btn-warning btn-small" onclick="edit()">
            <i class="fa fa-pencil"></i> Edit</button> -->
        <button class="btn btn-danger btn-small" onclick="del()">
            <i class="fa fa-trash"></i> Hapus</button>
    </div>
    <div class="panel-body">
        <table id="tabel_margin_simpanan" class="table table-bordered table-condensed table-hover table-striped nowrap" width="100%">
            <thead>
                <tr>
                    <th width="50">No.</th>
                    <th>Kode Jenis Simpanan</th>
                    <th>Jenis Simpanan</th>
                    <th>Jangka (Bulan)</th>
                    <th>Nominal</th>
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
                <h4 class="modal-title" id="myModalLabel">Rate Simpanan</h4>
            </div>
            <div class="modal-body">
                <form id="fm_modal" onsubmit="return false">
                    <div class="form-group">
                        <label>Jenis Simpanan</label>
                        <select id="kd_jns_simpanan" name="kd_jns_simpanan" class="form-control" required="">
                            <option value="3000">SIMPANAN SUKARELA 1</option>
                            <option value="4000">SIMPANAN SUKARELA 2</option>
                        </select>
                        <input type="hidden" id="nm_jns_simpanan" name="nm_jns_simpanan">
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
                        <label>Nominal</label>
                        <div class="row form-inline">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-addon">Rp</div>
                                    <input type="text" id="min_simpan" name="min_simpan" class="form-control number_format" required="" data-rule-number="true" placeholder="Jml. Minimal">
                                </div>
                            </div>
                            <div class="col-md-1">
                                s.d.
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <div class="input-group-addon">Rp</div>
                                    <input type="text" id="max_simpan" name="max_simpan" class="form-control number_format" required="" data-rule-number="true" placeholder="Jml. Maximal">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Margin</label>
                        <div class="input-group">
                            <input type="text" id="rate" name="rate" class="form-control" required="" data-rule-number="true">
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

$('#myModal').on("shown.bs.modal", function() {
    $('#fm_modal').valid();
});

function get_margin_simpanan() {
    url_tabel = situs + "master/get_margin_simpanan";
    tabel_id = "tabel_margin_simpanan";

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
                data: "kd_jns_simpanan"
            }, {
                data: "nm_jns_simpanan"
            }, {
                data: "tempo_bln"
            }, {
                data: null,
                render: function(data, type, row, meta) {
                    return number_format(row.min_simpan, 2) + " s.d. " + number_format(row.max_simpan, 2);
                }
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
    get_margin_simpanan();
}, 700);

$("#fm_modal #kd_jns_simpanan").select2().on("select2:select", function(e) {
    // var s2data = e.params.data;

    // $("#fm_modal #nm_jns_simpanan").val(s2data.nm_jns_simpanan);

    mode_jangka_simpanan($('#kd_jns_simpanan').val());
    mode_nominal($('#kd_jns_simpanan').val());
    $("#fm_modal").valid();
});
/*$("#fm_modal #kd_jns_simpanan").select2({
    ajax: {
        url: situs + 'master/select_jenis_simpanan/SUKARELA',
        dataType: 'json',
        delay: 500
    }
}).on("select2:select", function(e) {
    var s2data = e.params.data;

    $("#fm_modal #nm_jns_simpanan").val(s2data.nm_jns_simpanan);

    mode_jangka_simpanan(s2data.kd_jns_simpanan);
});*/

function mode_jangka_simpanan($kd_jns_simpanan) {
    $("#fm_modal").validate().destroy();

    if ($kd_jns_simpanan == "3000") {
        $("#fm_modal").validate({
            rules: {
                tempo_bln: {
                    required: false
                }
            }
        })
        $("#fm_modal #tempo_bln").val('').attr("disabled", true);
    } else {
        $("#fm_modal").validate({
            rules: {
                tempo_bln: {
                    required: true
                }
            }
        });
        $("#fm_modal #tempo_bln").removeAttr("disabled");
    }
}

function mode_nominal($kd_jns_simpanan) {
    if ($kd_jns_simpanan == '3000') {
        $("#fm_modal").validate({
            rules: {
                min_simpan: {
                    required: false
                },
                max_simpan: {
                    required: false
                }
            }
        });

        $("#fm_modal #min_simpan, #fm_modal #max_simpan").val('0').attr("disabled", true).trigger('change');
    } else {
        $("#fm_modal").validate({
            rules: {
                min_simpan: {
                    required: true
                },
                max_simpan: {
                    required: true
                }
            }
        });

        $("#fm_modal #min_simpan, #fm_modal #max_simpan").val('').attr("disabled", false).trigger('change');
    }
}

function add() {
    dataUrl = situs + 'master/add_margin_simpanan';
    edit_mode = 0;

    clear_form('fm_modal');

    $('#myModal').modal('show');
}

function edit() {
    row = $('#tabel_margin_simpanan').DataTable().row({
        selected: true
    }).data();

    if (row) {
        dataUrl = situs + 'master/edit_margin_simpanan/' + row.id_rate_simpanan;
        edit_mode = 1;

        mode_jangka_simpanan(row.kd_jns_simpanan);
        set_form('fm_modal', row);

        $('#myModal').modal('show');
    } else {
        alert("Pilih data di tabel");
    }
}

function simpan() {
    validasi = $('#fm_modal').valid();

    if (validasi) {
        if ($('#kd_jns_simpanan').val() == '4000') {
            vminSimpan = parseFloat(hapus_koma($('#min_simpan').val()));
            vmaxSimpan = parseFloat(hapus_koma($('#max_simpan').val()));

            if (vminSimpan > vmaxSimpan) {
                alert('nilai nominal minimal simpanan harus lebih kecil');
                return false;
            }

        }

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

                    get_margin_simpanan();
                }
            }
        });
    }
}

function del() {
    row = $('#tabel_margin_simpanan').DataTable().row({
        selected: true
    }).data();

    if (row) {
        prompt = confirm("Anda Yakin Ingin Menghapus Data Ini?");

        if (prompt) {
            $.ajax({
                url: situs + "master/del_margin_simpanan",
                data: "id_rate_simpanan=" + row.id_rate_simpanan,
                dataType: "JSON",
                type: "POST",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_margin_simpanan();
                    }
                }
            });
        }
    } else {
        alert("Pilih data di tabel");
    }
}
</script>