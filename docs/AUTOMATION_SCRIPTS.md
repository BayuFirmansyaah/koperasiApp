# MIGRATION AUTOMATION SCRIPTS
**Helper scripts untuk mempercepat migrasi**

---

## 1. BASH SCRIPT: Find Tailwind Classes

**File:** `scripts/find-tailwind-classes.sh`

```bash
#!/bin/bash

# Find all Tailwind classes in views
echo "🔍 Searching for Tailwind classes..."
echo ""

# Common Tailwind patterns
echo "=== TAILWIND CLASSES FOUND ==="
echo ""

echo "📝 Classes to migrate:"
grep -r "class=\"" resources/views/ | grep -E "(flex|items-|justify-|gap-|p[xyz]?-|mt-|mb-|text-|bg-|border-|rounded-|shadow-|w-|h-|space-)" | head -20

echo ""
echo "=== INLINE STYLES ==="
grep -r "style=" resources/views/ | head -10

echo ""
echo "=== SPACE-Y (Tailwind specific) ==="
grep -r "space-y" resources/views/

echo ""
echo "Done! Review the output above."
```

**Usage:**
```bash
chmod +x scripts/find-tailwind-classes.sh
./scripts/find-tailwind-classes.sh
```

---

## 2. PHP SCRIPT: Generate Migration Report

**File:** `scripts/generate-migration-report.php`

```php
<?php

/**
 * Generate a report of files needing Bootstrap migration
 * Usage: php scripts/generate-migration-report.php
 */

$viewsPath = 'resources/views';
$files = [];
$tailwindPatterns = [
    'flex', 'items-', 'justify-', 'gap-', 'px-', 'py-', 'p-',
    'mt-', 'mb-', 'ml-', 'mr-', 'text-', 'bg-', 'border-',
    'rounded-', 'shadow-', 'w-', 'h-', 'space-y', 'space-x'
];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewsPath),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        $hasClass = preg_match('/class="([^"]*(' . implode('|', $tailwindPatterns) . ')[^"]*)"/', $content);
        
        if ($hasClass) {
            $relative = str_replace($viewsPath . '/', '', $file->getRealPath());
            $files[] = $relative;
        }
    }
}

echo "📊 MIGRATION REPORT\n";
echo "==================\n\n";
echo "Total files needing migration: " . count($files) . "\n\n";
echo "Files:\n";
foreach ($files as $file) {
    echo "  ✓ $file\n";
}

// Save report
file_put_contents('migration_report.txt', implode("\n", $files));
echo "\n✅ Report saved to migration_report.txt\n";
```

**Usage:**
```bash
php scripts/generate-migration-report.php
```

---

## 3. BATCH REPLACE SCRIPT: Common Conversions

**File:** `scripts/batch-convert-classes.sh`

```bash
#!/bin/bash

# Auto-convert common Tailwind → Bootstrap classes
# ⚠️  BACKUP YOUR FILES FIRST!

echo "⚠️  This script will modify files. Make sure you have backups!"
echo ""
echo "Starting batch conversion..."
echo ""

# Common single-class conversions
declare -A CONVERSIONS=(
    ["flex"]="d-flex"
    ["block"]="d-block"
    ["hidden"]="d-none"
    ["mt-1"]="mt-2"
    ["mt-2"]="mt-3"
    ["mt-4"]="mt-4"
    ["mt-6"]="mt-4"
    ["mb-1"]="mb-2"
    ["mb-4"]="mb-4"
    ["px-4"]="px-3"
    ["py-2"]="py-2"
    ["w-full"]="w-100"
    ["w-1/2"]="w-50"
    ["text-center"]="text-center"
    ["text-gray-900"]="text-dark"
    ["text-gray-700"]="text-secondary"
    ["text-gray-600"]="text-secondary"
    ["text-gray-500"]="text-muted"
    ["bg-white"]="bg-white"
    ["bg-gray-100"]="bg-light"
    ["bg-gray-800"]="bg-dark"
    ["border-gray-300"]="border-secondary"
    ["rounded-lg"]="rounded"
    ["shadow-sm"]="shadow-sm"
    ["font-semibold"]="fw-bold"
    ["font-medium"]="fw-500"
)

# Apply conversions
for tailwind in "${!CONVERSIONS[@]}"; do
    bootstrap="${CONVERSIONS[$tailwind]}"
    
    echo "Converting: $tailwind → $bootstrap"
    
    find resources/views -name "*.blade.php" -type f -exec sed -i "s/$tailwind/$bootstrap/g" {} \;
done

echo ""
echo "✅ Batch conversion complete!"
echo ""
echo "⚠️  IMPORTANT: Review changes with git diff"
echo "   git diff resources/views/"
```

**Usage:**
```bash
chmod +x scripts/batch-convert-classes.sh
git add .  # Save before running
./scripts/batch-convert-classes.sh
git diff   # Review changes
```

---

## 4. VALIDATION SCRIPT: Check Bootstrap Classes

**File:** `scripts/validate-bootstrap-migration.php`

```php
<?php

/**
 * Validate Bootstrap migration
 * Checks if Bootstrap classes are properly used
 * Usage: php scripts/validate-bootstrap-migration.php
 */

$viewsPath = 'resources/views';
$issues = [];
$validClasses = [
    'd-flex', 'd-block', 'd-none', 'd-inline',
    'align-items-center', 'align-items-start', 'align-items-end',
    'justify-content-center', 'justify-content-between',
    'gap-2', 'gap-3', 'gap-4',
    'mt-1', 'mt-2', 'mt-3', 'mt-4',
    'mb-1', 'mb-2', 'mb-3', 'mb-4',
    'px-2', 'px-3', 'px-4',
    'py-2', 'py-3', 'py-4',
    'w-100', 'w-50',
    'text-center', 'text-start', 'text-end',
    'text-dark', 'text-secondary', 'text-muted',
    'bg-white', 'bg-light', 'bg-dark',
    'border', 'border-secondary',
    'rounded', 'rounded-pill',
    'shadow', 'shadow-sm',
    'fw-bold', 'fw-500', 'fw-normal',
    'fs-1', 'fs-2', 'fs-3', 'fs-4', 'fs-5', 'fs-6',
    'form-control', 'form-label', 'form-check-input',
    'btn', 'btn-primary', 'btn-secondary', 'btn-danger',
    'alert', 'alert-success', 'alert-danger',
    'card', 'card-body', 'card-header',
    'table', 'table-hover', 'table-striped',
];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewsPath),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        $relative = str_replace($viewsPath . '/', '', $file->getRealPath());
        
        // Check for Tailwind patterns
        if (preg_match('/(flex|items-|justify-|gap-|space-y|px-\d|py-\d|w-full|bg-gray|text-gray|border-gray|rounded-lg)/', $content)) {
            $issues[] = "⚠️  $relative - Contains Tailwind classes";
        }
    }
}

if (count($issues) > 0) {
    echo "🔍 VALIDATION REPORT\n";
    echo "====================\n\n";
    echo "Issues found: " . count($issues) . "\n\n";
    foreach ($issues as $issue) {
        echo "$issue\n";
    }
    echo "\n❌ Migration incomplete\n";
} else {
    echo "✅ All files migrated to Bootstrap 5!\n";
}
```

**Usage:**
```bash
php scripts/validate-bootstrap-migration.php
```

---

## 5. HELPER: Setup Scripts

**File:** `scripts/setup-migration.sh`

```bash
#!/bin/bash

echo "🚀 Setting up Bootstrap 5 Migration Environment..."
echo ""

# Create scripts directory if not exists
mkdir -p scripts

# Create backup
echo "📦 Creating backup branch..."
git checkout -b backup/before-bootstrap-migration-$(date +%s)
git add .
git commit -m "Backup before Bootstrap 5 migration"

# Go back to main branch
git checkout -

echo ""
echo "📥 Installing Bootstrap dependencies..."
npm install bootstrap bootstrap-icons popper.js

echo ""
echo "🏗️  Creating migration scripts..."
chmod +x scripts/*.sh

echo ""
echo "✅ Setup complete!"
echo ""
echo "Next steps:"
echo "1. Review MIGRATION_PLAN_TAILWIND_TO_BS5.md"
echo "2. Run: ./scripts/find-tailwind-classes.sh"
echo "3. Run: npm run build"
echo "4. Start migrating components"
```

**Usage:**
```bash
chmod +x scripts/setup-migration.sh
./scripts/setup-migration.sh
```

---

## 6. QUICK CHECKLIST TEMPLATE

**File:** `MIGRATION_CHECKLIST.md`

```markdown
# BOOTSTRAP 5 MIGRATION CHECKLIST

## Phase 1: Setup ✅
- [ ] Install Bootstrap dependencies
- [ ] Update CSS imports
- [ ] Test build (npm run build)

## Phase 2: Components (8 files)
- [ ] text-input.blade.php
- [ ] input-label.blade.php
- [ ] input-error.blade.php
- [ ] primary-button.blade.php
- [ ] secondary-button.blade.php
- [ ] danger-button.blade.php
- [ ] modal.blade.php
- [ ] dropdown.blade.php

## Phase 3: Layouts
- [ ] app.blade.php
- [ ] guest.blade.php
- [ ] navigation.blade.php

## Phase 4: Views (Core)
- [ ] dashboard.blade.php
- [ ] profile/edit.blade.php
- [ ] auth/login.blade.php
- [ ] auth/register.blade.php

## Phase 5: Testing
- [ ] Desktop browser testing
- [ ] Mobile responsive
- [ ] Form validation
- [ ] Button functionality
- [ ] Modal/Dropdown functionality
- [ ] Table rendering

## Phase 6: Cleanup
- [ ] Remove Tailwind packages
- [ ] Remove tailwind.config.js
- [ ] Remove postcss.config.js
- [ ] Final build
- [ ] Code review

## Sign-off
- [ ] All tests passed
- [ ] No console errors
- [ ] Performance optimized
- [ ] Ready for production
```

---

## INSTALLATION COMMANDS

```bash
# 1. Create scripts directory
mkdir -p scripts

# 2. Create all scripts
# Copy scripts above into separate files

# 3. Make executable
chmod +x scripts/*.sh

# 4. Run setup
./scripts/setup-migration.sh

# 5. Start migration
./scripts/find-tailwind-classes.sh
php scripts/generate-migration-report.php
```

---

## QUICK REFERENCE

```bash
# Check what needs migration
./scripts/find-tailwind-classes.sh

# Generate report
php scripts/generate-migration-report.php

# Auto-convert common classes (USE WITH CAUTION)
./scripts/batch-convert-classes.sh

# Validate migration
php scripts/validate-bootstrap-migration.php

# Build for testing
npm run build

# Start dev server
npm run dev
```

---

**Warning:** Always commit before running automation scripts!

```bash
git add .
git commit -m "Before Bootstrap migration automation"
./scripts/batch-convert-classes.sh
git diff  # Review changes
```
