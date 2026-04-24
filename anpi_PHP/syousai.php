<?php

include 'db.php';
session_start();
$syousaiID=$_SESSION['syousaiID'];

 try{

        //SQL分 [:id]の部分はあとでもらってきたIDが入る
        $sql=db->prepare("SELECT * FROM user WHRE user_id = :id");
        
        //ここでさっきの[:id]に[$syousaiID]が入る
        $stmt->bindparam(':id',$syousaiID);
        $stmt->execute();
        $usersyousai=$stmt->fetchall();

        

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



 <?php if (!empty($syousaiID)): ?>
<tr>
    <td><?php echo $syousaiID[''] ?></td> 
    <td><?php echo $syousaiID[''] ?></td> 
    <td><?php echo $syousaiID[''] ?></td> 
    <td><?php echo $syousaiID[''] ?></td> 
    <td><?php echo $syousaiID[''] ?></td> 
    <td><?php echo $syousaiID[''] ?></td> 
    <td><?php echo $syousaiID[''] ?></td>
</tr>
<?php endif; ?>


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








