<?php

include 'Registry.php';
include 'RegistryTestCase.php';

UnitTester::runTest(new RegistryTestCase);

UnitTester::printFails();