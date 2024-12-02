<?php
use function App\Services\h;
include '../Views/MemberHeader.php';
?>

<div class="wrapper edit-wrapper">
      <h1>編集ページ</h1>
      <div id="to_index"><a href="?mode=index">一覧へ戻る</a></div>
      <section>
        <form action="?mode=update" method="post">
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
          <input type="hidden" name="token" value="<?php echo h($token);?>">
        </form> 
      </section>
    </div>
</body>
</html>