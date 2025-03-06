<?php
namespace App\Database;

use function App\Services\flashMsg;
use function App\Services\writeLog;

/**
 * タスク更新
 */
class UpdateTask extends StoreTask
{
  /**
   * タスク内容の更新データ登録
   * @param array $request 入力データ
   * @return void
   */
  public static function updateTask (array $request): void
  {
    if(self::validation($request)) {
      try {
        $pdo = DbConnect::db_connect();
        $sql = "UPDATE task
                SET
                priority = :priority,
                category = :category,
                theme = :theme,
                content = :content,
                deadline = :deadline
                WHERE
                id = :id
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':priority', intval($request['priority']), \PDO::PARAM_INT);
        $stmt->bindValue(':category', $request['category'], \PDO::PARAM_STR);
        $stmt->bindValue(':theme', $request['theme'], \PDO::PARAM_STR);
        $stmt->bindValue(':content', $request['content'], \PDO::PARAM_STR);
        $stmt->bindValue(':deadline', $request['deadline'], \PDO::PARAM_STR);
        $stmt->bindValue('id', $request['id'], \PDO::PARAM_INT);
        $stmt->execute();
        
        if(isset($_SESSION['old'])) unset($_SESSION['old']);
        header('Location: ?mode=index');
        exit;
        
      } catch(\PDOException $e) {
        flashMsg('db', "内部サーバーエラーです。\n検索中のリソースに問題があるため、リソースを表示できません");
        writeLog(LOG_FILEPATH, $e->getMessage());
        header('Location:' . PATH . 'error?error_mode=500error');
        exit;
        
      } finally {
        list($pdo, $stmt) = [null, null];
      }

    } else {
      header("Location: ?mode=edit&id={$request['id']}");
      exit;
    }
  }
}