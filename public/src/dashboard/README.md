# Dashboard Assets

Struktur folder assets untuk Multi-Role Dashboard System.

## Struktur Folder

```
assets/dashboard/
├── css/
│   ├── shared/          # CSS yang digunakan bersama semua role
│   │   └── global.css
│   ├── admin/           # CSS khusus Admin Dashboard
│   │   └── _styles.css
│   ├── instructor/      # CSS khusus Instructor Dashboard
│   │   └── _styles.css
│   └── student/         # CSS khusus Student Dashboard
│       └── _styles.css
├── js/
│   ├── shared/          # JavaScript utilities yang digunakan bersama
│   │   ├── chart-config.js
│   │   └── formatters.js
│   ├── admin/           # JavaScript khusus Admin Dashboard
│   │   └── _scripts.js
│   ├── instructor/      # JavaScript khusus Instructor Dashboard
│   │   └── _scripts.js
│   └── student/         # JavaScript khusus Student Dashboard
│       └── _scripts.js
└── images/              # Images khusus dashboard (jika ada)
```

## Penggunaan

Semua assets dashboard menggunakan path `assets/dashboard/` dan dapat diakses melalui Laravel `asset()` helper:

```blade
{{ asset('assets/dashboard/css/shared/global.css') }}
{{ asset('assets/dashboard/js/shared/chart-config.js') }}
{{ asset('assets/dashboard/css/admin/_styles.css') }}
```

## Catatan

- File CSS dan JS yang ada di folder ini adalah file khusus untuk dashboard
- Assets global Metronic tetap berada di `assets/plugins/`, `assets/css/`, dan `assets/js/`
- Media files (images, icons) tetap berada di `assets/media/`
