-- Monitrade website — database schema (MySQL 5.7+ / MariaDB 10.3+)
-- Import this first, then seed.sql.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS admins;
CREATE TABLE admins (
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username       VARCHAR(60)  NOT NULL,
  email          VARCHAR(190) NOT NULL,
  password_hash  VARCHAR(255) NOT NULL,
  created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_login_at  DATETIME     NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_admins_username (username),
  UNIQUE KEY uq_admins_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS login_attempts;
CREATE TABLE login_attempts (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ip            VARCHAR(45)  NOT NULL,
  attempted_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_login_ip_time (ip, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- News items and articles share one table.
DROP TABLE IF EXISTS posts;
CREATE TABLE posts (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  type          ENUM('news','article') NOT NULL DEFAULT 'news',
  title         VARCHAR(255) NOT NULL,
  slug          VARCHAR(140) NOT NULL,
  summary       VARCHAR(500) NULL,
  body          MEDIUMTEXT   NULL,
  image         VARCHAR(255) NULL,
  source        VARCHAR(190) NULL,
  published_on  DATE         NULL,
  is_ticker     TINYINT(1)   NOT NULL DEFAULT 0,  -- show in the homepage news ticker
  is_published  TINYINT(1)   NOT NULL DEFAULT 1,
  created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_posts_type_pub (type, is_published, published_on)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS products;
CREATE TABLE products (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  section       ENUM('machines','accessories','software') NOT NULL,
  category      ENUM('spinning','weaving','dyeing','garments') NULL,
  name          VARCHAR(190) NOT NULL,
  slug          VARCHAR(140) NOT NULL,
  model         VARCHAR(190) NULL,
  specs         TEXT         NULL,
  offer         VARCHAR(190) NULL,
  image         VARCHAR(255) NULL,
  sort_order    INT          NOT NULL DEFAULT 0,
  is_published  TINYINT(1)   NOT NULL DEFAULT 1,
  created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_products_cat (section, category, is_published, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS brands;
CREATE TABLE brands (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name          VARCHAR(120) NOT NULL,
  description   VARCHAR(255) NULL,
  website       VARCHAR(255) NULL,
  logo          VARCHAR(255) NULL,
  sort_order    INT          NOT NULL DEFAULT 0,
  is_published  TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS team_members;
CREATE TABLE team_members (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name          VARCHAR(120) NOT NULL,
  title         VARCHAR(160) NULL,
  phone         VARCHAR(60)  NULL,
  email         VARCHAR(190) NULL,
  sort_order    INT          NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact-form messages and business (quote) requests.
DROP TABLE IF EXISTS submissions;
CREATE TABLE submissions (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  type          ENUM('contact','request') NOT NULL,
  first_name    VARCHAR(100) NOT NULL,
  last_name     VARCHAR(100) NULL,
  company       VARCHAR(160) NULL,
  job_title     VARCHAR(120) NULL,
  email         VARCHAR(190) NOT NULL,
  phone         VARCHAR(60)  NULL,
  mobile        VARCHAR(60)  NULL,
  country       VARCHAR(100) NULL,
  city          VARCHAR(100) NULL,
  subject       VARCHAR(200) NULL,
  message       TEXT         NOT NULL,
  ip            VARCHAR(45)  NULL,
  mail_sent     TINYINT(1)   NOT NULL DEFAULT 0,
  is_read       TINYINT(1)   NOT NULL DEFAULT 0,
  created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_sub_read (is_read, created_at),
  KEY idx_sub_ip (ip, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS settings;
CREATE TABLE settings (
  `key`    VARCHAR(80) NOT NULL,
  `value`  TEXT        NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
