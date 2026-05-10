
<?php
//ログイン機能、ID、パスワードの参照を行う

//db接続
include 'db.php';
session_start();
$input_id=$_POST['username']??"";
$input_pass=$_POST['password']??"";


if(!empty($input_id) && !empty($input_pass)){
    try{

        //SQL分 [:id]の部分はあとでもらってきたIDが入る
        $sql=$db->prepare("SELECT * FROM username WHERE id = :id");
         
        //ここでさっきの[:id]に[$input_id]が入る
        $stmt->bindparam(':id',$input_id);
        $stmt->execute();
        $user=$stmt->fetch();
        if($user){
            //ここで入力されたユーザーID と、それに紐づけているパスワードが等しいかの判定。
            if(password_verify($input_pass,$userid['password'])){


        $hash = password_hash($input_pass);  
        if($user['emp_no']==$input_id && $user['password_hash']===$hash)
            //もし管理者IDだったら遷移先を変更
                if($user['position']===1){
                    $_SESSION['ID']=$user['emp_no'];
                 header('Location: itiran.php');
                exit;
                }else{
                $_SESSION['ID']=$user['emp_no'];
                header('Location: mypage.php');
                exit;
                }
            }
        }

    }
    catch(PDOException $e){
        echo 'DBエラー';
    }

}

?>


















































































