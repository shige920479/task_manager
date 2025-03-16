<?php
use function App\Services\h;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TaskManager</title>
    <base href="<?php echo PATH;?>">
    <link rel="stylesheet" href="css/ress.min.css" />
    <link rel="stylesheet" href="css/style.css"/>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="js/main.js"></script>
  </head>
  <body>
    <header>
      <div id="task-header" class="task-wrapper">
        <h1>TaskManager</h1>
        <div id="header-nav">
          <div><a href="dashboard?mode=index" id="header-link">タスク一覧</a></div>
          <div><a href="dashboard?mode=callender&member_id=<?php echo $_SESSION['login_id'];?>" id="header-link">タスクカレンダー</a></div>
          <div id="menu-icon">
            <img src="images/menu.png" alt="">
            <div class="menu-content">
              <P><?php echo $_SESSION['login_name'];?>さん</P>
              <form action="<?php echo PATH . 'dashboard?mode=logout'?>" method="post">
                <button id="logout-btn" type="submit">ログアウト</button>
                <input type="hidden" name="token" value="<?php echo h($token) ?>">
                <input type="hidden" name="login_user" value="<?php echo MEMBER?>">
              </form>
            </div>
          </div>
        </div>  
      </div>
    </header>