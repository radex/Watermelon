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

include 'TestCase.php';

class UnitTester
{
   private static $testedModuleName = '';
   private static $unitTestsCounter = 0;
   
   public static function runTest(TestCase $testCaseObject)
   {
      // setting assert options
      
      assert_options(ASSERT_WARNING,    false);
      assert_options(ASSERT_QUIET_EVAL, true);
      assert_options(ASSERT_CALLBACK,   array(__CLASS__, 'assertHandler'));
      
      // saving tested module name and running the test
      
      self::$testedModuleName = $testCaseObject->testedModuleName();
      $testCaseObject->test();
      
      // restoring default assert options and UnitTester properties
      
      assert_options(ASSERT_WARNING,    true);
      assert_options(ASSERT_QUIET_EVAL, false);
      assert_options(ASSERT_CALLBACK,   null);
      
      self::$testedModuleName = '';
   }
   
   public static function nextTest()
   {
      self::$unitTestsCounter++;
   }
   
   public static function assertHandler($file, $line, $expression)
   {
      var_dump('fail');
   }
}