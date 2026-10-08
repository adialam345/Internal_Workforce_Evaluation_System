# DESIGN SPECIFICATION (Anti-Slop Standard)
## Sistem Evaluasi Tenaga Kerja Internal (Workforce Evaluation System)

### 1. Design Read & Dials
- **Product Kind:** Sistem Evaluasi & Monitoring Kinerja Tenaga Kerja Internal Perusahaan.
- **Audience:** HR Admin & 70+ Evaluator / Verifikator Departemen mengelola 4.000+ Tenaga Kerja.
- **Visual Tone:** Professional, Enterprise-grade, High Information-Density, Clear Status Differentiation, Zero Clutter.
- **Dials:**
  - **ENERGY:** 1 (Calm & Focused utility - tidak ada elemen gimmick atau flashy)
  - **RHYTHM:** 2 (Struktur konsisten dengan variasi hierarki yang fungsional antara dashboard ringkasan metrik, tabel data besar, dan formulir evaluasi bertahap)
  - **MOTION:** 1 (Transisi instan & snappy pada modal/tab/dropdown, zero unnecessary scrolling animation)

---

### 2. Color Palette (Purpose-Tested)
- **Base Background:** `#f8fafc` (Slate 50) untuk area kerja lembut di mata pada penggunaan lama, `#ffffff` untuk container kartu/tabel.
- **Surface & Borders:** `#e2e8f0` (Slate 200) dan `#cbd5e1` (Slate 300) untuk pembatas kolom data.
- **Primary Text:** `#0f172a` (Slate 900) - Memenuhi WCAG AAA contrast ratio > 12:1.
- **Secondary / Meta Text:** `#475569` (Slate 600) - Memenuhi WCAG AA contrast ratio > 6:1.
- **Brand Primary:** `#1e40af` (Blue 800) / `#2563eb` (Blue 600) untuk aksi primer (Simpan, Export, Login).
- **Status Semantics (Hanya untuk indikator data riil):**
  - *Submitted / Selesai:* `#047857` (Emerald 700) dengan background `#ecfdf5` (Emerald 50).
  - *Draft / Dalam Proses:* `#b45309` (Amber 700) dengan background `#fffbeb` (Amber 50).
  - *Belum Evaluasi / Belum Assign:* `#475569` (Slate 600) dengan background `#f1f5f9` (Slate 100).
  - *Danger / Nonaktif / Unlock:* `#b91c1c` (Red 700) dengan background `#fef2f2` (Red 50).

---

### 3. Typography & Spacing
- **Font Family:** System UI Stack / Inter (`ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`).
- **Hierarchy:**
  - Heading 1 (Page Title): `text-xl font-bold tracking-tight text-slate-900`
  - Section Title: `text-base font-semibold text-slate-900`
  - Table / Body Text: `text-sm font-normal text-slate-800`
  - Metadata / Table Header: `text-xs font-semibold text-slate-500 uppercase tracking-wider`
- **Border Radius:** `rounded-lg` (8px) untuk card & container; `rounded-md` (6px) untuk tombol & input. Hindari bentuk pill/kapsul berlebihan.

---

### 4. Layout Architecture
- **Desktop:** Fixed Sidebar Navigasi Kiri + Clean Top Header (User info, Role badge, Quick stats) + Dynamic Main Workspace.
- **Mobile / Responsive:** Sidebar Drawer dengan Backdrop, Table horizontal-scrolling terlindungi (`overflow-x-auto`) atau Card-based stack untuk layar kecil.
- **Print Friendly:** Dedicated `@media print` styling yang menghilangkan navbar, tombol aksi, filter, dan hanya menyisakan lembar berita acara evaluasi dan kolom tanda tangan resmi.

---

### 5. Craftsmanship & Anti-Slop Safeguards
1. **Zero Dead Buttons:** Setiap tombol memiliki aksi pasti (Submit form, Navigasi ke URL valid, Buka modal dialog, atau Trigger Print).
2. **Comprehensive UI States:** Setiap tabel dan filter menyediakan state `Loading`, `Empty State` informatif (misal: "Belum ada tenaga kerja yang ditugaskan"), dan `Error Alert` yang jelas.
3. **No Fake / Stock Data:** Menampilkan angka riil agregasi database `COUNT()` dan kalkulasi persentase langsung.
4. **Accessible Forms & Keyboards:** Focus ring yang jelas (`focus:ring-2 focus:ring-blue-500 focus:outline-none`), navigasi Tab logis, dialog modal dapat ditutup dengan tombol `Escape`.
