<?php
$finfo = finfo_open(FILEINFO_MIME);
$mimetype = finfo_file($finfo, "D:\\system\\Desktop\\123.doc");
echo $mimetype;
finfo_close($finfo);
echo mime_content_type("D:\\system\\Desktop\\123.doc");
?>