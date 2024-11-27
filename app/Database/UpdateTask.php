<?php
namespace App\Database;

use PDOException;

//フラッシュメッセージ用、完成後にメッセージも合わせて削除。
require_once '../Services/helper.php'; 
use function App\Services\flashMsg;
/////////////////////////////////////////////////////

class UpdateTask extends StoreTask
{
  public static function updateTask ($in) {

    if(self::validation($in)) {
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
        $stmt->bindValue(':priority', intval($in['priority']), \PDO::PARAM_INT);
        $stmt->bindValue(':category', $in['category'], \PDO::PARAM_STR);
        $stmt->bindValue(':theme', $in['theme'], \PDO::PARAM_STR);
        $stmt->bindValue(':content', $in['content'], \PDO::PARAM_STR);
        $stmt->bindValue(':deadline', $in['deadline'], \PDO::PARAM_STR);
        $stmt->bindValue('id', $in['id'], \PDO::PARAM_INT);
        $stmt->execute();
        $pdo = [];
        header('Location:../Views/index.php');
        exit;
  
      } catch(PDOException $e) {
        flashMsg('db', "登録に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
        header('Location: ../Views/500error.php');
        exit;
      }
    } else {
      header("Location: ../Views/MemberEditView.php?id={$in['id']}");
      exit;
    }
  }
}