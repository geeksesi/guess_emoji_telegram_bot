
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>ربات حدس ایموجی - وضعیت پرداخت</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='/assets/style.css'>
</head>
<body style="direction: rtl;">
        
        <h1 style="text-align: center;"><?= $data["title"] ?? "پرداخت نامعتبر" ?></h1>
        <div style="width: 300px; margin: auto; text-align: justify;">
            <small style="color: #222"><?= $data["error"] ?? "" ?></small>
        </div>
        <br>
        <br>
        <center>
            <a style="text-align: center; color:red; padding: 10px; border: 1px solid #efefef; text-decoration:none; font-size:larger" 
            href='<?php echo $_ENV["BOT_LINK"]; ?>'>
            بازگشت به ربات
            </a>
        </center>


    <script src='/assets/index.js'></script>
</body>
</html>