<?php
require_once '../../vendor/autoload.php';
require_once '../config/config.php';

use App\Setup\CreateTable;

CreateTable::memberTable();
CreateTable::managerTable();
CreateTable::taskTable();
CreateTable::messageTable();