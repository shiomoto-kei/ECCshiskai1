<?php
// itiran.php
include 'db.php';
session_start();
$ID = $_SESSION['ID'] ?? "";
$ID = "00001";
$ALLusere = [];

if (empty($ID)) {
    echo "IDがありません";
} else {
    try {
        //すべて取ってくる処理
        $sql_ALL = $db->prepare("SELECT p.PNAME, e.EMP_NO, e.ENAME, sa.SAFETY 
    FROM EMPLOYEE as e 
    LEFT JOIN SAFETY as sa ON e.EMP_NO = sa.SAFE_NO 
    LEFT JOIN E_POSITION as p ON e.E_POSITION = p.POSITION_NO 
    LEFT JOIN SECTION as s ON e.SECTION = s.SECTION_NO 
    WHERE e.IS_DELETED = 0");
        // ("SELECT p.pname, e.emp_no,e.ename,e.emp,sa.safety FROM employee as e left join  safety as sa on  e.emp_no = sa.safe_no left join position as p on e.position = p.position_no left join section as s on e.section = s.section_no where IS_DELETED = 0");
        $sql_ALL->execute();
        $ALLusere = $sql_ALL->fetchAll(PDO::FETCH_ASSOC);





    } catch (PDOException $e) {
        echo 'DBエラー' . $e;
    }
}

// 安否情報の初期化
if (isset($_GET["submitButton"])) {
    try {
        //全ての安否情報の値を初期値の１にする
        $sql_update = $db->prepare("update safety  set safety = 1");
        $sql_update->execute();





    } catch (PDOException $e) {
        echo 'DBエラー';
    }
}

// 詳細画面への遷移
if (isset($_POST["syousaiButton"])) {
    $_SESSION['syousaiID'] = $_POST['syousaiButton'];
    header('Location: syousai.php');
    exit;
}

//追加機能インサート分
if (isset($_POST["submitTuika"])) {
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
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql_in = $db->prepare("INSERT INTO EMPLOYEE (EMP_NO, PASSWORD, ENAME, BIRTHDAY, TEL, ADDRESS, E_POSITION, SECTION) VALUES (:emp_no, :password, :ename, :birthday, :tel, :address, :position, :section)");



            $sql_in->execute([
                ':emp_no' => $emp_no,
                ':password' => $password_hash,
                ':ename' => $ename,
                ':birthday' => $birthday,
                ':tel' => $tel,
                ':address' => $address,
                ':position' => $position,
                ':section' => $section

            ]);

            // $sql_p = $db->prepare("insert into position (pname) values (:pname)");
            // $sql_p->execute([
            //     ':pname' => $pname
            // ]);

            // $sql_j = $db->prepare("insert into section (jname) values (:jname)");
            // $sql_j->execute([
            //     ':jname' => $jname
            // ]);


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
    <title>管理者</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>


<!-- 一覧情報 -->

<header>
    <h1>管理者(Admin)</h1>
    <button class="logout-btn">Logout</button>
</header>

<div class="container">
    <form method="POST" action="admin_itiran.php">
        <table>
            <thead>
                <tr class="head">
                    <th>詳細</th>
                    <th>安否</th>
                    <th>社員番号</th>
                    <th>名前</th>
                    <th>役職</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($ALLusere)): ?>
                    <?php foreach ($ALLusere as $user): ?>

                        <tr>
                            <td> <button type="submit" name="syousaiButton" value="<?php echo $user['EMP_NO']; ?>">
                                    詳細
                                </button>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($user['SAFETY'] ?? ''); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($user['EMP_NO'] ?? ''); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($user['ENAME'] ?? ''); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($user['PNAME'] ?? ''); ?>
                            </td>


                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">表示するデータがありません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>

<!-- <button class="delete-btn">All delete</button> -->



<!-- 全安否情報の初期化 -->
<footer>
    <button type="button" id="openhennsyuuBtn" class="delete-btn">編集</button>
</footer>

<dialog id="dialogallsadel">

    <form method="get" action="admin_itiran.php">

        <button type="submit" name="submitButton">安否情報の初期化</button>
        <button type="button" class="closeModeBtn">キャンセル</button>
    </form>
</dialog>





<!-- 追加ボタン -->

<div>
    <button type="button" id="opentuikaBtn">＋追加</button>
</div>


<dialog id="dialogtuika">

    <form method="post" action="admin_itiran.php">


        名前: <input type="text" name="ename" required><br>
        住所:<input type="text" name="address" required><br>
        ID: <input type="text" name="emp_no" required><br>
        電話番号: <input type="number" name="tel" required><br>
        生年月日: <input type="text" name="birthday" required><br>
        役職番号: <input type="number" name="position" required><br>
        役職名: <input type="text" name="pname" required><br>
        部署番号: <input type="number" name="section" required><br>
        部署名: <input type="text" name="jname" required><br>
        パスワード: <input type="text" name="password_hash" required><br>

        <button type="submit" name="submitTuika">決定</button>
        <button type="button" class="closeModeBtn">キャンセル</button>
    </form>
</dialog>
<script src="../anpi_JS/itiran.js"></script>
</body>

</html>