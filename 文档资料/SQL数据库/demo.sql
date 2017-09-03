-- 如果"demo"数据库已存在，则删除
DROP DATABASE IF EXISTS `demo`;
-- 创建"demo"数据库
CREATE DATABASE `demo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
-- 使用"demo"数据库，使用之后可以省略输入要修改的数据库
USE `demo`;

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

-- 创建并填充"order"表，省略没必要写的内容
CREATE TABLE  `order` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`num` INT NOT NULL ,
`pid` INT NOT NULL ,
`money` INT NOT NULL
);
INSERT INTO `order` (`num`,`pid`,`money`) VALUES
(77895,3,3434),
(44678,3,4656),
(22456,1,5865),
(24562,1,7867),
(34764,2,6785),
(43212,65,7545);

-- 创建两个结构相同的"employees"表
CREATE TABLE `employees_cn` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`lastName` VARCHAR( 20 ) NOT NULL ,
`firstName` VARCHAR( 20 ) NOT NULL
);
INSERT INTO `employees_cn` (`lastName`, `firstName`) VALUES 
('Zhang','Hua'), 
('Wang','Wei'), 
('Carter','Thomas'), 
('Yang','Ming');

CREATE TABLE `employees_us` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`lastName` VARCHAR( 20 ) NOT NULL ,
`firstName` VARCHAR( 20 ) NOT NULL
);
INSERT INTO `employees_us` (`lastName`, `firstName`) VALUES 
('Adams','John'), 
('Bush','George'), 
('Carter','Thomas'), 
('Gates','Bill');

-- 创建user表 
CREATE TABLE `user` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 20 ) NOT NULL ,
`age` VARCHAR( 20 ) NOT NULL ,
`heigh` FLOAT( 11,3 ) NOT NULL
);
INSERT INTO `user` (`name`,`age`,`heigh`) VALUES 
('Adams',12,42.321), 
('Bush',14,60.593), 
('Carter',35,173.632), 
('Gates',47,187.052);