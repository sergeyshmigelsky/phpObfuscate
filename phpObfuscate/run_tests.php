 <?php
 define('test_count', 6);
 require_once($_SERVER['DOCUMENT_ROOT'].'/phpObfuscate/obfuscate.php');
 for ($i=1; $i<=test_count;$i++)
 {
 obfuscate("/phpObfuscate/test$i.php");
 };
 ?>