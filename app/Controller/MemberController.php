<?php
require_once '../../vendor/autoload.php';
require_once '../config/config.php';
require_once '../Services/helper.php';

use App\Database\DbConnect;
use App\Database\DeleteTask;
use App\Database\Login;
use App\Database\Logout;
use App\Database\Message;
use App\Database\StoreMemberAccount;
use App\Database\StoreTask;
use App\Database\UpdateTask;
use App\Services\GetForm;
use Carbon\Carbon;

use function App\Services\flash;
use function App\Services\h;
use function App\Services\old;
use function App\Services\paginate;
use function App\Services\setRecieveIcon;
use function App\Services\setSendIcon;
use function App\Services\setToken;


session_start();
// echo '<pre>';
// var_dump($_SESSION);
// echo '</pre>';
if(!isset($_SESSION['login'])) {
  header('Location: ./MemberLogin.php');
  exit;
}

$in = GetForm::getForm();
// echo '<pre>';
// var_dump($in);
// echo '</pre>';

switch ($in['mode']) {
  case 'index':
      $tasks = DbConnect::getMemberData($_SESSION['login_id']);
      if($tasks) {
        $current_page = isset($in['page']) ? $in['page'] : null;
        $paginate_tasks = paginate($tasks, $current_page, $in);
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
    Logout::logout($in);
    break;
  
  /** ルーティングは完了
   * エラーメッセージの配置がおかしい
   * エラーで戻ってきた後の入力欄に違和感あり（元のデータが入っている）。
   */
  case 'edit':
    $data = DbConnect::selectId($in['id']);
    $categories = DbConnect::getCategory($data['member_id']); 
    $token = setToken();
    $flash_array = "";
    $old = "";
    if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);   
    if(isset($_SESSION['old'])) $old = old($_SESSION['old']);
    include('../Views/MemberEditView.php');
    break;

  case 'store':
    StoreTask::storeTask($in);
    break;

  case 'update':  //完了
    UpdateTask::updateTask($in);
    break;

  case 'chat':
    $task = DbConnect::selectId($in['id']);
    $chats = DbConnect::getMessage($in['id'], MEMBER);
    $token = setToken();
    $flash_array = "";
    $old = "";
    if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);
    if(isset($_SESSION['old'])) $old = old($_SESSION['old']);

    include('../Views/MemberChatView.php');
    break;

  case 'send_message':
    Message::sendMessage($in);
    break;

  case 'dashboard':
    Carbon::setLocale('ja'); 
    $current_week = isset($in['week']) ? $in['week'] : Carbon::now()->format('Y-m-d');

    $start_date = Carbon::parse($current_week)->startOfWeek(Carbon::MONDAY);
    $end_date = $start_date->copy()->endOfWeek(Carbon::FRIDAY);

    $prev_week = $start_date->copy()->subWeek()->format('Y-m-d');
    $next_week = $start_date->copy()->addWeek()->format('Y-m-d');

    $tasks = DbConnect::getMemberData($in['member_id']);
    $categories = array_unique(array_column($tasks, 'category'));
    $token = setToken();

    include('../Views/MemberDashbordView.php');
    break;

  case 'soft_del':
    DeleteTask::softDelete($in['id']);
    break;

}