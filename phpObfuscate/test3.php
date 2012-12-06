<?php
function my_func($x, $y)
{
return $x*$y;
}
function my_other_func($x, $y)
{
return $x*$x+$y*$y;
}

$a = 10;
$b = 5;
echo my_func($a, $b)."<br/>";
echo my_other_func($a, $b);
?>