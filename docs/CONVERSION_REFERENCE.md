# TAILWIND → BOOTSTRAP 5 CONVERSION REFERENCE
**Quick Conversion Guide untuk Project Koperasi App**

---

## QUICK CONVERSIONS

### SPACING (Padding & Margin)

```
TAILWIND        → BOOTSTRAP 5
p-0             → p-0
p-1             → p-1
p-2             → p-2
p-3             → p-3
p-4             → p-3 (slight difference)
p-6             → p-4

pt-1, pb-1      → pt-2, pb-2
px-4            → px-3
py-2            → py-2

m-0, m-1, m-2   → m-0, m-1, m-2 (same)
mt-1            → mt-2
mt-4            → mt-4
mr-2            → me-2 (margin-end for RTL)
mb-4            → mb-4
ml-2            → ms-2 (margin-start for RTL)
```

---

### FLEX & GRID

```
TAILWIND                    → BOOTSTRAP 5
flex                        → d-flex
flex-row                    → flex-row
flex-col                    → flex-column
flex-wrap                   → flex-wrap
justify-start               → justify-content-start
justify-center              → justify-content-center
justify-between             → justify-content-between
justify-end                 → justify-content-end
items-start                 → align-items-start
items-center                → align-items-center
items-end                   → align-items-end
gap-4                       → gap-3
gap-6                       → gap-4
```

---

### GRID LAYOUT

```
TAILWIND                    → BOOTSTRAP 5
grid                        → row
grid-cols-1                 → col
grid-cols-2                 → col-md-6 (2 cols on medium+)
grid-cols-3                 → col-lg-4
grid-cols-4                 → col-lg-3 (4 cols on large+)
gap-4                       → g-3 (gap between rows/cols)
```

---

### COLORS

```
TAILWIND (Text)             → BOOTSTRAP 5
text-gray-900               → text-dark
text-gray-700               → text-secondary
text-gray-600               → text-secondary
text-gray-500               → text-muted
text-blue-600               → text-primary
text-red-600                → text-danger
text-green-600              → text-success
text-yellow-600             → text-warning
text-cyan-600               → text-info

TAILWIND (Background)       → BOOTSTRAP 5
bg-white                    → bg-white
bg-gray-100                 → bg-light
bg-gray-200                 → bg-light
bg-gray-800                 → bg-dark
bg-blue-600                 → bg-primary
bg-red-600                  → bg-danger
bg-green-600                → bg-success
bg-yellow-600               → bg-warning
bg-cyan-600                 → bg-info

TAILWIND (Border)           → BOOTSTRAP 5
border-gray-300             → border-secondary
border-gray-200             → border-light
border-red-500              → border-danger
border-green-500            → border-success
```

---

### TYPOGRAPHY

```
TAILWIND                    → BOOTSTRAP 5
text-xs                     → fs-6 + smaller
text-sm                     → fs-6
text-base                   → (default) fs-5
text-lg                     → fs-5
text-xl                     → fs-4
text-2xl                    → fs-3
text-3xl                    → fs-2
text-4xl                    → fs-1

font-thin                   → fw-light
font-normal                 → fw-normal
font-medium                 → fw-500
font-semibold               → fw-600 / fw-bold
font-bold                   → fw-bold
font-black                  → fw-900

leading-none                → (custom line-height)
leading-tight               → lh-1
leading-normal              → lh-base
leading-relaxed             → lh-lg

tracking-tight              → ls-tight (custom)
tracking-normal             → (default)
tracking-wide               → ls-wide (custom)

text-left                   → text-start
text-center                 → text-center
text-right                  → text-end
text-justify                → text-justify

text-uppercase              → text-uppercase
text-lowercase              → text-lowercase
text-capitalize             → text-capitalize

text-truncate               → text-truncate
line-clamp-3                → (custom CSS)
```

---

### SIZING

```
TAILWIND                    → BOOTSTRAP 5
w-full                      → w-100
w-1/2                       → w-50
h-screen                    → vh-100
h-32                        → (custom height)
max-w-7xl                   → mw-100 (max-width utility)
min-h-screen                → min-vh-100
```

---

### BORDERS & RADIUS

```
TAILWIND                    → BOOTSTRAP 5
border                      → border
border-2                    → border border-2
border-t                    → border-top
border-r                    → border-end
border-b                    → border-bottom
border-l                    → border-start

rounded                     → rounded
rounded-sm                  → rounded
rounded-lg                  → rounded
rounded-xl                  → rounded-3
rounded-full                → rounded-pill
```

---

### SHADOWS

```
TAILWIND                    → BOOTSTRAP 5
shadow-none                 → (remove shadow class)
shadow-sm                   → shadow-sm
shadow                      → shadow
shadow-md                   → shadow
shadow-lg                   → shadow-lg
shadow-xl                   → shadow-lg
```

---

### DISPLAY

```
TAILWIND                    → BOOTSTRAP 5
block                       → d-block
inline-block                → d-inline-block
inline                      → d-inline
flex                        → d-flex
grid                        → (use bootstrap grid)
hidden                      → d-none
visible                     → d-block (or visible)

(Responsive)
sm:hidden                   → d-sm-none
md:block                    → d-md-block
lg:flex                     → d-lg-flex
xl:grid                     → d-xl-flex
```

---

### POSITION

```
TAILWIND                    → BOOTSTRAP 5
static                      → position-static
relative                    → position-relative
absolute                    → position-absolute
fixed                       → position-fixed
sticky                      → position-sticky

top-0, right-0, etc         → top-0, end-0, etc
inset-0                     → (custom: top-0, right-0, bottom-0, left-0)
```

---

### OVERFLOW & VISIBILITY

```
TAILWIND                    → BOOTSTRAP 5
overflow-hidden             → overflow-hidden
overflow-auto               → overflow-auto
overflow-scroll             → overflow-y-auto
overflow-x-auto             → overflow-x-auto

visible                     → visibility-visible
invisible                   → visibility-hidden
```

---

### OPACITY

```
TAILWIND                    → BOOTSTRAP 5
opacity-0                   → opacity-0
opacity-50                  → opacity-50
opacity-75                  → opacity-75
opacity-100                 → opacity-100
```

---

### HOVER & ACTIVE STATES

```
TAILWIND                    → BOOTSTRAP 5
hover:bg-gray-100           → (use CSS :hover)
focus:outline-blue-500      → :focus-within: outline-primary
active:bg-gray-200          → :active: bg-light
disabled:opacity-50         → :disabled: opacity-50
```

---

### FORM STYLING

```
TAILWIND                    → BOOTSTRAP 5
block w-full                → form-control
px-4 py-2                   → (default in form-control)
border border-gray-300      → (default in form-control)
rounded                     → (default in form-control)
focus:outline               → (handled by Bootstrap)
focus:ring-blue-500         → :focus: outline outline-primary

(Select)
block w-full px-4           → form-select
appearance-none             → (default in form-select)

(Checkbox/Radio)
w-4 h-4                     → form-check-input (default size)
accent-blue-600             → accent-primary (custom)

(Textarea)
block w-full px-4           → form-control (with rows attr)
```

---

### COMMON PATTERNS

#### Button Styling
```blade
<!-- TAILWIND -->
<button class="inline-flex items-center px-4 py-2 bg-gray-800 text-white border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
  Click me
</button>

<!-- BOOTSTRAP 5 -->
<button class="btn btn-dark btn-sm">
  Click me
</button>
```

#### Form Group
```blade
<!-- TAILWIND -->
<div class="space-y-6">
  <div>
    <label class="block font-medium text-gray-700">
      Name
    </label>
    <input type="text" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
  </div>
</div>

<!-- BOOTSTRAP 5 -->
<div class="mb-3">
  <label class="form-label">
    Name
  </label>
  <input type="text" class="form-control">
</div>
```

#### Card Styling
```blade
<!-- TAILWIND -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
  <div class="p-6 text-gray-900">
    Content
  </div>
</div>

<!-- BOOTSTRAP 5 -->
<div class="card">
  <div class="card-body">
    Content
  </div>
</div>
```

#### Alert/Flash Message
```blade
<!-- TAILWIND -->
<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
  Error message
</div>

<!-- BOOTSTRAP 5 -->
<div class="alert alert-danger">
  Error message
</div>
```

#### Modal
```blade
<!-- TAILWIND -->
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
  <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
    Content
  </div>
</div>

<!-- BOOTSTRAP 5 -->
<div class="modal fade" id="exampleModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        Content
      </div>
    </div>
  </div>
</div>
```

#### Table
```blade
<!-- TAILWIND -->
<table class="min-w-full divide-y divide-gray-200">
  <thead class="bg-gray-50">
    <tr>
      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
  </thead>
  <tbody class="bg-white divide-y divide-gray-200">
    <tr class="hover:bg-gray-50">

<!-- BOOTSTRAP 5 -->
<table class="table table-hover">
  <thead class="table-light">
    <tr>
      <th scope="col" class="text-uppercase">
  </thead>
  <tbody>
    <tr>
```

---

## RESPONSIVE BREAKPOINTS

```
TAILWIND            → BOOTSTRAP 5
sm: 640px           → (none, default is mobile)
md: 768px           → md: breakpoint
lg: 1024px          → lg: breakpoint
xl: 1280px          → xl: breakpoint
2xl: 1536px         → xxl: breakpoint

USAGE:
sm:block md:hidden  → d-block d-md-none
md:w-1/2            → col-md-6
lg:grid-cols-3      → col-lg-4
```

---

## CUSTOM UTILITIES (If needed in Bootstrap)

Create `resources/css/custom.css`:

```css
/* Custom utilities for Bootstrap 5 */

/* Spacing variants like Tailwind */
.gap-2 { gap: 0.5rem; }
.gap-3 { gap: 0.75rem; }
.gap-4 { gap: 1rem; }

/* Line height utilities */
.lh-tight { line-height: 1.25; }
.lh-base { line-height: 1.5; }
.lh-lg { line-height: 1.75; }

/* Letter spacing */
.ls-tight { letter-spacing: -0.02em; }
.ls-normal { letter-spacing: 0; }
.ls-wide { letter-spacing: 0.05em; }

/* Line clamping */
.line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
```

Then import in `app.css`:
```css
@import 'bootstrap/dist/css/bootstrap.css';
@import 'custom.css';
```

---

**Reference:** Bootstrap 5 Documentation  
https://getbootstrap.com/docs/5.0/
