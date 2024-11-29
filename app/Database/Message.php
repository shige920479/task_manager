<?php
namespace App\Database;

use PDO;
use PDOException;

use function App\Services\flashMsg;
use function App\Services\old_store;

class Message extends DbConnect
{
  public static function sendMessage($in) {
    // getformの改行対応が必要

    if(self::validation($in)) {
      try {
        $pdo = self::db_connect();
        $sql = 'INSERT INTO message
                (task_id, comment, sender)
                VALUES
                (:task_id, :comment, :sender)
                ';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':task_id', $in['id'], PDO::PARAM_INT);
        $stmt->bindValue(':comment', $in['comment'], PDO::PARAM_STR);
        $stmt->bindValue(':sender', $in['sender'], PDO::PARAM_INT);
        $stmt->execute();
        
        if($in['sender'] === "0") $sql = "UPDATE task SET msg_flag = 1, mg_to_mem = 1 WHERE id = :id";
        if($in['sender'] === "1") $sql = "UPDATE task SET mem_to_mg = 1 WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $in['id'], PDO::PARAM_INT);
        $stmt->execute();
  
        if($in['sender'] === "0") header("Location: ../Views/ManagerChatView.php?id={$in['id']}");
        if($in['sender'] === "1") header("Location:?mode=chat&id={$in['id']}");
      
      } catch(PDOException $e) {
        flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
        header('Location: ../Views/500error.php');
        exit;
      }
    } else {
      if($in['sender'] === "0") header("Location: ../Views/ManagerChatView.php?id={$in['id']}");
      if($in['sender'] === "1") header("Location: ?mode=chat&id={$in['id']}");
    }
  }

  protected static function validation($in) {

    //コメントのバリデーション
    if($in['comment'] === "") {
      flashMsg('comment', '未入力です');
    } elseif(mb_strlen($in['comment']) > 255) {
      flashMsg('comment', '255文字以内で入力願います');
      old_store('comment', $in['comment']);
    }
    $result = empty($_SESSION['error']) ? true : false;
    return $result;
  }

}

