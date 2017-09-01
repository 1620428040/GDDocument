<?php
namespace Home\Controller;
use Think\Controller;

class PageController extends Controller{
    //绑定模板变量
    public function index(){
        $string="hello world !";
        $list=["action","boy","controller","data","echo","fire","group"];
        $array=[
            "a"=>"action",
            "b"=>"boy",
            "c"=>"controller",
            "d"=>"data",
            "e"=>"echo",
            "f"=>"fire",
            "g"=>"group"
        ];
        $boolean=true;

        $this->assign("str",$string);
        $this->assign("li",$list);
        $this->assign("arr",$array);
        $this->assign("bool",$boolean);

        $this->display();
    }
    //绑定数组
    public function bindArray(){
        $array=[[
            "name"=>"张三",
            "age"=>23
        ],[
            "name"=>"李四",
            "age"=>19
        ],[
            "name"=>"王五",
            "age"=>26
        ],[
            "name"=>"赵六",
            "age"=>17
        ]];
        $this->assign("data",$array);
        $this->assign("level",2);
        $this->display();
    }
    //模板页
    public function page(){
        $this->display();
    }
}
?>