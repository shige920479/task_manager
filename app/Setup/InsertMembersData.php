<?php
namespace App\Setup;

class InsertMembersData extends Database
{
  public static function insertMembers()
  {
    $member_pass = 'member123';
    $member_pass = password_hash($member_pass, PASSWORD_BCRYPT);
    $members = [
      [
        'name' => 'member1',
        'email' => 'member1@mail.com',
        'password' => $member_pass
      ],
      [
        'name' => 'member2',
        'email' => 'member2@mail.com',
        'password' => $member_pass
      ],
      [
        'name' => 'member3',
        'email' => 'member3@mail.com',
        'password' => $member_pass
      ],
      [
        'name' => 'member4',
        'email' => 'member4@mail.com',
        'password' => $member_pass
      ],
      [
        'name' => 'member5',
        'email' => 'member5@mail.com',
        'password' => $member_pass
      ]  
    ];

    try {
      $pdo = self::dbCon();

      $sql = "ALTER TABLE member auto_increment = 1";
      $pdo->query($sql);
  
      $sql = "INSERT INTO member
      (name, email, password)
      VALUES
      (:name, :email, :password)
      ";
  
      foreach($members as $member) {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $member['name'], \PDO::PARAM_STR);
        $stmt->bindValue(':email', $member['email'], \PDO::PARAM_STR);
        $stmt->bindValue(':password', $member['password'], \PDO::PARAM_STR);
        $stmt->execute();
      }
      list($pdo, $stmt) = [null, null];
      echo "memberデータの挿入に成功しました<br>";

    } catch(\PDOException $e) {
      echo "memberデータの挿入に失敗しました<br>";
      echo $e->getMessage();
      exit;
    }
  }
}