<?php

namespace Helpers;

use Types\ValueType;

class ValidationHelper
{
    public static function integer($value, float $min = -INF, float $max = INF): int
    {
        // PHPには、データを検証する組み込み関数があります。詳細は https://www.php.net/manual/en/filter.filters.validate.php を参照ください。
        $value = filter_var($value, FILTER_VALIDATE_INT, ["min_range" => (int) $min, "max_range"=>(int) $max]);

        // 結果がfalseの場合、フィルターは失敗したことになります。
        if ($value === false) throw new \InvalidArgumentException("The provided value is not a valid integer.");

        // 値がすべてのチェックをパスしたら、そのまま返します。
        return $value;
    }

    public static function string($value, int $minLength = 0, int $maxLength = PHP_INT_MAX): string
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException("The provided value is not a valid string.");
        }

        $length = mb_strlen($value);
        if ($length < $minLength || $length > $maxLength) {
            throw new \InvalidArgumentException(sprintf("The provided string is not within the valid length range of %d to %d.", $minLength, $maxLength));
        }

        return $value;
    }


    public static function validateDate(string $date, string $format = 'Y-m-d'): string
    {
        $d = \DateTime::createFromFormat($format, $date);
        if ($d && $d->format($format) === $date) {
            return $date;
        }

        throw new \InvalidArgumentException(sprintf("Invalid date format for %s. Required format: %s", $date, $format));
    }

    public static function validateFields(array $fields, array $data): array
    {
        $validatedData = [];

        foreach ($fields as $field => $type) {
            if (!isset($data[$field]) || ($data)[$field] === '') {
                throw new \InvalidArgumentException("Missing field: $field");
            }

            $value = $data[$field];

            $validatedValue = match ($type) {
                ValueType::STRING => is_string($value) ? $value : throw new \InvalidArgumentException("The provided value is not a valid string."),
                ValueType::INT => self::integer($value), // You can further customize this method if needed
                ValueType::FLOAT => filter_var($value, FILTER_VALIDATE_FLOAT),
                ValueType::DATE => self::validateDate($value),
                default => throw new \InvalidArgumentException(sprintf("Invalid type for field: %s, with type %s", $field, $type)),
            };

            if ($validatedValue === false) {
                throw new \InvalidArgumentException(sprintf("Invalid value for field: %s", $field));
            }

            $validatedData[$field] = $validatedValue;
        }

        return $validatedData;
    }

    public static function saveRequest(?string $subject, ?string $content, $image): array
    {
        $validated = [
            'success' => true,
            'errors' => [
                'subject' => [],
                'content' => [],
                'image' => [],
            ],
        ];

        // タイトルのnullチェックと文字数制限チェック
        if (empty($subject)) {
            array_push($validated['errors']['subject'], "タイトルを入力してください。");
            $validated['success'] = false;
        } elseif (mb_strlen($subject) > 255) {
            array_push($validated['errors']['subject'], "タイトルは255文字以内で入力してください。");
            $validated['success'] = false;
        }

        // 内容のnullチェックと文字数制限チェック
        if (empty($content)) {
            array_push($validated['errors']['content'], "内容を入力してください。");
            $validated['success'] = false;
        } elseif (mb_strlen($content) > 1000) {
            array_push($validated['errors']['content'], "内容は1000文字以内で入力してください。");
            $validated['success'] = false;
        }

        // 画像の添付有無、容量制限チェック（3MB以下),  拡張子チェック
        if (empty($image['name'])) {
            array_push($validated['errors']['image'], "画像を添付してください。");
            $validated['success'] = false;
        } elseif ($image['size'] > 3145728) {
            array_push($validated['errors']['image'], "画像ファイルのサイズは3MB以下にしてください。");
            $validated['success'] = false;
        } else {
            // 拡張子チェック
            $extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];
            if (!in_array($extension, $allowedExtensions)) {
                array_push($validated['errors']['image'], "許可されていないファイル形式です。JPEG、PNG、GIFのみが許可されています。");
                $validated['success'] = false;
            }
        }


        return $validated;
    }

    public static function saveRequestByReply(?string $content, $image, ?int $replyToId): array
    {
        $validated = [
            'success' => true,
            'hasImage' => false,
            'errors' => [
                'content' => [],
                'image' => [],
                'reply_to_id' => [],
            ],
        ];

        // 内容のnullチェックと文字数制限チェック
        if (empty($content)) {
            array_push($validated['errors']['content'], "内容を入力してください。");
            $validated['success'] = false;
        } elseif (mb_strlen($content) > 1000) {
            array_push($validated['errors']['content'], "内容は1000文字以内で入力してください。");
            $validated['success'] = false;
        }

        // 画像が提供された場合のみ、サイズと拡張子のチェックを行う
        if (!empty($image['name'])) {
            $validated['hasImage'] = true;
            if ($image['size'] > 3145728) { // 3MB
                array_push($validated['errors']['image'], "画像ファイルのサイズは3MB以下にしてください。");
                $validated['success'] = false;
            } else {
                // 拡張子チェック
                $extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];
                if (!in_array($extension, $allowedExtensions)) {
                    array_push($validated['errors']['image'], "許可されていないファイル形式です。JPEG、PNG、GIFのみが許可されています。");
                    $validated['success'] = false;
                }
            }
        }

        // リプライIDの方チェック
        if ($replyToId === null) {
            array_push($validated['errors']['reply_to_id'], "投稿IDが存在しません");
            $validated['success'] = false;
        }


        return $validated;
    }
}