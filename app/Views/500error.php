<?php
/////////// 完了後に削除/////////////////
require_once '../Services/helper.php';
use function App\Services\flash;
session_start();
$flash_msg = flash($_SESSION['error']);
unset($_SESSION['error']);
echo $flash_msg['db'];
////////////////////////////////////////

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <div>
      <h1><span>Error</span>500</h1>
      <p>Internal Server Error</p>
    </div>
    <div>
      <img
        src="../../images/database-exclamation.svg"
        alt=""
        style="width: 80px"
      />
    </div>
  </body>
</html>
