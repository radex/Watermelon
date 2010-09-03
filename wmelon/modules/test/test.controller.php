<?php

class test_Controller extends Controller
{
   function index_action($foo, $bar, $a = 5)
   {
      echo str_repeat('index!', 5);
   }
   
   function test_action()
   {
      // Models
      
      echo '<hr>';
      
      echo $this->load->model('TESTmodel')->foo;
      echo model('TesTmodel2')->foo2;
      
      // Blocks
      
      echo '<hr>';
      
      $this->load->blockSet('TEST')->foo();
      $this->load->blockSet('TEST')->bar('1', '2');
      BlockSet('TesT2')->foo2();
      BlockSet('TesT2')->bar2('3', '4');
      
      // Views
      
      echo '<hr>';
      
      $view1 = $this->load->view('Testing-VIEW');
      $view1->foo1 = 'bar1';
      $view1->bar1 = array(1,2,3);
      $view1->display();
      
      $view2 = view('WATERmelon/bar/FOO', true);
      $view2->display();
   }
   
   function dbtest_action()
   {
      /*
      define('DBTEST','');
      $arg1 = '"Blah blah %2"';
      $arg2 = '"Foo bar %1"';
      
      DB::query("%1    %2 fooo %1", $arg1, $arg2);
      define('AFTER','');
      */
   }
   
   function _actionHandler_()
   {
      echo str_repeat('actionhnd!', 5);
   }
}