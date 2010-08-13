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
   /*
    * abstract public string testedModuleName()
    * 
    * Return module name which is tested here
    */
   
   abstract public function testedModuleName();
   
   /*
    * abstract public void test()
    * 
    * Make tests (asserts) here, all of them preceded with $this->nextTest() call
    */
   
   abstract public function test();
   
   /*
    * public void nextTest()
    * 
    * Incrments tests counter
    * 
    * Call this before every test!
    */
   
   public function nextTest()
   {
      UnitTester::nextTest();
   }
   
   // TODO: make it global and more universal (in name)
   
   public function keyValueToMethodArgs($key, $value)
   {
      if(is_int($key))
      {
         $method = $value;
         $args   = false;
      }
      else
      {
         $method = $key;
         $args   = $value;
      }
      
      return array($method, $args);
   }
}