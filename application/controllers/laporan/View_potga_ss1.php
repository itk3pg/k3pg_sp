<?php

defined('BASEPATH') or exit('No direct script access allowed');

class View_potga_ss1 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();
    }

    public function index()
    {
        $bulan = get_option_tag(array_bulan(), "BULAN");

        $data['judul_menu'] = "Laporan Potong Gaji SS 1";
        $data['bulan']      = $bulan;

        $this->template->view("laporan/View_potga_ss1", $data);

    }

    public function tampilkan()
    {
        $data_req = get_request();

        if ($data_req) {
            $laporan = "<table class=\"table table-bordered table-condensed table-striped\" style=\"white-space: nowrap\">
                    <thead>
                        <tr style=\"\">
                            <th style=\"text-align: center; vertical-align: middle;\">No.</th>
                            <th style=\"text-align: center; vertical-align: middle;\">NAK</th>
							<th style=\"text-align: center; vertical-align: middle;\">NO.PEG</th>
                            <th style=\"text-align: center; vertical-align: middle;\">NAMA</th>
                            <th style=\"text-align: center; vertical-align: middle;\">PERUSAHAAN</th>
                            <th style=\"text-align: center; vertical-align: middle;\">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>";
            
			$Query = "SELECT a.*
			FROM m_potga_ss1 a
			JOIN (
			SELECT no_ang, MAX(tgl_masuk_ss1) max_tgl_masuk_ss1 FROM m_potga_ss1
			WHERE SUBSTR(tgl_masuk_ss1, 1, 7) <= '" . $data_req['tahun'] . "-" . $data_req['bulan'] . "'
			AND kd_prsh = '" . $data_req['kd_prsh'] . "'
			GROUP BY no_ang
			) b
			ON a.no_ang=b.no_ang AND a.tgl_masuk_ss1 = b.max_tgl_masuk_ss1
			WHERE a.jumlah > 0
			ORDER BY a.no_ang";
			$qdata = $this->db->query($Query);
			$i=0;
			$rows = array();
			$no=1;
			$sum_total=0;
			foreach($qdata->result() as $dt){
				$rows[$i]['no_ang'] = $dt->no_ang;
				$rows[$i]['no_peg'] = $dt->no_peg;
				$rows[$i]['nm_ang'] = $dt->nm_ang;
				$rows[$i]['nm_prsh'] = $dt->nm_prsh;
				$rows[$i]['jumlah'] = $dt->jumlah;
				
				$laporan.="
				<tr>
					<td>".$no."</td>
					<td>".$dt->no_ang."</td>
					<td>".$dt->no_peg."</td>
					<td>".$dt->nm_ang."</td>
					<td>".$dt->nm_prsh."</td>
					<td style=\"text-align: right;\">" . number_format($dt->jumlah, 2) . "</td>
				</tr>";
				$no++;
				
				$sum_total+=$dt->jumlah;
			}
				$laporan.="
				<tr>
					<td colspan='5'>TOTAL</td>
					<td style=\"text-align: right;\">" . number_format($sum_total, 2) . "</td>
				</tr>";
        }
        echo $laporan;    
	}
	
	public function excel()
    {
        $file = "potonggaji_ss1_" . date("Y-m-d_H-i-s") . ".xls";

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . $file);

        $data_req = get_request();

        if ($data_req) {
			echo "<table>
				<tr>
					<td colspan='6'><b>LAPORAN POTONG GAJI SS1</b></td>
				</tr>
			</table><p></p>";
            $laporan = "<table border='1'>
                    <thead>
                        <tr style=\"\">
                            <th style=\"text-align: center; vertical-align: middle;\">No.</th>
                            <th style=\"text-align: center; vertical-align: middle;\">NAK</th>
							<th style=\"text-align: center; vertical-align: middle;\">NO.PEG</th>
                            <th style=\"text-align: center; vertical-align: middle;\">NAMA</th>
                            <th style=\"text-align: center; vertical-align: middle;\">PERUSAHAAN</th>
                            <th style=\"text-align: center; vertical-align: middle;\">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>";
            
			$Query = "SELECT a.*
			FROM m_potga_ss1 a
			JOIN (
			SELECT no_ang, MAX(tgl_masuk_ss1) max_tgl_masuk_ss1 FROM m_potga_ss1
			WHERE SUBSTR(tgl_masuk_ss1, 1, 7) <= '" . $data_req['tahun'] . "-" . $data_req['bulan'] . "'
			AND kd_prsh = '" . $data_req['kd_prsh'] . "'
			GROUP BY no_ang
			) b
			ON a.no_ang=b.no_ang AND a.tgl_masuk_ss1 = b.max_tgl_masuk_ss1
			WHERE a.jumlah > 0
			ORDER BY a.no_ang";
			$qdata = $this->db->query($Query);
			$i=0;
			$rows = array();
			$no=1;
			$sum_total=0;
			foreach($qdata->result() as $dt){
				$rows[$i]['no_ang'] = $dt->no_ang;
				$rows[$i]['no_peg'] = $dt->no_peg;
				$rows[$i]['nm_ang'] = $dt->nm_ang;
				$rows[$i]['nm_prsh'] = $dt->nm_prsh;
				$rows[$i]['jumlah'] = $dt->jumlah;
				
				$laporan.="
				<tr>
					<td>".$no."</td>
					<td>".$dt->no_ang."</td>
					<td>".$dt->no_peg."</td>
					<td>".$dt->nm_ang."</td>
					<td>".$dt->nm_prsh."</td>
					<td style=\"text-align: right;\">" . number_format($dt->jumlah, 2) . "</td>
				</tr>";
				$no++;
				
				$sum_total+=$dt->jumlah;
			}
				$laporan.="
				<tr>
					<td colspan='5'>TOTAL</td>
					<td style=\"text-align: right;\">" . number_format($sum_total, 2) . "</td>
				</tr>";
        }
        echo $laporan; 
    }
   
}
