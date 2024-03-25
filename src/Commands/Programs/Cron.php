<?php

namespace Commands\Programs;

use Commands\AbstractCommand;
use Database\MySQLWrapper;
use Commands\Argument;
use Exception;
use Helpers\Settings;

class Cron extends AbstractCommand
{
    // 使用するコマンド名を設定
    protected static ?string $alias = 'cron';

    // 引数を割り当て
    public static function getArguments(): array
    {
        return [];
    }

    public function execute(): int
    {
        $this->log('Checking data for deletion...');

        $this->deleteOneMonthAgoData();

        return 0;
    }

    private function deleteOneMonthAgoData(): void
    {
        $db = new MySQLWrapper();

        // 1ヶ月前の日時を計算
        date_default_timezone_set('Asia/Tokyo');
        $oneMonthAgo = date('Y-m-d H:i:s', strtotime('-1 month'));

        // 削除対象の画像ファイルパスを取得
        $stmt = $db->prepare("SELECT image_path FROM images WHERE last_access_time < ?");
        $stmt->bind_param('s', $oneMonthAgo);
        $stmt->execute();
        $result = $stmt->get_result();

        $imageDir = __DIR__ . '/../../public/storage/';
        $deleteCount = 0;
        $directoriesToCheck = [];

        while ($row = $result->fetch_assoc()) {
            $filePath = $imageDir . $row['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
                $deleteCount++;

                // ディレクトリを記録
                $directoriesToCheck[dirname($filePath)] = true;
            }
        }

        // 空のディレクトリを削除
        foreach (array_keys($directoriesToCheck) as $dirPath) {
            if (is_dir($dirPath) && count(scandir($dirPath)) == 2) {
                rmdir($dirPath);
            }
        }

        // 削除対象があった場合、データベースレコードの削除
        if ($deleteCount > 0) {
            $stmt = $db->prepare("DELETE FROM images WHERE last_access_time < ?");
            $stmt->bind_param('s', $oneMonthAgo);
            $stmt->execute();

            $this->log("$deleteCount images were deleted.");
        } else {
            $this->log("No images were deleted.");
        }
    }


}