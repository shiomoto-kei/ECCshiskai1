<?php
// login_check.php (仮)
include 'db.php';
session_start();

$input_id = $_POST['username'] ?? "";
$input_pass = $_POST['password'] ?? "";
$error="";
if (!empty($input_id) && !empty($input_pass)) {
    try {
        // 論理削除されていないユーザーを取得
        $stmt = $db->prepare("SELECT * FROM EMPLOYEE WHERE EMP_NO = :id AND IS_DELETED = 0");
        $stmt->bindParam(':id', $input_id);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            // パスワード照合
            if (password_verify($input_pass, $user['PASSWORD'])) {
                
                // セッションにIDを保存 (カラム名の大文字小文字に注意)
                $_SESSION['ID'] = $user['EMP_NO'];

                // SECTIONが1なら管理者、それ以外は一般ユーザー
                if ($user['SECTION'] == 1) {
                    header('Location: admin_itiran.php');
                    exit;
                } else {
                    header('Location: main.php');
                    exit;
                }
            } else {
                // パスワード間違いの場合の処理（任意）
                $error = "パスワードが正しくありません。";
            }
        } else {
            // ユーザーが見つからない場合の処理（任意）
            $error = "ユーザーIDが見つかりません。";
        }
        echo $error;

    } catch (PDOException $e) {
        // デバッグ時は $e->getMessage() を出すと原因がわかりやすいです
        echo 'DBエラー: ' . $e->getMessage();
    }
}
?>