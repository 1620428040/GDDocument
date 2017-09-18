## IF
```sql
-- 如果数据库已经存在，则重新创建
DROP DATABASE IF EXISTS `demo`;
CREATE DATABASE `demo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

-- 如果数据库不存在，则创建
CREATE DATABASE IF NOT EXISTS `demo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
```


## 多表联合查询等比较复杂的语法
- 测试用的数据库可以使用demo.sql中的脚本创建


###IN 操作符
IN 操作符允许我们在 WHERE 子句中规定多个值。
```sql
SELECT `id`,`lastName` ,`firstName` FROM `person` WHERE id IN (1,2);
```
####子查询
在IN语句中，允许使用独立的查询，这称为子查询
```sql
SELECT `id`,`num`,`pid`,`money` FROM `order` WHERE `pid` IN 
(SELECT `id` FROM `person` WHERE `city` LIKE 'Beijing');
```


###BETWEEN 操作符
操作符 BETWEEN ... AND 会选取介于两个值之间的数据范围。这些值可以是数值、文本或者日期。
```sql
SELECT `id`,`lastName` ,`firstName` FROM `person` WHERE `id` BETWEEN 1 AND 3;
```
- 不同数据库，对是否包含边界上的数据，处理方式不同。


###Alias 别名
表的 SQL Alias 语法
```sql
SELECT `id`,`lastName` ,`firstName` FROM `person` AS `people`;
```
- 只在一个表中查询数据，表的别名没什么作用，主要是在多表联合查询中有用。
- 只要在语句中声明表的别名，那么这个别名就可以用在语句的任何位置，及时是在声明之前的位置

列的 SQL Alias 语法
```sql
SELECT `id` AS `编号`,`lastName` AS `姓` ,`firstName` AS `名` FROM `person`;
```


###多表联合查询
引用多个表中的数据
```sql
SELECT `o`.`id`,`o`.`num`, `p`.`lastName`, `p`.`firstName`
FROM `person` AS `p`, `order` AS `o`
WHERE `p`.`id`=`o`.`pid`;
```
####多表联合查询，会在多个表的数据的所有组合中，选出符合条件的组合。如果条件不足，会输出不合适的组合
```sql
SELECT `o`.`id`,`o`.`num`, `p`.`lastName`, `p`.`firstName`
FROM `person` AS `p`, `order` AS `o`
WHERE `p`.`lastName`='Adams' AND `p`.`firstName`='John';
```


###JOIN 连接
用于根据两个或多个表中的列之间的关系，从这些表中查询数据。
```sql
SELECT `o`.`id`,`o`.`num`, `p`.`lastName`, `p`.`firstName`
FROM `person` AS `p` INNER JOIN `order` AS `o` ON `p`.`id`=`o`.`pid`
ORDER BY `o`.`num`;
```
####以上语句与多表联合查询效果一样
####连接查询的其他选项
- INNER JOIN: 如果表中有至少一个匹配，则返回行
- LEFT JOIN: 即使右表中没有匹配，也从左表返回所有的行
- RIGHT JOIN: 即使左表中没有匹配，也从右表返回所有的行
- FULL JOIN: 只要其中一个表中存在匹配，就返回行。但是MySQL不支持FULL JOIN


###UNION 合并
UNION 操作符用于合并两个或多个 SELECT 语句的结果集。
```sql
SELECT `id`,`lastName`,`firstName` FROM `employees_cn`
UNION
SELECT `id`,`lastName`,`firstName` FROM `employees_us`;
```
- UNION 内部的 SELECT 语句必须拥有相同数量的列。列也必须拥有相似的数据类型。同时，每条 SELECT 语句中的列的顺序必须相同。
- UNION 命令只会选取不同的值。UNION ALL 命令会列出所有的值。


###SELECT INTO
您可以把所有的列插入新表
```sql
SELECT * INTO `order_backup` FROM `order`;
```
####MySQL不支持SELECT INTO，可以用CREATE语法替代
```sql
CREATE TABLE `order_backup` (SELECT * FROM `order`);
```
####可以用INSERT INTO实现类似的功能
```sql
INSERT INTO `order_backup`(`num`,`pid`,`money`) SELECT `num`,`pid`,`money` FROM `order`;
```
- SELECT INTO要求被导入的表不存在，INSERT INTO要求被导入的表已存在。INSERT INTO可以在插入前处理数据
- SELECT INTO创建的表，没有复制原表的主键，自增等设置


###GROUP BY 分组
GROUP BY 语句用于结合合计函数，根据一个或多个列对结果集进行分组。
```sql
SELECT `pid`,count(`id`) AS `order`,SUM(`money`) AS `total` FROM `order` GROUP BY `pid`;
```
- GROUP BY可以将数据按照某一个（或多个）字段的值的不同，将数据分组
- 返回的数据是对这个组进行统计，而不是某条数据的值
####分组加联合查询，统计每个用户的订单的数量和总额，按照订单总额排序
```sql
SELECT `o`.`pid`,`p`.`lastName`,`p`.`firstName`,count(`o`.`id`) AS `order`,SUM(`o`.`money`) AS `total`
FROM `person` AS `p` 
INNER JOIN `order` AS `o` ON `p`.`id`=`o`.`pid`
GROUP BY `o`.`pid`
ORDER BY SUM(`o`.`money`) DESC;
```


####HAVING
在 SQL 中增加 HAVING 子句原因是，WHERE 关键字无法与合计函数一起使用。
HAVING 子句可以实现将函数的结果作为查询条件
```sql
SELECT `o`.`pid`,`p`.`lastName`,`p`.`firstName`,count(`o`.`id`) AS `order`,SUM(`o`.`money`) AS `total`
FROM `person` AS `p` 
INNER JOIN `order` AS `o` ON `p`.`id`=`o`.`pid` GROUP BY `o`.`pid`
HAVING SUM(`o`.`money`)>=200
ORDER BY SUM(`o`.`money`) DESC;
```


###VIEW
视图是基于 SQL 语句的结果集的可视化的表。
视图包含行和列，就像一个真实的表。视图中的字段就是来自一个或多个数据库中的真实的表中的字段
```sql
CREATE VIEW person_order AS
SELECT `o`.`pid`,`p`.`lastName`,`p`.`firstName`,count(`o`.`id`) AS `order`,SUM(`o`.`money`) AS `total`
FROM `person` AS `p` INNER JOIN `order` AS `o` ON `p`.`id`=`o`.`pid`
GROUP BY `o`.`pid`;
```


###统计函数
- avg()     平均
- count()   个数
- first()   第一个，MySQL不支持
- last()    最后一个，MySQL不支持
- max()     最大的
- min()     最小的
- sum()     求和

```sql
SELECT `pid`,
count(`id`) AS `order`,
sum(`money`) AS `total`,
avg(`money`) AS `average`,
max(`money`) AS `max`,
min(`money`) AS `min`
FROM `order` GROUP BY `pid`;
```

###字符串处理
- ucase()   转换为大写
- lcase()   转换为小写
- mid()     提取字符    column_name,start[,length]
- len()     字符串长度   MySQL中用length

```sql
SELECT `firstName`,
ucase(`firstName`),
lcase(`firstName`),
mid(`firstName`,2),
length(`firstName`)
FROM `person`;
```


###其他函数
- round()   把数值字段舍入为指定的小数位数 column_name,decimals
- now()     当前系统的日期和时间
- format()  用于对字段的显示进行格式化   column_name,format

```sql
SELECT `id`,`name`,
round(`heigh`,1) as `height`,
now() AS `time`,
DATE_FORMAT(Now(),'%Y-%m-%d') AS `date`,
format(Now(),'%Y-%m-%d') AS `date2`,
format(`heigh`,2) as `height2`
FROM `user`;
```


###FOREIGN KEY 外键
外键约束是约束的一种。约束这玩意用起来很麻烦，有时候还不起作用。反正也没啥用。

####在创建数据表时，添加外键
```sql
-- 创建一个带有外键的数据表
CREATE TABLE  `order2` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`num` INT NOT NULL ,
`pid` INT NOT NULL ,
`money` INT NOT NULL ,
FOREIGN KEY (`pid`) REFERENCES `person`(`id`)
);
-- 此时。因为person表中没有id=65的数据，所以插入失败
INSERT INTO `order2` (`num`,`pid`,`money`) VALUES
(77895,3,3434),
(44678,3,4656),
(22456,1,5865),
(24562,1,7867),
(34764,2,6785),
(43212,65,7545);
```

####给已经存在的数据表插入外键
```sql
ALTER TABLE `order2`
ADD CONSTRAINT `fk_PerOrders` FOREIGN KEY (`pid`)
REFERENCES `person`(`id`);
-- 如果不需要给约束起名字
ALTER TABLE `order2`
ADD FOREIGN KEY (`pid`)
REFERENCES `person`(`id`);
```

####删除外键
```sql
ALTER TABLE `order2`
DROP FOREIGN KEY `fk_PerOrders`;
-- 如果没有给约束起名
ALTER TABLE `order2`
DROP FOREIGN KEY `pid`
```

### 复杂的多表联查
做众筹网站的时候，实现过一个功能，需要从投票表、用户投票记录表、用户投资表中统计用户的投票结果（以投资总额为权重）
```sql
SELECT `v`.`id` AS `vid`,`f`.`pid`,`f`.`uid`,sum(`f`.`money`) AS `money`,`vu`.`vote`,`v`.`status`
FROM `tp_fund` AS `f`
INNER JOIN `tp_vote` AS `v` ON `v`.`pid`=`f`.`pid`
INNER JOIN `tp_vote_user` AS `vu` ON `vu`.`uid`=`f`.`uid` AND `vu`.`vid`=`v`.`id`
GROUP BY `f`.`pid`,`f`.`uid`,`vu`.`vote`,`vid`,`v`.`status`
HAVING `v`.`status` = 'run'
```
这种多表联查比较复杂，很容易被迷惑。最好先在纸上画出相应的表的结构图和关联字段
然后逐个添加表和相关字段，此时产生的结果是几个表中所有数据的组合
然后添加分组，此时需要注意，所有要输出的字段，要么需要添加到GROUP BY关键字后，作为分组的依据，要么作为统计函数的参数。否则就会出错
最后用HAVING子句添加搜索条件
如果需要重复使用这个查询，可以根据这个查询新建视图


### 更复杂的多表联查，使用内连接和子查询完成
功能更复杂了，重新组织了一下。
用某个表作为主表，从其它表中查询数据做补充
在原来是表名的位置，使用子查询，构建符合条件的虚表，从而避免将所有搜索条件都混杂在一起
构建视图时，使用"CREATE OR REPLACE VIEW"代替"CREATE VIEW"
"[tablename].*"可以代替某个表中的所有字段
每次修改数据库中相关的表，都要用下面的脚本更新视图，否则视图中的字段不会自动更新

```sql
-- 项目表扩展视图
CREATE OR REPLACE VIEW `tp_project_view` AS
SELECT 
`p`.*,# 项目的基本信息
`a`.`username`,# 创建项目的管理员的用户名
ifnull(`f`.`money`,0) AS `had`,# 已经筹集的金额
ifnull(`v`.`money`,0) AS `vote`,# 投票中或投票成功的出售金额
ifnull(`vu`.`count`,0) AS `support`,# 投票支持出售的人数
`register`.`time` AS `register_time`,# 注册时间
`begin`.`time` AS `begin_time`,# 众筹开始时间
`end`.`time` AS `end_time`,# 众筹结束时间
`complete`.`time` AS `complete_time`# 项目结束时间
FROM `tp_project` AS `p`
LEFT JOIN(
    -- 每个管理员的用户名
    SELECT `id`,`username` 
    FROM `tp_admin`
) AS `a` ON `p`.`uid`=`a`.`id`
LEFT JOIN(
    -- 每个项目的投资总额
    SELECT `pid`,sum(`money`) AS `money` 
    FROM `tp_fund` 
    GROUP BY `pid`
) AS `f` ON `p`.`id`=`f`.`pid`
LEFT JOIN(
    -- 每个项目的投票价格
    SELECT `id`,`pid`,`money`
    FROM `tp_vote`
    WHERE `status` in ('run','success')
) AS `v` ON `p`.`id`=`v`.`pid`
LEFT JOIN(
    -- 每个项目的对应的投票的支持人数
    SELECT `vid`,count(*) AS `count`
    FROM `tp_vote_user`
    GROUP BY `vid`,`vote`
    HAVING `vote`= 'support'
) AS `vu` ON `v`.`id`=`vu`.`vid`
LEFT JOIN(
    -- 每个项目的注册时间
    SELECT `pid`,`time` FROM `tp_evolve` WHERE `event` = 'register'
) AS `register` ON `register`.`pid`=`p`.`id`
LEFT JOIN(
    -- 每个项目的筹款开始时间
    SELECT `pid`,`time` FROM `tp_evolve` WHERE `event` = 'fund_begin'
) AS `begin` ON `begin`.`pid`=`p`.`id`
LEFT JOIN(
    -- 每个项目的筹款结束时间
    SELECT `pid`,`time` FROM `tp_evolve` WHERE `event` = 'fund_end'
) AS `end` ON `end`.`pid`=`p`.`id`
LEFT JOIN(
    -- 每个项目的完成时间
    SELECT `pid`,`time` FROM `tp_evolve` WHERE `event` = 'complete'
) AS `complete` ON `complete`.`pid`=`p`.`id`


-- 用户投票记录表扩展视图
CREATE OR REPLACE VIEW `tp_vote_user_view` AS
SELECT `vu`.*,# 用户投票的基本信息
`v`.`pid`,# 投票对应的项目ID
`f`.`total`# 投票的权重(投票的人在此项目的投资总额)
FROM `tp_vote_user` AS `vu`
INNER JOIN(
    -- 每个投票的pid
    SELECT `id`,`pid`
    FROM `tp_vote`
) AS `v` ON `v`.`id`=`vu`.`vid`
INNER JOIN(
    -- 每个项目中每个人的投资总额
    SELECT `pid`,`uid`,sum(`money`) AS `total`
    FROM `tp_fund`
    GROUP BY `pid`,`uid`
) AS `f` ON `f`.`pid`=`v`.`pid` AND `f`.`uid`=`vu`.`uid`


-- 投票表扩展视图
CREATE OR REPLACE VIEW `tp_vote_view` AS
SELECT `v`.*,# 投票的基本信息
`f`.`total`,# 对应项目的投资总额
ifnull(`support`.`total`,0) AS `support`,# 投支持票的用户的投资总额
ifnull(`oppose`.`total`,0) AS `oppose`,# 投反对票的用户的投资总额
`begin`.`time` AS `begin_time`,# 投票开始时间
`end`.`time` AS `end_time`# 投票结束时间
FROM `tp_vote` AS `v`
LEFT JOIN(
    -- 每个项目的投资总额
    SELECT `pid`,sum(`money`) AS `total` 
    FROM `tp_fund`
    GROUP BY `pid`
) AS `f` ON `v`.`pid`=`f`.`pid`
LEFT JOIN(
    -- 每个投票中反对票的总额
    SELECT `vid`,`pid`,sum(`total`) AS `total`
    FROM `tp_vote_user_view`
    GROUP BY `vid`,`pid`,`vote`
    HAVING `vote` = 'support'
) AS `support` ON `support`.`vid`=`v`.`id`
LEFT JOIN(
    -- 每个投票中反对票的总额
    SELECT `vid`,`pid`,sum(`total`) AS `total`
    FROM `tp_vote_user_view`
    GROUP BY `vid`,`pid`,`vote`
    HAVING `vote` = 'oppose'
) AS `oppose` ON `oppose`.`vid`=`v`.`id`
LEFT JOIN(
    -- 每个项目的筹款开始时间
    SELECT `vid`,`time` FROM `tp_evolve` WHERE `event` = 'vote_begin'
) AS `begin` ON `begin`.`vid`=`v`.`id`
LEFT JOIN(
    -- 每个项目的筹款开始时间
    SELECT `vid`,`time` FROM `tp_evolve` WHERE `event` = 'vote_end'
) AS `end` ON `end`.`vid`=`v`.`id`
```