<?php
namespace Trade\Model;
use Think\Model;
use Trade\Model\Trade;

//所有处理实际的交易的类，应该继承此类或者将此类作为接口
class TopupModel extends Model implements Trade{
    public $type="topup";
    public function pre($params){
        $uid=$params["uid"];
        $userModel = M("Users");
        $user = $userModel->where("id='$uid'")->find();
        if(!$user){
            return "找不到用户";
        }
        $money=$params["money"];
        if(!is_numeric($money)){
            return "充值金额必须是数字";
        }

        $data=[
            "name"=>"充值",
            "total"=>$money,
            "description"=>"狐狸直播-虚拟货币",
            "trade"=>["uid"=>$uid,"money"=>$money]
        ];
        return $data;
    }
    public function confirm($params){
        $uid=$params["uid"];
        $inmoney=$params["money"];
        $userModel = M("Users");
        $user = $userModel->where("id='$uid'")->find();
        $money=$user["money"]+$inmoney*10;//充值后的虚拟币，虚拟币是充值金额的10倍
        $res = $userModel->save(["id"=>$uid,"money"=>$money]);
        if(!$res){
            return "更新余额失败";
        }

        $data = array(
            'uid' => $uid,
            'inmoney' => $inmoney,
            'lastmoney' => $money,
            'ptime' => date("Y年m月d日 H:i分")
        );
        $res = $this->add($data);
        if(!$res){
            return "创建充值记录失败";
        }
        return true;
    }
}