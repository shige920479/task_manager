<?php
require_once '../Services/helper.php';
use function App\Services\flash;
use function App\Services\old;
use function App\Services\setToken;

session_start();

$flash_array = "";
$old = "";
if(isset($_SESSION['error'])) {
  $flash_array = flash($_SESSION['error']);
  $old = old($_SESSION['old']);
  unset($_SESSION['error'], $_SESSION['old']);
}

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
      <h2>アカウント作成</h2>
      <form action="../Controller/MemberController.php" method="post" class="login-box">
        <ul>
          <div class="input">
              <label for="name">ユーザーネーム</label>
              <span><?php echo (isset($flash_array['name'])) ? $flash_array['name'] : ""; ?></span>
              <input type="text" name="name" id="name" value="<?php echo (isset($old['name'])) ? $old['name']: "";?>"/>
          </div>
          <div class="input">
            <label for="email">メールアドレス</label>
            <span><?php echo (isset($flash_array['email'])) ? $flash_array['email'] : ""; ?></span>
            <input type="email" name="email" id="email" value="<?php echo (isset($old['email'])) ? $old['email']: "";?>"/>
          </div>
          <div class="input">
            <label for="password">パスワード</label>
            <span><?php echo (isset($flash_array['password'])) ? $flash_array['password'] : ""; ?></span>
            <input type="password" name="password" id="password"/>
          </div>
          <div class="input">
            <label for="confirm-password">パスワード(確認用)</label>
            <input type="password" name="confirm-password" id="confirm-password"/>
          </div>
          <input type="hidden" name="mode" value="store_account">
          <input type="hidden" name="token" value="<?php echo setToken();?>">
          <button type="submit">アカウント登録</button>
        </ul>
        <a href="./MemberLoginView.php">ログイン画面に戻る</a>
      </form>
    </section>
  </body>
</html>