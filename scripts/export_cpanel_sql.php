<?php

declare(strict_types=1);

$databasePath = __DIR__.'/../database/database.sqlite';

if (! file_exists($databasePath)) {
    fwrite(STDERR, "SQLite database not found at {$databasePath}\n");
    exit(1);
}

$outputPath = $argv[1] ?? null;

if (! $outputPath) {
    fwrite(STDERR, "Usage: php scripts/export_cpanel_sql.php /absolute/path/output.sql\n");
    exit(1);
}

$pdo = new PDO('sqlite:'.$databasePath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$schema = [
    'categories' => <<<'SQL'
CREATE TABLE `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `type` ENUM('blog','tool','template') NOT NULL,
  `description` TEXT NULL,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` TEXT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_type_index` (`type`),
  KEY `categories_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL,
    'users' => <<<'SQL'
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `role` VARCHAR(255) NOT NULL DEFAULT 'admin',
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL,
    'pages' => <<<'SQL'
CREATE TABLE `pages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` TEXT NULL,
  `is_published` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_is_published_index` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL,
    'ad_slots' => <<<'SQL'
CREATE TABLE `ad_slots` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `key` VARCHAR(255) NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `code` LONGTEXT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ad_slots_key_unique` (`key`),
  KEY `ad_slots_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL,
    'settings' => <<<'SQL'
CREATE TABLE `settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(255) NOT NULL,
  `value` LONGTEXT NULL,
  `group` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_group_index` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL,
    'tools' => <<<'SQL'
CREATE TABLE `tools` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `short_description` TEXT NOT NULL,
  `body` LONGTEXT NULL,
  `icon` VARCHAR(255) NULL,
  `tool_type` VARCHAR(255) NOT NULL,
  `meta_title` VARCHAR(255) NOT NULL,
  `meta_description` TEXT NOT NULL,
  `og_image` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 0,
  `published_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tools_slug_unique` (`slug`),
  KEY `tools_category_id_index` (`category_id`),
  KEY `tools_is_featured_index` (`is_featured`),
  KEY `tools_is_published_index` (`is_published`),
  KEY `tools_published_at_index` (`published_at`),
  CONSTRAINT `tools_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL,
    'document_templates' => <<<'SQL'
CREATE TABLE `document_templates` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `short_description` TEXT NOT NULL,
  `content` LONGTEXT NOT NULL,
  `meta_title` VARCHAR(255) NOT NULL,
  `meta_description` TEXT NOT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 0,
  `published_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_templates_slug_unique` (`slug`),
  KEY `document_templates_category_id_index` (`category_id`),
  KEY `document_templates_is_featured_index` (`is_featured`),
  KEY `document_templates_is_published_index` (`is_published`),
  KEY `document_templates_published_at_index` (`published_at`),
  CONSTRAINT `document_templates_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL,
    'posts' => <<<'SQL'
CREATE TABLE `posts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `excerpt` TEXT NOT NULL,
  `content` LONGTEXT NOT NULL,
  `featured_image` VARCHAR(255) NULL,
  `meta_title` VARCHAR(255) NOT NULL,
  `meta_description` TEXT NOT NULL,
  `og_image` VARCHAR(255) NULL,
  `status` ENUM('draft','published') NOT NULL DEFAULT 'draft',
  `published_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_category_id_index` (`category_id`),
  KEY `posts_status_index` (`status`),
  KEY `posts_published_at_index` (`published_at`),
  CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL,
    'faqs' => <<<'SQL'
CREATE TABLE `faqs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `faqable_type` VARCHAR(255) NOT NULL,
  `faqable_id` BIGINT UNSIGNED NOT NULL,
  `question` VARCHAR(255) NOT NULL,
  `answer` TEXT NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faqs_faqable_type_faqable_id_index` (`faqable_type`, `faqable_id`),
  KEY `faqs_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL,
    'contact_messages' => <<<'SQL'
CREATE TABLE `contact_messages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `status` ENUM('new','read','resolved') NOT NULL DEFAULT 'new',
  `read_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_messages_email_index` (`email`),
  KEY `contact_messages_ip_address_index` (`ip_address`),
  KEY `contact_messages_status_index` (`status`),
  KEY `contact_messages_read_at_index` (`read_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL,
];

$tableOrder = [
    'categories',
    'users',
    'pages',
    'ad_slots',
    'settings',
    'tools',
    'document_templates',
    'posts',
    'faqs',
    'contact_messages',
];

function sqlValue(mixed $value, PDO $pdo): string
{
    if ($value === null) {
        return 'NULL';
    }

    if (is_bool($value)) {
        return $value ? '1' : '0';
    }

    if (is_int($value) || is_float($value)) {
        return (string) $value;
    }

    return $pdo->quote((string) $value);
}

$sql = [];
$sql[] = '-- BantuKerja.online MySQL import dump generated from local SQLite';
$sql[] = '-- Generated at '.date('Y-m-d H:i:s');
$sql[] = 'SET NAMES utf8mb4;';
$sql[] = 'SET FOREIGN_KEY_CHECKS=0;';
$sql[] = '';

foreach (array_reverse($tableOrder) as $table) {
    $sql[] = "DROP TABLE IF EXISTS `{$table}`;";
}

$sql[] = '';

foreach ($tableOrder as $table) {
    $sql[] = $schema[$table];
    $sql[] = '';

    $rows = $pdo->query("SELECT * FROM \"{$table}\"")->fetchAll(PDO::FETCH_ASSOC);

    if ($rows === []) {
        continue;
    }

    $columns = array_keys($rows[0]);
    $quotedColumns = implode(', ', array_map(fn ($column) => "`{$column}`", $columns));
    $values = [];

    foreach ($rows as $row) {
        $values[] = '('.implode(', ', array_map(
            fn ($column) => sqlValue($row[$column], $pdo),
            $columns,
        )).')';
    }

    $sql[] = "INSERT INTO `{$table}` ({$quotedColumns}) VALUES\n".implode(",\n", $values).';';
    $sql[] = '';
}

$sql[] = 'SET FOREIGN_KEY_CHECKS=1;';
$sql[] = '';

file_put_contents($outputPath, implode("\n", $sql));

fwrite(STDOUT, "Exported MySQL-compatible SQL to {$outputPath}\n");
