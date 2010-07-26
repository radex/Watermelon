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
      $this->nextTest();
      
      assert(true);
      
      $this->nextTest();
      
      assert(false);
      
      $this->nextTest();
      
      assert(true);
   }
}

UnitTester::runTest(new RegistryPrototypeTest);

UnitTester::printFails();