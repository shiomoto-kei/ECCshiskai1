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
    }catch(PDOException $stmt){
        $stmt -> getMessage();
    }

    if($_SERVER ==='push'){
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
    $row = $push->fetch(PDO::FETCH_ASSOC);
    //データがない場合
    $SAFETY = $row ? $row['SAFETY'] : 'データなし';
    
    //実行結果の更新
    $push = "UPDATE /*安否表のdbuser名*/ safety_system_db SET /*安否表のdbのテーブル名*/ SAFETY where SAFETY/*安否表の変数id*/ SAFE_NO:";
    
    $stmt ->execute();

    //PDOオブジェクトの破棄
    $stmt = null;
    $db = null;


}catch (PDOException $dummy){
 exit("DBエラー" . $dummy->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>安否確認</title>
<link rel="stylesheet" href="../css/chosescreen.css">
</head>

<body>

<div class="wrapper">
  <div class="form-box">

    <div class="top-bar">
        <button class="menu-btn">☰</button>

        <button class="logout-btn"
            onclick="location.href='/loginscreen.html'">
            Logout
        </button>
    </div>

    <h1>安否確認</h1>

    <form action="staff.html" method="get">

        <p class="question">被害に遭われましたか？</p>

        <label class="radio-box">
            <input type="radio" name="status" value="yes">
            Yes
        </label>

        <label class="text-box">
            <textarea name="text_yes"placeholder="詳細な内容を記入してください"></textarea>
        </label>

        <label class="radio-box">
            <input type="radio" name="status" value="no">
            No
        </label>

        <label class="text-box">
            <textarea name="text_no"placeholder="詳細な内容を記入してください"></textarea>
        </label>

        <button type="submit">送信</button>

    </form>

  </div>
</div>

</body>
</html>