<?php
  require_once('./functions.php');
  session_start();

  // DB接続
  $pdo = connectDB();
  //利用者のIDを獲得
  $user_id = $_SESSION['user']['id'];
  $sql0 = 'SELECT * FROM users_p WHERE id = :user_id';
  $statement = $pdo->prepare($sql0);
  $statement->execute([':user_id' => $user_id]);
  $result_u = $statement->fetch();
  // URLに含まれている記事のIDを取得
  $id = $_GET['id'];
    // 以下4行、記事をDBから取得し、変数$articleに格納
  $sql1 = 'SELECT * FROM articles_p WHERE id = :id';
  $statement = $pdo->prepare($sql1);
  $statement->execute([':id' => $_GET['id']]);
  $article = $statement->fetch();

  //記事作成者のIDを取得
  $writer_id = $article['user_id'];
  $sql2 = 'SELECT * FROM users_p WHERE id = :user_id';
  $statement = $pdo->prepare($sql2);
  $statement->execute([':user_id' => $writer_id]);
  $result_w = $statement->fetch();

  $category_id = $article['category_id'];
  $sql3 = 'SELECT * FROM category WHERE id = :category_id';
  $statement = $pdo->prepare($sql3);
  $statement->execute([':category_id' => $category_id]);
  $result_c = $statement->fetch();
  // var_dump($result_c);

  $sql7 = 'SELECT comment.comment, comment.cdate, users_p.username FROM comment LEFT JOIN users_p ON comment.user_id = users_p.id WHERE article_id = :id';
  $statement = $pdo->prepare($sql7);
  $statement->execute([':id' => $id]);
  $comments = $statement->fetchAll();

  //コメントが挿入されたら、commentテーブルにデータを格納
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $comment = $_POST['body'];
    $nichiji  = date('Y-m-d H:i:s');
    $sql4 = "INSERT INTO comment (user_id,cdate,comment,writer_id,article_id) VALUES(:user_id,:cdate,:comment,:writer_id,:article_id)";
    $statement = $pdo->prepare($sql4);
    $result = $statement->execute([
      ':user_id' => $user_id,
      ':cdate' => $nichiji,
      ':comment' => $comment,
      ':writer_id' => $writer_id,
      ':article_id' => $id,
    ]);

    // //同時にarticles_pテーブルへcomment_idを格納
    // $comment_id = $_POST['body'];
    // $sql5 = 'SELECT * FROM comment WHERE id = ';
    // $statement = $pdo->prepare($sql5);
    // $statement->execute(['id' => $comment_id]);
    // $result_co = $statement->fetch();
    // $sql6 = "INSERT INTO articles_p (comment_id) VALUES(:comment_id)";
    // $statement = $pdo->prepare($sql6);
    // $result_co1 = $statement->execute([
    //   ':comment_id' => $comment_id,
    // ]);
    header("Location: mypage.php");
}
?>
<!DOCTYPE html>

<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>しゅみっと！</title>
    <link href="./bootstrap-3.3.7-dist/css/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div>
  <div id="all">
    <h2 class="text-center">しゅみっと！</h2>

    <?php
      $message = "戻る";
      // リファラ値がなければ<a>タグを挿入しない
    if (empty($_SERVER['HTTP_REFERER'])) {
      echo $message;
    }
    // リファラ値があれば<a>タグ内へ
    else {
      echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">' . $message . "</a>";
    }
    ?>
    <div class="float">
      <h1>
          <?php echo h($article['title']); ?>
      </h1>
      <p>
        投稿者：<a href="yourpage.php?id=<?php echo $result_w['id']; ?>"><?php echo h($result_w['username']); ?></a>
      </p>
      <p class="date">
        投稿日時：<?php echo h($article['modified_at']); ?>
      <p>内容:</p>
      <pre><?php echo h($article['body']); ?></pre>
      <img class="img-circle" src="./pic/<?php echo h($article['picture']); ?>" width="250" height="300">
      <pre>カテゴリ:<?php echo mb_convert_encoding(h($result_c['name']), "UTF-8"); ?></pre>
    </div>

    <h3>コメント一覧</h3>
    <?php foreach($comments as $comment): ?>
      <table>
        <tr><td><?php echo $comment['username'];?></td></tr>
        <tr><td class="date"><?php echo $comment['cdate'];?></td></tr>
        <tr><td><?php echo $comment['comment'];?></td></tr>
      </table>
    <?php endforeach; ?>

    <p>コメントはこちら♪</p>
    <form action="" method="post">
      <?php echo h($result_u['username']); ?>
      <p>内容：<textarea name="body" rows="5" cols="40"></textarea></p>
      <input type="submit" value="送信">
    </form>
</div>
<!-- jQuery読み込み -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- BootstrapのJS読み込み -->
<script src="./bootstrap-3.3.7-dist/js/js/bootstrap.min.js"></script>
</body>
</html>
