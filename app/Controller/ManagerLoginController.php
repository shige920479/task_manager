<?php
session_start();

require_once '../../vendor/autoload.php';
require_once '../config/config.php';

use App\Database\Login;
use App\Services\GetForm;

$in = GetForm::getForm();

// echo "<pre>";
// var_dump($in);
// echo "</pre>";
// exit;


Login::login($in, MANAGER); // 別途、'manager'に戻す（テーブル修正後）
