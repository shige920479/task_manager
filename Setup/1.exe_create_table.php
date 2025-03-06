<?php
require_once '../app/config/config.php';
require_once './resource/Database.php';
require_once './resource/CreateTable.php';

use Setup\Resource\CreateTable;

CreateTable::memberTable();
CreateTable::managerTable();
CreateTable::taskTable();
CreateTable::messageTable();