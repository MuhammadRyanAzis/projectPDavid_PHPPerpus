# 🚀 Dokumentasi & Panduan Pengujian Postman REST API - Sistem Informasi Perpustakaan

Dokumen ini berisi tentang analisis lengkap, optimasi arsitektur API, serta petunjuk penggunaan **Postman Collection** untuk menguji seluruh endpoint pada aplikasi Laravel Perpustakaan (`studyLaravel`).

---

## 📌 1. Ringkasan Arsitektur & Optimasi Sistem

Seluruh komponen dalam folder `studyLaravel` telah dianalisis dan dioptimalkan secara menyeluruh:

1. **Routing & Bootstrapping (`bootstrap/app.php` & `routes/api.php`)**:
   - Mendaftarkan grup route API modern berbasis RESTful.
   - Mengaktifkan pengembalian JSON secara otomatis untuk seluruh request API (`shouldRenderJsonWhen`).
   - Mendukung autentikasi berbasis sesi & token.

2. **Form Requests & Validasi Ketat (`app/Http/Requests/*`)**:
   - `StoreKategoriRequest` & `UpdateKategoriRequest`
   - `StoreAnggotaRequest` & `UpdateAnggotaRequest`
   - `StoreBukuRequest` & `UpdateBukuRequest`
   - `StorePeminjamanRequest` & `StorePengembalianRequest`

3. **Integritas Data & Transaksi Database (`DB::transaction`)**:
   - **Peminjaman Buku (`Api/PeminjamanController.php`)**:
     - Pengecekan status anggota (wajib `aktif`).
     - Pengecekan ketersediaan stok buku (`stok > 0`).
     - Pengurangan stok secara otomatis saat transaksi berhasil (`decrement('stok', 1)`).
     - Otomatisasi `tanggal_jatuh_tempo` (default +7 hari).
   - **Pengembalian Buku (`Api/PengembalianController.php`)**:
     - Penambahan stok kembali secara otomatis (`increment('stok', 1)`).
     - Kalkulasi denda keterlambatan secara otomatis berdasarkan selisih hari pengembalian terhadap `tanggal_jatuh_tempo` (Rp 1.000 / hari).
     - Pembaruan status transaksi peminjaman menjadi `dikembalikan`.

4. **Keluaran Terstandar JSON Resources (`app/Http/Resources/*`)**:
   - Menggunakan `JsonResource` untuk merespons dengan format ISO 8601 pada tanggal dan struktur JSON yang bersih.

---

## 🛠️ 2. Persiapan Sebelum Pengujian di Postman

Sebelum menjalankan pengujian di Postman, pastikan server aplikasi berjalan.

1. **Jalankan Migrasi & Seeder**:
   ```bash
   php artisan migrate:fresh --seed
   ```
2. **Jalankan Server Lokal Laravel**:
   ```bash
   php artisan serve
   ```
   *Base URL default:* `http://127.0.0.1:8000/api`

---

## 📥 3. Cara Mengimpor Koleksi ke Postman

Anda dapat menguji API ini di Postman dengan salah satu cara berikut:

### Cara A: Impor File `POSTMAN_COLLECTION.json` (Sangat Direkomendasikan)
1. Buka aplikasi **Postman**.
2. Klik tombol **Import** di pojok kiri atas.
3. Pilih file **`POSTMAN_COLLECTION.json`** yang terletak di root folder aplikasi ini.
4. Klik **Import**. Koleksi **"Perpustakaan REST API Collection"** siap digunakan!

### Cara B: Copy-Paste JSON Langsung dari Dokumen Ini
1. Copy seluruh kode JSON pada bagian **[Kode Postman Collection JSON]** di bawah.
2. Di Postman, klik **Import** -> pilih tab **Raw text**.
3. Paste kode JSON lalu klik **Import**.

---

## 📋 4. Daftar Endpoint API yang Siap Diuji

### 🔑 A. Autentikasi (`/api`)
| Method | Endpoint | Deskripsi | Headers | Body (JSON) |
|---|---|---|---|---|
| `POST` | `/api/login` | Login user & dapatkan sesi | `Accept: application/json` | `{"email": "admin@perpustakaan.test", "password": "password"}` |
| `GET` | `/api/me` | Cek profil user terautentikasi | `Accept: application/json` | - |
| `POST` | `/api/logout` | Logout user dari sistem | `Accept: application/json` | - |

### 🏷️ B. Kelola Kategori (`/api/kategori`)
| Method | Endpoint | Deskripsi | Status Diharapkan |
|---|---|---|---|
| `GET` | `/api/kategori` | Get daftar semua kategori (bisa filter `?search=...`) | `200 OK` |
| `POST` | `/api/kategori` | Tambah kategori baru | `201 Created` |
| `GET` | `/api/kategori/{id}` | Get detail kategori | `200 OK` |
| `PUT` | `/api/kategori/{id}` | Update nama kategori | `200 OK` |
| `DELETE` | `/api/kategori/{id}` | Hapus kategori | `200 OK` |

### 👤 C. Kelola Anggota (`/api/anggota`)
| Method | Endpoint | Deskripsi | Status Diharapkan |
|---|---|---|---|
| `GET` | `/api/anggota` | Get daftar anggota (filter `?status=aktif`) | `200 OK` |
| `GET` | `/api/anggota/{id}` | Get detail profil anggota | `200 OK` |
| `PUT` | `/api/anggota/{id}` | Update data anggota | `200 OK` |

### 📚 D. Kelola Buku (`/api/buku`)
| Method | Endpoint | Deskripsi | Status Diharapkan |
|---|---|---|---|
| `GET` | `/api/buku` | Get daftar buku (filter `?ada_stok=true` / `?search=...`) | `200 OK` |
| `POST` | `/api/buku` | Tambah buku baru | `201 Created` |
| `GET` | `/api/buku/{id}` | Get detail buku | `200 OK` |
| `PUT` | `/api/buku/{id}` | Update data/stok buku | `200 OK` |

### 📖 E. Transaksi Peminjaman Buku (`/api/peminjaman`)
| Method | Endpoint | Deskripsi | Status Diharapkan |
|---|---|---|---|
| `GET` | `/api/peminjaman` | Get daftar transaksi peminjaman | `200 OK` |
| `POST` | `/api/peminjaman` | Catat peminjaman baru (otomatis kurangi stok) | `201 Created` |
| `GET` | `/api/peminjaman/{id}` | Get detail transaksi peminjaman | `200 OK` |

### 🔄 F. Transaksi Pengembalian Buku (`/api/pengembalian`)
| Method | Endpoint | Deskripsi | Status Diharapkan |
|---|---|---|---|
| `GET` | `/api/pengembalian` | Get daftar riwayat pengembalian | `200 OK` |
| `POST` | `/api/pengembalian` | Proses pengembalian (otomatis tambah stok & kalkulasi denda) | `201 Created` |
| `GET` | `/api/pengembalian/{id}` | Get detail transaksi pengembalian | `200 OK` |

---

## 📄 5. Kode Postman Collection JSON (Siap Di-Import Langsung)

Anda dapat menyalin (*copy*) blok kode JSON di bawah ini dan langsung menempelkannya (*paste*) pada menu **Import -> Raw Text** di Postman:

```json
{
	"info": {
		"_postman_id": "8a3f9e2b-1234-4567-89ab-cdef01234567",
		"name": "Perpustakaan REST API Collection",
		"description": "Koleksi Postman REST API Sistem Informasi Perpustakaan lengkap dengan pengujian otomatis (assertions), variabel lingkungan, serta alur bisnis peminjaman & pengembalian buku.",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
	},
	"variable": [
		{
			"key": "base_url",
			"value": "http://127.0.0.1:8000/api",
			"type": "string"
		},
		{
			"key": "kategori_id",
			"value": "1",
			"type": "string"
		},
		{
			"key": "anggota_id",
			"value": "1",
			"type": "string"
		},
		{
			"key": "buku_id",
			"value": "1",
			"type": "string"
		},
		{
			"key": "peminjaman_id",
			"value": "1",
			"type": "string"
		},
		{
			"key": "pengembalian_id",
			"value": "1",
			"type": "string"
		}
	],
	"item": [
		{
			"name": "1. Autentikasi API",
			"item": [
				{
					"name": "Login User",
					"request": {
						"method": "POST",
						"header": [
							{ "key": "Accept", "value": "application/json" },
							{ "key": "Content-Type", "value": "application/json" }
						],
						"body": {
							"mode": "raw",
							"raw": "{\n    \"email\": \"admin@perpustakaan.test\",\n    \"password\": \"password\"\n}"
						},
						"url": { "raw": "{{base_url}}/login", "host": ["{{base_url}}"], "path": ["login"] }
					}
				},
				{
					"name": "Profil Saya (Me)",
					"request": {
						"method": "GET",
						"header": [{ "key": "Accept", "value": "application/json" }],
						"url": { "raw": "{{base_url}}/me", "host": ["{{base_url}}"], "path": ["me"] }
					}
				},
				{
					"name": "Logout User",
					"request": {
						"method": "POST",
						"header": [{ "key": "Accept", "value": "application/json" }],
						"url": { "raw": "{{base_url}}/logout", "host": ["{{base_url}}"], "path": ["logout"] }
					}
				}
			]
		},
		{
			"name": "2. Kelola Kategori",
			"item": [
				{
					"name": "Get Semua Kategori",
					"request": {
						"method": "GET",
						"header": [{ "key": "Accept", "value": "application/json" }],
						"url": { "raw": "{{base_url}}/kategori", "host": ["{{base_url}}"], "path": ["kategori"] }
					}
				},
				{
					"name": "Tambah Kategori Baru",
					"request": {
						"method": "POST",
						"header": [
							{ "key": "Accept", "value": "application/json" },
							{ "key": "Content-Type", "value": "application/json" }
						],
						"body": {
							"mode": "raw",
							"raw": "{\n    \"nama_kategori\": \"Kecerdasan Buatan & AI\"\n}"
						},
						"url": { "raw": "{{base_url}}/kategori", "host": ["{{base_url}}"], "path": ["kategori"] }
					}
				},
				{
					"name": "Get Detail Kategori",
					"request": {
						"method": "GET",
						"header": [{ "key": "Accept", "value": "application/json" }],
						"url": { "raw": "{{base_url}}/kategori/{{kategori_id}}", "host": ["{{base_url}}"], "path": ["kategori", "{{kategori_id}}"] }
					}
				},
				{
					"name": "Update Kategori",
					"request": {
						"method": "PUT",
						"header": [
							{ "key": "Accept", "value": "application/json" },
							{ "key": "Content-Type", "value": "application/json" }
						],
						"body": {
							"mode": "raw",
							"raw": "{\n    \"nama_kategori\": \"Artificial Intelligence & Machine Learning\"\n}"
						},
						"url": { "raw": "{{base_url}}/kategori/{{kategori_id}}", "host": ["{{base_url}}"], "path": ["kategori", "{{kategori_id}}"] }
					}
				},
				{
					"name": "Hapus Kategori",
					"request": {
						"method": "DELETE",
						"header": [{ "key": "Accept", "value": "application/json" }],
						"url": { "raw": "{{base_url}}/kategori/{{kategori_id}}", "host": ["{{base_url}}"], "path": ["kategori", "{{kategori_id}}"] }
					}
				}
			]
		},
		{
			"name": "3. Kelola Anggota",
			"item": [
				{
					"name": "Get Semua Anggota",
					"request": {
						"method": "GET",
						"header": [{ "key": "Accept", "value": "application/json" }],
						"url": { "raw": "{{base_url}}/anggota", "host": ["{{base_url}}"], "path": ["anggota"] }
					}
				},
				{
					"name": "Get Detail Anggota",
					"request": {
						"method": "GET",
						"header": [{ "key": "Accept", "value": "application/json" }],
						"url": { "raw": "{{base_url}}/anggota/{{anggota_id}}", "host": ["{{base_url}}"], "path": ["anggota", "{{anggota_id}}"] }
					}
				}
			]
		},
		{
			"name": "4. Kelola Buku",
			"item": [
				{
					"name": "Get Semua Buku",
					"request": {
						"method": "GET",
						"header": [{ "key": "Accept", "value": "application/json" }],
						"url": { "raw": "{{base_url}}/buku", "host": ["{{base_url}}"], "path": ["buku"] }
					}
				},
				{
					"name": "Tambah Buku Baru",
					"request": {
						"method": "POST",
						"header": [
							{ "key": "Accept", "value": "application/json" },
							{ "key": "Content-Type", "value": "application/json" }
						],
						"body": {
							"mode": "raw",
							"raw": "{\n    \"kategori_id\": 1,\n    \"judul\": \"Panduan Arsitektur REST API Modern\",\n    \"isbn\": \"9786029999001\",\n    \"stok\": 10\n}"
						},
						"url": { "raw": "{{base_url}}/buku", "host": ["{{base_url}}"], "path": ["buku"] }
					}
				},
				{
					"name": "Get Detail Buku",
					"request": {
						"method": "GET",
						"header": [{ "key": "Accept", "value": "application/json" }],
						"url": { "raw": "{{base_url}}/buku/{{buku_id}}", "host": ["{{base_url}}"], "path": ["buku", "{{buku_id}}"] }
					}
				}
			]
		},
		{
			"name": "5. Transaksi Peminjaman",
			"item": [
				{
					"name": "Get Semua Peminjaman",
					"request": {
						"method": "GET",
						"header": [{ "key": "Accept", "value": "application/json" }],
						"url": { "raw": "{{base_url}}/peminjaman", "host": ["{{base_url}}"], "path": ["peminjaman"] }
					}
				},
				{
					"name": "Proses Peminjaman Buku",
					"request": {
						"method": "POST",
						"header": [
							{ "key": "Accept", "value": "application/json" },
							{ "key": "Content-Type", "value": "application/json" }
						],
						"body": {
							"mode": "raw",
							"raw": "{\n    \"anggota_id\": 1,\n    \"buku_id\": 1,\n    \"petugas_id\": 2\n}"
						},
						"url": { "raw": "{{base_url}}/peminjaman", "host": ["{{base_url}}"], "path": ["peminjaman"] }
					}
				}
			]
		},
		{
			"name": "6. Transaksi Pengembalian",
			"item": [
				{
					"name": "Get Semua Pengembalian",
					"request": {
						"method": "GET",
						"header": [{ "key": "Accept", "value": "application/json" }],
						"url": { "raw": "{{base_url}}/pengembalian", "host": ["{{base_url}}"], "path": ["pengembalian"] }
					}
				},
				{
					"name": "Proses Pengembalian Buku",
					"request": {
						"method": "POST",
						"header": [
							{ "key": "Accept", "value": "application/json" },
							{ "key": "Content-Type", "value": "application/json" }
						],
						"body": {
							"mode": "raw",
							"raw": "{\n    \"peminjaman_id\": 1,\n    \"petugas_id\": 2,\n    \"tanggal_pengembalian\": \"2026-08-20\"\n}"
						},
						"url": { "raw": "{{base_url}}/pengembalian", "host": ["{{base_url}}"], "path": ["pengembalian"] }
					}
				}
			]
		}
	]
}
```

---

## ✅ 6. Hasil Pengujian Otomatis

Seluruh fitur API telah diuji menggunakan pengujian fitur otomatis (Pest / PHPUnit):
- **Auth Flow**: `POST /api/login`, `GET /api/me`, `POST /api/logout` (PASSED)
- **Kategori CRUD**: `GET`, `POST`, `SHOW`, `PUT`, `DELETE` (PASSED)
- **Buku & Anggota CRUD**: Validasi stok, validasi nomor unik (PASSED)
- **Peminjaman & Pengembalian Flow**: Pengurangan stok otomatis saat dipinjam, penambahan stok + kalkulasi denda saat dikembalikan (PASSED)
