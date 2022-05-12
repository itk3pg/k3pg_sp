<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi_general_ledger extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();
    }

    public function index()
    {
        $bulan = get_option_tag(array_bulan(), "BULAN");

        $data['judul_menu'] = "Laporan Mutasi General Ledger";
        $data['bulan']      = $bulan;

        $this->template->view("laporan/mutasi_transaksi_ledger", $data);
    }

    public function tampilkan()
    {
        $data_req = get_request();

        if ($data_req) {
            $laporan = "<table class=\"table table-bordered table-condensed table-striped\" style=\"white-space: nowrap\">
                    <thead>
                        <tr style=\"\">
                            <th rowspan=\"2\" style=\"text-align: center; vertical-align: middle;\">No. Acc</th>
                            <th rowspan=\"2\" style=\"text-align: center; vertical-align: middle;\">Perkiraan</th>
                            <th colspan=\"2\" style=\"text-align: center; vertical-align: middle;\">Saldo Awal</th>
                            <th colspan=\"2\" style=\"text-align: center; vertical-align: middle;\">Mutasi</th>
                            <th colspan=\"2\" style=\"text-align: center; vertical-align: middle;\">Saldo Akhir</th>
                        </tr>
                        <tr>
                            <th style=\"text-align: center; vertical-align: middle;\">Debet</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Kredit</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Debet</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Kredit</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Debet</th>
                            <th style=\"text-align: center; vertical-align: middle;\">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>";

            $t_saldo_awal_debet   = 0;
            $t_saldo_awal_kredit  = 0;
            $t_mutasi_debet       = 0;
            $t_mutasi_kredit      = 0;
            $t_saldo_akhir_debet  = 0;
            $t_saldo_akhir_kredit = 0;
			
			if($data_req['bulan'] == 1){
				$tahun_lalu = $data_req['tahun']-1;
				$blth_awal  = $tahun_lalu . "-00";
			}
			else{
				$blth_awal  = $data_req['tahun'] . "-00";
			}
           
            $blth_akhir = $data_req['tahun'] . "-" . $data_req['bulan'];

            $query_mutasi = "SELECT kd_akun, nm_akun,
                sum(if(blth < '" . $blth_akhir . "', debet, 0)) saldo_awal_debet,
                sum(if(blth < '" . $blth_akhir . "', kredit, 0)) saldo_awal_kredit,
                sum(if(blth = '" . $blth_akhir . "', debet, 0)) mutasi_debet,
                sum(if(blth = '" . $blth_akhir . "', kredit, 0)) mutasi_kredit,
                sum(debet) saldo_akhir_debet,
                sum(kredit) saldo_akhir_kredit
            FROM t_saldo_general_ledger
            WHERE blth BETWEEN '" . $blth_awal . "' AND '" . $blth_akhir . "'
            GROUP BY kd_akun";
			
            $data_mutasi = $this->db->query($query_mutasi);

            foreach ($data_mutasi->result_array() as $key => $value) {
                $laporan .= "
                        <tr>
                            <td>" . $value['kd_akun'] . "</td>
                            <td>" . $value['nm_akun'] . "</td>
                            <td style=\"text-align: right\">" . number_format($value['saldo_awal_debet'], 2) . "</td>
                            <td style=\"text-align: right\">" . number_format($value['saldo_awal_kredit'], 2) . "</td>
                            <td style=\"text-align: right\">" . number_format($value['mutasi_debet'], 2) . "</td>
                            <td style=\"text-align: right\">" . number_format($value['mutasi_kredit'], 2) . "</td>
                            <td style=\"text-align: right\">" . number_format($value['saldo_akhir_debet'], 2) . "</td>
                            <td style=\"text-align: right\">" . number_format($value['saldo_akhir_kredit'], 2) . "</td>
                        </tr>
                    ";

                $t_saldo_awal_debet += $value['saldo_awal_debet'];
                $t_saldo_awal_kredit += $value['saldo_awal_kredit'];
                $t_mutasi_debet += $value['mutasi_debet'];
                $t_mutasi_kredit += $value['mutasi_kredit'];
                $t_saldo_akhir_debet += $value['saldo_akhir_debet'];
                $t_saldo_akhir_kredit += $value['saldo_akhir_kredit'];
            }
        }

        $laporan .= "</tbody>
                <tfoot>
                    <tr>
                        <th colspan=\"2\" style=\"text-align: right\">Total</th>
                        <th style=\"text-align: right\">" . number_format($t_saldo_awal_debet, 2) . "</th>
                        <th style=\"text-align: right\">" . number_format($t_saldo_awal_kredit, 2) . "</th>
                        <th style=\"text-align: right\">" . number_format($t_mutasi_debet, 2) . "</th>
                        <th style=\"text-align: right\">" . number_format($t_mutasi_kredit, 2) . "</th>
                        <th style=\"text-align: right\">" . number_format($t_saldo_akhir_debet, 2) . "</th>
                        <th style=\"text-align: right\">" . number_format($t_saldo_akhir_kredit, 2) . "</th>
                    </tr>
                </tfoot>
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

        $pdf = new mypdf("L", "mm", $ukuran_kertas);

        $kreator      = "MBagusRD";
        $judul_file   = "Cetak PDF";
        $judul_header = "Koperasi Karyawan Keluarga Besar Petrokimia Gresik";
        $teks_header  = NAMA_PHP;
        $judul_file   = "cetak_mutasi_general_ledger_" . date("Y-m-d_H-i-s") . ".pdf";

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

        $pdf->Cell(0, 0, "Laporan Mutasi General Ledger", 0, 0, "C");
        $pdf->SetFontSize('9');

        $pdf->Ln();

        $array_bln  = array_bulan();
        $nama_bulan = $array_bln[$data_req['bulan']];

        $pdf->Cell(0, 0, "Periode : " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");
        // $pdf->Cell(0, 0, "Periode : " . str_pad($data_req['tgl_awal'], 2, "0", STR_PAD_LEFT) . " - " . str_pad($data_req['tgl_akhir'], 2, "0", STR_PAD_LEFT) . " " . $nama_bulan . " " . $data_req['tahun'], 0, 0, "C");

        $pdf->Ln();
        $pdf->Ln();

        $koleng[1] = "15";
        $koleng[2] = "55";
        $koleng[3] = "33";
        $koleng[4] = "33";
        $koleng[5] = "33";
        $koleng[6] = "33";
        $koleng[7] = "33";
        $koleng[8] = "33";

        $koleng_sub = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5];
        $koleng_all = $koleng[1] + $koleng[2] + $koleng[3] + $koleng[4] + $koleng[5] + $koleng[6] + $koleng[7];

        $pdf->Cell($koleng[1], 0, "No. Acc", "TLR", 0, "C");
        $pdf->Cell($koleng[2], 0, "Perkiraan", "TLR", 0, "C");
        $pdf->Cell($koleng[3] + $koleng[4], 0, "Saldo Awal", 1, 0, "C");
        $pdf->Cell($koleng[5] + $koleng[6], 0, "Mutasi", 1, 0, "C");
        $pdf->Cell($koleng[7] + $koleng[8], 0, "Saldo Akhir", 1, 0, "C");

        $pdf->Ln();

        $pdf->Cell($koleng[1], 0, "", "BLR", 0, "C");
        $pdf->Cell($koleng[2], 0, "", "BLR", 0, "C");
        $pdf->Cell($koleng[3], 0, "Debet", 1, 0, "C");
        $pdf->Cell($koleng[4], 0, "Kredit", 1, 0, "C");
        $pdf->Cell($koleng[5], 0, "Debet", 1, 0, "C");
        $pdf->Cell($koleng[6], 0, "Kredit", 1, 0, "C");
        $pdf->Cell($koleng[7], 0, "Debet", 1, 0, "C");
        $pdf->Cell($koleng[8], 0, "Kredit", 1, 0, "C");

        $pdf->Ln();

        $t_saldo_awal_debet   = 0;
        $t_saldo_awal_kredit  = 0;
        $t_mutasi_debet       = 0;
        $t_mutasi_kredit      = 0;
        $t_saldo_akhir_debet  = 0;
        $t_saldo_akhir_kredit = 0;

        if($data_req['bulan'] == 1){
			$tahun_lalu = $data_req['tahun']-1;
			$blth_awal  = $tahun_lalu . "-00";
		}
		else{
			$blth_awal  = $data_req['tahun'] . "-00";
		}
        $blth_akhir = $data_req['tahun'] . "-" . $data_req['bulan'];

        $query_mutasi = "SELECT kd_akun, nm_akun,
                sum(if(blth < '" . $blth_akhir . "', debet, 0)) saldo_awal_debet,
                sum(if(blth < '" . $blth_akhir . "', kredit, 0)) saldo_awal_kredit,
                sum(if(blth = '" . $blth_akhir . "', debet, 0)) mutasi_debet,
                sum(if(blth = '" . $blth_akhir . "', kredit, 0)) mutasi_kredit,
                sum(debet) saldo_akhir_debet,
                sum(kredit) saldo_akhir_kredit
            FROM t_saldo_general_ledger
            WHERE blth BETWEEN '" . $blth_awal . "' AND '" . $blth_akhir . "'
            GROUP BY kd_akun";

        $data_mutasi = $this->db->query($query_mutasi);

        foreach ($data_mutasi->result_array() as $key => $value) {
            $pdf->Cell($koleng[1], 0, $value['kd_akun'], 0, 0, "L", 0, '', 1);
            $pdf->Cell($koleng[2], 0, $value['nm_akun'], 0, 0, "L", 0, '', 1);
            $pdf->Cell($koleng[3], 0, number_format($value['saldo_awal_debet'], 2), 0, 0, "R", 0, '', 1);
            $pdf->Cell($koleng[4], 0, number_format($value['saldo_awal_kredit'], 2), 0, 0, "R", 0, '', 1);
            $pdf->Cell($koleng[5], 0, number_format($value['mutasi_debet'], 2), 0, 0, "R", 0, '', 1);
            $pdf->Cell($koleng[6], 0, number_format($value['mutasi_kredit'], 2), 0, 0, "R", 0, '', 1);
            $pdf->Cell($koleng[7], 0, number_format($value['saldo_akhir_debet'], 2), 0, 0, "R", 0, '', 1);
            $pdf->Cell($koleng[8], 0, number_format($value['saldo_akhir_kredit'], 2), 0, 0, "R", 0, '', 1);

            $pdf->Ln();

            $t_saldo_awal_debet += $value['saldo_awal_debet'];
            $t_saldo_awal_kredit += $value['saldo_awal_kredit'];
            $t_mutasi_debet += $value['mutasi_debet'];
            $t_mutasi_kredit += $value['mutasi_kredit'];
            $t_saldo_akhir_debet += $value['saldo_akhir_debet'];
            $t_saldo_akhir_kredit += $value['saldo_akhir_kredit'];
        }

        $pdf->Ln();

        $pdf->Cell($koleng[1], 0, "", 'TB', 0, "L", 0, '', 1);
        $pdf->Cell($koleng[2], 0, "Total", 'TB', 0, "R", 0, '', 1);
        $pdf->Cell($koleng[3], 0, number_format($t_saldo_awal_debet, 2), 'TB', 0, "R", 0, '', 1);
        $pdf->Cell($koleng[4], 0, number_format($t_saldo_awal_kredit, 2), 'TB', 0, "R", 0, '', 1);
        $pdf->Cell($koleng[5], 0, number_format($t_mutasi_debet, 2), 'TB', 0, "R", 0, '', 1);
        $pdf->Cell($koleng[6], 0, number_format($t_mutasi_kredit, 2), 'TB', 0, "R", 0, '', 1);
        $pdf->Cell($koleng[7], 0, number_format($t_saldo_akhir_debet, 2), 'TB', 0, "R", 0, '', 1);
        $pdf->Cell($koleng[8], 0, number_format($t_saldo_akhir_kredit, 2), 'TB', 0, "R", 0, '', 1);

        $pdf->Output($judul_file, 'I');
    }
}
