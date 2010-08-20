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