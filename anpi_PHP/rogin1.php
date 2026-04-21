
<?php
//ログイン機能、ID、パスワードの参照を行う

//db接続
include 'db.php';
session_start();
$input_id=$_POST['user_id']??"";
$input_pass=$_POST['user_pass']??"";

if(!empty($input_id) && !empty($input_pass)){
    try{
        //SQL分 [:id]の部分はあとでもらってきたIDが入る
        $sql=db->prepare("SECT * FROM userid WHRE id = :id");
         
        //ここでさっきの[:id]に[$inpot_id]が入る
        $stmt->bindparam(':id',$inpot_id);
        $stmt->execute();
        $userid=$stmt->fetch();
        if($userid){
            //ここで入力されたユーザーID と、それに紐づけているパスワードが等しいかの判定。
            if(password_verify($input_pass,$userid['user_pass'])){


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


















































































