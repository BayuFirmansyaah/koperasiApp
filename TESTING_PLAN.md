# 🔍 TESTING PLAN - Koperasi App

**Tanggal:** 17 November 2025  
**Status:** In Progress

---

## ✅ Phase 1: Critical Routing Issues - COMPLETE

- [x] Fix route parameter `anggota` (anggotum → anggota)
- [x] Fix route parameter `simpanan` 
- [x] Fix route parameter `pinjaman`
- [x] Fix route parameter `angsuran`
- [x] Verify route:list output

---

## 🔄 Phase 2: Module Testing

### A. ANGGOTA Module (Priority: HIGH)

**Controller:** `AnggotaController`  
**Status:** ⚠️ Testing in Progress

#### Checklist:
- [ ] **Routes Testing**
  - [ ] GET /anggota (index) - List all members
  - [ ] GET /anggota/create (create) - Form create new member
  - [ ] POST /anggota (store) - Save new member
  - [ ] GET /anggota/{id} (show) - View member detail
  - [ ] GET /anggota/{id}/edit (edit) - Form edit member
  - [ ] PUT /anggota/{id} (update) - Update member
  - [ ] DELETE /anggota/{id} (destroy) - Delete member

- [ ] **Permissions Testing**
  - [ ] view-anggota (super-admin, pengurus, bendahara)
  - [ ] create-anggota (super-admin, pengurus)
  - [ ] update-anggota (super-admin, pengurus)
  - [ ] delete-anggota (super-admin)

- [ ] **Views Testing**
  - [ ] resources/views/anggota/index.blade.php
  - [ ] resources/views/anggota/create.blade.php
  - [ ] resources/views/anggota/edit.blade.php
  - [ ] resources/views/anggota/show.blade.php

- [ ] **Dependencies Check**
  - [ ] AnggotaService exists and works
  - [ ] User model relationship
  - [ ] File upload (foto, KTP, KK)
  - [ ] Status workflow (pending → active)

---

### B. SIMPANAN Module (Priority: HIGH)

**Controller:** `SimpananController`  
**Status:** ⏳ Pending

#### Checklist:
- [ ] **Routes Testing**
  - [ ] GET /simpanan (index) - List all savings
  - [ ] GET /simpanan/create (create) - Form new deposit
  - [ ] POST /simpanan (store) - Save deposit
  - [ ] GET /simpanan/{id} (show) - View deposit detail
  - [ ] GET /simpanan-verify - List pending deposits
  - [ ] POST /simpanan/{id}/verify - Verify deposit

- [ ] **Permissions Testing**
  - [ ] view-simpanan|view-own-simpanan
  - [ ] create-simpanan (all roles can deposit)
  - [ ] verify-simpanan (bendahara only)

- [ ] **Views Testing**
  - [ ] resources/views/simpanan/index.blade.php
  - [ ] resources/views/simpanan/create.blade.php
  - [ ] resources/views/simpanan/show.blade.php
  - [ ] resources/views/simpanan/verify.blade.php

- [ ] **Dependencies Check**
  - [ ] Anggota model relationship
  - [ ] JenisSimpanan model (sukarela, wajib, pokok)
  - [ ] Kas model integration (create kas entry on verify)
  - [ ] Polymorphic relationship with Kas

- [ ] **Business Logic**
  - [ ] Status flow: pending → verified → rejected
  - [ ] Kas entry creation on verification
  - [ ] Balance calculation

---

### C. PINJAMAN Module (Priority: HIGH)

**Controller:** `PinjamanController`  
**Status:** ⏳ Pending

#### Checklist:
- [ ] **Routes Testing**
  - [ ] GET /pinjaman (index) - List all loans
  - [ ] GET /pinjaman/create (create) - Apply for loan
  - [ ] POST /pinjaman (store) - Save loan application
  - [ ] GET /pinjaman/{id} (show) - View loan detail
  - [ ] GET /pinjaman/review - List loans for review
  - [ ] POST /pinjaman/{id}/review - Review loan (pengurus)
  - [ ] GET /pinjaman/approve - List loans for approval
  - [ ] POST /pinjaman/{id}/approve - Approve loan (super-admin)
  - [ ] GET /pinjaman/disburse - List loans for disbursement
  - [ ] POST /pinjaman/{id}/disburse - Disburse loan (bendahara)

- [ ] **Permissions Testing**
  - [ ] view-pinjaman|view-own-pinjaman
  - [ ] create-pinjaman (anggota)
  - [ ] review-pinjaman (pengurus)
  - [ ] approve-pinjaman (super-admin)
  - [ ] disburse-pinjaman (bendahara)

- [ ] **Views Testing**
  - [ ] resources/views/pinjaman/index.blade.php
  - [ ] resources/views/pinjaman/create.blade.php
  - [ ] resources/views/pinjaman/show.blade.php
  - [ ] resources/views/pinjaman/review.blade.php
  - [ ] resources/views/pinjaman/approve.blade.php
  - [ ] resources/views/pinjaman/disburse.blade.php

- [ ] **Dependencies Check**
  - [ ] Anggota model relationship
  - [ ] Angsuran model (auto-generate installments)
  - [ ] Kas model integration (create kas entry on disburse)
  - [ ] reviewedBy, approvedBy, disbursedBy relationships

- [ ] **Business Logic**
  - [ ] Status flow: pending → direview → disetujui → dicairkan → aktif
  - [ ] Auto-generate angsuran schedule
  - [ ] Interest calculation
  - [ ] Loan eligibility check

---

### D. ANGSURAN Module (Priority: MEDIUM)

**Controller:** `AngsuranController`  
**Status:** ⏳ Pending

#### Checklist:
- [ ] **Routes Testing**
  - [ ] GET /angsuran (index) - List all installments
  - [ ] GET /angsuran/create (create) - Form pay installment
  - [ ] POST /angsuran (store) - Save payment
  - [ ] GET /angsuran/{id} (show) - View installment detail
  - [ ] GET /angsuran-verify - List pending payments
  - [ ] POST /angsuran/{id}/verify - Verify payment

- [ ] **Permissions Testing**
  - [ ] view-angsuran|view-own-angsuran
  - [ ] create-angsuran (anggota)
  - [ ] verify-angsuran (bendahara)

- [ ] **Views Testing**
  - [ ] resources/views/angsuran/index.blade.php
  - [ ] resources/views/angsuran/create.blade.php
  - [ ] resources/views/angsuran/show.blade.php
  - [ ] resources/views/angsuran/verify.blade.php

- [ ] **Dependencies Check**
  - [ ] Pinjaman model relationship
  - [ ] Kas model integration
  - [ ] verifiedBy relationship

- [ ] **Business Logic**
  - [ ] Status flow: pending → verified → late
  - [ ] Late payment detection
  - [ ] Remaining balance calculation
  - [ ] Update pinjaman status to 'lunas' when complete

---

### E. KAS Module (Priority: MEDIUM)

**Controller:** `KasController`  
**Status:** ✅ Fixed (relationships)

#### Checklist:
- [ ] **Routes Testing**
  - [ ] GET /kas (index) - List all transactions
  - [ ] GET /kas/create (create) - Form new transaction
  - [ ] POST /kas (store) - Save transaction
  - [ ] GET /kas/{id} (show) - View transaction detail
  - [ ] GET /kas-laporan - Generate report

- [ ] **Permissions Testing**
  - [ ] view-kas (super-admin, bendahara)
  - [ ] create-kas (super-admin, bendahara)

- [ ] **Views Testing**
  - [ ] resources/views/kas/index.blade.php
  - [ ] resources/views/kas/create.blade.php
  - [ ] resources/views/kas/show.blade.php
  - [ ] resources/views/kas/laporan.blade.php

- [ ] **Dependencies Check**
  - [x] createdBy relationship (fixed)
  - [x] transactable/referensi polymorphic relationship (fixed)
  - [ ] Balance calculation logic
  - [ ] Transaction types (masuk/keluar)

- [ ] **Business Logic**
  - [ ] Running balance calculation
  - [ ] Automatic entries from simpanan/pinjaman/angsuran
  - [ ] Manual transaction entry
  - [ ] Report generation by date range

---

### F. LAPORAN Module (Priority: LOW)

**Controller:** `LaporanController`  
**Status:** ⏳ Pending

#### Checklist:
- [ ] **Routes Testing**
  - [ ] GET /laporan/keuangan - Financial report
  - [ ] GET /laporan/anggota - Members report
  - [ ] GET /laporan/rekening-koran/{id} - Member account statement

- [ ] **Permissions Testing**
  - [ ] view-laporan-global (super-admin, bendahara, pengurus)
  - [ ] view-laporan-own (anggota - only their own)

- [ ] **Views Testing**
  - [ ] resources/views/laporan/keuangan.blade.php
  - [ ] resources/views/laporan/anggota.blade.php
  - [ ] resources/views/laporan/rekening-koran.blade.php

- [ ] **Dependencies Check**
  - [ ] All models (Anggota, Simpanan, Pinjaman, Angsuran, Kas)
  - [ ] Authorization checks

- [ ] **Business Logic**
  - [ ] Date range filtering
  - [ ] Export to PDF/Excel (future)
  - [ ] Summary calculations

---

### G. PENGURUS Module (Priority: LOW)

**Controller:** `ApprovalController`  
**Status:** ⏳ Pending

#### Checklist:
- [ ] **Routes Testing**
  - [ ] GET /pengurus/approval (index) - List pending approvals
  - [ ] POST /pengurus/approval/{id}/approve - Approve member
  - [ ] POST /pengurus/approval/{id}/reject - Reject member

- [ ] **Views Testing**
  - [ ] resources/views/pengurus/approval/index.blade.php

- [ ] **Dependencies Check**
  - [ ] Anggota model
  - [ ] Status update logic

---

## 🔍 Phase 3: Common Issues Check

### Database & Models
- [ ] All relationships defined correctly
- [ ] Nullable fields have null safety operators (?->)
- [ ] Date casting correct (datetime vs date)
- [ ] Fillable/guarded attributes set properly

### Permissions
- [ ] All permissions seeded in database
- [ ] Permission names consistent across:
  - [ ] Controllers (middleware)
  - [ ] Views (@can directives)
  - [ ] Database seeders

### Views
- [ ] All route() helpers have correct parameters
- [ ] Form method spoofing for PUT/DELETE
- [ ] CSRF tokens in all forms
- [ ] Error message displays
- [ ] Success message displays
- [ ] Null safety for date fields
- [ ] Null safety for relationship fields

### User Experience
- [ ] Flash messages work (success/error)
- [ ] Form validation displays errors
- [ ] Back buttons work
- [ ] Pagination works
- [ ] Search/filter works
- [ ] Role-based UI visibility (@can)

---

## 📊 Testing Progress

**Total Modules:** 7  
**Completed:** 0  
**In Progress:** 1 (Anggota)  
**Pending:** 6

**Critical Issues Fixed:**
- ✅ Route parameters (anggotum → anggota, etc)
- ✅ Kas model relationships (user → createdBy)
- ✅ Middleware registration (Laravel 11 syntax)
- ✅ Permission middleware aliases
- ✅ Spatie Permission service provider

---

## 🐛 Known Issues

1. ~~Route parameter mismatch (anggotum)~~ - **FIXED**
2. ~~Kas model missing 'user' relationship~~ - **FIXED**
3. ~~Middleware syntax outdated~~ - **FIXED**
4. ~~Permission middleware not registered~~ - **FIXED**
5. ~~Date format errors on string dates~~ - **FIXED** (Created global helpers)

---

## 🛠️ Recent Improvements

### Date Formatting Helpers (2025-11-17)
Created global helper functions to handle date formatting safely:
- `formatDate($date, $format = 'd/m/Y')` - Custom format
- `formatDateTime($date)` - Short datetime (d/m/Y H:i)
- `formatDateLong($date)` - Long date (d F Y)
- `formatDateTimeLong($date)` - Long datetime (d F Y H:i)

**Benefits:**
- ✅ Null-safe (returns '-' for null)
- ✅ Type-flexible (handles Carbon, DateTime, string)
- ✅ Exception-handled
- ✅ Clean syntax in views

**Location:** `app/Helpers/helpers.php`  
**Documentation:** `DATE_HELPERS.md`

**Updated Views:**
- anggota/show.blade.php
- simpanan/show.blade.php, index.blade.php, verify.blade.php
- pengurus/approval/index.blade.php
- dashboard/admin.blade.php, bendahara.blade.php, anggota.blade.php

---

## 📝 Notes

- Test with different user roles (super-admin, pengurus, bendahara, anggota)
- Check both success and error scenarios
- Verify database integrity after operations
- Check cascade deletes work properly
- Test file uploads with various formats

---

## 🎯 Next Steps

1. **Immediate:** Test Anggota module completely
2. **Next:** Test Simpanan module (financial impact)
3. **Then:** Test Pinjaman & Angsuran (complex workflows)
4. **Finally:** Test Kas & Laporan (reporting)

---

**Last Updated:** 2025-11-17 by GitHub Copilot
