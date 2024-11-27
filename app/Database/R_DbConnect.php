<?php
namespace App\Database;
require_once '../config/config.php'; // ★★最終的に外せるか確認

use PDO;

class Dbcon
{
  private static $pdo;

  private function __construct()
   {
  }

  private static function getInstance():PDO
  {
    if(!isset($pdo)) {
      self::$pdo = new PDO(DSN, USERNAME, PASSWORD);
      self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    return self::$pdo;
  }

  public static function fetch(string $sql, array $param = []): array
  {
    $stmt = self::getInstance()->prepare($sql);
    $stmt->execute($param);
    $result = $stmt->fetch();
    return $result;
  }
  public static function fetchAll(string $sql, array $param = []): array
  {
    $stmt = self::getInstance()->prepare($sql);
    $stmt->execute($param);
    $result = $stmt->fetchAll();
    return $result;
  }
  public static function execute(string $sql, array $param = []): bool
  {
    $stmt = self::getInstance()->prepare($sql);
    return $stmt->execute($param);
  }
}