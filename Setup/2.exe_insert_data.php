<?php
require_once '../app/config/config.php';
require_once './resource/Database.php';
require_once './resource/InsertManagerData.php';
require_once './resource/InsertMembersData.php';
require_once './resource/InsertTaskData.php';
require_once './resource/InsertMessageData.php';

use Setup\Resource\Database;
use Setup\Resource\InsertManagerData;
use Setup\Resource\InsertMembersData;
use Setup\Resource\InsertMessageData;
use Setup\Resource\InsertTaskData;

InsertManagerData::insertManager();
InsertMembersData::insertMembers();
InsertTaskData::insertTasks();
InsertMessageData::insertMessage();
