<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bridging extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();

        $this->load->model("bridging_model");
    }

    public function index($page)
    {
        if ($page == "proses-bridging") {
            $data['judul_menu'] = "Sinkron Bridging";
            $this->template->view("proses/sinkron_bridging", $data);
        }
    }
	
	function sinkron_bridging_ss1(){
		$data['tanggal'] = $_POST['tanggal'];
 
        $this->bridging_model->sinkron_bridgingss1($data);
		echo "Data Berhasil Disinkron";
	}
	
	function sinkron_saldo_simpanan_ang(){
		$data['tahun'] = $_POST['tahun'];
        $data['bulan'] = $_POST['bulan'];
		$data['no_ang'] = $_POST['no_ang'];

        $this->simpanan_model->update_saldo_anggota_per_bulan($data);
		echo "Data Berhasil Disinkron";
	}
}
