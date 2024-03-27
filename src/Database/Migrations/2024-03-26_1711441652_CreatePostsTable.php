<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreatePostsTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            'CREATE TABLE posts (
                post_id INT AUTO_INCREMENT PRIMARY KEY,
                reply_to_id INT NULL,
                subject VARCHAR(255) NULL,
                content TEXT NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                image_path VARCHAR(255) NULL,
                FOREIGN KEY (reply_to_id) REFERENCES posts(post_id) ON DELETE SET NULL
            );'
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ['DROP TABLE IF EXISTS posts;'];
    }
}