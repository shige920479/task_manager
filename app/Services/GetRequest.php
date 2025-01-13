<?php
namespace App\Services;

/**
 * 入力データの各種処理用クラス
 */
class GetRequest
{
  /**
   * 入力データの各種処理後に配列でリターン
   * 処理内容 POSTデータのトークンチェック、UTF-8への変換、htmlエンティティ変換
   * @param void
   * @return array $request リクエストデータを戻す
   */
  public static function getRequest(): array
  {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      if(!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        flashMsg('tokenerror', "不正なリクエストです、再度ログインをお試しください"); 
        header('Location:' . PATH . 'error/?error_mode=400error');
        exit;
      } else {
        unset($_SESSION['token']);
      }
    }
    $request = [];
    $params= array();
    if(!empty($_POST) && is_array($_POST)) { $params += $_POST; };
    if(!empty($_GET) && is_array($_GET)) {$params += $_GET; };

    foreach($params as $key => $val) {
      $enc = mb_detect_encoding($val);
      $val = mb_convert_encoding($val, 'UTF-8', $enc);
      $val = htmlentities($val, ENT_QUOTES, 'UTF-8');
      $request[$key] = $val;
    }
    return $request;
  }
}