<?php
require_once '../../vendor/autoload.php';
require_once '../config/config.php';
require_once '../Services/helper.php';

use App\Database\Login;
use App\Database\Logout;
use App\Database\Message;
use App\Services\GetForm;

session_start();
$in = GetForm::getForm();

switch ($in['mode']) {
  case 'login':
    Login::login($in, MANAGER);
    break;
  case 'logout':
    Logout::logout($in);
    break;
  case 'send_message':
    Message::sendMessage($in);
  // default:
  //   code...
  //   break;
}