class Student {
    fullName: string;//参数，指定参数类型
    //构造函数  在构造函数参数中使用public是一种简写形式，它将自动创建具有该名称的属性
    constructor(public firstName, public middleInitial, public lastName) {
        this.fullName = firstName + " " + middleInitial + " " + lastName;
    }
}
//接口在编译时会被省略掉，而且使用时仅仅只需必要的结构形状，而不必有明确的implements子句
interface Person {
    firstName: string;
    lastName: string;
}

function greeter(person : Person) {
    return "Hello, " + person.firstName + " " + person.lastName;
}

var user = new Student("Jane", "M.", "User");

document.body.innerHTML = greeter(user);