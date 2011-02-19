<?php

class Test_Controller extends Controller
{
   function index_action()
   {
      $this->pageTitle = 'Testy';
      
      echo '<a href="$/test/config/">Watermelon config</a><br>';
      
      var_dump($_GET);
   }
   
   function config_action()
   {
      var_dump(Watermelon::$config);
   }
}