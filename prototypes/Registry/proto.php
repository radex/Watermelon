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
      ##### throwIfNameNotString
      #####
      
      {
         // testing functions that throw an exception if given name is not string
         
         $throwIfNameNotStringFunctions = array
            (
               'add',
               'get',
               'set' => array('null'),
               'isImmutable',
               'isTransient',
               'exists',
               'delete',
               'invalidate',
            );
         
         foreach($throwIfNameNotStringFunctions as $key => $value)
         {
            list($function, $args) = $this->keyValueToFunctionArgs($key, $value);
            
            $this->nextTest();
            $catched = false;

            try
            {
               if($args)
               {
                  eval('$r->' . $function . '(0,' . implode(',', $args) . ');');
               }
               else
               {
                  $r->{$function}(0);
               }
            }
            catch(WMException $e)
            {
               if($e->stringCode() == 'Registry:nameNotString')
                  $catched = true;
            }

            assert($catched);
         }
      }
      
      #####
      ##### throwIfDoesNotExist
      #####
      
      {
         // testing functions that throw an exception if entity with given name doesn't exist
         
         $throwIfDoesNotExistFunctions = array
            (
               'get',
               'set' => array('null'),
               'isImmutable',
               'isTransient',
               'delete',
               'invalidate',
            );
         
         foreach($throwIfDoesNotExistFunctions as $key => $value)
         {
            list($function, $args) = $this->keyValueToFunctionArgs($key, $value);

            $this->nextTest();
            $catched = false;

            try
            {
               if($args)
               {
                  eval('$r->' . $function . '("__0",' . implode(',', $args) . ');');
               }
               else
               {
                  $r->{$function}('__0');
               }
            }
            catch(WMException $e)
            {
               if($e->stringCode() == 'Registry:doesNotExist')
                  $catched = true;
            }

            assert($catched);
         }
      }
      
      #####
      ##### throwIfImmutable
      #####
      
      {
         // testing functions that throw an exception if entity with given is immutable
         
         $r->add('__0.1', null, true);
         
         $throwIfImmutableFunctions = array
            (
               'set' => array('null'),
               'delete',
               'invalidate',
            );
         
         foreach($throwIfImmutableFunctions as $key => $value)
         {
            list($function, $args) = $this->keyValueToFunctionArgs($key, $value);

            $this->nextTest();
            $catched = false;

            try
            {
               if($args)
               {
                  eval('$r->' . $function . '("__0.1",' . implode(',', $args) . ');');
               }
               else
               {
                  $r->{$function}('__0.1');
               }
            }
            catch(WMException $e)
            {
               if($e->stringCode() == 'Registry:immutable')
                  $catched = true;
            }
            
            assert($catched);
         }
      }
      
      #####
      ##### add(), checking existance of added entity, attempting to recreate invalidated entity
      #####
      
      {
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

         $r->add('__4.2');

         assert($r->isImmutable('__4.2') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__4.3', null, false);

         assert($r->isImmutable('__4.3') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__4.4', null, true);

         assert($r->isImmutable('__4.4') === true);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();
         $catched = false;

         try
         {
            $r->add('__4.5', null, 'true');
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:propertyNotBool')
               $catched = true;
         }

         assert($catched);
      }
      
      #####
      ##### delete(), recreating, checking existance of deleted entity
      #####
      
      {
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

         $r->add('__6.2');
         $r->invalidate('__6.2');

         assert($r->exists('__6.2') === false);
      }
      
      #####
      ##### exists(), checking existance of not existing entity
      #####
      
      {
         $this->nextTest();
         
         assert($r->exists('__0') === false);
      }
      
      #####
      ##### transience
      #####
      
      {
         
      }
   }
}

UnitTester::runTest(new RegistryPrototypeTest);

UnitTester::printFails();