<?php
require_once '../../vendor/autoload.php';

use App\Database\StoreTask;
use App\Services\GetForm;

session_start();

$in = GetForm::getForm();
StoreTask::storeTask($in);
exit;

