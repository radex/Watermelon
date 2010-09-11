<?php

class Test_BlockSet extends BlockSet
{
   function foo()
   {
      echo '<span style="color:red">Foo!</span><br>';
   }
   
   function bar($a, $b)
   {
      echo '<span style="color:green">Bar! ' . $a . $b . '</span><br>';
   }
}