<?php
namespace App\Database;

use DateTime;
use PDOException;

//フラッシュメッセージ用、完成後にメッセージも合わせて削除。
require_once '../Services/helper.php'; 
use function App\Services\flashMsg;
use function App\Services\old_store;

/////////////////////////////////////////////////////

/**
 * 新規タスク登録用クラス
 */
class StoreTask extends DbConnect
{

  /**
   * データベースへの新規タスク登録
   * @param array $in 入力データ
   * @return void 
   */
   public static function storeTask (array $in): void
  {
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
        list($pdo, $stmt) = [null, null];
        header('Location: ?mode=index');
        exit;
        
      } catch(PDOException $e) {
        flashMsg('db', "登録に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
        header('Location: ../Views/500error.php');
        exit;
      }
    } else {
      header('Location: ?mode=index');
      exit;
    }
  }
  /**
   * 新規タスク登録専用のバリデーション
   * @param array $in 入力データ
   * @return bool
   */
  private static function validation(array $in): bool
  {
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
  /**
   * 新規タスク登録のバリデーションで使用する日付バリデーション
   * @param string $input_date 入力された日付(フォーマット済の日付)
   * @param string $format 日付をインスタンス化する際に使用
   */
  private static function dateValidate(string $input_date, string $format = 'Y-m-d'): bool
  {
    date_default_timezone_set('Asia/Tokyo');
    $d = DateTime::createFromFormat($format, $input_date);
    return $d && $d->format($format) === $input_date;
  }

}