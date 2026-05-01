<?php

include 'db.php';
session_start();
$syousaiID=$_SESSION['syousaiID'];

 try{

        //SQL分 [:id]の部分はあとでもらってきたIDが入る
        $sql=$db->prepare("SELECT * FROM employee WHERE emp_no = :id");

        
        //ここでさっきの[:id]に[$syousaiID]が入る
        $stmt->bindparam(':id',$syousaiID);
        $stmt->execute();
        $usersyousai=$stmt->fetchall();

        $sql_posi=$db->prepare("SELECT * FROM position  WHERE position_no = :posi");
          $stmt->bindparam('posi', $syousaiID['position']);
        $stmt->execute();
        $user_posi=$stmt->fetchall();

        

    }
    catch(PDOException $e){
        echo 'DBエラー'.$e->getMessage();
    }


    if(isset($_GET["submitTuika"])){
    // $_SESSION['syousaiID']=$ID;
    //  header('Location: syousai.php');
    //  exit;
//$emp_no = $_POST['emp_no'];
$password = $_POST['password_hash'];
$ename = $_POST['ename'];
$birthday = $_POST['birthday'];
$tel = $_POST['tel'];
$address = $_POST['address'];
$position = $_POST['position'];
$section= $_POST['section'];
$jname= $_POST['jname'];
$pname= $_POST['pname'];

if(!empty($emp_no) && !empty($password) &&!empty($ename) &&!empty($birthday) &&!empty($tel) &&!empty($address) &&!empty($position) && !empty($section) && !empty($pname)&& !empty($jname)){
try{

$password_hash =$password_hash($password);

      $sql_in=$db->prepare("insert into employee (emp_no,password_hash,ename,birthday,tel,address,position,section) values (:emp_no,:password_hash,:ename,:birthday,:tel,:address,:position,:section)");

 $sql_in->execute([
                ':emp_no' => $emp_no,
                ':password_hash' =>$password_hash,
                ':ename' =>$ename,
                ':birthday' => $birthday,
                ':tel' => $tel ,
                ':address' =>$address,
                ':position' =>$position,
                ':section' =>$section

        ]);

             $sql_j=$db->prepare("insert into position (pname) values (:pname");
            $sql_p->execute([
                ':pname' => $pname
            ]);

            $sql_j=$db->prepare("insert into section (jname) values (:jname");
            $sql_j->execute([
                ':jname' => $jname
            ]);

       
        $db->commit();
}catch(PDOException $e){
    
 echo 'DBエラー'.$e ->getMessage();

}
}

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

<!-- 全安否情報の初期化 -->
<div>
    <button type="button" id="hennsyuuBton">編集</button>
</div>

 <dialog id="dialoghennsyuu">
    <?php echo $syousaiID['emp_no'] ?><br>
    名前: <input type="text" name="ename" required><br>
    住所:<input type="text" name="address" required><br>
    電話番号: <input type="number" name="tel" required><br>
    生年月日: <input type="text" name="birhday" required><br>
    役職番号: <in番号t type="number" name="pname" required><br>
    役職名: <input type="text" name="position" required><br>
    部署番号: <input type="number" name="section" required><br>
      部署名: <input type="text" name="jname" required><br>
    パスワード: <input type="text" name="password_hash" required><br>

    <button type="button" name="submitTuika">決定</button>
     <button type="button" id="closeModeBtn">キャンセル</button>


    <form method="get" action="syousai.php">
    <button type="button" name="sakujyoButton">削除</button>
     <button type="button" id="closeModeBtn">キャンセル</button>
    </form>

 </dialog>

 <?php if (!empty($syousaiID)): ?>
<tr>
    <td><?php echo $syousaiID['emp_no'] ?></td> 
    <td><?php echo $syousaiID['password_hash'] ?></td> 
    <td><?php echo $syousaiID['ename'] ?></td> 
    <td><?php echo $syousaiID['birthday'] ?></td> 
    <td><?php echo $syousaiID['tel'] ?></td> 
    <td><?php echo $syousaiID['address'] ?></td> 
    <td><?php echo $user_posi['pname'] ?></td>
    <td><?php echo $syousaiID['section'] ?></td>
    <td><?php echo $syousaiID['image_path'] ?></td>
   
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








