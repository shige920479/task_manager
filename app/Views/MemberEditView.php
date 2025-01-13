<?php
use function App\Services\h; 
?>
<?php include './app/Views/MemberHeader.php'; ?>
<div class="wrapper edit-wrapper">
      <h1>編集ページ</h1>
      <div id="to_index"><a href="?mode=index">一覧へ戻る</a></div>
      <section>
        <form action="<?php echo PATH;?>" method="post">
          <ul id="edit-table">
            <li>
              <label for="priority">優先度</label>
              <?php echo isset($flash_array['priority']) ? "<span class='flash-msg'>{$flash_array['priority']}</span>" : '' ?>
              <select name="priority" id="priority">
                  <option value="3" <?php echo $edit_data['priority'] === 3 ? "selected": "";?>>高</option>
                  <option value="2" <?php echo $edit_data['priority'] === 2 ? "selected": "";?>>中</option>
                  <option value="1" <?php echo $edit_data['priority'] === 1 ? "selected": "";?>>低</option>
              </select>
            </li>
            <li>
              <label for="category">カテゴリー</label>
              <?php echo isset($flash_array['category']) ? "<span class='flash-msg'>{$flash_array['category']}</span>" : '' ?>
              <input type="text" name="category" list="categories" value="<?php echo ($edit_data['category']) ?? '';?>" autocomplete="off" />
              <datalist id="categories">
              <?php foreach($categories as $category): ?>
                  <option value="<?php echo $category['category']; ?>"></option>
              <?php endforeach ;?>
              </datalist>
            </li>
            <li>
              <label for="theme">タイトル</label>
              <?php echo isset($flash_array['theme']) ? "<span class='flash-msg'>{$flash_array['theme']}</span>" : '' ?>
              <input type="text" name="theme" id="theme" value="<?php echo isset($old['theme']) ? h($old['theme']) : $edit_data['theme'];?>"/>
            </li>
            <li>
              <label for="content">コメント</label>
              <?php echo isset($flash_array['content']) ? "<span class='flash-msg'>{$flash_array['content']}</span>" : '' ?>
              <textarea type="text" name="content" id="content"><?php echo isset($old['content']) ? h($old['content']) : $edit_data['content']?>
              </textarea>
            </li>
            <li>
              <label for="deadline">目標完了日</label>
              <?php echo isset($flash_array['deadline']) ? "<span class='flash-msg'>{$flash_array['deadline']}</span>" : '' ?>
              <input type="date" name="deadline" id="deadline" value="<?php echo isset($old['deadline']) ? h($old['deadline']) : $edit_data['deadline']?>"/>
            </li>
            </li>
            <li>
              <button type="submit" id="regist-btn" class="btn">登録</button>
            </li>
          </ul>
          <input type="hidden" name="id" value="<?php echo h($edit_data['id']) ?>">
          <input type="hidden" name="mode" value="update">
          <input type="hidden" name="token" value="<?php echo h($token);?>">
        </form> 
      </section>
    </div>
</body>
</html>