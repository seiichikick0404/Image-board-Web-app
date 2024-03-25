<?php
use Database\MySQLWrapper;

$mysqli = new MySQLWrapper();

// usersテーブル作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/users-setup.sql'));

if($result === false) throw new Exception('Could not execute query.');
else print("Successfully created users table.".PHP_EOL);


// posts テーブルを作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/posts-setup.sql'));

if ($result === false) {
    throw new Exception('Could not create posts table.');
} else {
    print("Successfully created posts table.".PHP_EOL);
}

// comments テーブルを作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/comments-setup.sql'));

if ($result === false) {
    throw new Exception('Could not create comments table.');
} else {
    print("Successfully created comments table.".PHP_EOL);
}

// post-likes テーブルを作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/post-likes-setup.sql'));

if ($result === false) {
    throw new Exception('Could not create post-likes table.');
} else {
    print("Successfully created post-likes table.".PHP_EOL);
}

// comment-likes テーブルを作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/comment-likes-setup.sql'));

if ($result === false) {
    throw new Exception('Could not create comment-likes table.');
} else {
    print("Successfully created comment-likes table.".PHP_EOL);
}

// user-settingsテーブル作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/user-settings-setup.sql'));

if ($result === false) {
    throw new Exception('Could not create user-settings.');
} else {
    print("Successfully created user-settings.".PHP_EOL);
}

// categoriesテーブル作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/categories-setup.sql'));

if ($result === false) {
    throw new Exception('Could not create categories.');
} else {
    print("Successfully created categories.".PHP_EOL);
}

// tagsテーブル作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/tags-setup.sql'));

if ($result === false) {
    throw new Exception('Could not create tags.');
} else {
    print("Successfully created tags.".PHP_EOL);
}

// posts_tagsテーブル作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/posts-tags-setup.sql'));

if ($result === false) {
    throw new Exception('Could not create posts-tags.');
} else {
    print("Successfully created posts-tags.".PHP_EOL);
}

// cachesテーブル作成
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/caches-setup.sql'));

if ($result === false) {
    throw new Exception('Could not create caches.');
} else {
    print("Successfully created caches.".PHP_EOL);
}

//users-add-columnを実行
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/users-add-column.sql'));

if ($result === false) {
    throw new Exception('Could not update users-add-column table.');
} else {
    print("Successfully updated users-add-column table.".PHP_EOL);
}

// posts-add-columnを実行
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/posts-add-column.sql'));
if ($result === false) {
    throw new Exception('Could not update posts-add-column table.');
} else {
    print("Successfully updated posts-add-column table.".PHP_EOL);
}

// posts-add-foreignkeyを実行
$result = $mysqli->query(file_get_contents(__DIR__ . '/BlogBook/posts-add-foreignkey.sql'));
if ($result === false) {
    throw new Exception('Could not update posts-add-foreignkey table.');
} else {
    print("Successfully updated posts-add-foreignkey table.".PHP_EOL);
}