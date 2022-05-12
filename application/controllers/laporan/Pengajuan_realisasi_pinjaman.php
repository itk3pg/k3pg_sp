<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pengajuan_realisasi_pinjaman extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();
    }

    public function index()
    {
        $bulan = get_option_tag(array_bulan(), "BULAN");

        $data['judul_menu'] = "Laporan Pengajuan & Realisasi Pinjaman";
        $data['bulan']      = $bulan;

        $this->template->view("laporan/pengajuan_realisasi_pinjaman", $data);
    }

    public function tampilkan()
    {
        $data_req = get_request();

        if ($data_req) {
            $laporan = "<table class=\"table table-bordered table-condensed table-striped\" style=\"white-space: nowrap;\">
                    <thead>
                        <tr style=\"\">
                            <th rowspan=\"2\" style=\"text-align: center; vertical-align: middle;\">No.</th>
                            <th rowspan=\"2\" style=\"text-align: center; vertical-align: middle;\">Tgl. Pengajuan</th>
                            <th rowspan=\"2\" style=\"text-align: center; vertical-align: middle;\">NAK</th>
                            <th rowspan=\"2\" style=\"text-align: center; vertical-align: middle;\">NAMA</th>
                            <th rowspan=\"2\" style=\"text-align: center; vertical-align: middle;\">Nilai Pengajuan</th>
                            <th rowspan=\"2\" style=\"text-align: center; vertical-align: middle;\">Jenis Pinjam</th>
                            <th rowspan=\"2\" style=\"text-align: center; vertical-align: middle;\">Masa</th>
                            <th colspan=\"2\" style=\"text-align: center;\">Realisasi</th>
                            <th rowspan=\"2\" style=\"text-align: center; vertical-align: middle;\">Yang Belum Realisasi</th>
                            <th rowspan=\"2\" style=\"text-align: center; vertical-align: middle;\">Status</th>
                        </tr>
                        <tr>
                            <th style=\"text-align: center;\">TGL</th>
                            <th style=\"text-align: center;\">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>";

            $grandtotal_pengajuan    = 0;
            $grandtotal_realisasi    = 0;
            $grandtotal_blmrealisasi = 0;

            $tgl_awal  = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT);
            $tgl_akhir = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT);

            $data_tanggal = $this->db->where("tgl_pinjam between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
                ->group_by("tgl_pinjam")
                ->get("t_simulasi_pinjaman_ang");

            foreach ($data_tanggal->result_array() as $key => $value) {
                $no                    = 1;
                $subtotal_pengajuan    = 0;
                $subtotal_realisasi    = 0;
                $subtotal_blmrealisasi = 0;

                $data_pinjaman = $this->db->where("tgl_pinjam", $value['tgl_pinjam'])
                    ->order_by("no_pinjam")
                    ->get("t_simulasi_pinjaman_ang");

                foreach ($data_pinjaman->result_array() as $key1 => $value1) {
                    $jml_realisasi     = ($value1['is_realisasi'] == "1") ? $value1['jml_pinjam_realisasi'] : 0;
                    $jml_blm_realisasi = ($value1['is_realisasi'] == "0") ? $value1['jml_pinjam'] : 0;
                    $is_realisasi      = (($value1['is_realisasi'] == "1") ? "Terealisasi" : "Siap Bayar");

                    $laporan .= "
                        <tr>
                            <td style=\"text-align: right;\">" . $no . "</td>
                            <td>" . balik_tanggal($value1['tgl_pinjam']) . "</td>
                            <td>" . $value1['no_ang'] . "</td>
                            <td>" . $value1['nm_ang'] . "</td>
                            <td style=\"text-align: right;\">" . number_format($value1['jml_pinjam'], 2) . "</td>
                            <td>" . $value1['nm_pinjaman'] . "</td>
                            <td style=\"text-align: center;\">" . $value1['tempo_bln'] . "</td>
                            <td>" . balik_tanggal($value1['tgl_realisasi']) . "</td>
                            <td style=\"text-align: right;\">" . number_format($jml_realisasi, 2) . "</td>
                            <td style=\"text-align: right;\">" . number_format($jml_blm_realisasi, 2) . "</td>
                            <td>" . $is_realisasi . "</td>
                        </tr>";

                    $no++;
                    $subtotal_pengajuan += $value1['jml_pinjam'];
                    $subtotal_realisasi += $jml_realisasi;
                    $subtotal_blmrealisasi += $jml_blm_realisasi;

                    $grandtotal_pengajuan += $value1['jml_pinjam'];
                    $grandtotal_realisasi += $jml_realisasi;
                    $grandtotal_blmrealisasi += $jml_blm_realisasi;
                }

                $laporan .= "
                    <tr>
                        <th colspan=\"4\" style=\"text-align: right;\">Sub Total</th>
                        <th style=\"text-align: right;\">" . number_format($subtotal_pengajuan, 2) . "</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style=\"text-align: right;\">" . number_format($subtotal_realisasi, 2) . "</th>
                        <th style=\"text-align: right;\">" . number_format($subtotal_blmrealisasi, 2) . "</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan=\"11\"></th>
                    </tr>";
            }

            $laporan .= "
                        <tr>
                            <th colspan=\"4\" style=\"text-align: right;\">Grand Total</th>
                            <th style=\"text-align: right;\">" . number_format($grandtotal_pengajuan, 2) . "</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style=\"text-align: right;\">" . number_format($grandtotal_realisasi, 2) . "</th>
                            <th style=\"text-align: right;\">" . number_format($grandtotal_blmrealisasi, 2) . "</th>
                            <th></th>
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
        $judul_file   = "cetak_pengajuan_dan_realisasi_pinjaman_" . date("Y-m-d_H-i-s") . ".pdf";

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

        $pdf->SetAutoPageBreak(TRUE, 10);
        // $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();

        $pdf->SetFontSize('12');

        $pdf->Cell(0, 0, "Laporan Pengajuan & Realisasi Pinjaman", 0, 0, "C");
        $pdf->SetFontSize('9');

        $pdf->Ln();

        $array_bln  = array_bulan();
        $nama_bulan = $array_bln[$data_req['bulan']];

        $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

        $pdf->Ln();
        $pdf->Ln();

        $koleng[1]  = "7";
        $koleng[2]  = "20";
        $koleng[3]  = "13";
        $koleng[4]  = "50";
        $koleng[5]  = "30";
        $koleng[6]  = "20";
        $koleng[7]  = "10";
        $koleng[8]  = "20";
        $koleng[9]  = "30";
        $koleng[10] = "30";
        $koleng[11] = "30";

        $koleng_sub = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5];
        $koleng_all = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5] + $koleng[6] + $koleng[7];

        $pdf->Cell($koleng[1], 10, "No.", 1, 0, "C");
        $pdf->Cell($koleng[2], 10, "Tgl. Pengajuan", 1, 0, "C", 0, '', 1);
        $pdf->Cell($koleng[3], 10, "NAK", 1, 0, "C");
        $pdf->Cell($koleng[4], 10, "NAMA", 1, 0, "C");
        $pdf->Cell($koleng[5], 10, "Nominal", 1, 0, "C");
        $pdf->Cell($koleng[6], 10, "Jns. Pinjam", 1, 0, "C");
        $pdf->Cell($koleng[7], 10, "Masa", 1, 0, "C");
        $pdf->Cell(($koleng[8] + $koleng[9]), 5, "Realisasi", 1, 0, "C");
        $pdf->Cell($koleng[10], 10, "Yg Blm Realisasi", 1, 0, "C");
        $pdf->Cell($koleng[11], 10, "Status", 1, 0, "C");

        $pdf->Ln(5);

        $koleng_awal = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5] + $koleng[6] + $koleng[7];

        $koleng_sub  = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4];
        $koleng_sub1 = $koleng[6] + $koleng[7] + $koleng[8];

        $pdf->Cell($koleng_awal, 5, "", 0, 0, "C");
        $pdf->Cell($koleng[8], 5, "Tgl", 1, 0, "C");
        $pdf->Cell($koleng[9], 5, "Nominal", 1, 0, "C");

        $pdf->Ln(5);

        $grandtotal_pengajuan    = 0;
        $grandtotal_realisasi    = 0;
        $grandtotal_blmrealisasi = 0;

        $tgl_awal  = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT);
        $tgl_akhir = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT);

        $data_tanggal = $this->db->where("tgl_pinjam between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
            ->group_by("tgl_pinjam")
            ->get("t_simulasi_pinjaman_ang");

        foreach ($data_tanggal->result_array() as $key => $value) {
            $no                    = 1;
            $subtotal_pengajuan    = 0;
            $subtotal_realisasi    = 0;
            $subtotal_blmrealisasi = 0;

            $data_pinjaman = $this->db->where("tgl_pinjam", $value['tgl_pinjam'])
                ->order_by("no_pinjam")
                ->get("t_simulasi_pinjaman_ang");

            foreach ($data_pinjaman->result_array() as $key1 => $value1) {
                // for ($i = 0; $i < 20; $i++) {
                if ($pdf->GetY() > 204) {
                    $pdf->SetFontSize('12');

                    $pdf->Cell(0, 0, "Laporan Pengajuan & Realisasi Pinjaman", 0, 0, "C");
                    $pdf->SetFontSize('9');

                    $pdf->Ln();

                    $array_bln  = array_bulan();
                    $nama_bulan = $array_bln[$data_req['bulan']];

                    $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

                    $pdf->Ln();
                    $pdf->Ln();

                    $pdf->Cell($koleng[1], 10, "No.", 1, 0, "C");
                    $pdf->Cell($koleng[2], 10, "Tgl. Pengajuan", 1, 0, "C", 0, '', 1);
                    $pdf->Cell($koleng[3], 10, "NAK", 1, 0, "C");
                    $pdf->Cell($koleng[4], 10, "NAMA", 1, 0, "C");
                    $pdf->Cell($koleng[5], 10, "Nominal", 1, 0, "C");
                    $pdf->Cell($koleng[6], 10, "Jns. Pinjam", 1, 0, "C");
                    $pdf->Cell($koleng[7], 10, "Masa", 1, 0, "C");
                    $pdf->Cell(($koleng[8] + $koleng[9]), 5, "Realisasi", 1, 0, "C");
                    $pdf->Cell($koleng[10], 10, "Yg Blm Realisasi", 1, 0, "C");
                    $pdf->Cell($koleng[11], 10, "Status", 1, 0, "C");

                    $pdf->Ln(5);

                    $pdf->Cell($koleng_awal, 5, "", 0, 0, "C");
                    $pdf->Cell($koleng[8], 5, "Tgl", 1, 0, "C");
                    $pdf->Cell($koleng[9], 5, "Nominal", 1, 0, "C");

                    $pdf->Ln(5);
                }

                $jml_realisasi     = ($value1['is_realisasi'] == "1") ? $value1['jml_pinjam_realisasi'] : 0;
                $jml_blm_realisasi = ($value1['is_realisasi'] == "0") ? $value1['jml_pinjam'] : 0;
                $is_realisasi      = (($value1['is_realisasi'] == "1") ? "Terealisasi" : "Siap Bayar");

                $pdf->Cell($koleng[1], 0, $no, 0, 0, "R");
                $pdf->Cell($koleng[2], 0, balik_tanggal($value1['tgl_pinjam']), 0, 0, "C");
                $pdf->Cell($koleng[3], 0, $value1['no_ang'], 0, 0, "L", 0, '', 1);
                $pdf->Cell($koleng[4], 0, $value1['nm_ang'], 0, 0, "L", 0, '', 1);
                $pdf->Cell($koleng[5], 0, number_format($value1['jml_pinjam'], 2), 0, 0, "R", 0, '', 1);
                $pdf->Cell($koleng[6], 0, $value1['nm_pinjaman'], 0, 0, "L");
                $pdf->Cell($koleng[7], 0, $value1['tempo_bln'], 0, 0, "C");
                $pdf->Cell($koleng[8], 0, balik_tanggal($value1['tgl_realisasi']), 0, 0, "R", 0, '', 1);
                $pdf->Cell($koleng[9], 0, number_format($jml_realisasi, 2), 0, 0, "R", 0, '', 1);
                $pdf->Cell($koleng[10], 0, number_format($jml_blm_realisasi, 2), 0, 0, "R", 0, '', 1);
                $pdf->Cell($koleng[11], 0, $is_realisasi, 0, 0, "L");

                $pdf->Ln();

                $no++;
                $subtotal_pengajuan += $value1['jml_pinjam'];
                $subtotal_realisasi += $jml_realisasi;
                $subtotal_blmrealisasi += $jml_blm_realisasi;

                $grandtotal_pengajuan += $value1['jml_pinjam'];
                $grandtotal_realisasi += $jml_realisasi;
                $grandtotal_blmrealisasi += $jml_blm_realisasi;
            }
            // }

            if ($pdf->GetY() > 204) {
                $pdf->SetFontSize('12');

                $pdf->Cell(0, 0, "Laporan Pengajuan & Realisasi Pinjaman", 0, 0, "C");
                $pdf->SetFontSize('9');

                $pdf->Ln();

                $array_bln  = array_bulan();
                $nama_bulan = $array_bln[$data_req['bulan']];

                $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

                $pdf->Ln();
                $pdf->Ln();

                $pdf->Cell($koleng[1], 10, "No.", 1, 0, "C");
                $pdf->Cell($koleng[2], 10, "Tgl. Pengajuan", 1, 0, "C", 0, '', 1);
                $pdf->Cell($koleng[3], 10, "NAK", 1, 0, "C");
                $pdf->Cell($koleng[4], 10, "NAMA", 1, 0, "C");
                $pdf->Cell($koleng[5], 10, "Nominal", 1, 0, "C");
                $pdf->Cell($koleng[6], 10, "Jns. Pinjam", 1, 0, "C");
                $pdf->Cell($koleng[7], 10, "Masa", 1, 0, "C");
                $pdf->Cell(($koleng[8] + $koleng[9]), 5, "Realisasi", 1, 0, "C");
                $pdf->Cell($koleng[10], 10, "Yg Blm Realisasi", 1, 0, "C");
                $pdf->Cell($koleng[11], 10, "Status", 1, 0, "C");

                $pdf->Ln(5);

                $pdf->Cell($koleng_awal, 5, "", 0, 0, "C");
                $pdf->Cell($koleng[8], 5, "Tgl", 1, 0, "C");
                $pdf->Cell($koleng[9], 5, "Nominal", 1, 0, "C");

                $pdf->Ln(5);
            }

            $pdf->SetFont("", "B");

            $pdf->Cell($koleng_sub, 0, "Sub Total", "TB", 0, "R");
            $pdf->Cell($koleng[5], 0, number_format($subtotal_pengajuan, 2), "TB", 0, "R", 0, '', 1);
            $pdf->Cell($koleng_sub1, 0, "", "TB", 0, "R");
            $pdf->Cell($koleng[9], 0, number_format($subtotal_realisasi, 2), "TB", 0, "R", 0, '', 1);
            $pdf->Cell($koleng[10], 0, number_format($subtotal_blmrealisasi, 2), "TB", 0, "R", 0, '', 1);
            $pdf->Cell($koleng[11], 0, "", "TB", 0, "R");
            $pdf->Ln();

            $pdf->Cell(0, 0, "", 0, 0, "R");
            $pdf->Ln();

            $pdf->SetFont("", "");
        }

        if ($pdf->GetY() > 204) {
            $pdf->SetFontSize('12');

            $pdf->Cell(0, 0, "Laporan Pengajuan & Realisasi Pinjaman", 0, 0, "C");
            $pdf->SetFontSize('9');

            $pdf->Ln();

            $array_bln  = array_bulan();
            $nama_bulan = $array_bln[$data_req['bulan']];

            $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

            $pdf->Ln();
            $pdf->Ln();

            $pdf->Cell($koleng[1], 10, "No.", 1, 0, "C");
            $pdf->Cell($koleng[2], 10, "Tgl. Pengajuan", 1, 0, "C", 0, '', 1);
            $pdf->Cell($koleng[3], 10, "NAK", 1, 0, "C");
            $pdf->Cell($koleng[4], 10, "NAMA", 1, 0, "C");
            $pdf->Cell($koleng[5], 10, "Nominal", 1, 0, "C");
            $pdf->Cell($koleng[6], 10, "Jns. Pinjam", 1, 0, "C");
            $pdf->Cell($koleng[7], 10, "Masa", 1, 0, "C");
            $pdf->Cell(($koleng[8] + $koleng[9]), 5, "Realisasi", 1, 0, "C");
            $pdf->Cell($koleng[10], 10, "Yg Blm Realisasi", 1, 0, "C");
            $pdf->Cell($koleng[11], 10, "Status", 1, 0, "C");

            $pdf->Ln(5);

            $pdf->Cell($koleng_awal, 5, "", 0, 0, "C");
            $pdf->Cell($koleng[8], 5, "Tgl", 1, 0, "C");
            $pdf->Cell($koleng[9], 5, "Nominal", 1, 0, "C");

            $pdf->Ln(5);
        }

        $pdf->SetFont("", "B");

        $pdf->Cell($koleng_sub, 0, "Grand Total", "TB", 0, "R");
        $pdf->Cell($koleng[5], 0, number_format($grandtotal_pengajuan, 2), "TB", 0, "R", 0, '', 1);
        $pdf->Cell($koleng_sub1, 0, "", "TB", 0, "R");
        $pdf->Cell($koleng[9], 0, number_format($grandtotal_realisasi, 2), "TB", 0, "R", 0, '', 1);
        $pdf->Cell($koleng[10], 0, number_format($grandtotal_blmrealisasi, 2), "TB", 0, "R", 0, '', 1);
        $pdf->Cell($koleng[11], 0, "", "TB", 0, "R");

        $pdf->Output($judul_file, 'I');
    }
}
