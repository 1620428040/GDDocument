<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo U("doPagepay");?>" method="post" target="_blank">
        <div>
            <label for="out_trade_no">商户订单号：</label>
            <input type="text" name="out_trade_no" id="out_trade_no">
        </div>
        
        <div>
            <label for="subject">订单名称：</label>
            <input type="text" name="subject" id="subject">
        </div>

        <div>
            <label for="total_amount">付款金额：</label>
            <input type="text" name="total_amount" id="total_amount">
        </div>

        <div>
            <label for="body">商品描述：</label>
            <input type="text" name="body" id="body">
        </div>

        <div>
            <input type="submit" value="付款">
        </div>

    </form>
    <script>
        function GetDateNow() {
            var vNow = new Date();
            var sNow = "";
            sNow += String(vNow.getFullYear());
            sNow += String(vNow.getMonth() + 1);
            sNow += String(vNow.getDate());
            sNow += String(vNow.getHours());
            sNow += String(vNow.getMinutes());
            sNow += String(vNow.getSeconds());
            sNow += String(vNow.getMilliseconds());
            document.getElementById("out_trade_no").value =  sNow;
            document.getElementById("subject").value = "测试";
            document.getElementById("total_amount").value = "0.01";
            document.getElementById("body").value = "页面支付接口demo";
        }
        GetDateNow();
    </script>
</body>
</html>