<?php
use function App\Services\h;
use function App\Services\setToken;
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
        <h1>メンバータスク一覧</h1>
        <div id="header-nav">
          <div><a href="?mode=dashboard" id="dashboard-link">ダッシュボードへ</a></div>
          <div id="menu-icon">
            <img src="../../images/menu.png" alt="">
            <div class="menu-content">
              <p>manager account</p>
              <P><?php echo h($_SESSION['m_login_name'])?>さん</P>
              <form action="?mode=logout" method="post">
                <button id="logout-btn" type="submit">ログアウト</button>
                <input type="hidden" name="login_user" value="<?php echo MANAGER ?>">
                <input type="hidden" name="token" value="<?php echo h(setToken()) ?>">
              </form>
            </div>
          </div>
        </div>
      </div>
    </header>

