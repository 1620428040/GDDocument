//实现js中自定义类的方式（基于函数，独享参数，共享函数）
function Person(name) {
	this.name = name;
	if (Person.prototype.say == undefined) {
		Person.prototype.say = function() {
			alert("I am " + this.name);
		}
	}
}
var p1 = new Person("wang");
var p2 = new Person("li");
p1.say();
p2.say();
alert(p1.say == p2.say); //true


//			var lab=new Label("23333");
//			//document.getElementById("test").appendChild(lab.view);
//			//lab.text("66666");
//			var vi=new View();
//			vi.superView(document.getElementById("test"));
//			vi.add(lab.view);
			
			var obj=new GDObject();
			obj.method("add",function(num1,num2){
				return num1+num2;
			})
			var num3=obj.add(1,2);
			alert(num3);
		
		
			function create(tagName,text){
				var view=document.createElement(tagName);
				var node=document.createTextNode(text);
				view.appendChild(node);
				return view;
			}
			//自定义的两种控件
			function Label(text){
				this['key']="value";
				this.view=document.createElement("p");
				var node=document.createTextNode(text);
				this.view.appendChild(node);
				
				if(Label.prototype.text==null){
					Label.prototype.text=function(text){
						this.view.innerHTML=text;
						return this.view.innerHTML;
					}
				}
			}
			function View(){
				this.view=document.createElement("div");
				if(View.prototype.superView==null){
					View.prototype.superView=function(superView){
						superView.appendChild(this.view);
						return superView;
					}
				}
				if(View.prototype.add==null){
					View.prototype.add=function(subview){
						this.view.appendChild(subview);
						return true;
					}
				}
			}
			//可以实现用函数创建方法，但这样编辑器不会有提示！
			function GDObject(){
				if(GDObject.prototype.method==undefined){
					GDObject.prototype.method=function(name,method){
						if(GDObject.prototype[name]==undefined){
							GDObject.prototype[name]=method;
							return true;
						}
						else{
							return false;
						}
					}
				}
			}