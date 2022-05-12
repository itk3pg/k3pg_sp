<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bridging_model extends CI_Model
{
    public function sinkron_bridgingss1_backup($data)
    {
        set_time_limit(0);
		$tanggal = balik_tanggal($data['tanggal']);
		
		// $Query = "SELECT * FROM t_simpanan_ang WHERE tgl_simpan = '$tanggal' AND kd_jns_simpanan = '3000' AND (kd_jns_transaksi = '01' OR kd_jns_transaksi = '02') ";
		
		$Query = "SELECT kd_jns_transaksi,jm_tunai,jm_transfer,no_simpan,no_ang,kd_bank,nm_ang FROM t_simpanan_ang WHERE tgl_simpan = '$tanggal' AND kd_jns_simpanan = '3000' AND (kd_jns_transaksi = '01' OR kd_jns_transaksi = '02') 
		UNION
		SELECT '01' AS kd_jns_transaksi,jm_tunai,jm_transfer,no_simpan,no_ang,kd_bank,nm_ang FROM t_simpanan_sukarela2 
		WHERE tgl_simpan = '$tanggal' AND kd_jns_simpanan = '4000' AND is_debet = 0";
		$result=$this->db->query($Query);
		$i=0;
		$rows = array();
		foreach($result->result() as $dt){
			$row[$i]['kd_jns_transaksi'] = $dt->kd_jns_transaksi;
			$row[$i]['jm_tunai'] = $dt->jm_tunai;
			$row[$i]['jm_transfer'] = $dt->jm_transfer;
			$row[$i]['no_simpan'] = $dt->no_simpan;
			$row[$i]['no_ang'] = $dt->no_ang;
			$row[$i]['kd_bank'] = $dt->kd_bank;
			$row[$i]['nm_ang'] = $dt->nm_ang;
			
			if($dt->kd_jns_transaksi == '01'){
				$is_tarik = "N";
			}
			else{
				$is_tarik = "Y";
			}
			
			// -- bank --
			if($dt->kd_bank == 2000){ //bni
				$kdbank = 30248;
			}
			else if($dt->kd_bank == 3000){ // mandiri
				$kdbank = 1000002;
			}
			else{
				$kdbank = 1000000; 
			}
			
			$deskripsi = $dt->no_simpan."|".$dt->no_ang."|".$dt->nm_ang;
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'http://192.168.0.246:8090/api/ws/simpin/v1/syncSetorTarik',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_POSTFIELDS => 'transaction_type=1&amt_cash='.$dt->jm_tunai.'&amt_bank='.$dt->jm_transfer.'&description='.$dt->no_simpan.'&is_tarik='.$is_tarik.'&c_bankaccount_id='.$kdbank.'&date_doc='.$tanggal.'&reference_no='.$dt->no_simpan.'',
			  CURLOPT_HTTPHEADER => array(
				'KPG-Token: 544fdf75-8ae1-4217-9123-8bd60d7f56bb',
				'Content-Type: application/x-www-form-urlencoded'
			  ),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			echo $response;
		}
    }
	
	 public function sinkron_bridgingss1($data)
    {
        set_time_limit(0);
		$tanggal = balik_tanggal($data['tanggal']);
		
		$Query = "SELECT * FROM t_simpanan_ang WHERE tgl_simpan = '$tanggal' AND kd_jns_simpanan = '3000' AND (kd_jns_transaksi = '01' OR kd_jns_transaksi = '02') GROUP BY no_simpan";
		$rdata = $this->db->query($Query)->num_rows();
		if($rdata > 0){
		$result=$this->db->query($Query);
		$i=0;
		$rows = array();
		foreach($result->result() as $dt){
			$row[$i]['kd_jns_transaksi'] = $dt->kd_jns_transaksi;
			$row[$i]['jm_tunai'] = $dt->jm_tunai;
			$row[$i]['jm_transfer'] = $dt->jm_transfer;
			$row[$i]['no_simpan'] = $dt->no_simpan;
			$row[$i]['no_ang'] = $dt->no_ang;
			$row[$i]['nm_ang'] = $dt->nm_ang;
			$row[$i]['kd_bank'] = $dt->kd_bank;
			
			if($dt->kd_jns_transaksi == '01'){
				$is_tarik = "N";
			}
			else{
				$is_tarik = "Y";
			}
			
			// -- bank --
			if($dt->kd_bank == 2000){ //bni
				$kdbank = 30248;
			}
			else if($dt->kd_bank == 3000){ // mandiri
				$kdbank = 1000002;
			}
			else{
				$kdbank = 1000000; 
			}
			
			$deskripsi = $dt->no_simpan."|".$dt->no_ang."|".$dt->nm_ang;
			
			$datakirim[] = array(
				'amt_cash' 				=> $dt->jm_tunai,
				'amt_bank'              => $dt->jm_transfer,
				'is_tarik'              => $is_tarik,
				'c_bankaccount_id'      => $kdbank,
				'date_doc'              => $tanggal,
				'reference_no'          => $dt->no_simpan,
				'transaction_type'      => 1,
				'description'           => $deskripsi,
			);
			
			$jsonfile = json_encode(array('listSyncSimpin' => $datakirim), JSON_PRETTY_PRINT);
		}
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'http://192.168.0.246:8090/api/ws/simpin/v1/syncSetorTarikJSON',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_POSTFIELDS =>$jsonfile,
			  CURLOPT_HTTPHEADER => array(
				'KPG-Token: 449540fb-5c62-4f32-8214-0712de9aa1ae',
				'Content-Type: application/json'
			  ),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			echo $response;
			
			
		}	
    }

}

