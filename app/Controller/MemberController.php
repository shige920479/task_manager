<?php
require_once './app/config/config.php';
require_once './app/Services/helper.php';

use App\Database\Authenticate;
use App\Database\DbConnect;
use App\Database\DeleteTask;
use App\Database\Logout;
use App\Database\Message;
use App\Database\StoreTask;
use App\Database\UpdateTask;
use App\Services\GetRequest;
use Carbon\Carbon;
use function App\Services\flash;
use function App\Services\flashMsg;
use function App\Services\old;
use function App\Services\paginate;
use function App\Services\setToken;


session_start();

if(!isset($_SESSION['login'])) {
  header('Location:' . PATH);
  exit;
}

$request = GetRequest::getRequest();

switch ($request['mode']) {

  case 'index':

    $tasks = DbConnect::getMemberData($_SESSION['login_id'], $request);

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

    include('./app/Views/MemberIndex.php');
    break;
  
  case 'logout':
    Logout::logout($request);
    break;

  case 'edit':

    $task_member = Authenticate::taskBelongsToMember($request['id']);
    if($task_member !== $_SESSION['login_id']) {
      flashMsg('tokenerror', "不正なリクエストです、再度ログインをお試しください"); 
      header('Location:' . PATH . 'error/?error_mode=400error');
      exit;
    } 
    
    $edit_data = DbConnect::getTaskById($request['id']);
    $categories = DbConnect::getCategory($edit_data['member_id']); 

    $token = setToken();
    $flash_array = "";
    $old = "";
    if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);   
    if(isset($_SESSION['old'])) $old = old($_SESSION['old']);
    include('./app/Views/MemberEditView.php');
    break;

  case 'store':
    StoreTask::storeTask($request);
    break;

  case 'update':
    UpdateTask::updateTask($request);
    break;

  case 'chat':
    $task_member = Authenticate::taskBelongsToMember($request['id']);
    if($task_member !== $_SESSION['login_id']) {
      flashMsg('tokenerror', "不正なリクエストです、再度ログインをお試しください"); 
      header('Location:' . PATH . 'error/?error_mode=400error');
      exit;
    } 

    $task = DbConnect::getTaskById($request['id']);
    $chats = DbConnect::getChatData($request['id'], MEMBER);
    $token = setToken();
    $flash_array = "";
    $old = "";
    if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);
    if(isset($_SESSION['old'])) $old = old($_SESSION['old']);

    include('./app/Views/MemberChatView.php');
    break;

  case 'send_message':
    Message::sendMessage($request);
    break;

  case 'callender':

    if((int)$request['member_id'] !== $_SESSION['login_id']) {
      flashMsg('tokenerror', "不正なリクエストです、再度ログインをお試しください"); 
      header('Location:' . PATH . 'error/?error_mode=400error');
      exit;
    }

    Carbon::setLocale('ja'); 
    $current_week = isset($request['week']) ? $request['week'] : Carbon::now()->format('Y-m-d');
    $start_date = Carbon::parse($current_week)->startOfWeek(Carbon::MONDAY);
    $end_date = $start_date->copy()->endOfWeek(Carbon::FRIDAY);
    $prev_week = $start_date->copy()->subWeek()->format('Y-m-d');
    $next_week = $start_date->copy()->addWeek()->format('Y-m-d');

    $tasks = DbConnect::getMemberData($request['member_id'], null);
    $categories = array_unique(array_column($tasks, 'category'));
    $token = setToken();

    include('./app/Views/MemberCallenderView.php');
    break;

  case 'soft_del':
    DeleteTask::softDelete($request['id']);
    break;

}