<?php

class test_Controller
{
   function index_action($a, $b, $c)
   {
      var_dump($a, $b, $c);
      echo str_repeat('index!', 5);
   }
   
   function test_action()
   {
      echo str_repeat('test!', 5);
   }
   
   function _actionHandler()
   {
      echo str_repeat('actionhnd!', 5);
   }
}