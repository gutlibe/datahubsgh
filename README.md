# Data Portal

This is a web application for Data Portal.

## Local Development vs. Production

This application can be configured for local development (in a subdirectory) or for production (at the root of a domain).


### Production Configuration (Default)

The repository is now configured for production by default. The key settings are:

- **`config/bootstrap.php`**: `define('BASE_URL', '');`
- **`.htaccess`**: `RewriteBase /`
- **`public/.htaccess`**: `RewriteBase /`
- **`.env`**: `APP_URL=https://yourdomain.com`
- **Layouts (`views/layouts/*.php`)**: `<base href="/">`

### Local Development Configuration

To run the application locally in a `/datacty` subdirectory, you need to make the following changes:

#### 1. Bootstrap Configuration
- **File:** `config/bootstrap.php`
- **Change:** `define('BASE_URL', '/datacty');`

#### 2. Root `.htaccess`
- **File:** `.htaccess`
- **Change:** `RewriteBase /datacty/` and `RewriteCond %{REQUEST_URI} !^/datacty/api/`

#### 3. Public `.htaccess`
- **File:** `public/.htaccess`
- **Change:** `RewriteBase /datacty/public/`

#### 4. Environment File
- **File:** `.env`
- **Change:** `APP_URL=http://localhost/datacty`

#### 5. Layout Files
- **Files:** `views/layouts/guest.php`, `views/layouts/app.php`, `views/layouts/admin.php`
- **Change:** `<base href="/datacty/">`

## Git Setup

To commit the `vendor` directory for simplified deployment, follow these steps:

1.  **Remove `vendor/` from `.gitignore`**
2.  **Stage and commit the changes:**
    ```bash
    git add .gitignore vendor
    git commit -m "Add vendor directory to repository for simplified deployment"
    git push
    ```

To fix the case-sensitivity issue with the `Classes` directory, run these commands:

1.  **Rename the directory:**
    ```bash
    git mv app/classes app/classes_temp && git mv app/classes_temp app/Classes
    ```