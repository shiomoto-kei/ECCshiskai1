<?php
include 'db.php';

$search = filter_input(INPUT_GET,/*ボタンの変数 */"dummy");

try {
    //SQL分実行
    $sql = "SELECT * FROM /*テーブルの変数*/dummy2";
    $where = "";
    try{
         if($search == 1){
            $where = " where /*安否表*/ = serach";
        }else if($search == 2){
            $where = " where /*安否表*/ = serach";
        }
    }catch(PDOException $dummy){
        $dummy -> getMessage();
    }

    //SQL実行結果の処理
    $stmt = $db ->prepare($sql . $where);

    if($search == 1){
        $stmt -> bindParam('dummy' , $search, PDO::PARAM_INT);
    }else if($search == 2){
        $stmt -> bindParam('dummy' , $search, PDO::PARAM_INT);
    }
    
    //実行結果の更新
    $dummy = "UPDATE /*安否表のdbuser名*/ SET /*安否表のdbのテーブル名*/ where id/*安否表の変数id*/ :";
    
    $stmt ->execute();

    //PDOオブジェクトの破棄
    $stmt = null;
    $db = null;


}catch (PDOException $dummy){
 exit("DBエラー" . $dummy->getMessage());
}