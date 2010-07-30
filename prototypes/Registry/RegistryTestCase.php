<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2010 Radosław Pietruszewski.
 //  
 //  Watermelon CMS is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon CMS is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon CMS. If not, see <http://www.gnu.org/licenses/>.
 //

class RegistryTestCase extends TestCase
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
         // testing methods that throw an exception if given name is not string
         
         $throwIfNameNotStringMethods = array
            (
               'add',
               'get',
               'set' => array('null'),
               'isReadOnly',
               'isPrivate',
               'isPersistent',
               'exists',
               'delete',
               'invalidate',
            );
         
         foreach($throwIfNameNotStringMethods as $key => $value)
         {
            list($method, $args) = $this->keyValueToMethodArgs($key, $value);
            
            $this->nextTest();
            $catched = false;

            try
            {
               if($args)
               {
                  eval('$r->' . $method . '(0,' . implode(',', $args) . ');');
               }
               else
               {
                  $r->{$method}(0);
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
         // testing methods that throw an exception if item with given name doesn't exist
         
         $throwIfDoesNotExistMethods = array
            (
               'get',
               'set' => array('null'),
               'isReadOnly',
               'isPrivate',
               'isPersistent',
               'delete',
               'invalidate',
            );
         
         foreach($throwIfDoesNotExistMethods as $key => $value)
         {
            list($method, $args) = $this->keyValueToMethodArgs($key, $value);

            $this->nextTest();
            $catched = false;

            try
            {
               if($args)
               {
                  eval('$r->' . $method . '("__0",' . implode(',', $args) . ');');
               }
               else
               {
                  $r->{$method}('__0');
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
      ##### throwIfReadOnly
      #####
      
      {
         // testing methods that throw an exception if item with given is is read-only
         
         $r->add('__0.1', null, false, true);
         
         $throwIfReadOnlyMethods = array
            (
               'set' => array('null'),
               'delete',
               'invalidate',
            );
         
         foreach($throwIfReadOnlyMethods as $key => $value)
         {
            list($method, $args) = $this->keyValueToMethodArgs($key, $value);

            $this->nextTest();
            $catched = false;

            try
            {
               if($args)
               {
                  eval('$r->' . $method . '("__0.1",' . implode(',', $args) . ');');
               }
               else
               {
                  $r->{$method}('__0.1');
               }
            }
            catch(WMException $e)
            {
               if($e->stringCode() == 'Registry:readOnly')
                  $catched = true;
            }
            
            assert($catched);
         }
      }
      
      #####
      ##### throwIfWrongPrivateItemClass
      #####
      
      {
         // testing methods that throw an exception if attempting to access private item from other class than specified in isReadOnly property
         
         $r->add('__0.2', null, false, '__NonExistingClass');
         
         $throwIfWrongTransienceClassMethods = array
            (
               'get',
               'set' => array('null'),
               'delete',
               'invalidate',
            );
         
         foreach($throwIfWrongTransienceClassMethods as $key => $value)
         {
            list($method, $args) = $this->keyValueToMethodArgs($key, $value);

            $this->nextTest();
            $catched = false;

            try
            {
               if($args)
               {
                  eval('$r->' . $method . '("__0.2",' . implode(',', $args) . ');');
               }
               else
               {
                  $r->{$method}('__0.2');
               }
            }
            catch(WMException $e)
            {
               if($e->stringCode() == 'Registry:wrongPrivateItemClass')
                  $catched = true;
            }
            
            assert($catched);
         }
      }
      
      #####
      ##### add(), checking existance of added item, attempting to recreate invalidated item
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
         $catched = false;

         try
         {
            $r->add('__1.2.5',null,'true');
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:isPersistentWrongType')
               $catched = true;
         }

         assert($catched);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//
         
         $this->nextTest();
         $catched = false;

         try
         {
            $r->add('__1.3',null,false,0);
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:isReadOnlyWrongType')
               $catched = true;
         }

         assert($catched);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__1.4');

         assert($r->exists('__1.4') === true);
      }
      
      #####
      ##### get(), getting added items
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
      ##### isReadOnly(), checking mutability of added objects
      #####
      
      {
         $this->nextTest();

         $r->add('__4.2');

         assert($r->isReadOnly('__4.2') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__4.3', null, false, false);

         assert($r->isReadOnly('__4.3') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__4.4', null, false, true);

         assert($r->isReadOnly('__4.4') === true);
      }
      
      #####
      ##### delete(), recreating, checking existance of deleted item
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
      ##### invalidate(), checking existance of invalidated item
      #####
      
      {
         $this->nextTest();

         $r->add('__6.2');
         $r->invalidate('__6.2');

         assert($r->exists('__6.2') === false);
      }
      
      #####
      ##### exists(), checking existance of not existing item
      #####
      
      {
         $this->nextTest();
         
         assert($r->exists('__0') === false);
      }
      
      #####
      ##### isPrivate(), private items
      #####
      
      {
         $this->nextTest();

         $r->add('__7.1');

         assert($r->isPrivate('__7.1') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.2', null, false, true);

         assert($r->isPrivate('__7.2') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.3', null, false, false);

         assert($r->isPrivate('__7.3') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.4', null, false, 'foo');

         assert($r->isPrivate('__7.4') === true);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.5', null, false, 'foo');

         assert($r->isReadOnly('__7.5') === false);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.6', null, false, 'registrytestcase');
         $r->set('__7.6', 'foo');

         assert($r->get('__7.6') === 'foo');
      }
      
      #####
      ##### isPersistent(), persistence
      #####
      
      {
         $this->nextTest();

         $r->add('__8.1', null);

         assert($r->isPersistent('__8.1') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__8.2', null, false);

         assert($r->isPersistent('__8.2') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__8.3', null, true);

         assert($r->isPersistent('__8.3') === true);

         
      }
   }
}