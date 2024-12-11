<?php
namespace App\Database;

require_once '../Services/helper.php'; 
use function App\Services\flashMsg;

/**
 * タスク更新用クラス
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
        list($pdo, $stmt) = [null, null];
        header('Location: ?mode=index');
        exit;
  
      } catch(\PDOException $e) {
        flashMsg('db', "登録に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
        header('Location: ../Views/500error.php');
        exit;
      }
    } else {
      header("Location: ?mode=edit&id={$request['id']}");
      exit;
    }
  }
}