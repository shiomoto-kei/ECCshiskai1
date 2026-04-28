<?php

include 'db.php';
session_start();
$ID= $_SESSION['ID'];
 try{
    //すべて取ってくる処理
        $sql_ALL=$db->prepare("SELECT p.pname, e.emp_no,e.ename,e.emp,s.safety FROM safety as s left join employee as e on s.safe_no = e.emp_no left join position as p on e.position = p.position_no");
        $sql_ALL->execute();
        $ALLusere=$sql_ALL->fetchall();

        //IDだけをとってくる処理
        // $sql_ID=db->prepare("SELECT emp_no FROM employee");
        // $sql_ID->execute();
        // $ALLID=$sql_ID->fetchall();



    }
    catch(PDOException $e){
        echo 'DBエラー';
    }
if(isset($_GET["sumitButton"])){
 try{
    //全ての安否情報の値を初期値の１にする
        $sql_update=$db->prepare("update safety  set safety = 1");
        $sql_update->execute();
        

        //IDだけをとってくる処理
        // $sql_ID=db->prepare("SELECT emp_no FROM employee");
        // $sql_ID->execute();
        // $ALLID=$sql_ID->fetchall();



    }
    catch(PDOException $e){
        echo 'DBエラー';
    }
}
if(isset($_GET["syousaiButton"])){
    $_SESSION['syousaiID']=$ID;
     header('Location: syousai.php');
     exit;
}



?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>


<!-- 一覧情報 -->
 <form method="get" action="itiran.php">
<?php foreach ($ALLusere['emp_no'] as $ID):?>

<button type="submit" name="syousaiButton"> 
<p><?php $ID['safety']?><?php echo $ID['emp_no'] ?><?php echo $ID['ename']?><?php echo $ID['pname']?>></p><br>  
</button>
 <?php endforeach;?>
</form>



<!-- 全安否情報の初期化 -->
 <dialog id="dialogallsadel">

    <form method="get" action="itiran.php">

    <button type="button" name="submitButton">安否情報の初期化</button>
     <button type="button" id="closeModeBtn">キャンセル</button>
    </form>
 </dialog>


//この処理は詳細データが表示された画面で、 userがクリックされたら走る
// include 'db.php';
// session_start();
//
//
//クリックしたそのuserのIDを保存
//
//$input_syousai=クリックしたそのuserのID
//~~~~~~~~~~~~~~
// ~~~~~~~~~~~~~
// ~~~~~~~~~~~~~
// ~~~~~~~~~~~~~
//if(!empty($input_syousai))
// $_SESSION['syousaiID']=$input_syousai;
// header('Location: $syousai.php');
// exit;
</body>
</html>








