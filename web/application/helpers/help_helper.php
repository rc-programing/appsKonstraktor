<?php

function konversiTanggal($tgl)
{
    $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    $parts = explode("-", $tgl);
    return $parts[2] . " " . $bulan[(int) $parts[1]] . " " . $parts[0];
}

function konversiHari($hari)
{
    $days = [
        "Sun" => "Minggu",
        "Mon" => "Senin",
        "Tue" => "Selasa",
        "Wed" => "Rabu",
        "Thu" => "Kamis",
        "Fri" => "Jumat",
        "Sat" => "Sabtu",
    ];

    return $days[$hari];
}

function random_id($length)
{
    $data = 'ABCDEFGHIJKLMNOPQRSTU1234567890';
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $pos = rand(0, strlen($data) - 1);
        $string .= $data[$pos];
    }
    return $string;
}

function message($type, $info, $pesan)
{
    $html = '<div class="alert alert-' . $type . ' alert-dismissible fade show msg" role="alert">';
    $html .= '<strong>' . $info . ' !</strong>';
    $html .= ' ' . $pesan;
    $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    $html .= '<span aria-hidden="true">&times;</span>';
    $html .= '</button></div>';

    return $html;
}

function format_rupiah($angka)
{
    return "Rp. " . number_format((float)$angka, 0, ',', '.');
}
