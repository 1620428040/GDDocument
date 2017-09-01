<?php
require("../../../GDWriteToFile/main.php");
require("../../../common/php/curl.php");
require("../../../common/php/path.php");
require("../../main.php");

echo "test start <br/>";
$json=file_get_contents("source.json");
$params=json_decode($json,TRUE);
downloadMassFiles($params);

?>