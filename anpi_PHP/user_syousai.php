<?php
//syousai.php
include 'db.php';
session_start();
$syousaiID = $_SESSION['syousaiID'] ?? "";

if (empty($syousaiID)) {
    echo "IDがありません";
} else {
    try {

        //すべて取ってくる処理
        $sql_ALL = $db->prepare("SELECT e.*, p.pname, s.sname, sa.safety
        FROM employee as e
        LEFT JOIN safety as sa ON e.emp_no = sa.safe_no
        LEFT JOIN position as p ON e.position = p.position_no
        LEFT JOIN section as s ON e.section = s.section_no
        WHERE e.emp_no = :emp_no");
        $sql_ALL->execute([':emp_no' => $syousaiID]);
        $user_ALL = $sql_ALL->fetch();


    } catch (PDOException $e) {
        echo 'DBエラー:' . $e->getMessage();
    }
}


if (isset($_POST["submitTuika"])) {

    $password = $_POST['password_hash'];
    $ename = $_POST['ename'];
    $birthday = $_POST['birthday'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $position = $_POST['position'];
    $section = $_POST['section'];
    $sname = $_POST['sname'];
    $pname = $_POST['pname'];

    if (!empty($password) && !empty($ename) && !empty($birthday) && !empty($tel) && !empty($address) && !empty($position) && !empty($section) && !empty($pname) && !empty($sname)) {
        try {
            $db->beginTransaction();
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql_in = $db->prepare("update employee set password = :password_hash , ename = :ename , birthday = :birthday , tel = :tel , address = :address , position = :position ,section = :section  where emp_no = :emp_no");

            $sql_in->execute([
                ':emp_no' => $user_ALL['emp_no'],
                ':password_hash' => $password_hash,
                ':ename' => $ename,
                ':birthday' => $birthday,
                ':tel' => $tel,
                ':address' => $address,
                ':position' => $position,
                ':section' => $section

            ]);

            $db->commit();
            header("Location: syousai.php"); // 再読み込みして反映

            exit;
        } catch (PDOException $e) {

            echo 'DBエラー' . $e->getMessage();

        }
    }

}
if (isset($_GET["sakujyoButton"])) {
    try {
        $db->beginTransaction();
        $sql_up = $db->prepare("update employee set IS_DELETED = 1 where emp_no = :emp_no");
        $sql_up->execute([
            ':emp_no' => $user_ALL['emp_no']
        ]);

        $db->commit();
        header("Location: syousai.php"); // 再読み込みして反映

        exit;
    } catch (PDOException $e) {

        echo 'DBエラー' . $e->getMessage();

    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        $value == null;
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

    <?php if (!empty($syousaiID)): ?>
        <table border="1">
            <tr>
                <th>社員番号</th>
                <th>名前</th>
                <th>生年月日</th>
                <th>電話番号</th>
                <th>住所</th>
                <th>役職名</th>
                <th>部署名</th>
                <th>画像パス</th>
            </tr>
            <tr>
                <td><?php echo $user_ALL['emp_no'] ?></td>
                <td><?php echo $user_ALL['ename'] ?></td>
                <td><?php echo $user_ALL['birthday'] ?></td>
                <td><?php echo $user_ALL['tel'] ?></td>
                <td><?php echo $user_ALL['address'] ?></td>
                <td><?php echo $user_ALL['pname'] ?></td>
                <td><?php echo $user_ALL['sname'] ?></td>
                <td><?php echo $user_ALL['image_path'] ?></td>
            </tr>
        </table>
    <?php endif; ?>
    <script src="syousai.js"></script>
</body>

</html>