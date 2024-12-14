<?php include '../Views/ManagerHeader.php';?>
    <div class="calender-wrapper">
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
                      <a href='<?php echo "?mode=chat&id={$task['id']}"?>'>
                        <div class="box <?php echo 'mem-color' . $task['member_id'] ?>">
                          <p><?php echo $task['name'] ?></p>
                          <div class="theme-flex">
                            <span class="theme"><?php echo $task['theme'] ?></span>
                            <span class="star"><?php echo str_repeat('★', intval($task['priority'])) ?></span>
                          </div>
                          <p><?php echo $task['content'] ?></p>
                        </div>
                      </a>
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
