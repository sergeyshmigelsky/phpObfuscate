<?php

class One
{
	var $x = 10;
}

class Two
{
	var $composite = null;
	function __construct()
	{
		$this->composite = new One();
	}
	function action($test)
	{
		echo "<h1>$test</h1>";
	}
}

$example = new Two();
$example->action($example->composite->x);

?>