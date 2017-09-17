<?php
namespace Home\Controller;
use Think\Controller;

//http://127.0.0.1/gd/framework/thinkphp/index.php/Home/Alipay
//http://115.159.22.89/tpdemo/index.php/Home/Alipay
class AlipayController extends Controller {
    private $appid = "2016080800193517";
    private $privateKey = "MIIEpAIBAAKCAQEAvB4aTjYScfzxgkB1q81+LIO0FVf/RDXia+giE48vg1zaanXUnNba4swNi9jipAOWcNmugEgE6zJdUD0aOyTKp5ti63IrYkB1MTVg8oazF1K+jc8r3IIg/0qWoVAXo7RVgQ/oEGkONs93oY/n30eINXXApBu7adcKjZ769wPfzV7VsK01VLNRq2CKCYR+nL7EAYBsQzTh7IOVFQfUiyZKwjppMGa27gnLcNiXPrxES8G7eMVoxzg4EbDa+5MsXU6zrOBx9/38wZBKUDrbs6nbVH2McBF72ub8E/suNStnvkFmN5vJt8MEdgA/ONxJOCNgxLnhRnr85EBdJYdt98rGIwIDAQABAoIBAAL/4+F9YTvqEZvHFVlA9JMXIob4mTxoM40O7YQsU1Cf6WerK1G451KYeFrTgYGmAP8ZqXuoKlPpwK0n3EQ3KPgVNTWV43U0m+nF9R7I4RAtJJa0R/nDJW2Mrewyj73vgTGGpGemlTBwjHLKDaN0y4o2r3SucIWWhTeO4meP60CmDHKdNNnDjUjGYHRdWoujc9KodM/ySrJ2/x7P1B+8Rc6Hm1iBV/ivhWfqcMGVPMwdN3u7gSA9GakNggRbYx00LWGQbt/WBYxl/90RwIHGxWLIKfs+0bqTHRrJvYGFhyqOJbaXQJ3MGD1W985of6gBAw57wIGtbv4fpBT3hKcy8AkCgYEA7jQozFSgVVBBgHd2xCENLRZ/k8BhgxA9kA1o6xzHnrDkfQtHEMBUAncE2p2amkXdTd6yASyFOJhvAmSFbgtphkXzB92IKmCvkXaTgH+GEh/x8s5ZAhekenR+BQyJePuNcVrXU7+4NLtki3GZPgAn12RLFMHY9gKUQx5EceiRH+0CgYEAyiwBO+6VDmdz/OUlwhRQd0n8UipdImk1tTbp4+dKc4bSpVHJDklFkEeFq3mxIIFbVxOWQbIpWJuDPNGBAwNudzQElGsVNQA5GCEJNJzr5EMb8vKcjf/OthJrsglEbaNE6qM02qRUyQIXImpXXG13+DrA3kvQKcbQjYIXt4kUHE8CgYEAnqyQIMyeoTX87B6oNq3toQNDqKCWVEAVQkgsg7GAJ+2dxs4kYLw9OgcebKJfwjSX3q9h0/ZcJ/8is21SlKN1f8RIyAyCD/in+zYJu6c3IAu3mA5srEAjiI7hA2v+h2DKL6Bbn6YuhrHtoBXkBrED2S8t9H3MkNPW/37CCT7qrWECgYEAh4IdRaVxpRj0ZmLU/xQMpxghFpWsnT71r8Ph208Q2QRaNlLuNzQne0BlOP01Gvt5VJEdWmVnTyvVODOYjIOFTELCexSXP1Ip8qFaATjY58OPfTTeeFMoo2MPamLJyc7wh3DjIVWaOqy7AudSLPJ2whvnYFhia04GRYMYEH3BzAMCgYBC5worloe1H/V364a6lMWUldO+mxk26EXtKIIMnnpG/BJxoVofbgZMMX1chnRhPsLUZnFhVNo6vrk2it/bE0PGVxtIyO5qYrn/Be+Bn+OUEO7jIL3MQ6iOoHf/favJxsR9mI65xC9ZlQxq8truhX69Z6U+zN6nnQ7R8BHbNW5Wtw==";
    private $alipayPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAw7D+CxtfL2a7Vj5r+Q9hyxt3sczZGPo5vt78TZKu5gde5wMiX+v/cxDlR7OYpOtKzMS6tqVIg/pJ4vYfX7eXwRhhiatH0e8UbHSPWMWV7zam8YHtSTwLkI+/gjPbvGxuk3dNGWM3AIqzq2wl06mmVz2W37cRhl9oKc9jLzMIesYFXp9hurU+uKPq4ewZlcsUmYTtinq8q0BomCiZKjNt3EZfQ3JT7GfUgCGhCegLAO4IYFBvhxNr94Ex5CrKh0CyqTKWFdSnzobNCBX4ea9ifLn3YT2W1InEzRu+HqSm4Zmzr9N7IsigpMQAIaN6ohTkMWZLtZ9IAdVE30Hoozc70wIDAQAB";
    private $charset = "UTF-8";
    private $signType = "RSA2";
    private $gatewayUrl = "https://openapi.alipaydev.com/gateway.do";
    private $returnUrl = "http://115.159.22.89/huli2/index.php?g=user&m=alipay&a=topup";
    private $notifyUrl = "http://115.159.22.89/huli2/index.php?g=user&m=alipay&a=topupNotify";
    
    private $aop;//AopClient对象

    public function _initialize(){
        Vendor('Alipay.AopClient');
        $aop = new \AopClient();
        $aop->gatewayUrl = $this->gatewayUrl;
        $aop->appId = $this->appid;
        $aop->rsaPrivateKey = $this->privateKey;
        $aop->apiVersion = '1.0';
        $aop->signType = $this->signType;
        $aop->postCharset= $this->charset;
        $aop->format = 'json';
        $this->aop=$aop;
    }
    private function createTradeRecord($trade_no,$name,$total,$description){
        $trade=M("Trade");
        $trade->create([
            "trade_no"=>$trade_no,
            "name"=>$name,
            "total"=>$total,
            "description"=>$description
        ]);
        $trade->add();
    }
    public function pagepay(){
        $this->display();
    }
    public function doPagepay(){
        $out_trade_no=I("out_trade_no","");
        $subject=I("subject","");
        $total_amount=I("total_amount","");
        $body=I("body","");

        $this->createTradeRecord($out_trade_no,$subject,$total_amount,$body);

        $content=[
            "product_code"=>"FAST_INSTANT_TRADE_PAY",
            "out_trade_no"=>$out_trade_no,
            "subject"=>$subject,
            "total_amount"=>$total_amount,
            "body"=>$body
        ];

        Vendor('Alipay.request.AlipayTradePagePayRequest');
        $request = new \AlipayTradePagePayRequest();
        $request->setReturnUrl($this->returnUrl);
        $request->setNotifyUrl($this->notifyUrl);
        $request->setBizContent(json_encode($content));
        
        //请求
        $result = $this->aop->pageExecute($request);
        
        //输出
        echo $result;
    }
    //同步回调
    public function returnPage(){
        $get=I("get");
        $aop = new AopClient();
		$aop->alipayrsaPublicKey = $this->alipay_public_key;
		$result = $aop->rsaCheckV1($get, $this->alipay_public_key, $this->signtype);
        echo "result:".$result;
		//return $result;
    }
    //异步回调
    public function notify(){
        
    }

    public function index(){
        $this->show('AlipayController','utf-8');
    }
    public function page(){
        $this->display();
    }
}