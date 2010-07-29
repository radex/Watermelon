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
         
         $r->add('__0.10', null, true);
         
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
                  eval('$r->' . $function . '("__0.10",' . implode(',', $args) . ');');
               }
               else
               {
                  $r->{$function}('__0.10');
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
      ##### throwIfWrongTransienceClass
      #####
      
      {
         // testing functions that throw an exception if attempting to access entity from other class than specified in isTransient property
         
         $r->add('__0.20', null, false, '__NotExistingClass');
         
         $throwIfWrongTransienceClassFunctions = array
            (
               'get',
            );
         
         foreach($throwIfWrongTransienceClassFunctions as $key => $value)
         {
            list($function, $args) = $this->keyValueToFunctionArgs($key, $value);

            $this->nextTest();
            $catched = false;

            try
            {
               if($args)
               {
                  eval('$r->' . $function . '("__0.20",' . implode(',', $args) . ');');
               }
               else
               {
                  $r->{$function}('__0.20');
               }
            }
            catch(WMException $e)
            {
               if($e->stringCode() == 'Registry:wrongTransienceClass')
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
            $r->add('__1.10');
            $r->add('__1.10');
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
            $r->add('__1.20');
            $r->invalidate('__1.20');
            $r->add('__1.20');
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
            $r->add('__1.30',null,false,0);
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:transienceWrongType')
               $catched = true;
         }

         assert($catched);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__1.40');

         assert($r->exists('__1.40') === true);
      }
      
      #####
      ##### get(), getting added entities
      #####
      
      {
         $this->nextTest();

         $r->add('__2.10');

         assert($r->get('__2.10') === null);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__2.20', 'foo');

         assert($r->get('__2.20') === 'foo');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__2.30', array(true, 'foo'));

         assert($r->get('__2.30') === array(true, 'foo'));
      }
      
      #####
      ##### set(), setting and getting
      #####
      
      {
         $this->nextTest();

         $r->add('__3.30');
         $r->set('__3.30', 'true');

         assert($r->get('__3.30') === 'true');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__3.40');
         $r->set('__3.40', false);

         assert($r->get('__3.40') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__3.50', '1');
         $r->set('__3.50', '2');
         $r->set('__3.50', '3');

         assert($r->get('__3.50') === '3');
      }
      
      #####
      ##### isImmutable(), checking mutability of added objects
      #####
      
      {
         $this->nextTest();

         $r->add('__4.20');

         assert($r->isImmutable('__4.20') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__4.30', null, false);

         assert($r->isImmutable('__4.30') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__4.40', null, true);

         assert($r->isImmutable('__4.40') === true);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();
         $catched = false;

         try
         {
            $r->add('__4.50', null, 'true');
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

         $r->add('__5.20');
         $r->delete('__5.20');

         assert($r->exists('__5.20') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__5.30');
         $r->delete('__5.30');
         $r->add('__5.30');

         assert($r->exists('__5.30') === true);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__5.40', 'foo');
         $r->delete('__5.40');
         $r->add('__5.40', 'bar');

         assert($r->get('__5.40') === 'bar');
      }
      
      #####
      ##### invalidate(), checking existance of invalidated entity
      #####
      
      {
         $this->nextTest();

         $r->add('__6.20');
         $r->invalidate('__6.20');

         assert($r->exists('__6.20') === false);
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
         $this->nextTest();

         $r->add('__7.10');

         assert($r->isTransient('__7.10') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.20', null, false, true);

         assert($r->isTransient('__7.20') === true);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.30', null, false, false);

         assert($r->isTransient('__7.30') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.40', null, false, true);

         assert($r->isImmutable('__7.40') === true);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();
         $catched = false;

         try
         {
            $r->add('__7.50', null, false, true);
            $r->set('__7.50', null);
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:immutable')
               $catched = true;
         }

         assert($catched);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.60', 'foo', false, true);

         assert($r->get('__7.60') === 'foo');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();
         $catched = false;

         try
         {
            $r->add('__7.70', null, false, true);
            $r->get('__7.70');
            $r->get('__7.70');
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:doesNotExist')
               $catched = true;
         }

         assert($catched);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.80', 'foo', false, 'RegistryPrototypeTest'); //TODO: remember to change this

         assert($r->get('__7.80') === 'foo');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();
         $catched = false;

         try
         {
            $r->add('__7.90', null, false, 'RegistryPrototypeTest'); //TODO: remember to change this
            $r->get('__7.90');
            $r->get('__7.90');
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:doesNotExist')
               $catched = true;
         }

         assert($catched);

         
      }
   }
}

UnitTester::runTest(new RegistryPrototypeTest);

UnitTester::printFails();