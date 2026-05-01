
<?php
//ログイン機能、ID、パスワードの参照を行う

//db接続
include 'db.php';
session_start();
$input_id=$_POST['username']??"";
$input_pass=$_POST['password']??"";

//
if(!empty($input_id) && !empty($input_pass)){
    try{
        //SQL分 [:id]の部分はあとでもらってきたIDが入る
        $sql=db->prepare("SELECT * FROM username WHERE id = :id");
         
        //ここでさっきの[:id]に[$input_id]が入る
        $stmt->bindparam(':id',$input_id);
        $stmt->execute();
        $userid=$stmt->fetch();
        if($userid){
            //ここで入力されたユーザーID と、それに紐づけているパスワードが等しいかの判定。
            if(password_verify($input_pass,$userid['password'])){


            //もし管理者IDだったら遷移先を変更
                if($userid===$MASTERID){
                    $_SESSION['ID']=$userid;
                 header('Location: $MASTER.php');
                exit;
                }
                $_SESSION['ID']=$userid;
                header('Location: mypage.php');
                exit;
            }
        }

    }
    catch(PDOException $e){
        echo 'DBエラー';
    }

}

?>


















































































