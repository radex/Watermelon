<?php

class Test_Extension extends Extension
{
   public $foo = 'bar';
   
   public static function onAutoload()
   {
      //echo 'Test!';
   }
}