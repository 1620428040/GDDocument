一些现代的浏览器开始支持WebGL了，WebGL技术基于OpenGL和H5中的canvas，用来在网页中高效的绘制3D场景
基础教程：http://www.hewebgl.com/article/articledir/1
中文API：http://techbrood.com/threejs/docs/#参考手册/数学工具库(Math)/四维矩阵(Matrix4)

在Three.js中，要渲染物体到网页中，我们需要3个组件：场景（scene）、相机（camera）和渲染器（renderer）
场景是所有物体的容器
相机决定了场景中那个角度的景色会显示出来(分为透视投影和正投影)
渲染器决定了渲染的结果应该画在页面的什么元素上面，并且以怎样的方式来绘制(渲染器renderer的domElement元素，表示渲染器中的画布)

渲染有两种方式：实时渲染和离线渲染 。
先看看离线渲染，想想《西游降魔篇》中最后的佛主，他肯定不是真的，是电脑渲染出来的，其画面质量是很高的，它是事先渲染好一帧一帧的图片，然后再把图片拼接成电影的。这就是离线渲染。如果不事先处理好一帧一帧的图片，那么电影播放得会很卡。CPU和GPU根本没有能力在播放的时候渲染出这种高质量的图片。
实时渲染：就是需要不停的对画面进行渲染，即使画面中什么也没有改变，也需要重新渲染

物体的几何特征和材质
var geometry = new THREE.CubeGeometry(1,1,1);//创建一个长方形的几何体
var material = new THREE.MeshBasicMaterial({color: 0x00ff00});//创建一个绿色的材质
var cube = new THREE.Mesh(geometry, material);//将材质贴到几何体上
scene.add(cube);//将长方形添加到场景中

计算机的3D模型
在计算机世界里，3D世界是由点组成，两个点能够组成一条直线，三个不在一条直线上的点就能够组成一个三角形面，无数三角形面就能够组成各种形状的物体
我们通常把这种网格模型叫做Mesh模型。给物体贴上皮肤，或者专业点就叫做纹理，那么这个物体就活灵活现了。最后无数的物体就组成了我们的3D世界

线宽
在three.js中是没有线宽这个概念的。线宽这个概念只在2D绘图中有效
WebGLRenderer	3D渲染器
CanvasRenderer	2D渲染器，用2D的方式模拟3D图形

右手坐标系
Threejs使用的是右手坐标系，这源于opengl默认情况下，也是右手坐标系。

绘制线
var p1 = new THREE.Vector3( -100, 0, 100 );//创建一个三维向量作为一个点
var p2 = new THREE.Vector3( 100, 0, -100 );
var geometry = new THREE.Geometry();//创建一个图形
geometry.vertices.push(p1);//vertices是一个数组，可以存放若干个点
geometry.vertices.push(p2);
var line = new THREE.Line( geometry, THREE.LineBasicMaterial, THREE.LinePieces );

游戏循环、让图形动起来
function animation()
{
    //renderer.clear();
    camera.position.x =camera.position.x +1;
    renderer.render(scene, camera);
    requestAnimationFrame(animation);//requestAnimationFrame调用指定的函数
}

性能监视器
Stats类，用来检测FPS值

动画引擎Tween.js
可以和three结合的动画引擎，不过没什么用，很多时候还是得自己写函数控制物体的移动

光源和材质
光源可以分为环境光、点光源、方向光等很多种
不同的材质，对光的反射、散射等效果是不同的
当有多个光源存在时，效果是叠加的(色彩也遵循彩色光的叠加效果)

纹理
THREE.Texture( image, mapping, wrapS, wrapT, magFilter, minFilter, format, type, anisotropy )
要给图形添加纹理，需要指定一个mapping