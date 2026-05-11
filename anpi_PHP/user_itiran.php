<?php
// admin_itiran.phpと揃えて工程短縮
include 'db.php';
session_start();
// $syousaiID = $_SESSION['syousaiID'] ?? "";
// $ALLusere = [];


// 1.アクション系処理（SELECTより先に実行）

// ログアウト処理
if (isset($_POST['logout'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: ../loginscreen.html");
    exit;
}

//2. 表示用データの取得（最新の状態を取得）
$ID = $_SESSION['ID'] ?? "";
$ALLusere = [];

try {
    //すべて取ってくる処理
    $sql_ALL = $db->prepare("SELECT p.PNAME, e.EMP_NO, e.ENAME, sa.SAFETY 
    FROM EMPLOYEE as e 
    LEFT JOIN SAFETY as sa ON e.EMP_NO = sa.SAFE_NO 
    LEFT JOIN E_POSITION as p ON e.E_POSITION = p.POSITION_NO 
    LEFT JOIN SECTION as s ON e.SECTION = s.SECTION_NO 
    WHERE e.IS_DELETED = 0");
  
    $sql_ALL->execute();
    $ALLusere = $sql_ALL->fetchAll(PDO::FETCH_ASSOC);

      // $sql_ALL = $db->prepare("SELECT p.pname, e.emp_no,e.ename,e.emp,sa.safety FROM employee as e left join  safety as sa on  e.emp_no = sa.safe_no left join position as p on e.position = p.position_no left join section as s on e.section = s.section_no where IS_DELETED = 0");


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
    <title>社員安否一覧</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<header>
    <h1>社員一覧</h1>
    <form method="POST" action="admin_itiran.php">
        <button type="submit" name="logout" class="logout-btn">Logout</button>
    </form>
</header>

<div class="container">
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
                        <td>
                        <!-- ここがadmin_itiran.phpのままだった -->
                            <form method="POST" action="user_itiran.php" style="margin:0;">
                                <button type="submit" name="syousaiButton" value="<?= htmlspecialchars($user['EMP_NO']) ?>">詳細</button>
                            </form>
                        </td>
                        <td><?= htmlspecialchars($user['SAFETY'] ?? '未登録') ?></td>
                        <td><?= htmlspecialchars($user['EMP_NO']) ?></td>
                        <td><?= htmlspecialchars($user['ENAME']) ?></td>
                        <td><?= htmlspecialchars($user['PNAME'] ?? '未設定') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">表示するデータがありません。</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="../anpi_JS/itiran.js"></script>
</body>

</html>