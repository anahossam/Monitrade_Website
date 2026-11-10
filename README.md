# Monitrade Website — monitrade.net

Company website for **Monitrade**, the Egyptian textile agency (since 1989). It replaces the old site at monitrade.org: the pages, product catalog, news and articles were carried over, and the site was rebuilt with the yellow-and-grey Monitrade identity, a responsive layout and an admin panel.

- **Stack:** plain PHP 8.1+ and MySQL 5.7+ / MariaDB 10.3+. No framework and no build step, so it runs on standard cPanel shared hosting.
- **Admin panel:** `/admin`. Manages news, the news ticker, articles, products, brands, team members, company details and contact/quote submissions.
- **Deployment:** see [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md).

## Pages

| URL | Page | Old monitrade.org address (301-redirected) |
|---|---|---|
| `/` | Home: intro, sectors, featured products, brands, news | `index.php` |
| `/company-profile` | Who is Monitrade, Why Monitrade, team | `pages/cProfile.php` |
| `/products` | Catalog overview | `products/` |
| `/products/{machines\|accessories}/{spinning\|weaving\|dyeing\|garments}` | Category listings | `products/machines.php?mType=…`, `products/accessories.php?aType=…` |
| `/products/software` | Software | `products/software.php` |
| `/products/item/{id}-{slug}` | Product detail, with a "Request a quotation" button | — |
| `/news`, `/articles` and `/news/{id}`, `/articles/{id}` | News and articles | `pages/viewNews.php`, `pages/viewArticle.php` |
| `/contact` | Both offices, team contacts and a message form | `pages/contact.php` |
| `/business-request` | Quotation request form | `pages/request.php` |
| `/share` | Send to a Friend (share links) | `pages/mailToFriend.php` |
| `/search`, `/sitemap`, `/sitemap.xml` | Search, HTML site map, XML site map for search engines | `pages/siteMap.php` |
| `/terms`, `/privacy`, `/navigation` | Legal pages | `pages/terms.php` … |

The old **Members Area** login was left out on purpose; `pages/members.php` redirects to the home page.

## Project structure

```
index.php            Front controller: all public URLs are routed here
.htaccess            Apache rewrites, blocks private folders, caching
router.php           Router for PHP's built-in server (local development only)
app/                 Application code (blocked from the web)
  bootstrap.php      Config, session, security headers
  controllers.php    Public page logic and form handling
  helpers.php, db.php, auth.php, mail.php, upload.php, icons.php
  admin.php          Admin helpers
  views/             Templates (layout, pages, partials, legal/, admin/)
admin/               Admin panel pages
assets/css|js|img    Styles, scripts, logo (logo.svg) and favicon
uploads/             Product, news and brand images (PHP execution disabled)
config/              config.example.php (committed); config.php (your secrets, git-ignored)
database/            schema.sql + seed.sql (the content migrated from monitrade.org)
docs/                Deployment guide
Documentation/       Brand reference (Block-Note.jpeg: the Monitrade notepad / logo source)
```

## Local development

Requirements: PHP 8.1+ with `pdo_mysql`, `mbstring` and `fileinfo`, plus a MySQL or MariaDB server. Laragon on Windows provides both.

```bash
# 1. Create a database, then import the schema and the content
mysql -u root -e "CREATE DATABASE monitrade CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
mysql -u root monitrade < database/schema.sql
mysql -u root monitrade < database/seed.sql

# 2. Configure
cp config/config.example.php config/config.php   # then edit db + base_url (http://localhost:8090), env = development

# 3. Run
php -S localhost:8090 router.php
```

Then open <http://localhost:8090>, and <http://localhost:8090/admin/setup.php> to create the first admin account.

## Security notes

- All database queries use prepared statements, and all output is HTML-escaped.
- Every form uses a CSRF token. The public forms also have a honeypot field, a simple arithmetic question and a limit of 5 submissions per IP per hour.
- Admin passwords are stored with `password_hash`. Logins are rate-limited (5 failed attempts per 15 minutes per IP), sessions regenerate on login and expire after 2 hours idle.
- Uploads are checked to be real images (JPG/PNG/GIF/WebP, max 5 MB) and stored with random names. `uploads/.htaccess` stops anything there from running as code.
- `app/`, `config/`, `database/`, `docs/` and `Documentation/` are blocked from the web by `.htaccess`.
- "Send to a Friend" uses share links instead of server-sent e-mail, so the site can't be used to send spam.
