<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();

        $this->load->model("master_model");
    }

    public function index($page)
    {
        $data_bulan          = array_bulan();
        $data_tempo          = $this->master_model->get_tempo_bln();
        $data_tempo_simpanan = $this->master_model->get_tempo_bln_simpanan();

        if ($page == "jenis-pinjaman") {
            $data['judul_menu'] = "Master Jenis Pinjaman";
            $this->template->view("master/master_jenis_pinjaman", $data);
        }
        if ($page == "jenis-simpanan") {
            $data['judul_menu'] = "Master Jenis Simpanan";
            $this->template->view("master/master_jenis_simpanan", $data);
        }
        if ($page == "margin-pinjaman") {
            $data['judul_menu'] = "Master Margin Pinjaman";
            $data['tempo_bln']  = get_option_tag($data_tempo);

            $this->template->view("master/master_margin_pinjaman", $data);
        }
        if ($page == "transaksi-simpanan") {
            $data['judul_menu'] = "Master Transaksi Simpanan";
            $data['tempo_bln']  = get_option_tag($data_tempo_simpanan);

            $this->template->view("master/master_transaksi_simpanan", $data);
        }
        if ($page == "margin-simpanan") {
            $data['judul_menu'] = "Master Margin Simpanan";
            $data['tempo_bln']  = get_option_tag($data_tempo_simpanan);

            $this->template->view("master/master_margin_simpanan", $data);
        }
        if ($page == "potongan-bonus-pg") {
            $data['judul_menu'] = "Master Potongan Hak Diluar Gaji";
            $data['bulan']      = get_option_tag($data_bulan);

            $this->template->view("master/master_pot_bonus_pg", $data);
        }
        if ($page == "potga-ss1") {
            $data['judul_menu'] = "Master Potongan Gaji Simpanan Sukarela1";
            $data['bulan']      = get_option_tag($data_bulan);

            $this->template->view("master/master_potga_ss1", $data);
        }
        if ($page == "akun") {
            $data['judul_menu'] = "Master Akun";
            // $data['bulan']      = get_option_tag($data_bulan);

            $this->template->view("master/master_akun", $data);
        }
        if ($page == "kasbank") {
            $data['judul_menu'] = "Master Kasbank";
            // $data['bulan']      = get_option_tag($data_bulan);

            $this->template->view("master/master_kasbank", $data);
        }
    }

    public function get_jenis_pinjaman()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->master_model->get_jenis_pinjaman(1, $cari)->row(0)->numrows;
        $data_item    = $this->master_model->get_jenis_pinjaman(0, $cari, "", $offset, $limit);

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

    public function add_jenis_pinjaman()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->insert_jenis_pinjaman($data_post);

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

    public function edit_jenis_pinjaman($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->update_jenis_pinjaman($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diubah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diubah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_jenis_pinjaman()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->delete_jenis_pinjaman($data_post);

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

    public function get_jenis_simpanan()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->master_model->get_jenis_simpanan(1, $cari)->row(0)->numrows;
        $data_item    = $this->master_model->get_jenis_simpanan(0, $cari, "", $offset, $limit);

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

    public function add_jenis_simpanan()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->insert_jenis_simpanan($data_post);

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

    public function edit_jenis_simpanan($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->update_jenis_simpanan($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diubah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diubah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_jenis_simpanan()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->delete_jenis_simpanan($data_post);

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

    public function select_jenis_pinjaman()
    {
        $data_req = get_request();

        $value = isset($data_req['value']) ? $data_req['value'] : "";
        $q     = isset($data_req['q']) ? $data_req['q'] : $value;

        $cari['value'] = $q;

        $data = $this->master_model->get_jenis_pinjaman("", $cari, "", 0, 100)->result_array();

        $arrData = array();

        foreach ($data as $key => $value) {
            $value['id']   = $value['kd_jns_pinjaman'];
            $value['text'] = $value['nm_jns_pinjaman'];

            $arrData['results'][] = $value;
        }

        echo json_encode($arrData);
    }

    public function select_jenis_simpanan($mode_list = "")
    {
        $data_req = get_request();

        $value = isset($data_req['value']) ? $data_req['value'] : "";
        $q     = isset($data_req['q']) ? $data_req['q'] : $value;

        $cari['value'] = $q;

        $data = $this->master_model->get_jenis_simpanan("", $cari, "", 0, 100, $mode_list)->result_array();

        $arrData = array();

        foreach ($data as $key => $value) {
            $value['id']   = $value['kd_jns_simpanan'];
            $value['text'] = $value['nm_jns_simpanan'];

            $arrData['results'][] = $value;
        }

        echo json_encode($arrData);
    }

    public function get_margin_pinjaman()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->master_model->get_margin_pinjaman(1, $cari)->row(0)->numrows;
        $data_item    = $this->master_model->get_margin_pinjaman(0, $cari, "", $offset, $limit);

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

    public function add_margin_pinjaman()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_berlaku'] = balik_tanggal($data_post['tgl_berlaku']);

            $query = $this->master_model->insert_margin_pinjaman($data_post);

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

    public function edit_margin_pinjaman($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_berlaku'] = balik_tanggal($data_post['tgl_berlaku']);

            $query = $this->master_model->update_margin_pinjaman($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diubah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diubah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_margin_pinjaman()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->delete_margin_pinjaman($data_post);

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

    public function get_margin_simpanan()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->master_model->get_margin_simpanan(1, $cari)->row(0)->numrows;
        $data_item    = $this->master_model->get_margin_simpanan(0, $cari, "", $offset, $limit);

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

    public function add_margin_simpanan()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_berlaku'] = balik_tanggal($data_post['tgl_berlaku']);

            $query = $this->master_model->insert_margin_simpanan($data_post);

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

    public function edit_margin_simpanan($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_berlaku'] = balik_tanggal($data_post['tgl_berlaku']);

            $query = $this->master_model->update_margin_simpanan($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diubah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diubah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_margin_simpanan()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->delete_margin_simpanan($data_post);

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

    public function get_jenis_transaksi_simpanan()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->master_model->get_jenis_transaksi_simpanan(1, $cari)->row(0)->numrows;
        $data_item    = $this->master_model->get_jenis_transaksi_simpanan(0, $cari, "", $offset, $limit);

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

    public function add_jenis_transaksi_simpanan()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->insert_jenis_transaksi_simpanan($data_post);

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

    public function edit_jenis_transaksi_simpanan($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->update_jenis_transaksi_simpanan($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diubah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diubah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_jenis_transaksi_simpanan()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->delete_jenis_transaksi_simpanan($data_post);

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

    public function select_jenis_transaksi_simpanan($kd_jns_simpanan = "")
    {
        $data_req = get_request();

        $value = isset($data_req['value']) ? $data_req['value'] : "";
        $q     = isset($data_req['q']) ? $data_req['q'] : $value;

        $cari['value'] = $q;

        $data = $this->master_model->get_jenis_transaksi_simpanan("", $cari, "kd_jns_simpanan, kd_jns_transaksi", 0, 100, $kd_jns_simpanan)->result_array();

        $arrData = array();

        foreach ($data as $key => $value) {
            $value['id']   = $value['kd_jns_transaksi'];
            $value['text'] = "[" . $value['kd_jns_transaksi'] . "] " . $value['nm_jns_transaksi'];

            $arrData['results'][] = $value;
        }

        echo json_encode($arrData);
    }

    public function get_pot_bonus_pg()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->master_model->get_pot_bonus_pg(1, $cari, "", "", "", "0")->row(0)->numrows;
        $data_item    = $this->master_model->get_pot_bonus_pg(0, $cari, "", $offset, $limit, "0");

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

    public function add_pot_bonus_pg()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['is_jadwal_tetap'] = "0";

            $query = $this->master_model->insert_pot_bonus_pg($data_post);

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

    public function edit_pot_bonus_pg($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['is_jadwal_tetap'] = "0";

            $query = $this->master_model->update_pot_bonus_pg($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diubah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diubah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_pot_bonus_pg()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->delete_pot_bonus_pg($data_post);

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

    public function get_pot_bonus_tetap()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->master_model->get_pot_bonus_pg(1, $cari, "", "", "", "1")->row(0)->numrows;
        $data_item    = $this->master_model->get_pot_bonus_pg(0, $cari, "", $offset, $limit, "1");

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

    public function add_pot_bonus_tetap()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['is_jadwal_tetap'] = "1";
            $data_post['tahun']           = null;

            $query = $this->master_model->insert_pot_bonus_pg($data_post);

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

    public function edit_pot_bonus_tetap($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['is_jadwal_tetap'] = "1";
            $data_post['tahun']           = null;

            $query = $this->master_model->update_pot_bonus_pg($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diubah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diubah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_pot_bonus_tetap()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->delete_pot_bonus_pg($data_post);

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

    public function get_potga_ss1()
    {
        $data = get_request();

        $cari['field'] = array("no_ang");
        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->master_model->get_potga_ss1(1, $cari)->row(0)->numrows;
        $data_item    = $this->master_model->get_potga_ss1(0, $cari, "", $offset, $limit);

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

    public function add_potga_ss1()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->insert_potga_ss1($data_post);

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

    public function edit_potga_ss1($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->update_potga_ss1($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diubah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diubah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_potga_ss1()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->delete_potga_ss1($data_post);

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

    public function get_excel_master_potga_ss1($tahun, $bulan)
    {
        $file = "potga_ss1_" . $bulan . "-" . $tahun . ".xls";

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . $file);

        $laporan = "<table border=\"1\">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NAK</th>
                    <th>No. Peg</th>
                    <th>Nama</th>
                    <th>Prsh</th>
                    <th>Departemen</th>
                    <th>Bagian</th>
                    <th>Jumlah</th>
                    <th>Periode Potga</th>
                </tr>
            </thead>
            <tbody>";

        /*$query = "SELECT a.*
        FROM m_potga_ss1 a
        JOIN (
        SELECT no_ang, max(tgl_masuk_ss1) max_tgl_masuk_ss1 FROM m_potga_ss1
        WHERE tahun <= '" . $tahun . "' AND bulan <= '" . $bulan . "'
        GROUP BY no_ang
        ) b
        on a.no_ang=b.no_ang AND a.tgl_masuk_ss1 = b.max_tgl_masuk_ss1
        order by a.no_ang";*/

        $no = 1;

        $query = "SELECT a.*
                FROM m_potga_ss1 a
                JOIN (
                    SELECT no_ang, max(tgl_masuk_ss1) max_tgl_masuk_ss1 FROM m_potga_ss1
                    WHERE CONCAT(tahun, '-', bulan) <= '" . $tahun . "-" . $bulan . "'
                    GROUP BY no_ang
                ) b
                on a.no_ang=b.no_ang AND a.tgl_masuk_ss1 = b.max_tgl_masuk_ss1";

        $data_potga = $this->db->query($query);

        foreach ($data_potga->result_array() as $key => $value) {
            $laporan .= "
                <tr>
                    <td style=\"text-align: right\">" . $no . "</td>
                    <td>&nbsp;" . $value['no_ang'] . "</td>
                    <td>&nbsp;" . $value['no_peg'] . "</td>
                    <td>" . $value['nm_ang'] . "</td>
                    <td>" . $value['nm_prsh'] . "</td>
                    <td>" . $value['nm_dep'] . "</td>
                    <td>" . $value['nm_bagian'] . "</td>
                    <td>" . number_format($value['jumlah'], 2) . "</td>
                    <td>" . $bulan . "-" . $tahun . "</td>
                </tr>";

            $no++;
        }

        $laporan .= "
                </tbody>
            </table>";

        echo $laporan;
    }

    public function select_perusahaan()
    {
        $data_req = get_request();

        $value = isset($data_req['value']) ? $data_req['value'] : "";
        $q     = isset($data_req['q']) ? $data_req['q'] : $value;

        $cari['value'] = $q;

        $data = $this->master_model->get_perusahaan("", $cari, "nm_prsh", 0, 100)->result_array();

        $arrData = array();

        foreach ($data as $key => $value) {
            $value['id']   = $value['kd_prsh'];
            $value['text'] = $value['nm_prsh'];

            $arrData['results'][] = $value;
        }

        echo json_encode($arrData);
    }

    public function get_akun()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->master_model->get_akun(1, $cari)->row(0)->numrows;
        $data_item    = $this->master_model->get_akun(0, $cari, "", $offset, $limit);

        // $data_set = array();

        // foreach ($data_item->result_array() as $value) {
        //     $offset++;
        //     $value['nomor'] = $offset;
        //     $data_set[]     = $value;
        // }

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_item->result_array();

        echo json_encode($array);
    }

    public function add_akun()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->insert_akun($data_post);

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

    public function edit_akun($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->update_akun($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diubah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diubah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_akun()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->delete_akun($data_post);

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

    public function select_akun()
    {
        $data_req = get_request();

        $value = isset($data_req['value']) ? $data_req['value'] : "";
        $q     = isset($data_req['q']) ? $data_req['q'] : $value;

        $cari['value'] = $q;

        $data = $this->master_model->get_akun("", $cari, "", 0, 100)->result_array();

        $arrData['results'] = $data;

        echo json_encode($arrData);
    }

    public function get_kasbank()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->master_model->get_kasbank(1, $cari)->row(0)->numrows;
        $data_item    = $this->master_model->get_kasbank(0, $cari, "", $offset, $limit);

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_item->result_array();

        echo json_encode($array);
    }

    public function add_kasbank()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->insert_kasbank($data_post);

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

    public function edit_kasbank($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->update_kasbank($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diubah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diubah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_kasbank()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->master_model->delete_kasbank($data_post);

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

    public function select_kasbank()
    {
        $data_req = get_request();

        $value = isset($data_req['value']) ? $data_req['value'] : "";
        $q     = isset($data_req['q']) ? $data_req['q'] : $value;

        $cari['value'] = $q;

        $data = $this->master_model->get_kasbank("", $cari, "", 0, 100)->result_array();

        $arrData['results'] = $data;

        echo json_encode($arrData);
    }

}
