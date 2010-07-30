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
         // testing functions that throw an exception if given name is not string
         
         $throwIfNameNotStringFunctions = array
            (
               'add',
               'get',
               'set' => array('null'),
               'isReadOnly',
               'isPrivate',
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
         // testing functions that throw an exception if item with given name doesn't exist
         
         $throwIfDoesNotExistFunctions = array
            (
               'get',
               'set' => array('null'),
               'isReadOnly',
               'isPrivate',
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
      ##### throwIfReadOnly
      #####
      
      {
         // testing functions that throw an exception if item with given is is read-only
         
         $r->add('__0.10', null, true);
         
         $throwIfReadOnlyFunctions = array
            (
               'set' => array('null'),
               'delete',
               'invalidate',
            );
         
         foreach($throwIfReadOnlyFunctions as $key => $value)
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
         // testing functions that throw an exception if attempting to access private item from other class than specified in isReadOnly property
         
         $r->add('__0.20', null, '__NonExistingClass');
         
         $throwIfWrongTransienceClassFunctions = array
            (
               'get',
               'set' => array('null'),
               'delete',
               'invalidate',
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
/*
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
         */
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__1.40');

         assert($r->exists('__1.40') === true);
      }
      
      #####
      ##### get(), getting added items
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
      ##### isReadOnly(), checking mutability of added objects
      #####
      
      {
         $this->nextTest();

         $r->add('__4.20');

         assert($r->isReadOnly('__4.20') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__4.30', null, false);

         assert($r->isReadOnly('__4.30') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__4.40', null, true);

         assert($r->isReadOnly('__4.40') === true);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();
         $catched = false;

         try
         {
            $r->add('__4.50', null, 0);
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'Registry:readOnlyWrongType')
               $catched = true;
         }

         assert($catched);
      }
      
      #####
      ##### delete(), recreating, checking existance of deleted item
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
      ##### invalidate(), checking existance of invalidated item
      #####
      
      {
         $this->nextTest();

         $r->add('__6.20');
         $r->invalidate('__6.20');

         assert($r->exists('__6.20') === false);
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

         $r->add('__7.10');

         assert($r->isPrivate('__7.10') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.20', null, true);

         assert($r->isPrivate('__7.20') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.30', null, false);

         assert($r->isPrivate('__7.30') === false);

         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.40', null, 'foo');

         assert($r->isPrivate('__7.40') === true);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.50', null, 'foo');

         assert($r->isReadOnly('__7.50') === false);
         
         //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

         $this->nextTest();

         $r->add('__7.60', null, 'registrytestcase');
         $r->set('__7.60', 'foo');

         assert($r->get('__7.60') === 'foo');
      }
   }
}