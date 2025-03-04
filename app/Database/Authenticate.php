<?php
namespace App\Database;

use PDO;
use PDOException;
use function App\Services\flashMsg;
use function App\Services\writeLog;

class Authenticate extends DbConnect
{
  /**
   * ログイン中メンバーとリクエストされたタスク所有メンバーの照合
   * @param int $task_id
   * @return string メンバーid 該当なければfalse 
   */

  public static function taskBelongsToMember(string $task_id): string | bool
  {
    $sql = "SELECT member_id FROM task WHERE id = :id";

    try {
      $pdo = self::db_connect();
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':id', $task_id, PDO::PARAM_INT);
      $stmt->execute();
      $result = $stmt->fetch();
      return $result['member_id'];

    } catch(PDOException $e) {
      flashMsg('db', "内部サーバーエラーです。\n検索中のリソースに問題があるため、リソースを表示できません");
      writeLog(LOG_FILEPATH, $e->getMessage());
      header('Location:' . PATH . 'error/?error_mode=500error');
      exit;
    }
  }

}