<?php

namespace Helpers;

use Database\MySQLWrapper;
use Exception;
use Helpers\ImageHelper;

class DatabaseHelper
{
    public static function getImages(): array
    {
        $db = new MySQLWrapper();
        $stmt = $db->prepare("SELECT * FROM images ORDER BY created_at DESC LIMIT 15");
        $stmt->execute();

        $result = $stmt->get_result();
        $imagesData = [];
        if ($result) {
            $imagesData = $result->fetch_all(MYSQLI_ASSOC);
        }

        return $imagesData;
    }

    public static function createImage(string $title, array $image, string $ipAddress): array
    {
        try {
            // 画像ファイル保存処理
            $imageHelper = new ImageHelper();
            $imagePath = $imageHelper->saveImageFile($image);

            if ($imagePath === "") {
                return [
                    'success' => false,
                    'message' => '画像ファイルの保存に失敗しました。'
                ];
            }

            $db = new MySQLWrapper();

            $sql = "INSERT INTO images (ip_address, title, image_path, image_url, delete_url, view_count, last_access_time, byte_size) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";

            if (!$stmt = $db->prepare($sql)) {
                throw new Exception('Failed to prepare statement: ' . $db->error);
            }

            $deleteUrl = ImageHelper::generateImageDeletePath($image['name']);
            $imageUrl = ImageHelper::generateImageShowPath($image['name']);
            $viewCount = 0;
            $byteSize = $image['size'];

            $stmt->bind_param('sssssii',
                $ipAddress,
                $title,
                $imagePath,
                $imageUrl,
                $deleteUrl,
                $viewCount,
                $byteSize
            );

            if (!$stmt->execute()) {
                return [
                    'success' => false,
                    'message' => 'データベースへの登録に失敗しました。',
                ];
            }

        } catch(Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }

        return [
            'success' => true,
            'message' => '画像の登録が完了しました。',
            'data' => [
                'imageUrl' => $imageUrl,
                'deleteUrl' => $deleteUrl,
            ],
        ];
    }

    public static function checkDailyUploadLimit(string $ipAddress): bool
    {
        $db = new MySQLWrapper();
        $today = date("Y-m-d");

        $stmt = $db->prepare(
            "SELECT COUNT(*) AS uploads_today
            FROM images
            WHERE ip_address = ?
            AND DATE(created_at) = ?"
        );
        $stmt->bind_param('ss', $ipAddress, $today);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $dailyUploadLimit = 4;
        if ($row) {
            return $row['uploads_today'] <= $dailyUploadLimit;
        } else {
            return true;
        }
    }

    public static function checkDailyUploadCapacityLimit(string $ipAddress, int $byteSize): bool
    {
        $db = new MySQLWrapper();
        $today = date("Y-m-d");

        $stmt = $db->prepare(
            "SELECT SUM(byte_size) AS total_bytes
            FROM images
            WHERE ip_address = ?
            AND DATE(created_at) = ?"
        );

        $stmt->bind_param('ss', $ipAddress, $today);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // 5MBの上限をバイトで指定
        $dailyUploadLimitBytes = 5 * 1024 * 1024;

        if ($row && $row['total_bytes'] !== null) {
            return $row['total_bytes'] + $byteSize <= $dailyUploadLimitBytes;
        } else {
            return true;
        }
    }

    public static function getImage(string $uri): array
    {
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT * FROM images WHERE image_url = ?");
        $stmt->bind_param('s', $uri);
        $stmt->execute();

        $result = $stmt->get_result();
        $imageData = $result->fetch_assoc();

        if ($imageData === null) {
            return [];
        } else {
            return $imageData;
        }
    }

    public static function deleteImage(string $deleteUrl): bool
    {
        $db = new MySQLWrapper();

        // 該当する画像が存在するか確認
        $stmt = $db->prepare("SELECT * FROM images WHERE delete_url = ?");
        $stmt->bind_param('s', $deleteUrl);
        $stmt->execute();
        $result = $stmt->get_result();
        $imageData = $result->fetch_assoc();

        // 該当する画像がデータベースに存在しない場合、falseを返す
        if ($imageData === null) {
            return false;
        }

        // データベースから画像情報を削除
        $stmt = $db->prepare("DELETE FROM images WHERE delete_url = ?");
        $stmt->bind_param('s', $deleteUrl);
        $success = $stmt->execute();

        // データベースの削除が成功した場合、ファイルを削除
        if ($success) {
            $filePath = __DIR__ . "/../public/storage/" . $imageData['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);

                // フォルダが空の場合、削除
                $directoryPath = dirname($filePath);
                if (is_dir($directoryPath) && count(scandir($directoryPath)) === 2) {
                    rmdir($directoryPath);
                }
            } else {
                return false;
            }
        }

        return $success;
    }

    public static function incrementViewCount(string $uri): void
    {
        $db = new MySQLWrapper();
        $stmt = $db->prepare("UPDATE images SET view_count = view_count + 1 WHERE image_url = ?");
        $stmt->bind_param('s', $uri);
        $stmt->execute();
    }

}