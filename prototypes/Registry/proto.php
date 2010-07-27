<?php

include 'Exception.php';
include 'UnitTester.php';
include 'Registry.php';

class RegistryPrototypeTest extends TestCase
{
   public function testedModuleName()
   {
      return 'RegistryPrototype';
   }
   
   public function test()
   {
      $r = new Registry;

      #####
      ##### add()
      #####

      assert($r->exists('__0') === false);
      
      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();

      $r->add('__1.1');

      assert($r->exists('__1.1') === true);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();

      assert($r->get('__1.1') === null);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();
      $catched = false;
      
      try
      {
         $r->add('__1.1');
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Registry:alreadyRegistered')
            $catched = true;
      }

      assert($catched);
      
      #####
      ##### Name type checking
      #####

      $this->nextTest();
      $catched = false;

      try
      {
         $r->add(true);
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Registry:nameNotString')
            $catched = true;
      }

      assert($catched);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();
      $catched = false;

      try
      {
         $r->add(12345);
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Registry:nameNotString')
            $catched = true;
      }

      assert($catched);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();
      $catched = false;

      try
      {
         $r->add(array());
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Registry:nameNotString')
            $catched = true;
      }

      assert($catched);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();
      $catched = false;

      try
      {
         $r->add(new stdClass);
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Registry:nameNotString')
            $catched = true;
      }

      assert($catched);
      
      #####
      ##### get(), getting added entities
      #####

      $this->nextTest();
      $catched = false;

      try
      {
         $r->get(0);
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Registry:nameNotString')
            $catched = true;
      }

      assert($catched);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();
      $catched = false;

      try
      {
         $r->get('__2.1');
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Registry:doesNotExist')
            $catched = true;
      }

      assert($catched);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();

      $r->add('__2.1', true);

      assert($r->get('__2.1') === true);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();

      $r->add('__2.2', 'foo');

      assert($r->get('__2.2') === 'foo');

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();

      $r->add('__2.3', array(true, 'foo'));

      assert($r->get('__2.3') === array(true, 'foo'));

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();

      $g2t4 = new stdClass;
      $g2t4->foo = 'bar';
      
      $r->add('__2.4', $g2t4);

      assert($r->get('__2.4') === $g2t4);
      
      #####
      ##### set(), setting and getting
      #####

      $this->nextTest();
      $catched = false;

      try
      {
         $r->set(0, null);
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Registry:nameNotString')
            $catched = true;
      }

      assert($catched);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();
      $catched = false;

      try
      {
         $r->set('__3.1', null);
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Registry:doesNotExist')
            $catched = true;
      }

      assert($catched);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();
      $catched = false;

      try
      {
         $r->add('__3.2', null, true);
         $r->set('__3.2', null);
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Registry:immutable')
            $catched = true;
      }

      assert($catched);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();

      $r->add('__3.3');
      $r->set('__3.3', 'true');

      assert($r->get('__3.3') === 'true');

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();

      $r->add('__3.4');
      $r->set('__3.4', false);

      assert($r->get('__3.4') === false);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();

      $r->add('__3.5', '1');
      $r->set('__3.5', '2');
      $r->set('__3.5', '3');

      assert($r->get('__3.5') === '3');

      #####
      ##### isImmutable(), checking mutability of added objects
      #####

      $this->nextTest();
      $catched = false;

      try
      {
         $r->isImmutable(0);
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Registry:nameNotString')
            $catched = true;
      }

      assert($catched);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();
      $catched = false;

      try
      {
         $r->isImmutable('__4.1');
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'Registry:doesNotExist')
            $catched = true;
      }

      assert($catched);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();

      $r->add('__4.2');

      assert($r->isImmutable('__4.2') === false);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();

      $r->add('__4.3', null, 'true');

      assert($r->isImmutable('__4.3') === false);

      //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

      $this->nextTest();

      $r->add('__4.4', null, true);

      assert($r->isImmutable('__4.4') === true);
   }
}

UnitTester::runTest(new RegistryPrototypeTest);

UnitTester::printFails();