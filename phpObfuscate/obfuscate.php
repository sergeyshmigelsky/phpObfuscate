<?php
define('JOKER', 'v');
define('FUNCY', 'f');
define('CLS', 'c');
define('OBF_LOG', true);
function repeat_chars($char, $count)
{
 $result = "";
 for ($i=1; $i<$count;$i++)
 {
 $result .= $char;
 };
 return $result;
}
function my_str_replace($str, $pattern, $replacement)
{
 $result = $str;
 for ($i=0; $i<77; $i++)
 {	
		$result = str_replace($pattern, $replacement, $result);	
 };
 return $result;
}
function is_delimeter($char)
{			
	$result = (($char == '}') || ($char ==')') || ($char == '+') || ($char == '['));
	$result = $result || (($char == '(') || ($char ==']') || ($char == '!'));
	$result = $result || (($char == ';') || ($char ==',') || ($char == ' '));
	$result = $result || (($char == '=') || ($char =='*') ||($char == '/'));
	$result = $result || (($char == '%') || ($char == "\'") || ($char == "."));
	$result = $result || (($char == '>') || ($char == '<') || ($char == "\""));
	$result = $result || (($char == '*') || ($char == '@') || ($char == "#"));
	return $result;
}

function has_delimeters($string)
{
	$result = false;
	$l = strlen($string);
	for ($i = 0; $i<$l; $i++)
	{
		$char = substr($string, $i, 1);
		if ((is_delimeter($char)) || ($char == "'") || ($char == ":") || ($char == "-") ) 
		{$result = true;break;};
	};
	return $result;
}

function replace_function_names($contents)
{
	 $result = $contents;
	 $l = strlen($result);
	 $startChar = 0;
	 $func_names = array();
	 while ($startChar<$l)
	 {
		$pos = strpos($result, 'function ', $startChar);
		if ($pos === false) {break;} else {$startChar = $pos+1;};
		$pos2 = strpos($result, '(', $pos);
		$f = substr($result, $pos, $pos2-$pos);
		// echo $f ."<br/>";
		$f = substr($f, strpos($f, 'function ')+strlen('function '));
		if (not_special_function($f))
		{
		$func_names[] =  $f;
		}
		$startChar++;
	 };
	 array_unique($func_names);
	 my_sort($func_names);
	
	foreach ($func_names as $index => $func_name)
	{
		$result = my_str_replace($result, $func_name,  repeat_chars(FUNCY, $index+3));
	}; 
	
	if (OBF_LOG)
{
echo '<h1>Functions</h1>';
foreach ($func_names as $index=>$func)
{
 echo $func."<br/>";
};	
}
	
	return $result;
}


function replace_class_names($contents)
{
	 $result = $contents;
	 $l = strlen($result);
	 $startChar = 0;
	 $class_names = array();
	 while ($startChar<$l)
	 {
		$pos = strpos($result, 'class ', $startChar);
		if ($pos === false) {break;} else {$startChar = $pos+1;};
		$pos2 = strpos($result, '{', $pos);
		$f = trim(substr($result, $pos, $pos2-$pos));
		// echo $f ."<br/>";
		$class_names[] = substr($f, strpos($f, 'class ')+strlen('class '));
		$startChar++;
	 };
	 array_unique($class_names);
	 my_sort($class_names);
	
	foreach ($class_names as $index => $class)
	{
		$result = my_str_replace($result, $class,  repeat_chars(CLS, $index+3));
	}; 
	
	if (OBF_LOG)
{
echo '<h1>Classes</h1>';
foreach ($class_names as $index=>$class)
{
 echo $class."<br/>";
};	
}
	
	return $result;
}

function not_special_function($func)
{
  $result = true;
  $result = (strpos($func,'_construct')===false);
  $result = $result && (strpos($func,'_destruct')===false);
  return $result;
}

function not_global_array($name)
{
  $result = true;
  $result = (strpos($name,'$_SERVER')===false);
  $result = $result && (strpos($name,'$_POST')===false);
  $result = $result && (strpos($name,'$_GET')===false);
  $result = $result && (strpos($name,'$_SESSION')===false);
  $result = $result && (strpos($name,'$_COOKIE')===false);
  $result = $result && (strpos($name,'$_SERVER')===false);
  return $result;
}

function compare_length($str1, $str2)
{
 (strlen($str1)>strlen($str2)) ? $result = -1 : $result = +1;
 return $result;
}

function my_sort(&$arr)
{
	@usort($arr, compare_length);
}

function get_source($path)
{
	if (OBF_LOG)
{
echo "<h3>Get source from $path</h3>";
};
$fh = fopen($_SERVER['DOCUMENT_ROOT'].$path, "r");
$contents = "";
$contents = fread($fh, filesize($_SERVER['DOCUMENT_ROOT'].$path));	
fclose($fh);
return $contents;
}

function replace_variable_names($contents)
{
$variables = array();
$startChar = 0;
$varfound = -1;
while ($startChar<=strlen($contents))
{
	if (substr($contents, $startChar, 1) == '$')
	{
		$varfound = $startChar;									
	};
	if (is_delimeter(substr($contents, $startChar, 1)) && ($varfound>-1))
	{
		$variable = substr($contents, $varfound, $startChar-$varfound);
		$startChar = $varfound+1;
		
		$varfound = -1;		
		
		
		if ((!in_array($variable, $variables)) && not_global_array($variable) && (!has_delimeters($variable))) 		  
			{
				$variables[]=$variable;				
			};
			continue;
			
	};
	$startChar++;
};
my_sort($variables);
$variables = array_unique($variables);
if (OBF_LOG)
{
echo '<h1>Variables</h1>';
foreach ($variables as $index=>$variable)
{
 echo $variable."<br/>";
};	
}
foreach ($variables as $index => $variable_name)
	{
		$contents = my_str_replace($contents, '->'.substr($variable_name, 1),  '->'.repeat_chars(JOKER, $index+3));
	};	

foreach ($variables as $index => $variable_name)
	{
		$contents = my_str_replace($contents, $variable_name,  '$'.repeat_chars(JOKER, $index+3));
	};
return $contents;	
}

function save_obfuscated($source, $contents)
{
$dest = $_SERVER['DOCUMENT_ROOT'].$source.".src";
$fh = fopen($dest, "w+");
fwrite($fh, $contents);
fclose($fh);
if (OBF_LOG)
{
echo "<h3>Saved to $dest</h3>";
};
}

function obfuscate($source)
{
$contents = get_source($source);
$contents = replace_class_names($contents);
$contents = replace_function_names($contents);
$contents = replace_variable_names($contents);
save_obfuscated($source, $contents);
}
?>