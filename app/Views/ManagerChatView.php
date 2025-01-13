<?php
require_once './app/Services/helper.php';
use function App\Services\h;
use function App\Services\setChatHtml;
?>

<?php include './app/Views/ManagerHeader.php';?>
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
                  <div><?php echo str_repeat('☆',$task['priority']);?></div>
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
                  <label for="content">タスク概要</label>
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
                <?php echo $chats ? setChatHtml($chats, MANAGER) : "" ?>
              </ul>
            </div>
            <form action="<?php echo PATH . 'manager_dashboard/';?>" method="post" id="message-box">
              <label>メッセージ入力<?php echo isset($flash_array['comment']) ? "<span class='flash-msg'>{$flash_array['comment']}</span>" : ""; ?></label>
              <textarea name="comment" rows="3"><?php echo isset($old['comment']) ? h($old['comment']) : ""; ?></textarea>
              
              <!-- 機能追加 -->
              <?php if($task['del_flag'] === 0) :?>
                <button type="submit" class="sendmsg-btn btn">メッセージ送信</button>
                <input type="hidden" name="mode" value="send_message">
              <?php elseif($task['del_flag'] === 1) :?>
                <button type="submit" class="sendback-btn btn">差戻メッセージ</button>
                <input type="hidden" name="mode" value="sendback_message">
              <?php endif ;?>  
              <input type="hidden" name="id" value="<?php echo h($task['id']) ?>">
              <input type="hidden" name="sender" value="0">
              <input type="hidden" name="token" value="<?php echo h($token);?>">
            </form>
          </section>
        </div>
      </div>
  </body>
</html>