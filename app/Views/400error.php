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
      <p><?php echo isset($flash_array['tokenerror']) ? $flash_array['tokenerror'] : ''; ?></p>
    </div>
    <div>
      <img
        src="/task_manager/images/exclamation-triangle-fill.svg"
        alt=""
        style="width: 80px"
      />
    </div>
  </body>
</html>
