# ACTION PLAN: TAILWIND → BOOTSTRAP 5 MIGRATION
**Step-by-step execution guide**

---

## FASE 1: SETUP & PREPARATION (30 menit)

### Step 1.1: Backup Project
```bash
# Create backup branch
git checkout -b feature/tailwind-to-bootstrap5-migration
git add .
git commit -m "Backup before Bootstrap 5 migration"
```

### Step 1.2: Install Dependencies
```bash
# Install Bootstrap & Dependencies
npm install bootstrap bootstrap-icons popper.js

# Keep Tailwind for now (gradual migration)
# Don't remove yet - we'll do this after testing
```

### Step 1.3: Update CSS Import Order
**File:** `resources/css/app.css`

```css
/* Priority order matters! */
@import '@tabler/core/dist/css/tabler.min.css';      /* Tabler components */
@import 'bootstrap/dist/css/bootstrap.css';           /* Bootstrap 5 base */
@import 'bootstrap-icons/font/bootstrap-icons.css';   /* Bootstrap Icons */

@tailwind base;                                       /* Tailwind (temporary) */
@tailwind components;
@tailwind utilities;

/* Custom Styles */
:root {
    --primary-color: #206bc4;
    --secondary-color: #206bc4;
    --success-color: #2fb344;
    --danger-color: #d63939;
    --warning-color: #f76707;
    --info-color: #4299e1;
}

/* Your custom CSS below */
```

### Step 1.4: Update JavaScript (if needed)
**File:** `resources/js/app.js`

Add Bootstrap JS:
```javascript
import 'bootstrap';  // Add this line
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();
```

### Step 1.5: Test Build
```bash
npm run build
# Should complete without errors
```

---

## FASE 2: COMPONENT MIGRATION (2-3 jam)

### Priority Components (Migrate in this order):

#### Step 2.1: Text Input
**File:** `resources/views/components/text-input.blade.php`

```blade
<!-- BEFORE -->
@props(['disabled' => false])
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!} />

<!-- AFTER -->
@props(['disabled' => false])
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control' . ($disabled ? ' is-disabled' : '')]) !!} />
```

**Usage in forms stays same:**
```blade
<x-text-input id="email" name="email" type="email" required />
```

---

#### Step 2.2: Input Label
**File:** `resources/views/components/input-label.blade.php`

```blade
<!-- BEFORE -->
<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>

<!-- AFTER -->
<label {{ $attributes->merge(['class' => 'form-label']) }}>
    {{ $value ?? $slot }}
</label>
```

---

#### Step 2.3: Input Error
**File:** `resources/views/components/input-error.blade.php`

```blade
<!-- BEFORE -->
@props(['messages'])
@if ($messages)
    <div {{ $attributes->merge(['class' => 'space-y-2']) }}>
        @foreach ((array) $messages as $message)
            <p class="text-sm text-red-600">{{ $message }}</p>
        @endforeach
    </div>
@endif

<!-- AFTER -->
@props(['messages'])
@if ($messages)
    <div {{ $attributes->merge(['class' => '']) }}>
        @foreach ((array) $messages as $message)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @endforeach
    </div>
@endif
```

---

#### Step 2.4: Primary Button
**File:** `resources/views/components/primary-button.blade.php`

```blade
<!-- BEFORE -->
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

<!-- AFTER -->
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary']) }}>
    {{ $slot }}
</button>
```

---

#### Step 2.5: Secondary Button
**File:** `resources/views/components/secondary-button.blade.php`

```blade
<!-- BEFORE -->
<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

<!-- AFTER -->
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-outline-secondary']) }}>
    {{ $slot }}
</button>
```

---

#### Step 2.6: Danger Button
**File:** `resources/views/components/danger-button.blade.php`

```blade
<!-- BEFORE -->
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

<!-- AFTER -->
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-danger']) }}>
    {{ $slot }}
</button>
```

---

#### Step 2.7: Modal Component
**File:** `resources/views/components/modal.blade.php`

```blade
<!-- BEFORE (Alpine.js based) -->
<div x-data="{ open: @entangle($open) }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click="open = false">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" @click.stop>
        <!-- Modal content -->
    </div>
</div>

<!-- AFTER (Bootstrap Modal) -->
<div class="modal fade" id="{{ $id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
```

---

### Step 2.8: Test Components
```bash
npm run build
php artisan serve
# Open http://localhost:8000
# Test form pages, buttons, etc.
```

---

## FASE 3: LAYOUT & PAGES MIGRATION (1-2 jam)

### Step 3.1: Dashboard Page
**File:** `resources/views/dashboard.blade.php`

```blade
<!-- BEFORE -->
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                You're logged in!
            </div>
        </div>
    </div>
</div>

<!-- AFTER -->
<div class="container-xl py-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    You're logged in!
                </div>
            </div>
        </div>
    </div>
</div>
```

---

### Step 3.2: Profile Pages
**File:** `resources/views/profile/partials/update-profile-form.blade.php`

```blade
<!-- Update forms with Bootstrap form-group classes -->

<!-- BEFORE -->
<form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" />
    </div>
</form>

<!-- AFTER -->
<form method="post" action="{{ route('profile.update') }}">
    <div class="mb-3">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" />
    </div>
</form>
```

---

## FASE 4: FINAL CLEANUP (1 jam)

### Step 4.1: Remove Tailwind Dependencies
```bash
npm uninstall tailwindcss @tailwindcss/forms @tailwindcss/vite postcss autoprefixer
rm tailwind.config.js
rm postcss.config.js
```

### Step 4.2: Update app.css (Remove Tailwind imports)
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
    --info-color: #4299e1;
}

/* Sidebar Custom Styles (keep existing) */
/* ... */
```

### Step 4.3: Update vite.config.js
Remove Tailwind plugin if exists:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

### Step 4.4: Final Build & Test
```bash
npm run build
php artisan serve
# Visit all pages and test thoroughly
```

---

## FASE 5: TESTING CHECKLIST

- [ ] Dashboard loads correctly
- [ ] Forms submit without errors
- [ ] Buttons styled properly
- [ ] Colors display correctly
- [ ] Sidebar responsive
- [ ] Modal/Dropdowns work
- [ ] Tables look good
- [ ] Authentication pages work
- [ ] Responsive on mobile
- [ ] No console errors

---

## ROLLBACK (If needed)

```bash
# If something breaks
git checkout main
npm install
npm run build
```

---

## COMMANDS SUMMARY

```bash
# Setup
npm install bootstrap bootstrap-icons popper.js

# Development
npm run dev

# Build for production
npm run build

# Cleanup (after testing)
npm uninstall tailwindcss @tailwindcss/forms @tailwindcss/vite postcss autoprefixer
```

---

## FILES MODIFIED CHECKLIST

- [ ] `resources/css/app.css`
- [ ] `resources/views/components/text-input.blade.php`
- [ ] `resources/views/components/input-label.blade.php`
- [ ] `resources/views/components/input-error.blade.php`
- [ ] `resources/views/components/primary-button.blade.php`
- [ ] `resources/views/components/secondary-button.blade.php`
- [ ] `resources/views/components/danger-button.blade.php`
- [ ] `resources/views/components/modal.blade.php`
- [ ] `resources/views/layouts/app.blade.php` (if needed)
- [ ] `resources/views/dashboard.blade.php`
- [ ] Profile partials files
- [ ] Remove `tailwind.config.js`
- [ ] Remove `postcss.config.js`
- [ ] Update `vite.config.js`

---

**Status:** Ready to Execute  
**Estimated Time:** 4-5 hours  
**Risk Level:** Low (with proper testing)
