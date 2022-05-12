<div class="panel panel-default panel-color">
    <div class="panel-body">
        <div class="row">
            <form id="fm_data" onsubmit="return false">
                <div class="row" id="div_anggota">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>NAK</label>
                            <input type="text" name="no_ang" id="no_ang" class="form-control" data-rule-required="true" autocomplete="off" style="text-transform: uppercase;">
                            <!-- <select id="no_ang" name="no_ang" class="form-control" required=""></select> -->
                        </div>
                        <div class="form-group">
                            <label>No. Pegawai</label>
                            <input type="text" id="no_peg" name="no_peg" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Perusahaan</label>
                            <div class="row" style="margin: 0px;">
                                <div class="col-md-2" style="padding: 0px;">
                                    <input type="text" id="kd_prsh" name="kd_prsh" class="form-control" readonly>
                                </div>
                                <div class="col-md-10" style="padding: 0 0 0 5px;">
                                    <input type="text" id="nm_prsh" name="nm_prsh" class="form-control" readonly>
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
                            <label>Departemen</label>
                            <div class="row" style="margin: 0px;">
                                <div class="col-md-3" style="padding: 0px;">
                                    <input type="text" id="kd_dep" name="kd_dep" class="form-control" readonly>
                                </div>
                                <div class="col-md-9" style="padding: 0 0 0 5px;">
                                    <input type="text" id="nm_dep" name="nm_dep" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Bagian</label>
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
                    <div class="col-md-4">
                        <img src="<?php echo base_url('aset/gambar/no-image.png'); ?>" id="gambar_ktp" style="height: 250px" class="img-thumbnail" />
                        <input type="hidden" name="status_keluar" id="status_keluar">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Kode Transaksi Simpanan</label>
                            <div class="row" style="margin: 0px;">
                                <select id="kd_jns_transaksi" name="kd_jns_transaksi" class="form-control" required=""></select>
                                <input type="hidden" id="nm_jns_transaksi" name="nm_jns_transaksi">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Tanggal</label>
                                    <input type="text" name="tgl_simpan" id="tgl_simpan" class="form-control datepicker" required="" value="<?php echo date('d-m-Y'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>Kredit/Debet</label>
                                    <select class="form-control" id="kredit_debet" name="kredit_debet" required="">
                                        <option value="">[-PILIH-]</option>
                                        <option value="K">Kredit</option>
                                        <option value="D">Debet</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jumlah</label>
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="text" id="jumlah" name="jumlah" class="form-control number_format" data-rule-required="true" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <br>
                            <button type="button" class="btn btn-primary" onclick="simpan()">Simpan</button>
                            <button type="button" class="btn btn-default" onclick="batal()">Batal</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Saldo Akhir</label>
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="text" id="saldo_akhir" name="saldo_akhir" class="form-control input-lg number_format" readonly="" value="0" style="font-weight: bolder;">
                            </div>
                            <input type="hidden" name="mode_data" id="mode_data" value="belumcetak">
                            <input type="hidden" name="mode_cetak" id="mode_cetak" value="cetaklangsung">
                            <input type="hidden" name="tgl_awal" id="tgl_awal" value="">
                            <input type="hidden" name="tgl_akhir" id="tgl_akhir" value="">
                        </div>
                        <div>
                            <button type="button" class="btn btn-info btn-small" onclick="cetak_semua_data()">
                                <i class="fa fa-print"></i> Cetak Buku</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-heading">
        <div class="row">
            <div class="pull-left">
                <button class="btn btn-danger btn-small" onclick="del()">
                    <i class="fa fa-trash"></i> Hapus</button>
				<!--
				<button class="btn btn-success btn-small" onclick="bungass1ang()">
                    <i class="fa fa-file"></i> Proses Bunga SS1</button>-->
            </div>
        </div>
    </div>
    <div class="panel-body">
        <table id="tabel_sukarela1" class="table table-bordered table-condensed table-hover table-striped nowrap" width="100%">
            <thead>
                <tr>
                    <th width="50">No.</th>
                    <th>Bukti Simpanan</th>
                    <th>Tanggal</th>
                    <th>Transaksi</th>
                    <th>Kredit</th>
                    <th>Debet</th>
                    <th>Baris Cetak</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Setor/Tarik Simpanan</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpan()">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- modal bunga-->
<div class="modal fade" id="myModalbunga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Form Sinkron</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form id="formModal" onsubmit="return false">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label>Bulan</label><br>
								<select id="bulan" name="bulan" class="form-control">
									<?php echo $bulan; ?>
								</select>
							</div>
							<div class="form-group">
								<label>Tahun</label><br>
								 <input type="text" name="tahun" id="tahun" class="form-control" value="<?php echo date('Y'); ?>" maxlength="4" size="4" placeholder="Tahun">
							</div>
						</div>
						
					</div>
				</form>
			</div>

			<div class="modal-footer">
				
				<button type="button" class="btn btn-primary" onclick="prosesbungass1()" id="btnproses">Proses</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
			</div>

		</div>
	</div>
</div>
<!-- end modal -->
<script type="text/javascript">
edit_mode = 0;
mode_cetak = "off";

$(window).on("focus", function() {
    set_status_cetak();
});

function bungass1ang() {
	$('#myModalbunga').modal('show');
	$('#myModalbunga').on('shown.bs.modal', function () {
	});
}

function prosesbungass1(){
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	var no_ang = $('#no_ang').val();
	$.ajax({
		url: situs + 'simpanan/proses_bungass1_ang',
		data: "bulan="+bulan+"&tahun="+tahun+"&no_ang="+no_ang,
		type: "POST",
		beforeSend: function () {
			if ($('[name="no_ang"]').val() == '') {
				alert("No.anggota tidak boleh kosong");
				return false;
			}
			proses();
		},
		success: function (res) {
			if (res == 1) {
				no_proses();
				$('#myModalbunga').modal('hide');
				alert("Data Berhasil Disinkron");
				get_transaksi_simpanan();
			}
			else{
				no_proses();
				$('#myModalbunga').modal('hide');
				alert("Data Gagal Disinkron");
			}
		}
	});
}

function get_transaksi_simpanan() {
    $fm_data = $("#fm_data").serialize();
    url_tabel = situs + "simpanan/get_transaksi_simpanan?" + $fm_data;
    tabel_id = "tabel_sukarela1";

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
                data: "no_simpan"
            }, {
                data: "tgl_simpan"
            }, {
                data: "kd_jns_transaksi",
                defaultContent: "",
                render: function(data, type, row, meta) {
                    return "[" + data + "] " + row.nm_jns_transaksi;
                }
            }, {
                data: "kredit",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "debet",
                className: "text-right",
                render: function(data) {
                    return number_format(data, 2, '.', ',');
                }
            }, {
                data: "baris_cetak"
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

        $("#" + tabel_id).DataTable().off("draw.dt");
    }
}

var ev_get_anggota = 1;

$("#div_anggota #no_ang").focus().on("change", function() {
    if (ev_get_anggota == 0) {
        ev_get_anggota = 1;

        get_anggota();
        get_transaksi_simpanan();
    }
}).keydown(function(e) {
    if (e.which == 13) {
        if (ev_get_anggota == 0) {
            ev_get_anggota = 1;

            get_anggota();
            get_transaksi_simpanan();
        }
    } else {
        ev_get_anggota = 0;
    }
});

function get_anggota() {
    $no_ang = $("#fm_data #no_ang").val();

    if ($no_ang) {
        $.ajax({
            url: situs + 'anggota/select_nasabah_by_noang',
            data: "q=" + $no_ang,
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
                proses();
            },
            success: function(data) {
                if (typeof(data.results) != "undefined" && data.results.length > 0) {
                    no_proses();
                    data_nasabah = data.results;

                    $("#fm_data #nm_ang").val(data_nasabah[0].nm_ang);
                    $("#fm_data #no_peg").val(data_nasabah[0].no_peg);
                    $("#fm_data #kd_prsh").val(data_nasabah[0].kd_prsh);
                    $("#fm_data #nm_prsh").val(data_nasabah[0].nm_prsh);
                    $("#fm_data #kd_dep").val(data_nasabah[0].kd_dep);
                    $("#fm_data #nm_dep").val(data_nasabah[0].nm_dep);
                    $("#fm_data #kd_bagian").val(data_nasabah[0].kd_bagian);
                    $("#fm_data #nm_bagian").val(data_nasabah[0].nm_bagian);
                    $("#fm_data #status_keluar").val(data_nasabah[0].status_keluar);

                    cek_saldo_simpanan_sukarela1();
                    get_ktp();
                } else {
                    $("#fm_data #no_ang").val('');
                    pesan('Data tidak ditemukan');
                }
            }
        });
    }
}

// $("#fm_data #no_ang").select2({
//     ajax: {
//         url: situs + 'anggota/select_nasabah_by_noang/0',
//         dataType: 'json',
//         delay: 500
//     }
// }).on("select2:select", function(e) {
//     s2data = e.params.data;

//     $("#fm_data #nm_ang").val(s2data.nm_ang);
//     $("#fm_data #no_peg").val(s2data.no_peg);
//     $("#fm_data #kd_prsh").val(s2data.kd_prsh);
//     $("#fm_data #nm_prsh").val(s2data.nm_prsh);
//     $("#fm_data #kd_dep").val(s2data.kd_dep);
//     $("#fm_data #nm_dep").val(s2data.nm_dep);
//     $("#fm_data #kd_bagian").val(s2data.kd_bagian);
//     $("#fm_data #nm_bagian").val(s2data.nm_bagian);

//     get_transaksi_simpanan();
//     cek_saldo_simpanan_sukarela1();
//     get_ktp();
// });

$("#fm_data #kd_jns_transaksi").select2({
    ajax: {
        url: situs + 'master/select_jenis_transaksi_simpanan/3000',
        dataType: 'json',
        delay: 500
    }
}).on("select2:select", function(e) {
    var s2data = e.params.data;

    $("#fm_data #nm_jns_transaksi").val(s2data.nm_jns_transaksi);
    $("#fm_data #kredit_debet").val(s2data.kredit_debet);
    setTimeout(function() {
        $("#jumlah").focus();
    }, 300);
});

function get_ktp() {
    data_ajax = $("#fm_data").serialize();

    $.ajax({
        url: situs + "anggota/get_ktp",
        data: data_ajax,
        type: 'post',
        beforeSend: function() {
            // proses();
        },
        success: function(data) {
            $("#gambar_ktp").attr("src", data);

            // no_proses();
        }
    });
}

function cek_saldo_simpanan_sukarela1() {
    $data_form = $("#fm_data").serialize();

    $.ajax({
        url: situs + "simpanan/cek_saldo_simpanan_sukarela1",
        data: $data_form,
        type: 'post',
        success: function(data) {
            $("#saldo_akhir").val(data).trigger("change");
        }
    });
}

function simpan() {
    validasi = $('#fm_data').valid();

    if (validasi) {
        <?php if($this->session->userdata('username') != 'LAHDA') {?>
        if ($("#fm_data #status_keluar").val() == "1" && $("#fm_data #kredit_debet").val() == "K") {
            alert("Anggota/Nasabah sudah berstatus keluar");
            return false;
        }
        <?php } ?>

        konfirmasi = confirm("Anda yakin data sudah benar?");

        if (konfirmasi) {
            data_input = $('#fm_data').serialize();

            $.ajax({
                url: situs + 'simpanan/add_transaksi_simpanan_sukarela1',
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
                            $("#kd_jns_transaksi").val('').trigger("change");
                            $("#nm_jns_transaksi").val('');
                            $("#kredit_debet").val('');
                            $("#jumlah").val('');
                            $("#div_anggota input").val('');
                            setTimeout(function() {
                                $("#div_anggota #no_ang").focus();
                            }, 300);

                            cek_saldo_simpanan_sukarela1();
                        }

                        get_transaksi_simpanan();
                    }
                }
            });
        }
    }
}

function batal() {
    $("#kd_jns_transaksi").val('').trigger("change");
    $("#nm_jns_transaksi").val('');
    $("#kredit_debet").val('');
    $("#jumlah").val('');
}

function del() {
    if ($.fn.DataTable.isDataTable("#tabel_sukarela1")) {
        row = $('#tabel_sukarela1').DataTable().row({
            selected: true
        }).data();

        if (row) {
            prompt = confirm("Anda Yakin Ingin Menghapus Data Ini?");

            if (prompt) {
                $.ajax({
                    url: situs + "simpanan/del_transaksi_simpanan",
                    data: row,
                    dataType: "JSON",
                    type: "POST",
                    beforeSend: function() {
                        proses();
                    },
                    success: function(res) {
                        pesan(res.msg, 1);

                        if (res.status) {
                            get_transaksi_simpanan();
                            cek_saldo_simpanan_sukarela1();

                        }
                    }
                });
            }
        } else {
            alert("Pilih data di tabel");
        }
    }
}

function cetak_semua_data() {
    $no_ang = $("#fm_data #no_ang").val();

    if ($no_ang) {
        $data_ajax = JSON.stringify(get_form_array('fm_data'));

        window.open(situs + "cetak/cetak_buku_ss1?data=" + base64_encode($data_ajax));
        mode_cetak = "all";
    } else {
        alert("Pilih Anggota/Nasabah");
    }
}

function set_status_cetak() {
    if (mode_cetak != "off") {
        swal({
            title: 'Apakah Data Sudah Tercetak di Buku?',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, data sudah tercetak!',
            cancelButtonText: 'Tidak, data belum tercetak!',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false
        }).then((result) => {
            if (result.value) {
                dataSetCetak = $("#fm_data").serialize();

                $.ajax({
                    url: situs + "cetak/set_status_cetak_ss1",
                    data: dataSetCetak,
                    type: 'post',
                    success: function(data) {
                        // pesan("Data berhasil dicetak");
                    }
                });
            }
        });

        mode_cetak = "off";
    }
}
</script>