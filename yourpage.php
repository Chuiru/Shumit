<?php
  require_once('./functions.php');
  session_start();

  $id = $_GET['id'];
  $pdo = connectDB();
  $sql = "SELECT * FROM articles_p WHERE user_id = :target_user_id";
  $statement = $pdo->prepare($sql);
    $statement->execute([
      ':target_user_id' => $id,
    ]);
  // $articles 連想配列に指定した記事が複数入っている状態↓
  $articles = $statement->fetchAll();

  $sql2 = 'SELECT * FROM users_p WHERE id = :id';
  $statement = $pdo->prepare($sql2);
  $statement->execute([':id' => $id]);
  $user = $statement->fetch();
// var_dump($user["username"]);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $user['username']; ?>の小部屋</title>
  <link href="./bootstrap-3.3.7-dist/css/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="all">
  <h2><?php echo $user['username']; ?>の小部屋</h2>
  <form action="" method="post">
    <input type="submit" value="友達申請する！">
  </form>
  <p><a href="./friend_list.php">友達</a></p>
   <div class="float">
     <h3><?php echo $user['username']; ?>の投稿</h3>
     <table border="2">
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
             <td><a href="m_article.php?id=<?php echo $article['id']; ?>"><?php echo h($article['title']); ?></a></td>
             <td><?php echo $article['created_at'];?></td>
             <td><?php echo $article['modified_at'];?></td>
           </tr>
         <?php endforeach; ?>
       </tbody>
     </table>
    </div>
</div>
<!-- jQuery読み込み -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- BootstrapのJS読み込み -->
<script src="./bootstrap-3.3.7-dist/js/js/bootstrap.min.js"></script></body>
</html>
