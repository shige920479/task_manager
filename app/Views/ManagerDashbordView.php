<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ダッシュボード</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="../../css/calender_style.css" />
  </head>
  <body>
    <div class="wrapper">
      <div style="text-align:right"><a href="?mode=index">一覧画面へ</a></div> <!-- 要）CSS修正 -->
      <div id="month">
        <a href='<?php echo "?mode=dashboard&week={$prev_week}";?>'>前週</a>
        <p><?php echo $start_date->format('m/d'); ?> ～ <?php echo $end_date->format('m/d'); ?></p>
        <a href='<?php echo "?mode=dashboard&week={$next_week}";?>'>来週</a>
      </div>
      <table id="taskboard">
        <thead>
          <tr>
            <th>カテゴリー</th>
            <?php for($date = $start_date->copy(); $date <= $end_date; $date->addDay()): ?>
            <!-- <th><?php echo $date->format('m/d') . '(' . $date->format('D') . ')'; ?></th> -->
            <th><?php echo $date->isoFormat('MM/DD（ddd）'); ?></th>
            <?php endfor; ?>
          </tr>
        <tbody>
          <?php foreach($categories as $category): ?>
            <tr>
              <td><div class="category"><?php echo $category ?></div></td>
              <?php for($date = $start_date->copy(); $date <= $end_date; $date->addDay()): ?>
                <td>
                  <?php foreach($tasks as $task) :?>
                    <?php if($task['category'] === $category && $task['deadline'] === $date->format('Y-m-d')) : ?>
                      <div class="box <?php echo 'mem-color' . $task['member_id'] ?>">
                        <p><?php echo $task['name'] ?></p>
                        <div class="theme-flex">
                          <span class="theme"><?php echo $task['theme'] ?></span>
                          <span class="star"><?php echo str_repeat('★', intval($task['priority'])) ?></span>
                        </div>
                        <p><?php echo $task['content'] ?></p>
                      </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </td>
              <?php endfor;?>
            </tr>
          <?php endforeach;?>
        </tbody>
        </thead>
      </table>
    </div>
  </body>
</html>
