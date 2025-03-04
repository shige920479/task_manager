<?php
namespace App\Database;

use App\Database\DbConnect;
use function App\Services\writeLog;


class DateUpdate extends DbConnect
{
  /**
   * タスクの日付自動更新用の実行ファイル
   * xserverのcronを使い毎月曜に1週間プラス && logファイルへの履歴書き込み
   * 
   * @param void
   * @return void
   */
  public static function taskDateUpdate():void
  {
      date_default_timezone_set('Asia/Tokyo');
      $counter = 1; 
      $batch_datetime = date('Y/m/d H:i:s');
      $fh = fopen(__DIR__ .'/../../log/batch.log', 'a');
      fwrite($fh, "{$batch_datetime} : {$counter}回目のログ\n");
      fclose($fh);
      $counter += 1;
      exit;


    // try {
    //   $pdo = self::db_connect();
    //   $sql = "UPDATE task SET deadline = DATE_ADD(deadline, INTERVAL 7 DAY);";
    //   $pdo->query($sql);

    //   date_default_timezone_set('Asia/Tokyo'); 
    //   $batch_datetime = date('Y/m/d H:i:s');
    //   $fh = fopen(__DIR__ .'/../../log/batch.log', 'a');
    //   fwrite($fh, "{$batch_datetime} : taskデータのdeadline日付を1週間更新しました\n");
    //   fclose($fh);
    //   exit;

    // } catch(\PDOException $e) {
    //   writeLog(LOG_FILEPATH, $e->getMessage());
    //   exit;
      
    // } finally {
    //   $pdo = null;
    // }
  }
}