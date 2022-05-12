<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ledger extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();

        $this->load->model("ledger_model");
    }

    public function index($page)
    {
        $array_bulan = array_bulan();

        $option_bulan   = get_option_tag($array_bulan, "BULAN");
        $option_kasbank = get_option_tag($this->get_option_kasbank());

        if ($page == "entri-transaksi-ledger") {
            $data['judul_menu'] = "Entri Transaksi General Ledger";
            $data['bulan']      = $option_bulan;
            $data['kasbank']    = $option_kasbank;

            $this->template->view("ledger/entri_transaksi_ledger", $data);
        }
    }

    private function get_option_kasbank()
    {
        $this->load->model("master_model");

        $data = $this->master_model->get_kasbank(0);

        $hasilArray = array();

        foreach ($data->result_array() as $key => $value) {
            $hasilArray[$value['kd_kasbank']] = $value['nm_kasbank'];
        }

        return $hasilArray;
    }

    public function get_header_ledger()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];
        $tahun         = isset($data['tahun']) ? $data['tahun'] : "";
        $bulan         = isset($data['bulan']) ? $data['bulan'] : "";
        $no_ref_bukti  = isset($data['no_ref_bukti']) ? $data['no_ref_bukti'] : "";

        @$data_numrows = $this->ledger_model->get_ledger_grup_by_no_ref(1, $cari, "", "", "", $no_ref_bukti, $tahun, $bulan)->row(0)->numrows;

        if (!$data_numrows) {
            $data_numrows = 0;
        }

        $data_item = $this->ledger_model->get_ledger_grup_by_no_ref(0, $cari, "", $offset, $limit, $no_ref_bukti, $tahun, $bulan);

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_item->result_array();

        echo json_encode($array);
    }

    public function get_detail_ledger()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];
        $no_ref_bukti  = isset($data['no_ref_bukti']) ? $data['no_ref_bukti'] : "";

        $data_numrows = $this->ledger_model->get_detail_ledger_by_no_ref(1, $cari, "", "", "", $no_ref_bukti)->row(0)->numrows;
        $data_item    = $this->ledger_model->get_detail_ledger_by_no_ref(0, $cari, "", $offset, $limit, $no_ref_bukti);

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_item->result_array();

        echo json_encode($array);
    }

    public function get_ledger_by_no_ref()
    {
        $data_post   = get_request('post');
        $data_ledger = array();

        if ($data_post) {
            $data_ledger = $this->ledger_model->get_ledger_grup_by_no_ref(0, "", "", "", "", $data_post['no_ref_bukti']);

            if ($data_ledger->num_rows() > 0) {
                $data_ledger = $data_ledger->row_array(0);
            } else {
                $data_ledger = array();
            }
        }

        echo json_encode($data_ledger);
    }

    public function add_ledger()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_post['tgl_gl'] = balik_tanggal($data_post['tgl_gl']);

            $query = $this->ledger_model->insert_ledger($data_post);

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

    public function edit_ledger($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->ledger_model->update_ledger($data_post, $id);

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

    public function del_detail_ledger()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->ledger_model->delete_detail_ledger($data_post);

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

    public function del_header_ledger()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->ledger_model->delete_header_ledger($data_post);

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
