<?php
namespace Home\Controller;
use Think\Controller;

class RecordController extends Controller{
    //可以忽略，框架会自动调用相应的模板
    public function create(){
        $this->display();
    }
    public function doCreate(){
        $model=D("Record");
        if(!$model->create()){
            exit($model->getError());
        }
        $this->success("创建成功，id=".$model->add(),U("showList"));
    }
    public function update(){
        $id=I("id","");
        if($id===""){
            $this->error("请输入ID");
        }
        $record=D("Record")->where("id=".$id)->find();
        if($record){
            $this->assign("record",$record);
            $this->display();
        }
        else{
            $this->error("找不到ID对应的记录");
        }
    }
    public function doUpdate(){
        $model=D("Record");
        if(!$model->create()){
            exit($model->getError());
        }
        $this->success("修改成功，受影响的行数：".$model->save(),U("showList"));
    }
    public function delete(){
        $id=I("id","");
        if($id===""){
            $this->error("请输入ID");
        }
        $result=D("Record")->where("id=".$id)->delete();
        if($result){
            $this->success("删除成功，受影响的行数：".$result,U("showList"));
        }
        else{
            $this->error("找不到ID对应的记录");
        }
    }

    public function showList(){
        $list=D("Record")->where()->select();
        $this->assign("records",$list);
        $this->display();
    }
}
