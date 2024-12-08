<?php
use function App\Services\diffDate;
use function App\Services\h;
use function App\Services\MgSetReceiveIcon;
use function App\Services\MgSetSendIcon;
include '../Views/ManagerHeader.php';
?>
    <div class="task-wrapper">
      <section id="search-section">
        <h2>タスク検索</h2>
        <form action="" method="get" id="search">
          <ul id="search-flex">
            <li>
              <label for="name">メンバー名</label>
              <select name="name" id="name">
                <option value="">選択なし</option>
                <?php foreach($name_list as $name) :?>
                  <?php if($name === $in['name']): ?>
                    <option value="<?php echo $name ?>" selected><?php echo $name ?></option>
                  <?php else:?>
                    <option value="<?php echo $name ?>"><?php echo $name ?></option>
                  <?php endif; ?>
                <?php endforeach ;?>
              </select>
            </li>
            <li>
              <label for="category">カテゴリー</label>
              <select name="category" id="category">
                <option value="">選択なし</option>
                <?php foreach($category_list as $category):?>
                  <?php if($category === $in['category']): ?>
                    <option value="<?php echo $category ?>" selected><?php echo $category ?></option>
                  <?php else:?>
                    <option value="<?php echo $category ?>"><?php echo $category ?></option>
                  <?php endif; ?>
                <?php endforeach ;?>
              </select>
            </li>
            <li>
              <label for="theme">テーマ</label>
              <input type="text" name="theme">
            </li>
            <li><button type="submit" class="search-btn btn">検索</button></li>
          </ul>
          <input type="hidden" name="mode" value="index">
        </form>
      </section>
      <section id="m-task-list">
        <h2>タスク一覧</h2>
        <div id="sort-pagination">
          <form action="" method="get" id="sort">
            <select name="sort_order" id="sort_order">
              <option value="">新規登録順</option>
              <option value="sort_name" <?php echo isset($in['sort_order']) && $in['sort_order'] === 'sort_name' ? 'selected': "";?>>メンバー別</option>
              <option value="sort_category" <?php echo isset($in['sort_order']) && $in['sort_order'] === 'sort_category' ? 'selected': "";?>>カテゴリー別</option>
              <option value="sort_deadline" <?php echo isset($in['sort_order']) && $in['sort_order'] === 'sort_deadline' ? 'selected': "";?>>完了目標順</option>
              <option value="sort_priority" <?php echo isset($in['sort_order']) && $in['sort_order'] === 'sort_priority' ? 'selected': "";?>>優先度順</option>
            </select>
            <input type="hidden" name="mode" value="index">
            <input type="hidden" name="name" value="<?php echo $in['name'] ?? '';?>">
            <input type="hidden" name="category" value="<?php echo $in['category'] ?? '';?>">
            <input type="hidden" name="theme" value="<?php echo $in['theme'] ?? '';?>">

          </form>
          <ul id="paginate"><?php echo isset($paginate_tasks[1]) ? $paginate_tasks[1] : "" ;?></ul>
        </div>
        <table>
          <thead>
            <tr>
              <th>メンバー名</th><th>優先度</th><th>カテゴリー</th><th>タスクテーマ</th><th>タスク概要</th>
              <th>完了目標</th><th>残日数</th><th>送信</th><th>受信</th>
            </tr>
          </thead>
          <tbody>
            <?php if(isset($paginate_tasks[0])) :?>
            <?php foreach($paginate_tasks[0] as $task) :?>
            <tr>
              <td><?php echo h($task['name']);?></td>
              <td class="priority"><?php echo h(str_repeat('☆',$task['priority'])) ?></td>
              <td><?php echo h($task['category']) ?></td>
              <td class="edit-link"><?php echo "<a href='?mode=chat&id={$task['id']}'>{$task['theme']}</a>" ?></td>
              <td><?php echo h($task['content']) ?></td>
              <td><?php echo h($task['deadline']) ?></td>
              <td class="diff-date"><?php echo diffDate($task['deadline'])."日" ?></td>
              <td class="msg-icon">
                <?php echo MgSetSendIcon($task['msg_flag'],$task['mg_to_mem'] , $task['id']) ?>
              </td>
              <td class="msg-icon">
                <?php echo MgSetReceiveIcon($task['msg_flag'], $task['mem_to_mg'], $task['id']) ?>
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
