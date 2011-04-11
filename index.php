<?php

// running Watermelon if installed, installer otherwise

if(file_exists(realpath(dirname(__FILE__)) . '/wmelon/core/'))
{
   include 'wmelon/core/Watermelon.php';
   
   Watermelon::run();
}
else
{
   include 'wmelon/installer/index.php';
}