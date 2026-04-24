<?php

include 'db.php';
session_start();
 try{

        //IDだけをとってくる処理
        $sql_ID=db->prepare("SELECT user_id FROM user");
        $sql_ID->execute();
        $ALLID=$sql_ID->fetchall();

    }
    catch(PDOException $e){
        echo 'DBエラー';
    }



?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


<?php foreach ($ALLID as $ID):?>

 <p><?php echo $ID ?></p><br>  

 <?php endforeach;?>


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








