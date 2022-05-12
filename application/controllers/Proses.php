<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Proses extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        set_time_limit(0);
    }

    public function proses_saldo_simp_tahunan()
    {
        $this->load->model("simpanan_model");

        if (date('m-d') == "01-01") {
            $this->simpanan_model->update_saldo_simpanan_tahunan(date('Y'));

            $this->simpanan_model->updateSaldoSimpAwalTahun(date("Y"));
        }
    }

    public function proses_saldo_simp_bulanan($tahun = "", $bulan = "")
    {
        $this->load->model("simpanan_model");

        if ($tahun == "") {
            $tahun = date('Y');
        }
        if ($bulan == "") {
            $bulan = date('m');
        }

        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;

        $this->simpanan_model->update_saldo_simpanan_per_bulan($data);
    }

    public function proses_potga_ss1()
    {
        $this->load->model("simpanan_model");

        if (date('d') == "01") {
            $data['tahun'] = date('Y');
            $data['bulan'] = date('m');

            $this->simpanan_model->proses_insert_potga_ss1($data);
        }
    }

    public function proses_margin_ss2()
    {
        $this->load->model("simpanan_model");

        $data['tahun'] = date('Y');
        $data['bulan'] = date('m');

        $this->simpanan_model->proses_margin("SS2", $data);
    }

    public function proses_margin_ss1()
    {
        $this->load->model("simpanan_model");

        if (date("Y-m-d") == date("Y-m-t")) {
            $data['tahun'] = date('Y');
            $data['bulan'] = date('m');

            $this->simpanan_model->proses_pajak_ss1($data);
            $this->simpanan_model->proses_margin("SS1", $data);
        }
    }

    public function get_no_simpan_ss2($tgl_simpan, $kode_bukti = "SB")
    {
        $strtime = strtotime($tgl_simpan);
        $tahun   = date("Y", $strtime);
        $bulan   = date("m", $strtime);

        $nomor_baru = $kode_bukti . $bulan . $tahun;

        $nomor = $this->db->select("ifnull(max(substr(no_simpan, -5)), 0) + 1 nomor")->like("no_simpan", $nomor_baru, "after")
            ->get("t_simpanan_sukarela2")->row(0)->nomor;

        $nomor_baru .= str_pad($nomor, "5", "0", STR_PAD_LEFT);

        if ($this->db->set("no_simpan", $nomor_baru)->insert("t_simpanan_sukarela2")) {
            return $nomor_baru;
        } else {
            return $this->get_no_simpan_ss2($tgl_simpan);
        }
    }

    public function insert_ss2()
    {
        set_time_limit(0);

        $data_ss2 = $this->db->get("xss2");

        foreach ($data_ss2->result_array() as $key => $value) {
            $no_simpan = $this->get_no_simpan_ss2($value['TGL_VL']);

            $str_tgl = strtotime($value['TGL_VL']);
            $xhari   = date("d", $str_tgl);
            $xbulan  = date("m", $str_tgl);
            $xtahun  = date("Y", $str_tgl);

            $tgl_jt = date("Y-m-d", mktime(0, 0, 0, $xbulan + $value['JK_WAKTU'], $xhari, $xtahun));

            $margin = $value['SUKUBUNGA'];

            $jml_margin_setahun = hapus_koma($value['NOMINAL']) * ($margin / 100);

            $jml_margin_bln = round($jml_margin_setahun / 12);
            $jml_margin     = $jml_margin_bln * $value['JK_WAKTU'];

            $set_data = array(
                "no_simpan"       => $no_simpan,
                "tgl_simpan"      => $value['TGL_VL'],
                "no_ss2"          => $value['NO_REK'],
                "no_ang"          => strtoupper($value['NAK']),
                "no_peg"          => $value['NIK'],
                "nm_ang"          => $this->db->escape_str($value['NAMA']),
                // "kd_prsh"         => $value['kd_prsh'],
                // "nm_prsh"         => $value['nm_prsh'],
                // "kd_dep"          => $value['kd_dep'],
                "nm_dep"          => $value['DEPT'],
                // "kd_bagian"       => $value['kd_bagian'],
                "nm_bagian"       => $value['BAGIAN'],
                "kd_jns_simpanan" => "4000",
                "nm_jns_simpanan" => "SS2",
                "jml_simpanan"    => hapus_koma($value['NOMINAL']),
                "tempo_bln"       => $value['JK_WAKTU'],
                "tgl_jt"          => $tgl_jt,
                "margin"          => $margin,
                "jml_margin"      => $jml_margin,
                "jml_margin_bln"  => $jml_margin_bln,
                "ket"             => $value['KET'],
                "user_input"      => "IMPORT",
                "tgl_insert"      => date("Y-m-d H:i:s"),
            );

            $insert = $this->db->set($set_data)->where("no_simpan", $no_simpan)->update("t_simpanan_sukarela2");

            for ($i = 1; $i <= $value['JK_WAKTU']; $i++) {
                $xtgl_jt_det = mktime(0, 0, 0, $xbulan + 1, 1, $xtahun);
                $xtahun      = date("Y", $xtgl_jt_det);
                $xbulan      = date("m", $xtgl_jt_det);
                // $xhari       = date("d", $xtgl_jt_det);
                $tgl_jt_det = $xtahun . "-" . $xbulan . "-" . $xhari;

                if (!checkdate($xbulan, $xhari, $xtahun)) {
                    $tgl_jt_det = date("Y-m-t", mktime(0, 0, 0, $xbulan, 1, $xtahun));
                    // $xhari      = date("t", mktime(0, 0, 0, $xbulan, 1, $xtahun));
                }

                $set_data1 = array(
                    "no_simpan_det"  => $no_simpan . str_pad($i, 2, "0", STR_PAD_LEFT),
                    "no_simpan"      => $no_simpan,
                    "tgl_jt"         => $tgl_jt_det,
                    "blth"           => ($xtahun . "-" . $xbulan),
                    "tahun"          => $xtahun,
                    "bulan"          => $xbulan,
                    "hari"           => $xhari,
                    "margin_ke"      => $i,
                    "tempo_bln"      => $value['JK_WAKTU'],
                    "jml_margin_bln" => $jml_margin_bln,
                );

                $this->db->set($set_data1)->insert("t_simpanan_sukarela2_det");
            }
        }
    }

    public function insert_ss2_new()
    {
        set_time_limit(0);

        // $data_ss2 = $this->db->where_in("no_simpan", array('SB01201800010', 'SB01201800023', 'SB01201800056', 'SB01201800082', 'SB01201800083', 'SB01201800084', 'SB01201800085', 'SB01201800086', 'SB01201800087', 'SB01201800088', 'SB01201800089', 'SB03201800005', 'SB03201800032', 'SB03201800053', 'SB03201800054', 'SB03201800055', 'SB04201800034', 'SB04201800035', 'SB04201800053', 'SB05201800100', 'SB05201800101', 'SB05201800148', 'SB05201800149', 'SB05201800225', 'SB05201800227', 'SB05201800228', 'SB06201800088', 'SB06201800089', 'SB07201800084', 'SB08201800518', 'SB08201800666', 'SB09201800005', 'SB09201800121', 'SB09201800193', 'SB09201800300', 'SB10201700069', 'SB11201700010', 'SB11201700073', 'SB11201700074', 'SB11201700075', 'SB11201700076', 'SB12201700035', 'SB12201700082'))
        //     ->get("t_simpanan_sukarela2");

        foreach ($data_ss2->result_array() as $key => $value) {
            // $no_simpan = $this->get_no_simpan_ss2($value['TGL_VL']);

            $str_tgl = strtotime($value['tgl_simpan']);
            $xhari   = date("d", $str_tgl);
            $xbulan  = date("m", $str_tgl);
            $xtahun  = date("Y", $str_tgl);

            // $tgl_jt = date("Y-m-d", mktime(0, 0, 0, $xbulan + $value['JK_WAKTU'], $xhari, $xtahun));

            // $margin = $value['SUKUBUNGA'];

            // $jml_margin_setahun = hapus_koma($value['NOMINAL']) * ($margin / 100);

            // $jml_margin_bln = round($jml_margin_setahun / 12);
            // $jml_margin     = $jml_margin_bln * $value['JK_WAKTU'];

            // $set_data = array(
            //     "no_simpan"       => $no_simpan,
            //     "tgl_simpan"      => $value['TGL_VL'],
            //     "no_ss2"          => $value['NO_REK'],
            //     "no_ang"          => strtoupper($value['NAK']),
            //     "no_peg"          => $value['NIK'],
            //     "nm_ang"          => $this->db->escape_str($value['NAMA']),
            //     // "kd_prsh"         => $value['kd_prsh'],
            //     // "nm_prsh"         => $value['nm_prsh'],
            //     // "kd_dep"          => $value['kd_dep'],
            //     "nm_dep"          => $value['DEPT'],
            //     // "kd_bagian"       => $value['kd_bagian'],
            //     "nm_bagian"       => $value['BAGIAN'],
            //     "kd_jns_simpanan" => "4000",
            //     "nm_jns_simpanan" => "SS2",
            //     "jml_simpanan"    => hapus_koma($value['NOMINAL']),
            //     "tempo_bln"       => $value['JK_WAKTU'],
            //     "tgl_jt"          => $tgl_jt,
            //     "margin"          => $margin,
            //     "jml_margin"      => $jml_margin,
            //     "jml_margin_bln"  => $jml_margin_bln,
            //     "ket"             => $value['KET'],
            //     "user_input"      => "IMPORT",
            //     "tgl_insert"      => date("Y-m-d H:i:s"),
            // );

            // $insert = $this->db->set($set_data)->where("no_simpan", $no_simpan)->update("t_simpanan_sukarela2");

            for ($i = 1; $i <= $value['tempo_bln']; $i++) {
                $xtgl_jt_det = mktime(0, 0, 0, $xbulan + 1, 1, $xtahun);
                $xtahun      = date("Y", $xtgl_jt_det);
                $xbulan      = date("m", $xtgl_jt_det);
                // $xhari       = date("d", $xtgl_jt_det);
                $tgl_jt_det = $xtahun . "-" . $xbulan . "-" . $xhari;

                if (!checkdate($xbulan, $xhari, $xtahun)) {
                    $tgl_jt_det = date("Y-m-t", mktime(0, 0, 0, $xbulan, 1, $xtahun));
                    // $xhari      = date("t", mktime(0, 0, 0, $xbulan, 1, $xtahun));
                }

                $set_data1 = array(
                    "no_simpan_det"  => $value['no_simpan'] . str_pad($i, 2, "0", STR_PAD_LEFT),
                    "no_simpan"      => $value['no_simpan'],
                    "tgl_jt"         => $tgl_jt_det,
                    "blth"           => ($xtahun . "-" . $xbulan),
                    "tahun"          => $xtahun,
                    "bulan"          => $xbulan,
                    "hari"           => $xhari,
                    "margin_ke"      => $i,
                    "tempo_bln"      => $value['tempo_bln'],
                    "jml_margin_bln" => $value['jml_margin_bln'],
                );

                $this->db->set($set_data1)->insert("t_simpanan_sukarela2_det");
            }
        }
    }

    public function proses_ss2_baru_agustus2018x()
    {
        // $data_det_ss2 = $this->db->where("is_debet", "1")
        //     ->where("margin_ke = tempo_bln")->where("tgl_jt between '2018-08-01' and '2018-08-10'")
        //     ->order_by("tgl_jt, no_simpan")
        //     ->get("t_simpanan_sukarela2_det");

        foreach ($data_det_ss2->result_array() as $key => $value) {
            $data_header_ss2 = $this->db->where("no_simpan", $value['no_simpan'])->get("t_simpanan_sukarela2")->row_array(0);

            $no_simpan_ss2_baru = $this->get_no_simpan_ss2($value['tgl_jt']);

            $set_data2['is_debet']        = "1";
            $set_data2['tgl_debet']       = $value['tgl_jt'];
            $set_data2['is_diperpanjang'] = "1";
            $set_data2['no_bukti_baru']   = $no_simpan_ss2_baru;

            $strtime = strtotime($value['tgl_jt']);

            $xhari  = date("d", $strtime);
            $xbulan = date("m", $strtime);
            $xtahun = date("Y", $strtime);

            $tgl_jt = date("Y-m-d", mktime(0, 0, 0, $xbulan + $data_header_ss2['tempo_bln'], $xhari, $xtahun));

            $margin = $data_header_ss2['margin'];

            $jml_margin_setahun = hapus_koma($data_header_ss2['jml_simpanan']) * ($margin / 100);

            $jml_margin_bln = round($jml_margin_setahun / 12);
            $jml_margin     = $jml_margin_bln * $data_header_ss2['tempo_bln'];

            $set_data_ss2_diperpanjang = array(
                // "no_simpan"       => $no_simpan,
                "tgl_simpan"      => $value['tgl_jt'],
                "no_ss2"          => $data_header_ss2['no_ss2'],
                "no_ang"          => strtoupper($data_header_ss2['no_ang']),
                "no_peg"          => $data_header_ss2['no_peg'],
                "nm_ang"          => $this->db->escape_str($data_header_ss2['nm_ang']),
                "kd_prsh"         => $data_header_ss2['kd_prsh'],
                "nm_prsh"         => $data_header_ss2['nm_prsh'],
                "kd_dep"          => $data_header_ss2['kd_dep'],
                "nm_dep"          => $data_header_ss2['nm_dep'],
                "kd_bagian"       => $data_header_ss2['kd_bagian'],
                "nm_bagian"       => $data_header_ss2['nm_bagian'],
                "kd_jns_simpanan" => $data_header_ss2['kd_jns_simpanan'],
                "nm_jns_simpanan" => $data_header_ss2['nm_jns_simpanan'],
                "jml_simpanan"    => hapus_koma($data_header_ss2['jml_simpanan']),
                "tempo_bln"       => $data_header_ss2['tempo_bln'],
                "tgl_jt"          => $tgl_jt,
                "margin"          => $margin,
                "jml_margin"      => $jml_margin,
                "jml_margin_bln"  => $jml_margin_bln,
                "user_input"      => "PROSES",
                "tgl_insert"      => date("Y-m-d H:i:s"),
            );

            $insert = $this->db->set($set_data_ss2_diperpanjang)->where("no_simpan", $no_simpan_ss2_baru)->update("t_simpanan_sukarela2");

            for ($i = 1; $i <= $data_header_ss2['tempo_bln']; $i++) {
                $xtgl_jt_det = mktime(0, 0, 0, $xbulan + 1, $xhari, $xtahun);
                $xtahun      = date("Y", $xtgl_jt_det);
                $xbulan      = date("m", $xtgl_jt_det);
                $xhari       = date("d", $xtgl_jt_det);
                $tgl_jt_det  = $xtahun . "-" . $xbulan . "-" . $xhari;

                $set_data_ss2_diperpanjang_det = array(
                    "no_simpan_det"  => $no_simpan_ss2_baru . str_pad($i, 2, "0", STR_PAD_LEFT),
                    "no_simpan"      => $no_simpan_ss2_baru,
                    "tgl_jt"         => $tgl_jt_det,
                    "blth"           => ($xtahun . "-" . $xbulan),
                    "tahun"          => $xtahun,
                    "bulan"          => $xbulan,
                    "hari"           => $xhari,
                    "margin_ke"      => $i,
                    "tempo_bln"      => $data_header_ss2['tempo_bln'],
                    "jml_margin_bln" => $jml_margin_bln,
                );

                $this->db->set($set_data_ss2_diperpanjang_det)->insert("t_simpanan_sukarela2_det");
            }

            $this->db->set($set_data2)->where("no_simpan", $value['no_simpan'])->update("t_simpanan_sukarela2");
        }

        echo "selesai";
    }

    public function xxxxproses_det_pinjaman_reg()
    {
        $data_pinjaman = $this->db->like("no_pinjam", "XPP", "after")
        // ->limit("1")
            ->get("t_pinjaman_ang");

        $data_total = $data_pinjaman->num_rows();
        $data_now   = 1;

        foreach ($data_pinjaman->result_array() as $key => $value) {
            $xtgl           = strtotime($value['tgl_pinjam']);
            $tahun          = date("Y", $xtgl);
            $bulan          = date("m", $xtgl);
            $hari_realisasi = date("d", $xtgl);

            $tgl_angs = date('Y-m-t', mktime(0, 0, 0, $bulan + 1, 1, $tahun));
            $tgl_awal = $value['tgl_pinjam'];

            // $data_margin = $this->master_model->get_margin_pinjaman_berlaku("1", $data['tempo_bln'], $data['tgl_pinjam']);

            $margin = $value['margin'];

            $pokok_awal = hapus_koma($value['jml_diterima']) + hapus_koma($value['jml_biaya_admin']);

            // $data_angsuran = array();

            for ($i = 0; $i < $value['tempo_bln']; $i++) {
                $blth_angsuran = substr($tgl_angs, 0, 7);
                $tahun         = date("Y", strtotime($tgl_angs));
                $bulan         = date("m", strtotime($tgl_angs));

                $pokok_per_bulan = (hapus_koma($value['jml_diterima']) + hapus_koma($value['jml_biaya_admin'])) / $value['tempo_bln'];
                // $margin_per_bulan   = hapus_koma($value['jml_pinjam']) * (($margin / 100) / 12);
                $margin_per_bulan   = $value['jml_margin'] / $value['tempo_bln'];
                $angsuran_per_bulan = $value['angsuran'];

                $pokok_akhir = $pokok_awal - $pokok_per_bulan;

                $item = array(
                    "no_pinjam_det"  => $value['no_pinjam'] . str_pad($i, 4, "0", STR_PAD_LEFT),
                    "no_pinjam"      => $value['no_pinjam'],
                    "tgl_pinjam"     => $value['tgl_pinjam'],
                    "blth_angsuran"  => $blth_angsuran,
                    "bulan_angsuran" => $bulan,
                    "tahun_angsuran" => $tahun,
                    "angs_ke"        => ($i + 1),
                    "tempo_bln"      => $value['tempo_bln'],
                    "pokok_awal"     => $pokok_awal,
                    "pokok"          => $pokok_per_bulan,
                    "bunga"          => $margin_per_bulan,
                    "angsuran"       => $angsuran_per_bulan,
                    "pokok_akhir"    => $pokok_akhir,
                    // "nm_pot_bonus"   => "",
                );

                $this->db->set($item)->insert("t_pinjaman_ang_det");

                $tgl_angs = date("Y-m-t", mktime(0, 0, 0, $bulan + 1, 1, $tahun));

                $pokok_awal = $pokok_akhir;
            }

            baca($value['no_pinjam'] . " " . $data_now . " / " . $data_total);
            $data_now++;
        }
    }

    public function xxxxproses_det_pinjaman_pht()
    {
        $data_pinjaman = $this->db->like("no_pinjam", "XPHT", "after")
            ->get("t_pinjaman_ang");

        $data_total = $data_pinjaman->num_rows();
        $data_now   = 1;

        foreach ($data_pinjaman->result_array() as $key => $value) {
            $xtgl           = strtotime($value['tgl_pinjam']);
            $tahun          = date("Y", $xtgl);
            $bulan          = date("m", $xtgl);
            $hari_realisasi = date("d", $xtgl);

            $tgl_angs = date('Y-m-t', mktime(0, 0, 0, $bulan + 1, 1, $tahun));
            $tgl_awal = $value['tgl_pinjam'];

            // $data_margin = $this->master_model->get_margin_pinjaman_berlaku("1", $data['tempo_bln'], $data['tgl_pinjam']);

            // $margin = $value['margin'];

            $pokok_awal = hapus_koma($value['jml_pinjam']);

            // $data_angsuran = array();

            for ($i = 0; $i < $value['tempo_bln']; $i++) {
                $blth_angsuran = substr($tgl_angs, 0, 7);
                $tahun         = date("Y", strtotime($tgl_angs));
                $bulan         = date("m", strtotime($tgl_angs));

                // $pokok_per_bulan = (hapus_koma($value['jml_pinjam']) + hapus_koma($value['jml_biaya_admin'])) / $value['tempo_bln'];
                // $margin_per_bulan   = hapus_koma($value['jml_pinjam']) * (($margin / 100) / 12);
                $pokok_per_bulan    = (($i + 1) == $value['tempo_bln']) ? $value['jml_pinjam'] : 0;
                $margin_per_bulan   = $value['angsuran'];
                $angsuran_per_bulan = (($i + 1) == $value['tempo_bln']) ? ($value['jml_pinjam'] + $value['angsuran']) : $value['angsuran'];

                $pokok_akhir = $pokok_awal - $pokok_per_bulan;

                $item = array(
                    "no_pinjam_det"  => $value['no_pinjam'] . str_pad($i, 4, "0", STR_PAD_LEFT),
                    "no_pinjam"      => $value['no_pinjam'],
                    "tgl_pinjam"     => $value['tgl_pinjam'],
                    "blth_angsuran"  => $blth_angsuran,
                    "bulan_angsuran" => $bulan,
                    "tahun_angsuran" => $tahun,
                    "angs_ke"        => ($i + 1),
                    "tempo_bln"      => $value['tempo_bln'],
                    "pokok_awal"     => $pokok_awal,
                    "pokok"          => $pokok_per_bulan,
                    "bunga"          => $margin_per_bulan,
                    "angsuran"       => $angsuran_per_bulan,
                    "pokok_akhir"    => $pokok_akhir,
                    // "nm_pot_bonus"   => "",
                );

                $this->db->set($item)->insert("t_pinjaman_ang_det");

                // $data_angsuran[] = $item;

                $tgl_angs = date("Y-m-t", mktime(0, 0, 0, $bulan + 1, 1, $tahun));

                $pokok_awal = $pokok_akhir;
            }

            baca($value['no_pinjam'] . " " . $data_now . " / " . $data_total);
            $data_now++;
        }
    }

    public function xxxxxproses_det_bp()
    {
        $data_bp = $this->db->like("no_trans", "XPP", "after")
            ->get("t_bridging_plafon");

        $data_total = $data_bp->num_rows();
        $data_now   = 1;

        foreach ($data_bp->result_array() as $key => $value) {
            $xstrtotime = strtotime($value['tgl_trans']);

            $xhari  = date("d", $xstrtotime);
            $xbulan = date("m", $xstrtotime);
            $xtahun = date("Y", $xstrtotime);

            $margin = $value['margin'];

            $jml_margin = $value['jml_margin'];

            $jml_margin_bln = round($jml_margin / $value['tempo_bln']);

            $angsuran = $value['angsuran'];

            $pokok_per_bulan = (hapus_koma($value['jml_diterima']) + hapus_koma($value['jml_biaya_admin'])) / $value['tempo_bln'];

            for ($i = 1; $i <= $value['tempo_bln']; $i++) {
                $xtgl_jt_det = mktime(0, 0, 0, $xbulan + 1, $xhari, $xtahun);
                $xtahun      = date("Y", $xtgl_jt_det);
                $xbulan      = date("m", $xtgl_jt_det);
                $xhari       = date("d", $xtgl_jt_det);
                $tgl_jt_det  = $xtahun . "-" . $xbulan . "-" . $xhari;

                $set_data1 = array(
                    "no_trans_det"   => $value['no_trans'] . str_pad($i, 2, "0", STR_PAD_LEFT),
                    "no_trans"       => $value['no_trans'],
                    "tgl_trans"      => $value['tgl_trans'],
                    "kd_piutang"     => $value['kd_piutang'],
                    "blth_angsuran"  => ($xtahun . "-" . $xbulan),
                    "bulan_angsuran" => $xbulan,
                    "tahun_angsuran" => $xtahun,
                    "angs_ke"        => $i,
                    "tempo_bln"      => $value['tempo_bln'],
                    // "pokok_awal"     => "pokok_awal",
                    "pokok"          => $pokok_per_bulan,
                    "bunga"          => $jml_margin_bln,
                    "angsuran"       => $angsuran,
                    // "pokok_akhir"    => "pokok_akhir",
                    // "sts_lunas"=>"sts_lunas",
                    // "sts_potga"=>"sts_potga",
                    // "blth_bayar"=>"blth_bayar",
                    // "bukti_pelunasan"=>"bukti_pelunasan",
                    // "bukti_tagihan"=>"bukti_tagihan",
                    // "tgl_update"=>"tgl_update"
                );

                $this->db->set($set_data1)->insert("t_bridging_plafon_det");
            }

            baca($value['no_trans'] . " " . $data_now . " / " . $data_total);
            $data_now++;
        }
    }

}
