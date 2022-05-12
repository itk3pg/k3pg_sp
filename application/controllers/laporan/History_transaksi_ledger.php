<?php

defined('BASEPATH') or exit('No direct script access allowed');

class History_transaksi_ledger extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();
    }

    public function index()
    {
        $bulan = get_option_tag(array_bulan(), "BULAN");

        $data['judul_menu'] = "Laporan History Transaksi Ledger";
        $data['bulan']      = $bulan;

        $this->template->view("laporan/history_transaksi_ledger", $data);
    }

    public function tampilkan()
    {
        $data_req = get_request();

        if ($data_req) {
            $laporan = "";

            $tgl_awal  = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT);
            $tgl_akhir = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT);

            $data_akun = $this->db->where("tgl_gl between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
                ->group_by("kd_akun")
                ->get("t_general_ledger");

            if ($data_akun->num_rows() > 0) {
                foreach ($data_akun->result_array() as $key => $value) {
                    $laporan .= "
                        <h5>" . $value['kd_akun'] . " " . $value['nm_akun'] . "</h5>
                        <table class=\"table table-bordered table-condensed table-striped\" style=\"white-space: nowrap\">
                            <thead>
                                <tr style=\"\">
                                    <th style=\"text-align: center; vertical-align: middle;\">Tanggal</th>
                                    <th style=\"text-align: center; vertical-align: middle;\">No. Ref</th>
                                    <th style=\"text-align: center; vertical-align: middle;\">Keterangan</th>
                                    <th style=\"text-align: center; vertical-align: middle;\">Debet</th>
                                    <th style=\"text-align: center; vertical-align: middle;\">Kredit</th>
                                    <th style=\"text-align: center; vertical-align: middle;\">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>";

                    $debet  = 0;
                    $kredit = 0;
                    $saldo  = 0;

                    $list_no_ref = $this->db->select("tgl_gl, no_ref_bukti, nm_akun, kredit_debet,
                            ifnull(if(kredit_debet = 'D', jumlah, 0), 0) debet,
                            ifnull(if(kredit_debet = 'K', jumlah, 0), 0) kredit,
                            jumlah, ket")
                        ->where("tgl_gl between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
                        ->where("kd_akun", $value['kd_akun'])
                        ->order_by("no_ref_bukti")
                        ->get("t_general_ledger");

                    if ($list_no_ref->num_rows() > 0) {
                        foreach ($list_no_ref->result_array() as $key1 => $value1) {
                            $saldo += $value1['debet'];
                            $saldo -= $value1['kredit'];

                            $laporan .= "
                                <tr>
                                    <td>" . balik_tanggal($value1['tgl_gl']) . "</td>
                                    <td>" . $value1['no_ref_bukti'] . "</td>
                                    <td>" . $value1['ket'] . "</td>
                                    <td style=\"text-align: right;\">" . number_format($value1['debet'], 2) . "</td>
                                    <td style=\"text-align: right;\">" . number_format($value1['kredit'], 2) . "</td>
                                    <td style=\"text-align: right;\">" . number_format($saldo, 2) . "</td>
                                </tr>
                            ";

                            $debet += $value1['debet'];
                            $kredit += $value1['kredit'];
                        }
                    }

                    $laporan .= "</tbody>
                            <tfoot>
                                <tr>
                                    <th colspan=\"3\" style=\"text-align: right;\">Total</th>
                                    <th style=\"text-align: right;\">" . number_format($debet, 2) . "</th>
                                    <th style=\"text-align: right;\">" . number_format($kredit, 2) . "</th>
                                    <th style=\"text-align: right;\">" . number_format($saldo, 2) . "</th>
                                </tr>
                            </tfoot>
                        </table>
                        <br>";
                }
            }

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
        $judul_file   = "cetak_history_transaksi_ledger_" . date("Y-m-d_H-i-s") . ".pdf";

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

        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();

        $pdf->SetFontSize('12');

        $pdf->Cell(0, 0, "Laporan History Transaksi General Ledger", 0, 0, "C");
        $pdf->SetFontSize('9');

        $pdf->Ln();

        $array_bln  = array_bulan();
        $nama_bulan = $array_bln[$data_req['bulan']];

        $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

        $pdf->Ln();
        $pdf->Ln();

        $koleng[1] = "20";
        $koleng[2] = "20";
        $koleng[3] = "55";
        $koleng[4] = "35";
        $koleng[5] = "35";
        $koleng[6] = "40";

        $tgl_awal  = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT);
        $tgl_akhir = $data_req['tahun'] . "-" . $data_req['bulan'] . "-" . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT);

        $data_akun = $this->db->where("tgl_gl between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
            ->group_by("kd_akun")
            ->get("t_general_ledger");

        if ($data_akun->num_rows() > 0) {
            foreach ($data_akun->result_array() as $key => $value) {
                $pdf->Cell(0, 0, $value['kd_akun'] . " " . $value['nm_akun'], 0, 0, "L");

                $pdf->Ln();

                $pdf->Cell($koleng[1], 0, "Tanggal", 1, 0, "C");
                $pdf->Cell($koleng[2], 0, "No. Ref", 1, 0, "C");
                $pdf->Cell($koleng[3], 0, "Keterangan", 1, 0, "C");
                $pdf->Cell($koleng[4], 0, "Debet", 1, 0, "C");
                $pdf->Cell($koleng[5], 0, "Kredit", 1, 0, "C");
                $pdf->Cell($koleng[6], 0, "Saldo", 1, 0, "C");

                $pdf->Ln();

                $debet  = 0;
                $kredit = 0;
                $saldo  = 0;

                $list_no_ref = $this->db->select("tgl_gl, no_ref_bukti, nm_akun, kredit_debet,
                        ifnull(if(kredit_debet = 'D', jumlah, 0), 0) debet,
                        ifnull(if(kredit_debet = 'K', jumlah, 0), 0) kredit,
                        jumlah, ket")
                    ->where("tgl_gl between '" . $tgl_awal . "' and '" . $tgl_akhir . "'")
                    ->where("kd_akun", $value['kd_akun'])
                    ->order_by("no_ref_bukti")
                    ->get("t_general_ledger");

                if ($list_no_ref->num_rows() > 0) {
                    foreach ($list_no_ref->result_array() as $key1 => $value1) {
                        $saldo += $value1['debet'];
                        $saldo -= $value1['kredit'];

                        $pdf->Cell($koleng[1], 0, balik_tanggal($value1['tgl_gl']), 0, 0, "C", 0, '', 1);
                        $pdf->Cell($koleng[2], 0, $value1['no_ref_bukti'], 0, 0, "L", 0, '', 1);
                        $pdf->Cell($koleng[3], 0, $value1['ket'], 0, 0, "L", 0, '', 1);
                        $pdf->Cell($koleng[4], 0, number_format($value1['debet'], 2), 0, 0, "R", 0, '', 1);
                        $pdf->Cell($koleng[5], 0, number_format($value1['kredit'], 2), 0, 0, "R", 0, '', 1);
                        $pdf->Cell($koleng[6], 0, number_format($saldo, 2), 0, 0, "R", 0, '', 1);

                        $pdf->Ln();

                        $debet += $value1['debet'];
                        $kredit += $value1['kredit'];
                    }
                }

                $pdf->Ln();

                $pdf->Cell(($koleng[1] + $koleng[2] + $koleng[3]), 0, "Total", "T", 0, "R", 0, '', 1);
                // $pdf->Cell($koleng[2], 0, $value1['no_ref_bukti'], 0, 0, "L", 0, '', 1);
                // $pdf->Cell($koleng[3], 0, $value1['ket'], 0, 0, "R", 0, '', 1);
                $pdf->Cell($koleng[4], 0, number_format($debet, 2), "T", 0, "R", 0, '', 1);
                $pdf->Cell($koleng[5], 0, number_format($kredit, 2), "T", 0, "R", 0, '', 1);
                $pdf->Cell($koleng[6], 0, number_format($saldo, 2), "T", 0, "R", 0, '', 1);

                $pdf->Ln();
                $pdf->Ln();
            }
        }

        $pdf->Output($judul_file, 'I');
    }

}
