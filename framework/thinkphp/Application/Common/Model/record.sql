-- 如果"demo"数据库已存在，则删除
DROP DATABASE IF EXISTS `demo`;
-- 创建"demo"数据库
CREATE DATABASE `demo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
-- 使用"demo"数据库，使用之后可以省略输入要修改的数据库
USE `demo`;


CREATE TABLE `record` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` VARCHAR( 20 ) NOT NULL ,
`type` INT NOT NULL ,
`content` TEXT NOT NULL,
`time` INT NOT NULL
);





