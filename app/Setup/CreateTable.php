<?php
namespace App\Setup;

// require_once '../../vendor/autoload.php';
use App\Database\DbConnect;

class CreateTable extends DbConnect
{
  public static function memberTable(): void
   {
    try {
      $pdo = self::db_connect();
      $sql = "DROP TABLE IF EXISTS member";
      $pdo->query($sql);

      $sql = "CREATE table member (
              id INT PRIMARY KEY AUTO_INCREMENT,
              name VARCHAR(50) NOT NULL,
              email VARCHAR(50) NOT NULL,
              password VARCHAR(255) NOT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
              )";
      $pdo->query($sql);

      $pdo = null;
      echo 'memberテーブルを作成しました<br>';

     } catch(\PDOException $e) {
      echo "memberテーブルの作成に失敗しました<br>";
      echo $e->getMessage();
      exit;
     }
  }
  public static function managerTable(): void
  {
   try {
     $pdo = self::db_connect();
     $sql = "DROP TABLE IF EXISTS manager";
     $pdo->query($sql);

     $sql = "CREATE table manager (
             id INT PRIMARY KEY AUTO_INCREMENT,
             name VARCHAR(50) NOT NULL,
             email VARCHAR(50) NOT NULL,
             password VARCHAR(255) NOT NULL,
             created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
             updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
             )";
     $pdo->query($sql);

     $pdo = null;
     echo 'managerテーブルを作成しました<br>';

    } catch(\PDOException $e) {
     echo "managerテーブルの作成に失敗しました<br>";
     echo $e->getMessage();
     exit;
    }
 }

  public static function taskTable(): void
   {
     try {
      $pdo = self::db_connect();
      $sql = "DROP TABLE IF EXISTS task";
      $pdo->query($sql);

      $sql = "CREATE table task (
              id INT PRIMARY KEY AUTO_INCREMENT,
              member_id INT NOT NULL,
              priority INT NOT NULL,
              category VARCHAR(50) NOT NULL,
              theme VARCHAR(50) NOT NULL ,
              content VARCHAR(255) NOT NULL,
              deadline DATE NOT NULL,
              msg_flag INT DEFAULT 0,
              mg_to_mem INT DEFAULT 0,
              mem_to_mg INT DEFAULT 0,
              del_flag INT DEFAULT 0,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              FOREIGN KEY (member_id) REFERENCES member(id) ON DELETE CASCADE
              )";
      $pdo->query($sql);

      $pdo = null;
      echo 'taskテーブルを作成しました<br>';

     } catch(\PDOException $e) {
      echo "taskテーブルの作成に失敗しました<br>";
      echo $e->getMessage();
      exit;
     }
  }
  public static function messageTable(): void
  {
    try {
     $pdo = self::db_connect();
     $sql = "DROP TABLE IF EXISTS message";
     $pdo->query($sql);

     $sql = "CREATE table message (
             id INT PRIMARY KEY AUTO_INCREMENT,
             task_id INT NOT NULL,
             comment VARCHAR(255) NOT NULL,
             sender INT DEFAULT 0,
             created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
             updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
             FOREIGN KEY (task_id) REFERENCES task(id) ON DELETE CASCADE
             )";
     $pdo->query($sql);

     $pdo = null;
     echo 'messageテーブルを作成しました<br>';

    } catch(\PDOException $e) {
     echo "messageテーブルの作成に失敗しました<br>";
     echo $e->getMessage();
     exit;
    }
 }

}