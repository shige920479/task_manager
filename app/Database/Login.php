<?php
namespace App\Database;

require_once __DIR__ . '/../Services/helper.php';

use PDOException;
use function App\Services\flashMsg;
use function App\Services\old_store;

class Login extends DbConnect
{  
  private static $user_data;
  
  /**
   * メンバー・マネージャー共通のログイン認証
   * @param array $request 入力データ（メールアドレス・パスワード）
   * @param string $table member|manager テーブル 
   */
  public static function login(array $request, string $table): bool
  {
    if(self::blankCheck($request)) {

      self::$user_data = self::getUserByEmail($request['email'], $table);

      if(!self::$user_data) {
        flashMsg('email', 'メールアドレスが登録されておりません、再度入力願います');
        if($table === MEMBER) {header('Location: ./MemberLogin.php');}
        if($table === MANAGER) {header('Location: ./ManagerLogin.php');}
        exit;
      } elseif(!password_verify($request['password'], self::$user_data['password'])) {
        flashMsg('password', 'パスワードが異なっております、再度入力願います');
        if($table === MEMBER) {header('Location: ./MemberLogin.php');}
        if($table === MANAGER) {header('Location: ./ManagerLogin.php');}
        exit;
      } else {
        session_regenerate_id(TRUE); // セッションidを再発行
        if(isset($_SESSION['old'])) unset($_SESSION['old']);  // エラー時に使う入力情報を消去<-ここで使うべきか見直し
        if($table === MEMBER) { 
          $_SESSION['login'] = self::$user_data['email']; // セッションにログイン情報を登録
          $_SESSION['login_id'] = self::$user_data['id'];
          $_SESSION['login_name']= self::$user_data['name'];
          header('Location: ./MemberController.php?mode=index');
          return true;
        } 
        if($table === MANAGER) {
          $_SESSION['m_login'] = $request['email'];
          $_SESSION['m_login_name']= self::$user_data['name'];
          header('Location: ./ManagerController.php?mode=index');
          return true;
        }
        exit;
      }
    } else {
      if($table === MEMBER) {header('Location: ./MemberLogin.php');}
      if($table === MANAGER)  {header('Location: ./ManagerLogin.php');}
      exit;
    }
  }

  private static function blankCheck(array $request):bool
  {
    $result = false;
    if($request['email'] === '') {
      flashMsg('email', 'メールアドレスを入力してください');
    } else {
      old_store('email', $request['email']);
    }
    if($request['password'] === '') {
      flashMsg('password', 'パスワードを入力してください');
    }
    $result = !isset($_SESSION['error']) ?? true;
    return $result;
  }
}
