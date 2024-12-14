<?php
namespace App\Database;
use function App\Services\flashMsg;

/**
 * データ削除クラス
 */
class DeleteTask extends DbConnect
{
  /**
   * メンバー側のタスク完了処理（ソフトデリート）
   * 
   * @param $id タスクid
   * @return void
   */
  public static function softDelete(int $id): void
   {
    try {        
        // 削除後のメッセージ表示用の情報取得
        $pdo = self::db_connect();
        $sql = "SELECT theme FROM task WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $del_data = $stmt->fetch();

        // 削除処理
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

      } finally {
        list($pdo, $stmt) = [null, null];
    }
  }
}