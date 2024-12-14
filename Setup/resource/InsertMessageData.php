<?php
namespace Setup\Resource;

class InsertMessageData extends Database
{
  public static function insertMessage()
  {
    try {
      $pdo = self::dbCon();

      $sql = "ALTER TABLE message AUTO_INCREMENT = 1";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
           
      $pdo->beginTransaction();
      
      // メッセージ挿入
      $sql = "INSERT INTO message (task_id, comment, sender) VALUES 
              (1, 'manager-message text text text', 0),
              (1, 'member-message text text text', 1),
              (1, 'manager-message text text text', 0),
              (1, 'member-message text text text', 1),
              (1, 'manager-message text text text', 0),
              (1, 'member-message text text text', 1),
              (1, 'manager-message text text text', 0),
              (1, 'member-message text text text', 1),
              (1, 'manager-message text text text', 0),
              (1, 'member-messag text text text', 1),
              (1, 'manager-message text text text', 0),
              (2, 'manager-message text text text', 0),
              (2, 'member-message text text text', 1),
              (2, 'manager-message text text text', 0),
              (2, 'member-message text text text', 1),
              (2, 'manager-message text text text', 0),
              (2, 'member-message text text text', 1),
              (2, 'manager-message text text text', 0),
              (3, 'manager-message text text text', 0),
              (3, 'member-message text text text', 1),
              (3, 'manager-message text text text', 0),
              (3, 'member-message text text text', 1),
              (3, 'manager-message text text text', 0),
              (3, 'member-message text text text', 1),
              (3, 'manager-message text text text', 0),
              (3, 'member-message text text text', 1),
              (4, 'manager-message text text text', 0),
              (4, 'member-message text text text', 1),
              (4, 'manager-message text text text', 0),
              (4, 'member-message text text text', 1),
              (4, 'manager-message text text text', 0),
              (4, 'member-message text text text', 1),
              (4, 'manager-message text text text', 0),
              (4, 'member-message text text text', 1),
              (11, 'manager-message text text text', 0),
              (11, 'member-message text text text', 1),
              (11, 'manager-message text text text', 0),
              (11, 'member-message text text text', 1),
              (11, 'manager-message text text text', 0),
              (11, 'member-message text text text', 1),
              (11, 'manager-message text text text', 0),
              (11, 'member-message text text text', 1),
              (11, 'manager-message text text text', 0),
              (11, 'member-messag text text text', 1),
              (11, 'manager-message text text text', 0),
              (12, 'manager-message text text text', 0),
              (12, 'member-message text text text', 1),
              (12, 'manager-message text text text', 0),
              (12, 'member-message text text text', 1),
              (12, 'manager-message text text text', 0),
              (12, 'member-message text text text', 1),
              (12, 'manager-message text text text', 0),
              (13, 'manager-message text text text', 0),
              (13, 'member-message text text text', 1),
              (13, 'manager-message text text text', 0),
              (13, 'member-message text text text', 1),
              (13, 'manager-message text text text', 0),
              (13, 'member-message text text text', 1),
              (13, 'manager-message text text text', 0),
              (13, 'member-message text text text', 1),
              (14, 'manager-message text text text', 0),
              (14, 'member-message text text text', 1),
              (14, 'manager-message text text text', 0),
              (14, 'member-message text text text', 1),
              (14, 'manager-message text text text', 0),
              (14, 'member-message text text text', 1),
              (14, 'manager-message text text text', 0),
              (14, 'member-message text text text', 1),
              (21, 'manager-message text text text', 0),
              (21, 'member-message text text text', 1),
              (21, 'manager-message text text text', 0),
              (21, 'member-message text text text', 1),
              (21, 'manager-message text text text', 0),
              (21, 'member-message text text text', 1),
              (21, 'manager-message text text text', 0),
              (21, 'member-message text text text', 1),
              (21, 'manager-message text text text', 0),
              (21, 'member-messag text text text', 1),
              (21, 'manager-message text text text', 0),
              (22, 'manager-message text text text', 0),
              (22, 'member-message text text text', 1),
              (22, 'manager-message text text text', 0),
              (22, 'member-message text text text', 1),
              (22, 'manager-message text text text', 0),
              (22, 'member-message text text text', 1),
              (22, 'manager-message text text text', 0),
              (23, 'manager-message text text text', 0),
              (23, 'member-message text text text', 1),
              (23, 'manager-message text text text', 0),
              (23, 'member-message text text text', 1),
              (23, 'manager-message text text text', 0),
              (23, 'member-message text text text', 1),
              (23, 'manager-message text text text', 0),
              (23, 'member-message text text text', 1),
              (24, 'manager-message text text text', 0),
              (24, 'member-message text text text', 1),
              (24, 'manager-message text text text', 0),
              (24, 'member-message text text text', 1),
              (24, 'manager-message text text text', 0),
              (24, 'member-message text text text', 1),
              (24, 'manager-message text text text', 0),
              (24, 'member-message text text text', 1),
              (31, 'manager-message text text text', 0),
              (31, 'member-message text text text', 1),
              (31, 'manager-message text text text', 0),
              (31, 'member-message text text text', 1),
              (31, 'manager-message text text text', 0),
              (31, 'member-message text text text', 1),
              (31, 'manager-message text text text', 0),
              (31, 'member-message text text text', 1),
              (31, 'manager-message text text text', 0),
              (31, 'member-messag text text text', 1),
              (31, 'manager-message text text text', 0),
              (32, 'manager-message text text text', 0),
              (32, 'member-message text text text', 1),
              (32, 'manager-message text text text', 0),
              (32, 'member-message text text text', 1),
              (32, 'manager-message text text text', 0),
              (32, 'member-message text text text', 1),
              (32, 'manager-message text text text', 0),
              (33, 'manager-message text text text', 0),
              (33, 'member-message text text text', 1),
              (33, 'manager-message text text text', 0),
              (33, 'member-message text text text', 1),
              (33, 'manager-message text text text', 0),
              (33, 'member-message text text text', 1),
              (33, 'manager-message text text text', 0),
              (33, 'member-message text text text', 1),
              (34, 'manager-message text text text', 0),
              (34, 'member-message text text text', 1),
              (34, 'manager-message text text text', 0),
              (34, 'member-message text text text', 1),
              (34, 'manager-message text text text', 0),
              (34, 'member-message text text text', 1),
              (34, 'manager-message text text text', 0),
              (34, 'member-message text text text', 1),
              (41, 'manager-message text text text', 0),
              (41, 'member-message text text text', 1),
              (41, 'manager-message text text text', 0),
              (41, 'member-message text text text', 1),
              (41, 'manager-message text text text', 0),
              (41, 'member-message text text text', 1),
              (41, 'manager-message text text text', 0),
              (41, 'member-message text text text', 1),
              (41, 'manager-message text text text', 0),
              (41, 'member-messag text text text', 1),
              (41, 'manager-message text text text', 0),
              (42, 'manager-message text text text', 0),
              (42, 'member-message text text text', 1),
              (42, 'manager-message text text text', 0),
              (42, 'member-message text text text', 1),
              (42, 'manager-message text text text', 0),
              (42, 'member-message text text text', 1),
              (42, 'manager-message text text text', 0),
              (43, 'manager-message text text text', 0),
              (43, 'member-message text text text', 1),
              (43, 'manager-message text text text', 0),
              (43, 'member-message text text text', 1),
              (43, 'manager-message text text text', 0),
              (43, 'member-message text text text', 1),
              (43, 'manager-message text text text', 0),
              (43, 'member-message text text text', 1),
              (44, 'manager-message text text text', 0),
              (44, 'member-message text text text', 1),
              (44, 'manager-message text text text', 0),
              (44, 'member-message text text text', 1),
              (44, 'manager-message text text text', 0),
              (44, 'member-message text text text', 1),
              (44, 'manager-message text text text', 0),
              (44, 'member-message text text text', 1)
              ";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();

      $update_id = [[1, 2, 3, 4], [11, 12, 13, 14], [21, 22, 23, 24], [31, 32, 33, 34], [41, 42, 43, 44]];
      $sql_1st = "UPDATE task SET msg_flag = 1, mg_to_mem = 1, mem_to_mg = 0 WHERE id = :id";
      $sql_2nd = "UPDATE task SET msg_flag = 1, mg_to_mem = 0, mem_to_mg = 0 WHERE id = :id";
      $sql_3rd = "UPDATE task SET msg_flag = 1, mg_to_mem = 0, mem_to_mg = 1 WHERE id = :id";
      $sql_4th = "UPDATE task SET msg_flag = 1, mg_to_mem = 0, mem_to_mg = 2 WHERE id = :id";
      $sql = [$sql_1st, $sql_2nd, $sql_3rd, $sql_4th];

      foreach($update_id as $id) {
        for($i = 0; $i < 4; $i++) {
          $stmt = $pdo->prepare($sql[$i]);
          $stmt->bindValue(':id', $id[$i], \PDO::PARAM_INT);
          $stmt->execute();
        }
      }
      
      $pdo->commit();
      error_log("コミット実行");

      echo "messageデータの挿入に成功しました<br>";

    } catch (\PDOException $e) {
       $pdo->rollBack();
      echo "messageデータの挿入に失敗しました<br>";
      echo "エラー内容: " . $e->getMessage();
      exit;

    } finally {
      $pdo = null;
    }
  }
}





