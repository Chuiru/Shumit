<?php
  require_once('./functions.php');
  session_start();

  redirectIfNotLogin();
  $id = $_SESSION['user']['id'];
  $username = $_SESSION['user']['username'];
  $nichiji  = date('Y-m-d H:i:s');
  // POSTリクエストの場合
  if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST['title'];
    $body = $_POST['body'];
    $picture = $_FILES["upfile"]["name"];
    $category_id = $_POST['category_id'];

    // var_dump($_FILES["upfile"]);
    // exit();

    if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
  if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "files/" . $_FILES["upfile"]["name"])) {
    chmod("files/" . $_FILES["upfile"]["name"], 0644);
    echo $_FILES["upfile"]["name"] . "をアップロードしました。";
  } else {
    echo "ファイルをアップロードできません。";
  }
} else {
  echo "ファイルが選択されていません。";
}
    
    $pdo = connectDB();
    $sql = "INSERT INTO articles_p (user_id, title,body,picture,category_id,created_at,modified_at) VALUES(:user_id, :title,:body,:picture,:category_id,:created_at,:modified_at)";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
      ':user_id' => $id,
      ':title' => $title,
      ':body' => $body,
      ':picture' => $picture,
      ':category_id' => $category_id,
      ':created_at' => $nichiji,
      ':modified_at' => $nichiji,
    ]);
    header("Location: mypage.php");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>新規記事登録</title>
	<link href="./bootstrap-3.3.7-dist/css/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div id="all">
    <h2>新規記事登録</h2>
        <form action="" method="post" enctype="multipart/form-data">
          <p>タイトル:<input type="text" name="title" size="50" maxlength="50" value=""></p>
          <p>内容：<textarea name="body" rows="5" cols="40"></textarea></p>
          <p>ファイル：<br />
            <input type="file" name="upfile" size="30" /><br />
          </p>
          <div class="dropdown">
              <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          <select name="category_id">
            <option value="0">裁縫</option>
            <option value="1">編み物</option>
            <option value="2">レジン</option>
            <option value="3">ビーズ</option>
            <option value="4">紙工作</option>
            <option value="5">書道</option>
            <option value="6">電機</option>
            <option value="7">お絵かき</option>
          </select>
          <br />
          <input type="submit" value="送信">
        </form>

<!-- jQuery読み込み -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- BootstrapのJS読み込み -->
<script src="./bootstrap-3.3.7-dist/js/js/bootstrap.min.js"></script>
</body>
</html>
