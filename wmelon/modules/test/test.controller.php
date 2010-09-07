<?php

class test_Controller extends Controller
{
   function index_action($foo, $bar, $a = 5)
   {
      echo '<a href="$/test/modules">Modules tests</a><br>';
      echo '<a href="$/test/translations">Translations tests</a><br>';
      echo '<a href="$/test/dbtest">DB tests</a><br>';
      echo '<a href="$/test/cache">Cache tests</a><br>';
   }
   
   function cache_action()
   {
      
   }
   
   function modules_action()
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
   
   function translations_action()
   {
      Translations::addCodeTranslation('testScope', 'foobar', 'return "foo:" . $args[1] . ", bar:" . $args[2];');
      var_dump(gtr('testScope', 'foobar', 10, 15));

      Translations::addTextTranslation('testScope', 'test', 'Apples: %1, Watermelons: %2');
      var_dump(gtr('testScope', 'test', 1, 60));

      Loader::translation('test');

      var_dump(gtr('test', 'He had %1 apples and %2 watermelons', 50, 1050));
      var_dump(gtr('test', 'He liked/didn\'t like watermelons', true));
      var_dump(gtr('test', 'He liked/didn\'t like watermelons', false));
   }
   
   function dbtest_action()
   {
      /*
      $arg1 = '"Blah blah %2"';
      $arg2 = '"Foo bar %1"';
      
      DB::query("%1    %2 fooo %1", $arg1, $arg2);
      */
   }
   
   function _actionHandler_()
   {
      echo str_repeat('actionhnd!', 5);
   }
}