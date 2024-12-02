<?php
namespace App\Database;
require_once '../config/config.php';

//フラッシュメッセージ用、完成後にメッセージも合わせて削除。
require_once '../Services/helper.php'; 
use function App\Services\flashMsg;
/////////////////////////////////////////////////////

use PDO;
use PDOException;

// 作ってみた後にすべての例外処理を外せるか見直し -> ここは外さないで、他のファイルで外せるものを検討。

class DbConnect
{
  protected static function db_connect() {
      $pdo = new PDO(DSN, USERNAME, PASSWORD);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      return $pdo;
  }

  /** index.php メンバーidからタスクデータを取得
  * param int $member_id
  * return array $member_data 
  */
  public static function getMemberData(int $member_id): array
   {
    try {
      $pdo = self::db_connect();
      $sql = "SELECT * FROM task WHERE member_id = :member_id and del_flag = 0";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
      $stmt->execute();
      $member_data = $stmt->fetchAll();
      list($pdo, $stmt) = [null, null];
      return $member_data;

    } catch(PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
      header('Location: ../Views/500error.php');
      exit;
    }
  }

  /** ManagerChatView.php, MemberChatView.php, MemberEditView.php, DeleteTask.php で使用
   * 変数名が判りにくいので、別途命名
   */
  public static function selectId($id) {
    try {
      $pdo = self::db_connect();
      $sql = "SELECT t.*, m.name
              FROM task as t
              LEFT JOIN member as m
              ON t.member_id = m.id
              WHERE t.id = :id";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $task = $stmt->fetch();
      list($pdo, $stmt) = [null, null];
      return $task;

    } catch(PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
      header('Location: ../Views/500error.php');
      exit;
    } 
  }

  /** MemberChatView.php, ManagerChatView.php
   * param int $task_id
   * return array メッセージデータ
   */
  public static function getMessage($task_id, $login_user) {
    try {
      $pdo = self::db_connect();
      $sql = "SELECT * FROM message WHERE task_id = :task_id";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':task_id', $task_id, PDO::PARAM_INT);
      $stmt->execute();
      $chats = $stmt->fetchAll();

      // メッセージの読み込みと同時に、memberおよびmanagerの確認フラグを処理
      if($chats) self::loadConfirm($task_id, $login_user, $pdo);

      list($pdo, $stmt) = [null, null];
      return $chats;

    } catch(PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
      header('Location: ../Views/500error.php');
      exit;
    }
  }

  private static function loadConfirm(int $task_id, string $login_user, $pdo): void
  {
    $sql = "SELECT * FROM task WHERE id = :id"; //selectId()でも良いが、何度もこちらの方がPDO一回で済む
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $task_id, PDO::PARAM_INT);
    $stmt->execute();
    $task = $stmt->fetch();

    if($login_user === MANAGER && $task['mem_to_mg'] == 1) {
      $sql = "UPDATE task SET mem_to_mg = 2 WHERE id = :task_id";
    } elseif($login_user === MEMBER && $task['mg_to_mem'] == 1) {
      $sql = "UPDATE task SET mg_to_mem = 0 WHERE id = :task_id";
    } else {
      return;
    }
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':task_id', $task_id, PDO::PARAM_INT);
    $stmt->execute();
  }
  
  
  /** Login.php
   * param string メールアドレス、テーブル名
   * return array メンバー情報
   */
  public static function getUserByEmail($email, $table) {
    try {
      $pdo = self::db_connect();
      $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE email = :email");
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      $login_member = $stmt->fetch();
      list($pdo, $stmt) = [null, null];
      return $login_member;

    } catch(PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
      header('Location: ../Views/500error.php');
      exit;
    } 
  }


  public static function getCategory($member_id) {
    try {
      $pdo = self::db_connect();
      $sql = "SELECT DISTINCT category FROM task where member_id = :member_id";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
      $stmt->execute();
      $categories = $stmt->fetchAll();
      list($pdo, $stmt) = [null, null];
      return $categories;

    } catch(PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
      header('Location: ../Views/500error.php');
      exit;
    }
  }

//Manager.indexで使用（全員or検索双方のデータを取得） ※memberテーブルと連結させてメンバー名も取得
  public static function getTaskData($in) {
    try {
      $pdo = self::db_connect();
      $sql = 'SELECT t.*, m.name
              FROM task as t
              left join member as m 
              ON t.member_id = m.id
              WHERE t.del_flag = 0';
      if(!empty($in['name'])) {
        $sql .= " AND m.name = :name";
      }
      if(!empty($in['category'])) {
        $sql .= " AND t.category = :category";
      }
      if(!empty($in['theme'])) {
        $sql .= " AND t.theme LIKE :theme";
      }

      $stmt = $pdo->prepare($sql);

      if(!empty($in['name'])) {
        $stmt->bindValue(':name', $in['name'], PDO::PARAM_STR);
      }
      if(!empty($in['category'])) {
        $stmt->bindValue(':category', $in['category'], PDO::PARAM_STR);
      }
      if(!empty($in['theme'])) {
        $theme = '%'. $in['theme'] . '%';
        $stmt->bindValue(':theme', $theme, PDO::PARAM_STR);
      }
      // var_dump($stmt);
      // exit;
      $stmt->execute();
      $all_data = $stmt->fetchAll();
      list($pdo, $stmt) = [null, null];
      return $all_data;

    } catch(PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
      header('Location: ../Views/500error.php');
      exit;
    }
  }


  /**
   * 必要なデータ
   * 【要件】最大10件、ページは最後まで表示
   * 【必要なデータ】 10件分のデータ、ページ数、現在のページ
   */


  



}