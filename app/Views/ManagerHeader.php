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
        <div>
          <P>ユーザー名：<span><?php echo h($_SESSION['m_login_name'])?>さん</span></P>
          <form action="../Controller/ManagerController.php" method="post">
            <button id="logout-btn" type="submit">
              <img src="../../images/box-arrow-right.svg" alt="">
              <span>ログアウト</span>
              <input type="hidden" name="mode" value="logout">
              <input type="hidden" name="login_user" value="<?php echo MANAGER ?>">
              <input type="hidden" name="token" value="<?php echo h(setToken()) ?>">
            </button>
          </form>
        </div>
      </div>
    </header>

