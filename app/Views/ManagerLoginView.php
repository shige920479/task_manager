<?php
use function App\Services\h;
use function App\Services\setToken;
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
      <form action="./ManagerLogin.php" method="post">
        <div class="login-box">
          <h3>Sign Up</h3>
          <ul>
            <div class="input">
              <label for="email">メールアドレス</label>
              <input type="email" name="email" id="email" value="<?php echo isset($old['email']) ? h($old['email']): ""; ?>"/>
              <?php if(isset($flash_array['email'])) echo "<span class='flash-msg'>{$flash_array['email']}</span>" ?>
            </div>
            <div class="input">
              <label for="password">パスワード</label>
              <input type="password" name="password" id="password"/>
              <?php if(isset($flash_array['password'])) echo "<span class='flash-msg'>{$flash_array['password']}</span>" ?>
            </div>
            <input type="hidden" name="mode" value="login">
            <input type="hidden" name="token" value="<?php echo h(setToken()); ?>">
            <button type="submit">Login</button>
          </ul>
        </div>
      </form>
      <div id="to-member">
        <a href="./MemberLogin.php">メンバー用ログイン画面はこちら</a>
      </div>
    </section>
  </body>
</html>