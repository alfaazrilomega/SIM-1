# Implementasi Navigasi Fitur Penarikan Dana (Withdrawal)

Berdasarkan pengecekan file di dalam proyek (pada [app/Controllers/Withdrawal.php](file:///d:/laragon/www/SIM/app/Controllers/Withdrawal.php) dan [app/Views/withdrawal/index.php](file:///d:/laragon/www/SIM/app/Views/withdrawal/index.php)), **fitur Penarikan Dana (Withdrawal) yang diminta sebenarnya sudah selesai dibuat** pada sesi sebelumnya!

Fitur tersebut sudah memiliki:
1. Halaman Dashboard CEO yang keren.
2. Fitur menarik semua data berstatus 'Selesai' dan 'Belum Ditarik'.
3. Fitur membatalkan data yang 'Sudah Ditarik'.
4. Rekapan total pendapatan dan pending dana.

Karena fitur sudah sepenuhnya ada, kita hanya perlu **menambahkan akses navigasi** agar fitur ini mudah diakses, karena saat ini tautannya belum ada di halaman Import maupun halaman Home.

## Proposed Changes

### Navigation Updates
#### [MODIFY] [import/index.php](file:///d:/laragon/www/SIM/app/Views/import/index.php)
- Tambahkan tombol pintasan (link) menuju `/withdrawal` di sisi kanan header, bersebelahan dengan label platform (TikTok).

#### [MODIFY] [welcome_message.php](file:///d:/laragon/www/SIM/app/Views/welcome_message.php)
- Ubah halaman *Welcome CodeIgniter* default (di rute `/`) menjadi sebuah *Landing Page/Menu* simpel yang menampilkan dua kartu pilihan utama:
  - Menu menuju **Import Data Excel** (`/import`)
  - Menu menuju **CEO Withdrawal Dashboard** (`/withdrawal`)

## Verification Plan

### Manual Verification
- Buka `http://localhost:8080/` (atau root local Laragon). Pastikan tampilan root sekarang berisi menu navigasi yang jelas.
- Buka halaman Import, klik tombol navigasi baru di bagian header `Withdrawal Dashboard`. Pastikan berhasil pindah ke `/withdrawal`.
- Pada halaman `/withdrawal`, Anda bisa langsung menggunakan kelengkapan fitur "Tarik Dana" sesuai yang Anda minta (pada data yang berstatus *Selesai*).
