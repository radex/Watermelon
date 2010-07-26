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
      
      assert(8==7);
   }
}

UnitTester::runTest(new RegistryPrototypeTest);