<?php
require_once '../Services/helper.php';
use function App\Services\h;
use function App\Services\MgSetReceiveIcon;
use function App\Services\MgSetSendIcon;
use function App\Services\setToken;
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
        <h1>メンバータスク一覧</h1>
        <div>
          <P>ユーザー名：<span><?php echo h($_SESSION['m_login_name'])?>さん</span></P>
          <form action="?mode=logout" method="post">
            <button id="logout-btn" type="submit">
              <img src="../../images/box-arrow-right.svg" alt="">
              <span>ログアウト</span>
              <input type="hidden" name="login_user" value="<?php echo MANAGER ?>">
              <input type="hidden" name="token" value="<?php echo h(setToken()) ?>">
            </button>
          </form>
        </div>
      </div>
    </header>
    <div class="task-wrapper">
    <div style="text-align: right;"><a href="?mode=dashboard">ダッシュボードへ</a></div>
      <section id="search-section">
        <h2>タスク検索</h2>
        <form action="" method="get" id="search">
          <ul id="search-flex">
            <li>
              <label for="name">メンバー名</label>
              <select name="name" id="name">
                <option value="">選択してください</option>
                <?php foreach($name_list as $name) :?>
                <option value="<?php echo $name ?>"><?php echo $name ?></option>
                <?php endforeach ;?>
              </select>
            </li>
            <li>
              <label for="category">カテゴリー</label>
              <select name="category" id="category">
                <option value="">選択してください</option>
                <?php foreach($category_list as $category) :?>
                <option value="<?php echo $category ?>"><?php echo $category ?></option>
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
        <div>
          <h2>タスク一覧</h2>
            <!-- 宿題）↓↓↓ここはファンション化ですっきりさせたい -->        
            <?php if(!empty($_SESSION['del_msg'])) :?>
            <?php echo "<span>{$_SESSION['del_msg']}</span>"; ?>
            <?php $_SESSION['del_msg'] = ''?>
            <?php endif ;?>
            <!-- 後でaction="" method=""を追加する -->
        </div>
        <ul id="paginate"><?php echo isset($paginate_tasks[1]) ? $paginate_tasks[1] : "" ;?></ul>
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
              <td class="edit-link"><?php echo "<a href='?mode=chat&id={$task['id']}'>{$task['content']}</a>" ?></td>
              <td><?php echo h($task['deadline']) ?></td>
              <td>残日数</td>
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
