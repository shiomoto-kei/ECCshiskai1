<?php
include 'db.php';

$search = filter_input(INPUT_GET,/*ボタンの変数 */"radio-box");
$push = filter_input(INPUT_GET,"homeBtn");
try {
    //SQL分実行
    $sql = "SELECT * FROM /*テーブルの変数*/SAFETY";
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
    $sql = "SELECT * FROM /*テーブルの変数*/SAFETY";
    $where = "";

    if($push == 1){
        $where = "where SAFETY = push";
    }

    //SQL実行結果の処理
    $stmt = $db ->prepare($sql . $where);

    if($search == 1){
        $stmt -> bindParam('SAFETY' , $search, PDO::PARAM_INT);
    }else if($search == 2){
        $stmt -> bindParam('SAFETY' , $search, PDO::PARAM_INT);
    }

    if($push == 1){
        $stmt -> bindParam('' , $push,PDO::PARAM_INT);
    }
    
    //実行結果の更新
    $dummy = "UPDATE /*安否表のdbuser名*/ SAFETY SET SAFETY where id SAFE_NO :";
    
    $stmt ->execute();

    //PDOオブジェクトの破棄
    $stmt = null;
    $db = null;


}catch (PDOException $dummy){
 exit("DBエラー" . $dummy->getMessage());
}