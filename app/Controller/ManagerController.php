<?php
require_once '../../vendor/autoload.php';
require_once '../config/config.php';
require_once '../Services/helper.php';
use App\Database\DbConnect;
use App\Database\Logout;
use App\Database\Message;
use App\Services\GetForm;
use Carbon\Carbon;
use function App\Services\flash;
use function App\Services\old;
use function App\Services\paginate;

session_start();

if(!isset($_SESSION['m_login'])) {
  header('Location: ./ManagerLogin.php');
  exit;
}

$in = GetForm::getForm();

switch ($in['mode']) {

  case 'index':
    $tasks = DbConnect::getTaskData($in);
    var_dump($in);

    if($tasks) {
      $current_page = isset($in['page']) ? $in['page'] : null;
      $paginate_tasks = paginate($tasks, $current_page ,$in); 
      /**
       * 検索後のgetパラメータを渡す必要あり
       *  name/ category/ theme
      */

      $all_data =DbConnect::getTaskData(null);
      // echo '<pre>';
      // var_dump($all_data);
      // echo '</pre>';
      // exit;

      
      $name_list = array_unique(array_column($all_data, 'name')); //常にmember全員を表示
      sort($name_list);
      $category_list = array_unique(array_column($tasks, 'category'));//選択したメンバーでリスト内容を変える
      sort($category_list);
    }

    include('../Views/ManagerIndex.php');
    break;
  
  case 'chat':
    $task = DbConnect::selectId($in['id']);
    $chats = DbConnect::getMessage($in['id'], MANAGER);
    $flash_array = "";
    $old = "";
    if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);
    if(isset($_SESSION['old'])) $old = old($_SESSION['old']);

    include('../Views/ManagerChatView.php');
    break;

  case 'send_message':
    Message::sendMessage($in);
    
    include('../Views/ManagerChatView.php');
    break;
  
  case 'dashboard':
    $tasks = DbConnect::getTaskData($in);

    Carbon::setLocale('ja'); 
    $current_week = isset($in['week']) ? $in['week'] : Carbon::now()->format('Y-m-d');
    $start_date = Carbon::parse($current_week)->startOfWeek(Carbon::MONDAY);
    $end_date = $start_date->copy()->endOfWeek(Carbon::FRIDAY);
    $prev_week = $start_date->copy()->subWeek()->format('Y-m-d');
    $next_week = $start_date->copy()->addWeek()->format('Y-m-d');

    $categories = array_unique(array_column($tasks, 'category'));

    include('../Views/ManagerDashbordView.php');
    break;

  case 'logout':
    Logout::logout($in);
    break;
  // default:
  //   code...
  //   break;
}