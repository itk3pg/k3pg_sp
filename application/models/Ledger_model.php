<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ledger_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_ledger_grup_by_no_ref($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "", $no_ref_bukti = "", $tahun = "", $bulan = "")
    {
        // exit(baca_array($cari));

        $select = "bukti_gl, date_format(tgl_gl, '%d-%m-%Y') tgl_gl, tgl_gl tgl_gl1, no_ref_bukti, kd_akun, nm_akun, kredit_debet,
            round(sum(if(kredit_debet = 'D', jumlah, 0)), 2) debet,
            round(sum(if(kredit_debet = 'K', jumlah, 0)), 2) kredit,
            if(round(sum(if(kredit_debet = 'D', jumlah, 0)), 2) = round(sum(if(kredit_debet = 'K', jumlah, 0)), 2), 'Balance', 'Tidak Balance') status_balance,
            jumlah, ket";

        $this->db->select($select);

        $this->db->group_by("no_ref_bukti");

        $set_order = ($order) ? $order : "tgl_gl1 desc, no_ref_bukti desc";

        $this->db->order_by($set_order);

        if ($no_ref_bukti != "") {
            $this->db->where("no_ref_bukti", $no_ref_bukti);
        }

        if ($tahun != "" and $bulan != "") {
            $this->db->like("tgl_gl", ($tahun . "-" . $bulan), "after");
        }

        $query_header = $this->db->get_compiled_select("t_general_ledger");

        // exit($query_header);

        $query_header = str_replace("`", "", $query_header);

        $select = ($numrows) ? "ifnull(count(*), 0) numrows" : "(@nomor:=@nomor+1) nomor, a.*";

        $this->db->select($select);

        if (!$offset) {
            $offset = 0;
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("no_ref_bukti");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $this->db->escape_str($cari['value']));
            }

            $this->db->group_end();
        }

        // echo $this->db->get_compiled_select("(" . $query_header . ") a, (select @nomor:=" . $offset . ") z ");
        return $this->db->get("(" . $query_header . ") a, (select @nomor:=" . $offset . ") z ");
    }

    public function get_detail_ledger_by_no_ref($numrows = 0, $cari = "", $order = "", $offset = "", $limit = "", $no_ref_bukti = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "(@nomor:=@nomor+1) nomor, bukti_gl, tgl_gl, no_ref_bukti, kd_kasbank, nm_kasbank, kd_akun, nm_akun, kredit_debet, if(kredit_debet = 'D', jumlah, 0) debet, if(kredit_debet = 'K', jumlah, 0) kredit, jumlah, ket";

        $this->db->select($select);

        if (is_array($cari) and $cari['value'] != "") {
            $set_cari = isset($cari['field'][0]) ? $cari['field'] : array("kd_akun", "nm_akun");

            $this->db->group_start();

            foreach ($set_cari as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        $set_order = ($order) ? $order : "bukti_gl desc";

        $this->db->order_by($set_order);

        if (!$offset) {
            $offset = 0;
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($no_ref_bukti) {
            $this->db->where("no_ref_bukti", $no_ref_bukti);
        }

        return $this->db->get("t_general_ledger, (select @nomor:=" . $offset . ") z");
    }

    public function get_jml_debet_kredit($no_ref_bukti)
    {
        $data = $this->db->select("ifnull(sum(if(kredit_debet = 'D', jumlah, 0)), 0) debet, ifnull(sum(if(kredit_debet = 'K', jumlah, 0)), 0) kredit, if(ifnull(sum(if(kredit_debet = 'D', jumlah, 0)), 0) = ifnull(sum(if(kredit_debet = 'K', jumlah, 0)), 0), 'Balance', 'Tidak Balance') status_balance")
            ->where("no_ref_bukti", $no_ref_bukti)
            ->get("t_general_ledger");

        return $data;
    }

    public function get_bukti_gl($tgl)
    {
        $xtgl  = explode("-", $tgl);
        $bulan = $xtgl[1];
        $tahun = $xtgl[0];

        $bukti_baru = "GL" . $bulan . $tahun;

        $nomor = $this->db->select("ifnull(max(substr(bukti_gl, -5)), 0)+1 nomor")->like("bukti_gl", $bukti_baru, "after")->get("t_general_ledger")->row()->nomor;

        $bukti_baru .= str_pad($nomor, 5, "0", STR_PAD_LEFT);

        if ($this->db->set("bukti_gl", $bukti_baru)->insert("t_general_ledger")) {
            return $bukti_baru;
        } else {
            return $this->get_bukti_gl($tgl);
        }
    }

    public function insert_ledger($data)
    {
        $set_data0 = array(
            "tgl_gl" => $data['tgl_gl'],
            "ket"    => strtoupper($data['ket']),
        );

        $this->db->set($set_data0)->where("no_ref_bukti", $data['no_ref_bukti'])->update("t_general_ledger");

        $bukti_gl = $this->get_bukti_gl($data['tgl_gl']);

        $set_data = array(
            // "bukti_gl"     => $data['bukti_gl'],
            "tgl_gl"       => $data['tgl_gl'],
            "no_ref_bukti" => $data['no_ref_bukti'],
            "kd_kasbank"   => $data['kd_kasbank'],
            "nm_kasbank"   => $data['nm_kasbank'],
            "kd_akun"      => $data['kd_akun'],
            "nm_akun"      => $data['nm_akun'],
            "kredit_debet" => $data['kredit_debet'],
            "jumlah"       => hapus_koma($data['jumlah']),
            "ket"          => strtoupper($data['ket']),
            "user_input"   => $this->session->userdata("username"),
            "tgl_insert"   => date("Y-m-d H:i:s"),
            // "user_edit"    => $data['user_edit'],
            // "tgl_update"   => $data['tgl_update'],
        );

        $query_insert = $this->db->set($set_data)->where("bukti_gl", $bukti_gl)->update("t_general_ledger");

        $xtgl  = explode("-", $data['tgl_gl']);
        $tahun = $xtgl[0];
        $bulan = $xtgl[1];

        $this->update_saldo_ledger($tahun, $bulan);

        return $query_insert;
    }

    public function delete_detail_ledger($data)
    {
        $query_delete = $this->db->where("bukti_gl", $data['bukti_gl'])->delete("t_general_ledger");

        $xtgl  = explode("-", $data['tgl_gl']);
        $tahun = $xtgl[0];
        $bulan = $xtgl[1];

        $this->update_saldo_ledger($tahun, $bulan);

        return $query_delete;
    }

    public function delete_header_ledger($data)
    {
        $query_delete = $this->db->where("no_ref_bukti", $data['no_ref_bukti'])->delete("t_general_ledger");

        $xtgl  = explode("-", $data['tgl_gl1']);
        $tahun = $xtgl[0];
        $bulan = $xtgl[1];

        $this->update_saldo_ledger($tahun, $bulan);

        return $query_delete;
    }

    public function update_saldo_ledger($tahun, $bulan)
    {
        $this->db->where("blth", ($tahun . "-" . $bulan))->delete("t_saldo_general_ledger");

        $query_saldo = $this->db->select("'" . $tahun . "-" . $bulan . "', kd_kasbank, nm_kasbank, kd_akun, nm_akun,
                ifnull(sum(if(kredit_debet = 'D', jumlah, 0)), 0) debet,
                ifnull(sum(if(kredit_debet = 'K', jumlah, 0)), 0) kredit,
                ifnull(sum(if(kredit_debet = 'D', jumlah, 0)), 0) - ifnull(sum(if(kredit_debet = 'K', jumlah, 0)), 0) saldo_akhir
            ")
            ->like("tgl_gl", ($tahun . "-" . $bulan), "after")
            ->group_by("kd_akun")
            ->get_compiled_select("t_general_ledger");

        $update_saldo = "insert into t_saldo_general_ledger
            (blth, kd_kasbank, nm_kasbank, kd_akun, nm_akun, debet, kredit, saldo_akhir)
            " . $query_saldo;

        $this->db->query($update_saldo);
    }
}
