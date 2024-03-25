<?php

namespace Helpers;

use Exception;
use Helpers\Settings;

class ImageHelper
{
    const TARGET_DIR = __DIR__ . '/../public/storage/';

    public function saveImageFile(array $image): string
    {
        $imagePath = self::generateImagePath($image['name']);
        $targetFile = self::TARGET_DIR . $imagePath;

        // ディレクトリの存在を確認し、必要に応じて作成
        $dirPath = dirname($targetFile);
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
            chmod($dirPath, 0777);
        }

        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            return $imagePath;
        } else {
            return "";
        }
    }

    private static function generateImagePath(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $uniqueId = md5(uniqid(rand(), true));
        $dir = substr($uniqueId, 0, 2);
        $imagePath = "{$dir}/{$uniqueId}.{$extension}";

        return $imagePath;
    }

    /**
     * 一意な共有URLパスを生成
     *
     * @param string $filename
     * @return string
     */
    public static function generateImageShowPath(string $filename): string
    {
        $domain = Settings::env('DOMAIN');
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $uniqueId = md5(uniqid(rand(), true));

        return $domain . "/show" . "/media-type-" . $extension . "/" . $uniqueId;
    }

    /**
     * 一意な削除用URL生成
     *
     * @param string $filename
     * @return string
     */
    public static function generateImageDeletePath(string $filename): string
    {
        $domain = Settings::env('DOMAIN');
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $uniqueId = md5(uniqid(rand(), true));

        return  $domain . "/delete" . "/" . $extension . "/" . $uniqueId;
    }
}