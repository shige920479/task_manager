<?php
require_once '../../vendor/autoload.php';
require_once '../config/config.php';
require_once '../Services/helper.php';
use App\Database\DbConnect;
use App\Database\Logout;
use App\Database\Message;
use App\Services\GetRequest;
use Carbon\Carbon;
use function App\Services\flash;
use function App\Services\old;
use function App\Services\paginate;
use function App\Services\setToken;

session_start();

if(!isset($_SESSION['m_login'])) {
  header('Location: ./ManagerLogin.php');
  exit;
}

$request = GetRequest::getRequest();

// echo '<pre>';
// var_dump($request);
// echo '</pre>';


switch ($request['mode']) {

  case 'index':
    if(isset($request['sort_order'])) {
      $tasks = DbConnect::getTaskData($request, $request['sort_order']);
    } else {
      $tasks = DbConnect::getTaskData($request, null);
    }

    if($tasks) {
      $current_page = isset($request['page']) ? $request['page'] : null;
      $paginate_tasks = paginate($tasks, $current_page ,$request); 
      /**
       * 検索後のgetパラメータを渡す必要あり
       *  name/ category/ theme
      */
      $token = setToken();

      //検索フォーム用のデータ取得
      $all_data =DbConnect::getTaskData(null, null);
      $name_list = array_unique(array_column($all_data, 'name')); //常にmember全員を表示
      sort($name_list);
      $category_list = array_unique(array_column($tasks, 'category'));//選択したメンバーでリスト内容を変える
      sort($category_list);
    }

    include('../Views/ManagerIndex.php');
    break;
  
  case 'chat':
    // echo "<pre>";
    // echo var_dump($request);
    // echo "</pre>";

    $task = DbConnect::getTaskById($request['id']);
    $chats = DbConnect::getChatData($request['id'], MANAGER);
    $token = setToken();
    $flash_array = "";
    $old = "";
    if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);
    if(isset($_SESSION['old'])) $old = old($_SESSION['old']);

    include('../Views/ManagerChatView.php');
    break;

  case 'send_message':
    Message::sendMessage($request);
    
    include('../Views/ManagerChatView.php');
    break;
  
  case 'dashboard':
    // echo '<pre>';
    // var_dump($request);
    // echo '</pre>';
    // exit;
    
    $tasks = DbConnect::getTaskData(null, null);

    // echo '<pre>';
    // var_dump($tasks);
    // echo '</pre>';
    // exit;


    Carbon::setLocale('ja'); 
    $current_week = isset($request['week']) ? $request['week'] : Carbon::now()->format('Y-m-d');
    $start_date = Carbon::parse($current_week)->startOfWeek(Carbon::MONDAY);
    $end_date = $start_date->copy()->endOfWeek(Carbon::FRIDAY);
    $prev_week = $start_date->copy()->subWeek()->format('Y-m-d');
    $next_week = $start_date->copy()->addWeek()->format('Y-m-d');

    $categories = array_unique(array_column($tasks, 'category'));
    $token = setToken();
    include('../Views/ManagerDashbordView.php');
    break;

  case 'logout':
    
    Logout::logout($request);
    break;
  // default:
  //   code...
  //   break;
}