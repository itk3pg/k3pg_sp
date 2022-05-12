<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Anggota_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_anggota($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "", $status_keluar = "", $no_ang = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "id_ang, no_ang, no_peg, no_peglm, sts_instansi, nm_ang, jns_kel, kt_lhr, tgl_lhr tgl_lhr1, date_format(tgl_lhr, '%d-%m-%Y') tgl_lhr, nm_ibukdg, nm_psg, no_ktp, no_npwp, alm_rmh, tlp_hp, kd_prsh, nm_prsh, kd_dep, nm_dep, kd_bagian, nm_bagian, tlp_kntr, sts_pindah, ket_pindah, kd_prsh_pindah, nm_prsh_pindah, kd_dep_pindah, nm_dep_pindah, kd_bagian_pindah, nm_bagian_pindah, kd_klp_pindah, tgl_msk tgl_msk1, date_format(tgl_msk, '%d-%m-%Y') tgl_msk, id_klp, kd_klp, sts_ketua, gaji, plafon_persen, plafon, plafon_pakai, (plafon - plafon_pakai) sisa_plafon, status_keluar, tgl_keluar tgl_keluar1, date_format(tgl_keluar, '%d-%m-%Y') tgl_keluar, id_alasan_keluar, alasan_keluar, ket_keluar, file_ktp";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("no_ang", "no_peg", "nm_ang");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        if ($status_keluar != "") {
            $this->db->where("status_keluar", $status_keluar);
        }

        if ($no_ang != "") {
            $this->db->where("no_ang", $no_ang);
        }

        $set_order = ($order) ? $order : "id_ang desc";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get("t_anggota");
    }

    public function insert_anggota_masuk($data)
    {
        $id_ang = get_maxid("t_anggota", "id_ang");

        $set_data = array(
            "id_ang"    => $id_ang,
            "no_ang"    => strtoupper($data['no_ang']),
            "no_peg"    => strtoupper($data['no_peg']),
            "nm_ang"    => $this->db->escape_str(strtoupper($data['nm_ang'])),
            "jns_kel"   => $data['jns_kel'],
            "kt_lhr"    => $data['kt_lhr'],
            "tgl_lhr"   => $data['tgl_lhr'],
            "nm_ibukdg" => $this->db->escape_str(strtoupper($data['nm_ibukdg'])),
            "nm_psg"    => $this->db->escape_str(strtoupper($data['nm_psg'])),
            "no_ktp"    => $data['no_ktp'],
            "alm_rmh"   => $this->db->escape_str(strtoupper($data['alm_rmh'])),
            "tlp_hp"    => $data['tlp_hp'],
            "kd_prsh"   => $data['kd_prsh'],
            "nm_prsh"   => $data['nm_prsh'],
            "kd_dep"    => $data['kd_dep'],
            "nm_dep"    => $data['nm_dep'],
            "kd_bagian" => $data['kd_bagian'],
            "nm_bagian" => $data['nm_bagian'],
            "tgl_msk"   => $data['tgl_msk'],
            // "kd_klp"    => $data['kd_klp'],
            "gaji"      => hapus_koma($data['gaji']),
            "plafon"    => hapus_koma($data['plafon']),
        );

        $query_insert = $this->db->set($set_data)->insert("t_anggota");
        $query_insert = $this->db->set($set_data)->insert("t_anggota_masuk");

        $xtgl  = explode("-", $data['tgl_msk']);
        $tahun = $xtgl[0];
        $bulan = $xtgl[1];

        $this->update_mutasi_anggota($tahun, $bulan, $data['kd_prsh']);

        return $query_insert;
    }

    public function insert_anggota_pindah($data)
    {
        $set_data = array(
            "kd_prsh"   => $data['kd_prsh_baru'],
            "nm_prsh"   => $data['nm_prsh_baru'],
            "kd_dep"    => $data['kd_dep_baru'],
            "nm_dep"    => $data['nm_dep_baru'],
            "kd_bagian" => $data['kd_bagian_baru'],
            "nm_bagian" => $data['nm_bagian_baru'],
        );

        $this->db->set($set_data)->where("no_ang", $data['no_ang'])->update("t_anggota");

        $set_data1 = array(
            // "id_ang"         => $data['id_ang']
            "no_ang"         => $data['no_ang'],
            "no_peg"         => $data['no_peg'],
            "nm_ang"         => $data['nm_ang'],
            "kd_prsh_lama"   => $data['kd_prsh'],
            "nm_prsh_lama"   => $data['nm_prsh'],
            "kd_dep_lama"    => $data['kd_dep'],
            "nm_dep_lama"    => $data['nm_dep'],
            "kd_bagian_lama" => $data['kd_bagian'],
            "nm_bagian_lama" => $data['nm_bagian'],
            "kd_prsh_baru"   => $data['kd_prsh_baru'],
            "nm_prsh_baru"   => $data['nm_prsh_baru'],
            "kd_dep_baru"    => $data['kd_dep_baru'],
            "nm_dep_baru"    => $data['nm_dep_baru'],
            "kd_bagian_baru" => $data['kd_bagian_baru'],
            "nm_bagian_baru" => $data['nm_bagian_baru'],
            "tgl_pindah"     => $data['tgl_pindah'],
            "sts_pindah"     => "1",
            "ket_pindah"     => strtoupper($data['ket_pindah']),
        );

        $query = $this->db->set($set_data1)->insert("t_anggota_pindah");

        $xtgl  = explode("-", $data['tgl_pindah']);
        $tahun = $xtgl[0];
        $bulan = $xtgl[1];

        $this->update_mutasi_anggota($tahun, $bulan, $data['kd_prsh']);
        $this->update_mutasi_anggota($tahun, $bulan, $data['kd_prsh_baru']);

        return $query;
    }

    public function get_anggota_pindah($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "id_pindah, no_ang, no_peg, nm_ang, kd_prsh_lama, nm_prsh_lama, kd_dep_lama, nm_dep_lama, kd_bagian_lama, nm_bagian_lama, kd_prsh_baru, nm_prsh_baru, kd_dep_baru, nm_dep_baru, kd_bagian_baru, nm_bagian_baru, tgl_pindah, sts_pindah, ket_pindah, tgl_update";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("no_ang", "no_peg", "nm_ang");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "id_pindah desc";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get("t_anggota_pindah");
    }

    public function hapus_anggota_pindah($data)
    {
        $cari['field'][] = "id_pindah";
        $cari['value']   = $data['id_pindah'];

        $data_sebelumnya = $this->get_anggota_pindah(0, $cari)->row_array();

        $set_data = array(
            "kd_prsh"   => $data['kd_prsh_lama'],
            "nm_prsh"   => $data['nm_prsh_lama'],
            "kd_dep"    => $data['kd_dep_lama'],
            "nm_dep"    => $data['nm_dep_lama'],
            "kd_bagian" => $data['kd_bagian_lama'],
            "nm_bagian" => $data['nm_bagian_lama'],
        );

        $this->db->set($set_data)->where("no_ang", $data_sebelumnya['no_ang'])->update("t_anggota");

        $query = $this->db->where("id_pindah", $data['id_pindah'])->delete("t_anggota_pindah");

        $xtgl  = explode("-", $data['tgl_pindah']);
        $tahun = $xtgl[0];
        $bulan = $xtgl[1];

        $this->update_mutasi_anggota($tahun, $bulan, $data['kd_prsh_lama']);
        $this->update_mutasi_anggota($tahun, $bulan, $data['kd_prsh_baru']);

        return $query;
    }

    public function insert_anggota_keluar($data)
    {
        $set_data = array(
            "status_keluar" => "1",
            "tgl_keluar"    => $data['tgl_keluar'],
            // "id_alasan_keluar" => $data['id_alasan_keluar'],
            // "alasan_keluar"    => $data['alasan_keluar'],
            "ket_keluar"    => $this->db->escape_str(strtoupper($data['ket_keluar'])),
        );

        $this->db->set($set_data)->where("no_ang", $data['no_ang'])->update("t_anggota");

        $set_data1 = array(
            // "id_ang"     => $data['id_ang'],
            "no_ang"     => $data['no_ang'],
            "no_peg"     => $data['no_peg'],
            "nm_ang"     => $data['nm_ang'],
            "kd_prsh"    => $data['kd_prsh'],
            "nm_prsh"    => $data['nm_prsh'],
            "kd_dep"     => $data['kd_dep'],
            "nm_dep"     => $data['nm_dep'],
            "kd_bagian"  => $data['kd_bagian'],
            "nm_bagian"  => $data['nm_bagian'],
            "tgl_keluar" => $data['tgl_keluar'],
            // "kd_alasan"  => $data['kd_alasan'],
            // "nm_alasan"  => $data['nm_alasan'],
            "ket_keluar" => $this->db->escape_str(strtoupper($data['ket_keluar'])),
        );

        $query = $this->db->set($set_data1)->insert("t_anggota_keluar");

        $xtgl  = explode("-", $data['tgl_keluar']);
        $tahun = $xtgl[0];
        $bulan = $xtgl[1];

        $this->update_mutasi_anggota($tahun, $bulan, $data['kd_prsh']);

        return $query;
    }

    public function get_anggota_keluar($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "id_keluar, no_ang, no_peg, nm_ang, kd_prsh, nm_prsh, kd_dep, nm_dep, kd_bagian, nm_bagian, tgl_keluar, kd_alasan, nm_alasan, ket_keluar, tgl_update";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("no_ang", "no_peg", "nm_ang");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "id_keluar desc";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get("t_anggota_keluar");
    }

    public function hapus_anggota_keluar($data)
    {
        $set_data = array(
            "status_keluar" => "0",
            "tgl_keluar"    => null,
            "ket_keluar"    => null,
        );

        $this->db->set($set_data)->where("no_ang", $data['no_ang'])->update("t_anggota");

        $query = $this->db->where("id_keluar", $data['id_keluar'])->delete("t_anggota_keluar");

        $xtgl  = explode("-", $data['tgl_keluar']);
        $tahun = $xtgl[0];
        $bulan = $xtgl[1];

        $this->update_mutasi_anggota($tahun, $bulan, $data['kd_prsh']);

        return $query;
    }

    public function update_data_anggota($data, $id)
    {
        $set_data = array(
            // "id_ang"    => $id_ang,
            "no_ang"    => strtoupper($data['no_ang']),
            // "no_peg"    => strtoupper($data['no_peg']),
            "nm_ang"    => $this->db->escape_str(strtoupper($data['nm_ang'])),
            // "jns_kel"   => $data['jns_kel'],
            "kt_lhr"    => $data['kt_lhr'],
            "tgl_lhr"   => $data['tgl_lhr'],
            "nm_ibukdg" => $this->db->escape_str(strtoupper($data['nm_ibukdg'])),
            "nm_psg"    => $this->db->escape_str(strtoupper($data['nm_psg'])),
            "no_ktp"    => $data['no_ktp'],
            "alm_rmh"   => $this->db->escape_str(strtoupper($data['alm_rmh'])),
            "tlp_hp"    => $data['tlp_hp'],
            // "kd_prsh"   => $data['kd_prsh'],
            // "nm_prsh"   => $data['nm_prsh'],
            // "kd_dep"    => $data['kd_dep'],
            // "nm_dep"    => $data['nm_dep'],
            // "kd_bagian" => $data['kd_bagian'],
            // "nm_bagian" => $data['nm_bagian'],
            // "tgl_msk"   => $data['tgl_msk'],
            // "kd_klp"    => $data['kd_klp'],
            "gaji"      => hapus_koma($data['gaji']),
            "plafon"    => hapus_koma($data['plafon']),
        );

        if (isset($data['jns_kel'])) {
            $set_data['jns_kel'] = $data['jns_kel'];
        }

        return $this->db->set($set_data)->where("id_ang", $id)->update("t_anggota");
    }

    public function update_mutasi_anggota($tahun, $bulan, $kd_prsh)
    {
        $this->load->model("master_model");

        $cr['field'][] = "kd_prsh";
        $cr['value']   = $kd_prsh;

        $data_prsh = $this->master_model->get_perusahaan(0, $cr)->row_array(0);

        $nm_prsh = $data_prsh['nm_prsh'];

        $blth = $tahun . "-" . $bulan;

        $this->db->where("blth", $blth)->where("kd_prsh", $kd_prsh)->delete("t_mut_anggota");

        $from_union = "
            select '" . $blth . "' blth, \"" . $kd_prsh . "\" kd_prsh, \"" . $nm_prsh . "\" nm_prsh, sum(masuk), sum(keluar), sum(masuk-keluar)
            from
            (
                SELECT kd_prsh, nm_prsh, COUNT(*) masuk, 0 keluar
                FROM t_anggota
                WHERE tgl_msk LIKE '" . $tahun . "-" . $bulan . "%' and kd_prsh = '" . $kd_prsh . "'
                UNION
                SELECT kd_prsh_lama, nm_prsh_lama, 0 masuk, count(*) keluar
                FROM t_anggota_pindah
                WHERE tgl_pindah LIKE '" . $tahun . "-" . $bulan . "%' and kd_prsh_lama = '" . $kd_prsh . "'
                UNION
                SELECT kd_prsh_lama, nm_prsh_lama, count(*) masuk, 0 keluar
                FROM t_anggota_pindah
                WHERE tgl_pindah LIKE '" . $tahun . "-" . $bulan . "%' and kd_prsh_lama = '" . $kd_prsh . "'
                UNION
                SELECT kd_prsh, nm_prsh, 0, COUNT(*)
                FROM t_anggota_keluar
                WHERE tgl_keluar LIKE '" . $tahun . "-" . $bulan . "%' AND kd_prsh = '" . $kd_prsh . "'
            ) tabel
            group by kd_prsh";

        $query_insert = "insert into t_mut_anggota
            (blth, kd_prsh, nm_prsh, masuk, keluar, saldo_akhir)
            " . $from_union;

        $this->db->query($query_insert);

        $this->db->where("masuk", 0)->where("keluar", 0)->where("saldo_akhir", 0)->delete("t_mut_anggota");
    }

    public function get_nasabah($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "", $status_keluar = "", $no_ang = "", $no_ang_induk = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "id_ang, no_ang, no_ang_induk, kd_ang, no_peg, no_peglm, sts_instansi, nm_ang, jns_kel, kt_lhr, tgl_lhr tgl_lhr1, date_format(tgl_lhr, '%d-%m-%Y') tgl_lhr, nm_ibukdg, nm_psg, no_ktp, no_npwp, alm_rmh, tlp_hp, kd_prsh, nm_prsh, kd_dep, nm_dep, kd_bagian, nm_bagian, tlp_kntr, sts_pindah, ket_pindah, kd_prsh_pindah, nm_prsh_pindah, kd_dep_pindah, nm_dep_pindah, kd_bagian_pindah, nm_bagian_pindah, kd_klp_pindah, tgl_msk tgl_msk1, date_format(tgl_msk, '%d-%m-%Y') tgl_msk, id_klp, kd_klp, sts_ketua, gaji, plafon_persen, plafon, plafon_pakai, (plafon - plafon_pakai) sisa_plafon, status_keluar, tgl_keluar tgl_keluar1, date_format(tgl_keluar, '%d-%m-%Y') tgl_keluar, id_alasan_keluar, alasan_keluar, ket_keluar, file_ktp";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("no_ang", "no_peg", "nm_ang");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        if ($status_keluar != "") {
            $this->db->where("status_keluar", $status_keluar);
        }

        if ($no_ang != "") {
            $this->db->where("no_ang", $no_ang);
        }

        if ($no_ang_induk != "") {
            $this->db->where("no_ang_induk", $no_ang_induk);
        }

        $set_order = ($order) ? $order : "id_ang desc";

        $this->db->order_by($set_order);

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get("t_nasabah");
    }

    public function insert_update_nasabah($data)
    {
        $set_data = array(
            "no_ang"    => strtoupper($data['no_ang']),
            "no_peg"    => $data['no_peg'],
            "nm_ang"    => $this->db->escape_str(strtoupper($data['nm_ang'])),
            "kd_prsh"   => $data['kd_prsh'],
            "nm_prsh"   => $data['nm_prsh'],
            "kd_dep"    => $data['kd_dep'],
            "nm_dep"    => $data['nm_dep'],
            "kd_bagian" => $data['kd_bagian'],
            "nm_bagian" => $data['nm_bagian'],
        );

        $data_nasabah = $this->db->where("no_ang", $data['no_ang'])->get("t_nasabah");

        if ($data_nasabah->num_rows() > 0) {
            $this->db->set($set_data)->where("no_ang", $data['no_ang'])->update("t_nasabah");
        } else {
            $this->db->set($set_data)->insert("t_nasabah");
        }

        for ($i = 0; $i < sizeof($data['kd_ang']); $i++) {
            if ($data['nm_nasabah'][$i] != "") {
                $set_data = array(
                    "no_ang"       => (strtoupper($data['no_ang']) . $data['kd_ang'][$i]),
                    "no_ang_induk" => $data['no_ang'],
                    "kd_ang"       => $data['kd_ang'][$i],
                    "nm_ang"       => $this->db->escape_str(strtoupper($data['nm_nasabah'][$i])),
                );

                $data_nasabah = $this->db->where("no_ang", ($data['no_ang'] . $data['kd_ang'][$i]))->get("t_nasabah");

                if ($data_nasabah->num_rows() > 0) {
                    $this->db->set($set_data)->where("no_ang", ($data['no_ang'] . $data['kd_ang'][$i]))->update("t_nasabah");
                } else {
                    $this->db->set($set_data)->insert("t_nasabah");
                }
            }
        }

        return true;
    }

    public function insert_nasabah($data)
    {
        $ada_nasabah = $this->db->where("no_ang", $data['no_ang'])->get("t_nasabah")->num_rows();

        if ($ada_nasabah > 0) {
            $hasil['status'] = false;
            $hasil['msg']    = "NAK sudah terdaftar";

            echo json_encode($hasil);
            exit();
        }

        $set_data = array(
            "no_ang"    => $data['no_ang'],
            "nm_ang"    => $data['nm_ang'],
            "alm_rmh"   => $data['alm_rmh'],
            "tlp_hp"    => $data['tlp_hp'],
            "no_peg"    => $data['no_peg'],
            "nm_dep"    => $data['nm_dep'],
            "nm_bagian" => $data['nm_bagian'],
        );

        return $this->db->set($set_data)->insert("t_nasabah");
    }

    public function update_nasabah($data, $id)
    {
        $data_nasabah_sebelumnya = $this->db->where("id_ang", $id)->get("t_nasabah")->row_array(0);

        if ($data_nasabah_sebelumnya['no_ang'] != $data['no_ang']) {
            $ada_nasabah = $this->db->where("no_ang", $data['no_ang'])->get("t_nasabah");

            if ($ada_nasabah->num_rows() > 0) {
                $hasil['status'] = false;
                $hasil['msg']    = "NAK sudah terdaftar";

                echo json_encode($hasil);
                exit();
            }
        }

        $set_data = array(
            "no_ang"    => $data['no_ang'],
            "nm_ang"    => $data['nm_ang'],
            "alm_rmh"   => $data['alm_rmh'],
            "tlp_hp"    => $data['tlp_hp'],
            "no_peg"    => $data['no_peg'],
            "nm_dep"    => $data['nm_dep'],
            "nm_bagian" => $data['nm_bagian'],
        );

        return $this->db->set($set_data)->where("id_ang", $id)->update("t_nasabah");
    }

    public function delete_nasabah($data)
    {
        return $this->db->where("no_ang", $data['no_ang'])->delete("t_nasabah");
    }

}
