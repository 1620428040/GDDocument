<?php if (!defined('THINK_PATH')) exit();?><pre>foreach标签，可以直接用来输出select出的二维数组</pre>
<ul><?php if(is_array($data)): foreach($data as $key=>$item): ?><li>
姓名：<?php echo ($item["name"]); ?><br/>年龄：<?php echo ($item["age"]); ?>
</li><?php endforeach; endif; ?></ul>

<pre>volist类似于foreach，但是提供更多的功能</pre>
<ul><?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><li>
姓名：<?php echo ($item["name"]); ?><br/>年龄：<?php echo ($item["age"]); ?>
</li><?php endforeach; endif; else: echo "" ;endif; ?></ul>

<?php switch($level): case "1": ?>一级<?php break;?>
    <?php case "2": ?>二级<?php break;?>
    <?php default: ?>零级<?php endswitch;?>

<?php if(($level) >= "2"): ?>用户等级大于等于2<?php endif; ?>