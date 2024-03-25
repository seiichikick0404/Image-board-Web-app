<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class AddByteSizeToImagesTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "ALTER TABLE images ADD COLUMN byte_size BIGINT UNSIGNED DEFAULT 0"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return [
            "ALTER TABLE images DROP COLUMN byte_size"
        ];
    }
}