<?php

// Prepare /tmp storage and SQLite database for Vercel Serverless environment
$dirs = [
    '/tmp/storage',
    '/tmp/storage/app',
    '/tmp/storage/framework',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Copy pre-seeded SQLite database to writable /tmp directory if using sqlite
$sourceSqlite = __DIR__ . '/../database/database.sqlite';
$targetSqlite = '/tmp/database.sqlite';
if (file_exists($sourceSqlite) && !file_exists($targetSqlite)) {
    @copy($sourceSqlite, $targetSqlite);
}

require __DIR__ . '/../public/index.php';
