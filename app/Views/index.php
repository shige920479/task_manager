<?php
use function App\Services\h;
use function App\Services\setRecieveIcon;
use function App\Services\setSendIcon;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TODO</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="../../css/style.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../js/main.js"></script>
  </head>
  <body>
    <header>
      <div id="task-header" class="task-wrapper">
        <h1>タスクNOTE</h1>
        <div>
          <P>ユーザー名：<span><?php echo h($_SESSION['login_name'] )?>さん</span></P>
          <form action="../Controller/MemberController.php" method="post">
            <button id="logout-btn" type="submit">
              <img src="../../images/box-arrow-right.svg" alt="">
              <span>ログアウト</span>
              <input type="hidden" name="mode" value="logout">
              <input type="hidden" name="token" value="<?php echo h($token) ?>">
              <input type="hidden" name="login_user" value="<?php echo MEMBER?>">
            </button>
          </form>
        </div>
      </div>
    </header>

    <div class="task-wrapper">
      <!-- 要）CSS修正 -->
      <div style="text-align: right;"><a href="./MemberDashbordView.php?member_id=<?php echo $_SESSION['login_id'];?>">ダッシュボードへ</a></div>
      <section id="new-task">
        <h2>新規タスク登録</h2>
        <form action="?mode=store" method="post">
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
              <!-- <input type="text" /> -->
              <input type="text" name="category" list="categories" placeholder="テキスト入力または選択" autocomplete="off"
              value="<?php echo isset($old['category']) ? $old['category'] : ""; ?>"/>
              <datalist id="categories">
                <?php foreach($categories as $category): ?>
                  <option value="<?php echo $category['category']; ?>">
                <?php endforeach ;?>
              </datalist>
              <?php echo isset($flash_array['category']) ? "<span class='flash-msg'>{$flash_array['category']}</span>" : '' ?>
            </li>
            <li>
              <label for="theme">テーマ</label>
              <input type="text" name="theme" id="theme" value="<?php echo isset($old['theme']) ? $old['theme'] : "" ?>"/>
              <?php echo isset($flash_array['theme']) ? "<span class='flash-msg'>{$flash_array['theme']}</span>" : '' ?>
            </li>
            <li>
              <label for="content">タスク概略</label>
              <input type="text" name="content" id="content" value="<?php echo isset($old['content']) ? $old['content'] : "" ?>" />
              <?php echo isset($flash_array['content']) ? "<span class='flash-msg'>{$flash_array['content']}</span>" : '' ?>
            </li>
            <li>
              <label for="deadline">目標完了日</label>
              <input type="date" name="deadline" id="deadline" value="<?php echo isset($old['deadline']) ? $old['deadline'] : "" ?>"/>
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
            <!-- 宿題）↓↓↓ここはファンション化ですっきりさせたい -->        
            <?php if(!empty($_SESSION['del_msg'])) :?>
            <?php echo "<span>{$_SESSION['del_msg']}</span>"; ?>
            <?php $_SESSION['del_msg'] = ''?>
            <?php endif ;?>
            <!-- 後でaction="" method=""を追加する -->
          </div>
        <ul id="paginate"><?php echo $paginate_tasks[1] ? $paginate_tasks[1] : "" ;?></ul>
        <table>
          <thead>
            <tr>
              <th>優先度</th><th>カテゴリー</th><th>タイトル</th><th>コメント</th><th>目標完了日</th><th>送信</th><th>受信</th><th>完了</th>
            </tr>
          </thead>
          <tbody>
            <?php if(isset($paginate_tasks[0])) :?>
            <?php foreach($paginate_tasks[0] as $task) :?>
            <tr>
              <td class="priority"><?php echo str_repeat('☆', $task['priority'])?></td>
              <td><?php echo $task['category'] ?></td>
              <td class="edit-link"><?php echo "<a href='?mode=edit&id={$task['id']}'>{$task['theme']}</a>" ?></td>
              <td class="edit-link"><?php echo "<a href='?mode=edit?id={$task['id']}'>{$task['content']}</a>" ?></td>
              <td><?php echo $task['deadline'] ?></td>
              <td class="msg-icon">
                <?php echo setSendIcon($task['msg_flag'], $task['mem_to_mg'], $task['id']) ?>
              </td>
              <td class="msg-icon">
                <?php echo setRecieveIcon($task['msg_flag'], $task['mg_to_mem'], $task['id']) ?>
              </td>
              <td>
                <form action="../Controller/MemberController.php" method="post">
                  <button type="submit" class="comp-btn btn">完了</button>
                  <input type="hidden" name="mode" value="soft_del">
                  <input type="hidden" name="id" value="<?php echo $task['id'] ?>">
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
