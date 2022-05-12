<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Saldo extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();

        $this->load->model("simpanan_model");
    }

    public function index($page)
    {
        $data_tempo_simpanan = $this->simpanan_model->get_tempo_bln_simpanan();

        $master_tempo_simpanan = get_option_tag($data_tempo_simpanan);

        $bulan = get_option_tag(array_bulan(), "BULAN");

        if ($page == "proses-saldo-simpanan") {
            $data['judul_menu'] = "Sinkron Saldo Simpanan";
            $data['bulan']      = $bulan;
         
            $this->template->view("proses/sinkron_saldo_anggota", $data);
        }
    }
	
	function sinkron_saldo_simpanan(){
		$data['tahun'] = $_POST['tahun'];
        $data['bulan'] = $_POST['bulan'];

        $this->simpanan_model->update_saldo_simpanan_per_bulan($data);
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
