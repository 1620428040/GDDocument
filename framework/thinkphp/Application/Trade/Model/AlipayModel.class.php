<?php
namespace Trade\Model;
use Think\Model;

//所有处理实际的交易的类，应该实现此接口
interface Trade{
    //public $type;//声明交易类型
    //预处理交易，将交易数据保存到数据库或返回，等待付款完成。正常返回交易数据，异常返回字符串
    public function pre($params);
    //确认交易，用户付款完成后将交易数据写入实际的交易数据库。正常返回true，异常返回字符串
    public function confirm($record);
}

class AlipayModel extends Model{
    private $appid = "2017032106325798";
    private $gatewayUrl = "https://openapi.alipay.com/gateway.do";
    private $privateKey = "MIIEpAIBAAKCAQEAvB4aTjYScfzxgkB1q81+LIO0FVf/RDXia+giE48vg1zaanXUnNba4swNi9jipAOWcNmugEgE6zJdUD0aOyTKp5ti63IrYkB1MTVg8oazF1K+jc8r3IIg/0qWoVAXo7RVgQ/oEGkONs93oY/n30eINXXApBu7adcKjZ769wPfzV7VsK01VLNRq2CKCYR+nL7EAYBsQzTh7IOVFQfUiyZKwjppMGa27gnLcNiXPrxES8G7eMVoxzg4EbDa+5MsXU6zrOBx9/38wZBKUDrbs6nbVH2McBF72ub8E/suNStnvkFmN5vJt8MEdgA/ONxJOCNgxLnhRnr85EBdJYdt98rGIwIDAQABAoIBAAL/4+F9YTvqEZvHFVlA9JMXIob4mTxoM40O7YQsU1Cf6WerK1G451KYeFrTgYGmAP8ZqXuoKlPpwK0n3EQ3KPgVNTWV43U0m+nF9R7I4RAtJJa0R/nDJW2Mrewyj73vgTGGpGemlTBwjHLKDaN0y4o2r3SucIWWhTeO4meP60CmDHKdNNnDjUjGYHRdWoujc9KodM/ySrJ2/x7P1B+8Rc6Hm1iBV/ivhWfqcMGVPMwdN3u7gSA9GakNggRbYx00LWGQbt/WBYxl/90RwIHGxWLIKfs+0bqTHRrJvYGFhyqOJbaXQJ3MGD1W985of6gBAw57wIGtbv4fpBT3hKcy8AkCgYEA7jQozFSgVVBBgHd2xCENLRZ/k8BhgxA9kA1o6xzHnrDkfQtHEMBUAncE2p2amkXdTd6yASyFOJhvAmSFbgtphkXzB92IKmCvkXaTgH+GEh/x8s5ZAhekenR+BQyJePuNcVrXU7+4NLtki3GZPgAn12RLFMHY9gKUQx5EceiRH+0CgYEAyiwBO+6VDmdz/OUlwhRQd0n8UipdImk1tTbp4+dKc4bSpVHJDklFkEeFq3mxIIFbVxOWQbIpWJuDPNGBAwNudzQElGsVNQA5GCEJNJzr5EMb8vKcjf/OthJrsglEbaNE6qM02qRUyQIXImpXXG13+DrA3kvQKcbQjYIXt4kUHE8CgYEAnqyQIMyeoTX87B6oNq3toQNDqKCWVEAVQkgsg7GAJ+2dxs4kYLw9OgcebKJfwjSX3q9h0/ZcJ/8is21SlKN1f8RIyAyCD/in+zYJu6c3IAu3mA5srEAjiI7hA2v+h2DKL6Bbn6YuhrHtoBXkBrED2S8t9H3MkNPW/37CCT7qrWECgYEAh4IdRaVxpRj0ZmLU/xQMpxghFpWsnT71r8Ph208Q2QRaNlLuNzQne0BlOP01Gvt5VJEdWmVnTyvVODOYjIOFTELCexSXP1Ip8qFaATjY58OPfTTeeFMoo2MPamLJyc7wh3DjIVWaOqy7AudSLPJ2whvnYFhia04GRYMYEH3BzAMCgYBC5worloe1H/V364a6lMWUldO+mxk26EXtKIIMnnpG/BJxoVofbgZMMX1chnRhPsLUZnFhVNo6vrk2it/bE0PGVxtIyO5qYrn/Be+Bn+OUEO7jIL3MQ6iOoHf/favJxsR9mI65xC9ZlQxq8truhX69Z6U+zN6nnQ7R8BHbNW5Wtw==";
    private $alipayPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsW2DLrC1lR3zcPXKw1cvEujwrtKsVi7EL42tml3ykIYOuC6kvJyNzmgF9hzKqA3sHCqtiI4OrWZ4hIbch1hSaIg4kTxnu5OVeHS7BKfaGY5iIcIWQFniMf/dVuSK65nsUHnRWissf8qv4vwuBmahy77WzxxhdQH5Dee8K97PKhR7YlzuxuanIOYqpSiraM9dRoOwc5ZEcMnLRsmxCIPjtnCD9ZgA3ZlwDXGQ9KhZ8z9Uag7Svnqv/bUIgTA3n7oO4IDYwMuHu7ufC6xYcm0UXKgvnVpGKQcRt2YFvMnS/R4omnCTO0K6B+nXwpCONt5HKNBAnB/0JBlGfi75cqxZMQIDAQAB";
    
    // private $appid = "2016080800193517";
    // private $gatewayUrl = "https://openapi.alipaydev.com/gateway.do";
    // private $privateKey = "MIIEpAIBAAKCAQEAvB4aTjYScfzxgkB1q81+LIO0FVf/RDXia+giE48vg1zaanXUnNba4swNi9jipAOWcNmugEgE6zJdUD0aOyTKp5ti63IrYkB1MTVg8oazF1K+jc8r3IIg/0qWoVAXo7RVgQ/oEGkONs93oY/n30eINXXApBu7adcKjZ769wPfzV7VsK01VLNRq2CKCYR+nL7EAYBsQzTh7IOVFQfUiyZKwjppMGa27gnLcNiXPrxES8G7eMVoxzg4EbDa+5MsXU6zrOBx9/38wZBKUDrbs6nbVH2McBF72ub8E/suNStnvkFmN5vJt8MEdgA/ONxJOCNgxLnhRnr85EBdJYdt98rGIwIDAQABAoIBAAL/4+F9YTvqEZvHFVlA9JMXIob4mTxoM40O7YQsU1Cf6WerK1G451KYeFrTgYGmAP8ZqXuoKlPpwK0n3EQ3KPgVNTWV43U0m+nF9R7I4RAtJJa0R/nDJW2Mrewyj73vgTGGpGemlTBwjHLKDaN0y4o2r3SucIWWhTeO4meP60CmDHKdNNnDjUjGYHRdWoujc9KodM/ySrJ2/x7P1B+8Rc6Hm1iBV/ivhWfqcMGVPMwdN3u7gSA9GakNggRbYx00LWGQbt/WBYxl/90RwIHGxWLIKfs+0bqTHRrJvYGFhyqOJbaXQJ3MGD1W985of6gBAw57wIGtbv4fpBT3hKcy8AkCgYEA7jQozFSgVVBBgHd2xCENLRZ/k8BhgxA9kA1o6xzHnrDkfQtHEMBUAncE2p2amkXdTd6yASyFOJhvAmSFbgtphkXzB92IKmCvkXaTgH+GEh/x8s5ZAhekenR+BQyJePuNcVrXU7+4NLtki3GZPgAn12RLFMHY9gKUQx5EceiRH+0CgYEAyiwBO+6VDmdz/OUlwhRQd0n8UipdImk1tTbp4+dKc4bSpVHJDklFkEeFq3mxIIFbVxOWQbIpWJuDPNGBAwNudzQElGsVNQA5GCEJNJzr5EMb8vKcjf/OthJrsglEbaNE6qM02qRUyQIXImpXXG13+DrA3kvQKcbQjYIXt4kUHE8CgYEAnqyQIMyeoTX87B6oNq3toQNDqKCWVEAVQkgsg7GAJ+2dxs4kYLw9OgcebKJfwjSX3q9h0/ZcJ/8is21SlKN1f8RIyAyCD/in+zYJu6c3IAu3mA5srEAjiI7hA2v+h2DKL6Bbn6YuhrHtoBXkBrED2S8t9H3MkNPW/37CCT7qrWECgYEAh4IdRaVxpRj0ZmLU/xQMpxghFpWsnT71r8Ph208Q2QRaNlLuNzQne0BlOP01Gvt5VJEdWmVnTyvVODOYjIOFTELCexSXP1Ip8qFaATjY58OPfTTeeFMoo2MPamLJyc7wh3DjIVWaOqy7AudSLPJ2whvnYFhia04GRYMYEH3BzAMCgYBC5worloe1H/V364a6lMWUldO+mxk26EXtKIIMnnpG/BJxoVofbgZMMX1chnRhPsLUZnFhVNo6vrk2it/bE0PGVxtIyO5qYrn/Be+Bn+OUEO7jIL3MQ6iOoHf/favJxsR9mI65xC9ZlQxq8truhX69Z6U+zN6nnQ7R8BHbNW5Wtw==";
    // private $alipayPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAw7D+CxtfL2a7Vj5r+Q9hyxt3sczZGPo5vt78TZKu5gde5wMiX+v/cxDlR7OYpOtKzMS6tqVIg/pJ4vYfX7eXwRhhiatH0e8UbHSPWMWV7zam8YHtSTwLkI+/gjPbvGxuk3dNGWM3AIqzq2wl06mmVz2W37cRhl9oKc9jLzMIesYFXp9hurU+uKPq4ewZlcsUmYTtinq8q0BomCiZKjNt3EZfQ3JT7GfUgCGhCegLAO4IYFBvhxNr94Ex5CrKh0CyqTKWFdSnzobNCBX4ea9ifLn3YT2W1InEzRu+HqSm4Zmzr9N7IsigpMQAIaN6ohTkMWZLtZ9IAdVE30Hoozc70wIDAQAB";
    private $returnUrl = "http://115.159.22.89/huli3/trade.php?m=Alipay&a=returnPage";
    private $notifyUrl = "http://115.159.22.89/huli3/trade.php";
    private $charset = "UTF-8";
    private $signType = "RSA2";
    
    private $aop;//AopClient对象
    public $info="";//信息
    
    public function _initialize(){
        //关闭页面trace
        C('SHOW_PAGE_TRACE',false);

        //设置Alipay的SDK
        Vendor('Alipay.AopClient');
        $aop = new \AopClient();
        $aop->gatewayUrl = $this->gatewayUrl;
        $aop->appId = $this->appid;
        $aop->rsaPrivateKey = $this->privateKey;
        $aop->alipayrsaPublicKey = $this->alipayPublicKey;
        $aop->apiVersion = '1.0';
        $aop->signType = $this->signType;
        $aop->postCharset= $this->charset;
        $aop->format = 'json';
        $this->aop=$aop;
    }
    //创建交易记录和订单信息
    public function createTrade($trade,$data){
        $data=$trade->pre($data);
        if(is_string($data)){
            $this->info=$data;
            return false;
        }
        $trade_no=date("YmdHis");

        $this->create([
            "trade_no"=>$trade_no,
            "trade_type"=>$trade->type,
            "request"=>ACTION_NAME,
            "name"=>$data["name"],
            "total"=>$data["total"],
            "description"=>$data["description"],
            "time"=>date("Y-m-d H:i:s"),
            "trade_data"=>json_encode($data["trade"])
        ]);
        $this->add();

        $content=[
            "out_trade_no"=>$trade_no,
            "subject"=>$data["name"],
            "total_amount"=>$data["total"],
            "body"=>$data["description"]
        ];
        return $content;
    }
    //根据接收到的回调，验证并修改交易状态
    public function updateTrade($data,$type="notify",$controller){
        $data["sign"]=str_replace(" ","+",$data["sign"]);
        $result = $this->aop->rsaCheckV1($data, $this->alipayPublicKey, $this->signType);
        if(!$result){
            $this->log([
                "data"=>$data,
                "alipayPublicKey"=>$this->alipayPublicKey,
                "signType"=>$this->signType
            ],$type.".check");
            $this->info="验签失败";
            return false;
        }
        $trade_no=$data["out_trade_no"];
        $total=$data["total_amount"];
        $record=$this->where("trade_no = $trade_no")->find();
        if(!$record){
            $this->info="订单不存在";
            return false;
        }
        if($record["total"]!==$total){
            $this->info="交易金额不符";
            return false;
        }
        if($record[$type."_status"]==="success"){
            $this->info="该回调已被记录";
            return true;
        }
        $update=[
            "id"=>$record["id"],
            "alipay_trade_no"=>$data["trade_no"],
            $type."_status"=>"success",
            $type."_time"=>date("Y-m-d H:i:s")
        ];
        
        if($record["submit"]==="success"){
            $this->info="交易已结束";
            $this->save($update);
            return true;
        }
        $trade=$controller->getTradeObject($record["trade_type"]);

        if($record["trade_data"]){
            $data=json_decode($record["trade_data"],true);
        }
        else{
            $this->info="交易数据为空";
            return false;
        }

        $result = $trade->confirm($data);
        if($result === true){
            $this->info="交易成功";
            $update["submit"]="success";
            $this->save($update);
            return true;
        }
        else{
            $this->info=$result;
            return false;
        }
    }
    //将信息输出到文件中
    public function log($data,$type="log"){
        $data["action"]=__ACTION__;
        file_put_contents(
            LOG_PATH."/alipay.".$type.".log",
            json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)."\r\n\r\n",
            FILE_APPEND
        );
    }
    //接受电脑网页的交易请求并跳转到支付页面
    public function pagepay($content){
        $content["product_code"]="FAST_INSTANT_TRADE_PAY";

        Vendor('Alipay.request.AlipayTradePagePayRequest');
        $request = new \AlipayTradePagePayRequest();
        $request->setReturnUrl($this->returnUrl);
        $request->setNotifyUrl($this->notifyUrl);
        $request->setBizContent(json_encode($content));
        
        $result = $this->aop->pageExecute($request);
        return $result;
    }
    //接受手机APP的交易请求并且生成签名
    public function signature($content){
        $content["product_code"]="QUICK_MSECURITY_PAY";
        $content["timeout_express"]="30m";

        Vendor('Alipay.request.AlipayTradeAppPayRequest');
        $request = new \AlipayTradeAppPayRequest();
        $request->setNotifyUrl($this->notifyUrl);
        $request->setBizContent(json_encode($content));
        
        $result = $this->aop->sdkExecute($request);
        return $result;
        //return htmlspecialchars($result);
    }
    //查询接口
    public function query($trade_no,$alipay_trade_no){
        $record=[];
        if($trade_no){
            $where="trade_no = $trade_no";
        }
        elseif($alipay_trade_no){
            $where="alipay_trade_no = $alipay_trade_no";
        }
        else{
            $this->info="至少提供交易号或淘宝交易号其中之一";
            return false;
        }
        $record["local"]=$this->where($where)->find();

        $content=[
            "out_trade_no"=>$trade_no,
            "trade_no"=>$alipay_trade_no
        ];
        Vendor('Alipay.request.AlipayTradeQueryRequest');
        include_once(VENDOR_PATH."/Alipay/SignData.php");
        $request = new \AlipayTradeQueryRequest();
        $request->setBizContent(json_encode($content));
        $result = $this->aop->execute($request);
        $record["alipay"]=$result->alipay_trade_query_response;
        return $record;
    }
}