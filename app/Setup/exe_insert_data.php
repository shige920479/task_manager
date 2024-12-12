<?php
require_once '../../vendor/autoload.php';
require_once '../config/config.php';

use App\Setup\InsertManagerData;
use App\Setup\InsertMembersData;
use App\Setup\InsertMessageData;
use App\Setup\InsertTaskData;

InsertManagerData::insertManager();
InsertMembersData::insertMembers();
InsertTaskData::insertTasks();
// InsertMessageData::insertMessage();
