#源文件声明规则
// 在本节的最后部分，我们将学习源文件的声明规则。当在一个源文件中定义多个类，并且还有import语句和package语句时，要特别注意这些规则。

// 一个源文件中只能有一个public类 
// 一个源文件可以有多个非public类 
// 源文件的名称应该和public类的类名保持一致。例如：源文件中public类的类名是Employee，那么源文件应该命名为Employee.java。 
// 如果一个类定义在某个包中，那么package语句应该在源文件的首行。 
// 如果源文件包含import语句，那么应该放在package语句和类定义之间。如果没有package语句，那么import语句应该在源文件中最前面。 
// import语句和package语句对源文件中定义的所有类都有效。在同一源文件中，不能给不同的类不同的包声明。 
// 类有若干种访问级别，并且类也分不同的类型：抽象类和final类等。这些将在访问控制章节介绍。

// 除了上面提到的几种类型，Java还有一些特殊的类，如：内部类、匿名类。

//javac编译  java运行  java -version显示版本

#文件类型
//java	源文件
//class	类文件，可以直接执行
//jar	打包文件，需要专门的工具打包
//apk	android手机专用的格式

#Java包	package
//包主要用来对类和接口进行分类。当开发Java程序时，可能编写成百上千的类，因此很有必要对类和接口进行分类。
//包的名称必须和class文件所在的路径结构相同

#Import语句
// 在Java中，如果给出一个完整的限定名，包括包名、类名，那么Java编译器就可以很容易地定位到源代码或者类。Import语句就是用来提供一个合理的路径，使得编译器可以找到某个类。
// 例如，下面的命令行将会命令编译器载入java_installation/java/io路径下的所有类
// import java.io.*;//包含一个包
// import payroll.Employee;//包含一个类
//	没有包含相应的包或者类名可能有冲突时	使用类的全名payroll.Employee


#基本数据类型和包装类
//byte,short,int,long,float,double,boolean,char
//基础数据类型都有对应的包装类，比如java.lang.Integer，定义了Integer.SIZE,Integer.MIN_VALUE,Integer.MAX_VALUE等常量


#装箱与拆箱
Integer x = 5; // boxes int to an Integer object
x =  x + 10;   // unboxes the Integer to a int
System.out.println(x); 
//这种由编译器特别支持的包装称为装箱，所以当内置数据类型被当作对象使用的时候，编译器会把内置类型装箱为包装类。相似的，编译器也可以把一个对象拆箱为内置类型。Number类属于java.lang包。 


#输出
System.out.println("hello world"); 

#String/StringBuilder/StringBuffer
//String字符串一旦赋值，不能修改（但可以用"+"或者concat函数拼接）
//StringBuilder可以修改，而且不会产生多余的未使用字符串，速度比StringBuffer快
//StringBuffer是线程安全的

//创建格式化字符串
String fs;
fs = String.format("The value of the float variable is " +
                   "%f, while the value of the integer " +
                   "variable is %d, and the string " +
                   "is %s", floatVar, intVar, stringVar);
System.out.println(fs);

#数组和Arrays对象

