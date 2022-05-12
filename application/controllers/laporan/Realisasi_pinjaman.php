<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Realisasi_pinjaman extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();
    }

    public function index()
    {
        $bulan = get_option_tag(array_bulan(), "BULAN");

        $data['judul_menu'] = "Laporan Realisasi Pinjaman";
        $data['bulan']      = $bulan;

        $this->template->view("laporan/realisasi_pinjaman", $data);
    }

    public function tampilkan()
    {
        $data_req = get_request();

        if ($data_req) {
            $laporan = "<table class=\"table table-bordered table-condensed table-striped\" style=\"white-space: nowrap;\">
                <thead>
                    <tr style=\"\">
                        <th style=\"text-align: center; vertical-align: middle;\">No.</th>
                        <th style=\"text-align: center; vertical-align: middle;\">Tgl. Realisasi</th>
                        <th style=\"text-align: center; vertical-align: middle;\">NAK</th>
                        <th style=\"text-align: center; vertical-align: middle;\">NIK</th>
                        <th style=\"text-align: center; vertical-align: middle;\">NAMA</th>
                        <th style=\"text-align: center; vertical-align: middle;\">Jenis Pinjaman</th>
                        <th style=\"text-align: center; vertical-align: middle;\">Pokok Pinjaman</th>
                        <th style=\"text-align: center; vertical-align: middle;\">Margin Pinjaman</th>
                        <th style=\"text-align: center; vertical-align: middle;\">Biaya Admin</th>
                        <th style=\"text-align: center; vertical-align: middle;\">Masa</th>
                        <th style=\"text-align: center;\">Margin (%)</th>
                        <th style=\"text-align: center; vertical-align: middle;\">Total Omzet</th>
                    </tr>
                </thead>
                <tbody>";

            $no                = 1;
            $grandtotal_pokok  = 0;
            $grandtotal_margin = 0;
            $grandtotal_admin  = 0;
            $grandtotal_omzet  = 0;

            $tgl_awal  = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT);
            $tgl_akhir = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT);

            if ($data_req['pilihan_data'] != "SEMUA") {
                $this->db->where("kd_pinjaman", $data_req['pilihan_data']);
            }

            $data_tanggal = $this->db->where("tgl_pinjam between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
                ->group_by("tgl_pinjam")
                ->get("t_pinjaman_ang");

            foreach ($data_tanggal->result_array() as $key => $value) {
                if ($data_req['pilihan_data'] != "SEMUA") {
                    $this->db->where("kd_pinjaman", $data_req['pilihan_data']);
                }

                $data_pinjaman = $this->db->where("tgl_pinjam", $value['tgl_pinjam'])
                    ->order_by("no_pinjam")
                    ->get("t_pinjaman_ang");

                foreach ($data_pinjaman->result_array() as $key1 => $value1) {
                    $total_omzet = $value1['jml_pinjam'] + $value1['jml_margin'] + $value1['jml_biaya_admin'];

                    $laporan .= "
                        <tr>
                            <td style=\"text-align: right;\">" . $no . "</td>
                            <td>" . balik_tanggal($value1['tgl_pinjam']) . "</td>
                            <td>" . $value1['no_ang'] . "</td>
                            <td>" . $value1['no_peg'] . "</td>
                            <td>" . $value1['nm_ang'] . "</td>
                            <td>" . $value1['nm_pinjaman'] . "</td>
                            <td style=\"text-align: right;\">" . number_format($value1['jml_pinjam'], 2) . "</td>
                            <td style=\"text-align: right;\">" . number_format($value1['jml_margin'], 2) . "</td>
                            <td style=\"text-align: right;\">" . number_format($value1['jml_biaya_admin'], 2) . "</td>
                            <td style=\"text-align: center;\">" . $value1['tempo_bln'] . "</td>
                            <td style=\"text-align: center;\">" . $value1['margin'] . "</td>
                            <td style=\"text-align: right;\">" . number_format($total_omzet, 2) . "</td>
                        </tr>";

                    $no++;
                    $grandtotal_pokok += $value1['jml_pinjam'];
                    $grandtotal_margin += $value1['jml_margin'];
                    $grandtotal_admin += $value1['jml_biaya_admin'];
                    $grandtotal_omzet += $total_omzet;
                }
            }

            $laporan .= "
                        <tr>
                            <th colspan=\"6\" style=\"text-align: right;\">Grand Total</th>
                            <th style=\"text-align: right;\">" . number_format($grandtotal_pokok, 2) . "</th>
                            <th style=\"text-align: right;\">" . number_format($grandtotal_margin, 2) . "</th>
                            <th style=\"text-align: right;\">" . number_format($grandtotal_admin, 2) . "</th>
                            <th></th>
                            <th></th>
                            <th style=\"text-align: right;\">" . number_format($grandtotal_omzet, 2) . "</th>
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

        $pdf = new mypdf("L", "mm", $ukuran_kertas);

        $kreator      = "MBagusRD";
        $judul_file   = "Cetak PDF";
        $judul_header = "Koperasi Karyawan Keluarga Besar Petrokimia Gresik";
        $teks_header  = NAMA_PHP;
        $judul_file   = "cetak_realisasi_pinjaman_" . date("Y-m-d_H-i-s") . ".pdf";

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

        $pdf->SetAutoPageBreak(true, 10);
        // $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();

        $pdf->SetFontSize('12');

        $pdf->Cell(0, 0, "Laporan Realisasi Pinjaman", 0, 0, "C");
        $pdf->SetFontSize('9');

        $pdf->Ln();

        $array_bln  = array_bulan();
        $nama_bulan = $array_bln[$data_req['bulan']];

        $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

        $pdf->Ln();
        $pdf->Ln();

        $koleng[1]  = "7";
        $koleng[2]  = "20";
        $koleng[3]  = "11";
        $koleng[4]  = "18";
        $koleng[5]  = "50";
        $koleng[6]  = "17";
        $koleng[7]  = "30";
        $koleng[8]  = "30";
        $koleng[9]  = "25";
        $koleng[10] = "15";
        $koleng[11] = "15";
        $koleng[12] = "30";

        $koleng_sub = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5];
        $koleng_all = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5] + $koleng[6] + $koleng[7];

        $pdf->Cell($koleng[1], 0, "No.", 'TB', 0, "C");
        $pdf->Cell($koleng[2], 0, "Tgl. Realisasi", 'TB', 0, "C", 0, '', 1);
        $pdf->Cell($koleng[3], 0, "NAK", 'TB', 0, "C");
        $pdf->Cell($koleng[4], 0, "NIK", 'TB', 0, "C");
        $pdf->Cell($koleng[5], 0, "NAMA", 'TB', 0, "C");
        $pdf->Cell($koleng[6], 0, "Jns. Pinjaman", 'TB', 0, "C", '', '', 1);
        $pdf->Cell($koleng[7], 0, "Pokok Pinjaman", 'TB', 0, "C");
        $pdf->Cell($koleng[8], 0, "Margin Pinjaman", 'TB', 0, "C");
        $pdf->Cell($koleng[9], 0, "Biaya Admin", 'TB', 0, "C");
        $pdf->Cell($koleng[10], 0, "Masa", 'TB', 0, "C");
        $pdf->Cell($koleng[11], 0, "Margin (%)", 'TB', 0, "C");
        $pdf->Cell($koleng[12], 0, "Total Omzet", 'TB', 0, "C");

        $pdf->Ln();

        $no                = 1;
        $grandtotal_pokok  = 0;
        $grandtotal_margin = 0;
        $grandtotal_admin  = 0;
        $grandtotal_omzet  = 0;

        $tgl_awal  = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT);
        $tgl_akhir = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT);

        if ($data_req['pilihan_data'] != "SEMUA") {
            $this->db->where("kd_pinjaman", $data_req['pilihan_data']);
        }

        $data_tanggal = $this->db->where("tgl_pinjam between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
            ->group_by("tgl_pinjam")
            ->get("t_pinjaman_ang");

        foreach ($data_tanggal->result_array() as $key => $value) {
            if ($data_req['pilihan_data'] != "SEMUA") {
                $this->db->where("kd_pinjaman", $data_req['pilihan_data']);
            }

            $data_pinjaman = $this->db->where("tgl_pinjam", $value['tgl_pinjam'])
                ->order_by("no_pinjam")
                ->get("t_pinjaman_ang");

            foreach ($data_pinjaman->result_array() as $key1 => $value1) {
                if ($pdf->GetY() > 202) {
                    $pdf->SetFontSize('12');

                    $pdf->Cell(0, 0, "Laporan Realisasi Pinjaman", 0, 0, "C");
                    $pdf->SetFontSize('9');

                    $pdf->Ln();

                    $array_bln  = array_bulan();
                    $nama_bulan = $array_bln[$data_req['bulan']];

                    $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

                    $pdf->Ln();
                    $pdf->Ln();

                    $pdf->Cell($koleng[1], 0, "No.", 'TB', 0, "C");
                    $pdf->Cell($koleng[2], 0, "Tgl. Realisasi", 'TB', 0, "C", 0, '', 1);
                    $pdf->Cell($koleng[3], 0, "NAK", 'TB', 0, "C");
                    $pdf->Cell($koleng[4], 0, "NIK", 'TB', 0, "C");
                    $pdf->Cell($koleng[5], 0, "NAMA", 'TB', 0, "C");
                    $pdf->Cell($koleng[6], 0, "Jns. Pinjaman", 'TB', 0, "C");
                    $pdf->Cell($koleng[7], 0, "Pokok Pinjaman", 'TB', 0, "C");
                    $pdf->Cell($koleng[8], 0, "Margin Pinjaman", 'TB', 0, "C");
                    $pdf->Cell($koleng[9], 0, "Biaya Admin", 'TB', 0, "C");
                    $pdf->Cell($koleng[10], 0, "Masa", 'TB', 0, "C");
                    $pdf->Cell($koleng[11], 0, "Margin (%)", 'TB', 0, "C");
                    $pdf->Cell($koleng[12], 0, "Total Omzet", 'TB', 0, "C");

                    $pdf->Ln();
                }

                $total_omzet = $value1['jml_pinjam'] + $value1['jml_margin'] + $value1['jml_biaya_admin'];

                $pdf->Cell($koleng[1], 0, $no, 0, 0, "R", '', '', 1);
                $pdf->Cell($koleng[2], 0, balik_tanggal($value1['tgl_pinjam']), 0, 0, "C", 0, '', 1);
                $pdf->Cell($koleng[3], 0, $value1['no_ang'], 0, 0, "L", '', '', 1);
                $pdf->Cell($koleng[4], 0, $value1['no_peg'], 0, 0, "L", '', '', 1);
                $pdf->Cell($koleng[5], 0, $value1['nm_ang'], 0, 0, "L", 0, '', 1);
                $pdf->Cell($koleng[6], 0, $value1['nm_pinjaman'], 0, 0, "L");
                $pdf->Cell($koleng[7], 0, number_format($value1['jml_pinjam'], 2), 0, 0, "R");
                $pdf->Cell($koleng[8], 0, number_format($value1['jml_margin'], 2), 0, 0, "R");
                $pdf->Cell($koleng[9], 0, number_format($value1['jml_biaya_admin'], 2), 0, 0, "R");
                $pdf->Cell($koleng[10], 0, $value1['tempo_bln'], 0, 0, "C");
                $pdf->Cell($koleng[11], 0, $value1['margin'], 0, 0, "C");
                $pdf->Cell($koleng[12], 0, number_format($total_omzet, 2), 0, 0, "R");

                $pdf->Ln();

                $no++;
                $grandtotal_pokok += $value1['jml_pinjam'];
                $grandtotal_margin += $value1['jml_margin'];
                $grandtotal_admin += $value1['jml_biaya_admin'];
                $grandtotal_omzet += $total_omzet;
            }

            if ($pdf->GetY() > 202) {
                $pdf->SetFontSize('12');

                $pdf->Cell(0, 0, "Laporan Realisasi Pinjaman", 0, 0, "C");
                $pdf->SetFontSize('9');

                $pdf->Ln();

                $array_bln  = array_bulan();
                $nama_bulan = $array_bln[$data_req['bulan']];

                $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

                $pdf->Ln();
                $pdf->Ln();

                $pdf->Cell($koleng[1], 0, "No.", 'TB', 0, "C");
                $pdf->Cell($koleng[2], 0, "Tgl. Realisasi", 'TB', 0, "C", 0, '', 1);
                $pdf->Cell($koleng[3], 0, "NAK", 'TB', 0, "C");
                $pdf->Cell($koleng[4], 0, "NIK", 'TB', 0, "C");
                $pdf->Cell($koleng[5], 0, "NAMA", 'TB', 0, "C");
                $pdf->Cell($koleng[6], 0, "Jns. Pinjaman", 'TB', 0, "C");
                $pdf->Cell($koleng[7], 0, "Pokok Pinjaman", 'TB', 0, "C");
                $pdf->Cell($koleng[8], 0, "Margin Pinjaman", 'TB', 0, "C");
                $pdf->Cell($koleng[9], 0, "Biaya Admin", 'TB', 0, "C");
                $pdf->Cell($koleng[10], 0, "Masa", 'TB', 0, "C");
                $pdf->Cell($koleng[11], 0, "Margin (%)", 'TB', 0, "C");
                $pdf->Cell($koleng[12], 0, "Total Omzet", 'TB', 0, "C");

                $pdf->Ln();
            }
        }

        $koleng_grand = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5] + $koleng[6];

        $pdf->Cell(0, 0, "", 0, 0, "C");

        $pdf->Ln();

        if ($pdf->GetY() > 202) {
            $pdf->SetFontSize('12');

            $pdf->Cell(0, 0, "Laporan Realisasi Pinjaman", 0, 0, "C");
            $pdf->SetFontSize('9');

            $pdf->Ln();

            $array_bln  = array_bulan();
            $nama_bulan = $array_bln[$data_req['bulan']];

            $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

            $pdf->Ln();
            $pdf->Ln();

            $pdf->Cell($koleng[1], 0, "No.", 'TB', 0, "C");
            $pdf->Cell($koleng[2], 0, "Tgl. Realisasi", 'TB', 0, "C", 0, '', 1);
            $pdf->Cell($koleng[3], 0, "NAK", 'TB', 0, "C");
            $pdf->Cell($koleng[4], 0, "NIK", 'TB', 0, "C");
            $pdf->Cell($koleng[5], 0, "NAMA", 'TB', 0, "C");
            $pdf->Cell($koleng[6], 0, "Jns. Pinjaman", 'TB', 0, "C");
            $pdf->Cell($koleng[7], 0, "Pokok Pinjaman", 'TB', 0, "C");
            $pdf->Cell($koleng[8], 0, "Margin Pinjaman", 'TB', 0, "C");
            $pdf->Cell($koleng[9], 0, "Biaya Admin", 'TB', 0, "C");
            $pdf->Cell($koleng[10], 0, "Masa", 'TB', 0, "C");
            $pdf->Cell($koleng[11], 0, "Margin (%)", 'TB', 0, "C");
            $pdf->Cell($koleng[12], 0, "Total Omzet", 'TB', 0, "C");

            $pdf->Ln();
        }

        $pdf->SetFont("", "B");

        $pdf->Cell($koleng_grand, 0, "Grand Total", "TB", 0, "R");
        $pdf->Cell($koleng[7], 0, number_format($grandtotal_pokok, 2), "TB", 0, "R");
        $pdf->Cell($koleng[8], 0, number_format($grandtotal_margin, 2), "TB", 0, "R");
        $pdf->Cell($koleng[9], 0, number_format($grandtotal_admin, 2), "TB", 0, "R");
        $pdf->Cell($koleng[10], 0, "", "TB", 0, "R");
        $pdf->Cell($koleng[11], 0, "", "TB", 0, "R");
        $pdf->Cell($koleng[12], 0, number_format($grandtotal_omzet, 2), "TB", 0, "R");

        $pdf->Output($judul_file, 'I');
    }
}
