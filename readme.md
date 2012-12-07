
Usage
=====

For example, we have this source code in file test2.php

<pre><code>
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
</code></pre>

After applying function obfuscate (see file obfuscate.php) 
source code will be obfuscated

<pre><code>class cc
{
	var $vv = 0;
	function ff($vvv)
	{
		$this->vv = $this->vv + $vvv;		
	}
	function fff($vvv)
	{
		$this->vv = $this->vv - $vvv;		
	}
}
$vvvv = new cc();
$vvvv->ff(10);
$vvvv->fff(2);
echo $vvvv->vv;
</code></pre>

Also, script will remove breaklines and comments