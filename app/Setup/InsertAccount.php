<?php
require_once '../../vendor/autoload.php';
use App\Setup\CreateData;

/**
 * manager生成
 */
// $manager_pass = 'admin';
// $manager_pass = password_hash($manager_pass, PASSWORD_BCRYPT);
// CreateData::insertManager($manager_pass);

/**
 * manager生成
 */
$member_pass = 'member';
$member_pass = password_hash($member_pass, PASSWORD_BCRYPT);
$members = [
  [
    'name' => 'member111',
    'email' => 'member111@mail.com',
    'password' => $member_pass
  ],
  [
    'name' => 'member222',
    'email' => 'member222@mail.com',
    'password' => $member_pass
  ],
  [
    'name' => 'member333',
    'email' => 'member333@mail.com',
    'password' => $member_pass
  ],
  [
    'name' => 'member444',
    'email' => 'member444@mail.com',
    'password' => $member_pass
  ],
  [
    'name' => 'member555',
    'email' => 'member555@mail.com',
    'password' => $member_pass
  ]  
];

CreateData::insertMember($members);

