# Koperasi App — README (claude.md)

## 📌 Deskripsi Singkat

Aplikasi koperasi ini dibuat menggunakan Laravel dengan struktur best practice. Fokus utama aplikasi adalah pengelolaan anggota, simpanan, pinjaman, transaksi, dan laporan. Aplikasi ini menggunakan sistem role-based access control untuk memastikan setiap pengguna hanya dapat mengakses fitur yang sesuai dengan kewenangannya.

---

# 🧩 **Role & Akses Utama**

Aplikasi ini memiliki beberapa role inti berikut:

## 1. **Admin Utama (Super Admin)**

Role tertinggi dalam sistem.

### Tanggung Jawab & Hak Akses

* Manajemen seluruh user dan role.
* Pengaturan parameter koperasi (jenis simpanan, bunga, denda, alur persetujuan, dsb).
* Melihat seluruh data simpanan, pinjaman, transaksi.
* Mengelola laporan tingkat global.
* Reset data jika diperlukan.

---

## 2. **Pengurus Koperasi**

Role operasional utama.

### Tanggung Jawab & Hak Akses

* Menyetujui atau menolak pendaftaran anggota baru.
* Mengelola simpanan anggota (wajib, sukarela, pokok).
* Memproses pengajuan pinjaman.
* Menentukan limit pinjaman berdasarkan kebijakan.
* Melakukan pencatatan transaksi manual.
* Melihat laporan harian / bulanan.

---

## 3. **Bendahara**

Fokus pada transaksi keuangan.

### Tanggung Jawab & Hak Akses

* Verifikasi pembayaran simpanan.
* Verifikasi angsuran pinjaman.
* Pencatatan kas keluar & kas masuk.
* Melihat saldo kas keseluruhan.
* Membuat laporan keuangan berkala.

---

## 4. **Anggota**

Pengguna utama koperasi.

### Hak Akses

* Melihat profil & status keanggotaan.
* Melihat riwayat simpanan.
* Mengajukan pinjaman.
* Melihat angsuran berjalan.
* Mengunduh laporan pribadi (rekening koran koperasi).

---

# 🔄 **Flow Utama Aplikasi**

Di bawah ini adalah alur utama yang berjalan di dalam aplikasi koperasi.

---

## 🧍‍♂️ 1. **Flow Pendaftaran Anggota**

1. User mengisi form pendaftaran anggota.
2. Sistem membuat status *Pending Approval*.
3. Pengurus meninjau data.
4. Pengurus dapat:

   * **Approve** → akun aktif, anggota resmi.
   * **Reject** → pendaftaran ditolak.
5. Anggota aktif bisa login dan mulai memakai aplikasi.

---

## 💰 2. **Flow Simpanan Anggota**

### Jenis simpanan yang umum:

* **Simpanan Pokok** (dibayar sekali saat daftar)
* **Simpanan Wajib** (bulanan)
* **Simpanan Sukarela** (kapan saja)

### Alur:

1. Anggota melakukan request setoran atau setor langsung ke bendahara.
2. Bendahara memverifikasi transaksi.
3. Sistem mencatat transaksi simpanan.
4. Anggota dapat melihat progres simpanan.

---

## 💸 3. **Flow Pengajuan Pinjaman**

### Alur Utama:

1. Anggota membuka halaman *Pengajuan Pinjaman*.
2. Anggota mengisi nominal, tenor, dan alasan.
3. Sistem mengecek syarat dasar (jumlah simpanan, status keanggotaan, limit pinjaman).
4. Pengurus menerima pengajuan.
5. Pengurus meninjau dan memberikan keputusan:

   * **Approve →** request diteruskan ke bendahara.
   * **Reject →** selesai.
6. Bendahara mencairkan dana jika disetujui.
7. Sistem membuat jadwal angsuran otomatis.

---

## 📅 4. **Flow Pembayaran Angsuran Pinjaman**

1. Anggota melihat jadwal angsuran.
2. Anggota melakukan pembayaran.
3. Bendahara memverifikasi pembayaran.
4. Sistem mengurangi sisa pinjaman.
5. Jika seluruh angsuran lunas:

   * Status pinjaman berubah menjadi **Lunas**.

---

## 🧾 5. **Flow Transaksi Umum (Kas Masuk / Kas Keluar)**

1. Bendahara mencatat transaksi manual (non-pinjaman/non-simpanan).
2. Sistem menghitung saldo kas secara otomatis.
3. Admin & bendahara dapat melihat rekap kas.

---

## 📊 6. **Flow Laporan**

Role: Admin Utama, Pengurus, Bendahara (sesuai level akses).

### Laporan yang dapat dihasilkan:

* Laporan simpanan per anggota.
* Laporan pinjaman & status angsuran.
* Laporan keuangan koperasi.
* Laporan kas umum (masuk/keluar).
* Laporan perkembangan koperasi.

Semua laporan dapat diunduh dalam PDF/Excel.

---

# 🛡️ **Flow Hak Akses (RBAC)**

Setiap menu dalam aplikasi diatur melalui role permissions.

### Contoh Mapping Akses (Simplified):

| Modul          | Admin | Pengurus    | Bendahara    | Anggota      |
| -------------- | ----- | ----------- | ------------ | ------------ |
| Manajemen User | ✔️    | ❌           | ❌            | ❌            |
| Simpanan       | ✔️    | ✔️          | ✔️           | ✔️ (lihat)   |
| Pinjaman       | ✔️    | ✔️ (review) | ✔️ (cairkan) | ✔️ (ajukan)  |
| Kas Umum       | ✔️    | ❌           | ✔️           | ❌            |
| Laporan        | ✔️    | ✔️          | ✔️           | ✔️ (pribadi) |

---

# 🧱 **Entity / Data Utama** (Tanpa membahas struktur folder)

Aplikasi koperasi ini menggunakan beberapa entitas inti:

* **Users** (Anggota, Pengurus, Bendahara, Admin)
* **Simpanan** (pokok, wajib, sukarela)
* **Pinjaman**
* **Angsuran**
* **Kas** (kas masuk & keluar)
* **Laporan**

---

# 🚀 Next Step

Anda dapat melanjutkan pembuatan:

* Seeder default role & permission
* Modul CRUD berdasarkan flow di atas
* Tampilan dashboard berbeda untuk setiap role

Dokumen ini dapat dikembangkan menjadi blueprint lengkap sesuai kebutuhan koperasi Anda.
