<?php
require_once '../../vendor/autoload.php';
require_once '../Services/helper.php';

use App\Services\GetForm;
use App\Database\Login;
use function App\Services\flash;
use function App\Services\old;

session_start();
$in = GetForm::getForm();

if(!empty($in)) {
  Login::Login($in, MEMBER);
} else {
  $flash_array = "";
  $old = "";
  if(isset($_SESSION['error'])) $flash_array = flash($_SESSION['error']);
  if(isset($_SESSION['old'])) $old = old($_SESSION['old']);  

  include('../Views/MemberLoginView.php');
  exit;
}