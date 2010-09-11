<?php

class Test2_BlockSet extends BlockSet
{
   function foo2()
   {
      echo '<span style="color:red">Foo2!</span><br>';
   }
   
   function bar2($a, $b)
   {
      echo '<span style="color:green">Bar2! ' . $a . $b . '</span><br>';
   }
}