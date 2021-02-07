<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_admin');
    }

    public function index()
    {
        $total_debit = 0;
        $total_kredit = 0;
        $sisa_saldo = 0;
        $total_pengeluaran = 0;
        $checkDebit = $this->model_admin->getSaldo()->result_array();
        $checkSisasaldo = $this->model_admin->getSaldo(999);
        $checkKredit = $this->model_admin->getPengeluaranTanggal(TODAY)->result_array();

        foreach ($checkDebit as $debit) {
            if ($debit['status_saldo'] != 999) {
                $total_debit += (int) $debit['saldo'];
            }
        }

        foreach ($checkKredit as $kredit) {
            $total_kredit += (int) $kredit['total'];
            $total_pengeluaran++;
        }

        if ($checkSisasaldo->num_rows() > 0) {
            $d = $checkSisasaldo->result_array();
            $sisa_saldo = $d[0]['saldo'];
        }

        $data = [
            'debit' => $total_debit,
            'kredit' => $total_kredit,
            'sisa_saldo' => $sisa_saldo,
            'pengeluaran' => $total_pengeluaran,
        ];

        $this->load->view('template/header');
        $this->load->view('template/topbar');
        $this->load->view('template/sidebar');
        $this->load->view('bendahara/v_dashboard', $data);
        $this->load->view('template/footer');
    }

    public function data_saldo()
    {
        $status_saldo_pindahan = 1;
        $saldo_harini = $this->model_admin->getSaldo()->result_array();
        $status_saldo = $this->model_admin->getSaldo(1)->num_rows();
        $saldo_pindahan = $this->model_admin->getSaldoKemarin()->num_rows();

        if ($saldo_pindahan == 0) {
            if ($status_saldo == 0) {
                $status_saldo_pindahan = 0;
            }
        }

        $data['data_saldo'] =  $saldo_harini;
        $data['status_saldo'] = $status_saldo;
        $data['status_saldo_pindahan'] = $status_saldo_pindahan;
        $this->load->view('template/header');
        $this->load->view('template/topbar');
        $this->load->view('template/sidebar');
        $this->load->view('bendahara/v_data_saldo', $data);
        $this->load->view('template/footer');
    }

    public function data_pengeluaran()
    {
        $kredit = 0;
        $checkData = $this->model_admin->getdata('tb_pengeluaran', ['tanggal' => TODAY]);
        if ($checkData->num_rows() > 0) {
            $d = $checkData->result_array();
            foreach ($d as $dt) {
                $kredit += (int) $dt['total'];
            }
        }

        $data['data_keluar'] = $this->model_admin->getdata('tb_pengeluaran', ['tanggal' => TODAY])->result_array();
        $data['kredit'] = $kredit;
        $this->load->view('template/header');
        $this->load->view('template/topbar');
        $this->load->view('template/sidebar');
        $this->load->view('bendahara/v_data_pengeluaran', $data);
        $this->load->view('template/footer');
    }

    public function searching($whereNama = 'null', $whereAlokasi = '')
    {
        $where = [];
        $whereNama = str_replace('-', ' ', $whereNama);
        $whereAlokasi = str_replace('-', ' ', $whereAlokasi);

        if ($whereNama != 'null' && !empty($whereAlokasi)) {
            $where = [
                'nm_lengkap' => $whereNama,
                'alokasi' => $whereAlokasi,
            ];
        }

        if ($whereNama != 'null' && empty($whereAlokasi)) {
            $where = [
                'nm_lengkap' => $whereNama,
            ];
        }

        if ($whereNama == 'null' && !empty($whereAlokasi)) {
            $where = [
                'alokasi' => $whereAlokasi,
                'status' => 1,
            ];
        }

        $result = ($whereNama == "null" && empty($whereAlokasi)) ? [] : $this->model_admin->getdata('tb_pengeluaran', $where)->result_array();
        $kredit = 0;
        foreach ($result as $r) {
            $kredit += (int) $r['total'];
        }

        $data = [
            "data_nama" => $this->model_admin->getGroupByPengeluaran('nm_lengkap')->result_array(),
            "data_alokasi" => $this->model_admin->getGroupByPengeluaran('alokasi')->result_array(),
            "result" => $result,
            "kredit" => $kredit,
            "nama" => $whereNama,
            "alokasi" => $whereAlokasi,
        ];

        $this->load->view('template/header');
        $this->load->view('template/topbar');
        $this->load->view('template/sidebar');
        $this->load->view('bendahara/v_cari_data', $data);
        $this->load->view('template/footer');
    }

    public function cetak_harian($search = '')
    {
        $saldo = 0;
        $kredit = 0;
        $date = TODAY;
        $total_debit = 0;
        $dataKel = [];
        $checkSaldo = $this->model_admin->getdata('tb_saldo', ['tgl' => ((empty($search)) ? TODAY : $search)]);
        $checkData = $this->model_admin->getdata('tb_pengeluaran', ['tanggal' => ((empty($search)) ? TODAY : $search)]);

        if ($checkSaldo->num_rows() > 0) {
            $row = $checkSaldo->result_array();
            foreach ($row as $d) {
                if ($d['status_saldo'] != 999) {
                    $total_debit += (int) $d['saldo'];
                }
            }
        }

        if ($checkData->num_rows() > 0) {
            $d = $checkData->result_array();
            $dataKel = $d;
            foreach ($d as $dt) {
                $kredit += (int) $dt['total'];
            }
        }

        $data = [
            "date" => (empty($search)) ? $date : $search,
            "saldo" => ($total_debit - $kredit),
            "kredit" => $kredit,
            "total_debit" => ($total_debit + $saldo),
            "data_keluar" => $dataKel,
        ];

        $this->load->view('template/header');
        $this->load->view('template/topbar');
        $this->load->view('template/sidebar');
        $this->load->view('bendahara/v_cetak_harian', $data);
        $this->load->view('template/footer');
    }

    public function backup()
    {
        $this->load->view('template/header');
        $this->load->view('template/topbar');
        $this->load->view('template/sidebar');
        $this->load->view('bendahara/v_backup_db');
        $this->load->view('template/footer');
    }
}
