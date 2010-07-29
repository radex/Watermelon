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

abstract class TestCase
{
   abstract public function testedModuleName(); // return module name which is tested
   abstract public function test();             // make tests (asserts) here, all of them preceded with $this->nextTest() calls
   
   /*
    * public void nextTest()
    *
    * Incrments tests counter
    * 
    * Call this before every test (!)
    */
   
   public function nextTest()
   {
      UnitTester::nextTest();
   }
   
   public function keyValueToFunctionArgs($key, $value)
   {
      if(is_int($key))
      {
         $function = $value;
         $args = false;
      }
      else
      {
         $function = $key;
         $args = $value;
      }
      
      return array($function, $args);
   }
}