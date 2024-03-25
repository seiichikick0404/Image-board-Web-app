<?php


// タイムゾーンを日本時間に設定
date_default_timezone_set('Asia/Tokyo');

// コマンドを定義
$command = 'php console cron';

// コマンド実行
$output = shell_exec($command);

// ログファイルのパス
$logFilePath = __DIR__ . '/cron_execution_log.txt';

// 現在の日時
$currentDateTime = date('Y-m-d H:i:s');

// ログメッセージ
$logMessage = "Executed at: " . $currentDateTime . "\n" . "Command: " . $command . "\n" . "Output: " . $output . "\n\n";

// ログファイルに結果を追記
file_put_contents($logFilePath, $logMessage, FILE_APPEND);

echo "Command executed and logged.\n";
