<?php

class Test_Controller extends Controller
{
   function index_action()
   {
      $this->pageTitle = 'Testy';
      
      echo '<a href="$/test/tables/">ACP Tables</a><br>';
   }
   
   function tables_action()
   {
      $table = new ACPTable;
      
      // header
      
      $table->header = array('Foo', 'Bar', 'Akcje');
      
      // data
      
      $table->addLine(9, 'Foo', 'Bar', 'asd');
      $table->addLine(11, 'Foo2', 'Bar2', 'asd2');
      
      // options
      
      // $table->isCheckbox = false;
      $table->selectedActions[] = array('Usuń', 'test/tables/delete/');
      $table->selectedActions[] = array('Edytuj', 'test/tables/edit/');
      
      // $table->isPagination = false;
      $table->currentPage = 5;
      $table->lastPage = 10;
      $table->pageLink = 'test/tables/page:';
      
      echo $table->generate();
   }
}