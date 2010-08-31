<?php

function tr()
{
   return 't';
}

function h1($foo, $bar)
{
   var_dump('handler 1', $foo, $bar);
}

class Hs
{
   function h2($foo, $bar)
   {
      var_dump('handler2', $foo, $bar);
   }
   
   function h3($foo, $bar)
   {
      var_dump('handler3', $foo, $bar);
   }
}

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
      
      //--
      
      $h4 = function($foo, $bar){var_dump('handler 4', $foo, $bar);};
      
      $hs = new Hs;
      
      PluginsCenter::registerEventHandler('testEvent', 'h1');
      PluginsCenter::registerEventHandler('testEvent', array('hs', 'h2'));
      PluginsCenter::registerEventHandler('testEvent', array($hs, 'h3'));
      PluginsCenter::registerEventHandler('testEvent', $h4);
      
      PluginsCenter::triggerEvent_array('testEvent', array('g1', 'g2'));
      
      //--
      
      echo '<hr>';
      
      $view1 = $this->load->view('testing-view');
      $view1->foo1 = 'bar1';
      $view1->bar1 = array(1,2,3);
      $view1->display();
      
      $view2 = view('watermelon/bar/foo', true);
      $view2->display();
   }
   
   function _actionHandler_()
   {
      echo str_repeat('actionhnd!', 5);
   }
}