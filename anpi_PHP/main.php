<?php
include 'db.php';

$search = filter_input(INPUT_GET,/*ボタンの変数 */ "radio-box");
$push = filter_input(INPUT_GET,"host");
try {
    //SQL分実行
    $sql = "SELECT * FROM /*テーブルの変数*/SAFETY";
    $where = "";
    try{
         if($search == 1){
            $where = " where /SAFETY/ = serach";
        }else if($search == 2){
            $where = " where /SAFETY/ = serach";
        }
    }catch(PDOException $dummy){
        $dummy -> getMessage();
    }

    if($_SERVER['push'] ==='host'){
    }
    
    //SQL実行結果の処理
    $stmt = $db ->prepare($sql . $where);

    if($search == 1){
        $stmt -> bindParam('SAFETY' , $search, PDO::PARAM_INT);
    }else if($search == 2){
        $stmt -> bindParam('SAFETY' , $search, PDO::PARAM_INT);
    }

    $push = $db->prepare("SELECT E.ENAME, S.SAFETY FROM EMPLOYEE AS E
                          JOIN SAFETY AS S ON(E.EMP_NO = S.SAFE_NO) 
                          ");
    $push->execute();
    $row = $ENAME->fetch(PDO::FETCH_ASSOC);
    //データがない場合
    $SAFETY = $row ? $row['SAFETY'] : 'データなし';
    
    //実行結果の更新
    $dummy = "UPDATE /*安否表のdbuser名*/ SET /*安否表のdbのテーブル名*/ SAFETY where id/*安否表の変数id*/ :";
    
    $stmt ->execute();

    //PDOオブジェクトの破棄
    $stmt = null;
    $db = null;


}catch (PDOException $dummy){
 exit("DBエラー" . $dummy->getMessage());
}