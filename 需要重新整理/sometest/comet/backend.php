<?php
// 设置请求运行时间不限制，解决因为超过服务器运行时间而结束请求
ini_set("max_execution_time", "0");

$filename  = dirname(__FILE__).'/data.txt';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// 判断页面提交过来的修改内容是否为空，不为空则将内容写入文件，并中断流程
if ($msg != '')
{
    file_put_contents($filename,$msg);
    exit;
}

/* 获取文件上次修改时间戳 和 当前获取到的最近一次文件修改时间戳
 * 文件上次修改时间戳 初始 默认值为0
 * 最近一次文件修改时间戳 通过 函数 filemtime()获取
 */
$lastmodif    = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
clearstatcache();  // 清除文件状态缓存
$currentmodif = filemtime($filename);

/* 如果当前返回的文件修改unix时间戳小于或等于上次的修改时间，
 * 表明文件没有更新不需要推送消息
 * 如果当前返回的文件修改unix时间戳大于上次的修改时间
 * 表明文件有更新需要输出修改的内容作为推送消息
 */
while ($currentmodif <= $lastmodif)
{
    usleep(10000);     // 休眠10ms释放cpu的占用
    clearstatcache();  // 清除文件状态缓存
    $currentmodif = filemtime($filename);
}

// 推送信息处理(需要推送说明文件有更改，推送信息包含本次修改时间、内容)
$response = array();
$response['msg'] = file_get_contents($filename);
$response['timestamp'] = $currentmodif;
echo json_encode($response);
flush();
?>