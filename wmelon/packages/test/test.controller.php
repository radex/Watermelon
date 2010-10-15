<?php

class Test_Cache extends FileCache
{
   protected static function directory()
   {
      return 'test';
   }
}

class test_Controller extends Controller
{
   function index_action($foo, $bar, $a = 5)
   {
      echo '<a href="$/test/modules">Modules tests</a><br>';
      echo '<a href="$/test/translations">Translations tests</a><br>';
      echo '<a href="$/test/dbtest">DB tests</a><br>';
      echo '<a href="$/test/cache">Cache tests</a><br>';
      echo '<a href="$/test/tal">PHPTAL views</a><br>';
      echo '<a href="$/test/skinviews">Skin views</a><br>';
      echo '<a href="$/test/user">Logging in, etc.</a><br>';
      echo '<a href="$/test/output">Output types</a><br>';
      echo '<a href="$/test/css">Boxes, and more...</a><br>';
   }
   
   function css_action()
   {
      echo '<div class="errorBox">Ostatnie szlify systemu. Wyższe założenie ideowe, a także wykorzystanie unijnych dotacji pomaga w określaniu odpowiednich warunków aktywizacji. Wyższe założenie ideowe, a szczególnie wdrożenie nowych, lepszych rozwiązań wymaga niezwykłej precyzji w kształtowaniu modelu rozwoju. Przez ostatnie kilkanaście lat odkryliśmy że dokończenie aktualnych projektów jest ważne z powodu systemu szkolenia kadry odpowiadającego.</div>';
      
      echo '<div class="warningBox">Co mamy na stałe zabezpieczenie informacyjne naszej działalności koliduje z tym, że rozszerzenie naszej kompetencji w wypracowaniu istniejących kryteriów rozszerza nam horyzonty postaw uczestników wobec zadań programowych wymaga sprecyzowania i realizacji nowych propozycji. Mając na uwadze, że rozszerzenie bazy o nowe rekordy pomaga w restrukturyzacji przedsiębiorstwa. Nie zapominajmy jednak, że.</div>';
      
      echo '<div class="infoBox">Wszystko po kolei. Wagi i bogate doświadczenia pozwalają na stałe zabezpieczenie informacyjne naszej kompetencji w przygotowaniu i znaczenia tych problemów nie trzeba udowadniać, ponieważ zmiana istniejących kryteriów ukazuje nam efekt kolejnych kroków w wypracowaniu systemu szkolenia kadry odpowiadającego potrzebom. Nie damy się. Często niezauważanym szczegółem jest nieunikniony. Gdy za 4.</div>';
      
      echo '<div class="tickBox">Jednakże, inwestowanie w przyszłościowe rozwiązania wymaga sprecyzowania i realizacji obecnej sytuacji. Wagi i miejsce ostatnimi czasy, dobitnie świadczy o tym, że zakup nowego sprzętu zmusza nas do przeanalizowania nowych propozycji. Różnorakie i określenia form oddziaływania. Mając na rozpoczęcie powszechnej akcji kształtowania podstaw jest to, iż zmiana przestarzałego systemu szkolenia kadr.</div>';
      
      echo '<div class="tipBox">Nasza propozycja. W praktyce nowy model działalności powoduje docenianie wag modelu rozwoju. Już nie trzeba udowadniać, ponieważ zakup nowego sprzętu powoduje docenianie wag kierunków postępowego wychowania. Do tej sprawy wymaga sprecyzowania i rozwijanie struktur koliduje z szerokim aktywem jest ważne zadanie w określaniu systemu obsługi pomaga w przygotowaniu i unowocześniania.</div>';
      
      echo '<h1>Foo</h1>
      <p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum </p>
      <h2>Foo</h2>
      <p>dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum. Integer aliquam purus. Quisque lorem tortor fringilla sed, vestibulum id, eleifend justo vel bibendum sapien massa ac turpis faucibus orci luctus non, consectetuer lobortis quis, varius in, purus</p>
      <h3>Foo</h2>
      <p>Integer ultrices posuere cubilia Curae, Nulla ipsum dolor lacus, suscipit adipiscing. Cum sociis natoque penatibus et ultrices volutpat. Nullam wisi ultricies a, gravida vitae, dapibus risus ante sodales lectus blandit eu, tempor diam pede cursus vitae, ultricies eu, faucibus quis, porttitor eros cursus lectus, pellentesque eget, bibendum a, gravida ullamco</p>';
   }
   
   //--
   
   function output_action()
   {
      echo '<a href="$/test/plain_output">Plain</a><br>';
      echo '<a href="$/test/xml_output">XML</a><br>';
   }
   
   function plain_output_action()
   {
      $this->tal_action();
      $this->requestedOutputType = self::Plain_OutputType;
   }
   
   function xml_output_action()
   {
      $output = new SimpleXMLElement('<xml></xml>');
      $output->foo = 'bar';
      $output->addChild('foo');
      $output->foo[1]['foo'] = 'bar';
      $output['foo'] = 'bar';
      
      $this->output = $output;
      $this->requestedOutputType = self::XML_OutputType;
   }
   
   //--
   
   private function logged()
   {
      return (Auth::$isLogged ? 'true' : 'false');
   } 
   
   function user_action()
   {
      echo 'Logged:' . $this->logged() . '<br>';
      
      echo '<a href="$/test/user_login">Log in</a><br>';
      echo '<a href="$/test/user_logout">Log out</a><br>';
   }
   
   function user_login_action()
   {
      echo 'Logged:' . $this->logged() . '<br>';
      
      Auth::login('radex', 'qwerty');
      
      echo 'Logged:' . $this->logged() . '<br>';
   }
   
   function user_logout_action()
   {
      echo 'Logged:' . $this->logged() . '<br>';
      
      Auth::logout();
      
      echo 'Logged:' . $this->logged() . '<br>';
   }
   
   //--
   
   function skinviews_action()
   {
      echo 'Foo:';
      
      View('foo')->display();
      
      echo '<hr>Bar:';
      
      View('bar')->display();
      
      echo '<hr>watermelon/bar/foo:';
      
      View('watermelon/bar/foo', true)->display();
   }
   
   function tal_action()
   {
      $view = View('phptal');
      $view->foo = 'test';
      $view->bar = array('a','b');
      $view->display();
   }
   
   function cache_action()
   {
      Test_Cache::save('foo', 'bar');
      
      //--
      
      try
      {
         Test_Cache::fetch('asd');
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Cache:doesNotExist')
         {
            echo 'Doesn\'t exist';
         }
         else
         {
            throw $e;
         }
      }
      
      //--
      
      var_dump(Test_Cache::fetch('foo'));
      var_dump(Test_Cache::doesExist('foo'));
      var_dump(Test_Cache::doesExist('dupah'));
      
      Test_Cache::save('foo1','');
      Test_Cache::save('foo12','');
      Test_Cache::save('foo13','');
      Test_Cache::save('foo14','');
      Test_Cache::save('foo15','');
      
      Test_Cache::save('bar', 'some\'thing');
      var_dump(Test_Cache::fetch('bar'));
      
      Test_Cache::delete('foo1', 'foo14', 'foo15');
      Test_Cache::clear();
      
      //-----
      
      $c = new stdClass;
      $c->foo = 'bar';
      $c = array($c, 'foo');
      
      GenericCache::save(array('foo', 'bar'), $c);
      
      //--
      
      var_dump(GenericCache::fetch(array('foo', 'bar')));
      var_dump(GenericCache::doesExist(array('foo', 'bar')));
      var_dump(GenericCache::doesExist('dupah'));
      
      GenericCache::save('foo1', '');
      GenericCache::save('foo2', '');
      
      GenericCache::delete('foo1', 'foo2');
      GenericCache::clear();
      
      //-----
      
      $c = array();
      $c[] = array("asddas'as das da's da's d'a\" asda sdasd", 'fofoofofof %1 asff %2 \%3');
      $c[] = array('foo', 'baaarrr');
      
      TranslationsCache::save(array('test','pl'),$c);
      //TranslationsCache::fetch(array('test','pl'));
      
      //var_dump(Translations::$translations);
      
      TranslationsCache::delete(array('test','pl'));
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