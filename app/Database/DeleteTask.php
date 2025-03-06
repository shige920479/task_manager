<?php
namespace App\Database;

use PDOException;

use function App\Services\flashMsg;
use function App\Services\writeLog;

/**
 * タスクデータ削除
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
        flashMsg('db', "内部サーバーエラーです。\n検索中のリソースに問題があるため、リソースを表示できません");
        writeLog(LOG_FILEPATH, $e->getMessage());
        header('Location:' . PATH . 'error?error_mode=500error');
        exit;

      } finally {
        list($pdo, $stmt) = [null, null];
    }
  }
  /**
   * タスクの完全削除（マネージャー権限） taskテーブルとmessageテーブルの該当idを削除
   * 
   * @param int $id タスクid
   * @return void
   */
  public static function hardDelete(int $id): void
  {
    try {
      $pdo = self::db_connect();
      $pdo->beginTransaction();

      $sql = "SELECT theme FROM task WHERE id = :id";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
      $stmt->execute();
      $del_data = $stmt->fetch();

      $sql = "DELETE FROM task WHERE id = :id";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
      $stmt->execute();      

      $sql = "DELETE FROM message WHERE task_id = :task_id";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':task_id', $id, \PDO::PARAM_INT);
      $stmt->execute();    

      $pdo->commit();
      $_SESSION['del_msg'] = "タイトル : 「{$del_data['theme']}」のタスクを完全に削除しました";
      header('Location: ?mode=index');

    } catch(PDOException $e) {
      $pdo->rollBack();
      flashMsg('db', "内部サーバーエラーです。\n検索中のリソースに問題があるため、リソースを表示できません");
      writeLog(LOG_FILEPATH, $e->getMessage());
      header('Location:' . PATH . 'error?error_mode=500error');

      exit;
    } finally {
      list($pdo, $stmt) = [null, null];
    }

  }
}