<?php
$host = "dummy"; //user(ユーザー)
$dbname = "dummy1";//dbname(データベース名)
$user = "dummy2";//username(ユーザー名)
$pass = "dummy3";//password(パスワード)

try{
    //mysqlに接続(PDO)
    $db = new PDO(
        "mysql:host=$host;dname=$dname;charset=utf8",
        $dummy3,//MYSQLのusername(ユーザー名)
        $dummy4//password(パスワード)
    );

    //エラー時に例外を投げる設定
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
    echo "MYSQLに接続できました!";

    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
}catch (PDOException $e) {
    echo "接続エラー:" . $e->getMessage();
}