<?php
require("../../../GDWriteToFile/main.php");
require("../../../common/php/curl.php");
require("../../../common/php/path.php");
require("../../main.php");

echo "test start <br/>";

//从文件中找出相关的链接
filter("a.html", "save.html","/http:\/\/[^\ \"\'\)]{0,}/");
//filter("save.html", "save.html","/src=\"[^\"]{0,}\"/");
//filter("a.html", "save.html","/(?<=\"|\')[^\"\']{0,}?\/([^\/\"\']{0,}\.(js|css|gif|png|jpg|html|htm))(?=\"|\')/");

?>