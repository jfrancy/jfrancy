# Lake Zone Chemicals Limited Laravel CMS

Laravel CMS and high-converting marketing website for **LAKE ZONE CHEMICALS LIMITED**, a chemical supplier company in Tanzania.

## Features

- Laravel 13 application structure
- Database-backed CMS for company details, SEO, products, industries, and insights
- Hidden admin route: `/lake-zone-control`
- Powerful SEO: server-rendered meta tags, canonical URL, Open Graph, Twitter card, structured LocalBusiness schema, dynamic `robots.txt`, and dynamic `sitemap.xml`
- Clean extensionless URLs
- Sleek responsive marketing design
- Floating WhatsApp and call buttons
- Editable phone, WhatsApp, email, location, map link, and homepage content
- SQLite by default for easy setup, with MySQL config ready in `config/database.php`

## Local Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --seed
php artisan serve
```

Open the site:

```text
http://127.0.0.1:8000
```

Open the CMS:

```text
http://127.0.0.1:8000/lake-zone-control
```

Default admin password is set in `.env`:

```text
ADMIN_PASSWORD=change-this-strong-password
```

Change it before using the site publicly.

## GitHub Usage

Clone on another PC:

```bash
git clone https://github.com/jfrancy/jfrancy.git
cd jfrancy
composer install
cp .env.example .env
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --seed
php artisan serve
```

For production hosting, point the web server document root to the `public` directory.
