<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_auth extends CI_Model
{
    public function verifikasi_akun($username, $password)
    {
        return $this->db->get_where('tb_user', [
            'username' => $username,
            'password' => $password,
        ]);
    }
}
