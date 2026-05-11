<?php
$host = "localhost"; //MySQL(サーバー)
$dbname = "safety_system_db";//dbname(データベース名)
$user = "root";//username(ユーザー名)
$pass = "root";//password(パスワード)

try {
    //mysqlに接続(PDO)
    $db = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,//MYSQLのusername(ユーザー名)
        $pass//password(パスワード)
    );

    //エラー時に例外を投げる設定
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo 'console_log("MYSQLに接続できました!")';


    //↓↓↓↓二重になってたのでコメントアウトしました↓↓↓↓

    // $pdo = new PDO(
    //     "mysql:host={$host};dbname={$dbname};charset=utf8",
    //     $user,
    //     $pass,
    //     [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    // );

} catch (PDOException $e) {
    echo "接続エラー:" . $e->getMessage();
}