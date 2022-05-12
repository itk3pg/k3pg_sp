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
        <table id="tabel_jenis_pinjaman" class="table table-bordered table-condensed table-hover table-striped nowrap" width="100%">
            <thead>
                <tr>
                    <th width="50">No.</th>
                    <th width="150">Kode Jenis Pinjaman</th>
                    <th>Jenis Pinjaman</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Jenis Pinjaman</h4>
            </div>
            <div class="modal-body">
                <form id="fm_modal" onsubmit="return false">
                    <div class="form-group">
                        <label>Kode Jenis Pinjaman</label>
                        <input type="text" id="kd_jns_pinjaman" name="kd_jns_pinjaman" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jenis Pinjaman</label>
                        <input type="text" id="nm_jns_pinjaman" name="nm_jns_pinjaman" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpan()">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
edit_mode = 0;

function get_jenis_pinjaman() {
    url_tabel = situs + "master/get_jenis_pinjaman";
    tabel_id = "tabel_jenis_pinjaman";

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
                // id_jns_pinjaman, kd_jns_pinjaman, nm_jns_pinjaman
            }, {
                data: "kd_jns_pinjaman"
            }, {
                data: "nm_jns_pinjaman"
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
    get_jenis_pinjaman();
}, 700);

function add() {
    dataUrl = situs + 'master/add_jenis_pinjaman';
    edit_mode = 0;

    clear_form('fm_modal');
    $('#myModal').modal('show');
    $('#fm_modal #kd_jns_pinjaman').focus().removeAttr("readonly");
    // $('#myModal').on('shown.bs.modal', function() {
    // });
}

function edit() {
    row = $('#tabel_jenis_pinjaman').DataTable().row({
        selected: true
    }).data();

    if (row) {
        dataUrl = situs + 'master/edit_jenis_pinjaman/' + row.id_jns_pinjaman;
        edit_mode = 1;

        set_form('fm_modal', row);
        $('#myModal').modal('show');
        $('#fm_modal #kd_jns_pinjaman').focus().attr("readonly", true);
        // $('#myModal').on('shown.bs.modal', function() {
        // });
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
                no_proses();
                pesan(res.msg, 1);

                if (res.status) {
                    if (edit_mode) {
                        $('#myModal').modal('hide');
                    } else {
                        clear_form("fm_modal");
                    }

                    get_jenis_pinjaman();
                }
            }
        });
    }
}

function del() {
    row = $('#tabel_jenis_pinjaman').DataTable().row({
        selected: true
    }).data();

    if (row) {
        prompt = confirm("Anda Yakin Ingin Menghapus Data Ini?");

        if (prompt) {
            $.ajax({
                url: situs + "master/del_jenis_pinjaman",
                data: "id_jns_pinjaman=" + row.id_jns_pinjaman,
                dataType: "JSON",
                type: "POST",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    no_proses();
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_jenis_pinjaman();
                    }
                }
            });
        }
    } else {
        alert("Pilih data di tabel");
    }
}
</script>