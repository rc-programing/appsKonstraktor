<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_auth');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->load->view('v_login');
    }

    public function login()
    {
        $this->form_validation->set_rules("username", "username", "trim|required", ["required" => "Kolom {field} tidak boleh kosong"]);
        $this->form_validation->set_rules("password", "password", "trim|required", ["required" => "Kolom {field} tidak boleh kosong"]);
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $response = [];
        if ($this->form_validation->run()) {
            $check = $this->model_auth->verifikasi_akun($username, $password);
            if ($check->num_rows() > 0) {
                $data_login = $check->result_array()[0];
                $this->session->set_userdata([
                    'nama_lengkap' => $data_login['nm_lengkap'],
                    'username' => $data_login['username'],
                    'level' => $data_login['level'],
                    'id' => $data_login['id_user'],
                    'islogin' => true,
                ]);

                $response = [
                    'msg' => 'Login berhasil',
                    'status' => true,
                ];
            } else {
                $response = [
                    'msg' => 'Data login tidak ditemukan, periksa kembali username atau password anda',
                    'status' => false,
                ];
            }
        } else {
            $msg = '';
            foreach ($_POST as $key => $value) {
                $msg .= ' ' . form_error($key);
            }

            $response = [
                'msg' => 'Gagal login ' . $msg,
                'status' => false,
            ];
        }

        echo json_encode($response);
    }

    public function logout()
    {
        $this->session->unset_userdata('nama_lengkap');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('level');
        $this->session->unset_userdata('id');
        $this->session->unset_userdata('islogin');
        redirect(site_url('auth'));
    }
}
