<?php
namespace App\Setup;

class InsertManagerData extends Database
{
  public static function insertManager()
  {
    $manager_pass = 'manager123';
    $manager_pass = password_hash($manager_pass, PASSWORD_BCRYPT);

    try {
      $pdo = self::dbCon();
  
      $sql = "ALTER TABLE member auto_increment = 1";
      $pdo->query($sql);

      $sql = "INSERT INTO manager
              (name, email, password)
              VALUES
              ('manager', 'manager@mail.com', :password)
              ";
  
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':password', $manager_pass, \PDO::PARAM_STR);
      $stmt->execute();

      list($pdo, $stmt) = [null, null];
      echo "managerデータの挿入に成功しました<br>";

    } catch(\PDOException $e) {
      echo "managerデータの挿入に失敗しました<br>";
      echo $e->getMessage();
      exit;
    }
  }
}