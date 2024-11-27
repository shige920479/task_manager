<?php
namespace App\Database;

use DateTime;
use PDOException;

//フラッシュメッセージ用、完成後にメッセージも合わせて削除。
require_once '../Services/helper.php'; 
use function App\Services\flashMsg;
use function App\Services\old_store;

/////////////////////////////////////////////////////

class StoreTask extends DbConnect
{
  public static function storeTask ($in) {

    if(self::validation($in)) {

      try {
        $pdo = DbConnect::db_connect();
        $sql = "INSERT INTO task
              (member_id, priority, category, theme, content, deadline)
              VALUES
              (:member_id, :priority, :category, :theme, :content, :deadline)
              ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':member_id', $in['member_id'], \PDO::PARAM_STR);
        $stmt->bindValue(':priority', intval($in['priority']), \PDO::PARAM_INT);
        $stmt->bindValue(':category', $in['category'], \PDO::PARAM_STR);
        $stmt->bindValue(':theme', $in['theme'], \PDO::PARAM_STR);
        $stmt->bindValue(':content', $in['content'], \PDO::PARAM_STR);
        $stmt->bindValue(':deadline', $in['deadline'], \PDO::PARAM_STR);
        $stmt->execute();
        $pdo = [];
        header('Location: ../Views/index.php');
        exit;
        
      } catch(PDOException $e) {
        flashMsg('db', "登録に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
        header('Location: ../Views/500error.php');
        exit;
      }
    } else {
      header('Location: ../Views/index.php');
      exit;
    }
  }
  protected static function validation($in) {

    //優先度のバリデーション
    if($in['priority'] === "") {
      flashMsg('priority', '要選択');
    } elseif(!in_array($in['priority'], [1,2,3])) {
      flashMsg('priority', '再選択');      
    } else {
      old_store('priority', $in['priority']);
    }
    //カテゴリーのバリデーション
    if($in['category'] === "") {
      flashMsg('category', '入力or選択願います');      
    } elseif(mb_strlen($in['category']) > 50) {
      flashMsg('category', '50文字以内で入力願います');
    } else {
      old_store('category', $in['category']);
    }
    //テーマのバリデーション
    if($in['theme'] === "") {
      flashMsg('theme', '未入力です');      
    } elseif(mb_strlen($in['theme']) > 50) {
      flashMsg('theme', '50文字以内で入力願います');
    } else {
      old_store('theme', $in['theme']);
    }
    //タスク内容のバリデーション
    if($in['content'] === "") {
      flashMsg('content', '未入力です');      
    } elseif(mb_strlen($in['content']) > 255) {
      flashMsg('content', '255文字以内で入力願います');
    } else {
      old_store('content', $in['content']);
    }
    //完了目標のバリデーション
    if($in['deadline'] === "") {
      flashMsg('deadline', '日付が未入力です');      
    } elseif(!self::dateValidate($in['deadline'])) {
      flashMsg('deadline', '無効な日付形式です');
    } elseif($in['deadline'] < date('Y-m-d')) {
      flashMsg('deadline', '過去の日付です');
    } else {
      old_store('deadline', $in['deadline']);
    }

    $result = empty($_SESSION['error']) ? true : false;
    return $result;
  }
  //日付チェック
  protected static function dateValidate($inputDate, $format = 'Y-m-d') {
    date_default_timezone_set('Asia/Tokyo');
    $d = DateTime::createFromFormat($format, $inputDate);
    return $d && $d->format($format) === $inputDate;
  }

}