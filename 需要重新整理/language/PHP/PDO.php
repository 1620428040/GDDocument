<?php
//	PDO::exec() - 执行一条 SQL 语句，并返回受影响的行数
//	PDO::query() - 执行一条 SQL 语句，返回结果集
//	PDO::prepare() - 创建一个预处理语句
//	PDOStatement::execute() - 执行一条预处理语句

//$dbh = new PDO('mysql:host=localhost;dbname=test', "root", "123456");
//$stmt=$dbh->prepare("INSERT INTO `test`.`foo` (`name`, `date`, `description`) VALUES (:name, :date, :description)");
//$stmt->execute(array("name"=>"123","date"=>234432,"description"=>"注释2333"));


$dbh = new PDO('mysql:host=localhost;dbname=test', "root", "123456");
$stmt=$dbh->prepare("UPDATE `test`.`foo` SET `name` = :name, `date` = :date, `description` = :description WHERE `foo`.`id` = :id");
print_r($stmt);
print_r($stmt->execute(array("id"=>"2","name"=>"erqwwe","description"=>"测试")));


//	try {
//		//创建PDO对象和数据库连接
//	    $dbh = new PDO('mysql:host=localhost;dbname=test', "root", "123456",array(
//	    	//可选的参数，更多参数可以查看http://php.net/manual/zh/pdo.constants.php中ATTR_开头的参数
//		    PDO::ATTR_PERSISTENT => true//持久化连接，相当于初始化后调用$dbh->setAttribute(PDO::ATTR_PERSISTENT, true);
//		));
//		$data=$dbh->query('SELECT * from FOO');
////		print_r($data);
//	    foreach($data as $row) {//虽然$data是一个PDOStatement对象，但是可以直接枚举其中的数据
////	        print_r($row);
//	    }
//		$data->fetchAll(PDO::FETCH_ASSOC);//将结果解析为关联数组
//		
//		//插入一条数据并且获取到新插入的记录的id
//		$dbh->exec("INSERT INTO `test`.`foo` (`id`, `name`, `date`, `description`) VALUES (NULL, 'zhangsan', '23423424', 'dsedfewfwefwew')");
//		print_r($dbh->lastInsertId());
//		
////	    $dbh = null;//销毁PDO对象
//	} catch (PDOException $e) {
//	    print "Error!: " . $e->getMessage() . "<br/>";
//	    die();
//	}
	
	//事务
//	try {
//		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//设定报错的方式
//		$dbh->beginTransaction();//开始一个事务
//		$dbh->exec("INSERT INTO `test`.`foo` (`id`, `name`, `date`, `description`) VALUES (NULL, 'zhangsan', '23423424', 'dsedfewfwefwew')");
//		$dbh->exec("INSERT INTO `test`.`foo` (`id`, `name`, `date`, `description`) VALUES (NULL, '3213eef', '23423424', 'dsedfewfwefwew')");
//		$dbh->commit();//提交事务
//	} 
//	catch (Exception $e) {
//		$dbh->rollBack();//如果发生错误则回滚，如果脚本意外终止也会回滚
//		echo "Failed: " . $e->getMessage();
//	}
	
	//预处理语句可以加快重复的查询的速度并且防止sql注入
	//预处理语句如此有用，以至于它们唯一的特性是在驱动程序不支持的时PDO 将模拟处理。这样可以确保不管数据库是否具有这样的功能，都可以确保应用程序可以用相同的数据访问模式。
//	try {
//		//生成一条预处理语句并且绑定参数，占位符以":"开头，必须占用整个值的位置
//		$stmt = $dbh->prepare("INSERT INTO `test`.`foo` (`name`, `date`, `description`) VALUES (:name, :date, :description)");
//		$stmt->bindParam(':name', $name);
//		$stmt->bindParam(':date', $date);
//		$stmt->bindParam(':description', $description);
//		//$stmt->bindParam(':description', $description);//绑定参数
//		//$stmt->bindValue(':description', "注释");//绑定值
//		
//		//另一种绑定方式
////		$stmt = $dbh->prepare("INSERT INTO `test`.`foo` (`name`, `date`, `description`) VALUES (?, ? ,?)");
////		$stmt->bindParam(1, $name);
////		$stmt->bindParam(2, $date);
////		$stmt->bindParam(3, $description);
////		
////		//插入一行数据
////		$name = 'stmt1';
////		$date = 1;
////		$description = '预处理语句1';
////		$stmt->execute();//执行
////		
////		$name = 'stmt2';
////		$date = 2;
////		$description = '预处理语句2';
////		$stmt->execute();
//		
//		//另一种插入方式
//		$stmt = $dbh->prepare("INSERT INTO `test`.`foo` (`name`, `date`, `description`) VALUES (:name, :date, :description)");
//		if ($stmt->execute(array("name"=>"stmt3","date"=>3,"description"=>"预处理语句3"))) {
//			while ($row = $stmt->fetch()) {
//			    print_r($row);
//			}
//		}
//		//或者也能用数组的方式
////		$stmt = $dbh->prepare("INSERT INTO `test`.`foo` (`name`, `date`, `description`) VALUES (?, ? ,?)");
////		if ($stmt->execute(array("stmt3",3,"预处理语句3"))) {
////			while ($row = $stmt->fetch()) {
////			    print_r($row);
////			}
////		}
//
//		//调用存储过程，以及绑定返回值
////		$stmt = $dbh->prepare("CALL sp_returns_string(?)");
////		$stmt->bindParam(1, $return_value, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT, 4000);
////		//bool bindParam ( mixed $parameter , mixed &$variable [, int $data_type = PDO::PARAM_STR [, int $length [, mixed $driver_options ]]] )
////		//$return_value既会作为参数传递给存储过程，也会接收返回值
////		$stmt->execute();// 调用存储过程
////		print "procedure returned $return_value\n";
//	}
//	catch (Exception $e){
//		echo "Failed: " . $e->getMessage();
//	}
?>