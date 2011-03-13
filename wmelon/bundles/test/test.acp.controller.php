<?php

class Test_Controller extends Controller
{
   function index_action()
   {
      $this->pageTitle = 'Testy';
      
      echo '<a href="$/test/config/">Watermelon config</a><br>';
      echo '<a href="$/test/formats/">Formats</a><br>';
   }
   
   function formats_action()
   {
      $f1 = 'asd foo bar';
      $f2 = 'blah blah blah';
      $f3 = 'sth else';
      
      $data->f1 = $f1;
      $data->f2 = $f2;
      $data->f3 = $f3;
      
      switch($this->format)
      {
         case 'json': return $this->outputJSON($data);
         
         default:
            $this->pageTitle = 'Formaty';
            $this->outputView('formats', $data);
      }
   }
   
   
   function config_action()
   {
      var_dump(Watermelon::$config);
   }
}