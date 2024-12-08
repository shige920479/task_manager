<?php
namespace App\Database;

//フラッシュメッセージ用、完成後にメッセージも合わせて削除。
require_once '../Services/helper.php'; 
use function App\Services\flashMsg;
/////////////////////////////////////////////////////

/**
 * 宿題）$_SESSION[del_msg]を消してない為、エラー画面から戻ると
 * フラッシュメッセージが表示される。
 */

/**
 * データ削除クラス（完全削除とソフトデリート）
 * param $id
 * return void
 */

class DeleteTask extends DbConnect
{
  // 完了処理
  public static function softDelete($id) {
    try {
        $del_data = self::selectId($id);
        
        // 削除用の処理
        $pdo = self::db_connect();
        $sql = "UPDATE task SET del_flag = 1 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $_SESSION['del_msg'] = "タイトル : 「{$del_data['theme']}」のタスクを完了しました";
        header('Location: ?mode=index');
      
    } catch(\PDOException $e) {
      flashMsg('db', "登録に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
      header('Location: ../Views/500error.php');
      exit;
    }
  }

  public static function hardDelete($id) {
    try {
      // message用処理
      $del_data = self::selectId($id);
      $_SESSION['del_msg'] = "カテゴリー : 「{$del_data['category']}」/ タイトル : 「{$del_data['title']}」 を削除しました";
      
      // 削除用の処理
      $pdo = self::db_connect(); // tryに2回はいってしまう... DbConnectクラスの方を修正するか
      $sql = 'DELETE FROM task WHERE id = :id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
      $stmt->execute();
      header('Location: ../Views/index.php');
    } catch(\PDOException $e) {
      flashMsg('db', "登録に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
      header('Location: ../Views/500error.php');
      exit;
    }
  }

}