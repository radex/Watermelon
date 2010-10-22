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
   /*
    * public static int $testedUnitsCounter;
    * 
    * Number of ran test units
    */
   
   public static $testedUnitsCounter = 0;
   
   /*
    * public static bool $areTestsRunning
    * 
    * Whether unit testing is currently performed
    * 
    * Useful for changing behavior in libraries
    */
   
   public static $areTestsRunning = false;
   
   //--
   
   private static $failedTestsList  = array(); // array of failed tests data (module name, test ID, file name, and line)
   private static $testedModuleName = '';      // name of currently tested module
   
   //--
   
   /*
    * public static void runTest(TestCase $testCase)
    * 
    * Runs test case (TestCase child class instance)
    * 
    * Errors in assertions won't be shown immediately, and won't issue warnings
    * 
    * Use UnitTester::printFails() to print which tests failed
    *
    * Use $this->nextTest() in test case before doing every test (before every assert()), and specify tested module name - it will help with debugging and statistics
    * 
    * You won't need to call it if you place your test case in proper directory.
    */
   
   public static function runTest(TestCase $testCase)
   {
      self::$areTestsRunning = true;
      
      // setting assert options
      
      assert_options(ASSERT_WARNING,    false);
      assert_options(ASSERT_QUIET_EVAL, true);
      assert_options(ASSERT_CALLBACK,   array(__CLASS__, 'assertHandler'));
      
      // saving tested module name and running the test
      
      self::$testedModuleName = $testCase->testedModuleName();
      $testCase->test();
      
      // restoring default assert options and UnitTester properties
      
      assert_options(ASSERT_WARNING,    true);
      assert_options(ASSERT_QUIET_EVAL, false);
      assert_options(ASSERT_CALLBACK,   null);
      
      self::$testedModuleName = '';
      self::$areTestsRunning  = false;
   }
   
   /*
    * public static void printFails()
    *
    * Prints which test units failed
    *
    * Draws ugly HTML table with statistics and: module name, test ID, file name
    * and line for every failed test. Does not draw anything if no tests failed
    *
    * You don't need to call it for yourself.
    */
   
   public static function printFails()
   {
      $failedTests = count(self::$failedTestsList);
      
      if($failedTests == 0)
      {
         return;
      }
      
      echo '<hr>' . $failedTests . ' z ' . self::$testedUnitsCounter . ' testów nie powiodło się:<br><br>';
      
      echo '<table border="1">';
      echo '<tr><th>Moduł</th><th>ID testu</th><th>Plik</th><th>Linia</th></tr>';
      
      foreach(self::$failedTestsList as $failedTest)
      {
         echo '<tr>';
         
         foreach($failedTest as $value)
         {
            echo '<td>' . $value . '</td>';
         }
         
         echo '</tr>';
      }
      
      echo '</table><hr>';
   }
   
   /*
    * public static void nextTest()
    * 
    * Don't call it explicitly - use $this->nextTest() in test case instead
    */
   
   public static function nextTest()
   {
      self::$testedUnitsCounter++;
   }
   
   /*
    * assert() handler. Don't call it.
    */
   
   public static function assertHandler($file, $line, $expression)
   {
      self::$failedTestsList[] = array
         (
            self::$testedModuleName,
            self::$testedUnitsCounter,
            $file,
            $line
         );
   }
}