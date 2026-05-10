<?php
// itiran.php
include 'db.php';
session_start();
$syousaiID = $_SESSION['syousaiID'] ?? "";
$ALLusere = [];

try {
    //すべて取ってくる処理
    $sql_ALL = $db->prepare("SELECT p.PNAME, e.EMP_NO, e.ENAME, sa.SAFETY 
    FROM EMPLOYEE as e 
    LEFT JOIN SAFETY as sa ON e.EMP_NO = sa.SAFE_NO 
    LEFT JOIN E_POSITION as p ON e.E_POSITION = p.POSITION_NO 
    LEFT JOIN SECTION as s ON e.SECTION = s.SECTION_NO 
    WHERE e.IS_DELETED = 0");
    // $sql_ALL = $db->prepare("SELECT p.pname, e.emp_no,e.ename,e.emp,sa.safety FROM employee as e left join  safety as sa on  e.emp_no = sa.safe_no left join position as p on e.position = p.position_no left join section as s on e.section = s.section_no where IS_DELETED = 0");
    $sql_ALL->execute();
    $ALLusere = $sql_ALL->fetchAll(PDO::FETCH_ASSOC);
 
 
 
 
 
} catch (PDOException $e) {
    echo 'DBエラー';
}

// 詳細画面への遷移
if (isset($_POST["syousaiButton"])) {
    $_SESSION['syousaiID'] = $_POST['syousaiButton'];
    header('Location: user_syousai.php');
    exit;
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
<form method="POST" action="itiran.php">
    <?php foreach ($ALLusere as $user): ?>
 
        <button type="submit" name="syousaiButton" value="<?php echo $user['EMP_NO']; ?>">
            <p><?php echo htmlspecialchars($user['SAFETY'] ?? '')?>
                <?php echo htmlspecialchars($user['EMP_NO'] ?? '') ?>
                <?php echo htmlspecialchars($user['ENAME'] ?? '')?>
                <?php echo htmlspecialchars($user['PNAME'] ?? '')?>
            </p>
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
 
        <button type="submit" name="submitButton">安否情報の初期化</button>
        <button type="button" class="closeModeBtn">キャンセル</button>
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
<script src="itiran.js"></script>
</body>
 
</html>