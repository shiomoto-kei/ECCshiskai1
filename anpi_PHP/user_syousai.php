<?php
// admin_syousai.php
include 'db.php';
session_start();
$syousaiID = $_SESSION['syousaiID'] ?? "";

if (empty($syousaiID)) {
    echo "IDがありません";
} else {
    try {
        // すべて取ってくる処理
        $sql_ALL = $db->prepare("SELECT e.*, p.PNAME, s.SNAME, sa.SAFETY, sa.SAFE_TEXT
            FROM EMPLOYEE as e
            LEFT JOIN SAFETY as sa ON e.EMP_NO = sa.SAFE_NO
            LEFT JOIN E_POSITION as p ON e.E_POSITION = p.POSITION_NO
            LEFT JOIN SECTION as s ON e.SECTION = s.SECTION_NO
            WHERE e.EMP_NO = :emp_no");
        $sql_ALL->execute([':emp_no' => $syousaiID]);
        $user_ALL = $sql_ALL->fetch();

    } catch (PDOException $e) {
        echo 'DBエラー:' . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>詳細(Admin)</title>
    <link rel="stylesheet" href="../css/staff.css">
</head>
<body>
    <header>
        <h1>詳細(Admin)</h1>
    </header>

    <div class="container">
        <div class="image">
            <?php if (!empty($user_ALL['image_path'])): ?>
                <img src="image/<?php echo htmlspecialchars($user_ALL['image_path']); ?>" width="260" height="320" alt="person">
            <?php else: ?>
                <img src="image/person1-15.png" width="260" height="320" alt="person">
            <?php endif; ?>
        </div>

        <div class="detail">
            <div>
                <label>名前:</label>
                <input type="text" value="<?php echo htmlspecialchars($user_ALL['ENAME'] ?? ''); ?>" readonly>
            
            </div>
            <div>
                <label>ID:</label>
                <input type="text" value="<?php echo htmlspecialchars($user_ALL['EMP_NO'] ?? ''); ?>" readonly>
            
            </div>
            <div>
                <label>役職:</label>
                <input type="text" value="<?php echo htmlspecialchars($user_ALL['PNAME'] ?? ''); ?>" readonly>
            </div>
            <div>
                <label>安否状況:</label>
                <input type="text" value="<?php echo htmlspecialchars($user_ALL['SAFETY'] ?? ''); ?>" readonly>
            </div>

            <div class="comment">
                <textarea style="width: 100%;" rows="12" readonly><?php echo htmlspecialchars($user_ALL['SAFE_TEXT'] ?? 'コメントはありません'); ?></textarea>
            </div>
        </div>
    </div>

    <dialog id="dialoghennsyuu">
        <!-- action を admin_syousai.php に確実に合わせる -->
        <form method="POST" action="admin_syousai.php">
            <h3>社員情報編集</h3>
            <p>社員番号: <?php echo htmlspecialchars($user_ALL['EMP_NO'] ?? ''); ?></p>

            名前: <input type="text" name="ename" value="<?php echo htmlspecialchars($user_ALL['ENAME'] ?? ''); ?>" required><br>
            住所: <input type="text" name="address" value="<?php echo htmlspecialchars($user_ALL['ADDRESS'] ?? ''); ?>" required><br>
            電話番号: <input type="number" name="tel" value="<?php echo htmlspecialchars($user_ALL['TEL'] ?? ''); ?>" required><br>
            生年月日: <input type="date" name="birthday" value="<?php echo htmlspecialchars($user_ALL['BIRTHDAY'] ?? ''); ?>" required><br>
            役職番号: <input type="number" name="position" value="<?php echo htmlspecialchars($user_ALL['E_POSITION'] ?? ''); ?>" required><br>
            部署番号: <input type="number" name="section" value="<?php echo htmlspecialchars($user_ALL['SECTION'] ?? ''); ?>" required><br>
            パスワード: <input type="text" name="password_hash" placeholder="新しいパスワードを入力" required><br>

            <!-- ダミー値 -->
            <input type="hidden" name="pname" value="dummy">
            <input type="hidden" name="sname" value="dummy">

            <div style="margin-top: 15px;">
                <button type="submit" name="submitTuika">決定</button>
                <button type="button" id="closeModeBtn">キャンセル</button>
            </div>
        </form>

        <hr>

        <form method="GET" action="admin_syousai.php">
        </form>
    </dialog>

    <script src="../anpi_JS/syousai.js"></script>
</body>
</html>