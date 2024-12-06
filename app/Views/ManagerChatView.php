<?php
require_once '../Services/helper.php';
use function App\Services\h;
use function App\Services\setToken;
use function App\Services\setChatHtml;
include '../Views/ManagerHeader.php';
?>

  <div id="chat-wrapper">
      <h1>コメント編集</h1>
      <div id="chat-flex">
        <section id="task-side">
            <ul>
              <li>
                <label for="priority">メンバー名</label>
                <div><?php echo h($task['name']) ?></div>
              </li>
              <li>
                <label for="priority">優先度</label>
                <div><?php echo str_repeat('☆',$task['priority']);?></div>
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
          <form action="?mode=send_message" method="post" id="message-box">
            <label>メッセージ入力<?php echo isset($flash_array['comment']) ? "<span class='flash-msg'>{$flash_array['comment']}</span>" : ""; ?></label>
            <textarea name="comment" rows="3"><?php echo isset($old['comment']) ? h($old['comment']) : ""; ?></textarea>
            <button type="submit" class="sendmsg-btn btn">メッセージ送信</button>
            <input type="hidden" name="mode" value="send_message">
            <input type="hidden" name="id" value="<?php echo $task['id'] ?>">
            <input type="hidden" name="sender" value="0">
            <input type="hidden" name="token" value="<?php echo h($token);?>">
          </form>
        </section>
      </div>
    </div>
</body>
</html>