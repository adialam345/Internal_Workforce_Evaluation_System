# PROJECT SPECIFICATION
# Sistem Evaluasi Tenaga Kerja Internal

## 1. Tujuan Project

Membangun aplikasi web internal untuk melakukan evaluasi sekitar 4.000 tenaga kerja yang dikelola oleh sekitar 70 akun evaluator/verifikator.

Sistem harus memungkinkan:
- Admin mengelola data tenaga kerja.
- Admin mengelola akun evaluator.
- Admin melakukan assignment tenaga kerja ke evaluator.
- Evaluator login menggunakan akun masing-masing.
- Evaluator hanya dapat melihat dan mengevaluasi tenaga kerja yang menjadi tanggung jawabnya.
- Evaluator mengisi form evaluasi.
- Evaluator dapat melihat status/progress pekerjaannya.
- Hasil evaluasi dapat dicetak.
- Admin dapat melihat progress keseluruhan.
- Admin dapat melihat progress masing-masing evaluator.
- Admin dapat import data melalui Excel.
- Admin dapat export hasil evaluasi ke Excel.
- Sistem memiliki authentication dan authorization yang aman.
- UI responsive untuk desktop dan mobile.

## 2. Tech Stack

### Backend
- PHP 8.3+
- Laravel 13
- MySQL / MariaDB

### Frontend
- Laravel Blade
- Tailwind CSS
- Alpine.js jika diperlukan
- Chart.js untuk dashboard/chart

### Authentication
- Laravel authentication
- Session-based authentication
- Role Based Access Control

### Excel
Gunakan Laravel Excel / PhpSpreadsheet.

Fitur:
- Import Excel
- Export Excel
- Validasi data
- Error reporting saat import

### PDF / Print
Utamakan print-friendly HTML menggunakan CSS print.
Jika diperlukan, gunakan DomPDF untuk menghasilkan PDF.

## 3. Prinsip Pengembangan

Prioritas:
1. Simpel
2. Aman
3. Mudah digunakan
4. Mudah di-maintain
5. Cepat
6. Tidak overengineering

Jangan menggunakan microservices.
Jangan membuat SPA jika tidak diperlukan.
Gunakan Laravel Blade sebagai frontend utama.
Gunakan AJAX/fetch hanya jika memang meningkatkan UX.

## 4. Role User

### ADMIN

Admin memiliki akses penuh.

Admin dapat:
- Login
- Melihat dashboard
- Melihat seluruh tenaga kerja
- Menambah tenaga kerja
- Mengedit tenaga kerja
- Menghapus/nonaktifkan tenaga kerja
- Import tenaga kerja dari Excel
- Melihat seluruh evaluator
- Membuat akun evaluator
- Mengedit evaluator
- Mengaktifkan/nonaktifkan evaluator
- Assign tenaga kerja ke evaluator
- Melihat seluruh hasil evaluasi
- Melihat progress keseluruhan
- Melihat progress per evaluator
- Filter data
- Export hasil ke Excel
- Print hasil evaluasi
- Melihat activity/audit log jika fitur dibuat

### EVALUATOR / VERIFIKATOR

Evaluator hanya dapat mengakses data yang diberikan kepadanya.

Evaluator dapat:
- Login
- Melihat dashboard sendiri
- Melihat daftar tenaga kerja yang menjadi tanggung jawabnya
- Melihat progress sendiri
- Mencari tenaga kerja
- Membuka data tenaga kerja
- Mengisi evaluasi
- Menyimpan evaluasi
- Submit evaluasi
- Melihat hasil evaluasi
- Print hasil evaluasi

Evaluator TIDAK BOLEH:
- Melihat data evaluator lain
- Melihat tenaga kerja milik evaluator lain
- Mengubah assignment
- Mengakses dashboard admin
- Mengelola user
- Mengimport data
- Mengekspor seluruh database

## 5. Data Tenaga Kerja

Sistem harus mendukung minimal 4.000 data tenaga kerja.

Field awal:
- id
- employee_code / NIK
- nama
- jabatan
- divisi
- department
- unit
- evaluator_id
- status
- created_at
- updated_at

Field dapat disesuaikan setelah format data asli dari client diberikan.

Jangan hardcode nama field jika belum final.

## 6. Evaluator Assignment

Setiap tenaga kerja dapat dikaitkan dengan evaluator.

Contoh:
Evaluator 01:
- Employee 001
- Employee 002
- Employee 003

Evaluator 02:
- Employee 004
- Employee 005
- Employee 006

Versi pertama menggunakan satu evaluator utama per employee:
employees.evaluator_id -> users.id

Jika kebutuhan masa depan memerlukan lebih dari satu evaluator, struktur dapat dikembangkan.

## 7. Dashboard Admin

Dashboard admin harus menampilkan:
- Total tenaga kerja
- Sudah dievaluasi
- Belum dievaluasi
- Progress percentage
- Total evaluator
- Evaluator aktif
- Evaluator tidak aktif
- Total evaluasi

Contoh:
Total: 4.000
Selesai: 2.850
Belum: 1.150
Progress: 71,25%

## 8. Progress Dashboard

Admin dapat melihat progress per evaluator.

| Evaluator | Total | Selesai | Belum | Progress |
|---|---:|---:|---:|---:|
| User 01 | 60 | 60 | 0 | 100% |
| User 02 | 55 | 40 | 15 | 72,7% |
| User 03 | 58 | 25 | 33 | 43,1% |

Progress:
completed evaluations / assigned employees * 100

Progress dihitung dinamis dari database.

## 9. Dashboard Evaluator

Tampilkan:
- Total anggota
- Sudah dievaluasi
- Belum dievaluasi
- Progress
- Daftar anggota

Contoh:
| NIK | Nama | Jabatan | Status | Action |
|---|---|---|---|---|
| 001 | Budi | Staff | Selesai | Lihat |
| 002 | Andi | Staff | Belum | Evaluasi |

Tambahkan search dan pagination.

## 10. Form Evaluasi

Form evaluasi sebaiknya configurable.

Tabel:
evaluation_questions

Field:
- id
- question
- type
- options
- order
- is_required
- is_active
- created_at
- updated_at

Contoh:
- Kedisiplinan
- Tanggung jawab
- Kinerja
- Kehadiran
- Kerja sama
- Catatan evaluator

Jenis:
- rating
- select
- radio
- text
- number
- textarea

Pertanyaan final mengikuti requirement client.

## 11. Struktur Evaluasi

Tabel evaluations:
- id
- employee_id
- evaluator_id
- status
- submitted_at
- created_at
- updated_at

Status:
- draft
- submitted

Tabel evaluation_answers:
- id
- evaluation_id
- question_id
- answer
- created_at
- updated_at

Relasi:
Employee -> Evaluation -> Evaluation Answers -> Questions

## 12. Proses Evaluasi

Evaluator login
-> Dashboard
-> Pilih tenaga kerja
-> Buka data
-> Mulai evaluasi
-> Isi form
-> Simpan Draft atau Submit
-> Jika submit: status=submitted dan submitted_at=current timestamp
-> Progress evaluator dan admin otomatis berubah.

## 13. Aturan Submit

Setelah submitted:
- Evaluasi dianggap selesai.
- Evaluator tidak dapat mengubah evaluasi.

Admin dapat "Unlock / Buka Kembali Evaluasi".
Jika dibuka:
submitted -> draft

Semua perubahan penting sebaiknya dicatat dalam audit log.

## 14. Print Hasil Evaluasi

Buat route:
`/evaluations/{id}/print`

Format:
- Data tenaga kerja
- NIK
- Nama
- Jabatan
- Divisi
- Evaluator
- Tanggal evaluasi
- Hasil evaluasi
- Catatan
- Area tanda tangan

Gunakan `@media print`.
Sidebar, navbar, menu, dan tombol tidak boleh ikut tercetak.

## 15. Import Excel

Admin dapat upload Excel.

Contoh:
| NIK | Nama | Jabatan | Divisi | Evaluator |
|---|---|---|---|---|
| 001 | Budi | Staff | Produksi | User01 |
| 002 | Andi | Staff | Produksi | User01 |

Flow:
Upload -> Validasi -> Preview -> Konfirmasi -> Import

Validasi:
- Kolom wajib
- NIK unik
- Evaluator tersedia
- Data wajib tidak kosong
- Format valid

Jika error, tampilkan nomor baris dan alasan.
Data invalid tidak boleh masuk database.

## 16. Export Excel

Admin dapat export:
- Semua data
- Berdasarkan evaluator
- Berdasarkan status
- Berdasarkan divisi

Minimal:
- NIK
- Nama
- Jabatan
- Divisi
- Evaluator
- Status
- Nilai/hasil evaluasi
- Tanggal evaluasi
- Catatan

Contoh filename:
`evaluasi_2026-10-09.xlsx`

## 17. Search dan Filter

Admin:
Search:
- NIK
- Nama

Filter:
- Evaluator
- Divisi
- Jabatan
- Status evaluasi

Evaluator:
Search:
- NIK
- Nama

Filter:
- Status evaluasi

Gunakan server-side pagination.

Jangan load 4.000 record sekaligus ke browser.

## 18. Database Indexing

Index:
- employees.nik
- employees.nama
- employees.evaluator_id
- employees.divisi
- evaluations.employee_id
- evaluations.evaluator_id
- evaluations.status
- users.email
- users.role

Gunakan foreign key dengan benar.

## 19. Security

Implementasikan:
- CSRF protection
- Password hashing
- Authentication
- Authorization
- Role middleware
- Validation
- SQL injection protection melalui Eloquent/query builder
- XSS protection
- Mass assignment protection
- Login rate limiting
- Session security

Evaluator tidak boleh mengakses ID evaluasi milik evaluator lain dengan mengganti URL.

Gunakan Laravel Policy/Gate.

## 20. Authorization

Buat:
- EmployeePolicy
- EvaluationPolicy
- UserPolicy

Evaluator hanya boleh melihat employee jika:
`employee.evaluator_id == auth()->id()`

Evaluator hanya boleh melihat evaluation jika:
`evaluation.evaluator_id == auth()->id()`

Admin memiliki akses penuh.

Authorization wajib dilakukan di backend, bukan hanya menyembunyikan tombol frontend.

## 21. Audit Log

Jika memungkinkan, catat:
- Login
- Logout
- Create employee
- Update employee
- Import employee
- Assign employee
- Create evaluation
- Submit evaluation
- Unlock evaluation
- Export data

Minimal:
- user_id
- action
- description
- target_type
- target_id
- ip_address
- user_agent
- created_at

## 22. UI/UX

Gunakan Tailwind CSS.

Desain:
- Clean
- Professional
- Responsive
- Tidak terlalu banyak warna
- Mudah digunakan user non-teknis

Layout desktop:
Sidebar + Header + Content

Mobile:
Sidebar drawer.

Gunakan reusable Blade components:
- button
- input
- select
- modal
- badge
- card
- table
- pagination

## 23. Struktur Menu Admin

Dashboard

Tenaga Kerja
- Semua Tenaga Kerja
- Import Excel

Evaluator
- Semua Evaluator
- Tambah Evaluator

Evaluasi
- Semua Evaluasi
- Belum Selesai
- Sudah Selesai

Progress

Export

Audit Log

Settings

## 24. Struktur Menu Evaluator

Dashboard

Anggota Saya

Evaluasi
- Belum Dievaluasi
- Selesai

Profile

Logout

## 25. Struktur Laravel

Gunakan struktur Laravel standar.

Contoh:

app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Evaluator/
│   │   ├── Auth/
│   │   └── EvaluationController.php
│   ├── Requests/
│   │   ├── Employee/
│   │   └── Evaluation/
│   └── Middleware/
├── Models/
│   ├── User.php
│   ├── Employee.php
│   ├── Evaluation.php
│   ├── EvaluationAnswer.php
│   └── EvaluationQuestion.php
├── Policies/
│   ├── EmployeePolicy.php
│   └── EvaluationPolicy.php
└── Services/
    ├── EvaluationService.php
    ├── ImportService.php
    └── ExportService.php

database/
├── migrations/
├── seeders/
└── factories/

resources/
└── views/
    ├── layouts/
    ├── components/
    ├── admin/
    ├── evaluator/
    ├── auth/
    └── evaluations/

routes/
├── web.php
└── api.php

Jangan membuat struktur terlalu kompleks jika belum diperlukan.

## 26. Routes

Admin:
- /admin/dashboard
- /admin/employees
- /admin/employees/import
- /admin/evaluators
- /admin/evaluations
- /admin/progress
- /admin/export
- /admin/audit-logs

Evaluator:
- /evaluator/dashboard
- /evaluator/employees
- /evaluator/employees/{employee}
- /evaluator/evaluations/{employee}
- /evaluator/evaluations/{evaluation}/print

Gunakan middleware authorization.

## 27. Seeder

Buat seeder development:
- 1 Admin
- 2-3 Evaluator
- 20-50 dummy employees
- Dummy evaluation questions

Jangan gunakan password development di production.

## 28. Testing

Minimal feature test untuk:
- Authentication
- Admin access
- Evaluator access
- Evaluator tidak dapat melihat employee evaluator lain
- Evaluator dapat melihat employee miliknya
- Evaluator submit evaluation
- Progress berubah setelah submit
- Admin melihat progress
- Import Excel
- Export Excel
- Policy authorization

## 29. Performance

Target:
- 4.000 employees
- 70 evaluator accounts
- Tetap responsif

Gunakan:
- Pagination
- Eager loading
- Database indexing
- Query optimization
- Cache jika diperlukan
- Queue untuk proses berat

Hindari N+1 query.

## 30. Progress Calculation

Jangan menyimpan progress percentage sebagai source of truth.

Hitung:
`total = employee assigned ke evaluator`
`completed = evaluation dengan status submitted`
`progress = completed / total * 100`

Jika total = 0:
progress = 0

Gunakan pembulatan konsisten.

## 31. Empty States

Sediakan empty state untuk semua halaman.

Contoh:
- "Belum ada tenaga kerja yang ditugaskan kepada Anda."
- "Belum ada evaluasi yang selesai."
- "Data tidak ditemukan."

## 32. Error Handling

Production:
`APP_DEBUG=false`

Jangan tampilkan stack trace ke user.

Gunakan pesan user-friendly:
"Terjadi kesalahan saat menyimpan evaluasi. Silakan coba lagi."

Validation error tampil dekat field terkait.

## 33. Environment

Gunakan `.env`.

Jangan commit:
- `.env`
- credentials
- password production
- API keys

Sediakan `.env.example`.

## 34. Deployment

Target:
- VPS
- cPanel

Production checklist:
- APP_ENV=production
- APP_DEBUG=false
- APP_KEY tersedia
- Database configured
- Storage link
- Cache config
- Cache route
- Queue jika digunakan
- HTTPS
- Backup database

## 35. Backup

Karena data evaluasi penting:
- Daily database backup
- Backup retention configurable

Jangan bergantung pada satu database saja.

## 36. Business Rules

1. Satu evaluator hanya dapat mengakses data yang ditugaskan kepadanya.
2. Admin dapat melihat seluruh data.
3. Evaluasi submitted dianggap selesai.
4. Evaluator tidak dapat mengubah submitted evaluation kecuali admin unlock.
5. Progress dihitung otomatis.
6. Import Excel tidak boleh memasukkan data invalid.
7. NIK/employee code harus unik.
8. Export hanya dapat dilakukan Admin.
9. Evaluator tidak dapat mengakses endpoint admin.
10. Authorization wajib dilakukan di backend.

## 37. Development Order

### Phase 1 - Foundation
- Install Laravel
- Configure database
- Install Tailwind
- Authentication
- User model
- Role system

### Phase 2 - Employee Management
- Employee migration
- Employee model
- CRUD employee
- Search
- Filter
- Pagination

### Phase 3 - Evaluator Assignment
- Evaluator management
- Assign employee
- Authorization

### Phase 4 - Evaluation
- Questions
- Evaluation
- Answers
- Draft
- Submit
- Validation

### Phase 5 - Progress Dashboard
- Admin dashboard
- Evaluator dashboard
- Progress calculation
- Chart
- Progress per evaluator

### Phase 6 - Excel
- Import
- Validation
- Preview
- Export

### Phase 7 - Print
- Print evaluation
- Print CSS
- PDF jika diperlukan

### Phase 8 - Security
- Policies
- Authorization testing
- Rate limiting
- Audit log

### Phase 9 - Testing
- Feature tests
- Authorization tests
- Import/export tests

### Phase 10 - Production
- Production configuration
- HTTPS
- Backup
- Queue
- Cache
- Deployment documentation

## 38. Acceptance Criteria

### Authentication
- [ ] Admin dapat login.
- [ ] Evaluator dapat login.
- [ ] User dapat logout.
- [ ] Password menggunakan hashing.

### Employee
- [ ] Admin dapat CRUD employee.
- [ ] Admin dapat import Excel.
- [ ] Employee dapat diassign ke evaluator.
- [ ] NIK unik.
- [ ] Search bekerja.
- [ ] Pagination bekerja.

### Evaluator
- [ ] Evaluator hanya melihat employee miliknya.
- [ ] Evaluator dapat membuka employee.
- [ ] Evaluator dapat mengisi evaluasi.
- [ ] Evaluator dapat submit evaluasi.

### Evaluation
- [ ] Evaluation tersimpan.
- [ ] Status menjadi submitted.
- [ ] Submitted evaluation tidak dapat diedit evaluator.
- [ ] Admin dapat unlock evaluation.

### Dashboard
- [ ] Admin dapat melihat total employee.
- [ ] Admin dapat melihat total selesai.
- [ ] Admin dapat melihat total belum selesai.
- [ ] Admin dapat melihat progress percentage.
- [ ] Admin dapat melihat progress per evaluator.
- [ ] Evaluator dapat melihat progress sendiri.

### Export
- [ ] Admin dapat export Excel.
- [ ] Export dapat difilter.
- [ ] Data export benar.

### Print
- [ ] Evaluation dapat dicetak.
- [ ] Print layout rapi.
- [ ] Navigation/button tidak ikut tercetak.

### Security
- [ ] Evaluator tidak dapat mengakses data evaluator lain.
- [ ] Evaluator tidak dapat mengakses admin.
- [ ] CSRF protection aktif.
- [ ] Authorization menggunakan Policy/Gate.
- [ ] Production APP_DEBUG=false.

## 39. Important Instruction For AI Agent

Jangan langsung membuat seluruh aplikasi dalam satu langkah.

Kerjakan secara incremental.

Setiap phase:
1. Analisis requirement.
2. Buat migration.
3. Buat model.
4. Buat controller/service.
5. Buat policy.
6. Buat Blade UI.
7. Buat validation.
8. Buat test.
9. Jalankan test.
10. Perbaiki error.
11. Lanjut ke phase berikutnya.

Sebelum membuat fitur yang belum jelas, jangan mengarang business rule.

Jika ada requirement yang belum diketahui, gunakan struktur yang configurable.

Pertanyaan evaluasi jangan hardcode permanen di controller/Blade.

## 40. Final Goal

Hasil akhir adalah aplikasi web internal:

Laravel + Blade + Tailwind + MySQL

Alur utama:

ADMIN
↓
Import 4.000 tenaga kerja
↓
Buat 70 evaluator
↓
Assign tenaga kerja
↓
Evaluator login
↓
Melihat anggota masing-masing
↓
Mengisi evaluasi
↓
Submit
↓
Progress otomatis bertambah
↓
Admin melihat progress keseluruhan
↓
Admin melihat progress masing-masing evaluator
↓
Admin export hasil ke Excel
↓
Evaluator/Admin dapat print hasil evaluasi

Aplikasi harus production-ready, secure, responsive, maintainable, dan mudah dikembangkan.
