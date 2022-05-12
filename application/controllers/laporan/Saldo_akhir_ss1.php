<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Saldo_akhir_ss1 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();

        $this->load->model("laporan_model");

        $this->querySelect = "a.*, b.no_peg, b.nm_ang, b.kd_prsh, b.nm_prsh, b.nm_dep, b.nm_bagian";
    }

    public function index()
    {
        $bulan = get_option_tag(array_bulan(), "BULAN");

        $data['judul_menu'] = "Laporan Saldo Akhir Simpanan Sukarela 1";
        $data['bulan']      = $bulan;

        $this->template->view("laporan/saldo_akhir_ss1", $data);
    }

    private function querySaldo($tahun, $bulan, $hari)
    {
        return "SELECT a.*, b.no_peg, b.nm_ang
            from (
                SELECT a.*, ifnull(b.jml_setor, 0) jml_setor, ifnull(b.jml_ambil, 0) jml_ambil, (a.saldo_awal+ifnull(b.jml_setor, 0)-ifnull(b.jml_ambil, 0)) saldo_akhir
                FROM (
                    SELECT no_ang, SUM(if(blth < '" . $tahun . "-" . $bulan . "', saldo_akhir, 0)) saldo_awal
                    FROM t_saldo_simpanan
                    WHERE blth LIKE '" . $tahun . "%' AND blth <= '" . $tahun . "-" . $bulan . "'
                    GROUP BY no_ang
                ) a
                left JOIN (
                    SELECT no_ang, ifnull(SUM(IF(kredit_debet = 'K', jumlah, 0)), 0) jml_setor, ifnull(SUM(IF(kredit_debet = 'D', jumlah, 0)), 0) jml_ambil
                    FROM t_simpanan_ang
                    WHERE tgl_simpan BETWEEN '" . $tahun . "-" . $bulan . "-01' and '" . $tahun . "-" . $bulan . "-" . $hari . "'
                    GROUP BY no_ang
                ) b
                ON a.no_ang = b.no_ang
            ) a left join t_nasabah b
            on a.no_ang=b.no_ang";
    }

    public function tampilkan()
    {
        set_time_limit(0);

        $data_req = get_request();

        if ($data_req) {
            $exTgl = explode("-", $data_req['tgl_simpan']);
            $hari  = $exTgl[0];
            $bulan = $exTgl[1];
            $tahun = $exTgl[2];

            $querySaldo = $this->querySaldo($tahun, $bulan, $hari);
            $dataSaldo  = $this->db->query($querySaldo);

            $view = "<strong>Saldo Akhir periode tanggal 01-" . $bulan . "-" . $tahun . " s.d. " . $data_req['tgl_simpan'] . "</strong>
                <table class=\"table table-bordered table-condensed\" border=\"1\">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAK</th>
                            <th>NIK</th>
                            <th>NAMA</th>
                            <th>SETOR</th>
                            <th>AMBIL</th>
                            <th>SALDO AKHIR</th>
                        </tr>
                    </thead>
                    <tbody>";

            $no = 1;

            foreach ($dataSaldo->result_array() as $key => $value) {
                $view .= "<tr>
                    <td class=\"text-right\">" . $no . "</td>
                    <td>" . $value['no_ang'] . "</td>
                    <td>" . $value['no_peg'] . "</td>
                    <td>" . $value['nm_ang'] . "</td>
                    <td class=\"text-right\">" . number_format($value['jml_setor'], 2) . "</td>
                    <td class=\"text-right\">" . number_format($value['jml_ambil'], 2) . "</td>
                    <td class=\"text-right\">" . number_format($value['saldo_akhir'], 2) . "</td>
                </tr>";

                $no++;
            }

            $view .= "
                </tbody>
            </table>";

            echo $view;
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
        $judul_file   = "cetak_saldo_akhir_ss1_" . $data_req['tgl_simpan'] . "_" . date("Y-m-d_H-i-s") . ".pdf";

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

        $pdf->SetAutoPageBreak(true, "15");
        // $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();

        $pdf->SetFontSize('11');

        $pdf->Cell(0, 0, "Laporan Saldo Akhir SS1", 0, 0, "C");
        $pdf->SetFontSize('8');

        $pdf->Ln();

        // $array_bln  = array_bulan();
        // $nama_bulan = $array_bln[$data_req['bulan']];

        $exTgl = explode("-", $data_req['tgl_simpan']);
        $hari  = $exTgl[0];
        $bulan = $exTgl[1];
        $tahun = $exTgl[2];

        $pdf->Cell(0, 0, "Periode tanggal 01-" . $bulan . "-" . $tahun . " s.d. " . $data_req['tgl_simpan'], 0, 0, "C");
        $pdf->Ln();
        $pdf->Ln();

        $querySaldo = $this->querySaldo($tahun, $bulan, $hari);
        $dataSaldo  = $this->db->query($querySaldo);

        $koleng[1] = "10";
        $koleng[2] = "15";
        $koleng[3] = "17";
        $koleng[4] = "60";
        $koleng[5] = "30";
        $koleng[6] = "30";
        $koleng[7] = "30";

        $pdf->Cell($koleng[1], 0, "NO", "TB", 0, "C");
        $pdf->Cell($koleng[2], 0, "NAK", "TB", 0, "C");
        $pdf->Cell($koleng[3], 0, "NIK", "TB", 0, "C");
        $pdf->Cell($koleng[4], 0, "NAMA", "TB", 0, "C");
        $pdf->Cell($koleng[5], 0, "SETOR", "TB", 0, "C");
        $pdf->Cell($koleng[6], 0, "AMBIL", "TB", 0, "C");
        $pdf->Cell($koleng[7], 0, "SALDO AKHIR", "TB", 0, "C");
        $pdf->Ln();

        $no = 1;

        foreach ($dataSaldo->result_array() as $key => $value) {
            if ($pdf->GetY() > 278) {
                $pdf->Cell($koleng[1], 0, "NO", "TB", 0, "C");
                $pdf->Cell($koleng[2], 0, "NAK", "TB", 0, "C");
                $pdf->Cell($koleng[3], 0, "NIK", "TB", 0, "C");
                $pdf->Cell($koleng[4], 0, "NAMA", "TB", 0, "C");
                $pdf->Cell($koleng[5], 0, "SETOR", "TB", 0, "C");
                $pdf->Cell($koleng[6], 0, "AMBIL", "TB", 0, "C");
                $pdf->Cell($koleng[7], 0, "SALDO AKHIR", "TB", 0, "C");
                $pdf->Ln();
            }

            $pdf->Cell($koleng[1], 0, $no);
            $pdf->Cell($koleng[2], 0, $value['no_ang']);
            $pdf->Cell($koleng[3], 0, $value['no_peg'], 0, 0, "", 0, 0, 1);
            $pdf->Cell($koleng[4], 0, $value['nm_ang'], 0, 0, "", 0, 0, 1);
            $pdf->Cell($koleng[5], 0, number_format($value['jml_setor'], 2), 0, 0, "R");
            $pdf->Cell($koleng[6], 0, number_format($value['jml_ambil'], 2), 0, 0, "R");
            $pdf->Cell($koleng[7], 0, number_format($value['saldo_akhir'], 2), 0, 0, "R");
            $pdf->Ln();

            $no++;
        }

        $pdf->Output($judul_file, 'I');
    }

    public function excel()
    {
        $file = "saldoAkhir_ss1_" . date("Y-m-d_H-i-s") . ".xls";

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . $file);

        $this->tampilkan();
    }
}
