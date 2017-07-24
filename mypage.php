<?php
  require_once('./functions.php');
  session_start();

  // ログインしていなかったら、ログイン画面にリダイレクトする
  redirectIfNotLogin(); // ※ この関数はfunctions.phpに定義してある
  $id = $_SESSION['user']['id'];
  $username = $_SESSION['user']['username'];
  $address = $_SESSION['user']['address'];

  $pdo = connectDB();
  //$sql0 = 'SELECT address FROM user_p GROUP BY address HAVING $address';
  //'SELECT * FROM users_p where address in (SELECT address FROM users_p GROUP BY address HAVING COUNT(address) > 1)'
  $sql1 = 'SELECT * FROM articles_p ORDER BY id DESC LIMIT 5';
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category_id = $_POST['category_id'];
    // var_dump($category_id);
    $sql1 = "SELECT * FROM articles_p WHERE category_id = $category_id ORDER BY id DESC LIMIT 5";
  }


  // SQLを実行
  $statement1 = $pdo->query($sql1);
  // プレースメントフォルダが無いので，実行の表記が簡単
  $statement1->execute();
  // $articles 連想配列に指定した記事が複数入っている状態↓
  $articles1 = $statement1->fetchAll();

  $sql = "SELECT * FROM articles_p WHERE user_id = :target_user_id";
  $statement = $pdo->prepare($sql);
    $statement->execute([
      ':target_user_id' => $id,
    ]);
  // $articles 連想配列に指定した記事が複数入っている状態↓
  $articles = $statement->fetchAll();


?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $username; ?>の小部屋</title>
  <link href="./bootstrap-3.3.7-dist/css/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="all">
  <h2><?php echo $username; ?>の小部屋</h2>
  <ul>
    <li><a href="./new_article.php">新規記事</a></li>
    <li><a href="./friend_list.php">友達</a></li>
    <li><a href="./logout.php">ログアウト</a></li>
   </ul>
   <div class="float">
     <h3>自分の投稿</h3>
     <table class="table table-striped" border="2">
       <thead>
         <tr>
           <th>タイトル</th>
           <th>作成日</th>
           <th>修正日</th>
         </tr>
       </thead>
       <tbody>
         <?php foreach($articles as $article): ?>
           <tr>
             <td><a href="m_article.php?id=<?php echo $article['id'];?>"><?php echo h($article['title']);?></a></td>
             <td><?php echo $article['created_at'];?></td>
             <td><?php echo $article['modified_at'];?></td>
           </tr>
         <?php endforeach; ?>
       </tbody>
     </table>
    </div>
    <div id="sa">
      <h3>最新記事一覧</h3>
      <form action="" method="post">
        <select name="category_id">
          <option value="all">カテゴリを選択</option>
          <option value="0">裁縫</option>
          <option value="1">編み物</option>
          <option value="2">レジン</option>
          <option value="3">ビーズ</option>
          <option value="4">紙工作</option>
          <option value="5">書道</option>
          <option value="6">電機</option>
          <option value="7">お絵かき</option>
        </select>
        <input type="submit" value="決定">
      <table class="table table-striped" border="2">
        <thead>
          <tr>
            <th>タイトル</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($articles1 as $article1): ?>
            <tr>
              <td><a href="./m_article.php?id=<?php echo $article1['id']; ?>"><?php echo h($article1['title']); ?></a></td>
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
