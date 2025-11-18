# 📅 Date Formatting Helpers

## Overview
Helper functions untuk menangani date formatting dengan aman, mencegah error `Call to a member function format() on string`.

## Functions

### `formatDate($date, $format = 'd/m/Y')`
Format tanggal dengan format custom.

**Parameters:**
- `$date` - Carbon instance, DateTime, string date, atau null
- `$format` - Format string (default: 'd/m/Y')

**Returns:** String formatted date atau '-' jika null

**Example:**
```blade
{{ formatDate($anggota->tanggal_lahir) }}
// Output: 15/08/1990

{{ formatDate($anggota->tanggal_lahir, 'Y-m-d') }}
// Output: 1990-08-15
```

---

### `formatDateTime($date)`
Format tanggal + waktu (shorthand untuk 'd/m/Y H:i').

**Parameters:**
- `$date` - Carbon instance, DateTime, string date, atau null

**Returns:** String formatted datetime atau '-' jika null

**Example:**
```blade
{{ formatDateTime($simpanan->created_at) }}
// Output: 15/08/2024 14:30
```

---

### `formatDateLong($date)`
Format tanggal panjang (shorthand untuk 'd F Y').

**Parameters:**
- `$date` - Carbon instance, DateTime, string date, atau null

**Returns:** String formatted date atau '-' jika null

**Example:**
```blade
{{ formatDateLong($anggota->tanggal_lahir) }}
// Output: 15 August 1990
```

---

### `formatDateTimeLong($date)`
Format tanggal + waktu panjang (shorthand untuk 'd F Y H:i').

**Parameters:**
- `$date` - Carbon instance, DateTime, string date, atau null

**Returns:** String formatted datetime atau '-' jika null

**Example:**
```blade
{{ formatDateTimeLong($anggota->approved_at) }}
// Output: 15 August 2024 14:30
```

---

## Why Use These Helpers?

### ❌ Before (Error-prone):
```blade
{{ $anggota->tanggal_lahir->format('d/m/Y') }}
// Error: Call to a member function format() on string

{{ $simpanan->created_at ? $simpanan->created_at->format('d F Y') : '-' }}
// Tedious null checking
```

### ✅ After (Safe):
```blade
{{ formatDate($anggota->tanggal_lahir) }}
// Works with Carbon, DateTime, string, or null

{{ formatDateLong($simpanan->created_at) }}
// Automatic null handling
```

---

## Features

✅ **Null-safe** - Returns '-' for null values  
✅ **Type-flexible** - Works with Carbon, DateTime, or string dates  
✅ **Exception-handled** - Returns '-' on parsing errors  
✅ **Clean syntax** - Short and readable  
✅ **Globally available** - No imports needed

---

## Migration Guide

### Old Pattern → New Pattern

```blade
// Pattern 1: Direct format call
{{ $date->format('d/m/Y') }}
↓
{{ formatDate($date) }}

// Pattern 2: Ternary with null check
{{ $date ? $date->format('d/m/Y') : '-' }}
↓
{{ formatDate($date) }}

// Pattern 3: Carbon parse for strings
{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
↓
{{ formatDate($date) }}

// Pattern 4: Complex type checking
{{ $date ? (is_string($date) ? \Carbon\Carbon::parse($date)->format('d/m/Y') : $date->format('d/m/Y')) : '-' }}
↓
{{ formatDate($date) }}
```

---

## Implementation Details

**Location:** `app/Helpers/helpers.php`  
**Autoload:** Registered in `composer.json` → `autoload.files`  
**Load time:** Application bootstrap

---

## Common Formats

```php
'd/m/Y'           // 15/08/2024
'd F Y'           // 15 August 2024
'd/m/Y H:i'       // 15/08/2024 14:30
'd F Y H:i'       // 15 August 2024 14:30
'Y-m-d'           // 2024-08-15
'l, d F Y'        // Thursday, 15 August 2024
'd M Y'           // 15 Aug 2024
```

---

## Testing

```bash
# Test with null
{{ formatDate(null) }}
// Output: -

# Test with string
{{ formatDate('2024-08-15') }}
// Output: 15/08/2024

# Test with Carbon
{{ formatDate(now()) }}
// Output: Current date

# Test with invalid string
{{ formatDate('invalid-date') }}
// Output: -
```

---

## Best Practices

1. ✅ **Always use helpers** for date display in views
2. ✅ **Use specific helpers** (formatDateTime, formatDateLong) for clarity
3. ✅ **Don't chain format()** directly on model attributes
4. ✅ **Let helpers handle null** instead of @if checks

---

## Updated Files

Views using new helpers:
- ✅ `resources/views/anggota/show.blade.php`
- ✅ `resources/views/simpanan/show.blade.php`
- ✅ `resources/views/simpanan/index.blade.php`
- ✅ `resources/views/simpanan/verify.blade.php`
- ✅ `resources/views/pengurus/approval/index.blade.php`
- ✅ `resources/views/dashboard/admin.blade.php`
- ✅ `resources/views/dashboard/bendahara.blade.php`
- ✅ `resources/views/dashboard/anggota.blade.php`

Views still need migration:
- ⏳ `resources/views/angsuran/*.blade.php`
- ⏳ `resources/views/pinjaman/*.blade.php`
- ⏳ `resources/views/kas/*.blade.php`
- ⏳ `resources/views/laporan/*.blade.php`

---

## Notes

- Helpers are loaded on every request via Composer autoload
- No performance impact (simple function calls)
- Works seamlessly with Blade templates
- Compatible with all Carbon/DateTime operations
