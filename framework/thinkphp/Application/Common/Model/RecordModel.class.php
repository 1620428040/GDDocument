<?php
namespace Common\Model;
use Think\Model;

class RecordModel extends Model{
    protected $_validate = array(
        array('title','require','标题不能为空！'), //默认情况下用正则进行验证
        array('type',array(1,2,3),'类型的范围不正确！',0,'in'), // 当值不为空的时候判断是否在一个范围内
        array('content',2,10,'内容长度在两个字到十个字之间',0,'length')
    );
    protected $_auto = array (
        array('time','time',1,'function')
    );
}