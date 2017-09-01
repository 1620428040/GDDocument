<?php
//实现文件分片上传的页面+服务器

//步骤：
//php
//选择要进行的action

//输出上传文件的页面
//获取进程号
//生成申请上传用的URL路径，上传结束后通知的路径
//js
//查看是否有重复的文件，获取 文件存放地址，文件名
//分片上传文件
//发出文件上传结束的通知

//处理接收到的文件片段

//测试地址：
//http://localhost:81/GDTestCode/sometest/fileSliceUpload/fileUpload.php?checknum=100000

require("../../../bg/extend.php");
//writeToFile("检测是否能正确输出");

if(isset($_GET['action'])){
	if($_GET['action']=="uploadSlice"){
		//$uploadPath="C:/Users/admin/Desktop/upload.txt";//绝对目录
		//$uploadPath="upload/upload.txt";//相对目录
		acceptSlice("upload/upload.txt");
	}
	echo "ok";
}
elseif(isset($_GET['checknum'])){
	uploadPage($_GET['checknum']);
}
else{
	header("content-type:text/html;charset=utf-8");
	echo "未定义的行为";
}



function uploadPage($checknum){
	$check_url="fileUpload.php?action=100000";
	$finish_url="fileUpload.php?action=100000";
	$server_url="fileUpload.php?action=uploadSlice";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">

	<head>
		<title>分割大文件上传</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style>
			#progressBar {
				text-align: center;
				font: 8px/10px '微软雅黑', '黑体', sans-serif;
				width: 300px;
				height: 10px;
				border: 1px solid green;
			}
			#progress {
				width: 0%;
				height: 100%;
				background: green;
				text-align: center;
			}
		</style>
	</head>

	<body>
		<form enctype="multipart/form-data" action="index.php?message=uploadSlice&checknum=<?php echo $_GET['checknum']; ?>" method="post">
			<div id="progressBar">
				<div id="progress"></div>
			</div>
			<input type="file" id="uploadFile" multiple="multiple" />
			<input type="button" value="上传" onclick="uploadFileRequest()" />
			<input type="submit" value="submit" />
		</form>
		<script type="text/javascript">
			//alert("this");
			var progressBar = document.getElementById('progressBar');
			var progress = document.getElementById('progress');
			var length = 1 * 1024 * 1024;
			var file = null;
			var strat = 0;
			var end = 0;
			var server="<?php echo $server_url;?>"
			
			function uploadFileRequest() {
				//alert("开始上传文件");
				file = document.getElementById('uploadFile').files[0];
				if (!file) {
					alert('请选择文件');
					return;
				}
				checkIsExistAndGetFileName();
			}
			
			//检查是否存在重复的文件并且获取文件名
			function checkIsExistAndGetFileName(){
				ajaxGETRequest("<?php echo $check_url;?>",function(data){
					alert(data);
					uploadFileStart();
				});
			}
			
			function uploadFileStart() {
				alert("文件上传开始");
				start = 0;
				uploadSlice();
			}

			function uploadFileFinsh() {
				alert("文件上传结束");
				ajaxGETRequest("<?php echo $finish_url;?>",function(data){
					alert(data);
				});
			}

			function uploadSlice() {
				//alert("开始上传分片");
				if (start < file.size) {
					var request;
					if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
						request = new XMLHttpRequest();
					} 
					else { // code for IE6, IE5
						request = new ActiveXObject("Microsoft.XMLHTTP");
					}
					request.open('POST', server, true);
					//xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
					request.onreadystatechange = function() {
						if (this.readyState == 4) {
							if (this.status >= 200 && this.status < 300) {
								if(this.responseText=="ok"){
									uploadSliceProgress(end);
									start = end;
									uploadSlice();
								}
								else{
									alert('文件发送失败，请重新发送');
									progress.style.width = '0%';
									progress.innerText="0%";
								}
							}
						}
					}
					request.upload.onprogress = function(ev) {
						if (ev.lengthComputable) {
							uploadSliceProgress(ev.loaded + start);
						}
					}
					//分割文件核心部分slice
					end = start + length;
					blob = file.slice(start, end);
					fd = new FormData();
					fd.append('fileData', blob);
					fd.append('fileName', file.name);
					//console.log(fd);
					//pending=true;
					request.send(fd);
				} else {
					uploadFileFinsh();
				}
			}

			function uploadSliceProgress(byteLength) {
				//alert("更新上传进程：byteLength:"+byteLength);
				pecent = 100 * byteLength / file.size;
				if (pecent > 100) {
					pecent = 100;
				}
				//num.innerHTML=parseInt(pecent)+'%';
				progress.style.width = pecent + '%';
				progress.innerHTML = parseInt(pecent) + '%';
			}
			
			function ajaxGETRequest(url,callback){
				var xhr=null;
				if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
					xhr = new XMLHttpRequest();
				} 
				else { // code for IE6, IE5
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xhr.open("GET",url,true);
				xhr.onreadystatechange=function(){
					if(xhr.readyState==4&&xhr.status==200){
						if(callback!=null){
							callback(xhr.responseText);
						}
					}
				}
				xhr.send();
			}
		</script>
	</body>
</html>
<?php
}


function acceptSlice($path){
	//writeToFile("acceptSlice");
	$file = $_FILES['fileData'];//上传的文件
	// print_r($_POST['test']);exit;
	if ($file['error'] == 0) {
		if (!file_exists($path)) {
			if (!move_uploaded_file($file['tmp_name'],$path)) {
				writeToFile("move_error");
				echo 'failed';
			}
		}
		else {
			$content = file_get_contents($file['tmp_name']);
			if (!file_put_contents($path, $content, FILE_APPEND)) {
				writeToFile("path_notexists");
				echo 'failed';
			}
		}
	} 
	else {
		writeToFile("file_error");
		echo 'failed';
	}
}
?>