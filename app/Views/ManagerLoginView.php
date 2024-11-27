<?php
session_start();
require_once '../Services/helper.php';

use function App\Services\flash;
use function App\Services\h;
use function App\Services\old;
use function App\Services\setToken;


list($flash_array, $old) = [null, null];
if(isset($_SESSION['error'])) { $flash_array = flash($_SESSION['error']);}
if(isset($_SESSION['old'])) { $old = old($_SESSION['old']); }
unset($_SESSION['error'], $_SESSION['old']);

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="../../css/style.css">
  </head>
  <body>
    <section class="login-wrapper">
      <h1>Task Manager</h1>
      <h2 id="manager-title">管理者ログイン</h2>
      <form action="../Controller/ManagerController.php" method="post">
        <div class="login-box">
          <h3>Sign Up</h3>
          <ul>
            <div class="input">
              <label for="email">メールアドレス</label>
              <input type="email" name="email" id="email"/>
              <?php if(isset($flash_array['email'])) echo h("<span class='flash-msg'>{$flash_array['email']}</span>") ?>
            </div>
            <div class="input">
              <label for="password">パスワード</label>
              <input type="password" name="password" id="password"/>
              <?php if(isset($flash_array['password'])) echo h("<span class='flash-msg'>{$flash_array['password']}</span>") ?>
            </div>
            <input type="hidden" name="mode" value="login">
            <input type="hidden" name="token" value="<?php echo h(setToken()); ?>">
            <button type="submit">Login</button>
          </ul>
        </div>
      </form>
      <div id="to-member">
        <a href="./MemberLoginView.php">メンバー用ログイン画面はこちら</a>
      </div>
    </section>
  </body>
</html>