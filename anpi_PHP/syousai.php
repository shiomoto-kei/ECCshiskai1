<?php

include 'db.php';
session_start();
$syousaiID=$_SESSION['syousaiID'];

 try{
        //そのユーザーのすべての情報
        $sql_ALLuser=$db->prepare("SELECT p.pname, e.emp_no,e.ename,e.position,s.safety FROM employee as e 
        left join safety as s on   e.emp_no = s.safe_no
        left join position as p on e.position = p.position_no  
        left join section as se on e.section = se.section_no
        where e.emp_no = :emp_no");
        $sql_ALLuser->execute([':emp_no' =>$syousaiID['emp_no']]);
        $usersyousai=$sql_ALL->fetchall();



        // //SQL分 [:id]の部分はあとでもらってきたIDが入る
        // $sql=$db->prepare("SELECT * FROM employee WHERE emp_no = :id");
        // //ここでさっきの[:id]に[$syousaiID]が入る
        // $stmt->bindparam(':id',$syousaiID);
        // $stmt->execute();
        // $usersyousai=$stmt->fetchall();
        // $sql_posi=$db->prepare("SELECT * FROM position  WHERE position_no = :posi");
        //   $stmt->bindparam('posi', $syousaiID['position']);
        // $stmt->execute();
        // $user_posi=$stmt->fetchall();

        

    }
    catch(PDOException $e){
        echo 'DBエラー'.$e->getMessage();
    }



    //従業員の情報変更
    if(isset($_GET["submitHen"])){
$password = $_POST['password_hash'];
$ename = $_POST['ename'];
$birthday = $_POST['birthday'];
$tel = $_POST['tel'];
$address = $_POST['address'];
$position = $_POST['position'];
$section= $_POST['section'];

$sname= $_POST['sname'];
$pname= $_POST['pname'];

// if(empty($emp_no)){
//     $emp_no=$syousaiID['emp_no']; 
// }
if(empty($password)){
    $password=$usersyousai['password_hash']; 
}
if(empty($ename)){
    $ename=$usersyousai['ename']; 
}
if(empty($birthday)){
    $birthday=$usersyousai['birthday']; 
}
if(empty($tel)){
    $tel=$usersyousai['tel']; 
}
if(empty($address)){
    $address=$usersyousai['address']; 
}
if(empty($position)){
    $position=$usersyousai['position']; 
}
if(empty($section)){
    $section=$usersyousai['section']; 
}



if(empty($pname)){
    $pname=$usersyousai['pname']; 
}
if(empty($sname)){
    $sname=$usersyousai['sname']; 
}


if(!empty($emp_no) && !empty($password) &&!empty($ename) &&!empty($birthday) &&!empty($tel) &&!empty($address) &&!empty($position) && !empty($section) && !empty($pname)&& !empty($sname)){
try{

$password_hash =$password_hash($password);

      $sql_in=$db->prepare("update employee set password_hash = :password_hash,ename = :ename,birthday = :birthday,tel = :tel,address = :address,position = :position,section = :section where emp_no = :emp_no");

 $sql_in->execute([
                ':password_hash' =>$password_hash,
                ':ename' =>$ename,
                ':birthday' => $birthday,
                ':tel' => $tel ,
                ':address' =>$address,
                ':position' =>$position,
                ':section' =>$section,
                ':emp_no' =>$usersyousai['emp_no']

        ]);

             $sql_p=$db->prepare(" update  position set pname = :pname ,position_no = :position where emp_no = :emp_no");
            $sql_p->execute([
                 ':position' =>$position,
                ':pname' => $pname,
                ':emp_no' =>$$usersyousai['emp_no']
            ]);

            $sql_s=$db->prepare("update section set sname = :sname,section_no = :section where emp_no = :emp_no");
            $sql_s->execute([
                ':section' =>$section,
                ':sname' =>$sname,
                 ':emp_no' =>$$usersyousai['emp_no']
            ]);

       
        $db->commit();
}catch(PDOException $e){
    
 echo 'DBエラー'.$e ->getMessage();

}

}
}
 if(isset($_GET["sakujyoButton"])){
    try{
    
        $sql_update=$db->prepare("update employee  set IS_DELETED = 1 where emp_no = :emp_no");
        $sql_update->execute([ ':emp_no' =>$usersyousai['emp_no']]);
        
    }
    catch(PDOException $e){
        echo 'DBエラー';
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

<!-- 編集 -->
<div>
    <button type="button" id="hennsyuuBton">編集</button>
</div>

 <dialog id="dialoghennsyuu">
    <?php echo $syousaiID['emp_no'] ?><br>
    名前: <input type="text" name="ename" required><br>
    住所:<input type="text" name="address" required><br>
    電話番号: <input type="number" name="tel" required><br>
    生年月日: <input type="text" name="birhday" required><br>
    役職番号: <int type="number" name="position" required><br>
    役職名: <input type="text" name="pname" required><br>
    部署番号: <input type="number" name="section" required><br>
      部署名: <input type="text" name="sname" required><br>
    パスワード: <input type="text" name="password_hash" required><br>

    <button type="button" name="submitHen">決定</button>
     <button type="button" id="closeModeBtn">キャンセル</button>


    <form method="get" action="syousai.php">
    <button type="button" name="sakujyoButton">削除</button>
     <button type="button" id="closeModeBtn">キャンセル</button>
    </form>

 </dialog>

 <?php if (!empty($usersyousai)): ?>
<tr>
    <td><?php echo $usersyousai['emp_no'] ?></td> 
    <td><?php echo $usersyousai['password_hash'] ?></td> 
    <td><?php echo $usersyousai['ename'] ?></td> 
    <td><?php echo $usersyousai['birthday'] ?></td> 
    <td><?php echo $usersyousai['tel'] ?></td> 
    <td><?php echo $usersyousai['address'] ?></td> 
    <td><?php echo $usersyousai['pname'] ?></td>
    <td><?php echo $usersyousai['sname'] ?></td>
    <td><?php echo $usersyousai['image_path'] ?></td>
   
</tr>
<?php endif; ?>

</body>
</html>








