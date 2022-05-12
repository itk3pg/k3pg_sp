<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_ss2_jatuh_tempo extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();
    }

    public function index()
    {
        $bulan = get_option_tag(array_bulan(), "BULAN");

        $data['judul_menu'] = "Laporan Transaksi SS2 Jatuh Tempo";
        $data['bulan']      = $bulan;

        $this->template->view("laporan/transaksi_ss2_jatuh_tempo", $data);
    }

    private function query_jt($tgl_awal, $tgl_akhir)
    {
        return $this->db->where("(tgl_jt between '" . $tgl_awal . "' and '" . $tgl_akhir . "')")
            ->order_by("tgl_jt, no_ang")
            ->get("t_simpanan_sukarela2");
    }

    public function tampilkan()
    {
        $data_req = get_request();

        if ($data_req) {
            $laporan = "<table class=\"table table-bordered table-condensed table-striped\" style=\"white-space: nowrap;\">
                <thead>
                    <tr>
                        <th style=\"text-align: center;vertical-align: middle;\" rowspan=\"2\">No.</th>
                        <th style=\"text-align: center;vertical-align: middle;\" rowspan=\"2\">NAK</th>
                        <th style=\"text-align: center;vertical-align: middle;\" rowspan=\"2\">NAMA</th>
                        <th style=\"text-align: center;vertical-align: middle;\" rowspan=\"2\">No. SS2</th>
                        <th style=\"text-align: center;vertical-align: middle;\" rowspan=\"2\">Nominal</th>
                        <th style=\"text-align: center;vertical-align: middle;\" colspan=\"2\">Tanggal</th>
                        <th style=\"text-align: center;vertical-align: middle;\" rowspan=\"2\">Jangka</th>
                        <th style=\"text-align: center;vertical-align: middle;\" colspan=\"2\">Bunga</th>
                        <th style=\"text-align: center;vertical-align: middle;\" rowspan=\"2\">Ket</th>
                    </tr>
                    <tr>
                        <th style=\"text-align: center;vertical-align: middle;\">Jt. Tempo</th>
                        <th style=\"text-align: center;vertical-align: middle;\">Valuta</th>
                        <th style=\"text-align: center;vertical-align: middle;\">(%/Thn)</th>
                        <th style=\"text-align: center;vertical-align: middle;\">(Rp/Bln)</th>
                    </tr>
                </thead>
                <tbody>";

            $tgl_awal  = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . $data_req['tgl_awal'];
            $tgl_akhir = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . $data_req['tgl_akhir'];

            $no                  = 1;
            $grandtotal_simpanan = 0;

            // $sub_query1 = "(SELECT *, COUNT(*) banyak_data
            //     FROM
            //     (
            //         SELECT * FROM t_simpanan_sukarela2
            //         WHERE tgl_simpan <= '" . $tgl_akhir . "'
            //         ORDER by no_ss2, tgl_simpan DESC
            //     ) x
            //     GROUP BY x.no_ss2) a";

            $data_ss2 = $this->query_jt($tgl_awal, $tgl_akhir);

            foreach ($data_ss2->result_array() as $key => $value) {
                $laporan .= "
                    <tr>
                        <td style=\"text-align: right;\">" . $no . "</td>
                        <td>" . $value['no_ang'] . "</td>
                        <td>" . $value['nm_ang'] . "</td>
                        <td style=\"text-align: right;\">" . $value['no_ss2'] . "</td>
                        <td style=\"text-align: right;\">" . number_format($value['jml_simpanan'], 2) . "</td>
                        <td style=\"text-align: right;\">" . balik_tanggal($value['tgl_jt']) . "</td>
                        <td style=\"text-align: right;\">" . balik_tanggal($value['tgl_simpan']) . "</td>
                        <td style=\"text-align: center;\">" . $value['tempo_bln'] . "</td>
                        <td style=\"text-align: center;\">" . $value['margin'] . "</td>
                        <td style=\"text-align: right;\">" . number_format($value['jml_margin_bln'], 2) . "</td>
                        <td>" . $value['ket'] . "</td>
                    </tr>";

                $no++;
                $grandtotal_simpanan += $value['jml_simpanan'];
            }

            $laporan .= "
                        <tr>
                            <th colspan=\"4\" style=\"text-align: right;\">Grand Total</th>
                            <th style=\"text-align: right;\">" . number_format($grandtotal_simpanan, 2) . "</th>
                            <th colspan=\"6\"></th>
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
        $judul_file   = "cetak_ss2_baru_" . date("Y-m-d_H-i-s") . ".pdf";

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

        $pdf->Cell(0, 0, "Laporan Simpanan Sukarela 2 Jatuh Tempo", 0, 0, "C");
        $pdf->SetFontSize('8');

        $pdf->Ln();

        $array_bln  = array_bulan();
        $nama_bulan = $array_bln[$data_req['bulan']];

        $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

        $pdf->Ln();
        $pdf->Ln();

        $koleng[1]  = "7";
        $koleng[2]  = "10";
        $koleng[3]  = "40";
        $koleng[4]  = "17";
        $koleng[5]  = "25";
        $koleng[6]  = "18";
        $koleng[7]  = "18";
        $koleng[8]  = "10";
        $koleng[9]  = "10";
        $koleng[10] = "20";
        $koleng[11] = "25";

        $koleng_sub = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5];
        $koleng_all = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5] + $koleng[6] + $koleng[7];

        $pdf->Cell($koleng[1], 10, "No.", 1, 0, "C");
        $pdf->Cell($koleng[2], 10, "NAK", 1, 0, "C");
        $pdf->Cell($koleng[3], 10, "NAMA", 1, 0, "C");
        $pdf->Cell($koleng[4], 10, "No. SS2", 1, 0, "C");
        $pdf->Cell($koleng[5], 10, "Nominal", 1, 0, "C");
        $pdf->Cell(($koleng[6] + $koleng[7]), 5, "Tanggal", 1, 0, "C");
        $pdf->Cell($koleng[8], 10, "Jangka", 1, 0, "C", 0, '', 1);
        $pdf->Cell(($koleng[9] + $koleng[10]), 5, "Bunga", 1, 0, "C");
        $pdf->Cell($koleng[11], 10, "Ket", 1, 0, "C");

        $pdf->Ln(5);

        $koleng1 = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5];

        $pdf->Cell($koleng1, 10, "");
        $pdf->Cell($koleng[6], 5, "Valuta", 1, '', "C");
        $pdf->Cell($koleng[7], 5, "Jt. Tempo", 1, '', "C");
        $pdf->Cell($koleng[8], 10, "");
        $pdf->Cell($koleng[9], 5, "(%/Thn)", 1, '', "C", 0, '', 1);
        $pdf->Cell($koleng[10], 5, "(Rp/Bln)", 1, '', "C");
        $pdf->Cell($koleng[11], 10, "");

        $pdf->Ln(5);

        $tgl_awal  = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . $data_req['tgl_awal'];
        $tgl_akhir = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . $data_req['tgl_akhir'];

        $no                  = 1;
        $grandtotal_simpanan = 0;

        $data_ss2 = $this->query_jt($tgl_awal, $tgl_akhir);

        foreach ($data_ss2->result_array() as $key => $value) {
            if ($pdf->GetY() > 265) {
                $pdf->SetFontSize('12');

                $pdf->Cell(0, 0, "Laporan Simpanan Sukarela 2 Jatuh Tempo", 0, 0, "C");
                $pdf->SetFontSize('8');

                $pdf->Ln();

                $array_bln  = array_bulan();
                $nama_bulan = $array_bln[$data_req['bulan']];

                $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

                $pdf->Ln();
                $pdf->Ln();

                $pdf->Cell($koleng[1], 10, "No.", 1, 0, "C");
                $pdf->Cell($koleng[2], 10, "NAK", 1, 0, "C");
                $pdf->Cell($koleng[3], 10, "NAMA", 1, 0, "C");
                $pdf->Cell($koleng[4], 10, "No. SS2", 1, 0, "C");
                $pdf->Cell($koleng[5], 10, "Nominal", 1, 0, "C");
                $pdf->Cell(($koleng[6] + $koleng[7]), 5, "Tanggal", 1, 0, "C");
                $pdf->Cell($koleng[8], 10, "Jangka", 1, 0, "C", 0, '', 1);
                $pdf->Cell(($koleng[9] + $koleng[10]), 5, "Bunga", 1, 0, "C");
                $pdf->Cell($koleng[11], 10, "Ket", 1, 0, "C");

                $pdf->Ln(5);

                $pdf->Cell($koleng1, 10, "");
                $pdf->Cell($koleng[6], 5, "Valuta", 1, '', "C");
                $pdf->Cell($koleng[7], 5, "Jt. Tempo", 1, '', "C");
                $pdf->Cell($koleng[8], 10, "");
                $pdf->Cell($koleng[9], 5, "(%/Thn)", 1, '', "C", 0, '', 1);
                $pdf->Cell($koleng[10], 5, "(Rp/Bln)", 1, '', "C");
                $pdf->Cell($koleng[11], 10, "");

                $pdf->Ln(5);
            }

            $pdf->Cell($koleng[1], 0, $no, 0, '', "R");
            $pdf->Cell($koleng[2], 0, $value['no_ang'], 0, '', "L", 0, '', 1);
            $pdf->Cell($koleng[3], 0, $value['nm_ang'], 0, '', "L", 0, '', 1);
            $pdf->Cell($koleng[4], 0, $value['no_ss2'], 0, '', "R");
            $pdf->Cell($koleng[5], 0, number_format($value['jml_simpanan'], 2), 0, '', "R");
            $pdf->Cell($koleng[6], 0, balik_tanggal($value['tgl_simpan']), 0, '', "R");
            $pdf->Cell($koleng[7], 0, balik_tanggal($value['tgl_jt']), 0, '', "R");
            $pdf->Cell($koleng[8], 0, $value['tempo_bln'], 0, '', "C");
            $pdf->Cell($koleng[9], 0, $value['margin'], 0, '', "C");
            $pdf->Cell($koleng[10], 0, number_format($value['jml_margin_bln'], 2), 0, '', "R");
            $pdf->Cell($koleng[11], 0, $value['ket'], 0, '', "L", 0, '', 1);

            $pdf->Ln();

            $no++;
            $grandtotal_simpanan += $value['jml_simpanan'];
        }

        $koleng_grand1 = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4];
        $koleng_grand2 = $koleng[6] + $koleng[7] + $koleng[8] + $koleng[9] + $koleng[10] + $koleng[11];

        $pdf->SetFont("", "B");

        $pdf->Cell($koleng_grand1, 0, "Grand Total", 'TB', 0, "R");
        $pdf->Cell($koleng[5], 0, number_format($grandtotal_simpanan, 2), 'TB', 0, "R", 0, '', 1);
        $pdf->Cell($koleng_grand2, 0, "", 'TB', 1, "R", 0, '', 1);

        $pdf->Output($judul_file, 'I');
    }
}
