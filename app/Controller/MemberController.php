<?php
require_once '../../vendor/autoload.php';
require_once '../config/config.php';

use App\Database\DeleteTask;
use App\Database\Login;
use App\Database\Logout;
use App\Database\Message;
use App\Database\StoreMemberAccount;
use App\Database\StoreTask;
use App\Database\UpdateTask;
use App\Services\GetForm;

session_start();
$in = GetForm::getForm();

switch ($in['mode']) {
  case 'login':
    Login::Login($in, MEMBER);
    break;
  case 'logout':
    Logout::logout($in);
    break;
  case 'store_account':
    StoreMemberAccount::memberRegister($in);
    break;
  case 'store':
    StoreTask::storeTask($in);
    break;
  case 'update':
    UpdateTask::updateTask($in);
    break;
  case 'send_message':
    Message::sendMessage($in);
    break;
  case 'soft_del':
    
    // echo '<pre>';
    // var_dump($in);
    // echo '</pre>';
    // exit;
    DeleteTask::softDelete($in['id']);
    break;

  // 11/21は　228が消せない対策から
  // バリデーションもところどころないので追加
  // default:
    // code...
    // break;  
}