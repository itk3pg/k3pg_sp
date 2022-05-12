<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_model extends CI_Model
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

    public function get_tempo_bln_simpanan()
    {
        $tempo_bln = array(
            // "1"  => "1",
            "3"  => "3",
            "6"  => "6",
            "12" => "12",
			"24" => "24",
			"36" => "36",
        );

        return $tempo_bln;
    }

    public function get_jenis_pinjaman($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "id_jns_pinjaman, kd_jns_pinjaman, nm_jns_pinjaman";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("kd_jns_pinjaman", "nm_jns_pinjaman");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "kd_jns_pinjaman";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get("m_jenis_pinjaman");
    }

    public function insert_jenis_pinjaman($data)
    {
        $cek_data = $this->db->where("kd_jns_pinjaman", $data['kd_jns_pinjaman'])->get("m_jenis_pinjaman")->num_rows();

        if ($cek_data > 0) {
            $hasil['status'] = false;
            $hasil['msg']    = "Kode sudah ada";
            exit(json_encode($hasil));
        }

        $set_data = array(
            "kd_jns_pinjaman" => $data['kd_jns_pinjaman'],
            "nm_jns_pinjaman" => strtoupper($data['nm_jns_pinjaman']),
        );

        return $this->db->set($set_data)->insert("m_jenis_pinjaman");
    }

    public function update_jenis_pinjaman($data, $id)
    {
        $set_data = array(
            "kd_jns_pinjaman" => $data['kd_jns_pinjaman'],
            "nm_jns_pinjaman" => strtoupper($data['nm_jns_pinjaman']),
        );

        return $this->db->set($set_data)->where("id_jns_pinjaman", $id)->update("m_jenis_pinjaman");
    }

    public function delete_jenis_pinjaman($data)
    {
        return $this->db->where("id_jns_pinjaman", $data['id_jns_pinjaman'])->delete("m_jenis_pinjaman");
    }

    public function get_jenis_simpanan($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "", $mode_list = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "id_jns_simpanan, kd_jns_simpanan, nm_jns_simpanan";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("kd_jns_simpanan", "nm_jns_simpanan");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "kd_jns_simpanan";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($mode_list == "SUKARELA") {
            $this->db->like("nm_jns_simpanan", "sukarela");
        }

        if ($mode_list == "SUKARELA1") {
            $this->db->where("kd_jns_simpanan = '3000'");
        }

        if ($mode_list == "SUKARELA2") {
            $this->db->where("kd_jns_simpanan = '4000'");
        }

        return $this->db->get("m_jenis_simpanan");
    }

    public function insert_jenis_simpanan($data)
    {
        $cek_data = $this->db->where("kd_jns_simpanan", $data['kd_jns_simpanan'])->get("m_jenis_simpanan")->num_rows();

        if ($cek_data > 0) {
            $hasil['status'] = false;
            $hasil['msg']    = "Kode sudah ada";
            exit(json_encode($hasil));
        }

        $set_data = array(
            "kd_jns_simpanan" => $data['kd_jns_simpanan'],
            "nm_jns_simpanan" => strtoupper($data['nm_jns_simpanan']),
        );

        return $this->db->set($set_data)->insert("m_jenis_simpanan");
    }

    public function update_jenis_simpanan($data, $id)
    {
        $set_data = array(
            "kd_jns_simpanan" => $data['kd_jns_simpanan'],
            "nm_jns_simpanan" => strtoupper($data['nm_jns_simpanan']),
        );

        return $this->db->set($set_data)->where("id_jns_simpanan", $id)->update("m_jenis_simpanan");
    }

    public function delete_jenis_simpanan($data)
    {
        return $this->db->where("id_jns_simpanan", $data['id_jns_simpanan'])->delete("m_jenis_simpanan");
    }

    public function get_margin_pinjaman($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "id_rate_pinjaman, kd_jns_pinjaman, nm_jns_pinjaman, tempo_bln, bln_awal, bln_akhir, jenis_rate, rate, tgl_berlaku tgl_berlaku1, date_format(tgl_berlaku, '%d-%m-%Y') tgl_berlaku, user_input, tgl_insert, user_edit, tgl_update";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("kd_jns_pinjaman", "nm_jns_pinjaman");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "id_rate_pinjaman desc";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get("m_rate_pinjaman");
    }

    public function insert_margin_pinjaman($data)
    {
        $jenis_rate = "FLAT";

        if (in_array($data['kd_jns_pinjaman'], array("2", "4"))) {
            $jenis_rate = "ANUITAS";
        }

        $tempo_bln = isset($data['tempo_bln']) ? $data['tempo_bln'] : "";

        $set_data = array(
            // "id_rate_pinjaman" => $data['id_rate_pinjaman'],
            "kd_jns_pinjaman" => $data['kd_jns_pinjaman'],
            "nm_jns_pinjaman" => $data['nm_jns_pinjaman'],
            "tempo_bln"       => $tempo_bln,
            "bln_awal"        => $data['bln_awal'],
            "bln_akhir"       => $data['bln_akhir'],
            "jenis_rate"      => $jenis_rate,
            "rate"            => $data['rate'],
            "tgl_berlaku"     => $data['tgl_berlaku'],
            "user_input"      => $this->session->userdata("username"),
            "tgl_insert"      => date('Y-m-d'),
            // "user_edit"        => $data['user_edit'],
            // "tgl_update"       => CURRENT_TIMESTAMP,
        );

        return $this->db->set($set_data)->insert("m_rate_pinjaman");
    }

    public function update_margin_pinjaman($data, $id)
    {
        $jenis_rate = "FLAT";

        if (in_array($data['kd_jns_pinjaman'], array("2", "4"))) {
            $jenis_rate = "ANUITAS";
        }

        $tempo_bln = isset($data['tempo_bln']) ? $data['tempo_bln'] : "";

        $set_data = array(
            // "id_rate_pinjaman" => $data['id_rate_pinjaman'],
            "kd_jns_pinjaman" => $data['kd_jns_pinjaman'],
            "nm_jns_pinjaman" => $data['nm_jns_pinjaman'],
            "tempo_bln"       => $tempo_bln,
            "bln_awal"        => $data['bln_awal'],
            "bln_akhir"       => $data['bln_akhir'],
            "jenis_rate"      => $jenis_rate,
            "rate"            => $data['rate'],
            "tgl_berlaku"     => $data['tgl_berlaku'],
            // "user_input"       => $this->session->userdata("username"),
            // "tgl_insert"       => date('Y-m-d'),
            "user_edit"       => $this->session->userdata("username"),
            // "tgl_update"       => CURRENT_TIMESTAMP,
        );

        // $this->db->set($set_data)->where("kd_prsh", $id)->update("m_departemen");
        // $this->db->set($set_data)->where("kd_prsh", $id)->update("m_bagian");

        return $this->db->set($set_data)->where("id_rate_pinjaman", $id)->update("m_rate_pinjaman");
    }

    public function delete_margin_pinjaman($data)
    {
        return $this->db->where("id_rate_pinjaman", $data['id_rate_pinjaman'])->delete("m_rate_pinjaman");
    }

    public function get_margin_pinjaman_berlaku($kd_jns_pinjaman, $tempo_bln, $tgl_berlaku)
    {
        if ($kd_jns_pinjaman == "3") {
            $this->db->where("(bln_awal <= '" . $tempo_bln . "' and '" . $tempo_bln . "' <= bln_akhir)");
        } else {
            $this->db->where("tempo_bln", $tempo_bln);
        }

        $this->db->select("id_rate_pinjaman, kd_jns_pinjaman, nm_jns_pinjaman, tempo_bln, bln_awal, bln_akhir, jenis_rate, rate, tgl_berlaku, user_input, tgl_insert, user_edit, tgl_update")
            ->where("kd_jns_pinjaman", $kd_jns_pinjaman)->where("tgl_berlaku <=", $tgl_berlaku)
            ->order_by("tgl_berlaku", "desc")
            ->limit("1");

        return $this->db->get("m_rate_pinjaman");
    }

    public function get_pot_bonus_pg($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "", $is_jadwal_tetap = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "id, kd_prsh, nm_prsh, tahun, bulan, nm_pot_bonus, banyak_min_angsuran, banyak_max_angsuran";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("kd_prsh", "nm_prsh", "tahun", "bulan", "nm_pot_bonus");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "kd_prsh, tahun desc, bulan";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($is_jadwal_tetap != "") {
            $this->db->where("is_jadwal_tetap", $is_jadwal_tetap);
        }

        return $this->db->get("m_pot_bonus_pg");
    }

    public function insert_pot_bonus_pg($data)
    {
        $set_data = array(
            // "id"                  => $data['id'],
            "kd_prsh"             => $data['kd_prsh'],
            "nm_prsh"             => $data['nm_prsh'],
            "tahun"               => $data['tahun'],
            "bulan"               => $data['bulan'],
            "nm_pot_bonus"        => $this->db->escape_str(strtoupper($data['nm_pot_bonus'])),
            "banyak_min_angsuran" => $data['banyak_min_angsuran'],
            "banyak_max_angsuran" => $data['banyak_max_angsuran'],
            "is_jadwal_tetap"     => $data['is_jadwal_tetap'],
        );

        return $this->db->set($set_data)->insert("m_pot_bonus_pg");
    }

    public function update_pot_bonus_pg($data, $id)
    {
        $set_data = array(
            // "id"                  => $data['id'],
            "kd_prsh"             => $data['kd_prsh'],
            "nm_prsh"             => $data['nm_prsh'],
            "tahun"               => $data['tahun'],
            "bulan"               => $data['bulan'],
            "nm_pot_bonus"        => $this->db->escape_str(strtoupper($data['nm_pot_bonus'])),
            "banyak_min_angsuran" => $data['banyak_min_angsuran'],
            "banyak_max_angsuran" => $data['banyak_max_angsuran'],
            "is_jadwal_tetap"     => $data['is_jadwal_tetap'],
        );

        return $this->db->set($set_data)->where("id", $id)->update("m_pot_bonus_pg");
    }

    public function delete_pot_bonus_pg($data)
    {
        return $this->db->where("id", $data['id'])->delete("m_pot_bonus_pg");
    }

    public function get_pot_bonus_pg_berlaku($tahun, $bulan, $kd_prsh = "")
    {
        if ($kd_prsh) {
            $this->db->where("kd_prsh", $kd_prsh);
        }

        $this->db->select("kd_prsh, nm_prsh, nm_pot_bonus, tahun, bulan, sum(banyak_min_angsuran) banyak_min_angsuran, sum(banyak_max_angsuran) banyak_max_angsuran")
        // ->where("tahun", $tahun)
            ->where("bulan", $bulan)
            ->where("is_jadwal_tetap", "1")
            ->group_by("bulan");

        return $this->db->get("m_pot_bonus_pg");
    }

    public function get_margin_simpanan($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "id_rate_simpanan, kd_jns_simpanan, nm_jns_simpanan, tempo_bln, jenis_rate, min_simpan, max_simpan, rate, tgl_berlaku tgl_berlaku1, date_format(tgl_berlaku, '%d-%m-%Y') tgl_berlaku, user_input, tgl_insert, user_edit, tgl_update";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("kd_jns_simpanan", "nm_jns_simpanan");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "id_rate_simpanan desc";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get("m_rate_simpanan");
    }

    public function insert_margin_simpanan($data)
    {
        $arrJnsSimpanan = array("3000" => "SIMPANAN SUKARELA 1", "4000" => "SIMPANAN SUKARELA 2");

        $set_data = array(
            "kd_jns_simpanan" => $data['kd_jns_simpanan'],
            "nm_jns_simpanan" => $arrJnsSimpanan[$data['kd_jns_simpanan']],
            "tempo_bln"       => in_array($data['kd_jns_simpanan'], array("3000", "5000")) ? 0 : $data['tempo_bln'],
            "min_simpan"      => $data['kd_jns_simpanan'] == "4000" ? hapus_koma($data['min_simpan']) : 0,
            "max_simpan"      => $data['kd_jns_simpanan'] == "4000" ? hapus_koma($data['max_simpan']) : 0,
            "rate"            => $data['rate'],
            "tgl_berlaku"     => $data['tgl_berlaku'],
            "user_input"      => $this->session->userdata("username"),
            "tgl_insert"      => date('Y-m-d'),
        );

        // if (in_array($data['kd_jns_simpanan'], array("3000", "5000"))) {
        //     $set_data['tempo_bln'] = "0";
        // }

        return $this->db->set($set_data)->insert("m_rate_simpanan");
    }

    public function update_margin_simpanan($data, $id)
    {
        $tempo_bln = isset($data['tempo_bln']) ? $data['tempo_bln'] : "";

        $set_data = array(
            "kd_jns_simpanan" => $data['kd_jns_simpanan'],
            "nm_jns_simpanan" => $data['nm_jns_simpanan'],
            "tempo_bln"       => $tempo_bln,
            "min_simpan"      => $data['min_simpan'],
            "max_simpan"      => $data['max_simpan'],
            "rate"            => $data['rate'],
            "tgl_berlaku"     => $data['tgl_berlaku'],
            "user_edit"       => $this->session->userdata("username"),
        );

        return $this->db->set($set_data)->where("id_rate_simpanan", $id)->update("m_rate_simpanan");
    }

    public function delete_margin_simpanan($data)
    {
        return $this->db->where("id_rate_simpanan", $data['id_rate_simpanan'])->delete("m_rate_simpanan");
    }

    public function get_margin_simpanan_berlaku($kd_jns_simpanan, $tempo_bln = "", $tgl_berlaku, $jumlah = 0)
    {
        if (!in_array($kd_jns_simpanan, array("3000", "5000"))) {
            $this->db->where("tempo_bln", $tempo_bln);
        }

        /*mulai tanggal 10 juni 2019 cari bunga sesuai jumlah simpanan */
        if (strtotime($tgl_berlaku) >= strtotime("2019-06-10") and $kd_jns_simpanan == "4000") {
            $this->db->where("('" . $jumlah . "' between min_simpan and max_simpan)");
        }

        $this->db->select("id_rate_simpanan, kd_jns_simpanan, nm_jns_simpanan, tempo_bln, jenis_rate, min_simpan, max_simpan, rate, tgl_berlaku")
            ->where("kd_jns_simpanan", $kd_jns_simpanan)->where("tgl_berlaku <=", $tgl_berlaku)
            ->order_by("tgl_berlaku", "desc")
            ->limit("1");

        // echo $this->db->get_compiled_select("m_rate_simpanan");
        return $this->db->get("m_rate_simpanan");
    }

    public function get_jenis_transaksi_simpanan($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "", $kd_jns_simpanan = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "id_jns_simpanan, kd_jns_simpanan, nm_jns_simpanan, kd_jns_transaksi, nm_jns_transaksi, kredit_debet";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("kd_jns_simpanan", "nm_jns_simpanan", "kd_jns_transaksi", "nm_jns_transaksi");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "id_jns_simpanan desc";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($kd_jns_simpanan != "") {
            $this->db->where("kd_jns_simpanan", $kd_jns_simpanan);
        }

        return $this->db->get("m_jns_transaksi_simpanan");
    }

    public function insert_jenis_transaksi_simpanan($data)
    {
        $set_data = array(
            "kd_jns_simpanan"  => $data['kd_jns_simpanan'],
            "nm_jns_simpanan"  => $data['nm_jns_simpanan'],
            "kd_jns_transaksi" => $data['kd_jns_transaksi'],
            "nm_jns_transaksi" => strtoupper($data['nm_jns_transaksi']),
            "kredit_debet"     => $data['kredit_debet'],
        );

        return $this->db->set($set_data)->insert("m_jns_transaksi_simpanan");
    }

    public function update_jenis_transaksi_simpanan($data, $id)
    {
        $set_data = array(
            "kd_jns_simpanan"  => $data['kd_jns_simpanan'],
            "nm_jns_simpanan"  => $data['nm_jns_simpanan'],
            "kd_jns_transaksi" => $data['kd_jns_transaksi'],
            "nm_jns_transaksi" => strtoupper($data['nm_jns_transaksi']),
            "kredit_debet"     => $data['kredit_debet'],
        );

        return $this->db->set($set_data)->where("id_jns_simpanan", $id)->update("m_jns_transaksi_simpanan");
    }

    public function delete_jenis_transaksi_simpanan($data)
    {
        return $this->db->where("id_jns_simpanan", $data['id_jns_simpanan'])->delete("m_jns_transaksi_simpanan");
    }

    public function get_potga_ss1($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "id, no_ang, no_peg, nm_ang, kd_prsh, nm_prsh, kd_dep, nm_dep, kd_bagian, nm_bagian, jumlah, tahun, bulan";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("no_ang", "no_peg", "nm_ang");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "id desc";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get("m_potga_ss1");
    }

    public function insert_potga_ss1($data)
    {
        $tgl_masuk_ss1 = date("Y-m-01", mktime(0, 0, 0, $data['bulan'] + 1, 1, $data['tahun']));

        $set_data = array(
            // "id"        => $id,
            "no_ang"        => $data['no_ang'],
            "no_peg"        => $data['no_peg'],
            "nm_ang"        => $data['nm_ang'],
            "kd_prsh"       => $data['kd_prsh'],
            "nm_prsh"       => $data['nm_prsh'],
            "kd_dep"        => $data['kd_dep'],
            "nm_dep"        => $data['nm_dep'],
            "kd_bagian"     => $data['kd_bagian'],
            "nm_bagian"     => $data['nm_bagian'],
            "jumlah"        => hapus_koma($data['jumlah']),
            "tahun"         => $data['tahun'],
            "bulan"         => $data['bulan'],
            "tgl_masuk_ss1" => $tgl_masuk_ss1,
        );

        return $this->db->set($set_data)->insert("m_potga_ss1");
    }

    public function update_potga_ss1($data, $id)
    {
        $tgl_masuk_ss1 = date("Y-m-01", mktime(0, 0, 0, $data['bulan'] + 1, 1, $data['tahun']));

        $set_data = array(
            // "id"        => $id,
            "no_ang"        => $data['no_ang'],
            "no_peg"        => $data['no_peg'],
            "nm_ang"        => $data['nm_ang'],
            "kd_prsh"       => $data['kd_prsh'],
            "nm_prsh"       => $data['nm_prsh'],
            "kd_dep"        => $data['kd_dep'],
            "nm_dep"        => $data['nm_dep'],
            "kd_bagian"     => $data['kd_bagian'],
            "nm_bagian"     => $data['nm_bagian'],
            "jumlah"        => hapus_koma($data['jumlah']),
            "tahun"         => $data['tahun'],
            "bulan"         => $data['bulan'],
            "tgl_masuk_ss1" => $tgl_masuk_ss1,
        );

        return $this->db->set($set_data)->where("id", $id)->update("m_potga_ss1");
    }

    public function delete_potga_ss1($data)
    {
        return $this->db->where("id", $data['id'])->delete("m_potga_ss1");
    }

    public function get_perusahaan($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "(@nomor:=@nomor+1) nomor, kd_prsh, nm_prsh";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("kd_prsh", "nm_prsh");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "kd_prsh desc";

        $this->db->order_by($set_order);

        if (!$offset) {
            $offset = 0;
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get("m_perusahaan, (select @nomor:=" . $offset . ") z");
    }

    public function get_akun($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "(@nomor:=@nomor+1) nomor, kd_akun id, nm_akun text, kd_akun, nm_akun";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("kd_akun", "nm_akun");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "kd_akun desc";

        $this->db->order_by($set_order);

        if (!$offset) {
            $offset = 0;
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get("m_akun, (select @nomor:=" . $offset . ") z");
    }

    public function insert_akun($data)
    {
        $data_akun = $this->db->where("kd_akun", $data['kd_akun'])->get("m_akun");

        if ($data_akun->num_rows() > 0) {
            $hasil['status'] = false;
            $hasil['msg']    = "Kode Sudah Ada";

            exit(json_encode($hasil));
        }

        $set_data = array(
            "kd_akun" => $data['kd_akun'],
            "nm_akun" => $data['nm_akun'],
        );

        return $this->db->set($set_data)->insert("m_akun");
    }

    public function update_akun($data, $id)
    {
        $set_data = array(
            // "kd_akun" => $data['kd_akun'],
            "nm_akun" => $data['nm_akun'],
        );

        return $this->db->set($set_data)->where("kd_akun", $id)->update("m_akun");
    }

    public function delete_akun($data)
    {
        return $this->db->where("kd_akun", $data['kd_akun'])->delete("m_akun");
    }

    public function get_kasbank($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "(@nomor:=@nomor+1) nomor, kd_kasbank id, nm_kasbank text, kd_kasbank, nm_kasbank";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("kd_kasbank", "nm_kasbank");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "kd_kasbank desc";

        $this->db->order_by($set_order);

        if (!$offset) {
            $offset = 0;
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get("m_kasbank, (select @nomor:=" . $offset . ") z");
    }

    public function insert_kasbank($data)
    {
        $data_kasbank = $this->db->where("kd_kasbank", $data['kd_kasbank'])->get("m_kasbank");

        if ($data_kasbank->num_rows() > 0) {
            $hasil['status'] = false;
            $hasil['msg']    = "Kode Sudah Ada";

            exit(json_encode($hasil));
        }

        $set_data = array(
            "kd_kasbank" => $data['kd_kasbank'],
            "nm_kasbank" => $data['nm_kasbank'],
        );

        return $this->db->set($set_data)->insert("m_kasbank");
    }

    public function update_kasbank($data, $id)
    {
        $set_data = array(
            // "kd_kasbank" => $data['kd_kasbank'],
            "nm_kasbank" => $data['nm_kasbank'],
        );

        return $this->db->set($set_data)->where("kd_kasbank", $id)->update("m_kasbank");
    }

    public function delete_kasbank($data)
    {
        return $this->db->where("kd_kasbank", $data['kd_kasbank'])->delete("m_kasbank");
    }

}
