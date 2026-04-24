<?php
include 'db.php';


$search = filter_input(INPUT_GET,/*YESボタンの変数 */"dummy");
$point = filter_input(INPUT_GET,/*NOボタンの変数*/);
try {
    //SQL分実行
    $sql = "SELECT * FROM dummy";
    $where = "";

    if($search == 1){
        $where = "";
    }else if($point == 2){

    }


    
    //SQL実行結果の処理


















    //PDOオブジェクトの破棄
    $stmt = null;
    $db = null;


    }catch (PDOException $dummy){
        exit("DBエラー" . $dummy->getMessage());
    }