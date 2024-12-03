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
        <div id="header-nav">
          <div><a href="?mode=dashboard&member_id=<?php echo $_SESSION['login_id'];?>" id="dashboard-link">ダッシュボードへ</a></div>
          <div id="menu-icon">
            <img src="../../images/menu.png" alt="">
            <div class="menu-content">
              <p>member account</p>
              <P><?php echo h($_SESSION['login_name'] )?>さん</P>
              <form action="?mode=logout" method="post">
                <button id="logout-btn" type="submit">ログアウト</button>
                <input type="hidden" name="token" value="<?php echo h($token) ?>">
                <input type="hidden" name="login_user" value="<?php echo MEMBER?>">
              </form>
            </div>
          </div>
        </div>  
      </div>
    </header>