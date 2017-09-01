<?php
// comet.php
include('NovComet.php');

$comet = new NovComet();
$publish = filter_input(INPUT_GET, 'publish', FILTER_SANITIZE_STRING);
if ($publish != '') {
    echo $comet->publish($publish);
} else {
    foreach (filter_var_array($_GET['subscribed'], FILTER_SANITIZE_NUMBER_INT) as $key => $value) {
        $comet->setVar($key, $value);
    }
    echo $comet->run();
}
function send(msg){
    $.ajax({
        data : {'msg' : msg},
        type : 'post',
        url : '{:U('Live/SendMsg')}',
        success : function(response){
           //alert(response);;
        }
    })
}
$(document).ready(function(){
    connect();
    $("#btn").click(function(){
        var msg = $('#msg').val();
        send(msg);
        msg.html('');
      });
})


public function SendMsg(){
    
    $filename  = './Uploads/live/'.'data.json';
    if ($_POST['msg']!='') {
        file_put_contents($filename,$_POST['msg']);
        $this->ajaxReturn($_POST,'OK',100);
        die();
    }else{
        $this->ajaxReturn($_POST,'on',0);
        die();
    }
    
}