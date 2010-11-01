<?php

class Test_ACPInfo implements ACPInfo
{
   public function info()
   {
      $fooSubItems[] = array('1', null, 'foo/index/1/2/3');
      $fooSubItems[] = array('2', 'Foo hoo hoo', 'foo/index');
      
      $items[] = array('Foo', null, 'Foo!', 'foo', $fooSubItems);
      $items[] = array('Bar', null, null, 'bar', array());
      
      return $items;
   }
}