<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateImagesTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE images (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ip_address VARCHAR(45) NOT NULL,
                title VARCHAR(255) NOT NULL,
                image_path VARCHAR(255) NOT NULL,
                image_url VARCHAR(255) NOT NULL,
                delete_url VARCHAR(255) NOT NULL,
                view_count INT DEFAULT 0,
                last_access_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "DROP TABLE IF EXISTS images"
        ];
    }
}