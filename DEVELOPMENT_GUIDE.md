# Data Portal - Development & Deployment Guide

This guide covers the core architecture and workflows for the modernized Data Portal platform.

---

## 1. Getting Started

### Prerequisites
- PHP 8.1+
- MySQL/MariaDB
- Node.js & NPM
- Composer

### Initial Setup
1. **Environment:** Copy `.env.example` to `.env` (or create one).
   ```env
   APP_ENV=local
   APP_URL=http://localhost:8000
   DB_HOST=127.0.0.1
   DB_NAME=datacty_dev
   DB_USER=root
   DB_PASS=
   ```
2. **Dependencies:**
   ```bash
   composer install
   npm install
   ```
3. **Database:** You do not need to create the database manually. The app will create it on the first request if it doesn't exist.
4. **Migrations:** Run the initial schema:
   ```bash
   php migrate.php
   ```

---

## 2. Database Migrations

We use a Laravel-style migration system. All migrations are stored in `database/migrations/`.

### Creating a New Migration
1. Create a PHP file with a timestamp prefix: `database/migrations/2026_01_31_000002_add_new_table.php`.
2. Structure:
   ```php
   <?php
   use App\Classes\Migration;
   class AddNewTable extends Migration {
       public function up() {
           $this->db->exec("CREATE TABLE ...");
       }
       public function down() {
           $this->db->exec("DROP TABLE ...");
       }
   }
   ```
3. Apply changes: `php migrate.php`.

---

## 3. Asset Pipeline (Tailwind & JS)

We use a custom build system powered by **esbuild** (JS) and **Tailwind CLI** (CSS).

### Local Development (Live Changes)
Run the watchers in separate terminals to see changes instantly:
- **CSS:** `npm run dev:css` (Updates `public/css/style.css`)
- **JS:** In `local` mode, the app serves `public/js/*.js` directly. Just refresh the browser.
- **Combined:** `npm run dev`

### Production Build
Before deploying, run the production build to minify, obfuscate, and hash assets:
```bash
npm run build
```
This generates hashed files in `public/assets/` and updates `public/mix-manifest.json`.

---

## 4. Working with Assets (The `asset()` Helper)

Always use the `asset()` helper in your views to ensure correct path resolution:

```php
<!-- Correct way to link CSS -->
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">

<!-- Correct way to link JS -->
<script src="<?= asset('js/main.js') ?>"></script>
```

- **In Local:** Resolves to the raw files for debugging.
- **In Production:** Resolves to the obfuscated, hashed file in `/assets/` via the manifest.

---

## 5. Adding New JavaScript
1. Create your file in `public/js/` (e.g., `public/js/feature.js`).
2. Add the entry point to `build.js` in the `jsEntries` array:
   ```javascript
   const jsEntries = [
       'public/js/main.js',
       'public/js/feature.js', // Add here
       ...
   ];
   ```
3. Link it in your view: `<script src="<?= asset('js/feature.js') ?>"></script>`.

---

## 6. Authentication
- **Mechanism:** Standard PHP Sessions.
- **Helper Functions:**
    - `isLoggedIn()`: Returns boolean.
    - `getAuthUser()`: Returns current user array or null.
    - `isAdmin()`: Check if the logged-in user is an administrator.

---

## 7. Deployment (Production)
1. Upload all files to the server.
2. Ensure `.env` has `APP_ENV=production`.
3. Run `npm install && npm run build`.
4. Run `php migrate.php` to ensure the live database is up to date.
5. Point your web server's document root to the `public/` directory.
