<?php
require_once '../../vendor/autoload.php';
require_once '../config/config.php';


use App\Database\Login;
use App\Services\GetForm;

session_start();
$in = GetForm::getForm();

Login::login($in, MEMBER);
