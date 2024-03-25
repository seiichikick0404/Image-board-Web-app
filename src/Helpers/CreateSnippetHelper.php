<?php
namespace Helpers;

class CreateSnippetHelper
{
    /**
     * 保存処理
     */
    public static function createSnippet(array $validatedData, string $uniquePath): void
    {
        DatabaseHelper::storeSnippet($validatedData, $uniquePath);

        // TODO 新規パスに遷移する
        header("Location: ../snippet/show?uniqueKey=" . $uniquePath);
        exit;
    }
    /**
     * ランダムなデータと現在のタイムスタンプを組み合わせて一意な値を生成
     *
     * @return string
     */
    public static function generatePath()
    {
        $uniqueData = uniqid(rand(), true);

        return hash('sha256', $uniqueData);
    }
}