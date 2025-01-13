<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>サーバーエラー</title>
    <style>
      .e500-wrapper {
        width: 1000px;
        margin: 80px auto;
        text-align: center;
      }
    </style>
  </head>
  <body>
    <div class="e500-wrapper">
      <div>
        <h1><span>Error</span>500</h1>
        <p>Internal Server Error</p>
        <p><?php echo isset($flash_array['db']) ? $flash_array['db'] : ''; ?></p>
      </div>
      <div>
        <img src="<?php echo PATH . 'images/database-exclamation.svg';?>" alt="" style="width: 80px">
      </div>
    </div>
  </body>
</html>
