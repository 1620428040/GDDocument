scr.innerText='alert(str);function say(word){alert("say:"+word);}';
var bod = document.getElementById("bod");
bod.appendChild(scr);

say("hello");