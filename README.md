# Demo Project: CodeIgniter 4 Upload & Download File

Project ini adalah demo dari tutorial CodeIgniter 4 untuk fitur upload dan download file. Panduan lengkap dapat Anda baca di:

https://qadrlabs.com/post/tutorial-codeigniter-4-upload-dan-download-file

## Tentang Project
- Framework: CodeIgniter 4 (AppStarter).
- Fitur: unggah, daftar, lihat detail, unduh, dan hapus dokumen.
- Struktur utama: `app/Controllers/DocumentController.php`, `app/Models/DocumentModel.php`, tampilan di `app/Views/documents/`, file tersimpan di `public/uploads/`.

## Prasyarat
- PHP 8.1+ dan Composer.
- Database (MySQL/MariaDB/PostgreSQL) yang terkonfigurasi di `.env`.

## Langkah-Langkah Setup
1. Instal dependensi:
   ```bash
   composer install
   ```
2. Salin berkas environment dan generate key:
   ```bash
   cp env .env
   php spark key:generate
   ```
3. Konfigurasi `.env` (contoh yang perlu disetel):
   ```ini
   CI_ENVIRONMENT = development
   app.baseURL = 'http://localhost:8080/'
   database.default.hostname = 127.0.0.1
   database.default.database = your_db
   database.default.username = your_user
   database.default.password = your_pass
   database.default.DBDriver = MySQLi
   ```
4. Siapkan database dan jalankan migrasi tabel dokumen:
   ```bash
   php spark migrate
   ```
5. Jalankan server development:
   ```bash
   php spark serve
   ```

## Cara Menggunakan
- Akses aplikasi: `http://localhost:8080/` (daftar dokumen).
- Upload dokumen: `http://localhost:8080/documents/upload`.
- Unduh/hapus/detail mengikuti tautan pada tabel daftar dokumen.

## Hak Akses Folder (OS Spesifik)
Pastikan folder `writable/` dan `public/uploads/` dapat ditulis (writeable) oleh user/proses web server.

### Linux/macOS
```bash
# Buat folder yang diperlukan
mkdir -p writable/{cache,logs,session,uploads} public/uploads

# Opsi 1 (development): kepemilikan ke user saat ini
chown -R "$(whoami)":"$(id -gn)" writable public/uploads
find writable public/uploads -type d -exec chmod 775 {} \;
find writable public/uploads -type f -exec chmod 664 {} \;

# Opsi 2 (server): kepemilikan ke user web server
# Ubuntu/Debian: www-data, CentOS/RHEL: apache, Arch: http
sudo chown -R www-data:www-data writable public/uploads
sudo find writable public/uploads -type d -exec chmod 775 {} \;
sudo find writable public/uploads -type f -exec chmod 664 {} \;

# Alternatif granular (jika tersedia ACL)
sudo setfacl -R -m u:www-data:rwx -m u:"$(whoami)":rwx writable public/uploads
sudo setfacl -dR -m u:www-data:rwx -m u:"$(whoami)":rwx writable public/uploads
```

### Windows
- Klik kanan folder `writable` dan `public/uploads` → Properties → Security → Edit.
- Tambahkan user yang menjalankan PHP/web server (mis. IIS_IUSRS atau user lokal) lalu beri izin `Modify` dan `Write`.
- Untuk PHP built-in server (`php spark serve`), user yang menjalankan terminal biasanya sudah memiliki izin; pastikan atribut Read-only tidak aktif.

## Pengujian (opsional)
Jalankan seluruh test dengan PHPUnit:
```bash
composer test
```
