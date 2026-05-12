<?php

include 'db.php';
session_start();

// 1. ログインチェック
if (!isset($_SESSION['ID'])) {
    header("Location: ../loginscreen.html");
    exit;
}

$login_user = $_SESSION['ID'];

// 2. ログアウト処理
if (isset($_POST['logout'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: ../loginscreen.html");
    exit;
}

// 3. 安否情報の更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_anpi'])) {
    $status = filter_input(INPUT_POST, 'status');

    // ★ここを修正：Yesを1、Noを2にする
    $text = ($status === 'yes') ? $_POST['text_yes'] : $_POST['text_no'];
    $safety_val = ($status === 'yes') ? 1 : 2; 

    try {
        // (中略：前回のINSERT/UPDATE判定ロジックを推奨)
        $sql = "UPDATE SAFETY SET SAFETY = :safety, SAFE_TEXT = :text WHERE SAFE_NO = :emp_no";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':safety', $safety_val, PDO::PARAM_INT);
        $stmt->bindValue(':text', $text, PDO::PARAM_STR);
        $stmt->bindValue(':emp_no', $login_user, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: user_itiran.php");
        exit;
    } catch (PDOException $e) {
        exit("DBエラー: " . $e->getMessage());
    }
}

// 4. 表示用データの取得
try {
    $push = $db->prepare("SELECT ENAME FROM EMPLOYEE WHERE EMP_NO = ?");
    $push->execute([$login_user]);
    $row = $push->fetch(PDO::FETCH_ASSOC);

    $sql_all = "SELECT E.ENAME, S.SAFETY FROM EMPLOYEE AS E 
                LEFT JOIN SAFETY AS S ON E.EMP_NO = S.SAFE_NO 
                WHERE E.IS_DELETED = 0";
    $all_staff = $db->query($sql_all)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit("DBエラー: " . $e->getMessage());
}

// include 'db.php';
// session_start();
// $ID=$_SESSION['ID'];

// if (isset($_POST['submit_anpi'])) {

//     $_SESSION['ID'] = $ID;
//     header("Location: user_itiran.php");
//     exit;
// }
// // 1. ログインチェック
// if (!isset($_SESSION['ID'])) {
//     header("Location: ../loginscreen.html");
//     exit;
// }

// $login_user = $_SESSION['ID'];

// // 2. ログアウト処理
// if (isset($_POST['logout'])) {
//     $_SESSION = [];
//     session_destroy();
//     header("Location: ../loginscreen.html");
//     exit;
// }

// // 3. 安否情報の更新処理（POST送信時）
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_anpi'])) {
//     $status = $_POST['status']; 
//     $text = ($status === 'yes') ? $_POST['text_yes'] : $_POST['text_no'];
//     $safety_val = ($status === 'yes') ? 2 : 1; // 1:無事, 2:被害あり

//     try {
//         $sql = "UPDATE SAFETY SET SAFETY = :safety, SAFE_TEXT = :text WHERE SAFE_NO = :emp_no";
//         $stmt = $db->prepare($sql);
//         $stmt->bindValue(':safety', $safety_val, PDO::PARAM_INT);
//         $stmt->bindValue(':text', $text, PDO::PARAM_STR);
//         $stmt->bindValue(':emp_no', $login_user, PDO::PARAM_STR);
//         $stmt->execute();

//         header("Location: " . $_SERVER['PHP_SELF']);
//         exit;
//     } catch (PDOException $e) {
//         exit("DBエラー: " . $e->getMessage());
//     }
// }

// // 4. 表示用データの取得
// try {
//     // 自分の情報を取得
//     $push = $db->prepare("SELECT ENAME FROM EMPLOYEE WHERE EMP_NO = ?");
//     $push->execute([$login_user]);
//     $row = $push->fetch(PDO::FETCH_ASSOC);

//     // 【追加】全社員の安否状況を取得（ダイアログ用）
//     $sql_all = "SELECT E.ENAME, S.SAFETY FROM EMPLOYEE AS E 
//                 LEFT JOIN SAFETY AS S ON E.EMP_NO = S.SAFE_NO 
//                 WHERE E.IS_DELETED = 0";
//     $all_staff = $db->query($sql_all)->fetchAll(PDO::FETCH_ASSOC);

// } catch (PDOException $e) {
//     exit("DBエラー: " . $e->getMessage());
// }
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>安否確認</title>
    <link rel="stylesheet" href="../css/chosescreen.css">

</head>

<body>

    <div class="wrapper">
        <div class="form-box">

            <div class="top-bar">

                <!-- 全社員確認ボタンを追加 -->
                <button type="button" id="openStatusBtn" class="menu-btn">☰</button>

                <form method="POST" action="" style="display:inline;">
                    <button type="submit" class="logout-btn" name="logout">Logout</button>
                </form>
            </div>

            <h1>安否確認</h1>
            <p>こんにちは、<?= htmlspecialchars($row['ENAME'] ?? 'ゲスト') ?> さん</p>

            <!-- actionを空にして自分自身(PHP)にPOSTするように変更 -->
            <form action="" method="POST">

                <p class="question">被害に遭われましたか？</p>

                <label class="radio-box">
                    <input type="radio" name="status" value="yes" required> Yes
                </label>

                <label class="text-box">
                    <textarea name="text_yes" placeholder="詳細な内容を記入してください"></textarea>
                </label>

                <label class="radio-box">
                    <input type="radio" name="status" value="no"> No
                </label>

                <label class="text-box">
                    <textarea name="text_no" placeholder="詳細な内容を記入してください"></textarea>
                </label>

                <button type="submit" name="submit_anpi">送信</button>

            </form>
        </div>
    </div>

    <!-- 全社員の安否表示用ダイアログ -->
    <dialog id="statusDialog">
        <h3>全社員 安否一覧</h3>
        <table class="all-staff-table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>状況</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_staff as $staff): ?>
                    <tr>
                        <td><?= htmlspecialchars($staff['ENAME']) ?></td>
                        <td>
                            <?php
                            if ($staff['SAFETY'] == 1)
                                echo '<span class="status-1">無事</span>';
                            elseif ($staff['SAFETY'] == 2)
                                echo '<span class="status-2">被害あり</span>';
                            else
                                echo '未回答';
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" id="closeStatusBtn" class="close-btn">閉じる</button>
    </dialog>

    <script src="../anpi_JS/main.js">

    </script>
</body>

</html>