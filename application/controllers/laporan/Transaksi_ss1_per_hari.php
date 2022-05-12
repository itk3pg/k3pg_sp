<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_ss1_per_hari extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();
    }

    public function index()
    {
        $bulan = get_option_tag(array_bulan(), "BULAN");

        $data['judul_menu'] = "Laporan Transaksi SS1 Per Hari";
        $data['bulan']      = $bulan;

        $this->template->view("laporan/transaksi_ss1_per_hari", $data);

    }

    public function tampilkan()
    {
        $data_req = get_request();

        if ($data_req) {
            $laporan = "<table class=\"table table-bordered table-condensed table-striped\" style=\"white-space: nowrap\">
                    <thead>
                        <tr style=\"\">
                            <th style=\"text-align: center; vertical-align: middle;\">No.</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Tanggal</th>
                            <th style=\"text-align: center; vertical-align: middle;\">NAK</th>
                            <th style=\"text-align: center; vertical-align: middle;\">NAMA</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Jenis Transaksi</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Kredit</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Debet</th>
                        </tr>
                    </thead>
                    <tbody>";

            $tgl_awal  = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT);
            $tgl_akhir = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT);

            $grandtotal_kredit = 0;
            $grandtotal_debet  = 0;

            if ($data_req['pilihan_data'] == "nonbunga") {
                $this->db->where_not_in("kd_jns_transaksi", array("05", "06"));
            } else if ($data_req['pilihan_data'] == "bungasaja") {
                $this->db->where_in("kd_jns_transaksi", array("05", "06"));
            }

            $xquery_jns_transaksi = $this->db->select("kd_jns_transaksi")
                ->where("tgl_simpan between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
                ->group_by("kd_jns_transaksi")
                ->get_compiled_select("t_simpanan_ang");

            $query_jns_transaksi = $this->db->query(str_replace("`", "", $xquery_jns_transaksi));

            foreach ($query_jns_transaksi->result_array() as $key => $value) {
                $no              = 1;
                $subtotal_kredit = 0;
                $subtotal_debet  = 0;

                $xquery_simpanan = $this->db->select("tgl_simpan, no_ang, nm_ang, kd_jns_transaksi, nm_jns_transaksi, kredit_debet, jumlah")
                    ->where("tgl_simpan between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
                    ->where("kd_jns_transaksi", $value['kd_jns_transaksi'])
                    ->order_by("tgl_simpan, no_ang")
                    ->get_compiled_select("t_simpanan_ang");

                $query_simpanan = $this->db->query(str_replace("`", "", $xquery_simpanan));

                foreach ($query_simpanan->result_array() as $key1 => $value1) {
                    $jml_kredit = ($value1['kredit_debet'] == "K") ? $value1['jumlah'] : 0;
                    $jml_debet  = ($value1['kredit_debet'] == "D") ? $value1['jumlah'] : 0;

                    $laporan .= "
                        <tr>
                            <td style=\"text-align: right;\">" . $no . "</td>
                            <td>" . balik_tanggal($value1['tgl_simpan']) . "</td>
                            <td>" . $value1['no_ang'] . "</td>
                            <td>" . $value1['nm_ang'] . "</td>
                            <td>" . ($value1['kd_jns_transaksi'] . " " . $value1['nm_jns_transaksi']) . "</td>
                            <td style=\"text-align: right;\">" . number_format($jml_kredit, 2) . "</td>
                            <td style=\"text-align: right;\">" . number_format($jml_debet, 2) . "</td>
                        </tr>";

                    $no++;
                    $subtotal_kredit += $jml_kredit;
                    $subtotal_debet += $jml_debet;

                    $grandtotal_kredit += $jml_kredit;
                    $grandtotal_debet += $jml_debet;
                }

                $laporan .= "
                    <tr>
                        <th colspan=\"5\" style=\"text-align: right;\">Sub Total</th>
                        <th style=\"text-align: right;\">" . number_format($subtotal_kredit, 2) . "</th>
                        <th style=\"text-align: right;\">" . number_format($subtotal_debet, 2) . "</th>
                    </tr>
                    <tr>
                        <th colspan=\"7\"></th>
                    </tr>";
            }

            $laporan .= "
                        <tr>
                            <th colspan=\"5\" style=\"text-align: right;\">Grand Total</th>
                            <th style=\"text-align: right;\">" . number_format($grandtotal_kredit, 2) . "</th>
                            <th style=\"text-align: right;\">" . number_format($grandtotal_debet, 2) . "</th>
                        </tr>
                    </tbody>
                </table>";

            echo $laporan;
        }
    }

    public function cetak()
    {
        $get_request = get_request();

        $data_req = json_decode(base64_decode($get_request['data']), true);

        $this->load->library('mypdf');

        $ukuran_kertas = "LETTER";
        // $ukuran_kertas = array("216", "279");

        $pdf = new mypdf("P", "mm", $ukuran_kertas);

        $kreator      = "MBagusRD";
        $judul_file   = "Cetak PDF";
        $judul_header = "Koperasi Karyawan Keluarga Besar Petrokimia Gresik";
        $teks_header  = NAMA_PHP;
        $judul_file   = "cetak_ss1_harian_" . date("Y-m-d_H-i-s") . ".pdf";

        $pdf->SetCreator($kreator);
        $pdf->SetAuthor($kreator);
        $pdf->SetTitle($judul_file);

        $pdf->SetHeaderData("", "", $judul_header, $teks_header, "", "");
        $pdf->setFooterData("", "");
        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);

        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins("5", "18", "5");
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();

        $pdf->SetFontSize('12');

        $pdf->Cell(0, 0, "Laporan Harian Simpanan Sukarela 1", 0, 0, "C");
        $pdf->SetFontSize('9');

        $pdf->Ln();

        $array_bln  = array_bulan();
        $nama_bulan = $array_bln[$data_req['bulan']];

        $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

        $pdf->Ln();
        $pdf->Ln();

        $koleng[1] = "10";
        $koleng[2] = "15";
        $koleng[3] = "15";
        $koleng[4] = "60";
        $koleng[5] = "40";
        $koleng[6] = "30";
        $koleng[7] = "30";

        $koleng_sub = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5];
        $koleng_all = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5] + $koleng[6] + $koleng[7];

        $tgl_awal  = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT);
        $tgl_akhir = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT);

        $grandtotal_kredit = 0;
        $grandtotal_debet  = 0;

        if ($data_req['pilihan_data'] == "nonbunga") {
            $this->db->where_not_in("kd_jns_transaksi", array("05", "06"));
        } else if ($data_req['pilihan_data'] == "bungasaja") {
            $this->db->where_in("kd_jns_transaksi", array("05", "06"));
        }

        $xquery_jns_transaksi = $this->db->select("kd_jns_transaksi")
            ->where("tgl_simpan between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
            ->group_by("kd_jns_transaksi")
            ->get_compiled_select("t_simpanan_ang");

        $query_jns_transaksi = $this->db->query(str_replace("`", "", $xquery_jns_transaksi));

        foreach ($query_jns_transaksi->result_array() as $key => $value) {
            $pdf->Cell($koleng[1], 0, "No.", 'TB', 0, "C");
            $pdf->Cell($koleng[2], 0, "Tanggal", 'TB', 0, "C");
            $pdf->Cell($koleng[3], 0, "NAK", 'TB', 0, "C");
            $pdf->Cell($koleng[4], 0, "NAMA", 'TB', 0, "C");
            $pdf->Cell($koleng[5], 0, "Jns. Transaksi", 'TB', 0, "C");
            $pdf->Cell($koleng[6], 0, "Kredit", 'TB', 0, "C");
            $pdf->Cell($koleng[7], 0, "Debet", 'TB', 0, "C");

            $pdf->Ln();

            $no              = 1;
            $subtotal_kredit = 0;
            $subtotal_debet  = 0;

            $xquery_simpanan = $this->db->select("tgl_simpan, no_ang, nm_ang, kd_jns_transaksi, nm_jns_transaksi, kredit_debet, jumlah")
                ->where("tgl_simpan between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
                ->where("kd_jns_transaksi", $value['kd_jns_transaksi'])
                ->order_by("tgl_simpan, no_ang")
                ->get_compiled_select("t_simpanan_ang");

            $query_simpanan = $this->db->query(str_replace("`", "", $xquery_simpanan));

            foreach ($query_simpanan->result_array() as $key1 => $value1) {
                if ($pdf->GetY() > 265) {
                    $pdf->SetFontSize('12');

                    $pdf->Cell(0, 0, "Laporan Harian Simpanan Sukarela 1", 0, 0, "C");
                    $pdf->SetFontSize('9');

                    $pdf->Ln();

                    $array_bln  = array_bulan();
                    $nama_bulan = $array_bln[$data_req['bulan']];

                    $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

                    $pdf->Ln();
                    $pdf->Ln();

                    $pdf->Cell($koleng[1], 0, "No.", 'TB', 0, "C");
                    $pdf->Cell($koleng[2], 0, "Tanggal", 'TB', 0, "C");
                    $pdf->Cell($koleng[3], 0, "NAK", 'TB', 0, "C");
                    $pdf->Cell($koleng[4], 0, "NAMA", 'TB', 0, "C");
                    $pdf->Cell($koleng[5], 0, "Jns. Transaksi", 'TB', 0, "C");
                    $pdf->Cell($koleng[6], 0, "Kredit", 'TB', 0, "C");
                    $pdf->Cell($koleng[7], 0, "Debet", 'TB', 0, "C");

                    $pdf->Ln();
                }

                $jml_kredit = ($value1['kredit_debet'] == "K") ? $value1['jumlah'] : 0;
                $jml_debet  = ($value1['kredit_debet'] == "D") ? $value1['jumlah'] : 0;

                $jns_transaksi = $value1['kd_jns_transaksi'] . " " . $value1['nm_jns_transaksi'];

                $pdf->Cell($koleng[1], 0, $no, 0, 0, "R", 0, '', 1);
                $pdf->Cell($koleng[2], 0, balik_tanggal($value1['tgl_simpan']), 0, 0, "L", 0, '', 1);
                $pdf->Cell($koleng[3], 0, $value1['no_ang'], 0, 0, "L");
                $pdf->Cell($koleng[4], 0, $value1['nm_ang'], 0, 0, "L", 0, '', 1);
                $pdf->Cell($koleng[5], 0, $jns_transaksi, 0, 0, "L", 0, '', 1);
                $pdf->Cell($koleng[6], 0, number_format($jml_kredit, 2), 0, 0, "R");
                $pdf->Cell($koleng[7], 0, number_format($jml_debet, 2), 0, 0, "R");

                $pdf->Ln();

                $no++;
                $subtotal_kredit += $jml_kredit;
                $subtotal_debet += $jml_debet;

                $grandtotal_kredit += $jml_kredit;
                $grandtotal_debet += $jml_debet;
            }

            $pdf->SetFont("", "B");

            $pdf->Cell($koleng_sub, 0, "Sub Total", 'TB', 0, "R");
            $pdf->Cell($koleng[6], 0, number_format($subtotal_kredit, 2), 'TB', 0, "R", 0, '', 1);
            $pdf->Cell($koleng[7], 0, number_format($subtotal_debet, 2), 'TB', 1, "R", 0, '', 1);

            $pdf->Cell($koleng_all, 0, "", 0, 1, "L");

            $pdf->SetFont("", "");

        }

        $pdf->SetFont("", "B");

        $pdf->Cell($koleng_sub, 0, "Grand Total", 'TB', 0, "R");
        $pdf->Cell($koleng[6], 0, number_format($grandtotal_kredit, 2), 'TB', 0, "R", 0, '', 1);
        $pdf->Cell($koleng[7], 0, number_format($grandtotal_debet, 2), 'TB', 1, "R", 0, '', 1);

        $pdf->SetFont("", "");

        $pdf->Ln();
        $pdf->Ln();

        $pdf->Cell(10, 0);
        $pdf->Cell(40, 0, "Disiapkan Oleh");
        $pdf->Cell(20, 0);
        $pdf->Cell(40, 0, "Diperiksa Oleh");
        $pdf->Cell(20, 0);
        $pdf->Cell(40, 0, "Mengetahui,");

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();

        $ttd = $this->db->limit("1")->get("s_laporan");

        $pdf->Cell(10, 0);
        $pdf->Cell(40, 0, "");
        $pdf->Cell(20, 0);
        $pdf->Cell(40, 0, $ttd->row(0)->kabid_simpin);
        $pdf->Cell(20, 0);
        $pdf->Cell(40, 0, $ttd->row(0)->manager_op4);

        $pdf->Ln();

        $pdf->Cell(10, 0);
        $pdf->Cell(40, 0, "Teller", 'T');
        $pdf->Cell(20, 0);
        $pdf->Cell(40, 0, "Kabid Keanggotaan", 'T');
        $pdf->Cell(20, 0);
        $pdf->Cell(40, 0, "Mgr. Ops IV", 'T');

        $pdf->Output($judul_file, 'I');
    }
}
