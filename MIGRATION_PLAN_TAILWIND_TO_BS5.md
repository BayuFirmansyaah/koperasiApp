# PLAN MIGRASI TAILWIND CSS KE BOOTSTRAP 5
**Project:** Koperasi App  
**Tanggal:** November 17, 2025  
**Status:** Rencana Implementasi

---

## 1. AUDIT SAAT INI

### Dependencies Saat Ini:
```
✓ Tailwind CSS 3.1.0 (@tailwindcss/forms, @tailwindcss/vite)
✓ Bootstrap Tabler 1.4.0 (sudah di-import, tapi tidak konsisten)
✓ Tailwind PostCSS & Autoprefixer
```

### Penggunaan di Project:
- **Tabler Classes**: Sudah digunakan di layout utama (`navbar`, `sidebar`, `page-wrapper`, dll)
- **Tailwind Classes**: Ada di beberapa view (dashboard, profile, forms)
- **Inline Styles**: Campuran class dan inline style
- **Custom CSS**: Sidebar custom styling + Bootstrap

### Status Kompatibilitas:
⚠️ **Mixed Framework** - Kombinasi Tabler + Tailwind yang tidak konsisten

---

## 2. STRATEGI MIGRASI (3 TAHAP)

### TAHAP 1: SETUP BOOTSTRAP 5 (30 menit)
**Goal:** Siapkan foundation, jangan hapus Tailwind dulu

#### 1.1 Install Bootstrap 5 & Dependencies
```bash
npm install bootstrap bootstrap-icons popper.js
npm remove @tailwindcss/forms @tailwindcss/vite
```

#### 1.2 Update CSS Structure
- Ubah `resources/css/app.css`:
  ```css
  @import '@tabler/core/dist/css/tabler.min.css';  /* Keep Tabler */
  @import 'bootstrap/dist/css/bootstrap.css';      /* Add Bootstrap 5 */
  @import '@tailwind base';  /* Keep Tailwind temporary */
  @import '@tailwind components';
  @import '@tailwind utilities';
  ```

#### 1.3 Update `postcss.config.js`
```javascript
export default {
    plugins: {
        'postcss-import': {},
        'tailwindcss/nesting': 'postcss-nesting',
        tailwindcss: {},
        autoprefixer: {},
    },
}
```

#### 1.4 Update `vite.config.js`
- Pastikan `laravel-vite-plugin` bekerja dengan Bootstrap
- Remove `@tailwindcss/vite` plugin

---

### TAHAP 2: MIGRASI COMPONENT (2-3 jam)
**Goal:** Convert komponen Blade ke Bootstrap 5

#### 2.1 Priority List Komponen:

| Priority | Component | Tailwind Classes | Bootstrap 5 Equivalents |
|----------|-----------|------------------|------------------------|
| **P1** | Forms (text-input, input-label, input-error) | `block w-full mt-1` | `form-control`, `form-group`, `invalid-feedback` |
| **P1** | Buttons | `px-4 py-2 font-semibold` | `btn btn-primary`, `btn-sm` |
| **P2** | Modal | `fixed inset-0 space-y-6` | `modal`, `modal-dialog`, `modal-content` |
| **P2** | Dropdown | `flex items-center gap-4` | `dropdown-menu`, `dropdown-item` |
| **P3** | Profile/Auth Views | Inline styles campur Tailwind | `card`, `card-body` |

#### 2.2 Komponen Blade yang Perlu Update:

**`resources/views/components/text-input.blade.php`**
```blade
<!-- DARI: -->
<input {{ $attributes->merge(['class' => 'block w-full px-4 py-2 mt-1']) }} />

<!-- KE: -->
<input {{ $attributes->merge(['class' => 'form-control mt-2']) }} />
```

**`resources/views/components/input-label.blade.php`**
```blade
<!-- DARI: -->
<label class="block font-medium text-gray-700">
<!-- KE: -->
<label class="form-label">
```

**`resources/views/components/input-error.blade.php`**
```blade
<!-- DARI: -->
<div class="text-red-600 text-sm mt-2">
<!-- KE: -->
<div class="invalid-feedback d-block">
```

**`resources/views/components/primary-button.blade.php`**
```blade
<!-- DARI: -->
<button class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md">
<!-- KE: -->
<button class="btn btn-primary">
```

#### 2.3 Convert Tailwind Utilities → Bootstrap Classes:

```
Tailwind          → Bootstrap 5
---
flex              → d-flex
justify-center    → justify-content-center
items-center      → align-items-center
gap-4             → gap-3 (spacing)
mt-1              → mt-2
px-4              → px-3
py-2              → py-2
w-full            → w-100
bg-white          → bg-white
text-gray-900     → text-dark
rounded-lg        → rounded
shadow-sm         → shadow-sm
border-gray-300   → border-secondary
```

---

### TAHAP 3: CLEANUP & OPTIMASI (1-2 jam)
**Goal:** Remove Tailwind, finalisasi Bootstrap

#### 3.1 Remove Tailwind
```bash
npm uninstall tailwindcss postcss autoprefixer
rm tailwind.config.js postcss.config.js
```

#### 3.2 Update CSS Structure Akhir
```css
@import '@tabler/core/dist/css/tabler.min.css';
@import 'bootstrap/dist/css/bootstrap.css';
@import 'bootstrap-icons/font/bootstrap-icons.css';

/* Custom Styles */
:root {
    --primary-color: #206bc4;
    --secondary-color: #206bc4;
    --success-color: #2fb344;
    --danger-color: #d63939;
    --warning-color: #f76707;
}

/* Custom component styles */
```

#### 3.3 Update `package.json` Final
```json
{
    "devDependencies": {
        "laravel-vite-plugin": "^2.0.0",
        "vite": "^7.0.7"
    },
    "dependencies": {
        "bootstrap": "^5.3.0",
        "bootstrap-icons": "^1.11.0",
        "@tabler/core": "^1.4.0",
        "@tabler/icons": "^3.35.0"
    }
}
```

---

## 3. FILE-FILE YANG PERLU DIUBAH (Prioritas)

### PRIORITY 1: Components (Critical)
```
✓ resources/views/components/text-input.blade.php
✓ resources/views/components/input-label.blade.php
✓ resources/views/components/input-error.blade.php
✓ resources/views/components/primary-button.blade.php
✓ resources/views/components/secondary-button.blade.php
✓ resources/views/components/danger-button.blade.php
✓ resources/views/components/modal.blade.php
✓ resources/views/components/dropdown.blade.php
```

### PRIORITY 2: Layouts
```
✓ resources/views/layouts/app.blade.php
✓ resources/views/layouts/guest.blade.php
✓ resources/views/layouts/navigation.blade.php
```

### PRIORITY 3: Views
```
✓ resources/views/dashboard.blade.php
✓ resources/views/profile/partials/*.blade.php
✓ resources/views/auth/*.blade.php
✓ resources/views/*/index.blade.php (List pages)
```

---

## 4. MAPPING TAILWIND → BOOTSTRAP 5

### Spacing
```
mt-1  → mt-2     mt-2  → mt-3     mt-4  → mt-4     mt-6  → mt-4
px-4  → px-3     py-2  → py-2     py-6  → py-4
```

### Display & Alignment
```
flex                    → d-flex
flex-row                → flex-row (default)
flex-col                → flex-column
justify-between         → justify-content-between
justify-center          → justify-content-center
items-center            → align-items-center
items-start             → align-items-start
gap-4                   → gap-3 (g-3)
```

### Colors
```
bg-gray-800             → bg-dark
bg-gray-100             → bg-light
text-gray-900           → text-dark
text-gray-500           → text-secondary
text-red-600            → text-danger
text-green-600          → text-success
border-gray-300         → border-secondary
```

### Typography
```
text-lg                 → fs-5
text-sm                 → fs-6
font-semibold           → fw-bold
font-medium             → fw-500
text-center             → text-center
text-truncate           → text-truncate
```

### Borders & Shadows
```
rounded-lg              → rounded
rounded-full            → rounded-pill
shadow-sm               → shadow-sm
border                  → border
```

### Forms
```
block w-full mt-1       → form-control mt-2
text-gray-700           → form-label (for labels)
```

---

## 5. TESTING CHECKLIST

### Unit Testing
- [ ] Semua components render dengan benar
- [ ] Styling responsive di desktop & mobile
- [ ] Form validation messages muncul
- [ ] Buttons & links berfungsi
- [ ] Modals & dropdowns bekerja

### Visual Testing
- [ ] Colors konsisten
- [ ] Spacing sesuai design
- [ ] Sidebar & navbar terlihat baik
- [ ] Tables rapi
- [ ] Cards & alerts sesuai

### Browser Testing
- [ ] Chrome/Edge
- [ ] Firefox
- [ ] Safari
- [ ] Mobile (iOS/Android)

---

## 6. ROLLBACK PLAN

Jika ada issue:
```bash
# Revert ke state sebelumnya
git revert HEAD
npm install
npm run build
```

---

## 7. TIMELINE ESTIMATE

| Fase | Waktu | Status |
|------|-------|--------|
| Setup Bootstrap 5 | 30 min | ⏳ |
| Migrasi Components | 2 jam | ⏳ |
| Update Layouts & Views | 1 jam | ⏳ |
| Testing & Bug Fix | 1-2 jam | ⏳ |
| **Total** | **4.5-5 jam** | ⏳ |

---

## 8. QUICK REFERENCE TABLE

```
FRAMEWORK     | UTILITY     | TAILWIND        | BOOTSTRAP 5
============================================================
Layout        | Flexbox     | flex, items-*   | d-flex, align-items-*
              | Grid        | grid            | row, col-*
              | Position    | absolute, fixed | position-absolute/fixed
---
Spacing       | Margin      | m-*, mt-*, etc  | m-*, mt-*, etc
              | Padding     | p-*, pt-*, etc  | p-*, pt-*, etc
---
Typography   | Font Size   | text-*          | fs-1 to fs-6
              | Weight      | font-*          | fw-light/normal/bold
              | Color       | text-*-600      | text-dark/danger/etc
---
Colors        | Background  | bg-*-600        | bg-primary/danger/etc
              | Border      | border-*-300    | border-primary/secondary
              | Text        | text-*-900      | text-dark/secondary
---
Components    | Forms       | block w-full    | form-control
              | Button      | px-4 py-2 font  | btn btn-primary
              | Alert       | bg-red-100      | alert alert-danger
              | Card        | border rounded  | card
```

---

## 9. NEXT STEPS

1. **Approve Plan ini** ✓
2. **Mulai TAHAP 1** (Setup)
3. **Review Bootstrap struktur**
4. **Lanjut TAHAP 2** (Components)
5. **Test setiap perubahan**
6. **Deploy & Monitor**

---

**Created by:** GitHub Copilot  
**Last Updated:** 2025-11-17
