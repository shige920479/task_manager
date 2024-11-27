<?php
session_start();
require_once '../../vendor/autoload.php';
require_once '../Services/helper.php';
use App\Database\DbConnect;
use App\Services\GetForm;
use function App\Services\flash;
use function App\Services\h;
use function App\Services\old;
use function App\Services\setToken;

if(!isset($_SESSION['login'])) {
  header('Location: ./MemberLoginView.php');
  exit;
}

// echo __DIR__;
// echo '<br>';
// echo __FILE__;
// echo '<br>';
// echo dirname(__FILE__);

// exit;


$in = GetForm::getForm();
// echo "<pre>";
// var_dump($in);
// echo "</pre>";
// exit;


$data = DbConnect::selectId($in['id']);

// echo "<pre>";
// var_dump($data);
// echo "</pre>";
// exit;

$categories = DbConnect::getCategory($data['member_id']); 
// echo "<pre>";
// var_dump($categories);
// echo "</pre>";
// exit;
$flash_array = "";
$old = "";
if(isset($_SESSION['error'])) {
  $flash_array = flash($_SESSION['error']);
  unset($_SESSION['error']);
}
if(isset($_SESSION['old'])) {
  $old = old($_SESSION['old']);
  unset($_SESSION['old']);
}



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
<div class="wrapper edit-wrapper">
      <h1>編集ページ</h1>
      <div id="to_index"><a href="./index.php">一覧へ戻る</a></div>
      <section>
        <form action="../Controller/MemberController.php" method="post">
          <ul id="edit-table">
            <li>
              <label for="priority">優先度</label>
              <select name="priority" id="priority">
                  <option value="1">1:高</option>
                  <option value="2">2:中</option>
                  <option value="3">3:低</option>
              </select>
              <?php echo isset($flash_array['priority']) ? "<span class='flash-msg'>{$flash_array['priority']}</span>" : '' ?>
            </li>
            <li>
              <label for="category">カテゴリー</label>
              <input type="text" name="category" list="categories" value="<?php echo h($data['category']) ?>" autocomplete="off" />
              <datalist id="categories">
              <?php foreach($categories as $category): ?>
                  <option value="<?php echo $category['category']; ?>"><?php echo $category['category']; ?></option>
              <?php endforeach ;?>
              </datalist>
              <?php echo isset($flash_array['category']) ? "<span class='flash-msg'>{$flash_array['category']}</span>" : '' ?>
            </li>
            <li>
              <label for="theme">タイトル</label>
              <input type="text" name="theme" id="theme" value="<?php echo $data['theme']?>"/>
              <?php echo isset($flash_array['theme']) ? "<span class='flash-msg'>{$flash_array['theme']}</span>" : '' ?>
            </li>
            <li>
              <label for="content">コメント</label>
              <textarea type="text" name="content" id="content"><?php echo $data['content']?></textarea>
              <?php echo isset($flash_array['content']) ? "<span class='flash-msg'>{$flash_array['content']}</span>" : '' ?>
            </li>
            <li>
              <label for="deadline">目標完了日</label>
              <input type="date" name="deadline" id="deadline" value="<?php echo $data['deadline']?>"/>
              <?php echo isset($flash_array['deadline']) ? "<span class='flash-msg'>{$flash_array['deadline']}</span>" : '' ?>
            </li>
            </li>
            <li>
              <button type="submit" id="regist-btn" class="btn">登録</button>
            </li>
          </ul>
          <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
          <input type="hidden" name="mode" value="update">
          <input type="hidden" name="token" value="<?php echo h(setToken());?>">
        </form> 
      </section>
    </div>
</body>
</html>