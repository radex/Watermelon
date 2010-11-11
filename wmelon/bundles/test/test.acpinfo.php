<?php

class Test_ACPInfo implements ACPInfo
{
   public function info()
   {
      $items[] = array('Testy', null, null, 'test', array());
      
      return $items;
   }
}