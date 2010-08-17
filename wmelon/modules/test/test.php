<?php

class test_Controller
{
   function index_action()
   {
      echo str_repeat('index!', 5);
   }
   
   function test_action()
   {
      echo str_repeat('test!', 5);
   }
   
   function _actionHandlfer()
   {
      echo str_repeat('actionhnd!', 5);
   }
}