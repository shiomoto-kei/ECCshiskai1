<?php
// itiran.php
include 'db.php';
session_start();
$ID = $_SESSION['ID'];
try {
    //すべて取ってくる処理
    $sql_ALL = $db->prepare("SELECT p.pname, e.emp_no,e.ename,e.emp,sa.safety FROM employee as e left join  safety as sa on  e.emp_no = sa.safe_no left join position as p on e.position = p.position_no left join section as s on e.section = s.section_no where IS_DELETED = 0");
    $sql_ALL->execute();
    $ALLusere = $sql_ALL->fetchall();

 



} catch (PDOException $e) {
    echo 'DBエラー';
}
if (isset($_GET["sumitButton"])) {
    try {
        //全ての安否情報の値を初期値の１にする
        $sql_update = $db->prepare("update safety  set safety = 1");
        $sql_update->execute();





    } catch (PDOException $e) {
        echo 'DBエラー';
    }
}
if (isset($_GET["syousaiButton"])) {
    $_SESSION['syousaiID'] = $ID;
    header('Location: syousai.php');
    exit;
}

//追加機能インサート分
if (isset($_GET["submitTuika"])) {
    // $_SESSION['syousaiID']=$ID;
    //  header('Location: syousai.php');
    //  exit;
    $emp_no = $_POST['emp_no'];
    $password = $_POST['password_hash'];
    $ename = $_POST['ename'];
    $birthday = $_POST['birthday'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $position = $_POST['position'];
    $section = $_POST['section'];
    $jname = $_POST['jname'];
    $pname = $_POST['pname'];

    if (!empty($emp_no) && !empty($password) && !empty($ename) && !empty($birthday) && !empty($tel) && !empty($address) && !empty($position) && !empty($section) && !empty($pname) && !empty($jname)) {
        try {
            $db->beginTransaction();
            $password_hash = password_hash($password);

            $sql_in = $db->prepare("insert into employee (emp_no,password_hash,ename,birthday,tel,address,position,section) values (:emp_no,:password_hash,:ename,:birthday,:tel,:address,:position,:section)");

            $sql_in->execute([
                ':emp_no' => $emp_no,
                ':password_hash' => $password_hash,
                ':ename' => $ename,
                ':birthday' => $birthday,
                ':tel' => $tel,
                ':address' => $address,
                ':position' => $position,
                ':section' => $section

            ]);

            $sql_p = $db->prepare("insert into position (pname) values (:pname)");
            $sql_p->execute([
                ':pname' => $pname
            ]);

            $sql_j = $db->prepare("insert into section (jname) values (:jname)");
            $sql_j->execute([
                ':jname' => $jname
            ]);


            $db->commit();
        } catch (PDOException $e) {

            echo 'DBエラー' . $e->getMessage();

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


<!-- 一覧情報 -->
<form method="get" action="itiran.php">
    <?php foreach ($ALLusere as $ID): ?>

        <button type="submit" name="syousaiButton">
            <p><?php $ID['safety'] ?><?php echo $ID['emp_no'] ?><?php echo $ID['ename'] ?><?php echo $ID['pname'] ?>></p>
            <br>
        </button>
    <?php endforeach; ?>
</form>



<!-- 全安否情報の初期化 -->
<div>
    <button type="button" id="openhennsyuuBtn">編集</button>
</div>

<dialog id="dialogallsadel">

    <form method="get" action="itiran.php">

        <button type="button" name="submitButton">安否情報の初期化</button>
        <button type="button" id="closeModeBtn">キャンセル</button>
    </form>
</dialog>





<!-- 追加ボタン -->

<div>
    <button type="button" id="opentuikaBtn">＋追加</button>
</div>


<dialog id="dialogtuika">

    <form method="post" action="itiran.php">


        名前: <input type="text" name="ename" required><br>
        住所:<input type="text" name="address" required><br>
        ID: <input type="text" name="emp_no" required><br>
        電話番号: <input type="number" name="tel" required><br>
        生年月日: <input type="text" name="birhday" required><br>
        役職番号: <in番号t type="number" name="pname" required><br>
            役職名: <input type="text" name="position" required><br>
            部署番号: <input type="number" name="section" required><br>
            部署名: <input type="text" name="jname" required><br>
            パスワード: <input type="text" name="password_hash" required><br>

            <button type="button" name="submitTuika">決定</button>
            <button type="button" id="closeModeBtn">キャンセル</button>
    </form>
</dialog>

</body>

</html>