<?php

class test_Controller extends Controller
{
   function index_action($foo, $bar, $a = 5)
   {
      Watermelon::displayNoPageFoundError();
      return;
      echo str_repeat('index!', 5);
      $model = model('testMODEL');
      var_dump($model->foo);
   }
   
   function test_action()
   {
      echo str_repeat('test!', 5);
   }
   
   function _actionHandler_()
   {
      echo str_repeat('actionhnd!', 5);
   }
}