<?php
// admin_itiran.php
include 'db.php';
session_start();

// --- 1. アクション系処理（SELECTより先に実行） ---

// ログアウト処理
if (isset($_POST['logout'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: ../loginscreen.html");
    exit;
}

// 安否情報の初期化
if (isset($_POST["submitButton"])) {
   try {
        $db->beginTransaction();

        // A. すでにレコードがある人は「1」に更新
        $sql_update = $db->prepare("UPDATE SAFETY SET SAFETY = 1, SAFE_TEXT = ''");
        $sql_update->execute();

        // B. 【重要】もしSAFETYテーブルに存在しない社員がいたら、自動で作る
        // EMPLOYEEにはいるけどSAFETYにはいない人を初期値1で登録するSQL
        $sql_insert_missing = $db->prepare("
            INSERT INTO SAFETY (SAFE_NO, SAFETY, SAFE_TEXT)
            SELECT EMP_NO, 1, '' FROM EMPLOYEE
            WHERE EMP_NO NOT IN (SELECT SAFE_NO FROM SAFETY)
        ");
        $sql_insert_missing->execute();

        $db->commit();
        header("Location: admin_itiran.php");
        exit;
    } catch (PDOException $e) {
        $db->rollBack();
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
if (isset($_POST["submitTuika"])) {
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

            // EMPLOYEEテーブル
            $sql_in = $db->prepare("INSERT INTO EMPLOYEE (EMP_NO, PASSWORD, ENAME, BIRTHDAY, TEL, ADDRESS, E_POSITION, SECTION) VALUES (:emp_no, :password, :ename, :birthday, :tel, :address, :position, :section)");
            $sql_in->execute([
                ':emp_no' => $emp_no, ':password' => $password_hash, ':ename' => $ename,
                ':birthday' => $birthday, ':tel' => $tel, ':address' => $address,
                ':position' => $position, ':section' => $section
            ]);

            // SAFETYテーブル（初期値1）
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

// --- 2. 表示用データの取得（最新の状態を取得） ---

$ID = $_SESSION['ID'] ?? "";
$ALLusere = [];

if (empty($ID)) {
    // 本番環境ではここでリダイレクト等
} else {
    try {
        $sql_ALL = $db->prepare("SELECT p.PNAME, e.EMP_NO, e.ENAME, sa.SAFETY 
            FROM EMPLOYEE as e 
            LEFT JOIN SAFETY as sa ON e.EMP_NO = sa.SAFE_NO 
            LEFT JOIN E_POSITION as p ON e.E_POSITION = p.POSITION_NO 
            WHERE e.IS_DELETED = 0");
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
                <tr><td colspan="5">表示するデータがありません。</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div>
    <button type="button" id="opentuikaBtn">＋追加</button>
</div>

<footer>
    <button type="button" id="openhennsyuuBtn" class="delete-btn">編集</button>
</footer>

<!-- 安否初期化ダイアログ -->
<dialog id="dialogallsadel">
    <form method="POST" action="admin_itiran.php">
        <p>全ての安否情報を初期化（1に戻す）しますか？</p>
        <button type="submit" name="submitButton">安否情報の初期化</button>
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
        役職名: <input type="text" name="pname" required><br>
        部署番号: <input type="number" name="section" required><br>
        部署名: <input type="text" name="jname" required><br>
        パスワード: <input type="password" name="password_hash" required><br>
        <button type="submit" name="submitTuika">決定</button>
        <button type="button" class="closeModeBtn">キャンセル</button>
    </form>
</dialog>

<script src="../anpi_JS/itiran.js"></script>
</body>
</html>