<?php
session_start();
require_once '../../vendor/autoload.php';
use App\Database\DeleteTask;
use App\Services\GetForm;

$in = GetForm::getForm();
$id = $in['id']; //宿題：GetFormクラスにidだけ取ってくるfunctionを作るか。意味ないか？？
if($in['mode'] === 'soft_del') {
  DeleteTask::softDelete($id);
} elseif($in['mode'] === 'hard_del') {
  DeleteTask::hardDelete($id);
}

