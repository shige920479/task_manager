<?php
require_once '../../vendor/autoload.php';
require_once '../config/config.php';
require_once '../Services/helper.php';

use App\Database\DbConnect;
use App\Database\DeleteTask;
use App\Database\Logout;
use App\Database\Message;
use App\Database\StoreTask;
use App\Database\UpdateTask;
use App\Services\GetRequest;
use Carbon\Carbon;
use function App\Services\flash;
use function App\Services\old;
use function App\Services\paginate;
use function App\Services\setToken;


session_start();

if(!isset($_SESSION['login'])) {
  header('Location: ./MemberLogin.php');
  exit;
}

$request = GetRequest::getRequest();

switch ($request['mode']) {

  case 'index': // 最後にこの条件分岐を変えられるか検討
      if(isset($request['sort_order'])) {
        $tasks = DbConnect::getMemberData($_SESSION['login_id'], $request['sort_order']);
      } else {
        $tasks = DbConnect::getMemberData($_SESSION['login_id'], null);
      }

      if($tasks) {
        $current_page = isset($request['page']) ? $request['page'] : null;
        $paginate_tasks = paginate($tasks, $current_page, $request);
        $categories = DbConnect::getCategory($_SESSION['login_id']);
      }
      $token = setToken();
      $flash_array = "";
      $old = "";
      if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);
      if(isset($_SESSION['old'])) $old = old($_SESSION['old']);

      include('../Views/index.php');
      break;
  
  case 'logout':
    Logout::logout($request);
    break;

  case 'edit':
    $edit_data = DbConnect::getTaskById($request['id']);
    $categories = DbConnect::getCategory($edit_data['member_id']); 

    $token = setToken();
    $flash_array = "";
    $old = "";
    if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);   
    if(isset($_SESSION['old'])) $old = old($_SESSION['old']);
    include('../Views/MemberEditView.php');
    break;

  case 'store':
    StoreTask::storeTask($request);
    break;

  case 'update':
    UpdateTask::updateTask($request);
    break;

  case 'chat':
    $task = DbConnect::getTaskById($request['id']);
    $chats = DbConnect::getChatData($request['id'], MEMBER);
    $token = setToken();
    $flash_array = "";
    $old = "";
    if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);
    if(isset($_SESSION['old'])) $old = old($_SESSION['old']);

    include('../Views/MemberChatView.php');
    break;

  case 'send_message':
    Message::sendMessage($request);
    break;

  case 'dashboard':
    Carbon::setLocale('ja'); 
    $current_week = isset($request['week']) ? $request['week'] : Carbon::now()->format('Y-m-d');
    $start_date = Carbon::parse($current_week)->startOfWeek(Carbon::MONDAY);
    $end_date = $start_date->copy()->endOfWeek(Carbon::FRIDAY);
    $prev_week = $start_date->copy()->subWeek()->format('Y-m-d');
    $next_week = $start_date->copy()->addWeek()->format('Y-m-d');

    $tasks = DbConnect::getMemberData($request['member_id'], null);
    $categories = array_unique(array_column($tasks, 'category'));
    $token = setToken();

    include('../Views/MemberDashbordView.php');
    break;

  case 'soft_del':
    DeleteTask::softDelete($request['id']);
    break;

}