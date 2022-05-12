<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pinjaman extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();

        $this->load->model("pinjaman_model");
        $this->load->model("master_model");
    }

    public function index($page)
    {
        $data_tempo_reguler = $this->pinjaman_model->get_tempo_bln_reguler();
        $data_tempo_kkb     = $this->pinjaman_model->get_tempo_bln_kkb();
        $data_tempo_kpr     = $this->pinjaman_model->get_tempo_bln_kpr();

        if ($page == "reguler") {
            $data['judul_menu'] = "Pinjaman Reguler";
            $data['tempo_bln']  = get_option_tag($data_tempo_reguler);

            $this->template->view("pinjaman/reguler", $data);
        }
        if ($page == "kkb") {
            $data['judul_menu'] = "Kredit Khusus Beragunan";
            $data['tempo_bln']  = get_option_tag($data_tempo_kkb);

            $this->template->view("pinjaman/kkb", $data);
        }
        if ($page == "kpr") {
            $data['judul_menu'] = "Kredit Pemilikan Rumah";
            $data['tempo_bln']  = get_option_tag($data_tempo_kpr);

            $this->template->view("pinjaman/kpr", $data);
        }
        if ($page == "pht") {
            $data['judul_menu'] = "Pinjaman Hari Tua";

            $this->template->view("pinjaman/pht", $data);
        }
        if ($page == "aprove") {
            $data['judul_menu'] = "Aprove Pinjaman";

            $this->template->view("pinjaman/aprove_pinjaman", $data);
        }
        if ($page == "realisasi") {
            $data['judul_menu'] = "Realisasi Pinjaman";

            $this->template->view("pinjaman/realisasi_pinjaman", $data);
        }
        if ($page == "pelunasan-angsuran") {
            $data['judul_menu'] = "Pelunasan Angsuran Pinjaman";

            $this->template->view("pinjaman/pelunasan_angsuran", $data);
        }
        if ($page == "pelunasan-dipercepat") {
            $data['judul_menu'] = "Pelunasan Dipercepat";

            $this->template->view("pinjaman/pelunasan_dipercepat", $data);
        }

    }

    public function get_simulasi_pinjaman_reguler()
    {
        $data = get_request();

        $cari['field'] = array("no_ang");
        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];
        // $bulan         = $data['bulan'];
        // $tahun         = $data['tahun'];

        $data_numrows = $this->pinjaman_model->get_simulasi_pinjaman(1, $cari, "", "", "", "1", "0", "0")->row(0)->numrows;
        $data_item    = $this->pinjaman_model->get_simulasi_pinjaman(0, $cari, "", $offset, $limit, "1", "0", "0");

        $data_set = array();

        foreach ($data_item->result_array() as $value) {
            $offset++;
            $value['nomor'] = $offset;
            $data_set[]     = $value;
        }

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_set;

        echo json_encode($array);
    }

    public function proses_perhitungan_reguler()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $hasil = array();

            $data_post['tgl_pinjam']      = balik_tanggal($data_post['tgl_pinjam']);
            $data_post['tgl_realisasi']   = isset($data_post['tgl_realisasi']) ? balik_tanggal($data_post['tgl_realisasi']) : "";
            $data_post['mode']            = isset($data_post['mode']) ? $data_post['mode'] : "";
            $data_post['is_ganti_margin'] = isset($data_post['is_ganti_margin']) ? $data_post['is_ganti_margin'] : "";

            $tgl_pinjam = ($data_post['mode'] == "realisasi") ? $data_post['tgl_realisasi'] : $data_post['tgl_pinjam'];

            $xtgl  = strtotime($tgl_pinjam);
            $tahun = date("Y", $xtgl);
            $bulan = date("m", $xtgl);
            $hari  = date("d", $xtgl);

            $hasil['tgl_angs'] = date('Y-m-t', mktime(0, 0, 0, $bulan + 1, 1, $tahun));
            $hasil['tgl_jt']   = date('Y-m-t', mktime(0, 0, 0, $bulan + $data_post['tempo_bln'], 1, $tahun));

            $data_margin = $this->master_model->get_margin_pinjaman_berlaku("1", $data_post['tempo_bln'], $data_post['tgl_pinjam']);

            $margin = ($data_margin->num_rows() > 0) ? $data_margin->row()->rate : 0;

            if ($data_post['is_ganti_margin'] == "1") {
                $margin = $data_post['margin'];
            }

            $hasil['margin']       = $margin;
            $hasil['jenis_margin'] = "FLAT";

            $jml_biaya_admin          = hapus_koma($data_post['jml_pinjam']) * 0.01;
            $hasil['jml_biaya_admin'] = $jml_biaya_admin;

            $jml_kredit = hapus_koma($data_post['jml_pinjam']) + $jml_biaya_admin;

            $pokok_per_bulan  = (hapus_koma($data_post['jml_pinjam']) + $jml_biaya_admin) / $data_post['tempo_bln'];
            $margin_per_bulan = hapus_koma($data_post['jml_pinjam']) * (($margin / 100) / 12);

            $total_margin = $margin_per_bulan * $data_post['tempo_bln'];

            $hasil['jml_margin'] = $total_margin;

            $jml_angsuran      = $pokok_per_bulan + $margin_per_bulan;
            $hasil['angsuran'] = $jml_angsuran;

            $total_angsuran = $this->pinjaman_model->get_total_angsuran($data_post);

            $hasil['sisa_plafon'] = (hapus_koma($data_post['plafon']) - ($total_angsuran));
            // $hasil['sisa_plafon'] = (hapus_koma($data_post['plafon']) - ($total_angsuran + $jml_angsuran - hapus_koma($data_post['angsuran_edit'])));

            $hasil['jml_diterima'] = hapus_koma($data_post['jml_pinjam']);
            $hasil['jml_omzet']    = hapus_koma($data_post['jml_pinjam']) + $total_margin + $jml_biaya_admin;

            echo json_encode($hasil);
        }
    }

    public function add_simulasi_reguler()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $data_post['tgl_pinjam'] = balik_tanggal($data_post['tgl_pinjam']);

            // if (!cek_tanggal_entri($data_post['tgl_pinjam'])) {
            //     $hasil['status'] = false;
            //     $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            // } else {

            $query = $this->pinjaman_model->insert_simulasi_reguler($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Ditambah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Ditambah";
            }

            // }

            echo json_encode($hasil);
        }
    }

    public function edit_simulasi_reguler($id)
    {
        $data_post = get_request('post');

        if ($data_post) {
            $data_post['tgl_pinjam'] = balik_tanggal($data_post['tgl_pinjam']);

            // if (!cek_tanggal_entri($data_post['tgl_pinjam'])) {
            //     $hasil['status'] = false;
            //     $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            // } else {

            $query = $this->pinjaman_model->insert_simulasi_reguler($data_post, $id, "1");

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diedit";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diedit";
            }

            // }

            echo json_encode($hasil);
        }
    }

    public function delete_simulasi_pinjaman()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $data_post['tgl_pinjam'] = balik_tanggal($data_post['tgl_pinjam']);

            $query = $this->pinjaman_model->delete_simulasi_pinjaman($data_post);

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

    public function get_simulasi_pinjaman_kkb()
    {
        $data = get_request();

        $cari['field'] = array("no_ang");
        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];
        // $bulan         = $data['bulan'];
        // $tahun         = $data['tahun'];

        $data_numrows = $this->pinjaman_model->get_simulasi_pinjaman(1, $cari, "", "", "", "2", "0", "0")->row(0)->numrows;
        $data_item    = $this->pinjaman_model->get_simulasi_pinjaman(0, $cari, "", $offset, $limit, "2", "0", "0");

        $data_set = array();

        foreach ($data_item->result_array() as $value) {
            $offset++;
            $value['nomor'] = $offset;
            $data_set[]     = $value;
        }

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_set;

        echo json_encode($array);
    }

    public function proses_perhitungan_kkb()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $hasil = array();

            $data_post['tgl_pinjam']    = balik_tanggal($data_post['tgl_pinjam']);
            $data_post['tgl_realisasi'] = isset($data_post['tgl_realisasi']) ? balik_tanggal($data_post['tgl_realisasi']) : "";
            $data_post['mode']          = isset($data_post['mode']) ? $data_post['mode'] : "";
            $data_post['is_ganti_margin'] = isset($data_post['is_ganti_margin']) ? $data_post['is_ganti_margin'] : "";

            $tgl_pinjam = ($data_post['mode'] == "realisasi") ? $data_post['tgl_realisasi'] : $data_post['tgl_pinjam'];

            $xtgl  = strtotime($tgl_pinjam);
            $tahun = date("Y", $xtgl);
            $bulan = date("m", $xtgl);
            $hari  = date("d", $xtgl);

            $hasil['tgl_angs'] = date('Y-m-t', mktime(0, 0, 0, $bulan, 1, $tahun));
            $hasil['tgl_jt']   = date('Y-m-t', mktime(0, 0, 0, $bulan + $data_post['tempo_bln'] - 1, 1, $tahun));

            $data_margin = $this->master_model->get_margin_pinjaman_berlaku("2", $data_post['tempo_bln'], $data_post['tgl_pinjam']);

            $margin = ($data_margin->num_rows() > 0) ? $data_margin->row()->rate : 0;

            if ($data_post['is_ganti_margin'] == "1") {
                $margin = $data_post['margin'];
            }

            $hasil['margin']       = $margin;
            $hasil['jenis_margin'] = "ANUITAS";

            $angsuran         = hapus_koma($data_post['gaji']) * ($data_post['persen_angsuran'] / 100);
            $jml_min_angsuran = hapus_koma($data_post['gaji']) * ($data_post['min_angsuran'] / 100);
            $jml_max_angsuran = hapus_koma($data_post['gaji']) * ($data_post['max_angsuran'] / 100);

            $hasil['angsuran']         = $angsuran;
            $hasil['jml_min_angsuran'] = $jml_min_angsuran;
            $hasil['jml_max_angsuran'] = $jml_max_angsuran;

            $total_angsuran = $this->pinjaman_model->get_total_angsuran($data_post);

            $hasil['sisa_plafon'] = hapus_koma($data_post['plafon']) - ($total_angsuran);
            // $hasil['sisa_plafon'] = hapus_koma($data_post['plafon']) - ($total_angsuran + $angsuran - hapus_koma($data_post['angsuran_edit']));

            $hasil['jml_diterima'] = hapus_koma($data_post['jml_pinjam']);

            $data_saldo_akhir = $this->pinjaman_model->get_angsuran_kkb($data_post);

            $data_terakhir = end($data_saldo_akhir);

            $hasil['saldo_akhir'] = $data_terakhir['pokok_akhir'];

            echo json_encode($hasil);
        }
    }

    public function view_angsuran_kkb()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $view = "<table class=\"table table-bordered table-striped\" style=\"white-space: nowrap\">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Periode</th>
                        <th>Hari</th>
                        <th>Pokok Awal</th>
                        <th>Pokok</th>
                        <th>Margin</th>
                        <th>Angsuran</th>
                        <th>Pokok Akhir</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>";

            $no = 1;

            $t_pokok_awal  = 0;
            $t_pokok       = 0;
            $t_bunga       = 0;
            $t_angsuran    = 0;
            $t_pokok_akhir = 0;

            $data_post['tgl_pinjam'] = balik_tanggal($data_post['tgl_pinjam']);

            $data_saldo = $this->pinjaman_model->get_angsuran_kkb($data_post);

            foreach ($data_saldo as $key => $value) {
                $view .= "
                    <tr>
                        <td>" . $no . "</td>
                        <td>" . $value['blth_angsuran'] . "</td>
                        <td>" . $value['hari'] . "</td>
                        <td class=\"text-right\">" . number_format($value['pokok_awal'], 2, '.', ',') . "</td>
                        <td class=\"text-right\">" . number_format($value['pokok_per_bulan'], 2, '.', ',') . "</td>
                        <td class=\"text-right\">" . number_format($value['margin_per_bulan'], 2, '.', ',') . "</td>
                        <td class=\"text-right\">" . number_format($value['angsuran_per_bulan'], 2, '.', ',') . "</td>
                        <td class=\"text-right\">" . number_format($value['pokok_akhir'], 2, '.', ',') . "</td>
                        <td class=\"text-left\">" . $value['nm_pot_bonus'] . "</td>
                    </tr>";

                $no++;
                $t_pokok_awal += $value['pokok_awal'];
                $t_pokok += $value['pokok_per_bulan'];
                $t_bunga += $value['margin_per_bulan'];
                $t_angsuran += $value['angsuran_per_bulan'];
                $t_pokok_akhir += $value['pokok_akhir'];
            }

            $view .= "
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan=\"3\">Total</th>
                        <th class=\"text-right\">" . number_format(0, 2, '.', ',') . "</th>
                        <th class=\"text-right\">" . number_format($t_pokok, 2, '.', ',') . "</th>
                        <th class=\"text-right\">" . number_format($t_bunga, 2, '.', ',') . "</th>
                        <th class=\"text-right\">" . number_format($t_angsuran, 2, '.', ',') . "</th>
                        <th class=\"text-right\">" . number_format(0, 2, '.', ',') . "</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>";

            echo $view;
        }
    }

    public function add_simulasi_kkb()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $data_post['tgl_pinjam'] = balik_tanggal($data_post['tgl_pinjam']);

            // if (!cek_tanggal_entri($data_post['tgl_pinjam'])) {
            //     $hasil['status'] = false;
            //     $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            // } else {

            $query = $this->pinjaman_model->insert_simulasi_kkb($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Ditambah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Ditambah";
            }

            // }

            echo json_encode($hasil);
        }
    }

    public function edit_simulasi_kkb($id)
    {
        $data_post = get_request('post');

        if ($data_post) {
            $data_post['tgl_pinjam'] = balik_tanggal($data_post['tgl_pinjam']);

            // if (!cek_tanggal_entri($data_post['tgl_pinjam'])) {
            //     $hasil['status'] = false;
            //     $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            // } else {

            $query = $this->pinjaman_model->insert_simulasi_kkb($data_post, $id, "1");

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diedit";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diedit";
            }

            // }

            echo json_encode($hasil);
        }
    }

    public function get_simulasi_pinjaman_kpr()
    {
        $data = get_request();

        $cari['field'] = array("no_ang");
        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];
        // $bulan         = $data['bulan'];
        // $tahun         = $data['tahun'];

        $data_numrows = $this->pinjaman_model->get_simulasi_pinjaman(1, $cari, "", "", "", "4", "0", "0")->row(0)->numrows;
        $data_item    = $this->pinjaman_model->get_simulasi_pinjaman(0, $cari, "", $offset, $limit, "4", "0", "0");

        $data_set = array();

        foreach ($data_item->result_array() as $value) {
            $offset++;
            $value['nomor'] = $offset;
            $data_set[]     = $value;
        }

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_set;

        echo json_encode($array);
    }

    public function proses_perhitungan_kpr()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $hasil = array();

            $data_post['tgl_pinjam']    = balik_tanggal($data_post['tgl_pinjam']);
            $data_post['tgl_realisasi'] = isset($data_post['tgl_realisasi']) ? balik_tanggal($data_post['tgl_realisasi']) : "";
            $data_post['mode']          = isset($data_post['mode']) ? $data_post['mode'] : "";
            $data_post['is_ganti_margin'] = isset($data_post['is_ganti_margin']) ? $data_post['is_ganti_margin'] : "";

            $tgl_pinjam = ($data_post['mode'] == "realisasi") ? $data_post['tgl_realisasi'] : $data_post['tgl_pinjam'];

            $xtgl  = strtotime($tgl_pinjam);
            $tahun = date("Y", $xtgl);
            $bulan = date("m", $xtgl);
            $hari  = date("d", $xtgl);

            $hasil['tgl_angs'] = date('Y-m-t', mktime(0, 0, 0, $bulan, 1, $tahun));
            $hasil['tgl_jt']   = date('Y-m-t', mktime(0, 0, 0, $bulan + $data_post['tempo_bln'] - 1, 1, $tahun));

            $data_margin = $this->master_model->get_margin_pinjaman_berlaku("4", $data_post['tempo_bln'], $data_post['tgl_pinjam']);

            $margin = ($data_margin->num_rows() > 0) ? $data_margin->row()->rate : 0;

            if ($data_post['is_ganti_margin'] == "1") {
                $margin = $data_post['margin'];
            }

            $hasil['margin']       = $margin;
            $hasil['jenis_margin'] = "ANUITAS";

            $angsuran         = hapus_koma($data_post['gaji']) * ($data_post['persen_angsuran'] / 100);
            $jml_min_angsuran = hapus_koma($data_post['gaji']) * ($data_post['min_angsuran'] / 100);
            $jml_max_angsuran = hapus_koma($data_post['gaji']) * ($data_post['max_angsuran'] / 100);

            $hasil['angsuran']         = $angsuran;
            $hasil['jml_min_angsuran'] = $jml_min_angsuran;
            $hasil['jml_max_angsuran'] = $jml_max_angsuran;

            $total_angsuran = $this->pinjaman_model->get_total_angsuran($data_post);

            $hasil['sisa_plafon'] = hapus_koma($data_post['plafon']) - ($total_angsuran);
            // $hasil['sisa_plafon'] = hapus_koma($data_post['plafon']) - ($total_angsuran + $jml_angsuran - hapus_koma($data_post['angsuran_edit']));

            $hasil['jml_diterima'] = hapus_koma($data_post['jml_pinjam']);

            $data_saldo_akhir = $this->pinjaman_model->get_angsuran_kpr($data_post);

            $data_terakhir = end($data_saldo_akhir);

            $hasil['saldo_akhir'] = $data_terakhir['pokok_akhir'];

            echo json_encode($hasil);
        }
    }

    public function view_angsuran_kpr()
    {
        $data_req = get_request();

        $view = "<table class=\"table table-bordered table-striped\" style=\"white-space: nowrap\">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Periode</th>
                    <th>Hari</th>
                    <th>Pokok Awal</th>
                    <th>Pokok</th>
                    <th>Margin</th>
                    <th>Angsuran</th>
                    <th>Pokok Akhir</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>";

        $no = 1;

        $t_pokok_awal  = 0;
        $t_pokok       = 0;
        $t_bunga       = 0;
        $t_angsuran    = 0;
        $t_pokok_akhir = 0;

        $data_req['tgl_pinjam'] = balik_tanggal($data_req['tgl_pinjam']);

        $data_saldo = $this->pinjaman_model->get_angsuran_kpr($data_req);

        foreach ($data_saldo as $key => $value) {
            $view .= "
                <tr>
                    <td>" . $no . "</td>
                    <td>" . $value['blth_angsuran'] . "</td>
                    <td>" . $value['hari'] . "</td>
                    <td class=\"text-right\">" . number_format($value['pokok_awal'], 2, '.', ',') . "</td>
                    <td class=\"text-right\">" . number_format($value['pokok_per_bulan'], 2, '.', ',') . "</td>
                    <td class=\"text-right\">" . number_format($value['margin_per_bulan'], 2, '.', ',') . "</td>
                    <td class=\"text-right\">" . number_format($value['angsuran_per_bulan'], 2, '.', ',') . "</td>
                    <td class=\"text-right\">" . number_format($value['pokok_akhir'], 2, '.', ',') . "</td>
                    <td class=\"text-left\">" . $value['nm_pot_bonus'] . "</td>
                </tr>";

            $no++;
            $t_pokok_awal += $value['pokok_awal'];
            $t_pokok += $value['pokok_per_bulan'];
            $t_bunga += $value['margin_per_bulan'];
            $t_angsuran += $value['angsuran_per_bulan'];
            $t_pokok_akhir += $value['pokok_akhir'];
        }

        $view .= "
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=\"3\">Total</th>
                    <th class=\"text-right\">" . number_format(0, 2, '.', ',') . "</th>
                    <th class=\"text-right\">" . number_format($t_pokok, 2, '.', ',') . "</th>
                    <th class=\"text-right\">" . number_format($t_bunga, 2, '.', ',') . "</th>
                    <th class=\"text-right\">" . number_format($t_angsuran, 2, '.', ',') . "</th>
                    <th class=\"text-right\">" . number_format(0, 2, '.', ',') . "</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>";

        echo $view;
    }

    public function add_simulasi_kpr()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $data_post['tgl_pinjam'] = balik_tanggal($data_post['tgl_pinjam']);

            // if (!cek_tanggal_entri($data_post['tgl_pinjam'])) {
            //     $hasil['status'] = false;
            //     $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            // } else {

            $query = $this->pinjaman_model->insert_simulasi_kpr($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Ditambah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Ditambah";
            }

            // }

            echo json_encode($hasil);
        }
    }

    public function edit_simulasi_kpr($id)
    {
        $data_post = get_request('post');

        if ($data_post) {
            $data_post['tgl_pinjam'] = balik_tanggal($data_post['tgl_pinjam']);

            // if (!cek_tanggal_entri($data_post['tgl_pinjam'])) {
            //     $hasil['status'] = false;
            //     $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            // } else {

            $query = $this->pinjaman_model->insert_simulasi_kpr($data_post, $id, "1");

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diedit";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diedit";
            }

            // }

            echo json_encode($hasil);
        }
    }

    public function get_simulasi_pinjaman_pht()
    {
        $data = get_request();

        $cari['field'] = array("no_ang");
        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];
        // $bulan         = $data['bulan'];
        // $tahun         = $data['tahun'];

        $data_numrows = $this->pinjaman_model->get_simulasi_pinjaman(1, $cari, "", "", "", "3", "0", "0")->row(0)->numrows;
        $data_item    = $this->pinjaman_model->get_simulasi_pinjaman(0, $cari, "", $offset, $limit, "3", "0", "0");

        $data_set = array();

        foreach ($data_item->result_array() as $value) {
            $offset++;
            $value['nomor'] = $offset;
            $data_set[]     = $value;
        }

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_set;

        echo json_encode($array);
    }

    public function proses_perhitungan_pht()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $hasil = array();

            $data_post['tgl_pinjam']    = balik_tanggal($data_post['tgl_pinjam']);
            $data_post['tgl_realisasi'] = isset($data_post['tgl_realisasi']) ? balik_tanggal($data_post['tgl_realisasi']) : "";
            $data_post['mode']          = isset($data_post['mode']) ? $data_post['mode'] : "";
            $data_post['is_ganti_margin'] = isset($data_post['is_ganti_margin']) ? $data_post['is_ganti_margin'] : "";

            $tgl_pinjam = ($data_post['mode'] == "realisasi") ? $data_post['tgl_realisasi'] : $data_post['tgl_pinjam'];

            $xtgl  = strtotime($tgl_pinjam);
            $tahun = date("Y", $xtgl);
            $bulan = date("m", $xtgl);
            $hari  = date("d", $xtgl);

            $hasil['tgl_angs'] = date('Y-m-t', mktime(0, 0, 0, $bulan + 1, 1, $tahun));
            $hasil['tgl_jt']   = date('Y-m-t', mktime(0, 0, 0, $bulan + $data_post['tempo_bln'], 1, $tahun));

            $data_margin = $this->master_model->get_margin_pinjaman_berlaku("3", $data_post['tempo_bln'], $data_post['tgl_pinjam']);

            $margin = ($data_margin->num_rows() > 0) ? $data_margin->row(0)->rate : 0;

            if ($data_post['is_ganti_margin'] == "1") {
                $margin = $data_post['margin'];
            }

            $hasil['margin'] = $margin;

            $margin_per_bulan = hapus_koma($data_post['jml_pinjam']) * ($margin / 100) / 12;

            $jml_margin = $margin_per_bulan * $data_post['tempo_bln'];

            $hasil['jml_margin'] = $jml_margin;

            $jml_angsuran      = ($data_post['jns_potong_bunga'] == "POTONG") ? 0 : $margin_per_bulan;
            $hasil['angsuran'] = $jml_angsuran;

            $total_angsuran = $this->pinjaman_model->get_total_angsuran($data_post);

            $hasil['sisa_plafon'] = hapus_koma($data_post['plafon']) - ($total_angsuran);
            // $hasil['sisa_plafon'] = hapus_koma($data_post['plafon']) - ($total_angsuran + $jml_angsuran - hapus_koma($data_post['angsuran_edit']));

            $jml_biaya_admin          = hapus_koma($data_post['jml_pinjam']) * 0.01;
            $hasil['jml_biaya_admin'] = $jml_biaya_admin;

            $jml_potong_admin = ($data_post['jns_potong_admin'] == "POTONG") ? $jml_biaya_admin : 0;
            $jml_potong_bunga = ($data_post['jns_potong_bunga'] == "POTONG") ? $jml_margin : 0;

            $hasil['jml_diterima'] = (hapus_koma($data_post['jml_pinjam']) - ($jml_potong_admin + $jml_potong_bunga));

            echo json_encode($hasil);
        }
    }

    public function add_simulasi_pht()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $data_post['tgl_pinjam'] = balik_tanggal($data_post['tgl_pinjam']);

            // if (!cek_tanggal_entri($data_post['tgl_pinjam'])) {
            //     $hasil['status'] = false;
            //     $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            // } else {

            $query = $this->pinjaman_model->insert_simulasi_pht($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Ditambah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Ditambah";
            }

            // }

            echo json_encode($hasil);
        }
    }

    public function edit_simulasi_pht($id)
    {
        $data_post = get_request('post');

        if ($data_post) {
            $data_post['tgl_pinjam'] = balik_tanggal($data_post['tgl_pinjam']);

            $query = $this->pinjaman_model->insert_simulasi_pht($data_post, $id, "1");

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diedit";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diedit";
            }

            // if (!cek_tanggal_entri($data_post['tgl_pinjam'])) {
            //     $hasil['status'] = false;
            //     $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            // } else {
            // }

            echo json_encode($hasil);
        }
    }

    public function view_angsuran()
    {
        $data_req = get_request();

        $view = "<table class=\"table table-bordered table-striped\" style=\"white-space: nowrap\">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Periode</th>
                    <th>Hari</th>
                    <th>Pokok Awal</th>
                    <th>Pokok</th>
                    <th>Margin</th>
                    <th>Angsuran</th>
                    <th>Pokok Akhir</th>
                </tr>
            </thead>
            <tbody>";

        $no = 1;

        $t_pokok_awal  = 0;
        $t_pokok       = 0;
        $t_bunga       = 0;
        $t_angsuran    = 0;
        $t_pokok_akhir = 0;

        $data_saldo = $this->pinjaman_model->get_simulasi_angsuran($data_req['no_pinjam']);

        foreach ($data_saldo->result_array() as $key => $value) {
            $view .= "
                <tr>
                    <td>" . $no . "</td>
                    <td>" . $value['blth_angsuran'] . "</td>
                    <td>" . $value['hari'] . "</td>
                    <td class=\"text-right\">" . number_format($value['pokok_awal'], 2, '.', ',') . "</td>
                    <td class=\"text-right\">" . number_format($value['pokok'], 2, '.', ',') . "</td>
                    <td class=\"text-right\">" . number_format($value['bunga'], 2, '.', ',') . "</td>
                    <td class=\"text-right\">" . number_format($value['angsuran'], 2, '.', ',') . "</td>
                    <td class=\"text-right\">" . number_format($value['pokok_akhir'], 2, '.', ',') . "</td>
                </tr>";

            $no++;
            $t_pokok_awal += $value['pokok_awal'];
            $t_pokok += $value['pokok'];
            $t_bunga += $value['bunga'];
            $t_angsuran += $value['angsuran'];
            $t_pokok_akhir += $value['pokok_akhir'];
        }

        $view .= "
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=\"3\">Total</th>
                    <th class=\"text-right\">" . number_format(0, 2, '.', ',') . "</th>
                    <th class=\"text-right\">" . number_format($t_pokok, 2, '.', ',') . "</th>
                    <th class=\"text-right\">" . number_format($t_bunga, 2, '.', ',') . "</th>
                    <th class=\"text-right\">" . number_format($t_angsuran, 2, '.', ',') . "</th>
                    <th class=\"text-right\">" . number_format(0, 2, '.', ',') . "</th>
                </tr>
            </tfoot>
        </table>";

        echo $view;
    }

    public function get_aprove_pinjaman($is_aprove)
    {
        $data = get_request();

        $cari['field'] = array("no_ang");
        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];
        // $bulan         = $data['bulan'];
        // $tahun         = $data['tahun'];

        $data_numrows = $this->pinjaman_model->get_simulasi_pinjaman(1, $cari, "tgl_pinjam1 desc", "", "", "", $is_aprove)->row(0)->numrows;
        $data_item    = $this->pinjaman_model->get_simulasi_pinjaman(0, $cari, "tgl_aprove desc", $offset, $limit, "", $is_aprove);

        $data_set = array();

        foreach ($data_item->result_array() as $value) {
            $offset++;
            $value['nomor'] = $offset;
            $data_set[]     = $value;
        }

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_set;

        echo json_encode($array);
    }

    public function aprove_pinjaman()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $query = $this->pinjaman_model->aprove_pinjaman($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diaprove";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diaprove";
            }

            echo json_encode($hasil);
        }
    }

    public function batalkan_aprove_pinjaman()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $query = $this->pinjaman_model->batalkan_aprove_pinjaman($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Dibatalkan";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Dibatalkan";
            }

            echo json_encode($hasil);
        }
    }

    public function get_pinjaman_sudah_realisasi()
    {
        $data = get_request();

        $cari['field'] = array('no_ang');
        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->pinjaman_model->get_pinjaman(1, $cari)->row(0)->numrows;
        $data_item    = $this->pinjaman_model->get_pinjaman(0, $cari, "", $offset, $limit);

        $data_set = array();

        foreach ($data_item->result_array() as $value) {
            $offset++;
            $value['nomor'] = $offset;
            $data_set[]     = $value;
        }

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_set;

        echo json_encode($array);
    }

    public function get_pinjaman_belum_realisasi()
    {
        $data = get_request();

        $cari['field'] = array('no_ang');
        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->pinjaman_model->get_simulasi_pinjaman(1, $cari, "tgl_aprove desc", "", "", "", "0", "0")->row(0)->numrows;
        $data_item    = $this->pinjaman_model->get_simulasi_pinjaman(0, $cari, "tgl_aprove desc", $offset, $limit, "", "0", "0");

        $data_set = array();

        foreach ($data_item->result_array() as $value) {
            $offset++;
            $value['nomor'] = $offset;
            $data_set[]     = $value;
        }

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_set;

        echo json_encode($array);
    }

    public function get_realisasi_vars()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $html_vars = "";

            if ($data_post['jenis_pinjaman'] == "2") {
                $data_tempo_kkb = $this->pinjaman_model->get_tempo_bln_kkb();

                $html_vars .= "
                    <div class=\"col-md-4\">
                        <div class=\"form-group\">
                            <label>Jangka Pinjaman</label>
                            <div class=\"input-group\">
                                <select id=\"tempo_bln\" name=\"tempo_bln\" class=\"form-control \" required=\"\" onchange=\"proses_perhitungan_kkb();\">" . get_option_tag($data_tempo_kkb) . "</select>
                                <div class=\"input-group-addon\">Bulan</div>
                            </div>
                        </div>
                        <div class=\"form-group\">
                            <label>Saldo Akhir/Sisa Pinjaman</label>
                            <div class=\"row\" style=\"margin: 0px\">
                                <div class=\"col-md-10\" style=\"padding: 0px\">
                                    <div class=\"input-group\">
                                        <div class=\"input-group-addon\">Rp</div>
                                        <input type=\"text\" id=\"saldo_akhir\" name=\"saldo_akhir\" class=\"form-control number_format\" readonly=\"\" value=\"0\" />
                                    </div>
                                </div>
                                <div class=\"col-md-2\" style=\"padding: 0 0 0 5px\">
                                    <a href=\"javascript:void(0)\" class=\"btn btn-info btn-block\" onclick=\"tampilkan_perhitungan_angsuran_kkb()\">
                                        <i class=\"fa fa-search\"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=\"col-md-4\">
                        <div class=\"form-group\">
                            <label>Angsuran Potong Gaji</label>
                            <div class=\"input-group\">
                                <input type=\"text\" name=\"persen_angsuran\" id=\"persen_angsuran\" class=\"form-control\" onchange=\"proses_perhitungan_kpr();\" required=\"\">
                                <div class=\"input-group-addon\">%</div>
                            </div>
                        </div>
                        <div class=\"form-group\">
                            <div class=\"row\">
                                <div class=\"col-md-6\" style=\"padding-right: 0px;\">
                                    <label>Min. Angsuran Bonus</label>
                                    <div class=\"input-group\">
                                        <input type=\"text\" name=\"min_angsuran\" id=\"min_angsuran\" class=\"form-control\" onchange=\"proses_perhitungan_kkb();\" data-rule-number=\"true\" required=\"\">
                                        <div class=\"input-group-addon\">%</div>
                                    </div>
                                </div>
                                <div class=\"col-md-6\">
                                    <label>Max. Angsuran Bonus</label>
                                    <div class=\"input-group\">
                                        <input type=\"text\" name=\"max_angsuran\" id=\"max_angsuran\" class=\"form-control\" onchange=\"proses_perhitungan_kkb();\" data-rule-number=\"true\" required=\"\">
                                        <div class=\"input-group-addon\">%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=\"col-md-4\">
                        <div class=\"form-group\">
                            <label>Jml. Potong Gaji</label>
                            <div class=\"input-group\">
                                <div class=\"input-group-addon\">Rp</div>
                                <input type=\"text\" id=\"angsuran\" name=\"angsuran\" class=\"form-control number_format\" readonly=\"\" value=\"0\" />
                            </div>
                            <input type=\"hidden\" name=\"angsuran_edit\" id=\"angsuran_edit\" value=\"0\">
                        </div>
                        <div class=\"form-group\">
                            <div class=\"row\">
                                <div class=\"col-md-6\" style=\"padding-right: 0px;\">
                                    <label>Jml. Min Angs Bonus</label>
                                    <div class=\"input-group\">
                                        <div class=\"input-group-addon\">Rp</div>
                                        <input type=\"text\" id=\"jml_min_angsuran\" name=\"jml_min_angsuran\" class=\"form-control number_format\" readonly=\"\" value=\"0\" />
                                    </div>
                                </div>
                                <div class=\"col-md-6\">
                                    <label>Jml. Max Angs Bonus</label>
                                    <div class=\"input-group\">
                                        <div class=\"input-group-addon\">Rp</div>
                                        <input type=\"text\" id=\"jml_max_angsuran\" name=\"jml_max_angsuran\" class=\"form-control number_format\" readonly=\"\" value=\"0\" />
                                    </div>
                                    <input type=\"hidden\" name=\"jml_max_angsuran_edit\" id=\"jml_max_angsuran_edit\" value=\"0\">
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                    $('#fm_modal').validate().destroy();

                    $('#fm_modal').validate({
                        rules: {
                            tempo_bln: {
                                required: true
                            },
                            min_angsuran: {
                                required: true,
                                number: true
                            },
                            max_angsuran: {
                                required: true,
                                number: true
                            }
                        }
                    });

                    $('#fm_modal #tgl_realisasi, #fm_modal #jml_pinjam, #fm_modal #margin').unbind('change').on('change', function() {
                        proses_perhitungan_kkb();
                    });
                    </script>
                ";
            } else if ($data_post['jenis_pinjaman'] == "4") {
                $data_tempo_kpr = $this->pinjaman_model->get_tempo_bln_kpr();

                $html_vars .= "
                    <div class=\"col-md-4\">
                        <div class=\"form-group\">
                            <label>Jangka Pinjaman</label>
                            <div class=\"input-group\">
                                <select id=\"tempo_bln\" name=\"tempo_bln\" class=\"form-control\" required=\"\" onchange=\"proses_perhitungan_kpr();\">" . get_option_tag($data_tempo_kpr) . "</select>
                                <div class=\"input-group-addon\">Bulan</div>
                            </div>
                        </div>
                        <div class=\"form-group\">
                            <label>Saldo Akhir/Sisa Pinjaman</label>
                            <div class=\"row\" style=\"margin: 0px\">
                                <div class=\"col-md-10\" style=\"padding: 0px\">
                                    <div class=\"input-group\">
                                        <div class=\"input-group-addon\">Rp</div>
                                        <input type=\"text\" id=\"saldo_akhir\" name=\"saldo_akhir\" class=\"form-control number_format\" readonly=\"\" value=\"0\" />
                                    </div>
                                </div>
                                <div class=\"col-md-2\" style=\"padding: 0 0 0 5px\">
                                    <a href=\"javascript:void(0)\" class=\"btn btn-info btn-block\" onclick=\"tampilkan_perhitungan_angsuran_kpr()\">
                                        <i class=\"fa fa-search\"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=\"col-md-4\">
                        <div class=\"form-group\">
                            <label>Angsuran Potong Gaji</label>
                            <div class=\"input-group\">
                                <input type=\"text\" name=\"persen_angsuran\" id=\"persen_angsuran\" class=\"form-control\" onchange=\"proses_perhitungan_kpr();\" required=\"\">
                                <div class=\"input-group-addon\">%</div>
                            </div>
                        </div>
                        <div class=\"form-group\">
                            <div class=\"row\">
                                <div class=\"col-md-6\" style=\"padding-right: 0px;\">
                                    <label>Min. Angsuran Bonus</label>
                                    <div class=\"input-group\">
                                        <input type=\"text\" name=\"min_angsuran\" id=\"min_angsuran\" class=\"form-control\" onchange=\"proses_perhitungan_kpr();\" data-rule-number=\"true\" required=\"\">
                                        <div class=\"input-group-addon\">%</div>
                                    </div>
                                </div>
                                <div class=\"col-md-6\">
                                    <label>Max. Angsuran Bonus</label>
                                    <div class=\"input-group\">
                                        <input type=\"text\" name=\"max_angsuran\" id=\"max_angsuran\" class=\"form-control\" onchange=\"proses_perhitungan_kpr();\" data-rule-number=\"true\" required=\"\">
                                        <div class=\"input-group-addon\">%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=\"col-md-4\">
                        <div class=\"form-group\">
                            <label>Jml. Potong Gaji</label>
                            <div class=\"input-group\">
                                <div class=\"input-group-addon\">Rp</div>
                                <input type=\"text\" id=\"angsuran\" name=\"angsuran\" class=\"form-control number_format\" readonly=\"\" value=\"0\" />
                            </div>
                            <input type=\"hidden\" name=\"angsuran_edit\" id=\"angsuran_edit\" value=\"0\">
                        </div>
                        <div class=\"form-group\">
                            <div class=\"row\">
                                <div class=\"col-md-6\" style=\"padding-right: 0px;\">
                                    <label>Min. Angsuran Bonus</label>
                                    <div class=\"input-group\">
                                        <div class=\"input-group-addon\">Rp</div>
                                        <input type=\"text\" id=\"jml_min_angsuran\" name=\"jml_min_angsuran\" class=\"form-control number_format\" readonly=\"\" value=\"0\" />
                                    </div>
                                    <input type=\"hidden\" name=\"angsuran_edit\" id=\"angsuran_edit\" value=\"0\">
                                </div>
                                <div class=\"col-md-6\">
                                    <label>Jml. Max Angs</label>
                                    <div class=\"input-group\">
                                        <div class=\"input-group-addon\">Rp</div>
                                        <input type=\"text\" id=\"jml_max_angsuran\" name=\"jml_max_angsuran\" class=\"form-control number_format\" readonly=\"\" value=\"0\" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                    $('#fm_modal').validate().destroy();

                    $('#fm_modal').validate({
                        rules: {
                            tempo_bln: {
                                required: true
                            },
                            persen_angsuran: {
                                required: true,
                                number: true
                            },
                            min_angsuran: {
                                required: true,
                                number: true
                            },
                            max_angsuran: {
                                required: true,
                                number: true
                            }
                        }
                    });

                    $('#fm_modal #tgl_realisasi, #fm_modal #jml_pinjam, #fm_modal #margin').unbind('change').on('change', function() {
                        proses_perhitungan_kpr();
                    });
                    </script>
                ";
            } else if ($data_post['jenis_pinjaman'] == "1") {
                $data_tempo_reguler = $this->pinjaman_model->get_tempo_bln_reguler();

                $data_anggota = $this->db->where("no_ang", $data_post['no_ang'])->limit(1)->get("t_anggota");

                if ($data_anggota->num_rows() > 0) {
                    $is_pensiun = $data_anggota->row(0)->is_pensiun;
                } else {
                    $is_pensiun = 0;
                }

                $html_vars .= "
                    <div class=\"col-md-4\">
                        <div class=\"form-group\">
                            <label>Jangka Pinjaman</label>
                            <div class=\"input-group\">
                                <select id=\"tempo_bln\" name=\"tempo_bln\" class=\"form-control \" required=\"\" onchange=\"proses_perhitungan_reguler();\">" . get_option_tag($data_tempo_reguler) . "</select>
                                <div class=\"input-group-addon\">Bulan</div>
                            </div>
                        </div>
                    </div>
                    <div class=\"col-md-4\">
                        <div class=\"form-group\">
                            <label>Angsuran Potga</label>
                            <div class=\"input-group\">
                                <div class=\"input-group-addon\">Rp</div>
                                <input id=\"angsuran\" name=\"angsuran\" class=\"form-control number_format\" readonly=\"\" />
                            </div>
                        </div>
                    </div>
                    <div class=\"col-md-4\">";

                if ($is_pensiun == "1") {
                    $html_vars .= "
                        <div class=\"form-group\">
                            <label>Ada Agunan? (Khusus Extern)</label>
                            <select id=\"is_agunan\" name=\"is_agunan\" class=\"form-control\">
                                <option value=\"0\">Tidak</option>
                                <option value=\"1\">Ya</option>
                            </select>
                        </div>";
                }

                $html_vars .= "
                    </div>
                    <script>
                    $('#fm_modal #tgl_realisasi, #fm_modal #jml_pinjam, #fm_modal #margin').unbind('change').on('change', function() {
                        proses_perhitungan_reguler();
                    });
                    </script>
                ";
            } else if ($data_post['jenis_pinjaman'] == "3") {
                $html_vars .= "
                    <div class=\"col-md-4\">
                        <div class=\"form-group\">
                            <label>Jangka Pinjaman</label>
                            <div class=\"input-group\">
                                <input id=\"tempo_bln\" name=\"tempo_bln\" class=\"form-control\" onchange=\"proses_perhitungan_pht();\" />
                                <div class=\"input-group-addon\">Bulan</div>
                            </div>
                        </div>
                    </div>
                    <div class=\"col-md-4\">
                        <div class=\"form-group\">
                            <label>Angsuran Potga</label>
                            <div class=\"input-group\">
                                <div class=\"input-group-addon\">Rp</div>
                                <input id=\"angsuran\" name=\"angsuran\" class=\"form-control number_format\" readonly=\"\" />
                                <input type=\"hidden\" id=\"jns_potong_admin\" name=\"jns_potong_admin\" class=\"form-control\" readonly=\"\" />
                                <input type=\"hidden\" id=\"jns_potong_bunga\" name=\"jns_potong_bunga\" class=\"form-control\" readonly=\"\" />
                            </div>
                        </div>
                    </div>
                    <script>
                    $('#fm_modal #tgl_realisasi, #fm_modal #jml_pinjam, #fm_modal #margin').unbind('change').on('change', function() {
                        proses_perhitungan_pht();
                    });
                    </script>
                ";
            }

            echo $html_vars;
        }
    }

    public function realisasi_pinjaman()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $data_post['tgl_realisasi'] = balik_tanggal($data_post['tgl_realisasi']);
            $data_post['tgl_pinjam']    = balik_tanggal($data_post['tgl_pinjam']);

            // if (!cek_tanggal_entri($data_post['tgl_realisasi'])) {
            //     $hasil['status'] = false;
            //     $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            // } else {

            $query = $this->pinjaman_model->realisasi_pinjaman($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diproses";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diproses";
            }

            // }

            echo json_encode($hasil);
        }
    }

    public function hapus_realisasi_pinjaman()
    {
        $data_post = get_request('post');

        if ($data_post) {
            // $data_post['tgl_realisasi'] = balik_tanggal($data_post['tgl_realisasi']);
            // $data_post['tgl_pinjam']    = balik_tanggal($data_post['tgl_pinjam']);

            // exit(baca_array($data_post));

            // if (!cek_tanggal_entri($data_post['tgl_realisasi'])) {
            //     $hasil['status'] = false;
            //     $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            // } else {

            $query = $this->pinjaman_model->hapus_realisasi_pinjaman($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diproses";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diproses";
            }

            // }

            echo json_encode($hasil);
        }
    }

    public function get_angsuran_pinjaman($sts_lunas = "", $sts_potga = "")
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];
        // $bulan         = $data['bulan'];
        // $tahun         = $data['tahun'];

        $no_ang = isset($data['no_ang']) ? $data['no_ang'] : "xxx";

        $data_numrows = $this->pinjaman_model->get_angsuran_pinjaman(1, $cari, "", "", "", $no_ang, $sts_lunas, $sts_potga)->row(0)->numrows;
        $data_item    = $this->pinjaman_model->get_angsuran_pinjaman(0, $cari, "", $offset, $limit, $no_ang, $sts_lunas, $sts_potga);

        $data_set = array();

        foreach ($data_item->result_array() as $value) {
            $offset++;
            $value['nomor'] = $offset;
            $data_set[]     = $value;
        }

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_set;

        echo json_encode($array);
    }

    public function pelunasan_angsuran()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $data_post['jns_pelunasan'] = "PLAS";
            $data_post['kode_bukti']    = "PL";

            $query = $this->pinjaman_model->pelunasan_angsuran($data_post);

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

    public function get_pelunasan_pinjaman()
    {
        $data = get_request();

        $cari['field'] = array("no_ang");
        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];
        // $bulan         = $data['bulan'];
        // $tahun         = $data['tahun'];

        $jns_pelunasan = isset($data['jns_pelunasan']) ? $data['jns_pelunasan'] : "";

        $data_numrows = $this->pinjaman_model->get_pelunasan_pinjaman(1, $cari, "", "", "", $jns_pelunasan)->row(0)->numrows;
        $data_item    = $this->pinjaman_model->get_pelunasan_pinjaman(0, $cari, "", $offset, $limit, $jns_pelunasan);

        $data_set = array();

        foreach ($data_item->result_array() as $value) {
            $offset++;
            $value['nomor'] = $offset;
            $data_set[]     = $value;
        }

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_set;

        echo json_encode($array);
    }

    public function hapus_pelunasan_angsuran()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $query = $this->pinjaman_model->hapus_pelunasan_angsuran($data_post);

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

    public function get_pinjaman_belum_lunas()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];
        // $bulan         = $data['bulan'];
        // $tahun         = $data['tahun'];

        $no_ang = isset($data['no_ang']) ? $data['no_ang'] : "xxx";

        $data_numrows = $this->pinjaman_model->get_pinjaman_belum_lunas(1, $cari, "", "", "", $no_ang)->row(0)->numrows;
        $data_item    = $this->pinjaman_model->get_pinjaman_belum_lunas(0, $cari, "", $offset, $limit, $no_ang);

        $data_set = array();

        foreach ($data_item->result_array() as $value) {
            $offset++;
            $value['nomor'] = $offset;
            $data_set[]     = $value;
        }

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_set;

        echo json_encode($array);
    }

    public function get_var_pelunasan_dipercepat()
    {
        $data_post = get_request('post');

        $html = "";

        if ($data_post) {
            if ($data_post['kd_pinjaman'] == "3") {
                $html .= "<h4>Pembayaran Pinjaman Uang PHT</h4>
                    <table class=\"table table-bordered table-condensed table-striped form-inline\">
                        <tbody>
                            <tr>
                                <td>1.</td>
                                <td>Pokok Pinjaman</td>
                                <td>
                                    <div class=\"input-group\">
                                        <div class=\"input-group-addon\">Rp</div>
                                        <input type=\"text\" name=\"jml_pokok\" id=\"jml_pokok\" class=\"form-control number_format text-right\" readonly=\"\" value=\"" . number_format($data_post['jml_pinjam'], 2) . "\">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>Denda => Pokok Pinjaman x
                                    <div class=\"input-group\">
                                        <input type=\"text\" name=\"persen_denda\" id=\"persen_denda\" class=\"form-control\" onchange=\"hitung_total()\">
                                        <div class=\"input-group-addon\">%</div>
                                    </div></td>
                                <td>
                                    <div class=\"input-group\">
                                        <div class=\"input-group-addon\">Rp</div>
                                        <input type=\"text\" name=\"jml_denda\" id=\"jml_denda\" class=\"form-control number_format text-right\" readonly=\"\" value=\"0\">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>Bunga 1 Bulan</td>
                                <td>
                                    <div class=\"input-group\">
                                        <div class=\"input-group-addon\">Rp</div>
                                        <input type=\"text\" name=\"jml_bunga\" id=\"jml_bunga\" class=\"form-control number_format text-right\" readonly=\"\" value=\"" . number_format($data_post['bunga'], 2) . "\">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=\"2\">Jumlah Bayar</td>
                                <td>
                                    <div class=\"input-group\">
                                        <div class=\"input-group-addon\">Rp</div>
                                        <input type=\"text\" name=\"jml_bayar\" id=\"jml_bayar\" class=\"form-control number_format text-right input-lg text-bold\" readonly=\"\">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <script type=\"text/javascript\">
                    $(\".number_format\").on(\"change\", function() { $(this).val(number_format($(this).val(), 2)); });

                    $(\"#fm_pelunasan\").validate().destroy();

                    $(\"#fm_pelunasan\").validate({
                        rules: {
                            persen_denda: {
                                required: true,
                                number: true
                            }
                        }
                    });

                    function hitung_total() {
                        var_jml_pokok = parseFloat(hapus_koma($(\"#jml_pokok\").val()));
                        var_jml_bunga = parseFloat(hapus_koma($(\"#jml_bunga\").val()));
                        var_persen_denda = $(\"#persen_denda\").val();
                        var_jml_denda = var_jml_pokok * var_persen_denda / 100;
                        var_jml_bayar = var_jml_pokok + var_jml_bunga + var_jml_denda;

                        $(\"#jml_denda\").val(var_jml_denda).trigger(\"change\");
                        $(\"#jml_bayar\").val(var_jml_bayar).trigger(\"change\");
                    }
                    </script>
                ";

            } else if (in_array($data_post['kd_pinjaman'], array("2", "4"))) {
                $html .= "<h4>Pembayaran Pinjaman Uang KKB/KPR</h4>
                <table class=\"table table-bordered table-condensed table-striped form-inline\">
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>Posisi Akhir</td>
                            <td>
                                <div class=\"input-group\">
                                    <div class=\"input-group-addon\">Rp</div>
                                    <input type=\"text\" name=\"jml_angsuran\" id=\"jml_angsuran\" class=\"form-control number_format text-right\" readonly=\"\" value=\"" . number_format($data_post['posisi_akhir'], 2) . "\">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Denda => Posisi Akhir x
                                <div class=\"input-group\">
                                    <input type=\"text\" name=\"persen_denda\" id=\"persen_denda\" class=\"form-control\" onchange=\"hitung_total()\" value=\"\">
                                    <div class=\"input-group-addon\">%</div>
                                </div></td>
                            <td>
                                <div class=\"input-group\">
                                    <div class=\"input-group-addon\">Rp</div>
                                    <input type=\"text\" name=\"jml_denda\" id=\"jml_denda\" class=\"form-control number_format text-right\" readonly=\"\" value=\"0\">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Asuransi => Posisi Akhir x " . $data_post['sisa_bln'] . " Bulan x <div class=\"input-group\">
                                    <input type=\"text\" name=\"persen_asuransi\" id=\"persen_asuransi\" class=\"form-control\" onchange=\"hitung_total()\" value=\"\">
                                    <div class=\"input-group-addon\">%</div>
                                </div></td>
                            <td>
                                <input type=\"hidden\" name=\"sisa_bln\" id=\"sisa_bln\" value=\"" . $data_post['sisa_bln'] . "\">
                                <div class=\"input-group\">
                                    <div class=\"input-group-addon\">Rp</div>
                                    <input type=\"text\" name=\"jml_asuransi\" id=\"jml_asuransi\" class=\"form-control number_format text-right\" readonly=\"\" value=\"0\">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=\"2\">Jumlah Bayar</td>
                            <td>
                                <div class=\"input-group\">
                                    <div class=\"input-group-addon\">Rp</div>
                                    <input type=\"text\" name=\"jml_bayar\" id=\"jml_bayar\" class=\"form-control number_format text-right input-lg text-bold\" readonly=\"\" value=\"0\">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <script type=\"text/javascript\">
                $(\".number_format\").on(\"change\", function() { $(this).val(number_format($(this).val(), 2)); });

                $(\"#fm_pelunasan\").validate().destroy();

                $(\"#fm_pelunasan\").validate({
                    rules: {
                        persen_denda: {
                            required: true,
                            number: true
                        },
                        persen_asuransi: {
                            required: true,
                            number: true
                        }
                    }
                });

                function hitung_total() {
                    var_jml_angsuran = parseFloat(hapus_koma($(\"#jml_angsuran\").val()));
                    var_sisa_bln = parseFloat(hapus_koma($(\"#sisa_bln\").val()));
                    var_persen_denda = $(\"#persen_denda\").val();
                    var_jml_denda = var_jml_angsuran * var_persen_denda / 100;
                    var_persen_asuransi = $(\"#persen_asuransi\").val();
                    var_jml_asuransi = var_jml_angsuran * var_sisa_bln * (var_persen_asuransi/100)
                    var_jml_bayar = var_jml_angsuran + var_jml_denda + var_jml_asuransi;

                    $(\"#jml_denda\").val(var_jml_denda).trigger(\"change\");
                    $(\"#jml_asuransi\").val(var_jml_asuransi).trigger(\"change\");
                    $(\"#jml_bayar\").val(var_jml_bayar).trigger(\"change\");
                }
                </script>
                ";
            }
        }

        echo $html;
    }

    public function proses_pelunasan_dipercepat()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $data_post['jns_pelunasan'] = "PLNS";
            $data_post['kode_bukti']    = "PL";

            $query = $this->pinjaman_model->proses_pelunasan_dipercepat($data_post);

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

    public function hapus_pelunasan_dipercepat()
    {
        $data_post = get_request('post');

        if ($data_post) {
            $query = $this->pinjaman_model->hapus_pelunasan_dipercepat($data_post);

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

}
