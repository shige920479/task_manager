<?php
namespace App\Database;

use PDOException;

require_once '../Services/helper.php';
use function App\Services\flashMsg;
use function App\Services\old_store;

/**
 * 10/30:DB登録まで（すでにメールアドレス登録済の処理も◎）
 * 次回：ログイン機能、adminログイン機能、csrf対策、h()対策、その他、youtubeを見返して必要なセキュリティ対策を講じる。
 * さらに、バリデーション＋エラーメッセージなどのクラス化を検討。できそうな気がするが。
 *  ～余裕があれば、パスワード忘れの対応も。2段階認証的な要素もいれる（メール->ワンタイムパスを飛ばしてみたいな）
 */

class StoreMemberAccount extends DbConnect
{
  public static function memberRegister($in) {
    self::Validation($in);
    if(isset($_SESSION['error'])) {
      header('Location: ../Views/MemberAccountView.php');
      exit;
    } else {
      $hash_password = password_hash($in['password'], PASSWORD_BCRYPT);
      try {
        $pdo = DbConnect::db_connect();
        $sql = "INSERT INTO member
                (name, email, password)
                values
                (:name, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $in['name'], \PDO::PARAM_STR);
        $stmt->bindValue(':email', $in['email'], \PDO::PARAM_STR);
        $stmt->bindValue(':password', $hash_password, \PDO::PARAM_STR);
        $stmt->execute();
        header('Location: ../Views/MemberLoginView.php');
      } catch(PDOException $e) {
        if($e->errorInfo[1] === 1062) {
          flashMsg('email', 'このメールアドレスは登録済みです');
          header('Location: ../Views/MemberAccountView.php');
          exit;
        } else {
          flashMsg('db', "登録に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
          header('Location: ../Views/500error.php');
          exit;
        }
      }
    }
  }

  // private へ変更できそう
  public static function Validation($in) {
    if($in['name'] === "") {
      flashMsg('name', '名前を入力してください');
    } elseif(mb_strlen($in['name']) > 50) {
      flashMsg('name', '名前は50文字以下で入力してください');      
    } else {
      old_store('name', $in['name']);
    }
    if($in['email'] === "") {
      flashMsg('email', 'メールアドレスを入力してください');      
    } elseif(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $in['email'])) {
      flashMsg('email', 'メールアドレスが正しくありません');
    } else {
      old_store('email', $in['email']);
    }
    if($in['password'] === "") {
      flashMsg('password', 'パスワードを入力してください');
    } elseif($in['password'] !== $in['confirm-password']) {
      flashMsg('password', 'パスワードが一致しません、再入力ください');
    } elseif(!preg_match("/\A(?=.*?[A-z])(?=.*?\d)[A-z\d]{6,20}+\z/", $in['password'])) {
      flashMsg('password', '半角英数字混在で6桁以上20桁以下で登録願います');
    }
  }
  // public static function Validation($in) {
  //   if($in['name'] === "") {
  //     flashMsg('name', '名前を入力してください');
  //   } elseif(mb_strlen($in['name']) > 50) {
  //     flashMsg('name', '名前は50文字以下で入力してください');      
  //   }
  //   if($in['email'] === "") {
  //     flashMsg('email', 'メールアドレスを入力してください');      
  //   } elseif(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $in['email'])) {
  //     flashMsg('email', 'メールアドレスが正しくありません');
  //   }
  //   if($in['password'] === "") {
  //     flashMsg('password', 'パスワードを入力してください');
  //   } elseif($in['password'] !== $in['confirm-password']) {
  //     flashMsg('password', 'パスワードが一致しません、再入力ください');
  //   } elseif(!preg_match("/\A(?=.*?[A-z])(?=.*?\d)[A-z\d]{6,20}+\z/", $in['password'])) {
  //     flashMsg('password', '半角英数字混在で6桁以上20桁以下で登録願います');
  //   }
  // }
}



