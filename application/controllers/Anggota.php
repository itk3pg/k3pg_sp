<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Anggota extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();

        $this->load->model("anggota_model");
    }

    public function index($page)
    {
        if ($page == "anggota-masuk") {
            $data['judul_menu'] = "Entri Anggota Masuk";
            $this->template->view("anggota/entri_anggota_masuk", $data);
        }
        if ($page == "anggota-pindah") {
            $data['judul_menu'] = "Entri Anggota Pindah";
            $this->template->view("anggota/entri_anggota_pindah", $data);
        }
        if ($page == "anggota-keluar") {
            $data['judul_menu'] = "Entri Anggota Keluar";
            $this->template->view("anggota/entri_anggota_keluar", $data);
        }
        if ($page == "update-anggota") {
            $data['judul_menu'] = "Update Data Anggota";
            $this->template->view("anggota/update_data_anggota", $data);
        }
        if ($page == "nasabah") {
            $data['judul_menu'] = "Master Nasabah";

            $this->template->view("master/nasabah", $data);
        }
    }

    public function add_anggota_masuk()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_lhr'] = balik_tanggal($data_post['tgl_lhr']);
            $data_post['tgl_msk'] = balik_tanggal($data_post['tgl_msk']);

            if (!cek_tanggal_entri($data_post['tgl_msk'])) {
                $hasil['status'] = false;
                $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            } else {
                $query = $this->anggota_model->insert_anggota_masuk($data_post);

                if ($query) {
                    $hasil['status'] = true;
                    $hasil['msg']    = "Data Berhasil Ditambah";
                } else {
                    $hasil['status'] = false;
                    $hasil['msg']    = "Data Gagal Ditambah";
                }
            }

            echo json_encode($hasil);
        }
    }

    public function select_anggota_by_noang($status_anggota = "")
    {
        $data_req = get_request();

        $value = isset($data_req['value']) ? $data_req['value'] : "";
        $q     = isset($data_req['q']) ? $data_req['q'] : $value;

        // $cari['value'] = $q;
        // $cari['field'] = array("no_ang");

        $data = $this->anggota_model->get_anggota("", "", "", 0, 50, $status_anggota, $q)->result_array();

        $arrData = array();

        foreach ($data as $key => $value) {
            $value['id']   = $value['no_ang'];
            $value['text'] = "[" . $value['no_ang'] . " | " . $value['no_peg'] . "] " . $value['nm_ang'] . " | " . $value['nm_prsh'];

            $arrData['results'][] = $value;
        }

        echo json_encode($arrData);
    }

    public function select_nasabah_by_noang($status_anggota = "")
    {
        $data_req = get_request();

        $value = isset($data_req['value']) ? $data_req['value'] : "";
        $q     = isset($data_req['q']) ? $data_req['q'] : $value;

        // $cari['value'] = $q;
        // $cari['field'] = array("no_ang");

        $data = $this->anggota_model->get_nasabah("", "", "", 0, 50, $status_anggota, $q)->result_array();

        $arrData = array();

        foreach ($data as $key => $value) {
            $value['id']   = $value['no_ang'];
            $value['text'] = "[" . $value['no_ang'] . " | " . $value['no_peg'] . "] " . $value['nm_ang'] . " | " . $value['nm_prsh'];

            $arrData['results'][] = $value;
        }

        echo json_encode($arrData);
    }

    public function select_anggota_dari_noang()
    {
        $data_req = get_request();

        $cari['value'] = $data_req['no_ang'];
        $cari['field'] = array("no_ang");

        $data = $this->anggota_model->get_anggota("", $cari, "", 0, 50)->result_array();

        echo json_encode($data);
    }

    public function select_anggota_by_nopeg($status_anggota = "")
    {
        $data_req = get_request();

        $value = isset($data_req['value']) ? $data_req['value'] : "";
        $q     = isset($data_req['q']) ? $data_req['q'] : $value;

        $cari['value'] = $q;
        $cari['field'] = array("no_ang", "no_peg", "nm_ang");

        $data = $this->anggota_model->get_anggota("", $cari, "", 0, 50, $status_anggota)->result_array();

        $arrData = array();

        foreach ($data as $key => $value) {
            $value['id']   = $value['no_peg'];
            $value['text'] = $value['no_ang'] . " | " . $value['no_peg'] . " | " . $value['nm_ang'] . " | " . $value['nm_prsh'];

            $arrData['results'][] = $value;
        }

        echo json_encode($arrData);
    }

    public function add_anggota_pindah()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_pindah'] = balik_tanggal($data_post['tgl_pindah']);

            if (!cek_tanggal_entri($data_post['tgl_pindah'])) {
                $hasil['status'] = false;
                $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            } else {
                $query = $this->anggota_model->insert_anggota_pindah($data_post);

                if ($query) {
                    $hasil['status'] = true;
                    $hasil['msg']    = "Data Berhasil Disimpan";
                } else {
                    $hasil['status'] = false;
                    $hasil['msg']    = "Data Gagal Disimpan";
                }
            }

            echo json_encode($hasil);
        }
    }

    public function get_anggota_pindah()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->anggota_model->get_anggota_pindah(1, $cari)->row(0)->numrows;
        $data_item    = $this->anggota_model->get_anggota_pindah(0, $cari, "", $offset, $limit);

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

    public function hapus_anggota_pindah()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            if (!cek_tanggal_entri($data_post['tgl_pindah'])) {
                $hasil['status'] = false;
                $hasil['msg']    = "Data bulan lalu tidak boleh dihapus";
            } else {
                $query = $this->anggota_model->hapus_anggota_pindah($data_post);

                if ($query) {
                    $hasil['status'] = true;
                    $hasil['msg']    = "Data Berhasil Dihapus";
                } else {
                    $hasil['status'] = false;
                    $hasil['msg']    = "Data Gagal Dihapus";
                }
            }

            echo json_encode($hasil);
        }
    }

    public function add_anggota_keluar()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_keluar'] = balik_tanggal($data_post['tgl_keluar']);

            if (!cek_tanggal_entri($data_post['tgl_keluar'])) {
                $hasil['status'] = false;
                $hasil['msg']    = "Tanggal tidak boleh bulan lalu";
            } else {
                $query = $this->anggota_model->insert_anggota_keluar($data_post);

                if ($query) {
                    $hasil['status'] = true;
                    $hasil['msg']    = "Data Berhasil Disimpan";
                } else {
                    $hasil['status'] = false;
                    $hasil['msg']    = "Data Gagal Disimpan";
                }
            }

            echo json_encode($hasil);
        }
    }

    public function get_anggota_keluar()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->anggota_model->get_anggota_keluar(1, $cari)->row(0)->numrows;
        $data_item    = $this->anggota_model->get_anggota_keluar(0, $cari, "", $offset, $limit);

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

    public function hapus_anggota_keluar()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            if (!cek_tanggal_entri($data_post['tgl_keluar'])) {
                $hasil['status'] = false;
                $hasil['msg']    = "Data bulan lalu tidak boleh dihapus";
            } else {
                $query = $this->anggota_model->hapus_anggota_keluar($data_post);

                if ($query) {
                    $hasil['status'] = true;
                    $hasil['msg']    = "Data Berhasil Dihapus";
                } else {
                    $hasil['status'] = false;
                    $hasil['msg']    = "Data Gagal Dihapus";
                }
            }

            echo json_encode($hasil);
        }
    }

    public function get_anggota($status_anggota = "")
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->anggota_model->get_anggota(1, $cari, "", "", "", $status_anggota)->row(0)->numrows;
        $data_item    = $this->anggota_model->get_anggota(0, $cari, "", $offset, $limit, $status_anggota);

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

    public function update_data_anggota($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_lhr'] = balik_tanggal($data_post['tgl_lhr']);

            $query = $this->anggota_model->update_data_anggota($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Disimpan";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Disimpan";
            }

            echo json_encode($hasil);
        }
    }

    public function get_nasabah()
    {
        $data = get_request();

        $cari['field'][0] = "no_ang";
        $cari['value']    = $data['search']['value'];
        $offset           = $data['start'];
        $limit            = $data['length'];

        $data_numrows = $this->anggota_model->get_nasabah(1, $cari)->row(0)->numrows;
        $data_item    = $this->anggota_model->get_nasabah(0, $cari, "", $offset, $limit);

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

    public function get_tabel_nasabah()
    {
        $data_post = get_request('post');

        if ($data_post) {

            $html = "<table class=\"table table-condensed table-striped form-inline\">
                <thead>
                    <tr>
                        <th style=\"width: 50px\">No.</th>
                        <th style=\"width: 50px\">Kode</th>
                        <th>Nama</th>
                    </tr>
                </thead>
                <tbody>";

            $xdata_nasabah = $this->anggota_model->get_nasabah(0, "", "", "", "", "", "", $data_post['no_ang'])->result_array();

            $data_nasabah = array();

            foreach ($xdata_nasabah as $key => $value) {
                $data_nasabah[$value['kd_ang']] = $value;
            }

            $abjad = "A";

            for ($i = 0; $i < 5; $i++) {
                $nasabah = array("kd_ang" => "", "nm_ang" => "");

                if (isset($data_nasabah[$abjad])) {
                    $nasabah = $data_nasabah[$abjad];
                }

                $html .= "
                    <tr>
                        <td>" . ($i + 1) . "</td>
                        <td>" . $data_post['no_ang'] . $abjad . "<input type=\"hidden\" name=\"kd_ang[]\" id=\"kd_ang\" value=\"" . $abjad . "\"></td>
                        <td><input type=\"text\" name=\"nm_nasabah[]\" id=\"nm_ang\" class=\"form-control\" style=\"text-transform: uppercase; width:100%\" value=\"" . $nasabah['nm_ang'] . "\" autocomplete=\"off\"></td>
                    </tr>";

                $abjad++;
            }

            $html .= "
                </tbody>
            </table>
            <hr>
            <div class=\"text-center\">
            <button type=\"button\" class=\"btn btn-primary\" onclick=\"simpan()\">Simpan</button>
            <button type=\"button\" class=\"btn btn-default\" onclick=\"get_tabel_nasabah()\">Batal</button>
            </div>";

            echo $html;
        }
    }

    public function add_edit_nasabah()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->anggota_model->insert_update_nasabah($data_post);

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

    public function add_nasabah()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->anggota_model->insert_nasabah($data_post);

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

    public function edit_nasabah($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->anggota_model->update_nasabah($data_post, $id);

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

    public function del_nasabah()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->anggota_model->delete_nasabah($data_post);

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

    public function get_ktp()
    {
        $data_post = get_request("post");

        if ($data_post) {
            $data_nasabah = $this->db->where("no_ang", $data_post['no_ang'])
                ->get("t_nasabah")->row_array(0);

            if ($data_nasabah['file_ktp']) {
                echo base_url('aset/nasabah/' . $data_nasabah['file_ktp']);
            } else {
                echo base_url('aset/gambar/no-image.png');
            }
        }
    }

    public function upload_ktp()
    {
        $data_post = get_request("post");

        if ($data_post and $_FILES) {
            $file_upload = $_FILES['file_ktp'];

            $nama_file  = $file_upload['name'];
            $pecah_nama = explode(".", $nama_file);
            $ext        = end($pecah_nama);

            $config_upload['upload_path']   = FCPATH . "aset/nasabah";
            $config_upload['allowed_types'] = "*";
            $config_upload['overwrite']     = true;
            $config_upload['file_name']     = strtoupper($data_post['no_ang']) . "." . $ext;

            $this->load->library("upload", $config_upload);

            $hasil_proses = false;

            if ($this->upload->do_upload("file_ktp")) {
                $set_data = array("file_ktp" => $this->upload->data('file_name'));

                $this->db->set($set_data)->where("no_ang", $data_post['no_ang'])->update("t_anggota");
                $hasil_proses = $this->db->set($set_data)->where("no_ang", $data_post['no_ang'])->update("t_nasabah");
            }

            if ($hasil_proses) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diupload";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diupload";
            }

            echo json_encode($hasil);
        }
    }

    public function hapus_ktp($no_ang)
    {
        $data_nasabah = $this->db->where("no_ang", $no_ang)
            ->get("t_nasabah")->row_array();

        @unlink(FCPATH . "aset/nasabah/" . $data_nasabah['file_ktp']);

        $hasil_proses = false;

        $this->db->set("file_ktp", null)->where("no_ang", $no_ang)->update("t_anggota");
        $hasil_proses = $this->db->set("file_ktp", null)->where("no_ang", $no_ang)->update("t_nasabah");

        if ($hasil_proses) {
            $hasil['status'] = true;
            $hasil['msg']    = "Data Berhasil Dihapus";
        } else {
            $hasil['status'] = false;
            $hasil['msg']    = "Data Gagal Dihapus";
        }

        echo json_encode($hasil);
    }

}
