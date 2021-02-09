<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once FCPATH . 'PHPExcel/PHPExcel.php';

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('islogin') != true) {
            redirect(site_url('auth'));
        }

        $this->load->model('model_admin');
        $this->load->helper('file');
        $this->load->helper('download');
        $this->load->library('zip');
        $this->load->library('form_validation');
    }

    # saldo ===>
    private function updateSisaSaldo()
    {
        $today = date('Y-m-d');
        $totalKredit = 0;
        $totalSaldo = 0;
        $checkPengeluaran = $this->model_admin->getPengeluaranTanggal($today);
        $checkSaldo = $this->model_admin->getSaldoTanggal($today);

        if ($checkPengeluaran->num_rows() > 0) {
            $kredit = $this->model_admin->getSUMPengeluaran($today)->result_array();
            $totalKredit = $kredit[0]['total'];
        }

        if ($checkSaldo->num_rows() > 0) {
            $saldo = $this->model_admin->getSUMSaldo($today)->result_array();
            $totalSaldo = $saldo[0]['saldo'];
        }

        $data_update = ['saldo' => (intval($totalSaldo) - intval($totalKredit))];
        $where = ['tgl' => $today, 'status_saldo' => 999];
        return $this->model_admin->updateData('tb_saldo', $data_update, $where);
    }

    public function getSaldo($where = null)
    {
        $data = [];
        if ($where == null) $data = $this->model_admin->getdata('tb_saldo')->result_array();
        else $data = $this->model_admin->getdata('tb_saldo', ["id_saldo" => $where])->result_array();
        echo json_encode($data);
    }

    public function getStatuSaldo()
    {
        echo json_encode($this->model_admin->getSaldo(2)->num_rows());
    }

    public function tambahSaldo()
    {
        $saldo = $this->input->post('saldo');
        $saldo_pindahan = $this->input->post('saldo_pindahan');
        $ket = $this->input->post('keterangan');
        $checkbox = $this->input->post('statusSaldo');
        $status_saldo = $this->model_admin->getSaldo(2)->num_rows();
        $date = date('Y-m-d');
        $data_insert = [];
        $insert = false;

        if ($saldo_pindahan != null) {

            $saldo_ac = str_replace(["Rp", " ", "."], '', $saldo);
            $saldo_pd = str_replace(["Rp", " ", "."], '', $saldo_pindahan);

            $insert = $this->model_admin->saveData('tb_saldo', [
                'tgl' => $date,
                'saldo' => $saldo_ac,
                'status_saldo' => 2,
                'ket' => 'Saldo Ac',
            ]);

            if ($insert) {
                $insert = $this->model_admin->saveData('tb_saldo', [
                    'tgl' => $date,
                    'saldo' => $saldo_pd,
                    'status_saldo' => 1,
                    'ket' => 'Saldo Pindahan',
                ]);

                if ($insert) {
                    $insert = $this->model_admin->saveData('tb_saldo', [
                        'tgl' => $date,
                        'saldo' => ((int) $saldo_ac + (int) $saldo_pd),
                        'status_saldo' => 999,
                        'ket' => 'Sisa Saldo',
                    ]);
                }
            }
        } else {
            if ($status_saldo > 0) {
                $data_insert = [
                    'tgl' => $date,
                    'saldo' => str_replace(["Rp", " ", "."], '', $saldo),
                    'status_saldo' => $checkbox,
                    'ket' => (($ket == null) ? 'Saldo Tambahan' : ucwords($ket)),
                ];

                $insert = $this->model_admin->saveData('tb_saldo', $data_insert);
                if ($insert) {
                    $insert = $this->updateSisaSaldo();
                }
            } else {
                $data_insert = [
                    'tgl' => $date,
                    'saldo' => str_replace(["Rp", " ", "."], '', $saldo),
                    'status_saldo' => 2,
                    'ket' => 'Saldo Ac',
                ];

                $insert = $this->model_admin->saveData('tb_saldo', $data_insert);
                if ($insert) {
                    $checkSaldo = $this->model_admin->getSaldoKemarin();
                    if ($checkSaldo->num_rows() > 0) {
                        $d = $checkSaldo->result_array()[0];
                        $insert = $this->model_admin->saveData('tb_saldo', [
                            'tgl' => $date,
                            'saldo' => $d['saldo'],
                            'status_saldo' => 1,
                            'ket' => 'Saldo Pindahan',
                        ]);

                        if ($insert) {
                            $insert = $this->model_admin->saveData('tb_saldo', [
                                'tgl' => $date,
                                'saldo' => ((int) str_replace(["Rp", " ", "."], '', $saldo) + (int) $d['saldo']),
                                'status_saldo' => 999,
                                'ket' => 'Sisa Saldo',
                            ]);

                            if ($insert) {
                                $insert = $this->updateSisaSaldo();
                            }
                        }
                    }
                }
            }
        }

        $response = [
            'msg' => 'Data saldo ' . (($insert) ? 'berhasil' : 'gagal') . ' tersimpan',
            'status' => $insert,
        ];

        echo json_encode($response);
    }

    public function hapusSaldo()
    {
        $id_saldo = $this->input->post('id_pengeluaran');
        $delete = $this->model_admin->deleteData('tb_saldo', [
            'id_saldo' => $id_saldo,
        ]);

        if ($delete) {
            $delete = $this->updateSisaSaldo();
        }

        $response = [
            'msg' => 'Data saldo ' . (($delete) ? 'berhasil' : 'gagal') . ' dihapus',
            'status' => $delete,
        ];

        echo json_encode($response);
    }

    public function ubahSaldo($id_saldo)
    {
        $total = $this->input->post('saldo');
        $ket = $this->input->post('keterangan');

        $data = [];
        $status_saldo = $this->model_admin->cekIdSaldo($id_saldo);
        if ($status_saldo == 2) {
            $data = [
                'saldo' => str_replace(["Rp", " ", "."], '', $total),
            ];
        } else {
            $data = [
                'saldo' => str_replace(["Rp", " ", "."], '', $total),
                'ket' => ($ket != null) ? ucwords($ket) : 'Saldo Tambahan',
                'status_saldo' => ($ket != null) ? 4 : 3,
            ];
        }

        $update = $this->model_admin->updateData('tb_saldo', $data, [
            'id_saldo'    => $id_saldo,
        ]);

        if ($update) {
            $update = $this->updateSisaSaldo();
        }

        $response = [
            'msg' => 'Data saldo ' . (($update) ? 'berhasil' : 'gagal') . ' diubah',
            'status' => $update,
        ];

        echo json_encode($response);
    }

    # akun ===>
    public function ubahAkun()
    {
        $this->form_validation->set_rules("nm_lengkap", "nama lengkap", "trim|required", ["required" => "Kolom {field} tidak boleh kosong"]);
        $this->form_validation->set_rules("username", "username", "trim|required", ["required" => "Kolom {field} tidak boleh kosong"]);
        $this->form_validation->set_rules("passbaru", "password baru", "trim|required", ["required" => "Kolom {field} tidak boleh kosong"]);

        $nm_lengkap = $this->input->post('nm_lengkap');
        $username = $this->input->post('username');
        $session = $this->session->userdata('id');
        $password = $this->input->post('passbaru');
        $passwordlama = $this->input->post('passlama');

        if ($this->form_validation->run()) {
            $checkPassLama = $this->model_admin->checkPassLama($passwordlama)->num_rows();
            if ($checkPassLama > 0) {
                $update = $this->model_admin->updateData('tb_user', [
                    'nm_lengkap' => $nm_lengkap,
                    'username' => $username,
                    'password' => $password,
                    'level' => 0,
                ], [
                    'id_user'    => $session,
                ]);

                $response = [
                    'msg' => 'Data akun ' . (($update) ? 'berhasil' : 'gagal') . ' diubah',
                    'status' => $update,
                ];
            } else {
                $response = [
                    'msg' => 'Data akun gagal diubah, password lama anda salah',
                    'status' => false,
                ];
            }
        } else {
            $msg = '';
            foreach ($_POST as $key => $value) {
                $msg .= ' ' . form_error($key);
            }

            $response = [
                'msg' => 'Data saldo gagal diubah ' . $msg,
                'status' => false,
            ];
        }

        echo json_encode($response);
    }

    # pengeluaran
    public function getPengeluaran($where = null)
    {
        $data = [];
        if ($where == null) $data = $this->model_admin->getdata('tb_pengeluaran')->result_array();
        else $data = $this->model_admin->getdata('tb_pengeluaran', ["id_pengeluaran" => $where])->result_array();
        echo json_encode($data);
    }

    public function tambahPengeluaran()
    {
        $this->form_validation->set_rules("total", "total", "trim|required", ["required" => "Kolom {field} tidak boleh kosong"]);

        $nm_lengkap = ucwords($this->input->post('nm_lengkap'));
        $jenis = ucwords($this->input->post('jenis'));
        $alokasi = ucwords($this->input->post('alokasi'));
        $total = $this->input->post('total');
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $status = $this->input->post('status');

        if ($this->form_validation->run()) {
            $insert = $this->model_admin->saveData('tb_pengeluaran', [
                'nm_lengkap' => $nm_lengkap,
                'jenis' => $jenis,
                'alokasi' => $alokasi,
                'total' => str_replace(["Rp", " ", "."], '', $total),
                'tanggal' => $date,
                'time_created' => $time,
                'status' => $status,
            ]);

            if ($insert) {
                $insert = $this->updateSisaSaldo();
            }

            $response = [
                'msg' => 'Data pengeluaran ' . (($insert) ? 'berhasil' : 'gagal') . ' tersimpan',
                'status' => $insert,
            ];
        } else {
            $msg = '';
            foreach ($_POST as $key => $value) {
                $msg .= ' ' . form_error($key);
            }

            $response = [
                'msg' => 'Data saldo gagal diubah ' . $msg,
                'status' => false,
            ];
        }

        echo json_encode($response);
    }

    public function ubahPengeluaran($id_pengeluaran)
    {
        $this->form_validation->set_rules("total", "total", "trim|required", ["required" => "Kolom {field} tidak boleh kosong"]);

        $nm_lengkap = ucwords($this->input->post('nm_lengkap'));
        $jenis = ucwords($this->input->post('jenis'));
        $alokasi = ucwords($this->input->post('alokasi'));
        $total = $this->input->post('total');
        $time = date('h:m:s');
        $status = $this->input->post('status');

        if ($this->form_validation->run()) {
            $update = $this->model_admin->updateData('tb_pengeluaran', [
                'nm_lengkap' => ucwords($nm_lengkap),
                'jenis' => ucwords($jenis),
                'alokasi' => ucwords($alokasi),
                'total' => str_replace(["Rp", " ", "."], '', $total),
                'time_created' => $time,
                'status' => $status,
            ], [
                'id_pengeluaran' => $id_pengeluaran,
            ]);

            if ($update) {
                $update = $this->updateSisaSaldo();
            }

            $response = [
                'msg' => 'Data pengeluaran ' . (($update) ? 'berhasil' : 'gagal') . ' diubah',
                'status' => $update,
            ];
        } else {
            $msg = '';
            foreach ($_POST as $key => $value) {
                $msg .= ' ' . form_error($key);
            }

            $response = [
                'msg' => 'Data saldo gagal diubah ' . $msg,
                'status' => false,
            ];
        }

        echo json_encode($response);
    }

    public function hapusPengeluaran()
    {
        $id_pengeluaran = $this->input->post('id_pengeluaran');
        $delete = $this->model_admin->deleteData('tb_pengeluaran', [
            'id_pengeluaran' => $id_pengeluaran,
        ]);

        if ($delete) {
            $delete = $this->updateSisaSaldo();
        }

        $response = [
            'msg' => 'Data pengeluaran ' . (($delete) ? 'berhasil' : 'gagal') . ' dihapus',
            'status' => $delete,
        ];

        echo json_encode($response);
    }

    public function getEksport()
    {
        $fileName = 'databases_' . date('Ymd-His') . '.zip';
        $url = base_url('download/' . $fileName);
        $this->zip->read_file(APPPATH . 'databases.db');
        $this->zip->archive(FCPATH . 'download/' . $fileName);

        echo json_encode(['url' => $url, 'filename' => $fileName]);
    }

    public function getDeleteZipDB($filename)
    {
        $path = FCPATH . 'download/' . $filename;
        if (file_exists($path)) {
            // todo acction
            unlink($path);
            echo json_encode('eksport database successfully');
        }
    }

    public function getImport()
    {
        $response = [];
        if (isset($_FILES['file'])) {
            $file_name = $_FILES['file']['name'];
            $file_temp = $_FILES['file']['tmp_name'];

            $exp = explode(".", $file_name);
            $ext = end($exp);
            $file =  'databases.' . $ext;
            $location = APPPATH . $file;
            if ($ext != 'db') {
                $response = [
                    'msg' => 'File database tidak berekstensi *.db',
                    'status' => false,
                ];
            } else {
                if (file_exists($location))
                    unlink($location);

                if (move_uploaded_file($file_temp, $location)) {
                    $response = [
                        'msg' => 'File database berhasil diupload',
                        'status' => false,
                    ];
                } else {
                    $response = [
                        'msg' => 'File database Gagal diupload',
                        'status' => true,
                    ];
                }
            }
        } else {
            $response = [
                'msg' => 'File Gambar tidak ditemukan system',
                'status' => false,
            ];
        }

        echo json_encode($response);
    }

    public function do_cetak_harian($tgl)
    {
        $total_debit = 0;
        $total_kredit = 0;
        $check_pengeluaran = $this->model_admin->getPengeluaranTanggal($tgl)->result_array();
        $check_saldo = $this->model_admin->getSaldoTanggal($tgl)->result_array();
        $sisa_saldo = $this->model_admin->getSaldoTanggal($tgl, true)->result_array();

        if (count($check_saldo) > 0) {
            $row = $this->model_admin->getSUMSaldo($tgl)->result_array();
            $total_debit = $row[0]['saldo'];
        }

        if (count($check_pengeluaran) > 0) {
            $d = $this->model_admin->getSUMPengeluaran($tgl)->result_array();
            $total_kredit = $d[0]['total'];
        }

        $excel = new PHPExcel();
        // Settingan awal file excel

        $excel->getProperties()->setCreator('SIPDAK')
            ->setLastModifiedBy('RC')
            ->setTitle("Data Pengeluaran")
            ->setSubject("Pengeluaran")
            ->setDescription("Laporan Harian")
            ->setKeywords("Data pengeluaran");

        $excel->getDefaultStyle()
            ->applyFromArray([
                'font' => [
                    'name' => 'Calibri',
                ],
            ]);

        $excel->setActiveSheetIndex(0)->setCellValue('A1', konversiHari(date("D", strtotime($tgl))) . ", " . konversiTanggal($tgl)); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai F1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "KETERANGAN"); // Set kolom B3 dengan tulisan "NIS"
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "DEBIT"); // Set kolom C3 dengan tulisan "NAMA"
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "KREDIT"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "SALDO"); // Set kolom E3 dengan tulisan "TELEPON"

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($this->setStyleExcel()['style_col']);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($this->setStyleExcel()['style_col']);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($this->setStyleExcel()['style_col']);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($this->setStyleExcel()['style_col']);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($this->setStyleExcel()['style_col']);

        // Space 
        $this->setSpaceExcel($excel, 4);

        $rowsaldo = 5;
        foreach ($check_saldo as $val) {
            if ($val['status_saldo'] != 999) {
                $excel->setActiveSheetIndex(0)->setCellValue('A' . $rowsaldo, (($val['status_saldo'] == 1 || ($val['status_saldo'] == 2) ? '#' : '')));
                $excel->setActiveSheetIndex(0)->setCellValue('B' . $rowsaldo, (($val['status_saldo'] == 3) ? '' : $val['ket']));
                $excel->setActiveSheetIndex(0)->setCellValue('C' . $rowsaldo, $val['saldo']);
                $excel->setActiveSheetIndex(0)->setCellValue('D' . $rowsaldo, "");
                $excel->setActiveSheetIndex(0)->setCellValue('E' . $rowsaldo, "");

                $excel->getActiveSheet()->getStyle('A' . $rowsaldo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

                // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
                $excel->getActiveSheet()->getStyle('A' . $rowsaldo)->applyFromArray($this->setStyleExcel()['style_start']);
                $excel->getActiveSheet()->getStyle('B' . $rowsaldo)->applyFromArray($this->setStyleExcel()['style_row']);
                $excel->getActiveSheet()->getStyle('C' . $rowsaldo)->applyFromArray($this->setStyleExcel()['style_row']);
                $excel->getActiveSheet()->getStyle('D' . $rowsaldo)->applyFromArray($this->setStyleExcel()['style_row']);
                $excel->getActiveSheet()->getStyle('E' . $rowsaldo)->applyFromArray($this->setStyleExcel()['style_end']);

                $excel->getActiveSheet()->getStyle('C' . $rowsaldo)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_ACCOUNTING_RUPIAH);
                $excel->getActiveSheet()->getRowDimension($rowsaldo)->setRowHeight(20);

                $rowsaldo++;
            }
        }

        // Space 
        $this->setSpaceExcel($excel, $rowsaldo);

        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = $rowsaldo + 1; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($check_pengeluaran as $d) {
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no . ".");
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $d['jenis'] . ((empty($d['nm_lengkap'])) ? ' ' : ' ' . $d['nm_lengkap'] . ' ') . $d['alokasi']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, '');
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $d['total']);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, '');

            $excel->getActiveSheet()->getStyle('A' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($this->setStyleExcel()['style_start']);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($this->setStyleExcel()['style_row']);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($this->setStyleExcel()['style_row']);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($this->setStyleExcel()['style_row']);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($this->setStyleExcel()['style_end']);

            $excel->getActiveSheet()->getStyle('D' . $numrow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_ACCOUNTING_RUPIAH);
            $excel->getActiveSheet()->getRowDimension($numrow)->setRowHeight(20);

            $no++;
            $numrow++;
        }

        // Space 
        $this->setSpaceExcel($excel, $numrow);
        $numrow += 1;

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($this->setStyleExcel()['style_col']);
        $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($this->setStyleExcel()['style_col']);
        $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($this->setStyleExcel()['style_col']);
        $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($this->setStyleExcel()['style_col']);
        $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($this->setStyleExcel()['style_col']);

        // set Jumlah
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, "#");
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, "JUMLAH");
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $total_debit);
        $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $total_kredit);
        $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $sisa_saldo[0]['saldo']);

        $excel->getActiveSheet()->getStyle('C' . $numrow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_ACCOUNTING_RUPIAH);
        $excel->getActiveSheet()->getStyle('D' . $numrow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_ACCOUNTING_RUPIAH);
        $excel->getActiveSheet()->getStyle('E' . $numrow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_ACCOUNTING_RUPIAH);

        $excel->setActiveSheetIndex(0)->setCellValue('D' . ($numrow + 6), "Palu, " . konversiTanggal($tgl));
        $excel->setActiveSheetIndex(0)->setCellValue('D' . ($numrow + 7), "Di Setujui:");
        $excel->setActiveSheetIndex(0)->setCellValue('D' . ($numrow + 12), "Yasin Malewa");
        $excel->setActiveSheetIndex(0)->setCellValue('D' . ($numrow + 13), "Direktur");

        $excel->getActiveSheet()->getStyle('D' . ($numrow + 7))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('D' . ($numrow + 12))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('D' . ($numrow + 13))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('D' . ($numrow + 12))->getFont()->setUnderline(true);
        $excel->getActiveSheet()->getStyle('D' . ($numrow + 12))->getFont()->setBold(true);

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(4.57);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(46.71);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(21.86);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(19.57);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20.86);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Laporan Pengeluaran Harian");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan-pegeluaran-harian-' . str_replace('-', '', $tgl) . date('His') . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    public function do_cetak_cariData($nama = 'null', $alokasi = '')
    {
        $where = [];
        $nama = str_replace('-', ' ', $nama);
        $alokasi = str_replace('-', ' ', $alokasi);

        if ($nama != 'null' && !empty($alokasi)) {
            $where = [
                'nm_lengkap' => $nama,
                'alokasi' => $alokasi,
            ];
        }

        if ($nama != 'null' && empty($alokasi)) {
            $where = [
                'nm_lengkap' => $nama,
            ];
        }

        if ($nama == 'null' && !empty($alokasi)) {
            $where = [
                'alokasi' => $alokasi,
                'status' => 1,
            ];
        }

        $result = ($nama == "null" && empty($alokasi)) ? [] : $this->model_admin->getdata('tb_pengeluaran', $where)->result_array();
        $total_kredit = 0;
        if (count($result) > 0) {
            $kredit = $this->model_admin->getWhereSUMPengeluaran($where)->result_array();
            $total_kredit = $kredit[0]['total'];
        }

        $excel = new PHPExcel();
        // Settingan awal file excel

        $excel->getProperties()->setCreator('SIPDAK')
            ->setLastModifiedBy('RC')
            ->setTitle("Data Pertanggal")
            ->setSubject("Pengeluaran")
            ->setDescription("Laporan Pertanggal")
            ->setKeywords("Data pertanggal");

        $excel->getDefaultStyle()
            ->applyFromArray([
                'font' => [
                    'name' => 'Calibri',
                ],
            ]);

        // E26B0A
        $excel->setActiveSheetIndex(0)->setCellValue('A1', (($nama == 'null') ? '' : 'NAMA : ' . $nama . ' ') . ((empty($alokasi) ? '' : ' ALOKASI DANA :' . $alokasi))); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai F1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "TANGGAL"); // Set kolom B3 dengan tulisan "NIS"
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "URAIAN"); // Set kolom C3 dengan tulisan "NAMA"
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "DEBIT"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "KREDIT"); // Set kolom E3 dengan tulisan "TELEPON"

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($this->setStyleExcel(true)['style_col']);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($this->setStyleExcel(true)['style_col']);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($this->setStyleExcel(true)['style_col']);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($this->setStyleExcel(true)['style_col']);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($this->setStyleExcel(true)['style_col']);

        // Space 
        $this->setSpaceExcel($excel, 4);

        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 5; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($result as $d) {
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no . ".");
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $d['tanggal']);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $d['jenis'] . ((empty($d['nm_lengkap'])) ? ' ' : ' ' . $d['nm_lengkap'] . ' ') . $d['alokasi']);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, '');
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $d['total']);

            $excel->getActiveSheet()->getStyle('A' . $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($this->setStyleExcel()['style_start']);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($this->setStyleExcel()['style_row']);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($this->setStyleExcel()['style_row']);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($this->setStyleExcel()['style_row']);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($this->setStyleExcel()['style_end']);

            $excel->getActiveSheet()->getStyle('E' . $numrow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_ACCOUNTING_RUPIAH);
            $excel->getActiveSheet()->getRowDimension($numrow)->setRowHeight(20);

            $no++;
            $numrow++;
        }

        // Space 
        $this->setSpaceExcel($excel, $numrow);
        $numrow += 1;

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($this->setStyleExcel(true)['style_col']);
        $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($this->setStyleExcel(true)['style_col']);
        $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($this->setStyleExcel(true)['style_col']);
        $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($this->setStyleExcel(true)['style_col']);
        $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($this->setStyleExcel(true)['style_col']);

        // set Jumlah
        $excel->getActiveSheet()->mergeCells('B' . $numrow . ':C' . $numrow);
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, "#");
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, "JUMLAH");
        $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, 0);
        $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $total_kredit);

        $excel->getActiveSheet()->getStyle('D' . $numrow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_ACCOUNTING_RUPIAH);
        $excel->getActiveSheet()->getStyle('E' . $numrow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_ACCOUNTING_RUPIAH);

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(4.57);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(21.86);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(46.71);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(19.57);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20.86);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Laporan Pengeluaran Pertanggal");
        $excel->setActiveSheetIndex(0);
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan-pegeluaran-' . date('His') . '.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    public function setStyleExcel($cari_data = false)
    {
        return [
            'style_col' => [
                'font' => array('bold' => true), // Set font nya jadi bold
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
                ),
                'borders' => array(
                    'top' => array('style'  => PHPExcel_Style_Border::BORDER_MEDIUM), // Set border top dengan garis tipis
                    'right' => array('style'  => PHPExcel_Style_Border::BORDER_MEDIUM),  // Set border right dengan garis tipis
                    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_MEDIUM), // Set border bottom dengan garis tipis
                    'left' => array('style'  => PHPExcel_Style_Border::BORDER_MEDIUM) // Set border left dengan garis tipis
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => (($cari_data) ? 'E26B0A' : 'AEAAAA'))
                )
            ],
            'style_row' => [
                'alignment' => array(
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
                ),
                'borders' => array(
                    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
                )
            ],
            'style_start' => [
                'alignment' => array(
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
                ),
                'borders' => array(
                    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                    'left' => array('style'  => PHPExcel_Style_Border::BORDER_MEDIUM) // Set border left dengan garis tipis
                )
            ],
            'style_end' => [
                'alignment' => array(
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
                ),
                'borders' => array(
                    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                    'right' => array('style'  => PHPExcel_Style_Border::BORDER_MEDIUM),  // Set border right dengan garis tipis
                    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
                )
            ],
        ];
    }

    public function setSpaceExcel($excel, $row)
    {
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, "");
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $row, "");
        $excel->setActiveSheetIndex(0)->setCellValue('C' . $row, "");
        $excel->setActiveSheetIndex(0)->setCellValue('D' . $row, "");
        $excel->setActiveSheetIndex(0)->setCellValue('E' . $row, "");

        $excel->getActiveSheet()->getStyle('A' . $row)->applyFromArray($this->setStyleExcel()['style_start']);
        $excel->getActiveSheet()->getStyle('B' . $row)->applyFromArray($this->setStyleExcel()['style_row']);
        $excel->getActiveSheet()->getStyle('C' . $row)->applyFromArray($this->setStyleExcel()['style_row']);
        $excel->getActiveSheet()->getStyle('D' . $row)->applyFromArray($this->setStyleExcel()['style_row']);
        $excel->getActiveSheet()->getStyle('E' . $row)->applyFromArray($this->setStyleExcel()['style_end']);
    }
}
