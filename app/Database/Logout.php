<?php
namespace App\Database;
require_once '../Services/GetForm.php';
require_once '../config/config.php';

class Logout
{
  /**
   * メンバー・マネージャー共通のログアウト処理
   * @param array $in member|manager
   * @return void
   */
  public static function logout(array $in): void
  {
    $_SESSION = array();
    setcookie(session_name(), '', time()-1, '/');
    session_destroy();
    if($in['login_user'] === MEMBER) {
      header('Location: ./MemberLogin.php');
    } elseif($in['login_user'] === MANAGER) {
      header('Location: ./ManagerLogin.php');
    }
    exit;
  }
}