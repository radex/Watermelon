<?php

class Test_Controller extends Controller
{
   function index_action()
   {
      $this->pageTitle = 'Testy';
      
      echo '<a href="$/test/config/">Watermelon config</a><br>';
   }
   
   function config_action()
   {
      var_dump(Watermelon::$config);
   }
}