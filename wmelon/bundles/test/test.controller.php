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
   function index_action()
   {
      $this->pageTitle = 'Testy';
      
      echo '<a href="$/test/translations">Translations tests</a><br>';
      echo '<a href="$/test/cache">Cache tests</a><br>';
      echo '<a href="$/test/output">Output types</a><br>';
      echo '<a href="$/test/curl">cURL and stuff</a><br>';
      echo '<a href="$/test/phptal">PHPTAL tests</a><br>';
      echo '<a href="$/test/form">Form generator</a><br>';
   }
   
   function formSubmit_action()
   {
      $result = Form::validate();
      
      var_dump($result);
   }
   
   function form_action()
   {
      $form = new Form('test/formSubmit', 'test/form');
      
      $input1 = new TextFormInput('foo', 'Foo:');
      $input1->value = 'bar';
      $input1->required = false;
      
      $form->addInputObject($input1);
      
      $form->addInput('text', 'foo2', 'Foo2:', true, array('value' => 'test', 'maxlength' => 5));
      
      $result = $form->generate();
      
      var_dump($result);
      
      echo '<hr>';
      
      echo $result;
   }
   
   //--
   
   function phptal_action()
   {
      View('phptal')->display();
   }
   
   //--
   
   function testtext_action()
   {
      $this->outputType = self::Plain_OutputType;
      
      echo 'Foo!';
   }
   
   function curl_action()
   {
      echo 'cURL:';
      
      $ch = curl_init();
      
      curl_setopt($ch, CURLOPT_URL, 'http://localhost/w/test/testtext');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
      echo curl_exec($ch);
      
      echo '<hr>';
      
      echo 'file_get_contents:';
      
      echo file_get_contents('http://localhost/w/test/testtext');
   }
   
   //--
   
   function output_action()
   {
      echo '<a href="$/test/plain_output">Plain</a><br>';
      echo '<a href="$/test/xml_output">XML</a><br>';
   }
   
   function plain_output_action()
   {
      $this->index_action();
      $this->outputType = self::Plain_OutputType;
   }
   
   function xml_output_action()
   {
      $output = new SimpleXMLElement('<xml></xml>');
      $output->foo = 'bar';
      $output->addChild('foo');
      $output->foo[1]['foo'] = 'bar';
      $output['foo'] = 'bar';
      
      $this->output = $output;
      $this->outputType = self::XML_OutputType;
   }
   
   //--
   
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
}