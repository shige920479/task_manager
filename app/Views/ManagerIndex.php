<?php
use function App\Services\diffDate;
use function App\Services\h;
use function App\Services\MgSetReceiveIcon;
use function App\Services\MgSetSendIcon;
?>
<?php include './app/Views/ManagerHeader.php';?>
    <div class="task-wrapper">
      <section id="search-section">
        <h2>タスク検索</h2>
        <form action="<?php echo PATH . 'manager_dashboard/' ?>" method="get" id="search">
          <ul id="search-flex">
            <li>
              <label for="name">メンバー名</label>
              <select name="name" id="name">
                <option value="">全て選択</option>
                <?php foreach($members as $member) :?>
                  <?php if($member['name'] === $request['name']): ?>
                    <option value="<?php echo $member['name'] ?>" selected><?php echo h($member['name']) ?></option>
                  <?php else:?>
                    <option value="<?php echo $member['name'] ?>"><?php echo h($member['name']) ?></option>
                  <?php endif; ?>
                <?php endforeach ;?>
              </select>
            </li>
            <li>
              <label for="category">カテゴリー</label>
              <select name="category" id="category">
                <option value="">全て選択</option>
                <?php foreach($category_list as $category):?>
                  <?php if($category === $request['category']): ?>
                    <option value="<?php echo $category ?>" selected><?php echo h($category) ?></option>
                  <?php else:?>
                    <option value="<?php echo $category ?>"><?php echo h($category) ?></option>
                  <?php endif; ?>
                <?php endforeach ;?>
              </select>
            </li>
            <li>
              <label for="theme">テーマ</label>
              <input type="text" name="theme" value="<?php echo isset($request['theme']) ? h($request['theme']): "";?>">
            </li>
            <li><button type="submit" class="search-btn btn">検索</button></li>
          </ul>
          <input type="hidden" name="mode" value="index">
        </form>
      </section>
      <section id="m-task-list">
        <div id="title-page">
          <h2>タスク一覧</h2>
            <?php if(!empty($_SESSION['del_msg'])) :?>
                <?php echo "<span class='del_msg'>{$_SESSION['del_msg']}</span>"; ?>
                <?php unset($_SESSION['del_msg']);?>
            <?php endif ;?>
            <?php echo empty($tasks) ? "<span class='initial-msg'>未完了のタスクはありません</span>": '';?>
        </div>
        <div id="sort-pagination">
          <form action="<?php echo PATH . 'manager_dashboard/';?>" method="get" id="sort">
            <select name="sort_order" id="sort_order">
              <option value="">新規登録順</option>
              <option value="sort_name" <?php echo isset($request['sort_order']) && $request['sort_order'] === 'sort_name' ? 'selected': "";?>>メンバー別</option>
              <option value="sort_category" <?php echo isset($request['sort_order']) && $request['sort_order'] === 'sort_category' ? 'selected': "";?>>カテゴリー別</option>
              <option value="sort_deadline" <?php echo isset($request['sort_order']) && $request['sort_order'] === 'sort_deadline' ? 'selected': "";?>>完了目標順</option>
              <option value="sort_priority" <?php echo isset($request['sort_order']) && $request['sort_order'] === 'sort_priority' ? 'selected': "";?>>優先度順</option>
            </select>
            <input type="hidden" name="mode" value="index">
            <input type="hidden" name="name" value="<?php echo $request['name'] ?? '';?>">
            <input type="hidden" name="category" value="<?php echo $request['category'] ?? '';?>">
            <input type="hidden" name="theme" value="<?php echo $request['theme'] ?? '';?>">

          </form>
          <ul id="paginate"><?php echo isset($paginate_tasks[1]) ? $paginate_tasks[1] : "" ;?></ul>
        </div>
        <table>
          <thead>
            <tr>
              <th>メンバー名</th><th>優先度</th><th>カテゴリー</th><th>完了</th><th>タスクテーマ</th><th>タスク概要</th>
              <th>完了目標</th><th>残日数</th><th>送信</th><th>受信</th><th>削除</th>
            </tr>
          </thead>
          <tbody>
            <?php if(isset($paginate_tasks[0])) :?>
            <?php foreach($paginate_tasks[0] as $task) :?>
            <tr>
              <td><?php echo $task['name'];?></td>
              <td class="priority"><?php echo h(str_repeat('★',$task['priority'])) ?></td>
              <td><?php echo $task['category'] ?></td>
              <td class="comp-icon">
                <?php if($task['del_flag'] === 1): ?>
                  <img src="<?php echo PATH . 'images/check-pink.png';?>">
                <?php elseif($task['del_flag'] === 2): ?>
                  <img src="<?php echo PATH . 'images/turnback-green.png';?>">
                <?php endif ;?>
              </td>
              <td class="edit-link"><?php echo "<a href='?mode=chat&id={$task['id']}'>{$task['theme']}</a>" ?></td>
              <td><?php echo $task['content'] ?></td>
              <td><?php echo date('m月d日', strtotime($task['deadline']))  ?></td>
              <td class="diff-date" data-days="<?= diffDate($task['deadline']) ?>"><?= diffDate($task['deadline'])."日" ?></td>
              <td class="msg-icon">
                <?php echo MgSetSendIcon($task['msg_flag'],$task['mg_to_mem'] , $task['id']) ?>
              </td>
              <td class="msg-icon">
                <?php echo MgSetReceiveIcon($task['msg_flag'], $task['mem_to_mg'], $task['id']) ?>
              </td>
              <td>
                <?php if($task['del_flag'] === 1):?>
                  <form action="<?php echo PATH . 'manager_dashboard/';?>" method="post">
                    <button type="submit" class="del-btn btn">削除</button>
                    <input type="hidden" name="mode" value="hard_del">
                    <input type="hidden" name="id" value="<?php echo h($task['id'])?>">
                    <input type="hidden" name="token" value="<?php echo h($token);?>">
                  </form>
                <?php endif;?>
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
