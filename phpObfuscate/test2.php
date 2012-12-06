<?php
class test
{
	var $x = 0;
	function add($y)
	{
		$this->x = $this->x + $y;		
	}
	function sub($y)
	{
		$this->x = $this->x - $y;		
	}
}
$c = new test();
$c->add(10);
$c->sub(2);
echo $c->x;
?>