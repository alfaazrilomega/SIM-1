<?php

namespace App\Controllers;

use App\Libraries\ExcelImporter;

class Import extends BaseController
{
    // -------------------------------------------------------
    // GET /import  — Halaman upload
    // -------------------------------------------------------
    public function index(): string
    {
        return view('import/index');
    }

    // -------------------------------------------------------
    // POST /import/process  — AJAX handler
    // Mengembalikan JSON
    // -------------------------------------------------------
    public function process(): \CodeIgniter\HTTP\ResponseInterface
    {
        // Hanya izinkan AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)
                ->setJSON(['success' => false, 'error' => 'Forbidden']);
        }

        // Validasi ada file
        $file = $this->request->getFile('excel_file');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'error'   => 'Tidak ada file yang diupload atau file tidak valid.',
            ]);
        }

        // Validasi ekstensi
        if (strtolower($file->getExtension()) !== 'xlsx') {
            return $this->response->setJSON([
                'success' => false,
                'error'   => 'Hanya file .xlsx yang diterima. File kamu: .' . $file->getExtension(),
            ]);
        }

        // Validasi ukuran (max 50MB)
        if ($file->getSizeByUnit('mb') > 50) {
            return $this->response->setJSON([
                'success' => false,
                'error'   => 'Ukuran file terlalu besar. Maksimum 50 MB.',
            ]);
        }

        // Simpan ke writable/uploads/excel sementara
        $uploadDir = WRITEPATH . 'uploads/excel/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadDir, $newName);
        $filePath = $uploadDir . $newName;

        // Jalankan import
        try {
            $importer = new ExcelImporter();
            $result   = $importer->import($filePath, $file->getClientName());
        } catch (\Throwable $e) {
            @unlink($filePath);
            return $this->response->setJSON([
                'success' => false,
                'error'   => 'Import gagal: ' . $e->getMessage(),
            ]);
        }

        // Hapus file sementara
        @unlink($filePath);

        return $this->response->setJSON($result);
    }
}
