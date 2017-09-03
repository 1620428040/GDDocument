<?php
namespace Api\Controller;
use Think\Controller;

//这个例子是不使用Model自动创建数据对象时的写法
//需要sql进阶语法.md中的创建的demo数据库
class ApiController extends Controller{
    public function create(){
        $name = I('name','');
        $age = I('age','');
        $heigh = I('heigh','');
        if ($name == ''||$age == ''||$heigh == ''){
            $this->ajaxReturn(array('msg'=>'参数不允许为空！'));
        }
        $usermod = M('User');
        $data = array(
            'name' => $name,
            'age' => $age,
            'heigh' => $heigh
        );
        $in = $usermod->data($data)->add();
        if ($in){
            $this->ajaxReturn(array('msg'=>'success'));
        }else{
            $this->ajaxReturn(array('msg'=>'添加数据失败！'));
        }
    }
	public function update(){
    	
    }
	public function select(){
    	
    }
}
?>