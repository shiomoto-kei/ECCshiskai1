<?php
include 'db.php';

$search = filter_input(INPUT_GET,/*ボタンの変数 */"dummy");
$push = filter_input(INPUT_GET,"homeBtn");
try {
    //SQL分実行
    $sql = "SELECT * FROM /*テーブルの変数*/dummy2";
    $where = "";
    try{
         if($search == 1){
            $where = " where SAFETY = serach";
        }else if($search == 2){
            $where = " where SAFETY = serach";
        }
    }catch(PDOException $dummy){
        $dummy -> getMessage();
    }
    $sql = "SELECT * FROM /*テーブルの変数*/dummy3";
    $where = "";

    if($push == 1){
        $where = "where SAFETY = push";
    }

    //SQL実行結果の処理
    $stmt = $db ->prepare($sql . $where);

    if($search == 1){
        $stmt -> bindParam('dummy' , $search, PDO::PARAM_INT);
    }else if($search == 2){
        $stmt -> bindParam('dummy' , $search, PDO::PARAM_INT);
    }

    if($push == 1){
        $stmt -> bindParam('dummy' , $push,PDO::PARAM_INT);
    }
    
    //実行結果の更新
    $dummy = "UPDATE /*安否表のdbuser名*/ SET SAFERY where id SAFE_NO :";
    
    $stmt ->execute();

    //PDOオブジェクトの破棄
    $stmt = null;
    $db = null;


}catch (PDOException $dummy){
 exit("DBエラー" . $dummy->getMessage());
}