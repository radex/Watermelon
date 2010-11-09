<?php

class Test_Controller extends Controller
{
   function index_action()
   {
      $this->pageTitle = 'Testy';
      
      echo '<a href="$/test/tables/">ACP Tables</a><br>';
      echo '<a href="$/test/questions/">Questions</a><br>';
   }
   
   function questions_action()
   {
      Watermelon::addMessage('error', 'Foo! Bar!');
      Watermelon::addMessage('warning', 'Foo! Bar!');
      Watermelon::addMessage('info', 'Foo! Bar!');
      Watermelon::addMessage('tick', 'Foo! Bar!');
      Watermelon::addMessage('tip', 'Foo! Bar!');
      
      
      echo '
      
      <div class="questionBox">
         <strong>Czy na pewno chcesz usunąć 15 artykułów o tytułach:</strong>
         <ul>
         <li>Blah blah foo bar lorem ipsum
         <li> oiac uias iocu aiucoauscoiu
         </ul>
         <menu>
            <input type="button" value="Anuluj" onclick="history.back()" autofocus>
            <input type="button" value="Tak">
         </menu>
      </div>
      
      ';
      
      echo questionBox('Foo!', 'bar');
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