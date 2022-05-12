<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pinjaman_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_tempo_bln()
    {
        $tempo_bln = array();

        for ($i = 1; $i <= 20; $i++) {
            $bln             = $i * 12;
            $tempo_bln[$bln] = "(" . $i . " Tahun) " . $bln;
        }

        return $tempo_bln;
    }

    public function get_tempo_bln_reguler()
    {
        $tempo_bln = array();

        $data_tempo = $this->db->where("kd_jns_pinjaman", "1")->group_by("tempo_bln")->get("m_rate_pinjaman");

        foreach ($data_tempo->result_array() as $key => $value) {
            $tahun                          = $value['tempo_bln'] / 12;
            $tempo_bln[$value['tempo_bln']] = "(" . $tahun . " Tahun) " . $value['tempo_bln'];
        }

        return $tempo_bln;
    }

    public function get_tempo_bln_kkb()
    {
        $tempo_bln = array();

        $data_tempo = $this->db->where("kd_jns_pinjaman", "2")->group_by("tempo_bln")->get("m_rate_pinjaman");

        foreach ($data_tempo->result_array() as $key => $value) {
            $tahun                          = $value['tempo_bln'] / 12;
            $tempo_bln[$value['tempo_bln']] = "(" . $tahun . " Tahun) " . $value['tempo_bln'];
        }

        return $tempo_bln;
    }

    public function get_tempo_bln_kpr()
    {
        $tempo_bln = array();

        $data_tempo = $this->db->where("kd_jns_pinjaman", "4")->group_by("tempo_bln")->get("m_rate_pinjaman");

        foreach ($data_tempo->result_array() as $key => $value) {
            $tahun                          = $value['tempo_bln'] / 12;
            $tempo_bln[$value['tempo_bln']] = "(" . $tahun . " Tahun) " . $value['tempo_bln'];
        }

        return $tempo_bln;
    }

    public function get_pinjaman($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "", $kd_pinjaman = "")
    {
        $select = ($numrows) ? "count(*) numrows, 1 tgl_pinjam1" : "no_pinjam, tgl_pinjam tgl_pinjam1, date_format(tgl_pinjam, '%d-%m-%Y') tgl_pinjam, no_simulasi, tgl_simulasi, is_aprove, tgl_aprove, user_aprove, is_realisasi, tgl_realisasi, user_realisasi, unit_adm, no_ang, no_peg, nm_ang, nm_ibukdg, tgl_lhr, alamat_ang, no_hp, no_npwp, no_ktp, kd_prsh, nm_prsh, kd_dep, nm_dep, kd_bagian, nm_bagian, kd_pinjaman, nm_pinjaman, tgl_angs, tgl_jt, jml_pinjam, tempo_bln, jns_jangka, jenis_margin, margin, jml_margin, gaji, plafon, sisa_plafon, plafon_bonus, sisa_plafon_bonus, min_angsuran, jml_min_angsuran, max_angsuran, jml_max_angsuran, persen_angsuran, angsuran, saldo_angsuran, saldo_pinjaman, jml_biaya_admin, jml_provisi_bln, jml_simp_agunan, jml_pot_bunga, jml_potong, jml_diterima, jns_potong_admin, jns_potong_bunga, jns_bayar, no_rek_cek, kd_bank_dana, nm_bank_dana, kd_bank_ke, nm_bank_ke, kd_cb, nm_cb, sts_lunas, blth_lunas, tgl_update";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("no_pinjam", "no_ang", "no_peg", "nm_ang");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "tgl_pinjam1 desc, no_pinjam desc";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($kd_pinjaman) {
            $this->db->where("kd_pinjaman", $kd_pinjaman);
        }

        return $this->db->get("t_pinjaman_ang");
    }

    public function get_simulasi_pinjaman($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "", $kd_pinjaman = "", $is_aprove = "", $is_realisasi = "")
    {
        $select = ($numrows) ? "count(*) numrows, 1 tgl_pinjam1" : "no_pinjam, tgl_pinjam tgl_pinjam1, date_format(tgl_pinjam, '%d-%m-%Y') tgl_pinjam, is_aprove, tgl_aprove, user_aprove, is_realisasi, tgl_realisasi, user_realisasi, unit_adm, no_ang, no_peg, nm_ang, nm_ibukdg, tgl_lhr, alamat_ang, no_hp, no_npwp, no_ktp, kd_prsh, nm_prsh, kd_dep, nm_dep, kd_bagian, nm_bagian, kd_pinjaman, nm_pinjaman, tgl_angs, tgl_jt, jml_pinjam, jml_pinjam_realisasi, tempo_bln, jns_jangka, jenis_margin, margin, jml_margin, gaji, plafon, sisa_plafon, plafon_bonus, sisa_plafon_bonus, min_angsuran, jml_min_angsuran, max_angsuran, jml_max_angsuran, persen_angsuran, angsuran, saldo_angsuran, saldo_pinjaman, jml_biaya_admin, jml_provisi_bln, jml_simp_agunan, jml_pot_bunga, jml_potong, jml_diterima, jns_potong_admin, jns_potong_bunga, tgl_update";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("no_pinjam", "no_ang", "no_peg", "nm_ang");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "tgl_pinjam1 desc, no_pinjam desc";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($kd_pinjaman) {
            $this->db->where("kd_pinjaman", $kd_pinjaman);
        }

        if ($is_aprove != "") {
            $this->db->where("is_aprove", $is_aprove);
        }

        if ($is_realisasi != "") {
            $this->db->where("is_realisasi", $is_realisasi);
        }

        return $this->db->get("t_simulasi_pinjaman_ang");
    }

    public function get_simulasi_angsuran($no_pinjam)
    {
        $this->db->select("no_pinjam_det, no_pinjam, tgl_pinjam, hari, blth_angsuran, bulan_angsuran, tahun_angsuran, angs_ke, tempo_bln, pokok_awal, pokok, bunga, angsuran, pokok_akhir, sts_lunas, sts_potga, blth_bayar, bukti_pelunasan, bukti_tagihan, nm_pot_bonus, tgl_update")
            ->where("no_pinjam", $no_pinjam)
            ->order_by("angs_ke");

        return $this->db->get("t_simulasi_pinjaman_ang_det");
    }

    public function get_angsuran($no_pinjam)
    {
        $this->db->select("no_pinjam_det, no_pinjam, tgl_pinjam, hari, blth_angsuran, bulan_angsuran, tahun_angsuran, angs_ke, tempo_bln, pokok_awal, pokok, bunga, angsuran, pokok_akhir, sts_lunas, sts_potga, blth_bayar, bukti_pelunasan, bukti_tagihan, nm_pot_bonus")
            ->where("no_pinjam", $no_pinjam)
            ->order_by("angs_ke");

        return $this->db->get("t_pinjaman_ang_det");
    }

    public function get_total_angsuran_flat($data)
    {
        $data_angsuran_simulasi = $this->db->select("ifnull(sum(angsuran), 0) angsuran")
            ->where_in("kd_pinjaman", array("1", "4"))
            ->where("no_ang", $data['no_ang'])
            ->where("is_realisasi", "0")
            ->get("t_simulasi_pinjaman_ang");

        $jml_angsuran_simulasi = ($data_angsuran_simulasi->num_rows() > 0) ? $data_angsuran_simulasi->row(0)->angsuran : 0;

        $data_angsuran = $this->db->select("ifnull(sum(angsuran), 0) angsuran")
            ->where_in("kd_pinjaman", array("1", "4"))
            ->where("no_peg", $data['no_peg'])
            ->where("sts_lunas", "0")
            ->get("t_pinjaman_ang");

        $jml_angsuran = ($data_angsuran->num_rows() > 0) ? $data_angsuran->row(0)->angsuran : 0;

        return ($jml_angsuran_simulasi + $jml_angsuran);
    }

    public function get_total_angsuran_anuitas($data)
    {
        $data_angsuran_simulasi = $this->db->select("ifnull(sum(jml_max_angsuran), 0) angsuran")
            ->where_in("kd_pinjaman", array("2", "4"))
            ->where("no_ang", $data['no_ang'])
            ->where("is_realisasi", "0")
            ->get("t_simulasi_pinjaman_ang");

        $jml_angsuran_simulasi = ($data_angsuran_simulasi->num_rows() > 0) ? $data_angsuran_simulasi->row(0)->angsuran : 0;

        $data_angsuran = $this->db->select("ifnull(sum(jml_max_angsuran), 0) angsuran")
            ->where_in("kd_pinjaman", array("2", "4"))
            ->where("no_peg", $data['no_peg'])
            ->where("sts_lunas", "0")
            ->get("t_pinjaman_ang");

        $jml_angsuran = ($data_angsuran->num_rows() > 0) ? $data_angsuran->row(0)->angsuran : 0;

        return ($jml_angsuran_simulasi + $jml_angsuran);
    }

    public function get_total_angsuran($data)
    {
        $data_plafon_debet = $this->db->select("ifnull(sum(jml_debet), 0) jml_debet_plafon")->where("no_ang", $data['no_ang'])->get("t_plafon_debet");

        return $data_plafon_debet->row(0)->jml_debet_plafon;
    }

    public function get_no_pinjam_simulasi($tgl_pinjam)
    {
        $strtime = strtotime($tgl_pinjam);
        $tahun   = date("Y", $strtime);
        $bulan   = date("m", $strtime);

        $nomor_baru = "SM" . $bulan . $tahun;

        $nomor = $this->db->select("ifnull(max(substr(no_pinjam, -5)), 0) + 1 nomor")->like("no_pinjam", $nomor_baru, "after")
            ->get("t_simulasi_pinjaman_ang")->row(0)->nomor;

        $nomor_baru .= str_pad($nomor, "5", "0", STR_PAD_LEFT);

        if ($this->db->set("no_pinjam", $nomor_baru)->insert("t_simulasi_pinjaman_ang")) {
            return $nomor_baru;
        } else {
            return $this->get_no_pinjam_simulasi($tgl_pinjam);
        }
    }

    public function get_no_pinjam($tgl_pinjam)
    {
        $strtime = strtotime($tgl_pinjam);
        $tahun   = date("Y", $strtime);
        $bulan   = date("m", $strtime);

        $nomor_baru = "PJ" . $bulan . $tahun;

        $nomor = $this->db->select("ifnull(max(substr(no_pinjam, -5)), 0) + 1 nomor")->like("no_pinjam", $nomor_baru, "after")
            ->get("t_pinjaman_ang")->row(0)->nomor;

        $nomor_baru .= str_pad($nomor, "5", "0", STR_PAD_LEFT);

        if ($this->db->set("no_pinjam", $nomor_baru)->insert("t_pinjaman_ang")) {
            return $nomor_baru;
        } else {
            return $this->get_no_pinjam($tgl_pinjam);
        }
    }

    public function insert_simulasi_reguler($data, $no_pinjam_edit = "", $mode_edit = "")
    {
        $set_data_anggota = array(
            "gaji"   => hapus_koma($data['gaji']),
            "plafon" => hapus_koma($data['plafon']),
            // "plafon_pakai" => (hapus_koma($data['plafon']) - hapus_koma($data['sisa_plafon'])),
        );

        $this->db->set($set_data_anggota)->where("no_ang", $data['no_ang'])->update("t_anggota");

        if ($mode_edit == "1") {
            $no_pinjam = $no_pinjam_edit;
        } else {
            $no_pinjam = $this->get_no_pinjam_simulasi($data['tgl_pinjam']);
        }

        $set_data = array(
            "no_pinjam"       => $no_pinjam,
            "angsuran"        => hapus_koma($data['angsuran']),
            "gaji"            => hapus_koma($data['gaji']),
            "jenis_margin"    => $data['jenis_margin'],
            "jml_biaya_admin" => hapus_koma($data['jml_biaya_admin']),
            "jml_diterima"    => hapus_koma($data['jml_pinjam']),
            "jml_margin"      => hapus_koma($data['jml_margin']),
            "jml_pinjam"      => hapus_koma($data['jml_pinjam']),
            "kd_bagian"       => $data['kd_bagian'],
            "kd_dep"          => $data['kd_dep'],
            "kd_pinjaman"     => "1",
            "kd_prsh"         => $data['kd_prsh'],
            "margin"          => $data['margin'],
            "nm_ang"          => $data['nm_ang'],
            "nm_bagian"       => $data['nm_bagian'],
            "nm_dep"          => $data['nm_dep'],
            "nm_pinjaman"     => "REGULER",
            "nm_prsh"         => $data['nm_prsh'],
            "no_ang"          => $data['no_ang'],
            "no_peg"          => $data['no_peg'],
            "plafon"          => hapus_koma($data['plafon']),
            "tempo_bln"       => $data['tempo_bln'],
            "tgl_pinjam"      => $data['tgl_pinjam'],
            // "user_edit" => $this->
            // "sisa_plafon"     => hapus_koma($data['sisa_plafon']),
            // "tgl_angs"        => $data['tgl_angs'],
            // "tgl_entri"       => date("Y-m-d"),
            // "tgl_jt"          => $data['tgl_jt'],
        );

        if ($mode_edit == "1") {
            $set_data['user_edit'] = $this->session->userdata("username");
        } else {
            $set_data['user_input'] = $this->session->userdata("username");
            $set_data['tgl_insert'] = $data['tgl_pinjam'];
        }

        $insert = $this->db->set($set_data)->where("no_pinjam", $no_pinjam)->update("t_simulasi_pinjaman_ang");

        return $insert;
    }

    public function delete_simulasi_pinjaman($data)
    {
        $this->db->where("no_pinjam", $data['no_pinjam'])->delete("t_simulasi_pinjaman_ang_det");
        return $this->db->where("no_pinjam", $data['no_pinjam'])->delete("t_simulasi_pinjaman_ang");
    }

    public function delete_pinjaman($data)
    {
        $this->db->where("no_pinjam", $data['no_pinjam'])->delete("t_pinjaman_ang_det");
        return $this->db->where("no_pinjam", $data['no_pinjam'])->delete("t_pinjaman_ang");
    }

    public function jumlah_hari($tanggal1, $tanggal2)
    {
        $datediff = strtotime($tanggal2) - (strtotime($tanggal1));
        return round($datediff / (60 * 60 * 24));
    }

    public function get_angsuran_reguler($data)
    {
        $mode            = isset($data['mode']) ? $data['mode'] : "";
        $is_ganti_margin = isset($data['is_ganti_margin']) ? $data['is_ganti_margin'] : "";

        $tgl_pinjam = ($mode == "realisasi") ? $data['tgl_realisasi'] : $data['tgl_pinjam'];

        $xtgl           = strtotime($tgl_pinjam);
        $tahun          = date("Y", $xtgl);
        $bulan          = date("m", $xtgl);
        $hari_realisasi = date("d", $xtgl);

        $tgl_angs = date('Y-m-t', mktime(0, 0, 0, $bulan + 1, 1, $tahun));
        $tgl_awal = $tgl_pinjam;

        $data_margin = $this->master_model->get_margin_pinjaman_berlaku("1", $data['tempo_bln'], $data['tgl_pinjam']);

        $margin = ($data_margin->num_rows() > 0) ? $data_margin->row(0)->rate : 0;

        if ($mode == "realisasi" and $is_ganti_margin == "1") {
            $margin = $data['margin'];
        }

        $pokok_awal = hapus_koma($data['jml_pinjam']) + hapus_koma($data['jml_biaya_admin']);

        $data_angsuran = array();

        for ($i = 0; $i < $data['tempo_bln']; $i++) {
            $blth_angsuran = substr($tgl_angs, 0, 7);
            $tahun         = date("Y", strtotime($tgl_angs));
            $bulan         = date("m", strtotime($tgl_angs));

            $pokok_per_bulan    = (hapus_koma($data['jml_pinjam']) + hapus_koma($data['jml_biaya_admin'])) / $data['tempo_bln'];
            $margin_per_bulan   = hapus_koma($data['jml_pinjam']) * (($margin / 100) / 12);
            $angsuran_per_bulan = $pokok_per_bulan + $margin_per_bulan;

            $pokok_akhir = $pokok_awal - $pokok_per_bulan;

            $item = array(
                "blth_angsuran"      => $blth_angsuran,
                "tahun"              => $tahun,
                "bulan"              => $bulan,
                "hari"               => "",
                "pokok_awal"         => $pokok_awal,
                "pokok_per_bulan"    => $pokok_per_bulan,
                "margin_per_bulan"   => $margin_per_bulan,
                "angsuran_per_bulan" => $angsuran_per_bulan,
                "pokok_akhir"        => $pokok_akhir,
                "nm_pot_bonus"       => "",
            );

            $data_angsuran[] = $item;

            $tgl_angs = date("Y-m-t", mktime(0, 0, 0, $bulan + 1, 1, $tahun));

            $pokok_awal = $pokok_akhir;
        }

        return $data_angsuran;
    }

    public function get_angsuran_kkb($data)
    {
        $mode            = isset($data['mode']) ? $data['mode'] : "";
        $is_ganti_margin = isset($data['is_ganti_margin']) ? $data['is_ganti_margin'] : "";

        $tgl_pinjam = ($mode == "realisasi") ? $data['tgl_realisasi'] : $data['tgl_pinjam'];

        $xtgl           = strtotime($tgl_pinjam);
        $tahun          = date("Y", $xtgl);
        $bulan          = date("m", $xtgl);
        $hari_realisasi = date("d", $xtgl);

        $tgl_awal = $tgl_pinjam;
        $tgl_angs = date('Y-m-t', mktime(0, 0, 0, $bulan, 1, $tahun));

        $angsuran         = hapus_koma($data['gaji']) * ($data['persen_angsuran'] / 100);
        $jml_min_angsuran = hapus_koma($data['gaji']) * ($data['min_angsuran'] / 100);
        $jml_max_angsuran = hapus_koma($data['gaji']) * ($data['max_angsuran'] / 100);

        $data_margin = $this->master_model->get_margin_pinjaman_berlaku("2", $data['tempo_bln'], $data['tgl_pinjam']);

        $margin = ($data_margin->num_rows() > 0) ? $data_margin->row(0)->rate : 0;

        if ($mode == "realisasi" and $is_ganti_margin == "1") {
            $margin = $data['margin'];
        }

        $pokok_awal  = hapus_koma($data['jml_pinjam']);
        $pokok_akhir = hapus_koma($data['jml_pinjam']);

        $data_angsuran = array();

        for ($i = 0; $i < $data['tempo_bln']; $i++) {
            $blth_angsuran = substr($tgl_angs, 0, 7);
            $tahun         = date("Y", strtotime($tgl_angs));
            $bulan         = date("m", strtotime($tgl_angs));
            $hari          = $this->jumlah_hari($tgl_awal, $tgl_angs);

            $data_pot_bonus = $this->master_model->get_pot_bonus_pg_berlaku($tahun, $bulan, $data['kd_prsh']);

            $banyak_min_angsuran = ($data_pot_bonus->num_rows() > 0) ? $data_pot_bonus->row(0)->banyak_min_angsuran : 0;
            $banyak_max_angsuran = ($data_pot_bonus->num_rows() > 0) ? $data_pot_bonus->row(0)->banyak_max_angsuran : 0;
            $nm_pot_bonus        = ($data_pot_bonus->num_rows() > 0) ? $data_pot_bonus->row(0)->nm_pot_bonus : "";

            $angsuran = hapus_koma($data['gaji']) * ($data['persen_angsuran'] / 100);

            if ($i == 0) {
                $banyak_min_angsuran = 0;
                $banyak_max_angsuran = 0;
                $angsuran            = 0;
                $nm_pot_bonus        = "";
            }

            if ($angsuran > 0) {
                $nm_pot_bonus = "Potong Gaji; " . $nm_pot_bonus;
            }

            $xjml_potga        = $angsuran;
            $xjml_min_angsuran = ($banyak_min_angsuran * $jml_min_angsuran);
            $xjml_max_angsuran = ($banyak_max_angsuran * $jml_max_angsuran);

            $angsuran_per_bulan = $angsuran + ($banyak_min_angsuran * $jml_min_angsuran) + ($banyak_max_angsuran * $jml_max_angsuran);
            $pokok_per_bulan    = ($i == 0) ? hapus_koma($data['jml_pinjam']) : 0;
            $margin_per_bulan   = $pokok_akhir * ($margin / 100) * ($hari / 365);

            $pokok_akhir = $pokok_awal + $margin_per_bulan - $angsuran_per_bulan;

            if ($pokok_akhir <= 0) {
                $angsuran_per_bulan = $pokok_awal + $margin_per_bulan;
                $pokok_akhir        = 0;

                $xangsuran_per_bulan = $angsuran_per_bulan;

                if (($xangsuran_per_bulan - $xjml_potga) > 0) {
                    $xangsuran_per_bulan -= $xjml_potga;

                    if (($xangsuran_per_bulan - $xjml_min_angsuran) > 0) {
                        $xangsuran_per_bulan -= $xjml_min_angsuran;

                        if (($xangsuran_per_bulan - $xjml_max_angsuran) <= 0) {
                            $xjml_max_angsuran = $xangsuran_per_bulan;
                        }

                    } else {
                        $xjml_min_angsuran = $xangsuran_per_bulan;
                        $xjml_max_angsuran = 0;
                    }
                } else {
                    $xjml_potga        = $xangsuran_per_bulan;
                    $xjml_min_angsuran = 0;
                    $xjml_max_angsuran = 0;
                }
            }

            $item = array(
                "blth_angsuran"      => $blth_angsuran,
                "tahun"              => $tahun,
                "bulan"              => $bulan,
                "hari"               => $hari,
                "pokok_awal"         => $pokok_awal,
                "pokok_per_bulan"    => $pokok_per_bulan,
                "margin_per_bulan"   => $margin_per_bulan,
                "angsuran_per_bulan" => $angsuran_per_bulan,
                "jml_potga"          => $xjml_potga,
                "jml_min_angsuran"   => $xjml_min_angsuran,
                "jml_max_angsuran"   => $xjml_max_angsuran,
                "pokok_akhir"        => $pokok_akhir,
                "nm_pot_bonus"       => $nm_pot_bonus,
            );

            $data_angsuran[] = $item;

            if ($pokok_akhir == 0) {
                break;
            }

            $tgl_awal = $tgl_angs;
            $tgl_angs = date("Y-m-t", mktime(0, 0, 0, $bulan + 1, 1, $tahun));

            $pokok_awal = $pokok_akhir;
        }

        return $data_angsuran;
    }

    public function insert_simulasi_kkb($data, $no_pinjam_edit = "", $mode_edit = "")
    {
        $set_data_anggota = array(
            "gaji"   => hapus_koma($data['gaji']),
            "plafon" => hapus_koma($data['plafon']),
        );

        $this->db->set($set_data_anggota)->where("no_ang", $data['no_ang'])->update("t_anggota");

        if ($mode_edit == "1") {
            $no_pinjam = $no_pinjam_edit;
        } else {
            $no_pinjam = $this->get_no_pinjam_simulasi($data['tgl_pinjam']);
        }

        $set_data = array(
            "no_pinjam"        => $no_pinjam,
            "angsuran"         => hapus_koma($data['angsuran']),
            "gaji"             => hapus_koma($data['gaji']),
            "jenis_margin"     => $data['jenis_margin'],
            "jml_diterima"     => hapus_koma($data['jml_pinjam']),
            "jml_max_angsuran" => hapus_koma($data['jml_max_angsuran']),
            "jml_min_angsuran" => hapus_koma($data['jml_min_angsuran']),
            "jml_pinjam"       => hapus_koma($data['jml_pinjam']),
            "kd_bagian"        => $data['kd_bagian'],
            "kd_dep"           => $data['kd_dep'],
            "kd_pinjaman"      => "2",
            "kd_prsh"          => $data['kd_prsh'],
            "margin"           => $data['margin'],
            "max_angsuran"     => $data['max_angsuran'],
            "min_angsuran"     => $data['min_angsuran'],
            "nm_ang"           => $data['nm_ang'],
            "nm_bagian"        => $data['nm_bagian'],
            "nm_dep"           => $data['nm_dep'],
            "nm_pinjaman"      => "KKB",
            "nm_prsh"          => $data['nm_prsh'],
            "no_ang"           => $data['no_ang'],
            "no_peg"           => $data['no_peg'],
            "persen_angsuran"  => $data['persen_angsuran'],
            "plafon"           => hapus_koma($data['plafon']),
            "tempo_bln"        => $data['tempo_bln'],
            "tgl_pinjam"       => $data['tgl_pinjam'],
            // "plafon_bonus"      => $data['plafon_bonus'],
            // "sisa_plafon_bonus" => $data['sisa_plafon_bonus'],
            // "tgl_angs"         => $data['tgl_angs'],
            // "tgl_entri"         => date("Y-m-d"),
            // "tgl_jt"           => $data['tgl_jt'],
        );

        if ($mode_edit == "1") {
            $set_data['user_edit'] = $this->session->userdata("username");
        } else {
            $set_data['user_input'] = $this->session->userdata("username");
            $set_data['tgl_insert'] = $data['tgl_pinjam'];
        }

        $insert = $this->db->set($set_data)->where("no_pinjam", $no_pinjam)->update("t_simulasi_pinjaman_ang");

        // $tgl_awal = $data['tgl_pinjam'];

        // $xtgl_awal      = explode("-", $tgl_awal);
        // $hari_tgl_awal  = $xtgl_awal[2];
        // $bulan_tgl_awal = $xtgl_awal[1];
        // $tahun_tgl_awal = $xtgl_awal[0];

        // $tgl_angs = $data['tgl_angs'];

        // $data_margin = $this->master_model->get_margin_pinjaman_berlaku("2", $data['tempo_bln'], $data['tgl_pinjam']);

        // $margin = ($data_margin->num_rows() > 0) ? $data_margin->row(0)->rate : 0;

        // $pokok_awal  = hapus_koma($data['jml_pinjam']);
        // $pokok_akhir = hapus_koma($data['jml_pinjam']);

        /*if ($mode_edit == "1") {
        $this->db->where("no_pinjam", $no_pinjam)->delete("t_simulasi_pinjaman_ang_det");
        }*/

        // $jml_margin = 0;

        // $set_data['jml_margin'] = round($jml_margin, 2);

        return $insert;
    }

    public function get_angsuran_pht($data)
    {
        $mode            = isset($data['mode']) ? $data['mode'] : "";
        $is_ganti_margin = isset($data['is_ganti_margin']) ? $data['is_ganti_margin'] : "";

        $tgl_pinjam = ($mode == "realisasi") ? $data['tgl_realisasi'] : $data['tgl_pinjam'];

        $xtgl           = strtotime($tgl_pinjam);
        $tahun          = date("Y", $xtgl);
        $bulan          = date("m", $xtgl);
        $hari_realisasi = date("d", $xtgl);

        $tgl_angs = date('Y-m-t', mktime(0, 0, 0, $bulan + 1, 1, $tahun));
        $tgl_awal = $tgl_pinjam;

        $data_margin = $this->master_model->get_margin_pinjaman_berlaku("3", $data['tempo_bln'], $data['tgl_pinjam']);

        $margin = ($data_margin->num_rows() > 0) ? $data_margin->row(0)->rate : 0;

        if ($mode == "realisasi" and $is_ganti_margin == "1") {
            $margin = $data['margin'];
        }

        $pokok_awal = hapus_koma($data['jml_pinjam']);

        $data_angsuran = array();

        for ($i = 0; $i < $data['tempo_bln']; $i++) {
            $blth_angsuran = substr($tgl_angs, 0, 7);
            $tahun         = date("Y", strtotime($tgl_angs));
            $bulan         = date("m", strtotime($tgl_angs));

            if ($data['jns_potong_bunga'] == "POTONG") {
                if ($i == ($data['tempo_bln'] - 1)) {
                    $pokok_per_bulan    = ($i < ($data['tempo_bln'] - 1)) ? 0 : hapus_koma($data['jml_pinjam']);
                    $margin_per_bulan   = 0;
                    $angsuran_per_bulan = ($pokok_per_bulan + $margin_per_bulan);
                    $pokok_akhir        = (hapus_koma($data['jml_pinjam']) - $pokok_per_bulan);

                    $item = array(
                        "blth_angsuran"      => $blth_angsuran,
                        "tahun"              => $tahun,
                        "bulan"              => $bulan,
                        "hari"               => "",
                        "pokok_awal"         => $pokok_awal,
                        "pokok_per_bulan"    => $pokok_per_bulan,
                        "margin_per_bulan"   => $margin_per_bulan,
                        "angsuran_per_bulan" => $angsuran_per_bulan,
                        "pokok_akhir"        => $pokok_akhir,
                        "nm_pot_bonus"       => "",
                    );

                    $data_angsuran[] = $item;
                }
            } else {
                $pokok_per_bulan    = ($i < ($data['tempo_bln'] - 1)) ? 0 : hapus_koma($data['jml_pinjam']);
                $margin_per_bulan   = hapus_koma($data['jml_margin']) / $data['tempo_bln'];
                $angsuran_per_bulan = ($pokok_per_bulan + $margin_per_bulan);
                $pokok_akhir        = (hapus_koma($data['jml_pinjam']) - $pokok_per_bulan);

                $item = array(
                    "blth_angsuran"      => $blth_angsuran,
                    "tahun"              => $tahun,
                    "bulan"              => $bulan,
                    "hari"               => "",
                    "pokok_awal"         => $pokok_awal,
                    "pokok_per_bulan"    => $pokok_per_bulan,
                    "margin_per_bulan"   => $margin_per_bulan,
                    "angsuran_per_bulan" => $angsuran_per_bulan,
                    "pokok_akhir"        => $pokok_akhir,
                    "nm_pot_bonus"       => "",
                );

                $data_angsuran[] = $item;
            }

            $tgl_angs = date("Y-m-t", mktime(0, 0, 0, $bulan + 1, 1, $tahun));
        }

        return $data_angsuran;
    }

    public function insert_simulasi_pht($data, $no_pinjam_edit = "", $mode_edit = "")
    {
        $set_data_anggota = array(
            "gaji"   => hapus_koma($data['gaji']),
            "plafon" => hapus_koma($data['plafon']),
        );

        $this->db->set($set_data_anggota)->where("no_ang", $data['no_ang'])->update("t_anggota");

        if ($mode_edit == "1") {
            $no_pinjam = $no_pinjam_edit;
        } else {
            $no_pinjam = $this->get_no_pinjam_simulasi($data['tgl_pinjam']);
        }

        $set_data = array(
            "no_pinjam"        => $no_pinjam,
            "angsuran"         => hapus_koma($data['angsuran']),
            "gaji"             => hapus_koma($data['gaji']),
            "jenis_margin"     => $data['jenis_margin'],
            "jml_biaya_admin"  => hapus_koma($data['jml_biaya_admin']),
            "jml_diterima"     => hapus_koma($data['jml_diterima']),
            "jml_margin"       => hapus_koma($data['jml_margin']),
            "jml_pinjam"       => hapus_koma($data['jml_pinjam']),
            "jns_potong_admin" => $data['jns_potong_admin'],
            "jns_potong_bunga" => $data['jns_potong_bunga'],
            "kd_bagian"        => $data['kd_bagian'],
            "kd_dep"           => $data['kd_dep'],
            "kd_pinjaman"      => "3",
            "kd_prsh"          => $data['kd_prsh'],
            "margin"           => $data['margin'],
            "nm_ang"           => $data['nm_ang'],
            "nm_bagian"        => $data['nm_bagian'],
            "nm_dep"           => $data['nm_dep'],
            "nm_pinjaman"      => "PHT",
            "nm_prsh"          => $data['nm_prsh'],
            "no_ang"           => $data['no_ang'],
            "no_peg"           => $data['no_peg'],
            "plafon"           => hapus_koma($data['plafon']),
            "tempo_bln"        => $data['tempo_bln'],
            "tgl_pinjam"       => $data['tgl_pinjam'],
            // "tgl_angs"         => $data['tgl_angs'],
            // "tgl_entri"    => date("Y-m-d"),
            // "tgl_jt"           => $data['tgl_jt'],
        );

        if ($mode_edit == "1") {
            $set_data['user_edit'] = $this->session->userdata("username");
        } else {
            $set_data['user_input'] = $this->session->userdata("username");
            $set_data['tgl_insert'] = $data['tgl_pinjam'];
        }

        $insert = $this->db->set($set_data)->where("no_pinjam", $no_pinjam)->update("t_simulasi_pinjaman_ang");

        /*if ($mode_edit == "1") {
        $this->db->where("no_pinjam", $no_pinjam)->delete("t_simulasi_pinjaman_ang_det");
        }*/

        return $insert;
    }

    public function get_angsuran_kpr($data)
    {
        $mode            = isset($data['mode']) ? $data['mode'] : "";
        $is_ganti_margin = isset($data['is_ganti_margin']) ? $data['is_ganti_margin'] : "";

        $tgl_pinjam = ($mode == "realisasi") ? $data['tgl_realisasi'] : $data['tgl_pinjam'];

        $xtgl           = strtotime($tgl_pinjam);
        $tahun          = date("Y", $xtgl);
        $bulan          = date("m", $xtgl);
        $hari_realisasi = date("d", $xtgl);

        $tgl_awal = $tgl_pinjam;
        $tgl_angs = date('Y-m-t', mktime(0, 0, 0, $bulan, 1, $tahun));

        $angsuran         = hapus_koma($data['gaji']) * ($data['persen_angsuran'] / 100);
        $jml_min_angsuran = hapus_koma($data['gaji']) * ($data['min_angsuran'] / 100);
        $jml_max_angsuran = hapus_koma($data['gaji']) * ($data['max_angsuran'] / 100);

        $data_margin = $this->master_model->get_margin_pinjaman_berlaku("4", $data['tempo_bln'], $data['tgl_pinjam']);

        $margin = ($data_margin->num_rows() > 0) ? $data_margin->row(0)->rate : 0;

        if ($mode == "realisasi" and $is_ganti_margin == "1") {
            $margin = $data['margin'];
        }

        $pokok_awal  = hapus_koma($data['jml_pinjam']);
        $pokok_akhir = hapus_koma($data['jml_pinjam']);

        $data_angsuran = array();

        for ($i = 0; $i < $data['tempo_bln']; $i++) {
            $blth_angsuran = substr($tgl_angs, 0, 7);
            $tahun         = date("Y", strtotime($tgl_angs));
            $bulan         = date("m", strtotime($tgl_angs));
            $hari          = $this->jumlah_hari($tgl_awal, $tgl_angs);

            $data_pot_bonus = $this->master_model->get_pot_bonus_pg_berlaku($tahun, $bulan, $data['kd_prsh']);

            $banyak_min_angsuran = ($data_pot_bonus->num_rows() > 0) ? $data_pot_bonus->row(0)->banyak_min_angsuran : 0;
            $banyak_max_angsuran = ($data_pot_bonus->num_rows() > 0) ? $data_pot_bonus->row(0)->banyak_max_angsuran : 0;
            $nm_pot_bonus        = ($data_pot_bonus->num_rows() > 0) ? $data_pot_bonus->row(0)->nm_pot_bonus : "";

            $angsuran = hapus_koma($data['gaji']) * ($data['persen_angsuran'] / 100);

            if ($i == 0) {
                $banyak_min_angsuran = 0;
                $banyak_max_angsuran = 0;
                $angsuran            = 0;
                $nm_pot_bonus        = "";
            }

            if ($angsuran > 0) {
                $nm_pot_bonus = "Potong Gaji; " . $nm_pot_bonus;
            }

            $xjml_potga        = $angsuran;
            $xjml_min_angsuran = ($banyak_min_angsuran * $jml_min_angsuran);
            $xjml_max_angsuran = ($banyak_max_angsuran * $jml_max_angsuran);

            $angsuran_per_bulan = $angsuran + ($banyak_min_angsuran * $jml_min_angsuran) + ($banyak_max_angsuran * $jml_max_angsuran);
            $pokok_per_bulan    = ($i == 0) ? hapus_koma($data['jml_pinjam']) : 0;
            $margin_per_bulan   = $pokok_akhir * ($margin / 100) * ($hari / 365);

            $pokok_akhir = $pokok_awal + $margin_per_bulan - $angsuran_per_bulan;

            if ($pokok_akhir <= 0) {
                $angsuran_per_bulan = $pokok_awal + $margin_per_bulan;
                $pokok_akhir        = 0;

                $xangsuran_per_bulan = $angsuran_per_bulan;

                if (($xangsuran_per_bulan - $xjml_potga) > 0) {
                    $xangsuran_per_bulan -= $xjml_potga;

                    if (($xangsuran_per_bulan - $xjml_min_angsuran) > 0) {
                        $xangsuran_per_bulan -= $xjml_min_angsuran;

                        if (($xangsuran_per_bulan - $xjml_max_angsuran) <= 0) {
                            $xjml_max_angsuran = $xangsuran_per_bulan;
                        }

                    } else {
                        $xjml_min_angsuran = $xangsuran_per_bulan;
                        $xjml_max_angsuran = 0;
                    }
                } else {
                    $xjml_potga        = $xangsuran_per_bulan;
                    $xjml_min_angsuran = 0;
                    $xjml_max_angsuran = 0;
                }
            }

            $item = array(
                "blth_angsuran"      => $blth_angsuran,
                "tahun"              => $tahun,
                "bulan"              => $bulan,
                "hari"               => $hari,
                "pokok_awal"         => $pokok_awal,
                "pokok_per_bulan"    => $pokok_per_bulan,
                "margin_per_bulan"   => $margin_per_bulan,
                "angsuran_per_bulan" => $angsuran_per_bulan,
                "jml_potga"          => $xjml_potga,
                "jml_min_angsuran"   => $xjml_min_angsuran,
                "jml_max_angsuran"   => $xjml_max_angsuran,
                "pokok_akhir"        => $pokok_akhir,
                "nm_pot_bonus"       => $nm_pot_bonus,
            );

            $data_angsuran[] = $item;

            if ($pokok_akhir == 0) {
                break;
            }

            $tgl_awal = $tgl_angs;
            $tgl_angs = date("Y-m-t", mktime(0, 0, 0, $bulan + 1, 1, $tahun));

            $pokok_awal = $pokok_akhir;
        }

        return $data_angsuran;
    }

    public function insert_simulasi_kpr($data, $no_pinjam_edit = "", $mode_edit = "")
    {
        $set_data_anggota = array(
            "gaji"   => hapus_koma($data['gaji']),
            "plafon" => hapus_koma($data['plafon']),
        );

        $this->db->set($set_data_anggota)->where("no_ang", $data['no_ang'])->update("t_anggota");

        if ($mode_edit == "1") {
            $no_pinjam = $no_pinjam_edit;
        } else {
            $no_pinjam = $this->get_no_pinjam_simulasi($data['tgl_pinjam']);
        }

        $set_data = array(
            "no_pinjam"        => $no_pinjam,
            "angsuran"         => hapus_koma($data['angsuran']),
            "gaji"             => hapus_koma($data['gaji']),
            "jenis_margin"     => $data['jenis_margin'],
            "jml_diterima"     => hapus_koma($data['jml_pinjam']),
            "jml_max_angsuran" => hapus_koma($data['jml_max_angsuran']),
            "jml_min_angsuran" => hapus_koma($data['jml_min_angsuran']),
            "jml_pinjam"       => hapus_koma($data['jml_pinjam']),
            "kd_bagian"        => $data['kd_bagian'],
            "kd_dep"           => $data['kd_dep'],
            "kd_pinjaman"      => "4",
            "kd_prsh"          => $data['kd_prsh'],
            "margin"           => $data['margin'],
            "max_angsuran"     => $data['max_angsuran'],
            "min_angsuran"     => $data['min_angsuran'],
            "nm_ang"           => $data['nm_ang'],
            "nm_bagian"        => $data['nm_bagian'],
            "nm_dep"           => $data['nm_dep'],
            "nm_pinjaman"      => "KPR",
            "nm_prsh"          => $data['nm_prsh'],
            "no_ang"           => $data['no_ang'],
            "no_peg"           => $data['no_peg'],
            "persen_angsuran"  => $data['persen_angsuran'],
            "plafon"           => hapus_koma($data['plafon']),
            "tempo_bln"        => $data['tempo_bln'],
            "tgl_pinjam"       => $data['tgl_pinjam'],
            // "plafon_bonus"      => $data['plafon_bonus'],
            // "sisa_plafon"      => hapus_koma($data['sisa_plafon']),
            // "sisa_plafon_bonus" => $data['sisa_plafon_bonus'],
            // "tgl_angs"         => $data['tgl_angs'],
            // "tgl_entri"         => date("Y-m-d"),
            // "tgl_jt"           => $data['tgl_jt'],
        );

        if ($mode_edit == "1") {
            $set_data['user_edit'] = $this->session->userdata("username");
        } else {
            $set_data['user_input'] = $this->session->userdata("username");
            $set_data['tgl_insert'] = $data['tgl_pinjam'];
        }

        $insert = $this->db->set($set_data)->where("no_pinjam", $no_pinjam)->update("t_simulasi_pinjaman_ang");

        // $xtgl  = strtotime($data['tgl_pinjam']);
        // $tahun = date("Y", $xtgl);
        // $bulan = date("m", $xtgl);

        // $tgl_awal = date('Y-m-01', mktime(0, 0, 0, $bulan + 1, 1, $tahun));
        // $tgl_angs = $data['tgl_angs'];

        // $data_margin = $this->master_model->get_margin_pinjaman_berlaku("4", $data['tempo_bln'], $data['tgl_pinjam']);

        // $margin = ($data_margin->num_rows() > 0) ? $data_margin->row(0)->rate : 0;

        // $pokok_awal  = hapus_koma($data['jml_pinjam']);
        // $pokok_akhir = hapus_koma($data['jml_pinjam']);

        /*if ($mode_edit == "1") {
        $this->db->where("no_pinjam", $no_pinjam)->delete("t_simulasi_pinjaman_ang_det");
        }*/

        // $jml_margin = 0;

        // $set_data['jml_margin'] = $jml_margin;

        return $insert;
    }

    public function update_angsuran_kkb_kpr($tahun, $bulan)
    {
        $query_pj = "SELECT a.no_pinjam, tgl_pinjam, tgl_aprove, is_aprove, unit_adm, no_ang, no_peg, nm_ang, nm_ibukdg, tgl_lhr, alamat_ang, no_hp, no_npwp, no_ktp, kd_prsh, nm_prsh, kd_dep, nm_dep, kd_bagian, nm_bagian, kd_pinjaman, nm_pinjaman, tgl_angs, tgl_jt, jml_pinjam, a.tempo_bln, jns_jangka, jenis_margin, margin, jml_margin, gaji, plafon, sisa_plafon, plafon_bonus, sisa_plafon_bonus, min_angsuran, jml_min_angsuran, max_angsuran, jml_max_angsuran, a.angsuran
            FROM t_pinjaman_ang a JOIN
            t_pinjaman_ang_det b
            on a.no_pinjam = b.no_pinjam
            WHERE a.is_aprove = '0' and kd_pinjaman in ('2', '4') and b.blth_angsuran = '" . $tahun . "-" . $bulan . "'";

        $e_query_pj = $this->db->query($query_pj);

        foreach ($e_query_pj->result_array() as $key => $value) {
            $xblth_angsuran = $tahun . "-" . $bulan;

            $data_det = $this->db->select("no_pinjam_det, no_pinjam, tgl_rilis, hari, blth_angsuran, bulan_angsuran, tahun_angsuran, angs_ke, tempo_bln, pokok_awal, pokok, bunga, angsuran, pokok_akhir, sts_lunas, sts_potga, blth_bayar, bukti_pelunasan, bukti_tagihan")
                ->where("no_pinjam", $value['no_pinjam'])
                ->where("blth_angsuran >=", $xblth_angsuran)
                ->order_by("angs_ke")
                ->get("t_pinjaman_ang_det");

            if ($value['kd_pinjaman'] == "2") {
                $tgl_awal = $value['tgl_pinjam'];

                $data_margin = $this->master_model->get_margin_pinjaman_berlaku("2", $value['tempo_bln'], $value['tgl_pinjam']);

                $margin = ($data_margin->num_rows() > 0) ? $data_margin->row(0)->rate : 0;

                $pokok_awal  = $value['jml_pinjam'];
                $pokok_akhir = $value['jml_pinjam'];

                $angs_ke  = $data_det->row(0)->angs_ke;
                $tgl_angs = date("Y-m-t", mktime(0, 0, 0, $bulan, 1, $tahun));

                foreach ($data_det->result_array() as $key1 => $value1) {
                    $this->db->where("no_pinjam_det", $value1['no_pinjam_det'])->delete("t_pinjaman_ang_det");

                    $no_pinjam_det = $value['no_pinjam'] . str_pad($angs_ke, 5, "0", STR_PAD_LEFT);
                    $blth_angsuran = substr($tgl_angs, 0, 7);
                    $tahun         = date("Y", strtotime($tgl_angs));
                    $bulan         = date("m", strtotime($tgl_angs));
                    $hari          = ($key1 == 0) ? $value1['hari'] : $this->jumlah_hari($tgl_awal, $tgl_angs);

                    $data_pot_bonus = $this->master_model->get_pot_bonus_pg_berlaku($tahun, $bulan, $data['kd_prsh']);

                    $nm_pot_bonus        = ($data_pot_bonus->num_rows() > 0) ? $data_pot_bonus->row(0)->nm_pot_bonus : "";
                    $banyak_min_angsuran = ($data_pot_bonus->num_rows() > 0) ? $data_pot_bonus->row(0)->banyak_min_angsuran : 0;
                    $banyak_max_angsuran = ($data_pot_bonus->num_rows() > 0) ? $data_pot_bonus->row(0)->banyak_max_angsuran : 0;

                    $angsuran         = ($banyak_min_angsuran * $value['jml_min_angsuran']) + ($banyak_max_angsuran * $value['jml_max_angsuran']);
                    $pokok_per_bulan  = ($angs_ke == 1) ? $value['jml_pinjam'] : 0;
                    $margin_per_bulan = $pokok_akhir * ($margin / 100) * ($hari / 365);

                    $pokok_akhir = $pokok_awal + $margin_per_bulan - $angsuran;

                    if ($pokok_akhir < 0 or ($pokok_akhir > 0 and $angs_ke == $value['tempo_bln'])) {
                        $angsuran += $pokok_akhir;
                        $pokok_akhir -= $pokok_akhir;
                    }

                    if ($pokok_akhir >= 0) {
                        $set_data1 = array(
                            "no_pinjam_det"  => $no_pinjam_det,
                            "no_pinjam"      => $value['no_pinjam'],
                            // "tgl_rilis"     => $data['tgl_rilis'],
                            "blth_angsuran"  => $blth_angsuran,
                            "hari"           => $hari,
                            "bulan_angsuran" => $bulan,
                            "tahun_angsuran" => $tahun,
                            "angs_ke"        => $angs_ke,
                            "tempo_bln"      => $value['tempo_bln'],
                            "pokok_awal"     => $pokok_awal,
                            "pokok"          => $pokok_per_bulan,
                            "bunga"          => $margin_per_bulan,
                            "angsuran"       => $angsuran,
                            "pokok_akhir"    => $pokok_akhir,
                            "nm_pot_bonus"   => $nm_pot_bonus,
                            // "sts_lunas"     => $data['sts_lunas'],
                            // "sts_potga"     => $data['sts_potga'],
                            // "blth_bayar"    => $data['blth_bayar'],
                        );

                        $this->db->set($set_data1)->insert("t_pinjaman_ang_det");
                    }

                    $tgl_awal = $tgl_angs;
                    $tgl_angs = date("Y-m-t", mktime(0, 0, 0, $bulan + 1, 1, $tahun));

                    $pokok_awal = $pokok_akhir;
                    $angs_ke++;
                }
            } else if ($value['kd_pinjaman'] == "4") {
                $tgl_awal = $value['tgl_pinjam'];

                $data_margin = $this->master_model->get_margin_pinjaman_berlaku("2", $value['tempo_bln'], $value['tgl_pinjam']);

                $margin = ($data_margin->num_rows() > 0) ? $data_margin->row(0)->rate : 0;

                $pokok_awal  = $value['jml_pinjam'];
                $pokok_akhir = $value['jml_pinjam'];

                $angs_ke  = $data_det->row(0)->angs_ke;
                $tgl_angs = date("Y-m-t", mktime(0, 0, 0, $bulan, 1, $tahun));

                foreach ($data_det->result_array() as $key1 => $value1) {
                    $this->db->where("no_pinjam_det", $value1['no_pinjam_det'])->delete("t_pinjaman_ang_det");

                    $no_pinjam_det = $value['no_pinjam'] . str_pad($angs_ke, 5, "0", STR_PAD_LEFT);
                    $blth_angsuran = substr($tgl_angs, 0, 7);
                    $tahun         = date("Y", strtotime($tgl_angs));
                    $bulan         = date("m", strtotime($tgl_angs));
                    $hari          = ($key1 == 0) ? $value1['hari'] : $this->jumlah_hari($tgl_awal, $tgl_angs);

                    $data_pot_bonus = $this->master_model->get_pot_bonus_pg_berlaku($tahun, $bulan, $data['kd_prsh']);

                    $nm_pot_bonus        = ($data_pot_bonus->num_rows() > 0) ? $data_pot_bonus->row(0)->nm_pot_bonus : "";
                    $banyak_max_angsuran = ($data_pot_bonus->num_rows() > 0) ? $data_pot_bonus->row(0)->banyak_max_angsuran : 0;

                    $angsuran         = ($value['angsuran']) + ($banyak_max_angsuran * $value['jml_max_angsuran']);
                    $pokok_per_bulan  = ($angs_ke == 1) ? $value['jml_pinjam'] : 0;
                    $margin_per_bulan = $pokok_akhir * ($margin / 100) * ($hari / 365);

                    $pokok_akhir = $pokok_awal + $margin_per_bulan - $angsuran;

                    if ($pokok_akhir < 0 or ($pokok_akhir > 0 and $angs_ke == $value['tempo_bln'])) {
                        $angsuran += $pokok_akhir;
                        $pokok_akhir -= $pokok_akhir;
                    }

                    if ($pokok_akhir >= 0) {
                        $set_data1 = array(
                            "no_pinjam_det"  => $no_pinjam_det,
                            "no_pinjam"      => $value['no_pinjam'],
                            // "tgl_rilis"     => $data['tgl_rilis'],
                            "blth_angsuran"  => $blth_angsuran,
                            "hari"           => $hari,
                            "bulan_angsuran" => $bulan,
                            "tahun_angsuran" => $tahun,
                            "angs_ke"        => $angs_ke,
                            "tempo_bln"      => $value['tempo_bln'],
                            "pokok_awal"     => $pokok_awal,
                            "pokok"          => $pokok_per_bulan,
                            "bunga"          => $margin_per_bulan,
                            "angsuran"       => $angsuran,
                            "pokok_akhir"    => $pokok_akhir,
                            "nm_pot_bonus"   => $nm_pot_bonus,
                            // "sts_lunas"     => $data['sts_lunas'],
                            // "sts_potga"     => $data['sts_potga'],
                            // "blth_bayar"    => $data['blth_bayar'],
                        );

                        $this->db->set($set_data1)->insert("t_pinjaman_ang_det");
                    }

                    $tgl_awal = $tgl_angs;
                    $tgl_angs = date("Y-m-t", mktime(0, 0, 0, $bulan + 1, 1, $tahun));

                    $pokok_awal = $pokok_akhir;
                    $angs_ke++;
                }
            }
        }
    }

    public function aprove_pinjaman($data)
    {
        $set_data = array(
            "is_aprove"   => "1",
            "tgl_aprove"  => date("Y-m-d"),
            "user_aprove" => $this->session->userdata("username"),
        );

        return $this->db->set($set_data)->where("no_pinjam", $data['no_pinjam'])->update("t_simulasi_pinjaman_ang");
    }

    public function batalkan_aprove_pinjaman($data)
    {
        $set_data = array(
            "is_aprove"   => "0",
            "tgl_aprove"  => null,
            "user_aprove" => null,
        );

        return $this->db->set($set_data)->where("no_pinjam", $data['no_pinjam'])->update("t_simulasi_pinjaman_ang");
    }

    public function realisasi_pinjaman($data)
    {
        $set_data = array(
            "jml_pinjam_realisasi" => hapus_koma($data['jml_pinjam']),
            // "angsuran"             => hapus_koma($data['angsuran']),
            "is_realisasi"         => "1",
            "tgl_realisasi"        => $data['tgl_realisasi'],
            "user_realisasi"       => $this->session->userdata("username"),
        );

        $update_simulasi = $this->db->set($set_data)->where("no_pinjam", $data['no_pinjam'])->update("t_simulasi_pinjaman_ang");

        $no_pinjam_realisasi = $this->get_no_pinjam($data['tgl_realisasi']);

        $set_data_pinjaman = array(
            "no_pinjam"        => $no_pinjam_realisasi,
            "tgl_pinjam"       => $data['tgl_realisasi'],
            "no_simulasi"      => $data['no_pinjam'],
            "tgl_simulasi"     => $data['tgl_pinjam'],
            "is_aprove"        => $data['is_aprove'],
            "tgl_aprove"       => $data['tgl_aprove'],
            "user_aprove"      => $data['user_aprove'],
            "is_realisasi"     => $data['is_realisasi'],
            "tgl_realisasi"    => $data['tgl_realisasi'],
            "user_realisasi"   => $data['user_realisasi'],
            "unit_adm"         => $data['unit_adm'],
            "no_ang"           => $data['no_ang'],
            "no_peg"           => $data['no_peg'],
            "nm_ang"           => $data['nm_ang'],
            "kd_prsh"          => $data['kd_prsh'],
            "nm_prsh"          => $data['nm_prsh'],
            "kd_dep"           => $data['kd_dep'],
            "nm_dep"           => $data['nm_dep'],
            "kd_bagian"        => $data['kd_bagian'],
            "nm_bagian"        => $data['nm_bagian'],
            "kd_pinjaman"      => $data['kd_pinjaman'],
            "nm_pinjaman"      => $data['nm_pinjaman'],
            "tgl_angs"         => $data['tgl_angs'],
            "tgl_jt"           => $data['tgl_jt'],
            "jml_pinjam"       => hapus_koma($data['jml_pinjam']),
            "tempo_bln"        => $data['tempo_bln'],
            "jns_jangka"       => $data['jns_jangka'],
            "jenis_margin"     => $data['jenis_margin'],
            "margin"           => $data['margin'],
            "jml_margin"       => hapus_koma($data['jml_margin']),
            "gaji"             => hapus_koma($data['gaji']),
            "plafon"           => hapus_koma($data['plafon']),
            "sisa_plafon"      => hapus_koma($data['sisa_plafon']),
            "min_angsuran"     => $data['min_angsuran'],
            "jml_min_angsuran" => hapus_koma($data['jml_min_angsuran']),
            "max_angsuran"     => $data['max_angsuran'],
            "jml_max_angsuran" => hapus_koma($data['jml_max_angsuran']),
            "persen_angsuran"  => $data['persen_angsuran'],
            "angsuran"         => hapus_koma($data['angsuran']),
            "jml_biaya_admin"  => hapus_koma($data['jml_biaya_admin']),
            "jml_potong"       => $data['jml_potong'],
            "jml_diterima"     => hapus_koma($data['jml_diterima']),
            "jns_potong_admin" => $data['jns_potong_admin'],
            "jns_potong_bunga" => $data['jns_potong_bunga'],
        );

        $set_data_pinjaman['user_input'] = $this->session->userdata("username");
        $set_data_pinjaman['tgl_insert'] = $data['tgl_pinjam'];

        $query = $this->db->set($set_data_pinjaman)->where("no_pinjam", $no_pinjam_realisasi)->update("t_pinjaman_ang");

        if ($data['kd_pinjaman'] == "1") {
            if (strtotime($data['tgl_realisasi']) >= strtotime('2018-10-01')) {
                $set_debet_plafon = array(
                    "no_ang"      => $data['no_ang'],
                    "no_peg"      => $data['no_peg'],
                    "nm_ang"      => $data['nm_ang'],
                    "kd_prsh"     => $data['kd_prsh'],
                    "nm_prsh"     => $data['nm_prsh'],
                    "kd_dep"      => $data['kd_dep'],
                    "nm_dep"      => $data['nm_dep'],
                    "kd_bagian"   => $data['kd_bagian'],
                    "nm_bagian"   => $data['nm_bagian'],
                    "jenis_debet" => "PINJAMAN",
                    "noref_penj"  => $no_pinjam_realisasi,
                    "tgl_penj"    => $data['tgl_realisasi'],
                    "jml_debet"   => hapus_koma($data['angsuran']),
                    // "status"      => $status,
                );

                $this->db->set($set_debet_plafon)->insert("t_plafon_debet");

                $jml_plafon_pakai = $this->db->select("ifnull(sum(jml_debet), 0) jml_debet_plafon")->where("no_ang", $data['no_ang'])->get("t_plafon_debet")->row(0)->jml_debet_plafon;

                $this->db->set(array("plafon_pakai" => $jml_plafon_pakai))->where("no_ang", $data['no_ang'])->update("t_anggota");
            }

            $tgl_awal = $data['tgl_realisasi'];

            $xtgl_awal      = explode("-", $tgl_awal);
            $hari_tgl_awal  = $xtgl_awal[2];
            $bulan_tgl_awal = $xtgl_awal[1];
            $tahun_tgl_awal = $xtgl_awal[0];

            $tgl_angs = $data['tgl_angs'];

            $margin = $data['margin'];

            $pokok_awal = hapus_koma($data['jml_pinjam']) + hapus_koma($data['jml_biaya_admin']);

            $data_angsuran = $this->get_angsuran_reguler($data);

            foreach ($data_angsuran as $key => $value) {
                $no_pinjam_det = $no_pinjam_realisasi . str_pad(($key + 1), 4, "0", STR_PAD_LEFT);

                $set_data1 = array(
                    "no_pinjam_det"  => $no_pinjam_det,
                    "no_pinjam"      => $no_pinjam_realisasi,
                    // "tgl_entri"      => date("Y-m-d"),
                    "blth_angsuran"  => $value['blth_angsuran'],
                    "bulan_angsuran" => $value['bulan'],
                    "tahun_angsuran" => $value['tahun'],
                    "angs_ke"        => ($key + 1),
                    "tempo_bln"      => $data['tempo_bln'],
                    "pokok_awal"     => $value['pokok_awal'],
                    "pokok"          => $value['pokok_per_bulan'],
                    "bunga"          => $value['margin_per_bulan'],
                    "angsuran"       => $value['angsuran_per_bulan'],
                    "pokok_akhir"    => $value['pokok_akhir'],
                    // "sts_lunas"     => $data['sts_lunas'],
                    // "sts_potga"     => $data['sts_potga'],
                    // "blth_bayar"    => $data['blth_bayar'],
                );

                $this->db->set($set_data1)->insert("t_pinjaman_ang_det");
            }

        } else if ($data['kd_pinjaman'] == "2") {
            if (strtotime($data['tgl_realisasi']) >= strtotime('2018-10-01')) {
                $set_debet_plafon = array(
                    "no_ang"      => $data['no_ang'],
                    "no_peg"      => $data['no_peg'],
                    "nm_ang"      => $data['nm_ang'],
                    "kd_prsh"     => $data['kd_prsh'],
                    "nm_prsh"     => $data['nm_prsh'],
                    "kd_dep"      => $data['kd_dep'],
                    "nm_dep"      => $data['nm_dep'],
                    "kd_bagian"   => $data['kd_bagian'],
                    "nm_bagian"   => $data['nm_bagian'],
                    "jenis_debet" => "PINJAMAN",
                    "noref_penj"  => $no_pinjam_realisasi,
                    "tgl_penj"    => $data['tgl_realisasi'],
                    "jml_debet"   => hapus_koma($data['angsuran']),
                    // "status"      => $status,
                );

                $this->db->set($set_debet_plafon)->insert("t_plafon_debet");

                $jml_plafon_pakai = $this->db->select("ifnull(sum(jml_debet), 0) jml_debet_plafon")->where("no_ang", $data['no_ang'])->get("t_plafon_debet")->row(0)->jml_debet_plafon;

                $this->db->set(array("plafon_pakai" => $jml_plafon_pakai))->where("no_ang", $data['no_ang'])->update("t_anggota");
            }

            $tgl_awal = $data['tgl_realisasi'];

            $xtgl_awal      = strtotime($tgl_awal);
            $hari_tgl_awal  = date("d", $xtgl_awal);
            $bulan_tgl_awal = date("m", $xtgl_awal);
            $tahun_tgl_awal = date("Y", $xtgl_awal);

            $tgl_angs = $data['tgl_angs'];

            $margin = $data['margin'];

            $pokok_awal  = hapus_koma($data['jml_pinjam']);
            $pokok_akhir = hapus_koma($data['jml_pinjam']);

            $jml_margin = 0;

            $data_angsuran = $this->get_angsuran_kkb($data);

            foreach ($data_angsuran as $key => $value) {
                $no_pinjam_det = $no_pinjam_realisasi . str_pad(($key + 1), 4, "0", STR_PAD_LEFT);

                $jml_margin += $value['margin_per_bulan'];

                $set_data1 = array(
                    "no_pinjam_det"    => $no_pinjam_det,
                    "no_pinjam"        => $no_pinjam_realisasi,
                    "nm_pot_bonus"     => $value['nm_pot_bonus'],
                    "blth_angsuran"    => $value['blth_angsuran'],
                    "hari"             => $value['hari'],
                    "bulan_angsuran"   => $value['bulan'],
                    "tahun_angsuran"   => $value['tahun'],
                    "angs_ke"          => ($key + 1),
                    "tempo_bln"        => $data['tempo_bln'],
                    "pokok_awal"       => $value['pokok_awal'],
                    "pokok"            => $value['pokok_per_bulan'],
                    "bunga"            => $value['margin_per_bulan'],
                    "angsuran"         => $value['angsuran_per_bulan'],
                    "jml_potga"        => $value['jml_potga'],
                    "jml_min_angsuran" => $value['jml_min_angsuran'],
                    "jml_max_angsuran" => $value['jml_max_angsuran'],
                    "pokok_akhir"      => $value['pokok_akhir'],
                    // "sts_lunas"     => $data['sts_lunas'],
                    // "sts_potga"     => $data['sts_potga'],
                    // "blth_bayar"    => $data['blth_bayar'],
                );

                $this->db->set($set_data1)->insert("t_pinjaman_ang_det");
            }

            $set_data_margin['jml_margin'] = $jml_margin;

            $this->db->set($set_data_margin)->where("no_pinjam", $no_pinjam_realisasi)->update("t_pinjaman_ang");
        } else if ($data['kd_pinjaman'] == "3") {
            if (strtotime($data['tgl_realisasi']) >= strtotime('2018-10-01')) {
                $set_debet_plafon = array(
                    "no_ang"      => $data['no_ang'],
                    "no_peg"      => $data['no_peg'],
                    "nm_ang"      => $data['nm_ang'],
                    "kd_prsh"     => $data['kd_prsh'],
                    "nm_prsh"     => $data['nm_prsh'],
                    "kd_dep"      => $data['kd_dep'],
                    "nm_dep"      => $data['nm_dep'],
                    "kd_bagian"   => $data['kd_bagian'],
                    "nm_bagian"   => $data['nm_bagian'],
                    "jenis_debet" => "PINJAMAN",
                    "noref_penj"  => $no_pinjam_realisasi,
                    "tgl_penj"    => $data['tgl_realisasi'],
                    "jml_debet"   => hapus_koma($data['angsuran']),
                    // "status"      => $status,
                );

                $this->db->set($set_debet_plafon)->insert("t_plafon_debet");

                $jml_plafon_pakai = $this->db->select("ifnull(sum(jml_debet), 0) jml_debet_plafon")->where("no_ang", $data['no_ang'])->get("t_plafon_debet")->row(0)->jml_debet_plafon;

                $this->db->set(array("plafon_pakai" => $jml_plafon_pakai))->where("no_ang", $data['no_ang'])->update("t_anggota");
            }

            $tgl_awal = $data['tgl_realisasi'];

            $xtgl_awal      = explode("-", $tgl_awal);
            $hari_tgl_awal  = $xtgl_awal[2];
            $bulan_tgl_awal = $xtgl_awal[1];
            $tahun_tgl_awal = $xtgl_awal[0];

            $tgl_angs = $data['tgl_angs'];

            $data_angsuran = $this->get_angsuran_pht($data);

            foreach ($data_angsuran as $key => $value) {
                $no_pinjam_det = $no_pinjam_realisasi . str_pad(($key + 1), 4, "0", STR_PAD_LEFT);

                $angs_ke = ($data['jns_potong_bunga'] == "POTONG") ? $data['tempo_bln'] : ($key + 1);

                $set_data1 = array(
                    "no_pinjam_det"  => $no_pinjam_det,
                    "no_pinjam"      => $no_pinjam_realisasi,
                    // "tgl_entri"      => date("Y-m-d"),
                    "blth_angsuran"  => $value['blth_angsuran'],
                    "bulan_angsuran" => $value['bulan'],
                    "tahun_angsuran" => $value['tahun'],
                    "angs_ke"        => $angs_ke,
                    "tempo_bln"      => $data['tempo_bln'],
                    "pokok_awal"     => hapus_koma($data['jml_pinjam']),
                    "pokok"          => $value['pokok_per_bulan'],
                    "bunga"          => $value['margin_per_bulan'],
                    "angsuran"       => $value['angsuran_per_bulan'],
                    "pokok_akhir"    => $value['pokok_akhir'],
                    // "sts_lunas"     => $data['sts_lunas'],
                    // "sts_potga"     => $data['sts_potga'],
                    // "blth_bayar"    => $data['blth_bayar'],
                );

                $this->db->set($set_data1)->insert("t_pinjaman_ang_det");
            }
        } else if ($data['kd_pinjaman'] == "4") {
            // if (strtotime($data['tgl_realisasi']) >= strtotime('2018-10-01')) {
            $set_debet_plafon = array(
                "no_ang"      => $data['no_ang'],
                "no_peg"      => $data['no_peg'],
                "nm_ang"      => $data['nm_ang'],
                "kd_prsh"     => $data['kd_prsh'],
                "nm_prsh"     => $data['nm_prsh'],
                "kd_dep"      => $data['kd_dep'],
                "nm_dep"      => $data['nm_dep'],
                "kd_bagian"   => $data['kd_bagian'],
                "nm_bagian"   => $data['nm_bagian'],
                "jenis_debet" => "PINJAMAN",
                "noref_penj"  => $no_pinjam_realisasi,
                "tgl_penj"    => $data['tgl_realisasi'],
                "jml_debet"   => hapus_koma($data['angsuran']),
                // "status"      => $status,
            );

            $this->db->set($set_debet_plafon)->insert("t_plafon_debet");

            $jml_plafon_pakai = $this->db->select("ifnull(sum(jml_debet), 0) jml_debet_plafon")->where("no_ang", $data['no_ang'])->get("t_plafon_debet")->row(0)->jml_debet_plafon;

            $this->db->set(array("plafon_pakai" => $jml_plafon_pakai))->where("no_ang", $data['no_ang'])->update("t_anggota");
            // }

            $tgl_awal = $data['tgl_realisasi'];

            $xtgl_awal      = strtotime($tgl_awal);
            $hari_tgl_awal  = date("d", $xtgl_awal);
            $bulan_tgl_awal = date("m", $xtgl_awal);
            $tahun_tgl_awal = date("Y", $xtgl_awal);

            $tgl_angs = $data['tgl_angs'];

            $margin = $data['margin'];

            $pokok_awal  = hapus_koma($data['jml_pinjam']);
            $pokok_akhir = hapus_koma($data['jml_pinjam']);

            $jml_margin = 0;

            $data_angsuran = $this->get_angsuran_kpr($data);

            foreach ($data_angsuran as $key => $value) {
                $no_pinjam_det = $no_pinjam_realisasi . str_pad(($key + 1), 4, "0", STR_PAD_LEFT);

                $jml_margin += $value['margin_per_bulan'];

                $set_data1 = array(
                    "no_pinjam_det"    => $no_pinjam_det,
                    "no_pinjam"        => $no_pinjam_realisasi,
                    "nm_pot_bonus"     => $value['nm_pot_bonus'],
                    "blth_angsuran"    => $value['blth_angsuran'],
                    "hari"             => $value['hari'],
                    "bulan_angsuran"   => $value['bulan'],
                    "tahun_angsuran"   => $value['tahun'],
                    "angs_ke"          => ($key + 1),
                    "tempo_bln"        => $data['tempo_bln'],
                    "pokok_awal"       => $value['pokok_awal'],
                    "pokok"            => $value['pokok_per_bulan'],
                    "bunga"            => $value['margin_per_bulan'],
                    "angsuran"         => $value['angsuran_per_bulan'],
                    "jml_potga"        => $value['jml_potga'],
                    "jml_min_angsuran" => $value['jml_min_angsuran'],
                    "jml_max_angsuran" => $value['jml_max_angsuran'],
                    "pokok_akhir"      => $value['pokok_akhir'],
                    // "sts_lunas"     => $data['sts_lunas'],
                    // "sts_potga"     => $data['sts_potga'],
                    // "blth_bayar"    => $data['blth_bayar'],
                );

                $this->db->set($set_data1)->insert("t_pinjaman_ang_det");
            }

            $set_data_margin['jml_margin'] = $jml_margin;

            $insert = $this->db->set($set_data_margin)->where("no_pinjam", $no_pinjam_realisasi)->update("t_pinjaman_ang");
        }

        return $query;
    }

    public function hapus_realisasi_pinjaman($data)
    {
        $set_data = array(
            "jml_pinjam_realisasi" => 0,
            "angsuran"             => 0,
            "is_realisasi"         => "0",
            "tgl_realisasi"        => null,
            "user_realisasi"       => null,
        );

        $update_simulasi = $this->db->set($set_data)->where("no_pinjam", $data['no_simulasi'])->update("t_simulasi_pinjaman_ang");

        $this->db->where("no_pinjam", $data['no_pinjam'])->delete("t_pinjaman_ang");
        $this->db->where("no_pinjam", $data['no_pinjam'])->delete("t_pinjaman_ang_det");

        $this->db->where("noref_penj", $data['no_pinjam'])->where("no_ang", $data['no_ang'])->delete("t_plafon_debet");

        $jml_plafon_pakai = $this->db->select("ifnull(sum(jml_debet), 0) jml_debet_plafon")->where("no_ang", $data['no_ang'])->get("t_plafon_debet")->row(0)->jml_debet_plafon;

        $this->db->set(array("plafon_pakai" => $jml_plafon_pakai))->where("no_ang", $data['no_ang'])->update("t_anggota");

        return true;
    }

    public function get_angsuran_pinjaman($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "", $no_ang = "", $sts_lunas = "", $sts_potga = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "no_pinjam_det, a.no_pinjam, b.tgl_pinjam, hari, blth_angsuran, bulan_angsuran, tahun_angsuran, angs_ke, b.tempo_bln, pokok_awal, pokok, bunga, a.angsuran, pokok_akhir, a.sts_lunas, sts_potga, blth_bayar, bukti_pelunasan, bukti_tagihan, nm_pot_bonus, b.no_ang, b.no_peg, b.nm_ang, b.kd_pinjaman, b.nm_pinjaman, b.nm_prsh, b.jml_pinjam";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("b.no_ang", "b.no_peg", "b.nm_ang");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "blth_angsuran, a.no_pinjam";

        $this->db->order_by($set_order);

        $this->db->from("t_pinjaman_ang_det a")->join("t_pinjaman_ang b", "a.no_pinjam=b.no_pinjam");

        $this->db->where("blth_angsuran <=", "2018-08")->where("a.angsuran !=", '0');

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($no_ang != "") {
            $this->db->where("b.no_ang", $no_ang);
        }

        if ($sts_lunas != "") {
            $this->db->where("a.sts_lunas", $sts_lunas);
        }

        if ($sts_potga != "") {
            $this->db->where("a.sts_potga", $sts_potga);
        }

        return $this->db->get();
    }

    public function get_bukti_pelunasan($tgl_pelunasan, $kode = "PL")
    {
        $strtime = strtotime($tgl_pelunasan);
        $tahun   = date("Y", $strtime);
        $bulan   = date("m", $strtime);

        $nomor_baru = $kode . $bulan . $tahun;

        $nomor = $this->db->select("ifnull(max(substr(bukti_lunas, -5)), 0) + 1 nomor")->like("bukti_lunas", $nomor_baru, "after")
            ->get("t_pinjaman_ang_lunas")->row(0)->nomor;

        $nomor_baru .= str_pad($nomor, "5", "0", STR_PAD_LEFT);

        if ($this->db->set("bukti_lunas", $nomor_baru)->insert("t_pinjaman_ang_lunas")) {
            return $nomor_baru;
        } else {
            return $this->get_bukti_pelunasan($tgl_pelunasan, $kode);
        }
    }

    public function pelunasan_angsuran($data)
    {
        $bukti_lunas = $this->get_bukti_pelunasan(date("Y-m-d"), $data['kode_bukti']);

        $set_data = array(
            // "bukti_lunas"     => $bukti_lunas,
            "tgl_lunas"       => date("Y-m-d"),
            // "bukti_potga"     => $bukti_potga,
            "no_pinjam_lunas" => $data['no_pinjam'],
            // "no_pinjam_baru"  => $no_pinjam_baru,
            // "status_anggota"  => $status_anggota,
            "jns_pelunasan"   => $data['jns_pelunasan'],
            "kd_pinjaman"     => $data['kd_pinjaman'],
            "nm_pinjaman"     => $data['nm_pinjaman'],
            "tempo_bln"       => $data['tempo_bln'],
            "blth_angsuran"   => $data['blth_angsuran'],
            "angsuran"        => $data['angsuran'],
            // "sisa_bln"        => $data['sisa_bln'],
            "jml_angsuran"    => $data['angsuran'],
            "jml_pokok"       => $data['pokok'],
            "jml_pokok_pdk"   => $data['pokok'],
            // "jml_pokok_pjg"   => $data['jml_pokok_pjg'],
            "jml_bunga"       => $data['bunga'],
            // "jml_pot_bunga"   => $data['jml_pot_bunga'],
            "jml_bayar"       => $data['angsuran'],
            // "jml_dibayar"     => $data['jml_dibayar'],
        );

        $set_data_detail = array(
            "sts_lunas"       => "1",
            "blth_bayar"      => date("Y-m"),
            "bukti_pelunasan" => $bukti_lunas,
        );

        $this->db->set($set_data_detail)->where("no_pinjam_det", $data['no_pinjam_det'])->update("t_pinjaman_ang_det");

        $data_angsuran_belum_lunas = $this->db->where("sts_lunas", "0")->where("no_pinjam", $data['no_pinjam'])->get("t_pinjaman_ang_det");

        if ($data_angsuran_belum_lunas->num_rows() < 1) {
            $set_data['is_proses_plafon'] = "1";

            $set_pinjaman_lunas = array(
                "sts_lunas"  => "1",
                "blth_lunas" => date("Y-m"),
            );

            $this->db->set($set_pinjaman_lunas)->where("no_pinjam", $data['no_pinjam'])->update("t_pinjaman_ang");

            $set_debet_plafon = array(
                "no_ang"      => $data['no_ang'],
                "no_peg"      => $data['no_peg'],
                "nm_ang"      => $data['nm_ang'],
                // "kd_prsh"     => $data['kd_prsh'],
                // "nm_prsh"     => $data['nm_prsh'],
                // "kd_dep"      => $data['kd_dep'],
                // "nm_dep"      => $data['nm_dep'],
                // "kd_bagian"   => $data['kd_bagian'],
                // "nm_bagian"   => $data['nm_bagian'],
                "jenis_debet" => "PINJAMAN",
                "noref_penj"  => $bukti_lunas,
                "tgl_penj"    => date("Y-m-d"),
                "jml_debet"   => hapus_koma((0 - $data['angsuran'])),
                // "status"      => $status,
            );

            $this->db->set($set_debet_plafon)->insert("t_plafon_debet");

            $jml_plafon_pakai = $this->db->select("ifnull(sum(jml_debet), 0) jml_debet_plafon")->where("no_ang", $data['no_ang'])->get("t_plafon_debet")->row(0)->jml_debet_plafon;

            $this->db->set(array("plafon_pakai" => $jml_plafon_pakai))->where("no_ang", $data['no_ang'])->update("t_anggota");
        }

        $this->db->set($set_data)->where("bukti_lunas", $bukti_lunas)->update("t_pinjaman_ang_lunas");

        return true;
    }

    public function get_pelunasan_pinjaman($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "", $jenis_pelunasan = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "bukti_lunas, tgl_lunas, bukti_potga, no_pinjam_lunas, no_pinjam_baru, status_anggota, jns_pelunasan, a.tempo_bln, blth_angsuran, a.angsuran, sisa_bln, jml_angsuran, jml_pokok, jml_pokok_pdk, jml_pokok_pjg, jml_bunga, a.jml_pot_bunga, jml_bayar, jml_dibayar, is_proses_plafon, rilis, b.no_ang, b.no_peg, b.nm_ang, b.nm_pinjaman, b.nm_prsh, b.jml_pinjam";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("b.no_ang", "b.no_peg", "b.nm_ang");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "tgl_lunas desc, bukti_lunas desc";

        $this->db->order_by($set_order);

        $this->db->from("t_pinjaman_ang_lunas a")->join("t_pinjaman_ang b", "a.no_pinjam_lunas=b.no_pinjam");

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($jenis_pelunasan != "") {
            $this->db->where("a.jns_pelunasan", $jenis_pelunasan);
        }

        return $this->db->get();
    }

    public function hapus_pelunasan_angsuran($data)
    {
        if ($data['is_proses_plafon'] == "1") {
            $this->db->where("noref_penj", $data['bukti_lunas'])->delete("t_plafon_debet");

            $jml_plafon_pakai = $this->db->select("ifnull(sum(jml_debet), 0) jml_debet_plafon")->where("no_ang", $data['no_ang'])->get("t_plafon_debet")->row(0)->jml_debet_plafon;

            $this->db->set(array("plafon_pakai" => $jml_plafon_pakai))->where("no_ang", $data['no_ang'])->update("t_anggota");
        }

        $set_pinjaman_lunas = array(
            "sts_lunas"  => "0",
            "blth_lunas" => null,
        );

        $this->db->set($set_pinjaman_lunas)->where("no_pinjam", $data['no_pinjam_lunas'])->update("t_pinjaman_ang");

        $this->db->where("bukti_lunas", $data['bukti_lunas'])->delete("t_pinjaman_ang_lunas");

        $set_data = array(
            "sts_lunas"       => "0",
            "blth_bayar"      => null,
            "bukti_pelunasan" => null,
        );

        $this->db->set($set_data)->where("bukti_pelunasan", $data['bukti_lunas'])->update("t_pinjaman_ang_det");

        return true;
    }

    public function get_pinjaman_belum_lunas($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "", $no_ang = "")
    {
        $this->db->select("COUNT(*) sisa_bln, b.blth_angsuran, b.bunga, b.angs_ke,
            if(kd_pinjaman in ('2', '4'), pokok_awal+bunga, sum(pokok+bunga)) posisi_akhir,
            a.no_pinjam, a.tgl_pinjam, a.no_ang, a.no_peg, a.kd_prsh, a.nm_prsh, a.kd_dep, a.nm_dep, a.kd_bagian, a.nm_bagian, a.kd_pinjaman, a.nm_pinjaman, a.tempo_bln, a.jml_pinjam, a.angsuran")
            ->from("t_pinjaman_ang a")->join("t_pinjaman_ang_det b", "a.no_pinjam=b.no_pinjam")
            ->where("b.sts_lunas", "0")->where("a.sts_lunas", "0")
            ->group_by("a.no_pinjam")
            ->order_by("tgl_pinjam desc, a.no_pinjam desc, b.angs_ke");

        if ($no_ang != "") {
            $this->db->where("a.no_ang", $no_ang);
        }

        $dataset = $this->db->get_compiled_select();

        $this->db->from("(" . $dataset . ") as a");

        $select = ($numrows) ? "count(*) numrows" : "no_pinjam, tgl_pinjam, kd_pinjaman, nm_pinjaman, tempo_bln, jml_pinjam, blth_angsuran, angsuran, bunga, posisi_akhir, sisa_bln";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("no_ang", "no_peg", "nm_ang");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "tgl_pinjam desc, no_pinjam desc";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $query = str_replace("`", "", $this->db->get_compiled_select());

        return $this->db->query($query);
    }

    public function proses_pelunasan_dipercepat($data)
    {
        $bukti_lunas = $this->get_bukti_pelunasan(date("Y-m-d"), $data['kode_bukti']);

        $set_data = array(
            // "bukti_lunas"     => $bukti_lunas,
            "tgl_lunas"        => date("Y-m-d"),
            // "bukti_potga"     => $bukti_potga,
            "no_pinjam_lunas"  => $data['no_pinjam'],
            // "no_pinjam_baru"  => $no_pinjam_baru,
            // "status_anggota"  => $status_anggota,
            "jns_pelunasan"    => $data['jns_pelunasan'],
            "kd_pinjaman"      => $data['kd_pinjaman'],
            "nm_pinjaman"      => $data['nm_pinjaman'],
            "tempo_bln"        => $data['tempo_bln'],
            "blth_angsuran"    => $data['blth_angsuran'],
            // "angsuran"        => $angsuran,
            // "sisa_bln"        => $sisa_bln,
            // "jml_angsuran"    => $jml_angsuran,
            // "jml_pokok"       => $jml_pokok,
            // "jml_pokok_pdk"   => $jml_pokok_pdk,
            // "jml_pokok_pjg"   => $jml_pokok_pjg,
            // "jml_bunga"       => $jml_bunga,
            // "jml_pot_bunga"   => $jml_pot_bunga,
            // "persen_denda"    => $persen_denda,
            // "jml_denda"       => $jml_denda,
            // "persen_asuransi" => $persen_asuransi,
            // "jml_asuransi"    => $jml_asuransi,
            "jml_bayar"        => hapus_koma($data['jml_bayar']),
            // "jml_dibayar"     => $jml_dibayar,
            "is_proses_plafon" => "1",
            // "rilis"           => $rilis,
            // "tgl_update"      => $tgl_update,
        );

        if ($data['kd_pinjaman'] == "3") {
            $set_data['jml_pokok']    = hapus_koma($data['jml_pokok']);
            $set_data['persen_denda'] = $data['persen_denda'];
            $set_data['jml_denda']    = hapus_koma($data['jml_denda']);
            $set_data['jml_bunga']    = hapus_koma($data['jml_bunga']);
        } else if (in_array($data['kd_pinjaman'], array("2", "4"))) {
            $set_data['jml_angsuran']    = hapus_koma($data['jml_angsuran']);
            $set_data['persen_denda']    = $data['persen_denda'];
            $set_data['jml_denda']       = hapus_koma($data['jml_denda']);
            $set_data['persen_asuransi'] = $data['persen_asuransi'];
            $set_data['jml_asuransi']    = hapus_koma($data['jml_asuransi']);
        }

        $this->db->set($set_data)->where("bukti_lunas", $bukti_lunas)->update("t_pinjaman_ang_lunas");

        $set_data_detail = array(
            "sts_lunas"       => "1",
            "blth_bayar"      => date("Y-m"),
            "bukti_pelunasan" => $bukti_lunas,
        );

        $this->db->set($set_data_detail)
            ->where("no_pinjam", $data['no_pinjam'])->where("sts_lunas", "0")
            ->update("t_pinjaman_ang_det");

        $set_pinjaman_lunas = array(
            "sts_lunas"  => "1",
            "blth_lunas" => date("Y-m"),
        );

        $this->db->set($set_pinjaman_lunas)->where("no_pinjam", $data['no_pinjam'])->update("t_pinjaman_ang");

        $set_debet_plafon = array(
            "no_ang"      => $data['no_ang'],
            "no_peg"      => $data['no_peg'],
            "nm_ang"      => $data['nm_ang'],
            // "kd_prsh"     => $data['kd_prsh'],
            // "nm_prsh"     => $data['nm_prsh'],
            // "kd_dep"      => $data['kd_dep'],
            // "nm_dep"      => $data['nm_dep'],
            // "kd_bagian"   => $data['kd_bagian'],
            // "nm_bagian"   => $data['nm_bagian'],
            "jenis_debet" => "PINJAMAN",
            "noref_penj"  => $bukti_lunas,
            "tgl_penj"    => date("Y-m-d"),
            "jml_debet"   => hapus_koma((0 - $data['angsuran'])),
            // "status"      => $status,
        );

        $this->db->set($set_debet_plafon)->insert("t_plafon_debet");

        $jml_plafon_pakai = $this->db->select("ifnull(sum(jml_debet), 0) jml_debet_plafon")->where("no_ang", $data['no_ang'])->get("t_plafon_debet")->row(0)->jml_debet_plafon;

        $this->db->set(array("plafon_pakai" => $jml_plafon_pakai))->where("no_ang", $data['no_ang'])->update("t_anggota");

        return true;
    }

    public function hapus_pelunasan_dipercepat($data)
    {
        $set_data = array(
            "sts_lunas"       => "0",
            "blth_bayar"      => null,
            "bukti_pelunasan" => null,
        );

        $this->db->set($set_data)
            ->where("bukti_pelunasan", $data['bukti_lunas'])
            ->update("t_pinjaman_ang_det");

        $set_pinjaman_lunas = array(
            "sts_lunas"  => "0",
            "blth_lunas" => null,
        );

        $this->db->set($set_pinjaman_lunas)
            ->where("no_pinjam", $data['no_pinjam_lunas'])
            ->update("t_pinjaman_ang");

        $this->db->where("bukti_lunas", $data['bukti_lunas'])->delete("t_pinjaman_ang_lunas");

        if ($data['is_proses_plafon'] == "1") {
            $this->db->where("noref_penj", $data['bukti_lunas'])->delete("t_plafon_debet");

            $jml_plafon_pakai = $this->db->select("ifnull(sum(jml_debet), 0) jml_debet_plafon")->where("no_ang", $data['no_ang'])->get("t_plafon_debet")->row(0)->jml_debet_plafon;

            $this->db->set(array("plafon_pakai" => $jml_plafon_pakai))->where("no_ang", $data['no_ang'])->update("t_anggota");
        }

        return true;
    }

}
