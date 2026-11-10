# Deploying monitrade.net on cPanel

This takes about 20 minutes. You need cPanel access for the hosting account that serves **monitrade.net**.

## 1. PHP version

cPanel → **MultiPHP Manager** (or **Select PHP Version**): set monitrade.net to **PHP 8.1 or newer**. Make sure the `pdo_mysql`, `mbstring` and `fileinfo` extensions are enabled. They are on by default on most hosts.

## 2. Database

1. cPanel → **MySQL® Databases**:
   - Create a database, e.g. `monitrade`. cPanel names it `cpaneluser_monitrade`.
   - Create a user with a strong password.
   - **Add the user to the database** with **ALL PRIVILEGES**.
2. cPanel → **phpMyAdmin** → select the new database → **Import**:
   1. Import `database/schema.sql` first.
   2. Then import `database/seed.sql`. This loads all the content migrated from monitrade.org.

## 3. Upload the files

Use either option.

- **Git (recommended):** cPanel → **Git™ Version Control** → *Create* → clone the private GitHub repo `Monitrade_Website` into a folder such as `/home/cpaneluser/repositories/Monitrade_Website`. Then deploy to `public_html` by copying the files, or set the domain's document root to that folder. Private repos need a GitHub deploy key; cPanel's Git page explains how to add the SSH key.
- **File Manager / FTP:** upload every file and folder in the repository into `public_html/`, including the hidden `.htaccess` files.

The site must sit at the **document root** (`public_html`) so that `.htaccess` applies. If you have to use a sub-folder, set `base_path` in the config and `RewriteBase` in `.htaccess` to that folder.

## 4. Configuration

1. In `public_html/config/`, copy `config.example.php` to **`config.php`**.
2. Edit `config.php`:
   - `env` → `production`
   - `base_url` → `https://monitrade.net`
   - `db` → the database name, user and password from step 2. `host` is usually `localhost`.
   - `mail.from` → a mailbox that exists on the domain, e.g. `no-reply@monitrade.net` (see step 6).
   - `mail.to` → the address(es) that should receive website enquiries, comma-separated. If you leave it empty, the **Company e-mail** from Admin → Company Settings is used.
3. Folder permissions: `uploads/` and its subfolders need to be **755** (writable by PHP). Files are **644**.

## 5. SSL and HTTPS

1. cPanel → **SSL/TLS Status** → run **AutoSSL** for monitrade.net and www.monitrade.net.
2. Once the certificate is active, uncomment these three lines in `.htaccess`. They redirect `http://` and `www.` to `https://monitrade.net`:
   ```apache
   # RewriteCond %{HTTPS} !=on [OR]
   # RewriteCond %{HTTP_HOST} ^www\. [NC]
   # RewriteRule ^ https://monitrade.net%{REQUEST_URI} [L,R=301]
   ```

## 6. E-mail delivery

The contact and business request forms e-mail each submission to you and also store it in the admin panel.

1. cPanel → **Email Accounts** → create `no-reply@monitrade.net`, matching `mail.from`.
2. cPanel → **Email Deliverability** → make sure **SPF** and **DKIM** are valid for monitrade.net. Without them, the notification e-mails may land in spam.
3. Send a test message from `/contact`, then check that it arrives. Admin → Submissions shows a ✓ when the e-mail was sent.

## 7. Create the admin account

Open **https://monitrade.net/admin/setup.php** and create the first administrator. Use a password of at least 10 characters. The setup page switches itself off once an admin exists. You can add more admins under **Admins & Password**.

## 8. Check before going live

Admin → **Company Settings** holds all the company details. Before launch:

- **E-mail:** create the mailbox `monitrade@monitrade.net` (cPanel → Email Accounts). It is the address shown on the site and the default recipient of enquiries.

## 9. Optional: point monitrade.org to the new site

If monitrade.org will be retired, add a 301 redirect from it to `https://monitrade.net`. Old links such as `monitrade.org/pages/contact.php` will then land on the right new pages, because the new site understands the old addresses.

## Updating the site later

- **Content** (news, products, brands, team, contact details): use the admin panel. No code changes are needed.
- **Code:** push to GitHub, then in cPanel → Git Version Control → **Pull** (and deploy).
- **Backups:** cPanel → **Backup**. Download the database and the `uploads/` folder regularly, because images added through the admin panel exist only on the server, not in git.
