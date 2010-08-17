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

class Registry_TestCase extends TestCase
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
         // also, testing if names are case-insensitive
         
         $throwIfNameNotStringMethods = array
            (
               'create',
               'get',
               'set' => array('null'),
               'isReadOnly',
               'isPrivate',
               'isPersistent',
               'exists',
               'delete',
               'invalidate',
            );
         
         $i = 0;
         
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
            
            // case insensitivity
            
            $this->nextTest();
            $catched = false;
            
            $r->create('__0.TESTINGcaseINSENSITIVITY.' . $i);
            
            try
            {
               if($args)
               {
                  eval('$r->' . $method . '("__0.testingCaseInsensitivty.' . $i . '",' . implode(',', $args) . ');');
               }
               else
               {
                  $r->{$method}('__0.testingCaseInsensitivty.' . $i);
               }
            }
            catch(WMException $e)
            {
               if($e->stringCode() == 'Registry:nameNotString')
                  $catched = true;
            }

            assert(!$catched);
            
            //--
            
            
            $i++;
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
         
         $r->create('__0.1', null, false, true);
         
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
         
         $r->create('__0.2', null, false, '__NonExistingClass');
         
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
      ##### create(), checking existance of created item
      #####
      
      {
         $this->nextTest();
         $catched = false;
      
         try
         {
            $r->create('__1.1');
            $r->create('__1.1');
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
            $r->create('__1.2',null,'true');
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
            $r->create('__1.3',null,false,0);
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:isReadOnlyWrongType')
               $catched = true;
         }

         assert($catched);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__1.4');

         assert($r->exists('__1.4') === true);
      }
      
      #####
      ##### get(), getting added items
      #####
      
      {
         $this->nextTest();

         $r->create('__2.1');

         assert($r->get('__2.1') === null);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__2.2', 'foo');

         assert($r->get('__2.2') === 'foo');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__2.3', array(true, 'foo'));

         assert($r->get('__2.3') === array(true, 'foo'));
      }
      
      #####
      ##### set(), setting and getting
      #####
      
      {
         $this->nextTest();

         $r->create('__3.3');
         $r->set('__3.3', 'true');

         assert($r->get('__3.3') === 'true');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__3.4');
         $r->set('__3.4', false);

         assert($r->get('__3.4') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__3.5', '1');
         $r->set('__3.5', '2');
         $r->set('__3.5', '3');

         assert($r->get('__3.5') === '3');
      }
      
      #####
      ##### isReadOnly(), checking mutability of added objects
      #####
      
      {
         $this->nextTest();

         $r->create('__4.2');

         assert($r->isReadOnly('__4.2') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__4.3', null, false, false);

         assert($r->isReadOnly('__4.3') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__4.4', null, false, true);

         assert($r->isReadOnly('__4.4') === true);
      }
      
      #####
      ##### delete(), recreating, checking existance of deleted item
      #####
      
      {
         $this->nextTest();

         $r->create('__5.2');
         $r->delete('__5.2');

         assert($r->exists('__5.2') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__5.3');
         $r->delete('__5.3');
         $r->create('__5.3');

         assert($r->exists('__5.3') === true);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__5.4', 'foo');
         $r->delete('__5.4');
         $r->create('__5.4', 'bar');

         assert($r->get('__5.4') === 'bar');
      }
      
      #####
      ##### invalidate(), checking existance of invalidated item, attempting to recreate invalidated item
      #####
      
      {
         $this->nextTest();

         $r->create('__6.1');
         $r->invalidate('__6.1');

         assert($r->exists('__6.1') === false);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();
         $catched = false;

         try
         {
            $r->create('__6.2');
            $r->invalidate('__6.2');
            $r->create('__6.2');
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:alreadyRegistered')
               $catched = true;
         }

         assert($catched);
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

         $r->create('__7.1');

         assert($r->isPrivate('__7.1') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__7.2', null, false, true);

         assert($r->isPrivate('__7.2') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__7.3', null, false, false);

         assert($r->isPrivate('__7.3') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__7.4', null, false, 'foo');

         assert($r->isPrivate('__7.4') === true);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__7.5', null, false, 'foo');

         assert($r->isReadOnly('__7.5') === false);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__7.6', null, false, 'registry_testcase');
         $r->set('__7.6', 'foo');

         assert($r->get('__7.6') === 'foo');
      }
      
      #####
      ##### isPersistent(), persistence
      #####
      
      {
         $this->nextTest();

         $r->create('__8.1', null);

         assert($r->isPersistent('__8.1') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__8.2', null, false);

         assert($r->isPersistent('__8.2') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__8.3', null, true);

         assert($r->isPersistent('__8.3') === true);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-// following tests don't really test adding to DB, so they aren't very useful

         $this->nextTest();

         $r->create('__8.4', 'foo', true);
         
         assert($r->get('__8.4') === 'foo');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__8.4.5', 'foo', true);
         $r->set('__8.4.5', 'bar');

         assert($r->get('__8.4.5') === 'bar');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__8.5', null, true);
         $r->delete('__8.5');

         assert($r->exists('__8.5') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__8.6', null, true);
         $r->invalidate('__8.6');

         assert($r->exists('__8.6') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();
         $catched = false;

         try
         {
            $r->create('__8.7', null, true);
            $r->invalidate('__8.7');
            $r->create('__8.7');
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:alreadyRegistered')
               $catched = true;
         }

         assert($catched);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-// cleaning up
         
         $maxItem = 16;
         $nonStandardItems = array('4.5');
         
         for($i = 1; $i <= $maxItem; $i++)
         {
            DB::query("DELETE FROM `__registry` WHERE `registry_name` = '%1'", '__8.' . $i);
         }
         
         foreach($nonStandardItems as $item)
         {
            DB::query("DELETE FROM `__registry` WHERE `registry_name` = '%1'", '__8.' . $item);
         }
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-// these are much more precise

         $this->nextTest();

         $r->create('__8.8', array('foo', 'bar'), true);

         $g8t8 = DB::query("SELECT * FROM `__registry` WHERE `registry_name` = '%1'", '__8.8')->object->registry_value;

         assert(unserialize($g8t8) === array('foo', 'bar'));

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__8.9', 'test', true);

         $g8t8 = DB::query("SELECT * FROM `__registry` WHERE `registry_name` = '%1'", '__8.9')->object->registry_value;

         assert(unserialize($g8t8) === 'test');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         DB::query("INSERT INTO `__registry` SET `registry_name` = '%1', `registry_value` = '%2'", '__8.10', serialize('foo'));
         
         $r->create('__8.10', 'bar', true);
         
         assert($r->get('__8.10') === 'foo');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         DB::query("INSERT INTO `__registry` SET `registry_name` = '%1', `registry_value` = '%2'", '__8.11', serialize('foo'));
         
         $r->create('__8.11', 'bar', true);
         
         $g8t11 = DB::query("SELECT * FROM `__registry` WHERE `registry_name` = '%1'", '__8.11')->object->registry_value;

         assert(unserialize($g8t11) === 'foo');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__8.12', 'foo', true);
         $r->set('__8.12', 'bar');
         
         $g8t12 = DB::query("SELECT * FROM `__registry` WHERE `registry_name` = '%1'", '__8.12')->object->registry_value;

         assert(unserialize($g8t12) === 'bar');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();
         
         DB::query("INSERT INTO `__registry` SET `registry_name` = '%1', `registry_value` = '%2'", '__8.13', serialize('1'));
         
         $r->create('__8.13', '2', true);
         $r->set('__8.13', '3');

         assert($r->get('__8.13') === '3');

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();
         
         DB::query("INSERT INTO `__registry` SET `registry_name` = '%1', `registry_value` = '%2'", '__8.14', serialize('1'));
         
         $r->create('__8.14', '2', true);
         $r->set('__8.14', '3');
         
         $g8t14 = DB::query("SELECT * FROM `__registry` WHERE `registry_name` = '%1'", '__8.14')->object->registry_value;

         assert(unserialize($g8t14) === '3');
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__8.15', null, true);
         $r->delete('__8.15');
         
         $g8t15 = DB::query("SELECT * FROM `__registry` WHERE `registry_name` = '%1'", '__8.15')->rows();
         
         assert($g8t15 === 0);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->create('__8.16', null, true);
         $r->invalidate('__8.16');
         
         $g8t16 = DB::query("SELECT * FROM `__registry` WHERE `registry_name` = '%1'", '__8.16')->rows();

         assert($g8t16 === 0);
      }
   }
}