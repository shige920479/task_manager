<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>不正なリクエスト</title>
  </head>
  <style>
    .e400-wrapper {
      width: 1000px;
      margin: 80px auto;
      text-align: center;
    }
  </style>
  <body>
    <div class="e400-wrapper">
      <div>
        <h1><span>Error</span>400</h1>
        <p>Bad Request</p>
        <p><?php echo isset($flash_array['tokenerror']) ? $flash_array['tokenerror'] : ''; ?></p>
      </div>
      <div>
        <img src="images/exclamation-triangle-fill.svg" alt="" style="width: 80px">
      </div>
    </div>
  </body>
</html>
