<?php
namespace App\Services;
require_once '../Services/helper.php';


class GetForm
{
  public static function getForm() {
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      if(!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        flashMsg('tokenerror', "不正なリクエストです、再度ログインをお試しください"); //フラッシュメッセージ用、完成後に削除。
        header('Location: ../Views/400error.php');
        exit;
      } else {
        unset($_SESSION['token']);
      }
    }
    $in = [];
    $params= array();
    if(!empty($_POST) && is_array($_POST)) { $params += $_POST; };
    if(!empty($_GET) && is_array($_GET)) {$params += $_GET; };

    foreach($params as $key => $val) {
      $enc = mb_detect_encoding($val);
      $val = mb_convert_encoding($val, 'UTF-8', $enc);
      $val = htmlentities($val, ENT_QUOTES, 'UTF-8');
      $in[$key] = $val;
    }
    return $in;
  }
}