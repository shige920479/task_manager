<?php
namespace App\Database;

use PDOException;

use function App\Services\flashMsg;
use function App\Services\getUri;
use function App\Services\old_store;
use function App\Services\writeLog;

/**
 * タスクに対するコメント（チャット）のデータベース登録
 */
class Message extends DbConnect
{
  /**
   * チャットメッセージ登録&&一覧表のアイコンステータス変更用フラグの切替
   * 
   * @param array $request 入力データ ['sender'] = member|manager ・・処理が異なるので識別用の引数
   * @return void
   */

  public static function sendMessage(array $request): void
   {
    $uri = getUri();

    if(self::validation($request)) {
      try {
        $pdo = self::db_connect();
        $pdo->beginTransaction(); // トランザクション開始
        $sql = 'INSERT INTO message
                (task_id, comment, sender)
                VALUES
                (:task_id, :comment, :sender)
                ';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':task_id', $request['id'], \PDO::PARAM_INT);
        $stmt->bindValue(':comment', $request['comment'], \PDO::PARAM_STR);
        $stmt->bindValue(':sender', $request['sender'], \PDO::PARAM_INT);
        $stmt->execute();
        
        if($request['sender'] === "0") $sql = "UPDATE task SET msg_flag = 1, mg_to_mem = 1 WHERE id = :id";
        if($request['sender'] === "1") $sql = "UPDATE task SET mem_to_mg = 1 WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $request['id'], \PDO::PARAM_INT);
        $stmt->execute();
        $pdo->commit(); // コミット

        
        header("Location:" . PATH . "{$uri}?mode=chat&id={$request['id']}");
      
      } catch(\PDOException $e) {
        $pdo->rollBack(); // ロールバック
        flashMsg('db', "内部サーバーエラーです。\n検索中のリソースに問題があるため、リソースを表示できません");
        writeLog(LOG_FILEPATH, $e->getMessage());
        header('Location:' . PATH . 'error?error_mode=500error');
        exit;
      
      } finally {
        list($pdo, $stmt) = [null, null];
      }
    
    } else {
      header("Location:" . PATH . "{$uri}?mode=chat&id={$request['id']}");
    }
  }
  /**
   * 差し戻し用メッセージ ※メンバーが完了したタスクに対してマネージャーから差し戻しする処理
   * 
   * @param array $request 入力データ ['sender'] = member|manager ・・処理が異なるので識別用の引数
   * @return void
   */
  public static function sendBack(array $request): void
  {
    if(self::validation($request)) {
      try {
        $pdo = self::db_connect();
        $pdo->beginTransaction();

        $sql = 'INSERT INTO message
        (task_id, comment, sender)
        VALUES
        (:task_id, :comment, :sender)';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':task_id', $request['id'], \PDO::PARAM_INT);
        $stmt->bindValue(':comment', $request['comment'], \PDO::PARAM_STR);
        $stmt->bindValue(':sender', $request['sender'], \PDO::PARAM_INT);
        $stmt->execute();
        
        $sql = "UPDATE task SET msg_flag = 1, mg_to_mem = 1, del_flag = 2 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $request['id'], \PDO::PARAM_INT);
        $stmt->execute();

        $pdo->commit();
        header("Location:" . PATH . "manager_dashboard?mode=chat&id={$request['id']}");

      } catch(PDOException $e) {
        $pdo->rollBack();
        flashMsg('db', "内部サーバーエラーです。\n検索中のリソースに問題があるため、リソースを表示できません");
        writeLog(LOG_FILEPATH, $e->getMessage());
        header('Location:' . PATH . 'error?error_mode=500error');
        exit;
      } finally {
        list($pdo, $stmt) = [null, null];
      }
    } else {
      header("Location:" . PATH . "manager_dashboard?mode=chat&id={$request['id']}");
    }
  }

  /**
   * チャットメッセージ専用のバリデーション
   * 
   * @param array $request 入力データ
   * @return bool
   */
  private static function validation(array $request): bool
   {
    if($request['comment'] === "") {
      flashMsg('comment', '未入力です');
    } elseif(mb_strlen($request['comment']) > 255) {
      flashMsg('comment', '255文字以内で入力願います');
      old_store('comment', $request['comment']);
    }
    $result = empty($_SESSION['error']) ? true : false;
    return $result;
  }
}

