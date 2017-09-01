//实体
abstract class Entity{
	constructor(public name){
		
	}
	// public name:string;
	// constructor(name) {
	// 	this.name=name;
    // }
}
//攻击行为的接口
interface attackAction{
	attack(target:Entity):boolean;
}
//动物
class Animal extends Entity implements attackAction{
	attack(target:Entity):boolean{
		console.log(`${this.name}攻击了${target.name}`);
		return true;
	}
}
//食草动物
class Vegetarian extends Animal{
	attack(target:Plant):boolean{
		console.log(`${this.name}啃了${target.name}`);
		return true;
	}
}
//植物
class Plant extends Entity{
	
}
//通过草，羊，狼之间的攻击行为的不同，演示类之间的继承关系
var sheep=new Vegetarian("羊");
var gress=new Plant("草");
var wolf=new Animal("狼");
sheep.attack(gress);
wolf.attack(sheep);
sheep.attack(wolf);
