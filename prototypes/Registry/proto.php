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
      ##### add(), checking existance of added entity, attempting to recreate invalidated entity
      #####
      
      {
         $this->nextTest();
         $catched = false;

         try
         {
            $r->add(0);
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
            $r->add('__1.1');
            $r->add('__1.1');
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:alreadyRegistered')
               $catched = true;
         }

         assert($catched);
      
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();
         $catched = false;

         try
         {
            $r->add('__1.2');
            $r->invalidate('__1.2');
            $r->add('__1.2');
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:alreadyRegistered')
               $catched = true;
         }

         assert($catched);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__1.3');

         assert($r->exists('__1.3') === true);
      }
      
      #####
      ##### get(), getting added entities
      #####
      
      {
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

         $r->add('__2.1');

         assert($r->get('__2.1') === null);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__2.2', 'foo');

         assert($r->get('__2.2') === 'foo');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__2.3', array(true, 'foo'));

         assert($r->get('__2.3') === array(true, 'foo'));
      }
      
      #####
      ##### set(), setting and getting
      #####
      
      {
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
      }
      
      #####
      ##### isImmutable(), checking mutability of added objects
      #####
      
      {
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
      
      #####
      ##### delete(), recreating, checking existance of deleted entity
      #####
      
      {
         $this->nextTest();
         $catched = false;

         try
         {
            $r->delete(0);
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
            $r->delete('__5.1');
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
            $r->add('__5.1', null, true);
            $r->delete('__5.1');
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:immutable')
               $catched = true;
         }

         assert($catched);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__5.2');
         $r->delete('__5.2');

         assert($r->exists('__5.2') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__5.3');
         $r->delete('__5.3');
         $r->add('__5.3');

         assert($r->exists('__5.3') === true);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__5.4', 'foo');
         $r->delete('__5.4');
         $r->add('__5.4', 'bar');

         assert($r->get('__5.4') === 'bar');
      }
      
      #####
      ##### invalidate(), checking existance of invalidated entity
      #####
      
      {
         $this->nextTest();
         $catched = false;

         try
         {
            $r->invalidate(0);
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
            $r->invalidate('__6.1');
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
            $r->add('__6.1', null, true);
            $r->invalidate('__6.1');
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:immutable')
               $catched = true;
         }

         assert($catched);
      
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__6.2');
         $r->invalidate('__6.2');

         assert($r->exists('__6.2') === false);
      }
      
      #####
      ##### exists(), checking existance of not existing entity
      #####
      
      {
         $this->nextTest();
         $catched = false;

         try
         {
            $r->exists(0);
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:nameNotString')
               $catched = true;
         }

         assert($catched);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//
         
         $this->nextTest();
         
         assert($r->exists('__0') === false);
      }
   }
}

UnitTester::runTest(new RegistryPrototypeTest);

UnitTester::printFails();