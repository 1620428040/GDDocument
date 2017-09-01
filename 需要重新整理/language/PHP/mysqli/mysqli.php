<?php
$conn=new mysqli("localhost","root","123456","test");//创建新的数据库连接对象
//$conn->select_db("test");//指定数据库
$result=$conn->query('select * from custom_field');//执行一条sql语句
$row=$result->fetch_row();//获取一行数据
$row=$result->fetch_assoc();//获取一行数据(关联数组形式)
//print_r($row);
$data=$result->fetch_all(MYSQL_ASSOC);//获取全部数据，MYSQL_ASSOC表示用关联数组的形式
$fields=$result->fetch_fields();//获取结果中的字段
$result->close();//释放结果对象
$conn->close();//释放数据库连接对象
echo json_encode($fields);

///*也可以写成这样*/
//$mysqli = new mysqli("localhost", "my_user", "my_password", "world");
///* check connection */
//if (mysqli_connect_errno()) {
//  printf("Connect failed: %s\n", mysqli_connect_error());
//  exit();
//}
//$query = "SELECT Name, CountryCode FROM City ORDER by ID DESC LIMIT 50,5";
//if ($result = $mysqli->query($query)) {
//  /* fetch object array */
//  while ($row = $result->fetch_row()) {
//      printf ("%s (%s)\n", $row[0], $row[1]);
//  }
//  /* free result set */
//  $result->close();
//}
///* close connection */
//$mysqli->close();
?>