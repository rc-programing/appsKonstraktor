<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_admin extends CI_Model
{
    public function getdata($table, $where = null)
    {
        if ($where != null)
            return  $this->db->get_where($table, $where);

        return $this->db->get($table);
    }

    public function getGroupByPengeluaran($group_by)
    {
        return $this->db->group_by($group_by)
            ->order_by('id_pengeluaran', 'ASC')
            ->get('tb_pengeluaran');
    }

    // Saldo

    public function getSaldo($status_saldo  = null)
    {
        if ($status_saldo != null)
            return $this->db->order_by('status_saldo', 'DESC')->get_where('tb_saldo', ['tgl' => TODAY, 'status_saldo' => $status_saldo]);

        return $this->db->order_by('status_saldo', 'DESC')->get_where('tb_saldo', ['tgl' => TODAY]);
    }

    public function getSaldoKemarin($status_saldo = false)
    {
        if ($status_saldo)
            return $this->db->order_by('status_saldo', 'DESC')->get_where('tb_saldo', ['tgl' => YESTERDAY]);

        return $this->db->order_by('status_saldo', 'DESC')->get_where('tb_saldo', ['tgl' => YESTERDAY, 'status_saldo' => 999]);
    }

    public function cekIdSaldo($id_saldo)
    {
        $check = $this->db->get_where('tb_saldo', ['id_saldo' => $id_saldo]);
        if ($check->num_rows() > 0) {
            $data = $check->result_array();
            return $data[0]['status_saldo'];
        }

        return 0;
    }

    public function getSaldoTanggal($tgl, $salso_akhir = false)
    {
        if ($salso_akhir)
            return $this->db->order_by('status_saldo', 'ASC')->get_where('tb_saldo', ['tgl' => $tgl, 'status_saldo' => 999]);

        return $this->db->order_by('status_saldo', 'ASC')
            ->get_where('tb_saldo', ['tgl' => $tgl]);
    }

    public function getPengeluaranTanggal($tgl)
    {
        return $this->db->get_where('tb_pengeluaran', ['tanggal' => $tgl]);
    }

    public function checkPassLama($pass)
    {
        return $this->db->get_where('tb_user', ['password' => $pass]);
    }

    public function saveData($table, $data)
    {
        return $this->db->insert($table, $data);
    }
    public function updateData($table, $data, $where)
    {
        $this->db->where($where);
        return $this->db->update($table, $data);
    }
    public function deleteData($table, $where)
    {
        return $this->db->where($where)->delete($table);
    }
}
