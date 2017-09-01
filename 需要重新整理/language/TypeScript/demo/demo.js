var __extends = (this && this.__extends) || (function () {
    var extendStatics = Object.setPrototypeOf ||
        ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
        function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
//实体
var Entity = (function () {
    function Entity(name) {
        this.name = name;
    }
    return Entity;
}());
//动物
var Animal = (function (_super) {
    __extends(Animal, _super);
    function Animal() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    Animal.prototype.attack = function (target) {
        console.log(this.name + "\u653B\u51FB\u4E86" + target.name);
        return true;
    };
    return Animal;
}(Entity));
//食草动物
var Vegetarian = (function (_super) {
    __extends(Vegetarian, _super);
    function Vegetarian() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    Vegetarian.prototype.attack = function (target) {
        console.log(this.name + "\u5543\u4E86" + target.name);
        return true;
    };
    return Vegetarian;
}(Animal));
//植物
var Plant = (function (_super) {
    __extends(Plant, _super);
    function Plant() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    return Plant;
}(Entity));
//通过草，羊，狼之间的攻击行为的不同，演示类之间的继承关系
var sheep = new Vegetarian("羊");
var gress = new Plant("草");
var wolf = new Animal("狼");
sheep.attack(gress);
wolf.attack(sheep);
sheep.attack(wolf);
