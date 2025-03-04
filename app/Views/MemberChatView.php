<?php
use function App\Services\h;
use function App\Services\setChatHtml;
?>

<?php include './app/Views/MemberHeader.php'; ?>
  <div id="chat-wrapper">
      <h1>コメント編集</h1>
      <div id="chat-flex">
        <section id="task-side">
            <ul>
              <li>
                <label for="priority">メンバー名</label>
                <div><?php echo $task['name'] ?></div>
              </li>
              <li>
                <label for="priority">優先度</label>
                <div>
                  <?php echo str_repeat('☆',$task['priority']);?>
                </div>
              </li>
              <li>
                <label for="category">カテゴリー</label>
                <div class="m-edit-list"><?php echo $task['category'] ?></div>
              </li>
              <li>
                <label for="theme">タスクテーマ</label>
                <div class="m-edit-list"><?php echo $task['theme']?></div>
              </li>
              <li>
                <label for="content">タスク概略</label>
                <div class="m-edit-list"><?php echo $task['content']?></div>
              </li>
              <li>
                <label for="deadline">完了目標</label>
                <div class="m-edit-list"><?php echo $task['deadline']?></div>
              </li>
            </ul>
        </section>
        <section id="chat-side">
          <label>メッセージボックス</label>
          <div id="chat-room">
            <ul id="chat-inner">
              <?php echo $chats ? setChatHtml($chats, MEMBER) : "" ?>
            </ul>
          </div>
          <form action="<?php echo PATH . 'dashboard/' ?>" method="post" id="message-box">
            <label>メッセージ入力<?php echo isset($flash_array['comment']) ? "<span class='flash-msg'>{$flash_array['comment']}</span>" : ""; ?></label>
            <textarea name="comment" rows="3"><?php echo isset($old['comment']) ? h($old['comment']) : ""; ?></textarea>
            <button type="submit" class="sendmsg-btn btn">メッセージ送信</button>
            <input type="hidden" name="id" value="<?php echo h($task['id']) ?>">
            <input type="hidden" name="sender" value="1">
            <input type="hidden" name="mode" value="send_message">
            <input type="hidden" name="token" value="<?php echo h($token);?>">
          </form>
        </section>
      </div>
    </div>
</body>
</html>