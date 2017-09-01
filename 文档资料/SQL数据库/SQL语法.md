###注释
```sql
-- 单行注释(MySQL中"--"之后必须加空格)
# 单行注释
/*
多行注释
*/
```

###创建用来测试的数据库
```sql
-- 创建"person"表
CREATE TABLE `demo`.`person` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`lastName` VARCHAR( 20 ) NOT NULL ,
`firstName` VARCHAR( 20 ) NOT NULL ,
`address` VARCHAR( 20 ) NOT NULL ,
`city` VARCHAR( 20 ) NOT NULL
) ENGINE = MYISAM ;
-- 设置AUTO_INCREMENT的值
ALTER TABLE  `demo`.`person` AUTO_INCREMENT =1;
-- 填充"person"表的多行数据
INSERT INTO `demo`.`person` (`id`, `lastName`, `firstName`, `address`, `city`) VALUES 
(NULL, 'Adams', 'John', 'Oxford Street', 'London'), 
(NULL, 'Bush', 'George', 'Fifth Avenue', 'New York'), 
(NULL, 'Carter', 'Thomas', 'Changan Street', 'Beijing');
```

###使用指定的数据库
```sql
USE `demo`;
```

###SELECT	查找
SELECT 列名称 FROM 表名称
```sql
SELECT * FROM `person`;
SELECT `id`, `lastName`, `firstName` FROM `person`;
```

####DISTINCT	去重
用来列出某一列中不同的值
```sql
SELECT DISTINCT  `lastName` FROM `person`;
```

##创建/删除数据库
```sql
create database if not exists `demo`;
drop database if exists `demo`; 
```