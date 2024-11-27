<?php
namespace App\Database;
require_once '../Services/GetForm.php';
require_once '../config/config.php';

class Logout
{
  public static function logout(array $in): void
  {
    $_SESSION = array();
    session_destroy();
    if($in['login_user'] === MEMBER) {
      header('Location: ../Views/index.php');
    } elseif($in['login_user'] === MANAGER) {
      header('Location: ../Views/ManagerLoginView.php');
    }
    exit;
  }
}