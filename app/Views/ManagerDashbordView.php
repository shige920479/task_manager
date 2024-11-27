<?php
require_once '../../vendor/autoload.php';
// date_default_timezone_set('Asia/Tokyo');

use App\Database\DbConnect;
use App\Services\GetForm;
use Carbon\Carbon;

session_start();
if(!isset($_SESSION['m_login'])) {
  header('Location: ./ManagerLoginView.php');
  exit;
}

$in = GetForm::getForm();
$tasks = DbConnect::getTaskData($in);
// echo "<pre>";
// var_dump($tasks);
// echo "</pre>";
// exit;


Carbon::setLocale('ja'); 
$current_week = isset($in['week']) ? $in['week'] : Carbon::now()->format('Y-m-d');

$start_date = Carbon::parse($current_week)->startOfWeek(Carbon::MONDAY);
$end_date = $start_date->copy()->endOfWeek(Carbon::FRIDAY);

$prev_week = $start_date->copy()->subWeek()->format('Y-m-d');
$next_week = $start_date->copy()->addWeek()->format('Y-m-d');

$categories = array_unique(array_column($tasks, 'category'));

// echo "<pre>";
// var_dump(array_unique(array_column($tasks, 'member_id')));
// echo "</pre>";
// exit;

// echo $current_week;
// echo $prev_week;
// echo $next_week;

// echo "<pre>";
// var_dump($current_week);
// echo "</pre>";
// exit;


?>

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
      <div style="text-align:right"><a href="./ManagerIndex.php">一覧画面へ</a></div> <!-- 要）CSS修正 -->
      <div id="month">
        <a href='<?php echo "?week={$prev_week}";?>'>前週</a>
        <p><?php echo $start_date->format('m/d'); ?> ～ <?php echo $end_date->format('m/d'); ?></p>
        <a href='<?php echo "?week={$next_week}";?>'>来週</a>
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
