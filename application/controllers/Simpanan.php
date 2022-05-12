<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Simpanan extends CI_Controller
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

        if ($page == "transaksi-simpanan-sukarela1") {
            $data['judul_menu'] = "Transaksi Simpanan Sukarela 1";
            $data['bulan']      = $bulan;
            $data['tempo_bln']  = $master_tempo_simpanan;

            $this->template->view("simpanan/transaksi_simp_sukarela1", $data);
        }
        if ($page == "cetak-buku-simpanan-sukarela1") {
            $data['judul_menu'] = "Cetak Buku Simpanan Sukarela 1";
            $data['bulan']      = $bulan;
            $data['tempo_bln']  = $master_tempo_simpanan;

            $this->template->view("simpanan/cetak_simp_sukarela1", $data);
        }
        if ($page == "transaksi-simpanan-sukarela2") {
            $data['judul_menu'] = "Transaksi Simpanan Sukarela 2";
            $data['bulan']      = $bulan;
            $data['tempo_bln']  = $master_tempo_simpanan;

            $this->template->view("simpanan/transaksi_simp_sukarela2", $data);
        }
        if ($page == "proses-margin-simpanan") {
            $data['judul_menu'] = "Proses Margin Simpanan";
            $data['bulan']      = $bulan;

            $this->template->view("proses/proses_margin_simpanan", $data);
        }
        if ($page == "proses-potga-ss1") {
            $data['judul_menu'] = "Proses Potga SS1";
            $data['bulan']      = $bulan;

            $this->template->view("proses/proses_potga_ss1", $data);
        }
        if ($page == "proses-pajak-ss1") {
            $data['judul_menu'] = "Proses Pajak Bunga SS1 dan SS2";
            $data['bulan']      = $bulan;

            $this->template->view("proses/proses_pajak_ss1", $data);
        }
        if ($page == "proses-saldo-tahunan-ss1") {
            $data['judul_menu'] = "Proses Saldo Awal Tahun SS1";
            // $data['bulan']      = $bulan;

            $this->template->view("proses/proses_saldo_ss1_tahunan", $data);
        }
    }

    public function get_transaksi_simpanan($kd_jns_simpanan = "")
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $no_ang = (isset($data['no_ang']) and $data['no_ang'] != "") ? $data['no_ang'] : "xxx";

        $data_numrows = $this->simpanan_model->get_simpanan(1, $cari, "", "", "", "", "", $no_ang, "", "")->row(0)->numrows;
        $data_item    = $this->simpanan_model->get_simpanan(0, $cari, "", $offset, $limit, "", "", $no_ang, "", "");

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_item->result_array();

        echo json_encode($array);
    }

    public function cek_saldo_simpanan_sukarela1()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_simpan']      = date("Y-m-d");
            $data_post['kd_jns_simpanan'] = "3000";

            echo $this->simpanan_model->cek_saldo_simpanan($data_post);
        }
    }

    public function add_transaksi_simpanan_sukarela1()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_simpan'] = balik_tanggal($data_post['tgl_simpan']);
            // $data_post['kredit_debet'] = "K";

            // if (!cek_tanggal_entri(date("Y-m-d"))) {
            //     $hasil['status'] = false;
            //     $hasil['msg']    = "Tidak bisa entri data bulan lalu";

            //     echo json_encode($hasil);exit();
            // }

            $data_post['kd_jns_simpanan'] = "3000";
            $data_post['nm_jns_simpanan'] = "SIMPANAN SUKARELA 1";

            $query = $this->simpanan_model->insert_simpanan($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Ditambah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Ditambah";
            }

            echo json_encode($hasil);
        }
    }

    public function add_transaksi_simpanan()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->simpanan_model->insert_simpanan($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Ditambah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Ditambah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_transaksi_simpanan()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_simpan'] = balik_tanggal($data_post['tgl_simpan']);

            // if (!cek_tanggal_entri($data_post['tgl_simpan'])) {
            //     $hasil['status'] = false;
            //     $hasil['msg']    = "Tidak bisa hapus data bulan lalu";

            //     echo json_encode($hasil);exit();
            // }

            $query = $this->simpanan_model->delete_simpanan($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Dihapus";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Dihapus";
            }

            echo json_encode($hasil);
        }
    }

    public function view_saldo_simpanan()
    {
        $data_req = get_request();

        $view = "<table class=\"table table-bordered table-striped\">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Jenis Simpanan</th>
                    <th>Saldo Awal</th>
                    <th>Kredit</th>
                    <th>Debet</th>
                    <th>Saldo Akhir</th>
                </tr>
            </thead>
            <tbody>";

        $no = 1;

        $t_saldo_awal  = 0;
        $t_kredit      = 0;
        $t_debet       = 0;
        $t_saldo_akhir = 0;

        $data_saldo = $this->simpanan_model->get_saldo_simpanan($data_req['tahun'], $data_req['bulan'], $data_req['no_ang']);

        foreach ($data_saldo->result_array() as $key => $value) {
            $view .= "
                <tr>
                    <td>" . $no . "</td>
                    <td>" . $value['kd_jns_simpanan'] . " " . $value['nm_jns_simpanan'] . "</td>
                    <td class=\"text-right\">" . number_format($value['saldo_awal'], 2, '.', ',') . "</td>
                    <td class=\"text-right\">" . number_format($value['jml_kredit'], 2, '.', ',') . "</td>
                    <td class=\"text-right\">" . number_format($value['jml_debet'], 2, '.', ',') . "</td>
                    <td class=\"text-right\">" . number_format($value['saldo_akhir'], 2, '.', ',') . "</td>
                </tr>";

            $no++;
            $t_saldo_awal += $value['saldo_awal'];
            $t_kredit += $value['jml_kredit'];
            $t_debet += $value['jml_debet'];
            $t_saldo_akhir += $value['saldo_akhir'];
        }

        $view .= "
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=\"2\">Total</th>
                    <th class=\"text-right\">" . number_format($t_saldo_awal, 2, '.', ',') . "</th>
                    <th class=\"text-right\">" . number_format($t_kredit, 2, '.', ',') . "</th>
                    <th class=\"text-right\">" . number_format($t_debet, 2, '.', ',') . "</th>
                    <th class=\"text-right\">" . number_format($t_saldo_akhir, 2, '.', ',') . "</th>
                </tr>
            </tfoot>
        </table>";

        echo $view;
    }

    public function get_simpanan_sukarela2()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];
        $no_ang        = (isset($data['no_ang']) and $data['no_ang'] != "") ? $data['no_ang'] : "xxx";

        $data_numrows = $this->simpanan_model->get_simpanan_sukarela2(1, $cari, "", "", "", $no_ang)->row(0)->numrows;
        $data_item    = $this->simpanan_model->get_simpanan_sukarela2(0, $cari, "", $offset, $limit, $no_ang);

        $data_set = array();

        foreach ($data_item->result_array() as $value) {
            $bunga_diperoleh = round($value['jml_margin_bln'] * $value['umur_bulan'], 2);

            $offset++;
            $value['nomor']           = $offset;
            $value['bunga_diperoleh'] = $bunga_diperoleh;
            $data_set[]               = $value;
        }

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_set;

        echo json_encode($array);
    }

    public function cek_saldo_simpanan_sukarela2()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_simpan']      = date("Y-m-d");
            $data_post['kd_jns_simpanan'] = "4000";

            echo $this->simpanan_model->cek_pokok_simpanan_sukarela2($data_post);
        }
    }

    public function get_margin_simpanan_berlaku()
    {
        $data_post = get_request("post");

        if ($data_post) {
            $data_margin = $this->master_model->get_margin_simpanan_berlaku("4000", $data_post['tempo_bln'], balik_tanggal($data_post['tgl_simpan']), hapus_koma($data_post['jml_simpanan']));

            $margin = ($data_margin->num_rows()) ? $data_margin->row()->rate : "0";

            if ($data_post['act'] == "edit-margin") {
                $margin = hapus_koma($data_post['margin']);
            }

            $hasil['margin'] = $margin;

            $jumlah                    = isset($data_post['jml_simpanan']) ? hapus_koma($data_post['jml_simpanan']) : 0;
            $margin_per_tahun          = round($jumlah * ($margin / 100), 2);
            $hasil['margin_per_tahun'] = $margin_per_tahun;
            $margin_per_bulan          = round($margin_per_tahun / 12, 2);
            $hasil['margin_per_bulan'] = $margin_per_bulan;

            echo json_encode($hasil);
        }
    }

    public function add_transaksi_simpanan_sukarela2()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['kd_jns_simpanan'] = "4000";
            $data_post['nm_jns_simpanan'] = "SIMPANAN SUKARELA 2";
            $data_post['tgl_simpan']      = balik_tanggal($data_post['tgl_simpan']);

            $query = $this->simpanan_model->insert_simpanan_sukarela2($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Ditambah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Ditambah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_transaksi_simpanan_sukarela2()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_simpan'] = balik_tanggal($data_post['tgl_simpan']);

            if (!cek_tanggal_entri($data_post['tgl_simpan']) and $this->session->userdata("username") != "LAHDA") {
                $hasil['status'] = false;
                $hasil['msg']    = "Tidak bisa hapus data bulan lalu";

                exit(json_encode($hasil));
            }

            $query = $this->simpanan_model->delete_simpanan_sukarela2($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Dihapus";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Dihapus";
            }

            echo json_encode($hasil);
        }
    }

    public function perpanjang_ss2()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->simpanan_model->perpanjang_ss2($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Simpanan Berhasil Diperpanjang";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Simpanan Gagal Diperpanjang";
            }

            echo json_encode($hasil);
        }
    }

    public function ss2_dipercepat()
    {
        $data_post = get_request("post");

        if ($data_post) {
            $data_post['tgl_debet'] = balik_tanggal($data_post['tgl_debet']);
            
            $query = $this->simpanan_model->proses_penarikan_dipercepat_ss2($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diproses";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diproses";
            }

            echo json_encode($hasil);
        }
    }

    public function simpan_no_ss2()
    {
        $data_post = get_request("post");

        if ($data_post) {
            $query = $this->simpanan_model->simpan_no_ss2($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diproses";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diproses";
            }

            echo json_encode($hasil);
        }
    }

    public function simpan_tgldebet()
    {
        $data_post = get_request("post");

        if ($data_post) {
            $data_post['tgl_debet'] = balik_tanggal($data_post['tgl_debet']);

            $query = $this->simpanan_model->simpan_tgldebet($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diproses";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diproses";
            }

            echo json_encode($hasil);
        }
    }

    public function simpan_ubah_jangka_ss2()
    {
        $data_post = get_request("post");

        if ($data_post) {
            $data_post['tgl_simpan'] = balik_tanggal($data_post['tgl_simpan']);

            $query = $this->simpanan_model->simpan_ubah_jangka_ss2($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diproses";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diproses";
            }

            echo json_encode($hasil);
        }
    }

    public function init_progress_margin($jenis_simpanan)
    {
        if ($jenis_simpanan == "SS2") {
            $this->cache->file->save('margin_ss2_' . session_id(), "0;0;0");
        } else if ($jenis_simpanan == "SS1") {
            $this->cache->file->save('margin_ss1_' . session_id(), "0;0;0");
        }
        // else if ($jenis_simpanan == "syariah") {
        //     $this->cache->file->save('margin_syariah_' . session_id(), "0;0;0");
        // }
    }

    public function get_progress_margin($jenis_simpanan)
    {
        if ($jenis_simpanan == "SS2") {
            $data_proses = explode(";", $this->cache->file->get('margin_ss2_' . session_id()));
        } else if ($jenis_simpanan == "SS1") {
            $data_proses = explode(";", $this->cache->file->get('margin_ss1_' . session_id()));
        }
        // else if ($jenis_simpanan == "syariah") {
        //     $data_proses = explode(";", $this->cache->file->get('margin_syariah_' . session_id()));
        // }

        $json['persen']     = $data_proses[0];
        $json['data_now']   = $data_proses[1];
        $json['data_total'] = $data_proses[2];

        echo json_encode($json);
    }

    public function proses_margin($jenis_simpanan)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $this->simpanan_model->proses_margin($jenis_simpanan, $data_post);
        }
    }
	
	public function proses_margin_tgl($jenis_simpanan)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $this->simpanan_model->proses_margin_tgl($jenis_simpanan, $data_post);
        }
    }

    public function init_progress_potga_ss1()
    {
        $this->cache->file->save('proses_potga_ss1_' . session_id(), "0;0;0");
    }

    public function get_progress_potga_ss1()
    {
        $data_proses = explode(";", $this->cache->file->get('proses_potga_ss1_' . session_id()));

        $json['persen']     = $data_proses[0];
        $json['data_now']   = $data_proses[1];
        $json['data_total'] = $data_proses[2];

        echo json_encode($json);
    }

    public function proses_potga_ss1()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $this->simpanan_model->proses_insert_potga_ss1($data_post);
        }
    }

    public function init_progress_saldo_simp_tahunan()
    {
        $this->cache->file->save('proses_saldo_simp_tahunan_' . session_id(), "0;0;0");
    }

    public function get_progress_saldo_simp_tahunan()
    {
        $data_proses = explode(";", $this->cache->file->get('proses_saldo_simp_tahunan_' . session_id()));

        $json['persen']     = $data_proses[0];
        $json['data_now']   = $data_proses[1];
        $json['data_total'] = $data_proses[2];

        echo json_encode($json);
    }

    public function proses_saldo_simp_tahunan()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $tahun = $data_post['tahun'];

            $this->simpanan_model->update_saldo_simpanan_tahunan($tahun);
        }
    }

    public function init_progress_pajak_ss1()
    {
        $this->cache->file->save('proses_pajak_ss1_' . session_id(), "0;0;0");
    }

    public function get_progress_pajak_ss1()
    {
        $data_proses = explode(";", $this->cache->file->get('proses_pajak_ss1_' . session_id()));

        $json['persen']     = $data_proses[0];
        $json['data_now']   = $data_proses[1];
        $json['data_total'] = $data_proses[2];

        echo json_encode($json);
    }

    public function proses_pajak_ss1()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $this->simpanan_model->proses_pajak_ss1($data_post);
        }
    }
	
	function proses_bungass1_ang(){
		$data_post = $this->input->post();

        if ($data_post) {
			if($_POST['jns_simpanan'] == "SS1"){
				$this->simpanan_model->proses_margin_ss1_ang($data_post);
			}
			else{
				$this->simpanan_model->proses_margin_ss2_ang($data_post);
			}
			echo 1;
        }
		else{
			echo 2;
		}
	}
	
	public function proses_pajak_ss2()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $this->simpanan_model->proses_pajak_ss2($data_post);
        }
    }
}
