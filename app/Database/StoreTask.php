<?php
namespace App\Database;

use DateTime;
use PDOException;
use function App\Services\flashMsg;
use function App\Services\old_store;
use function App\Services\writeLog;

/**
 * 新規タスク登録用クラス
 */
class StoreTask extends DbConnect
{

  /**
   * データベースへの新規タスク登録
   * @param array $request 入力データ
   * @return void 
   */
   public static function storeTask (array $request): void
  {
    if(self::validation($request)) {
      try {
        $pdo = DbConnect::db_connect();
        $sql = "INSERT INTO task
              (member_id, priority, category, theme, content, deadline)
              VALUES
              (:member_id, :priority, :category, :theme, :content, :deadline)
              ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':member_id', $request['member_id'], \PDO::PARAM_STR);
        $stmt->bindValue(':priority', intval($request['priority']), \PDO::PARAM_INT);
        $stmt->bindValue(':category', $request['category'], \PDO::PARAM_STR);
        $stmt->bindValue(':theme', $request['theme'], \PDO::PARAM_STR);
        $stmt->bindValue(':content', $request['content'], \PDO::PARAM_STR);
        $stmt->bindValue(':deadline', $request['deadline'], \PDO::PARAM_STR);
        $stmt->execute();
        
        if(isset($_SESSION['old'])) unset($_SESSION['old']);
        header('Location: ?mode=index');
        exit;
        
      } catch(PDOException $e) {
        flashMsg('db', "内部サーバーエラーです。\n検索中のリソースに問題があるため、リソースを表示できません");
        writeLog(LOG_FILEPATH, $e->getMessage());
        header('Location:' . PATH . 'error/?error_mode=500error');
        exit;
        
      } finally {
        list($pdo, $stmt) = [null, null];
      } 
    } else {
      header('Location: ?mode=index');
      exit;
    }
  }
  /**
   * 新規タスク登録専用のバリデーション
   * @param array $request 入力データ
   * @return bool
   */
  protected static function validation(array $request): bool
  {
    //優先度のバリデーション
    if($request['priority'] === "") {
      flashMsg('priority', '要選択');
    } elseif(!in_array($request['priority'], [1,2,3])) {
      flashMsg('priority', '再選択');      
    } else {
      old_store('priority', $request['priority']);
    }
    //カテゴリーのバリデーション
    if($request['category'] === "") {
      flashMsg('category', '入力or選択願います');      
    } elseif(mb_strlen($request['category']) > 50) {
      flashMsg('category', '50文字以内で入力願います');
    } else {
      old_store('category', $request['category']);
    }
    //テーマのバリデーション
    if($request['theme'] === "") {
      flashMsg('theme', '未入力です');      
    } elseif(mb_strlen($request['theme']) > 50) {
      flashMsg('theme', '50文字以内で入力願います');
    } else {
      old_store('theme', $request['theme']);
    }
    //タスク内容のバリデーション
    if($request['content'] === "") {
      flashMsg('content', '未入力です');      
    } elseif(mb_strlen($request['content']) > 255) {
      flashMsg('content', '255文字以内で入力願います');
    } else {
      old_store('content', $request['content']);
    }
    //完了目標のバリデーション
    if($request['deadline'] === "") {
      flashMsg('deadline', '未入力です');      
    } elseif(!self::dateValidate($request['deadline'])) {
      flashMsg('deadline', '無効な日付形式');
    } elseif($request['deadline'] < date('Y-m-d')) {
      flashMsg('deadline', '過去の日付です');
    } else {
      old_store('deadline', $request['deadline']);
    }

    $result = empty($_SESSION['error']) ? true : false;
    return $result;
  }
  /**
   * 新規タスク登録のバリデーションで使用する日付バリデーション
   * @param string $input_date 入力された日付(フォーマット済の日付)
   * @param string $format 日付をインスタンス化する際に使用
   */
  protected static function dateValidate(string $input_date, string $format = 'Y-m-d'): bool
  {
    date_default_timezone_set('Asia/Tokyo');
    $d = DateTime::createFromFormat($format, $input_date);
    return $d && $d->format($format) === $input_date;
  }

}