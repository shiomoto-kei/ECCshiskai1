<?php
// admin_itiran.php
include 'db.php';
session_start();

// --- 1. アクション系処理（更新・追加・削除・ログアウト） ---

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
        $db->exec("UPDATE SAFETY SET SAFETY = 1, SAFE_TEXT = ''");
        $db->exec("INSERT INTO SAFETY (SAFE_NO, SAFETY, SAFE_TEXT) SELECT EMP_NO, 1, '' FROM EMPLOYEE WHERE EMP_NO NOT IN (SELECT SAFE_NO FROM SAFETY)");
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

            // EMPLOYEEテーブルへの挿入
            $sql_in = $db->prepare("INSERT INTO EMPLOYEE (EMP_NO, PASSWORD, ENAME, BIRTHDAY, TEL, ADDRESS, E_POSITION, SECTION) VALUES (:emp_no, :password, :ename, :birthday, :tel, :address, :position, :section)");
            $sql_in->execute([
                ':emp_no' => $emp_no, ':password' => $password_hash, ':ename' => $ename,
                ':birthday' => $birthday, ':tel' => $tel, ':address' => $address,
                ':position' => $position, ':section' => $section
            ]);

            // SAFETYテーブルへの初期登録
            $sql_safe = $db->prepare("INSERT INTO SAFETY (SAFE_NO, SAFETY, SAFE_TEXT) VALUES (:emp_no, 1, '')");
            $sql_safe->execute([':emp_no' => $emp_no]);

            $db->commit();
            header('Location: admin_itiran.php'); // 追加後はリダイレクトして再読み込み
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
$search_word = $_GET['search'] ?? ""; // URLのパラメータから取得

if (!empty($ID)) {
    try {
        // ベースのSQL
        $query = "SELECT p.PNAME, e.EMP_NO, e.ENAME, sa.SAFETY 
                  FROM EMPLOYEE as e 
                  LEFT JOIN SAFETY as sa ON e.EMP_NO = sa.SAFE_NO 
                  LEFT JOIN E_POSITION as p ON e.E_POSITION = p.POSITION_NO 
                  WHERE e.IS_DELETED = 0";

        // 検索ボタンが押された時だけ条件を動的に追加
        if (isset($_GET['executeSearch']) && $search_word !== "") {
            $query .= " AND e.EMP_NO LIKE :search";
        }

        $sql_ALL = $db->prepare($query);

        // バインド処理
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
    
        <form method="GET" action="admin_itiran.php">
            社員番号検索: 
            <input type="text" name="search" placeholder="社員番号を入力" value="<?= htmlspecialchars($search_word) ?>">
            <button type="submit" name="executeSearch">検索開始</button>
            <a href="admin_itiran.php" >全件表示</a>
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

<div>
    <button type="button" id="opentuikaBtn">＋追加</button>
    <button type="button" id="openhennsyuuBtn" class="delete-btn">安否初期化</button>
</div>

<!-- ダイアログ等は以前のものをそのまま下に配置してください -->

<script src="../anpi_JS/itiran.js"></script>
</body>
</html>