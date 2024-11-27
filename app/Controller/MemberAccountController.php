<?php

require_once "../../vendor/autoload.php";

use App\Database\StoreMemberAccount;
use App\Services\GetForm;

session_start();
$in = GetForm::getForm();
StoreMemberAccount::memberRegister($in);
// echo "<pre>";
// var_dump($in);
// echo "</pre>";