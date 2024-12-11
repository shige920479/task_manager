<?php
namespace App\Database;

use function App\Services\flashMsg;
use PDO;

class DbConnect
{
  /**
   * データベース接続
   * @param void
   * @return pdo
   */
  protected static function db_connect(): pdo
  {
      $pdo = new PDO(DSN, USERNAME, PASSWORD);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      return $pdo;
  }

  /**
   * メンバーidから該当のタスクデータを取得
   * 
   * @param int $member_id
   * @param null|string $sort_order デフォルトはnull、並べ替えの際に規則を取得
   * @return array|bool $member_data メンバーのタスクデータ、登録直後の初期データがない際はfalse
   */
  public static function getMemberData(int $member_id, ?string $sort_order): array
   {
    try {
      $pdo = self::db_connect();
      $sql = "SELECT * FROM task WHERE member_id = :member_id and del_flag = 0";

      if(!isset($sort_order) || $sort_order === "") $sql .= ' ORDER BY created_at desc';
      if(isset($sort_order) && $sort_order === 'sort_deadline') $sql .= ' ORDER BY deadline';
      if(isset($sort_order) && $sort_order === 'sort_category') $sql .= ' ORDER BY category'; 
      if(isset($sort_order) && $sort_order === 'sort_priority') $sql .= ' ORDER BY priority desc'; 

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
      $stmt->execute();
      $member_data = $stmt->fetchAll();
      list($pdo, $stmt) = [null, null];
      return $member_data;

    } catch(\PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}");
      header('Location: ../Views/500error.php');
      exit;
    }
  }

  /**
   * タスクidから該当のタスクデータとメンバーの名前を取得 
   * 
   * (MemberController.php,ManagerController.php,DeleteTask.php)
   * @param int $task_id 選択したタスクid
   * @return array $task 該当のタスクデータ・メンバーの名前
   */
  public static function getTaskById(int $task_id): array
  {
    try {
      $pdo = self::db_connect();
      $sql = "SELECT t.*, m.name
              FROM task as t
              LEFT JOIN member as m
              ON t.member_id = m.id
              WHERE t.id = :id";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':id', $task_id, PDO::PARAM_INT);
      $stmt->execute();
      $task = $stmt->fetch();
      list($pdo, $stmt) = [null, null];
      return $task;

    } catch(\PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}");
      header('Location: ../Views/500error.php');
      exit;
    } 
  }

  /** 
   * チャットボックス内のメッセージデータ取得＆ページ読込時に未読メッセージの既読化（一覧ページのアイコン切替）
   * 
   * @param int $task_id
   * @param string $login_user メンバー|マネージャーかを識別
   * @return array $chats メッセージデータ
   */
  public static function getChatData(int $task_id, string $login_user): array|false
  {
    try {
      $pdo = self::db_connect();
      $pdo->beginTransaction(); // トランザクション開始
      $sql = "SELECT * FROM message WHERE task_id = :task_id";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':task_id', $task_id, PDO::PARAM_INT);
      $stmt->execute();
      $chats = $stmt->fetchAll();

      // メッセージの読み込みと同時に、memberおよびmanagerの確認フラグを処理
      if($chats) self::loadConfirm($task_id, $login_user, $pdo);

      $pdo->commit(); //コミット
      list($pdo, $stmt) = [null, null];
      return $chats;

    } catch(\PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}");
      $pdo->rollBack(); // ロールバック
      header('Location: ../Views/500error.php');
      exit;
    }
  }
  /**
   * getChatData()内でのみ使用、ページ読込時に既読としてメンバー・マネージャー双方のフラグを処理（アイコンに反映）
   * 
   * @param int $task_id
   * @param string $login_user メンバーorマネージャーかを識別
   * @param pdo $pdo
   * @return void
   */
  private static function loadConfirm(int $task_id, string $login_user, $pdo): void
  {
    $sql = "SELECT * FROM task WHERE id = :id";
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
  
  /** 
   * ログイン認証時にメールアドレスからユーザー情報を取得（Login.php）
   * 
   * @param string $email メールアドレス
   * @param string $table memberテーブルormanagerテーブルを識別
   * @return array|bool $user_data 認証中ユーザーの登録情報|登録無ければfalse
   */
  public static function getUserByEmail(string $email, string $table): array|false
   {
    try {
      $pdo = self::db_connect();
      $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE email = :email");
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      $user_data = $stmt->fetch();
      list($pdo, $stmt) = [null, null];
      return $user_data;

    } catch(\PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}");
      header('Location: ../Views/500error.php');
      exit;
    } 
  }
  /**
   * タスク一覧のセレクトボックスのoptionタグに使用するカテゴリーを取得（MemberController.php）
   * 
   * @param int $member_id ログイン中のメンバーid
   * @return $categories タスクに使用しているカテゴリー（重複なし）
   */
  public static function getCategory(int $member_id): array
   {
    try {
      $pdo = self::db_connect();
      $sql = "SELECT DISTINCT category FROM task where member_id = :member_id";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
      $stmt->execute();
      $categories = $stmt->fetchAll();
      list($pdo, $stmt) = [null, null];
      return $categories;

    } catch(\PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}");
      header('Location: ../Views/500error.php');
      exit;
    }
  }

  /**
   * 全メンバーのタスクデータに加えmemberテーブルから名前を取得（ManagerIndex.php）
   * 
   * @param array $request 検索時のGETリクエストを取得
   * @param null|array 一覧表の並べ替え時の規則を取得
   * @return array $all_data 全メンバー|検索|並べ替え後のタスクデータ
   */
  public static function getTaskData(?array $request, ?string $sort_order): array
   {
    try {
      $pdo = self::db_connect();
      $sql = 'SELECT t.*, m.name
              FROM task as t
              left join member as m 
              ON t.member_id = m.id
              WHERE t.del_flag = 0';
      if(!empty($request['name'])) $sql .= " AND m.name = :name";
      if(!empty($request['category'])) $sql .= " AND t.category = :category";
      if(!empty($request['theme'])) $sql .= " AND t.theme LIKE :theme";
      if(!isset($sort_order) || $sort_order === "") $sql .= ' ORDER BY t.created_at desc';
      if(isset($sort_order) && $sort_order === 'sort_name') $sql .= ' ORDER BY m.name';
      if(isset($sort_order) && $sort_order === 'sort_deadline') $sql .= ' ORDER BY t.deadline';
      if(isset($sort_order) && $sort_order === 'sort_category') $sql .= ' ORDER BY t.category'; 
      if(isset($sort_order) && $sort_order === 'sort_priority') $sql .= ' ORDER BY t.priority desc'; 

      $stmt = $pdo->prepare($sql);

      if(!empty($request['name'])) $stmt->bindValue(':name', $request['name'], PDO::PARAM_STR);
      if(!empty($request['category'])) $stmt->bindValue(':category', $request['category'], PDO::PARAM_STR);
      if(!empty($request['theme'])) {
        $theme = '%'. $request['theme'] . '%';
        $stmt->bindValue(':theme', $theme, PDO::PARAM_STR);
      }

      $stmt->execute();
      $all_data = $stmt->fetchAll();
      list($pdo, $stmt) = [null, null];
      return $all_data;

    } catch(\PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}"); //フラッシュメッセージ用、完成後に削除。
      header('Location: ../Views/500error.php');
      exit;
    }
  }
  /**
   * 登録されている全メンバー名を取得
   * 
   * @param void
   * @return array $members memberテーブルに登録済のメンバー名
   */
  public static function getMemberName(): array
  {
    try {
      $pdo = self::db_connect();
      $sql = 'SELECT name FROM member';
      $stmt = $pdo->query($sql);
      $members = $stmt->fetchAll();
      list($pdo, $stmt) = [null, null];
      return $members;

    } catch(\PDOException $e) {
      flashMsg('db', "データ取得に失敗しました : {$e->getMessage()}");
      header('Location: ../Views/500error.php');
    }
  }
}