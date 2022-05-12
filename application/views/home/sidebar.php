<!-- Left Sidebar Start -->
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>" class="waves-effect"><i class="md md-home"></i><span> Dashboard </span></a>
                </li>
                <?php if($this->session->userdata("username") != "") { ?>
                <?php if($this->session->userdata("id_grup") == "1") { ?>
                <li class="has_sub">
                    <a href="javascript:void(0)" class="waves-effect"><i class="md md-settings"></i><span>Setting</span><span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul>
                        <li>
                            <a href="<?php echo site_url('setting/index/grup-user'); ?>"><span>Grup & User</span></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('setting/index/password-otorisasi'); ?>"><span>Password Otorisasi</span></a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <li class="has_sub">
                    <a href="javascript:void(0)" class="waves-effect"><i class="fa fa-table"></i><span>Master</span><span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul>
                        <li>
                            <a href="<?php echo site_url('master/index/margin-pinjaman'); ?>"><span>Margin Pinjaman</span></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('master/index/potongan-bonus-pg'); ?>"><span>Potongan Hak Diluar Gaji</span></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('master/index/transaksi-simpanan'); ?>"><span>Transaksi Simpanan</span></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('master/index/margin-simpanan'); ?>"><span>Margin Simpanan</span></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('master/index/potga-ss1'); ?>"><span>Potongan Gaji SS1</span></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('anggota/index/nasabah'); ?>"><span>Nasabah</span></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('master/index/akun'); ?>"><span>Akun</span></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('master/index/kasbank'); ?>"><span>Kasbank</span></a>
                        </li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0)" class="waves-effect"><i class="md md-assignment"></i><span>Pinjaman</span><span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul>
                        <li class="has_sub">
                            <a href="javascript:void(0)"><span>Pengajuan</span><span class="pull-right"><i class="md md-add"></i></a>
                            <ul>
                                <li>
                                    <a href="<?php echo site_url('pinjaman/index/reguler'); ?>"><span>Pinjaman Reguler</span></a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('pinjaman/index/kkb'); ?>"><span>Kredit Khusus Beragunan</span></a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('pinjaman/index/kpr'); ?>"><span>Kredit Pemilikan Rumah</span></a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('pinjaman/index/pht'); ?>"><span>Pinjaman Hari Tua</span></a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?php echo site_url('pinjaman/index/realisasi'); ?>"><span>Realisasi Pinjaman</span></a>
                        </li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0)" class="waves-effect"><i class="fa fa-money"></i><span>Simpanan</span><span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul>
                        <li> <a href="<?php echo site_url('simpanan/index/transaksi-simpanan-sukarela1'); ?>"><span>Transaksi Simpanan Sukarela 1</span></a> </li>
                        <li> <a href="<?php echo site_url('simpanan/index/cetak-buku-simpanan-sukarela1'); ?>"><span>Cetak Buku Simpanan Sukarela 1</span></a> </li>
                        <li> <a href="<?php echo site_url('simpanan/index/transaksi-simpanan-sukarela2'); ?>"><span>Transaksi Simpanan Sukarela 2</span></a> </li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0)" class="waves-effect"><i class="md md-book"></i><span>Laporan</span><span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul>
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><span>Pinjaman</span> <span class="pull-right"><i class="fa fa-money"></i></span></a>
                            <ul style="">
                                <li><a href="<?php echo site_url('laporan/pengajuan_realisasi_pinjaman'); ?>"><span>Pengajuan & Realisasi Pinjaman</span></a></li>
                                <li><a href="<?php echo site_url('laporan/realisasi_pinjaman'); ?>"><span>Realisasi Pinjaman</span></a></li>
                            </ul>
                        </li>
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><span>Simpanan</span> <span class="pull-right"><i class="fa fa-money"></i></span></a>
                            <ul style="">
                                <li><a href="<?php echo site_url('laporan/transaksi_ss1_per_hari'); ?>"><span>Transaksi SS1 Per Hari</span></a></li>
                                <li><a href="<?php echo site_url('laporan/saldo_akhir_ss1'); ?>"><span>Saldo Akhir SS1</span></a></li>
                                <li><a href="<?php echo site_url('laporan/pajak_ss1_ss2'); ?>"><span>Pajak SS1 dan SS2</span></a></li>
                                <li><a href="<?php echo site_url('laporan/transaksi_ss2_baru'); ?>"><span>Transaksi SS2 Baru</span></a></li>
                                <li><a href="<?php echo site_url('laporan/transaksi_ss2_diperpanjang'); ?>"><span>Transaksi SS2 Diperpanjang</span></a></li>
                                <li><a href="<?php echo site_url('laporan/transaksi_ss2_ditarik'); ?>"><span>Transaksi SS2 Ditarik</span></a></li>
                                <li><a href="<?php echo site_url('laporan/transaksi_ss2_by_nak'); ?>"><span>Transaksi SS2 urut NAK</span></a></li>
                                <li><a href="<?php echo site_url('laporan/transaksi_ss2_jatuh_tempo'); ?>"><span>Transaksi SS2 Jatuh Tempo</span></a></li>
								<li><a href="<?php echo site_url('laporan/transaksi_ss2_rekap'); ?>"><span>Rekap SS2 Bulanan</span></a></li>
                            </ul>
                        </li>
                        <li><a href="<?php echo site_url('setting/index/ttd-laporan'); ?>"><span>Setting Tanda Tangan</span></a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0)" class="waves-effect"><i class="fa fa-dollar"></i><span>General Ledger</span><span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul>
                        <li> <a href="<?php echo site_url('ledger/index/entri-transaksi-ledger'); ?>"><span>Entri Transaksi</span></a> </li>
                        <li> <a href="<?php echo site_url('laporan/transaksi_ledger'); ?>"><span>Lap. Transaksi General Ledger</span></a> </li>
                        <li> <a href="<?php echo site_url('laporan/history_transaksi_ledger'); ?>"><span>Lap. History General Ledger</span></a> </li>
                        <li> <a href="<?php echo site_url('laporan/mutasi_general_ledger'); ?>"><span>Lap. Mutasi General Ledger</span></a> </li>
                    </ul>
                </li>
                <?php if($this->session->userdata("id_grup") != "1") { ?>
                <li class="has_sub">
                    <a href="javascript:void(0)" class="waves-effect"><i class="fa fa-refresh"></i><span>Proses</span><span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul>
                        <li> <a href="<?php echo site_url('simpanan/index/proses-potga-ss1'); ?>"><span>Potga SS1</span></a> </li>
                        <li> <a href="<?php echo site_url('simpanan/index/proses-pajak-ss1'); ?>"><span>Pajak Margin SS1</span></a> </li>
						<li> <a href="<?php echo site_url('simpanan/index/proses-margin-simpanan'); ?>"><span>Margin Simpanan</span></a> </li>
						<li> <a href="<?php echo site_url('saldo/index/proses-saldo-simpanan'); ?>"><span>Sinkron Saldo Simpanan</span></a> </li>
						<li> <a href="<?php echo site_url('bridging/index/proses-bridging'); ?>"><span>Sinkron Bridging</span></a> </li>
                    </ul>
                </li>
                <?php } ?>
                <?php if($this->session->userdata("id_grup") == "1") { ?>
                <li class="has_sub">
                    <a href="javascript:void(0)" class="waves-effect"><i class="fa fa-refresh"></i><span>Proses</span><span class="pull-right"><i class="md md-add"></i></span></a>
                    <ul>
                        <li> <a href="<?php echo site_url('simpanan/index/proses-potga-ss1'); ?>"><span>Potga SS1</span></a> </li>
                        <li> <a href="<?php echo site_url('simpanan/index/proses-pajak-ss1'); ?>"><span>Pajak Margin SS1 & SS2</span></a> </li>
                        <li> <a href="<?php echo site_url('simpanan/index/proses-margin-simpanan'); ?>"><span>Margin Simpanan</span></a> </li>
						<li> <a href="<?php echo site_url('saldo/index/proses-saldo-simpanan'); ?>"><span>Sinkron Saldo Simpanan</span></a> </li>
                        <li> <a href="<?php echo site_url('simpanan/index/proses-saldo-tahunan-ss1'); ?>"><span>Saldo Awal Tahun Simpanan Sukarela 1</span></a> </li>
						<li> <a href="<?php echo site_url('bridging/index/proses-bridging'); ?>"><span>Sinkron Bridging</span></a> </li>
                    </ul>
                </li>
                <?php } ?>
                <?php } ?>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End