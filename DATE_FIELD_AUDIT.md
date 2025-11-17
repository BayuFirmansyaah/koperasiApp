# 🔍 DATE FIELD AUDIT & FIX PLAN

**Generated:** 2025-11-17  
**Purpose:** Map database date fields vs view usage to fix missing/incorrect data

---

## 📊 DATABASE SCHEMA - Date Fields

### 1. **anggotas** table
```php
$table->date('tanggal_lahir');              // ✅ Date only
$table->timestamp('approved_at')->nullable(); // ✅ Timestamp
$table->timestamps();                        // created_at, updated_at
```

### 2. **simpanans** table
```php
$table->date('tanggal_transaksi');          // ⚠️ DB: tanggal_transaksi
$table->timestamp('verified_at')->nullable();
$table->timestamps();
```

### 3. **pinjamans** table
```php
$table->date('tanggal_pengajuan');          // ✅ Date only
$table->date('tanggal_approve')->nullable();
$table->date('tanggal_pencairan')->nullable();
$table->timestamps();
```

### 4. **angsurans** table
```php
$table->date('tanggal_jatuh_tempo');        // ✅ Date only
$table->date('tanggal_bayar')->nullable();
$table->timestamps();
```

### 5. **kas** table
```php
$table->date('tanggal_transaksi');          // ✅ Date only (already fixed)
$table->timestamps();
```

---

## 🔴 CRITICAL ISSUES FOUND

### Issue #1: **simpanans** table
- **DB Column:** `tanggal_transaksi`
- **Views using wrong name:** `tanggal_simpanan` ❌
- **Impact:** HIGH - All simpanan views show empty dates

**Affected Files:**
- ✅ `resources/views/anggota/show.blade.php` (line ~155) - **FIXED**
- ❌ `resources/views/simpanan/show.blade.php` - **NEEDS FIX**
- ❌ `resources/views/simpanan/index.blade.php` - **NEEDS FIX**
- ❌ `resources/views/simpanan/verify.blade.php` - **NEEDS FIX**
- ❌ `resources/views/simpanan/create.blade.php` - **NEEDS CHECK**

### Issue #2: **pinjamans** status field
- **Views using:** `status == 'berjalan'`
- **DB has:** `'pending', 'approved_pengurus', 'approved_bendahara', 'dicairkan', 'berjalan', 'lunas', 'ditolak'`
- **Status:** ✅ OK

### Issue #3: **Model $casts** configuration
Need to verify all models have proper date casting:
- ❌ Anggota model casts
- ❌ Simpanan model casts
- ❌ Pinjaman model casts
- ❌ Angsuran model casts

---

## 📋 AUDIT PLAN

### **Phase 1: Model Verification** (Priority: CRITICAL)

#### Step 1.1: Check Anggota Model
- [ ] Verify `tanggal_lahir` cast as 'date'
- [ ] Verify `approved_at` cast as 'datetime'
- [ ] Verify `created_at`, `updated_at` cast

#### Step 1.2: Check Simpanan Model
- [ ] Verify `tanggal_transaksi` (NOT tanggal_simpanan!) cast as 'date'
- [ ] Verify `verified_at` cast as 'datetime'
- [ ] Fix any views using wrong field name

#### Step 1.3: Check Pinjaman Model
- [ ] Verify `tanggal_pengajuan` cast as 'date'
- [ ] Verify `tanggal_approve` cast as 'date'
- [ ] Verify `tanggal_pencairan` cast as 'date'

#### Step 1.4: Check Angsuran Model
- [ ] Verify `tanggal_jatuh_tempo` cast as 'date'
- [ ] Verify `tanggal_bayar` cast as 'date'

---

### **Phase 2: View Audit** (Priority: HIGH)

#### A. ANGGOTA Views
**File:** `resources/views/anggota/show.blade.php`
- [x] Line ~85: `tanggal_lahir` - ✅ FIXED (using formatDateLong)
- [x] Line ~95: `created_at` - ✅ FIXED
- [x] Line ~100: `approved_at` - ✅ FIXED
- [x] Line ~155: `simpanan->tanggal_transaksi` - ✅ FIXED (was using wrong `tanggal`)
- [x] Line ~185: `pinjaman->tanggal_pengajuan` - ✅ FIXED

**File:** `resources/views/anggota/index.blade.php`
- [ ] Check if uses any date fields
- [ ] Verify column names match DB

**File:** `resources/views/anggota/create.blade.php` / `edit.blade.php`
- [ ] Input name="tanggal_lahir" matches DB column
- [ ] Date picker configuration

---

#### B. SIMPANAN Views ⚠️ **HIGH PRIORITY**
**File:** `resources/views/simpanan/show.blade.php`
- [ ] Line ~41: Change `tanggal_simpanan` → `tanggal_transaksi`
- [ ] Line ~104: `verified_at` - Check usage
- [ ] Line ~148: `created_at` - Check usage

**File:** `resources/views/simpanan/index.blade.php`
- [ ] Change `tanggal_simpanan` → `tanggal_transaksi`
- [ ] Check search/filter by date

**File:** `resources/views/simpanan/verify.blade.php`
- [ ] Change `tanggal_simpanan` → `tanggal_transaksi`

**File:** `resources/views/simpanan/create.blade.php`
- [ ] Input name should be `tanggal_transaksi`
- [ ] NOT `tanggal_simpanan`

---

#### C. PINJAMAN Views
**File:** `resources/views/pinjaman/show.blade.php`
- [ ] `tanggal_pengajuan` usage
- [ ] `tanggal_approve` usage
- [ ] `tanggal_pencairan` usage

**File:** `resources/views/pinjaman/index.blade.php`
- [ ] Date column displays
- [ ] Filter by date

**File:** `resources/views/pinjaman/create.blade.php`
- [ ] Input name="tanggal_pengajuan"

**File:** `resources/views/pinjaman/review.blade.php`
- [ ] Date displays

**File:** `resources/views/pinjaman/approve.blade.php`
- [ ] Date displays

**File:** `resources/views/pinjaman/disburse.blade.php`
- [ ] Date displays

---

#### D. ANGSURAN Views
**File:** `resources/views/angsuran/show.blade.php`
- [ ] Line ~41: `tanggal_jatuh_tempo` using Carbon::parse (why?)
- [ ] Line ~69: `tanggal_bayar` using Carbon::parse (why?)
- [ ] Should use formatDate() helper instead

**File:** `resources/views/angsuran/index.blade.php`
- [ ] Line ~44: Using Carbon::parse for `tanggal_jatuh_tempo`
- [ ] Change to formatDate() helper

**File:** `resources/views/angsuran/verify.blade.php`
- [ ] Line ~37: Using Carbon::parse for `tanggal_bayar`
- [ ] Change to formatDate() helper

**File:** `resources/views/angsuran/create.blade.php`
- [ ] Input names match DB columns

---

#### E. KAS Views
**File:** `resources/views/kas/show.blade.php`
- [ ] Line ~39: Using Carbon::parse for `tanggal_transaksi`
- [ ] Change to formatDate() helper

**File:** `resources/views/kas/index.blade.php`
- [ ] Line ~20: Using Carbon::parse
- [ ] Line ~59: Using Carbon::parse
- [ ] Change to formatDate() helper

**File:** `resources/views/kas/laporan.blade.php`
- [ ] Line ~76: Using Carbon::parse
- [ ] Change to formatDate() helper

**File:** `resources/views/kas/create.blade.php`
- [ ] Input name="tanggal_transaksi" (should be correct)

---

#### F. LAPORAN Views
**File:** `resources/views/laporan/rekening-koran.blade.php`
- [ ] Multiple Carbon::parse usage
- [ ] Standardize with formatDate() helper

**File:** `resources/views/laporan/keuangan.blade.php`
- [ ] Check date usage

**File:** `resources/views/laporan/anggota.blade.php`
- [ ] Check date usage

---

### **Phase 3: Controller Verification**

#### Check Query Selects
- [ ] AnggotaController: Check column names in queries
- [ ] SimpananController: Verify using `tanggal_transaksi` NOT `tanggal_simpanan`
- [ ] PinjamanController: Verify date columns
- [ ] AngsuranController: Verify date columns
- [ ] KasController: Already fixed

#### Check Form Requests/Validation
- [ ] AnggotaRequest: Verify field names
- [ ] Simpanan store/update: Verify `tanggal_transaksi`
- [ ] Pinjaman store/update: Verify date fields
- [ ] Angsuran store/update: Verify date fields

---

### **Phase 4: Database Seeders**

#### Check Seeder Field Names
- [ ] AnggotaSeeder: Using correct `tanggal_lahir`
- [ ] SimpananSeeder: Should use `tanggal_transaksi` NOT `tanggal_simpanan`
- [ ] PinjamanSeeder: Verify date fields
- [ ] AngsuranSeeder: Verify date fields
- [ ] KasSeeder: Already using `tanggal_transaksi`

---

## 🎯 EXECUTION ORDER

### **IMMEDIATE (Phase 1):**
1. ✅ Check all Model $casts
2. ✅ Fix Simpanan model if needed
3. ✅ Check Simpanan seeder

### **HIGH PRIORITY (Phase 2A):**
4. ⚠️ Fix all SIMPANAN views (`tanggal_simpanan` → `tanggal_transaksi`)
5. ⚠️ Fix Simpanan controller queries
6. ⚠️ Fix Simpanan form validation

### **MEDIUM PRIORITY (Phase 2B):**
7. Replace all `Carbon::parse()` with `formatDate()` helper in:
   - Angsuran views
   - Kas views
   - Laporan views

### **LOW PRIORITY (Phase 3):**
8. Standardize all form inputs
9. Add date validation rules
10. Update documentation

---

## 🔧 FIX PATTERNS

### Pattern 1: Wrong Column Name
```blade
<!-- ❌ WRONG -->
{{ formatDate($simpanan->tanggal_simpanan) }}

<!-- ✅ CORRECT -->
{{ formatDate($simpanan->tanggal_transaksi) }}
```

### Pattern 2: Unnecessary Carbon::parse
```blade
<!-- ❌ WRONG -->
{{ \Carbon\Carbon::parse($angsuran->tanggal_jatuh_tempo)->format('d/m/Y') }}

<!-- ✅ CORRECT -->
{{ formatDate($angsuran->tanggal_jatuh_tempo) }}
```

### Pattern 3: Model $casts
```php
// ✅ CORRECT
protected $casts = [
    'tanggal_transaksi' => 'date',
    'verified_at' => 'datetime',
];
```

### Pattern 4: Form Input Names
```blade
<!-- ✅ CORRECT -->
<input type="date" name="tanggal_transaksi" value="{{ old('tanggal_transaksi') }}">
```

---

## 📊 TRACKING

**Total Issues Found:** TBD  
**Critical Issues:** 1 (Simpanan field name)  
**High Priority:** ~15 files need Carbon::parse → formatDate()  
**Medium Priority:** Model casts verification  
**Low Priority:** Standardization

**Estimated Time:**
- Phase 1: 30 minutes
- Phase 2A: 1 hour
- Phase 2B: 1 hour
- Phase 3: 30 minutes

**Total:** ~3 hours

---

## ✅ COMPLETION CHECKLIST

- [ ] All models have correct $casts
- [ ] All views use correct DB column names
- [ ] All views use formatDate() helper (no Carbon::parse)
- [ ] All forms use correct input names
- [ ] All controllers query correct columns
- [ ] All seeders use correct column names
- [ ] Test with fresh migration + seed
- [ ] Verify all dates display correctly
- [ ] No more "empty date" issues

---

**Next Action:** Start Phase 1 - Check all Models
