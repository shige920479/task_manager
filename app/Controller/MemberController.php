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


$in = GetForm::getForm();
// echo '<pre>';
// var_dump($in);
// echo '</pre>';


switch ($in['mode']) {
  case 'login': // 後でログイン処理含めて検討
    if(Login::Login($in, MEMBER)) {
      if(!isset($_SESSION['login'])) {
        header('Location: ../Views/MemberLoginView.php');
        exit;
      }
      $tasks = DbConnect::getMemberData($_SESSION['login_id']);
      $current_page = isset($in['page']) ? $in['page'] : null;
      $paginate_tasks = paginate($tasks, $current_page); 
      $categories = DbConnect::getCategory($_SESSION['login_id']);
      $token = setToken();
      $flash_array = "";
      $old = "";
      if(isset($_SESSION['error'])) {
        $flash_array = flash($_SESSION['error']);
        unset($_SESSION['error']);
      }
      if(isset($_SESSION['old'])) {
        $old = old($_SESSION['old']);
        unset($_SESSION['old']);
      }
      include('../Views/index.php');
    }
    break;
  case 'index':
      if(!isset($_SESSION['login'])) {
        header('Location: ../Views/MemberLoginView.php');
        exit;
      }
      $tasks = DbConnect::getMemberData($_SESSION['login_id']);
      $current_page = isset($in['page']) ? $in['page'] : null;
      $paginate_tasks = paginate($tasks, $current_page, $base_url=$_SERVER['PHP_SELF']);
      $categories = DbConnect::getCategory($_SESSION['login_id']);
      $token = setToken();
      $flash_array = "";
      $old = "";
      if(isset($_SESSION['error'])) {
        $flash_array = flash($_SESSION['error']);
        unset($_SESSION['error']);
      }
      if(isset($_SESSION['old'])) {
        $old = old($_SESSION['old']);
        unset($_SESSION['old']);
      }
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
    if(!isset($_SESSION['login'])) {
      header('Location: ./MemberLoginView.php');
      exit;
    }
    $data = DbConnect::selectId($in['id']);
    $categories = DbConnect::getCategory($data['member_id']); 
    $flash_array = "";
    $old = "";
    if(isset($_SESSION['error'])) {
      $flash_array = flash($_SESSION['error']);
      unset($_SESSION['error']);
    }
    if(isset($_SESSION['old'])) {
      $old = old($_SESSION['old']);
      unset($_SESSION['old']);
    }
    include('../Views/MemberEditView.php');
    break;

  case 'store_account':
    StoreMemberAccount::memberRegister($in);
    break;
  case 'store':
    StoreTask::storeTask($in);
    break;
  case 'update':  //完了
    UpdateTask::updateTask($in);
    break;

  case 'chat':
    if(!isset($_SESSION['login'])) {
      header('Location: ./MemberLoginView.php');
      exit;
    }    
    $task = DbConnect::selectId($in['id']);
    $chats = DbConnect::getMessage($in['id'], MEMBER);
    $flash_array = "";
    $old = "";
    if(isset($_SESSION['error'])) {
      $flash_array = flash($_SESSION['error']);
      unset($_SESSION['error']);
    }
    if(isset($_SESSION['old'])) {
      $old = old($_SESSION['old']);
      unset($_SESSION['old']);
    }
    include('../Views/MemberChatView.php');
    break;

  case 'send_message':
    Message::sendMessage($in);
    break;

  case 'soft_del':
    DeleteTask::softDelete($in['id']);
    break;

  // 11/21は　228が消せない対策から
  // バリデーションもところどころないので追加
  // default:
    // code...
    // break;  
}