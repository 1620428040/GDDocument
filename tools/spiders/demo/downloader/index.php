<html>
	<head>
		<title></title>
	</head>
	<body>
		<script type="text/javascript">
		//alert("this");
		try{
			var body=document.getElementsByTagName("body")[0];
			var div=document.createElement("div");
			var textNode=document.createTextNode("dferfregrgerg");
			div.appendChild(textNode);
			body.appendChild(div);
		}catch(e){
			alert(e);
		}
		
		//alert("over");
		
		alert(body.innerHTML);
		
	</script>
	</body>
	
</html>