<?php
require_once '../Services/helper.php';
use function App\Services\flash;

session_start();
$flash_msg = flash($_SESSION['error']);
echo $flash_msg['tokenerror'];
$_SESSION = array();
setcookie(session_name(), '', time()-1, '/');
session_destroy();
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
      <h1><span>Error</span>400</h1>
      <p>Bad Request</p>
    </div>
    <div>
      <img
        src="../../images/exclamation-triangle-fill.svg"
        alt=""
        style="width: 80px"
      />
    </div>
  </body>
</html>
