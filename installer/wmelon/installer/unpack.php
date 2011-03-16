<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2011 Radosław Pietruszewski.
 //  
 //  Watermelon is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon. If not, see <http://www.gnu.org/licenses/>.
 //  

// combining parts

$resultFile = fopen('wmelon.tar', 'ab');

foreach(new DirectoryIterator(dirname(__FILE__)) as $file)
{
   if($file->isFile() && substr($file->getFilename(), -4) != '.php')
   {
      $part = file_get_contents($file->getFilename());
      
      fwrite($resultFile, $part);
      
      unset($part);
   }
}

// untaring

include 'Tar.php';

$file = new Archive_Tar('wmelon.tar');

$file->extract('wm/');

// moving wmelon/ contents

foreach(new DirectoryIterator('wm/wmelon/') as $file)
{
   if(!$file->isDot())
   {
      rename($file->getPathname(), '../' . $file->getFilename());
   }
}

// moving index.php and dot.htaccess

copy('wm/dot.htaccess', '../../dot.htaccess');
copy('wm/index.php', '../../index.php');

// ok

echo 'ok';