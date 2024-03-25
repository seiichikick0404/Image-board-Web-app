<?php
use Database\MySQLWrapper;

$mysqli = new MySQLWrapper();

// carsテーブル作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/Examples/cars-setup.sql'));

if($result === false) throw new Exception('Could not execute query.');
else print("Successfully ran all SQL setup queries.".PHP_EOL);


// parts テーブルを作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/Examples/parts-setup.sql'));

if ($result === false) {
    throw new Exception('Could not create parts table.');
} else {
    print("Successfully created parts table.".PHP_EOL);
}

// cars_parts テーブルを作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/Examples/cars-parts-setup.sql'));

if ($result === false) {
    throw new Exception('Could not create cars_parts table.');
} else {
    print("Successfully created cars_parts table.".PHP_EOL);
}

