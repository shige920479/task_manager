<?php
session_start();
require_once '../../vendor/autoload.php';

use App\Database\Message;
use App\Services\GetForm;

$in = GetForm::getForm();
// echo "<pre>";
// var_dump($in);
// echo "</pre>";
// exit;
Message::sendMessage($in);