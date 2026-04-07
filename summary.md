# Analisis Data & Kebutuhan Database Manajemen Pesanan

Berdasarkan analisa dari data Excel (Tiktok/Shopee) dan kebutuhan sistem, berikut adalah rancangan alur, struktur tabel, dan *business logic* yang perlu diimplementasikan di sisi backend:

## 1. Aturan Pengolahan Data (Business Rules)

* **Platform Unique ID (Sistem Upsert):** Setiap transaksi memiliki ID unik dari platform (Order ID). Kolom ini bertindak sebagai **Primary Key (Unique)**. Jika ada upload file Excel baru dan mendeteksi ID yang sudah ada di database, sistem harus melakukan **UPDATE** pada record tersebut (overwrite data lama), bukan membuat duplikat baru.
* **Logika Filter Laporan:** Sistem akan menyimpan riwayat transaksi, namun semua bentuk penarikan laporan (Get Data) hanya memunculkan data yang memiliki `status_pesanan = 'Selesai'`.
* **Logika Penarikan Dana (Withdrawal):** Sistem memerlukan field/kolom khusus `status_penarikan` untuk diakses oleh manajemen (CEO). Field ini saling berkaitan dengan status pesanan.
* **Kombinasi Produk & Kategori:** Sistem perlu menggabungkan (combine) data dari kolom "Nama Produk" dan "Variasi/Kategori" menjadi satu kesatuan untuk pelacakan *Quantity*. Hal ini diperlukan karena adanya penamaan variasi seperti "Default" yang harus di-mapping ke nama produk aslinya (Bumbu Rendang, Soto, Rawon, Gulai, Ceker Mercon, dll).
* **Tracking Quantity:**
    Setiap ID unik harus memuat rekapan *Quantity Order* yang valid dari hasil kombinasi produk di atas.

## 2. Draft Rancangan Tabel (Entity Requirements)

Untuk mengakomodasi aturan di atas, backend setidaknya memerlukan struktur entitas berikut:

### Tabel `transaksi_pesanan` (Order Table)
Tabel utama untuk mencatat identitas pesanan.
* `order_id` (String / Varchar) -> **UNIQUE / PRIMARY KEY** (ID dari platform)
* `status_pesanan` (String) -> (Selesai, Dikirim, Batal, dll)
* `status_penarikan` (Boolean / Enum) -> (Sudah Ditarik / Belum Ditarik)
* `total_amount` (Integer / Decimal) -> Total nilai transaksi
* `tanggal_update` (Timestamp) -> Waktu terakhir file Excel mengupdate baris ini.

### Tabel `detail_pesanan` (Order Items Table)
Tabel untuk memecah barang apa saja yang dibeli dalam satu `order_id` (karena 1 pesanan bisa beli bumbu rendang dan soto sekaligus).
* `id_detail` (Integer) -> PK
* `order_id` (String) -> **FOREIGN KEY** (Relasi ke tabel transaksi)
* `nama_produk_raw` (String) -> Nama produk asli dari Excel
* `variasi_raw` (String) -> Nama variasi asli dari Excel (termasuk "Default")
* `kombinasi_produk` (String) -> Hasil generate backend (misal: "Bumbu Soto 250gr")
* `quantity` (Integer) -> Jumlah barang

## 3. Catatan Tambahan untuk Backend
* Pastikan logika perhitungan total dan relasi database kuat untuk mengantisipasi *update* harga/kuantitas jika ada Order ID yang ter-update di upload Excel bulan berikutnya.