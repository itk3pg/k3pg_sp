<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_ledger extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();
    }

    public function index()
    {
        $bulan = get_option_tag(array_bulan(), "BULAN");

        $data['judul_menu'] = "Laporan Transaksi Ledger";
        $data['bulan']      = $bulan;

        $this->template->view("laporan/transaksi_ledger", $data);
    }

    public function tampilkan()
    {
        $data_req = get_request();

        if ($data_req) {
            $laporan = "<table class=\"table table-bordered table-condensed table-striped\" style=\"white-space: nowrap\">
                    <thead>
                        <tr style=\"\">
                            <th style=\"text-align: center; vertical-align: middle;\">No. Acc</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Perkiraan</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Tanggal</th>
                            <th style=\"text-align: center; vertical-align: middle;\">No. Ref</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Kredit</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Debet</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>";

            $tgl_awal  = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT);
            $tgl_akhir = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT);

            $list_no_ref = $this->db->where("tgl_gl between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
                ->group_by("no_ref_bukti")
                ->get("t_general_ledger");

            foreach ($list_no_ref->result_array() as $key => $value) {
                $saldo = 0;

                $list_detail = $this->db->select("*, if(kredit_debet = 'D', jumlah, 0) debet, if(kredit_debet = 'K', jumlah, 0) kredit")
                    ->where("no_ref_bukti", $value['no_ref_bukti'])
                    ->order_by("kredit_debet, kd_akun")
                    ->get("t_general_ledger");

                foreach ($list_detail->result_array() as $key1 => $value1) {
                    $saldo += $value1['debet'];
                    $saldo -= $value1['kredit'];

                    $laporan .= "
                        <tr>
                            <td>" . $value1['kd_akun'] . "</td>
                            <td>" . $value1['nm_akun'] . "</td>
                            <td>" . balik_tanggal($value1['tgl_gl']) . "</td>
                            <td>" . $value1['no_ref_bukti'] . "</td>
                            <td style=\"text-align: right\">" . number_format($value1['debet'], 2) . "</td>
                            <td style=\"text-align: right\">" . number_format($value1['kredit'], 2) . "</td>
                            <td style=\"text-align: right\">" . number_format($saldo, 2) . "</td>
                        </tr>
                    ";
                }

                $laporan .= "
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    ";
            }
        }

        $laporan .= "</tbody>
                </table>";

        echo $laporan;
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
        $judul_file   = "cetak_transaksi_ledger_" . date("Y-m-d_H-i-s") . ".pdf";

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

        $pdf->Cell(0, 0, "Laporan Entri Transaksi General Ledger", 0, 0, "C");
        $pdf->SetFontSize('9');

        $pdf->Ln();

        $array_bln  = array_bulan();
        $nama_bulan = $array_bln[$data_req['bulan']];

        $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

        $pdf->Ln();
        $pdf->Ln();

        $koleng[1] = "15";
        $koleng[2] = "50";
        $koleng[3] = "20";
        $koleng[4] = "20";
        $koleng[5] = "33";
        $koleng[6] = "33";
        $koleng[7] = "33";

        $koleng_sub = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5];
        $koleng_all = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5] + $koleng[6] + $koleng[7];

        $pdf->Cell($koleng[1], 0, "No. Acc", 1, 0, "C");
        $pdf->Cell($koleng[2], 0, "Perkiraan", 1, 0, "C");
        $pdf->Cell($koleng[3], 0, "Tanggal", 1, 0, "C");
        $pdf->Cell($koleng[4], 0, "No. Ref", 1, 0, "C");
        $pdf->Cell($koleng[5], 0, "Kredit", 1, 0, "C");
        $pdf->Cell($koleng[6], 0, "Debet", 1, 0, "C");
        $pdf->Cell($koleng[7], 0, "Saldo", 1, 0, "C");

        $pdf->Ln();

        $tgl_awal  = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT);
        $tgl_akhir = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT);

        $list_no_ref = $this->db->where("tgl_gl between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
            ->group_by("no_ref_bukti")
            ->get("t_general_ledger");

        foreach ($list_no_ref->result_array() as $key => $value) {
            $saldo = 0;

            $list_detail = $this->db->select("*, if(kredit_debet = 'D', jumlah, 0) debet, if(kredit_debet = 'K', jumlah, 0) kredit")
                ->where("no_ref_bukti", $value['no_ref_bukti'])
                ->order_by("kredit_debet, kd_akun")
                ->get("t_general_ledger");

            foreach ($list_detail->result_array() as $key1 => $value1) {
                $saldo += $value1['debet'];
                $saldo -= $value1['kredit'];

                $pdf->Cell($koleng[1], 0, $value1['kd_akun'], 0, 0, "C");
                $pdf->Cell($koleng[2], 0, $value1['nm_akun'], 0, 0, "L", 0, '', 1);
                $pdf->Cell($koleng[3], 0, balik_tanggal($value1['tgl_gl']), 0, 0, "C");
                $pdf->Cell($koleng[4], 0, $value1['no_ref_bukti'], 0, 0, "L", 0, '', 1);
                $pdf->Cell($koleng[5], 0, number_format($value1['debet'], 2), 0, 0, "R", 0, '', 1);
                $pdf->Cell($koleng[6], 0, number_format($value1['kredit'], 2), 0, 0, "R", 0, '', 1);
                $pdf->Cell($koleng[7], 0, number_format($saldo, 2), 0, 0, "R", 0, '', 1);

                $pdf->Ln();
            }

            $pdf->Cell($koleng[1], 0, "", 'T', 0, "C");
            $pdf->Cell($koleng[2], 0, "", 'T', 0, "C");
            $pdf->Cell($koleng[3], 0, "", 'T', 0, "C");
            $pdf->Cell($koleng[4], 0, "", 'T', 0, "C");
            $pdf->Cell($koleng[5], 0, "", 'T', 0, "C");
            $pdf->Cell($koleng[6], 0, "", 'T', 0, "C");
            $pdf->Cell($koleng[7], 0, "", 'T', 0, "C");

            $pdf->Ln();
        }

        $pdf->Output($judul_file, 'I');
    }
}
