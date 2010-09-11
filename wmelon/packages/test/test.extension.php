<?php

class Test_Extension extends Extension
{
   public $foo = 'bar';
   
   public function onAutoload()
   {
      //echo 'Test!';
   }
}