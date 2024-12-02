<?php
require_once '../../vendor/autoload.php';
require_once '../Services/helper.php';
require_once '../config/config.php';

use App\Services\GetForm;
use App\Database\StoreMemberAccount;
use function App\Services\flash;
use function App\Services\old;

session_start();
$in = GetForm::getForm();

if(!empty($in) && $in['mode'] === 'store_account') {
  StoreMemberAccount::memberRegister($in);
  exit;
} else {
  $flash_array = "";
  $old = "";
  if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);
  if(isset($_SESSION['old'])) $old = old($_SESSION['old']);  

  include('../Views/MemberAccountView.php');
  exit;
}