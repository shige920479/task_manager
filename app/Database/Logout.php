<?php
namespace App\Database;
require_once '../Services/GetRequest.php';
require_once '../config/config.php';

class Logout
{
  /**
   * メンバー・マネージャー共通のログアウト処理
   * @param array $request member|manager
   * @return void
   */
  public static function logout(array $request): void
  {
    $_SESSION = array();
    setcookie(session_name(), '', time()-1, '/');
    session_destroy();
    if($request['login_user'] === MEMBER) {
      header('Location: ./MemberLogin.php');
    } elseif($request['login_user'] === MANAGER) {
      header('Location: ./ManagerLogin.php');
    }
    exit;
  }
}