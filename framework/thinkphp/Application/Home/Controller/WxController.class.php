<?php
namespace Home\Controller;
use Think\Controller;

class WxController extends Controller {
    private $appid="wxd07db81313cd477b";
    private $secret="4105ab810af7951e6188dbaae327c547";
    public function login(){
        $code=I("code","");
        if($code===""){
            $this->ajaxReturn(["code"=>-1,"info"=>"code不能为空！"]);
        }
        $result=$this->curlGet("https://api.weixin.qq.com/sns/jscode2session",[
            "appid"=>$appid,
            "secret"=>$secret,
            "js_code"=>$code,
            "grant_type"=>"authorization_code"
        ]);
        if(!$result){
            $this->ajaxReturn(["code"=>-2,"info"=>"网络请求错误"]);
        }
        $data=json_decode($result,true);
        if(isset($data["openid"])){
            $data["time"]=time();
            session("wxUserInfo",$data);
            $this->ajaxReturn(["code"=>0,"info"=>"登陆成功","key"=>session_id()]);
        }
        else{
            $data["code"]=-3;
            $data["info"]="请检查参数是否错误";
            $this->ajaxReturn($data);
        }
    }
    public function addToCart(){
        $info=$this->readUserInfo();
        //TODO:利用$info中的openid作为用户的标记，将订单添加到购物车
        $this->ajaxReturn($info);
    }
    public function pay(){
        //TODO:结算购物车中的商品价格
        $total;//总价
        


    }
    //生成支付签名
    private function sign(){

    }
    //以get方式发送curl请求
    private function curlGet($url,$data){
        $url.= (strpos($url,"?")===false ? "?" : "&") .http_build_query($data);
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    //读取登陆过的用户的信息
    private function readUserInfo(){
        $key=I("key","");
        if($key===""){
            $this->ajaxReturn(["code"=>-1,"info"=>"key不能为空！"]);
        }
        //使用给定的key重置session
        session_commit();
        $_SESSION=null;
        session_id($key);
        session_start();
        $info=I("session.wxUserInfo","");
        if($info===""){
            $this->ajaxReturn(["code"=>-2,"info"=>"请重新登录"]);
        }
        return $info;
    }
    //生成虚拟的用户的session，用来进行测试
    public function dummyInfo(){
        $data=[
            "session_key"=>"uYsuDzHCEFahUvMrObPZXA==",
            "expires_in"=>7200,
            "openid"=>"o6GUY0aOADcLV71C6E5Q32NvjZKc",
            "time"=>time()
        ];
        session("wxUserInfo",$data);
        $this->ajaxReturn(["code"=>0,"info"=>"成功","key"=>session_id()]);
    }
}