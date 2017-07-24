<?php
  require_once('./functions.php');

  // DB接続
  $pdo = connectDB(); // ※ この関数はfunctions.phpに定義してある
  // 全記事(10記事文)を降順に取得するSQL文
  $sql = 'SELECT * FROM articles_p ORDER BY id DESC LIMIT 10';
  // SQLを実行
  $statement = $pdo->query($sql);
  // プレースメントフォルダが無いので，実行の表記が簡単
  $statement->execute();
  // $articles 連想配列に指定した記事が複数入っている状態↓
  $articles = $statement->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>しゅみっと！</title>
  <!-- BootstrapのCSS読み込み -->
  <link href="./bootstrap-3.3.7-dist/css/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="all">
  <div style="float:left;">
    <p>趣味でツナガルLNS</p>
    <h2 class="page-header">しゅみっと！</h2>
    <ul>
      <li><a href="./login.php">ログイン</a></li>
      <li><a href="./user_register.php">新規ユーザ登録</a></li>
     </ul>
   </div>
   <div>
     <h3>最新記事一覧</h3>
     <table class="table table-striped">
       <thead>
         <tr>
           <th>タイトル</th>
         </tr>
       </thead>
       <tbody>
         <?php foreach($articles as $article): ?>
           <tr>
             <td><a href="./article.php?title=<?php echo h($article['title']); ?>"><?php echo h($article['title']); ?></a></td>
           </tr>
         <?php endforeach; ?>
       </tbody>
     </table>
    </div>

</div>
<!-- jQuery読み込み -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- BootstrapのJS読み込み -->
<script src="./bootstrap-3.3.7-dist/js/js/bootstrap.min.js"></script>
</body>
</html>
