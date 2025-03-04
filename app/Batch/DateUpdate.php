<?php

require_once './app/config/config.php';
require_once './vendor/autoload.php';
require_once './app/Services/helper.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

use App\Database\DateUpdate;

DateUpdate::taskDateUpdate();

exit;
?>