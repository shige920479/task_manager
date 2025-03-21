<?php
use function App\Services\h;
use function App\Services\setRecieveIcon;
use function App\Services\setSendIcon;
include './app/Views/MemberHeader.php';
?>

    <div class="task-wrapper">
      <section id="new-task">
        <h2>新規タスク登録</h2>
        <form action="dashboard?mode=store" method="post">
          <ul>
            <li>
              <label for="priority">優先度</label>
              <select name="priority">
                <option value="">選択</option>
                <option value="3" <?php echo isset($old['priority']) && $old['priority'] === "3" ? 'selected': '' ?>>高</option>
                <option value="2" <?php echo isset($old['priority']) && $old['priority'] === "2" ? 'selected': '' ?>>中</option>
                <option value="1" <?php echo isset($old['priority']) && $old['priority'] === "1" ? 'selected': '' ?>>低</option>
              </select>
              <?php echo isset($flash_array['priority']) ? "<span class='flash-msg'>{$flash_array['priority']}</span>" : '' ?>
            </li>
            <li>
              <label for="catgory">カテゴリー</label>
              <input type="text" name="category" list="categories" placeholder="テキスト入力または選択" autocomplete="off"
              value="<?php echo isset($old['category']) ? h($old['category']) : ""; ?>"/>
              <datalist id="categories">
                <?php foreach($categories as $category): ?>
                  <option value="<?php echo $category['category']; ?>">
                <?php endforeach ;?>
              </datalist>
              <?php echo isset($flash_array['category']) ? "<span class='flash-msg'>{$flash_array['category']}</span>" : '' ?>
            </li>
            <li>
              <label for="theme">テーマ</label>
              <input type="text" name="theme" id="theme" value="<?php echo isset($old['theme']) ? h($old['theme']) : "" ?>"/>
              <?php echo isset($flash_array['theme']) ? "<span class='flash-msg'>{$flash_array['theme']}</span>" : '' ?>
            </li>
            <li>
              <label for="content">タスク概略</label>
              <input type="text" name="content" id="content" value="<?php echo isset($old['content']) ? h($old['content']) : "" ?>" />
              <?php echo isset($flash_array['content']) ? "<span class='flash-msg'>{$flash_array['content']}</span>" : '' ?>
            </li>
            <li>
              <label for="deadline">目標完了日</label>
              <input type="date" name="deadline" id="deadline" value="<?php echo isset($old['deadline']) ? h($old['deadline']) : "" ?>"/>
              <?php echo isset($flash_array['deadline']) ? "<span class='flash-msg'>{$flash_array['deadline']}</span>" : '' ?>
            </li>
            <li>
              <button type="submit" id="regist-btn" class="btn">登録</button>
            </li>
          </ul>
          <input type="hidden" name="member_id" value="<?php echo h($_SESSION['login_id']) ?>"/>
          <input type="hidden" name="mode" value="store">
          <input type="hidden" name="token" value="<?php echo h($token)?>">
        </form>
      </section>
      <section id="task-list">
        <div id="title-page">
          <h2>タスク一覧</h2>
            <?php if(!empty($_SESSION['del_msg'])) :?>
            <?php echo "<span class='del_msg'>{$_SESSION['del_msg']}</span>"; ?>
            <?php unset($_SESSION['del_msg'])?>
            <?php endif ;?>
            <?php echo empty($tasks) ? "<span class='initial-msg'>未完了のタスクはありません</span>": '';?>
        </div>
        <div id="sort-pagination">
          <form action="dashboard" method="get" id="sort">
            <select name="sort_order" id="sort_order">
              <option value="">新規登録順</option>
              <option value="sort_deadline" <?php echo isset($request['sort_order']) && $request['sort_order'] === 'sort_deadline' ? 'selected': "";?>>目標完了日順</option>
              <option value="sort_category" <?php echo isset($request['sort_order']) && $request['sort_order'] === 'sort_category' ? 'selected': "";?>>カテゴリー別</option>
              <option value="sort_priority" <?php echo isset($request['sort_order']) && $request['sort_order'] === 'sort_priority' ? 'selected': "";?>>優先度順</option>
            </select>
            <input type="hidden" name="mode" value="index">
          </form>
          <ul id="paginate"><?php echo isset($paginate_tasks[1]) ? $paginate_tasks[1] : "" ;?></ul>
        </div>
        <table>
          <thead>
            <tr>
              <th>優先度</th><th>カテゴリー</th><th></th><th>テーマ</th><th>タスク概略</th><th>目標完了日</th><th>送信</th><th>受信</th><th>完了</th>
            </tr>
          </thead>
          <tbody>
            <?php if(isset($paginate_tasks[0])) :?>
            <?php foreach($paginate_tasks[0] as $task) :?>
            <tr>
              <td class="priority"><?php echo str_repeat('★', $task['priority'])?></td>
              <td><?php echo $task['category'] ?></td>
              <td class="comp-icon"><?php echo $task['del_flag'] === 2 ? '<img src="images/turnback-green.png">' : "" ;?></td>
              <td class="edit-link"><?php echo "<a href='dashboard?mode=edit&id={$task['id']}'>{$task['theme']}</a>" ?></td>
              <td><?php echo $task['content'] ?></td>
              <td><?php echo date('m月d日', strtotime($task['deadline']))  ?></td>
              <td class="msg-icon">
                <?php echo setSendIcon($task['msg_flag'], $task['mem_to_mg'], $task['id']) ?>
              </td>
              <td class="msg-icon">
                <?php echo setRecieveIcon($task['msg_flag'], $task['mg_to_mem'], $task['id']) ?>
              </td>
              <td>
                <form action="<?php echo PATH . 'dashboard' ?>" method="post">
                  <button type="submit" class="comp-btn btn">完了</button>
                  <input type="hidden" name="mode" value="soft_del">
                  <input type="hidden" name="id" value="<?php echo h($task['id']) ?>">
                  <input type="hidden" name="token" value="<?php echo h($token); ?>">
                </form>
              </td>
            </tr>
            <?php endforeach ;?>
            <?php endif ;?>
          </tbody>
        </table>
      </section>
    </div>
  </body>
</html>
