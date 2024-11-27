<?php
require_once '../../vendor/autoload.php';
require_once '../Services/GetForm.php';

use App\Database\Logout;
use App\Services\GetForm;

session_start();

$in = GetForm::getForm();
Logout::logout($in);