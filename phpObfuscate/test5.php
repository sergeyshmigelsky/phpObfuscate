<?php
class Sample 
{
    var $Title;
    var $Content;
 
function setTitle($title) 
{
    $this->Title = $title;
}
 
function setContent($content) 
{
    $this->Content = $content;
}
 
function setAll($title, $content) 
{

    $this->setTitle($title);
    $this->setContent($content);

    //$this->Title = $title;
    //$this->Content = $content;
}
}
 
$newClass = new Sample(); 
$newClass->Title = "My title"; 
$newClass->setAll("Other title", "Test content"); 
echo $newClass->Content; 
?>