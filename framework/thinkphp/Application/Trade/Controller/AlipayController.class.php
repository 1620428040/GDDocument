<?php
namespace Trade\Controller;
use Think\Controller;
//http://115.159.22.89/huli3/index.php?g=User&m=Alipay&a=index

//http://127.0.0.1/gd/framework/thinkphp/index.php?m=Home&c=Alipay
//http://115.159.22.89/tpdemo/index.php?m=Home&c=Alipay
class AlipayController extends Controller {
    public function getTradeObject($tradeType){
        if(!$tradeType){
            $tradeType=I("tradeType","topup");
        }
        
        if($tradeType === "topup"){
            return D("Topup");
        }
        elseif($tradeType === "noble"){
            return D("UserNoble");
        }
        else{
            return false;
        }
    }
    //接受电脑网页的交易请求并跳转到支付页面
    public function doPagepay(){
        $model=D("Alipay");
        $trade=$this->getTradeObject();
        $content=$model->createTrade($trade,$_POST);
        if($content===false){
            echo $model->info;
        }
        else{
            echo $model->pagepay($content);
        }
    }
    //接受手机APP的交易请求并且声称签名
    function signature(){
        $model=D("Alipay");
        $trade=$this->getTradeObject();
        $content=$model->createTrade($trade,$_POST);
        if($content===false){
            echo $model->info;
        }
        else{
            echo $model->signature($content);
        }
    }
    //同步回调，验证交易信息，并显示交易成功页面
    public function returnPage(){
        $model=D("Alipay");
        $result = $model->updateTrade($_GET,"return",$this);
        $model -> log (["result"=>$result,"info"=>$model->info],"return.result");
        echo $model->info;
    }
    //异步回调，验证交易信息，并将结果返回给支付宝服务器
    public function notify(){
        $model=D("Alipay");
        $result = $model->updateTrade($_POST,"notify",$this);
        $model -> log (["result"=>$result,"info"=>$model->info],"notify.result");
        echo $result ? "success" : "fail";
    }

    //电脑网页交易查询页面
    public function query(){
        $this->display();
    }
    //查询接口
    public function doQuery(){
        $out_trade_no=I("out_trade_no","");
        $trade_no=I("trade_no","");

        $record=D("Alipay")->query($out_trade_no,$trade_no);
        echo json_encode($record,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}