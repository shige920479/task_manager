<?php
use function App\Services\h;
use function App\Services\setToken;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TaskManager</title>
    <base href="<?php echo PATH;?>">
    <link rel="stylesheet" href="css/ress.min.css" />
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <section class="login-wrapper">
      <h1>Task Manager</h1>
      <h2>チームメンバーログイン</h2>
      <div class="login-box">
        <p style="color:cornflowerblue; text-align:center;"><?php echo isset($old['success']) ? h($old['success']) : '';?></p> 
        <h3>Sign Up</h3>
        <form action="<?php echo PATH?>" method="post">
          <ul>
            <div class="input">
              <label for="email">メールアドレス</label>
              <input type="email" name="email" id="email" value="<?php echo isset($old['email']) ? h($old['email']) : ''; ?>" />
              <?php if(isset($flash_array['email'])) echo "<span class='flash-msg'>{$flash_array['email']}</span>"; ?>
            </div>
            <div class="input">
              <label for="password">パスワード</label>
              
              <input type="password" name="password" id="password"/>
              <?php if(isset($flash_array['password'])) echo "<span class='flash-msg'>{$flash_array['password']}</span>"; ?>
            </div>
            <input type="hidden" name="mode" value="login">
            <input type="hidden" name="token" value="<?php echo h(setToken()); ?>">
            <button type="submit">Login</button>
          </ul>
        </form>
        <div id="to-register">
          <span>アカントが未登録ですか？</span>
          <a href="<?php echo PATH . 'account'?>" class="auth-link">→ アカウントを作成</a>
        </div>
      </div>
      <div id="to-manager">
        <a href="<?php echo PATH . 'managerLogin'?>" class="auth-link">管理者用ログイン画面はこちら</a>
      </div>
    </section>
  </body>
</html>