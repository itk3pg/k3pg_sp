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
        <table id="tabel_jenis_simpanan" class="table table-bordered table-condensed table-hover table-striped nowrap" width="100%">
            <thead>
                <tr>
                    <th width="50">No.</th>
                    <th width="150">Kode Jenis Simpanan</th>
                    <th>Jenis Simpanan</th>
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
                <h4 class="modal-title" id="myModalLabel">Jenis Simpanan</h4>
            </div>
            <div class="modal-body">
                <form id="fm_modal" onsubmit="return false">
                    <div class="form-group">
                        <label>Kode Jenis Simpanan</label>
                        <input type="text" id="kd_jns_simpanan" name="kd_jns_simpanan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Jenis Simpanan</label>
                        <input type="text" id="nm_jns_simpanan" name="nm_jns_simpanan" class="form-control" required>
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

function get_jenis_simpanan() {
    url_tabel = situs + "master/get_jenis_simpanan";
    tabel_id = "tabel_jenis_simpanan";

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
                // id_jns_simpanan, kd_jns_simpanan, nm_jns_simpanan
            }, {
                data: "kd_jns_simpanan"
            }, {
                data: "nm_jns_simpanan"
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
    get_jenis_simpanan();
}, 700);

function add() {
    dataUrl = situs + 'master/add_jenis_simpanan';
    edit_mode = 0;

    clear_form('fm_modal');
    $('#myModal').modal('show');
    $('#fm_modal #kd_jns_simpanan').focus().removeAttr("readonly");
    // $('#myModal').on('shown.bs.modal', function() {
    // });
}

function edit() {
    row = $('#tabel_jenis_simpanan').DataTable().row({
        selected: true
    }).data();

    if (row) {
        dataUrl = situs + 'master/edit_jenis_simpanan/' + row.id_jns_simpanan;
        edit_mode = 1;

        set_form('fm_modal', row);

        $('#myModal').modal('show');
        $('#fm_modal #kd_jns_simpanan').focus().attr("readonly", true);
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
                pesan(res.msg, 1);

                if (res.status) {
                    if (edit_mode) {
                        $('#myModal').modal('hide');
                    } else {
                        clear_form("fm_modal");
                    }

                    get_jenis_simpanan();
                }
            }
        });
    }
}

function del() {
    row = $('#tabel_jenis_simpanan').DataTable().row({
        selected: true
    }).data();

    if (row) {
        prompt = confirm("Anda Yakin Ingin Menghapus Data Ini?");

        if (prompt) {
            $.ajax({
                url: situs + "master/del_jenis_simpanan",
                data: "id_jns_simpanan=" + row.id_jns_simpanan,
                dataType: "JSON",
                type: "POST",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_jenis_simpanan();
                    }
                }
            });
        }
    } else {
        alert("Pilih data di tabel");
    }
}
</script>