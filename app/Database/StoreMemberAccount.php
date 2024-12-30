<?php
namespace App\Database;

use function App\Services\flashMsg;
use function App\Services\old_store;
use function App\Services\writeLog;

/**
 * メンバー新規アカウント登録用クラス
 */
class StoreMemberAccount extends DbConnect
{
  /**
   * データベースへのアカント登録
   * @param array $request 入力データ
   * @return void 登録後はログイン画面へ遷移
   */
  public static function memberRegister(array $request): void
   {
    self::Validation($request);
    if(isset($_SESSION['error'])) {
      header('Location: /task_manager/account/');
      exit;
    } else {
      $hash_password = password_hash($request['password'], PASSWORD_BCRYPT);
      try {
        $pdo = DbConnect::db_connect();
        $sql = "INSERT INTO member
                (name, email, password)
                values
                (:name, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $request['name'], \PDO::PARAM_STR);
        $stmt->bindValue(':email', $request['email'], \PDO::PARAM_STR);
        $stmt->bindValue(':password', $hash_password, \PDO::PARAM_STR);
        $stmt->execute();
        header('Location: /task_manager/');

      } catch(\PDOException $e) {
        if($e->errorInfo[1] === 1062) {
          flashMsg('email', 'このメールアドレスは登録済みです');
          header('Location: /task_manager/account/');
          exit;
        } else {
          flashMsg('db', "内部サーバーエラーです。\n検索中のリソースに問題があるため、リソースを表示できません");
          writeLog(LOG_FILEPATH, $e->getMessage());
          header('Location: /task_manager/error/?error_mode=500error');
          exit;
        }

      } finally {
        list($pdo, $stmt) = [null, null];
      }
    }
  }
  /**
   * 新規アカンウト登録専用のバリデーション
   * @param array $request 入力データ
   * @return bool
   */
  private static function Validation($request) {
    if($request['name'] === "") {
      flashMsg('name', '名前を入力してください');
    } elseif(mb_strlen($request['name']) > 50) {
      flashMsg('name', '名前は50文字以下で入力してください');      
    } else {
      old_store('name', $request['name']);
    }
    if($request['email'] === "") {
      flashMsg('email', 'メールアドレスを入力してください');      
    } elseif(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $request['email'])) {
      flashMsg('email', 'メールアドレスが正しくありません');
    } else {
      old_store('email', $request['email']);
    }
    if($request['password'] === "") {
      flashMsg('password', 'パスワードを入力してください');
    } elseif($request['password'] !== $request['confirm-password']) {
      flashMsg('password', 'パスワードが一致しません、再入力ください');
    } elseif(!preg_match("/\A(?=.*?[A-z])(?=.*?\d)[A-z\d]{6,20}+\z/", $request['password'])) {
      flashMsg('password', '半角英数字混在で6桁以上20桁以下で登録願います');
    }
  }
}



