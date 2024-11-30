<?php
namespace App\Database;

require_once __DIR__ . '/../Services/helper.php';

use PDOException;
use function App\Services\flashMsg;
use function App\Services\old_store;

class Login extends DbConnect
{  
  static $login_user;
  /**
   * param array $in postデータ
   * param string $table テーブル名 
   */
  public static function login(array $in, string $table): bool
  {
    if(self::blankCheck($in)) {

      $login_user = self::getUserByEmail($in['email'], $table);

      if(!$login_user) {
        flashMsg('email', 'メールアドレスが登録されておりません、再度入力願います');
        if($table === MEMBER) {header('Location: ./MemberLogin.php');}
        if($table === MANAGER) {header('Location: ../Views/ManagerLoginView.php');}
        exit;
      } elseif(!password_verify($in['password'], $login_user['password'])) {
        flashMsg('password', 'パスワードが異なっております、再度入力願います');
        if($table === MEMBER) {header('Location: ./MemberLogin.php');}
        if($table === MANAGER) {header('Location: ../Views/ManagerLoginView.php');}
        exit;
      } else {
        session_regenerate_id(TRUE); // セッションidを再発行
        if(isset($_SESSION['old'])) unset($_SESSION['old']);  // エラー時に使う入力情報を消去<-ここで使うべきか見直し
        if($table === MEMBER) { 
          $_SESSION['login'] = $login_user['email']; // セッションにログイン情報を登録
          $_SESSION['login_id'] = $login_user['id'];
          $_SESSION['login_name']= $login_user['name'];
          header('Location: ./MemberController.php?mode=index');
          return true;
        } 
        if($table === MANAGER) {
          $_SESSION['m_login'] = $in['email'];
          $_SESSION['m_login_name']= $login_user['name'];
          // header('Location: ../Views/ManagerIndex.php');
          return true;
        }
        exit;
      }
    } else {
      if($table === MEMBER) {header('Location: ./MemberLogin.php');}
      if($table === MANAGER)  {header('Location: ../Views/ManagerLoginView.php');}
      exit;
    }
  }

  private static function blankCheck(array $in):bool
  {
    $result = false;
    if($in['email'] === '') {
      flashMsg('email', 'メールアドレスを入力してください');
    } else {
      old_store('email', $in['email']);
    }
    if($in['password'] === '') {
      flashMsg('password', 'パスワードを入力してください');
    }
    $result = !isset($_SESSION['error']) ?? true;
    return $result;
  }
}
