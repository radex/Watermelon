<?php

class Test_Controller extends Controller
{
   function index_action()
   {
      $this->pageTitle = 'Testy';
      
      echo '<a href="$/test/tables/">ACP Tables</a><br>';
      echo '<a href="$/test/textile/">Textile</a><br>';
      echo '<a href="$/test/config/">Watermelon config</a><br>';
   }
   
   function config_action()
   {
      var_dump(Watermelon::$config);
   }
   
   function textile_action()
   {
      $textile = <<<TEXTILE
*foo* _bar_ %{color:red}buhaha%

Just don't

"Foobar"

'foobar'

just don't's

as (TM) asd(C) foo bar (R)

foo-bar -- asd --- hehe... 

1024x768

<notextile>
   <pre class="brush: php">
      \$foo = bar('qwerty');
   </pre>
</notextile>

<code(php)>
   \$foo = bar('qwerty');
   <foo></foo>
</code>

<exec>
   return time();
</exec>

TEXTILE;
      
      $textile = Textile::textile($textile);
      
      var_dump($textile);
      
      echo $textile;
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