<?php
session_start();
require_once '../../vendor/autoload.php';
require_once '../Services/helper.php';
require_once '../config/config.php';

use App\Database\DbConnect;
use App\Services\GetForm;

use function App\Services\h;
use function App\Services\setToken;
use function App\Services\setChatHtml;

if(!isset($_SESSION['m_login'])) {
  header('Location: ./ManagerLoginView.php');
  exit;
}


$in = GetForm::getForm();
$task = DbConnect::selectId($in['id']);
$chats = DbConnect::getMessage($in['id'], MANAGER);
// echo "<pre>";
// var_dump($chats);
// echo "</pre>";
// exit;


?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TODO-EDIT</title>
  <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
  <link rel="stylesheet" href="../../css/style.css" />
</head>
<body>
  <div id="chat-wrapper">
      <h1>コメント編集</h1>
      <div id="to_index"><a href="./ManagerIndex.php">一覧へ戻る</a></div>
      <div id="chat-flex">
        <section id="task-side">
            <ul>
              <li>
                <label for="priority">メンバー名</label>
                <div><?php echo h($task['name']) ?></div>
              </li>
              <li>
                <label for="priority">優先度</label>
                <div>
                  <?php echo str_repeat('☆',$task['priority']);?>
                </div>
              </li>
              <li>
                <label for="category">カテゴリー</label>
                <div class="m-edit-list"><?php echo h($task['category']) ?></div>
              </li>
              <li>
                <label for="theme">タスクテーマ</label>
                <div class="m-edit-list"><?php echo h($task['theme'])?></div>
              </li>
              <li>
                <label for="content">タスク概要</label>
                <div class="m-edit-list"><?php echo h($task['content'])?></div>
              </li>
              <li>
                <label for="deadline">完了目標</label>
                <div class="m-edit-list"><?php echo h($task['deadline'])?></div>
              </li>
            </ul>
        </section>
        <section id="chat-side">
          <label>メッセージボックス</label>
          <ul class="chat-room">
          <?php echo $chats ? setChatHtml($chats, MANAGER) : "" ?>
          </ul>

          <!-- メッセージを飛ばすと、mem_to_mg が2になる、送信マークも出ない・・・＞原因調査 -->
          <form action="../Controller/ManagerController.php" method="post" id="message-box">
            <label>メッセージ入力</label>
            <textarea name="comment" rows="3"></textarea>
            <button type="submit" class="sendmsg-btn btn">メッセージ送信</button>
            <input type="hidden" name="mode" value="send_message">
            <input type="hidden" name="id" value="<?php echo $task['id'] ?>">
            <input type="hidden" name="sender" value="0">
            <input type="hidden" name="token" value="<?php echo h(setToken());?>">
          </form>
        </section>
      </div>
    </div>
</body>
</html>