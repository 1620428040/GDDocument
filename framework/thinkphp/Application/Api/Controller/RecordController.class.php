<?php
namespace Api\Controller;
use Think\Controller;

class RecordController extends Controller{
    public function create(){
        $model=D("Record");
        if(!$model->create()){
            exit($model->getError());
        }
        $this->ajaxReturn($model->add());
    }
    public function update(){
        $model=D("Record");
        if(!$model->create()){
            exit($model->getError());
        }
        $this->ajaxReturn($model->save());
    }
    public function delete(){
        $id=I("id","");
        if($id===""){
            exit("请输入ID");
        }
        $result=D("Record")->where("id=".$id)->delete();
        if($result){
            $this->ajaxReturn($result);
        }
        else{
            exit("找不到ID对应的记录");
        }
    }
    public function showList(){
        $list=D("Record")->where()->select();
        $this->ajaxReturn($list);
    }
    
}
