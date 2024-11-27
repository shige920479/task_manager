<?php

require_once '../../vendor/autoload.php';
use App\Database\DbConnect;
use App\Services\GetForm;

use function PHPSTORM_META\type;

session_start();
$in = GetForm::getForm();
$tasks = DbConnect::getTaskData($in);

// echo count($tasks); //77件

// echo "<pre>";
// var_dump($in);
// echo "</pre>";
// exit;

// $current_page = $in['page'];
  paginate($tasks, $in['page']);

function paginate($tasks, $current_page)
{
  $max_per_page = 10;
  $total_num = count($tasks); #77
  $total_page = intval(ceil($total_num / $max_per_page)); #8
  $start_num = $current_page * $max_per_page - $max_per_page; #70
  if($total_page === intval($current_page)) {
    $end_num = $total_num;
  } else {
    $end_num = $current_page * $max_per_page;
  }

  $data_array = array();
  $page_html = "";

  // if(empty($in['page'])) {
  if(empty($current_page)) {
    for($i = 0; $i < $max_per_page; $i++ ) {
      array_push($data_array, $tasks[$i]);
    } 
    for($i = 1; $i <= $total_page ; $i++) {
      if($i === 1) {
        $page_html .= "<span>{$i}</span>";
      } else {
        $page_html .= "<a href='./paginate_test.php?page={$i}'><span>{$i}</span></a>";
      }
    }
  } else {
    for($i = $start_num; $i < $end_num; $i++ ) {
      array_push($data_array, $tasks[$i]);
    } 
    for($i = 1; $i <= $total_page ; $i++) {
      if($i === intval($current_page)) {
        $page_html .= "<span style='margin-right:5px;'>{$i}</span>";
      } else {
        $page_html .= "<a href='./paginate_test.php?page={$i}'><span style='margin-right:5px;'>{$i}</span></a>";
      }
    }
  }
  $data_list = [$tasks, $page_html];
  return $data_list;
}



/**
 * 初期画面でpramがない場合
 * ⇒ 1~max_per のデータは取得
 * ⇒ 1～total_pageまでのhtmlを作成 1以外にaタグをつける
 * Getでparamがある場合
 * $max_page * param + 1を頭にfor文で10件分のデータを取り出し
 * ⇒ 2～total_pageまでのhtmlを作成、param以外のページにaタグをつける
 */


?>
