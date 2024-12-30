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
      <p><?php echo isset($flash_array['db']) ? $flash_array['db'] : ''; ?></p>
    </div>
    <div>
      <img
        src="/task_manager/images/database-exclamation.svg"
        alt=""
        style="width: 80px"
      />
    </div>
  </body>
</html>
