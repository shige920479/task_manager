<?php
use function App\Services\h;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TODO</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="../../css/style.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../js/main.js"></script>
  </head>
  <body>
    <header>
      <div id="task-header" class="task-wrapper">
        <h1>タスクNOTE</h1>
        <div>
          <P>ユーザー名：<span><?php echo h($_SESSION['login_name'] )?>さん</span></P>
          <form action="?mode=logout" method="post">
            <button id="logout-btn" type="submit">
              <img src="../../images/box-arrow-right.svg" alt="">
              <span>ログアウト</span>
              <input type="hidden" name="token" value="<?php echo h($token) ?>">
              <input type="hidden" name="login_user" value="<?php echo MEMBER?>">
            </button>
          </form>
        </div>
      </div>
    </header>