<?php
// admin_itiran.php
include 'db.php';
session_start();

// --- 1. アクション系処理 ---

// ログアウト処理
if (isset($_POST['logout'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: ../loginscreen.html");
    exit;
}

// 安否情報の初期化
if (isset($_POST["submitAnpiReset"])) { // HTMLのボタン名と一致
    try {
        $db->beginTransaction();
        $db->exec("UPDATE SAFETY SET SAFETY = 1, SAFE_TEXT = ''");
        $db->exec("INSERT INTO SAFETY (SAFE_NO, SAFETY, SAFE_TEXT) SELECT EMP_NO, 1, '' FROM EMPLOYEE WHERE EMP_NO NOT IN (SELECT SAFE_NO FROM SAFETY)");
        $db->commit();
        header("Location: admin_itiran.php");
        exit;
    } catch (PDOException $e) {
        if ($db->inTransaction()) $db->rollBack();
        exit('DBエラー: ' . $e->getMessage());
    }
}

// 詳細画面への遷移
if (isset($_POST["syousaiButton"])) {
    $_SESSION['syousaiID'] = $_POST['syousaiButton'];
    header('Location: admin_syousai.php');
    exit;
}

// 社員追加処理
if (isset($_POST["submitTuika"])) { // HTMLの決定ボタン名と一致
    $emp_no = $_POST['emp_no'];
    $password = $_POST['password_hash'];
    $ename = $_POST['ename'];
    $birthday = $_POST['birthday'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $position = $_POST['position'];
    $section = $_POST['section'];

    if (!empty($emp_no) && !empty($password) && !empty($ename)) {
        try {
            $db->beginTransaction();
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql_in = $db->prepare("INSERT INTO EMPLOYEE (EMP_NO, PASSWORD, ENAME, BIRTHDAY, TEL, ADDRESS, E_POSITION, SECTION) VALUES (:emp_no, :password, :ename, :birthday, :tel, :address, :position, :section)");
            $sql_in->execute([
                ':emp_no' => $emp_no, ':password' => $password_hash, ':ename' => $ename,
                ':birthday' => $birthday, ':tel' => $tel, ':address' => $address,
                ':position' => $position, ':section' => $section
            ]);

            $sql_safe = $db->prepare("INSERT INTO SAFETY (SAFE_NO, SAFETY, SAFE_TEXT) VALUES (:emp_no, 1, '')");
            $sql_safe->execute([':emp_no' => $emp_no]);

            $db->commit();
            header('Location: admin_itiran.php');
            exit;
        } catch (PDOException $e) {
            if ($db->inTransaction()) $db->rollBack();
            echo 'DBエラー（追加）: ' . $e->getMessage();
        }
    }
}

// --- 2. 表示用データの取得（検索機能を含む） ---

$ID = $_SESSION['ID'] ?? "";
$ALLusere = [];
$search_word = $_GET['search'] ?? ""; 

if (!empty($ID)) {
    try {
        $query = "SELECT p.PNAME, e.EMP_NO, e.ENAME, sa.SAFETY 
                  FROM EMPLOYEE as e 
                  LEFT JOIN SAFETY as sa ON e.EMP_NO = sa.SAFE_NO 
                  LEFT JOIN E_POSITION as p ON e.E_POSITION = p.POSITION_NO 
                  WHERE e.IS_DELETED = 0";

        if (isset($_GET['executeSearch']) && $search_word !== "") {
            $query .= " AND e.EMP_NO LIKE :search";
        }

        $sql_ALL = $db->prepare($query);

        if (isset($_GET['executeSearch']) && $search_word !== "") {
            $sql_ALL->bindValue(':search', '%' . $search_word . '%', PDO::PARAM_STR);
        }

        $sql_ALL->execute();
        $ALLusere = $sql_ALL->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo 'DBエラー（取得）: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理者(Admin)</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<header>
    <h1>管理者(Admin)</h1>
    <form method="POST" action="admin_itiran.php">
        <button type="submit" name="logout" class="logout-btn">Logout</button>
    </form>
</header>

<div class="container">
    <!-- 検索フォーム -->
    <div class="search-box">
        <form method="GET" action="admin_itiran.php">
            社員番号検索: 
            <input type="text" name="search" placeholder="社員番号を入力" value="<?= htmlspecialchars($search_word) ?>">
            <button type="submit" name="executeSearch">検索開始</button>
            <a href="admin_itiran.php">全件表示</a>
        </form>
    </div>

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
                            <form method="POST" action="admin_itiran.php" style="margin:0;">
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
                <tr><td colspan="5">該当する社員がいません。</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="footer-btns">
    <button type="button" id="opentuikaBtn">＋追加</button>
    <button type="button" id="openhennsyuuBtn" class="delete-btn">安否初期化</button>
</div>

<!-- 安否初期化ダイアログ -->
<dialog id="dialogallsadel">
    <form method="POST" action="admin_itiran.php">
        <p>全ての安否情報を初期化しますか？</p>
        <button type="submit" name="submitAnpiReset">実行</button>
        <button type="button" class="closeModeBtn">キャンセル</button>
    </form>
</dialog>

<!-- 追加ダイアログ -->
<dialog id="dialogtuika">
    <form method="POST" action="admin_itiran.php">
        名前: <input type="text" name="ename" required><br>
        住所: <input type="text" name="address" required><br>
        ID: <input type="text" name="emp_no" required><br>
        電話番号: <input type="text" name="tel" required><br>
        生年月日: <input type="date" name="birthday" required><br>
        役職番号: <input type="number" name="position" required><br>
        部署番号: <input type="number" name="section" required><br>
        パスワード: <input type="password" name="password_hash" required><br>
        <button type="submit" name="submitTuika">決定</button>
        <button type="button" class="closeModeBtn">キャンセル</button>
    </form>
</dialog>

<script src="../anpi_JS/itiran.js"></script>
</body>
</html>