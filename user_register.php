<?php
  require_once('./functions.php');
  session_start();


  // POSTリクエストの場合
  if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 送られた値を変数に格納
    $username = $_POST['username'];
    $passwd = $_POST['passwd'];
    $passwd_confirmation = $_POST['passwd_confirmation'];
    $address = $_POST['address'];

    // 未入力の項目があるか
    if (empty($username) || empty($passwd) || empty($passwd_confirmation) || empty($address)) {
        $_SESSION["error"] = "入力されていない項目があります";
        header("Location: user_register.php");
        return;
    }

    // パスワードとパスワード確認が一致しているか
    if ($passwd !== $passwd_confirmation) {
        $_SESSION["error"] = "パスワードが一致しません";
        header("Location: user_register.php");
        return;
    }

    $pdo = connectDB();
    $sql = "INSERT INTO users_p (username, passwd, address) VALUES(:username, :passwd, :address)";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
        ':username' => $username,
        ':passwd' => crypt($passwd),
        ':address' => $address,
    ]);
    if (!$result) {
        die('Database Error');
    }

    // セッションにメッセージを格納
    $_SESSION["success"] = "登録が完了しました。ログインしてください。";
    // ログイン画面に遷移
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ユーザー登録</title>
	<link href="./bootstrap-3.3.7-dist/css/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div id="all">

    <!-- セッション変数(successやerror)に値が入っている場合の処理
    ログインに成功した．または失敗した理由を表示 -->
      <!-- Success Message -->
      <?php if(!empty($_SESSION['success'])): ?>
          <div class="alert alert-success" role="success">
            <!-- メッセージを表示 -->
              <pre><?php echo $_SESSION['success']; ?></pre>
            <!-- セッション変数 succcess の値を空に -->
              <?php $_SESSION['success'] = null; ?>
          </div>
      <?php endif; ?>
      <!-- Error Message -->
      <?php if(!empty($_SESSION['error'])): ?>
          <div>
            <!-- メッセージを表示 -->
              <pre><?php echo $_SESSION['error']; ?></pre>
            <!-- セッション変数 succcess の値を空に -->
              <?php $_SESSION['error'] = null; ?>
          </div>
      <?php endif; ?>

    <form action="" method="POST">
        <p>ユーザー名
            <input type="text" name="username">
        </p>
        <p>パスワード
            <input type="password" name="passwd">
        </p>
        <p>パスワード再入力
            <input type="password" name="passwd_confirmation">
        </p>
        <p>居住地
            <input type="text" name="address">
        </p>
        <input type="submit" value="ログイン">
    </form>
	</div>

<!-- jQuery読み込み -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- BootstrapのJS読み込み -->
<script src="./bootstrap-3.3.7-dist/js/js/bootstrap.min.js"></script></body>
</html>
