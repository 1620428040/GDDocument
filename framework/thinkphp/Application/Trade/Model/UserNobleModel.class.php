<?php
namespace Trade\Model;
use Think\Model;
use Trade\Model\Trade;

//所有处理实际的交易的类，应该继承此类或者将此类作为接口
class UserNobleModel extends Model implements Trade{
    public $type="noble";
    public function pre($params){
        $uid=$params["uid"];
        $userModel = M("Users");
        $user = $userModel->where("id='$uid'")->find();
        if(!$user){
            return "找不到用户";
        }

        $nid=$params["nid"];
        $noble = M("Noble")->where("id='$nid'")->find();
        if (!$noble){
            return "找不到对应的爵位";
        }

        $money=$params["money"];
        if(!is_numeric($money)&&$money>0){
            return "充值金额必须是数字";
        }
        $validmonth=$params["validmonth"];
        if(!is_numeric($validmonth)&&$validmonth>0){
            return "有效期必须是数字";
        }

        $data=[
            "name"=>"爵位",
            "total"=>$money,
            "description"=>"狐狸直播-开通爵位",
            "trade"=>["uid"=>$uid,"nkind"=>$noble["nkind"],"money"=>$money,"validmonth"=>$validmonth]
        ];
        return $data;
    }
    public function confirm($params){
        $params["stime"]=date("Y-m-d");
        $res = $this->add($params);
        if(!$res){
            return "添加用户爵位失败";
        }
        return true;
    }
}