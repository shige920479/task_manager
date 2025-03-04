<?php
namespace App\Database;

/**
 * ログアウト処理
 */

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
      header('Location:' . PATH);
    } elseif($request['login_user'] === MANAGER) {
      header('Location:' . PATH . 'managerLogin/');
    }
    exit;
  }
}