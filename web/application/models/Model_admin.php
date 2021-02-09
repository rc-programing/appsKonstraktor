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
        $today = date('Y-m-d');
        if ($status_saldo != null) {
            return $this->db->order_by('status_saldo', 'DESC')->get_where('tb_saldo', ['tgl' => $today, 'status_saldo' => $status_saldo]);
        }

        return $this->db->order_by('status_saldo', 'DESC')->get_where('tb_saldo', ['tgl' => $today]);
    }

    public function getSaldoKemarin($status_saldo = false)
    {
        $kemarin = date('Y-m-d', strtotime("-1 day", strtotime(date("Y-m-d"))));
        if ($status_saldo) {
            return $this->db->order_by('status_saldo', 'DESC')
                ->get_where('tb_saldo', ['tgl' =>  $kemarin]);
        }

        return $this->db->order_by('status_saldo', 'DESC')
            ->get_where('tb_saldo', ['tgl' =>  $kemarin, 'status_saldo' => 999]);
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

    public function getSaldoTanggal($tgl, $saldo_akhir = false)
    {
        if ($saldo_akhir) {
            return $this->db->order_by('status_saldo', 'ASC')
                ->get_where('tb_saldo', ['tgl' => $tgl, 'status_saldo' => 999]);
        }

        return $this->db->order_by('status_saldo', 'ASC')
            ->get_where('tb_saldo', ['tgl' => $tgl]);
    }

    public function getSUMSaldo($tgl)
    {
        return $this->db
            ->select_sum('saldo')
            ->from('tb_saldo')
            ->where('status_saldo !=', 999)
            ->where('tgl', $tgl)
            ->order_by('saldo desc')
            ->get();
    }

    public function getPengeluaranTanggal($tgl)
    {
        return $this->db->get_where('tb_pengeluaran', ['tanggal' => $tgl]);
    }

    public function getSUMPengeluaran($tgl)
    {
        return $this->db
            ->select_sum('total')
            ->from('tb_pengeluaran')
            ->where('tanggal', $tgl)
            ->order_by('total desc')
            ->get();
    }

    public function getWhereSUMPengeluaran($where)
    {
        return $this->db
            ->select_sum('total')
            ->from('tb_pengeluaran')
            ->where($where)
            ->order_by('total desc')
            ->get();
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
